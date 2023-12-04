<?php
/**
 * Copyright © Mucan All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lokesh\Add\Block\Checkout\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

/**
 * Class NewAddressAttribute
 */
class NewAddressAttribute implements LayoutProcessorInterface
{
    CONST BILLING_TYPE = 'billingAddress';
    CONST SHIPPING_TYPE = 'shippingAddress';
    CONST ADDRESS_ATTRIBUTE_CODE = 'house';
    CONST ATTRIBUTE_LABEL = 'House Number';

    /**
     * @param $jsLayout
     * @return array
     */
    public function process($jsLayout): array
    {
        //Build shipping address field
        $jsLayout['components']['checkout']['children']['steps']
        ['children']['shipping-step']['children']['shippingAddress']
        ['children']['shipping-address-fieldset']['children'][self::ADDRESS_ATTRIBUTE_CODE] = $this->getCustomField(self::SHIPPING_TYPE);

        //Build billing address field
        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'] as $key => $payment)
        {
            $paymentCode = self::BILLING_TYPE.str_replace('-form','',$key);
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children'][self::ADDRESS_ATTRIBUTE_CODE] = $this->getCustomField($paymentCode);
        }

        return $jsLayout;
    }

    /**
     * @param string $type
     * @return array
     */
    private function getCustomField(string $type): array
    {
        return [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => $type . '.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => self::ATTRIBUTE_LABEL,
                ],
            ],
            'dataScope' => $type . '.custom_attributes' . '.' . self::ADDRESS_ATTRIBUTE_CODE,
            'label' => self::ATTRIBUTE_LABEL,
            'provider' => 'checkoutProvider',
            'sortOrder' => 0,
            'validation' => [
                'required-entry' => false
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => '' // value field is used to set a default value of the attribute
        ];
    }
}
