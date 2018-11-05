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
 * @package   Dhl\Shipping\Observer
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */
namespace Dhl\LabelStatus\Observer;

use Dhl\LabelStatus\Api\Data\LabelStatusInterfaceFactory;
use Dhl\LabelStatus\Api\LabelStatusRepositoryInterface;
use Dhl\LabelStatus\Model\Label\LabelStatus;
use Dhl\LabelStatus\Model\SalesOrderGrid\OrderGridUpdater;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class DeleteTrackObserver
 *
 * @package   Dhl\LabelStatus\Observer
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */
class DeleteTrackObserver implements ObserverInterface
{
    /**
     * @var LabelStatusInterfaceFactory
     */
    private $labelStatusFactory;

    /**
     * @var LabelStatusRepositoryInterface
     */
    private $labelStatusRepository;

    /**
     * @var OrderGridUpdater
     */
    private $orderGridUpdater;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * DeleteTrackObserver constructor.
     *
     * @param LabelStatusInterfaceFactory $labelStatusFactory
     * @param LabelStatusRepositoryInterface $labelStatusRepo
     * @param OrderGridUpdater $orderGridUpdater
     * @param EventManager $eventManager
     */
    public function __construct(
        LabelStatusInterfaceFactory $labelStatusFactory,
        LabelStatusRepositoryInterface $labelStatusRepo,
        OrderGridUpdater $orderGridUpdater,
        EventManager $eventManager
    ) {
        $this->labelStatusFactory = $labelStatusFactory;
        $this->labelStatusRepository = $labelStatusRepo;
        $this->orderGridUpdater = $orderGridUpdater;
        $this->eventManager = $eventManager;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Shipment\Track $track */
        $track = $observer->getData('track');

        /** set label status here because dispatching new event inside of observer is not good */
        $orderId = $track->getShipment()->getOrderId();
        $labelStatus = $this->labelStatusRepository->getByOrderId($orderId);

        if (null === $labelStatus) {
            $this->labelStatusFactory->create()->setOrderId($orderId);
        }
        $labelStatus->setStatusCode(LabelStatus::CODE_PENDING);
        $this->labelStatusRepository->save($labelStatus);
        $this->orderGridUpdater->update($orderId);
    }
}
