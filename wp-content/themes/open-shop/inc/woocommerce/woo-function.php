<?php 
/**
 * Perform all main WooCommerce configurations for this theme
 *
 * @package  Open Shop WordPress theme
 */
// If plugin - 'WooCommerce' not exist then return.
if ( ! class_exists( 'WooCommerce' ) ){
	   return;
}
/*******************************/
/** Sidebar Add Cart Product **/
/*******************************/
if ( ! function_exists( 'open_shop_cart_total_item' ) ){
  /**
   * Cart Link
   * Displayed a link to the cart including the number of items present and the cart total
   */
 function open_shop_cart_total_item(){
   global $woocommerce; 
  ?>
  <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart','open-shop' ); ?>"><i class="fa fa-shopping-basket"></i> <span class="cart-content"><?php echo sprintf ( _n( '<span class="count-item">%d <span class="item">'.__('item','open-shop').'</span></span>', '<span class="count-item">%d <span class="item">'.__('items','open-shop').'</span></span>', WC()->cart->get_cart_contents_count() ,'open-shop'), WC()->cart->get_cart_contents_count(),'open-shop' ); ?><?php echo WC()->cart->get_cart_total(); ?></span></a>
  <?php }
}


// check wihsh list
if(!function_exists('open_shop_whishlist_check')){

function open_shop_whishlist_check($pid){
      if( class_exists( 'THWL_Wishlist' ) ){
        echo open_shop_whish_list($pid);
        }

     if( class_exists( 'th_product_compare' ) || class_exists('Tpcp_product_compare') ){
        echo open_shop_add_to_compare_fltr($pid);
        }

}

}


/***********************************************/
//Sort section Woocommerce category filter show
/***********************************************/
function open_shop_add_to_cart_url($product){
 $cart_url =  apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button th-button %s %s"><span>%s</span></a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( $product->get_id() ),
        esc_attr( $product->get_sku() ),
        esc_attr( isset( $quantity ) ? $quantity : 1 ),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->is_purchasable() && $product->is_in_stock() && $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
        esc_html( $product->add_to_cart_text() )
    ),$product );
 return $cart_url;
}

/**********************************/
//Shop Product Markup
/**********************************/
if ( ! function_exists( 'open_shop_product_meta_start' ) ){
  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_meta_start(){
    echo '<div class="thunk-product-wrap"><div class="thunk-product">';
  }
}
if ( ! function_exists( 'open_shop_product_meta_end' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_meta_end(){

    echo '</div></div>';
  }
}
/**********************************/
//Shop Product Image Markup
/**********************************/
if ( ! function_exists( 'open_shop_product_image_start' ) ){
  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_image_start(){
    echo '<div class="thunk-product-image">';
  }
}
if ( ! function_exists( 'open_shop_product_image_end' ) ){

  /**
   * Thumbnail wrap start.
   */
    function open_shop_product_image_end(){
    do_action('quickview');
    echo '</div>';
  }
}

if ( ! function_exists( 'open_shop_product_content_start' ) ){
  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_content_start(){
    echo '<div class="thunk-product-content">';
  }
}
if ( ! function_exists( 'open_shop_product_content_end' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_content_end(){

    echo '</div>';
  }
}
 /**
   * Thunk-product-hover start.
   */
 if ( ! function_exists( 'open_shop_product_hover_start' ) ){
  function open_shop_product_hover_start(){

    echo'<div class="thunk-product-hover">';
    // do_action('open_shop_wishlist');
    // do_action('open_shop_compare');
      
  }
}
if ( ! function_exists( 'open_shop_product_hover_end' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_product_hover_end(){
    
    echo '</div>';
  }
}

if ( ! function_exists( 'open_shop_shop_content_start' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_shop_content_start(){
    
    $viewshow = get_theme_mod('open_shop_prd_view','grid-view');
    if($viewshow == 'grid-view'){
    echo '<div id="shop-product-wrap" class="thunk-grid-view">';
    }else{
    echo '<div id="shop-product-wrap" class="thunk-list-view">';
    }
  }
}

if ( ! function_exists( 'open_shop_shop_content_end' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_shop_content_end(){
    
    echo '</div>';
  }
}
/**
* Shop customization.
*
* @return void
*/
add_action( 'woocommerce_before_shop_loop_item', 'open_shop_product_meta_start', 10);
add_action( 'woocommerce_after_shop_loop_item', 'open_shop_product_meta_end', 12 );
add_action( 'woocommerce_before_shop_loop_item_title', 'open_shop_product_content_start',20);
add_action( 'woocommerce_after_shop_loop_item_title', 'open_shop_product_content_end', 20 );
add_action( 'woocommerce_after_shop_loop_item_title', 'open_shop_product_hover_start',50);
add_action( 'woocommerce_after_shop_loop_item', 'open_shop_product_hover_end',20);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open',5);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close',10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open',15);
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close',1);
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price',0);
add_action( 'woocommerce_before_shop_loop_item_title', 'open_shop_product_image_start', 0);
add_action( 'woocommerce_before_shop_loop_item_title', 'open_shop_product_image_end',10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_show_product_sale_flash',4);
add_action( 'woocommerce_after_shop_loop_item', 'open_shop_whish_list',11);
add_action( 'woocommerce_after_shop_loop_item', 'open_shop_add_to_compare_fltr',11);

add_action( 'woocommerce_before_shop_loop', 'open_shop_shop_content_start',1);
add_action( 'woocommerce_after_shop_loop', 'open_shop_shop_content_end',1);

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
remove_action('woocommerce_init','th_compare_add_action_shop_list');
//To disable th compare Pro button 
remove_action('woocommerce_init', 'tpcp_add_action_shop_list');

// To disable Wishlist button for loop button at shop page
remove_action( 'wp', 'thwl_hook_wishlist_loop_button_position');

// To disable Wishlist button for loop button at single page
// remove_action( 'wp', 'thwl_hook_wishlist_single_button_position');

/***************/
// single page
/***************/
if ( ! function_exists( 'open_shop_single_summary_start' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_single_summary_start(){
    
    echo '<div class="thunk-single-product-summary-wrap">';
  }
}
if( ! function_exists( 'open_shop_single_summary_end' ) ){

  /**
   * Thumbnail wrap start.
   */
  function open_shop_single_summary_end(){
    
    echo '</div>';
  }
}
add_action( 'woocommerce_before_single_product_summary', 'open_shop_single_summary_start',0);
add_action( 'woocommerce_after_single_product_summary', 'open_shop_single_summary_end',0);


/***********************/
// Th Compare Product 
/**********************/

function open_shop_add_to_compare_fltr($pid){ 
  global $product;
  $pid = $product->get_id();
  if( shortcode_exists('th_compare')){ ?>
   
      <?php echo do_shortcode('[th_compare pid="' . esc_attr($pid) . '"]'); ?>
    
          <?php  }
    }




/**********************/
/** THWL_Wishlist **/
/**********************/
function open_shop_whish_list($pid){
       global $product;
   $pid = $product->get_id();  
        if ( shortcode_exists( 'thwl_add_to_wishlist' ) ) { ?>
            <div class="thunk-wishlist"><span class="thunk-wishlist-inner">
              <?php echo do_shortcode('[thwl_add_to_wishlist 
                product_id="' . esc_attr($pid) . '" 
                add_icon="th-icon th-icon-heart1" 
                add_text="Wishlist"
                add_browse_icon="th-icon th-icon-favorite"
                browse_text="Added"
                theme_style="yes"
                custom_class="th-wishlist-integrated"
              ]'); ?>
              </span></div>
      <?php  }
      elseif( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ){?>
        <div class="thunk-wishlist"><span class="thunk-wishlist-inner"><?php echo do_shortcode('[yith_wcwl_add_to_wishlist  product_id='.esc_attr($pid).' icon="th-icon th-icon-favorite" label='.__('wishlist','open-shop').'
         already_in_wishslist_text='.__('Already','open-shop').' browse_wishlist_text='.__('Added','open-shop').']' );?></span></div>
      <?php  }


 }         


/****************/
// WPC add to compare
/****************/

function open_shop_wpc_add_to_compare_fltr($pid){
    if( class_exists( 'WPCleverWoosc' ) ){
           echo '<div class="thunk-compare"><span class="compare-list"><div class="woocommerce product compare-button">'.do_shortcode('[woosc id='.$pid.']').'</div></span></div>';
         }
     }


function open_shop_whishlist_url(){
  $wishlist_page_id = '';
  if (class_exists( 'THWL_Wishlist' )) {
    $wishlist_page_id =  get_option( 'thwl_page_id' );
  }
  elseif( class_exists( 'YITH_WCWL' ) ){
    $wishlist_page_id =  get_option( 'yith_wcwl_wishlist_page_id' );
  }
$wishlist_permalink = get_the_permalink( $wishlist_page_id );
return $wishlist_permalink ;
}
// shop open
/** My Account Menu **/
function open_shop_account(){
 if ( is_user_logged_in() ) {
  $return = '<a class="account" href="'.esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )).'" title="Account"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-6 h-6 stroke-[1.5px]" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><span class="account-text tooltip">Account</span></a>';
  } 
 else {
  $return = '<span><a href="'.esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )).'" title="Account"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-6 h-6 stroke-[1.5px]" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><span class="account-text tooltip">Account</span></a></span>';
}
 echo $return;
}
// Plus Minus Quantity Buttons @ WooCommerce Single Product Page
add_action( 'woocommerce_before_add_to_cart_quantity', 'open_shop_display_quantity_minus',10,2 );
function open_shop_display_quantity_minus(){
    echo '<div class="open-shop-quantity"><button type="button" class="minus" >-</button>';
}
add_action( 'woocommerce_after_add_to_cart_quantity', 'open_shop_display_quantity_plus',10,2 );
function open_shop_display_quantity_plus(){
    echo '<button type="button" class="plus" >+</button></div>';

}

//Woocommerce: How to remove page-title at the home/shop page archive & category pages
add_filter('woocommerce_show_page_title', '__return_null');

function open_shop_not_a_shop_page() {
    return boolval(!is_shop());
}
//************************************************************************************************//
// *****************************HOME PAGE WOO FUNCTION****************************************//
//************************************************************************************************//
//***********************/
// product category list
//************************/
// function open_shop_product_list_categories( $args = '' ){
//     $defaults = array(
//         'child_of'            => 0,
//         'current_category'    => 0,
//         'depth'               => 5,
//         'echo'                => 0,
//         'exclude'             => '',
//         'exclude_tree'        => '',
//         'feed'                => '',
//         'feed_image'          => '',
//         'feed_type'           => '',
//         'hide_empty'          => 1,
//         'hide_title_if_empty' => false,
//         'hierarchical'        => true,
//         'order'               => 'ASC',
//         'orderby'             => 'menu_order',
//         'separator'           => '<br />',
//         'show_count'          => 0,
//         'show_option_all'     => '',
//         'show_option_none'    => __( 'No categories','open-shop' ),
//         'style'               => 'list',
//         'taxonomy'            => 'product_cat',
//         'title_li'            => '',
//         'use_desc_for_title'  => 0,
//     );
//  $html = wp_list_categories($defaults);
//         echo '<ul class="product-cat-list thunk-product-cat-list" data-menu-style="vertical">'.$html.'</ul>';
//   }

function open_shop_product_list_categories( $args = '' ) {
    // Get the list of category slugs from the theme mod
    $include_slugs = get_theme_mod('open_shop_header_category_list');

    // Initialize an empty array to store category IDs
    $include_ids = array();

    // Check if the slugs array is not empty
    if ( ! empty( $include_slugs ) && is_array( $include_slugs ) ) {
        // Loop through each slug and get the corresponding category ID
        foreach ( $include_slugs as $slug ) {
            $term = get_term_by( 'slug', $slug, 'product_cat' );
            if ( $term && ! is_wp_error( $term ) ) {
                $include_ids[] = $term->term_id;
            }
        }
    }

    // Convert the array of IDs to a comma-separated list
    $include_ids = implode( ',', $include_ids );

    // Set up the default arguments for wp_list_categories
    $defaults = array(
        'include'             => $include_ids,  // Use the IDs we've just generated
        'child_of'            => 0,
        'current_category'    => 0,
        'depth'               => 5,
        'echo'                => 0,
        'exclude'             => '',
        'exclude_tree'        => '',
        'feed'                => '',
        'feed_image'          => '',
        'feed_type'           => '',
        'hide_empty'          => 1,
        'hide_title_if_empty' => false,
        'hierarchical'        => true,
        'order'               => 'ASC',
        'orderby'             => 'menu_order',
        'separator'           => '<br />',
        'show_count'          => 0,
        'show_option_all'     => '',
        'show_option_none'    => __( 'No categories', 'open-shop' ),
        'style'               => 'list',
        'taxonomy'            => 'product_cat',
        'title_li'            => '',
        'use_desc_for_title'  => 0,
    );

    // Merge any additional arguments passed to the function
    $args = wp_parse_args( $args, $defaults );

    // Get the categories list
    $html = wp_list_categories( $args );

    // Output the categories list
    echo '<ul class="product-cat-list thunk-product-cat-list" data-menu-style="vertical">' . $html . '</ul>';
}



/*****************************/
// Product show function
/****************************/
function  open_shop_widget_product_query($query){
$productType = $query['prd-orderby'];
$count = $query['count'];
$cat_slug = $query['cate'];
global $th_cat_slug;
$th_cat_slug = $cat_slug;
        $args = array(
            'hide_empty' => 1,
            'posts_per_page' => $count,        
            'post_type' => 'product',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        );
       if($productType == 'featured'){
       // featured product
            $args['meta_query'] =  array(
                'key'   => '_featured',
                'value' => 'yes'
            );
        } 
        elseif($productType == 'random'){
            //random product
          $args['orderby'] = 'rand';
        }
        elseif($productType == 'sale') {
          //sale product
        $args['meta_query']     = array(
        'relation' => 'OR',
        array( // Simple products type
            'key'           => '_sale_price',
            'value'         => 0,
            'compare'       => '>',
            'type'          => 'numeric'
        ),
        array( // Variable products type
            'key'           => '_min_variation_sale_price',
            'value'         => 0,
            'compare'       => '>',
            'type'          => 'numeric'
        )
    );
}
$args['meta_key'] = '_thumbnail_id';
if($cat_slug != '0'){
                //$args['product_cat'] = $cat_slug;
                $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $cat_slug,
                            )
                         );
     }
$return = new WP_Query($args);
return $return;
}
/*****************************/
// Product show function
/****************************/
function open_shop_post_query($query){

       $args = array(
            'orderby' => $query['orderby'],
            'order' => 'DESC',
            'ignore_sticky_posts' => $query['sticky'],
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $query['count'], 
            'cat' => $query['cate'],
            'meta_key'     => '_thumbnail_id',
           
        );

       if($query['thumbnail']){
          $args['meta_key'] = '_thumbnail_id';
       }

            $return = new WP_Query($args);

            return $return;
}
