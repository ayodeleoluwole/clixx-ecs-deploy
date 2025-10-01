<?php
/**
 * Plugin Name:             All In One Advance Cart
 * Requires Plugins:        woocommerce
 * Plugin URI:              https://themehunk.com/th-all-in-one-woo-cart/
 * Description:             TH All In One Woo Cart is a perfect choice to display Cart on your website and improve your potential customer’s buying experience. This plugin will add Floating Cart in your website.  Customers can update or remove products from the cart without reloading the cart continuously. It is a fully Responsive, mobile friendly plugin and supports many advanced features.
 * Version:                 2.3.1
 * Author:                  ThemeHunk
 * License:                 GPL-2.0+
 * License URI:             http://www.gnu.org/licenses/gpl-2.0.txt
 * Author URI:              https://themehunk.com
 * Requires at least:       5.5
 * Tested up to:            6.9
 * WC requires at least:    3.2
 * WC tested up to:         9.9
 * Domain Path:             /languages
 * Text Domain:             th-all-in-one-woo-cart
 * Tags: floating cart,ajax,cart,woocommerce,advance cart,slider
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('TAIOWC_PLUGIN_FILE')) {
    define('TAIOWC_PLUGIN_FILE', __FILE__);
}

if (!defined('TAIOWC_PLUGIN_URI')) {
    define( 'TAIOWC_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

if (!defined('TAIOWC_PLUGIN_PATH')) {
    define( 'TAIOWC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if (!defined('TAIOWC_PLUGIN_DIRNAME')) {
    define( 'TAIOWC_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
}

if (!defined('TAIOWC_PLUGIN_BASENAME')) {
    define( 'TAIOWC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if (!defined('TAIOWC_IMAGES_URI')) {
define( 'TAIOWC_IMAGES_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'images' ) );
}

if (!defined('TAIOWC_VERSION')){
$plugin_data = get_file_data(__FILE__, array('version' => 'Version'), false);
define('TAIOWC_VERSION', $plugin_data['version']);
} 

/**
*  Declare the woo HPOS compatibility.
*/
function taiowc_hpos_compatibility() {
if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
          \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', TAIOWC_PLUGIN_FILE, true );
      }
}
add_action( 'before_woocommerce_init', 'taiowc_hpos_compatibility');

if (!class_exists('Taiowc') && !class_exists('Taiowcp_Main')){

include_once(TAIOWC_PLUGIN_PATH . 'inc/themehunk-menu/admin-menu.php');
require_once("inc/taiowc.php");
require_once TAIOWC_PLUGIN_PATH . '/inc/taiowc-block.php';
require_once TAIOWC_PLUGIN_PATH . 'inc/deactivate-feedback.php';

}              

/**
* Add the settings link to plugin row
*
* @param array $links - Links for the plugin
* @return array - Links
 */

function taiowc_plugin_action_links($links){
          $settings_page = add_query_arg(array('page' => 'taiowc'), admin_url('admin.php'));
          $settings_link = '<a href="'.esc_url($settings_page).'">'.__('Settings', 'th-all-in-one-woo-cart' ).'</a>';
          array_unshift($links, $settings_link);
          return $links;
        }
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'taiowc_plugin_action_links', 10, 1);

/**
   * Add links to plugin's description in plugins table
   *
   * @param array  $links  Initial list of links.
   * @param string $file   Basename of current plugin.
   *
   * @return array
   */
if ( ! function_exists( 'taiowc_plugin_meta_links' ) ){

  function taiowc_plugin_meta_links($links, $file){

    if ($file !== plugin_basename(__FILE__)) {
      return $links;
    }

    $inline_styles =  "color: #0171e1; font-weight: 700;";

   
    $demo_link = '<a target="_blank" href="'. esc_url('https://themehunk.com/th-all-in-one-woo-cart/').'" title="' . __('Live Demo', 'th-all-in-one-woo-cart') . '"><span class="dashicons  dashicons-laptop"></span>' . __('Live Demo', 'th-all-in-one-woo-cart') . '</a>';

    $doc_link = '<a target="_blank" href="'.esc_url('https://themehunk.com/docs/th-all-in-one-woo-cart/').'" title="' . __('Documentation', 'th-all-in-one-woo-cart') . '"><span class="dashicons  dashicons-search"></span>' . __('Documentation', 'th-all-in-one-woo-cart') . '</a>';

    $support_link = '<a target="_blank" href="'.esc_url('https://themehunk.com/contact-us').'" title="' . __('Support', 'th-all-in-one-woo-cart') . '"><span class="dashicons  dashicons-admin-users"></span>' . __('Support', 'th-all-in-one-woo-cart') . '</a>';

    $pro_link = '<a  style="'.esc_attr( $inline_styles ).'" target="_blank" href="'. esc_url('https://themehunk.com/th-all-in-one-woo-cart/').'" title="' . __('Pro Version', 'th-all-in-one-woo-cart') . '"><span class="dashicons  dashicons-cart"></span>' . __('Premium Version', 'th-all-in-one-woo-cart') . '</a>';

    $links[] = $demo_link;
    $links[] = $doc_link;
    $links[] = $support_link;
    $links[] = $pro_link;

    return $links;

  } // plugin_meta_links

}
add_filter('plugin_row_meta', 'taiowc_plugin_meta_links', 10, 2);