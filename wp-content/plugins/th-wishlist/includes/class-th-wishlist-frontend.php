<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Frontend-facing functions and hooks for TH Wishlist.
 *
 * @class THWL_Frontend
 */
class THWL_Frontend {
    // Declare the property to avoid dynamic property deprecation warning
    private $thwl_option;

    private static $styles_enqueued = false;
    
    public function __construct() {
        // Use static method directly, no need to instantiate
        $saved_options = get_option( 'thwl_settings', [] );
        $this->thwl_option = wp_parse_args( $saved_options, THWL_Settings::thwl_get_default_settings() );
        add_filter( 'body_class', [ $this, 'thwl_wishlist_body_class' ] );
        add_action('wp_enqueue_scripts', array( $this, 'thwl_enqueue_styles_scripts' ) );
        //global button
        add_shortcode('thwl_wishlist_button', array( $this,'thwl_add_to_wishlist_button_shortcode'));
        //page shortcode
        add_shortcode('thwl_wishlist', array( $this, 'thwl_wishlist_page_shortcode' ) );
        //flexible shortcode
        add_shortcode('thwl_add_to_wishlist', array( $this, 'thwl_add_to_wishlist_button_flexible_shortcode') );
        // AJAX handlers
        add_action( 'wp_ajax_thwl_add_to_wishlist', array( $this, 'thwl_add_to_wishlist_ajax' ) );
        add_action( 'wp_ajax_nopriv_thwl_add_to_wishlist', array( $this, 'thwl_add_to_wishlist_ajax' ) );
        add_action( 'wp_ajax_thwl_remove_from_wishlist', array( $this, 'thwl_remove_from_wishlist_ajax' ) );
        add_action( 'wp_ajax_thwl_update_item_quantity', array( $this, 'thwl_update_item_quantity_ajax' ) );
        add_action( 'wp_ajax_thwl_add_all_to_cart', array( $this, 'thwl_add_all_to_cart_ajax' ) );
        add_action( 'wp_ajax_nopriv_thwl_add_all_to_cart', array( $this, 'thwl_add_all_to_cart_ajax' ) );
        add_action( 'wp_ajax_thwl_add_to_cart_and_manage', array( $this, 'thwl_add_to_cart_and_manage'));
        add_action( 'wp_ajax_nopriv_thwl_add_to_cart_and_manage', array( $this, 'thwl_add_to_cart_and_manage'));
    }

    public function thwl_enqueue_styles_scripts() {

        if ( self::$styles_enqueued ) {
		return;
        }
        self::$styles_enqueued = true;
        if ( ! defined( 'THWL_PRO_ACTIVE' ) || ! THWL_PRO_ACTIVE ) {
           wp_enqueue_style('thwl', THWL_URL . 'assets/css/wishlist.css', array(),THWL_VERSION);
           wp_register_script( 'thwl', THWL_URL . 'assets/js/wishlist.js', array( 'jquery' ),'1.2.4', array( 
                    'strategy'  => 'async',
                    'in_footer' => false,
            ) );
            wp_enqueue_script( 'thwl' );
        }
        
        wp_add_inline_style('thwl',thwl_add_inline_custom_styles() );

        $wishlist_page_id = ! empty( $this->thwl_option['thwl_page_id'] ) 
        ? $this->thwl_option['thwl_page_id'] 
        : get_option( 'thwl_page_id' );

        $thw_redirect_to_cart = isset($this->thwl_option['thw_redirect_to_cart']) ? $this->thwl_option['thw_redirect_to_cart'] : '';
        wp_localize_script( 'thwl', 'thwl_wishlist_params', array(
            'ajax_url'            => admin_url( 'admin-ajax.php' ),
            'add_nonce'           => wp_create_nonce( 'thwl-add-nonce' ),
            'remove_nonce'        => wp_create_nonce( 'thwl-remove-nonce' ),
            'update_qty_nonce'    => wp_create_nonce( 'thwl-update-qty-nonce' ),
            'add_all_nonce'       => wp_create_nonce( 'thwl-add-all-nonce' ),
            'wishlist_page_url'   => $wishlist_page_id ? get_permalink( $wishlist_page_id ) : '',
            'i18n_added'          => isset($this->thwl_option['thw_browse_wishlist_text']) ? $this->thwl_option['thw_browse_wishlist_text'] : __('Browse Wishlist', 'th-wishlist'),
            'i18n_error'          => __('An error occurred. Please try again.', 'th-wishlist' ),
            'i18n_empty_wishlist' => __('Your wishlist is currently empty.', 'th-wishlist'),
            'redirect_to_cart'    => $thw_redirect_to_cart === '1',
            'cart_url'            => wc_get_cart_url(),
            'icon_style'          => isset($this->thwl_option['thw_button_display_style']) ? $this->thwl_option['thw_button_display_style'] : 'icon_text',
            'redirect_nonce'      => wp_create_nonce('thwl_wishlist_redirect_nonce'),
            'th_wishlist_brws_icon' => isset($this->thwl_option['th_wishlist_brws_icon']) ? $this->thwl_option['th_wishlist_brws_icon'] : 'heart-filled',
            ) );
    }

    public function thwl_wishlist_body_class( $classes ) {

        $wishlist_page_id = get_option( 'thwl_page_id' );

        // Add class only if we are on the wishlist page
        if ( $wishlist_page_id && is_page( $wishlist_page_id ) ) {
            $classes[] = 'thwl-page';
        }

        return $classes;
    }

    /**
     * Wishlist button shortcode with optimized logic and guest popup alert.
     */
    public function thwl_add_to_wishlist_button_shortcode() {

        global $product;

        if (empty($product) || !($product instanceof WC_Product)) {
            return '';
        }

        $output        = '';
        $wishlist      = THWL_Data::get_or_create_wishlist();
        $product_id    = $product->get_id();
        $variation_id  = $product->is_type('variation') ? $product->get_id() : 0;
        $is_logged_in  = is_user_logged_in();

        $in_wishlist = $wishlist && THWL_Data::is_product_in_wishlist(
            $wishlist->id, $product_id, $variation_id
        );

        // Text settings
        $add_text    = $this->thwl_option['thw_add_to_wishlist_text'] ?? __('Add to Wishlist', 'th-wishlist');
        $browse_text = $this->thwl_option['thw_browse_wishlist_text'] ?? __('Browse Wishlist', 'th-wishlist');

        $text   = $in_wishlist ? $browse_text : $add_text;
        $textCls = $in_wishlist ? 'thw-to-browse-text' : 'thw-to-add-text';

        // Button style
        $classes = $in_wishlist ? ['in-wishlist'] : [];
        $btnclasses = '';
        $display_style = $this->thwl_option['thw_button_display_style'] ?? 'icon_text';

        switch ($display_style) {
            case 'icon_only_no_style':
                $classes[] = 'no-style';
                break;
            case 'icon_text':
                $classes[] = 'th-icon-text';
                break;
            case 'icon':
                $classes[] = 'th-icon';
                $btnclasses = 'th-button';
                break;
            case 'text':
                $classes[] = 'th-text';
                break;
        }

        $icons = thwl_get_wishlist_icons_svg();
        $icon_html = '';
        if (in_array($display_style, ['icon', 'icon_text', 'icon_only_no_style'], true)) {
            $icon_key = $in_wishlist ? ($this->thwl_option['th_wishlist_brws_icon'] ?? 'heart-filled')
                                    : ($this->thwl_option['th_wishlist_add_icon'] ?? 'heart-outline');
            $icon_key = isset($icons[$icon_key]) ? $icon_key : 'heart-outline';
            $icon_html = sprintf('<span class="thw-icon">%s</span>', $icons[$icon_key]['svg']);
        }

        if ($display_style === 'icon' || $display_style === 'icon_only_no_style') {
            $text_html = '';
        } elseif ($display_style === 'text') {
            $icon_html = '';
            $text_html = sprintf('<span class="%s">%s</span>', esc_attr($textCls), esc_html($text));
        } else {
            $text_html = sprintf('<span class="%s">%s</span>', esc_attr($textCls), esc_html($text));
        }

        $themedefault = !empty($this->thwl_option['thw_btn_style_theme'])
            ? 'thw-btn-theme-style'
            : 'thw-btn-custom-style';

        $wrap_class = ( is_product() && ! doing_action( 'woocommerce_after_shop_loop_item' ) )
        ? 'th-wishlist-single'
        : '';

        $output .= sprintf('<div class="thw-add-to-wishlist-button-wrap %s %s">', esc_attr($themedefault) , esc_attr($wrap_class));

        $class_attr = trim($btnclasses . ' ' . implode(' ', $classes));

        if (!$is_logged_in && ($this->thwl_option['thw_require_login'] ?? '0') === '1') {

            // 🔥 Login required → show popup alert
            $output .= sprintf(
                '<a class="thw-add-to-wishlist-button %s thw-login-required" data-alert="Required Login">%s%s</a>',
                esc_attr($class_attr),
                $icon_html,
                $text_html
            );

        } else {

            // Normal behavior
            $output .= sprintf(
                '<a class="thw-add-to-wishlist-button %s %s" data-product-id="%s" data-variation-id="%s">%s%s</a>',
                esc_attr($class_attr),
                $in_wishlist ? 'in-wishlist' : '',
                esc_attr($product_id),
                esc_attr($variation_id),
                $icon_html,
                $text_html
            );
        }

        $output .= '</div>';

        return $output;
    }

   public function add_to_wishlist_button(){

   echo do_shortcode('[thwl_wishlist_button]');

   }

   public function thwl_hook_wishlist_loop_button_position() {

    $thw_show_in_loop = isset( $this->thwl_option['thw_show_in_loop'] ) ? $this->thwl_option['thw_show_in_loop'] : 1;

    if ( ! $thw_show_in_loop ) {

		return;
	}

    $position = isset( $this->thwl_option['thw_in_loop_position'] ) ? $this->thwl_option['thw_in_loop_position'] : 'after_crt_btn';

    switch ( $position ) {

            case 'before_crt_btn':
               
                if ( thwl_is_wc_block_template( 'archive-product' ) ) {
                    // For blockified loop: hook before Add to Cart (product-button)
                    add_filter( 'render_block_woocommerce/product-button', array( $this, 'inject_wishlist_in_block' ), 5, 3 );
                } else {
                    // Classic template: hook before Add to Cart in loop
                    add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_to_wishlist_button' ), 7 );
                }
                break;

            case 'after_crt_btn':
                if ( thwl_is_wc_block_template( 'archive-product' ) ) {
                    add_filter( 'render_block_woocommerce/product-button', array( $this, 'inject_wishlist_in_block' ),20, 3  );
                } else {
                    add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_to_wishlist_button' ), 15 );
                }
                break;

            case 'on_top':
                if ( thwl_is_wc_block_template( 'archive-product' ) ) {
                    add_filter( 'render_block_woocommerce/product-image', array( $this, 'inject_wishlist_in_block' ), 10, 3 );
                } else {
                    add_action( 'woocommerce_before_shop_loop_item', array( $this, 'add_to_wishlist_button' ), 5 );
                }
                break;

            case 'on_shortcode':
                // Do not hook automatically
                break;
        }

  }

  public function thwl_hook_wishlist_single_button_position() {

    $thw_show_in_product = isset( $this->thwl_option['thw_show_in_product'] )
    ? (int) $this->thwl_option['thw_show_in_product']
    : 1; 
    $position = isset( $this->thwl_option['thw_in_single_position'] ) ? $this->thwl_option['thw_in_single_position'] : 'after_crt_btn';

    if ( ! is_singular( 'product' )) {
        return; 
    }

    if( $thw_show_in_product == '0' ){
        return; 
    }

    if ( thwl_is_wc_block_template( 'single-product' ) ) {
        
       $this->add_button_for_blockified_template('single-product', $position);
       
    }else{

        switch ( $position ) {
        case 'before_summ':
            // Hook before "Add to Cart" by using before item end
            add_action( 'woocommerce_before_single_product_summary', array( $this, 'add_to_wishlist_button' ), 21 );
            break;

        case 'after_crt_btn':
            add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'add_to_wishlist_button' ), 1 );
            break;

        case 'after_summ':
            add_action( 'woocommerce_after_single_product_summary', array( $this, 'add_to_wishlist_button' ), 11 );
            break;

        case 'on_shortcode':
            // Do not hook automatically
            break;
       }

    }

  }

  /**
 * Inject wishlist button into blockified WooCommerce templates.
 *
 * @param string $template Template slug (e.g., 'single-product').
 * @param string $position Insertion position (e.g., 'after_crt_btn', 'after_thumb', 'after_summ').
 */
    public function add_button_for_blockified_template( $template, $position ) {
        
        $hooked = false;

        switch ( $position ) {
            case 'after_crt_btn':
                //$block = ( 'single-product' === $template ) ? 'add-to-cart-form' : 'product-button';
                add_filter( "render_block_woocommerce/add-to-cart-form", array( $this, 'inject_wishlist_in_block' ), 10, 3 );
                $hooked = true;
                break;

            case 'before_summ':
                add_filter( 'render_block_woocommerce/product-image-gallery', array( $this, 'inject_wishlist_in_block' ), 10, 3 );
                $hooked = true;
                break;

            case 'after_summ':
                add_filter( 'render_block_woocommerce/product-details', array( $this, 'inject_wishlist_in_block' ), 10, 3 );
                $hooked = true;
                break;
        }

        if ( $hooked ) {
            do_action( 'thwl_wishlist_blockified_hook_attached', $template, $position );
        }
    }

    /**
     * Appends the wishlist button HTML to a WooCommerce block's output.
     *
     * @param string $block_content The original block HTML.
     * @param array  $block The full parsed block array.
     * @param WP_Block $instance The block instance.
     * @return string Modified block content.
     */
    public function inject_wishlist_in_block( $block_content, $block, $instance ) {
        ob_start();
        $this->add_to_wishlist_button();
        $wishlist_button_html = ob_get_clean();
        // Append after original content. You could also prepend or place conditionally.
        return $block_content . $wishlist_button_html;
    }

    public function thwl_wishlist_page_shortcode() {

    global $product;
    $output = '';
    $wishlist = null;
    $wishlist_token = isset($_GET['wishlist_token']) ? sanitize_text_field(wp_unslash($_GET['wishlist_token'])) : null;
    $nonce = isset($_GET['wishlist_nonce']) ? sanitize_text_field(wp_unslash($_GET['wishlist_nonce'])) : null;
    $wishlist_action = isset($_GET['wishlist_action']) ? sanitize_text_field(wp_unslash($_GET['wishlist_action'])) : '';
    $is_view_only = ($wishlist_action === 'view');

    if ($wishlist_token) {
        if (!$is_view_only) {
            if (!wp_verify_nonce($nonce, 'thwl_wishlist_nonce_action')) {
                return;
            }
            if (!current_user_can('manage_options')) {
                return;
            }
        }

        $shared_wishlist = THWL_Data::get_wishlist_by_token($wishlist_token);
        if ($shared_wishlist) {
            $is_owner = is_user_logged_in() && $shared_wishlist->user_id === get_current_user_id();

            if ('private' !== $shared_wishlist->privacy || $is_owner || current_user_can('manage_options')) {
                $wishlist = $shared_wishlist;
            } else {
                return '<p>' . esc_html__('This wishlist is private and cannot be viewed.', 'th-wishlist') . '</p>';
            }
        } else {
            return '<p>' . esc_html__('The wishlist you are trying to view does not exist or has been deleted.', 'th-wishlist') . '</p>';
        }
    } else {
        $wishlist = THWL_Data::get_or_create_wishlist();
    }

    if (!$wishlist) {
        return '<p>' . esc_html__('Could not retrieve your wishlist.', 'th-wishlist') . '</p>';
    }

    $items = THWL_Data::get_wishlist_items($wishlist->id);

    $columns = !empty($this->thwl_option['th_wishlist_table_columns'])
        ? $this->thwl_option['th_wishlist_table_columns']
        : [];

    $themedefault = !empty($this->thwl_option['thw_btn_style_theme']) && '1' === $this->thwl_option['thw_btn_style_theme']
        ? 'thw-table-theme-style'
        : 'thw-table-custom-style';

    $output .= '<div class="thw-wishlist-wrapper ' . esc_attr($themedefault) . '">';
    $output .= '<form class="thw-wishlist-form">';
    $output .= '<table class="thw-wishlist-table"><thead><tr>';

    $default_labels = [
        'checkbox' => '<input type="checkbox" id="thw-select-all" />',
        'thumbnail' => esc_html__('Image', 'th-wishlist'),
        'name' => esc_html__('Product', 'th-wishlist'),
        'price' => esc_html__('Price', 'th-wishlist'),
        'stock' => esc_html__('Stock Status', 'th-wishlist'),
        'quantity' => esc_html__('Quantity', 'th-wishlist'),
        'add_to_cart' => esc_html__('Button', 'th-wishlist'),
        'date' => esc_html__('Date Added', 'th-wishlist'),
        'remove' => esc_html__('Remove', 'th-wishlist'),
    ];

    $saved_labels = !empty($this->thwl_option['th_wishlist_table_column_labels'])
        ? $this->thwl_option['th_wishlist_table_column_labels']
        : [];

    foreach ($columns as $key) {

        // Hide quantity & remove columns if not logged in
        if (!is_user_logged_in() && in_array($key, ['remove', 'quantity','checkbox'], true)) {
            continue;
        }

        if ($is_view_only && $key === 'remove') {
            continue;
        }

        if (isset($default_labels[$key])) {
            $label = $key === 'checkbox'
                ? $default_labels['checkbox']
                : (!empty($saved_labels[$key]) ? esc_html($saved_labels[$key]) : $default_labels[$key]);

            $output .= '<th class="product-' . esc_attr($key) . '">' . $label . '</th>';
        }
    }

    $output .= '</tr></thead><tbody>';

    if (!empty($items)) {
        foreach ($items as $item) {
            $_product = wc_get_product($item->variation_id ? $item->variation_id : $item->product_id);
            if (!$_product) continue;

            $output .= '<tr class="thwl-wishlist-item" data-item-id="' . esc_attr($item->id) . '" data-product-id="' . esc_attr($_product->get_id()) . '">';

            foreach ($columns as $key) {

                if (!is_user_logged_in() && in_array($key, ['remove', 'quantity','checkbox'], true)) {
                    continue;
                }

                if ($is_view_only && $key === 'remove') {
                    continue;
                }

                $output .= '<td class="product-' . esc_attr($key) . '">';

                switch ($key) {
                    case 'checkbox':
                        $output .= '<input type="checkbox" name="wishlist_items[]" value="' . esc_attr($item->id) . '">';
                        break;

                    case 'thumbnail':
                        $output .= '<a href="' . esc_url($_product->get_permalink()) . '">' . wp_kses_post($_product->get_image()) . '</a>';
                        break;

                    case 'name':
                        $output .= '<a href="' . esc_url($_product->get_permalink()) . '">' . esc_html($_product->get_name()) . '</a>';
                        if ($_product->is_type('variation')) {
                            $output .= wp_kses_post(wc_get_formatted_variation($_product, true));
                        }
                        break;

                    case 'price':
                        $output .= wp_kses_post($_product->get_price_html());
                        break;

                    case 'stock':
                        $stock_status = $_product->get_stock_status();
                        $output .= '<span class="stock ' . ($stock_status === 'instock' ? 'in-stock' : 'out-of-stock') . '">' .
                                   ($stock_status === 'instock' ? esc_html__('In Stock', 'th-wishlist') : esc_html__('Out of Stock', 'th-wishlist')) .
                                   '</span>';
                        break;

                    case 'quantity':
                        if (is_user_logged_in()) {
                            $output .= '<input type="number" class="thw-qty" value="' . esc_attr($item->quantity) . '" min="1" step="1" data-item-id="' . esc_attr($item->id) . '">';
                        }
                        break;

                    case 'add_to_cart':
                        $output .= '<div class="thw-add-to-cart-cell">' . $this->thw_render_add_to_cart_button($_product, $item, $wishlist) . '</div>';
                        break;

                    case 'date':
                        $output .= '<span>' . esc_html(date_i18n(get_option('date_format'), strtotime($item->added_at))) . '</span>';
                        break;

                    case 'remove':
                        if (is_user_logged_in()) {
                            $output .= '<a href="#" class="thw-remove-item" title="' . esc_attr__('Remove this product', 'th-wishlist') . '">×</a>';
                        }
                        break;
                }

                $output .= '</td>';
            }

            $output .= '</tr>';
        }
    } else {
        $output .= '<tr><td colspan="' . esc_attr(count($columns)) . '">' . esc_html__('Your wishlist is currently empty.', 'th-wishlist') . '</td></tr>';
    }

    $output .= '</tbody></table></form>';
    $output .= '<div class="thw-wishlist-actions">';

    if (is_user_logged_in() && in_array('checkbox', $columns, true) && !empty($items)) {
        $output .= '<button class="button wp-element-button add_to_cart_button thw-add-all-to-cart">' .
                    esc_html__('Add Selected to Cart', 'th-wishlist') .
                    '</button>';
    }

    $output .= $this->render_social_share_links($wishlist);
    $output .= '</div></div>';

    return $output;
}


    public function render_social_share_links( $wishlist ) {
    if (
        empty( $this->thwl_option['thw_show_social_share'] ) ||
        '1' !== $this->thwl_option['thw_show_social_share'] ||
        'private' === $wishlist->privacy ||
        empty( $wishlist->wishlist_token )
    ) {
        return '';
    }

    $output = '';

    $share_url = add_query_arg(
    array(
        'wishlist_token'  => $wishlist->wishlist_token,
        'wishlist_action' => 'view',
    ),
    get_permalink( $this->thwl_option['thwl_page_id'] )
    );

    $encoded_url = urlencode( $share_url );
    $encoded_title = urlencode( __( 'My Wishlist', 'th-wishlist' ) );

    $output .= '<div class="thw-social-share">';
    $output .= sprintf(
        '<span class="thw-social-text">%s</span>',
        esc_html__( 'Share on:', 'th-wishlist' )
    );

    // Facebook
    $output .= sprintf(
        '<a href="%s" target="_blank" title="%s" class="thw-share-facebook"><span class="dashicons dashicons-facebook"></span></a>',
        esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url ),
        esc_attr__( 'Facebook', 'th-wishlist' )
    );

    // Twitter (X)
    $output .= sprintf(
        '<a href="%s" target="_blank" title="%s" class="thw-share-twitter"><span class="dashicons dashicons-twitter"></span></a>',
        esc_url( 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title ),
        esc_attr__( 'X (Twitter)', 'th-wishlist' )
    );

    // WhatsApp
    $output .= sprintf(
        '<a href="%s" target="_blank" title="%s" class="thw-share-whatsapp"><span class="dashicons dashicons-whatsapp"></span></a>',
        esc_url( 'https://wa.me/?text=' . $encoded_title . '%20' . $encoded_url ),
        esc_attr__( 'WhatsApp', 'th-wishlist' )
    );

    // Email
    $output .= sprintf(
        '<a href="%s" title="%s" class="thw-share-email"><span class="dashicons dashicons-email-alt"></span></a>',
        esc_url( 'mailto:?subject=' . $encoded_title . '&body=' . $encoded_url ),
        esc_attr__( 'Email', 'th-wishlist' )
    );

    // Copy link
    $output .= sprintf(
        '<a href="#" class="thw-copy-link-button" data-link="%s" title="%s"><span class="dashicons dashicons-admin-links"></span></a>',
        esc_attr( $share_url ),
        esc_attr__( 'Copy Link', 'th-wishlist' )
    );

    $output .= '</div>';

    return $output;
}

    
    public function thw_render_add_to_cart_button( $product, $item, $wishlist ) {

    if ( $this->thwl_option['thw_redirect_to_cart'] === '1' ) {
        // Build add to cart button HTML
        $button_attributes = [
            'class' => 'button wp-element-button add_to_cart_button thw-add-to-cart-ajax',
            'data-product-id' => esc_attr( $product->get_id() ),
            'data-quantity' => esc_attr( $item->quantity ),
            'data-item-id' => esc_attr( $item->id ),
            'data-wishlist-token' => esc_attr( $wishlist->wishlist_token ),
        ];

        $button_html = sprintf(
            '<button %s>%s</button>',
            implode(' ', array_map(
                function( $key, $value ) { return sprintf( '%s="%s"', $key, $value ); },
                array_keys( $button_attributes ),
                $button_attributes
            )),
            esc_html__( 'Add to Cart', 'th-wishlist' )
        );

        $output = sprintf( '<div class="thw-add-to-cart-cell">%s</div>', $button_html );

    }else{
        
        $custom_product = $product;

        if ( ! $custom_product || ! is_a( $custom_product, 'WC_Product' ) ) {
                return;
            }
            // Set global product to custom product
            global $product;
            $previous_product = $product;
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
            $product = $custom_product;
            // Capture WooCommerce add to cart template output
            ob_start();
            woocommerce_template_loop_add_to_cart( [ 'quantity' => $item->quantity ] );
            $output = ob_get_clean();

            // Restore global product
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
            $product = $previous_product;

            } 

            return $output;
    }
   
    // AJAX Handlers
        public function thwl_add_to_wishlist_ajax() {
        if ($this->thwl_option['thw_require_login'] === '1' && !is_user_logged_in()) {
                wp_send_json_error(['message' => 'login_required']);
                return;
            }
            check_ajax_referer('thwl-add-nonce', 'nonce');
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
            $wishlist = THWL_Data::get_or_create_wishlist();
            if ($wishlist && THWL_Data::add_item($wishlist->id, $product_id)) {
                wp_send_json_success();
            } else {
                wp_send_json_error();
            }
        }

        public function thwl_remove_from_wishlist_ajax() {

    check_ajax_referer( 'thwl-remove-nonce', 'nonce' );

    $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;
    if ( ! $item_id ) {
        wp_send_json_error( [ 'message' => 'invalid_item' ] );
    }

    $item = THWL_Data::get_item( $item_id );
    if ( ! $item ) {
        wp_send_json_error( [ 'message' => 'item_not_found' ] );
    }

    $wishlist = THWL_Data::get_wishlist_by_id( $item->wishlist_id );
    if ( ! $wishlist ) {
        wp_send_json_error( [ 'message' => 'wishlist_not_found' ] );
    }

    $current_user_id = get_current_user_id();
    $guest_token = isset($_COOKIE['thwl_guest_uniqid']) ? sanitize_text_field( wp_unslash($_COOKIE['thwl_guest_uniqid']) ) : '';

    // Logged-in user is owner?
    if ( $wishlist->user_id && intval($wishlist->user_id) === intval($current_user_id) ) {
        $owner_id = $current_user_id;
    }
    // Guest is owner?
    elseif ( $wishlist->session_id && $guest_token === $wishlist->session_id ) {
        $owner_id = 0; // guest mode
    }
    else {
        wp_send_json_error( [ 'message' => 'unauthorized' ] );
    }

    if ( THWL_Data::remove_item( $item_id, $owner_id, $guest_token ) ) {
        wp_send_json_success();
    }

    wp_send_json_error( [ 'message' => 'remove_failed' ] );
}

        public function thwl_update_item_quantity_ajax() {

        if ( !is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => 'not_logged_in' ] );
        }

        check_ajax_referer( 'thwl-update-qty-nonce', 'nonce' );

        $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

        if ( !$item_id || $quantity < 1 ) {
            wp_send_json_error( [ 'message' => 'invalid_input' ] );
        }

        $item = THWL_Data::get_item( $item_id );
        if ( !$item ) {
            wp_send_json_error( [ 'message' => 'item_not_found' ] );
        }

        $wishlist = THWL_Data::get_wishlist_by_id( $item->wishlist_id );
        if ( !$wishlist ) {
            wp_send_json_error( [ 'message' => 'wishlist_not_found' ] );
        }

        // 🔐 Ownership validation — Logged-in and Guest both covered
        $current_user_id = get_current_user_id();
        $guest_token = isset($_COOKIE['thwl_guest_uniqid']) ? sanitize_text_field( wp_unslash($_COOKIE['thwl_guest_uniqid']) ) : '';

        if ( $wishlist->user_id ) {

            if ( intval( $wishlist->user_id ) !== intval( $current_user_id ) ) {
                wp_send_json_error( [ 'message' => 'unauthorized' ] );
            }

        } elseif ( $wishlist->session_id ) {

            if ( empty( $guest_token ) || $wishlist->session_id !== $guest_token ) {
                wp_send_json_error( [ 'message' => 'unauthorized' ] );
            }

        } else {
            wp_send_json_error( [ 'message' => 'invalid_wishlist_owner' ] );
        }

        $updated = THWL_Data::update_item_quantity( $item_id, $quantity );

        if ( $updated !== false ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( [ 'message' => 'update_failed' ] );
        }
    }


    
    public function thwl_add_all_to_cart_ajax() {

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'not_logged_in' ] );
    }

    check_ajax_referer( 'thwl-add-all-nonce', 'nonce' );

    $item_ids = isset($_POST['items']) ? array_map('absint', $_POST['items']) : [];

    if ( empty( $item_ids ) ) {
        wp_send_json_error( [ 'message' => 'no_items_selected' ] );
    }

    $added_count = 0;
    $current_user_id = get_current_user_id();
    $guest_token = isset($_COOKIE['thwl_guest_uniqid']) ? sanitize_text_field( wp_unslash($_COOKIE['thwl_guest_uniqid']) ) : '';

    foreach ( $item_ids as $item_id ) {

        // Fetch item
        $item = THWL_Data::get_item( $item_id );
        if ( ! $item ) {
            continue;
        }

        // Fetch wishlist
        $wishlist = THWL_Data::get_wishlist_by_id( $item->wishlist_id );
        if ( ! $wishlist ) {
            continue;
        }

        // 🔐 Ownership validation — must be owner of wishlist
        if ( $wishlist->user_id ) {
            if ( intval( $wishlist->user_id ) !== intval( $current_user_id ) ) {
                continue;
            }
        } elseif ( $wishlist->session_id ) {
            if ( empty( $guest_token ) || $wishlist->session_id !== $guest_token ) {
                continue;
            }
        } else {
            continue;
        }

        // Add to cart
        $result = WC()->cart->add_to_cart(
            $item->product_id,
            $item->quantity,
            $item->variation_id
        );

        if ( $result ) {
            $added_count++;
        }
    }

    if ( $added_count > 0 ) {
        wp_send_json_success( [
            'message' => 'Products added to cart.',
            'added_count' => $added_count
        ] );
    } else {
        wp_send_json_error( [
            'message' => 'failed_to_add'
        ] );
    }
}

public function thwl_add_to_wishlist_button_flexible_shortcode( $atts = [] ) {

	global $product;

	// Default product ID from global $product
	$default_product_id = ( isset( $product ) && is_a( $product, 'WC_Product' ) ) ? $product->get_id() : '';

	// Merge shortcode attributes
	$atts = shortcode_atts( [
		'product_id'        => $default_product_id,
		'add_text'          => !empty( $this->thwl_option['thw_add_to_wishlist_text'] )
			? $this->thwl_option['thw_add_to_wishlist_text']
			: esc_html__( 'Add to Wishlist', 'th-wishlist' ),
		'browse_text'       => !empty( $this->thwl_option['thw_browse_wishlist_text'] )
			? $this->thwl_option['thw_browse_wishlist_text']
			: esc_html__( 'Browse Wishlist', 'th-wishlist' ),
		'icon_style'        => !empty( $this->thwl_option['thw_button_display_style'] )
			? $this->thwl_option['thw_button_display_style']
			: 'icon_text',
		'add_icon'          => '',
		'add_browse_icon'   => '',
		'custom_class'      => '',
		'theme_style'       => '',
	], $atts, 'thw_add_to_wishlist' );

	// Load product via attribute
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$product = wc_get_product( $atts['product_id'] );
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}

	$product_id   = $product->get_id();
	$variation_id = $product->is_type( 'variation' ) ? $product->get_id() : 0;

	$is_logged_in = is_user_logged_in();

	// 🔐 Login required logic
	if ( !$is_logged_in && ($this->thwl_option['thw_require_login'] ?? '0') === '1' ) {

		$wrap_class = ( is_product() && ! doing_action( 'woocommerce_after_shop_loop_item' ) )
        ? 'th-wishlist-single'
        : '';
		$themedefault = ($atts['theme_style'] === 'yes')
			? 'thw-btn-theme-style'
			: 'thw-btn-custom-style';

		$text    = esc_html__( 'wishlist', 'th-wishlist' );
		$textCls = 'thw-to-add-text';

		// Display icon + label same as style selected
		$icons = thwl_get_wishlist_icons_svg();
		$icon_html = '';
		if ( in_array( $atts['icon_style'], ['icon','icon_text','icon_only_no_style'], true ) ) {
			$default_icon = $this->thwl_option['th_wishlist_add_icon'] ?? 'heart-outline';
			$icon_svg = $icons[$default_icon]['svg'] ?? '';
			$icon_html = sprintf('<span class="thw-icon add">%s</span>', $icon_svg);
		}
		if ($atts['icon_style'] === 'text') {
			$icon_html = '';
		}

		$text_html = in_array($atts['icon_style'], ['icon','icon_only_no_style'])
			? ''
			: sprintf('<span class="%s">%s</span>', esc_attr($textCls), esc_html($text));

		return sprintf(
			'<div class="thw-add-to-wishlist-button-wrap thw-add-to-wishlist-shorcode %s %s">'.
			'<a class="thw-add-to-wishlist-button thw-login-required %s" '.
			'data-alert="%s" data-product-id="%s" data-variation-id="%s">%s%s</a></div>',
			esc_attr($wrap_class),
			esc_attr($themedefault),
			esc_attr($atts['custom_class']),
			esc_attr__('Required Login', 'th-wishlist'),
			esc_attr($product_id),
			esc_attr($variation_id),
			$icon_html,
			$text_html
		);
	}

	// 🔄 Fetch wishlist & check state normally
	$wishlist = THWL_Data::get_or_create_wishlist();
	$in_wishlist = $wishlist
		? THWL_Data::is_product_in_wishlist( $wishlist->id, $product_id, $variation_id )
		: false;

	$text    = $in_wishlist ? $atts['browse_text'] : $atts['add_text'];
	$textCls = $in_wishlist ? 'thw-to-browse-text' : 'thw-to-add-text';

	$classes = $in_wishlist ? ['in-wishlist'] : [];
	if ( $atts['custom_class'] ) $classes[] = $atts['custom_class'];

	$btnclasses = '';
	switch ( $atts['icon_style'] ) {
		case 'icon_only_no_style': $classes[] = 'no-style'; break;
		case 'icon_text':          $classes[] = 'th-icon-text'; break;
		case 'icon':
			$classes[] = 'th-icon';
			$btnclasses = 'th-button';
			break;
		case 'text':               $classes[] = 'th-text'; break;
	}
	$class_attr = implode(' ', array_filter($classes));

	$icons = thwl_get_wishlist_icons_svg();
	$icon_html = '';

	if ( in_array( $atts['icon_style'], ['icon','icon_text','icon_only_no_style'], true ) ) {
		if ( $in_wishlist ) {
			$type = $atts['add_browse_icon'] ?: ($this->thwl_option['th_wishlist_brws_icon'] ?? 'heart-filled');
			$type = isset($icons[$type]) ? $type : 'heart-filled';
			$icon_html = sprintf('<span class="thw-icon browse">%s</span>', $icons[$type]['svg']);
		} else {
			$type = $atts['add_icon'] ?: ($this->thwl_option['th_wishlist_add_icon'] ?? 'heart-outline');
			$type = isset($icons[$type]) ? $type : 'heart-outline';
			$icon_html = sprintf('<span class="thw-icon add">%s</span>', $icons[$type]['svg']);
		}
	}

	$text_html = ($atts['icon_style'] === 'text')
		? sprintf('<span class="%s">%s</span>', esc_attr($textCls), esc_html($text))
		: (($atts['icon_style'] === 'icon') ? '' : sprintf('<span class="%s">%s</span>', esc_attr($textCls), esc_html($text)));

	$wrap_class = ( is_product() && ! doing_action( 'woocommerce_after_shop_loop_item' ) )
        ? 'th-wishlist-single'
        : '';
	$themedefault = ($atts['theme_style'] === 'yes') ? 'thw-btn-theme-style' : 'thw-btn-custom-style';

	return sprintf(
		'<div class="thw-add-to-wishlist-button-wrap thw-add-to-wishlist-shorcode %s %s">'.
		'<a class="thw-add-to-wishlist-button is-shortcode %s %s" '.
		'data-browse-text="%s" data-product-id="%s" data-variation-id="%s" '.
		'data-add-icon="%s" data-browse-icon="%s">%s%s</a></div>',
		esc_attr($wrap_class),
		esc_attr($themedefault),
		esc_attr($btnclasses),
		esc_attr($class_attr),
		esc_attr($atts['browse_text']),
		esc_attr($product_id),
		esc_attr($variation_id),
		esc_attr($atts['add_icon']),
		esc_attr($atts['add_browse_icon']),
		$icon_html,
		$text_html
	);
}


// ajax mange table function
/**
 * Handle adding product to cart and managing wishlist via AJAX.
 */
public function thwl_add_to_cart_and_manage() {

    check_ajax_referer( 'thwl_wishlist_redirect_nonce', 'nonce' );

    $product_id = absint($_POST['product_id'] ?? 0);
    $quantity   = max(1, absint($_POST['quantity'] ?? 1));
    $item_id    = absint($_POST['item_id'] ?? 0);

    if (!$product_id || !$item_id) {
        wp_send_json_error(['message' => 'invalid_input']);
    }

    $product = wc_get_product($product_id);
    if (!$product || !$product->is_purchasable()) {
        wp_send_json_error(['message' => 'not_purchasable']);
    }

    if (!$product->is_in_stock()) {
        wp_send_json_error(['message' => 'out_of_stock']);
    }

    // Add to cart for both Guest + User
    WC()->cart->add_to_cart($product_id, $quantity);

    // ❌ Guests can't remove wishlist DB items → Stop here
    if (!is_user_logged_in()) {
        wp_send_json_success([
            'message'  => 'added_to_cart',
            'cart_url' => wc_get_cart_url()
        ]);
    }

    // Fetch wishlist ownership check
    $item = THWL_Data::get_item($item_id);
    if (!$item) wp_send_json_error(['message' => 'item_not_found']);

    $wishlist = THWL_Data::get_wishlist_by_id($item->wishlist_id);
    if (!$wishlist) wp_send_json_error(['message' => 'wishlist_not_found']);

    if ((int)$wishlist->user_id !== (int)get_current_user_id()) {
        wp_send_json_error(['message' => 'unauthorized']);
    }

    // Remove item ONLY IF logged-in & setting enabled
    if (!empty($this->thwl_option['thw_redirect_to_cart']) 
        && '1' === $this->thwl_option['thw_redirect_to_cart']) {

        THWL_Data::remove_item($item_id);
    }

    wp_send_json_success([
        'message'  => 'added_to_cart',
        'cart_url' => wc_get_cart_url()
    ]);
}

}