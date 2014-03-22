<?php
/**
 * WooCommerce Coming Soon Admin.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	WooCommerce Coming Soon/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Coming_Soon_Admin' ) ) {

class WC_Coming_Soon_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Actions
		add_action( 'current_screen', array( &$this, 'conditonal_includes' ) );
	}

	/**
	 * Include admin files conditionally
	 */
	public function conditonal_includes() {
		$screen = get_current_screen();

		switch ( $screen->id ) {

			case 'product' :
				include('post-types/meta-boxes/class-wc-coming-soon-meta-box-product-data.php');
				break;

		} // end switch
	}

}

} // end if class exists

return new WC_Coming_Soon_Admin();

?>