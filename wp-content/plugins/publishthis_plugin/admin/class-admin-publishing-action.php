<?php

/**
 * defines Publishthis Publishing Action page options
 */

class Publishthis_Admin_Publishing_Action {

	/**
	 *  Publishthis_Admin_Publishing_Action constructor.
	 */
	function __construct() {
		global $publishthis;

		// Edit screen
		add_action( 'pre_post_update', array ( $this, 'validate_publish_action' ), 300 );
		add_action( 'add_meta_boxes', array ( $this, 'add_meta_box' ) );
		add_action( 'save_post', array ( $this, 'save_publish_action' ), 300, 2 );

		// Init Popups
		add_action( 'admin_init', array ( $this, 'init_popups' ) );
	}

	/**
	 *   Bind options fields for Publishing Actions edit page. Specify template with options fields
	 */
	function add_meta_box() {
		global $publishthis;

		add_meta_box( 'publishthis-options-box', 'Options', array( $this, 'display_meta_box_options' ), $publishthis->post_type, 'normal', 'default', null );
		add_meta_box( 'publishthis-excerpts-box', 'Excerpt Options', array( $this, 'display_meta_box_excerpts' ), $publishthis->post_type, 'normal', 'default', null );
		add_meta_box( 'publishthis-layout-box', 'Layout Options', array( $this, 'display_meta_box_layout' ), $publishthis->post_type, 'normal', 'default', null );
	}

	/**
	 * Render Options fields
	 *
	 * @param unknown $post Publishing Action object
	 *
	 */
	function display_meta_box_options( $post ) {
		global $publishthis;

    try{
			//set form default values
			$metabox = array(
				'_publishthis_poll_interval' => '300', // Poll interval
				'_publishthis_publish_author' => '', // Publish Author name
				'_publishthis_feed_template' => 0, // Feed Template ID
				'_publishthis_template_section' => 0, // Template section
				'_publishthis_content_type' => 'post', // Content type
				'_publishthis_content_type_wp_template' => 'default', // Content type WP template
				'_publishthis_content_type_format' => 'individual', // Content type format
				'_publishthis_combined_layout' => 'defaultdigest', // the template to be used for the combined digest
				'_publishthis_content_status' => 'draft', // Content status
				'_publishthis_featured_image' => '0', // Featured image
				'_publishthis_featured_image_size' => 'theme_default', // Featured image size
				'_publishthis_featured_image_width' => 300, // Custom image width
				'_publishthis_featured_image_height' => 300, // Custom image height
				'_publishthis_html_body_image' => '1', // Post html body image
				'_publishthis_ok_override_fimage_size' => '1', // ok to resize the image and ignore the original images size
				'_publishthis_up_to_max_width' => '0',
				'_publishthis_featured_max_image_width' => '300', // setting the maximum image width
				'_publishthis_category' => '0', // Category
				'_publishthis_synchronize' => '0', // Synchronize content for digest
				'_publishthis_individual_insert' => '1', // Add Posts from new content ( for individuals )
				'_publishthis_individual_update' => '1', // Modified content in PublishThis updates Posts ( for individuals )
				'_publishthis_individual_delete' => '0', // Delete Posts when deleted in PublishThis ( for individuals )
				'_publishthis_taxonomy' => 'category', // Taxonomy
				'_publishthis_tags' => '0', // Add Tags to Content
			);
	
			// Feed templates
			$metabox['feed_templates'] = $publishthis->api->get_feed_templates();
	
			// Content Types
			$metabox['content_types'] = array_merge(
				array( 'post' => 'Posts', 'page' => 'Pages' ),
				$this->get_custom_post_types()
			);

			// Content Types WP Templates
			$metabox['content_type_wp_templates'] = $this->get_content_type_wp_templates();
	
			$metabox['combined_layouts'] = array_merge(
				$publishthis->get_digest_templates()
			);
			
	
			$metabox = $this->set_saved_values( $post->ID, $metabox );
	
			include 'templates/meta-box-options.php';
	
			$this->clean_temp_data( array_keys( $metabox ) );
		}catch(Exception $ex){
			$publishthis->log->add ( "display_meta_box_options:" . $ex->getMessage () );	
		}
	}

	/**
	 * Render Excerpts Options fields
	 *
	 * @param unknown $post Publishing Action object
	 *
	 */
	function display_meta_box_excerpts( $post ) {
		global $publishthis;

    try{
			//set form default values
			$metabox = array(
			'_publishthis_create_excerpt' => '0', // include the annotation in the excerpt: 0 - no, 1 - yes
			'_publishthis_manual_excerpt' => '0', // allow for an excerpt to be created manually? 0 - no, 1 - yes
			'_publishthis_excerpt_first_item' => '1', // for Digests, Excerpt Only First Curated Item: 0 - no, 1 - yes
			'_publishthis_excerpt_more_tag' => '0' // for Individuals if the option is yes, we need to insert the <!-- more --> tag
			);
	
			$metabox = $this->set_saved_values( $post->ID, $metabox );
	
			include 'templates/meta-box-excerpts.php';
	
			$this->clean_temp_data( array_keys( $metabox ) );
		}catch(Exception $ex){
			$publishthis->log->add ( "display_meta_box_excerpts:" . $ex->getMessage () );	
		}
	}

	/**
	 * Render Layout Options fields
	 *
	 * @param unknown $post Publishing Action object
	 *
	 */
	function display_meta_box_layout( $post ) {
		global $publishthis;

    try{
			$_publishthis_image_alignment = get_post_meta( $post->ID, '_publishthis_image_alignment', true );
			$imageAlignment = 'left';
			if( isset( $_publishthis_image_alignment ) ) {
				switch( $_publishthis_image_alignment ) {
					case '0': $imageAlignment = 'default'; break;
					case '1': $imageAlignment = 'center'; break;
					case '2': $imageAlignment = 'left'; break;
					case '3': $imageAlignment = 'right'; break;
					default: break;
				}
			}
	
			$_publishthis_ok_resize_preview = get_post_meta( $post->ID, '_publishthis_ok_resize_preview', true );
			$imageResizePreview = isset( $_publishthis_ok_resize_preview ) ? intval($_publishthis_ok_resize_preview) : '1';
	
			$_publishthis_max_image_width = get_post_meta( $post->ID, '_publishthis_max_image_width', true );
			$imageMaxWidth = '300';
			$size = 'default';
			if( !empty( $_publishthis_max_image_width ) ) {
				$size = 'custom_max';
				$imageMaxWidth = $_publishthis_max_image_width;
			}
			
			//set form default values
			$metabox = array(
				'_publishthis_content_type_format' => 'individual', // Content type format
				'_publishthis_combined_layout' => 'default', // the template to be used for the combined digest
				'_publishthis_html_body_image' => '1',
				'_publishthis_ok_override_fimage_size' => '0', //ok to esize the featured image, and ignore the original images size
				'_publishthis_read_more' => 'Read More', // Read More link label
				'_publishthis_annotation_placement' => '0', // set the annotation placement: 0 - Above, 1 - Below
	
				'_publishthis_layout_image' => '1',
				'_publishthis_layout_image_custom_styles' => '{"imageLayoutSettings":{"aligment":"'.$imageAlignment.'","override_custom_images":"0","ok_resize_previews":"'.$imageResizePreview.'","size":"'.$size.'","width":"0","height":"0","max_width":"'.$imageMaxWidth.'","use_caption_shortcode":"1"}}',
	
				'_publishthis_layout_title' => '1',
				'_publishthis_layout_title_custom_styles' => '{"titleLayoutSettings":{"clickable":"1","nofollow":"1"}}',
				
				'_publishthis_layout_summary' => '1',
				
				'_publishthis_layout_publishdate' => '0',
				
				'_publishthis_layout_readmore' => '1',
				'_publishthis_layout_readmore_custom_styles' => '{"readmoreLayoutSettings":{"newwindow":"1", "publisher":"1", "read_more":"Read More", "nofollow":"1"}}',
	
				'_publishthis_layout_annotation' => '1',
				'_publishthis_layout_annotation_custom_styles' => '{"annotationLayoutSettings":{"annotation_title_text":"Our Take","annotation_placement":"0", "annotation_title_alignment":{"vertical":"top","horizontal":"left"}}}',
	
				'_publishthis_layout_embed' => '1',
				'_publishthis_layout_embed_custom_styles' => '{"embedLayoutSettings":{"size":"default","width":"0","height":"0","max_width":"0"}}'
			);
	
			$metabox = $this->set_saved_values( $post->ID, $metabox );
	
			include 'templates/meta-box-layout.php';
	
			$this->clean_temp_data( array_keys( $metabox ) );
		}catch(Exception $ex){
			$publishthis->log->add ( "display_meta_box_layout:" . $ex->getMessage () );	
		}
	}

	/**
	 * Validate Publishing Action before saving
	 *
	 * @param unknown $post_id Publishing Action ID
	 *
	 */
	function validate_publish_action( $post_id ) {
		global $publishthis;

		try{				
			//do actions only for edit
			if ( !isset( $_POST['action'] ) || isset( $_POST['action'] ) && in_array( $_POST['action'], array( 'delete', 'trash','-1','untrash' ) ) ) {
				return;
			}
		
			$post = get_post( $post_id );
	
			//check for correspondent post type
			if ( ! $post || $post->post_type != $publishthis->post_type ) {
				return;
			}
	
			//validate title
			if ( empty ( $_POST ['post_title'] ) ) {
				$this->_validation_redirect ( $post_id, 3 );
			}
	
			//validate for unique template/section values pair
			$publish_actions = get_posts( array(
					'numberposts'  => 100,
					'post_type'    => $publishthis->post_type,
					'post__not_in' => array( $post_id )
				) );
	
			foreach ( $publish_actions as $action ) {
				$_feed_template = get_post_meta( $action->ID, '_publishthis_feed_template', true );
				$_template_section = get_post_meta( $action->ID, '_publishthis_template_section', true );
	
				if ( $_feed_template == $_POST['publishthis_publish_action']['feed_template'] && $_template_section == $_POST['publishthis_publish_action']['template_section'] ) {
					$this->_validation_redirect ( $post_id, 2 );
				}
			}
		}catch(Exception $ex){
			$publishthis->log->add ( "validate_publish_action:" . $ex->getMessage () );	
		}

	}

	/**
	 * Init popups for options settings
	 */
	function init_popups() {
		add_thickbox();
	}

	/**
	 * Validate and save Publishing Action
	 *
	 * @param unknown $post_id Publishing Action ID
	 * @param unknown $post    Publishing Action data object
	 *
	 */
	function save_publish_action( $post_id, $post ) {
		global $publishthis;

		//check if action can be saved
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_type != $publishthis->post_type || ! current_user_can( 'manage_options', $post_id ) || empty ( $_POST['publishthis_publish_action'] ) ) {
			return;
		}

		//validate form. Title should be mandatory
		$errors = array();

		if ( empty ( $_POST['post_title'] ) ) {
			$errors['post_title'] = 'Title is required.';
		}

		if ( $errors ) {
			return;
		}

		//allowed fields
		$field_keys = array ( 'poll_interval', 'feed_template', 'template_section', 'content_type', 'content_type_wp_template', 'content_type_format',
			'content_status', 'featured_image_size', 'html_body_image', 'category', 'synchronize', 'featured_image',
			'publish_author', 'read_more', 'annotation_placement', 'excerpt_first_item', 'layout_title_custom_styles',
			'featured_image_width', 'featured_image_height', 'featured_max_image_width', 'create_excerpt', 'taxonomy',
			'layout_embed', 'layout_embed_custom_styles', 'layout_image', 'layout_image_custom_styles','manual_excerpt',
			'layout_title', 'layout_summary', 'layout_publishdate', 'layout_readmore', 'layout_readmore_custom_styles',
			'layout_annotation', 'layout_annotation_custom_styles','ok_override_fimage_size','up_to_max_width',
			'individual_insert', 'individual_update', 'individual_delete', 'tags','combined_layout', 'excerpt_more_tag'); 

		//update allowed fields with value
		foreach ( $field_keys as $field_key ) {
			$meta_key = "_publishthis_{$field_key}";
			update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST['publishthis_publish_action'][$field_key] ) );
		}
		update_post_meta( $post_id, "_publishthis_import_start", time() );

		if( !isset($_POST['dont_redirect']) || $_POST['dont_redirect']==0 ) {
			//on success redirect to Publishing Actions list
			wp_safe_redirect( admin_url( 'edit.php?post_type=publishthis_action' ) );
		}
		else {
			wp_safe_redirect( admin_url( 'post.php?post='.$post_id.'&action=edit' ) );
		}
		
		exit;
	}

	/**
	 * Publishing Actions edit page needs some extra info
	 */
	function get_extra_info() {
		global $publishthis;

    try{
			$feed_templates = $publishthis->api->get_feed_templates();
	
			$fields = $sections = array ();
			if ( is_array( $feed_templates ) ) {
				foreach ( $feed_templates as $template ) {
					$sections[$template->templateId] = $template->templateSections;
					$fields[$template->templateId] = $template->templateFields;
				}
			}
	
			return array (
				'templateFields' => $fields,
				'templateSections' => $sections,
				'taxonomies' => $this->get_post_type_taxonomies()
	
			);
		}catch(Exception $ex){
			$publishthis->log->add ( "get_extra_info:" . $ex->getMessage () );	
		}
	}

	/**
	 * Rewrites form values with entered values
	 */
	private function set_saved_values( $post_id, $options ) {
		$meta = get_post_meta( $post_id );

		//rewrite form values with entered values
		foreach ( $options as $key => $value ) {
			if ( isset ( $meta [$key] [0] ) && !isset( $_REQUEST['publishthis_validation_message'] ) ) {
				$options[$key] = $meta[$key] [0];
			}
			else if ( isset ( $_SESSION ['publishthis_publish_action'] [$key] ) ) {
					$options[$key] = stripslashes( $_SESSION ['publishthis_publish_action'] [$key] );
				}
		}
		return $options;
	}

	/**
	 * Clean temporary saved values
	 */
	private function clean_temp_data( $keys ) {
		foreach ( $keys as $key ) {
			if ( isset ( $_SESSION ['publishthis_publish_action'] [$key] ) ) {
				unset( $_SESSION['publishthis_publish_action'] [$key] );
			}
		}
	}

	/**
	 * Redirect if validation doesn't passed
	 *
	 * @param unknown $post_id    Publishing Action ID
	 * @param unknown $message_id Message index from display_alerts()
	 *
	 */
	private function _validation_redirect( $post_id, $message_id ) {
		global $publishthis;
		
		//save entered data to the user session
		$_SESSION['publishthis_publish_action'] = array();
		try{
			foreach ( $_POST["publishthis_publish_action"] as $key => $value ) {
				$_SESSION['publishthis_publish_action']["_publishthis_" . sanitize_text_field( $key )] = sanitize_text_field( $value );
			}
		}catch( Exception $ex ) {
			if (isset($publishthis)){
				$publishthis->log->add ( $ex->getMessage () );	
		  }
		}

		
		//get location
		$location = add_query_arg( 'publishthis_validation_message', $message_id, get_edit_post_link( $post_id, 'url' ) );
		$location = add_query_arg( 'post_type', $publishthis->post_type, $location );
	
		//process redirect
		wp_safe_redirect( $location );
		exit();
	}

	/**
	 * Returns Custom Post Type WP Templates
	 */
	private function get_content_type_wp_templates() {
		$templates = wp_get_theme()->get_page_templates();
		$templates = array_merge( array( 'default' => "Default Template" ) , $templates);
		
		return $templates;
	}

	/**
	 * Returns Custom Post Types
	 */
	private function get_custom_post_types() {
		$custom_post_types = array();

		//set search criteria
		$args = array(
			'public'   => true,
			'_builtin' => false
		);

		//set output data format: names or objects
		$output = 'objects';

		//set search criteria rule: 'and' or 'or'
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator );

		foreach ( $post_types as $key => $value ) {
			$custom_post_types[ $key ] = $value->label;
		}
		return $custom_post_types;
	}

	/**
	 * Returns Taxonomies for Post Type
	 */
	private function get_post_type_taxonomies() {
		$taxonomies = array();

		//set search criteria
		$args = array(
			'public'   => true,
			'_builtin' => false

		);

		//set output data format: names or objects
		$output = 'objects';

		//set search criteria rule: 'and' or 'or'
		$operator = 'and';

		$all_taxonomies = get_taxonomies( $args, $output, $operator );

		foreach ( $all_taxonomies as $key => $value ) {
			foreach ( $value->object_type as $post_type_key => $post_type ) {
				$taxonomies[ $post_type ][ $key ] = $value->label;
			}
		}
		return $taxonomies;
	}
}
