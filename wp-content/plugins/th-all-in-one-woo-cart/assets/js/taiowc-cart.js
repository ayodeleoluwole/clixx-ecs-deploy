(function ($){

    var TAIOWCscriptLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
           var $this = this;
            $this.cartopen();
            $this.cartclose();
            $this.AddCartProduct();
            $this.refreshMyFragments();
            $this.UpdateCart();

        },
        cartopen: function (){
            $(document).on('click','a.taiowc-content',function(e){
                e.preventDefault();

                

                // Remove active class from all first (optional cleanup)
                $('.taiowc-model-wrap').removeClass('model-cart-active');

                // Then activate the modal wrapper
                $('.taiowc-model-wrap').addClass('model-cart-active');

                
            });
        },
        cartclose: function () {
            // Close button inside cart
            $(document).on('click', 'a.taiowc-cart-close', function (e) {
                e.preventDefault();
                $('.taiowc-model-wrap').removeClass('model-cart-active');
            });

            // Click outside the cart closes it
            $(document).on('click', 'body', function (evt) {
                if ($(evt.target).closest('a.taiowc-content, .taiowc-cart-model').length)
                    return;
                $('.taiowc-model-wrap').removeClass('model-cart-active');
            });
        },

        refreshMyFragments:function (){
            $.ajax({
                url:taiowc_param.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                type: 'POST',
                data: {},
                success: function( response ){
                     if(response.fragments){
                        var fragments = response.fragments,
                            cart_hash =  response.cart_hash;

                        //Set fragments
                        $.each( response.fragments, function( key, value ) {
                            $( key ).replaceWith( value );
                            //$( key ).stop( true ).css( 'opacity', '1' ).unblock();
                        });

                        if( typeof wc_cart_fragments_params !== 'undefined' && ( 'sessionStorage' in window && window.sessionStorage !== null ) ){

                            sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( response.fragments ) );
                            localStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );
                            sessionStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );

                            if ( response.cart_hash ) {
                                sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
                            }

                        }

                        $(document.body).trigger('wc_fragments_loaded');

                        //Refresh checkout page
                            // if( window.wc_checkout_params && wc_checkout_params.is_checkout === "1" ){
                            //     if( $( 'form.checkout' ).length === 0 ){
                            //         location.reload();
                            //         return;
                            //     }
                            //     $(document.body).trigger("update_checkout");
                            // }

                            //Refresh Cart page
                            // if( window.wc_add_to_cart_params && window.wc_add_to_cart_params.is_cart && wc_add_to_cart_params.is_cart === "1" ){
                            //     $(document.body).trigger("wc_update_cart");
                            // }
                      }
                },
                
            })

        },

        AddCartProduct: function (){

               // remove item from cart

               $(document).on('click','a.taiowc-remove-item',function(e){

                 updateItemQty( $( e.currentTarget ).data('key'), 0 );
               
               });

                    // Quantity change and update cart
                    $(document).on('click', '.quantity-decrement, .quantity-increment', function(e) {
                        e.preventDefault();
                
                        // Get the quantity input associated with the clicked button
                        var input = $(this).siblings('input.taiowc-quantity');
                        var currentVal = parseInt(input.val());
                        var newVal;
                
                        // Check if the decrement or increment button was clicked
                        if ($(this).hasClass('quantity-decrement')) {
                            newVal = currentVal > parseInt(input.attr('min')) ? currentVal - 1 : currentVal;
                        } else if ($(this).hasClass('quantity-increment')) {
                            newVal = !input.attr('max') || currentVal < parseInt(input.attr('max')) ? currentVal + 1 : currentVal;

                        }
                
                        // Update the input value and trigger the change event
                        input.val(newVal).trigger('change');
                        
                    });
                
                    // Trigger update when input value changes
                    $(document).on('change', 'input.taiowc-quantity', function(e) {
                        var quantity = $(this).val();
                        updateItemQty($(e.currentTarget).data('key'), quantity);
                    });
                
               // update item 
              function updateItemQty( cart_key, qty ){

                if( !cart_key || qty === undefined ) return;

                show_loader();

                $.ajax({
                    url:taiowc_param.wc_ajax_url.toString().replace( '%%endpoint%%', 'taiowc_update_item_quantity' ),
                    type: 'POST',
                    data: {
                            cart_key: cart_key,
                            new_qty: qty,
                            security: taiowc_param.update_qty_nonce
                          },
                    success: function(response){ 
                    hide_loader();
                    show_custom_notice();

                    if(response.fragments){
                        var fragments = response.fragments,
                            cart_hash =  response.cart_hash;

                        //Set fragments
                        $.each( response.fragments, function( key, value ) {
                            $( key ).replaceWith( value );
                            $( key ).stop( true ).css( 'opacity', '1' ).unblock();
                        });

                        if(wc_cart_fragments_params){
                            var cart_hash_key = wc_cart_fragments_params.ajax_url.toString() + '-wc_cart_hash';
                            //Set cart hash
                            sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( fragments ) );
                            localStorage.setItem( cart_hash_key, cart_hash );
                            sessionStorage.setItem( cart_hash_key, cart_hash );
                        }

                        $(document.body).trigger('wc_fragments_loaded');
                        //Refresh checkout page
                            if( window.wc_checkout_params && wc_checkout_params.is_checkout === "1" ){
                                if( $( 'form.checkout' ).length === 0 ){
                                    location.reload();
                                    return;
                                }
                                $(document.body).trigger("update_checkout");
                            }

                            //Refresh Cart page
                            if( window.wc_add_to_cart_params && window.wc_add_to_cart_params.is_cart && wc_add_to_cart_params.is_cart === "1" ){
                                $(document.body).trigger("wc_update_cart");
                            }
                      }

                  }

                })

             }
 
               //loader show

               function show_loader(){
                   
                   $('.taiowc-cart-model-body').addClass('loading');

               }
               //hide loader
                function hide_loader(){

                   $('.taiowc-cart-model-body').removeClass('loading');
               }

               //Notice Function

                function show_notice(notice_type,notice){

                $('.taiowc-notice').html(notice).attr('class','taiowc-notice').addClass('taiowc-nt-'+notice_type);
                $('.taiowc-notice-box').fadeIn('fast');

                clearTimeout(fadenotice);

                var fadenotice = setTimeout(function(){

                    $('.taiowc-notice-box').fadeOut('slow');

                 },3000);
             };

             //custom Notic add header
            
                function show_custom_notice(){

                     $( document ).ready(function() {

                     $('.taiowc-notice-container').fadeIn('fast');

                       clearTimeout(fadenotice);

                       var fadenotice = setTimeout(function(){

                       $('.taiowc-notice-container').fadeOut('slow');

                       },3000);
                     });
                 };


          },

         UpdateCart: function () {
    var $this = this;

    // ✅ For WooCommerce Blocks add-to-cart event
    $(document.body).on('wc-blocks_added_to_cart', function (event) {
        // Refresh cart fragments (needed for WC blocks)
        $this.refreshMyFragments();

        // ✅ Open the modal cart
        $('.taiowc-model-wrap').addClass('model-cart-active');

        // ✅ Refresh checkout page
        if (window.wc_checkout_params && wc_checkout_params.is_checkout === "1") {
            if ($('form.checkout').length === 0) {
                location.reload();
                return;
            }
            $(document.body).trigger("update_checkout");
        }

        // ✅ Refresh Cart page
        if (window.wc_add_to_cart_params && wc_add_to_cart_params.is_cart === "1") {
            $(document.body).trigger("wc_update_cart");
        }
    });

    // ✅ For classic WooCommerce add-to-cart event
    $(document).on('added_to_cart', function (event, fragments, hash, atc_btn) {
        // ✅ Open the modal cart
        $('.taiowc-model-wrap').addClass('model-cart-active');

        // ✅ Refresh checkout page
        if (window.wc_checkout_params && wc_checkout_params.is_checkout === "1") {
            if ($('form.checkout').length === 0) {
                location.reload();
                return;
            }
            $(document.body).trigger("update_checkout");
        }

        // ✅ Refresh Cart page
        if (window.wc_add_to_cart_params && wc_add_to_cart_params.is_cart === "1") {
            $(document.body).trigger("wc_update_cart");
        }
    });
  }
 
}

TAIOWCscriptLib.init();

})(jQuery);