<div id="imageLayoutSettings" class="hidden">
	<h2>Image</h2>
	<table class="publishthis-input widefat publishthis-popup">
		<tr>
			<td class="label"><label for="publishthis-aligment"><?php _e( 'Alignment', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="radio" name="publishthis-aligment" class="publishthis-aligment" value="default" /> Default</label></li>
					<li><label><input type="radio" name="publishthis-aligment" class="publishthis-aligment" value="left" /> Left</label></li>
					<li><label><input type="radio" name="publishthis-aligment" class="publishthis-aligment" value="center" /> Center</label></li>
					<li><label><input type="radio" name="publishthis-aligment" class="publishthis-aligment" value="right" /> Right</label></li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="publishthis-max-width"><?php _e( 'Image Size', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio vertical">
					<li>
						<label style="vertical-align: sub;">
							<input type="radio" name="publishthis-post-image-size" class="publishthis-post-image-size"
							id="publishthis-image-size-themedefault" value="default"  style="vertical-align: baseline;" /> Theme Default
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="publishthis-post-image-size" class="publishthis-post-image-size"
							id="publishthis-image-size-custom" value="custom" /></label>
							Width <input type="text" name="publishthis-post-image-width"
							id="publishthis-post-image-width" class="check-for-int layout-inline-item"
							value="0" size="5" maxlength="4" />
							Height <input type="text" name="publishthis-post-image-height"
							id="publishthis-post-image-height" class="check-for-int layout-inline-item"
							value="0" size="5" maxlength="4" />						
					</li>				
					<li>
						<label>
							<input type="radio" name="publishthis-post-image-size" class="publishthis-post-image-size" id="publishthis-image-size-maxwidth" value="custom_max" /></label>
							Max Width <input type="text" name="publishthis-post-image-max-width"
							id="publishthis-post-image-max-width" class="check-for-int layout-inline-item" value="0" size="5" maxlength="4" />						
					</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="publishthis-override-custom-images"><?php _e( 'Custom Images', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="checkbox" name="publishthis-override-custom-images" id="publishthis-override-custom-images" value="1" /> Override user uploaded widths and heights?</label></li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="publishthis-ok-resize-previews"><?php _e( 'Preview Images', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="checkbox" name="publishthis-ok-resize-previews" id="publishthis-ok-resize-previews" value="1" /> Resize preview images to max width</label></li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="publishthis-use-caption-shortcode"><?php _e( 'Caption', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="checkbox" name="publishthis-use-caption-shortcode" id="publishthis-use-caption-shortcode" value="1" /> Use shortcode for captions</label></li>
				</ul>
			</td>
		</tr>
	</table>
	<input type="button" value="Save" id="layout-image-save" class="button button-primary button-large" name="layout-image-save" />
</div>