<?php

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'Taiowc_Cart_Fragment' ) ):

    class Taiowc_Cart_Fragment {

        /**
         * Member Variable
         *
         * @var object instance
         */
        private static $instance;

        /**
         * Initiator
         */
        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self();

            }

            return self::$instance;

        }

        /**
         * Constructor
         */

        public function __construct(){
            add_action( 'wc_ajax_get_refreshed_fragments', array( $this, 'get_refreshed_fragments' ) );
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'taiowc_cart_show' ));
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'taiowc_cart_item_show' ));    
        }

        public function get_refreshed_fragments(){
            
        WC_AJAX::get_refreshed_fragments();

        }


        public function taiowc_cart_show($fragments){

             ob_start();

            ?>
                       <a class="taiowc-content" href="#">
                        
                          

                           <?php if ( ! WC()->cart->is_empty() ) {
                            ?>
                            <div class="cart-count-item">

                                <?php taiowc()->get_cart_count() ?>
                                    
                            </div>
                        <?php } ?>
                           
                            <div class="taiowc-cart-item">
                                <div class="taiowc-icon">
                                    <?php do_action('taiowc_cart_show_icon'); ?>
                                 </div>
                                 <?php if ( ! WC()->cart->is_empty() ) { 

                                    if(taiowc()->get_option( 'taiowc-tpcrt_show_price' ) == true){ 

                                        ?>
                                 <div class="taiowc-total">

                                    <span><?php echo wp_kses_post(WC()->cart->get_cart_total()); ?></span>

                                </div>
                                <?php } } ?>
                            </div>
                        </a>
                

        <?php 
                    $fragments['a.taiowc-content'] = ob_get_clean();
                    
                    return $fragments;


      }



    /**
     * Output updated cart modal content for fragments
     */
    public function taiowc_cart_item_show( $fragments ) {
        ob_start();

        ?>
        <div class="taiowc-cart-model-wrap">
            <?php 
            // Optional notices (like item added to cart)
            if ( function_exists( 'taiowc' ) ) {
                taiowc()->taiowc_print_notices_html( 'cart' );
            }

            // Header section
            taiowc_markup()->taiowc_cart_header();
            ?>

            <div class="taiowc-cart-model-body">
                <?php do_action( 'taiowc_mini_cart' ); ?>
            </div>

            <div class="taiowc-cart-model-footer">
                <?php 
                if ( class_exists( 'WooCommerce' ) && ! WC()->cart->is_empty() ) {
                    taiowc_markup()->taiowc_cart_footer();
                }
                ?>
    
                    <p class="copyright"><?php esc_html_e( 'Powered by ', 'th-all-in-one-woo-cart' ); ?><a href="<?php echo esc_url('https://themehunk.com/'); ?>"><?php esc_html_e('ThemeHunk','th-all-in-one-woo-cart'); ?> <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" aria-hidden="true"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg></a></p>

            </div>
        </div>
        <?php

        // Capture output buffer
        $fragments['div.taiowc-cart-model-wrap'] = ob_get_clean();

        return $fragments;
      }
    } 
endif; 
Taiowc_Cart_Fragment::get_instance();