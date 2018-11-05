<?php
/**
 * Dhl LabelStatus
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future.
 *
 * PHP version 7
 *
 * @package   Dhl\LabelStatus\Setup
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */

namespace Dhl\LabelStatus\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * ShippingSetup
 *
 * @package  Dhl\LabelStatus\Setup
 * @author   Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.netresearch.de/
 */
class ShippingSetup
{
    const TABLE_LABEL_STATUS = 'dhlshipping_label_status';


    /**
     * Create table dhlshipping_label_status
     *
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    public static function createLabelStatusTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
                       ->newTable($setup->getTable(self::TABLE_LABEL_STATUS));
        $table->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            null,
            ['identity'  => false, 'unsigned'  => true, 'nullable'  => false],
            'Entitiy Id'
        )->addColumn(
            'status_code',
            Table::TYPE_TEXT,
            10,
            ['default' => 0, 'unsigned' => true, 'nullable' => false],
            'Status Code'
        )->addIndex(
            $setup->getIdxName(
                $setup->getTable(self::TABLE_LABEL_STATUS),
                'order_id',
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            'order_id',
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $setup->getFkName(
                $setup->getTable(self::TABLE_LABEL_STATUS),
                'order_id',
                $setup->getTable('sales_order'),
                'entity_id'
            ),
            'order_id',
            $setup->getTable('sales_order'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->createTable($table);

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_grid'),
            'dhlshipping_label_status',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 10,
                'comment' => 'DHL Shipping Label Status'
            ]
        );
    }
}
