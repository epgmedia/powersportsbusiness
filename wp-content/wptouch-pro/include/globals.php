<?php

add_action( 'wptouch_pro_loaded', 'wptouch_load_extensions' );

function wptouch_load_extensions() {
	if ( defined( 'WORDTWIT_PRO_INSTALLED' ) ) {
		require_once( WPTOUCH_DIR . '/include/extensions/wordtwit-pro.php' );	
	}
}

/*!		\brief Determines whether or not the active theme supports iPad/Tablet devices
 *
 *		This function can be used to determine if the active theme supports the iPad or Tablet devices.
 *
 *		\returns TRUE if iPad is supported, otherwise FALSE
 *
 *		\ingroup templatetags 
 */
function wptouch_theme_supports_ipad() {
	global $wptouch_pro;
	
	return $wptouch_pro->theme_supports_ipad();	
}

/*!		\brief Respects the admin setting to show/hide the switch links in WPtouch and regular theme footers
 *
 *		Boolean depending on the admin setting.
 *
 *		\returns TRUE or FALSE depending on the admin setting.
 *
 *		\note If this setting is unchecked, users will NOT be able to switch from mobile to desktop, or vice versa. 
 *
 *		\ingroup templatetags 
 */

function wptouch_show_switch_link() {
	$settings = wptouch_get_settings();
	if ( $settings->show_switch_link ) {
		return true;
	} else {
		return false;
	}
}

/*!		\brief Echos the mobile/desktop switch link URL for desktop theme
 *
 *		This function echos the mobile/desktop switch link URL.  It echos the result from wptouch_get_desktop_switch_link().
 *
 *		\ingroup templatetags 
 */
function wptouch_the_desktop_switch_link() {
	echo wptouch_get_desktop_switch_link();
}

/*!		\brief Retrieves the mobile/desktop switch link URL for desktop them
 *
 *		This function can be used to retrieve the mobile/desktop switch link URL.  It can be filtered using the WordPress filter \em wptouch_desktop_switch_link.
 *
 *		\returns The URL for the desktop switch link, and respects the admin setting whether the URL re-direct is the request URI, or homepage.
 *
 *		\note Visiting this URL alters the mobile switch COOKIE and redirects back to the current page.
 *
 *		\ingroup templatetags 
 */
function wptouch_get_desktop_switch_link() {
	$settings = wptouch_get_settings();
	if ( $settings->show_switch_link ) {
		if ( $settings->home_page_redirect_address == 'same' ) {
			return apply_filters( 'wptouch_desktop_switch_link', get_bloginfo( 'url' ) . '?wptouch_switch=mobile&amp;redirect=' . urlencode( $_SERVER['REQUEST_URI'] ) );
		} else {
			return apply_filters( 'wptouch_desktop_switch_link', get_bloginfo( 'url' ) . '?wptouch_switch=mobile&amp;redirect=' . get_bloginfo( 'url' ) );
		}
	}
}