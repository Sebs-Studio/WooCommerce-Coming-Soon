<?php
/**
 * WooCommerce Coming Soon Template Hooks
 *
 * Action/filter hooks used for Coming Soon functions/templates
 *
 * @author   Sebs Studio
 * @category Core
 * @package  WooCommerce Coming Soon/Templates
 * @version  1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'woocommerce_get_availability', 'ss_wc_coming_soon_change_stock_status_label', 10, 2 );
