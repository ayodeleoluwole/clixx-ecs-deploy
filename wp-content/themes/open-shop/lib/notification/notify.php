<?php
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class OpenShopAdminNotice {

    public function __construct() {
        add_action('admin_init', array($this, 'unset_cookie'));
        add_action('admin_enqueue_scripts', array($this, 'admin_script'));
        add_action('wp_ajax_open_shop_install_and_activate_callback', array($this, 'install_and_activate_callback'));
        add_action('after_switch_theme', array($this, 'clear_notice_cookie'));

        if (isset($_GET['notice-disable']) && $_GET['notice-disable'] == true) {
            add_action('admin_init', array($this, 'set_cookie'));
        }

        if (!isset($_COOKIE['open_shop_thms_time'])) {
            add_action('admin_notices', array($this, 'display_admin_notice'));
        }

        if (isset($_COOKIE['open_shop_thms_time'])) {
            add_action('admin_notices', array($this, 'unset_cookie'));
        }
    }

    public function set_cookie() {
        $cok_time = time() + (86457 * 7);

        if (!isset($_COOKIE['open_shop_thms_time'])) {
            setcookie('open_shop_thms_time', $cok_time, time() + (86457 * 7), COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    public function unset_cookie() {
        $visit_time = time();
        $cookie_time = isset($_COOKIE['open_shop_thms_time']) ? $_COOKIE['open_shop_thms_time'] : 0;

        if ($cookie_time < $visit_time) {
            setcookie('open_shop_thms_time', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    public function clear_notice_cookie() {
        if (isset($_COOKIE['open_shop_thms_time'])) {
            setcookie('open_shop_thms_time', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    public function display_admin_notice() {
        $allowed_pages = array(
        'dashboard',             // index.php
        'themes',                // themes.php
        'plugins',               // plugins.php
        'users',
        'appearance_page_thunk_started' // appearance_page_thunk_started
    );

    // Get the current screen
    $current_screen = get_current_screen();

    // Check if the current screen is one of the allowed pages
    if (!in_array($current_screen->base, $allowed_pages)) {
        return; // Exit if not on an allowed page
    }
        global $current_user;
        $user_id = $current_user->ID;
        $theme_data = wp_get_theme();

        if (get_user_meta($user_id, esc_html($theme_data->get('TextDomain')) . '_notice_ignore')) {
            return;
        }

        $plugin_data = get_theme_support('recommend-plugins');
        $plugin_data = $plugin_data[0];
        $hunk_companion = isset($plugin_data['hunk-companion']) ? $plugin_data['hunk-companion'] : array();
        $th_shop_mania_pro = isset($hunk_companion['pro-plugin']) ? $hunk_companion['pro-plugin'] : array();

        $plugin_pro_slug = isset($th_shop_mania_pro['slug']) ? $th_shop_mania_pro['slug'] : 'open-shop-pro';
        $plugin_pro_file = isset($th_shop_mania_pro['init']) ? $th_shop_mania_pro['init'] : 'open-shop-pro/open-shop-pro';
        $plugin_companion_slug = isset($plugin_data['hunk-companion']['slug']) ? $plugin_data['hunk-companion']['slug'] : 'hunk-companion';
        $plugin_companion_file = isset($plugin_data['hunk-companion']['active_filename']) ? $plugin_data['hunk-companion']['active_filename'] : '';

        $plugin_pro_installed = is_plugin_active($plugin_pro_file);
        $plugin_pro_exists = file_exists(WP_PLUGIN_DIR . '/' . $plugin_pro_file);
        $plugin_companion_installed = is_plugin_active($plugin_companion_file);
        $plugin_companion_exists = file_exists(WP_PLUGIN_DIR . '/' . $plugin_companion_file);

        if ((isset($_GET['page']) && $_GET['page'] == 'thunk_started') || ((!$plugin_pro_exists && !$plugin_companion_exists) || ($plugin_pro_exists && !$plugin_pro_installed) || (!$plugin_pro_exists && $plugin_companion_exists && !$plugin_companion_installed))) {

            if ($plugin_pro_exists) {
                if ($plugin_pro_installed) {
                    echo '<div class="notice notice-info th-shop-mania-wrapper-banner is-dismissible">
                        <div class="left"><h2 class="title">
                             '.sprintf(esc_html__('Thank you for installing %1$s - Version %2$s', 'open-shop'), esc_html($theme_data->Name), esc_html($theme_data->Version)).'</h2>
                            <p>'.esc_html__('To take full advantage of all the features this theme has to offer, please install and activate the ', 'open-shop').'<strong>Hunk Companion</strong></p>
                            <button class="button button-primary" id="go-to-starter-sites" data-slug="'.esc_attr($plugin_pro_slug).'">'.esc_html__('Go to Ready To Import website Templates ', 'open-shop').'</button>
                        </div>
                        <div class="right">
                            <img src="'.esc_url(get_template_directory_uri().'/lib/notification/banner.png').'" />
                        </div>
                    </div>';
                } else {
                    echo '<div class="notice notice-info th-shop-mania-wrapper-banner is-dismissible">
                        <div class="left">
                            <h2 class="title">
                             '.sprintf(esc_html__('Thank you for installing %1$s - Version %2$s', 'open-shop'), esc_html($theme_data->Name), esc_html($theme_data->Version)).'</h2>
                            <p>'.esc_html__('To take full advantage of all the features this theme has to offer, please install and activate the ', 'open-shop').'<strong>Open Shop Pro</strong></p>
                            <button class="button button-primary" id="activate-th-shop-mania-pro" data-slug="'.esc_attr($plugin_pro_slug).'"><span>'.esc_html__('Activate', 'open-shop').'</span><span class="dashicons dashicons-update loader"></span></button>
                             <button class="button button-primary" id="go-to-starter-sites" data-slug="'.esc_attr($plugin_pro_slug).'" disabled>'.esc_html__('Go to Ready To Import website Templates ', 'open-shop').'</button>
                        </div>
                        <div class="right">
                            <img src="'.esc_url(get_template_directory_uri().'/lib/notification/banner.png').'" />
                        </div>
                    </div>';
                }
            } else {
                $plugin_companion_installed = is_plugin_active($plugin_companion_file);
                $plugin_companion_exists = file_exists(WP_PLUGIN_DIR . '/' . $plugin_companion_file);

                echo '<div class="notice notice-info th-shop-mania-wrapper-banner is-dismissible">
                    <div class="left">
                          <h2 class="title">
                             '.sprintf(esc_html__('Thank you for installing %1$s - Version %2$s', 'open-shop'), esc_html($theme_data->Name), esc_html($theme_data->Version)).'</h2>
                            <p>'.esc_html__('To take full advantage of all the features this theme has to offer, please install and activate the ', 'open-shop').'<strong>Hunk Companion</strong></p>';

                if ($plugin_companion_exists) {
                    if ($plugin_companion_installed) {
                        echo '<button class="button button-primary" id="go-to-starter-sites" data-slug="'.esc_attr($plugin_pro_slug).'">'.esc_html__('Go to Ready To Import website Templates ', 'open-shop').'<span class="dashicons dashicons-update loader"></span></button>';
                    } else {
                        echo '<button class="button button-primary" id="activate-hunk-companion" data-slug="'.esc_attr($plugin_companion_slug).'"><span>'.esc_html__('Activate', 'open-shop').'</span><span class="dashicons dashicons-update loader"></span></button> <button class="button button-primary" id="go-to-starter-sites" data-slug="'.esc_attr($plugin_pro_slug).'" disabled>'.esc_html__('Go to Ready To Import website Templates ', 'open-shop').'</button>';
                    }
                } else {
                    echo '<button class="button button-primary" id="install-hunk-companion" data-slug="'.esc_attr($plugin_companion_slug).'"><span>'.esc_html__('Install', 'open-shop').'</span><span class="dashicons dashicons-update loader"></span></button><button class="button button-primary" id="go-to-starter-sites" data-slug="'.esc_attr($plugin_pro_slug).'" disabled>'.esc_html__('Go to Ready To Import website Templates ', 'open-shop').'</button>';
                }

                echo '</div>
                    <div class="right">
                        <img src="'.esc_url(get_template_directory_uri().'/lib/notification/banner.png').'" />
                    </div>
                    <a href="?notice-disable=1" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>
                </div>';
            }
        }
    }

    public function install_custom_plugin($plugin_slug) {
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $plugin_info = plugins_api('plugin_information', array('slug' => $plugin_slug));

        if (is_wp_error($plugin_info)) {
            return $plugin_info->get_error_message();
        }

        $upgrader = new Plugin_Upgrader(new Plugin_Installer_Skin(array(
            'api' => $plugin_info,
        )));

        $result = $upgrader->install($plugin_info->download_link);

        if (is_wp_error($result)) {
            return $result->get_error_message();
        }

        return "success";
    }

    public function install_and_activate_callback() {
        check_ajax_referer('thactivatenonce', 'security');

        $plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field($_POST['plugin_slug']) : '';

        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => 'Plugin slug is missing.'));
            return;
        }

        $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_slug . '.php';

        if (!file_exists($plugin_file)) {
            ob_start();

            $status = $this->install_custom_plugin($plugin_slug);

            $install_output = ob_get_clean();

            if (is_wp_error($status)) {
                wp_send_json_error(array('message' => $status->get_error_message(), 'install_output' => $install_output));
                return;
            }

            if (!file_exists($plugin_file)) {
                wp_send_json_error(array('message' => 'Plugin file does not exist after installation.', 'install_output' => $install_output));
                return;
            }
        }

        if (!is_plugin_active($plugin_file)) {
            $status = activate_plugin($plugin_file);
            if (is_wp_error($status)) {
                wp_send_json_error(array('message' => $status->get_error_message()));
                return;
            }
        }
        wp_send_json_success('Plugin installed and activated successfully.');
    }

    public function admin_script($hook_suffix) {
        $allowed_pages = array(
            'index.php',
            'themes.php',
            'plugins.php',
            'users.php',
            'appearance_page_thunk_started'
        );

        if (!in_array($hook_suffix, $allowed_pages)) {
            return;
        }

        wp_enqueue_style('open-shop-admin-css', get_template_directory_uri() . '/lib/notification/css/admin.css', array(), OPEN_SHOP_THEME_VERSION, 'all');
        wp_enqueue_script('open-shop-notifyjs', get_template_directory_uri() . '/lib/notification/js/notify.js', array('jquery'), OPEN_SHOP_THEME_VERSION, true);

        wp_localize_script('open-shop-notifyjs', 'theme_data', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('thactivatenonce'),
            'redirectUrl' => esc_url(admin_url('themes.php?page=themehunk-site-library&template=step'))
        ));
    }
}

new OpenShopAdminNotice();
?>
