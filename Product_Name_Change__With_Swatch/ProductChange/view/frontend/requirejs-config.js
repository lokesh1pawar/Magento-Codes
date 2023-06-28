var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Plumtree_ProductChange/js/model/attswitch': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Plumtree_ProductChange/js/model/swatch-attswitch': true
            }
        }
    }
};