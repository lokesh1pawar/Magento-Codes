<?php
namespace Lokesh\Add\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveCustomAttribute implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Address $address */
        $address = $observer->getEvent()->getAddress();
        $customAttributeValue = $address->getData('house');

        // Save the custom attribute value to the database
        $address->setData('house', $customAttributeValue)->save();
    }
}