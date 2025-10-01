<?php if ( ! defined( 'ABSPATH' ) ) exit;
$cartPosition = taiowc()->get_option( 'taiowc-fxd_cart_position' );
$cartPositionMobile = taiowc()->get_option( 'taiowc-fxd_cart_mobile_position' );
$cartPositionMobile = 'th-mobile-' . $cartPositionMobile;
$openStyle = taiowc()->get_option( 'taiowc-cart_effect' );
?>
<!-- preview wrapper -->
			<div class="setting-preview-wrap live-mobile" data-tab="taiowc_mobile_cart">
				    <div class="page-wrapper">
       

        <!-- Empty content area -->
        <div class="content-area">
            
            <div class="phone">
    <div class="screen">

        <!-- HEADER -->
        <div class="header">
            <div class="logo">
                <span class="logo-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panels-top-left" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18"></path><path d="M9 21V9"></path></svg></span>
                <span class="logo-text"><?php esc_html_e('MEGASTORE','th-all-in-one-woo-cart');  ?></span>
            </div>

            
             <!-- Cart -->
            <div class="cart">
                <div id="3" class="taiowc-wrap taiowc-slide-right fxd-right" data-th-bg="taiowc-bg_clr" data-th-toggle="taiowc-dsble_mnu_crt-field" data-th-toggle-mode="hide">
    <a class="taiowc-content" href="#">
        <div class="taiowc-cart-item">

            <div class="taiowc-icon-wrapper">
                <div class="taiowc-icon" data-th-color="taiowc-cart_icon_clr"  data-th-width="taiowc-icon_size-field">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-bag">
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                        <path d="M3.103 6.034h17.794"></path>
                        <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path>
                    </svg>
                </div>

                <div class="cart-count-item"  data-th-bg="taiowc-quantity_bg_clr"
                      data-th-color="taiowc-quantity_clr"
                      data-th-border="taiowc-price_clr" data-th-shadow="taiowc-quantity_shadow_clr" data-th-shadow-val="0 0 10px {color}"
                      data-th-toggle="taiowc-dsble_mnu_crt_qnty-field" data-th-toggle-mode="hide"
                      >2</div>
            </div>

            <div class="taiowc-total" data-th-color="taiowc-price_clr" data-th-font-size="taiowc-prc_font_size-field" data-th-unit="px" data-th-toggle="taiowc-dsble_mnu_crt_price-field" data-th-toggle-mode="hide">
                <span class="woocommerce-Price-amount amount">
                     <?php echo wp_kses_post( wc_price( 1008 ) ); ?>
                </span>
            </div>

        </div>
    </a>
</div>

            </div>

        </div>

        <!-- FLASH SALE -->
        <div class="flash">
            <h2><?php esc_html_e( 'Flash Sale', 'th-all-in-one-woo-cart' ); ?></h2>
            <p><?php esc_html_e( 'ENDING IN 02:45:00', 'th-all-in-one-woo-cart' ); ?></p>
        </div>

        <!-- PRODUCTS -->
        <div class="grid">
            <div class="card">
                <img src="<?php echo esc_url(TAIOWC_PLUGIN_URI.'/images/product-1.jpg'); ?>" />
                <span class="add">+</span >
                <div class="skeleton title"></div>
                <div class="skeleton price"></div>
            </div>

            <div class="card">
                <img src="<?php echo TAIOWC_PLUGIN_URI.'/images/product-2.jpg'; ?>" />
                <span class="add">+</span>
                <div class="skeleton title"></div>
                <div class="skeleton price"></div>
            </div>

            <div class="card">
                <img src="<?php echo TAIOWC_PLUGIN_URI.'/images/product-3.jpg'; ?>" />
                <span class="add">+</span>
                <div class="skeleton title"></div>
                <div class="skeleton price"></div>
            </div>

            <div class="card">
                <img src="<?php echo TAIOWC_PLUGIN_URI.'/images/product-4.jpg'; ?>" />
                <span class="add">+</span>
                <div class="skeleton title"></div>
                <div class="skeleton price"></div>
            </div>
        </div>

            <!-- Fixed Cart -->
           <div id="3" class="taiowc-wrap taiowc-slide-right taiowc-fixed-cart fxd-right cart_fixed_1 <?php echo esc_attr($cartPosition); ?> <?php echo esc_attr($cartPositionMobile); ?>" data-th-right="taiowc-fxd_cart_frm_right-field" data-th-bottom="taiowc-fxd_cart_frm_btm-field" data-th-left="taiowc-fxd_cart_frm_leftfield" 
           data-th-toggle="taiowc-dsble_fxd_crt-field" data-th-toggle-mode="hide">

    <a class="taiowc-content" href="#">

        <div class="cart-count-item" data-th-bg="taiowc-fxcrt_qnty_bg_clr" data-th-color="taiowc-fxcrt_qnty_clr" data-th-toggle="taiowc-dsble_fxd_crt_qnty-field" data-th-toggle-mode="hide">
            2
        </div>

        <div class="taiowc-cart-item" data-th-bg="taiowc-fxcrt_cart_bg_clr" data-th-radius="taiowc-fxcrt_icon_brd_rds-field">

            <div class="taiowc-icon" data-th-color="taiowc-fxcrt_cart_icon_clr" data-th-width="taiowc-fxcrt_icon_size-field">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24"
                     height="24"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="lucide lucide-shopping-bag w-6 h-6 stroke-[1.5px]"
                     aria-hidden="true">
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                    <path d="M3.103 6.034h17.794"></path>
                    <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path>
                </svg>
            </div>

            <div class="taiowc-total" data-th-color="taiowc-fxcrt_cart_tle_clr" data-th-toggle="taiowc-show_price-field">
                <span class="woocommerce-Price-amount amount">
                    <?php echo wp_kses_post( wc_price( 1008 ) ); ?>
                </span>
            </div>

        </div>

    </a>

</div>


<!-- Cart Panel Popup -->
<div class="cart-panel-preview">
 <div class="cart-wrapper <?php echo esc_attr($openStyle); ?>" data-th-bg="taiowc-cart_pan_bg_clr">
    <div class="cart-header" data-th-bg="taiowc-cart_pan_hdr_bg_clr">
        <div class="cart-title" data-th-color="taiowc-cart_pan_icon_clr">
            <div  data-th-toggle="taiowc-cart_pan_icon_shw-field">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag">
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                            <path d="M3.103 6.034h17.794"></path>
                            <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path>
                        </svg>
            </div>
             <span data-th-color="taiowc-cart_pan_hd_clr" data-th-text-html="taiowc-cart_hd-field"><?php echo esc_html($cartStyle); ?></span>
            <span class="cart-count">3</span>
        </div>
        <button type="button" class="close-btn" data-th-color="taiowc-cart_pan_cls_clr">✕</button>
    </div>

    <div class="cart-items">
        <div class="cart-item" data-th-bg="taiowc-cart_pan_prd_bg_clr" data-th-color="taiowc-cart_pan_prd_txt_clr" data-th-border="taiowc-cart_pan_prd_brd_clr">
            <img src="<?php echo esc_url( TAIOWC_PLUGIN_URI.'/images/product-1.jpg' ); ?>" alt="">
            <div class="item-info">
                <h4 data-th-color="taiowc-cart_pan_prd_tle_clr"><?php esc_html_e( 'Classic Red Sneakers', 'th-all-in-one-woo-cart' ); ?></h4>
                <div class="rating" data-th-color="taiowc-cart_pan_prd_rat_clr"><?php esc_html_e( '★★★★★', 'th-all-in-one-woo-cart' ); ?></div>
                <div class="qty">
                    <span >-</span>
                    <span>1</span>
                    <span>+</span>
                </div>
            </div>
            <div class="price"><?php esc_html_e( '₹400.00', 'th-all-in-one-woo-cart' ); ?></div>
        </div>

        <div class="cart-item" data-th-bg="taiowc-cart_pan_prd_bg_clr" data-th-color="taiowc-cart_pan_prd_txt_clr" data-th-border="taiowc-cart_pan_prd_brd_clr">
            <img src="<?php echo esc_url( TAIOWC_PLUGIN_URI.'/images/product-2.jpg' ); ?>" alt="">
            <div class="item-info">
                <h4 data-th-color="taiowc-cart_pan_prd_tle_clr"><?php esc_html_e( 'Minimalist White Tee', 'th-all-in-one-woo-cart' ); ?></h4>
                <div class="rating" data-th-color="taiowc-cart_pan_prd_rat_clr"><?php esc_html_e( '★★★★★', 'th-all-in-one-woo-cart' ); ?></div>
                <div class="qty">
                    <span>-</span>
                    <span>1</span>
                    <span>+</span>
                </div>
            </div>
            <div class="price"><?php esc_html_e( '₹250.00', 'th-all-in-one-woo-cart' ); ?></div>
        </div>
    </div>

    <div class="summary" data-th-bg="taiowc-cart_pan_pay_bg_clr" data-th-color="taiowc-cart_pan_pay_txt_clr">
        <p class="summary-title" data-th-bg="taiowc-cart_pan_pay_hd_bg_clr" data-th-color="taiowc-cart_pan_pay_hd_clr"><?php esc_html_e( 'ORDER SUMMARY', 'th-all-in-one-woo-cart' ); ?></p>

        <div class="row">
            <span><?php esc_html_e( 'Subtotal', 'th-all-in-one-woo-cart' ); ?></span>
            <span><?php esc_html_e( '₹1,220.00', 'th-all-in-one-woo-cart' ); ?></span>
        </div>

        <div class="row total">
            <span><?php esc_html_e( 'ORDER TOTAL', 'th-all-in-one-woo-cart' ); ?></span>
            <span><?php esc_html_e( '₹720.00', 'th-all-in-one-woo-cart' ); ?></span>
        </div>
    </div>

    <button type="button"class="checkout-btn" data-th-bg="taiowc-cart_pan_pay_btn_bg_clr" data-th-color="taiowc-cart_pan_pay_btn_clr">
        <?php esc_html_e( 'PROCEED TO CHECKOUT →', 'th-all-in-one-woo-cart' ); ?>
    </button>
</div>

</div>

    </div>
</div>


        </div>
    </div>
				
			   </div>
<!-- preview wrapper -->  