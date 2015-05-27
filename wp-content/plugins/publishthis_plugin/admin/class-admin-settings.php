<?php

/**
 * defines Publishthis Settings page options
 */

class Publishthis_Admin_Settings {

	static $_settings_menu_slug = 'publishthis_settings';
	static $_option_group = 'publishthis_options';

	/**
	 *  Publishthis_Admin_Settings constructor.
	 */
	function __construct() {
		global $publishthis;

		// Settings Page Options
		add_action( 'admin_init', array ( $this, 'init_options' ) );
		//add_action( 'admin_notices', array( $this, 'show_publishthis_messages' ) );  
	}

	/**
	 *   Display Publishthis plugin setup warnings
	 */
	function show_publishthis_messages() {
		try {
			
			if (is_admin() && current_user_can('administrator') ) {
	 
			
			if( intval( ini_get( 'output_buffering' ) ) == 0 ) {
				echo '<div id="message" class="error">'.
						'<p><strong>Output Buffering is OFF.</strong>'.
						'<br>What does this mean? It means that some of the features of this plugin will not work. Please contact your PublishThis Client Services representative and they can help you with a solution to fix this.'.
						'</p></div>';
			}  
		}
		}
		catch ( Exception $ex ) {}
	}

	/**
	 *   Bind options names, render functions and values
	 */
	function init_options() {
		global $publishthis;

		register_setting( $publishthis->option_name, self::$_option_group, array ( $this, 'validate_options' ) );

		//Create Settings general section and fields
		add_settings_section( $publishthis->settings_section, '', '__return_false', $publishthis->settings_section );

		add_settings_field( 'publishthis-curated-publish', 'PublishThis Curated Publish Options', array ( $this, 'display_curated_publish_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-pause-polling', 'Pause Polling', array ( $this, 'display_pause_polling_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-cms-url', 'CMS URL', array ( $this, 'display_cms_url_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-styling', 'Styling', array ( $this, 'display_styling_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-debug', 'Logging Options', array ( $this, 'display_debug_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-curatedby', 'Show PublishThis logo in', array ( $this, 'display_curatedby_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-curatedby-logos', 'Logo Options', array ( $this, 'display_curatedby_logos_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-logo-align', 'Logo Alignment', array ( $this, 'display_logo_align_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-cat-mappings', 'Category Mapping', array ( $this, 'display_cat_mappings_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-author-mappings', 'Author Mapping', array ( $this, 'display_author_mappings_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-include-analytics', 'Page Analysis', array ( $this, 'display_include_analytics_field' ), $publishthis->settings_section, $publishthis->settings_section );
		add_settings_field( 'publishthis-clear-caches', 'Purge all PublishThis Caches', array ( $this, 'display_clear_caches_field' ), $publishthis->settings_section, $publishthis->settings_section );
				
		//Create Token section and field
		add_settings_section( $publishthis->settings_token_section, 'API Token', '__return_false', $publishthis->settings_section );

		add_settings_field( 'publishthis-api-token', '', array ( $this, 'display_api_token_field' ), $publishthis->settings_section, $publishthis->settings_token_section );

		//Create Style Options sections and fields
		$sections = $publishthis->utils->css_sections;
		foreach ($sections as $section_key => $section_title) {
			add_settings_section( 'publishthis_styles_'.$section_key, $section_title, '__return_false', $publishthis->styles_section );

			add_settings_field( 'publishthis-'.$section_key.'-font', 'Font', array ( 'Publishthis_Admin_Style_Options', 'display_font_name_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_font' ) );
			add_settings_field( 'publishthis-'.$section_key.'-font-size', 'Font Size', array ( 'Publishthis_Admin_Style_Options', 'display_font_size_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_font_size' ) );
			add_settings_field( 'publishthis-'.$section_key.'-font-color', 'Font Color', array ( 'Publishthis_Admin_Style_Options', 'display_color_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_font_color' ) );
			add_settings_field( 'publishthis-'.$section_key.'-font-style', 'Font Style', array ( 'Publishthis_Admin_Style_Options', 'display_font_style_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_font_style' ) );

			//render only four item below for Annotation Title
			if( $section_key == 'annotation_title' ) continue;

			add_settings_field( 'publishthis-'.$section_key.'-border-size', 'Border Size', array ( 'Publishthis_Admin_Style_Options', 'display_border_size_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_border_size' ) );
			add_settings_field( 'publishthis-'.$section_key.'-border-color', 'Border Color', array ( 'Publishthis_Admin_Style_Options', 'display_color_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_border_color' ) );
			add_settings_field( 'publishthis-'.$section_key.'-background-color', 'Background Color', array ( 'Publishthis_Admin_Style_Options', 'display_color_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_background_color' ) );
			add_settings_field( 'publishthis-'.$section_key.'-margins', 'Margins', array ( 'Publishthis_Admin_Style_Options', 'display_margins_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_margins' ) );
			add_settings_field( 'publishthis-'.$section_key.'-paddings', 'Paddings', array ( 'Publishthis_Admin_Style_Options', 'display_margins_field' ), $publishthis->styles_section, 'publishthis_styles_'.$section_key, array( 'field_name' => $section_key.'_paddings' ) );
		}
	}

	/**
	 * Validates token and return an error if not valid
	 */
	function is_token_valid( $token ) {
		global $publishthis;

		//check token works
		$status = $publishthis->api->validate_token( $token );
		return $status;
	}

	/**
	 * Validate Settings before saving
	 *
	 * @param unknown $fields All settings data
	 * @return $options Validated settings data object
	 *
	 */
	function validate_options( $fields ) {
		global $publishthis;

		//invalidate the cache for client info
		delete_option( 'pt_client_info' );
		delete_transient( 'pt_client_info' );
		delete_transient( 'pt_admin_client_info' );

		$options = $publishthis->get_options();

		$errors = 0;
		if ( !isset( $fields['tax_mapping'] ) ) {
			$fields['tax_mapping'] = array();
		}

		foreach ( $fields as $key=>$val ) {
			//save selected value to user session
			if ( !isset( $_SESSION['publishthis_settings_'.$key] ) ) $_SESSION['publishthis_settings_'.$key] = $val;

			switch ( $key ) {
			case 'api_token': //validate API token
				$api_token = sanitize_text_field( $fields['api_token'] );
				$token_status = $this->is_token_valid( $api_token );
				if ( ! ( isset( $api_token ) && is_string( $api_token ) && $token_status['valid'] ) ) {
					add_settings_error( 'publishthis_api_token', 'publishthis_api_token_error', $token_status['message'], 'error' );
					$errors++;
				}

				break;

			case 'debug': //validate Debug
			case 'curatedby':   //validate curated by logo
				if ( ! ( isset( $fields[ $key ] ) && in_array( $fields[ $key ], array( '0', '1', '2' ) ) ) ) {
					$errors++;
				}
				break;

			case 'cat_mappings':
			case 'author_mappings':
			case 'include_analytics': //whether to include our js file for tracking how pt content does for them
			case 'pause_polling': //validate Pause polling
			case 'styling':   //validate Styling
				if ( ! ( isset( $val ) && in_array( $val, array( '0', '1' ) ) ) ) {
					$errors++;
				}
				break;

			case 'curated_publish': //no specific validation required, just setup crons
				if ( !isset( $val ) ) {
					$errors++;
				}
				else {
					if( $val == 'import_with_cron' ) {
						$publishthis->cron->pt_add();
					}
					else {
						$publishthis->cron->pt_remove();
					}
				}
				break;

			default:
				break;
			}
		}
		if ( $errors == 0 ) {
			//reset simulate cron
			update_option( "pt_simulated_cron", 0 );
			$options = array_merge( $options, $fields );
		}

		return $options;
	}

	/* Fields rendering functions */
	/**
	 *   Render Pause polling checkbox. If checked - stop polling the API for new content
	 */
	function display_pause_polling_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'pause_polling' ) ) ? '1' : '0';
		$checked = isset( $_SESSION['publishthis_settings_pause_polling'] ) ? $_SESSION['publishthis_settings_pause_polling'] : $checked;
		unset( $_SESSION['publishthis_settings_pause_polling'] );

		echo '<input type="hidden" name="' . $publishthis->option_name . '[pause_polling]" value="0" />' .
			'<label>' .
			'<input type="checkbox"' .
			'name="' . $publishthis->option_name . '[pause_polling]"' .
			'id="publishthis_pause_polling" value="1"';
		checked( $checked, '1' );
		echo '/> Stop polling the API for new content' .
			'</label>';
	}

	/**
	 *   Render API token field with value
	 */
	function display_api_token_field() {
		global $publishthis;

		$value = $publishthis->get_option( 'api_token' );
		$value = isset( $_SESSION['publishthis_settings_api_token'] ) ? $_SESSION['publishthis_settings_api_token'] : $value;
		unset( $_SESSION['publishthis_settings_api_token'] );

		echo '<p id="verify-token-error" class="hidden"></p>' .
			'<label>API Token (required)' .
			'<input type="text" id="publishthis_api_token" ' .
			'name="' . $publishthis->option_name . '[api_token]" ' .
			'value="' . esc_attr( $value ) . '" class="regular-text" /></label>';
		echo '<label>' .
			'<input type="button" value="Verify" class="button button-primary" id="verify-token" name="verify-token"> ' .
			'or <a id="login-link">Log in</a></label>';

		echo '<div class="hidden" id="token-setup">' .
			'<div id="token-setup-content">' .
			'<fieldset>
                <p>
                    <img title="Publishthis" alt="Publishthis" src="' . $publishthis->plugin_url() . '/assets/img/logo-publishthis.png">
                </p>
                <p class="error hidden"></p>
            </fieldset>
            <fieldset id="login-info-block">
                <label for="email">
                	Email<br /><input type="text" name="email" id="email" />
                </label>
                <label for="pass">
                	Password<br /><input type="password" name="password" id="pass" />
                </label>
				<label>
					Role<br />
					<select id="role">
                        <option value="EDITOR">Curator</option>
                        <option value="CLIENT_ADMIN">Administrator</option>
                    </select>
				</label>
                <label>
                    <a class="button button-primary" id="token-login">Login</a>
                </label>
            </fieldset>
            <fieldset id="client-info-block" class="hidden">
                <label>
                	Client<br />
                	<input type="hidden" id="sessionid" value="" />
                	<select id="clients"></select>
                </label>
                <label>
                    <a class="button button-primary" id="token-login-as">Select</a>
                </label>
            </fieldset>' .
			'</div>' .
			'</div>';

		/*

			*/
	}

	/**
	 *   Render API version select with selected value
	 */
	function display_api_version_field() {
		global $publishthis;
?>
		<input type="hidden" name="<?php echo $publishthis->option_name ?>[api_version]" id="publishthis_api_version" value="3.0" />
		<?php
	}

	/**
	 *   Render debug select with selected value
	 */
	function display_debug_field() {
		global $publishthis;

		$selected = $publishthis->get_option( 'debug' );
		$selected = isset( $_SESSION['publishthis_settings_debug'] ) ? $_SESSION['publishthis_settings_debug'] : $selected;
		unset( $_SESSION['publishthis_settings_debug'] );
?>
		<select name="<?php echo $publishthis->option_name ?>[debug]" id="publishthis_debug">
			<option <?php selected( $selected, '0' ); ?> value="0">None</option>
			<option <?php selected( $selected, '1' ); ?> value="1">Errors Only</option>
			<option <?php selected( $selected, '2' ); ?> value="2">Debug</option>
		</select>
		<?php

	}

	/**
	 *   Render Enable PublishThis CSS styles checkbox
	 */
	function display_styling_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'styling' ) ) ? '1' : '0';
		$checked = isset( $_SESSION['publishthis_settings_styling'] ) ? $_SESSION['publishthis_settings_styling'] : $checked;
		unset( $_SESSION['publishthis_settings_styling'] );
?>
		<input type="hidden" name="<?php echo $publishthis->option_name ?>[styling]" value="0" />
		<label>
			<input type="checkbox"
				name="<?php echo $publishthis->option_name ?>[styling]"
				id="publishthis_styling" value="1" <?php checked( $checked, '1' ); ?> />
			Enable PublishThis CSS styles
		</label>
		<?php
	}

	/**
	 *   Render option to show/hide Publishthis logo
	 */
	function display_curatedby_field() {
		global $publishthis, $client_info;

		$default_curatedby_field = '1'; //display in 'Footer' option
		$checked = strlen( $publishthis->get_option( 'curatedby' ) ) > 0 ? $publishthis->get_option( 'curatedby' ) : $default_curatedby_field;
		$checked = isset( $_SESSION['publishthis_settings_curatedby'] ) ? $_SESSION['publishthis_settings_curatedby'] : $checked;
		unset( $_SESSION['publishthis_settings_curatedby'] );
		$checked = $checked==0 && !$client_info->allowDisableLogo ? $default_curatedby_field : $checked;
?>
		<ul class="radio_list checkbox_list checkbox horizontal">
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby]" id="publishthis_curatedby_footer" value="1" <?php checked( $checked, '1' ); ?> />
					 Footer
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby]" id="publishthis_curatedby_page_post" value="2" <?php checked( $checked, '2' ); ?> />
					 Page or Post
				</label>
			</li>
			<?php if ( $client_info && $client_info->allowDisableLogo ) { ?>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby]" id="publishthis_curatedby_hide" value="0" <?php checked( $checked, '0' ); ?> />
					 Do not display logo
				</label>
			</li>
			<?php } ?>
		</ul>
		<?php
	}

	/**
	 *   Render Cureted By Logo images selection
	 */
	function display_curatedby_logos_field() {
		global $publishthis;

		$curatedby_logos = $publishthis->get_option( 'curatedby_logos' );
		$curatedby_logos = $curatedby_logos=='4' ? '5' : $curatedby_logos;
		$checked = ( $curatedby_logos ) ? $curatedby_logos : '1';
		$checked = isset( $_SESSION['publishthis_settings_curatedby_logos'] ) ? $_SESSION['publishthis_settings_curatedby_logos'] : $checked;
		unset( $_SESSION['publishthis_settings_curatedby_logos'] );
?>
		<ul class="radio_list checkbox_list checkbox horizontal curatedby_logos">
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby_logos]" id="publishthis_curatedby_logo_black" value="1" <?php checked( $checked, '1' ); ?> />
					<img src="<?php echo $publishthis->utils->getCuratedByLogoImage( 1 ); ?>" alt="Publishthis Curated By Logo Black"/>
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby_logos]" id="publishthis_curatedby_logo_grey" value="2" <?php checked( $checked, '2' ); ?> />
					<img src="<?php echo $publishthis->utils->getCuratedByLogoImage( 2 ); ?>" alt="Publishthis Curated By Logo Gray"/>
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby_logos]" id="publishthis_curatedby_logo_white" value="3" <?php checked( $checked, '3' ); ?> />
					<img src="<?php echo $publishthis->utils->getCuratedByLogoImage( 3 ); ?>" alt="Publishthis Curated By Logo White"/>
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curatedby_logos]" id="publishthis_curatedby_logo_medium" value="5" <?php checked( $checked, '5' ); ?> />
					<img src="<?php echo $publishthis->utils->getCuratedByLogoImage( 5 ); ?>" alt="Publishthis Curated By Logo Medium"/>
				</label>
			</li>
		</ul>
		<?php
	}

	/**
	 *   Render Cureted Publish options selection
	 */
	function display_curated_publish_field() {
		global $publishthis, $import_options;

		$checked = ( $publishthis->get_option( 'curated_publish' ) ) ? $publishthis->get_option( 'curated_publish' ) : 'import_from_manager';
		$checked = isset( $_SESSION['publishthis_settings_curated_publish'] ) ? $_SESSION['publishthis_settings_curated_publish'] : $checked;
		unset( $_SESSION['publishthis_settings_curated_publish'] );

		echo '<ul class="radio_list checkbox_list checkbox horizontal curatedby_logos">';
		foreach( $import_options as $key=>$label ) { ?>
			<li>
				<label>
					<input type="radio" name="<?php echo $publishthis->option_name ?>[curated_publish]" id="publishthis_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( $checked, $key ); ?> />
					<?php echo esc_html( $label ); ?>
				</label>
			</li>
		<?php }
		echo '</ul>';
	}

	/**
	 *   Render CMS endpoint URL
	 */
	function display_cms_url_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'curated_publish' ) ) ? $publishthis->get_option( 'curated_publish' ) : 'import_from_manager';
		$checked = isset( $_SESSION['publishthis_settings_curated_publish'] ) ? $_SESSION['publishthis_settings_curated_publish'] : $checked;
		unset( $_SESSION['publishthis_settings_curated_publish'] );

		echo '<input type="text" value="' . site_url('?pt_endpoint=1') . '" id="cms-url" readonly />';
	}

	/**
	 *   Render Use PT Include Analytics checkbox
	 */
	function display_include_analytics_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'include_analytics' ) ) ? '1' : '0';
		$checked = isset( $_SESSION['publishthis_settings_include_analytics'] ) ? $_SESSION['publishthis_settings_include_analytics'] : $checked;
		unset( $_SESSION['publishthis_settings_include_analytics'] );
?>
		<input type="hidden" name="<?php echo $publishthis->option_name ?>[include_analytics]" value="0" />
		<label>
			<input type="checkbox"
				name="<?php echo $publishthis->option_name ?>[include_analytics]"
				id="publishthis_include_analytics" value="1" <?php checked( $checked, '1' ); ?> />
			I want to have PublishThis observe how content is doing
		</label>
		<?php
	}


	/**
	 *   Render Use PT Cat Mappings checkbox
	 */
	function display_cat_mappings_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'cat_mappings' ) ) ? '1' : '0';
		$checked = isset( $_SESSION['publishthis_settings_cat_mappings'] ) ? $_SESSION['publishthis_settings_cat_mappings'] : $checked;
		unset( $_SESSION['publishthis_settings_cat_mappings'] );

		$tax_maps = $publishthis->get_option( 'tax_mapping' );
		if( !isset($tax_maps) ) { $tax_maps = array('pt_tax_all'); }
		$tax_maps = array_values($tax_maps);
		
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
?>
		<input type="hidden" name="<?php echo $publishthis->option_name ?>[cat_mappings]" value="0" />
		<label>
			<input type="checkbox"
				name="<?php echo $publishthis->option_name ?>[cat_mappings]"
				id="publishthis_cat_mappings" value="1" <?php checked( $checked, '1' ); ?> />
			I want to use Categories set from PublishThis Content Mixes
		</label>
		<div class="cat_mappings_options">
			<a href="#" id="edit_map_categories">Edit Categories</a>
			<div class="cat_mappings_options_select">
				<p>Select Taxonomies, which contain categories, to send to Publishthis.</p>
				<div class="cat_mappings_options_select_inner">
					<label class="all" for="tax_all"><input type="checkbox" name="publishthis_options[tax_mapping][]" id="tax_all" value="pt_tax_all" <?php checked( (in_array('pt_tax_all', $tax_maps) ? '1' : '0'), '1' ); ?>>All</label>
					<label for="tax_category"><input type="checkbox" name="publishthis_options[tax_mapping][]" id="tax_category" value="category" <?php checked( (in_array('category', $tax_maps) ? '1' : '0'), '1' ); ?>>WordPress Default</label>
					<?php
						foreach($all_taxonomies as $taxonomy_key=>$taxonomy) { ?>
							<label for="tax_<?php echo $taxonomy_key; ?>">
								<input type="checkbox" name="publishthis_options[tax_mapping][]" <?php checked( (in_array($taxonomy_key, $tax_maps) ? '1' : '0'), '1' ); ?> id="tax_<?php echo $taxonomy_key; ?>" value="<?php echo $taxonomy_key; ?>"><?php echo $taxonomy->name; ?>
							</label>
						<?php }
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 *   Render Use PT Authors Mappings checkbox
	 */
	function display_author_mappings_field() {
		global $publishthis;

		$checked = ( $publishthis->get_option( 'author_mappings' ) ) ? '1' : '0';
		$checked = isset( $_SESSION['publishthis_settings_author_mappings'] ) ? $_SESSION['publishthis_settings_author_mappings'] : $checked;
		unset( $_SESSION['publishthis_settings_author_mappings'] );
?>
		<input type="hidden" name="<?php echo $publishthis->option_name ?>[author_mappings]" value="0" />
		<label>
			<input type="checkbox"
				name="<?php echo $publishthis->option_name ?>[author_mappings]"
				id="publishthis_author_mappings" value="1" <?php checked( $checked, '1' ); ?> />
			I want to use Authors set from PublishThis Content Mixes
		</label>
		<?php
	}

	/**
	 *   Render Use PT Authors Mappings checkbox
	 */
	function display_clear_caches_field() {
?>
		<label>
			<input type="button" name="clear-cache" id="clear-cache" class="button button-primary" value="Run">
			<img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" class="publishthis-ajax-img hidden" />
		</label>
		<?php
	}

	/**
	 *   Render Logo Alignment select
	 */
	function display_logo_align_field() {
		global $publishthis;

		$default_logo_align = '1'; //center
		$selected = $publishthis->get_option( 'logo_align' );
		$selected = !isset( $selected ) ? $default_logo_align : $selected;
		$selected = isset( $_SESSION['publishthis_settings_logo_align'] ) ? $_SESSION['publishthis_settings_logo_align'] : $selected;
		unset( $_SESSION['publishthis_settings_logo_align'] );
?>
		<select name="<?php echo $publishthis->option_name ?>[logo_align]" id="publishthis_logo_align">
			<option <?php selected( $selected, '2' ); ?> value="2">Left Aligned</option>
			<option <?php selected( $selected, '1' ); ?> value="1">Centered</option>
			<option <?php selected( $selected, '3' ); ?> value="3">Right Aligned</option>
		</select>
		<?php
	}
}
