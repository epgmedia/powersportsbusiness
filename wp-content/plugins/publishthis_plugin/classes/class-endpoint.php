<?php
/**
 * This handles the input and response for the Publishing Endpoints from
 * the PublishThis platform
 * Current Actions
 * 1 - Verify
 * 2 - Publish
 * 3 - GetAuthors
 * 4 - GetCategories
 */


class Publishthis_Endpoint {

	function __construct() {
		add_filter( 'query_vars', array ( $this, 'add_endpoint_trigger' ) );

		add_action( 'template_redirect', array ( $this, 'endpoint_trigger_check' ) );
	}

	function plugin_path() {
		if ( $this->_plugin_path )
			return $this->_plugin_path;
		return $this->_plugin_path = untrailingslashit( dirname( __FILE__ ) );
	}

	/**
	 * Adds Publishthis endpoint trigger
	 */
	function add_endpoint_trigger( $vars ) {
		$vars[] = 'pt_endpoint';
		$vars[] = 'ptgetinfo';
		return $vars;

	}

	/**
	 * Bind action to Publishthis endpoint
	 */
	function endpoint_trigger_check() {
		if ( intval( get_query_var( 'pt_endpoint' ) ) == 1 ) {
			//do our plugin endpoint code
			ob_start();
			$this->process_request();
			exit();
		}
	}

	/**
	 * Escape sprecial characters
	 */
	function escapeJsonString( $value ) { // list from www.json.org: (\b backspace, \f formfeed)
		$escapers = array( "\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c" );
		$replacements = array( "\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b" );
		$result = str_replace( $escapers, $replacements, $value );
		$escapers = array( '\":\"', '\",\"', '{\"', '\"}' );
		$replacements = array( '":"', '","', '{"', '"}' );
		$result = str_replace( $escapers, $replacements, $result );
		return $result;
	}

	/**
	 * Returns json response with failed status
	 */
	function sendFailure( $message ) {
		$obj = new stdClass();

		$obj->success = false;
		$obj->errorMessage = $this->escapeJsonString( $message );

		$this->sendJSON( $obj );
	}

    function sendIncompleteFailure($state, $message, $feedId=0 ) {

		$obj = new stdClass();
		$obj->success = false;
		$obj->status = "incomplete";
		$obj->nextPage = $state->page;
		$obj->importId = $state->importId;
		$obj->errorMessage = $this->escapeJsonString( $message );

		//check for the failures count, if more then 3 - reset import
		$count_name = 'pt_import_failed_cnt_i'.$state->importId.'_f'.$feedId;
		$pt_import_failed_cnt = intval( get_option( $count_name, 0 ) );
		$pt_import_failed_cnt++;
		if( $pt_import_failed_cnt >= 3 ) {
			delete_option( $count_name );
			$this->resetState('Import state for the mixId '.$feedId.' is reset after 3 insuccessful publish attempts');
		}
		else {
			update_option( $count_name, $pt_import_failed_cnt );
		}
		$this->sendJSON( $obj );	
    }

	/**
	 * Returns json response with succeess status
	 */
	function sendSuccess( $message ) {
		$obj = new stdClass();

		$obj->success = true;
		$obj->errorMessage = null;

		$this->sendJSON( $obj );
	}

    function sendRestoreState($state, $message, $mixId) {
		$obj = new stdClass();
        $obj->success = true;
        $obj->status = "incomplete";
        $obj->nextPage = $state->pageId;
        $obj->importId = $state->importId;
        $obj->errorMessage = $this->escapeJsonString($message);
        if ($mixId != null) {
            $obj->newMixId = $mixId;
        }
        $this->logPublishState('PT restore', $state);
        $this->sendJSON( $obj );
    }

	/*
	* Send object in JSON format
	*/
	private function sendJSON( $obj ) {
		global $publishthis;

		if ( intval( get_query_var( 'ptgetinfo' ) ) == 1 ) {
			phpinfo();

			$pt_categories = $pt_publishing_actions = $pt_settings = array();

			//get taxonomies categories
			$all_taxonomies = get_taxonomies( array( 'public'   => true ), $output = 'objects', $operator = 'and' );
			$taxonomies_keys = array_keys( $all_taxonomies );

			$all_terms = get_terms( $taxonomies_keys, array( 'orderby' => 'id', 'hide_empty' => 0, 'exclude' => array(1) ) );

			foreach ( $all_terms as $term ) {
				$pt_categories[] = array(
					'category' => 'Category #' . intval( $term->term_id ) . " " . $term->name . " (slug: " . $term->slug . ")",
					'taxonomy' => 'Taxonomy #' . intval( $term->term_taxonomy_id ) . " " . $term->taxonomy,
					'parent' => 'Parent: ' . intval( $term->parent )
				);
			}

			$actions = $publishthis->publish->get_publishing_actions();
			foreach( $actions as $action ) {
				$pt_publishing_actions[$action->ID] = array_merge( array( 'title' => $action->post_title ), get_post_meta( $action->ID ) );
			}

			$pt_settings = $publishthis->get_options();

			$obj->debugInfo = array(
				'general' => $publishthis->log->getMessages(),
				'pt_version' => $publishthis->version,
				'pt_categories' => $pt_categories,
				'pt_publishing_actions' => $pt_publishing_actions,
				'pt_settings' => $pt_settings
			);
		}

		$response = json_encode( $obj );

		$publishthis->log->addWithLevel( array( 'message' => 'Endpoint Response', 'status' => 'info', 'details' => $response ), "2" );

		/* we have to set the header to json. On some clients, they may have theme code that has all
		  ready submitted the headers. if they did, then we can not use the endpoint, they would need
		  to see an error occur and and work with our cs team to get things fixed.
		  outputting in our debug area where the culprit is will be helpful though
		 */
		if (!headers_sent($ptfilename, $ptlinenum)) {
		    header( 'Content-Type: application/json' );
		} else {
		
		    $message = array(
					'message' => 'Headers all ready sent.',
					'status' => 'error',
					'details' => 'Headers are all ready sent in file:' . $ptfilename . ' at line num:' .$ptlinenum . '.' );
				$publishthis->log->addWithLevel( $message, "1" );
		}

		echo $response;
		exit();
		
		
	}

	/**
	 * Verify endpoint action
	 */
	private function actionVerify() {
		global $publishthis;

		//first check to make sure we have our api token
		$apiToken = $publishthis->get_option ( 'api_token' );

		if ( empty( $apiToken ) ) {

			$message = array(
				'message' => 'Verify Plugin Endpoint',
				'status' => 'error',
				'details' => 'Asked to verify our install at: '. date( "Y-m-d H:i:s" ) . ' failed because api token is not filled out' );
			$publishthis->log->addWithLevel( $message, "1" );

			$this->sendFailure( "No API Key Entered" );
			return;
		}

		//then, make a easy call to our api that should return our basic info.
		$apiResponse = $publishthis->api->get_client_info();

		if ( empty( $apiResponse ) ) {
			$message = array(
				'message' => 'Verify Plugin Endpoint',
				'status' => 'error',
				'details' => 'Asked to verify our install at: '. date( "Y-m-d H:i:s" ) . ' failed because api token is not valid' );
			$publishthis->log->addWithLevel( $message, "1" );

			$this->sendFailure( "API Key Entered is not Valid" );
			return;
		}

		//if we got here, then it is a valid api token, and the plugin is installed.

		$message = array(
			'message' => 'Verify Plugin Endpoint',
			'status' => 'info',
			'details' => 'Asked to verify our install at: '. date( "Y-m-d H:i:s" ) );
		$publishthis->log->addWithLevel( $message, "2" );


		$this->sendSuccess( "" );
	}

	    /**
     * Publish endpoint action
     * we get the information and then publish the feed
     * here is the info being passed right now
     * action: "publish",
     * feedId: 123,
     * templateId: 456,
     * clientId: 789,
     * userId: 21,
     * publishDate: Date
     *
     * @param integer $feedId
     */

    private function actionPublish2( $feedId, $pageNum=0, $importId = '' ) {
    	global $publishthis;

    	$state = get_option( 'pt_import_state' );
    	//state is undefined
    	if( $state === false) {
    		$state = new stdClass();
	        $state->importId = null;
	        $state->mixId = null;
	        $state->pageId = null;      // indicate next page to publish
	        $state->state = 'standby'; // standby:inprogress standby by default
	        $state->json = null;
	        //save state
        	update_option( 'pt_import_state', $state );
    	}
        $startPublish = $pageNum==0;
        $invalidMix = $state->mixId != $feedId;
        $invalidPage = $state->pageId != $pageNum;
        $invalidImportId = $state->importId != $importId;

        if ($state->state == "standby") { // STANDBY
            if ($startPublish) { // Publish first page. Success story. Migrate to inprogress state
                $state->importId = uniqid("pt");
                $state->mixId = $feedId;
                $state->pageId = 0;
                $state->state = "inprogress";
                $this->logPublishState('== PT-Publish starting new import', $state);
            } else { // ignore all other requests
                $obj = new stdClass();
                $obj->success = false;
                $obj->status = "complete";
                $obj->errorMessage = "Not in publish state";
                $this->sendJSON( $obj );
                return;
            }
        } else { // INPROGRESS
            if ($invalidMix) {
                $this->sendRestoreState($state, "Invalid MixID. Restart from last state", $state->mixId);
                return;
            }
            if ($invalidImportId) {
                $this->sendRestoreState($state, "Invalid importId. Restart from last state", null);
                //this method calls an exit, so, throws an error is trying to then do a return
                //return;
            }
            if ($invalidPage) {
                $this->sendRestoreState($state, "Invalid page. Restart from last state", null);
                //this method calls an exit, so, throws an error is trying to then do a return
                //return;
            }
        }

        try {
            //publish page here
            update_option( 'pt_import_state', $state );

            $publishResult = $publishthis->publish->publish_specific_feeds( array($feedId), $pageNum, $state->importId );

            //get state from db with saved json
            $state = get_option( 'pt_import_state' );

	    if( $publishResult['error'] ) {
		throw new Exception( $publishResult['errorMessage']);
	    }
        }catch(Exception $ex){
            $this->logPublishState('== PT-Publish failed. Request retry. ' . $ex->getMessage(), $state);
            $this->sendIncompleteFailure($state, "Publish failed ". $ex->getMessage(), $state->mixId);
            return;
        }
        $mixPublished = $publishResult['complete']; // Indicate that mix published completely. All pages processed
        $itemsProcessed = $publishResult['itemsPublished'];
        if ($mixPublished) {
            $state->state = "standby"; // migrate to STANDBY mode
            $state->importId = null;
            $state->mixId = null;
            $state->pageId = null;
            $state->json = null;

            $res = new stdClass();
            $res->success = true;
            $res->status = 'complete';
            $res->itemsProcessed = $itemsProcessed;
            $res->importId = $state->importId;
            $this->logPublishState('== PT-Publish finished', $state);
            update_option( 'pt_import_state', $state );
		
		//reset failures counter for current feed
		delete_option( 'pt_import_failed_cnt_i'.$state->importId.'_f'.$feedId );

            $this->sendJSON( $res );
        } else {
            $state->pageId = ($state->pageId + 1);  // Increment page
            $res = new stdClass();
            $res->success = true;
            $res->status = 'incomplete';
            $res->itemsProcessed = $itemsProcessed;
            $res->importId = $state->importId;
            $res->nextPage = $state->pageId;
            $this->logPublishState('== PT-Publish incomplete', $state);
            update_option( 'pt_import_state', $state );
            $this->sendJSON( $res );
        }
    }

    private function logPublishState($message, $state)
    {
        global $publishthis;
        $report = new stdClass();
        $report->importId = $state->importId;
        $report->mixId = $state->mixId;
        $report->pageId = $state->pageId;
        $report->state = $state->state;
        $publishthis->log->addWithLevel(array('message' => $message, 'status' => 'info', 'details' => json_encode($report)), "2");
    }

    private function updateStateAndSend( $response, $state ) {
    	//save new state
    	update_option( 'pt_import_state', $state );

        $this->sendJSON($response);
    }

    private function resetState($customMessage='Manual Reset') {
	global $publishthis;

    	//delete the state
    	delete_option( 'pt_import_state' );

	$publishthis->log->addWithLevel( array( 'message' => 'Import State reset', 'status' => 'info', 'details' => $customMessage ), "2" );

        $this->sendSuccess( "Import State reset completed" );
    }

    private function stopEndpoint() {
	global $publishthis;

    	//delete the state
    	update_option( 'pt_import_manually_stopped', 1 );

	$publishthis->log->addWithLevel( array( 'message' => 'Endpoint Stopped', 'status' => 'info', 'details' => '' ), "2" );

        $this->sendSuccess( "Endpoint Stopped" );
    }

    private function resumeEndpoint() {
	global $publishthis;

    	//delete the state
    	update_option( 'pt_import_manually_stopped', 0 );

	$publishthis->log->addWithLevel( array( 'message' => 'Endpoint Resumed', 'status' => 'info', 'details' => '' ), "2" );

        $this->sendSuccess( "Endpoint Resumed" );
    }

    /**
	 * Returns wordpress authors list
	 */
	private function actionGetAuthors() {
		$authors = array();
		$obj = new stdClass();

		$users = get_users( 'exclude=[1]&who=authors' );

		foreach ( $users as $user ) {
			$authors[] = array( 'id' => $user->ID, 'name' => $user->display_name );
		}

		$obj->success = true;
		$obj->errorMessage = null;
		$obj->authors = $authors;

		$this->sendJSON( $obj );
	}

	/**
	 * Returns wordpress categories and taxonomies list
	 */
	private function actionGetCategories() {
		global $publishthis;

		$categories = array();
		$obj = new stdClass();

		//get taxonomies categories
		$all_taxonomies = get_taxonomies( array( 'public'   => true ), $output = 'objects', $operator = 'and' );
		$taxonomies_keys = array_keys( $all_taxonomies );
		$all_terms = get_terms( $taxonomies_keys, array( 'orderby' => 'id', 'hide_empty' => 0, 'exclude' => array(1) ) );

		$tax_maps = $publishthis->get_option( 'tax_mapping' );
		$tax_maps = array_values($tax_maps);

		foreach ( $all_terms as $term ) {
			if ( !in_array( $term->taxonomy, array( 'post_format', 'post_tag' ) ) ) {
			if ( !in_array( $term->taxonomy, $tax_maps ) && !in_array( 'pt_tax_all', $tax_maps ) ) continue;

			$category = array(
				'id' => intval( $term->term_id ),
				'name' => $term->name,
				'taxonomyId' => intval( $term->term_taxonomy_id ),
				'taxonomyName' => $term->taxonomy,
				'subcategories' => array() );
			if( intval( $term->parent ) > 0 ) {
					$categories[ $term->parent ]['subcategories'][ $term->term_id ] = $category;
			}
			else {
					$categories[ $term->term_id ] = $category;
				}
			}
		}

		$obj->success = true;
		$obj->errorMessage = null;
		$obj->categories = $this->array_values_recursive( $categories );

		$this->sendJSON( $obj );
	}

	private function array_values_recursive($arr) {
		$arr = array_values($arr);
		foreach($arr as $key => $val) {
			if(array_values($val['subcategories']) !== $val['subcategories']) {
				$arr[$key]['subcategories'] = $this->array_values_recursive($val['subcategories']);
			}
		}

		return $arr;
	}

	/**
	 * Process request main function
	 */
	function process_request() {
		global $publishthis;

		try{

			$bodyContent = '';

			if ( function_exists( 'wpcom_vip_file_get_contents' ) ) {
				$bodyContent = wpcom_vip_file_get_contents( 'php://input', 10, 60 );
			} else {
				$bodyContent = file_get_contents( 'php://input' );
			}

			$publishthis->log->addWithLevel( array( 'message' => 'Endpoint Request', 'status' => 'info', 'details' => $bodyContent ), "2" );

			$arrEndPoint = json_decode( $bodyContent, true );

			$action = $arrEndPoint["action"];

			$pt_settings = $publishthis->get_options();

			if( !in_array( $action, array('resetState', 'stopEndpoint', 'resumeEndpoint') ) ) {
				$manually_stopped = get_option( 'pt_import_manually_stopped' );
				if ( $manually_stopped == 1 ) {
					$this->sendFailure('Import manually stopped');
					return;
				}
			}

			switch( $action ) {
				case "verify":
					$this->actionVerify();
					break;

				case "publish":
					if( $publishthis->get_option( 'curated_publish' ) != 'import_from_manager' ) {
						$this->sendFailure( "Publishing through CMS is disabled" );
						return;
					}
					$feedId = intval( $arrEndPoint["feedId"], 10 );
					$pageNum = intval( $arrEndPoint["pageNum"], 10 );
					$importId = $arrEndPoint["importId"];

					$this->actionPublish2( $feedId, $pageNum, $importId );
					break;

				case "getAuthors":
					$this->actionGetAuthors();
					break;

				case "getCategories":
					$this->actionGetCategories();
					break;

				case "resetState":
					$this->resetState();
					break;

				case "stopEndpoint":
					$this->stopEndpoint();
					break;

				case "resumeEndpoint":
					$this->resumeEndpoint();
					break;

				default:
					$this->sendFailure( "Empty or bad request made to endpoint" );
					break;
			}
		} catch( Exception $ex ) {
			//we will log this to the pt logger, but we always need to send back a failure if this occurs

			$this->sendFailure( $ex->getMessage() );
		}

		return;
	}
}

?>
