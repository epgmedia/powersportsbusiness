<?php

/**
 * defines Publishthis Topic widget
 * - setup widget options
 * - output results
 */
class Publishthis_Topic_Content_Widget extends WP_Widget {

	function __construct() {
		parent::__construct (
			'pt_topic_content_widget',
			'PublishThis: Topic Content',
			array ( 'classname' => 'topic-content-widget',
				'description' => 'Display topic content from PublishThis.' ) );

		// define ajax call to get topics
		add_action( 'wp_ajax_get_publishthis_topics', array ( $this, 'get_topics' ) );
	}

	/**
	 *   Get topics for ajax call
	 *
	 * @return JSON object
	 */
	function get_topics() {
		global $publishthis;

		// Check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_widgets_nonce' ) ) {
			echo json_encode( array( 'status' => 'access deny' ) );
			exit();
		}

		// Check topic name
		$safe_topic_name = sanitize_text_field( $_GET['topic_name'] );
		$topic_name = ! empty( $safe_topic_name ) ? $safe_topic_name : '';
		if ( ! $topic_name ) {
			$json = array( 'message' => 'Empty topic name', 'status' => 'error' );
			echo json_encode( $json );
			exit();
		}

		// Get topics (API call)
		$topics = $publishthis->api->get_topics( $topic_name );

		if ( ! $topics ) {
			$json = array ( 'message' => 'No topics found', 'status' => 'error' );
			echo json_encode( $json );
			exit();
		}

		// Return topics as json object
		$json = array ( 'topics' => $topics, 'status' => 'success' );
		echo json_encode( $json );
		exit();
	}

	/**
	 *   Display Topics widget output
	 */
	function widget( $args, $instance ) {
		global $publishthis;

		// sanitize data
		$instance = $this->sanitize_data_array( $instance );

		// check for cached content
		$html = get_transient( $this->id );
		if ( $html !== false ) {
			echo $html;
			return;
		}

		// check that topic id passed
		if ( ! $instance['topic'] )
			return;

		// generate output
		ob_start();

		echo $args['before_widget'];

		if ( $title = apply_filters ( 'widget_title', $instance['title'] ) ) {
			echo $args['before_title'] . $title . $args ['after_title'];
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

		// retrieve topics (API call)

		$content = $publishthis->api->get_topic_content_by_id( $instance['topic'], $params );

		// pass widget settings to template
		if ( $content ) {
			$GLOBALS ['pt_content'] = array (
				'result'             => $content,
				'type'               => 'topic',
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
	 *   Prepare Topics widget settings for saving
	 */
	function update( $new_instance, $old_instance ) {
		delete_transient( $this->id );
		$instance = $new_instance + $old_instance;
		return $this->sanitize_data_array( $instance );;
	}

	/**
	 *   Init and display Topics widget setup form
	 */
	function form( $instance ) {
		global $publishthis;

		// set defaults
		$instance = ( array ) $instance +
			array (
			'title'              => 'Topic Content - PublishThis',
			'topic_name'         => '',
			'topic'           => '0',
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

		// fill available topics select
		$topic_options = '';
		if ( defined( 'DOING_AJAX' ) ) {
			$topics = $publishthis->api->get_topics( $instance['topic_name'] );
			if ( $topics ) {
				foreach ( $topics as $topic ) {
					$topic_options .= sprintf( '<option value="%s"%s>%s</option>', $topic->topicId, selected ( $topic->topicId, $instance['topic'], false ), $topic->displayName . " (" . $topic->shortLabel . ")" );
				}
			}
		}

		$instance = $this->sanitize_data_array( $instance );

		//define content type to render correspondent settings
		$content_type = "topic";

		//render settings
		include dirname( __FILE__ ) . "/setup-widget.php";
	}
}
