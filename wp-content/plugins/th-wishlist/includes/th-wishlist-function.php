<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'thwl_is_wc_block_template' ) ) {

	    function thwl_is_wc_block_template( string $template_name ): bool {
        
		static $cache = array();

		if ( isset( $cache[ $template_name ] ) ) {
			return $cache[ $template_name ];
		}

		// Default false
		$cache[ $template_name ] = false;

		// WooCommerce >= 7.9 and block theme required
		if ( ! function_exists( 'WC' ) || version_compare( WC()->version, '7.9.0', '<' ) || ! wp_is_block_theme() ) {
			return false;
		}

		// Default blockified templates from WC 7.9+
		$blockified_templates = array(
			'archive-product',
			'product-search-results',
			'single-product',
			'taxonomy-product_attribute',
			'taxonomy-product_cat',
			'taxonomy-product_tag',
			'cart',
			'checkout',
		);

		$templates = get_block_templates( array( 'slug__in' => array( $template_name ) ) );

		$is_block_template = function ( string $content ) use ( $template_name ): bool {
			switch ( $template_name ) {
				case 'cart':
					return has_block( 'woocommerce/cart', $content );
				case 'checkout':
					return has_block( 'woocommerce/checkout', $content );
				default:
					return ! has_block( 'woocommerce/legacy-template', $content );
			    }
		};

		// If the template exists
		if ( ! empty( $templates ) && isset( $templates[0]->content ) ) {
			$content = $templates[0]->content;

			if ( ! $is_block_template( $content ) ) {
				return $cache[ $template_name ] = false;
			}

			// Check for patterns embedded inside
			if ( has_block( 'core/pattern', $content ) ) {
				foreach ( parse_blocks( $content ) as $block ) {
					if ( $block['blockName'] === 'core/pattern' ) {
						$slug = $block['attrs']['slug'] ?? '';
						if ( $slug && WP_Block_Patterns_Registry::get_instance()->is_registered( $slug ) ) {
							$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
							if ( isset( $pattern['content'] ) && ! $is_block_template( $pattern['content'] ) ) {
								return $cache[ $template_name ] = false;
							}
						}
					}
				}
			}

			// Valid block template
			return $cache[ $template_name ] = true;

		} elseif ( in_array( $template_name, $blockified_templates, true ) ) {
			// Template is blockified by default
			return $cache[ $template_name ] = true;
		}

		return $cache[ $template_name ];
	}
}

// get icon
function thwl_get_wishlist_icons_svg() {
    $addicondashicons = array(
        'heart-outline' => array(
            'name' => 'Heart Outline',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>'
        ),
        'heart-filled' => array(
            'name' => 'Heart Filled',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>'
        ),
        'star-outline' => array(
            'name' => 'Star Outline',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21L12 17.77L5.82 21L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>'
        ),
        'star-filled' => array(
            'name' => 'Star Filled',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21L12 17.77L5.82 21L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>'
        ),
        'bookmark-outline' => array(
            'name' => 'Bookmark Outline',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M6.32 2.577c2.83-.33 5.66-.33 8.49 0 1.497.174 2.57 1.46 2.57 2.93V21l-6.165-3.583-7.165 3.583V5.507c0-1.47 1.073-2.756 2.57-2.93Z"/></svg>'
        ),
        'bookmark-filled' => array(
            'name' => 'Bookmark Filled',
            'svg' => '<svg class="th-wishlist-icon-svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"/></svg>'
        ),
    );
    return $addicondashicons;
}

/******************************************/
// init code loop button or single button
/******************************************/

add_action('wp','thwl_hook_wishlist_loop_button_position');
add_action('wp','thwl_hook_wishlist_single_button_position');

function thwl_hook_wishlist_loop_button_position() {
    if ( class_exists( 'THWL_Frontend' ) ) {
        $object = new THWL_Frontend();
        $object->thwl_hook_wishlist_loop_button_position();
    }
}

function thwl_hook_wishlist_single_button_position() {
    if ( class_exists( 'THWL_Frontend' ) ) {
        $object = new THWL_Frontend();
        $object->thwl_hook_wishlist_single_button_position();
    }
}

/*******************************************/
// Redirect wishlist page icon shorcode
/********************************************/
function thwl_wishlist_redirect_shortcode() {
    $options = get_option( 'thwl_settings', [] );
    // Get Wishlist page URL
    $wishlist_url = thwl_wishlist_redirect_url();
    // Load all available icons
    $icons = function_exists( 'thwl_get_wishlist_icons_svg' ) ? thwl_get_wishlist_icons_svg() : [];
    // Selected icon + color from settings
	$options['thw_redirect_wishlist_page_icon'];
    $selected_icon_key = ! empty( $options['thw_redirect_wishlist_page_icon'] )
        ? $options['thw_redirect_wishlist_page_icon']
        : 'heart-outline';

    $icon_color = ! empty( $options['thw_redirect_wishlist_page_icon_color'] )
        ? $options['thw_redirect_wishlist_page_icon_color']
        : '#111';

    // Allowed SVG tags for sanitization
    $allowed_svg_tags = array(
        'svg'  => array(
            'class'        => true,
            'width'        => true,
            'height'       => true,
            'viewbox'      => true,
            'fill'         => true,
            'stroke'       => true,
            'stroke-width' => true,
            'xmlns'        => true,
        ),
        'path' => array(
            'd'              => true,
            'fill'           => true,
            'stroke'         => true,
            'stroke-linecap' => true,
            'stroke-linejoin'=> true,
            'clip-rule'      => true,
            'fill-rule'      => true,
        ),
    );

    // Get icon SVG if available
    $icon_html = '';
    if ( isset( $icons[ $selected_icon_key ]['svg'] ) ) {
        // Add color attribute
        $icon_svg = str_replace(
            '<svg',
            '<svg',
            $icons[ $selected_icon_key ]['svg']
        );
        $icon_html = sprintf(
            '<span class="thwl-page-redirect-icon" style="display:inline-flex;">%s</span>',
             wp_kses( $icon_svg, $allowed_svg_tags )
        );
    }

    // Build final HTML
    $html  = '<a style="display:inline-flex;" class="thwl-page-redirect-whishlist" href="' . esc_url( $wishlist_url ) . '">';
    $html .= $icon_html; // Inject icon
    $html .= '</a>';

    return $html;
}
add_shortcode( 'thwl_wishlist_redirect', 'thwl_wishlist_redirect_shortcode' );

function thwl_wishlist_redirect_url(){
    $wishlist_page_id = '';
    if ( class_exists( 'THWL_Wishlist' ) ){
        $wishlist_page_id = get_option('thwl_page_id');
    }
    $wishlist_permalink = $wishlist_page_id ? get_the_permalink( $wishlist_page_id ) : home_url();
    return $wishlist_permalink;
}

/**
 * Generate and add dynamic inline CSS for TH Wishlist based on user settings.
 */
function thwl_add_inline_custom_styles() {

    // Helper function to safely get option value
    if ( ! function_exists( 'thwl_get_option_value' ) ) {
        function thwl_get_option_value( $options, $key ) {
            return is_array( $options ) && isset( $options[ $key ] ) && $options[ $key ] !== '' ? esc_attr( $options[ $key ] ) : false;
        }
    }

    // Always ensure this is an array
    $th_wishlist_option = get_option( 'thwl_settings', [] );
    if ( ! is_array( $th_wishlist_option ) ) {
        $th_wishlist_option = [];
    }
    $custom_css = '';

    /* --- Add to wishlist icon color --- */
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_add_icon_color' ) ) {
        $custom_css .= ".thw-btn-custom-style .thw-add-to-wishlist-button .thw-icon { color: {$color}; }";
    }

    /* --- In wishlist icon color --- */
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_brws_icon_color' ) ) {
        $custom_css .= ".thw-btn-custom-style .thw-add-to-wishlist-button.in-wishlist .thw-icon { color: {$color}; }";
    }

    /* --- Button text color --- */
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_btn_txt_color' ) ) {
        $custom_css .= "
            .thw-btn-custom-style .thw-add-to-wishlist-button,
            .thw-btn-custom-style .thw-add-to-wishlist-button .thw-to-add-text,
            .thw-btn-custom-style .thw-add-to-wishlist-button .thw-to-browse-text {
                color: {$color};
            }";
    }

    /* --- Table button text and background --- */
    $tb_btn_txt = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_tb_btn_txt_color' );
    $tb_btn_bg  = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_tb_btn_bg_color' );
    if ( $tb_btn_txt || $tb_btn_bg ) {
        $custom_css .= "
            .thw-table-custom-style .thw-wishlist-actions .thw-add-all-to-cart,
            .thw-table-custom-style .thw-add-to-cart-cell .button {
                " . ( $tb_btn_txt ? "color: {$tb_btn_txt};" : '' ) . "
                " . ( $tb_btn_bg ? "background: {$tb_btn_bg};" : '' ) . "
            }";
    }

    /* --- Table colors --- */
    $tb_txt = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_table_txt_color' );
    $tb_bg  = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_table_bg_color' );
    if ( $tb_txt || $tb_bg ) {
        $custom_css .= "
            .thw-table-custom-style .thw-wishlist-table {
                " . ( $tb_txt ? "color: {$tb_txt};" : '' ) . "
                " . ( $tb_bg ? "background: {$tb_bg};" : '' ) . "
            }";
    }

    /* --- Table border --- */
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'th_wishlist_table_brd_color' ) ) {
        $custom_css .= "
            .thw-table-custom-style .thw-wishlist-table th,
            .thw-table-custom-style .thw-wishlist-table td {
                border-color: {$color};
            }";
    }

    /* --- Social share colors --- */
    $socials = [
        'facebook' => ['th_wishlist_shr_fb_color', 'th_wishlist_shr_fb_hvr_color'],
        'twitter'  => ['th_wishlist_shr_x_color',  'th_wishlist_shr_x_hvr_color'],
        'whatsapp' => ['th_wishlist_shr_w_color',  'th_wishlist_shr_w_hvr_color'],
        'email'    => ['th_wishlist_shr_e_color',  'th_wishlist_shr_e_hvr_color'],
        'copy-link-button' => ['th_wishlist_shr_c_color', 'th_wishlist_shr_c_hvr_color'],
    ];

    foreach ( $socials as $class => $keys ) {
        $normal = thwl_get_option_value( $th_wishlist_option, $keys[0] );
        $hover  = thwl_get_option_value( $th_wishlist_option, $keys[1] );
        if ( $normal || $hover ) {
            $custom_css .= ".thw-table-custom-style .thw-social-share a.thw-share-{$class} { " . ( $normal ? "color: {$normal};" : '' ) . " }";
            if ( $hover ) {
                $custom_css .= ".thw-table-custom-style .thw-social-share a.thw-share-{$class}:hover { color: {$hover}; }";
            }
        }
    }

    /* --- Redirect icon size & color --- */
    if ( $size = thwl_get_option_value( $th_wishlist_option, 'thw_redirect_wishlist_page_icon_size' ) ) {
        $custom_css .= ".thwl-page-redirect-icon svg { height: {$size}px; width: {$size}px; }";
    }
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'thw_redirect_wishlist_page_icon_color' ) ) {
        $custom_css .= ".thwl-page-redirect-whishlist .thwl-page-redirect-icon { color: {$color}; }";
    }
    if ( $color = thwl_get_option_value( $th_wishlist_option, 'thw_redirect_wishlist_page_icon_color_hvr' ) ) {
        $custom_css .= ".thwl-page-redirect-whishlist:hover .thwl-page-redirect-icon { color: {$color}; }";
    }

    // Finally add inline style if CSS exists
    return $custom_css;
}
