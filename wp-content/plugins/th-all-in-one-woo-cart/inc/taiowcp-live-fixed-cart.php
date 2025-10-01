<?php if ( ! defined( 'ABSPATH' ) ) exit;
$cartPosition = taiowc()->get_option( 'taiowc-fxd_cart_position' );
$cartStyle = taiowc()->get_option( 'taiowc-cart_style' );
?>
<!-- preview wrapper -->
			<div class="setting-preview-wrap" data-tab="taiowc_fixed_cart">
				    <div class="page-wrapper">
        <header class="site-header">
            <!-- Logo -->
            <div class="logo">
                <span class="logo-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panels-top-left" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18"></path><path d="M9 21V9"></path></svg></span>
                <span class="logo-text"><?php esc_html_e('MEGASTORE','th-all-in-one-woo-cart');  ?></span>
            </div>

            <!-- Navigation -->
            <nav class="main-nav">
                <a href="#"><?php esc_html_e('Home','th-all-in-one-woo-cart');  ?></a>
                <a href="#"><?php esc_html_e('Shop','th-all-in-one-woo-cart');  ?></a>
                <a href="#"><?php esc_html_e('Categories','th-all-in-one-woo-cart');  ?></a>
            </nav>

            <!-- Cart -->
            <div class="cart">
                <div id="3" class="taiowc-wrap taiowc-slide-right fxd-right" data-th-bg="taiowc-bg_clr">
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
                      data-th-toggle="taiowc-show_quantity-field">2</div>
            </div>

            <div class="taiowc-total" data-th-color="taiowc-price_clr" data-th-font-size="taiowc-prc_font_size-field" data-th-unit="px">
                <span class="woocommerce-Price-amount amount">
                    <?php echo wp_kses_post( wc_price( 1008 ) ); ?>
                </span>
            </div>

        </div>
    </a>
</div>

            </div>
        </header>

        <!-- Empty content area -->
        <div class="content-area">  
            <div id="3" class="taiowc-wrap taiowc-slide-right taiowc-fixed-cart cart_fixed_1 <?php echo esc_attr($cartPosition); ?> <?php echo esc_attr($cartStyle); ?>" data-th-right="taiowc-fxd_cart_frm_right-field" data-th-bottom="taiowc-fxd_cart_frm_btm-field" data-th-left="taiowc-fxd_cart_frm_left-field">

    <a class="taiowc-content" href="#">

        <div class="cart-count-item" data-th-bg="taiowc-fxcrt_qnty_bg_clr" data-th-color="taiowc-fxcrt_qnty_clr" data-th-toggle="taiowc-fxcrt_show_quantity-field">
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
                <span>
                    <span class="woocommerce-Price-amount amount">
                    <?php echo wp_kses_post( wc_price( 1008 ) ); ?>
                </span>
                </span>
            </div>

        </div>

    </a>

</div>

        </div>
    </div>
				
			   </div>
<!-- preview wrapper -->  