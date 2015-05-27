<div id="readmoreLayoutSettings" class="hidden">
	<h2>Read More Link</h2>
	<table class="publishthis-input widefat publishthis-popup">		
		<tr>
			<td class="label"><label for="publishthis-read-more-field"><?php _e( 'Display Text', 'publishthis' ); ?></label>
			</td>
			<td><input type="text" name="publishthis_publish_action[read_more]"
				id="publishthis-read-more-field" value="<?php echo $metabox['_publishthis_read_more']; ?>" /></label>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="layout-readmore-publisher"><?php _e( 'Include Publisher', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="radio" name="publishthis-readmore-publisher" class="publishthis-readmore-publisher" value="1" /> Yes</label></li>
					<li><label><input type="radio" name="publishthis-readmore-publisher" class="publishthis-readmore-publisher" value="0" /> No</label></li>
				</ul>				
			</td>
		</tr>
		<tr>
			<td class="label"><label for="layout-readmore-newwindow"><?php _e( 'Open New Window', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio horizontal">
					<li><label><input type="radio" name="publishthis-readmore-newwindow" class="publishthis-readmore-newwindow" value="1" /> Yes</label></li>
					<li><label><input type="radio" name="publishthis-readmore-newwindow" class="publishthis-readmore-newwindow" value="0" /> No</label></li>
				</ul>				
			</td>
		</tr>
		<tr>
			<td class="label"><label for="layout-readmore-nofollow"><?php _e( 'Wrap Link with "No Follow"', 'publishthis' ); ?></label>
			</td>
			<td>
				<a id="nofollow" href="http://support.google.com/webmasters/bin/answer.py?hl=en&answer=96569" target="_blank">What's this?</a>
				<ul class="radio_list radio horizontal">
					<li><label><input type="radio" name="publishthis-readmore-nofollow" class="publishthis-readmore-nofollow" value="1" /> Yes</label></li>
					<li><label><input type="radio" name="publishthis-readmore-nofollow" class="publishthis-readmore-nofollow" value="0" /> No</label></li>
				</ul>				
			</td>
		</tr>
	</table>
	<input type="button" value="Save" id="layout-readmore-save" class="button button-primary button-large" name="layout-readmore-save" /><br /><br />
</div>
