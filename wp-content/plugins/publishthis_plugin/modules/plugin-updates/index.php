<?php 

/**
 * Init Plugin Auto Update object
 */
if ( !function_exists( 'wpcom_is_vip' ) || !wpcom_is_vip() ){
global $publishthis;

// include settings file
require_once $publishthis->plugin_path() . '/publishthis-settings.php';

// include plugin updater class file
require_once $publishthis->plugin_path() . '/modules/plugin-updates/class-plugin-update-checker.php';

// init plugin updater class
$PublishThisUpdateChecker = new PluginUpdateChecker(
		AUTO_UPDATE_JSON_INFO,
		$publishthis->plugin_path() . '/publishthis.php',
		'publishthis'
);
}

?>