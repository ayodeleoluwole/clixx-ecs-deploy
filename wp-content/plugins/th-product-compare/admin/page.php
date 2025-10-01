<?php if (!defined('ABSPATH')) exit;
if ( ! function_exists( 'thpc_allowed_svg_tags' ) ) {
    function thpc_allowed_svg_tags() {

        return [
          'svg' => [
    'xmlns'            => true,
    'width'            => true,
    'height'           => true,
    'viewbox'          => true, // ← lowercase!
    'fill'             => true,
    'stroke'           => true,
    'stroke-width'     => true,
    'stroke-linecap'   => true,
    'stroke-linejoin'  => true,
    'class'            => true,
],

            'g' => [
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
            ],
            'path' => [
                'd'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
            ],
            'circle' => [
                'cx'              => true,
                'cy'              => true,
                'r'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
            ],
            'rect' => [
                'width'           => true,
                'height'          => true,
                'x'               => true,
                'y'               => true,
                'rx'              => true,
                'ry'              => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
            ],
            'line' => [
                'x1'              => true,
                'x2'              => true,
                'y1'              => true,
                'y2'              => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
            ],
        ];
    }
}



if ( ! function_exists( 'thpc_get_svg_icon' ) ) {
    function thpc_get_svg_icon( $name ) {

        $icons = [

            // ⚙️ Basic Settings Icon (Gear)
            'settings' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            ',

            // 🔧 Advance Icon
            'advance' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="4" y1="21" y2="14"></line>
                <line x1="4" x2="4" y1="10" y2="3"></line>
                <line x1="12" x2="12" y1="21" y2="12"></line>
                <line x1="12" x2="12" y1="8" y2="3"></line>
                <line x1="20" x2="20" y1="21" y2="16"></line>
                <line x1="20" x2="20" y1="12" y2="3"></line>
                <line x1="1" x2="7" y1="14" y2="14"></line>
                <line x1="9" x2="15" y1="8" y2="8"></line>
                <line x1="17" x2="23" y1="16" y2="16"></line>
            </svg>
            ',

            // 🔐 Premium Icon
            'premium' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            ',

            // ❓ Help Icon
            'help' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <path d="M12 17h.01"></path>
            </svg>
            ',

            // 🔌 Useful Plugins Icon
            'plugins' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22v-5"></path>
                <path d="M9 8V2"></path>
                <path d="M15 8V2"></path>
                <path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z"></path>
            </svg>
            ',

            // 🔐 Premium Icon
            'reset' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path><path d="M16 16h5v5"></path></svg>
            ',
        ];

        return $icons[ $name ] ?? '';
    }
}

?>
<div class="th-product-compare-wrap th-plugin-common-wrap">
    <div class="th-product-compare-container">
        <div class=th-left>
        <nav class="th-nav_">
            <span class="logo-detail">
                <div class="img_">
                    <img src="<?php echo esc_url(TH_PRODUCT_URL . 'assets/img/th-logo.png'); ?>">
                </div>
                <span><?php esc_html_e('Product Compare', 'th-product-compare'); ?></span>
            </span>
            <a data-group-tabs="main" data-tab="general" href="#" class="active"><span><?php echo wp_kses( thpc_get_svg_icon( 'settings' ), thpc_allowed_svg_tags() ); ?></span><?php esc_html_e('Basic Settings', 'th-product-compare'); ?></span></a>
            <a data-group-tabs="main" data-tab="setting" href="#"><span><?php echo wp_kses( thpc_get_svg_icon( 'advance' ), thpc_allowed_svg_tags() ); ?></span><?php esc_html_e('Advance', 'th-product-compare'); ?></a>
            <a data-group-tabs="main" data-tab="pro-feature" href="#"><span><?php echo wp_kses( thpc_get_svg_icon( 'premium' ), thpc_allowed_svg_tags() ); ?></span><?php esc_html_e('Premium', 'th-product-compare'); ?></a>
            <a data-group-tabs="main" data-tab="help" href="#"><span><?php echo wp_kses( thpc_get_svg_icon( 'help' ), thpc_allowed_svg_tags() ); ?></span><?php esc_html_e('Help', 'th-product-compare'); ?></a>
             <a data-group-tabs="main" data-tab="reset" href="#"><span><?php echo wp_kses( thpc_get_svg_icon( 'reset' ), thpc_allowed_svg_tags() ); ?></span><?php esc_html_e('Reset', 'th-product-compare'); ?></a>
            
        </nav>

        <div class="th-subscribe-btn">
                    <h4><?php esc_html_e('Pro Plan', 'th-product-compare'); ?></h4>
                    <h5><?php esc_html_e('Upgrade for more features', 'th-product-compare'); ?></h5>
                    <a href="<?php echo esc_url('https://themehunk.com/th-product-compare-plugin/'); ?>" target="_blank" class="th-support-btn button button-primary"><?php esc_html_e('Upgrade Now', 'th-product-compare'); ?></a>  
        </div>

        </div>

        <div class="th-right">
        <div class="top-header">
                <h2 class="tabheading"><?php esc_html_e("Basic Settings", 'th-product-compare'); ?></h2>
                 <a href="<?php echo esc_url( 'https://themehunk.com/product-compare-plugin/' ); ?>"
               title="<?php esc_attr_e( 'Get Premium Version', 'th-all-in-one-woo-cart' ); ?>"
               target="_blank">
                <?php esc_html_e( 'Get Premium Version', 'th-all-in-one-woo-cart' ); ?>
            </a>
                <div class="th-save-btn">
                    
                    <button class="button button-primary th-option-save-btn"><?php esc_html_e("Save Changes", 'th-product-compare'); ?></button>
                </div>
            </div>
            
        <div class="container-tabs">

            <!-- general tab -->
            <div data-group-tabs="main" data-tab-container="general" class="active">
                <?php include_once 'pages/general.php'; ?>
            </div>
            <!-- setting tab -->
            <div data-group-tabs="main" data-tab-container="setting">
                <?php include_once 'pages/advance-setting.php'; ?>
            </div>
            <!-- help tab -->
            <div data-group-tabs="main" data-tab-container="help">
                <?php include_once 'pages/help.php'; ?>
            </div>
            <!-- pro feature tab -->
            <div data-group-tabs="main" data-tab-container="pro-feature">
                <?php include_once 'pages/pro-feature.php'; ?>
            </div>

            <!-- reset tab -->
            <div data-group-tabs="main" data-tab-container="reset">
                <?php include_once 'pages/reset.php'; ?>
            </div>

        </div>

        </div>

    </div>
</div>