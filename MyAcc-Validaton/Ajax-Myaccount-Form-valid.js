<script>
   
    require(['jquery', 'jquery/ui'], function($){
        $(document).ready(function(){
            $('.streetError').hide();
                   
            $(".form-address-edit").submit(function(){
              
    });
            $("#street_1").change(function(){
            var xyz = setTimeout(function() {
               
  }, 1000)
            });

            $("#street_2").on("change", function(){
             
                var poRegex = /\bP(ost|ostal)?([ \.]*O(ffice)?)?([ \.]*Box)?\b/i;
               
                var s1 = $('#street_1').val();
                var s2 = $('#street_2').val();
               
                var shippingStreet = [];
                 shippingStreet.push(s1);
                 shippingStreet.push(s2);

                 $.ajax({
                    method: "POST",
                    url: "<?php  echo $block->getUrl('addressvalidation/street/validate'); ?>",
                    data: { shippingStreet: shippingStreet},
                    dataType: "json",
                    success: function(response)
                    {
                        if(response == 'error'){
                           
                            $('#submit_action').prop('disabled', true);
                            
                            $('.streetError').show();
                            $('.streetError').addClass('message');
                            $('.streetError').text('Please check the Shipping address information. Field "street" is not valid. It is not allowed to enter a PO Box in the shipping address');
                                
                                return false;
                        } else{
                            $('.streetError').hide();
                            $('#submit_action').prop('disabled', false);

                            alert("else");

                             $("#submit_action").on('click',function(e){
                                
                                return true;
                                });

                                return true;

                        }

                    },

                });

            });
        

        });
    });
</script>
