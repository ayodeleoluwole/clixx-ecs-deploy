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
add_action( 'admin_enqueue_scripts', 'thpc_deactivate_feedback_assets' );
function thpc_deactivate_feedback_assets( $hook ) {
    if ( $hook !== 'plugins.php' ) return;

    wp_enqueue_style(
        'thpc-deactivate-feedback-css',
        TH_PRODUCT_URL . 'assets/deactivate-feedback.css',
        array(),
        '1.0.0'
    );
    wp_enqueue_script(
        'thpc-deactivate-feedback-js',
        TH_PRODUCT_URL . 'assets/js/deactivate-feedback.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );
    $plugin_file = TH_PRODUCT_PATH . 'th-product-compare.php';
    $plugin_data = get_plugin_data( $plugin_file, false, false );

    wp_localize_script( 'thpc-deactivate-feedback-js', 'thpcDeactivate', array(
        'pluginFile'    => 'th-product-compare/th-product-compare.php',
        'apiUrl'        => rest_url( 'thpc/v1/deactivate-feedback' ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'pluginName'    => $plugin_data['Name'],
        'pluginVersion' => $plugin_data['Version'],
        'i18n'          => array(
            'submitting' => __( 'Submitting…', 'th-product-compare' ),
            'submit'     => __( 'Submit & Deactivate', 'th-product-compare' ),
        ),
    ) );
}

/* =========================================================
 * 2. RENDER MODAL HTML in admin footer (plugins page only)
 * ========================================================= */
add_action( 'admin_footer', 'thpc_deactivate_feedback_modal' );
function thpc_deactivate_feedback_modal() {
    global $hook_suffix;
    if ( 'plugins.php' !== $hook_suffix ) return;
    $reasons = array(
        'no_longer_needed'   => __( 'I no longer need the plugin', 'th-product-compare' ),
        'found_better'       => __( 'I found a better plugin', 'th-product-compare' ),
        'not_working'        => __( 'I couldn\'t get the plugin to work', 'th-product-compare' ),
        'temporary'          => __( 'It\'s a temporary deactivation', 'th-product-compare' ),
        'missing_feature'    => __( 'Plugin is missing a required feature', 'th-product-compare' ),
        'other'              => __( 'Other', 'th-product-compare' ),
    );
    ?>
    <div id="thpc-deactivate-overlay" class="thpc-df-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="thpc-df-title">
        <div class="thpc-df-modal">

            <div class="thpc-df-header">
                <span class="thpc-df-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" rx="4" fill="#e74c3c"/>
                        <text x="10" y="15" text-anchor="middle" font-size="13" font-weight="bold" fill="#fff">!</text>
                    </svg>
                </span>
                <strong id="thpc-df-title"><?php esc_html_e( 'QUICK FEEDBACK', 'th-product-compare' ); ?></strong>
            </div>

            <div class="thpc-df-body">
                <p><?php esc_html_e( 'If you have a moment, please share why you are deactivating ThemeHunk Product Compare:', 'th-product-compare' ); ?></p>

                <ul class="thpc-df-reasons">
                    <?php foreach ( $reasons as $value => $label ) : ?>
                        <li>
                            <label>
                                <input type="radio" name="thpc_deactivate_reason" value="<?php echo esc_attr( $value ); ?>" />
                                <span><?php echo esc_html( $label ); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="thpc-df-detail" id="thpc-df-detail-wrap" style="display:none;">
                    <textarea id="thpc-df-detail-text" rows="3" placeholder="<?php esc_attr_e( 'Please share more details (optional)…', 'th-product-compare' ); ?>"></textarea>
                </div>
            </div>

            <div class="thpc-df-footer">
                <button type="button" id="thpc-df-submit" class="button button-primary thpc-df-submit-btn">
                    <?php esc_html_e( 'Submit & Deactivate', 'th-product-compare' ); ?>
                </button>
                <a href="#" id="thpc-df-skip" class="thpc-df-skip-link">
                    <?php esc_html_e( 'Skip & Deactivate', 'th-product-compare' ); ?>
                </a>
            </div>

        </div>
    </div>
    <?php
}

/* =========================================================
 * 3. REST API ROUTE – receive feedback
 * ========================================================= */
add_action( 'rest_api_init', 'thpc_deactivate_feedback_rest_route' );
function thpc_deactivate_feedback_rest_route() {
    register_rest_route( 'thpc/v1', '/deactivate-feedback', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'thpc_rest_save_deactivate_feedback',
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

function thpc_rest_save_deactivate_feedback( $request ) {
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
        'message' => __( 'Thank you for your feedback!', 'th-product-compare' ),
    ) );
}
