<?php
if (!defined('ABSPATH')) exit;

class th_product_compare_shortcode
{
    public $cookiesName;
    public $optionName = 'th_compare_option';

    function __construct()
    {
        $this->cookiesName = th_product_compare::cookieName();
    }

    public function getPrevId()
    {
        if (isset($_COOKIE[$this->cookiesName]) && $_COOKIE[$this->cookiesName] != '') {
            $getPRoductId = sanitize_text_field(wp_unslash($_COOKIE[$this->cookiesName]));
            if ($getPRoductId) {
                $removeSlace = stripslashes($getPRoductId);
                $removeSlace = json_decode($removeSlace);
                $decodeArray = [];
                foreach ($removeSlace as $array_value) {
                    $decodeArray[] = th_product_compare::th_decrypt($array_value);
                }
                return $decodeArray;
            }
        }
        return [];
    }

    public function get()
    {
        add_shortcode('th_compare', array($this, 'compare'));
        $this->showAndHideSingle();
    }

    public function showAndHideShopPage()
    {
        $checkOption = get_option($this->optionName);
        if ($checkOption && is_array($checkOption) && !empty($checkOption)) {
            if (isset($checkOption['field-product-page']) && $checkOption['field-product-page'] == '1' && (isset($checkOption['compare-at-shop-hook']) && $checkOption['compare-at-shop-hook'] == 'after')) {
                add_action('woocommerce_after_shop_loop_item', array($this, 'addCompareBtn'), 11);
            }
            elseif (isset($checkOption['field-product-page']) && $checkOption['field-product-page'] == '1' && (isset($checkOption['compare-at-shop-hook']) && $checkOption['compare-at-shop-hook'] == 'before')) {
                add_action('woocommerce_after_shop_loop_item', array($this, 'addCompareBtn'), 9);
            }
        } else {
            add_action('woocommerce_after_shop_loop_item', array($this, 'addCompareBtn'), 11);
        }
    }

    public function showAndHideSingle()
    {
        $checkOption = get_option($this->optionName);
        if ($checkOption && is_array($checkOption) && !empty($checkOption)) {
            if (isset($checkOption['field-product-single-page']) && $checkOption['field-product-single-page'] == '1') {
                add_action('woocommerce_after_add_to_cart_button', array($this, 'addCompareBtn'), 30);
            }
        } else {
            add_action('woocommerce_after_add_to_cart_button', array($this, 'addCompareBtn'), 30);
        }
    }

    public function addCompareBtn()
    {
        global $product;
        $productId = intval($product->get_id());
        if ($productId) {
            $this->btnBYoption($productId);
        }
    }

    public function compare($atts, $content = null)
    {
        $a = shortcode_atts(['pid' => ''], $atts);
        $product_id = intval($a['pid']);
        
        // Validate product ID and check if it's a valid WooCommerce product
        if (!$product_id || !wc_get_product($product_id)) {
            return '<span class="th-compare-error">' . __('Invalid product ID.', 'th-product-compare') . '</span>';
        }

        // Start output buffering to capture the button HTML
        ob_start();
        $this->btnBYoption($product_id);
        return ob_get_clean();
    }

    public function btnBYoption($product_id)
    {
        $compareText = __('Compare', 'th-product-compare');
        $checkOption = get_option($this->optionName);

        // Button text from options
        if (is_array($checkOption) && !empty($checkOption)) {
            if (isset($checkOption['compare-btn-text']) && $checkOption['compare-btn-text']) {
                $compareText = $checkOption['compare-btn-text'];
            }
        }

        // Check if product is already in comparison
        $previousCookie = $this->getPrevId();
       $isChecked = (!empty($previousCookie) && in_array($product_id, $previousCookie));

        $isAdded = (!empty($previousCookie) && in_array($product_id, $previousCookie)) ? ' th-added-compare' : '';

        // Determine button style
        $buttonStyle = (is_array($checkOption) && isset($checkOption['compare-btn-type']) && $checkOption['compare-btn-type'] === 'checkbox') ? 'checkbox' : 'icon';

        if ($buttonStyle === 'checkbox') {
            // Checkbox class
            $checkboxClass = 'th-product-compare-checkbox' . $isAdded;
?>
            <div class='th-product-compare-checkbox-wrap'>
                <label class="th-compare-label">
                    <input type="checkbox" class="<?php echo esc_attr($checkboxClass); ?>" data-th-product-id="<?php echo esc_attr($product_id); ?>"<?php echo checked($isChecked); ?> aria-label="<?php echo esc_attr($compareText); ?>">
                    <?php echo esc_html($compareText); ?>
                </label>
            </div>
<?php
        } else {
            // Icon class
            $iconClass = 'th-product-compare-btn compare' . $isAdded;
?>
            <div class="thunk-compare">
                <div th-tooltip="<?php echo esc_attr($compareText); ?>" class="compare-tooltip th-product-compare-btn-wrap">
                    <a class="<?php echo esc_attr($iconClass); ?>" data-th-product-id="<?php echo esc_attr($product_id); ?>" aria-label="<?php echo esc_attr($compareText); ?>">
                        <span class="icon">&#8646;</span>
                        <span class="text"><?php echo esc_html($compareText); ?></span>
                    </a>
                </div>
            </div>
<?php
        }
    }
}

function th_compare_add_action_shop_list()
{
    $obj = new th_product_compare_shortcode();
    $obj->showAndHideShopPage();
}

add_action('woocommerce_init', 'th_compare_add_action_shop_list');
?>