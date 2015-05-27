<?php

/**
 * defines Simulate Cron option
 */

class Publishthis_Simulate_Cron {
	/**
	 *   Publishthis_Simulate_Cron constructor.
	 */
	function __construct() {}

	/**
	 *   simulate cron is wp cron is not available
	 */
	function simulateCron() {
		global $publishthis;

		// Return here is we want to pause polling.
		if ( $publishthis->get_option ( 'pause_polling' ) ) {
			if ( ! get_option ( 'publishthis_paused_on' ) ) {
				update_option ( 'publishthis_paused_on', time() );
			}
			return;
		}

		ignore_user_abort( true );

		//modifying the logic here a bit and going with options, instead of
		//transients, because those were sometimes disappearing from
		//the wp cache. don't want to disrupt the clients site

		//basic algorithm
		/*
      1 - see if we are doing the cron all ready, if so, don't do anything
      2 - if not doing cron, get the last timestamp of when we did this cron
        -- we only want to check every XX minutes
      3 - if no time is set yet, we do the check
      4 - if the time is set, and we have not yet passed our XX minutes, we do not do anything
      5 - if we are doing the check, update that we are doing the cron
      6 - do the cron action
      7 - once completed, set
         - the timestamp we completed at, for future checks
         - remove the doing cron value
    */
		$doingSimulatedCron = get_option ( 'pt_simulated_cron' );

		//create lock flag if not exists and set it to 0 (false)
		if ( false === $doingSimulatedCron ) {
			update_option( "pt_simulated_cron", 0 );
		}
		$doingSimulatedCron = intval($doingSimulatedCron);
		//cron is not running
		if ( 0 === $doingSimulatedCron ) {
			//check the time
			$secondsExpiration = 60 * 2; //roughly 2 minutes. should be based on publishing action set poll times, but that would be too much to query;

			$timestamp = get_option ( 'pt_simulated_cron_ts' );

			$currentTimestamp = ( time() ) * 1000;

			if ( !$timestamp ) {
				//this has never been set before, so, we can just assume we need to do the cron
				$timestamp = $currentTimestamp;
				
				//set the timestamp the first time
				update_option( "pt_simulated_cron_ts", $timestamp );
			}
			//see if we need to do the cron
			$diffTimestamp = $currentTimestamp - $timestamp;
			
			$diffTimeSeconds = ( $diffTimestamp / 1000 );
	
			if ( $diffTimeSeconds >= $secondsExpiration ) {
				//ok, we need to do the cron action
				update_option( "pt_simulated_cron", 1 );

				try {
					//if we are here, that means we need to do the cron action
					//get only active Publishing Actions
					$actions = $publishthis->publish->get_publishing_actions();

					$publishthis->log->addWithLevel ( array(
							'message' => 'Checking on simulated cron events',
							'status' => 'info',
							'details' => "Found " . count( $actions ) . " publishing events to check" ), "2" );
					
					// do import
					$publishthis->publish->run_import();
				} catch (Exception $e) {
					//set simulate cron options on failure
					//leaving duplicated lines, because php4 doesn't have finally block
					update_option( "pt_simulated_cron_ts", $currentTimestamp );
					update_option( "pt_simulated_cron", 0 );
				}
			
				//now that we are done, set the old timestamp
				update_option( "pt_simulated_cron_ts", $currentTimestamp );
				update_option( "pt_simulated_cron", 0 );
			}	
		}		
	}

	/**
	 *   Process cron simulation
	 */
	function pt_simulate_cron() {
		global $publishthis;
			
		if ( $publishthis->get_option('curated_publish') == 'import_without_cron' ) {
			if ( !is_feed() && !is_robots() && !is_trackback() ) {
				$this->simulateCron();
			}
		}
	}
}

?>
