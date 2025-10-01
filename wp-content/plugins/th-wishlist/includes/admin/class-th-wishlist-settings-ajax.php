<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles AJAX requests for TH Wishlist admin.
 *
 * @class THWL_Ajax
 */
class THWL_Ajax {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wp_ajax_thwl_save_settings', array( $this, 'thwl_save_settings' ) );
        add_action( 'wp_ajax_thwl_reset_settings', array( $this, 'thwl_reset_settings' ) );
    }

    /**
     * Sanitize form data.
     *
     * @param array $data The form data to sanitize.
     * @return array Sanitized and validated data.
     */
    private function thwl_sanitize_form_data( $data ) {
    $sanitized = [];

    if ( ! is_array( $data ) || empty( $data ) ) {
        return $sanitized;
    }

    foreach ( $data as $key => $value ) {
        // Skip invalid keys
        if ( empty( $key ) ) {
            continue;
        }

        // Special handling for columns (array of slugs)
        if ( $key === 'th_wishlist_table_columns' && is_array( $value ) ) {
            $sanitized[ $key ] = array_map( 'sanitize_text_field', array_filter( $value ) );
            continue;
        }

        // Special handling for column labels (key => label pairs)
        if ( $key === 'th_wishlist_table_column_labels' && is_array( $value ) ) {
            $sanitized[ $key ] = array_map( 'sanitize_text_field', $value );
            continue;
        }

        // Sanitize page IDs or numeric settings
        if ( strpos( $key, '_id' ) !== false || strpos( $key, '_number' ) !== false ) {
            $sanitized[ $key ] = absint( $value );
            continue;
        }

        // Handle arrays in general (checkboxes, lists, etc.)
        if ( is_array( $value ) ) {
            $sanitized[ $key ] = array_map( 'sanitize_text_field', $value );
            continue;
        }

        // Fallback — treat as string
        $sanitized[ $key ] = sanitize_text_field( $value );
    }

    return $sanitized;
}

    /**
     * Save settings via AJAX.
     */
    public function thwl_save_settings() {
        // Verify nonce
        check_ajax_referer( 'thwl_wishlist_nonce', '_wpnonce' );

        // Ensure user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Invalid permissions.', 'th-wishlist' ) );
        }

       // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ( ! isset( $_POST['settings'] ) || ! is_array( $_POST['settings'] ) ) {
            wp_send_json_error( __( 'Error saving settings. Invalid data.', 'th-wishlist' ) );
        }
        // Unslash and sanitize the settings array

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $sanitized_data = $this->thwl_sanitize_form_data( wp_unslash( $_POST['settings'] ) );
        if ( ! empty( $sanitized_data ) ) {
            update_option( 'thwl_settings', $sanitized_data );
            if ( isset( $sanitized_data['thwl_page_id'] ) ) {
            update_option( 'thwl_page_id', absint( $sanitized_data['thwl_page_id'] ) );
            }
            wp_send_json_success( __( 'Settings saved successfully!', 'th-wishlist' ) );
        }
        wp_send_json_error( __( 'Error saving settings. Invalid data.', 'th-wishlist' ) );
    }

    /**
     * Reset settings to defaults via AJAX.
     */
    public function thwl_reset_settings() {
        // Verify nonce
        check_ajax_referer( 'thwl_wishlist_nonce', '_wpnonce' );
        // Ensure user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Invalid permissions.', 'th-wishlist' ) );
        }
        // Get and save default settings
        update_option( 'thwl_settings', []);
        wp_send_json_success( __( 'Settings reset to defaults!', 'th-wishlist' ) );
    }
}