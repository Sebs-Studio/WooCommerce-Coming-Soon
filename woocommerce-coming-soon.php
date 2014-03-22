<?php
/*
 * Plugin Name: WooCommerce Coming Soon
 * Plugin URI: https://github.com/seb86/WooCommerce-Coming-Soon
 * Description: Enables you to display a message to customers saying the product is "Coming Soon" rather than "Out of Stock".
 * Version: 1.0.0
 * Author: Sebs Studio
 * Author URI: http://www.sebs-studio.com
 * Author Email: sebastien@sebs-studio.com
 * Requires at least: 3.7.1
 * Tested up to: 3.8.1
 *
 * Text Domain: wc_coming_soon
 * Domain Path: /languages/
 * Network: false
 *
 * Copyright: (c) 2014 Sebs Studio. (sebastien@sebs-studio.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WC_Coming_Soon
 * @author Sebs Studio
 * @category Core
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Coming_Soon' ) ) {

/**
 * Main WooCommerce Coming Soon Class
 *
 * @class WC_Coming_Soon
 * @version 1.0.0
 */
final class WC_Coming_Soon {

	/**
	 * Constants
	 */

	// Slug
	const slug = 'wc_coming_soon';

	// Text Domain
	const text_domain = 'wc_coming_soon';

	/**
	 * Global Variables
	 */

	/**
	 * The Plug-in name.
	 *
	 * @var string
	 */
	public $name = "WooCommerce Coming Soon";

	/**
	 * The Plug-in version.
	 *
	 * @var string
	 */
	public $version = "1.0.0";

	/**
	 * The WordPress version the plugin requires minumum.
	 *
	 * @var string
	 */
	public $wp_version_min = "3.7.1";

	/**
	 * The WooCommerce version this extension requires minumum.
	 *
	 * @var string
	 */
	public $woo_version_min = "1.6.6";

	/**
	 * The single instance of the class
	 *
	 * @var WooCommerce Coming Soon
	 */
	protected static $_instance = null;

	/**
	 * The Plug-in documentation URL.
	 *
	 * @var string
	 */
	public $doc_url = "https://github.com/seb86/WooCommerce-Coming-Soon/wiki/";

	/**
	 * The WordPress Plug-in Support URL.
	 *
	 * @var string
	 */
	public $wp_plugin_support_url = "http://wordpress.org/support/plugin/woocommerce-coming-soon";

	/**
	 * The Plug-in manage woocommerce.
	 *
	 * @var string
	 */
	public $manage_plugin = "manage_woocommerce";

	/**
	 * Main WooCommerce Coming Soon Instance
	 *
	 * Ensures only one instance of WooCommerce Coming Soon is loaded or can be loaded.
	 *
	 * @access public static
	 * @see WC_Coming_Soon()
	 * @return WooCommerce Coming Soon - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Plugin links
		add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );

		// Check plugin requirements
		add_action( 'admin_init', array( &$this, 'check_requirements' ) );

		// Initiate plugin
		add_action( 'init', array( &$this, 'init_wc_coming_soon' ), 0 );
	}

	/**
	 * Plugin row meta links
	 *
	 * @access public
	 * @param array $input already defined meta links
	 * @param string $file plugin file path and name being processed
	 * @return array $input
	 */
	public function plugin_row_meta( $input, $file ) {
		if ( plugin_basename( __FILE__ ) !== $file ) {
			return $input;
		}

		$links = array(
			'<a href="'.$this->doc_url.'" target="_blank">'.__('Documentation', self::text_domain).'</a>',
			'<a href="'.$this->wp_plugin_support_url.'" target="_blank">'.__('Support', self::text_domain).'</a>',
			'<a href="http://www.sebs-studio.com/donation/" target="_blank">'.__('Donate', self::text_domain).'</a>',
			'<a href="http://www.sebs-studio.com/wp-plugins/woocommerce-extensions/" target="_blank">'.__('More WooCommerce Extensions', self::text_domain).'</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	}

	/**
	 * Define Constants
	 *
	 * @access private
	 */
	private function define_constants() {
		define( 'WC_COMING_SOON', $this->name );
		define( 'WC_COMING_SOON_FILE', __FILE__ );
		define( 'WC_COMING_SOON_VERSION', $this->version );
		define( 'WC_COMING_SOON_WP_VERSION_REQUIRE', $this->wp_version_min );
		define( 'WC_COMING_SOON_WOO_VERSION_REQUIRE', $this->woo_version_min );
	}

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @access public
	 * @global string $wp_version
	 * @return boolean
	 */
	public function check_requirements() {
		global $wp_version, $woocommerce;

		$woo_version_installed = get_option('woocommerce_version');
		if( empty( $woo_version_installed ) ) { $woo_version_installed = WOOCOMMERCE_VERSION; }
		define( 'WC_COMING_SOON_WOOVERSION', $woo_version_installed );

		if (!version_compare($wp_version, WC_COMING_SOON_WP_VERSION_REQUIRE, '>=')) {
			add_action('admin_notices', array( &$this, 'display_req_notice' ) );
			return false;
		}

		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action('admin_notices', array( &$this, 'display_req_woo_not_active_notice' ) );
			return false;
		}
		else{
			if( version_compare(WC_COMING_SOON_WOOVERSION, WC_COMING_SOON_WOO_VERSION_REQUIRE, '<' ) ) {
				add_action('admin_notices', array( &$this, 'display_req_woo_notice' ) );
				return false;
			}
		}

		return true;
	}

	/**
	 * Display the WordPress requirement notice.
	 *
	 * @access static
	 */
	static function display_req_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WordPress %s or higher. Please upgrade your WordPress setup.', self::text_domain), WC_COMING_SOON, WC_COMING_SOON_WP_VERSION_REQUIRE );
		echo '</p></div>';
	}

	/**
	 * Display the WooCommerce requirement notice.
	 *
	 * @access static
	 */
	static function display_req_woo_not_active_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WooCommerce to be installed and activatd first. Please <a href="%s">install WooCommerce</a> first.', self::text_domain), WC_COMING_SOON, admin_url('plugin-install.php?tab=search&type=term&s=WooCommerce') );
		echo '</p></div>';
	}

	/**
	 * Display the WooCommerce requirement notice.
	 *
	 * @access static
	 */
	static function display_req_woo_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WooCommerce %s or higher. Please update WooCommerce for %s to work.', self::text_domain), WC_COMING_SOON, WC_COMING_SOON_WOO_VERSION_REQUIRE, WC_COMING_SOON );
		echo '</p></div>';
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {
		if ( is_admin() ) {
			$this->admin_includes();
		}

		if ( ! is_admin() || defined('DOING_AJAX') ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required admin files.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'includes/woocommerce-coming-soon-hooks.php' ); // Hooks used in the admin
		include_once( 'includes/admin/class-woocommerce-coming-soon-admin.php' ); // Admin section
	}

	/**
	 * Include required frontend files.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
		// Functions
		include_once( 'includes/woocommerce-coming-soon-template-hooks.php' ); // Include template hooks for themes to remove/modify them
		include_once( 'includes/woocommerce-coming-soon-functions.php' ); // Contains functions for various front-end events
	}

	/**
	 * Runs when the plugin is initialized.
	 *
	 * @access public
	 */
	public function init_wc_coming_soon() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any 
	 * following ones if the same translation is present.
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), self::text_domain );

		load_textdomain( self::text_domain, WP_LANG_DIR . "/".self::slug."/" . $locale . ".mo" );

		// Set Plugin Languages Directory
		// Plugin translations can be filed in the wc-coming-soon/languages/ directory
		// Wordpress translations can be filed in the wp-content/languages/ directory
		load_plugin_textdomain( self::text_domain, false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

} // end class

} // end if class exists

/**
 * Returns the main instance of WC_Coming_Soon to prevent the need to use globals.
 *
 * @return WooCommerce Coming Soon
 */
function WC_Coming_Soon() {
	return WC_Coming_Soon::instance();
}

// Global for backwards compatibility.
$GLOBALS['wc_coming_soon'] = WC_Coming_Soon();

?>