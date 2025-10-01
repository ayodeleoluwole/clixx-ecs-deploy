<?php 
/**
 * all file includeed
 *
 * @param  
 * @return mixed|string
 */
$plugin_companion_file = 'hunk-companion/hunk-companion.php';
require_once OPEN_SHOP_THEME_DIR.'inc/admin-function.php';


require_once OPEN_SHOP_THEME_DIR.'inc/header-function.php';

require_once OPEN_SHOP_THEME_DIR.'inc/footer-function.php';
require_once OPEN_SHOP_THEME_DIR.'inc/blog-function.php';
//breadcrumbs
require_once OPEN_SHOP_THEME_DIR.'lib/breadcrumbs/breadcrumbs.php';
//page-post-meta
require_once OPEN_SHOP_THEME_DIR.'lib/page-meta-box/open-page-meta-box.php';
//custom-style
require_once OPEN_SHOP_THEME_DIR.'inc/open-shop-custom-style.php';

// customizer
require_once OPEN_SHOP_THEME_DIR.'customizer/extend-customizer/class-open-shop-wp-customize-panel.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/extend-customizer/class-open-shop-wp-customize-section.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer-radio-image/class/class-open-shop-customize-control-radio-image.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer-range-value/class/class-open-shop-customizer-range-value-control.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer-scroll/class/class-open-shop-customize-control-scroll.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/color/class-control-color.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customize-buttonset/class-control-buttonset.php';
// require_once OPEN_SHOP_THEME_DIR.'customizer/sortable/class-open-control-sortable.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/background/class-open-shop-background-image-control.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer-toggle/class-open-shop-toggle-control.php';
//typography
// require_once OPEN_SHOP_THEME_DIR.'/customizer/typography/class-open-shop-control-typography.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/custom-customizer.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer-constant.php';
require_once OPEN_SHOP_THEME_DIR.'customizer/customizer.php';
/******************************/
// woocommerce
/******************************/
require_once OPEN_SHOP_THEME_DIR. 'inc/woocommerce/woo-core.php';
require_once OPEN_SHOP_THEME_DIR. 'inc/woocommerce/woo-function.php';
require_once OPEN_SHOP_THEME_DIR.'inc/woocommerce/woocommerce-ajax.php';

//Th Option
require_once OPEN_SHOP_THEME_DIR. '/lib/th-option/th-option.php';

// Probutton
/******************************/
require_once OPEN_SHOP_THEME_DIR.'customizer/pro-button/class-customize.php';

/******************************/
// Plugin Activation
/******************************/
require_once OPEN_SHOP_THEME_DIR. 'lib/notification/notify.php';
if (is_customize_preview() && !is_plugin_active($plugin_companion_file)) {
require_once OPEN_SHOP_THEME_DIR.'lib/notification/customizer-notification/thsm-custom-section.php';
}