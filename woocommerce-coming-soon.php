<?php
/*
 * Plugin Name:       WooCommerce Coming Soon
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-coming-soon/
 * Description:       Enables you to display a message to customers saying the product is "Coming Soon" rather than "Out of Stock".
 * Version:           1.0.1
 * Author:            Sebs Studio
 * Author URI:        http://www.sebs-studio.com
 * Developer:         Sébastien Dumont
 * Developer URI:     http://www.sebastiendumont.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ss-wc-coming-soon
 * Domain Path:       languages
 * Network:           false
 * GitHub Plugin URI: https://github.com/Sebs-Studio/WooCommerce-Coming-Soon
 *
 * WooCommerce Coming Soon is distributed under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * WooCommerce Coming Soon is distributed in the hope that it
 * will be useful but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce Coming Soon.
 * If not, see <http://www.gnu.org/licenses/gpl-2.0.txt>
 *
 * Copyright: (c) 2015 Sebs Studio. (sebastien@sebs-studio.com)
 *
 * @package  SS_WC_Coming_Soon
 * @author   Sebs Studio
 * @category Core
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SS_WC_Coming_Soon' ) ) {

/**
 * Main WooCommerce Coming Soon Class
 *
 * @version 1.0.1
 */
final class SS_WC_Coming_Soon {

	/**
	 * The single instance of the class
	 *
	 * @since  1.0.1
	 * @access protected
	 * @var    WooCommerce Coming Soon
	 */
	protected static $_instance = null;

	/**
	 * Slug
	 *
	 * @since  1.0.1
	 * @access public
	 * @var    string
	 */
	public $plugin_slug = 'ss_wc_coming_soon';

	/**
	 * Text Domain
	 *
	 * @since  1.0.1
	 * @access public
	 * @var    string
	 */
	public $text_domain = 'ss-wc-coming-soon';

	/**
	 * The Plugin name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = "WooCommerce Coming Soon";

	/**
	 * The Plug-in version.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $version = "1.0.1";

	/**
	 * The WordPress version the plugin requires minumum.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $wp_version_min = "4.2.0";

	/**
	 * The WooCommerce version this extension requires minimum.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $woo_version_min = "2.1.0";

	/**
	 * The Plug-in documentation URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $doc_url = "https://github.com/Sebs-Studio/WooCommerce-Coming-Soon/wiki/";

	/**
	 * The WordPress Plug-in Support URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $wp_support_url = "http://wordpress.org/support/plugin/woocommerce-coming-soon";

	/**
	 * The Plug-in manage woocommerce.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $manage_plugin = "manage_woocommerce";

	/**
	 * Main WooCommerce Coming Soon Instance
	 *
	 * Ensures only one instance of WooCommerce Coming Soon is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @see    SS_WC_Coming_Soon()
	 * @return WooCommerce Coming Soon - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new SS_WC_Coming_Soon;
		}
		return self::$_instance;
	} // END instance()

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->text_domain ), $this->version );
	} // END __clone()

	/**
	 * Disable unserializing of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->text_domain ), $this->version );
	} // END __wakeup()

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Check plugin requirements
		$this->check_requirements();

		// Include required files
		$this->includes();

		// Hooks
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		// Initiate plugin
		add_action( 'init', array( $this, 'init_wc_coming_soon' ), 0 );
	} // END __construct()

	/**
	 * Plugin row meta links
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $input already defined meta links
	 * @param  string $file plugin file path and name being processed
	 * @return array $input
	 */
	public function plugin_row_meta( $input, $file ) {
		if ( plugin_basename( __FILE__ ) !== $file ) {
			return $input;
		}

		$links = array(
			'<a href="' . $this->doc_url . '" target="_blank">' . __( 'Documentation', $this->text_domain ) . '</a>',
			'<a href="' . $this->wp_support_url . '" target="_blank">' . __( 'Support', $this->text_domain ) . '</a>',
			'<a href="https://profiles.wordpress.org/sebsstudio/" target="_blank">' . __( 'More Free Extensions', $this->text_domain ) . '</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	} // END plugin_row_meta()

	/**
	 * Define Constants
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_constants() {
		define( 'WC_COMING_SOON', $this->name );
		define( 'WC_COMING_SOON_FILE', __FILE__ );
		define( 'WC_COMING_SOON_VERSION', $this->version );
		define( 'WC_COMING_SOON_WP_VERSION_REQUIRE', $this->wp_version_min );
		define( 'WC_COMING_SOON_WOO_VERSION_REQUIRE', $this->woo_version_min );
	} // END define_constants()

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @since  1.0.0
	 * @access private
	 * @global string $wp_version
	 * @global string $woocommerce
	 * @return bool
	 */
	public function check_requirements() {
		global $wp_version, $woocommerce;

		$wc_version_installed = get_option( 'woocommerce_version' );
		if ( empty( $wc_version_installed ) ) { $wc_version_installed = WOOCOMMERCE_VERSION; }
		define( 'WC_COMING_SOON_WOOVERSION', $wc_version_installed );

		if (!version_compare($wp_version, WC_COMING_SOON_WP_VERSION_REQUIRE, '>=')) {
			add_action('admin_notices', array( $this, 'display_req_notice' ) );
			return false;
		}

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action('admin_notices', array( $this, 'display_req_woo_not_active_notice' ) );
			return false;
		} else {
			if ( version_compare( WC_COMING_SOON_WOOVERSION, WC_COMING_SOON_WOO_VERSION_REQUIRE, '<' ) ) {
				add_action('admin_notices', array( $this, 'display_req_woo_notice' ) );
				return false;
			}
		}

		return true;
	} // END check_requirements()

	/**
	 * Display the WordPress requirement notice.
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
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
	 * @since  1.0.0
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
	 * @since  1.0.0
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
	} // END includes()

	/**
	 * Include required admin files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'includes/woocommerce-coming-soon-hooks.php' ); // Hooks used in the admin
		include_once( 'includes/admin/class-woocommerce-coming-soon-admin.php' ); // Admin section
	} // END admin_includes()

	/**
	 * Include required frontend files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
		// Functions
		include_once( 'includes/woocommerce-coming-soon-template-hooks.php' ); // Include template hooks for themes to remove/modify them
		include_once( 'includes/woocommerce-coming-soon-functions.php' ); // Contains functions for various front-end events
	} // END frontend_includes()

	/**
	 * Runs when the plugin is initialized.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init_wc_coming_soon() {
		// Set up localisation
		$this->load_plugin_textdomain();
	} // END init_wc_coming_soon()

	/**
	* Load Localisation files.
	*
	* Note: The first-loaded translation file overrides any following ones if
	* the same translation is present.
	*
	* @since  1.0.1
	* @access public
	* @return void
	*/
	public function load_plugin_textdomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( WC_COMING_SOON_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'ss_wc_coming_soon_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale',  get_locale(), $this->text_domain );
		$mofile = sprintf( '%1$s-%2$s.mo', $this->text_domain, $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->text_domain . '/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/ss-wc-coming-soon/ folder
			load_textdomain( $this->text_domain, $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/ss-wc-coming-soon/languages/ folder
			load_textdomain( $this->text_domain, $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( $this->text_domain, false, $lang_dir );
		}
	} // END load_plugin_textdomain()

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	} // END plugin_url()

	/**
	 * Get the plugin path.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	} // END plugin_path()

} // END SS_WC_Coming_Soon()

} // END if class_exists('SS_WC_Coming_Soon')

/**
 * Returns the main instance of SS_WC_Coming_Soon to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WooCommerce Coming Soon
 */
function SS_WC_Coming_Soon() {
	return SS_WC_Coming_Soon::instance();
}

// Run the plugin.
SS_WC_Coming_Soon();
