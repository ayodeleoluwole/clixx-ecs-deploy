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
add_action( 'admin_enqueue_scripts', 'thaps_deactivate_feedback_assets' );
function thaps_deactivate_feedback_assets( $hook ) {
    if ( $hook !== 'plugins.php' ) return;

    wp_enqueue_style(
        'thaps-deactivate-feedback-css',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'assets/css/deactivate-feedback.css',
        array(),
        TH_ADVANCE_PRODUCT_SEARCH_VERSION
    );
    wp_enqueue_script(
        'thaps-deactivate-feedback-js',
        TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'assets/js/deactivate-feedback.js',
        array( 'jquery' ),
        TH_ADVANCE_PRODUCT_SEARCH_VERSION,
        true
    );
    $plugin_file = TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . 'th-advance-product-search.php';
    $plugin_data = get_plugin_data( $plugin_file, false, false );

    wp_localize_script( 'thaps-deactivate-feedback-js', 'thapsDeactivate', array(
        'pluginFile'    => 'th-advance-product-search/th-advance-product-search.php',
        'apiUrl'        => rest_url( 'thaps/v1/deactivate-feedback' ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'pluginName'    => $plugin_data['Name'],
        'pluginVersion' => $plugin_data['Version'],
        'i18n'          => array(
            'submitting' => __( 'Submitting…', 'th-advance-product-search' ),
            'submit'     => __( 'Submit & Deactivate', 'th-advance-product-search' ),
        ),
    ) );
}

/* =========================================================
 * 2. RENDER MODAL HTML in admin footer (plugins page only)
 * ========================================================= */
add_action( 'admin_footer', 'thaps_deactivate_feedback_modal' );
function thaps_deactivate_feedback_modal() {
    global $hook_suffix;
    if ( 'plugins.php' !== $hook_suffix ) return;
    $reasons = array(
        'no_longer_needed'   => __( 'I no longer need the plugin', 'th-advance-product-search' ),
        'found_better'       => __( 'I found a better plugin', 'th-advance-product-search' ),
        'not_working'        => __( 'I couldn\'t get the plugin to work', 'th-advance-product-search' ),
        'temporary'          => __( 'It\'s a temporary deactivation', 'th-advance-product-search' ),
        'missing_feature'    => __( 'Plugin is missing a required feature', 'th-advance-product-search' ),
        'other'              => __( 'Other', 'th-advance-product-search' ),
    );
    ?>
    <div id="thaps-deactivate-overlay" class="thaps-df-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="thaps-df-title">
        <div class="thaps-df-modal">

            <div class="thaps-df-header">
                <span class="thaps-df-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" rx="4" fill="#e74c3c"/>
                        <text x="10" y="15" text-anchor="middle" font-size="13" font-weight="bold" fill="#fff">!</text>
                    </svg>
                </span>
                <strong id="thaps-df-title"><?php esc_html_e( 'QUICK FEEDBACK', 'th-advance-product-search' ); ?></strong>
            </div>

            <div class="thaps-df-body">
                <p><?php esc_html_e( 'If you have a moment, please share why you are deactivating Advance Product Search:', 'th-advance-product-search' ); ?></p>

                <ul class="thaps-df-reasons">
                    <?php foreach ( $reasons as $value => $label ) : ?>
                        <li>
                            <label>
                                <input type="radio" name="thaps_deactivate_reason" value="<?php echo esc_attr( $value ); ?>" />
                                <span><?php echo esc_html( $label ); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="thaps-df-detail" id="thaps-df-detail-wrap" style="display:none;">
                    <textarea id="thaps-df-detail-text" rows="3" placeholder="<?php esc_attr_e( 'Please share more details (optional)…', 'th-advance-product-search' ); ?>"></textarea>
                </div>
            </div>

            <div class="thaps-df-footer">
                <button type="button" id="thaps-df-submit" class="button button-primary thaps-df-submit-btn">
                    <?php esc_html_e( 'Submit & Deactivate', 'th-advance-product-search' ); ?>
                </button>
                <a href="#" id="thaps-df-skip" class="thaps-df-skip-link">
                    <?php esc_html_e( 'Skip & Deactivate', 'th-advance-product-search' ); ?>
                </a>
            </div>

        </div>
    </div>
    <?php
}

/* =========================================================
 * 3. REST API ROUTE – receive feedback
 * ========================================================= */
add_action( 'rest_api_init', 'thaps_deactivate_feedback_rest_route' );
function thaps_deactivate_feedback_rest_route() {
    register_rest_route( 'thaps/v1', '/deactivate-feedback', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'thaps_rest_save_deactivate_feedback',
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

function thaps_rest_save_deactivate_feedback( $request ) {
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
        'message' => __( 'Thank you for your feedback!', 'th-advance-product-search' ),
    ) );
}
