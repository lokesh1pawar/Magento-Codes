<?php

namespace Plumtree\CustomerAttribute\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagement
{
    public $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function beforeSaveAddressInformation($subject, $cartId, $addressInformation)
    {
        $quote = $this->cartRepository->getActive($cartId);
        $po = $addressInformation->getShippingAddress()->getExtensionAttributes()->getPoAttribute();
        $quote->setPoAttribute($po);
        $this->cartRepository->save($quote);
        return [$cartId, $addressInformation];
    }
}