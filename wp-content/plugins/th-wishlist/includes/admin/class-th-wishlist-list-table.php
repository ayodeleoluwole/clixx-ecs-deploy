<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class THWL_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => __( 'Wishlist', 'th-wishlist' ),
            'plural'   => __( 'Wishlists', 'th-wishlist' ),
            'ajax'     => false,
        ] );
    }

    /**
     * Prepare table items
     */
    public function prepare_items() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];

        $this->process_bulk_action();

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $total_items  = THWL_Data::get_wishlist_count();

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ] );

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'created_at';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $order   = isset( $_GET['order'] ) ? sanitize_key( $_GET['order'] ) : 'DESC';

        // Allowed ordering
        $allowed_orderby = [ 'wishlist_name', 'username', 'created_at' ];
        if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
            $orderby = 'created_at';
        }

        $allowed_order = [ 'ASC', 'DESC' ];
        if ( ! in_array( strtoupper( $order ), $allowed_order, true ) ) {
            $order = 'DESC';
        }

        $this->items = THWL_Data::get_wishlists( [
            'per_page' => $per_page,
            'paged'    => $current_page,
            'orderby'  => $orderby,
            'order'    => $order,
        ] );
    }

    public function get_columns() {
        return [
            'cb'            => '<input type="checkbox" />',
            'wishlist_name' => __( 'Name', 'th-wishlist' ),
            'username'      => __( 'Username', 'th-wishlist' ),
            'privacy'       => __( 'Privacy', 'th-wishlist' ),
            'items'         => __( 'Items', 'th-wishlist' ),
            'created_at'    => __( 'Date', 'th-wishlist' ),
        ];
    }

    public function get_hidden_columns() {
        return [];
    }

    public function get_sortable_columns() {
        return [
            'wishlist_name' => [ 'wishlist_name', false ],
            'username'      => [ 'username', false ],
            'created_at'    => [ 'created_at', true ],
        ];
    }

    protected function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="wishlist[]" value="%d" />', absint( $item['id'] ) );
    }

    protected function column_wishlist_name( $item ) {
        $actions = [];
        $wishlist_page_url = get_permalink( get_option( 'thwl_page_id' ) );

        if ( $wishlist_page_url ) {
            $actions['view'] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url( add_query_arg( [
                    'wishlist_token' => $item['wishlist_token'],
                    'wishlist_nonce' => wp_create_nonce( 'thwl_wishlist_nonce_action' ),
                ], $wishlist_page_url ) ),
                __( 'View', 'th-wishlist' )
            );
        }

        $delete_nonce = wp_create_nonce( 'thw_delete_wishlist' );
        $page = 'thwl-wishlists-tracking';
        $actions['delete'] = sprintf(
            '<a href="%s" onclick="return confirm(\'%s\')">%s</a>',
            esc_url( add_query_arg( [
                'page'     => $page,
                'action'   => 'delete',
                'wishlist' => absint( $item['id'] ),
                '_wpnonce' => $delete_nonce,
            ], admin_url( 'admin.php' ) ) ),
            esc_attr__( 'Are you sure?', 'th-wishlist' ),
            esc_html__( 'Delete', 'th-wishlist' )
        );

        return '<strong>' . esc_html( $item['wishlist_name'] ) . '</strong>' . $this->row_actions( $actions );
    }

    protected function column_username( $item ) {
        return ! empty( $item['username'] )
            ? esc_html( $item['username'] )
            : '<em>' . __( 'Guest', 'th-wishlist' ) . '</em>';
    }

    protected function column_items( $item ) {
        return absint( $item['item_count'] );
    }

    protected function column_privacy( $item ) {
        return ucfirst( esc_html( $item['privacy'] ) );
    }

    public function column_default( $item, $column_name ) {
        if ( 'created_at' === $column_name ) {
            return esc_html( date_i18n( get_option( 'date_format' ), strtotime( $item['created_at'] ) ) );
        }
        return '---';
    }

    public function get_bulk_actions() {
        return [
            'bulk-delete' => __( 'Delete', 'th-wishlist' ),
        ];
    }

    public function process_bulk_action() {

        // Single delete
        if ( 'delete' === $this->current_action() ) {

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'Not allowed.', 'th-wishlist' ) );
            }

            if ( ! isset( $_GET['_wpnonce'] ) ||
                 ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'thw_delete_wishlist' ) ) {
                wp_die( esc_html__( 'Security check failed.', 'th-wishlist' ) );
            }

            $wishlist_id = isset( $_GET['wishlist'] ) ? absint( $_GET['wishlist'] ) : 0;

            if ( $wishlist_id ) {
                THWL_Data::delete_wishlist( $wishlist_id );
            }

            wp_safe_redirect(
                add_query_arg([
                    'page'    => 'thwl-wishlists-tracking',
                    'deleted' => 1,
                ], admin_url( 'admin.php' ) )
            );
            exit;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (
            ( isset( $_POST['action'] ) && 'bulk-delete' === $_POST['action'] ) ||
            ( isset( $_POST['action2'] ) && 'bulk-delete' === $_POST['action2'] )
        ) {

            if ( ! isset( $_POST['_wpnonce'] ) ||
                 ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'bulk-' . $this->_args['plural'] ) ) {
                wp_die( esc_html__( 'Security check failed.', 'th-wishlist' ) );
            }

            $delete_ids = isset( $_POST['wishlist'] )
                ? array_map( 'absint', (array) $_POST['wishlist'] )
                : [];

            foreach ( $delete_ids as $id ) {
                THWL_Data::delete_wishlist( $id );
            }

            wp_safe_redirect(
                add_query_arg([
                    'page'    => 'thwl-wishlists-tracking',
                    'deleted' => count( $delete_ids ),
                ], admin_url( 'admin.php' ) )
            );
            exit;
        }
    }
}

/**
 * Admin notice after delete
 */
add_action( 'admin_notices', function() {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['deleted'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $count = (int) $_GET['deleted'];

        /* translators: %d: Number of deleted wishlists */
        $text = _n( '%d wishlist deleted.', '%d wishlists deleted.', $count, 'th-wishlist' );

        printf(
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
            esc_html( sprintf( $text, $count ) )
        );
    }
} );
