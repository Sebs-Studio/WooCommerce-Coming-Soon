<?php
/**
 * WooCommerce Coming Soon Page Functions
 *
 * Functions related to product pages.
 *
 * @author   Sebs Studio
 * @category Core
 * @package  WooCommerce Coming Soon/Functions
 * @version  1.0.1
 */

function ss_wc_coming_soon_change_stock_status_label( $availability, $_product ) {
	global $post;

	// First check that if we have stock.
	if ( !$_product->is_in_stock() ) {

		// Change the label text "Out of Stock" to "'Coming Soon".
		$set_coming_soon   = get_post_meta( $post->ID, '_set_coming_soon', true );
		$coming_soon_label = get_post_meta( $post->ID, '_coming_soon_label', true );

		if ( !empty( $set_coming_soon ) && $set_coming_soon == 'yes' ) {
			if ( !empty( $coming_soon_label ) ) {
				$availability['availability'] = $coming_soon_label;
			} else {
				$availability['availability'] = __( 'Coming Soon', 'ss-wc-coming-soon' );
			}

			$availability['class'] = 'out-of-stock coming-soon';
		}
	}

	return $availability;
}
