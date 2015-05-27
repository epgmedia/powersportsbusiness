<table class="publishthis-input widefat" id="publishthis-options">
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Image', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_image]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_image]"
						id="publishthis-include-imageLayoutSettings" value="1"
						<?php echo ($metabox['_publishthis_html_body_image']=='1'?'class="disabled"':''); ?>
						<?php checked( $metabox['_publishthis_layout_image'], '1' ); ?> /> Include</label></li>
				<li>
				<a href="#" class="customize-popup <?php echo ($metabox['_publishthis_layout_image']=="0"?'disabled':''); ?>" data-title="Image: Customize" data-popup="imageLayoutSettings" data-popup-width="500" data-popup-height="450">Customize</a></li>
				<input type="hidden" data-popup="imageLayoutSettings" id="publishthis-layout-image-custom-styles" name="publishthis_publish_action[layout_image_custom_styles]" value='<?php echo esc_html( $metabox['_publishthis_layout_image_custom_styles'] ); ?>' />
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Title', 'publishthis' ); ?></label>
		</td>
		<td id="title-row">
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_title]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_title]"
						id="publishthis-include-titleLayoutSettings" value="1"
						<?php echo ($metabox['_publishthis_content_type_format']=='individual'?'class="disabled"':''); ?>
						<?php checked( $metabox['_publishthis_layout_title'], "1" ); ?> /> Include</label></li>
				<li>
				<a href="#" class="customize-popup <?php echo (($metabox['_publishthis_content_type_format']=='individual' || $metabox['_publishthis_layout_title']=="0")?'disabled':''); ?>" data-title="Title: Customize" data-popup="titleLayoutSettings" data-popup-width="500" data-popup-height="210">Customize</a></li>
				<input type="hidden" data-popup="titleLayoutSettings" id="publishthis-layout-title-custom-styles" name="publishthis_publish_action[layout_title_custom_styles]" value='<?php echo esc_html( $metabox['_publishthis_layout_title_custom_styles'] ); ?>' />
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Summary', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_summary]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_summary]"
						id="publishthis-include-summaryLayoutSettings" value="1"
						<?php checked( $metabox['_publishthis_layout_summary'], '1' ); ?> /> Include</label></li>
				<li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Publish Date', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_publishdate]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_publishdate]"
						id="publishthis-include-publishdateLayoutSettings" value="1"
						<?php checked( $metabox['_publishthis_layout_publishdate'], '1' ); ?> /> Include</label></li>
				<li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Annotation', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_annotation]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_annotation]"
						id="publishthis-include-annotationLayoutSettings" value="1"
						<?php checked( $metabox['_publishthis_layout_annotation'], '1' ); ?> /> Include</label></li>
				<li>
				<a href="#" class="customize-popup <?php echo ($metabox['_publishthis_layout_annotation']=="0"?'disabled':''); ?>" data-title="Annotation: Customize" data-popup="annotationLayoutSettings" data-popup-width="500" data-popup-height="320">Customize</a></li>
				<input type="hidden" data-popup="annotationLayoutSettings" id="publishthis-layout-annotation-custom-styles" name="publishthis_publish_action[layout_annotation_custom_styles]" value='<?php echo esc_html( $metabox['_publishthis_layout_annotation_custom_styles'] ); ?>' />
				<input type="hidden" id="hidden-annotation-placement" name="publishthis_publish_action[annotation_placement]" value="" />
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Read More', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_readmore]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_readmore]"
						id="publishthis-include-readmoreLayoutSettings" value="1"
						<?php checked( $metabox['_publishthis_layout_readmore'], '1' ); ?> /> Include</label></li>
				<li>
				<a href="#" class="customize-popup <?php echo ($metabox['_publishthis_layout_readmore']=="0"?'disabled':''); ?>" data-title="Read More: Customize" data-popup="readmoreLayoutSettings" data-popup-width="500" data-popup-height="330">Customize</a></li>
				<input type="hidden" data-popup="readmoreLayoutSettings" id="publishthis-layout-readmore-custom-styles" name="publishthis_publish_action[layout_readmore_custom_styles]" value='<?php echo esc_html( $metabox['_publishthis_layout_readmore_custom_styles'] ); ?>' />
				<input type="hidden" name="publishthis_publish_action[read_more]" id="hidden-read-more" value="" />
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Embed', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list checkbox_list checkbox horizontal">
				<li><input type="hidden"
					name="publishthis_publish_action[layout_embed]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[layout_embed]"
						id="publishthis-include-embedLayoutSettings" value="1"
						<?php checked( $metabox['_publishthis_layout_embed'], '1' ); ?> /> Include</label></li>
				<li>
				<a href="#" class="customize-popup <?php echo ($metabox['_publishthis_layout_embed']=="0"?'disabled':''); ?>" data-title="Embed: Customize" data-popup="embedLayoutSettings" data-popup-width="500" data-popup-height="220">Customize</a></li>
				<input type="hidden" data-popup="embedLayoutSettings" id="publishthis-layout-embed-custom-styles" name="publishthis_publish_action[layout_embed_custom_styles]" value='<?php echo esc_html( $metabox['_publishthis_layout_embed_custom_styles'] ); ?>' />
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php /* for next release ?><a href="#" id="pt-layout-preview" class="button button-large button-primary">Preview</a>&nbsp;&nbsp;&nbsp;<?php */ ?>
			<a href="#" id="publish-bottom" class="button button-primary button-large">Save Changes</a>
		</td>
	</tr>
</table>

<?php
/**
 * Customization popups definition
 */
include 'layout-image-settings.php';
include 'layout-title-settings.php';
include 'layout-readmore-settings.php';
include 'layout-annotation-settings.php';
include 'layout-embed-settings.php';
?>

