<?php

/**
 * Simulate Cron handler
 */
if ( !function_exists( 'wpcom_is_vip' ) || !wpcom_is_vip() ){

	// add option to Settings page 
	$import_options['import_without_cron'] = "This CMS polls PublishThis (without cron)";

	global $publishthis, $import_options;

	if( $publishthis->get_option('curated_publish') == 'import_without_cron' ) {
		// include simulate cron class file
		require_once $publishthis->plugin_path() . '/modules/simulate-cron/class-simulate-cron.php';

		$simulate_cron = new Publishthis_Simulate_Cron();

		// enable cron simulation
		add_action( 'wp_footer', array ( $simulate_cron, 'pt_simulate_cron' ), 1001 );
		add_action( 'admin_init', array ( $simulate_cron, 'pt_simulate_cron' ) );
	}	
}
?>
