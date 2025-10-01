<?php

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'Taiowc_Markup' ) ):

    class Taiowc_Markup {
         /**
         * Member Variable
         *
         * @var object instance
         */
       
       private static $instance;

       /**
         * Initiator
         */
        public static function instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        public function __construct(){
            
        // Render the cart modal only once in the footer
        add_action( 'wp_footer', [ $this, 'render_cart_modal_once' ],999 );

        }

        public function render_cart_modal_once() {
        static $rendered = false;
        if ( $rendered ) {
            return;
        }
        $rendered = true;

        // Output the modal HTML only once
        $this->taiowc_cart_item_show();
        }

        public function taiowc_cart_show(){
    
            if ( class_exists( 'WooCommerce' ) && ! is_null( WC()->cart ) ) {
            ?>
               
                       <a class="taiowc-content" href="#">
                           
                        

                            <?php if ( ! WC()->cart->is_empty() ) { ?>

                            <div class="cart-count-item">
                                
                                <?php taiowc()->get_cart_count(); ?>
                                    
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
                

        <?php } 
    }


        public function taiowc_cart_item_show(){ 
            
            ?>

            <div class="taiowc-model-wrap">
                <div class="taiowc-model-overlay"></div>
            <div class="taiowc-cart-model">   

               <div class="taiowc-cart-model-wrap">

                    <?php $this->taiowc_cart_header();?>

                    <div class="taiowc-cart-model-body">
                        
                        <?php 

                        do_action('taiowc_mini_cart'); 


                        ?>

                    </div>

                    <div class="taiowc-cart-model-footer">

                     <?php 

                     $this->taiowc_cart_footer(); 
                    
                    ?>

                     <p class="copyright"><?php esc_html_e( 'Powered by ', 'th-all-in-one-woo-cart' ); ?><a href="<?php echo esc_url('https://themehunk.com/'); ?>"><?php esc_html_e('ThemeHunk','th-all-in-one-woo-cart'); ?> <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" aria-hidden="true"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg></a></p>
                     
                   </div>

                   

               </div>
              

                    <div class="taiowc-notice-box">

                    <span class="taiowc-notice"></span>

                    </div>

             
            </div>

            </div>

        <?php }


        public function taiowc_cart_header(){?>


                    <div class="taiowc-cart-model-header">

                        <div class="cart-heading">

                            <?php do_action('taiowc_cart_show_icon');?>

                           <?php if(taiowc()->get_option( 'taiowc-cart_hd' )!==''){ ?>
                          <h4><?php echo wp_kses_post(taiowc()->get_option( 'taiowc-cart_hd' ));?></h4>
                           <?php } ?>

                        </div> 
                        <a class="taiowc-cart-close"></a>
                    </div>


        <?php }


        public function taiowc_cart_footer(){ ?>

                    <?php   

                     $this->taiowc_cart_total();

                     $this->taiowc_cart_button(); 

                    ?>
        <?php }
        
          public function taiowc_cart_total(){  
        

            ?>
                <div class="cart-total">
                    <span class="taiowc-payment-title"><?php esc_html_e('Payment Details','th-all-in-one-woo-cart'); ?></span>

                     <div class="taiowc-total-wrap">
                                
                            <div class="taiowc-subtotal">
                                <span class="taiowc-label"><?php esc_html_e('Sub Total','th-all-in-one-woo-cart'); ?></span>
                                <span class="taiowc-value"><?php if(WC()->cart){
                                    echo wp_kses_post(WC()->cart->get_cart_subtotal());
                                 } ?></span>
                              </div>

                   </div>

                </div>


       <?php  }

        
        public function taiowc_cart_button(){ ?>
                

                     <div class="cart-button">
                            
                        <p class="buttons normal">

                        <?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
                            
                        </p>
                              
                     </div>

       <?php  }

        

}

function taiowc_markup(){

        return Taiowc_Markup::instance();

}
endif; 