(function ($){
    var TAIOWCsettingLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
          var $this = this;
            $this.SettingTab();
            $this.ColorPickr();
            $this.ImageAdd();
            $this.SaveSetting();
            $this.ChangeSettinghideshow();

            $this.CopyToClipboard();
            $this.LiveStylePreview();
            $this.LiveTogglePreview();
            $this.LiveTextPreview();
            $this.LiveCartEffectPreview();
            $this.LiveMobileCartEffectPreview();
            $this.LiveMobileSwitcher();
            $this.HeaderResetTrigger();
        },

         HeaderResetTrigger: function () {

    $(document).on('click', 'span.reset', function (e) {
        // e.preventDefault();

        // Trigger click on Reset tab
        $("a[data-target='taiowc_reset']").trigger('click');
    });

},

        LiveMobileSwitcher: function () {

    // OPEN cart (bottom → top)
    $(document).on('click', '.live-mobile .taiowc-content', function (e) {
        e.preventDefault();

        $('.live-mobile .cart-panel-preview .cart-wrapper')
            .addClass('active');
    });

    // CLOSE cart (button / overlay)
    $(document).on('click', '.live-mobile .close-btn, .live-mobile .cart-overlay', function (e) {
        e.preventDefault();

        $('.live-mobile .cart-panel-preview .cart-wrapper')
            .removeClass('active');
    });

    // ESC key close (optional)
    $(document).on('keyup', function (e) {
        if (e.key === 'Escape') {
            $('.live-mobile .cart-panel-preview .cart-wrapper')
                .removeClass('active');
        }
    });

},

        LiveMobileCartEffectPreview: function () {

    var $mobileSelect = $('#taiowc-cart_mobile_effect-field');
    var $globalSelect = $('#taiowc-cart_effect-field');
    var $wrapper      = $('.live-mobile .cart-panel-preview .cart-wrapper');

    function applyMobileEffect() {
        var mobileEffect = $mobileSelect.val();
        var globalEffect = $globalSelect.val();

        // remove ALL effect classes
        $wrapper.removeClass(function (i, cls) {
            return (cls.match(/(taiowc-\S+|mobiletopslide)/g) || []).join(' ');
        });

        if (mobileEffect === 'mobiletopslide') {
            // mobile-only bottom slide
            $wrapper.addClass('mobiletopslide');
        } else {
            // inherit global effect
            if (globalEffect) {
                $wrapper.addClass(globalEffect);
            }
        }
    }

    // mobile effect change
    $(document).on('change', '#taiowc-cart_mobile_effect-field', function () {
        applyMobileEffect();
    });

    // global effect change (only when mobile = global)
    $(document).on('change', '#taiowc-cart_effect-field', function () {
        if ($mobileSelect.val() === 'global') {
            applyMobileEffect();
        }
    });

    // apply on page load
    $(document).ready(function () {
        applyMobileEffect();
    });

},



        LiveTogglePreview: function () {


  function applyToggle(inputId, isChecked) {

            $('[data-th-toggle="' + inputId + '"]').each(function () {

              var mode = $(this).data('th-toggle-mode') || 'show';

              if (mode === 'hide') {
                // checked = hide
                if (isChecked) {
                  $(this).hide();
                } else {
                  $(this).show();
                }
              } else {
                // default: checked = show
                if (isChecked) {
                  $(this).show();
                } else {
                  $(this).hide();
                }
              }

            });

          }


  // Live change
  $(document).on('change', 'input[type="checkbox"]', function () {
    if (!this.id) return;
    applyToggle(this.id, $(this).is(':checked'));
  });

  // Apply on page load
  $(document).ready(function () {
    $('input[type="checkbox"]').each(function () {
      if (this.id) {
        applyToggle(this.id, $(this).is(':checked'));
      }
    });
  });

},

        LiveStylePreview: function () {

function applyPreview(inputId, value) {

  /* ---------- COLORS ---------- */

  $('[data-th-bg="' + inputId + '"]').css('background-color', value);
  $('[data-th-color="' + inputId + '"]').css('color', value);
  $('[data-th-border="' + inputId + '"]').css('border-color', value);

  $('[data-th-shadow="' + inputId + '"]').each(function () {
    var shadowTpl = $(this).data('th-shadow-val');
    if (!shadowTpl) return;
    $(this).css('box-shadow', shadowTpl.replace('{color}', value));
  });

  /* ---------- NUMBERS ---------- */

  applyNumberCss('font-size', 'data-th-font-size');
  applyNumberCss('width', 'data-th-width');
  applyNumberCss('height', 'data-th-height');
  applyNumberCss('line-height', 'data-th-line-height');
  applyNumberCss('border-radius', 'data-th-radius');
  applyNumberCss('padding', 'data-th-padding');
  applyNumberCss('margin', 'data-th-margin');
  applyNumberCss('letter-spacing', 'data-th-letter-spacing');
  applyNumberCss('top', 'data-th-top');
  applyNumberCss('right', 'data-th-right');
  applyNumberCss('bottom', 'data-th-bottom');
  applyNumberCss('left', 'data-th-left');
  applyNumberCss('max-width', 'data-th-max-width');
  applyNumberCss('min-height', 'data-th-min-height');

  function applyNumberCss(cssProp, attrName) {
    $('[ ' + attrName + '="' + inputId + '" ]').each(function () {
      var unit = $(this).data('th-unit');
      unit = (unit === undefined) ? 'px' : unit;
      $(this).css(cssProp, value + unit);
    });
  }

  /* ---------- ADVANCED PROPERTIES ---------- */

// gap
$('[data-th-gap="' + inputId + '"]').each(function () {
  var unit = $(this).data('th-unit');
  unit = (unit === undefined) ? 'px' : unit;
  $(this).css('gap', value + unit);
});

// z-index
$('[data-th-z-index="' + inputId + '"]').css('z-index', value);

// opacity
$('[data-th-opacity="' + inputId + '"]').css('opacity', value);

// transform: scale
$('[data-th-transform="' + inputId + '"]').css('transform', 'scale(' + value + ')');


}




  // Listen to color picker updates
$(document).on('change keyup', '.color_picker', function () {
  var inputId = this.id;
  var value = $(this).val();
  applyPreview(inputId, value);
});

  // Listen to Number Control updates
$(document).on('input change', 'input[type="number"]', function () {
  if (this.id) {
    applyPreview(this.id, $(this).val());
  }
});



  // Apply saved values on page load
  $(document).ready(function () {
    $('.color_picker').each(function () {
      if (this.id && $(this).val()) {
        applyPreview(this.id, $(this).val());
      }
    });
  });


},

LiveTextPreview: function () {

  function applyText(inputId, value) {

    // TEXT ONLY
    $('[data-th-text="' + inputId + '"]').each(function () {
      $(this).text(value);
    });

    // HTML ALLOWED
    $('[data-th-text-html="' + inputId + '"]').each(function () {
      $(this).html(value);
    });

  }

  // Live typing
  $(document).on('input keyup change', 'input[type="text"], textarea', function () {
    if (!this.id) return;
    applyText(this.id, $(this).val());
  });

  // Apply saved values on page load
  $(document).ready(function () {
    $('input[type="text"], textarea').each(function () {
      if (this.id && $(this).val()) {
        applyText(this.id, $(this).val());
      }
    });
  });

},

LiveCartEffectPreview: function () {

    var $select = $('#taiowc-cart_effect-field');
    var $wrapper = $('.cart-wrapper');

    function applyCartEffect() {
        var selectedClass = $select.val();

        // Remove ONLY dynamic taiowc-* classes
        $wrapper.removeClass(function (index, className) {
            return (className.match(/taiowc-\S+/g) || []).join(' ');
        });

        // Add selected class
        if (selectedClass) {
            $wrapper.addClass(selectedClass);
        }
    }

    // Apply on change
    $(document).on('change', '#taiowc-cart_effect-field', function () {
        applyCartEffect();
    });

    // Apply default selected on page load
    $(document).ready(function () {
        applyCartEffect();
    });

},




        CopyToClipboard: function () {

  $(document).on('click', '.taiowc-copy-btn', function (e) {
    e.preventDefault();

    var $btn = $(this);
    var $box = $btn.closest('.shortcode-box');
    var textToCopy = $box.find('code').text().trim();

    // Modern clipboard
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(textToCopy).then(function () {
        copiedFeedback($btn);
      });
    } else {
      // Fallback for old browsers / WP admin
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
                  $('#taiowc').on('click', '.nav-tab', function (event){
                  event.preventDefault()
                  var target = $(this).data('target')
                  $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active')
                  $('#' + target).show().siblings().hide()
                  $('#_last_active_tab').val(target)
                  if ($("a[data-target='taiowc_style']").hasClass('nav-tab-active')){
                         $('.setting-preview-wrap').show();
                    }else{
                         $('.setting-preview-wrap').hide();
                    }
                  if ($("a[data-target='taiowc_reset']").hasClass('nav-tab-active')){
                        $('a.reset').show();
                        $('button#submit').hide();
                  }else{
                       $('a.reset').hide();
                       $('button#submit').show();

                  }

                  // ===== header title change =====
                  var tabText = $(this).clone().children().remove().end().text().trim();
                  $('.tabheading').text(tabText);
    
                   /* ===== ADD THIS PART ===== */

            // remove old dynamic classes but keep setting-wrap
            $('.setting-wrap').removeClass(function(index, className) {
                return (className.match(/(^|\s)active-tab-\S+/g) || []).join(' ');
            });

            // add new dynamic class to closest setting-wrap
            $('#' + target).closest('.setting-wrap').addClass('active-tab-' + target);

                        /* --------- DYNAMIC PREVIEW (REPLACED PART) --------- */

            var $preview = $('.setting-preview-wrap[data-tab="' + target + '"]');
            var $form = $('.setting-preview-wrap').closest('.taiowc-setting-form');

            // remove old preview classes
          
// remove ONLY dynamic preview classes
$form.removeClass(function (i, cls) {
    return (cls.match(/(^|\s)preview-\S+/g) || []).join(' ');
});
            // hide all previews
            $('.setting-preview-wrap').hide();

            // show matching preview & add dynamic class
            if ($preview.length) {
                $preview.show();
                $form.addClass('preview-' + target);
            }

                });
             });
        },
       
        ColorPickr: function () {
            function myColorPicker() {
              let value_ = this;
              const inputElement = $(value_);
              const defaultColor = inputElement.css("background-color");
              const pickr = new Pickr({
                el: value_,
                useAsButton: true,
                default: defaultColor,
                theme: "nano", // or 'monolith', or 'nano'
                swatches: [
                  "rgba(244, 67, 54, 1)",
                  "rgba(233, 30, 99, 0.95)",
                  "rgba(156, 39, 176, 0.9)",
                  "rgba(103, 58, 183, 0.85)",
                  "rgba(63, 81, 181, 0.8)",
                  "rgba(33, 150, 243, 0.75)",
                  "rgba(255, 193, 7, 1)",
                ],
                components: {
                  preview: true,
                  opacity: true,
                  hue: true,
                  interaction: {
                    input: true,
                  },
                },
              })
                .on("change", (color, instance) => {
                  let color_ = color.toRGBA().toString(0);
                  // preview css on input editor item
                  inputElement.css("background-color", color_);
                  // apply color on selected item
                  inputElement.val(color_);
                  //_this.onColorChangeHandler(inputElement,color_);
                  //save button active
                  $("#submit").removeAttr("disabled");
                })
                .on("init", (instance) => {
                  $(instance._root.app).addClass("visible");
                })
                .on("hide", (instance) => {
                  instance._root.app.remove();
                });
            }
            $(document).on("click", "input.color_picker", myColorPicker);
          },
          
        ImageAdd:function (){
            
           $(document).on('click', '.button.taiowc_upload_image_button', function (event){

                    event.preventDefault();

                    var self = $(this);
                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: self.data('uploader_title'),
                        button: {
                            text: self.data('uploader_button_text'),
                        },
                        multiple: false
                    });
                    file_frame.on('select', function () {
                        attachment = file_frame.state().get('selection').first().toJSON();

                        self.prev('.icon_url').val(attachment.url);
                    });

                    // Finally, open the modal
                    file_frame.open();

                    $('#submit').removeAttr("disabled");

          });
        },
        SaveSetting:function(){
        $(document).on('keyup change paste', '.taiowc-setting-form input, .taiowc-setting-form select, .taiowc-setting-form textarea', function () {
        
              $('#submit').removeAttr("disabled");
              
        });  
        $(document).on("click", ".taiowc-button-wrapper #submit", function (e) {
        e.preventDefault();
        $(this).addClass('loader');
        
        var form_settting = $(".taiowc-setting-form").serialize();
        $.ajax({
          url: taiowcluginObject.ajaxurl,
          type: "POST",
          data: form_settting +'&_wpnonce=' + taiowcluginObject.nonce +'',
          success: function (response) {
           
            $('#submit').removeClass('loader');
            $('#submit').attr("disabled","disabled");

          },
        });
      });
    },
    ChangeSettinghideshow:function(){
        
           $(document).on('click', '#taiowc-show_cart-field', function (event){

                    if($(this).is(':checked')){

                      $('#cart_style-wrapper, #taiowc-cart_open-wrapper, #taiowc_cart_styletaiowc_cart_style-section-1, .taiowc_cart_styletaiowc_cart_style-section-1').show(500);

                    }else{

                      $('#cart_style-wrapper, #taiowc-cart_open-wrapper, #taiowc_cart_styletaiowc_cart_style-section-1, .taiowc_cart_styletaiowc_cart_style-section-1').hide(500);

                   }
                   
             });

           $(document).on('click', '#cart_style', function (event){

                    if($("input[id=cart_style]:checked").val() == "style-1"){

                      if($('#taiowc-fxd_cart_position-field').find("option:selected").val() == "fxd-left"){

                          $('#taiowc-fxd_cart_frm_left-wrapper,#taiowc-fxd_cart_frm_btm-wrapper').show(500);
                          $('#taiowc-fxd_cart_frm_right-wrapper').hide(500);

                      }else{

                         $('#taiowc-fxd_cart_frm_right-wrapper,#taiowc-fxd_cart_frm_btm-wrapper').show(500);
                         $('#taiowc-fxd_cart_frm_left-wrapper').hide(500);
                      }
                      
             
                    }else{

                      $('#taiowc-fxd_cart_frm_right-wrapper,#taiowc-fxd_cart_frm_left-wrapper,#taiowc-fxd_cart_frm_btm-wrapper').hide(500);

                   }
                   
             });

           $(document).on('change', '#taiowc-fxd_cart_position-field', function (event){

                    if($(this).find("option:selected").val() == "fxd-left"){

                       if($("input[id=cart_style]:checked").val() == "style-1"){
                          $('#taiowc-fxd_cart_frm_left-wrapper').show(500);
                          $('#taiowc-fxd_cart_frm_btm-wrapper').show(500);
                          $('#taiowc-fxd_cart_frm_right-wrapper').hide(500);
                        }

                    }else{

                          if($("input[id=cart_style]:checked").val() == "style-1"){
                          $('#taiowc-fxd_cart_frm_left-wrapper').hide(500);
                          $('#taiowc-fxd_cart_frm_right-wrapper').show(500);
                          $('#taiowc-fxd_cart_frm_btm-wrapper').show(500);
                        }
                        

                   }
                   
             });

           $(document).on('click', '#taiowc-cart-icon', function (event){

                    if($("input[id=taiowc-cart-icon]:checked").val() == "icon-7"){

                      $('#icon_url-wrapper').show(500);

                    }else{

                      $('#icon_url-wrapper').hide(500);

                   }
                   
             });

       
            $(document).on('click', '#taiowc-cart_pan_icon_shw-field', function (event){

                    if($(this).is(':checked')){

                      $('#taiowc-cart_pan_icon_clr-wrapper').show(500);

                    }else{

                      $('#taiowc-cart_pan_icon_clr-wrapper').hide(500);

                   }
                   
             });

            $('input#cart_style[value="style-2"]').attr("disabled", true);
            $('#taiowc-cart_effect-field option[value="taiowc-slide-left"]').attr("disabled", true);
            $('#taiowc-cart_effect-field option[value="taiowc-click-dropdown"]').attr("disabled", true);
            $('#taiowc-cart_item_order-field option[value="prd_last"]').attr("disabled", true);
            $('#taiowc-cart_open-field option[value="fly-image-open"]').attr("disabled", true);
            $('input#taiowc-cart-icon[value="icon-2"], input#taiowc-cart-icon[value="icon-3"], input#taiowc-cart-icon[value="icon-4"], input#taiowc-cart-icon[value="icon-5"], input#taiowc-cart-icon[value="icon-6"], input#taiowc-cart-icon[value="icon-7"]').attr("disabled", true);

        },
  

}
TAIOWCsettingLib.init();
})(jQuery);


jQuery(function ($) {

    function taiowc_move_premium_outside_form() {

        $('.taiowc-setting-form .th-premium-box').each(function () {

            var $box = $(this);

            // already moved? skip
            if ($box.data('moved-outside')) return;

            var $form = $box.closest('form.taiowc-setting-form');
            var $header = $form.prevAll('.top-header').first();

            if ($header.length) {
                $box.insertAfter($header);
                $box.data('moved-outside', true);
            }
        });
    }

    // on load
    taiowc_move_premium_outside_form();

    // tabs / ajax reload
    $(document).on('click', '.nav-tab', function(){
        setTimeout(taiowc_move_premium_outside_form, 120);
    });

    $(document).ajaxComplete(function(){
        taiowc_move_premium_outside_form();
    });

});