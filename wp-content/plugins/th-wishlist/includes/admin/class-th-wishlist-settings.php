<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles the settings page for TH Wishlist.
 *
 * @class THWL_Settings
 */
class THWL_Settings {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add admin menu page.
     */
    public function admin_menu() {
        add_menu_page(
            __( 'TH Wishlist', 'th-wishlist' ),
            __( 'TH Wishlist', 'th-wishlist' ),
            'manage_options',
            'thwl-wishlist',
            array( $this, 'settings_page' ),
            'dashicons-heart',
            56
        );
    }
/**
 * Render Wishlist Settings Tabs
 */
function thwl_render_settings_tabs() {

    $tabs = [
        'general'  => esc_html__( 'General Settings', 'th-wishlist' ),
        'button'   => esc_html__( 'Wishlist Button', 'th-wishlist' ),
        'loop'     => esc_html__( 'Loop Settings', 'th-wishlist' ),
        'product'  => esc_html__( 'Product Page', 'th-wishlist' ),
        'wishlist' => esc_html__( 'Wishlist Page', 'th-wishlist' ),
        'wishlistRedirect' => esc_html__( 'Wishlist Redirect', 'th-wishlist' ),
        'style'    => esc_html__( 'Style Customization', 'th-wishlist' ),
    ];
    /**
     * Allow Pro plugin or other addons to add new tabs.
     */
    $tabs = apply_filters( 'thwl_pro_settings_tabs', $tabs );
    ?>
    <ul class="thw-tabs-list">
        <?php 
        $first = true;
        foreach ( $tabs as $id => $label ) : ?>
            <li class="thw-tab <?php echo esc_attr($first) ? 'active' : ''; ?>" data-tab="<?php echo esc_attr( $id ); ?>">
                <?php echo esc_html( $label ); ?>
            </li>
            <?php $first = false; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}

public function settings_page() {
    $all_columns = apply_filters( 'thwl_all_columns', [
    'checkbox'    => __( 'Checkbox', 'th-wishlist' ),
    'thumbnail'   => __( 'Image', 'th-wishlist' ),
    'name'        => __( 'Name', 'th-wishlist' ),
    'price'       => __( 'Price', 'th-wishlist' ),
    'stock'       => __( 'Stock Status', 'th-wishlist' ),
    'quantity'    => __( 'Quantity', 'th-wishlist' ),
    'add_to_cart' => __( 'Add to Cart', 'th-wishlist' ),
    'date'        => __( 'Date Added', 'th-wishlist' ),
    'remove'      => __( 'Remove', 'th-wishlist' ),
    ] );

    $default_columns = apply_filters( 'thwl_default_columns', [
        'thumbnail',
        'name',
        'price',
        'stock',
        'add_to_cart',
        'remove'
    ] );
    $saved_options = get_option( 'thwl_settings', [] );
    $options = wp_parse_args( $saved_options, self::thwl_get_default_settings() );
    $saved_columns = isset( $options['th_wishlist_table_columns'] ) ? $options['th_wishlist_table_columns'] : $default_columns;
    $labels = isset( $options['th_wishlist_table_column_labels'] ) ? $options['th_wishlist_table_column_labels'] : self::thwl_get_default_settings()['th_wishlist_table_column_labels'];
?>
<div class="wrap">
    <div id="thw-settings-notice"></div>
    <div class="thw-tabs-container">
        <div class="thw-tabs-nav">
            <div class="thw-title-content">
                <div id="logo">
						<a href="https://themehunk.com/" target="_blank">
						<img src="<?php echo esc_url(THWL_URL.'assets/images/th-logo.png') ?>" alt="th-logo">
					</a>
					</div>
            <h3><?php esc_html_e( 'TH Wishlist', 'th-wishlist' ); ?></h3>
            </div>
            <?php $this->thwl_render_settings_tabs(); ?>
        </div>
        <form id="thw-settings-form" data-nonce="<?php echo esc_attr( wp_create_nonce( 'thwl_wishlist_nonce' ) ); ?>">
            <div class="thw-tabs-content">
                <div id="general" class="thw-tab-content active">
                    <h3 class="thws-content-title"><?php esc_html_e( 'General Settings', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Wishlist Page', 'th-wishlist' ); ?></th>
                            <td>
                            <?php
                          
    // Retrieve the stored wishlist page ID, default to empty string if not set
    $selected_page_id = isset($options['thwl_page_id']) && !empty($options['thwl_page_id']) 
    ? absint($options['thwl_page_id']) 
    : get_option('thwl_page_id');
                                wp_dropdown_pages([
                                    'name'              => 'settings[thwl_page_id]', // Corrected to match form structure
                                    'selected'          => absint($selected_page_id), // Sanitize as integer
                                    'show_option_none'  => esc_html__('Select a page', 'th-wishlist'), // Escaped for safety
                                ]);
                                ?>                       
                                <p class="description"><?php esc_html_e( 'The page where the `[thwl_wishlist]` shortcode is located.', 'th-wishlist' ); ?></p>
                            </td>
                        </tr>
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Require Login', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="checkbox" name="settings[thw_require_login]" value="1" <?php checked( isset( $options['thw_require_login'] ) ? $options['thw_require_login'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Only logged-in users can add products to the wishlist.', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="button" class="thw-tab-content">
                    <h3 class="thws-content-title"><?php esc_html_e( 'Wishlist Button', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Button Style', 'th-wishlist' ); ?></th>
                            <td>
                                <select name="settings[thw_button_display_style]">
                                    <option value="icon_text" <?php selected( isset( $options['thw_button_display_style'] ) ? $options['thw_button_display_style'] : 'icon_text', 'icon_text' ); ?>><?php esc_html_e( 'Icon and Text', 'th-wishlist' ); ?></option>
                                    <option value="icon" <?php selected( isset( $options['thw_button_display_style'] ) ? $options['thw_button_display_style'] : 'icon_text', 'icon' ); ?>><?php esc_html_e( 'Icon Only (with Button Style)', 'th-wishlist' ); ?></option>
                                    <option value="icon_only_no_style" <?php selected( isset( $options['thw_button_display_style'] ) ? $options['thw_button_display_style'] : 'icon_text', 'icon_only_no_style' ); ?>><?php esc_html_e( 'Icon Only (No Button Style)', 'th-wishlist' ); ?></option>
                                    <option value="text" <?php selected( isset( $options['thw_button_display_style'] ) ? $options['thw_button_display_style'] : 'icon_text', 'text' ); ?>><?php esc_html_e( 'Text Only', 'th-wishlist' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( '"Add to Wishlist" Text', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="text" name="settings[thw_add_to_wishlist_text]" value="<?php echo esc_attr( isset( $options['thw_add_to_wishlist_text'] ) ? $options['thw_add_to_wishlist_text'] : __( 'Add to Wishlist', 'th-wishlist' ) ); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( '"Browse Wishlist" Text', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="text" name="settings[thw_browse_wishlist_text]" value="<?php echo esc_attr( isset( $options['thw_browse_wishlist_text'] ) ? $options['thw_browse_wishlist_text'] : __( 'Browse Wishlist', 'th-wishlist' ) ); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e( 'Text shown when the product is already in the wishlist.', 'th-wishlist' ); ?></p>
                            </td>
                        </tr>
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Theme Default Style', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="checkbox" name="settings[thw_btn_style_theme]" value="1" <?php checked( isset( $options['thw_btn_style_theme'] ) ? $options['thw_btn_style_theme'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Choose to Wishlist button style as theme', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <div id="loop" class="thw-tab-content">
                     <h3 class="thws-content-title"><?php esc_html_e( 'Loop Settings', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Show "Add to wishlist" in loop', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="checkbox" name="settings[thw_show_in_loop]" value="1" <?php checked( isset( $options['thw_show_in_loop'] ) ? $options['thw_show_in_loop'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Enable the "Add to wishlist" feature in WooCommerce products loop', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Position of "Add to wishlist" in loop', 'th-wishlist' ); ?></th>
                            <td>
                                <?php $selected_position = isset( $options['thw_in_loop_position'] ) ? $options['thw_in_loop_position'] : 'after_crt_btn'; ?>
                                <select name="settings[thw_in_loop_position]">
                                    <option value="after_crt_btn" <?php selected( $selected_position, 'after_crt_btn' ); ?>><?php esc_html_e( 'After "Add to Cart" Button', 'th-wishlist' ); ?></option>
                                    <option value="before_crt_btn" <?php selected( $selected_position, 'before_crt_btn' ); ?>><?php esc_html_e( 'Before "Add to Cart" Button', 'th-wishlist' ); ?></option>
                                    <option value="on_top" <?php selected( $selected_position, 'on_top' ); ?>><?php esc_html_e( 'On Top', 'th-wishlist' ); ?></option>
                                    <option value="on_shortcode" <?php selected( $selected_position, 'on_shortcode' ); ?>><?php esc_html_e( 'Use Shortcode', 'th-wishlist' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="product" class="thw-tab-content">
                     <h3 class="thws-content-title"><?php esc_html_e( 'Product Page', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Show "Add to wishlist" in Product Page', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="hidden" name="settings[thw_show_in_product]" value="0" />
                                <input type="checkbox" name="settings[thw_show_in_product]" value="1" <?php checked( isset( $options['thw_show_in_product'] ) ? $options['thw_show_in_product'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Enable the "Add to wishlist" feature in WooCommerce products Page', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Position of "Add to wishlist" on product page', 'th-wishlist' ); ?></th>
                            <td>
                                <?php $selected_position = isset( $options['thw_in_single_position'] ) ? $options['thw_in_single_position'] : 'after_crt_btn'; ?>
                                <select name="settings[thw_in_single_position]">
                                    <option value="after_crt_btn" <?php selected( $selected_position, 'after_crt_btn' ); ?>><?php esc_html_e( 'After "Add to Cart"', 'th-wishlist' ); ?></option>
                                    <option value="after_summ" <?php selected( $selected_position, 'after_summ' ); ?>><?php esc_html_e( 'After Summary', 'th-wishlist' ); ?></option>
                                    <option value="before_summ" <?php selected( $selected_position, 'before_summ' ); ?>><?php esc_html_e( 'Before Summary', 'th-wishlist' ); ?></option>
                                    <option value="on_shortcode" <?php selected( $selected_position, 'on_shortcode' ); ?>><?php esc_html_e( 'Use Shortcode', 'th-wishlist' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="wishlist" class="thw-tab-content">
                    <h3 class="thws-content-title"><?php esc_html_e( 'Wishlist Page', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Redirect to Cart', 'th-wishlist' ); ?></th>
                            <td>
                                <input type="checkbox" name="settings[thw_redirect_to_cart]" value="1" <?php checked( isset( $options['thw_redirect_to_cart'] ) ? $options['thw_redirect_to_cart'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Remove Product in Wishlist table and Redirect to the cart page after adding item(s) from the wishlist.', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Show Social Share Buttons', 'th-wishlist' ); ?></th>
                            <td>
                                <input id="thwp_wishlist_share" type="checkbox" name="settings[thw_show_social_share]" value="1" <?php checked( isset( $options['thw_show_social_share'] ) ? $options['thw_show_social_share'] : 0, 1 ); ?> />
                                <span class="description"><?php esc_html_e( 'Allows to share their wishlist.', 'th-wishlist' ); ?></span>
                            </td>
                        </tr>
                        <?php do_action('thwl_after_pro_set_share_settings_fields');?>
                        <tr class="th-sort-row">
                            <th scope="row"><?php esc_html_e( 'Wishlist Table Columns', 'th-wishlist' ); ?></th>
                            <td>
                                <p class="description"><?php esc_html_e( 'Check the columns to display and drag to reorder.', 'th-wishlist' ); ?></p>
                                <ul id="thw-sortable-columns" class="thw-sortable-columns">
                                    <?php
                                    foreach ( $saved_columns as $key ) {
                                        if ( isset( $all_columns[ $key ] ) ) {
                                            $label = isset( $labels[ $key ] ) ? $labels[ $key ] : $all_columns[ $key ];
                                    ?>
                                    <li class="thw-sortable-item">
                                        <input type="checkbox" name="settings[th_wishlist_table_columns][]" value="<?php echo esc_attr( $key ); ?>" checked="checked" />
                                        <span class="dashicons dashicons-menu thw-drag-handle"></span>
                                        <span class="thw-column-label">
                                            <?php echo esc_html( $all_columns[ $key ] ); ?>:
                                            <input type="text" name="settings[th_wishlist_table_column_labels][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $label ); ?>" class="regular-text" />
                                        </span>
                                    </li>
                                    <?php
                                        }
                                    }
                                    foreach ( $all_columns as $key => $label ) {
                                        if ( ! in_array( $key, $saved_columns ) ) {
                                            $custom_label = isset( $labels[ $key ] ) ? $labels[ $key ] : $label;
                                    ?>
                                    <li class="thw-sortable-item">
                                        <input type="checkbox" name="settings[th_wishlist_table_columns][]" value="<?php echo esc_attr( $key ); ?>" />
                                        <span class="dashicons dashicons-menu thw-drag-handle"></span>
                                        <span class="thw-column-label">
                                            <?php echo esc_html( $label ); ?>
                                            <input type="text" name="settings[th_wishlist_table_column_labels][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $custom_label ); ?>" class="regular-text" />
                                        </span>
                                    </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>
                        </tr>
                        
                    </table>
                </div>

                <div id="wishlistRedirect" class="thw-tab-content">
                     <h3 class="thws-content-title"><?php esc_html_e( 'Wishlist Redirect', 'th-wishlist' ); ?></h3>
                    <table class="form-table">
                        <tr class="th-row-with-checkbox">
                            <th scope="row"><?php esc_html_e( 'Redirect to Wishlist Page via shorcode', 'th-wishlist' ); ?></th>
                            <td>
                               <input type="checkbox" 
                                id="thw_redirect_wishlist_page" 
                                name="settings[thw_redirect_wishlist_page]" 
                                value="1" 
                                <?php checked( isset( $options['thw_redirect_wishlist_page'] ) ? $options['thw_redirect_wishlist_page'] : 0, 1 ); ?> 
                            />
                                <span class="description">
                                     <?php esc_html_e( 'Use this shortcode anywhere on your site to create a Icon that redirects users to the Wishlist page. Example: [thwl_wishlist_redirect]', 'th-wishlist' ); ?>
                                </span>
                            </td>
                        </tr>
                </table>
                <?php include plugin_dir_path( __FILE__ ) . 'style-setting/redirect-style-setting.php';?>
                </div>
                <?php if(defined( 'THWL_PRO_ACTIVE' ) && THWL_PRO_ACTIVE ) { ?>
                <?php do_action('thwl_pro_style_customization');?>
                <?php }else{ ?>
                <?php $this->thwl_add_style_customization(); ?>
                <?php } ?>
              <?php do_action( 'thwl_after_pro_settings_fields' );?>
            </div>
            <p>
                <button type="submit" class="button button-primary"><?php esc_html_e( 'Save Settings', 'th-wishlist' ); ?></button>
                <button type="button" class="button" id="thw-reset-settings" data-nonce="<?php echo esc_attr( wp_create_nonce( 'thwl_wishlist_nonce' ) ); ?>"><?php esc_html_e( 'Reset to Defaults', 'th-wishlist' ); ?></button>
            </p>
        </form>
        <div class="thw-notes">
            <div class="thw-wrap-side">
                <h4 class="wrp-title"><?php esc_html_e( 'Documentation', 'th-wishlist' ); ?></h4>
                <p><?php esc_html_e( 'Want to know how this plugin works. Read our Documentation.', 'th-wishlist' ); ?></p>
                <a target="_blank" href="https://themehunk.com/docs/th-wishlist"><?php esc_html_e( 'View More', 'th-wishlist' ); ?></a>
            	</div>
                <div class="thw-wrap-side">
                <h4 class="wrp-title"><?php esc_html_e( 'Contact Support', 'th-wishlist' ); ?></h4>
                <p><?php esc_html_e( 'If you need any help you can contact to our support team', 'th-wishlist' ); ?></p>
                <a target="_blank" href="https://themehunk.com/contact-us/"><?php esc_html_e( 'Need Help ?', 'th-wishlist' ); ?></a>
            	</div>
                <div class="thw-wrap-side">
                <h4 class="wrp-title"><?php esc_html_e( 'Spread the News', 'th-wishlist' ); ?></h4>
                <p><?php esc_html_e( 'Enjoying this plugin? Help spread the the creation and show off your amazing website with such amazing functionality.', 'th-wishlist' ); ?></p>
                <a target="_blank" href="https://twitter.com/intent/tweet?url=https://themehunk.com/advance-product-search/&amp;text=Hey, I just tried out this amazing WordPress Plugin for http://localhost:8888/wp1 to add SearchBar. Show off your amazing website with such amazing functionality with this awesome plugin: TH Advance Product Search Pro By 
@ThemeHunk %20%23WooCommerce%20%23WordPress"></span>
        <span> <?php esc_html_e( 'Click to Tweet', 'th-wishlist' ); ?></span></a>
            	</div>
        </div>
    </div>
</div>
<?php }
    /**
     * Get default settings.
     *
     * @return array
     */
    public static function thwl_get_default_settings() {
        $defaults =  [
            'thwl_page_id' =>'',
            'thw_require_login'            => 0,
            'thw_redirect_to_cart'         => 0,
            'thw_button_display_style'     => 'icon_text',
            'thw_add_to_wishlist_text'     => __( 'Add to Wishlist', 'th-wishlist' ),
            'thw_browse_wishlist_text'     => __( 'Browse Wishlist', 'th-wishlist' ),
            'thw_btn_style_theme'          => 0,
            'thw_custom_icon_url'          => '',
            'thw_show_in_loop'             => 1,
            'thw_show_in_product'          => 1,
            'thw_in_loop_position'         => 'after_crt_btn',
            'thw_in_single_position'       => 'after_crt_btn',
            'thw_show_social_share'        => 0,
            'thw_show_quantity'            => 0,
            'th_wishlist_table_columns'       => [ 'thumbnail', 'name', 'price', 'stock', 'add_to_cart', 'remove' ],
            'th_wishlist_table_column_labels' => [],
            'thw_redirect_wishlist_page'      => 0,
            'thw_redirect_wishlist_page_icon' => 'heart-outline',
            'thw_redirect_wishlist_page_icon_color' => '#111',
            'thw_redirect_wishlist_page_icon_color_hvr' => '#111',
            'thw_redirect_wishlist_page_icon_size' => '24',
            'th_wishlist_add_icon'         => 'heart-outline',
            'th_wishlist_add_icon_color'   => '#111',
            'th_wishlist_brws_icon'        => 'heart-filled',
            'th_wishlist_brws_icon_color'  => '#111',
            'th_wishlist_btn_txt_color'    => '#333',
            'th_wishlist_btn_bg_color'     => '#6a4df5',
            'th_wishlist_table_bg_color'   => '#fff',
            'th_wishlist_table_brd_color'  => '#eee',
            'th_wishlist_table_txt_color'  => '#111',
            'th_wishlist_tb_btn_bg_color'  => '#6a4df5',
            'th_wishlist_tb_btn_txt_color' => '#fff',
            'th_wishlist_shr_fb_color'     => '#1877F2',
            'th_wishlist_shr_fb_hvr_color' => '#1877F2',
            'th_wishlist_shr_x_color'      => '#000',
            'th_wishlist_shr_x_hvr_color'  => '#000',
            'th_wishlist_shr_w_color'      => '#25D366',
            'th_wishlist_shr_w_hvr_color'  => '#25D366',
            'th_wishlist_shr_e_color'      => '#E4405F',
            'th_wishlist_shr_e_hvr_color'  => '#E4405F',
            'th_wishlist_shr_c_color'      => '#333',
            'th_wishlist_shr_c_hvr_color'  => '#3333', 
        ];
        return apply_filters( 'thwl_default_settings', $defaults );
    }

    private static function thwl_render_color_picker($id, $value, $label, $default='') {
        ?>
        <div class="th-color-picker">
            <p><?php echo esc_html($label); ?></p>
            
             <!-- Reset button -->
            <div class="th-color-content">
            <button type="button" 
                class="th-color-reset" 
                data-target="<?php echo esc_attr($id); ?>"
                title="<?php esc_attr_e('Reset color', 'th-wishlist'); ?>"
                >
            <span class="dashicons dashicons-image-rotate"></span>
            </button>
            <input type="text" name="settings[<?php echo esc_attr($id); ?>]" 
                id="<?php echo esc_attr($id); ?>" 
                value="<?php echo esc_attr($value); ?>" 
                class="th_color_picker" 
                data-default-color="<?php echo esc_attr($default);?>"
                style="background-color: <?php echo esc_attr($value); ?>" />
            </div>
        </div>
        <?php
    }

    public static function thwl_add_style_customization() {
        
    ?>
     <div id="style" class="thw-tab-content">
        <div class="thwl-pro-style-wrapper">
        <!-- Tabs -->
        <ul class="thwl-pro-tabs">
            <li class="active" data-tab="wishlist-button"><?php esc_html_e( 'Button', 'th-wishlist' ); ?></li>
            <li data-tab="wishlist-content"><?php esc_html_e( 'Content', 'th-wishlist' ); ?></li>
            
        </ul>
        <!-- Tab Contents -->
        <div class="thwl-pro-tab-contents">
            <!-- Wishlist Button Tab -->
            <div class="thwl-tab-content active" id="wishlist-button">
                <?php include plugin_dir_path( __FILE__ ) . 'style-setting/button-style-setting.php';?>
            </div>
            <!-- Wishlist Page Tab -->
            <div class="thwl-tab-content" id="wishlist-content">
               <?php include plugin_dir_path( __FILE__ ) . 'style-setting/content-style-setting.php';?>
            </div>
           
        </div>
    </div>
    </div>
    <?php
  }

}