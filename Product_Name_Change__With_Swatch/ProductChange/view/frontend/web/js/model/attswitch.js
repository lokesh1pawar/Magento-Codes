define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';
    return function(targetModule){

        $('h1 span').attr("data-dynamic", "name");

        var reloadPrice = targetModule.prototype._reloadPrice;
        targetModule.prototype.dynamic = {};

        $('[data-dynamic]').each(function(){
            var code = $(this).data('dynamic');
            var value = $(this).html();

            targetModule.prototype.dynamic[code] = value;
        });

        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function(original){
            var dynamic = this.options.spConfig.dynamic;
            console.log(dynamic);
            for (var code in dynamic){
                if (dynamic.hasOwnProperty(code)) {
                    var value = "";
                    var $placeholder = $('[data-dynamic='+code+']');

                    if(!$placeholder.length) {
                        continue;
                    }

                    if(this.simpleProduct){
                        value = this.options.spConfig.dynamic[code][this.simpleProduct].value;
                    } else {
                        value = this.dynamic[code];
                    }

                    $placeholder.html(value);
                }
            }

            return original();
        });

        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});
