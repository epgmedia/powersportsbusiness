<?php

/**
 * defines Publishthis Tweets widget
 * - setup widget options
 * - output results
 */
class Publishthis_Automated_Tweet_Content_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'pt_automated_tweet_content_widget',
			'PublishThis: Automated Tweet Content',
			array ( 'classname'   => 'automated-tweet-content-widget',
				'description' => 'Display automated tweets content from a PublishThis Mix.' ) );		
	}

	/**
	 *   Display Tweets widget output
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

		$params = array ( 'results' => $instance['num_results']);
    
		if( $instance['mix_defaults'] != '1' ) {
			$params['sort'] = $instance['sort_by'];
		}
		else {
			//$params['sort'] = "most_recent";
		}

    	//$params["mixd"] =  $instance['mix_defaults'];

		// retrieve tweets (API call)
		$content = $publishthis->api->get_tweets_by_feed_id( $instance['feed_id'], $params );

		// pass widget settings to template
		if ( $content ) {
			$GLOBALS['pt_content'] = array(
				'result'             => $content,
				'type'               => 'tweet',
				'columns_count'      => $instance['columns_count'] );
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
	 *   Prepare Tweets widget settings for saving
	 */
	function update( $new_instance, $old_instance ) {
		delete_transient( $this->id_base );
		$instance = $new_instance + $old_instance;
		return $this->sanitize_data_array( $instance );
	}

	/**
	 *   Sanitize data before usage
	 */
	function sanitize_data_array( $data ) {
		$safe_data = array();
		foreach ( $data as $key=>$val ) {
			if ($val != "-1"){
				$safe_data[ $key ] = sanitize_text_field( $val );
			}
		}
		return $safe_data;
	}

	/**
	 *   Init and display Tweets widget setup form
	 */
	function form( $instance ) {
		global $publishthis;

		// set defaults
		$instance = ( array ) $instance +
			array(
			'feed_id'            => '-1',
			'title'              => 'Automated Content - PublishThis',
			'sort_by'            => '-1',
			'mix_defaults'		 => '1',
			'num_results'        => 10,
			'cache_interval'     => 60 );

		// fill available feeds select
		$feeds = $publishthis->api->get_feeds();

		$instance = $this->sanitize_data_array( $instance );

		//define content type to render correspondent settings
		$content_type = "tweet";

		//render settings
		include dirname( __FILE__ ) . "/setup-widget.php";
	}
}
