<?php
/**
 * WooCommerce Coming Soon Page Functions
 *
 * Functions related to product pages.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	WooCommerce Coming Soon/Functions
 * @version 	1.0.0
 */

function change_stock_status_label( $availability ) {
	global $post;

	// Change the label text "Out of Stock' to 'Coming Soon'
	$set_coming_soon = get_post_meta( $post->ID, '_set_coming_soon', true );
	$coming_soon_label = get_post_meta( $post->ID, '_coming_soon_label', true );

	if ( !empty( $set_coming_soon ) ) {
		if( !empty( $coming_soon_label ) ) {
			$availability['availability'] = $coming_soon_label;
		}
		else{
			$availability['availability'] = __( 'Coming Soon', 'wc_coming_soon' );
		}
	}
	return $availability;
}

?>