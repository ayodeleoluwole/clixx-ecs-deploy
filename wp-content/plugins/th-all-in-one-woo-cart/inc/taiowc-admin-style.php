<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue admin styles and apply conditional inline CSS
 */
function taiowc_admin_style() {
	$css = '';

	// Conditional CSS rules based on plugin options
	if ( taiowc()->get_option( 'taiowc-show_cart' ) == false ) {
		$css .= '#cart_style-wrapper, #taiowc-cart_open-wrapper, #taiowc_cart_styletaiowc_cart_style-section-1, .taiowc_cart_styletaiowc_cart_style-section-1 { display: none; }';
	}

	if ( taiowc()->get_option( 'cart_style' ) === 'style-2' ) {
		$css .= '#taiowc-fxd_cart_frm_left-wrapper, #taiowc-fxd_cart_frm_right-wrapper, #taiowc-fxd_cart_frm_btm-wrapper { display: none; }';
	}

	if ( taiowc()->get_option( 'taiowc-fxd_cart_position' ) === 'fxd-left' ) {
		$css .= '#taiowc-fxd_cart_frm_right-wrapper { display: none; }';
	}

	if ( taiowc()->get_option( 'taiowc-fxd_cart_position' ) === 'fxd-right' ) {
		$css .= '#taiowc-fxd_cart_frm_left-wrapper { display: none; }';
	}

	if ( taiowc()->get_option( 'taiowc-cart-icon' ) === 'icon-7' ) {
		$css .= '#icon_url-wrapper { display: contents; }';
	}

	if ( taiowc()->get_option( 'taiowc-cart_pan_notify_shw' ) == false ) {
		$css .= '#taiowc-success_mgs_bg_clr-wrapper, #taiowc-success_mgs_txt_clr-wrapper, #taiowc-error_mgs_bg_clr-wrapper, #taiowc-error_mgs_txt_clr-wrapper { display: none; }';
	}

	if ( taiowc()->get_option( 'taiowc-cart_pan_icon_shw' ) == false ) {
		$css .= '#taiowc-cart_pan_icon_clr-wrapper { display: none; }';
	}

	// Only add inline style if needed
	if ( ! empty( $css ) ) {
		return $css;
	}
}
