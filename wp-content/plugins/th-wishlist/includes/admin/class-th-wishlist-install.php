<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Installation-related functions and hooks for TH Wishlist.
 *
 * @class THWL_Install
 */
class THWL_Install {

    /**
     * Hook in tabs.
     */
    public static function install() {
        self::create_tables();
        self::create_page();
    }

    /**
     * Create database tables.
     */
    private static function create_tables() {
        global $wpdb;
        $wpdb->hide_errors();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $collate = $wpdb->get_charset_collate();

        // Wishlist table with new 'privacy' column
        $sql = "
            CREATE TABLE {$wpdb->prefix}thwl_wishlists (
              id BIGINT(20) NOT NULL AUTO_INCREMENT,
              user_id BIGINT(20) NULL,
              session_id VARCHAR(255) NULL,
              wishlist_name VARCHAR(255) NOT NULL,
              wishlist_token VARCHAR(64) NOT NULL UNIQUE,
              privacy VARCHAR(20) NOT NULL DEFAULT 'private',
              is_default TINYINT(1) NOT NULL DEFAULT 1,
              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id)
            ) $collate;
        ";
        dbDelta( $sql );

        // Wishlist items table
        $sql = "
            CREATE TABLE {$wpdb->prefix}thwl_wishlist_items (
              id BIGINT(20) NOT NULL AUTO_INCREMENT,
              wishlist_id BIGINT(20) NOT NULL,
              product_id BIGINT(20) NOT NULL,
              variation_id BIGINT(20) DEFAULT 0,
              quantity INT(11) NOT NULL DEFAULT 1,
              added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id),
              KEY wishlist_id (wishlist_id),
              KEY product_id (product_id)
            ) $collate;
        ";
        dbDelta( $sql );
    }

    /**
     * Create the wishlist page.
     */
    private static function create_page() {

        if ( get_option( 'thwl_page_id' )) {
            return;
        }
        $wishlist_page = array(
            'post_title'     => 'Wishlist',
            'post_content'   => '[thwl_wishlist]',
            'post_status'    => 'publish',
            // 'post_author'    => 1,
            'post_type'      => 'page',
            'comment_status' => 'closed',
        );
        $page_id = wp_insert_post( $wishlist_page );
        if ( $page_id ) {
            update_option( 'thwl_page_id', $page_id );
        }
    }
}
