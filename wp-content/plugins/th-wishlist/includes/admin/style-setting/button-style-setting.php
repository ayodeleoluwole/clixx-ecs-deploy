<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$thwl_saved_options = get_option( 'thwl_settings', [] );
$thwl_options = wp_parse_args( $thwl_saved_options, self::thwl_get_default_settings() );
?>
<div class="thwl-settings-wrapper" style="display:flex; gap:30px;">

    <!-- Left Settings Panel -->
    <div class="thwl-settings-left" style="flex:1;">

        <!-- Add to Wishlist Icon -->
        <div class="thwl-setting-group">
            <label class="thwl-setting-label"><?php esc_html_e( 'Add to Wishlist Icon', 'th-wishlist' ); ?></label>
            <?php 
            $thwl_allowed_svg_tags = [
                'svg' => [ 'class'=>true,'width'=>true,'height'=>true,'viewbox'=>true,'fill'=>true,'stroke'=>true,'stroke-width'=>true,'xmlns'=>true ],
                'path'=> [ 'd'=>true,'fill'=>true,'stroke'=>true,'stroke-linecap'=>true,'stroke-linejoin'=>true,'clip-rule'=>true,'fill-rule'=>true ],
            ];

            $thwl_selected_icon = $thwl_options['th_wishlist_add_icon'] ?? '';
            $thwl_add_icons = thwl_get_wishlist_icons_svg();
            $thwl_add_icon_color = $thwl_options['th_wishlist_add_icon_color'] ?? '#111';
            ?>
            <div class="thw-icon-options" style="display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ( $thwl_add_icons as $thwl_icon_key => $thwl_icon_data ) : ?>
                    <label class="thw-dashicon-option" style="cursor:pointer;display:flex;flex-direction:column;align-items:center;">
                        <input type="radio" name="settings[th_wishlist_add_icon]" 
                            value="<?php echo esc_attr( $thwl_icon_key ); ?>" 
                            <?php checked( $thwl_selected_icon, $thwl_icon_key ); ?> />
                        <span title="<?php echo esc_attr( $thwl_icon_data['name'] ); ?>">
                            <?php echo wp_kses( $thwl_icon_data['svg'], $thwl_allowed_svg_tags ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
            
            <div class="thwl-setting-content thwl-2-col">
                <?php 
                self::thwl_render_color_picker(
                    'th_wishlist_add_icon_color',
                    $thwl_add_icon_color,
                    __( 'Add to Wishlist Icon', 'th-wishlist' ),
                    $thwl_add_icon_color
                );
                ?>
            </div>
        </div>


        <!-- Browse Wishlist Icon -->
        <div class="thwl-setting-group" style="margin-top:20px;">
            <label class="thwl-setting-label"><?php esc_html_e( 'Browse Wishlist Icon', 'th-wishlist' ); ?></label>
            <?php 
            $thwl_selected_brws_icon = $thwl_options['th_wishlist_brws_icon'] ?? '';
            $thwl_browse_icons = thwl_get_wishlist_icons_svg();
            $thwl_browse_icon_color = $thwl_options['th_wishlist_brws_icon_color'] ?? '#111';
            ?>
            <div class="thw-icon-options" style="display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ( $thwl_browse_icons as $thwl_icon_key => $thwl_icon_data ) : ?>
                    <label class="thw-dashicon-option" style="cursor:pointer;display:flex;flex-direction:column;align-items:center;">
                        <input type="radio" name="settings[th_wishlist_brws_icon]" 
                            value="<?php echo esc_attr( $thwl_icon_key ); ?>" 
                            <?php checked( $thwl_selected_brws_icon, $thwl_icon_key ); ?> />
                        <span title="<?php echo esc_attr( $thwl_icon_data['name'] ); ?>">
                            <?php echo wp_kses( $thwl_icon_data['svg'], $thwl_allowed_svg_tags ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="thwl-setting-content thwl-2-col">
                <?php 
                self::thwl_render_color_picker(
                    'th_wishlist_brws_icon_color',
                    $thwl_browse_icon_color,
                    __( 'Browse Wishlist Icon', 'th-wishlist' ),
                    $thwl_browse_icon_color
                );
                ?>
            </div>
        </div>


        <!-- Button color -->
        <div class="thwl-setting-group">
            <div class="thwl-setting-content thwl-2-col">
                <?php 
                $thwl_btn_text_color = $thwl_options['th_wishlist_btn_txt_color'] ?? '#333';
                self::thwl_render_color_picker(
                    'th_wishlist_btn_txt_color',
                    $thwl_btn_text_color,
                    __( 'Button Text', 'th-wishlist' ),
                    $thwl_btn_text_color
                );
                ?>
            </div>
        </div>

    </div>


    <!-- Right Live Preview Panel -->
    <div class="thwl-live-preview-right" style="flex:1;">

        <a id="thwl_button_preview" 
            style="font-size:14px;display:flex;align-items:center;padding:10px 20px;gap:5px;width:max-content;">
            <span id="thwl_icon_preview">
                <?php 
                if ( isset( $thwl_add_icons[ $thwl_selected_icon ] ) ) {
                    echo wp_kses( $thwl_add_icons[ $thwl_selected_icon ]['svg'], $thwl_allowed_svg_tags );
                }
                ?>
            </span>
            <?php esc_html_e( 'Wishlist', 'th-wishlist' ); ?>
        </a>

        <a id="thwl_button_preview_browse" class="browse"
            style="display:flex;font-size:14px;align-items:center;padding:10px 20px;gap:5px;width:max-content;">
            <span id="thwl_brws_icon_preview">
                <?php 
                if ( isset( $thwl_browse_icons[ $thwl_selected_brws_icon ] ) ) {
                    echo wp_kses( $thwl_browse_icons[ $thwl_selected_brws_icon ]['svg'], $thwl_allowed_svg_tags );
                }
                ?>
            </span>
            <?php esc_html_e( 'Browse', 'th-wishlist' ); ?>
        </a>

    </div>

</div>
