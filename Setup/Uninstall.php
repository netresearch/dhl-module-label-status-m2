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

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

/**
 * Uninstall
 *
 * @package  Dhl\LabelStatus\Setup
 * @author   Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.netresearch.de/
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * Uninstall
     * @param EavSetup $eavSetup
     */
    public function __construct(EavSetup $eavSetup)
    {
        $this->eavSetup = $eavSetup;
    }

    /**
     * Remove schema and data as created during module installation.
     *
     * @param SchemaSetupInterface $schemaSetup
     * @param ModuleContextInterface $context
     */
    public function uninstall(
        SchemaSetupInterface $schemaSetup,
        ModuleContextInterface $context
    ) {
        $this->deleteStatusTableAndColumn($schemaSetup);
    }



    /**
     * @param SchemaSetupInterface $uninstaller
     * @return void
     */
    private function deleteStatusTableAndColumn($uninstaller)
    {
        $uninstaller->getConnection()->dropTable(ShippingSetup::TABLE_LABEL_STATUS);
        $uninstaller->getConnection()->dropColumn(
            $uninstaller->getTable('sales_order_grid'),
            'dhlshipping_label_status'
        );
    }
}
