<?php
/**
 * WooCommerce Coming Soon Admin.
 *
 * @author   Sebs Studio
 * @category Admin
 * @package  WooCommerce Coming Soon/Admin
 * @version  1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SS_WC_Coming_Soon_Admin' ) ) {

class SS_WC_Coming_Soon_Admin {

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		// Actions
		add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
	} // END __construct()

	/**
	 * Include admin files conditionally.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function conditonal_includes() {
		$screen = get_current_screen();

		switch ( $screen->id ) {

			case 'product' :
				include( 'post-types/meta-boxes/class-wc-coming-soon-meta-box-product-data.php' );
				break;

		} // END switch
	} // END conditional_includes()

} // END class SS_WC_Coming_Soon_Admin()

} // END if class_exists('SS_WC_Coming_Soon_Admin')

return new SS_WC_Coming_Soon_Admin();
