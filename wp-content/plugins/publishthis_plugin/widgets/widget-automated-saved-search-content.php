<?php

/**
 * defines Publishthis Saved Searches widget
 * - setup widget options
 * - output results
 */
class Publishthis_Automated_Saved_Search_Content_Widget extends WP_Widget {

	function __construct() {
		parent::__construct (
			'pt_automated_saved_search_content_widget',
			'PublishThis: Automated Saved Search Content',
			array ( 'classname' => 'automated-saved-search-content-widget',
				'description' => 'Display automated saved search content from PublishThis.' ) );
		// define ajax call to get saved searches
		add_action( 'wp_ajax_get_publishthis_savedsearches', array( $this, 'get_savedsearches_json' ) );
	}

	/**
	 *   Display Saved Searches widget output
	 */
	function widget( $args, $instance ) {
		global $publishthis;

		// sanitize data
		$instance = $this->sanitize_data_array( $instance );

		// check that saved search(bundle_id) id passed
		if ( $instance['bundle_id'] === '-1' )
			return;

		// check for cached content
		$html = get_transient ( $this->id);
		if ( $html !== false ) {
			echo $html;
			return;
		}

		// generate output
		ob_start();

		echo $args ['before_widget'];

		if ( $title = apply_filters ( 'widget_title', $instance['title'] ) ) {
			echo $args ['before_title'] . $title . $args ['after_title'];
		}

		$params = array ( 
							'sort' => $instance['sort_by'], 
							'results' => $instance['num_results'], 
							'contentTypes' => $instance ['content_types'],
							'removeNearDuplicates' => ($instance ['remove_duplicates']=="1" ? "true" : "false"),
							'removeNearRelated' => ($instance ['remove_related']=="1" ? "true" : "false")
						);

		if ('-1' == $params['sort']){
			unset($params['sort']);
		}


		// retrieve saved searches (API call)
		$content = $publishthis->api->get_saved_search_content ( array ( $instance['bundle_id'] ), $params );

		// pass widget settings to template
		if ( $content ) {
			$GLOBALS ['pt_content'] = array (
				'result'             => $content,
				'type'               => 'savedsearch',
				'columns_count'      => $instance['columns_count'],
				'show_links'         => $instance['show_links'],
				'show_photos'        => $instance['show_photos'],
				'show_source'        => $instance['show_source'],
				'show_summary'       => $instance['show_summary'],
				'show_date'          => $instance['show_date'],
				'show_nofollow'      => $instance['show_nofollow'],
				'image_size'         => $instance['image_size'],
				'image_align'        => $instance['image_align'],
				'ok_resize_previews' => $instance['ok_resize_previews'] );
			if( $instance['image_size']=='custom' ) {
				$GLOBALS['pt_content']['image_width'] = $instance['image_width']; 
				$GLOBALS['pt_content']['image_height'] = $instance['image_height'];
			}
			else if( $instance['image_size']=='custom_max' ) {
				$GLOBALS['pt_content']['max_width_images'] = $instance['max_width_images'];
			}
			else if( !isset( $instance['image_size'] ) && isset( $instance['max_width_images'] ) ) {
				$GLOBALS ['pt_content']['image_size'] = 'custom_max';
				$GLOBALS ['pt_content']['max_width_images'] = $instance['max_width_images'];
			}
			$publishthis->load_template ( 'automated-content.php' );
			unset ( $GLOBALS ['pt_content'] );
		}

		echo $args ['after_widget'];

		$html = ob_get_clean();
		set_transient ( $this->id, $html, $instance['cache_interval'] );

		// render output
		echo $html;
	}

	/**
	 *   Sanitize data before usage
	 */
	function sanitize_data_array( $data ) {
		$safe_data = array();
		foreach ( $data as $key=>$val ) {
			$safe_data[ $key ] = sanitize_text_field( $val );
		}
		return $safe_data;
	}

	/**
	 *   Prepare Saved Searches widget settings for saving
	 */
	function update( $new_instance, $old_instance ) {
		delete_transient( $this->id );
		$instance = $new_instance + $old_instance;
		return $this->sanitize_data_array( $instance );
	}

	/**
	 *   Retrieve saved searches data
	 */
	function get_savedsearches() {
		global $publishthis;

		// Get saved searches (API call)
		$saved_searches = $publishthis->api->get_saved_searches();
		return $saved_searches;
	}

	/**
	 *   Retrieve saved searches data for AJAX
	 *
	 * @return JSON object
	 */
	function get_savedsearches_json() {
		global $publishthis;

		// Get saved searches
		$saved_searches = $this->get_savedsearches();

		// Return empty result
		if ( !$saved_searches ) {
			$json = array(
				'message' => 'No Saved Searches found',
				'status' => 'error',
			);
			echo json_encode( $json );
			exit;
		}

		// Return saved searches with success message
		$json = array(
			'saved_searches' => $saved_searches,
			'status' => 'success',
		);
		echo json_encode( $json );
		exit;
	}

	/**
	 *   Init and display Saved Searches widget setup form
	 */
	function form( $instance ) {
		global $publishthis;

		// set defaults
		$instance = ( array ) $instance +
			array (
			'bundle_id'          => '-1',
			'title'              => 'Automated Content - PublishThis',
			'sort_by'            => 'most_recent',
			'num_results'        => 10,
			'show_links'         => '1',
			'show_photos'        => '1',
			'show_source'        => '1',
			'show_summary'       => '1',
			'remove_duplicates'  => '1',
			'remove_related'     => '0',
			'show_date'          => '0',
			'show_nofollow'      => '0',
			'content_types'      => 'article,video,blog',
			'max_width_images'   => '300',
			'image_width'		 => '0',
			'image_height'		 => '0',
			'image_size'		 => 'default',
			'image_align'		 => 'left',
			'ok_resize_previews' => '1',
			'cache_interval'     => 60 );
		// fill available saved searches select
		$saved_searches = $this->get_savedsearches();

		$instance = $this->sanitize_data_array( $instance );

		//define content type to render correspondent settings
		$content_type = "savedsearch";

		//render settings
		include dirname( __FILE__ ) . "/setup-widget.php";
	}
}
