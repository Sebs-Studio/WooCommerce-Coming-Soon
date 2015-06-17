<?php
/**
 * WooCommerce Coming Soon Post Meta Data
 *
 * Adds additional fields to the product meta data.
 *
 * @author 		Sebs Studio
 * @package 	WooCommerce Coming Soon/Admin/Post Types/Meta Boxes
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Adds the option to disable the photo tab on the product page.
function write_coming_soon_tab_panel(){
	echo '<div class="options_group">';
	woocommerce_wp_checkbox(
		array(
			'id' => '_set_coming_soon', 
			'label' => __('Set for Coming Soon?', 'wc_coming_soon'),
			'desc_tip' => 'true', 
			'description' => __( 'Make sure that you have set the stock to "Out of Stock".', 'wc_coming_soon' )
		)
	);
	woocommerce_wp_text_input(
		array(
			'id' => '_coming_soon_label', 
			'label' => __( 'Coming Soon Label', 'wc_coming_soon' ), 
			'placeholder' => __( 'Coming Soon', 'wc_coming_soon' ),
			'desc_tip' => 'true', 
			'description' => __( 'Enter the label you want to show if coming soon is set. Default: Coming Soon', 'wc_coming_soon' )
		)
	);
	echo '</div>';
}

function write_coming_soon_tab_panel_save($post_id){
	$woocommerce_product_coming_soon = isset($_POST['_set_coming_soon']) ? 'yes' : '';
	update_post_meta($post_id, '_set_coming_soon', $woocommerce_product_coming_soon);

	if( isset( $_POST['_coming_soon_label'] ) ) {
		update_post_meta($post_id, '_coming_soon_label', $_POST['_coming_soon_label']);
	}
	else{
		delete_post_meta($post_id, '_coming_soon_label', $_POST['_coming_soon_label']);
	}
}

?>
