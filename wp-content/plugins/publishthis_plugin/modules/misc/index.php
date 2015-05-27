<?php

/**
 * Add Google Analitics tracking and Curated By logo
 */
include 'tracking.php';

/**
 * Add custom css to imported post according to Style Options
 */
add_action( 'wp_head', 'attach_custom_css_filter' );

//Get post custom css by id and add to the page content
function attach_custom_css_filter() {
	if ( ! is_admin() && ! is_feed() && ! is_robots() && ! is_trackback() ) {
		global $publishthis;

		if( is_singular() ) echo $publishthis->utils->display_css();
	}
}

?>
