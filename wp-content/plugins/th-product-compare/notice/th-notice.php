<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( is_plugin_active( 'th-product-compare-pro/th-product-compare-pro.php' ) ) {
    return;
}

if ( ! class_exists( 'Th_Product_Compare_Notice' ) ) {

    class Th_Product_Compare_Notice {

        public function __construct() {

            // Handle dismiss action securely.
           // Handle dismiss action securely
                if (
                    isset( $_GET['ntc-cmpr-disable'], $_GET['_wpnonce'] ) &&
                    '1' === sanitize_text_field( wp_unslash( $_GET['ntc-cmpr-disable'] ) ) &&
                    wp_verify_nonce(
                        sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
                        'thpc_notice_nonce'
                    )
                ) {
                    add_action( 'admin_init', array( $this, 'set_cookie' ) );
                }


            if ( ! isset( $_COOKIE['thntc_time'] ) ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
                add_action( 'admin_notices', array( $this, 'conditionally_display_notice' ) );
            } else {
                add_action( 'admin_notices', array( $this, 'maybe_unset_cookie' ) );
            }
        }

        public function enqueue_styles() {
            wp_enqueue_style(
                'th-product-compare-notice-style',
                esc_url( TH_PRODUCT_URL . 'notice/css/th-notice.css' ),
                array(),
                '1.0.0'
            );
        }

    public function conditionally_display_notice() {

    $screen = get_current_screen();

    if ( ! $screen ) {
        return;
    }

    if (
        'plugins' === $screen->id ||
        'toplevel_page_th-product-compare' === $screen->id
    ) {
        $this->render_notice();
    }
}


        private function render_notice() {
            ?>

            <div class="th-product-compare-notice notice">
                <div class="th-product-compare-notice-wrap">

                    <div class="th-product-compare-notice-image">
                        <img src="<?php echo esc_url( TH_PRODUCT_URL . 'notice/img/compare-pro.png' ); ?>"
                            alt="<?php echo esc_attr__( 'TH Product Compare Pro', 'th-product-compare' ); ?>">
                    </div>

                    <div class="th-product-compare-notice-content-wrap">
                        <div class="th-product-compare-notice-content">

                            <p class="th-product-compare-heading">
                                <?php esc_html_e(
                                    "Let's remove users confusion & help them choose the correct product. Make product selection easy & advanced using Compare Pro.",
                                    'th-product-compare'
                                ); ?>
                            </p>

                            <p>
                                <?php esc_html_e(
                                    'Filter similarities and differences in the compare table for fast and easy comparison. Show custom and global attributes and order them as you like.',
                                    'th-product-compare'
                                ); ?>
                            </p>

                        </div>

                        <a target="_blank"
                            href="<?php echo esc_url( 'https://themehunk.com/th-product-compare-plugin/' ); ?>"
                            class="upgradetopro-btn">
                            <?php esc_html_e( 'Upgrade To Pro', 'th-product-compare' ); ?>
                        </a>
                    </div>

                    <a href="<?php echo esc_url(
                        wp_nonce_url(
                            add_query_arg( 'ntc-cmpr-disable', '1' ),
                            'thpc_notice_nonce'
                        )
                    ); ?>"
                    class="ntc-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon">
                    </a>

                </div>
            </div>

            <?php
        }

        public function set_cookie() {

            $expiry = time() + ( 15 * DAY_IN_SECONDS );

            setcookie(
                'thntc_time',
                $expiry,
                $expiry,
                COOKIEPATH,
                COOKIE_DOMAIN
            );
        }

        public function maybe_unset_cookie() {

            $cookie_time = isset( $_COOKIE['thntc_time'] )
                ? (int) sanitize_text_field( wp_unslash( $_COOKIE['thntc_time'] ) )
                : 0;

            if ( $cookie_time < time() ) {

                setcookie(
                    'thntc_time',
                    '',
                    time() - DAY_IN_SECONDS,
                    COOKIEPATH,
                    COOKIE_DOMAIN
                );
            }
        }
    }

    new Th_Product_Compare_Notice();
}
