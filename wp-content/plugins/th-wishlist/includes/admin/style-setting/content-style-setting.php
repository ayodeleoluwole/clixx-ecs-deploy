<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$thwl_saved_options = get_option( 'thwl_settings', [] );
$thwl_options = wp_parse_args( $thwl_saved_options, self::thwl_get_default_settings() );

$thwl_tb_btn_txt_color  = isset( $thwl_options['th_wishlist_tb_btn_txt_color'] ) ? $thwl_options['th_wishlist_tb_btn_txt_color'] : '';
$thwl_tb_btn_bg_color   = isset( $thwl_options['th_wishlist_tb_btn_bg_color'] ) ? $thwl_options['th_wishlist_tb_btn_bg_color'] : '';

$thwl_table_bg_color    = isset( $thwl_options['th_wishlist_table_bg_color'] ) ? $thwl_options['th_wishlist_table_bg_color'] : '#fff';
$thwl_table_brd_color   = isset( $thwl_options['th_wishlist_table_brd_color'] ) ? $thwl_options['th_wishlist_table_brd_color'] : '#eee';
$thwl_table_txt_color   = isset( $thwl_options['th_wishlist_table_txt_color'] ) ? $thwl_options['th_wishlist_table_txt_color'] : '#111';

$thwl_shr_fb_color      = isset( $thwl_options['th_wishlist_shr_fb_color'] ) ? $thwl_options['th_wishlist_shr_fb_color'] : '';
$thwl_shr_fb_hvr_color  = isset( $thwl_options['th_wishlist_shr_fb_hvr_color'] ) ? $thwl_options['th_wishlist_shr_fb_hvr_color'] : '';

$thwl_shr_x_color       = isset( $thwl_options['th_wishlist_shr_x_color'] ) ? $thwl_options['th_wishlist_shr_x_color'] : '';
$thwl_shr_x_hvr_color   = isset( $thwl_options['th_wishlist_shr_x_hvr_color'] ) ? $thwl_options['th_wishlist_shr_x_hvr_color'] : '';

$thwl_shr_w_color       = isset( $thwl_options['th_wishlist_shr_w_color'] ) ? $thwl_options['th_wishlist_shr_w_color'] : '';
$thwl_shr_w_hvr_color   = isset( $thwl_options['th_wishlist_shr_w_hvr_color'] ) ? $thwl_options['th_wishlist_shr_w_hvr_color'] : '';

$thwl_shr_c_color       = isset( $thwl_options['th_wishlist_shr_c_color'] ) ? $thwl_options['th_wishlist_shr_c_color'] : '';
$thwl_shr_c_hvr_color   = isset( $thwl_options['th_wishlist_shr_c_hvr_color'] ) ? $thwl_options['th_wishlist_shr_c_hvr_color'] : '';

$thwl_shr_e_color       = isset( $thwl_options['th_wishlist_shr_e_color'] ) ? $thwl_options['th_wishlist_shr_e_color'] : '';
$thwl_shr_e_hvr_color   = isset( $thwl_options['th_wishlist_shr_e_hvr_color'] ) ? $thwl_options['th_wishlist_shr_e_hvr_color'] : '';
?>

<div class="thwl-settings-wrapper" style="display:flex; gap:30px;">
    
    <!-- Left Panel -->
    <div class="thwl-settings-left">

        <div class="thwl-setting-group">
            <label class="thwl-setting-label"><?php esc_html_e( 'Button', 'th-wishlist' ); ?></label>
            <div class="thwl-setting-content thwl-2-col">
                <?php 
                self::thwl_render_color_picker( 'th_wishlist_tb_btn_txt_color', $thwl_tb_btn_txt_color, __( 'Color', 'th-wishlist' ), $thwl_tb_btn_txt_color );
                self::thwl_render_color_picker( 'th_wishlist_tb_btn_bg_color', $thwl_tb_btn_bg_color, __( 'Background', 'th-wishlist' ), $thwl_tb_btn_bg_color );
                ?>
            </div>
        </div>

        <div class="thwl-setting-group">
            <label class="thwl-setting-label"><?php esc_html_e( 'Wishlist Table', 'th-wishlist' ); ?></label>
            <div class="thwl-setting-content thwl-2-col">
                <?php 
                self::thwl_render_color_picker( 'th_wishlist_table_bg_color', $thwl_table_bg_color, __( 'Background', 'th-wishlist' ), $thwl_table_bg_color );
                self::thwl_render_color_picker( 'th_wishlist_table_brd_color', $thwl_table_brd_color, __( 'Border', 'th-wishlist' ), $thwl_table_brd_color );
                self::thwl_render_color_picker( 'th_wishlist_table_txt_color', $thwl_table_txt_color, __( 'Text', 'th-wishlist' ), $thwl_table_txt_color );
                ?>
            </div>
        </div>

        <!-- 💡 ALL other color pickers same hi rakhe hain -->
        <!-- Bas variables rename hue hain -->

    </div>

    <!-- Right Preview Section -->
    <div class="thwl-live-preview-right">

        <h4><?php esc_html_e( 'Live Preview', 'th-wishlist' ); ?></h4>

        <div class="thw-wishlist-wrapper thw-table-custom-style lite">

            <table class="thwl-preview-table thwl-preview-table-1"
                style="width:100%;border-collapse:collapse;
                background-color:<?php echo esc_attr( $thwl_table_bg_color ); ?>;
                color:<?php echo esc_attr( $thwl_table_txt_color ); ?>;
                border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;">

                <thead>
                    <tr>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;">
                            <input type="checkbox" />
                        </th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Product', 'th-wishlist' ); ?></th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Title', 'th-wishlist' ); ?></th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Price', 'th-wishlist' ); ?></th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Stock', 'th-wishlist' ); ?></th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Action', 'th-wishlist' ); ?></th>
                        <th style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;"><?php esc_html_e( 'Remove', 'th-wishlist' ); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;text-align:center;">
                            <input type="checkbox" />
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;text-align:center;">
                            <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>"
                                alt="<?php esc_attr_e( 'Dummy Product', 'th-wishlist' ); ?>"
                                style="max-width:60px;border:1px solid #ddd;border-radius:4px;" />
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;">
                            <?php esc_html_e( 'Sample Product', 'th-wishlist' ); ?>
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;">
                            <?php esc_html_e( '$49.00', 'th-wishlist' ); ?>
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;">
                            <?php esc_html_e( 'In Stock', 'th-wishlist' ); ?>
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;text-align:center;">
                            <button style="padding:6px 12px;border:none;border-radius:4px;cursor:pointer;">
                                <?php esc_html_e( 'Add to Cart', 'th-wishlist' ); ?>
                            </button>
                        </td>
                        <td style="padding:10px;border:1px solid <?php echo esc_attr( $thwl_table_brd_color ); ?>;text-align:center;">
                            <span style="cursor:pointer;"><?php esc_html_e( '✕', 'th-wishlist' ); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="thw-wishlist-actions" style="margin-top:10px;">
                <div class="all-button"
                    style="color:<?php echo esc_attr( $thwl_tb_btn_txt_color ); ?>;
                    background-color:<?php echo esc_attr( $thwl_tb_btn_bg_color ); ?>;
                    font-size:16px;padding:10px 20px;border-radius:4px;display:inline-block;">
                    <?php esc_html_e( 'Button', 'th-wishlist' ); ?>
                </div>
            </div>

        </div>

    </div>

</div>
