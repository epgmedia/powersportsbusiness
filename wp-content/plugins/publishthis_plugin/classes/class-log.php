<?php
class Publishthis_Log {

	/**
	 *   Add a log entry
	 *
	 * @param string or array $message Log Message
	 * @param unknown $level   Used backward compatibility
	 */
	function addWithLevel( $message, $level ) {
		global $publishthis;

		//check message format
		if ( !is_array( $message ) ) $message = array( 'message' => $message, 'status' => 'info' );
		$localMessages = get_option( "pt_local_messages" );

		if ( ! is_array( $localMessages ) ) {
			$localMessages = array ();
		}

		if ( count( $localMessages ) > 100 ) {
			// keep it at a limited size
			array_pop( $localMessages );
		}

    //if we are on debug level, we log everything to the messages
		if ( $publishthis->debug() ) {
			$message = array_merge( (array)$message, array( 'time' => current_time( 'mysql' ) ) );
			// we only log messages to local messages if we are debugging
			array_unshift( $localMessages, $message );
			update_option( "pt_local_messages", $localMessages);
			return;
		}

    //if we are set to error messages, we only log level 2 messages, which are errors
    
		if ( ($publishthis->error()) && ($level == "1")) {
			$message = array_merge( (array)$message, array( 'time' => current_time( 'mysql' ) ) );
			// we only log messages to local messages if we are debugging
			array_unshift( $localMessages, $message );
			update_option( "pt_local_messages", $localMessages);
			return;
		}

	}

  function getMessages(){
     return get_option("pt_local_messages");	
  }

	/**
	 *   Add a log entry.
	 */
	function add( $message ) {
		$this->addWithLevel( $message, "1" );
	}
}



	
