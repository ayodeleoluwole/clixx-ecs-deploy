<?php
/**
 * Perform WooCommerce function with Ajax
 *
 * @package Open WordPress theme
 */
add_action( 'wp_ajax_open_shop_product_remove', 'open_shop_product_remove' );
add_action( 'wp_ajax_nopriv_open_shop_product_remove', 'open_shop_product_remove' );
function  open_shop_product_remove(){
    global $woocommerce;
    $cart = $woocommerce->cart;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item){
    if($cart_item['product_id'] == $_POST['product_id'] ){
        // Remove product in the cart using  cart_item_key.
        $cart->remove_cart_item($cart_item_key);
        woocommerce_mini_cart();
        exit();
      }
    }
  die();
}

function open_shop_product_count_update(){
   global $woocommerce; 
  ?>
<span class="cart-content"><?php echo sprintf ( _n( '<span class="count-item">%d <span class="item">item</span></span>', '<span class="count-item">%d <span class="item">'.__('items','open-shop').'</span></span>', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?><?php echo WC()->cart->get_cart_total(); ?></span>
<?php 
  die();
}
add_action( 'wp_ajax_open_shop_product_count_update', 'open_shop_product_count_update' );
add_action( 'wp_ajax_nopriv_open_shop_product_count_update', 'open_shop_product_count_update' );

/**
 * Live autocomplete search feature.
 *
 * @since 1.0.0
 */
function open_shop_search_site(){

  if (isset($_POST['match']) && $_POST['match'] != '') {
    if (isset($_POST['cat']) && $_POST['cat'] !== '' && $_POST['cat'] !== '0') {
      $category_ = sanitize_text_field($_POST['cat']);
      $taxsrch = array(
        array(
          'taxonomy' => 'product_cat',
          'field' => 'slug',
          'terms' => $category_,
        ),
      );
    } else {
      $taxsrch = '';
    }
    $match_ = sanitize_text_field($_POST['match']);
    $results = new WP_Query(array(
      'post_type'     => 'product',
      'post_status'   => 'publish',
      'nopaging'      => true,
      'posts_per_page' => 100,
      's'             => $match_,
      'tax_query' => $taxsrch,
    ));
    $items = array();
    if (!empty($results->posts)) {
      foreach ($results->posts as $result) {
        $product = wc_get_product($result->ID);
        $items[] = array(
          'label' => $result->post_title,
          'link' => get_permalink($result->ID),
          'imglink' => wp_get_attachment_url($product->get_image_id()),
          // 'imglink' => get_the_post_thumbnail($result->ID, 'thumbnail'),
          'price' => $product->get_price_html(),
          'urli' => $urli
        );
      }
    }
    wp_send_json_success($items);
  }

}
add_action( 'wp_ajax_open_shop_search_site',        'open_shop_search_site' );
add_action( 'wp_ajax_nopriv_open_shop_search_site', 'open_shop_search_site' );