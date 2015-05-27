<?php

/**
 * defines Publishthis Style Options page options
 */

class Publishthis_Admin_Style_Options {
	static $_styles_menu_slug = 'publishthis_style_options';

	/**
	 *  Publishthis_Admin_Style_Options constructor.
	 */
	function __construct() {}

	/* Fields rendering functions */
	/**
	 *   Render Font selectbox
	 */
	function display_font_name_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );

		$fonts = array("Arial", "Arial Black", "Comic Sans MS", "Courier New", "Georgia", "Impact", "Times New Roman", "Trebuchet MS", "Verdana", "Andale Mono", "Helvetica");
	
		echo '<ul class="radio_list radio horizontal">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).'  name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="custom" /> </label>
							<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-custom]" class="layout-inline-item">';
							foreach($fonts as $font) {
								echo '<option value="' . $font . '" class="'.str_replace(' ', '_', $font).'" '.selected($publishthis->get_option( $args['field_name'].'-custom'), $font, false).'>' . $font . '</option>';
							}
					echo '</select>
						</li>
				</ul>';
	}

	/**
	 *   Render Font Size selectbox
	 */
	function display_font_size_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );
		
		echo '<ul class="radio_list radio horizontal">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="custom" /> </label>
							<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-custom]" class="layout-inline-item">';
		for($i=6; $i<=54; $i++) {
			echo '<option value="' . $i . 'pt" '.selected($publishthis->get_option( $args['field_name'].'-custom'), $i.'pt', false).'>' . $i . 'pt</option>';
		}
					echo ' </select>
						</li>
				</ul>';
	}

	/**
	 *   Render Color color picker
	 */
	function display_color_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );
		
		echo '<ul class="radio_list radio horizontal choose_color_item">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].'] color-options" value="custom" /> </label>
						<input type="text" value="'.$publishthis->get_option( $args['field_name'].'-custom').'" class="choose_color layout-inline-item" name="'.$publishthis->option_name.'[' . $args['field_name'].'-custom]" id="'.$publishthis->option_name.'[' . $args['field_name'].'-custom]" />
					</li>
				</ul>';
	}

	/**
	 *   Render Font Style select
	 */
	function display_font_style_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );

		echo '<ul class="radio_list radio horizontal">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="custom" /> </label>
						<input type="hidden" name="'.$publishthis->option_name.'[' . $args['field_name'].'-bold]" value="0" />
						<input type="checkbox" '.checked( $publishthis->get_option( $args['field_name'].'-bold'), 1, false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].'-bold]" id="'.$publishthis->option_name.'[' . $args['field_name'].'-bold]" value="1" class="publishthis-font-style-bold font-style-selector" />
						<label for="'.$publishthis->option_name.'[' . $args['field_name'].'-bold]"></label>

						<input type="hidden" name="'.$publishthis->option_name.'[' . $args['field_name'].'-italic]" value="0" />
						<input type="checkbox" '.checked( $publishthis->get_option( $args['field_name'].'-italic'), '1', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].'-italic]" id="'.$publishthis->option_name.'[' . $args['field_name'].'-italic]" value="1" class="publishthis-font-style-italic font-style-selector" />
						<label for="'.$publishthis->option_name.'[' . $args['field_name'].'-italic]"></label>

						<input type="hidden" name="'.$publishthis->option_name.'[' . $args['field_name'].'-underline]" value="0" />
						<input type="checkbox" '.checked( $publishthis->get_option( $args['field_name'].'-underline'), '1', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].'-underline]" id="'.$publishthis->option_name.'[' . $args['field_name'].'-underline]" value="1" class="publishthis-font-style-underline font-style-selector" />
						<label for="'.$publishthis->option_name.'[' . $args['field_name'].'-underline]"></label>
					</li>
				</ul>';
	}

	/**
	 *   Render Border Size selectbox
	 */
	function display_border_size_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );
		
		echo '<ul class="radio_list radio horizontal">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="custom" /> </label>
						<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-custom]" class="layout-inline-item">';
							for($i=1; $i<=25; $i++) {
								echo '<option value="' . $i . '" '.selected($publishthis->get_option( $args['field_name'].'-custom'), $i, false).'>' . $i . '</option>';
							}
					echo '</select>
					</li>
				</ul>';
	}

	/**
	 *   Render Margins selectboxes
	 */
	function display_margins_field( $args ) {
		global $publishthis;

		$checked = Publishthis_Admin_Style_Options::_get_checked_option( $args['field_name'] );
		
		echo '<ul class="radio_list radio horizontal">
					<li><label><input type="radio" '.checked( $checked, 'default', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="default" /> Default</label></li>
					<li><label><input type="radio" '.checked( $checked, 'custom', false ).' name="'.$publishthis->option_name.'[' . $args['field_name'].']" class="'.$publishthis->option_name.'[' . $args['field_name'].']" value="custom" /> </label>
						&nbsp;Left&nbsp;&nbsp;<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-left]" class="layout-inline-item-small">';
							for($i=0; $i<=50; $i++) {
								echo '<option value="'.$i.'" '.selected($publishthis->get_option( $args['field_name'].'-left'), $i, false).'>'.$i.'</option>';
							}
						echo '</select>
						&nbsp;Right&nbsp;&nbsp;<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-right]" class="layout-inline-item-small">';
							for($i=0; $i<=50; $i++) {
								echo '<option value="'.$i.'" '.selected($publishthis->get_option( $args['field_name'].'-right'), $i, false).'>'.$i.'</option>';
							}
						echo '</select>
						&nbsp;Top&nbsp;&nbsp;<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-top]" class="layout-inline-item-small">';
							for($i=0; $i<=50; $i++) {
								echo '<option value="'.$i.'" '.selected($publishthis->get_option( $args['field_name'].'-top'), $i, false).'>'.$i.'</option>';
							}
						echo '</select>
						&nbsp;Bottom&nbsp;&nbsp;<select name="'.$publishthis->option_name.'[' . $args['field_name'].'-btm]" class="layout-inline-item-small">';
							for($i=0; $i<=50; $i++) {
								echo '<option value="'.$i.'" '.selected($publishthis->get_option( $args['field_name'].'-btm'), $i, false).'>'.$i.'</option>';
							}
						echo '</select>
					</li>
				</ul>';
	}

	static function _get_checked_option( $field_name ) {
		global $publishthis;

		$checked = ( $publishthis->get_option( $field_name ) ) ? $publishthis->get_option( $field_name ) : 'default';
		$checked = isset( $_SESSION['publishthis_settings_'.$field_name] ) ? $_SESSION['publishthis_settings_'.$field_name] : $checked;
		unset( $_SESSION['publishthis_settings_'.$field_name] );

		return $checked;
	}
}
