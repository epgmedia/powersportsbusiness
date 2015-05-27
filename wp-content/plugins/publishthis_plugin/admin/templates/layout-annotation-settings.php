<div id="annotationLayoutSettings" class="hidden">
	<h2>Annotation</h2>
	<table class="publishthis-input widefat publishthis-popup">
		<tr>
			<td class="label"><label for="publishthis-annotation-placement-field"><?php _e( 'Alignment', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="radio"
							name="publishthis_publish_action[annotation_placement]"
							class="publishthis-annotation-placement"
							id="publishthis-annotation-placement-field" value="0"
							<?php checked( $metabox['_publishthis_annotation_placement'], '0' ); ?> /> Above the Content</label></li>
					<li><label><input type="radio"
							name="publishthis_publish_action[annotation_placement]"
							class="publishthis-annotation-placement"
							id="publishthis-annotation-placement-field" value="1"
							<?php checked( $metabox['_publishthis_annotation_placement'], '1' ); ?> /> Below the Content</label></li>
				</ul>
			</td>
		</tr>
	</table>
	<h2>Title</h2>
	<table class="publishthis-input widefat publishthis-popup">
		<tr>
			<td class="label"><label for="publishthis-annotation-title-text"><?php _e( 'Display Text', 'publishthis' ); ?></label>
			</td>
			<td><input type="text" name="publishthis_publish_action[annotation_title_text]" id="publishthis-annotation-title-text" value="" /></label>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="layout-annotation-title-alignment"><?php _e( 'Text Alignment', 'publishthis' ); ?></label>
			</td>
			<td>
				<select id="layout-annotation-title-custom-vertical" class="layout-inline-item">
					<option value="top">Top</option>
					<option value="middle">Middle</option>
					<option value="bottom">Bottom</option>
				</select>
				<select id="layout-annotation-title-custom-horizontal" class="layout-inline-item">
					<option value="left">Left</option>
					<option value="right">Right</option>
				</select>
			</td>
		</tr>
	</table>
	<input type="button" value="Save" id="layout-annotation-save" class="button button-primary button-large" name="layout-annotation-save" /><br /><br />
</div>
