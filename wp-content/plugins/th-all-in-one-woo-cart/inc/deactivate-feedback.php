<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * – Deactivation Feedback Popup
 * Shows a "Quick Feedback" modal when the user deactivates the plugin,
 * collects the reason, and sends it via REST API before deactivating.
 */

/* =========================================================
 * 1. ENQUEUE SCRIPTS / STYLES on the Plugins admin page
 * ========================================================= */
add_action( 'admin_enqueue_scripts', 'taiowc_deactivate_feedback_assets' );
function taiowc_deactivate_feedback_assets( $hook ) {
    if ( $hook !== 'plugins.php' ) return;

    wp_enqueue_style(
        'taiowc-deactivate-feedback-css',
        TAIOWC_PLUGIN_URI . 'assets/css/deactivate-feedback.css',
        array(),
        TAIOWC_VERSION
    );
    wp_enqueue_script(
        'taiowc-deactivate-feedback-js',
        TAIOWC_PLUGIN_URI . 'assets/js/deactivate-feedback.js',
        array( 'jquery' ),
        TAIOWC_VERSION,
        true
    );
    $plugin_file = TAIOWC_PLUGIN_PATH . 'th-all-in-one-woo-cart.php';
    $plugin_data = get_plugin_data( $plugin_file, false, false );

    wp_localize_script( 'taiowc-deactivate-feedback-js', 'taiowcDeactivate', array(
        'pluginFile'    => 'th-all-in-one-woo-cart/th-all-in-one-woo-cart.php',
        'apiUrl'        => rest_url( 'taiowc/v1/deactivate-feedback' ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'pluginName'    => $plugin_data['Name'],
        'pluginVersion' => $plugin_data['Version'],
        'i18n'          => array(
            'submitting' => __( 'Submitting…', 'th-all-in-one-woo-cart' ),
            'submit'     => __( 'Submit & Deactivate', 'th-all-in-one-woo-cart' ),
        ),
    ) );
}

/* =========================================================
 * 2. RENDER MODAL HTML in admin footer (plugins page only)
 * ========================================================= */
add_action( 'admin_footer', 'taiowc_deactivate_feedback_modal' );
function taiowc_deactivate_feedback_modal() {
    global $hook_suffix;
    if ( 'plugins.php' !== $hook_suffix ) return;
    $reasons = array(
        'no_longer_needed'   => __( 'I no longer need the plugin', 'th-all-in-one-woo-cart' ),
        'found_better'       => __( 'I found a better plugin', 'th-all-in-one-woo-cart' ),
        'not_working'        => __( 'I couldn\'t get the plugin to work', 'th-all-in-one-woo-cart' ),
        'temporary'          => __( 'It\'s a temporary deactivation', 'th-all-in-one-woo-cart' ),
        'missing_feature'    => __( 'Plugin is missing a required feature', 'th-all-in-one-woo-cart' ),
        'other'              => __( 'Other', 'th-all-in-one-woo-cart' ),
    );
    ?>
    <div id="taiowc-deactivate-overlay" class="taiowc-df-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="taiowc-df-title">
        <div class="taiowc-df-modal">

            <div class="taiowc-df-header">
                <span class="taiowc-df-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" rx="4" fill="#e74c3c"/>
                        <text x="10" y="15" text-anchor="middle" font-size="13" font-weight="bold" fill="#fff">!</text>
                    </svg>
                </span>
                <strong id="taiowc-df-title"><?php esc_html_e( 'QUICK FEEDBACK', 'th-all-in-one-woo-cart' ); ?></strong>
            </div>

            <div class="taiowc-df-body">
                <p><?php esc_html_e( 'If you have a moment, please share why you are deactivating All In One Advance Cart:', 'th-all-in-one-woo-cart' ); ?></p>

                <ul class="taiowc-df-reasons">
                    <?php foreach ( $reasons as $value => $label ) : ?>
                        <li>
                            <label>
                                <input type="radio" name="taiowc_deactivate_reason" value="<?php echo esc_attr( $value ); ?>" />
                                <span><?php echo esc_html( $label ); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="taiowc-df-detail" id="taiowc-df-detail-wrap" style="display:none;">
                    <textarea id="taiowc-df-detail-text" rows="3" placeholder="<?php esc_attr_e( 'Please share more details (optional)…', 'th-all-in-one-woo-cart' ); ?>"></textarea>
                </div>
            </div>

            <div class="taiowc-df-footer">
                <button type="button" id="taiowc-df-submit" class="button button-primary taiowc-df-submit-btn">
                    <?php esc_html_e( 'Submit & Deactivate', 'th-all-in-one-woo-cart' ); ?>
                </button>
                <a href="#" id="taiowc-df-skip" class="taiowc-df-skip-link">
                    <?php esc_html_e( 'Skip & Deactivate', 'th-all-in-one-woo-cart' ); ?>
                </a>
            </div>

        </div>
    </div>
    <?php
}

/* =========================================================
 * 3. REST API ROUTE – receive feedback
 * ========================================================= */
add_action( 'rest_api_init', 'taiowc_deactivate_feedback_rest_route' );
function taiowc_deactivate_feedback_rest_route() {
    register_rest_route( 'taiowc/v1', '/deactivate-feedback', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'taiowc_rest_save_deactivate_feedback',
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
        'args' => array(
            'reason' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'required'          => true,
            ),
            'details' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default'           => '',
            ),
            'site_url' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
            'plugin_version' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
            'plugin_name' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
        ),
    ) );
}

function taiowc_rest_save_deactivate_feedback( $request ) {
    $data = array(
        'reason'         => $request->get_param( 'reason' ),
        'details'        => $request->get_param( 'details' ),
        'site_url'       => $request->get_param( 'site_url' ),
        'plugin_version' => $request->get_param( 'plugin_version' ),
        'plugin_name'    => $request->get_param( 'plugin_name' ),
    );

    // Send to remote ThemeHunk server
    wp_remote_post(
        'https://themehunk.com/wp-json/wp/v2/themehunk/feedback',
        array(
            'method'   => 'POST',
            'timeout'  => 15,
            'blocking' => true,
            'headers'  => array(
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ),
            'body'     => wp_json_encode( $data ),
        )
    );

    return rest_ensure_response( array(
        'success' => true,
        'message' => __( 'Thank you for your feedback!', 'th-all-in-one-woo-cart' ),
    ) );
}
