<?php if ( ! defined( 'ABSPATH' ) ) exit;
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
/****************/
//Block registered
/****************/

function taiowc_register_blocks() {
    $blocks = array(
        array(
            'name'           => 'taiowc/taiowc',
            'script_handle'  => 'taiowc',
            'editor_style'   => 'taiowc-editor-style',
            'frontend_style' => 'taiowc-frontend-style',
            'render_callback' => 'taiowc_blocks_render_callback',
            'localize_data'  => array(
                'adminUrltaiowc' => admin_url('admin.php?page=taiowc'),
            ),
        ),
    );
  
    foreach ( $blocks as $block ) {
        // Register JavaScript file
        wp_register_script(
        $block['script_handle'],
        TAIOWC_PLUGIN_URI . 'build/' . $block['script_handle'] . '.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( TAIOWC_PLUGIN_PATH . '/build/' . $block['script_handle'] . '.js' ),
        array( 'strategy' => 'defer' )
       );
  
        // Register editor style
        wp_register_style(
            $block['editor_style'],
            TAIOWC_PLUGIN_URI . 'build/' . $block['script_handle'] . '.css',
            array( 'wp-edit-blocks' ),
            filemtime( TAIOWC_PLUGIN_PATH . '/build/' . $block['script_handle'] . '.css' )
        );
  
        // Register front end block style
        wp_register_style(
            $block['frontend_style'],
            TAIOWC_PLUGIN_URI . 'build/style-' . $block['script_handle'] . '.css',
            array(),
            filemtime( TAIOWC_PLUGIN_PATH . '/build/style-' . $block['script_handle'] . '.css' )
        );
  
        // Localize the script with data
        if ( isset( $block['localize_data'] ) && ! is_null( $block['localize_data'] ) ) {
            wp_localize_script(
                $block['script_handle'],
                'ThBlockDatataiowc',
                $block['localize_data']
            );
        }
  
        // Prepare the arguments for registering the block
        $block_args = array(
            'editor_script'   => $block['script_handle'],
            'editor_style'    => $block['editor_style'],
            'style'           => $block['frontend_style'],
        );
  
        // Check if the render callback is set and not null
        if ( isset( $block['render_callback'] ) && ! is_null( $block['render_callback'] ) ) {
            $block_args['render_callback'] = $block['render_callback'];
           
        }
  
        // Register each block
        register_block_type( $block['name'], $block_args );
    }
    
  }
  
  add_action( 'init', 'taiowc_register_blocks' );
  
  function taiowc_blocks_categories( $categories ) {
    return array_merge(
        
        [
            [
                'slug'  => 'vayu-blocks',
                'title' => __( 'ThemeHunk', 'th-all-in-one-woo-cart' ),
            ],
        ],
        $categories
    );
  }

  if ( !is_plugin_active( 'vayu-blocks/vayu-blocks.php' ) ) {
  add_filter( 'block_categories_all', 'taiowc_blocks_categories', 11, 2);
  }
  
  function taiowc_blocks_editor_assets(){
    if ( is_admin() ) {
    
    wp_enqueue_style(
        'taiowc-component-editor-css',
        TAIOWC_PLUGIN_URI . 'build/component-editor.css',
         array(),
         TAIOWC_VERSION,
    );
   }
   
    wp_enqueue_style(
        'th-icon-css',
        TAIOWC_PLUGIN_URI . 'th-icon/style.css',
        array(),
        TAIOWC_VERSION
    );
    
      
  }
  add_action( 'enqueue_block_assets', 'taiowc_blocks_editor_assets' );
  
  
   /**
    * Sanitize a CSS unit value against a whitelist.
    */
   function taiowc_sanitize_css_unit( $unit ) {
       $allowed_units = array( 'px', 'em', 'rem', '%', 'vw', 'vh' );
       return in_array( $unit, $allowed_units, true ) ? $unit : 'px';
   }

   /**
    * Sanitize a CSS color value.
    */
   function taiowc_sanitize_css_color( $color ) {
       // Allow hex, rgb, rgba, hsl, hsla, and named colors
       if ( preg_match( '/^(#[0-9a-fA-F]{3,8}|rgb\(|rgba\(|hsl\(|hsla\(|[a-zA-Z]+$)/', $color ) ) {
           return sanitize_text_field( $color );
       }
       return '';
   }

   function taiowc_blocks_render_callback( $attr ) {

   $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
   if ( $screen && method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) {
        return;
    }


    $taiowBlockStyle = '';
    $paddingUnit = taiowc_sanitize_css_unit( isset($attr['paddingUnit']) ? $attr['paddingUnit'] : 'px' );
    $marginUnit = taiowc_sanitize_css_unit( isset($attr['marginUnit']) ? $attr['marginUnit'] : 'px' );

    // desktop
    if (isset($attr['paddingType']) && 'unlinked' === $attr['paddingType']) {

        $paddingTop = intval( isset($attr['paddingTop']) ? $attr['paddingTop'] : 0 );
        $paddingRight = intval( isset($attr['paddingRight']) ? $attr['paddingRight'] : 0 );
        $paddingBottom = intval( isset($attr['paddingBottom']) ? $attr['paddingBottom'] : 0 );
        $paddingLeft = intval( isset($attr['paddingLeft']) ? $attr['paddingLeft'] : 0 );
        $taiowBlockStyle .= "
            --taiowc-padding-top: {$paddingTop}{$paddingUnit};
            --taiowc-padding-bottom: {$paddingBottom}{$paddingUnit};
            --taiowc-padding-left: {$paddingLeft}{$paddingUnit};
            --taiowc-padding-right: {$paddingRight}{$paddingUnit};
            ";
    } else {
        $padding = intval( isset($attr['padding']) ? $attr['padding'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-padding-top: {$padding}{$paddingUnit};
            --taiowc-padding-bottom: {$padding}{$paddingUnit};
            --taiowc-padding-left: {$padding}{$paddingUnit};
            --taiowc-padding-right: {$padding}{$paddingUnit};
            ";

    }
    if (isset($attr['iconfontSize'])) {
        $iconfontSizeUnit = taiowc_sanitize_css_unit( isset($attr['iconfontSizeUnit']) ? $attr['iconfontSizeUnit'] : 'px' );
        $iconfontSize = intval( $attr['iconfontSize'] );
        $taiowBlockStyle .= "--taiowc-iconfontSize: {$iconfontSize}{$iconfontSizeUnit}; ";
    }
    if (isset($attr['pricefontSize'])) {
        $pricefontSizeUnit = taiowc_sanitize_css_unit( isset($attr['pricefontSizeUnit']) ? $attr['pricefontSizeUnit'] : 'px' );
        $pricefontSize = intval( $attr['pricefontSize'] );
        $taiowBlockStyle .= "--taiowc-pricefontSize: {$pricefontSize}{$pricefontSizeUnit}; ";
    }

    if (isset($attr['countSize'])) {
        $countSizeUnit = taiowc_sanitize_css_unit( isset($attr['countSizeUnit']) ? $attr['countSizeUnit'] : 'px' );
        $countSize = intval( $attr['countSize'] );
        $taiowBlockStyle .= "--taiowc-countSize: {$countSize}{$countSizeUnit}; ";
    }
    if (isset($attr['countFontSize'])) {
        $countFontSizeUnit = taiowc_sanitize_css_unit( isset($attr['countFontSizeUnit']) ? $attr['countFontSizeUnit'] : 'px' );
        $countFontSize = intval( $attr['countFontSize'] );
        $taiowBlockStyle .= "--taiowc-countFontSize: {$countFontSize}{$countFontSizeUnit}; ";
    }
    if (isset($attr['borderRadius'])) {
        $borderRadiusUnit = taiowc_sanitize_css_unit( isset($attr['borderRadiusUnit']) ? $attr['borderRadiusUnit'] : 'px' );
        $borderRadius = intval( $attr['borderRadius'] );
        $taiowBlockStyle .= "--taiowc-borderRadius: {$borderRadius}{$borderRadiusUnit}; ";
    }
    // margin
    if (isset($attr['marginType']) && 'unlinked' === $attr['marginType']) {

        $marginTop = intval( isset($attr['marginTop']) ? $attr['marginTop'] : 0 );
        $marginRight = intval( isset($attr['marginRight']) ? $attr['marginRight'] : 0 );
        $marginBottom = intval( isset($attr['marginBottom']) ? $attr['marginBottom'] : 0 );
        $marginLeft = intval( isset($attr['marginLeft']) ? $attr['marginLeft'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-margin-top: {$marginTop}{$marginUnit};
            --taiowc-margin-bottom: {$marginBottom}{$marginUnit};
            --taiowc-margin-left: {$marginLeft}{$marginUnit};
            --taiowc-margin-right: {$marginRight}{$marginUnit};
            ";
    } else {
        $margin = intval( isset($attr['margin']) ? $attr['margin'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-margin-top: {$margin}{$marginUnit};
            --taiowc-margin-bottom: {$margin}{$marginUnit};
            --taiowc-margin-left: {$margin}{$marginUnit};
            --taiowc-margin-right: {$margin}{$marginUnit};
            ";

    }
    //tablet
    if (isset($attr['paddingTypeTablet']) && 'unlinked' === $attr['paddingType']) {

        $paddingTopTablet = intval( isset($attr['paddingTopTablet']) ? $attr['paddingTopTablet'] : 0 );
        $paddingRightTablet = intval( isset($attr['paddingRightTablet']) ? $attr['paddingRightTablet'] : 0 );
        $paddingBottomTablet = intval( isset($attr['paddingBottomTablet']) ? $attr['paddingBottomTablet'] : 0 );
        $paddingLeftTablet = intval( isset($attr['paddingLeftTablet']) ? $attr['paddingLeftTablet'] : 0 );
        $taiowBlockStyle .= "
            --taiowc-padding-top-tablet: {$paddingTopTablet}{$paddingUnit};
            --taiowc-padding-bottom-tablet: {$paddingBottomTablet}{$paddingUnit};
            --taiowc-padding-left-tablet: {$paddingLeftTablet}{$paddingUnit};
            --taiowc-padding-right-tablet: {$paddingRightTablet}{$paddingUnit};
            ";
    } else {
        $paddingTablet = intval( isset($attr['paddingTablet']) ? $attr['paddingTablet'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-padding-top-tablet: {$paddingTablet}{$paddingUnit};
            --taiowc-padding-bottom-tablet: {$paddingTablet}{$paddingUnit};
            --taiowc-padding-left-tablet: {$paddingTablet}{$paddingUnit};
            --taiowc-padding-right-tablet: {$paddingTablet}{$paddingUnit};
            ";

    }
    // margin
    if (isset($attr['marginTypeTablet']) && 'unlinked' === $attr['marginTypeTablet']) {

        $marginTopTablet = intval( isset($attr['marginTopTablet']) ? $attr['marginTopTablet'] : 0 );
        $marginRightTablet = intval( isset($attr['marginRightTablet']) ? $attr['marginRightTablet'] : 0 );
        $marginBottomTablet = intval( isset($attr['marginBottomTablet']) ? $attr['marginBottomTablet'] : 0 );
        $marginLeftTablet = intval( isset($attr['marginLeftTablet']) ? $attr['marginLeftTablet'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-margin-top-tablet: {$marginTopTablet}{$marginUnit};
            --taiowc-margin-bottom-tablet: {$marginBottomTablet}{$marginUnit};
            --taiowc-margin-left-tablet: {$marginLeftTablet}{$marginUnit};
            --taiowc-margin-right-tablet: {$marginRightTablet}{$marginUnit};
            ";
    } else {
        $marginTablet = intval( isset($attr['marginTablet']) ? $attr['marginTablet'] : 0 );

        $taiowBlockStyle .= "
            --taiowc-margin-top-tablet: {$marginTablet}{$marginUnit};
            --taiowc-margin-bottom-tablet: {$marginTablet}{$marginUnit};
            --taiowc-margin-left-tablet: {$marginTablet}{$marginUnit};
            --taiowc-margin-right-tablet: {$marginTablet}{$marginUnit};
            ";

    }
    if (isset($attr['iconfontSizeTablet'])) {
        $iconfontSizeTablet = intval( $attr['iconfontSizeTablet'] );
        $taiowBlockStyle .= "--taiowc-iconfontSizeTablet: {$iconfontSizeTablet}{$iconfontSizeUnit}; ";
    }
    if (isset($attr['pricefontSizeTablet'])) {
        $pricefontSizeTablet = intval( $attr['pricefontSizeTablet'] );
        $taiowBlockStyle .= "--taiowc-pricefontSizeTablet: {$pricefontSizeTablet}{$pricefontSizeUnit}; ";
    }

    if (isset($attr['countSizeTablet'])) {
        $countSizeTablet = intval( $attr['countSizeTablet'] );
        $taiowBlockStyle .= "--taiowc-countSizeTablet: {$countSizeTablet}{$countSizeUnit}; ";
    }
    if (isset($attr['countFontSizeTablet'])) {
        $countFontSizeTablet = intval( $attr['countFontSizeTablet'] );
        $taiowBlockStyle .= "--taiowc-countFontSizeTablet: {$countFontSizeTablet}{$countFontSizeUnit}; ";
    }

    if (isset($attr['borderRadiusTablet'])) {
        $borderRadiusUnit = taiowc_sanitize_css_unit( isset($attr['borderRadiusUnit']) ? $attr['borderRadiusUnit'] : 'px' );
        $borderRadiusTablet = intval( $attr['borderRadiusTablet'] );
        $taiowBlockStyle .= "--taiowc-borderRadiusTablet: {$borderRadiusTablet}{$borderRadiusUnit}; ";
    }
    //mobile

        if (isset($attr['paddingTypeMobile']) && 'unlinked' === $attr['paddingTypeMobile']) {

            $paddingTopMobile = intval( isset($attr['paddingTopMobile']) ? $attr['paddingTopMobile'] : 0 );
            $paddingRightMobile = intval( isset($attr['paddingRightMobile']) ? $attr['paddingRightMobile'] : 0 );
            $paddingBottomMobile = intval( isset($attr['paddingBottomMobile']) ? $attr['paddingBottomMobile'] : 0 );
            $paddingLeftMobile = intval( isset($attr['paddingLeftMobile']) ? $attr['paddingLeftMobile'] : 0 );

            $taiowBlockStyle .= "
                --taiowc-padding-top-mobile: {$paddingTopMobile}{$paddingUnit};
                --taiowc-padding-bottom-mobile: {$paddingBottomMobile}{$paddingUnit};
                --taiowc-padding-left-mobile: {$paddingLeftMobile}{$paddingUnit};
                --taiowc-padding-right-mobile: {$paddingRightMobile}{$paddingUnit};
                ";
        } else {
            $paddingMobile = intval( isset($attr['paddingMobile']) ? $attr['paddingMobile'] : 0 );

            $taiowBlockStyle .= "
                --taiowc-padding-top-mobile: {$paddingMobile}{$paddingUnit};
                --taiowc-padding-bottom-mobile: {$paddingMobile}{$paddingUnit};
                --taiowc-padding-left-mobile: {$paddingMobile}{$paddingUnit};
                --taiowc-padding-right-mobile: {$paddingMobile}{$paddingUnit};
                ";
        }

        // margin
        if (isset($attr['marginTypeMobile']) && 'unlinked' === $attr['marginTypeMobile']) {

            $marginTopMobile = intval( isset($attr['marginTopMobile']) ? $attr['marginTopMobile'] : 0 );
            $marginRightMobile = intval( isset($attr['marginRightMobile']) ? $attr['marginRightMobile'] : 0 );
            $marginBottomMobile = intval( isset($attr['marginBottomMobile']) ? $attr['marginBottomMobile'] : 0 );
            $marginLeftMobile = intval( isset($attr['marginLeftMobile']) ? $attr['marginLeftMobile'] : 0 );

            $taiowBlockStyle .= "
                --taiowc-margin-top-mobile: {$marginTopMobile}{$marginUnit};
                --taiowc-margin-bottom-mobile: {$marginBottomMobile}{$marginUnit};
                --taiowc-margin-left-mobile: {$marginLeftMobile}{$marginUnit};
                --taiowc-margin-right-mobile: {$marginRightMobile}{$marginUnit};
                ";
        } else {
            $marginMobile = intval( isset($attr['marginMobile']) ? $attr['marginMobile'] : 0 );

            $taiowBlockStyle .= "
                --taiowc-margin-top-mobile: {$marginMobile}{$marginUnit};
                --taiowc-margin-bottom-mobile: {$marginMobile}{$marginUnit};
                --taiowc-margin-left-mobile: {$marginMobile}{$marginUnit};
                --taiowc-margin-right-mobile: {$marginMobile}{$marginUnit};
                ";
        }

        if (isset($attr['iconfontSizeMobile'])) {
            $iconfontSizeMobile = intval( $attr['iconfontSizeMobile'] );
            $taiowBlockStyle .= "--taiowc-iconfontSizeMobile: {$iconfontSizeMobile}{$iconfontSizeUnit}; ";
        }
        if (isset($attr['pricefontSizeMobile'])) {
            $pricefontSizeMobile = intval( $attr['pricefontSizeMobile'] );
            $taiowBlockStyle .= "--taiowc-pricefontSizeMobile: {$pricefontSizeMobile}{$pricefontSizeUnit}; ";
        }
        if (isset($attr['countSizeMobile'])) {
            $countSizeMobile = intval( $attr['countSizeMobile'] );
            $taiowBlockStyle .= "--taiowc-countSizeMobile: {$countSizeMobile}{$countSizeUnit}; ";
        }
        if (isset($attr['countFontSizeMobile'])) {
            $countFontSizeMobile = intval( $attr['countFontSizeMobile'] );
            $taiowBlockStyle .= "--taiowc-countFontSizeMobile: {$countFontSizeMobile}{$countFontSizeUnit}; ";
        }
        if (isset($attr['borderRadiusMobile'])) {
            $borderRadiusUnit = taiowc_sanitize_css_unit( isset($attr['borderRadiusUnit']) ? $attr['borderRadiusUnit'] : 'px' );
            $borderRadiusMobile = intval( $attr['borderRadiusMobile'] );
            $taiowBlockStyle .= "--taiowc-borderRadiusMobile: {$borderRadiusMobile}{$borderRadiusUnit}; ";
        }


    if (isset($attr['cartBgClr'])) {
     $cartBgClr = taiowc_sanitize_css_color( $attr['cartBgClr'] );
     $taiowBlockStyle .= "--taiowc-cartBgClr:{$cartBgClr};";
    }

    $iconClr    = taiowc_sanitize_css_color( isset($attr['iconClr']) ? $attr['iconClr'] : '#111' );
    $priceClr   = taiowc_sanitize_css_color( isset($attr['priceClr']) ? $attr['priceClr'] : '#111' );
    $countClr   = taiowc_sanitize_css_color( isset($attr['countClr']) ? $attr['countClr'] : '#fff' );
    $countBgClr = taiowc_sanitize_css_color( isset($attr['countBgClr']) ? $attr['countBgClr'] : '#111' );

    $taiowBlockStyle .= "

        --taiowc-iconClr:{$iconClr};
        --taiowc-priceClr:{$priceClr};
        --taiowc-countClr:{$countClr};
        --taiowc-countBgClr:{$countBgClr};
                ";
    // class
    if (!isset($attr['cartPrice'])) {
        $taiowcriceShow = "th-showprice";
    }else{
        $taiowcriceShow = "";
    }
    if (!isset($attr['cartCount'])) {
    $taiowcCountShow = "th-showcount";
    }  else{
        $taiowcCountShow = "";
    }

    $countPosition    = isset($attr['countPosition']) ? sanitize_text_field( $attr['countPosition'] ) : 'left';
    $taiowBlockStyle  = preg_replace('/\s+/', ' ', trim($taiowBlockStyle));

    $block_content = '<div id="wp-block-taiowc-' . esc_attr($attr['uniqueID']) . '"  class="wp-block-taiowc ' . esc_attr($taiowcriceShow) . ' ' . esc_attr($taiowcCountShow) . '  '.esc_attr($countPosition).'" style="'.esc_attr($taiowBlockStyle).'">';

    $cartStyle = isset($attr['cartStyle']) ? sanitize_text_field( $attr['cartStyle'] ) : '[taiowc]';

    $block_content .= ''.do_shortcode($cartStyle).'</div>';

    return $block_content;

  }