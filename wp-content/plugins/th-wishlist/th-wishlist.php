<?php
/**
 * Plugin Name:       TH Wishlist for WooCommerce
 * Plugin URI:        https://themehunk.com/wishlist
 * Description:       TH Wishlist is a powerful and user-friendly wishlist plugin for WooCommerce that lets your customers save their favorite products for later and helps boost conversions by keeping users engaged with the products they love.
 * Version:           1.1.5
 * Author:            themehunk
 * Author URI:        https://www.themehunk.com
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       th-wishlist
 * Tested up to:      6.9
 * Requires at least: 5.0
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to:   8.9
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
 */
function thwl_hpos_compatibility() {
	if ( defined( 'THWL_PLUGIN_FILE' ) && class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', THWL_PLUGIN_FILE, true );
	}
}
add_action( 'before_woocommerce_init', 'thwl_hpos_compatibility' );

if ( ! class_exists( 'THWL_Wishlist' ) ) :

/**
 * Main THWL_Wishlist Class.
 */
final class THWL_Wishlist {

	/**
	 * @var string Plugin version.
	 */
	public $version;

	/**
	 * @var THWL_Wishlist The single instance of the class.
	 */
	protected static $_instance = null;

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->thwl_define_constants();
		$this->thwl_set_version();
		$this->thwl_includes();
		$this->thwl_init_hooks();

		// Ensure install runs if demo import skipped activation.
		add_action( 'init', array( $this, 'thwl_maybe_run_install' ) );
	}

	/**
	 * Define constants.
	 */
	private function thwl_define_constants() {
		define( 'THWL_PLUGIN_FILE', __FILE__ );
		define( 'THWL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'THWL_VERSION', $this->version );
		define( 'THWL_DIR', plugin_dir_path( __FILE__ ) );
		define( 'THWL_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Set plugin version.
	 */
	private function thwl_set_version() {
		$plugin_data   = get_file_data( __FILE__, array( 'version' => 'version' ), false );
		$this->version = $plugin_data['version'];
	}

	/**
	 * Include core files.
	 */
	public function thwl_includes() {
		require_once THWL_DIR . 'includes/admin/class-th-wishlist-list-table.php';
		require_once THWL_DIR . 'includes/admin/class-th-wishlist-data.php';
		require_once THWL_DIR . 'includes/admin/class-th-wishlist-admin.php';
		require_once THWL_DIR . 'includes/admin/class-th-wishlist-install.php';
		require_once THWL_DIR . 'includes/class-th-wishlist-settings-manager.php';
		require_once THWL_DIR . 'includes/class-th-wishlist-frontend.php';
		require_once THWL_DIR . 'includes/th-wishlist-function.php';
	}

	/**
	 * Hooks.
	 */
	private function thwl_init_hooks() {
		// Activation hook for installation.
		register_activation_hook( THWL_PLUGIN_FILE, array( 'THWL_Install', 'install' ) );

		// Initialize classes after plugins load.
		add_action( 'plugins_loaded', array( $this, 'thwl_init' ) );

		// Add settings link.
		add_action( 'init', array( $this, 'thwl_add_plugin_action_links' ) );
	}

	/**
	 * Add plugin action links filter.
	 */
	public function thwl_add_plugin_action_links() {
		add_filter( 'plugin_action_links_' . THWL_PLUGIN_BASENAME, array( $this, 'thwl_wishlist_plugin_action_links' ), 10, 1 );
	}

	/**
	 * Init plugin when plugins are loaded.
	 */
	public function thwl_init() {
		add_action( 'init', array( $this, 'thwl_check_woocommerce' ) );
	}

	/**
	 * Check if WooCommerce is active.
	 */
	public function thwl_check_woocommerce() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'thwl_woocommerce_missing_notice' ) );
			return;
		}
		THWL_Manager::get_instance();
		new THWL_Frontend();
		new THWL_Admin();
	}

	/**
	 * Add settings link to plugin row.
	 */
	public function thwl_wishlist_plugin_action_links( $links ) {
		$settings_page = add_query_arg( array( 'page' => 'thwl-wishlist' ), admin_url( 'admin.php' ) );
		$settings_link = '<a href="' . esc_url( $settings_page ) . '">' . esc_html__( 'Settings', 'th-wishlist' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Display admin notice if WooCommerce is missing.
	 */
	
		public function thwl_woocommerce_missing_notice() {
			if ( ! is_admin() ) {
				return;
			}

			$plugin_name = 'TH Wishlist';
			$woo_link    = '<a href="' . esc_url( 'https://woocommerce.com/' ) . '" target="_blank" rel="noopener noreferrer">' .
						esc_html__( 'WooCommerce', 'th-wishlist' ) .
						'</a>';

			?>
			<div class="notice notice-error is-dismissible">
				<p>
					<?php
					/* translators: 1: Plugin name. 2: WooCommerce link */
					echo wp_kses_post(
						sprintf(
							// translators: %1$s: Plugin name, %2$s: WooCommerce link
							__( '%1$s requires %2$s to be installed and active.', 'th-wishlist' ),
							esc_html( $plugin_name ),
							$woo_link
						)
					);
					?>
				</p>
			</div>
			<?php
		}



	/**
	 * Re-run installer if demo import skipped activation.
	 */
	public function thwl_maybe_run_install() {
		// Run only in admin area to avoid slowing frontend.
		if ( ! is_admin() ) {
			return;
		}

		// Avoid running check too frequently.
		if ( get_transient( 'thwl_install_checked' ) ) {
			return;
		}
		set_transient( 'thwl_install_checked', true, 12 * HOUR_IN_SECONDS );

		global $wpdb;
		$table_name = $wpdb->prefix . 'thwl_wishlists';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
		$table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );

		$page_id = get_option( 'thwl_page_id' );

		if ( ! $table_exists || ! $page_id ) {
			if ( class_exists( 'THWL_Install' ) ) {
				THWL_Install::install();
			}
		}
	}
}

endif;

/**
 * Main instance of THWL_Wishlist.
 */
function THWL() {
	return THWL_Wishlist::instance();
}

$GLOBALS['thwl_wishlist'] = THWL();