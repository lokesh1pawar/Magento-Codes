<?php
namespace Lokesh\Add\Plugins;

class AddressRendererPlugin
{
    public function aroundRenderArray(
        \Magento\Customer\Block\Address\Renderer\DefaultRenderer $subject,
        \Closure $proceed,
        array $addressData
    ) {
        if (isset($addressData['house'])) {
            
            // $addressData['street'] .= '<br/>House Number: ' . $addressData['house'];
            // unset($addressData['house']);
            $addressData['street'] .= "\nHouse Number: " . $addressData['house'];
            unset($addressData['house']);
        }

        return $proceed($addressData);
    }
}