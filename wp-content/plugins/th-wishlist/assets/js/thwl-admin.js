jQuery(document).ready(function($) {
    // tab
    $(document).on('click', '.thw-tab', function(e) {
        e.preventDefault();
        $('.thw-tab').removeClass('active');
        $('.thw-tab-content').removeClass('active');
        $(this).addClass('active');
        const tabId = $(this).data('tab');
        $('#' + tabId).addClass('active');
    });

    // Media uploader for custom icon
    $('#thw_upload_icon_button').click(function(e) {
        e.preventDefault();
        var frame = wp.media({ title: 'Upload Icon', multiple: false });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#thw_custom_icon_url').val(attachment.url);
        });
        frame.open();
    });

    // Toggle custom icon URL field visibility
    function toggleCustomIcon() {
        $('.thw-custom-icon-row').toggleClass('show', $('#thw_use_custom_icon').is(':checked'));
    }
    toggleCustomIcon();
    $('#thw_use_custom_icon').on('change', toggleCustomIcon);

    // Make columns sortable
    $('#thw-sortable-columns').sortable({
    axis: 'y',
    opacity: 0.7,
    placeholder: 'ui-state-highlight',
    update: function() {
        $('#thw-sortable-columns li').each(function() {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('name', checkbox.is(':checked') ? 'settings[th_wishlist_table_columns][]' : '');
        });
    }
    });

    // Update checkbox name based on checked state
    $('#thw-sortable-columns input[type="checkbox"]').on('change', function() {
        $(this).prop('name', $(this).is(':checked') ? 'settings[th_wishlist_table_columns][]' : '');
    });

    // Save settings via AJAX
    $('#thw-settings-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $notice = $('#thw-settings-notice');
        var data = $form.serializeArray();
        data.push({ name: 'action', value: 'thwl_save_settings' });
        data.push({ name: '_wpnonce', value: $form.data('nonce') });
  
        $.ajax({
            url: thwlAdmin.ajax_url,
            type: 'POST',
            data: data,
            beforeSend: function() {
                $form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                $notice.removeClass('success error').addClass(response.success ? 'success' : 'error').text(response.data).show();
                setTimeout(function() { $notice.fadeOut(); }, 3000);
            },
            error: function() {
                $notice.removeClass('success').addClass('error').text(thwlAdmin.i18n.save_error).show();
                setTimeout(function() { $notice.fadeOut(); }, 3000);
            },
            complete: function() {
                $form.find('button[type="submit"]').prop('disabled', false).text('Save Settings');
            }
        });
    });

    // Reset settings via AJAX
    $('#thw-reset-settings').on('click', function() {
        if (!confirm(thwlAdmin.i18n.confirm_reset)) {
            return;
        }
        var $button = $(this);
        var $notice = $('#thw-settings-notice');
        var data = {
            action: 'thwl_reset_settings',
            _wpnonce: $button.data('nonce')
        };

        $.ajax({
            url: thwlAdmin.ajax_url,
            type: 'POST',
            data: data,
            beforeSend: function() {
                $button.prop('disabled', true).text('Resetting...');
            },
            success: function(response) {
                $notice.removeClass('success error').addClass(response.success ? 'success' : 'error').text(response.data).show();
                if (response.success) {
                    location.reload();
                } else {
                    setTimeout(function() { $notice.fadeOut(); }, 3000);
                }
            },
            error: function() {
                $notice.removeClass('success').addClass('error').text(thwlAdmin.i18n.reset_error).show();
                setTimeout(function() { $notice.fadeOut(); }, 3000);
            },
            complete: function() {
                $button.prop('disabled', false).text('Reset to Defaults');
            }
        });
    });

    function myColorPicker() {
    let value_ = this;
    const inputElement = $(value_);
    const defaultColor = inputElement.data("default-color") || 'rgba(0, 0, 0, 1)';

    const pickr = new Pickr({
        el: value_,
        useAsButton: true,
        default: inputElement.val() || defaultColor,
        theme: 'nano',
        swatches: [
            'rgba(244, 67, 54, 1)',
            'rgba(233, 30, 99, 0.95)',
            'rgba(156, 39, 176, 0.9)',
            'rgba(103, 58, 183, 0.85)',
            'rgba(63, 81, 181, 0.8)',
            'rgba(33, 150, 243, 0.75)',
            'rgba(255, 193, 7, 1)',
        ],
        components: {
            preview: true,
            opacity: true,
            hue: true,
            interaction: { input: true },
        },
    })
    .on('change', (color, instance) => {
        let color_ = color.toRGBA().toString(0);
        inputElement.css('background-color', color_);
        inputElement.val(color_);
        $('#submit').removeAttr('disabled');
    })
    .on('init', (instance) => {
        $(instance._root.app).addClass('visible');
    })
    .on('hide', (instance) => {
        instance._root.app.remove();
    });

    // Reset button handler
    $(document).on('click', '.th-color-reset', function () {
        const targetId = $(this).data('target');
        const targetInput = $('#' + targetId);
        const resetColor = targetInput.data('default-color') || 'rgba(0, 0, 0, 1)';

        // Reset UI + input value
        targetInput.val(resetColor).css('background-color', resetColor);

        // If Pickr is bound to this input, set color programmatically
        if (targetInput[0]._pickr) {
            targetInput[0]._pickr.setColor(resetColor);
        }

        $('#submit').removeAttr('disabled');
    });

    // Attach pickr reference to input element for reset use
    inputElement[0]._pickr = pickr;
   }
    // Attach Pickr to inputs with class color_picker
    $(document).on('click', 'input.th_color_picker', myColorPicker);
   //icon picker change color
   $(document).ready(function () {
    function initColorPicker($colorInput, radioName) {
        let lastVal = $colorInput.val().trim();

        const applyColorToAllIcons = (color) => {
            const $radios = $('input[name="' + radioName + '"]');

            $radios.each(function () {
                const $svg = $(this).closest('label').find('svg.th-wishlist-icon-svg');

                if ($svg.length) {
                    $svg.css({ fill: '', stroke: '' }); // reset both

                    if ($svg.attr('fill') === 'currentColor') {
                        $svg.css('fill', color); // for filled icons
                    } else {
                        $svg.css('stroke', color); // for outline icons
                    }
                }
            });
        };

        // Apply immediately on load
        if (/^#(?:[0-9a-f]{3}){1,2}$/i.test(lastVal) || /^rgba?\([\d\s,\.]+\)$/i.test(lastVal)) {
            applyColorToAllIcons(lastVal);
        }

        // Watch for changes
        setInterval(function () {
            const currentVal = $colorInput.val().trim();
            if (currentVal !== lastVal &&
                (/^#(?:[0-9a-f]{3}){1,2}$/i.test(currentVal) || /^rgba?\([\d\s,\.]+\)$/i.test(currentVal))) {
                lastVal = currentVal;
                applyColorToAllIcons(currentVal);
            }
        }, 200);

        // Also update icons on radio change (to refresh styles)
        $('input[name="' + radioName + '"]').on('change', function () {
            const currentVal = $colorInput.val().trim();
            if (/^#(?:[0-9a-f]{3}){1,2}$/i.test(currentVal) || /^rgba?\([\d\s,\.]+\)$/i.test(currentVal)) {
                applyColorToAllIcons(currentVal);
            }
        });

        // Force apply on load
        setTimeout(() => {
            $('input[name="' + radioName + '"]:checked').trigger('change');
        }, 50);
    }

    // Init both color pickers with their respective radio groups
    initColorPicker($('input[name="settings[th_wishlist_add_icon_color]"]'), 'settings[th_wishlist_add_icon]');
    initColorPicker($('input[name="settings[th_wishlist_brws_icon_color]"]'), 'settings[th_wishlist_brws_icon]');
});

//hide show page redirect setting
jQuery(document).ready(function($) {
    function toggleRedirectWishlistSettings() {
        if ($('#thw_redirect_wishlist_page').is(':checked')) {
            $('.thw-redirect-wishlist-dependent').show();
        } else {
            $('.thw-redirect-wishlist-dependent').hide();
        }
    }
    // Run on page load
    toggleRedirectWishlistSettings();

    // Run on checkbox change
    $('#thw_redirect_wishlist_page').on('change', function() {
        toggleRedirectWishlistSettings();
    });
});
   
});

// style tab
(function($){
            $('.thwl-pro-tabs li').on('click', function(){
                var tab = $(this).data('tab');
                $('.thwl-pro-tabs li').removeClass('active');
                $(this).addClass('active');
                $('.thwl-tab-content').removeClass('active');
                $('#' + tab).addClass('active');
            });
})(jQuery);


jQuery(document).ready(function($){
    function updateIconColor(value){
    const $svg = $('#thwl_icon_preview').find('svg.th-wishlist-icon-svg');

    if ($svg.length) {
        $svg.add($svg.find('*')).each(function(){
            const $el = $(this);
            const fillAttr = $el.attr('fill');
            const strokeAttr = $el.attr('stroke');

            // Fill only if not "none"
            if (fillAttr && fillAttr.toLowerCase() !== 'none') {
                if (fillAttr === 'currentColor') {
                    $el.css('fill', value);
                } else {
                    $el.css('fill', value);
                }
            }

            // Stroke only if not "none"
            if (strokeAttr && strokeAttr.toLowerCase() !== 'none') {
                if (strokeAttr === 'currentColor') {
                    $el.css('stroke', value);
                } else {
                    $el.css('stroke', value);
                }
            }
        });
    }
}

function updateIconBColor(value){
    const $svg = $('#thwl_brws_icon_preview').find('svg.th-wishlist-icon-svg');

    if ($svg.length) {
        $svg.add($svg.find('*')).each(function(){
            const $el = $(this);
            const fillAttr = $el.attr('fill');
            const strokeAttr = $el.attr('stroke');

            if (fillAttr && fillAttr.toLowerCase() !== 'none') {
                if (fillAttr === 'currentColor') {
                    $el.css('fill', value);
                } else {
                    $el.css('fill', value);
                }
            }

            if (strokeAttr && strokeAttr.toLowerCase() !== 'none') {
                if (strokeAttr === 'currentColor') {
                    $el.css('stroke', value);
                } else {
                    $el.css('stroke', value);
                }
            }
        });
    }
}

    // When Add to Wishlist icon changes
    $('input[name="settings[th_wishlist_add_icon]"]').on('change', function(){
        var iconHtml = $(this).next('span').html();
        $('#thwl_icon_preview').html(iconHtml);
        // Apply current icon color
        var color = $('#thwl-icon-color').val();
        if(color) updateIconColor(color);
    });

    // When Add to Wishlist Browse icon changes
    $('input[name="settings[th_wishlist_brws_icon]"]').on('change', function(){
        var iconHtml = $(this).next('span').html();
        $('#thwl_brws_icon_preview').html(iconHtml);
        // Apply current icon color
        var color = $('#thwl-icon-b-color').val();
        if(color) updateIconBColor(color);
    });
    

    // Live color pickers
    $('.th_color_picker').each(function(){
        var $input = $(this);
        var target = $input.attr('id');
        function applyColor(value){
            switch(target){
                case 'th_wishlist_btn_bg_color':
                    $('#thwl_button_preview,#thwl_button_preview_browse').css('background-color', value);
                    break;
                case 'th_wishlist_btn_txt_color':
                    $('#thwl_button_preview,#thwl_button_preview_browse').css('color', value);
                    break;
                case 'th_wishlist_add_icon_color':
                    updateIconColor(value);
                    break;
                case 'th_wishlist_brws_icon_color':
                    updateIconBColor(value);
                    break;
            }
        }

        var lastVal = $input.val().trim();
        if(lastVal) applyColor(lastVal);

        // Polling for live changes
        setInterval(function(){
            var val = $input.val().trim();
            if(val !== lastVal){
                lastVal = val;
                applyColor(val);
            }
        }, 200);
    });

    // Initial preview for selected icon
    var selectedIcon = $('input[name="settings[th_wishlist_add_icon]"]:checked').next('span').html();
    if(selectedIcon){
        $('#thwl_icon_preview').html(selectedIcon);
        var color = $('#thwl-icon-color').val();
        if(color) updateIconColor(color);
    }
    var selectedBIcon = $('input[name="settings[th_wishlist_brws_icon]"]:checked').next('span').html();
    if(selectedBIcon){
        $('#thwl_brws_icon_preview').html(selectedBIcon);
        var color = $('#thwl-icon-b-color').val();
        if(color) updateIconBColor(color);
    }
});


// table content live
jQuery(document).ready(function($){
    // Map color pickers to preview elements
    $('.th_color_picker').each(function(){
        var $input = $(this);
        var target = $input.attr('id');
        function applyColor(value){
            switch(target){
                case 'th_wishlist_tb_btn_txt_color':
                    $('.thw-wishlist-actions .all-button,.cart button').css('color', value);
                    break;
                case 'th_wishlist_tb_btn_bg_color':
                    $('.thw-wishlist-actions .all-button,.cart button').css('background', value);
                    break;
                case 'th_wishlist_table_bg_color':
                    $('.thwl-preview-table-1').css('background', value);
                    break;
                case 'th_wishlist_table_brd_color':
                    $('.thwl-preview-table-1 th,.thwl-preview-table-1 td').css('border-color', value);
                    break;
                case 'th_wishlist_table_txt_color':
                    $('.thwl-preview-table-1').css('color', value);
                    break;
                case 'th_wishlist_shr_fb_color':
                    $('.thw-table-custom-style .thw-social-share a.thw-share-facebook').css('color', value);
                    break;
                case 'th_wishlist_shr_fb_hvr_color':
                    $('#thwl-fb-hover-style').remove();
                    $('head').append('<style id="thwl-fb-hover-style">.thw-table-custom-style .thw-social-share a.thw-share-facebook:hover{color:'+value+' !important;}</style>');
                    break;
                case 'th_wishlist_shr_x_color':
                    $('.thw-table-custom-style .thw-social-share a.thw-share-twitter').css('color', value);
                    break;
                case 'th_wishlist_shr_x_hvr_color':
                    $('#thwl-x-hover-style').remove();
                    $('head').append('<style id="thwl-x-hover-style">.thw-table-custom-style .thw-social-share a.thw-share-twitter:hover{color:'+value+' !important;}</style>');
                    break;
                case 'th_wishlist_shr_w_color':
                    $('.thw-table-custom-style .thw-social-share a.thw-share-whatsapp').css('color', value);
                    break;
                case 'th_wishlist_shr_w_hvr_color':
                    $('#thwl-w-hover-style').remove();
                    $('head').append('<style id="thwl-w-hover-style">.thw-table-custom-style .thw-social-share a.thw-share-whatsapp:hover{color:'+value+' !important;}</style>');
                    break;
                case 'th_wishlist_shr_e_color':
                    $('.thw-table-custom-style .thw-social-share a.thw-share-email').css('color', value);
                    break;
                case 'th_wishlist_shr_e_hvr_color':
                    $('#thwl-e-hover-style').remove();
                    $('head').append('<style id="thwl-e-hover-style">.thw-table-custom-style .thw-social-share a.thw-share-email:hover{color:'+value+' !important;}</style>');
                    break;
                case 'th_wishlist_shr_c_color':
                    $('.thw-table-custom-style .thw-social-share a.thw-copy-link-button').css('color', value);
                    break;
                case 'th_wishlist_shr_c_hvr_color':
                    $('#thwl-c-hover-style').remove();
                    $('head').append('<style id="thwl-c-hover-style">.thw-table-custom-style .thw-social-share a.thw-copy-link-button:hover{color:'+value+' !important;}</style>');
                    break;

                    
                }
        }
        // init on load
        var lastVal = $input.val().trim();
        if(lastVal) applyColor(lastVal);
        // poll for live changes
        setInterval(function(){
            var val = $input.val().trim();
            if(val !== lastVal){
                lastVal = val;
                applyColor(val);
            }
        }, 200);
    });
});

//page redirect
jQuery(document).ready(function($){

    function updatePreviewIconColor(value){
        const $svg = $('#thwl_icon_preview_redirect').find('svg');
        if ($svg.length) {
            $svg.add($svg.find('*')).each(function(){
                const $el = $(this);
                const fillAttr = $el.attr('fill');
                const strokeAttr = $el.attr('stroke');

                if (fillAttr && fillAttr.toLowerCase() !== 'none') {
                    $el.css('fill', value);
                }
                if (strokeAttr && strokeAttr.toLowerCase() !== 'none') {
                    $el.css('stroke', value);
                }
            });
        }
    }

    function updatePreviewIconHoverColor(value){
        $('#thwl_button_preview_redirect')
            .off('mouseenter mouseleave')
            .on('mouseenter', function(){
                updatePreviewIconColor(value);
            })
            .on('mouseleave', function(){
                let normalColor = $('#thw_redirect_wishlist_page_icon_color').val();
                if(normalColor) updatePreviewIconColor(normalColor);
            });
    }

    function updatePreviewIconSize(value){
        $('#thwl_icon_preview_redirect svg').css({
            width: value + 'px',
            height: value + 'px'
        });
    }

    // When icon selection changes
    $('input[name="settings[thw_redirect_wishlist_page_icon]"]').on('change', function(){
        var iconHtml = $(this).next('span').html();
        $('#thwl_icon_preview_redirect').html(iconHtml);

        // Grab current values
        let color = $('#thw_redirect_wishlist_page_icon_color').val();
        let hvrColor = $('#thw_redirect_wishlist_page_icon_color_hvr').val();
        let size = $('#thw_redirect_wishlist_page_icon_size').val();

        // ✅ Reapply immediately
        if(color) updatePreviewIconColor(color);
        if(hvrColor) updatePreviewIconHoverColor(hvrColor);
        if(size) updatePreviewIconSize(size);
    });

    // Color live update
    $('#thw_redirect_wishlist_page_icon_color').on('input change', function(){
        updatePreviewIconColor($(this).val());
    });

    // Hover color live update
    $('#thw_redirect_wishlist_page_icon_color_hvr').on('input change', function(){
        updatePreviewIconHoverColor($(this).val());
    });

    // Size live update
    $('#thw_redirect_wishlist_page_icon_size').on('input change', function(){
        updatePreviewIconSize($(this).val());
    });

    // Initialize on page load
    var selectedIcon = $('input[name="settings[thw_redirect_wishlist_page_icon]"]:checked').next('span').html();
    if(selectedIcon){
        $('#thwl_icon_preview_redirect').html(selectedIcon);

        let color = $('#thw_redirect_wishlist_page_icon_color').val();
        let hvrColor = $('#thw_redirect_wishlist_page_icon_color_hvr').val();
        let size = $('#thw_redirect_wishlist_page_icon_size').val();

        if(color) updatePreviewIconColor(color);
        if(hvrColor) updatePreviewIconHoverColor(hvrColor);
        if(size) updatePreviewIconSize(size);
    }
});

