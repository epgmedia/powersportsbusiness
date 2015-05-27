<?php
class Publishthis_Publish {
	private $_author_mappings;
	private $_cat_mappings;
	private $_individuals_log = array();
	private $_advanced_content_info;

	function __construct() {
		global $publishthis;

		add_filter( 'get_the_excerpt', array ( $this, 'remove_pt_excerpt_items' ), 1 );
		add_filter( 'the_content', array ( $this, 'remove_pt_excerpt_items' ), 1 );

		$this->_author_mappings = $publishthis->get_option( 'author_mappings' );
		$this->_cat_mappings = $publishthis->get_option( 'cat_mappings' );
	}

	/**
	 * returns all publishing actions that are published
	 */
	function get_publishing_actions() {
		global $publishthis;


		$actions = get_posts ( array ( 'numberposts' => 100, 'post_type' => $publishthis->post_type, 'post_status' => 'publish' ) );

		return $actions;
	}

	/**
	 * returns all publishing actions that are published that are specific to a Feed Template
	 *
	 * @param template_id - This is the Feed Template ID to find Publishing Actions for
	 */
	function get_publishing_actions_by_template_id( $template_id ) {
		global $publishthis;

		$actions = get_posts ( array ( 'numberposts' => 100, 'post_type' => $publishthis->post_type, 'post_status' => 'publish', 'meta_query' => array(
					array(
						'key' => 'template_id',
						'value' => $template_id,
					)
				) ) );

		return $actions;
	}

	/**
	 * Returns publishing actions defaults for old plugin installations or specified value
	 */
	function check_value( $value, $default='' ) {
		return !isset( $value ) ? $default : $value;
	}

	/**
	 *   Get the Meta data for a Publishing Action
	 *
	 * @param unknown $action_id Publishing Action
	 * sets up our meta array for this Publishing Action for use by other functions
	 */

	function get_action_meta( $action_id ) {

		// Get meta
		$meta = get_post_meta ( $action_id );

		// Init $action meta
		$styles = array(
			'image' => json_decode( $meta['_publishthis_layout_image_custom_styles'][0] ),
			'title' => json_decode( $meta['_publishthis_layout_title_custom_styles'][0] ),
			'summary' => json_decode( $meta['_publishthis_layout_summary_custom_styles'][0] ),
			'publishdate' => json_decode( $meta['_publishthis_layout_publishdate_custom_styles'][0] ),
			'readmore' => json_decode( $meta['_publishthis_layout_readmore_custom_styles'][0] ),
			'annotation' => json_decode( $meta['_publishthis_layout_annotation_custom_styles'][0] ),
			'embed' => json_decode( $meta['_publishthis_layout_embed_custom_styles'][0] )
		);
		$include_styles = array();
		if ( $this->check_value( $meta['_publishthis_layout_image'][0], "1" ) == "1" ) $include_styles[] = 'image';
		if ( $this->check_value( $meta['_publishthis_layout_title'][0], "1" ) == "1" ) $include_styles[] = 'title';
		if ( $this->check_value( $meta['_publishthis_layout_summary'][0], "1" ) == "1" ) $include_styles[] = 'summary';
		if ( $this->check_value( $meta['_publishthis_layout_publishdate'][0], "0" ) == "1" ) $include_styles[] = 'publishdate';
		if ( $this->check_value( $meta['_publishthis_layout_readmore'][0], "1" ) == "1" ) $include_styles[] = 'readmore';
		if ( $this->check_value( $meta['_publishthis_layout_annotation'][0], "1" ) == "1" ) $include_styles[] = 'annotation';
		if ( $this->check_value( $meta['_publishthis_layout_embed'][0], "1" ) == "1" ) $include_styles[] = 'embed';

		$action_meta = array(
			"section_id" => $this->check_value( $meta['_publishthis_template_section'][0], '0' ),
			"template_id" => $this->check_value( $meta['_publishthis_feed_template'][0], '0' ),
			"category" => $this->check_value( $meta['_publishthis_category'][0], '0' ),
			"tags" => $this->check_value( $meta['_publishthis_tags'][0], '0' ),
			"post_status" => $this->check_value( $meta['_publishthis_content_status'][0], 'draft' ),
			"post_type" => $this->check_value( $meta['_publishthis_content_type'][0], 'post' ),
			"format" => $this->check_value( $meta['_publishthis_content_type_format'][0], 'individual' ),
			"digest_template" => $this->check_value( $meta['_publishthis_combined_layout'][0], 'defaultdigest' ),
			"synchronize" => $this->check_value( $meta['_publishthis_synchronize'][0], '0' ),
			"individual_insert" => $this->check_value( $meta['_publishthis_individual_insert'][0], '1' ),
			"individual_delete" => $this->check_value( $meta['_publishthis_individual_delete'][0], '0' ),
			"individual_update" => $this->check_value( $meta['_publishthis_individual_update'][0], '0' ),
			"featured_image" => $this->check_value( $meta['_publishthis_featured_image'][0], '0' ),
			"create_excerpt" => $this->check_value( $meta['_publishthis_create_excerpt'][0], '0' ),
			"manual_excerpt" => $this->check_value( $meta['_publishthis_manual_excerpt'][0], '0' ),
			"featured_max_image_width" => $this->check_value( $meta['_publishthis_featured_max_image_width'][0], '300' ),
			"featured_image_width" => $this->check_value( $meta['_publishthis_featured_image_width'][0], '0' ),
			"featured_image_height" => $this->check_value( $meta['_publishthis_featured_image_height'][0], '0' ),
			"ok_override_fimage_size" => $this->check_value( $meta['_publishthis_ok_override_fimage_size'][0], '0' ),
			"up_to_max_width" => $this->check_value( $meta['_publishthis_up_to_max_width'][0], '300' ),
			"html_body_image" => $this->check_value( $meta['_publishthis_html_body_image'][0], '1' ),
			"featured_image_size" => $this->check_value( $meta['_publishthis_featured_image_size'][0], 'theme_default' ),
			"publish_author" => $this->check_value( $meta['_publishthis_publish_author'][0], '' ),
			"read_more" => $this->check_value( $meta['_publishthis_read_more'][0], 'Read More' ),
			"image_alignment" => $this->check_value( $meta['_publishthis_image_alignment'][0], '0' ),
			"annotation_placement" => $this->check_value( $meta['_publishthis_annotation_placement'][0], '0' ),
			"taxonomy" => $this->check_value( $meta['_publishthis_taxonomy'][0], 'category' ),
			"page_template" => $this->check_value( $meta['_publishthis_content_type_wp_template'][0], '' ),
			"excerpt_first_item" => $this->check_value( $meta['_publishthis_excerpt_first_item'][0], '0' ),
			"excerpt_more_tag" => $this->check_value( $meta['_publishthis_excerpt_more_tag'][0], '0' ),
			"include_styles" => $include_styles,
			"styles" => $styles );


		return $action_meta;

	}

	/**
	 * Publishes the single feed with a Publishing Actions meta information
	 *
	 * @param int     $feed_id     Publishthis Feed id
	 * @param array   $feed_meta   Publishthis Feed data (display name, etc.)
	 * @param int     $action_id   Publishing Action id
	 * @param array   $action_meta Publishing Action data
	 */
	function publish_feed_with_publishing_action( $feed, $action_meta, $currentPageNum=-1, $importId='' ) {
		global $publishthis;

		$nextPageNum = -1;

		try{

			$posts_updated = $posts_inserted = $posts_deleted = $posts_skipped = 0;

			$feed_id = $feed['feedId'];
						
			$feed_meta = array( "displayName" => $feed['displayName'] );

			$post_type = $action_meta['post_type'];

			$import_state = get_option( 'pt_import_state' );
			if( ($action_meta['format'] == 'individual' ) && ( $currentPageNum >= 0 )) {
				$curated_content = $import_state->json;
				$curated_content = $this->_advanced_content_info = json_decode($curated_content);

				if ( !is_object($curated_content) && !is_array($curated_content) || $curated_content === false ) {
					if( $import_state->state == 'inprogress' ) {
						$errorMessage = ( $curated_content === false ) 
										? 'Failed to download JSON content for import ID #' . $importId . 'not found (Page #' . $currentPageNum . ')'
										: 'Data were corrupted for import ID #' . $importId . 'not found (Page #' . $currentPageNum . ')';						

						$complete = false;
					}
					else {
						$errorMessage = 'Not in publish state. Ignore';
						$complete = true;
					}					
					
					return array( 
						'error' => true, 
						'errorMessage' => $errorMessage, 
						'complete' => $complete );
				}
				
			} else {
				$curated_content = $this->_advanced_content_info = $publishthis->api->get_section_content ( $feed_id, $action_meta['section_id'] );
				if ( empty ( $curated_content ) ) {
                    return;
				}

				if( $action_meta['format'] != 'combined' ) {
					// make sure to reverse the array, as the order in the publish
					// this template sections have a defined order. so, the first one in the template
					// section should be marked as most recently published
					$curated_content = array_reverse( $curated_content );
					
					/*ok, now we have different ways to publish. we can only do paged publishing if we have an
					  importid, so, check it. if we have one, then we need to set our paging number to 0 if it
					  isn't set so we can starting paging at the beginning
					  and also store our json for future paged events if any.
					 */
					 
					 if (!empty($importId)){
					 	//store the json for future paging runs					 	
						//$import_state->json = json_encode($curated_content);
						//update_option( 'pt_import_state', $import_state );
	
						//set the page number if it hasn't been set all ready
						if ($currentPageNum < 0){
							$currentPageNum = 0; //start the paging process!	
						}
					 }
					
				}
			}
			
			// Unique set name
			$set_name = '_publishthis_set_' . $action_meta['template_id'] . '_' . $action_meta['section_id'] . '_' . $feed_id;

			$arrPostCategoryNames = array();

			$result_list = $publishthis->api->get_custom_data_by_feed_id ( $feed_id, array () );
			$custom_data = $managerCategories = $tags = array();
			$action_meta['ptauthors'] = -1;
			foreach ( $result_list as $result ) {
				$custom_data[$result->shortCode] = $result->value;

				if ( $this->_cat_mappings != '1' && !in_array( $result->shortCode, array( 'ptauthors', 'ptcategories', 'pttags' ) ) ) {
					$managerCategories[] = $result->value;
				}
			}

			if ( $this->_author_mappings == '1' ) {
				if ( isset( $custom_data['ptauthors'] ) ) {
					$action_meta['ptauthors'] = $custom_data['ptauthors'];
				}
			}

			if ( $this->_cat_mappings == '1' ) {
				if( isset( $custom_data['ptcategories'] ) ) {
				foreach ( $custom_data['ptcategories'] as $category ) {
						$wp_cat = get_term_by( 'id', $category->id, $category->taxonomyName );
						$managerCategories[] = '#' . $category->id . ': ' . $wp_cat->name;

					if ( strlen( $wp_cat->name ) > 0 ) {
						$new_cat = array(
							'taxonomy' => $category->taxonomyName,
							'category' => $wp_cat->name );
						if ( !in_array( $new_cat, $arrPostCategoryNames ) ) {
							$arrPostCategoryNames[] = $new_cat;
						}
					}
				}
				}				

				$publishthis->log->addWithLevel( array( 'message' => 'Trying to map to CMS categories', 'status' => 'info', 'details' => implode( ",", $managerCategories ) ), "2" );
			}
			else {
				// Categorize
				// map categories from custom data in a Feed to categories in wordpress
				if ( $action_meta['category'] !== '0' ) {
					if ( isset( $custom_data[ $action_meta['category'] ] ) ) {
						$strCategoryValue = $custom_data[ $action_meta['category'] ];

						// Set category to Uncategorized if we received some value, but it is empty
						$uncategorized_term = get_term_by( 'name', 'uncategorized', 'category' );
						if ( empty( $strCategoryValue ) && $uncategorized_term ) {
							$strCategoryValue = $uncategorized_term->name;
						}

						foreach ( explode( ',', $strCategoryValue ) as $category ) {
							$arrPostCategoryNames[] = array(
								'taxonomy' => $action_meta['taxonomy'],
								'category' => $category );
						}
					}

					$publishthis->log->addWithLevel( array( 'message' => 'Trying to map to categories', 'status' => 'info', 'details' => implode( ",", $managerCategories ) ), "2" );
				}
			}

			// Base $post
			$post = compact( 'post_type' );

			// Combined mode selected - all imported content in single WP post
			if ( $action_meta['format'] == 'combined' ) {
				//don't update existed posts if synchronization is turned off
				$post_id = $this->_get_post_by_docid ( $set_name );
				if ( $post_id && ! $action_meta['synchronize'] ) {
					$posts_skipped++;
				}
				else {
					//set WP post title
					$post['post_title'] = $feed_meta['displayName'];
					$post['page_template'] = $action_meta['page_template']!='default' ? $action_meta['page_template'] : '';

					//save imported data
					//this is updating a "combined or digest post"
					$status = $this->_update_combined( $post_id, $feed_id, $set_name, $arrPostCategoryNames, $post, $curated_content, $action_meta, $custom_data['pttags'] );
					if ( $status == 'updated' ) $posts_updated++;
					if ( $status == 'inserted' ) $posts_inserted++;
				}
			}
			else { // Individual mode selected - import content in separate WP posts
				$new_set_docids = array ();
				$this->_individuals_log = array();
				
				$index = 0;
				$index_from = $currentPageNum*IMPORT_MAX_ITEMS_COUNT;
				
				$nextPageNum = $currentPageNum + 1;
				$index_to = $nextPageNum*IMPORT_MAX_ITEMS_COUNT;

                $publishthis->log->addWithLevel(array(
                    'message' => '== Paging state',
                    'status' => 'info',
                    'details' => "Page: " . $currentPageNum . " IndexFrom: " . $index_from . " IndexTo: " . $index_to ." Total docs: ".count($curated_content)), "2");


                foreach ( $curated_content as $content ) {
					
					//if we are doing paging, fall into this if/then block
					if($currentPageNum != -1 ) {
					
						if( $index < $index_from) {
							//keep skipping until we get to where we left off							
							$index++;
							continue;
						}
						
						if( $index >= $index_to) {
							
							//if we are at the end of our paging part, just break out, because
							//we do not need to keep looping
							break;
						}
						
					}
					$index++;
                    $publishthis->log->addWithLevel(array(
                        'message' => '== Paging state. Publish doc #'.$index,
                        'status' => 'info',
                        'details' => ""), "2");

                    //don't update existed posts if synchronization is turned off
					$post_id = $this->_get_post_by_docid ( $content->docId );
				
					//save imported data			
					//until manager tool fixes the original vs thumbnail issue, we need to switch bookmark images to their thumbnails
					if ( !empty( $content->imageUrl )){
						if (strrpos($content->imageUrl, "bookmark") > 0){
							$content->imageUrl = $content->imageUrlThumbnail;	
						}
					}
					
					$action_meta['feed_tags'] = $custom_data['pttags'];
					$status = $this->_update_individual ( $post_id, $feed_id, $arrPostCategoryNames, $post, $content, $action_meta );

					if ( $status == 'updated' ) $posts_updated++;
					if ( $status == 'inserted' ) $posts_inserted++;
					if ( $status == 'skipped' ) $posts_skipped++;

					$new_set_docids[] = $content->docId;
				}

				//delete_option( 'pt_import_current_page_'.$importId );

				if( count($this->_individuals_log) > 0 ) {
					$message = '';
					foreach($this->_individuals_log as $log_key=>$log_value) {
						$feed_id = key($log_value);
						switch($log_key) {
							case 'not_updated':
								$message = 'Skipped doc because it was not updated.<br>Feed id: ' . $feed_id . '<br>' . count($log_value[$feed_id]) . ' Post id(s): ' . implode(', ', $log_value[$feed_id]);
							break;

							case 'empty_date':
								$message = 'Skipped doc because it had an empty update date. set it and skipping.<br>Feed id:' . $feed_id . '<br>' . implode(';', $log_value[$feed_id]);
							break;
						}
					}
					$publishthis->log->addWithLevel( array(
						'message' => 'Skipped Individual Doc',
						'status' => 'info',
						'details' => $message), "1" );
				}

				/*
				  needed logic here:
				  1 - are we paging at all?
				    if we are not, just delete as normal
				  2 - are we paging?
				    - if we are done paging, then move forward with deletes
				    - if we are not done with paging, then we skip deletes and return our next page num
				 */
				if ($currentPageNum != -1){
					//we were paging
								
					//store up any of the last doc ids/etc
					$prev_published_items = get_option( 'pt_import_published' . $importId );
					$published_items = $prev_published_items ? array_merge($prev_published_items, $new_set_docids) : $new_set_docids;
					$published_items = array_unique( $published_items );

					if ($index >= count($curated_content)){
						//looks like we are done paging!
						$new_set_docids = $published_items; //set this to our complete array value so any deletes or future work can be done	
					}else{
						//ignore the rest, as we need to skip out because we have more paging to do
						//store our current state and work on returning our next page to do
						
						update_option( 'pt_import_published'  . $importId , $published_items );
						
						$total_items = $posts_updated+$posts_inserted+$posts_skipped;
						return array( 'error' => false, 'itemsPublished' => $total_items, 'complete' => false );
					}
					
				}
                $publishthis->log->addWithLevel(array('message' => 'Paging state', 'status' => 'info', 'details' => "Current page " . $currentPageNum . " index: " . $index . " count: " . count($curated_content)), "2");

				if ( $action_meta['individual_delete'] == "1" ) {
					$old_set_docids = get_option ( $set_name );
					if ( is_array( $old_set_docids ) ) {
						$docids = array();

						foreach($old_set_docids as $old) {
							if( count( preg_grep( "/^{$old}(__.*)?$/", $new_set_docids ) ) == 0 ) {
								$docids[] = $old;
							}	
						}						
						$posts_deleted = $this->_delete_individuals ( $docids );
					}	
					update_option ( $set_name, $new_set_docids );						
				}
			}

			$total_items = $posts_updated+$posts_inserted+$posts_skipped;

			$message = array(
				'message' => 'Import Results',
				'status' => 'info',
				'details' => $total_items.' post(s) processed: '.
				$posts_updated.' updated, '.$posts_inserted.' inserted, '.$posts_deleted.' deleted, '.$posts_skipped.' skipped' );
			$publishthis->log->addWithLevel( $message, "2" );
		}catch( Exception $ex ) {
			$message = array(
				'message' => 'Import Results',
				'status' => 'error',
				'details' => 'Unable to publish the mix id:' . $feed['feedId'] . ', because of:' . $ex->getMessage() );
			$publishthis->log->addWithLevel( $message, "1" );

			throw $ex;
		}
        $publishthis->log->addWithLevel( array( 'message' => 'Paging finished,  function complete', 'status' => 'info', 'details' => "" ), "2" );
		return array( 'error' => false, 'itemsPublished' => $total_items, 'complete' => true );
	}

	function publish_paged_feed_with_publishing_action( $feed, $currentPageNum=-1, $importId='' ) {
		global $publishthis;

		$nextPageNum = -1;

		try{

			$posts_updated = $posts_inserted = $posts_deleted = $posts_skipped = 0;

			$feed_id = $feed['feedId'];
						
			$feed_meta = array( "displayName" => $feed['displayName'] );

			$import_state = get_option( 'pt_import_state' );
		
			$curated_content = $import_state->json;
			$actions_meta = unserialize( $import_state->actions_meta );
			$curated_content = $this->_advanced_content_info = json_decode($curated_content);

			if ( !is_object($curated_content) && !is_array($curated_content) || $curated_content === false ) {

				if( $import_state->state == 'inprogress' ) {
					$errorMessage = ( $curated_content === false ) 
									? 'Failed to download JSON content for import ID #' . $importId . 'not found (Page #' . $currentPageNum . ')'
									: 'Data were corrupted for import ID #' . $importId . 'not found (Page #' . $currentPageNum . ')';						

					$complete = false;
				}
				else {
					$errorMessage = 'Not in publish state. Ignore';
					$complete = true;
				}					
				
				return array( 
					'error' => true, 
					'errorMessage' => $errorMessage, 
					'complete' => $complete );
			}
	
			$arrPostCategoryNames = array();

			$result_list = $publishthis->api->get_custom_data_by_feed_id ( $feed_id, array () );
			$custom_data = $managerCategories = $tags = array();
			$action_meta_ptauthors = -1;
			foreach ( $result_list as $result ) {
				$custom_data[$result->shortCode] = $result->value;

				if ( $this->_cat_mappings != '1' && !in_array( $result->shortCode, array( 'ptauthors', 'ptcategories', 'pttags' ) ) ) {
					$managerCategories[] = $result->value;
				}
			}

			if ( $this->_author_mappings == '1' ) {
				if ( isset( $custom_data['ptauthors'] ) ) {
					$action_meta_ptauthors = $custom_data['ptauthors'];
				}
			}

			if ( $this->_cat_mappings == '1' ) {
				if( isset( $custom_data['ptcategories'] ) ) {
				foreach ( $custom_data['ptcategories'] as $category ) {
						$wp_cat = get_term_by( 'id', $category->id, $category->taxonomyName );
						$managerCategories[] = '#' . $category->id . ': ' . $wp_cat->name;

					if ( strlen( $wp_cat->name ) > 0 ) {
						$new_cat = array(
							'taxonomy' => $category->taxonomyName,
							'category' => $wp_cat->name );
						if ( !in_array( $new_cat, $arrPostCategoryNames ) ) {
							$arrPostCategoryNames[] = $new_cat;
						}
					}
				}
				}				

				$publishthis->log->addWithLevel( array( 'message' => 'Trying to map to CMS categories', 'status' => 'info', 'details' => implode( ",", $managerCategories ) ), "2" );
			}

			// Base $post
			$new_set_docids = array ();
			$this->_individuals_log = array();
			
			$index = 0;
			$index_from = $currentPageNum*IMPORT_MAX_ITEMS_COUNT;
			
			$nextPageNum = $currentPageNum + 1;
			$index_to = $nextPageNum*IMPORT_MAX_ITEMS_COUNT;

            $publishthis->log->addWithLevel(array(
                'message' => '== Paging state',
                'status' => 'info',
                'details' => "Page: " . $currentPageNum . " IndexFrom: " . $index_from . " IndexTo: " . $index_to ." Total docs: ".count((array)$curated_content)), "2");

			foreach ( $curated_content as $content_key=>$content ) {
				//if we are doing paging, fall into this if/then block
				if($currentPageNum != -1 ) {
					
					if( $index < $index_from) {
						//keep skipping until we get to where we left off							
						$index++;
						continue;
					}
					
					if( $index >= $index_to) {
						
						//if we are at the end of our paging part, just break out, because
						//we do not need to keep looping
						break;
					}
					
				}
				$index++;
                $publishthis->log->addWithLevel(array(
                    'message' => '== Paging state. Publish doc #'.$index,
                    'status' => 'info',
                    'details' => ""), "2");

				$action_meta_id = $content_key === 'combined' ? $content[0]->action_meta_id : $content->action_meta_id;
				$action_meta = $actions_meta[$action_meta_id];
				$action_meta['ptauthors'] = $action_meta_ptauthors;

				$post_type = $action_meta['post_type'];
				$post = compact( 'post_type' );

				$set_name = '_publishthis_set_' . $action_meta['template_id'] . '_' . $action_meta['section_id'] . '_' . $feed_id;

				if ( $this->_cat_mappings != '1' ) {
					// Categorize
					// map categories from custom data in a Feed to categories in wordpress
					if ( $action_meta['category'] !== '0' ) {
						if ( isset( $custom_data[ $action_meta['category'] ] ) ) {
							$strCategoryValue = $custom_data[ $action_meta['category'] ];

							// Set category to Uncategorized if we received some value, but it is empty
							$uncategorized_term = get_term_by( 'name', 'uncategorized', 'category' );
							if ( empty( $strCategoryValue ) && $uncategorized_term ) {
								$strCategoryValue = $uncategorized_term->name;
							}

							foreach ( explode( ',', $strCategoryValue ) as $category ) {
								$arrPostCategoryNames[] = array(
									'taxonomy' => $action_meta['taxonomy'],
									'category' => $category );
							}
						}

						$publishthis->log->addWithLevel( array( 'message' => 'Trying to map to categories', 'status' => 'info', 'details' => implode( ",", $managerCategories ) ), "2" );
					}
				}			
				try {
				if($content_key === 'combined') {	
					//don't update existed posts if synchronization is turned off
					$post_id = $this->_get_post_by_docid ( $set_name );
					if ( $post_id && ! $action_meta['synchronize'] ) {
						$posts_skipped++;
					}
					else {
						//set WP post title
						$post['post_title'] = $feed_meta['displayName'];
						$post['page_template'] = $action_meta['page_template']!='default' ? $action_meta['page_template'] : '';

						//save imported data
						//this is updating a "combined or digest post"
						$status = $this->_update_combined( $post_id, $feed_id, $set_name, $arrPostCategoryNames, $post, $content, $action_meta, $custom_data['pttags'] );
					
						if ( $status == 'updated' ) $posts_updated++;
						if ( $status == 'inserted' ) $posts_inserted++;
					}
				}
				else {
					//don't update existed posts if synchronization is turned off
					$post_id = $this->_get_post_by_docid ( $content->docId );
				
					//save imported data			
					//until manager tool fixes the original vs thumbnail issue, we need to switch bookmark images to their thumbnails
					if ( !empty( $content->imageUrl )){
						if (strrpos($content->imageUrl, "bookmark") > 0){
							$content->imageUrl = $content->imageUrlThumbnail;	
						}
					}
					
					$action_meta['feed_tags'] = $custom_data['pttags'];
					$status = $this->_update_individual ( $post_id, $feed_id, $arrPostCategoryNames, $post, $content, $action_meta );
					
					if ( $status == 'updated' ) $posts_updated++;
					if ( $status == 'inserted' ) $posts_inserted++;
					if ( $status == 'skipped' ) $posts_skipped++;

					$new_set_docids[] = $content->docId.'__'.$action_meta_id;
				}
				}	
				catch(Exception $e) {
					$publishthis->log->addWithLevel( array(
						'message' => 'Failed to insert/update post for mix id ' . $feed_id,
						'status' => 'error',
						'details' => 'Import stopped: '.$e->getMessage()), "1" );

					return array( 'error' => true, 'errorMessage' => 'Failed to insert/update post for mix id ' . $feed_id . '. Import stopped: '.$e->getMessage(), 'complete' => false );
				}

			}

			if( count($this->_individuals_log) > 0 ) {
				$message = '';
				foreach($this->_individuals_log as $log_key=>$log_value) {
					$feed_id = key($log_value);
					switch($log_key) {
						case 'not_updated':
							$message = 'Skipped doc because it was not updated.<br>Feed id: ' . $feed_id . '<br>' . count($log_value[$feed_id]) . ' Post id(s): ' . implode(', ', $log_value[$feed_id]);
						break;

						case 'empty_date':
							$message = 'Skipped doc because it had an empty update date. set it and skipping.<br>Feed id:' . $feed_id . '<br>' . implode(';', $log_value[$feed_id]);
						break;
					}
				}
				$publishthis->log->addWithLevel( array(
					'message' => 'Skipped Individual Doc',
					'status' => 'info',
					'details' => $message), "1" );
			}

			/*
			  needed logic here:
			  1 - are we paging at all?
			    if we are not, just delete as normal
			  2 - are we paging?
			    - if we are done paging, then move forward with deletes
			    - if we are not done with paging, then we skip deletes and return our next page num
			 */
			if ($currentPageNum != -1){
				//we were paging
							
				//store up any of the last doc ids/etc
				$prev_published_items = get_option( 'pt_import_published' . $importId );
				$published_items = $prev_published_items ? array_merge($prev_published_items, $new_set_docids) : $new_set_docids;
				$published_items = array_unique( $published_items );

				if ($index >= count((array)$curated_content)){
					//looks like we are done paging!
					$new_set_docids = $published_items; //set this to our complete array value so any deletes or future work can be done	
				}else{
					//ignore the rest, as we need to skip out because we have more paging to do
					//store our current state and work on returning our next page to do
					
					update_option( 'pt_import_published'  . $importId , $published_items );
					
					$total_items = $posts_updated+$posts_inserted+$posts_skipped;
					return array( 'error' => false, 'itemsPublished' => $total_items, 'complete' => false );
				}
				
			}
            $publishthis->log->addWithLevel(array('message' => 'Paging state', 'status' => 'info', 'details' => "Current page " . $currentPageNum . " index: " . $index . " count: " . count((array)$curated_content)), "2");

			foreach ($actions_meta as $action_key => $action_values) {
				if ( $action_values['format'] == 'individual' ) {
					if ( $action_values['individual_delete'] == "1" ) {
						$old_set_docids = get_option ( $set_name );
						if ( is_array( $old_set_docids ) ) {
							$docsData = array();

							foreach($old_set_docids as $old) {
								if( count( preg_grep( "/^{$old}(__.*)?$/", $new_set_docids ) ) == 0 ) {
									$docsData[] = $old;
								}	
							}
							
							$docids = array();
							foreach($docsData as $data) { 
								list($dId, $aId) = explode('__', $data);
								
								if( isset($aId) && $aId==$action_key) {
									$docids[] = $dId;
								}
								elseif(strpos($dId, '__')===false) {
									$docids[] = $data;
								} 
							}
							$posts_deleted = $this->_delete_individuals ( $docids );
						}	
						update_option ( $set_name, $new_set_docids );						
					}
				}
			}

			$total_items = $posts_updated+$posts_inserted+$posts_skipped;

			$message = array(
				'message' => 'Import Results',
				'status' => 'info',
				'details' => $total_items.' post(s) processed: '.
				$posts_updated.' updated, '.$posts_inserted.' inserted, '.$posts_deleted.' deleted, '.$posts_skipped.' skipped' );
			$publishthis->log->addWithLevel( $message, "2" );
		}catch( Exception $ex ) {
			$message = array(
				'message' => 'Import Results',
				'status' => 'error',
				'details' => 'Unable to publish the mix id:' . $feed['feedId'] . ', because of:' . $ex->getMessage() );
			$publishthis->log->addWithLevel( $message, "1" );

			throw $ex;
		}
        $publishthis->log->addWithLevel( array( 'message' => 'Paging finished,  function complete', 'status' => 'info', 'details' => "" ), "2" );
		return array( 'error' => false, 'itemsPublished' => $total_items, 'complete' => true );
	}

	/**
	 * Clean up excerpts
	 *
	 * @param unknown $text this should be the excerpt text
	 */
	function remove_pt_excerpt_items( $text ) {
		if ( !is_single() ) {
			try{
				if ( !empty( $text ) ) {

					//remove our pt comment items first
					$text = preg_replace( '/(<!--startptremove-->.*?<!--endptremove-->)/', '', $text );
				}
			}catch( Exception $ex ) {

			}
		}

		return $text;
	}

	/*
  	 * Returns categories existing in wp
  	*/
	private function _check_categories( $arrPostCategoryNames ) {
		global $publishthis;

		$arrTermIds = $mappedTo = array();
		foreach ( $arrPostCategoryNames as $itemCategory ) {
			// try to get existed category
			$term = get_term_by ( 'name', $itemCategory['category'], $itemCategory['taxonomy'] );

			//category found and it wasn't changed
			if ( $term ) {
				$arrTermIds[] = $term->term_id;
				$mappedTo[] = $term->name . ' (slug: '. $term->slug . ')';
			}
		}

		if ( empty( $arrTermIds ) ) {
			// Set category to Uncategorized if we received some value, but it is empty
			$uncategorized_term = get_term_by( 'name', 'uncategorized', 'category' );
			if ( $uncategorized_term ) {
				$arrTermIds[] = $uncategorized_term->term_id;
			}

			$publishthis->log->addWithLevel( array( 'message' => 'No categories to map', 'status' => 'warn', 'details' => '' ), "2" );
		}
		else {
			$publishthis->log->addWithLevel( array( 'message' => 'Successfully mapped to:', 'status' => 'info', 'details' => implode( ',', $mappedTo ) ), "2" );
		}

		return $arrTermIds;
	}

	/*
  	 * Returns categories existing in wp
	 */
	private function _add_categories_with_taxonomy( $post_id, $arrPostCategoryNames ) {
		global $publishthis;

		$sortedCategories = array();		
		foreach ( $arrPostCategoryNames as $itemCategory ) {
			// try to get existed category
			$term = get_term_by ( 'name', $itemCategory['category'], $itemCategory['taxonomy'] );

			//category found and it wasn't changed
			if ( $term ) {
				$sortedCategories[$term->taxonomy][] = $term->name;		
			}
			}

		if( empty($sortedCategories) ) {
			$sortedCategories['category'][] = 'uncategorized';
		}

		// clean old categories and taxonomies		
		$post = &get_post($post_id);

		// get post type taxonomies
		$taxonomies = get_object_taxonomies($post);
		$taxonomies = array_diff( $taxonomies,  array('post_tag', 'post_format') );
		wp_delete_object_term_relationships( $post_id, $taxonomies );

		// set new values 		
		foreach ($sortedCategories as $taxonomy => $categories ) {
			wp_set_object_terms( $post_id, $categories, $taxonomy );
		}

		return true;
	}

	/**
	 * Returns array of tags to set
	 *
	 * @param array   $a1 previous manager tags
	 * @param array   $a2 post current tags
	 * @param array   $a3 new manager tags
	 */
	private function _combine_tags_array( $a1, $a2, $a3 ) {
		if ( empty( $a1 ) ) return array_merge( $a3, $a2 );

		//get custom tags added manually
		$a4 = array_diff( $a2, $a1 );

		if ( empty( $a3 ) ) return $a4;

		//manager tags to process
		$a5 = array_diff( array_merge( $a3, $a1 ), array_diff( $a1, $a3 ) );

		//get result unique tags array
		$a6 = array_unique( array_merge( $a5, $a4 ) );

		return $a6;
				}

	/**
	 * Delete post tags
	 * Returns array of tags to set
	 */
	private function _delete_unused_tags( $post_id, $tags_to_check, $saved_as_keys ) {
		$saved_manager_tags = $saved_post_tags = array(); //a1
		foreach ( $saved_as_keys as $saved_as_key ) {
			$tags_data = get_post_meta( $post_id, $saved_as_key, true );
			$saved_manager_tags = array_merge( $saved_manager_tags, $tags_data );
		}

		$saved_post_tags_objects = wp_get_post_tags( $post_id );
		foreach ( $saved_post_tags_objects as $saved_post_tags_object ) {
			$saved_post_tags[] = $saved_post_tags_object->name;
		}

		return $this->_combine_tags_array( $saved_manager_tags, $saved_post_tags, $tags_to_check );
	}

	/**
	 * Set post tags
	 */
	private function _set_tags( $post_id, $post_tags ) {
		$tags = $entities = $topics = $parentTopics = array();
		foreach ( $post_tags as $tag ) {
			switch ( $tag->type ) {
			case 'keyword':
				$tags[] = $tag->text;
				break;

			case 'entity':
				$entities[] = $tag->text;
				break;

			case 'topic':
				$topics[] = $tag->displayName . ' (' . $tag->topicLabel . ')';
				break;

			case 'parentTopic':
				$parentTopics[] = $tag->displayName . ' (' . $tag->topicLabel . ')';
				break;

			default: break;
			}
		}

		$tags_to_set = array_merge( $tags, $entities, $topics, $parentTopics );
			
		//delete old tags
		$tags_to_set = $this->_delete_unused_tags( $post_id, $tags_to_set, array( '_publishthis_tags', '_publishthis_entities_tags', '_publishthis_topics_tags', '_publishthis_parentTopics_tags' ) );

		//update post meta
		update_post_meta( $post_id, '_publishthis_tags', $tags );
		update_post_meta( $post_id, '_publishthis_entities_tags', $entities );
		update_post_meta( $post_id, '_publishthis_topics_tags', $topics );
		update_post_meta( $post_id, '_publishthis_parentTopics_tags', $parentTopics );

		//set post tags
		wp_set_post_tags( $post_id, $tags_to_set, $append=false );
	}

	/**
	 * Generate post excerpt if needed
	 *
	 * @param array   $options Publishing Action Settings
	 * @param object  $data    Current import data object
	 * @param string  $format  Possible values: individual, combined
	 * @return string $excerpt Post excerpt
	 */
	private function _generate_excerpt( $options, $data, $format='individual' ) {
		$excerpt = '';

		// don't continue if user don't want to use first feed as an excerpt for digest
		if ( $options['excerpt_first_item'] == '0' && $format=='combined' ) {
			return $excerpt;
		}

		//Get data object according to post type
		//For combined format use first item from the digest
		$info = $format=='combined' ? $data[0] : $data;

		if ( !isset( $info ) ) {
			return $excerpt;
		}

		if ( $options['create_excerpt'] == '1' ) {
			if ( isset ( $info->annotations ) && count( $info->annotations ) > 0 ) {
				// Generates an excerpt from the PT annotation
				$excerpt .= '<p class="pt-excerpt">' . $info->annotations[0]->annotation . '</p>';
			}
		}

		if ( strlen( $info->summary ) > 0 ) {
			// Generates an excerpt from the content
			$excerpt .= '<p class="pt-excerpt">' . $info->summary . '</p>';
		}
		return $excerpt;
	}

	/**
	 *   Delete WP posts by docid
	 *
	 * @param unknown $docids Array of posts docid values
	 */
	private function _delete_individuals( $docids ) {
		$posts_deleted = 0;
		foreach ( $docids as $docid ) {
			$post_id = $this->_get_post_by_docid ( $docid );
			if ( $post_id ) {
				wp_delete_post ( $post_id, true );
				$posts_deleted++;
			}
		}
		return $posts_deleted;
	}

	/**
	 * Set post author
	 */
	private function _get_post_author( $wp_author, $content_authors, $feed_authors ) {
		global $publishthis;

		//Map to Manager authors
		if ( $this->_author_mappings == '1' ) {
			$author = isset( $content_authors ) 
						? $content_authors[0]->id 
						: ( isset( $feed_authors ) ? $feed_authors[0]->id : 0 );
		} else {
			if ( is_numeric( $wp_author ) && intval( $wp_author ) >= 0 ) {
				$author = intval( $wp_author );
			}
		}
		return $author >= 0 ? $author : 0;
	}

	/**
	 *   Upload imported image, get attachment ID
	 *
	 * @param integer $post_id      Post ID
	 * @param string  $url          Image url
	 * @param string  $title        Post title
	 * @param array   $image_params Image resize options
	 * @return Attachment ID or WP error object
	 */
	private function _get_attachment_id( $post_id, $url, $title ) {
		global $publishthis;
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-includes/functions.php';

		if ( ! empty ( $url ) ) {
			$tmp = download_url ( $url );

			// Set variables for storage
			// fix file filename for query strings
			preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches );
			$file_array ['name'] = basename( $matches [0] );
			$file_array ['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error ( $tmp ) ) {
				@unlink( $file_array ['tmp_name'] );
				$file_array ['tmp_name'] = '';
				
				$message = array(
				'message' => 'Fetch Image Issue',
				'status' => 'error',
				'details' => 'Unable to fetch and store temporary image:' . $url . ', error was:' . $tmp->get_error_message());
				$publishthis->log->addWithLevel( $message, "1" );
				
				
			}

			// do the validation and storage stuff
			$id = media_handle_sideload ( $file_array, $post_id, $title );

			if ( is_wp_error ( $id ) ) {
				@unlink( $file_array ['tmp_name'] );
				
				$message = array(
				'message' => 'Fetch Image Issue',
				'status' => 'error',
				'details' => 'Unable to side load image:' . $url . ', error was:' . $id->get_error_message());
				$publishthis->log->addWithLevel( $message, "1" );
				
			}

			return $id;
		}
	}

	/**
	 *   Get post ID by specified docid value
	 *
	 * @param unknown $docid
	 */
	private function _get_post_by_docid( $docid ) {
		global $wpdb, $publishthis;

		$post_id = $wpdb->get_var ( $wpdb->prepare ( "
			SELECT pm.post_id
			FROM $wpdb->postmeta pm
			WHERE pm.meta_key = '_publishthis_docid' AND pm.meta_value = %s
		", $docid ) );
		if( $post_id === false ) {
			$message = array(
				'message' => 'Failed to get post by docid',
				'status' => 'error',
				'details' => 'docid: ' . $docid . ', error:' . $wpdb->last_error);
			$publishthis->log->addWithLevel( $message, "1" );
			throw new Exception('Failed to get post by docid '.$docid.'. Details: '. $wpdb->last_error);
		}
		return ( $post_id ) ? $post_id : 0;
	}

	/**
	 * Remove previous featured image link, but keep image on the server
	 */
	private function _remove_featured_image( $post_id, $image_url='', $content_features=array() ) {
		if( !empty( $image_url ) && !empty( $content_features ) ) {
			$strImageKey = $this->_get_image_key( $image_url, $content_features );
			delete_post_meta ( $post_id, $strImageKey );	
		}
		
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		if ( ! empty( $post_thumbnail_id ) ) {
			wp_delete_attachment( intval( $post_thumbnail_id ), true );
			delete_post_meta ( $post_id, '_thumbnail_id', $post_thumbnail_id );
		}
	}

	/**
	 * Get download url key
	 */
	private function _get_image_key( $image_url, $content_features ) {
		global $publishthis;

		//build the url that we would need to download the featured image for
		switch ( $content_features ['featured_image_size'] ) {
		case 'custom':
			$image_url = $publishthis->utils->getResizedPhotoUrl( $image_url, $content_features['featured_image_width'], "1", $content_features ['featured_image_height'], $content_features['ok_override_fimage_size'], "0" );
			$publishthis->log->add( "custom, ok to resize original featured image:" . $content_features['ok_override_fimage_size'] . "; url:" . $image_url );
			
			break;

		case 'custom_max':
			$image_url = $publishthis->utils->getResizedPhotoUrl( $image_url, $content_features['featured_max_image_width'], "1", 0, $content_features['ok_override_fimage_size'], "0" );
			$publishthis->log->add( "custom max, ok to resize original featured image:" . $content_features['ok_override_fimage_size'] . "; url:" . $image_url );
			break;

		case 'custom_up_to_max':
			$image_url = $publishthis->utils->getResizedPhotoUrl( $image_url, $content_features['up_to_max_width'], "1", 0, $content_features['ok_override_fimage_size'], "1" );
			$publishthis->log->add( "custom up to max, ok to resize original featured image:" . $content_features['ok_override_fimage_size'] . "; url:" . $image_url );
			break;

		case 'theme_default':
		default: break;
		}

    
		return $image_url;
	}

	/**
	 * Generates resized featured image and link it to the post
	 */
	private function _add_featured_image( $post_id, $image_url, $content_features, $post_title ) {
		$strImageKey = $this->_get_image_key( $image_url, $content_features );

		//new 'reset image' added to Manager: need to update featured links even if we already upload such image
		$thumbnail_id = $this->_get_attachment_id ( $post_id, $strImageKey, $post_title );

		if ( ! is_wp_error ( $thumbnail_id ) ) {
			update_post_meta ( $post_id, '_thumbnail_id', $thumbnail_id );
		}
		//now see if we have all ready downloaded this url for this post
		if ( "1" == get_post_meta ( $post_id, $strImageKey, true ) ) {
			//we have all ready downloaded it, so just return
			return;
		}else {
			// set this so we do not try to upload again
			update_post_meta ( $post_id, $strImageKey, "1" );
		}

	}

	/**
	 *   Save import content as a single post (combined mode)
	 *
	 * @param unknown $post_id              WP Post ID
	 * @param number  $feed_id              The PublishThis Feed Id
	 * @param unknown $docid                docid linked to this post
	 * @param unknown $arrPostCategoryNames WP Post category
	 * @param unknown $post                 WP Post
	 * @param unknown $curated_content      Imported content
	 * @param unknown $content_features     Additional content info
	 */
	private function _update_combined( $post_id, $feed_id, $docid, $arrPostCategoryNames, $post, $curated_content, $content_features, $tags ) {
		global $publishthis;

		// Content
		// Set post excerpt if needed
		$excerpt_text = $this->_generate_excerpt( $content_features, $curated_content, 'combined' );
		if( !empty($excerpt_text) ) $post['post_excerpt'] = $excerpt_text;
		$post['post_content'] = $post_content = '';
		$featured_image = isset( $content_features['featured_image'] ) ? $content_features['featured_image'] : false;

		$GLOBALS['pt_break_page'] = $content_features['excerpt_first_item']=='1' ? true : false;
		$GLOBALS['pt_is_first'] = true;
		
		$contentImageUrl = $contentAuthor = null;

        $curated_content_index = 1; //an index for usage in our template rendering. This way, we can do different things per item of content
		
		// Generate html output
		foreach ( $curated_content as $content ) {
		
		       //until manager tool fixes the original vs thumbnail issue, we need to switch bookmark images to their thumbnails
			if ( !empty( $content->imageUrl )){
				if (strrpos($content->imageUrl, "bookmark") > 0){
					$content->imageUrl = $content->imageUrlThumbnail;	
				}
			}
		
			//save first image url for featured
			$GLOBALS['pt_found_featured_image'] = false;
			if ( !empty( $content->imageUrl ) && $contentImageUrl == null ){
				$contentImageUrl = $content->imageUrl;
				$GLOBALS['pt_found_featured_image'] = true;
			}

			$content->feedId = $feed_id;
			$content->curatedContentIndex = $curated_content_index;
			$content->curatedContentCount = count($curated_content);
			$GLOBALS['pt_content'] = $content;
			$GLOBALS['pt_content_features'] = $content_features;

			ob_start();
			
			if (array_key_exists  ( $content_features['digest_template'] , $publishthis->get_digest_templates() )){
				$publishthis->load_template( $content_features['digest_template'] . '.php' );
			}else{
				$publishthis->load_template("combined.php");
			}
			//$publishthis->load_template( 'combined.php' );
			$post_content .= ob_get_clean();

			$GLOBALS['pt_break_page'] = false;
			$GLOBALS['pt_is_first'] = false;
			
			$curated_content_index++;
		}

		unset ( $GLOBALS['pt_content'] );
		unset ( $GLOBALS['pt_content_features'] );

		// Set post author
		$author_id = $this->_get_post_author( $content_features["publish_author"], $contentAuthor, $content_features['ptauthors'] );
		if ( $author_id >= 0 ) {
			$post['post_author'] = $author_id;
		}

    	


		$post['post_content'] = $post_content;

		// Manage category
		//$post ['post_category'] = $this->_check_categories( $arrPostCategoryNames );

		// Add / update post
		if ( $post_id ) {
			$post['ID'] = $post_id;
			$result = wp_update_post( $post, true );

			if (is_wp_error($result)) {
			    throw new Exception('Failed to update post id '.$post_id.' mix id '.$feed_id.'. Details: '.$result->get_error_message());
			}

			$status = 'updated';
			
			update_post_meta( $post_id, '_publishthis_posttype','combined');
				
			//post this action, in case any other plugins want to do something with it
			$arrPostAction = array ();
			$arrPostAction[] = $post_id;
			$publishthis->postAction("publishthis-post-update",$arrPostAction);
		} else {
			$post['post_status'] = $content_features ['post_status'];
			$post_id = $result = wp_insert_post( $post, true );
			if(is_wp_error($result)) {
			    throw new Exception('Failed to insert post for docid '.$docid.' mix id '.$feed_id.'. Details: '.$result->get_error_message());
			}
			add_post_meta( $post_id, '_imported', '1', true );
			add_post_meta( $post_id, '_publishthis_docid', $docid, true );
			add_post_meta( $post_id, '_publishthis_posttype','combined');
			
			$status = 'inserted';
			
			//post this action, in case any other plugins want to do something with it
			$arrPostAction = array ();
			$arrPostAction[] = $post_id;
			$publishthis->postAction("publishthis-post-insert",$arrPostAction);
		}

		if ( $content_features['tags'] == '1' ) {
			$this->_set_tags( $post_id, $tags );
		}

		$this->_add_categories_with_taxonomy( $post_id, $arrPostCategoryNames );

		//log messages on error
		if ( is_wp_error( $result ) ) {
			$message = array(
				'message' => 'Post insert/update error',
				'status' => 'error',
				'details' => implode( ';', $result->get_error_messages() ) );
			$publishthis->log->addWithLevel( $message, "1" );
		}

		// Download and set featured image
		if ( $featured_image && ! empty ( $contentImageUrl ) ) {
			//add in the new one
			$this->_add_featured_image( $post_id, $contentImageUrl, $content_features, $post['post_title'] );
		}
		else {
			//unlink featured image from post
			delete_post_meta ( $post_id, '_thumbnail_id' );
		}

		// Add / update meta
		update_post_meta( $post_id, '_publishthis_raw', $content );
		update_post_meta( $post_id, '_wp_page_template', $content_features['page_template'] );
		
		return $status;
	}

	/**
	 *   Save import content as a separate post (individual mode)
	 *
	 * @param unknown $post_id              WP Post ID
	 * @param number  $feed_id              The PublishThis Feed Id
	 * @param unknown $arrPostCategoryNames WP Post category
	 * @param unknown $post                 WP Post
	 * @param unknown $content              Imported content
	 * @param unknown $featured_image       Flag that shows set featured image or not
	 * @param unknown $content_features     Additional content info
	 */
	private function _update_individual( $post_id, $feed_id, $arrPostCategoryNames, $post, $content, $content_features ) {
		global $publishthis;

		$featured_image = isset( $content_features['featured_image'] ) ? $content_features['featured_image'] : false;

		//first, see if we are even allowed to do an update if it is there
		if ( ( $post_id ) && ( $content_features ['individual_update'] == '1' ) ) {
			$contentAuthor = null;

			$postLastUpdateDateValue = get_post_meta( $post_id, '_publishthis_doc_last_update_date', true );

			if ( empty( $postLastUpdateDateValue ) || ( $postLastUpdateDateValue == $content->curateUpdateDate ) ) {
				if ( empty( $postLastUpdateDateValue ) ) {
					add_post_meta( $post_id, '_publishthis_doc_last_update_date', $content->curateUpdateDate, true );
					$this->_individuals_log['empty_date'][$feed_id][] = 'Post id:' . $post_id . ' date was:' . $content->curateUpdateDate;
				} else {
          $this->_individuals_log['not_updated'][$feed_id][] = '' . $post_id . '.' . $content->docId;
				}
				return "skipped";
			}
		} else if ( ( $post_id ) && ( $content_features ['individual_update'] != '1' ) ) {
				return "skipped";
			}

		// Set post author
		if ( !empty( $content->contentAuthors ) ) {
			$contentAuthor = $content->contentAuthors;
		}
		
		$author_id = $this->_get_post_author( $content_features["publish_author"], $contentAuthor, $content_features['ptauthors'] );
		if ( $author_id >= 0 ) {
			$post['post_author'] = $author_id;
		}

		// Set Manager content specific categories
		if ( $this->_cat_mappings == '1' && isset( $content->contentCategories ) ) {
			
			foreach ( $content->contentCategories as $category ) {
				$wp_cat = get_term_by( 'id', $category->id, $category->taxonomyName );

				if ( strlen( $wp_cat->name ) > 0 ) {
					$new_cat = array(
						'taxonomy' => $category->taxonomyName,
						'category' => $wp_cat->name );
					if ( !in_array( $new_cat, $arrPostCategoryNames ) ) {
						$arrPostCategoryNames[] = $new_cat;
					}
				}
			}
		}

		// Set post title
		$post ['post_title'] = ! empty ( $content->title ) ? $content->title : '';
		if( empty ( $content->title ) ) { 
			$post ['post_name'] = $content->docId;
		}
		$post ['page_template'] = $content_features['page_template']!='default' ? $content_features['page_template'] : '';

		// Set post excerpt if needed
		$post ['post_excerpt'] = $this->_generate_excerpt( $content_features, $content, 'individual' );
		$post ['post_content'] = '';

		$content->feedId = $feed_id;

		// Set Content
		$GLOBALS ['pt_content'] = $content;
		$GLOBALS ['pt_content_features'] = $content_features;

		// Manage category
		//$post ['post_category'] = $this->_check_categories( $arrPostCategoryNames );
		
		// Generate html output
		ob_start();
		$publishthis->load_template ( 'individual.php' );
		$thePostContent = ob_get_clean();
		
    	
    
    
		$post ['post_content'] = $thePostContent;
		unset ( $GLOBALS ['pt_content'] );
		unset ( $GLOBALS ['pt_content_features'] );

		$status = 'skipped';
		// Add / update post
		if ( $post_id ) {
			if ( $content_features ['individual_update'] == '1' ) {
				$post ['ID'] = $post_id;
				$result = wp_update_post ( $post, true );

				if(is_wp_error($result)) {
					throw new Exception('Failed to update post id '.$post_id.'. Details: '.$result->get_error_message());
				}

				update_post_meta( $post_id, '_publishthis_doc_last_update_date', $content->curateUpdateDate );

				update_post_meta( $post_id, '_publishthis_posttype','individual');

				$status = 'updated';

				$publishthis->log->addWithLevel( array(
						'message' => 'POST UPDATED',
						'status' => 'info',
						'details' => $post_id.'('.$content->docId.')'), "1" );
				
				//post this action, in case any other plugins want to do something with it
				$arrPostAction = array ();
				$arrPostAction[] = $post_id;
				$publishthis->postAction("publishthis-post-update",$arrPostAction);
				
			}
		} else {
			if ( $content_features ['individual_insert'] == '1' ) {
				$post['post_status'] = $content_features ['post_status'];
				$post_id = $result = wp_insert_post ( $post, true );

				if(is_wp_error($result)) {
					throw new Exception('Failed to insert post for docid '.$content->docId.'. Details: '.$result->get_error_message());
				}
				add_post_meta ( $post_id, '_imported', '1', true );
				add_post_meta ( $post_id, '_publishthis_docid', $content->docId, true );
				add_post_meta( $post_id, '_publishthis_doc_last_update_date', $content->curateUpdateDate, true );
				add_post_meta( $post_id, '_publishthis_posttype','individual');
			
				$status = 'inserted';
				
				$publishthis->log->addWithLevel( array(
						'message' => 'POST INSERTED',
						'status' => 'info',
						'details' => $post_id.'('.$content->docId.')'), "1" );

				//post this action, in case any other plugins want to do something with it
				$arrPostAction = array ();
				$arrPostAction[] = $post_id;
				$publishthis->postAction("publishthis-post-insert",$arrPostAction);
			}
		}

		// Set Manager Tags
		if ( $post_id && $content_features['tags'] == '1' ) {
			$tags = array();
			foreach( $content->contentTags as $tag ) $tags[] = $tag;
			foreach( $content_features['feed_tags'] as $tag ) $tags[] = $tag;			
			$this->_set_tags( $post_id, $tags );
		}

		$this->_add_categories_with_taxonomy( $post_id, $arrPostCategoryNames );

		//log messages on error
		if ( is_wp_error( $result ) ) {
			$message = array(
				'message' => 'Post insert/update error',
				'status' => 'error',
				'details' => implode( ';', $result->get_error_messages() ) );
			$publishthis->log->addWithLevel( $message, "1" );
		}

		// Set post Formats
		if ( $post ['post_type'] == 'post' && isset ( $content->contentType ) ) {
			switch ( $content->contentType ) {
			case 'video' :
				set_post_format ( $post_id, 'video' );
				break;
			case 'photo' :
				set_post_format ( $post_id, 'image' );
				break;
			case 'tweet' :
				set_post_format ( $post_id, 'status' );
				break;
			case 'article' :
			case 'text' :
			default :
				break;
			}
		}

		// Download and set featured image
		if ( $featured_image && ! empty ( $content->imageUrl ) ) {
			//add in the new one
			$this->_add_featured_image( $post_id, $content->imageUrl, $content_features, $post['post_title'] );
		}
		else {
			//unlink featured image from post
			delete_post_meta ( $post_id, '_thumbnail_id' );
		}

		// Add / update meta
		update_post_meta( $post_id, '_publishthis_raw', $content );
		update_post_meta( $post_id, '_wp_page_template', $content_features['page_template'] );

		return $status;
	}

	/**
	 * this takes an array of feed ids, and then tries to publish each one of them
	 * using all of our helper functions.
	 * This will usually be called from our publishing endpoint
	 */

	public function publish_specific_feeds( $arrFeedIds, $currentPageNum=-1, $importId='' ) {
		global $publishthis;

		$nextPageNum = -1;

		if (! isset( $publishthis ) ) {
            throw new Exception( 'Plugin install did not load our primary PT object.' );
        }

        //use these to keep track of what published and what didn't
        //so we can report it back to the caller in an exception
        $intDidPublish = 0;
        $arrFeedsNotPublished = array();
        $arrExceptions = array();

        try{
            //to publish, we need our actual feed objects
            $arrFeeds = $publishthis->api->get_feeds_by_ids( $arrFeedIds );
	    if(empty( $arrPublishingActions )) {
	 	$arrExceptions[] = 'Feeds not found';
		$arrFeedsNotPublished = array_merge($arrFeedsNotPublished, $arrFeedIds);
	    }

            //loop feeds to publish
            foreach ( $arrFeeds as $feed ) {

                //get all publishing actions that match up with this feed template (usually 1)
                $arrPublishingActions = $this->get_publishing_actions();

		if(empty( $arrPublishingActions )) {
			$arrExceptions[] = 'Publishing Actions not found';
		}

                $blnDidPublish = false;

                //loop the publishing actions and it will then publish content for that feed
                $saveJson = array();
                $actions_meta = array();
                foreach ( $arrPublishingActions as $pubAction ) {
                    $actionId = $pubAction->ID;

                    $action_meta = $this->get_action_meta( $actionId );

                    if ( $feed['templateId'] == $action_meta['template_id'] ) {
                        try{
                        	$curated_content = $publishthis->api->get_section_content ( $feed['feedId'], $action_meta['section_id'] );
                        	
                        	foreach($curated_content as $cc_key=>$cc_val) {
                        		$curated_content[$cc_key]->action_meta_id = $actionId;
                        		$actions_meta[$actionId] = $action_meta;
                        	}

                        	if(count( (array)$curated_content ) > 0 ) {
	                        	if( $action_meta['format'] != 'combined' ) {
									// make sure to reverse the array, as the order in the publish
									// this template sections have a defined order. so, the first one in the template
									// section should be marked as most recently published
									$curated_content = array_reverse( $curated_content );								
									$saveJson = array_merge($saveJson, (array)$curated_content);								
						 		}
						 		else {					 			
						 			$saveJson = array_merge($saveJson, array('combined' => $curated_content));					 			
						 		}
						 	}                    	

                        } catch( Exception $ex ) {
                            //we capture individual errors and report them,
                            //but we should keep trying to loop because not all feeds may have an issue
                            $message = array(
                                'message' => 'Import of Mix Failed',
                                'status' => 'error',
                                'details' => 'The Mix Id that failed:' . $feed['feedId'] . ' on page:' . $currentPageNum . ' and import id:' .  $importId . ' with the following error:' . $ex->getMessage() );
                            $publishthis->log->addWithLevel( $message, "1" );
                            $arrExceptions []= $ex->getMessage();
                            continue;
                        }
                        $intDidPublish++;
                        $blnDidPublish = true;
                    }else{
                        $arrExceptions []= "Template Id of feed and action differ";
                    }
                }

                //store the json for future paging runs		
                $import_state = get_option( 'pt_import_state' );

               	if ( empty ( $saveJson ) ) {
                    $publishthis->log->addWithLevel( array( 'message' => 'Paging finished. empty content', 'status' => 'info', 'details' => "" ), "2" );
					$nextPageInfo = array( 'error' => false, 'itemsPublished' => 0, 'complete' => true );
					return $nextPageInfo;
				}
				else {
					$import_state->json = json_encode($saveJson);
					$import_state->actions_meta = serialize( $actions_meta );
					update_option( 'pt_import_state', $import_state );
				}				

				$nextPageInfo = $this->publish_paged_feed_with_publishing_action( $feed, $currentPageNum, $importId );

                if ( !$blnDidPublish ) {
                    $arrFeedsNotPublished [] = $feed['feedId'];
                }
            }
        }catch( Exception $ex ) {
            //some other occurred while we tried publish, not sure what
            //capture this and log it and then throw it as well as what info we have

            $message = array(
                'message' => 'Import of Mix Failed',
                'status' => 'error',
                'details' => 'A general exception happened during the publishing of specific feeds. Mix Ids not published:' . implode( ',', $arrFeedsNotPublished ) . ' specific message:' . $ex->getMessage() );
            $publishthis->log->addWithLevel( $message, "1" );

            throw new Exception( 'General exception.  Only ' . $intDidPublish . ' of ' . count( $arrFeedIds ) . ' published. These were the Mix Ids that did not publish:' . implode( ',', $arrFeedsNotPublished ) . $ex->getMessage());
        }

        if ( $intDidPublish < count( $arrFeedIds ) ) {
		$message = array(
                                'message' => 'Import of Mix Failed',
                                'status' => 'error',
                                'details' =>  'Some Mixes published.  Only ' . $intDidPublish . ' of ' . count( $arrFeedIds ) . ' published. These were the Mix Ids that did not publish:' . implode( ',', $arrFeedsNotPublished ) ." Errors: " . implode(';', $arrExceptions) );
		$publishthis->log->addWithLevel( $message, "1" );
		$nextPageInfo = array( 'error' => true, 'itemsPublished' => 0, 'complete' => true );
		return $nextPageInfo;
        }
		return $nextPageInfo;
	}



	/**
	 *   Process content import
	 */
	function run_import() {
		global $publishthis;

		if( $publishthis->get_option('curated_publish') == 'import_from_manager' ) return;

		// Return here is we want to pause polling.
		if ( $publishthis->get_option ( 'pause_polling' ) ) {
			$publishthis->log->addWithLevel ( array( 'message' => 'Pause Polling', 'status' => 'warn', 'details' => '' ), "2" );
			return;
		}

		$actions = $publishthis->publish->get_publishing_actions();

		$import_id = get_option( 'pt_import_id' );
		if ( false === $import_id ) {
			$import_id = 0;
			update_option( 'pt_import_id' , $import_id );
		}

		$publishthis->log->addWithLevel( array( 'message' => 'Import Started (#'.$import_id.')', 'status' => 'info', 'details' => 'Time: '.date( "Y-m-d H:i:s" ) ), "2" );

		foreach ( $actions as $action ) {
			$action_prev_timestamp = get_post_meta( $action->ID, '_publishthis_import_start', true );
			$action_poll_interval = get_post_meta( $action->ID, '_publishthis_poll_interval', true );
			$action_curr_timestamp = time();

			if ( $action_curr_timestamp >= intval( $action_prev_timestamp ) + intval( $action_poll_interval ) ) {
				$publishthis->publish->import_content ( $action->ID, $action_prev_timestamp );
			}
		}

		$publishthis->log->addWithLevel( array( 'message' => 'Import Finished (#'.$import_id.')', 'status' => 'info', 'details' => 'Time: '.date( "Y-m-d H:i:s" ) ), "2" );
		update_option( 'pt_import_id' , intval( $import_id ) + 1 );
	}

	/**
	 *   Import data from Manager tool using Publishing Action settings
	 *
	 * @param unknown $action_id Publishing Action
	 * @param unknown $timestamp Used to find newly created or published content
	 * This will pull in the curated content for feeds.  Depending on the
	 * publishing action, individual posts will be created from the curated
	 * documents, or, digest posts will be created from the curated documents.
	 */
	function import_content( $action_id, $timestamp ) {
		global $publishthis;

		// Collect debug info
		$message = array();
		$message['message'] = 'Content Import';
		$message['status'] = 'info';
		$message['details'] = '';
		$message['details'] .= 'Import Timestamp: '.$timestamp.'<br/>';
		$message['details'] .= 'From: '.$publishthis->get_option( 'curated_publish' ).'<br/>';
		$message['details'] .= 'Action ID: '.$action_id.'<br/>';

		// Get $action
		$action = get_post ( $action_id );

		// Publishing Action should be active (not in trash)
		if ( ! $action || $action->post_status!='publish' ) {
			$message['status'] = 'error';
			$message['details'] .= 'Status: error ( action not found )';
			$publishthis->log->addWithLevel( $message, "2" );
			return;
		}

		//save import timestamp
		update_post_meta( $action_id, '_publishthis_import_start', time() );

		//get all meta data for this publishing action
		$action_meta = $this->get_action_meta( $action_id );

		$message['details'] .= 'Action format: '.$action_meta['format'].'<br/>';

		// Get feeds
		$feeds = $publishthis->api->get_feeds_since_timestamp ( $timestamp, $action_meta['template_id'] );

		$message['details'] .= 'Found: '.count( $feeds ).' mix(es)<br/>';

		if ( empty ( $feeds ) ) {
			$message['status'] = 'warn';
			$message['details'] .= 'Status: error ( empty mixes list )';
			$publishthis->log->addWithLevel( $message, "2" );
			return;
		}

		/* loop each of our feeds, and then either create individual posts or digests
		  from the curated documents in the feed
		 */
		$ids = array();
		$intErrorCount = 0;
		foreach ( $feeds as $feed ) {
			$ids[] = $feed['feedId'];
			try{
				$this->publish_feed_with_publishing_action( $feed, $action_meta );
			}catch( Exception $ex ) {

				//we capture individual errors and report them,
				//but we should keep trying to loop because not all feeds may have an issue
				$message = array(
					'message' => 'Import of Mix Failed',
					'status' => 'error',
					'details' => 'Time: '.date( "Y-m-d H:i:s" ).'<br/>Cron Timestamp: '.$timestamp . ' feed id:' . $feed['feedId'] . ' specific error:' . $ex->getMessage() );
				$publishthis->log->addWithLevel( $message, "1" );

				$intErrorCount++;
			}
		}

		if ( $intErrorCount == 0 ) {

			$message['details'] .= 'Mix IDs: '.implode( ',', $ids ).'<br/>';
			$message['details'] .= 'Status: ' . $intErrorCount . ' errors';
			$publishthis->log->addWithLevel( $message, "2" );

		}else {
			$message = array(
				'message' => 'Some Import of Feeds Failed',
				'status' => 'error',
				'details' => 'Total Mix Failures:' . $intErrorCount . ' out of ' . count( $feeds ) );
			$publishthis->log->addWithLevel( $message, "1" );
		}
	}
}
