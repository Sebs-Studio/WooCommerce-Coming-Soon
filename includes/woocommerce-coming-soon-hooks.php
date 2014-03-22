<?php
/**
 * WooCommerce Coming Soon Hooks
 *
 * Hooks for various functions used.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	WooCommerce Coming Soon/Functions
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Write panel
add_action( 'woocommerce_product_options_general_product_data', 'write_coming_soon_tab_panel' );
add_action( 'woocommerce_process_product_meta', 'write_coming_soon_tab_panel_save' );

?>