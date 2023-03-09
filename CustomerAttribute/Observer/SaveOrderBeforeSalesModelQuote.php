<?php

namespace Plumtree\CustomerAttribute\Observer;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class SaveOrderBeforeSalesModelQuote implements ObserverInterface
{
    public $objectCopyService;

    public function __construct(
        Copy $objectCopyService
    ) {
        $this->objectCopyService = $objectCopyService;
    }

    public function execute(Observer $observer)
    {
        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_quote',
            'to_order',
            $observer->getEvent()->getQuote(),
            $observer->getEvent()->getOrder()
        );

        return $this;
    }
}