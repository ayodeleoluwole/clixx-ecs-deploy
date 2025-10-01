<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return;
}
function open_shop_customizer_scripts() {
    wp_enqueue_script('open-shop-customizer', get_template_directory_uri() . '/lib/notification/customizer-notification/customizer.js', array('jquery'), '1.0', true);

    wp_localize_script('open-shop-customizer', 'theme_data_customizer', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('thactivatenonce'),
        'redirectUrl' => esc_url(admin_url('themes.php?page=themehunk-site-library&template=step'))
    ));
}
add_action('customize_controls_enqueue_scripts', 'open_shop_customizer_scripts');

// style
function open_shop_customizer_notify_css(){
    
  wp_enqueue_style('open-shop-customizer-notify-styles', OPEN_SHOP_THEME_URI .'lib/notification/customizer-notification/customizer-notify.css');
}
add_action('customize_controls_print_styles', 'open_shop_customizer_notify_css');