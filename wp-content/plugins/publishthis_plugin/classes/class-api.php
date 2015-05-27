<?php
class Publishthis_API extends Publishthis_API_Common {
	private $_api_token;

	/**
	 * Publishthis_API constructor
	 */
	function __construct() {
		global $publishthis;

		// Set api url
		switch ( $publishthis->get_option ( 'api_version' ) ) {
		case '3.0' :
		default :
			$this->_api_url = PT_API_URL_3_0;
			break;
		}

		$this->_api_token = $publishthis->get_option( 'api_token' );
	}

	public function _get_token() {
		return $this->_api_token;
	}

	public function set_token($strAPIToken){
		
		$this->_api_token = $strAPIToken;	
	}

	public function _log_message( $message, $level='' ) {
		global $publishthis;
		if( !empty( $level ) ) {
			$publishthis->log->addWithLevel( $message, $level );
		}
		else {
			$publishthis->log->add( $message );
		}
	}

	/**
	 * Returns Publishthis client info
	 */
	public function get_client_info( $params = array() ) {	
		$params = $params + array ( 'token' => $this->_get_token() );

		$url = $this->_compose_api_call_url( '/client', $params );

		try {
			$response = $this->_request ( $url );
			//save client info 
			set_transient( 'pt_client_info', $response, 2*60 );
		} catch ( Exception $ex ) {
			$this->_log_message( $ex->getMessage () );
			$response = null;
		}
		
		return $response;
	}	
	
	/**
	 *   process API request
	 * we call our API method, then return the correct JSON object or thrown an exception
	 * if the API had an error, or there was an error in parsing, or there was an error in
	 * the fetch call itself.
	 */
	public function _request( $url, $return_errors=false, $cache_data=true, $is_post=false, $post_args=array() ) {
		global $publishthis;
		$cache_key = $url;
		// get cached data if exists
		$data = wp_cache_get( $cache_key );


		if ( false === $data || empty( $data ) || !$cache_data ) {
			// process request
			$url = str_replace( " ", "%20", $url );

			if($is_post) {
				$post_url = substr($url, 0, strpos($url, '?'));
				$response = wp_remote_post( $post_url,  array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'headers' => array(),
					'body' => $post_args,
					'cookies' => array()
				) );
			}
			else {
				$response = wp_remote_get( $url, $args );
			}

			// check for failure
			if ( ( !$response || is_wp_error( $response ) || 200 != $response['response']['code'] ) ) {
				
				$strExtraMessage = "";
				if (!$response){
					$strExtraMessage = "No valid response info";
				}else if (is_wp_error($response)){
					$strExtraMessage = $response->get_error_message();
				}else{
					$strExtraMessage = "response code was:" . $response['response']['code'];
				}
				
				$message = array(
						'message' => 'PublishThis API error',
						'status' => 'error',
						'details' => 'URL: '.$url . ' had error:' . $strExtraMessage
					);
					$this->_log_message( $message, "2" );
				if ( $return_errors ){
					return null;
				}else {
					
					throw new Exception( "PublishThis API error ({$url}), with error:" . $strExtraMessage );
				}
			}

			$data = $response['body'];

			//set the cache to 50 seconds.
			//our cron events can run every 1 minute, and our widgets lowest cache that they
			//have are 1 minute. So, we don't want to mess with any of those higher cache level
			//limits and return no results when there should be.
			wp_cache_set( $cache_key, $data, '', 50 );

		}

		$json = "";

		try {
			$json = json_decode( $data );

			if ( ! $json ) {
			
			  $jsonErrorStr = "";
			  if (function_exists('json_last_error_msg')){
			  	$jsonErrorStr = json_last_error_msg();	
			  }
			
				throw new Exception( "inner JSON conversion error ({$url}). json decode msg:" . $jsonErrorStr );
			}

		} catch ( Exception $ex ) {
			// try utf encoding it and then capturing it again.
			// we have seen problems in some wordpress/server installs where the json_decode
			//doesn't actually like the utf-8 response that is returned
			$message = array(
				'message' => 'Issue in decoding the json, data:' . $data,
				'status' => 'error',
				'details' => $ex->getMessage ()
			);
			$this->_log_message( $message, "2" );

			try {
				$tmpBody = utf8_encode( $data );
				$json = json_decode( $tmpBody );
			} catch ( Exception $exc ) {
			
			    $jsonErrorStr = "";
			  	if (function_exists('json_last_error_msg')){
			  		$jsonErrorStr = json_last_error_msg();	
			  	}
			
				$message = array(
					'message' => 'Issue in utf8 encoding and then decoding the json, data:' . $data . ', json error:' . $jsonErrorStr,
					'status' => 'error',
					'details' => $ex->getMessage ()
				);
				$this->_log_message( $message, "2" );

				

				throw new Exception( "Your wordpress install is not correctly decoding our API response, please contact your client service representative" );
			}
		}

		if ( ! $json ) {
			throw new Exception( "JSON conversion error ({$url})." );
		}

		if ( 200 != $json->resp->code ) {
			$message = !empty( $response->resp->errorMessage ) ? $response->resp->errorMessage : "PublishThis API error ({$url})";
			$errorMessage = array(
				'message' => 'PublishThis API error',
				'status' => 'error',
				'details' => 'Invalid response code<br/>URL: '.$url . ', msg:' .$message
			);
			$this->_log_message( $errorMessage, "2" );
			throw new Exception ( $message );
		}

		return $json->resp->data;
	}

}
