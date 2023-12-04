var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'Lokesh_Add/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Lokesh_Add/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Lokesh_Add/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'Lokesh_Add/js/action/set-billing-address-mixin': true
            }
        }
    }
};
