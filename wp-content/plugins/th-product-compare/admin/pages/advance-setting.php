<?php
if (!defined('ABSPATH')) exit;

// Force default + safe loading
$defaultAttributes = [
    'image'       => ['active' => 1],
    'title'       => ['active' => 1],
    'rating'      => ['active' => 1],
    'price'       => ['active' => 1],
    'add-to-cart' => ['active' => 1],
    'description' => ['active' => 0],
    'availability'=> ['active' => 1],
    'SKU'         => ['active' => 1],
];

$th_compare_option = get_option('th_compare_option');


if (!is_array($th_compare_option)) {
    $th_compare_option = [];
}

if (isset($th_compare_option['attributes']) && is_array($th_compare_option['attributes'])) {
    // Merge with defaults (saved values priority)
    foreach ($th_compare_option['attributes'] as $key => $val) {
        if (array_key_exists($key, $defaultAttributes)) {
            if (is_array($val)) {
                $defaultAttributes[$key] = $val;
            } elseif (is_numeric($val)) {
                $defaultAttributes[$key]['active'] = (int)$val;
            }
        }
    }
}

if (is_string($th_compare_option) && !empty($th_compare_option)) {
    error_log("th_compare_option is STRING: " . substr($th_compare_option, 0, 200));
}


$fieldRepeatPrice = isset($th_compare_option['field-repeat-price']) && $th_compare_option['field-repeat-price'] == '1' ? 'checked="checked"' : '';
$fieldrepeatAddToCart = isset($th_compare_option['field-repeat-add-to-cart']) && $th_compare_option['field-repeat-add-to-cart'] == '1' ? 'checked="checked"' : '';

function th_compare_productsAttributes($defaultAttributes)
{
    foreach ($defaultAttributes as $key => $value) {
        $uniqId = 'compare-attributes-' . $key;
        $name_ = ucfirst(str_replace("-", " ", $key));
        $active = 0;

if (is_array($value) && isset($value['active'])) {
    $active = $value['active'];
} elseif ($value == 1 || $value === "1") {
    $active = 1;
}

$checkActive = $active == 1 ? "checked='checked'" : '';
?>
        <div class="th-compare-radio">
            <input type="checkbox" data-th-save="compare-attributes" <?php echo esc_attr($checkActive); ?> id="<?php echo esc_attr($uniqId); ?>" value="<?php echo esc_attr($key); ?>">
            <label class="th-color-title" for="<?php echo esc_attr($uniqId); ?>"> <?php echo esc_html($name_); ?> </label>
        </div>
<?php
    }
}

?>
<div class="setting_">
    <div class="field-to-show">
        <span class="th-tab-heading"><?php esc_html_e('Configuration', 'th-product-compare'); ?></span>
        <div>
            <div class="row_">
                <div>
                    <span class="bold-heading"><?php esc_html_e('Table Fields', 'th-product-compare') ?></span>
                     <span class="th-alt-title"><?php esc_html_e('Select which product attributes to display in the comparison table.', 'th-product-compare') ?></span>
                </div>
                <div class="th-compare-field-wrap woocommerce-th-attributes">
                    <?php th_compare_productsAttributes($defaultAttributes); ?>
                </div>
            </div>
            <div class="row_">
                <div>
                    <span class="bold-heading"><?php esc_html_e('Sticky Rows', 'th-product-compare'); ?></span>
                    <span class="th-alt-title"><?php esc_html_e('Repeat specific rows at the bottom for easier comparison on long tables.

.', 'th-product-compare') ?></span>
                </div>
                <div class="sticky-rows-settings">
                    <div class="th-compare-radio">
                        <input type="checkbox" data-th-save='compare-field' id="compare-fields-repeat-price" <?php echo esc_html($fieldRepeatPrice); ?> value="repeat-price">
                        <label class="th-color-title" for="compare-fields-repeat-price"><?php esc_html_e('Repeat the Price at the end of the table', 'th-product-compare'); ?></label>
                    </div>

                    <div class="th-compare-radio">

                        <input type="checkbox" data-th-save='compare-field' id="compare-fields-repeat-add-to-cart" <?php echo esc_html($fieldrepeatAddToCart); ?> value="repeat-add-to-cart">

                        <label class="th-color-title" for="compare-fields-repeat-add-to-cart"><?php esc_attr_e('Repeat Add To Cart at the end of the table', 'th-product-compare'); ?>
                        </label>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>