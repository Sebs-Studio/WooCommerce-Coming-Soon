<?php
/**
 * WooCommerce Coming Soon Post Meta Data
 *
 * Adds additional fields to the product meta data.
 *
 * @author  Sebs Studio
 * @package WooCommerce Coming Soon/Admin/Post Types/Meta Boxes
 * @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Adds the option to set the product as coming soon for single products.
 *
 * @since  1.0.0
 * @access public
 * @uses   woocommerce_wp_checkbox()
 * @uses   woocommerce_wp_text_input()
 */
function ss_wc_write_coming_soon_tab_panel() {
	echo '<div class="options_group">';
	woocommerce_wp_checkbox(
		array(
			'id'            => '_set_coming_soon',
			'label'         => __( 'Set for Coming Soon?', 'ss-wc-coming-soon' ),
			'desc_tip'      => true,
			'description'   => __( 'Make sure that you have set the stock to "Out of Stock".', 'ss-wc-coming-soon' ),
			'wrapper_class' => 'hide_if_variable',
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'            => '_coming_soon_label',
			'label'         => __( 'Coming Soon Label', 'ss-wc-coming-soon' ),
			'placeholder'   => __( 'Coming Soon', 'ss-wc-coming-soon' ),
			'desc_tip'      => true,
			'description'   => __( 'Enter the label you want to show if coming soon is set. Default: Coming Soon', 'ss-wc-coming-soon' ),
			'wrapper_class' => 'hide_if_variable',
		)
	);
	echo '</div>';
} // END ss_wc_write_coming_soon_tab_panel()

/**
 * Saves the product options for single products.
 *
 * @since  1.0.0
 * @access public
 * @param  $post_id
 */
function ss_wc_save_coming_soon_tab_panel( $post_id ) {
	$wc_product_coming_soon = isset( $_POST['_set_coming_soon'] ) ? 'yes' : '';
	$wc_coming_soon_label   = trim( strip_tags( $_POST['_coming_soon_label'] ) );

	if ( !empty( $wc_product_coming_soon ) && $wc_product_coming_soon == 'yes' ) {
		update_post_meta( $post_id, '_set_coming_soon', $wc_product_coming_soon );
	} else {
		delete_post_meta( $post_id, '_set_coming_soon' );
	}

	if ( isset( $wc_coming_soon_label ) ) {
		update_post_meta( $post_id, '_coming_soon_label', $wc_coming_soon_label );
	} else {
		delete_post_meta( $post_id, '_coming_soon_label' );
	}
} // END ss_wc_save_coming_soon_tab_panel()
