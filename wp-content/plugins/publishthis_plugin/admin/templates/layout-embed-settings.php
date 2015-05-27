<div id="embedLayoutSettings" class="hidden">
	<h2>Embed</h2>
	<table class="publishthis-input widefat publishthis-popup">
		<tr>
			<td class="label"><label for="layout-embed-size-field"><?php _e( 'Size', 'publishthis' ); ?></label>
			</td>
			<td>
				<ul class="radio_list radio vertical">
					<li>
						<label style="vertical-align: sub;">
							<input type="radio" style="vertical-align: baseline;" value="default" id="publishthis-size" class="publishthis-size" name="publishthis-size" /> Default
						</label>
					</li>
					<li>
						<label>
							<input type="radio" value="custom" id="publishthis-size" class="publishthis-size" name="publishthis-size" />
							Width <input type="text" class="check-for-int layout-inline-item" maxlength="4" size="5" value="0" id="publishthis-width" name="publishthis-width">
							Height <input type="text" class="check-for-int layout-inline-item" maxlength="4" size="5" value="0" id="publishthis-height" name="publishthis-height">
						</label>
					</li>
					<li>
						<label>
							<input type="radio" checked="checked" value="custom_max" id="publishthis-size" class="publishthis-size" name="publishthis-size">
							Max Width <input type="text" class="check-for-int layout-inline-item" maxlength="4" size="5" value="0" id="publishthis-max-width" name="publishthis-max-width" />
						</label>
					</li>
				</ul>				
			</td>
		</tr>
	</table>
	<input type="button" value="Save" id="layout-embed-save" class="button button-primary button-large" name="layout-embed-save" />
</div>
