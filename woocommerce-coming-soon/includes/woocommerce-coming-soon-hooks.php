<?php
/**
 * WooCommerce Coming Soon Hooks
 *
 * @author   Sebs Studio
 * @category Core
 * @package  WooCommerce Coming Soon/Functions
 * @version  1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Write panel for single products
add_action( 'woocommerce_product_options_inventory_product_data', 'ss_wc_write_coming_soon_tab_panel' );
add_action( 'woocommerce_process_product_meta', 'ss_wc_save_coming_soon_tab_panel' );
