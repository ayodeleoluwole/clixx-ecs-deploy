jQuery(function($) {
    // Add to wishlist (normal + shortcode in one handler)
$(document).on('click', '.thw-add-to-wishlist-button:not(.thw-login-required)', function (e) {
    e.preventDefault();
    var $button = $(this);
    if ($button.hasClass('create-multi')){
    return;
    }
    var isShortcode = $button.hasClass('is-shortcode');
    var product_id = $button.data('product-id');
    var variation_id = $button.data('variation-id');

     // Define all wishlist SVG icons in JS
                const THWL_ICONS = {
                    'heart-outline': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
                    'heart-filled': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
                    'star-outline': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21L12 17.77L5.82 21L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>',
                    'star-filled': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21L12 17.77L5.82 21L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>',
                    'bookmark-outline': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M6.32 2.577c2.83-.33 5.66-.33 8.49 0 1.497.174 2.57 1.46 2.57 2.93V21l-6.165-3.583-7.165 3.583V5.507c0-1.47 1.073-2.756 2.57-2.93Z"/></svg>',
                    'bookmark-filled': '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"/></svg>'
                };

    // If already in wishlist → go to wishlist page
    if ($button.hasClass('in-wishlist')) {
        if (thwl_wishlist_params.wishlist_page_url) {
            window.location.href = thwl_wishlist_params.wishlist_page_url;
        }
        return;
    }

    $.ajax({
        type: 'POST',
        url: thwl_wishlist_params.ajax_url,
        data: {
            action: 'thwl_add_to_wishlist',
            nonce: thwl_wishlist_params.add_nonce,
            product_id: product_id,
            variation_id: variation_id
        },
        beforeSend: function () {
            $button.addClass('loading');
        },
        success: function (response) {
            if (response.success) {

                // Update button text (different for shortcode vs normal)
                if (thwl_wishlist_params.icon_style !== 'icon') {
                    if (isShortcode) {
                        // Shortcode-specific text
                        var browseText = $button.attr('data-browse-text');
                        if (browseText) {
                            $button.find('span').last().text(browseText).attr('class', 'thw-to-browse-text');
                        } else {
                            $button.find('span').last().attr('class', 'thw-to-browse-text');
                        }
                    } else {
                        // Normal button text
                        $button.find('span').last().text(thwl_wishlist_params.i18n_added).attr('class', 'thw-to-browse-text');
                    }
                }

                $button.addClass('in-wishlist');

                // Update icon
                if (['icon', 'icon_text', 'icon_only_no_style'].includes(thwl_wishlist_params.icon_style)) {
                    if (isShortcode) {
                        var browseIcon = $button.data('browse-icon');
                        if (browseIcon) {
                            // Decode &lt; etc.
                            var decodedIcon = $('<textarea/>').html(browseIcon).text();
                            var icon_html = '<span class="thw-icon browse"><span class="' + decodedIcon + '"></span></span>';
                            $button.find('.thw-icon').replaceWith(icon_html);
                        } else {
                            
                            var selected_brwsicon = thwl_wishlist_params.th_wishlist_brws_icon || 'heart-filled';
                            var icon_html = '<span class="thw-icon browse">' + (THWL_ICONS[selected_brwsicon] || THWL_ICONS['heart-filled']) + '</span>';
                            $button.find('.thw-icon').replaceWith(icon_html);
                        }
                    } else {
                        
                        var selected_brwsicon = thwl_wishlist_params.th_wishlist_brws_icon || 'heart-filled';
                        var icon_html = '<span class="thw-icon browse">' + (THWL_ICONS[selected_brwsicon] || THWL_ICONS['heart-filled']) + '</span>';
                        $button.find('.thw-icon').replaceWith(icon_html);
                    }
                }

            } else {
                console.log(thwl_wishlist_params.i18n_error);
            }
        },
        complete: function () {
            $button.removeClass('loading');
        }
    });
    });
    // Remove from wishlist
    $(document).on('click', '.thw-remove-item', function(e) {
    e.preventDefault();
    var $this = $(this);
    // Universal parent with data-item-id
    var $parent = $this.closest('[data-item-id]');
    if (!$parent.length) return; // safety check
    var item_id = $parent.data('item-id');
    if (!item_id) return; // safety check
    $.ajax({
        type: 'POST',
        url: thwl_wishlist_params.ajax_url,
        data: {
            action: 'thwl_remove_from_wishlist',
            nonce: thwl_wishlist_params.remove_nonce,
            item_id: item_id
        },
        beforeSend: function() {
            $parent.css('opacity', '0.5');
        },
        success: function(response) {
            if (response.success) {
                $parent.fadeOut(300, function() {

                    // Table layout empty check
                    if ($parent.is('tr') && $parent.siblings().length === 0) {
                        var colspan = $parent.children().length;
                        $parent.closest('tbody').html('<tr><td colspan="' + colspan + '">' + thwl_wishlist_params.i18n_empty_wishlist + '</td></tr>');
                    }

                    // Div layout empty check
                    if ($parent.hasClass('thwl-wishlist-item') && $parent.siblings('.thwl-wishlist-item').length === 0) {
                        $parent.closest('.thw-wishlist-items').html('<p>' + thwl_wishlist_params.i18n_empty_wishlist + '</p>');
                    }

                    // Grid layout empty check
                    if ($parent.hasClass('thw-wishlist-card') && $parent.siblings('.thw-wishlist-card').length === 0) {
                        $parent.closest('.thw-wishlist-grid').html('<p>' + thwl_wishlist_params.i18n_empty_wishlist + '</p>');
                    }

                    $parent.remove();
                });
            } else {
                $parent.css('opacity', '1');
            }
        },
        error: function() {
            $parent.css('opacity', '1');
            alert('Unable to remove item. Please try again.');
        }
       });
    });

    // Update quantity
    $(document).on('change', '.thw-qty', function() {
        var $input = $(this);
        var item_id = $input.data('item-id');
        var quantity = $input.val();
        // Update WooCommerce add_to_cart_button data-quantity
        var $row = $input.closest('tr');
        var $button = $row.find('.add_to_cart_button');
        if ($button.length) {
            $button.attr('data-quantity', quantity);
        }
        $.post(thwl_wishlist_params.ajax_url, { action: 'thwl_update_item_quantity', nonce: thwl_wishlist_params.update_qty_nonce, item_id: item_id, quantity: quantity });
    });

    // Select/Deselect all
    $(document).on('change', '#thw-select-all', function() {
    $('.thwl-wishlist-item input[type="checkbox"]').prop('checked', this.checked);
    });

    // Add all selected to cart
    $(document).on('click', '.thw-add-all-to-cart', function(e) {
            e.preventDefault();

            var $button = $(this);
            var items = [];

            $('.thwl-wishlist-item input[type="checkbox"]:checked').each(function() {
                items.push($(this).val());
            });

            if (items.length === 0) {
                alert('Please select products to add to cart.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: thwl_wishlist_params.ajax_url,
                data: {
                    action: 'thwl_add_all_to_cart',
                    nonce: thwl_wishlist_params.add_all_nonce,
                    items: items
                },
                beforeSend: function() {
                    $button.addClass('loading');
                },
                success: function() {
                    if (thwl_wishlist_params.redirect_to_cart) {
                        window.location.href = thwl_wishlist_params.cart_url;
                    } else {
                        $button.text('Added to Cart!');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });

        // Copy link
        $(document).on('click', '.thw-copy-link-button', function() {
            var link = $(this).data('link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(link).select();
            document.execCommand("copy");
            $temp.remove();
            alert('Wishlist link copied to clipboard!');
        });

    // redirect to cart or remove code

    $(document).on('click', '.thw-add-to-cart-ajax', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const product_id = $btn.data('product-id');
        const quantity = $btn.data('quantity') || 1;
        const item_id = $btn.data('item-id');
        const token = $btn.data('wishlist-token');
        const $row = $('.thwl-wishlist-item[data-item-id="' + item_id + '"]');
        // Optional: disable button while processing
        $btn.prop('disabled', true).addClass('loading');
        $.ajax({
            type: 'POST',
            url: thwl_wishlist_params.ajax_url,
            data: {
                action: 'thwl_add_to_cart_and_manage',
                product_id,
                quantity,
                item_id,
                token,
                nonce: thwl_wishlist_params.redirect_nonce
            },
            success: function (response) {
                if (response.success) {
                    $row.fadeOut(300, function () {
                        const $tbody = $row.closest('tbody');
                        $row.remove();
                        // Check if this was the last item
                        if ($tbody.find('tr').length === 0) {
                            const colspan = $btn.closest('table').find('thead th').length;
                            $tbody.html('<tr><td colspan="' + colspan + '">' + thwl_wishlist_params.i18n_empty_wishlist + '</td></tr>');
                        }
                        // Redirect only after animation (optional)
                        if (thwl_wishlist_params.redirect_to_cart) {
                            window.location.href = thwl_wishlist_params.cart_url;
                        }
                    });
                } else {
                    $btn.prop('disabled', false).removeClass('loading');
                    alert(response.data.message || 'Error adding to cart.');
                }
            },
            error: function () {
                $btn.prop('disabled', false).removeClass('loading');
                alert('Something went wrong.');
            }
        });
    });

    // Login required popup alert
    $(document).on('click', '.thw-login-required', function (e) {
        e.preventDefault();
        alert($(this).data('alert'));
    });
});
