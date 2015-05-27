<?php

class Publishthis_Utils extends Publishthis_Utils_Common {
	/**
	 * Publishthis constructor.
	 */
	function __construct() {
	}	

	/**
	 *   Draw Content Itemt
	 *
	 * @param string  $id   Content item id
	 * @param object  $item Content data
	 * @return string Layout html
	 */
	function drawContentItem( $id, $item ) {
		global $publishthis;

		ob_start();
		$html = '';

		//select and load layout template
		$GLOBALS['content_item'] = $item;
		$GLOBALS['content_item_id'] = $id;

		$publishthis->load_template( 'components/block-div-item.php' );

		unset( $GLOBALS['content_item'] );
		unset( $GLOBALS['content_item_id'] );

		$html = ob_get_clean();

		return $html;
	}

	/**
	 *  Divide Content into columns
	 *
	 * @param object  $content Content data
	 * @param string  $colCnt  Columns count
	 * @return object $content Content data
	 */
	function divideContentIntoColumns( $content, $colCnt ) {
		$items = array();
		$i = $j = $z = 0;

		foreach ( $content as $result ) {
			$items[$i++][$j] = $content[$z++];

			if ( $i > $colCnt-1 ) {
				$i=0; $j++;
			}
		}

		return $items;
	}

	/**
	 *  Returns Curated By Logo
	 */
	function getCuratedByLogo() {
		global $publishthis, $client_info;

		//get selected logo
		$logo_index = $publishthis->get_option( 'curatedby_logos' );
		$logo_index = isset( $logo_index ) ? $logo_index : 0;

		$logo_align = $publishthis->get_option( 'logo_align' );
		$logo_align = isset( $logo_align ) ? $logo_align : 3; //defaults to right				

		$client_id = $client_info && $client_info->clientId ? $client_info->clientId : 0;

		$url = 'http://www.publishthis.com/?utm_source='.trim( $_SERVER['HTTP_HOST'] ).'_'.$client_id.'&utm_medium=image&utm_campaign=WPPluginCurateByButton';

		$html = '<p id="pt_curated_by" class="pt_curated_by '.$publishthis->utils->getImageAlignmentClass($logo_align).'">'.
			'<a href="'.esc_url( $url ).'" target="_blank">'.
			'<img src="' . $this->getCuratedByLogoImage( $logo_index ) . '" alt="Curated By Logo">'.
			'</a>'.
			'</p>';

		return $html;
	}

	public function _get_style_value( $key ) {
		global $publishthis;
		return $publishthis->get_option( $key );
	}


  /**
	 *   process url request
	 * 
	 */
	public function requestURL( $url, $return_errors=false ) {
		global $publishthis;
		$cache_key = $url;
		// get cached data if exists
		$data = wp_cache_get( $cache_key );


		if ( false === $data || empty( $data ) ) {
			// process request
			$url = str_replace( " ", "%20", $url );
			$response = wp_remote_get( $url );

			// check for failure
			if ( ( !$response || is_wp_error( $response ) || 200 != $response['response']['code'] ) ) {
				if ( $return_errors ){
					return null;
				}else {
					$message = array(
						'message' => 'PublishThis URL Fetch error',
						'status' => 'error',
						'details' => 'URL: '.$url
					);
					$publishthis->log->addWithLevel( $message, "2" );
					throw new Exception( "PublishThis URL Fetch error ({$url})." );
				}
			}

			$data = $response['body'];

			//set the cache to 5 minutes.
			wp_cache_set( $cache_key, $data, '', 60*5 );

		}

		return $data;

	}


}
