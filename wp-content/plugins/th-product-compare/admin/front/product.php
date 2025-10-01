<?php
if (!defined('ABSPATH')) exit;
class th_product_compare_return
{
    public static function get()
    {
        return new self();
    }
    private function __construct()
    {
        add_action('wp_ajax_th_get_compare_product', array($this, 'get_products'));
        add_action('wp_ajax_nopriv_th_get_compare_product', array($this, 'get_products'));
    }
    // public function get_products()
    // {
    //     /* ---------- NONCE CHECK (BEST WAY) ---------- */
    // check_ajax_referer( 'th_product_compare_nonce', 'nonce' );

    //     if ( isset($_POST['product_id']) && ( intval($_POST['product_id']) || $_POST['product_id'] === 'refresh' ) ) {

    //         $productID = $_POST['product_id'] === 'refresh' ? 'refresh' : intval($_POST['product_id']);

    //         $addREmove = sanitize_text_field($_POST['add_remove']);

    //         $setID = $this->setId_cookie($productID, $addREmove);

    //         if (!empty($setID)) {

    //             $html = $this->productHtml($setID);

    //             if (isset($setID['product_limit'])) {

    //                 $html['product_limit'] = __('Product Limit Exceeded.', 'th-product-compare');

    //             }

    //             $return = $html;

    //         } else {

    //             $return = ['no_product' => 1];

    //         }

    //         wp_send_json($return);

    //     }

    // }
    
    public function get_products() {

    /* ---------- NONCE CHECK (BEST WAY) ---------- */
    check_ajax_referer( 'th_product_compare_nonce', 'nonce' );

    /* ---------- PRODUCT ID ---------- */
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Missing product id', 'th-product-compare' ) ) );
    }

    $raw_product = sanitize_text_field( wp_unslash( $_POST['product_id'] ) );

    if ( 'refresh' === $raw_product ) {
        $productID = 'refresh';
    } else {
        $productID = intval( $raw_product );
        if ( $productID <= 0 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid product id', 'th-product-compare' ) ) );
        }
    }

    /* ---------- ACTION ---------- */
    if ( ! isset( $_POST['add_remove'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Missing action', 'th-product-compare' ) ) );
    }

    $addREmove = sanitize_text_field( wp_unslash( $_POST['add_remove'] ) );

    /* ---------- PROCESS ---------- */
    $setID = $this->setId_cookie( $productID, $addREmove );

    if ( ! empty( $setID ) ) {

        $html = $this->productHtml( $setID );

        if ( isset( $setID['product_limit'] ) ) {
            $html['product_limit'] = __( 'Product Limit Exceeded.', 'th-product-compare' );
        }

        wp_send_json( $html );

    } else {

        wp_send_json( array( 'no_product' => 1 ) );

    }
}


  public function productHtml($setID, $type_ = [])
{

    $product_details_keys = array(
    'image',
    'title',
    'SKU',
    'price',
    'add-to-cart',
);
    $removeBtn = true;
    if (isset($type_['remove_btn'])) {
        $removeBtn = $type_['remove_btn'];
    }
    $chekBYoption = $this->compareOption(); 

    $footerProduct = '';
    $wp_is_mobile = wp_is_mobile();
    $mobileClass = $wp_is_mobile ? 'mobile-flex' : '';
    $table = '';
    $table .= '<table class="product-table-configure woocommerce ' . $mobileClass . '">';
    $initTitleAndRow = [];

    if (!empty($chekBYoption['attributes'])) {
        foreach ($chekBYoption['attributes'] as $title_key => $title_value) {
            
    // 🔒 Skip only merged fields
    if (in_array($title_key, $product_details_keys, true)) {
        continue;
    }
            if ($title_value['active'] == 1) {
                unset($title_value['active']);
                $checkCustomAttr = isset($title_value['custom']) ? true : false;
                $name_ = $checkCustomAttr ? $title_value['label'] : str_replace("-", " ", $title_key);
                $putHtml = ''; 
                    $putHtml .= '<tr class="_' . esc_attr($title_key) . '_"><td class="left-title">';
                    if ($name_ != 'image') {
                        $putHtml .= '<span>' . esc_html($name_) . '</span>';
                    }
                    $putHtml .= '</td>';
                
                $title_value['html'] = $putHtml;
                $initTitleAndRow[$title_key] = $title_value;
            }
        }

    $pd_html = '';


 if ($wp_is_mobile) {
   $pd_html .= '<tr class="_product_details_"><td class="left-title">';
    $pd_html .= '<span>' . esc_html__('Product Details', 'th-product-compare') . '</span>';
    $pd_html .= '</td>';
} else {
    $pd_html .= '<tr class="_product_details_"><td class="left-title">';
    $pd_html .= '<span>' . esc_html__('Product Details', 'th-product-compare') . '</span>';
    $pd_html .= '</td>';
}


// Add row at TOP (before others)
$initTitleAndRow = array_merge(
    array(
        'product_details' => array(
            'html' => $pd_html,
            'type' => 'product_details',
        ),
    ),
    $initTitleAndRow
);

    }

    if ($chekBYoption['field-repeat-price']) {

            $trRepeatPrice_ = '<tr class="th-price">
                <td class="left-title"><span>' . esc_html__('Price', 'th-product-compare') . '</span></td>';

    }

    if ($chekBYoption['field-repeat-add-to-cart']) {

            $trRepeatAddTocart = '<tr class="th-add-to-cart">
                 <td class="left-title"><span>' . esc_html__('Add To Cart', 'th-product-compare') . '</span></td>';

    }

    if ($removeBtn) {

            $trDelete_ = '<tr class="th-delete">
                 <td class="left-title"><span>' . esc_html__('Remove', 'th-product-compare') . '</span></td>';
        
    }

    $add_TR_AT_last = 0;
    $count_length_last = count($setID);
    $countProductsForFooter = 0;

    foreach ($setID as $IDvalue) {
        $ProductID = intval($IDvalue);
        if ($ProductID) {
            $add_TR_AT_last++;
            $countProductsForFooter++;
            $CheckLAstProduct = $count_length_last == $add_TR_AT_last ? true : false;
            $product = wc_get_product($ProductID);
            if (!$product) continue;
            $price_ = '<span class="price_">' . $product->get_price_html() . '</span>';
            $Add_to_cart_ = '<div class="th-add-to-cart_"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart" aria-hidden="true"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>' . $this->add_to_cart($product) . '</div>';
            $link_ = esc_url(get_permalink($ProductID));

            foreach ($initTitleAndRow as $initTitleAndRow_key => $initTitleAndRow_value) {
                $addMoreHtml = '';
                // ❌ Skip individual rows
                if (in_array($initTitleAndRow_key, ['image','title','price','SKU','add-to-cart'])) {
                    continue;
                }
                     $addMoreHtml = '';
                    if ($initTitleAndRow_key === 'product_details') {

    $addMoreHtml  = '<div class="pc-product-details">';

    // IMAGE
    $addMoreHtml .= '<div class="_image_"><div class="image-and-addcart"><div class="img_"><a target="_blank" href="' . $link_ . '">' . $product->get_image() . '</a></div></div></div>';

    // TITLE
    $addMoreHtml .= '<div class="_title_"><div class="product-title_"><a target="_blank" href="' . $link_ . '">' . esc_html($product->get_name()) . '</a></div></div>';

    // SKU
    $sku = $product->get_sku() ?: '-';
    $addMoreHtml .= '<div class="_SKU_">' . esc_html($sku) . '</div>';

    // PRICE
    $addMoreHtml .= '<div class="_price_"><div class="price_">' . $price_ . '</div></div>';

    // ADD TO CART
    $addMoreHtml .= '<div class="_add-to-cart_">' . $Add_to_cart_ . '</div>';

    $addMoreHtml .= '</div>';

    $addHtml = '<td class="">' . $addMoreHtml . '</td>';
    if ($CheckLAstProduct) $addHtml .= '</tr>';

    $initTitleAndRow_value['html'] .= $addHtml;
    $initTitleAndRow[$initTitleAndRow_key] = $initTitleAndRow_value;

    continue;
} 
                elseif ($initTitleAndRow_key == 'image') {
                    $addMoreHtml .= '<div class="image-and-addcart">';
                    $addMoreHtml .= '<div class="img_ product-comp">';
                    $addMoreHtml .= '<a target="_blank" href="' . $link_ . '">' . $product->get_image() . '</a>';
                    $addMoreHtml .= '</div>';
                    $addMoreHtml .= '</div>';
                } elseif ($initTitleAndRow_key == 'title') {
                    $addMoreHtml .= '<span class="product-title_"><a target="_blank" href="' . $link_ . '">' . esc_html($product->get_name()) . '</a></span>';
                } elseif ($initTitleAndRow_key == 'price') {
                    $addMoreHtml .= $price_;
                } elseif ($initTitleAndRow_key == 'add-to-cart') {
                    $addMoreHtml .= $Add_to_cart_;
                } elseif ($initTitleAndRow_key == 'SKU') {
                    $sku = $product->get_sku();
                    $sku = $sku ? $sku : "-";
                    $addMoreHtml .= '<span>' . esc_html($sku) . '</span>';
                } elseif ($initTitleAndRow_key == 'availability') {
                    $productNumber = $product->is_in_stock();
                    $productAvailbulity = __('out of stock', 'th-product-compare');
                    $StockClass = 'th-out-of-stoct';
                    if ($productNumber) {
                        $productAvailbulity = __('in stock', 'th-product-compare');
                        $StockClass = 'th-in-stoct';
                    }
                    $addMoreHtml .= '<span class="' . esc_attr($StockClass) . '">' . esc_html($productAvailbulity) . '</span>';
                } elseif ($initTitleAndRow_key == 'rating') {
                    $rating_ = $this->productRating($product);
                    $rating_ = $rating_ ? $rating_ : "-";
                    $addMoreHtml .= '<span class="th-compare-rating">' . $rating_ . '</span>';
                } elseif ($initTitleAndRow_key == 'description') {
                    $description_ = $product->get_short_description();
                    $description_ = $description_ ? $description_ : "-";
                    $addMoreHtml .= '<span>' . wp_kses_post($description_) . '</span>';
                } elseif (isset($initTitleAndRow_value['custom'])) {
                    $customAttrGlobal = $product->get_attribute($initTitleAndRow_key);
                    $customAttrGlobal = $customAttrGlobal ? $customAttrGlobal : '-';
                    $addMoreHtml .= '<span>' . esc_html($customAttrGlobal) . '</span>';
                }

                $addHtml = '<td>' . $addMoreHtml . '</td>';
                if ($CheckLAstProduct) {
                    $addHtml .= '</tr>';
                }
                $initTitleAndRow_value['html'] .= $addHtml;
                $initTitleAndRow[$initTitleAndRow_key] = $initTitleAndRow_value;
            }

            if (isset($trRepeatPrice_)) {
                $trRepeatPrice_ .= '<td>' . $price_ . '</td>';
                if ($CheckLAstProduct) {
                    $trRepeatPrice_ .= '</tr>';
                }
            }
            if (isset($trRepeatAddTocart)) {
                $trRepeatAddTocart .= '<td>' . $Add_to_cart_ . '</td>';
                if ($CheckLAstProduct) {
                    $trRepeatAddTocart .= '</tr>';
                }
            }
            if (isset($trDelete_)) {
                $trDelete_ .= '<td><button class="th-compare-product-remove" data-th-product-id="' . esc_attr($ProductID) . '"><i class="dashicons dashicons-dismiss"></i>' . esc_html__('Remove', 'th-product-compare') . '</button></td>';
                if ($CheckLAstProduct) {
                    $trDelete_ .= '</tr>';
                }
            }

            // add to footer
            $footerProduct .= '<div data-product-id="' . esc_attr($ProductID) . '" class="img_ product-comp">';
            $footerProduct .= '<i class="th-remove-product th-compare-product-remove" data-th-product-id="' . esc_attr($ProductID) . '"></i>';
            $footerProduct .= "<a target='_blank' href='" . esc_url($link_) . "'>";
            $footerProduct .= $product->get_image();
            $footerProduct .= '</a></div>';
        }
    }

    $th_product_atleast_txt = $chekBYoption['compare-atleast-text'];
    $compare_product_limit = $chekBYoption['compare-product-limit'];

    // Add empty slots up to 7 uncommennt below line to add an empty slots
    // $emptySlots = max(0, $compare_product_limit - $countProductsForFooter);

    $emptySlots = 0;  // $emptySlots set to 0 to remove teh empty slots
    for ($i = 0; $i < $emptySlots; $i++) {
        $footerProduct .= '<div class="img_ empty-slot"></div>';
    }


    // footer bar build
    $footerBArPosition = $chekBYoption['compare-popup-position'];
    $returnFooter ="<div class='th-compare-footer-wrap active position-" . esc_attr($footerBArPosition) . "'><div class='th-compare-footer-level2'><div class='th-compare-footer-level3'>" .
                "<div class='th-compare-left'>
                <p class='th-atleast'><span class='th-selected'>Selected</span><span class='th-select-count'>" . esc_html($th_product_atleast_txt) . "</span></p>" .
                "<div class='product_image'>" . $footerProduct . "</div>" .
                 "<div class='th-addremove'><a href='#' class='th-add-product-bar'><i class='dashicons dashicons-plus'></i></a></div></div>" .

                "<div class='th-compare-right'><a id='thpc-removeall'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-trash2 lucide-trash-2' aria-hidden='true'><path d='M10 11v6'></path><path d='M14 11v6'></path><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6'></path><path d='M3 6h18'></path><path d='M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a>" .
                "<div class='th-compare-enable'><a href='#' class='th-compare-footer-product-opner'>" .
                "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-layers text-indigo-600' aria-hidden='true'><path d='M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z'></path><path d='M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12'></path><path d='M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17'></path></svg><span class='text_'>" . esc_html__('Compare', 'th-product-compare') . "</span></a></div></div></div></div></div>";
   

    foreach ($initTitleAndRow as $initTitleAndRow_final_value) {
        $table .= $initTitleAndRow_final_value['html'];
    }

    if (isset($trRepeatPrice_)) {
        $table .= $trRepeatPrice_;
    }
    if (isset($trRepeatAddTocart)) {
        $table .= $trRepeatAddTocart;
    }
    if (isset($trDelete_)) {
        $table .= $trDelete_;
    }
    $table .= '</table>';

    return [
        'html' => $table,
        'footer_bar' => $returnFooter,
        'add_more' => $returnFooter
    ];
}

    private function compareOption()
    {
        $checkChecked = [
            'attributes' => [
                'image' => ["active" => 1],
                'title' => ["active" => 1],
                'rating' => ["active" => 1],
                'price' => ["active" => 1],
                'add-to-cart' => ["active" => 1],
                'description' => ["active" => 0],
                'availability' => ["active" => 1],
                'SKU' => ["active" => 1],
            ],
            'field-repeat-price' => false,
            'field-repeat-add-to-cart' => false,
        ];
        $th_compare_option = get_option('th_compare_option');
      if (isset($th_compare_option['attributes']) && is_array($th_compare_option['attributes'])) {

    foreach ($th_compare_option['attributes'] as $key => $val) {

        if (is_array($val)) {
            $checkChecked['attributes'][$key] = $val;
        } else {
            $checkChecked['attributes'][$key] = ['active' => intval($val)];
        }
    }
}
        if (isset($th_compare_option['compare-popup-position'])) {
            $checkChecked['compare-popup-position'] = $th_compare_option['compare-popup-position'];
        } else {
            $checkChecked['compare-popup-position'] = 'bottom';
        }
        //ATLEAST MESSAGE
         if (isset($th_compare_option['compare-atleast-text'])) {
            $checkChecked['compare-atleast-text'] = $th_compare_option['compare-atleast-text'];
        } else {
            $checkChecked['compare-atleast-text'] = 'Products';
        }

        if (isset($th_compare_option['compare-product-limit'])) {
            $checkChecked['compare-product-limit'] = $th_compare_option['compare-product-limit'];
        } else {
            $checkChecked['compare-product-limit'] = 4;
        }

        return $checkChecked;
    }
    public function add_to_cart($product)
    {
        $args = [];
        $defaults = array(
            'quantity'   => 1,
            'class'      => implode(
                ' ',
                array_filter(
                    array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                    )
                )
            ),
            'attributes' => array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => $product->add_to_cart_description(),
                'rel'              => 'nofollow',
            ),
        );

        $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

        if (isset($args['attributes']['aria-label'])) {
            $args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
        }
        $AddTocartTxt = '%s';
        return apply_filters(
            'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
            sprintf(
                '<a href="%s" data-quantity="%s" class="th-compare-add-to-cart-btn %s" %s>' . $AddTocartTxt . '</a>',
                esc_url($product->add_to_cart_url()),
                esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                esc_html($product->add_to_cart_text())
            ),
            $product,
            $args
        );
    }
    public function productRating($product)
    {
        if (wc_review_ratings_enabled()) {
            $getRAtingHtml = wc_get_rating_html($product->get_average_rating());
            if ($getRAtingHtml) {
                $rating_ = $getRAtingHtml;
                $rating_ .= "<div class='th-rating-count'>(";
                $rating_ .= esc_html($product->get_rating_count()) . ' ' . esc_html__('Review', 'th-product-compare');
                $rating_ .= ")</div>";
                return $rating_;
            }
        }
    }
    // cookies
  public function getPrevId() {

    $cookiesName = th_product_compare::cookieName();

    if ( empty( $_COOKIE[ $cookiesName ] ) ) {
        return array();
    }

    /* -------- sanitize cookie -------- */
    $cookie_value = sanitize_text_field(
        wp_unslash( $_COOKIE[ $cookiesName ] )
    );

    if ( empty( $cookie_value ) ) {
        return array();
    }

    /* -------- decode JSON safely -------- */
    $decoded = json_decode( $cookie_value, true );

    if ( ! is_array( $decoded ) ) {
        return array();
    }

    /* -------- decrypt & validate -------- */
    $decodeArray = array();

    foreach ( $decoded as $value ) {

        $product_id = th_product_compare::th_decrypt( $value );
        $product_id = absint( $product_id );

        if ( $product_id > 0 ) {
            $decodeArray[] = $product_id;
        }
    }

    return $decodeArray;
}

    function setId_cookie($id, $addREmove)
    {
        $previousCookie = array();
        $previousCookie = $this->getPrevId();
        $updateCookie = true;
        $chekBYoption = get_option('th_compare_option');
        if ($addREmove == 'add' || $id == 'refresh') {
            if (!empty($previousCookie)) {
                // check limit 
                $checkLimit = 4;
                // $chekBYoption
                if (is_array($chekBYoption) && isset($chekBYoption['compare-product-limit']) && intval($chekBYoption['compare-product-limit'])) {
                    $checkLimit = intval($chekBYoption['compare-product-limit']);
                }
                $countProduct = count($previousCookie);
                $checkProduct = true;
                if ($countProduct <= ($checkLimit - 1)) {
                    $checkProduct = false;
                }
                $getExist = in_array($id, $previousCookie);
                if ($getExist || $checkProduct || $id === 'refresh') {
                    $updateCookie = false;
                    if ($checkProduct && $addREmove == 'add') {
                        $previousCookie['product_limit'] = 'product_limit';
                    }
                } else {
                    // $previousCookie[] = $id;
                    array_unshift($previousCookie, $id);
                }
            } else {
                if ($id !== 'refresh') {
                    $previousCookie[] = $id;
                }
            }
        } else {

            if (!empty($previousCookie)) {
                $getExist = in_array($id, $previousCookie);
                if ($getExist) {
                    $findID = array_search($id, $previousCookie);
                    unset($previousCookie[$findID]);
                    if (count($previousCookie) == 0) {
                        array_push($previousCookie,false);
                    }
                }
            }
        }
        //   update cookies
        if ($updateCookie) {
            if (isset($previousCookie['product_limit'])) {
                unset($previousCookie['product_limit']);
            }
            $cookieValue = '';
            if (!empty($previousCookie) && is_array($previousCookie)) {
                $arrayENcrypt = [];

                foreach ($previousCookie as $array_value) {
                    $arrayENcrypt[] = th_product_compare::th_encrypt($array_value);
                }
                $cookieValue = json_encode($arrayENcrypt);
            }
            $cookiesName = th_product_compare::cookieName();
            setcookie($cookiesName, $cookieValue, time() + (86400), "/"); // 86400 = 1 day
        }
        return $previousCookie;
    }
    // class end
}


// setcookie("th_compare_product", '', time() + (86400), "/"); // 86400 = 1 day
// setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day