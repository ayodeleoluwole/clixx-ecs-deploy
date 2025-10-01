jQuery(document).ready(function(){
//redirect
//above-header
jQuery( '.focus-customizer-menu-redirect-col1,.focus-customizer-menu-redirect-col2,.focus-customizer-menu-redirect-col3' ).on( 'click', function (e){
            e.preventDefault();
            wp.customize.panel('nav_menus').focus();
} );
jQuery( '.focus-customizer-widget-redirect-col1,.focus-customizer-widget-redirect-col2,.focus-customizer-widget-redirect-col3,.focus-customizer-widget-redirect' ).on( 'click', function (e){
            e.preventDefault();
            wp.customize.panel( 'widgets' ).focus();
} );
jQuery( '.focus-customizer-social_media-redirect-col1,.focus-customizer-social_media-redirect-col2,.focus-customizer-social_media-redirect-col3' ).on( 'click', function (e){
            e.preventDefault();
            wp.customize.section( 'open-shop-social-icon' ).focus();
} ); 

/* === Checkbox Multiple Control === */
    jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on(
        'change',
        function() {
   // alert('');
            checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );

// section sorting
      jQuery( "#sortable" ).sortable({
        placeholder: "ui-sortable-placeholder", 
        cursor: 'move',
        opacity: 0.65,
        stop: function ( event, ui){
        var data = jQuery(this).sortable('toArray');
            //  console.log(data); // This should print array of IDs, but returns empty string/array      
            jQuery( this ).parents( '.customize-control').find( 'input[type="hidden"]' ).val( data ).trigger( 'change' );
        }
    });

// Add class to control
jQuery('#customize-control-open_shop_above_header_layout,#customize-control-open_shop_above_footer_layout').addClass("lite");   
//Disable Header Layout, radio image
jQuery('input[id=open_shop_main_header_layout-mhdrone], input[id=open_shop_main_header_layout-mhdrtwo], input[id=open_shop_main_header_layout-mhdrthree], input[id=open_shop_top_slide_layout-slide-layout-2], input[id=open_shop_top_slide_layout-slide-layout-3], input[id=open_shop_top_slide_layout-slide-layout-4], input[id=open_shop_cat_slide_layout-cat-layout-2], input[id=open_shop_cat_slide_layout-cat-layout-3],input[id=open_shop_banner_layout-bnr-two],input[id=open_shop_banner_layout-bnr-four],input[id=open_shop_banner_layout-bnr-five],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-one],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-two],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-three],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-five],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-six],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-seven],input[id=open_shop_bottom_footer_widget_layout-ft-wgt-eight]').attr("disabled",true);
jQuery('input[id=open_shop_above_header_layout-abv-none],input[id=open_shop_above_header_layout-abv-one],input[id=open_shop_above_header_layout-abv-three],input[id=open_shop_top_slide_layout-slide-layout-5]').attr("disabled",true);

//Disable select option
jQuery('#_customize-input-open_shop_category_optn option[value="featured"],#_customize-input-open_shop_category_optn option[value="random"],#_customize-input-open_shop_woo_product_animation option[value="swap"],#_customize-input-open_shop_pagination option[value="scroll"],#_customize-input-open_shop_pagination option[value="click"],#_customize-input-open_shop_main_header_option option[value="button"],#_customize-input-open_shop_main_header_option option[value="widget"], #_customize-input-open_shop_blog_post_pagination option[value="click"], #_customize-input-open_shop_blog_post_pagination option[value="scroll"],#_customize-input-open_shop_blog_post_content option[value="full"],#_customize-input-open_shop_blog_post_content option[value="nocontent"]').attr("disabled", true);

//Disable select input, toggle
jQuery('#customize-control-open_shop_blog_read_more_txt input, #customize-control-open_shop_blog_expt_length input,#customize-control-open_shop_disable_top_slider_sec input,#customize-control-open_shop_disable_highlight_sec input').attr("disabled",true);

//Disable select input, toggle plugin
jQuery('#customize-control-open_shop_disable_cat_sec input,#customize-control-open_shop_single_row_slide_cat input,#customize-control-open_shop_disable_product_slide_sec input,#customize-control-open_shop_single_row_prdct_slide input,#customize-control-open_shop_disable_ribbon_sec input,#customize-control-open_shop_disable_product_list_sec input,#customize-control-open_shop_disable_category_slide_sec input,#customize-control-open_shop_single_row_prdct_list input,#customize-control-open_shop_disable_banner_sec input,#customize-control-open_shop_top_slider_optn input').attr("disabled",true);
jQuery('#customize-control-open_shop_category_tab_list li input').attr("disabled",true);
jQuery('#customize-control-open_shop_category_tab_list li:nth-of-type(1) input,#customize-control-open_shop_category_tab_list li:nth-of-type(2) input,#customize-control-open_shop_category_tab_list li:nth-of-type(3) input').attr("disabled",false);
jQuery('#customize-control-open_shop_category_slide_list li input').attr("disabled",true);
jQuery('#customize-control-open_shop_category_slide_list li:nth-of-type(1) input,#customize-control-open_shop_category_slide_list li:nth-of-type(2) input,#customize-control-open_shop_category_slide_list li:nth-of-type(3) input,#customize-control-open_shop_category_slide_list li:nth-of-type(4) input').attr("disabled",false);
jQuery('#customize-control-open_shop_header_category_list li input').attr("disabled",true);
jQuery('#customize-control-open_shop_header_category_list li:nth-of-type(1) input,#customize-control-open_shop_header_category_list li:nth-of-type(2) input,#customize-control-open_shop_header_category_list li:nth-of-type(3) input,#customize-control-open_shop_header_category_list li:nth-of-type(4) input,#customize-control-open_shop_header_category_list li:nth-of-type(5) input').attr("disabled",false);

jQuery('#_customize-input-open_shop_product_slider_cat option,#_customize-input-open_shop_product_list_cata option').attr("disabled",true);
jQuery('#_customize-input-open_shop_product_slider_cat option:nth-of-type(1),#_customize-input-open_shop_product_list_cata option:nth-of-type(1)').attr("disabled",false);
jQuery('input[id=open_shop_banner_layout-bnr-one]').attr("disabled",true);


jQuery('#_customize-input-open_shop_above_header_col1_set option[value="none"],#_customize-input-open_shop_above_header_col1_set option[value="menu"],#_customize-input-open_shop_above_header_col1_set option[value="widget"],#_customize-input-open_shop_above_header_col1_set option[value="social"],#_customize-input-open_shop_above_header_col2_set option[value="none"],#_customize-input-open_shop_above_header_col2_set option[value="text"],#_customize-input-open_shop_above_header_col2_set option[value="menu"],#_customize-input-open_shop_above_header_col2_set option[value="widget"]').attr("disabled",true);
jQuery('#customize-control-open_shop_above_mobile_disable input,#customize-control-open_shop_rtl input').attr("disabled",true);
jQuery('#customize-control-open_shop_shadow_header input,#customize-control-open_shop_sticky_header input,#customize-control-open_shop_whislist_mobile_disable input,#customize-control-open_shop_account_mobile_disable input,#customize-control-open_shop_cart_mobile_disable input,input[id=open_shop_above_footer_layout-ft-abv-none],input[id=open_shop_above_footer_layout-ft-abv-one],input[id=open_shop_above_footer_layout-ft-abv-three]').attr("disabled",true);

jQuery('#customize-control-open_shop_top_slide_content .button.add_field,#customize-control-open_shop_highlight_content .button.add_field').remove();
jQuery('#customize-control-open_shop_top_slide_content,#customize-control-open_shop_highlight_content').append("<h4>(To Add More Slides Feature Available In Pro Version)</h4>");

jQuery('#customize-control-open_shop_header_category_list,#customize-control-open_shop_product_slider_cat,#customize-control-open_shop_product_list_cata').append("<h4>(To Select More Feature Available In Pro Version)</h4>");

});


( function( $ ) {
   

      //footer top
      wp.customize.bind( 'ready', function() {
        $('#customize-control-open_shop_footer_col1_widget_redirect .menu-shortcut').on('click', function() {
            // This opens the Widgets panel and focuses on your specific sidebar section
            wp.customize.section('sidebar-widgets-footer-top-first').expand();
        });
    });

     wp.customize.bind( 'ready', function() {
        $('#customize-control-open_shop_above_footer_col2_widget_redirect .menu-shortcut').on('click', function() {
            // This opens the Widgets panel and focuses on your specific sidebar section
            wp.customize.section('sidebar-widgets-footer-top-second').expand();
        });
    });


})( jQuery );
