<?php

/**
 * defines Publishthis Feeds widget
 * - setup widget options
 * - output results
 */
class Publishthis_Curated_Feed_Content_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'pt_curated_feed_content_widget',
			'PublishThis: Curated Content',
			array ( 'classname'   => 'curated-feed-content-widget',
				'description' => 'Display curated news/video content from a PublishThis Mix.' ) );
		// define ajax call to get feeds
		add_action( 'wp_ajax_get_publishthis_curatedfeeds', array( $this, 'get_curatedfeeds_json' ) );
	}

	/**
	 *   Display Feeds widget output
	 */
	function widget( $args, $instance ) {
		global $publishthis;

		// sanitize data
		$instance = $this->sanitize_data_array( $instance );

		// check that feed id passed
		if ( $instance['feed_id'] === '-1' )
			return;

		// check for cached content only if caching enabled
		$html = get_transient( $this->id );
		if ( $html !== false ) {
			echo $html;
			return;
		}

		// generate output
		ob_start();

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'] ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$params = array ( 
							'results' => $instance['num_results'] 
						);

		// retrieve feeds (API call)
		$content = $publishthis->api->get_paged_curated_feed_content_by_id( $instance['feed_id'], $params );

		// pass widget settings to template
		if ( $content ) {
			$GLOBALS['pt_content'] = array(
				'result'             => $content,
				'type'               => 'feed',
				'feedId'             => $instance['feed_id'],
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
			$publishthis->load_template( 'automated-content.php' );
			unset( $GLOBALS['pt_content'] );
		}

		echo $args['after_widget'];

		$html = ob_get_clean();
		set_transient( $this->id, $html, $instance['cache_interval'] );

		// render output
		echo $html;
	}

	/**
	 *   Prepare Feeds widget settings for saving
	 */
	function update( $new_instance, $old_instance ) {
		delete_transient( $this->id );
		$instance = $new_instance + $old_instance;
		return $this->sanitize_data_array( $instance );
	}

	/**
	 *   Retrieve feed data
	 */
	function get_curatedfeeds() {
		global $publishthis;

		// Set defaults
		$instance = array();
		$instance = (array)$instance + array(
			'feed_id'            => '-1',
			'title'              => 'Curated Content - PublishThis',
			'num_results'        => 10,
			'show_links'         => '1',
			'show_photos'        => '1',
			'show_source'        => '1',
			'show_summary'       => '1',
			'show_date'          => '0',
			'show_nofollow'      => '0',
			'max_width_images'   => '300',
			'ok_resize_previews' => '1',
			'cache_interval' => 60,
		);

		// Get feeds (API call)
		$feeds = $publishthis->api->get_feeds();
		return $feeds;
	}

	/**
	 *   Retrieve feed data for AJAX
	 *
	 * @return JSON object
	 */
	function get_curatedfeeds_json() {
		global $publishthis;

		// Get feeds (API call)
		$feeds = $this->get_feeds();

		if ( !$feeds ) {
			$json = array(
				'message' => 'No Mixes found',
				'status' => 'error',
			);
			echo json_encode( $json );
			exit;
		}

		// Return feeds with success message
		$json = array(
			'feeds' => $feeds,
			'status' => 'success',
		);
		echo json_encode( $json );
		exit;
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
	 *   Init and display Feeds widget setup form
	 */
	function form( $instance ) {
		global $publishthis;

		// set defaults
		$instance = ( array ) $instance +
			array(
			'feed_id'            => '-1',
			'title'              => 'Curated Content - PublishThis',
			'num_results'        => 10,
			'show_links'         => '1',
			'show_photos'        => '1',
			'show_source'        => '1',
			'show_summary'       => '1',
			'show_date'          => '0',
			'show_nofollow'      => '0',
			'max_width_images'   => '300',
			'image_width'		 => '0',
			'image_height'		 => '0',
			'image_size'		 => 'default',
			'image_align'		 => 'left',
			'ok_resize_previews' => '1',
			'cache_interval'     => 60 );

		// fill available feeds select
		$feeds = $this->get_curatedfeeds();

		$instance = $this->sanitize_data_array( $instance );

		//define content type to render correspondent settings
		$content_type = "curatedfeed";

		//render settings
		include dirname( __FILE__ ) . "/setup-widget.php";
	}
}
