<table class="publishthis-input widefat" id="publishthis-options">
	<tr>
		<td class="label"><label for="publishthis-create-excerpt-field"><?php _e( 'For Digest/Individual Posts, include annotation in Excerpt?', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[create_excerpt]"
						id="publishthis-create-excerpt-field" value="0"
						<?php checked( $metabox['_publishthis_create_excerpt'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[create_excerpt]"
						id="publishthis-create-excerpt-field" value="1"
						<?php checked( $metabox['_publishthis_create_excerpt'], '1' ); ?> /> Yes</label></li>
			</ul>
			<p>Note: This will only work if you use WordPress excerpt controls. If your theme overrides these controls, then we may not be able to remove the annotations in the excerpts.</p>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-excerpt-first-item-field"><?php _e( 'For Digest Posts, set a excerpt break after the first curated item?', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[excerpt_first_item]"
						id="publishthis-excerpt-first-item-field" value="0"
						<?php checked( $metabox['_publishthis_excerpt_first_item'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[excerpt_first_item]"
						id="publishthis-excerpt-first-item-field" value="1"
						<?php checked( $metabox['_publishthis_excerpt_first_item'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="label"><label for="publishthis-excerpt-more-tag-field"><?php _e( 'Include a WordPress &lt;more&gt; tag after the summary?', 'publishthis' ); ?></label>
		</td>
		<td>
			<ul class="radio_list radio horizontal">
				<li><label><input type="radio"
						name="publishthis_publish_action[excerpt_more_tag]"
						id="publishthis-excerpt-more-tag-field" value="0"
						<?php checked( $metabox['_publishthis_excerpt_more_tag'], '0' ); ?> /> No</label></li>
				<li><label><input type="radio"
						name="publishthis_publish_action[excerpt_more_tag]"
						id="publishthis-excerpt-more-tag-field" value="1"
						<?php checked( $metabox['_publishthis_excerpt_more_tag'], '1' ); ?> /> Yes</label></li>
			</ul>
		</td>
	</tr>
</table>
