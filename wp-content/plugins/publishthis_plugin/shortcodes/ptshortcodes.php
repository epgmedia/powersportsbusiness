<?php
/**
 * defines Publishthis Content Shortcodes
 * - setup shortcodes options
 * - output results
 */
class PublishThis_Shortcodes {

	/**
	 *   Returns shortcodes assets base dir
	 *
	 * @return Assets URL
	 */
	static function shortcodes_base_dir() {
		return WP_PLUGIN_URL . '/' . str_replace( array( basename( __FILE__ ), 'shortcodes/' ), "", plugin_basename ( __FILE__ ) );
	}

	/**
	 *   Registers shortcodes buttons styles for editor
	 */
	function pt_buttons_css() {
		wp_register_style ( 'shortcodes-buttons', PublishThis_Shortcodes::shortcodes_base_dir () . 'assets/css/shortcodes-buttons.css', false, '1.0.0' );
		wp_enqueue_style ( 'shortcodes-buttons' );
	}

	/**
	 *   Registers shortcodes buttons for use
	 */
	function register_shortcode_buttons( $buttons ) {
		// Add | to insert a separator between existing buttons and our new one
		array_push( $buttons, "|", "ptfeed_button", "ptsavedsearch_button", "pttopics_button", "pttweets_button", "ptcuratedfeed_button" );
		return $buttons;
	}

	/**
	 *   Filters the tinyMCE buttons and adds our custom buttons
	 */
	function pt_shortcode_buttons() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can ( 'edit_posts' ) && ! current_user_can ( 'edit_pages' ) )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option ( 'rich_editing' ) == 'true' ) {
			// filter the tinyMCE buttons and add our own
			add_filter ( "mce_external_plugins", array ( 'PublishThis_Shortcodes', "add_pt_tinymce_plugin" ) );
			add_filter ( 'mce_buttons', array ( 'PublishThis_Shortcodes', 'register_shortcode_buttons' ) );
			add_filter ( 'mce_before_init', array ( 'PublishThis_Shortcodes', 'add_pt_settings' ) );
		}
	}

	/**
	 *   Add the button to the tinyMCE bar. Link buttons to js events
	 *
	 * @param unknown $plugin_array Array with buttons actions
	 */
	function add_pt_tinymce_plugin( $plugin_array ) {
		$plugin_array ['ptfeed_button'] = PublishThis_Shortcodes::shortcodes_base_dir () . 'assets/js/shortcode-buttons.js';
		$plugin_array ['ptsavedsearch_button'] = PublishThis_Shortcodes::shortcodes_base_dir () . 'assets/js/shortcode-buttons.js';
		$plugin_array ['pttopics_button'] = PublishThis_Shortcodes::shortcodes_base_dir () . 'assets/js/shortcode-buttons.js';
		return $plugin_array;
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
	 *   Compose shortcode output according to shortcode type
	 *
	 * @param array   $atts    Shortcode settings
	 * @param string  $content Input data
	 * @param string  $type    Shortcode type. Possible values: feed, topic, savedsearch. Default: feed
	 * @return string Shortcode output result
	 */
	function getContent( $atts, $content, $type = 'feed' ) {
		global $publishthis;
		
		
		
		
		ob_start();
		$html = '';
		$params = array();

		// get shortcode attributes
		$params ['results'] = $atts ['num_results'];

		if( in_array( $type, array('curatedfeed', 'feed') ) ) {
			$add_advanced = $atts ['mix_defaults']=='1' ? false : true;
		}
		else {
			$add_advanced = true;
		}

		if( $add_advanced ) {
			$params ['sort'] = $atts ['sort_by'];
			if ($type != 'tweet'){
				$params ['removeNearDuplicates'] = ( isset( $atts ['remove_duplicates'] ) && $atts ['remove_duplicates']==1 ) ? "true" : "false";
				$params ['removeNearRelated'] = ( isset( $atts ['remove_related'] ) && $atts ['remove_related']==1 ) ? "true" : "false";
				//$params ['contentTypes'] = $atts ['content_types'];
			}
		}		

		if ('-1' == $params['sort']){
			unset($params['sort']);
		}

		// get main template for layout
		$template_name = 'automated-content.php';

		// retrieve data (API call)
		switch ( $type ) {
		case 'curatedfeed' :
			extract( shortcode_atts ( array ( "sort_by" => "", "num_results" => "", "feed_id" => "" ), $atts ) );
			$result = $publishthis->api->get_paged_curated_feed_content_by_id ( $feed_id, $params );
			break;
			
		case 'feed' :
			extract( shortcode_atts ( array ( "sort_by" => "", "num_results" => "", "feed_id" => "" ), $atts ) );
			$result = $publishthis->api->get_feed_content_by_id ( $feed_id, PublishThis_Shortcodes::sanitize_data_array($params) );
			break;

		case 'topic' :
			extract( shortcode_atts ( array ( "sort_by" => "", "num_results" => "", "topic_id" => "" ), $atts ) );
			$result = $publishthis->api->get_topic_content_by_id ( $topic_id, $params );
			break;

		case 'savedsearch' :
			extract( shortcode_atts ( array ( "sort_by" => "", "num_results" => "", "bundle_id" => "" ), $atts ) );
			$result = $publishthis->api->get_saved_search_content ( array ( $bundle_id ), $params );
			break;

		case 'tweet' :
			extract( shortcode_atts ( array ( "sort_by" => "", "num_results" => "", "feed_id" => "" ), $atts ) );
			$result = $publishthis->api->get_tweets_by_feed_id ( $feed_id, PublishThis_Shortcodes::sanitize_data_array($params) );
			break;
		}

		if ( $result ) {
			$GLOBALS ['pt_content'] = array (
				'result' => $result,
				'title' => $content,
				'type' => $type,
				'columns_count' => ( isset ( $atts ['columns_count'] ) ? $atts ['columns_count'] : 1 ),
				'show_links' => ( isset ( $atts ['show_links'] ) && $atts ['show_links'] == 1 ? true : false ),
				'show_photos' => ( isset ( $atts ['show_photos'] ) && $atts ['show_photos'] == 1 ? true : false ),
				'show_source' => ( isset ( $atts ['show_source'] ) && $atts ['show_source'] == 1 ? true : false ),
				'show_summary' => ( isset ( $atts ['show_summary'] ) && $atts ['show_summary'] == 1 ? true : false ),
				'show_date' => ( isset ( $atts ['show_date'] ) && $atts ['show_date'] == 1 ? true : false ),
				'show_nofollow' => ( isset ( $atts ['show_nofollow'] ) && $atts ['show_nofollow'] == 1 ? true : false ),
				'image_size' => $atts ['image_size'],
				'image_align' => $atts ['image_align'],
				'ok_resize_previews' => ( isset ( $atts ['ok_resize_previews'] ) && $atts ['ok_resize_previews'] == 1 ? true : false ) );
			if( $atts ['image_size']=='custom' ) {
				$GLOBALS ['pt_content']['image_width'] = $atts ['image_width']; 
				$GLOBALS ['pt_content']['image_height'] = $atts ['image_height'];
			}
			else if( $atts ['image_size']=='custom_max' ) {
				$GLOBALS ['pt_content']['max_width_images'] = $atts ['max_width_images'];
			}
			else if( !isset( $atts ['image_size'] ) && isset( $atts['max_width_images'] ) ) {
				$GLOBALS ['pt_content']['image_size'] = 'custom_max';
				$GLOBALS ['pt_content']['max_width_images'] = $atts ['max_width_images'];
			}
			$publishthis->load_template ( $template_name );
			unset ( $GLOBALS ['pt_content'] );
		}
		// get output
		$html = ob_get_clean();

		// apply caching setting
		set_transient ( $type, $html, $atts ['cache_interval'] );
		return $html;
	}

	/**
	 *   Process Curated Feeds Shortcode
	 */
	function ptcuratedfeed( $atts, $content = null ) {
		return PublishThis_Shortcodes::getContent ( $atts, $content, 'curatedfeed' );
	}

	/**
	 *   Process Feeds Shortcode
	 */
	function ptfeed( $atts, $content = null ) {
		return PublishThis_Shortcodes::getContent ( $atts, $content, 'feed' );
	}

	/**
	 *   Process Topics Shortcode
	 */
	function pttopic( $atts, $content = null ) {
		return PublishThis_Shortcodes::getContent ( $atts, $content, 'topic' );
	}

	/**
	 *   Process Saved Searches Shortcode
	 */
	function ptsavedsearch( $atts, $content = null ) {
		return PublishThis_Shortcodes::getContent ( $atts, $content, 'savedsearch' );
	}

	/**
	 *   Process Tweets Shortcode
	 */
	function pttweet( $atts, $content = null ) {
		return PublishThis_Shortcodes::getContent ( $atts, $content, 'tweet' );
	}
}

// init shortcodes for all types
add_shortcode ( 'ptcuratedfeed', array ( 'PublishThis_Shortcodes', 'ptcuratedfeed' ) );
add_shortcode ( 'ptfeed', array ( 'PublishThis_Shortcodes', 'ptfeed' ) );
add_shortcode ( 'pttopic', array ( 'PublishThis_Shortcodes', 'pttopic' ) );
add_shortcode ( 'ptsavedsearch', array ( 'PublishThis_Shortcodes', 'ptsavedsearch' ) );
add_shortcode ( 'pttweet', array ( 'PublishThis_Shortcodes', 'pttweet' ) );

$objShortcode = new PublishThis_Shortcodes();

// init shortcodes buttons css
add_action ( 'admin_enqueue_scripts', array ( $objShortcode, 'pt_buttons_css' ) );

// init process for button control
add_action ( 'init', array ( $objShortcode, 'pt_shortcode_buttons' ) );
?>
