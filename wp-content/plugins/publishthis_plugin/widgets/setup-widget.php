<?php
/**
 * this file included to all widgets setup forms
 */
$status = $publishthis->api->validate_token( sanitize_text_field( $publishthis->get_option('api_token') ) );
if(!$status['valid']) {
	echo '<p class="error">API Token is empty or invalid</p>';
}
//set default content type if not specified
$content_type = empty( $content_type ) ? 'feed' : $content_type;
?>

<?php
	$obj = $this; 
	include realpath( dirname( __FILE__ ) . "/../templates/automated-popup.php" );
?>
