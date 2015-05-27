<?php /* global $publishthis; */ ?>
<input type="hidden" name="dont_redirect" id="dont_redirect" value="0" />
<table class="publishthis-input widefat" id="publishthis-options">
	<?php if( $publishthis->get_option( 'curated_publish' ) != 'import_from_manager' ) { ?>
	<tr>
		<td class="label"><label for="publishthis-poll-interval-field"><?php _e( 'Poll Interval', 'publishthis' ); ?></label>
		</td>
		<td>
			<select id="publishthis-poll-interval-field" name="publishthis_publish_action[poll_interval]">
				<option value="60" <?php selected( $metabox['_publishthis_poll_interval'], '60' ); ?>>1 min</option>
				<option value="300" <?php selected( $metabox['_publishthis_poll_interval'], '300' ); ?>>5 min</option>
				<option value="600" <?php selected( $metabox['_publishthis_poll_interval'], '600' ); ?>>10 min</option>
				<option value="900" <?php selected( $metabox['_publishthis_poll_interval'], '900' ); ?>>15 min</option>
				<option value="1800" <?php selected( $metabox['_publishthis_poll_interval'], '1800' ); ?>>30 min</option>
				<option value="2700" <?php selected( $metabox['_publishthis_poll_interval'], '2700' ); ?>>45 min</option>
				<option value="3600" <?php selected( $metabox['_publishthis_poll_interval'], '3600' ); ?>>60 min</option>
				<option value="7200" <?php selected( $metabox['_publishthis_poll_interval'], '7200' ); ?>>2 hrs</option>
				<option value="21600" <?php selected( $metabox['_publishthis_poll_interval'], '21600' ); ?>>6 hrs</option>
				<option value="43200" <?php selected( $metabox['_publishthis_poll_interval'], '43200' ); ?>>12 hrs</option>
				<option value="86400" <?php selected( $metabox['_publishthis_poll_interval'], '86400' ); ?>>24 hrs</option>
			</select>
		</td>
	</tr>
	<?php } else { ?>
		<input type="hidden" id="publishthis-poll-interval-field" name="publishthis_publish_action[poll_interval]" value="<?php echo $metabox['_publishthis_poll_interval']; ?>" />
	<?php } ?>

	<?php if( $publishthis->get_option( 'author_mappings' ) != '1' ) { ?>
	<tr>
		<td class="label"><label for="publishthis-publish-author-field"><?php _e( 'Publish Author', 'publishthis' ); ?></label>
		</td>
		<td>
			<?php
				$authorsArgs = array ( 'exclude' => '1', 'who' => 'authors' );
				wp_dropdown_users ( array ( 'who' => 'authors', 'name' => 'publishthis_publish_action[publish_author]', 'id' => 'publishthis-publish-author-field', 'include_selected' => true, 'selected' => $metabox['_publishthis_publish_author'] ) );
			?>
		</td>
	</tr>
	<?php } else { ?>
		<input type="hidden" id="publishthis-author-field" name="publishthis_publish_action[publish_author]" value="<?php echo $metabox['_publishthis_publish_author']; ?>" />
	<?php } ?>

	<tr>
		<td class="label"><label for="publishthis-feed-template-field"><?php _e( 'Mix Template', 'publishthis' ); ?></label>
		</td>
		<td>
			<select id="publishthis-feed-template-field" name="publishthis_publish_action[feed_template]">
			<?php foreach ( $metabox['feed_templates'] as $template ) : ?>
				<?php if ( $template->curated ) : ?>
					<option value="<?php echo esc_attr( $template->templateId ); ?>" <?php selected( $template->templateId, (int)$metabox['_publishthis_feed_template'] ); ?>><?php echo esc_attr( $template->displayName ); ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-template-section-field"><?php _e( 'Template Section', 'publishthis' ); ?></label>
		</td>
		<td>
			<select id="publishthis-template-section-field" name="publishthis_publish_action[template_section]"
				data-template-section="<?php echo esc_attr( $metabox['_publishthis_template_section'] ); ?>">
			</select>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-content-type-field"><?php _e( 'Content Type', 'publishthis' ); ?></label>
		</td>
		<td>
			<select id="publishthis-content-type-field" name="publishthis_publish_action[content_type]">
				<?php foreach ( $metabox['content_types'] as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected($key, $metabox['_publishthis_content_type'] ); ?>><?php echo esc_attr( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-content-type-field"><?php _e( 'Content Type Wordpress Template', 'publishthis' ); ?></label>
		</td>
		<td>
			<select id="publishthis-content-type-wp-template-field" name="publishthis_publish_action[content_type_wp_template]">
				<?php foreach ( $metabox['content_type_wp_templates'] as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected($key, $metabox['_publishthis_content_type_wp_template'] ); ?>><?php echo esc_attr( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-content-type-format-field"><?php _e( 'Content Type Format', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio vertical">
				<li><label><input type="radio" class="content_type_format"
						name="publishthis_publish_action[content_type_format]"
						id="publishthis-content-type-format-field" value="individual"
						<?php checked( $metabox['_publishthis_content_type_format'], 'individual' ); ?> />
						Individual</label></li>
				<li><label><input type="radio" class="content_type_format"
						name="publishthis_publish_action[content_type_format]"
						id="publishthis-content-type-format-field" value="combined"
						<?php checked( $metabox['_publishthis_content_type_format'], 'combined' ); ?> /> Digest</label>
						<ul class="checkbox_list checkbox ptpadtop">
							<li>Digest Layout to use:&nbsp;&nbsp;&nbsp;&nbsp;<select id="publishthis-combined-layout-field" name="publishthis_publish_action[combined_layout]">
										<?php foreach ( $metabox['combined_layouts'] as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected($key, $metabox['_publishthis_combined_layout'] ); ?>><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>
									</select></label></li>
								
						</ul>		
				</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-content-status-field"><?php _e( 'Content Status', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio vertical">
				<li><label><input type="radio"
						name="publishthis_publish_action[content_status]"
						id="publishthis-content-status-field" value="draft"
						<?php checked( $metabox['_publishthis_content_status'], 'draft' ); ?> /> Save as 'Draft'</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[content_status]"
						id="publishthis-content-status-field" value="publish"
						<?php checked( $metabox['_publishthis_content_status'], 'publish' ); ?> /> Publish immediately</label></li>
			</ul>
		</td>
	</tr>

	<?php if( $publishthis->get_option( 'cat_mappings' ) != '1' ) { ?>
	<tr>
		<td class="label"><label for="publishthis-category-field"><?php _e( 'Category', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="checkbox_list checkbox">
				<li>
					<select id="publishthis-category-field"
							name="publishthis_publish_action[category]"
							data-current="<?php echo esc_attr( $metabox['_publishthis_category'] ); ?>">
								<option value="0">Do not categorize</option>
					</select>
				</li>
				<li>
					Taxonomy to use for categories:&nbsp;&nbsp;&nbsp;&nbsp;
					<select id="publishthis-taxonomy-field" name="publishthis_publish_action[taxonomy]"
						data-taxonomy="<?php echo esc_attr( $metabox['_publishthis_taxonomy'] ); ?>">
					</select>
				</li>
			</ul>
		</td>
	</tr>
	<?php } else { ?>
		<input type="hidden" id="publishthis-category-field" name="publishthis_publish_action[category]" value="<?php echo $metabox['_publishthis_category']; ?>" />
		<input type="hidden" id="publishthis-taxonomy-field" name="publishthis_publish_action[taxonomy]" value="<?php echo $metabox['_publishthis_taxonomy']; ?>" />
	<?php } ?>

	<tr>
		<td class="label"><label for="publishthis-featured-image-field"><?php _e( 'Featured Image', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="checkbox_list checkbox">
				<li><input type="hidden"
					name="publishthis_publish_action[featured_image]" value="0" /> <label><input
						type="checkbox" name="publishthis_publish_action[featured_image]"
						id="publishthis-featured-image-field" value="1"
						<?php checked( $metabox['_publishthis_featured_image'], '1' ); ?> /> Download and save
						content image as the "Featured Image"</label></li>
				<li>Post Image:&nbsp;&nbsp;<input type="hidden" name="publishthis_publish_action[html_body_image]" value="0" />
					<label>
						<input type="checkbox"
							name="publishthis_publish_action[html_body_image]"
							id="publishthis-html-body-image-field"
							value="1"
							<?php checked( $metabox['_publishthis_html_body_image'], '1' ); ?> />
							Include document image in template? (uncheck this if your Featured image gets inserted into your post by your theme)
					</label>
				</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-max-image-width-field"><?php _e( 'Featured Image Size', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li>
					<label style="vertical-align: sub;">
						<input type="radio" name="publishthis_publish_action[featured_image_size]" class="publishthis-featured-image-size-field"
						id="publishthis-featured-image-size-themedefault-field" value="theme_default"  style="vertical-align: baseline;"
						<?php checked( $metabox['_publishthis_featured_image_size'], 'theme_default' ); ?> /> Theme Default
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="publishthis_publish_action[featured_image_size]" class="publishthis-featured-image-size-field"
						id="publishthis-featured-image-size-custom-field" value="custom"
						<?php checked( $metabox['_publishthis_featured_image_size'], 'custom' ); ?> />
						Width <input type="text" name="publishthis_publish_action[featured_image_width]"
						id="publishthis-featured-image-width-field" class="check-for-int layout-inline-item"
						value="<?php echo $metabox['_publishthis_featured_image_width']; ?>" size="5" maxlength="4" />
						Height <input type="text" name="publishthis_publish_action[featured_image_height]"
						id="publishthis-featured-image-height-field" class="check-for-int layout-inline-item"
						value="<?php echo $metabox['_publishthis_featured_image_height']; ?>" size="5" maxlength="4" />
					</label>
				</li>				
				<li>
					<label>
						<input type="radio" name="publishthis_publish_action[featured_image_size]" class="publishthis-featured-image-size-field"
						id="publishthis-featured-image-size-custommax-field" value="custom_max"
						<?php checked( $metabox['_publishthis_featured_image_size'], 'custom_max' ); ?> />
						Max Width <input type="text" name="publishthis_publish_action[featured_max_image_width]"
						id="publishthis-featured-max-image-width-field" class="check-for-int layout-inline-item"
						value="<?php echo $metabox['_publishthis_featured_max_image_width']; ?>" size="5" maxlength="4" />
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="publishthis_publish_action[featured_image_size]" class="publishthis-featured-image-size-field"
						id="publishthis-featured-image-size-custommax-field" value="custom_up_to_max"
						<?php checked( $metabox['_publishthis_featured_image_size'], 'custom_up_to_max' ); ?> />
						Up To Max Width <input type="text" name="publishthis_publish_action[up_to_max_width]"
						id="publishthis-up-to-max-width-field" class="check-for-int layout-inline-item"
						value="<?php echo $metabox['_publishthis_up_to_max_width']; ?>" size="5" maxlength="4" />
					</label>
				</li>
			</ul>
			<ul class="checkbox_list checkbox">
				<li><input type="hidden" name="publishthis_publish_action[ok_override_fimage_size]" value="0" />
					<label>
						<input type="checkbox"
							name="publishthis_publish_action[ok_override_fimage_size]"
							id="publishthis-ok-override-fimage-size-field"
							value="1"
							<?php checked( $metabox['_publishthis_ok_override_fimage_size'], '1' ); ?> />
							Ok to ignore original image's size?:&nbsp;&nbsp;If an editor modifies the image in the PublishThis Content Manager for a curated item, we default to using whatever that images size is when publishing.
							If you wish to ignore whatever the Editor did in the PublishThis Content Manager for that image, you can check this box and all of the image width/height options you have setup will be applied
							to editorially updated images as well.  If this is unchecked, then we pass through the editorially modified images as is, no changes to width/height are ever applied.
					</label>
				</li>
			</ul>
		</td>
	</tr>
	<tr id="digest-synchronize-row">
		<td class="label"><label for="publishthis-synchronize-field"><?php _e( 'Allow PublishThis to Override Edits', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[synchronize]"
						id="publishthis-content-status-field" value="0"
						<?php checked( $metabox['_publishthis_synchronize'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[synchronize]"
						id="publishthis-content-status-field" value="1"
						<?php checked( $metabox['_publishthis_synchronize'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
	<tr id="individual-insert-row">
		<td class="label"><label for="publishthis-individual-insert-field"><?php _e( 'Add Posts from new content', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_insert]"
						id="publishthis-individual-insert-field" value="0"
						<?php checked( $metabox['_publishthis_individual_insert'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_insert]"
						id="publishthis-individual-insert-field" value="1"
						<?php checked( $metabox['_publishthis_individual_insert'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
	<tr id="individual-delete-row">
		<td class="label"><label for="publishthis-individual-delete-field"><?php _e( 'Delete Posts when deleted in PublishThis', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_delete]"
						id="publishthis-individual-delete-field" value="0"
						<?php checked( $metabox['_publishthis_individual_delete'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_delete]"
						id="publishthis-individual-delete-field" value="1"
						<?php checked( $metabox['_publishthis_individual_delete'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
	<tr id="individual-update-row">
		<td class="label"><label for="publishthis-individual-update-field"><?php _e( 'Modified content in PublishThis updates Posts', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_update]"
						id="publishthis-individual-update-field" value="0"
						<?php checked( $metabox['_publishthis_individual_update'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[individual_update]"
						id="publishthis-individual-update-field" value="1"
						<?php checked( $metabox['_publishthis_individual_update'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-tags-field"><?php _e( 'Add Tags to Content', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[tags]" id="publishthis-tags-field" value="0"
						<?php checked( $metabox['_publishthis_tags'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[tags]" id="publishthis-tags-field" value="1"
						<?php checked( $metabox['_publishthis_tags'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
</table>
