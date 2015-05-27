<?php
/*
Plugin Name: PublishThis Curation
Plugin URI: http://publishthis.com
Description: PublishThis plugin that creates pages/posts from curated content as well as widgets for automated content.
Version: 1.0.15.1430424822
Author: PublishThis
Author URI: http://www.publishthis.com
License: ...
Copyright: ...
*/

if ( ! defined( 'ABSPATH' ) )
	exit();

//contains widgets and shortcodes settings data and used to render popups
require_once untrailingslashit( dirname( __FILE__ ) ) . '/publishthis-settings.php';

class Publishthis {

	var $option_name = 'publishthis_options';

	var $settings_section = 'publishthis_settings_section';
	var $settings_token_section = 'publishthis_settings_token_section';
	
	var $styles_section = 'publishthis_styles_section';

	var $post_type = 'publishthis_action';
	var $version = '1.0.15.1430424822';

	var $digestTemplateOptions = array( 'defaultdigest' => 'Default', 'firstprimary' => 'First Item Featured' );

	var $admin;
	var $api;
	var $cron;
	var $log;
	var $utils;
	var $publish;
	var $endpoint;
	
	private $_options;
	private $_plugin_path;
	private $_plugin_url;
	private $_manager_api_url = MANAGER_API_URL;

	/**
	 *   Publishthis constructor.
	 */
	function __construct() {
		// Activation
		register_activation_hook( __FILE__, array ( $this, 'activate' ) );

		// Deactivation
		register_deactivation_hook( __FILE__, array ( $this, 'deactivate' ) );

		// Actions
		add_action( 'init', array ( $this, 'register_post_type' ), 0 );
		add_action( 'init', array ( $this, 'init_sub_classes' ), 0 );
		add_action( 'widgets_init', array ( $this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_assets' ), 0 );
		add_action( 'add_meta_boxes', array ( $this, 'remove_unwanted_metaboxes' ) );
	
		// define ajax call to validate token
		add_action( 'wp_ajax_validate_publishthis_token', array ( $this, 'validate_token_json' ) );

		// define clear cache ajax
		add_action( 'wp_ajax_clear_pt_caches', array ( $this, 'clear_caches' ) );

		// define ajax call to process publishing action preview
		add_action( 'wp_ajax_publishing_action_preview', array ( $this, 'publishing_action_preview' ) );

		//manager api ajax calls
		add_action( 'wp_ajax_publishthis_clients_list', array ( $this, 'get_clients_list' ) );
		add_action( 'wp_ajax_publishthis_select_client', array ( $this, 'get_client_token' ) );
		add_action( 'wp_ajax_publishthis_client_token', array ( $this, 'get_client_token_for_single_client' ) );
	}

	/**
	 * Ajax call to validate API token
	 */
	function validate_token_json() {
		//check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_settings_nonce' ) ) {
			echo json_encode( array( 'valid' => false, 'message' => 'Access deny' ) );
			exit();
		}

		//check token works
		$status = $this->api->validate_token( sanitize_text_field( $_POST['token'] ) );

		echo json_encode( $status );
		die();
	}

	/**
	 * Ajax call to clear PT caches
	 */
	function clear_caches() {
		global $wpdb;

		try {
			update_option( "pt_simulated_cron", 0 );
			$is_deleted = $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient%_pt_%' ");
			die( json_encode( true ) );
		} catch( Exception $ex ) {
			die( json_encode( false ) );
		}
		die();
	}

	/**
	 * Ajax call to process publishing action preview
	 */
	function publishing_action_preview() {
		//check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_publishing_action_nonce' ) ) {
			echo json_encode( array( 'error' => 'Access deny' ) );
			exit();
		}

		session_start();
		$_SESSION['process_preview'] = true;
		$_SESSION['process_preview_data_' . $_POST['_ajax_nonce']] = array(
			'styles' => $_POST['styles'],
			'content_type_format' => $_POST['content_type_format'],
			'annotation_placement' => $_POST['annotation_placement'],
			'annotation_title_text' => $_POST['annotation_title_text'],
			'readmore' => $_POST['readmore']
		);

		echo json_encode( array( 'success' => $_SESSION['process_preview'], 'post_url' => site_url('/?p=-1&_wpnonce='.$_POST['_ajax_nonce']) )  );
		exit();
	}

	/**
	 * Manager API: get clients list by username and password
	 */
	function get_clients_list() {
		//check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_clients_list_nonce' ) ) {
			echo json_encode( array( 'errorCode' => 'AccessDeny' ) );
			exit();
		}

		$url = $this->_manager_api_url . 'login';
		$args = array(
			'method' => 'POST',
			'timeout' => 10,
			'sslverify' => false,
			'headers' => array( 'content-type' => 'application/json;charset=UTF-8' ),
			'body' => '{"email": "'.sanitize_text_field( $_POST['email'] ).'","password":"'.sanitize_text_field( $_POST['password'] ).'","role":"'.sanitize_text_field( $_POST['role'] ).'"}'
		);
		$response = wp_remote_post( $url, $args );

		if ( !is_wp_error( $response ) ) {
			echo $response['body'];
		}
		else {
			echo json_encode( array( 'errorCode' => 'Unknown error' ) );
			exit();
		}

		die();
	}

	/**
	 * Manager API: get client token by client id
	 */
	function get_client_token() {
		//check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_select_client_nonce' ) ) {
			echo json_encode( array( 'errorCode' => 'AccessDeny' ) );
			exit();
		}

		$url = $this->_manager_api_url . 'loginAs;jsessionid=' . sanitize_text_field( $_POST['sessionid'] );
		$args = array(
			'method' => 'POST',
			'timeout' => 10,
			'sslverify' => false,
			'headers' => array( 'content-type' => 'application/json;charset=UTF-8' ),
			'body' => '{"clientId":"'.sanitize_text_field( $_POST['clientId'] ).'"}'
		);
		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			echo json_encode( array( 'tokenId' => $token ) );
			exit;
		}

		$login_result = json_decode( $response['body'] );

		if ( isset( $login_result ) && isset( $login_result->data ) && isset( $login_result->data->status ) && $login_result->data->status=='success' ) {
			echo json_encode( $this->get_token( $login_result->sessionId ) );
			exit();
		}
		echo json_encode( array( 'tokenId' => '' ) );
		die();
	}

	/**
	 * Manager API: get client token for single client
	 */
	function get_client_token_for_single_client() {
		$token = '';
		//check user access and ajax nonce
		if ( !current_user_can( 'manage_options' ) || !check_ajax_referer( 'publishthis_admin_client_token_nonce' ) ) {
			echo json_encode( array( 'errorCode' => 'AccessDeny' ) );
			exit();
		}

		echo json_encode( $this->get_token( $_POST['sessionid'] ) );
		die();
	}

	/**
	 * Manager API: get client token using sessionid
	 */
	private function get_token( $sessionId ) {
		$url = $this->_manager_api_url . 'getTokenId;jsessionid=' . sanitize_text_field( $sessionId );
		$args = array(
			'method' => 'GET',
			'timeout' => 10,
			'sslverify' => false,
			'headers' => array( 'content-type' => 'application/json;charset=UTF-8' )
		);
		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return array( 'tokenId' => '' );
		}

		$token_result = json_decode( $response['body'] );
		$token = $token_result->tokenId;

		return array( 'tokenId' => $token );
	}

	/**
	 *   Plugin activation
	 */
	function activate() {
		//invalidate the cache for client info
		delete_option( 'pt_client_info' );
		delete_transient( 'pt_admin_client_info' );

		require_once $this->plugin_path() . '/classes/class-log.php';
		$log = new Publishthis_Log();
		$message = array(
			'message' => 'PublishThis Plugin activated',
			'status' => 'warn',
			'details' => ''
		);
		$log->addWithLevel($message, "2");

		$client_plugin_id = md5( uniqid( mt_rand(), true ) );

		if ( $options = get_option( $this->option_name ) ) {
			$advanced_options = array();
			//set client id for old installations
			if ( !isset( $options['client_plugin_id'] ) ) {
				$advanced_options['client_plugin_id'] = $client_plugin_id;
			}

			if ( $options['curatedby_logos'] == '4' ){
			  $options['curatedby_logos'] = '5';
                        }

			//set curated_publish for old installations
			if ( isset( $options['simulate_cron'] ) && !isset( $options['curated_publish'] ) ) {
				$advanced_options['curated_publish'] = $options['simulate_cron']=="1" ? 'import_without_cron' : 'import_with_cron';
				unset($options['simulate_cron']);
			}	
			$options = array_merge( $options, $advanced_options );

			require_once $this->plugin_path() . '/classes/class-cron.php';
			$cron = new Publishthis_Cron();
			//setup wp cron
			if( $options['curated_publish'] == 'import_with_cron' ) {
				$cron->pt_add();
			}
			else {
				$cron->pt_remove();
			}
	
			update_option( $this->option_name, $options );
		}else{

			add_option( $this->option_name,
				array(
					'client_plugin_id' => $client_plugin_id,
					'api_token'        => '',
					'api_version'      => '3.0',
					'debug'            => '1',
					'pause_polling'    => '0',
					'curated_publish'  => 'import_from_manager',
					'styling'          => '1' )
			);
		}
		
		
		/* we could be doing an upgrade to our 4.0 plugin. we need to loop through
		  the posts and assign a curate date to any individual posts that have been created
		  before so that future updates/mods to these docs can be done */
		$pagingOffset = 0;
		$pagingNumberOfPages = 5;
		
		$findPTPostsArgs = array(
			'meta_query' => array(
				array(
					'key' => '_publishthis_raw'
				)
			),
			'posts_per_page'   => $pagingNumberOfPages,
			'offset' => $pagingOffset,
			'post_status' => 'new,publish,pending,draft,auto-draft,future,private,trash',
			'fields' => 'ids'
		);
		 
		$trackingCounterPosts = 0;
		$trackingCounterPostsUpdated = 0;
		
		 
		while(count($arrPosts = get_posts( $findPTPostsArgs )) > 0){
			
			//loop the array and see if we need to set the curate date value
			
			foreach ( $arrPosts as $postItem ) {
		  	$tempPostId = $postItem->ID;
		  	$postLastUpdateDateValue = get_post_meta( $tempPostId, '_publishthis_doc_last_update_date', true );
				$postRawData = get_post_meta( $tempPostId, '_publishthis_raw', true );
				
				if (empty($postLastUpdateDateValue) && !empty($postRawData)){
						if (isset($postRawData->curateUpdateDate)){
							if (!empty($postRawData->curateUpdateDate)){
								//ok, we found a post that needs to have its last curate date set
								//this will be used for any future "update" modifications that can happen to
								//a publishthis content feed
								add_post_meta( $tempPostId, '_publishthis_doc_last_update_date', $postRawData->curateUpdateDate,true );
								$trackingCounterPostsUpdated++;	
							}
						}
		  	}
		  	
		  	$trackingCounterPosts++;
		  }
			
			//now increment so we can get the next set of pages
			$pagingOffset += $pagingNumberOfPages;
			$findPTPostsArgs = array(
				'meta_query' => array(
					array(
						'key' => '_publishthis_raw'
					)
				),
				'posts_per_page'   => $pagingNumberOfPages,
				'offset' => $pagingOffset,
				'post_status' => 'new,publish,pending,draft,auto-draft,future,private,trash',
				'fields' => 'ids'
			);
		}
		
		
		$log->add("on activate, found " . $trackingCounterPosts . " posts to look to see if they need publishthis updates.");
		$log->add("on activate, updated " . $trackingCounterPostsUpdated . " with curate update date");
		
	}

	/**
	 *   Plugin dectivation: for future implementations
	 */
	function deactivate() {
		require_once $this->plugin_path() . '/classes/class-log.php';
		$log = new Publishthis_Log();
		$message = array(
			'message' => 'PublishThis Plugin deactivated',
			'status' => 'warn',
			'details' => ''
		);
		$log->addWithLevel($message, "2");
			
		require_once $this->plugin_path() . '/classes/class-cron.php';
		$cron = new Publishthis_Cron();
		$cron->pt_remove();
	}

	/**
	 *   Remove the platinum seo box
	 */
	function remove_unwanted_metaboxes() {
		remove_meta_box( 'postpsp', $this->post_type, 'advanced' );
	}

	/**
	 *   Bring in CSS.
	 */
	function enqueue_assets() {
		wp_enqueue_style( 'publishthis-content-all', $this->plugin_url () . '/assets/css/content-on.css' );

		wp_enqueue_script( 'publishthis-content-js', $this->plugin_url () . '/assets/js/content.js', array ( 'jquery' ), $this->version );
		wp_enqueue_script( 'publishthis-masonry', $this->plugin_url () . '/assets/js/lib/jquery.masonry.min.js', array (), $this->version );

		//Add Tweeter widget js
		wp_enqueue_script( 'publishthis-tweet', 'http://platform.twitter.com/widgets.js', array (), $this->version, true );
		
		if ( ! $this->get_option( 'styling' ) ) {
			return;
		}

		wp_enqueue_style( 'publishthis-widgets', $this->plugin_url () . '/assets/css/widgets.css' );
		wp_enqueue_style( 'publishthis-content', $this->plugin_url () . '/assets/css/content.css' );
	}

	/**
	 *   Init Publishthis sub classes.
	 */
	function init_sub_classes() {
		// Admin functions
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			require $this->plugin_path() . '/admin/class-admin.php';
			$this->admin = new Publishthis_Admin();
		}

		// API functions
		require $this->plugin_path() . '/classes/common/class-api-common.php';
		require $this->plugin_path() . '/classes/class-api.php';
		$this->api = new Publishthis_API();
			
		// Cron functions
		require $this->plugin_path() . '/classes/class-cron.php';
		$this->cron = new Publishthis_Cron();

		// Logging functions
		require $this->plugin_path() . '/classes/class-log.php';
		$this->log = new Publishthis_Log();

		// Utils functions
		require $this->plugin_path() . '/classes/common/class-utils-common.php';
		require $this->plugin_path() . '/classes/class-utils.php';
		$this->utils = new Publishthis_Utils();
		
		// Utils functions
		require $this->plugin_path() . '/classes/class-publish.php';
		$this->publish = new Publishthis_Publish();
			
		// Endpoint functions
		require $this->plugin_path() . '/classes/class-endpoint.php';
		$this->endpoint = new Publishthis_Endpoint();

		global $client_info;
		//get client info for every admin page load
		if ( is_admin() ) {
			if ( false === $client_info = get_transient('pt_admin_client_info') ) {
				$client_info = $info = $this->api->get_client_info();
				if( isset( $info ) ) {
					set_transient( 'pt_admin_client_info', $info, 2*60 );	
				} 
				else {
					delete_transient( 'pt_admin_client_info' );
				}
				
			}
		}
		else {
			if ( false === $client_info = get_option('pt_client_info') ) {
				$client_info = $info = $this->api->get_client_info();
				if( isset( $info ) ) {
					update_option('pt_client_info', $info );
				}
				else {
					delete_option('pt_client_info');
				}
			}
		}	
	}


	/**
	 *   Register Publishthis api actions post type on 'init'.
	 */
	function register_post_type() {
		register_post_type( $this->post_type,
			array ( 'labels' => array (
					'name' => __ ( 'Publishing Actions', 'publishthis' ),
					'singular_name' => __ ( 'Publishing Action', 'publishthis' ),
					'add_new' => __ ( 'Add New Publishing Action', 'publishthis' ),
					'all_items' => __ ( 'All Publishing Actions', 'publishthis' ),
					'add_new_item' => __ ( 'Add New Publishing Action', 'publishthis' ),
					'edit_item' => __ ( 'Edit Publishing Action', 'publishthis' ),
					'new_item' => __ ( 'New Publishing Action', 'publishthis' ),
					'view_item' => __ ( 'View Publishing Action', 'publishthis' ),
					'search_items' => __ ( 'Search Publishing Actions', 'publishthis' ),
					'not_found' => __ ( 'No Publishing Actions', 'publishthis' ),
					'not_found_in_trash' => __ ( 'No Publishing Actions found in Trash', 'publishthis' ),
					'menu_name' => __ ( 'Publishing Actions', 'publishthis' ) ),
				'capability_type' => 'post',
				'exclude_from_search' => true,
				'has_archive' => false,
				'hierarchical' => false,
				'public' => false,
				'publicly_queryable' => false,
				'show_in_admin_bar' => false,
				'show_in_menu' => false,
				'show_in_nav_menus' => false,
				'show_ui' => true,
				'supports' => array ( 'title' ) ) );
	}

	/**
	 *   Loads and registers widgets.
	 */
	function register_widgets() {
		require $this->plugin_path() . '/widgets/widget-automated-feed-content.php';
		register_widget( 'Publishthis_Automated_Feed_Content_Widget' );

		require $this->plugin_path() . '/widgets/widget-automated-saved-search-content.php';
		register_widget( 'Publishthis_Automated_Saved_Search_Content_Widget' );

		require $this->plugin_path() . '/widgets/widget-topic-content.php';
		register_widget( 'Publishthis_Topic_Content_Widget' );

		require $this->plugin_path() . '/widgets/widget-automated-tweet-content.php';
		register_widget( 'Publishthis_Automated_Tweet_Content_Widget' );
		
		require $this->plugin_path() . '/widgets/widget-curated-feed-content.php';
		register_widget( 'Publishthis_Curated_Feed_Content_Widget' );
		
	}

	/*
	 * --- Helper methods ----------
	 */

	/**
	 *   Loads a template.
	 */
	function load_template( $template ) {
		$located = locate_template( array ( 'publishthis/' . $template ) );
		if ( ! $located ) {
			$located = $this->plugin_path() . '/templates/' . $template;
		}
		include $located;
	}

  function get_digest_templates(){
  	return $this->digestTemplateOptions;	
  	
  }

	/**
	 *   Gets the plugin path.
	 */
	function plugin_path() {
		if ( $this->_plugin_path )
			return $this->_plugin_path;
		return $this->_plugin_path = untrailingslashit( dirname( __FILE__ ) );
	}

	/**
	 *   Gets the plugin url.
	 */
	function plugin_url() {
		if ( $this->_plugin_url )
			return $this->_plugin_url;
		return $this->_plugin_url = plugins_url( '', __FILE__ );
	}

	/**
	 * Allow us to send actions into WordPress on events in our code.
	 * Allows other plugins, or portions of code to respond to PT
	 * Actions
	 */

	function postAction( $strActionName, $args ) {

		try {
			do_action( $strActionName, $args );
		} catch( Exception $ex ) {
			$this->log->add( $ex->getMessage () );
		}
	}


	/*
	 * --- Options ----------
	 */

	/**
	 *   Gets debug level.
	 */
	function debug() {
		return ( $this->get_option( 'debug' ) == "2" ) ? true : false;
	}

	/**
	 *   Gets error level.
	 */
	function error() {
		return ( $this->get_option( 'debug' ) == "1" ) ? true : false;
	}

	/**
	 *   Gets an option value by key
	 *
	 * @param unknown $key Option key
	 * @return Option value
	 */
	function get_option( $key ) {
		if ( isset( $this->_options[$key] ) ) {
			return $this->_options[$key];
		}
		$this->_init_options();
		return isset( $this->_options[$key] ) ? $this->_options[$key] : null;
	}

	/**
	 *   Gets the entire options set.
	 */
	function get_options() {
		if ( isset( $this->_options ) ) {
			return $this->_options;
		}
		$this->_init_options();
		return $this->_options;
	}

	/*
	 * --- Private methods ----------
	 */

	/**
	 *   Init publishthis options array
	 */
	private function _init_options() {
		$defaults = array( 'api_token' => '', 'api_version' => '3.0', 'debug' => '1', 'pause_polling' => '0', 'curatedby' => '1' );
		$options = get_option( $this->option_name );
		if ( ! isset( $options ) || ! is_array( $options ) ) {
			$options = array ();
		}
		$this->_options = $options + $defaults;
	}
}

// Init general handler
$GLOBALS['publishthis'] = new Publishthis();

// Include necessary files

//raw handler is used for putting the twitter card code into a post
require_once $GLOBALS['publishthis']->plugin_path() . '/ptraw-handler.php';

//debug widget is placed on the dashboard so that clients and publishthis
//can ensure that api calls are made and if there are any issues with the plugin
require_once $GLOBALS['publishthis']->plugin_path() . '/publishthis-debug-widget.php';

//shortcodes are used to display automated content in any post from WYSIWYG editor
require_once $GLOBALS['publishthis']->plugin_path() . '/shortcodes/ptshortcodes.php';

// load Publishthis modules
$exclude_list = array( ".", ".." );
$dir_path = $GLOBALS['publishthis']->plugin_path() . '/modules/';
$directories = array_diff( scandir( $dir_path ), $exclude_list );
foreach ( $directories as $dir ) {
	$module_path = $dir_path . $dir;
	if ( is_dir( $module_path ) && file_exists( $module_path . '/index.php' ) ) {
		require_once $module_path . '/index.php';
	}
}