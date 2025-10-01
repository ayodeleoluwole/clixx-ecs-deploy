<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles the settings page for TH Wishlist.
 *
 * @class THWL_Tracking
 */
class THWL_Tracking {

    /**
     * Constructor.
     */
    public function __construct() {
        
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add admin menu page.
     */
    public function admin_menu() {
        add_submenu_page(
            'thwl-wishlist',
            __( 'Wishlists Tracking', 'th-wishlist' ),
            __( 'Wishlists', 'th-wishlist' ),
            'manage_options',
            'thwl-wishlists-tracking',
            array( $this, 'tracking_page' )
        );
    }

    /**
     * Render the wishlists tracking page with the list table.
     */
    public static function tracking_page() {
        // The list table class should be included before it's instantiated.
        require_once THWL_DIR . 'includes/admin/class-th-wishlist-list-table.php';

        $wishlist_table = new THWL_Table();
        $wishlist_table->prepare_items();
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('TH Wishlists', 'th-wishlist'); ?></h1>
            <form method="post">
                <?php
                // Display search box, filters, and the table
                $wishlist_table->search_box('Search Wishlists', 'wishlist');
                $wishlist_table->display();
                ?>
            </form>
        </div>
        <?php
    }
}