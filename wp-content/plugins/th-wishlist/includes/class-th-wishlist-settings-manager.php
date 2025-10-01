<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class THWL_Manager {
    
    private static $instance = null;
    private $settings = [];

    private function __construct() {

        require_once THWL_DIR . 'includes/admin/class-th-wishlist-settings.php';
        $this->settings = get_option( 'thwl_settings', []);
    }

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_setting( $key, $default = null ) {
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
    }

    public function get_settings() {
        return $this->settings;
    }

    public function refresh_settings() {
        $this->settings = get_option( 'thwl_settings', [] );
    }
}