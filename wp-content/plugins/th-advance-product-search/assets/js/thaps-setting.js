(function ($){
    var THVSsettingLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
          var $this = this;
            $this.SettingTab();
            $this.ColorPiker();
            $this.ImageAdd();
            $this.RemoveImage();
            $this.SaveSetting();
            $this.ResetSetting();
            $this.ChangeSetting();
            $this.CopyToClipboard();
          
        },

         CopyToClipboard: function () {

  $(document).on('click', '.copy-btn', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var $box = $btn.closest('.shortcode-box, .th-code-box');
    var textToCopy = $box.find('code').text().trim();

    if (!textToCopy) {
      return;
    }

    // Modern clipboard
    if (navigator.clipboard && window.isSecureContext) {

      navigator.clipboard.writeText(textToCopy).then(function () {
        copiedFeedback($btn);
      });

    } else {

      // fallback for old browsers / WP admin
      var $temp = $('<textarea>');
      $('body').append($temp);
      $temp.val(textToCopy).select();
      document.execCommand('copy');
      $temp.remove();

      copiedFeedback($btn);

    }

    function copiedFeedback(btn) {

      var originalText = btn.text();

      btn.text('Copied');
      btn.addClass('copied');

      setTimeout(function () {

        btn.text(originalText);
        btn.removeClass('copied');

      }, 1500);

    }

  });

},
        SettingTab: function (){
          $(document).ready(function(){ 
                 $('#thaps').on('click', '.nav-tab', function (event){
                  event.preventDefault()
                  var target = $(this).data('target')
                  $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active')
                  $('#' + target).show().siblings().hide()
                  $('#_last_active_tab').val(target);

                  if ($("a[data-target='style']").hasClass('nav-tab-active')){
                         $('.setting-preview-wrap.style-wrapper').show();
                    }else{
                         $('.setting-preview-wrap.style-wrapper').hide();
                    }

                if ($("a[data-target='reset']").hasClass('nav-tab-active')){
                         $('.preview-reset-wrapper').show();
                    }else{
                         $('.preview-reset-wrapper').hide();
                    }

                if ($("a[data-target='help']").hasClass('nav-tab-active')){
                         $('.setting-preview-wrap.help-wrapper').show();
                    }else{
                         $('.setting-preview-wrap.help-wrapper').hide();
                    }

                    
              // ===== header title change =====
                  var tabText = $(this).clone().children().remove().end().text().trim();
                  $('.tabheading').text(tabText);

                  /* Dynamic class add */
                var wrap = $('.setting-wrap');

                // Remove all classes except the base class
                wrap.attr('class', 'setting-wrap');

                // Add the current target class
                wrap.addClass(target);

                  

                        });

                 $("#search-configure input,#tapsp_specific_key_search-field,#thaps_enable_fuzzy-field").prop("disabled", true);
          });
        },
        ColorPiker: function (){
        jQuery(document).ready(function ($) {

            function applyHoverColor(inputId, value) {

          var styleId = 'hover-style-' + inputId;
          $('#' + styleId).remove();

          var css = '';

          // background hover
          $('[data-th-bg-hover="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':hover { background-color: ' + value + ' !important; }';
          });

          // text hover
          $('[data-th-color-hover="' + inputId + '"]').each(function () {
            var selector = getSelector(this);

            css += selector + ':hover { color: ' + value + ' !important; }';
          });

          $('head').append('<style id="' + styleId + '">' + css + '</style>');
}

           function getSelector(el) {

  // priority 1: ID
  if (el.id) {
    return '#' + el.id;
  }

  // priority 2: unique class
  if (el.className) {
    var classes = el.className.trim().split(/\s+/).join('.');
    return el.tagName.toLowerCase() + '.' + classes;
  }

  // fallback (rare case)
  return el.tagName.toLowerCase();
}

  function applyPreview(inputId, value) {
    $('[data-th-bg="' + inputId + '"]').css('background-color', value);
    $('[data-th-color="' + inputId + '"]').css('color', value);
    $('[data-th-border="' + inputId + '"]').css('border-color', value);
  }

  // ✅ INIT ON LOAD
  $('.thaps-color-picker').each(function () {
    var val = $(this).val();
    if (val) applyPreview(this.id, val);
     // hover
    applyHoverColor(this.id, val);
  });

  // ✅ COLOR PICKER CHANGE
  $('.thaps-color-picker').wpColorPicker({
    change: function (event, ui) {
       var id = event.target.id;
    var value = ui.color.toString();

    // normal
    applyPreview(id, value);

    // hover
    applyHoverColor(id, value);
    }
  });

  // 🔥 IMPORTANT: ALPHA SLIDER LIVE TRACK
  $(document).on('mousemove', '.iris-slider, .iris-square', function () {

    $('.thaps-color-picker').each(function () {
      var inputId = this.id;
      var value = $(this).val(); // updated rgba

      if (value) {
        applyPreview(inputId, value);
      }
    });

  });

});
        },

        ImageAdd:function (){
           $(document).on('click', 'button.thaps_upload_image_button', function (event){
                var _this = this;
                event.preventDefault();
                event.stopPropagation();

                var file_frame = void 0;

                if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.select_image = wp.media({
                        title: THAPSPluginObject.media_title,
                        button: {
                            text: THAPSPluginObject.button_title
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        if ($.trim(attachment.id) !== '') {

                            var url = typeof attachment.sizes.thumbnail === 'undefined' ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;

                            $(_this).prev().val(attachment.id);
                            $(_this).closest('.meta-image-field-wrapper').find('img').attr('src', url);
                            $(_this).next().show();
                        }
                        //file_frame.close();
                    });

                    // When open select selected
                    file_frame.on('open', function () {

                        // Grab our attachment selection and construct a JSON representation of the model.
                        var selection = file_frame.state().get('selection');
                        var current = $(_this).prev().val();
                        var attachment = wp.media.attachment(current);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });

                    // Finally, open the modal.
                    file_frame.open();
                }
          });
        },
        RemoveImage:function (){
          $(document).on('click', 'button.thaps_remove_image_button', function (event){
                 event.preventDefault();
                 event.stopPropagation();

                var placeholder = $(this).closest('.meta-image-field-wrapper').find('img').data('placeholder');
                $(this).closest('.meta-image-field-wrapper').find('img').attr('src', placeholder);
                $(this).prev().prev().val('');
                $(this).hide();
                return false;
              });

        },
        
        SaveSetting:function(){
        $(document).on('keyup change paste', '.thaps-setting-form input, .thaps-setting-form select', function () {
        
              $('#submit').removeAttr("disabled");
              
        });  
        $(document).on("click", ".thaps-button-wrapper #submit", function (e) {
        e.preventDefault();
        $(this).addClass('loader');
        
        var form_settting = $(".thaps-setting-form").serialize();
        $.ajax({
          url: THAPSPluginObject.ajaxurl,
          type: "POST",
          data: form_settting +'&_wpnonce=' + THAPSPluginObject.nonce +'',
          success: function (response) {
           
            $('#submit').removeClass('loader');
            $('#submit').attr("disabled","disabled");

          },
        });
      });
    },
    ResetSetting: function() {
      $(document).on("click", ".reset", function (e) {
          e.preventDefault();
          $(this).addClass('loader');
          var confirmReset = confirm(THAPSPluginObject.reset_text);
          if (confirmReset) {
              $.ajax({
                url: THAPSPluginObject.ajaxurl,
                type: "POST",
                data: {
                  action: 'thaps_reset_settings', // Action to be handled on the server-side
                  nonce: THAPSPluginObject.nonce // Nonce for security
                },
                success: function (response) {
                  $(this).removeClass('loader');
                  location.reload();
                },
              }); 
          }
              
      });
  },
    ChangeSetting:function(){
             $(document).on('change', '#select_srch_type-field', function (e){
                   var optionSelected = $("option:selected", this);
                   var valueSelected = this.value;
                   if(valueSelected=='post_srch'){

                        $('#autosettingautosetting-section-2').hide('100');
                        $('.autosettingautosetting-section-2').hide('100');
                        $('#autosettingautosetting-section-4').hide('100');
                        $('.autosettingautosetting-section-4').hide('100');
                        $('#enable_cat_image-wrapper').hide('100');
                        $('#autosettingautosetting-section-3').show('100');
                        $('.autosettingautosetting-section-3').show('100');

                    
                   }else if(valueSelected=='product_srch'){

                        $('#autosettingautosetting-section-3').hide('100');
                        $('.autosettingautosetting-section-3').hide('100');
                        $('#autosettingautosetting-section-4').hide('100');
                        $('.autosettingautosetting-section-4').hide('100');

                        $('#autosettingautosetting-section-2').show('100');
                        $('.autosettingautosetting-section-2').show('100');
                        $('#enable_cat_image-wrapper').show('100');
                       
                    
                   }else if(valueSelected=='page_srch'){

                        $('#autosettingautosetting-section-2').hide('100');
                        $('.autosettingautosetting-section-2').hide('100');
                        $('#autosettingautosetting-section-3').hide('100');
                        $('.autosettingautosetting-section-3').hide('100');
                        $('#enable_cat_image-wrapper').hide('100');
                        $('#autosettingautosetting-section-4').show('100');
                        $('.autosettingautosetting-section-4').show('100');
                       
                    
                   }
  
                   
             });

              $(document).on('click', '#show_submit-field', function (event){
                   if($(this).is(':checked')){
                    $('#level_submit-wrapper').show();
                   }else{
                      $('#level_submit-wrapper').hide();
                   }
                   
             });

             
     },

   
}
THVSsettingLib.init();
})(jQuery);


jQuery(document).ready(function ($) {

    $('#thaps-toggle-sidebar').on('click', function () {

        $('#thaps .nav-tab-wrapper').toggleClass('thaps-sidebar-collapsed');

        // change arrow direction
        $(this).find('.dashicons')
        .toggleClass('dashicons-arrow-left-alt2 dashicons-arrow-right-alt2');

    });

       function handleSidebarOnResize() {
            if ($(window).width() <= 768) {
                $('#thaps .nav-tab-wrapper').addClass('thaps-sidebar-collapsed');
            } else {
                $('#thaps .nav-tab-wrapper').removeClass('thaps-sidebar-collapsed');
            }
        }

        // Run on load
        handleSidebarOnResize();

        // Run on resize
        $(window).on('resize', handleSidebarOnResize);


    // move premium div just below top-header
     $('.th-premium-box').insertAfter('.setting-wrap .top-header');
});