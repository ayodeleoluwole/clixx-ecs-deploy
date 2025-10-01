(function ($) {
  let thCompare = {
    init: function () {
      thCompare.bind();
      thCompare.containerScroll();
      if (!$(".th-compare-output-wrap-inner").length) {
        thCompare.openAndaddPopup("refresh", false);
      }
      thCompare.popupOpener();
    },
       updateCompareEnableState: function () {
  let count = $(".product_image > div.img_").not(".empty-slot").length;
  let compareBtn = $(".th-compare-enable");

  if (count > 1) {
    compareBtn.addClass("active").removeClass("disabled");
  } else {
    compareBtn.removeClass("active").addClass("disabled");
  }
},
    popupOpener: function () {
      $(document).on("click", ".th-footer-up-down", function () {
        let button = $(this);
        button.closest(".th-compare-footer-wrap").toggleClass("active");
      });
      $(document).on("click", ".th-compare-footer-product-opner", function (e) {
        e.preventDefault();
        let button = $(this);
        button.toggleClass("active");
        let popupWrap = $(".th-compare-output-wrap");
        if (popupWrap.hasClass("active")) {
          popupWrap
            .addClass("th-compare-output-wrap-close")
            .removeClass("active");
          $("body").removeClass("th_product_Compare_body_Class");
        } else {
          popupWrap
            .removeClass("th-compare-output-wrap-close")
            .addClass("active");
          $("body").addClass("th_product_Compare_body_Class");
        }
      });
    },
    addProduct: function (e) {
      e.preventDefault();
      let thisElement = $(this);
      let getProductId = thisElement.attr("data-th-product-id");
      if (parseInt(getProductId)) {
        let action = thisElement.hasClass("th-added-compare") ? "remove" : "add";
        thisElement.addClass("loading");
        if (thisElement.closest(".th-product-compare-checkbox-wrap, .thunk-compare").hasClass("th-single-page")) {
          thCompare.getOrUpdatePRoducts(getProductId, action, thisElement);
        } else {
          thCompare.openAndaddPopup(getProductId, thisElement, action);
        }
      }
    },
    removeProduct: function (e) {
      e.preventDefault();
      let thisBtn = $(this);
      let getProductId = thisBtn.attr("data-th-product-id");
      if (parseInt(getProductId)) {
        thisBtn
          .closest(".th-compare-output-wrap-inner")
          .stop()
          .animate({ scrollTop: 0 }, 500, "swing");
        thisBtn.closest(".th-compare-output-wrap").addClass("th-loading");
        thCompare.getOrUpdatePRoducts(getProductId, "remove", thisBtn);
      }
    },
    openAndaddPopup: function (ids = "", thisElement, action = "add") {
      let ExistCompare = $("#th-compare-output-wrap");
      if (ExistCompare.length) {
        ExistCompare.remove();
      }
      let html = '<div class="thcompare-open-by-popup">';
      html += '<div class="th-compare-output-wrap th-loading" id="th-compare-output-wrap">';
      html += '<div class="th-compare-output-wrap-inner">';
      html += '<div class="th-compare-heading">';
      html += "<span class='heading_'>COMPARE PRODUCTS</span>";
      html += "<span class='error_'></span>";
      html += "<div class='th-compare-output-close'><i class='dashicons dashicons-no-alt'></i></div>";
      html += "</div>";
      html += thCompare.loaderIcon();
      html += '<div class="th-compare-output-product">';
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += "</div>";
      if ($(".thcompare-open-by-popup").length) {
        $(".thcompare-open-by-popup").remove();
      }
      $("body").append(html);
      thCompare.getOrUpdatePRoducts(ids, action, thisElement);
    },
    getOrUpdatePRoducts: function (ids, action_, thisElement = false) {
      $(".thcompare-open-by-popup .th-compare-output-wrap").addClass("th-loading");
      $(".th-compare-footer-wrap").addClass("loading");
      $.ajax({
        method: "post",
        url: th_product.th_product_ajax_url,
        data: {
          action: "th_get_compare_product",
          product_id: ids,
          add_remove: action_,
          nonce: th_product.nonce
        },
        dataType: "json",
        success: function (response) {
          if (thisElement) {
            thisElement.removeClass("loading");
            if (action_ === "add") {
              thisElement.addClass("th-added-compare");
              if (thisElement.hasClass("th-product-compare-checkbox")) {
                thisElement.prop("checked", true);
              }
            } else {
              thisElement.removeClass("th-added-compare");
              if (thisElement.hasClass("th-product-compare-checkbox")) {
                thisElement.prop("checked", false);
              }
              // Update all instances of this product
              $("[data-th-product-id='" + ids + "'].th-product-compare-checkbox").removeClass("th-added-compare").prop("checked", false);
              $("[data-th-product-id='" + ids + "'].th-product-compare-btn").removeClass("th-added-compare");
            }
          }
          if (response.no_product == "1") {
            let getWrap = $(".thcompare-open-by-popup");
            getWrap.find(">div").addClass("th-compare-output-wrap-close");
            setTimeout(() => {
              getWrap.remove();
              $(".th-compare-footer-wrap").remove();
              $(".th-add-more-product-container").remove();
            }, 500);
            $("body").removeClass("th_product_Compare_body_Class");
            // Clear all checkboxes and icons
            $(".th-product-compare-checkbox").removeClass("th-added-compare").prop("checked", false);
            $(".th-product-compare-btn").removeClass("th-added-compare");
          } else {
            $(".thcompare-open-by-popup .th-compare-output-product").html(response.html);

            // Mobile flex: sync scroll and set product-count classes
            var $mobileTable = $(".product-table-configure.mobile-flex");
            if ($mobileTable.length) {
              var productCount = $mobileTable.find("tr:first td:not(.left-title)").length;
              if (productCount > 3) {
                $mobileTable.addClass("_has-scroll");
              } else {
                $mobileTable.removeClass("_has-scroll");
              }
              if (productCount === 2) {
                $mobileTable.addClass("_two-products");
              } else {
                $mobileTable.removeClass("_two-products");
              }
              // Sync horizontal scroll across all rows
              var syncScrollRAF = null;
              var isSyncing = false;
              var allRows = $mobileTable.find("tr").toArray();
              allRows.forEach(function (row) {
                row.addEventListener("scroll", function () {
                  if (isSyncing) return;
                  var source = this;
                  var sl = source.scrollLeft;
                  if (syncScrollRAF) cancelAnimationFrame(syncScrollRAF);
                  syncScrollRAF = requestAnimationFrame(function () {
                    isSyncing = true;
                    for (var i = 0; i < allRows.length; i++) {
                      if (allRows[i] !== source) {
                        allRows[i].scrollLeft = sl;
                      }
                    }
                    isSyncing = false;
                    syncScrollRAF = null;
                  });
                }, { passive: true });
              });
            }

            if (response.footer_bar && response.footer_bar != "") {
              let footer_bar = $(".th-compare-footer-wrap");
              if (footer_bar.length) {
                footer_bar.remove();
              }
              $("body").append(response.footer_bar);
              $(".th-compare-footer-wrap > div").append(thCompare.loaderIcon());
              // Check if product_image contains any product-comp
              let productImage = $(".th-compare-footer-wrap .product_image");
              if (productImage.find(".product-comp").length === 0) {
                
                  $(".thcompare-open-by-popup").remove();
                  $(".th-compare-footer-wrap").remove();
                  $(".th-add-more-product-container").remove();
                  $("body").removeClass("th_product_Compare_body_Class");
                  $(".th-product-compare-checkbox").removeClass("th-added-compare").prop("checked", false);
                  $(".th-product-compare-btn").removeClass("th-added-compare");
                
              } 
              else if (parseInt(ids)) {
                setTimeout(() => {
                  $(".th-compare-footer-wrap").addClass("active");
                }, 500);
              }
            }
            if (response.product_limit) {
              $(".th-compare-heading .error_").html(response.product_limit);
            }
            thCompare.containerScroll();
            $(".thcompare-open-by-popup .th-compare-output-wrap").removeClass("th-loading");
          }
          thCompare.updateCompareEnableState();
        },
        error: function () {
          if (thisElement) {
            thisElement.removeClass("loading");
          }
          $(".thcompare-open-by-popup .th-compare-output-wrap").removeClass("th-loading");
          $(".th-compare-footer-wrap").removeClass("loading");
        }
      });
    },
    removeCompare: function () {
      let closebtn = $(this);
      let getWrap = closebtn.closest(".thcompare-open-by-popup");
      getWrap
        .find(".th-compare-output-wrap")
        .addClass("th-compare-output-wrap-close")
        .removeClass("active");
      $(".th-compare-footer-product-opner").removeClass("active");
      $("body").removeClass("th_product_Compare_body_Class");
      // Update all checkboxes and icons
      $(".th-product-compare-checkbox").removeClass("th-added-compare").prop("checked", false);
      $(".th-product-compare-btn").removeClass("th-added-compare");
    },
    addMorePopup: function (e) {
      e.preventDefault();
      let existContainer = $(".th-add-more-product-container");
      if (!existContainer.length) {
        let popupAddMore = '<div class="th-add-more-product-container active">';
        popupAddMore += '<div class="th-add-more-product-inner">';
        popupAddMore += '<div class="th-search-product">';
        popupAddMore += '<span class="remove_search_popup dashicons dashicons-no"></span>';
        popupAddMore += '<div class="th-search-product-input">';
        popupAddMore += '<input type="text" placeholder="Product Title...">';
        popupAddMore += "</div>";
        popupAddMore += '<div class="th-search-product-input-result">';
        popupAddMore += "</div>";
        popupAddMore += "</div>";
        popupAddMore += "</div>";
        popupAddMore += "</div>";
        $("body").append(popupAddMore);
        thCompare.footerSEarch();
        thCompare.removeAddmorePopup();
      } else {
        existContainer.addClass("active");
      }
    },
    removeAddmorePopup: function () {
      $(document).mouseup(function (e) {
        let searchWrap = $(".th-add-more-product-container");
        let inner = searchWrap.find(".th-add-more-product-inner");
        if (!inner.is(e.target) && inner.has(e.target).length === 0) {
          searchWrap.removeClass("active");
        }
      });
      $(document).on("click", ".remove_search_popup", function () {
        let button_ = $(this);
        let searchWrap = button_.closest(".th-add-more-product-container");
        searchWrap.removeClass("active");
      });
    },
    footerSEarch: function () {
      let container_ = $(".th-add-more-product-container");
      if (container_.length) {
        container_.find(".th-search-product-input >input").keyup(function () {
          let this_input = $(this);
          thCompare.addPRoduct(this_input);
        });
      }
    },
    addPRoduct: function (thisInput, type = "keyup") {
      let addProductWrap = thisInput.closest(".th-add-more-product-inner");
      let putResult = addProductWrap.find(".th-search-product-input-result");
      let added_product_id = $(".th-compare-footer-wrap .product_image");
      addProductWrap.addClass("loading");
      let value_ = thisInput.val();
      value_ = value_.length > 1 ? value_ : "";
      let timeOutTimer = type == "keyup" ? 100 : 0;
      setTimeout(() => {
        $.ajax({
          method: "post",
          url: th_product.th_product_ajax_url,
          data: {
            action: "th_compare_filter_product",
            inputs: value_,
            nonce: th_product.nonce
          },
          dataType: "json",
          success: function (response) {
            if (response.success && response.data && response.data.length > 0) {
              let list_ = "";
              response.data.map((key_) => {
                let existId = added_product_id.find(
                  '[data-product-id="' + key_.id + '"]'
                );
                let list_class = existId.length
                  ? "th-compare-external-popup-open checked"
                  : "th-compare-external-popup-open";
                let Wrapper_ =
                  '<span class="' + list_class + '" data-id="' + key_.id + '">';
                if (key_.image_url != 0) {
                  Wrapper_ +=
                    "<div class='img_'><img src='" +
                    key_.image_url +
                    "'></div>";
                }
                Wrapper_ += "<span>" + key_.label + "</span>";
                Wrapper_ += "</span>";
                list_ += Wrapper_;
              });
              putResult.html(list_);
              addProductWrap.removeClass("loading");
            }
          },
        });
      }, timeOutTimer);
    },
    addMorePopupClick: function (e) {
      let thisBtn_ = $(this);
      let productId = thisBtn_.attr("data-id");
      if (productId) {
        thCompare.getOrUpdatePRoducts(productId, "add", thisBtn_);
        thisBtn_.addClass("checked");
        $("[data-th-product-id='" + productId + "'].th-product-compare-checkbox").addClass("th-added-compare").prop("checked", true);
        $("[data-th-product-id='" + productId + "'].th-product-compare-btn").addClass("th-added-compare");
      }
    },
    bind: function () {
      $(document).on("change", ".th-product-compare-checkbox", thCompare.addProduct);
      $(document).on("click", ".th-product-compare-btn", thCompare.addProduct);
      $(document).on(
        "click",
        ".th-compare-product-remove[data-th-product-id]",
        thCompare.removeProduct
      );
      $(document).on(
        "click",
        ".thcompare-open-by-popup .th-compare-output-wrap .th-compare-output-close",
        thCompare.removeCompare
      );
      $(document).on("click", ".th-add-product-bar", thCompare.addMorePopup);
      $(document).on(
        "click",
        ".th-compare-external-popup-open",
        thCompare.addMorePopupClick
      );
    },
    containerScroll: function () {
      let heading_ = $(".th-compare-heading");
      if (heading_.length) {
        let height_ = heading_.outerHeight();
        $(".th-compare-output-wrap .th-compare-output-wrap-inner").css(
          "padding-top",
          height_ + "px"
        );
      }
      const win_ = $(window);
      let getWindowWidth = win_.innerWidth();
      if (getWindowWidth < 450) {
        $(
          ".th-compare-output-wrap .th-compare-output-wrap-inner,.th-compare-footer-wrap"
        ).addClass("th-mobile-view");
      }
      let container_ = $(".th-compare-output-product");
      let containerTable = $(".th-compare-output-product > table").innerWidth();
      if (containerTable > getWindowWidth) {
        container_.css("cursor", "grab");
      }
      if (!$(".th-mobile-view").length) {
        container_.scroll(function (event) {
          let container = $(this);
          let getLeft = container.scrollLeft();
          getLeft = getLeft > 1 ? getLeft - 1 : getLeft;
          container.find("td.left-title").css("left", getLeft + "px");
        });
      }
      const slider = document.querySelector(".th-compare-output-product");
      if (slider) {
        let isDown = false;
        let startX;
        let scrollLeft;
        slider.addEventListener("mousedown", (e) => {
          isDown = true;
          slider.classList.add("active");
          startX = e.pageX - slider.offsetLeft;
          scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener("mouseleave", () => {
          isDown = false;
          slider.classList.remove("active");
        });
        slider.addEventListener("mouseup", () => {
          isDown = false;
          slider.classList.remove("active");
        });
        slider.addEventListener("mousemove", (e) => {
          if (!isDown) return;
          e.preventDefault();
          const x = e.pageX - slider.offsetLeft;
          const walk = (x - startX) * 3;
          slider.scrollLeft = scrollLeft - walk;
        });
      }
    },
    loaderIcon: function () {
      return '<div class="th-compare-loader"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 32 32" height="32" viewBox="0 0 32 32" width="32"><path d="m16 6.001v2.999c0 .369.203.708.527.883.147.078.311.118.473.118.193 0 .387-.057.555-.168l6-4.001c.278-.186.445-.497.445-.831 0-.335-.167-.646-.445-.832l-6-4c-.307-.206-.703-.225-1.025-.05-.327.174-.53.513-.53.882v3c-6.617 0-12 5.383-12 12 0 .552.448 1 1 1s1-.448 1-1c0-5.515 4.486-10 10-10zm2-3.132 3.197 2.132-3.197 2.131zm9 12.132c-.552 0-1 .447-1 1 0 5.516-4.486 10-10 10v-3.001c0-.369-.203-.707-.528-.882s-.72-.155-1.026.05l-6 4c-.279.186-.446.498-.446.832s.167.646.445.832l6 4c.168.111.361.168.555.168.162 0 .324-.039.472-.118.325-.174.528-.513.528-.882v-3c6.617 0 12-5.383 12-11.999 0-.553-.448-1-1-1zm-13 14.13-3.197-2.131 3.197-2.131z" fill="#222"/></svg></div>';
    }
  };
  thCompare.init();
})(jQuery);