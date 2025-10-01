(function ($) {
  const TH = {
    init: function () {
      TH.bind();
      TH.tab();
      TH.mainTabheader();
      TH.copyToClip();
    },
     mainTabheader: function () {
      jQuery(document).ready(function($) {
    // When the page loads or a new tab is clicked
    $('.th-nav_ a').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        
        // Get the content of the active link
        var activeContent = $('.th-nav_ a.active').text(); // You can also use .html() if you need the HTML content

        // Replace the content inside .tabheading with the active link content
        $('.tabheading').text(activeContent);
    });
});

    },
         copyToClip: function () {

            $('.th-copy-btn').on('click', function () {

                  var button = $(this);
                  var targetId = button.data('copy-target');
                  var textToCopy = $('#' + targetId).text();

                  // Create temporary input
                  var tempInput = $('<input>');
                  $('body').append(tempInput);
                  tempInput.val(textToCopy).select();

                  document.execCommand('copy');
                  tempInput.remove();

                  // UI Feedback
                  button.text('Copied!');
                  button.addClass('copied');

                  setTimeout(function () {
                      button.text('Copy');
                      button.removeClass('copied');
                  }, 1500);

              });

    },

    tab: function () {
      $("[data-group-tabs][data-tab]").click(function (e) {
        e.preventDefault();
        let BTN = $(this);
        let getTAbGRoup = BTN.attr("data-group-tabs");
        let getTAbSingle = BTN.attr("data-tab");
        let TABgorup = '[data-group-tabs="' + getTAbGRoup + '"]';
        $(TABgorup).removeClass("active");
        $(TABgorup + '[data-tab="' + getTAbSingle + '"]').addClass("active");
        $(TABgorup + '[data-tab-container="' + getTAbSingle + '"]').addClass(
          "active"
        );
      });
    },
    saveFN: function (inputs) {
      // console.log("inputs->", inputs);
      let returnSave = { attributes: {} };
      $.each(inputs, (ind_, val_) => {
        let Input_ = $(val_);
        let inputName = Input_.attr("data-th-save");
        if (inputName == "compare-field") {
          let inputVal = Input_.val();
          let save_ = "field-" + inputVal;
          if (Input_.prop("checked") == true) {
            returnSave[save_] = 1;
          } else {
            returnSave[save_] = "hide";
          }
        } else if (inputName == "compare-attributes") {
          let inputVal = Input_.val();
          returnSave["attributes"][inputVal] = {};
          if (Input_.prop("checked") == true) {
            returnSave["attributes"][inputVal]["active"] = 1;
          } else {
            returnSave["attributes"][inputVal]["active"] = 0;
          }
          if (Input_.attr("data-custom-attr") == 1) {
            returnSave["attributes"][inputVal]["custom"] = 1;
            returnSave["attributes"][inputVal]["label"] =
              Input_.siblings("label").html();
          }
        } else if (val_.tagName == "SELECT" || val_.tagName == "INPUT") {
          let inputVal = Input_.val();
          returnSave[inputName] = inputVal;
        }
      });
      return returnSave;
      // console.log("val_", val_);
    },
    saveData: function () {
      let thisBTN = $(this);
      let thContainer = thisBTN.closest(".th-product-compare-wrap");
      let inputs = thContainer.find(".container-tabs").find("[data-th-save]");
      thisBTN.addClass("loading");
      let sendData = TH.saveFN(inputs);
      // console.log("sendData", sendData);
      // return;
      $.ajax({
        method: "post",
        url: th_product.th_product_ajax_url,
        data: {
          action: "th_compare_save_data",
          inputs: sendData,
          nonce: th_product.th_product_compare_nonce,
        },
        success: function (response) {
          if (response.trim() === "update") {
            thisBTN.removeClass("loading");
          }
        },
      });
    },
    resetStyle: function () {
      let btn = $(this);
      btn.addClass("loading");
      $.ajax({
        method: "post",
        url: th_product.th_product_ajax_url,
        data: {
          action: "th_compare_reset_data",
          inputs: "reset",
          nonce: th_product.th_product_compare_nonce,
        },
        success: function (response) {
          if (response == "reset") {
            setTimeout(() => {
              location.reload();
            }, 500);
          } else {
            location.reload();
          }
        },
      });
    },
    bind: function () {
      $(document).on("click", ".th-option-save-btn", TH.saveData);
      $(document).on("click", ".th-compare-reset-style-btn", TH.resetStyle);
    },
  };
  TH.init();
})(jQuery);
