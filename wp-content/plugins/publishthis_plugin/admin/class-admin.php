<?php

/**
 * defines our Publishing Actions Post Types.
 * -- These are used to poll our API and take curated content
 * -- and turn those documents into posts or pages
 * defines our PublishThis Settings
 * -- this defines our API key, debug settings, etc.
 */

class Publishthis_Admin {

	private $_option_group;
	private $_settings_menu_slug;
	private $_styles_menu_slug;
	private $_settings_section = '';
	private $_styles_section = '';
	private $_settings_token_section = '';

	private $_screens;

	private $_class_publishing_action;

	/**
	 *  Publishthis_Admin constructor.
	 */
	function __construct() {
		global $publishthis;

		// Define subclasses and variables
		// Settings Page setup
		require dirname( __FILE__ ) . '/class-admin-settings.php';
		$class_settings = new Publishthis_Admin_Settings();
		$this->_settings_menu_slug = Publishthis_Admin_Settings::$_settings_menu_slug;
		$this->_option_group = Publishthis_Admin_Settings::$_option_group;

		// Publishing Actions Page setup
		require dirname( __FILE__ ) . '/class-admin-publishing-action.php';
		$this->_class_publishing_action = new Publishthis_Admin_Publishing_Action();

		// Styles Options Page setup
		require dirname( __FILE__ ) . '/class-admin-style-options.php';
		$class_styles = new Publishthis_Admin_Style_Options();
		$this->_styles_menu_slug = Publishthis_Admin_Style_Options::$_styles_menu_slug;

		// Get sections name
		$this->_settings_section = $publishthis->settings_section;
		$this->_settings_token_section = $publishthis->settings_token_section;
		$this->_styles_section = $publishthis->styles_section;

		// Init screens
		$this->_screens = array ( $publishthis->post_type, "edit-{$publishthis->post_type}" );

		// Actions
		add_action( 'admin_enqueue_scripts', array ( $this, 'add_help_tabs' ), 20 );
		add_action( 'admin_enqueue_scripts', array ( $this, 'enqueue_assets' ) );
		add_action( 'admin_menu', array ( $this, 'setup_menu' ), 11 );
		add_action( 'admin_notices', array ( $this, 'display_alerts' ), 0 );

		add_filter( 'post_updated_messages', array ( $this, 'add_post_updated_messages' ) );

		// List screen
		add_filter( "manage_{$publishthis->post_type}_posts_columns", array ( $this, 'edit_columns' ) );
		add_filter( 'post_row_actions', array ( $this, 'remove_quick_edit_link' ), 10, 2 );

		// Init session for internal use
		add_action( 'admin_init', array ( $this, 'init_session' ) );
	}

	/**
	 * Init $_SESSION to store form data while saving
	 */
	function init_session() {
		if ( !session_id() ) {
			session_start();
		}
	}

	/**
	 *   Add help tabs
	 */
	function add_help_tabs() {
		global $publishthis, $current_screen;

		if ( !method_exists( $current_screen, 'set_help_sidebar' ) ) {
			return;
		}

		// All pages get the sidebar
		if ( in_array( $current_screen->id, $this->_screens ) ) {
			try {
				$current_screen->set_help_sidebar( '<p><strong>' . __ ( 'For more information:', 'publishthis' ) . '</strong></p>' .
					'<p><a href="http://docs.publishthis.com/" target="_blank">' . __ ( 'PublishThis Education Center', 'publishthis' ) . '</a></p>' );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}
		}

		// Specific page help
		switch ( $current_screen->id ) {
			/*
			 * Settings page help section. Contains detailed info for each setting.
			 */
		case 'publishthis_page_publishthis_settings' :
			try {
				$current_screen->add_help_tab( array (
						'id'      => 'overview',
						'title'   => __ ( 'Overview', 'publishthis' ),
						'content' => '
										<p>The fields on this screen determine the basics of your PublishThis Curation setup.</p>
										<p>Pause Polling toggles whether or not the plugin will poll the PublishThis API for new content. Previously imported content will not be affected.</p>
										<p>API Tokens are what you use to access and use the PublishThis API. They can be found on the "API" tab of your PublishThis dashboard.</p>
										<p>Styling will include the PublishThis CSS file to provide a consistent format.</p>
										<p>Debug enables logging to <code>publishthis/logs/debug.log</code>.</p>' ) );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}
			break;
			/*
			 * Publish Action edit page help section. Contains info about most important fields for this page.
			 */
		case $publishthis->post_type :
			try {
				$current_screen->add_help_tab( array(
						'id'      => 'overview',
						'title'   => __ ( 'Overview', 'publishthis' ),
						'content' => '
										<p>Poll Interval sets how often the plugin will poll for new content.</p>
										<p>Mix Template allows you to choose which mix template the Publish Action will use. Once a Mix Template is chosen, you will be able to select a Template Section.</p>
										<p>*All fields are required.</p>' ) );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}
			break;
			/*
			 * Publish Action list page help section. 3 tabs with general info, available actions for single items and bulk actions.
			 */
		case "edit-{$publishthis->post_type}" :
			try {
				$current_screen->add_help_tab( array(
						'id'      => 'overview',
						'title'   => __ ( 'Overview', 'publishthis' ),
						'content' => '<p>This screen provides access to all of your Publish Actions.</p>' ) );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}

			try {
				$current_screen->add_help_tab( array(
						'id'      => 'available_actions',
						'title'   => __ ( 'Available Actions', 'publishthis' ),
						'content' => '
										<p>Hovering over a row in the Publish Actions list will display action links that allow you to manage your Publish Action. You can perform the following actions:</p>
										<ul>
											<li><strong>Edit</strong> takes you to the editing screen for that Publish Action. You can also reach that screen by clicking on the Publish Action title.</li>
											<li><strong>Trash</strong> removes your Publish Action from this list and places it in the trash, from which you can permanently delete it.</li>
										</ul>' ) );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}

			try {
				$current_screen->add_help_tab( array(
						'id'      => 'bulk_actions',
						'title'   => __ ( 'Bulk Actions', 'publishthis' ),
						'content' => '
										<p>You can also edit or move multiple posts to the trash at once. Select the posts you want to act on using the checkboxes, then select the action you want to take from the Bulk Actions menu and click Apply.</p>' ) );
			} catch ( Exception $ex ) {
				$publishthis->log->add( $ex->getMessage () );
			}
			break;
		default :
			break;
		}
	}


	/**
	 * Generates custom message when Publishing Action saved or updated.
	 *
	 * @param string  $messages
	 *
	 * @return string
	 */
	function add_post_updated_messages( $messages ) {
		global $publishthis;

		$custom_message = array( $publishthis->post_type => array( 1 => __ ( 'Publishing Action updated.', 'publishthis' ) ) );

		if( is_array( $messages ) && !empty( $messages ) ) {
			return $messages + $custom_message;
		}
		else {
			return $custom_message;
		}		
	}

	/**
	 *   Display alerts for Settings page - validation errors and updated message
	 */
	function display_alerts() {

		//check if there are some errors
		if ( isset ( $_GET['publishthis_validation_message'] ) ) {
			$messages = array (
				'1' => __ ( 'All fields are required', 'publishthis' ),
				'2' => __ ( 'There is already a Publishing Action using that mix template / template section.', 'publishthis' ),
				'3' => __ ( 'Title is required', 'publishthis' ) );
			echo '<div class="error"><p><strong>' . $messages [$_GET['publishthis_validation_message']] . '</strong></p></div>';
		}
		//check if settings were updated

		if ( isset ( $_GET['page'], $_GET['settings-updated'] ) && $_GET['page'] == 'publishthis_settings' && $_GET['settings-updated'] == 'true' ) {
			$token_error = get_settings_errors( 'publishthis_api_token', true );
			if ( !empty( $token_error ) && !empty( $token_error[0]['message'] ) ) {
				echo '<div class="error"><p><strong>'.$token_error[0]['message'].'</strong></p></div>';
			}
			else {
				echo '<div class="updated"><p><strong>Settings saved.</strong></p></div>';
			}
		}
	}

	/**
	 *   Enqueue assets and get extra data. Creates unique token (nonce) for secure ajax call
	 */
	function enqueue_assets() {
		global $publishthis, $current_screen;

		wp_register_style ( 'publishthis-logo', $publishthis->plugin_url () . '/assets/css/logo.css', array (), $publishthis->version );
		wp_enqueue_style ( 'publishthis-logo' );

		// Load assets for all screens
		if ( in_array( $current_screen->id, $this->_screens ) ) {
			wp_enqueue_style ( 'publishthis-admin', $publishthis->plugin_url () . '/assets/css/admin.css', array ( 'wp-color-picker' ), $publishthis->version );
		
			wp_enqueue_script ( 'publishthis-admin', $publishthis->plugin_url () . '/assets/js/admin.js', array ( 'jquery', 'wp-color-picker', 'postbox' ), $publishthis->version );
			wp_enqueue_script ( 'publishthis-admin-sha', $publishthis->plugin_url () . '/assets/js/lib/jquery.sha256.min.js', array (), $publishthis->version );
			
			// Publishing Actions edit page needs some extra info
			if ( $current_screen->id == $publishthis->post_type ) {
				wp_localize_script( 'publishthis-admin', 'Publishthis', $this->_class_publishing_action->get_extra_info() );
			}
		}

		//generate ajax nonce for Settings page
		wp_localize_script(
			'publishthis-admin',
			'publishthis_settings_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_settings_nonce' )
			)
		);		
		wp_localize_script(
			'publishthis-admin',
			'publishthis_clients_list_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_clients_list_nonce' )
			)
		);
		wp_localize_script(
			'publishthis-admin',
			'publishthis_select_client_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_select_client_nonce' )
			)
		);
		wp_localize_script(
			'publishthis-admin',
			'publishthis_client_token_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_client_token_nonce' )
			)
		);

		wp_localize_script(
			'publishthis-admin',
			'publishthis_clear_caches_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_clear_caches_nonce' )
			)
		);

		//generate ajax nonce for Publishing Action page
		wp_localize_script(
			'publishthis-admin',
			'publishthis_publishing_action_ajax',
			array(
				'nonce' => wp_create_nonce( 'publishthis_admin_publishing_action_nonce' )
			)
		);		

		if ( $current_screen->id == 'widgets' ) {
			wp_enqueue_script ( 'publishthis-admin-widgets', $publishthis->plugin_url () . '/assets/js/admin-widgets.js', array ( 'jquery' ), $publishthis->version );

			//generate ajax nonce for widget
			wp_localize_script(
				'publishthis-admin-widgets',
				'publishthis_widgets_ajax',
				array(
					'nonce' => wp_create_nonce( 'publishthis_admin_widgets_nonce' )
				)
			);
		}
	}

	/**
	 *   Add Publishthis Plugin menus: set display name, actions, icons.
	 *
	 * @return Pass data to global variable
	 */
	function setup_menu() {
		global $publishthis, $submenu;

		$parent_slug = "edit.php?post_type={$publishthis->post_type}";

		//Add top level menu page
		add_menu_page( __ ( 'PublishThis', 'publishthis' ), __ ( 'PublishThis', 'publishthis' ), 'manage_options', $parent_slug, null, $publishthis->plugin_url () . '/assets/img/empty.gif' );

		//Specify submenus
		$this->_screens[] = add_submenu_page( $parent_slug, __ ( 'Style Options', 'publishthis' ), __ ( 'Style Options', 'publishthis' ), 'manage_options', $this->_styles_menu_slug, array ( $this, 'styles_options_page' ) );
		$this->_screens[] = add_submenu_page( $parent_slug, __ ( 'Settings', 'publishthis' ), __ ( 'Settings', 'publishthis' ), 'manage_options', $this->_settings_menu_slug, array ( $this, 'settings_options_page' ) );

		//Publishing Actions shall only be a visible option in WordPress after the settings have been successfully saved.
		$token = $publishthis->get_option('api_token');
		if($token && isset ( $submenu[$parent_slug][0][0] ) ) {
			$status = $publishthis->api->validate_token( sanitize_text_field( $publishthis->get_option('api_token') ) );

			if($status['valid']) {
				//Set custom name for the first submenu item				
				$submenu[$parent_slug][0][0] = 'Publishing Actions';			
			}
			else {
				unset($submenu[$parent_slug][0]);
			}
		}
		else {
			unset($submenu[$parent_slug][0]);
		}		
	}

	/**
	 *   Remove quick edit links for Publishing Actions list
	 *
	 * @param unknown $actions Array of possible actions
	 * @param unknown $post    Current list item
	 * @return Modified actions array
	 */
	function remove_quick_edit_link( $actions, $post ) {
		global $publishthis;
		if ( $post->post_type != $publishthis->post_type ) {
			return $actions;
		}
		unset( $actions['inline hide-if-no-js'] );
		return $actions;
	}

	/**
	 *   Prepare Options for saving - remove data value as it will be set automaticallly
	 *
	 * @param unknown $columns Options array
	 * @return Columns array
	 */
	function edit_columns( $columns ) {
		unset( $columns['date'] );
		return $columns;
	}

	/**
	 *   Render Publishthis Settings page
	 */
	function settings_options_page() {
?>
		<div class="wrap">

			<div class="icon32" id="icon-publishthis">
				<br>
			</div>
			<h2 style="margin: 4px 0 15px;"><?php _e( 'Settings', 'publishthis' ); ?></h2>
			<?php
			 try {
			
					if (is_admin() && current_user_can('administrator') ) {
			 

						if( intval( ini_get( 'output_buffering' ) ) == 0 ) {
							echo '<div id="message" class="error">'.
									'<p><strong>Output Buffering is OFF.</strong>'.
									'<br>What does this mean? It means that the Plugin will not work as a CMS Endpoint with the PublishThis system. Please contact your PublishThis Client Services representative and they can help you with a solution to fix this.'.
									'</p></div>';
						}  
					}
				}
				catch ( Exception $ex ) {}
			?>
			<form action="options.php" method="post">
				<?php settings_fields( $this->_option_group ); ?>
				<table class="form-table">
					<?php do_settings_fields( $this->_settings_section, $this->_settings_section ); ?>
				</table>

				<div class="publishthis-token-setup-block">
							<h3>API Token</h3>
							<p>To authenticate your API token, paste your API token in the field below or Log in to PublishThis to automatically generate your API token.</p>
							<div class="publishthis-token-setup">
								<?php do_settings_fields( $this->_settings_section, $this->_settings_token_section ); ?>
							</div>
				</div>

				<?php submit_button( __( 'Save Changes', 'publishthis' ) ); ?>
			</form>

		</div>
		<?php
	}

	/**
	 *   Render Publishthis Styles Options page
	 */
	function styles_options_page() {
		global $publishthis;
		$sections = $publishthis->utils->css_sections;		
?>
		<div class="wrap publishthis-styles-options">

			<div class="icon32" id="icon-publishthis">
				<br>
			</div>
			<h2 style="margin: 4px 0 15px;"><?php _e( 'Style Options', 'publishthis' ); ?></h2>

			<form action="options.php" method="post" id="poststuff">
			<?php settings_fields( $this->_option_group ); ?>
			<div class="metabox-holder columns-2" id="post-body">
				<!-- /post-body-content -->

				<div class="postbox-container" id="postbox-container-1">
					<div class="meta-box-sortables ui-sortable" id="side-sortables">
						<div class="postbox " id="submitdiv">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle"><span>Actions</span></h3>
							<div class="inside">
								<div id="submitpost">
									<div id="major-publishing-actions">
										<div id="publishing-action">
											<?php submit_button( __( 'Save Changes', 'publishthis' ) ); ?>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="postbox-container" id="postbox-container-2">
					<div class="meta-box-sortables ui-sortable" id="normal-sortables">
						<div class="postbox  hide-if-js" id="slugdiv" style="">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle"><span>Slug</span></h3>
							<div class="inside">
								<label for="post_name" class="screen-reader-text">Slug</label><input type="text" value="hhh" id="post_name" size="13" name="post_name">
							</div>
						</div>
						<?php foreach ($sections as $key => $section_title) {
							$section_key = 'publishthis_styles_'.$key; ?>
						<div class="postbox " id="publishthis-<?php echo $section_title; ?>-box">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle"><span><?php echo $section_title; ?></span></h3>
							<div class="inside">
								<table id="publishthis-options" class="publishthis-input widefat">
									<?php do_settings_fields( $this->_styles_section, $section_key ); ?>
								</table>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="meta-box-sortables ui-sortable" id="advanced-sortables"></div>
				</div>
			</div><!-- /post-body -->
			</form>	
			<br class="clear">
		</div>
		<script>postboxes.add_postbox_toggles('publishthis_style_options');</script>
		<?php
	}


}
