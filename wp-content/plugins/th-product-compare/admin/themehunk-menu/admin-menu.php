<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!function_exists('themehunk_admin_menu')) {
  include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
    define('THEMEHUNK_PURL', plugin_dir_url(__FILE__));
    define('THEMEHUNK_PDIR', plugin_dir_path(__FILE__));
    add_action( 'wp_ajax_themehunk_activeplugin','themehunk_activeplugin');
    add_action('admin_menu',  'themehunk_admin_menu');
    add_action( 'admin_enqueue_scripts', 'admin_scripts');


 if ( !function_exists('themehunk_activeplugin') ) {
        function themehunk_activeplugin(){
            if ( ! current_user_can( 'manage_options' ) ) {
               wp_die( -1, 403 );
           }
     
          if (
            ! isset( $_POST['nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
                'th_product_compare_admin_nonce'
            )
        ) {
             wp_die( - 1, 403 );
          }
          if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
           wp_send_json_error(
             array(
               'success' => false,
               'message' => __( 'No plugin specified', 'th-product-compare' ),
             )
           );
         }
         $plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( $_POST['init'] ) : '';
         $activate = activate_plugin( $plugin_init);
         if ( is_wp_error( $activate ) ) {
           wp_send_json_error(
             array(
               'success' => false,
               'message' => $activate->get_error_message(),
             )
           );
         }
         wp_send_json_success(
           array(
             'success' => true,
             'message' => __( 'Plugin Successfully Activated', 'th-product-compare' ),
           )
         );
           }
       }
    function themehunk_admin_menu(){
            add_menu_page(__('ThemeHunk', 'th-product-compare'), __('ThemeHunk', 'th-product-compare'), 'manage_options', 'themehunk-plugins', 'themehunk_plugins',  THEMEHUNK_PURL . '/th-option/assets/images/ico.png', 59);
        }
    function themehunk_plugins(){

      if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.','th-product-compare' ) );
        }

        include_once THEMEHUNK_PDIR . "/th-option/th-option.php";
        $obj = new themehunk_plugin_option();
        $obj->tab_page();
    }

function admin_scripts( $hook ) {
  
    if ($hook === 'toplevel_page_themehunk-plugins'  ) {
      
      wp_enqueue_style( 'themehunk-plugin-css', THEMEHUNK_PURL . '/th-option/assets/css/started.css',false,'1.0.0' );

      wp_enqueue_script('themehunk-plugin-js', THEMEHUNK_PURL . '/th-option/assets/js/th-options.js',array( 'jquery', 'updates' ),'1', true);

      wp_localize_script(
          'themehunk-plugin-js', 'ThCompareAdmin', array(
            'nonce'         => wp_create_nonce( 'th_product_compare_admin_nonce' ),
          )
      );

    }

  }

}