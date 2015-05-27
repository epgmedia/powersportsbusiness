<?php

/**
 * Add Google Analitics tracking and Curated By logo
 */
class Publishthis_Tracking {

	function pt_init() {
		global $publishthis;
		
		//$objTracking = new Publishthis_Tracking();

		add_action( 'wp_head', array( $this, 'pt_analytics' ), 1001 );

		if( $publishthis->get_option( 'curatedby' ) == "2") {
			add_action( 'the_content', array( $this, 'pt_post_page' ), 1001 );
		}
		else {
			//try to display in footer
			add_action( 'wp_footer', array( $this, 'pt_footer' ), 1001 );
		}
		
		
		add_action('wp_head', array($this, 'pt_add_per_page_analytics'),1002);
		
	}

	/**
	 * Returns PublishThis Analytics tracking code
	 *
	 * 
	 */
	function pt_analytics() {
		
		
		if ( ! is_admin() && ! is_feed() && ! is_robots() && ! is_trackback() ) {
			global $publishthis, $client_info, $post;
			
		if( $publishthis->get_option( 'include_analytics' ) == "1") {
		//get client id
		$client_id = $client_info && $client_info->clientId ? $client_info->clientId : 0;

			$strTrackKey = "pttrack-" . $client_id;
			
			//first, get from our transients api
			$trackCode = get_transient( $strTrackKey );
			
			
			if (empty($trackCode)){
				//next check in our options
				$trackCode = get_option($strTrackKey);
				
				
				if (empty($trackCode)){
					//we need to fetch this and then store the results
					try{
						$trackCode = $publishthis->utils->requestURL(PT_ANALYTICS_URL . $client_id);
					} catch ( Exception $ex ) {
						$message = array(
							'message' => 'PT Content Analysis',
							'status' => 'error',
							'details' => 'Unable to get analysis code ' . $ex->getMessage() );
							$publishthis->log->addWithLevel( $message, "1" );
						$trackCode = null;
					}
					
					if (!empty($trackCode)){
						update_option($strTrackKey, $trackCode );
						set_transient($strTrackKey,$trackCode,60 * 60 * 24 * 30); //cache it for 30 days	
					}
					
				}else{  //end of check for option in the db
						set_transient($strTrackKey,$trackCode,60 * 60 * 24 * 30); //cache it for 30 days
				}
			}else{
				//found it in the transient cache, yey!
			}
				
			if (!empty($trackCode)){
				//for safety purposes, just ensure that it is our valid script file
				if (strrpos ($trackCode , "script" ) > 0){
					if (strrpos($trackCode, "analytics") > 0){
						echo $trackCode;		
					}else{
						//need to reset our track code
						delete_transient($strTrackKey);
						delete_option($strTrackKey);	
					}
				}else{
					//need to reset our track code
					delete_transient($strTrackKey);
					delete_option($strTrackKey);	
				}
			}	
			
				
		}
		}
	}

	/**
	 *   Display logo in the post/page
	 */
	function pt_post_page( $content ) {
		if ( ! is_admin() && ! is_feed() && ! is_robots() && ! is_trackback() ) {
			global $publishthis, $post;

			$logo = '';

			//check that post/page related to publishthis content
			if( get_post_meta( $post->ID, '_publishthis_docid', true ) ) {
				$logo .= $publishthis->utils->getCuratedByLogo();
			}
			
			return $content . $logo;
		}
		else {
			return $content;
		}
	}

	/**
	 *   Display blog footer. Show Publishthis logo if needed
	 */
	function pt_footer() {
		if ( ! is_admin() && ! is_feed() && ! is_robots() && ! is_trackback() ) {
			global $publishthis, $client_info;

			try {
				$strText = "";		
			
				//check that user can hide logo
				$default_placement = '1'; //display in 'Footer' option
				$placement = strlen( $publishthis->get_option( 'curatedby' ) ) > 0 ? $publishthis->get_option( 'curatedby' ) : $default_placement;
				$placement = $placement==0 && !$client_info->allowDisableLogo ? $default_placement : $placement;

				if( $placement == 1 ) {
					$strText .= $publishthis->utils->getCuratedByLogo();
				}

				echo $strText, "\n";


			} catch ( Exception $ex ) {

			}	
		}	
	}
	
	
	function pt_add_per_page_analytics(){
		global $post;
		try{
			if ( ! is_admin() && ! is_feed() && ! is_robots() && ! is_trackback() ) {
				global $publishthis;
				
				if (is_single() || is_page()){
					$strPublishThisPostType = get_post_meta( $post->ID, "_publishthis_posttype", true );
				}else{
					$strPublishThisPostType = "";
				}
				
				if (!empty($strPublishThisPostType)){
					
					if ("combined" == $strPublishThisPostType){
						$strPageTrackCode = $this->getPageJSCode(true,'cur_combined');
						echo $strPageTrackCode;	
					}else if ("individual" == $strPublishThisPostType){
						$strPageTrackCode = $this->getPageJSCode(true,'cur_individual');
						echo $strPageTrackCode;
					}
					
				}
				
		    
		    
			}
		}catch(Exception $ex){}
			
		
	}
	
	
	/** 
	* Add in specific page information to help analytics know if this is a PT generated page
	* and what type of page it is
	
	 valid ptPostTypes are
	   cur_combined
	   cur_individual
	*/
	
	function getPageJSCode($isPTContentGenerate, $ptPostType){
		
		
		$strScript = "";
		
		
		
		$strScript .= "<script type=\"text/javascript\">\n";
		$strScript .= "	  \n";
		$strScript .= "	  \n";
		$strScript .= "	  try{\n";
		$strScript .= "	    var ptPageEventIntervalId = -1;\n";
		$strScript .= "	    var ptPageEventCalls = 0;\n";
		$strScript .= "	    function pt_CallPageEvent(){\n";
		$strScript .= "	    	\n";
		$strScript .= "	    	if (!ptTracker){ \n";
		$strScript .= "	    	   if (ptPageEventCalls>50){ \n";
		$strScript .= "	    	    	window.clearInterval(ptPageEventIntervalId);\n"; 
		$strScript .= "	    	    	return;\n";
		$strScript .= "	    	   }\n"; 
		$strScript .= "	    	   ptPageEventCalls++;\n";	 		
		$strScript .= "	    	   return;\n";
		$strScript .= "	    	};\n";
		$strScript .= "	    	ptTracker(\"ptInfoHook\",\n";
		$strScript .= "	    	  {\n";
		$strScript .= "	    	  	\"hasPtContent\" : " . $isPTContentGenerate . ",\n";
		$strScript .= "	    	  	\"postType\" : \"" . $ptPostType . "\"	\n";
		$strScript .= "	    	  }\n";
		$strScript .= "	    	);\n";
		$strScript .= "	      window.clearInterval(ptPageEventIntervalId);\n";
		$strScript .= "	    }\n";
		$strScript .= "	    ptPageEventIntervalId = window.setInterval(\"pt_CallPageEvent\", 100);\n";
		$strScript .= "			\n";
		$strScript .= "		}catch(e){}	\n";
		$strScript .= "		</script>	\n";
		$strScript .= "";
		
		return $strScript;
	}
	
}

$tracking = new Publishthis_Tracking();
$tracking->pt_init();

?>
