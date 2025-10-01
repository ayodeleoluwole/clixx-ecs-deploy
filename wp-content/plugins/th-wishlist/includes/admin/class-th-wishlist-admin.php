<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin-facing functions and hooks for TH Wishlist.
 *
 * @class THWL_Admin
 */
class THWL_Admin {

    public function __construct() {
        // Include and instantiate settings, tracking, and AJAX handlers.
        require_once THWL_DIR . 'includes/admin/class-th-wishlist-settings.php';
        require_once THWL_DIR . 'includes/admin/class-th-wishlist-settings-ajax.php';
        require_once THWL_DIR . 'includes/admin/class-th-wishlist-track.php';
        new THWL_Settings();
        new THWL_Ajax();
        new THWL_Tracking();
        add_action( 'admin_enqueue_scripts', array( $this, 'thwl_enqueue_scripts' ));
    }

    /**
     * Enqueue admin scripts for the color picker and media uploader.
     */
    public function thwl_enqueue_scripts( $hook ) {
        if ( 'toplevel_page_thwl-wishlist' !== $hook && 'th-wishlist_page_thwl-wishlists-tracking' !== $hook ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'jquery-ui-sortable' );
    
             wp_enqueue_style(
                'pickr-style',
                THWL_URL . 'assets/css/pickr.min.css',
                [],
                '1.9.1'
            );

            // Enqueue custom admin scripts with localized data.
            wp_enqueue_script(
                'pickr-script',
                THWL_URL . 'assets/js/pickr.min.js',
                ['jquery'],
                '1.9.1',
                true
            );

            if ( ! defined( 'THWL_PRO_ACTIVE' ) || ! THWL_PRO_ACTIVE ) { 
            // Enqueue custom admin styles.
                wp_enqueue_style(
                    'thwl-admin',
                    THWL_URL . 'assets/css/thwl-admin.css',
                    [],
                    THWL_VERSION
                );
            
            wp_enqueue_script(
                'thwl-admin',
                THWL_URL . 'assets/js/thwl-admin.js',
                [ 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ],
                THWL_VERSION,
                true
            );
            }

            wp_localize_script(
                'thwl-admin',
                'thwlAdmin',
                [
                    'ajax_url'  => admin_url( 'admin-ajax.php' ),
                    'nonce'     => wp_create_nonce( 'thwl_wishlist_nonce' ),
                    'i18n'      => [
                        'save_success'  => __( 'Settings saved successfully!', 'th-wishlist' ),
                        'save_error'    => __( 'Error saving settings. Please try again.', 'th-wishlist' ),
                        'reset_success' => __( 'Settings reset to defaults!', 'th-wishlist' ),
                        'reset_error'   => __( 'Error resetting settings. Please try again.', 'th-wishlist' ),
                        'confirm_reset' => __( 'Are you sure you want to reset all settings to default?', 'th-wishlist' ),
                    ],
                ]
            );
      } 
}