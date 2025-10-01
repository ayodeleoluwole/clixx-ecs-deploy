<?php
if (!defined('ABSPATH')) exit;
class th_compare_admin
{
    public $optionName = 'th_compare_option';
    public static function get()
    {
        return new self();
    }
    private function __construct()
    {
        add_action('wp_ajax_th_compare_save_data', array($this, 'save'));
        add_action('wp_ajax_th_compare_reset_data', array($this, 'reset'));
        add_action('wp_ajax_th_compare_filter_product', array($this, 'filter_product'));
        add_action('wp_ajax_nopriv_th_compare_filter_product', array($this, 'filter_product'));
    }

    public function filter_product()
    {
        check_ajax_referer( 'th_product_compare_nonce', 'nonce' );

            /* -------- INPUT -------- */
        $text_ = '';
        if ( isset( $_POST['inputs'] ) ) {
            $text_ = sanitize_text_field( wp_unslash( $_POST['inputs'] ) );
            $text_ = substr( $text_, 0, 50 ); // prevent heavy searches
        }

        $arrArg = array(
            'post_type'     => 'product',
            'post_status'   => 'publish',
            'nopaging'      => true,
            'posts_per_page' => 100,
            's'             => $text_,
        );
        if ($text_ != '') {
            $arrArg['s'] = $text_;
            $arrArg['posts_per_page'] = 100;
        } else {
            $arrArg['posts_per_page'] = 20;
        }
        $results = new WP_Query($arrArg);
        $items = array();
        if (!empty($results->posts)) {
            foreach ($results->posts as $result) {

                $imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($result->ID), 'single-post-thumbnail');
                $imageUrl = isset($imageUrl[0]) ? $imageUrl[0] : wc_placeholder_img_src();

                $items[] = array(
                    'image_url' => $imageUrl,
                    'label' => $result->post_title,
                    'id' => $result->ID,
                );
            }
        } else {
            $items['no_product'] = __('No Product Found', 'th-product-compare');
        }
        wp_send_json_success($items);
    }

private function sanitize_nested_array( $array ) {
    $clean = array();
    foreach ( $array as $key => $value ) {
        $key = sanitize_key( $key );
        if ( is_array( $value ) ) {
            $clean[ $key ] = $this->sanitize_nested_array( $value );
        } else {
            $clean[ $key ] = sanitize_text_field( $value );
        }
    }
    return $clean;
}

public function save() {
    /* -------- CAPABILITY -------- */
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( -1, 403 );
    }

    /* -------- NONCE -------- */
    if (
        ! isset( $_POST['nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
            '_wpnonce'
        )
    ) {
        wp_die( -1, 403 );
    }

    /* -------- INPUT -------- */
    if ( ! isset( $_POST['inputs'] ) || ! is_array( $_POST['inputs'] ) ) {
        wp_send_json_error( array( 'message' => 'No input data received' ) );
        wp_die();
    }

    $raw_inputs = wp_unslash( $_POST['inputs'] );

    // Final clean structure 
    $clean_data = array();

    // Recursive sanitize + preserve structure
    foreach ( $raw_inputs as $key => $value ) {
        $key = sanitize_key( $key );

        if ( is_array( $value ) ) {
            // Nested array (jaise compare-attributes, compare-field)
            $clean_data[ $key ] = $this->sanitize_nested_array( $value );
        } else {
            // Simple field (text, number, etc.)
            $clean_data[ $key ] = sanitize_text_field( $value );
        }
    }

    // Special handling for attributes → 'on' ko 1 mein convert + missing ko 0  do
    if ( isset( $clean_data['compare-attributes'] ) && is_array( $clean_data['compare-attributes'] ) ) {
        $attributes = array();
        foreach ( $clean_data['compare-attributes'] as $attr_key => $val ) {
            $attr_key = sanitize_key( $attr_key );
            $attributes[ $attr_key ] = array(
                'active' => ( ! empty( $val ) && $val !== '0' ) ? 1 : 0
            );
        }
        $clean_data['attributes'] = $attributes;  // rename key to match frontend expectation
        unset( $clean_data['compare-attributes'] ); // optional cleanup
    }

    // Repeat fields ko '1'/'0' string mein convert (for consistency)
    if ( isset( $clean_data['compare-field'] ) && is_array( $clean_data['compare-field'] ) ) {
        $clean_data['field-repeat-price'] = ! empty( $clean_data['compare-field']['repeat-price'] ) ? '1' : '0';
        $clean_data['field-repeat-add-to-cart'] = ! empty( $clean_data['compare-field']['repeat-add-to-cart'] ) ? '1' : '0';
        unset( $clean_data['compare-field'] ); // optional
    }

    /* -------- SAVE -------- */
    $result = $this->setOption( $clean_data );

    if ( $result ) {
        echo esc_html__( 'update', 'th-product-compare' );
    } else {
        echo esc_html__( 'nochange', 'th-product-compare' );
    }

    wp_die();
}


    // cookies
    public function setOption($inputs)
    {
        $checkOption = get_option($this->optionName);

        $saveOption = $this->sanitizeOptions($inputs);

        if ($checkOption) {

            $result = update_option($this->optionName, $saveOption);

        } else {

            $result = add_option($this->optionName, $saveOption);

        }

        return $result;
    }

    function sanitizeOptions($array)
    {
        foreach ($array as $key => &$value) {

            if (is_array($value)) {

                $value = $this->sanitizeOptions($value);

            } else {

                $value = sanitize_text_field($value);
            }

        }

        return $array;
    }

    public function reset()
    {

        if ( ! current_user_can( 'manage_options' ) ) {

            wp_die( -1, 403 );

            }

          // Nonce check
        if (
            ! isset( $_POST['nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
                '_wpnonce'
            )
        ) {
            wp_die( -1, 403 );
        }

        if (isset($_POST['inputs']) && $_POST['inputs'] == 'reset') {

            $checkOption = get_option($this->optionName);

            if ($checkOption) {

                delete_option($this->optionName);

                echo esc_html('reset');

            }

        }

        die();

  }

    // class end
}
