<?php
if (!defined('ABSPATH')) exit;

$th_product_compare_btn_txt = isset($th_compare_option['compare-btn-text']) ? sanitize_text_field($th_compare_option['compare-btn-text']) : __('Compare', 'th-product-compare');

$th_product_atleast_txt = isset($th_compare_option['compare-atleast-text']) ? sanitize_text_field($th_compare_option['compare-atleast-text']) : __('Products', 'th-product-compare');

$th_product_limit = isset($th_compare_option['compare-product-limit']) ? sanitize_text_field($th_compare_option['compare-product-limit']) : 4;

$th_product_btn_type = isset($th_compare_option['compare-btn-type']) ? sanitize_text_field($th_compare_option['compare-btn-type']) : 'icon';

$th_product_shop_hook = isset($th_compare_option['compare-at-shop-hook']) ? sanitize_text_field($th_compare_option['compare-at-shop-hook']) : 'after';

$checkChecked = [
    'field-product-page' => 'checked="checked"',
    'field-product-single-page' => 'checked="checked"',
];

if (is_array($th_compare_option)) {
    foreach ($checkChecked as $key => $value) {
        if (isset($th_compare_option[$key])) {
            if ($th_compare_option[$key] == '1') {
                $checkChecked[$key] = 'checked="checked"';
            } else {
                $checkChecked[$key] = '';
            }
        }
    }
}
?>
<div class="th-general">
    <div class="th-option_">
        <span class="th-tab-heading"><?php esc_html_e('Appearance', 'th-product-compare'); ?></span>
        <table>
            <tr>
                <td>
                    <span class="th-color-title"><?php esc_html_e('Comapre Button Type and Text', 'th-product-compare'); ?></span>
                    <span class="th-alt-title"><?php esc_html_e('Choose how the trigger is displayed to users.', 'th-product-compare'); ?></span>
                </td>
                <td>
                    <div class="th-compare-select-wrapper">
                        <div> 
                    <select data-th-save='compare-btn-type'>
                        <option value="checkbox" <?php echo esc_html($th_product_btn_type == 'checkbox' ? "selected" : ''); ?> disabled><?php esc_attr_e('Text (Checkbox) Pro', 'th-product-compare'); ?></option>
                        <option value="icon" <?php echo esc_html($th_product_btn_type == 'icon' ? "selected" : ''); ?>><?php esc_attr_e('Icon', 'th-product-compare'); ?></option>
                    </select>
                    <i class="description"><?php esc_html_e('How you want to display compare trigger.', 'th-product-compare'); ?></i>
                    </div>

                    <div>
                            <input data-th-save='compare-btn-text' type="text" placeholder="Compare" value="<?php echo esc_html($th_product_compare_btn_txt); ?>">
                    <i class="description"><?php esc_html_e('This value define maximum number of products you want to add in the compare table.', 'th-product-compare'); ?></i>
                    </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="th-color-title"><?php esc_html_e('Comparison Limit (Pro)', 'th-product-compare') ?></span>
                    <span class="th-alt-title"><?php esc_html_e('Max products allowed in the compare table.', 'th-product-compare'); ?></span>
                </td>
                <td>
                    <input class="th-limit-input" data-th-save='compare-product-limit' type="number" placeholder="4" value="<?php echo esc_html($th_product_limit); ?>"  readonly>
                </td>
            </tr>
            <tr>
                <td><span class="th-color-title"><?php esc_html_e('Visibility', 'th-product-compare') ?></span>
             <span class="th-alt-title"><?php esc_html_e('Where should the compare button appear?', 'th-product-compare'); ?></span>
            </td>
                <td>
                    <div class="th-compare-radio">
                        <!--title-->

                        <input type="checkbox" data-th-save='compare-field' id="field-show-product-page"
       <?php echo esc_html($checkChecked['field-product-single-page']); ?>
       value="product-single-page">
<label class="th-color-title" for="field-show-product-page">
    <?php esc_html_e('Product Single Page.', 'th-product-compare') ?>
</label>



                        <!-- <input type="checkbox" data-th-save='compare-field' id="field-show-product-page" <?php echo esc_html($checkChecked['field-product-single-page']); ?> value="product-single-page"> -->
                        <!-- <label class="th-color-title" for="field-show-product-page"><?php esc_html_e('Product Single Page.', 'th-product-compare') ?></label> -->
                    </div>

                    <div class="th-compare-radio shop-archive">
                        <!--title-->
                        <input type="checkbox" data-th-save='compare-field' id="compare-fields-product-list" <?php echo esc_html($checkChecked['field-product-page']); ?> value="product-page">
                        <label class="th-color-title" for="compare-fields-product-list"><?php esc_html_e('Shop and Archive Pages.', 'th-product-compare') ?></label>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="th-atleast-title"><?php esc_html_e('Minimum products message', 'th-product-compare'); ?></span>
                    <span class="th-alt-title"><?php esc_html_e('Warning when user selects too few items.', 'th-product-compare'); ?></span>
                </td>
                <td>
                    <input data-th-save='compare-atleast-text' type="text" placeholder="Compare" value="<?php echo esc_html($th_product_atleast_txt); ?>">
                    <!-- <i class="description"><?php esc_html_e('This value define maximum number of products you want to add in the compare table.', 'th-product-compare'); ?></i> -->
                </td>
            </tr>

            <tr>
                <td>
                    <span class="th-color-title"><?php esc_html_e('Button Placement', 'th-product-compare'); ?></span>
                    <span class="th-alt-title"><?php esc_html_e('Position relative to the Add to Cart button.', 'th-product-compare'); ?></span>
                </td>
                <td>
                    <select data-th-save='compare-at-shop-hook'>
                        <option value="before" <?php echo esc_html($th_product_shop_hook == 'before' ? "selected" : ''); ?>><?php esc_html_e('Before Cart', 'th-product-compare'); ?></option>
                        <option value="after" <?php echo esc_html($th_product_shop_hook == 'after' ? "selected" : ''); ?>><?php esc_html_e('After Cart', 'th-product-compare'); ?></option>
                    </select>
                    
                </td>
            </tr>

        </table>

    </div>
</div>
 <div class="th-shortcode-box">
            
            <div class="th-shortcode-header">
                <span class="th-shortcode-icon">⧉</span>
                <strong><?php esc_html_e('Display via Shortcode', 'th-product-compare'); ?></strong>
            </div>

            <p class="th-shortcode-text">
                <?php esc_html_e('To display the compare button for a specific product anywhere on your site, use the following shortcode:', 'th-product-compare'); ?>
            </p>

           <div class="th-shortcode-code">
                <code id="th-copy-shortcode">[th_compare pid="856"]</code>
                <button type="button" class="th-copy-btn" data-copy-target="th-copy-shortcode">
                    Copy
                </button>
            </div>


            <p class="th-shortcode-note">
                <?php esc_html_e('* Replace "856" with the specific Product ID.', 'th-product-compare'); ?>
            </p>

        </div>