<?php
class Publishthis_Cron {

	private $_hook = 'import_publishthis_content';
	private $_publish = null;

	function __construct() {
		// Actions
		add_action( $this->_hook, array ( 'Publishthis_Publish', 'run_import' ), 10, 2 );

		// Filters
		add_filter( 'cron_schedules', array ( $this, 'cron_schedules' ) );
	}

	/**
	 *   Define custom cron schedules
	 *
	 * @param unknown $schedules Old cron schedules
	 * @return array $schedules
	 */
	function cron_schedules( $schedules ) {
		$schedules = array(
			'every_50' => array(
				'interval' => 50,
				'display' => __ ( 'Every 50 seconds', 'publishthis' ) )
		);

		return $schedules;
	}

	/**
	 * Add cron event on activation
	 */
	function pt_add() {
		$this->_clean_cron_array();
		wp_schedule_event( time(), 'every_50', $this->_hook, array() );
	}

	/**
	 * Remove cron event on deactivation
	 */
	function pt_remove() {
		$this->_clean_cron_array();
	}

	/**
	 * Clean cron array
	 */
	private function _clean_cron_array() {
		//retrive all crons
		$crons = _get_cron_array ();
		if ( ! is_array( $crons ) ) {
			return;
		}

		$local_time = microtime( true );
		$doing_wp_cron = sprintf( '%.22F', $local_time );
		set_transient ( 'doing_cron', $doing_wp_cron );

		foreach ( $crons as $timestamp => $cronhooks ) {
			foreach ( $cronhooks as $hook => $keys ) {
				if ( $hook == $this->_hook ) {
					unset ( $crons [$timestamp] [$hook] );
				}
			}

			if ( empty ( $crons [$timestamp] ) ) {
				unset ( $crons [$timestamp] );
			}
		}

		//update cron with new array
		_set_cron_array ( $crons );
		delete_transient ( 'doing_cron' );
	}

}
