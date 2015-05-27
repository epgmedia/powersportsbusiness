<?php
/**
 * Process Widgets and Shortcodes options popup rendering
 */
?>
<div class="automated-dialog">
	<?php /* Render Title block */ ?>
	<p class="wide">
		<label for="<?php echo $obj->get_field_id( 'title' );?>">Title:</label>
		<input type="text" name="<?php echo $obj->get_field_name( 'title' );?>" value="<?php echo $instance['title']; ?>" id="<?php echo $obj->get_field_id( 'title' );?>" />
	</p>
	<?php /* Render Title block end*/ ?>

	<?php /* Render Topic-specific block */ ?>
	<?php $instance['topic_name'] = isset($instance['topic_name']) ? $instance['topic_name'] : ''; ?>
	<?php  if ( $content_type=='topic' ) { ?>			
	<p class="wide">
		<label for="<?php echo $obj->get_field_id( 'topic_name' );?>">Topic Name:</label>
		<input class="widefat topic-name-field" type="text" id="<?php echo $obj->get_field_id( 'topic_name' );?>" name="<?php echo $obj->get_field_name( 'topic_name' );?>" value="<?php echo $instance['topic_name']; ?>" />
		<span class="mceActionPanel">
			<a href="#" class="button topic-name-button">Search Topics</a>
			<img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" class="publishthis-ajax-img" />
		</span><br />
	</p>
	<p class="wide">
		<label for="<?php echo $obj->get_field_id( 'topic' );?>">Topic:</label>
		<select class="topic-id-field" name="<?php echo $obj->get_field_name( 'topic' );?>" id="<?php echo $obj->get_field_id( 'topic' );?>" data-current="<?php echo esc_attr( $instance['topic_id'] ); ?>">
			<?php echo ( $topic_options ) ? $topic_options :'<option value="0">No topics found</option>'; ?>
		</select>
	</p>
	<?php } ?>
	<?php /* Render Topic-specific block end */ ?>

	<?php /* Render SavedSearch-specific block */ ?>
	<?php  if ( $content_type=='savedsearch' ) { ?>
	<p class="wide">
		<label for="<?php echo $obj->get_field_id( 'bundle_id' );?>">Saved Search <span class="asterisk">*</span>:</label>
		<?php if( !isset( $saved_searches ) ) { ?>
			<img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" class="publishthis-ajax-img publishthis-ajax-img-inline" />
		<?php } ?>
		<select name="<?php echo $obj->get_field_name( 'bundle_id' );?>" id="<?php echo $obj->get_field_id( 'bundle_id' );?>" size="1">
			<?php if( isset( $saved_searches ) ) { ?>
				<option value="-1" <?php selected( $instance['bundle_id'], '-1' ); ?>>None</option>
				<?php foreach ( $saved_searches as $saved_search ) : 
						$displaySearch = strlen($saved_search->displayName) > 30 ? substr($saved_search->displayName, 0, 30).'...' : $saved_search->displayName;
				?>
					<option value="<?php echo $saved_search->bundleId; ?>" <?php selected( $saved_search->bundleId, $instance['bundle_id'] ); ?>><?php echo $displaySearch; ?></option>
				<?php endforeach; ?>
			<?php } ?>
		</select>
	</p>
	<?php } ?>
	<?php /* Render SavedSearch-specific block end */ ?>

	<?php /* Render Feeds-Tweets-specific block */ ?>
	<?php  if ( $content_type=='feed' || $content_type=='curatedfeed' || $content_type=='tweet' ) { ?>
	<p class="wide">
		<label for="<?php echo $obj->get_field_id( 'published_feeds' );?>">Published Mixes <span class="asterisk">*</span>:</label>
		<img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" class="publishthis-ajax-img publishthis-ajax-img-inline" />
		<select name="<?php echo $obj->get_field_name( 'feed_id' );?>" id="<?php echo $obj->get_field_id( 'published_feeds' );?>" size="1">
			<?php if( isset( $feeds ) ) { ?>
			<option value="-1" <?php selected( $instance['feed_id'], '-1' ); ?>>None</option>
			<?php 
				foreach ( $feeds as $feed ) {
					$skip = false;
					if($content_type=='feed' && !$feed['automatedContentOn']) $skip = true;
					if($content_type=='tweet' && !$feed['automatedTwitterOn']) $skip = true;
															
					if( !$skip ) {
						$displayFeed = strlen($feed['displayName']) > 30 ? substr($feed['displayName'], 0, 30).'...' : $feed['displayName'];
			?>
						<option value="<?php echo $feed['feedId']?>" <?php selected( $feed['feedId'], $instance['feed_id'] ); ?>><?php echo $displayFeed; ?></option>
			<?php
					} 
				} 
			}
			?>
		</select>
	</p>
	<?php } ?>
	<?php /* Render Feeds-Tweets-specific block end */ ?>

	<?php /* Render Mix Defaults block */ ?>
	<?php  if ( $content_type=='feed' || $content_type=='tweet' ) { ?>
	<p>
		<input type="hidden" name="<?php echo $obj->get_field_name( 'mix_defaults' )?>" value="0" />
		<input class="checkbox" type="checkbox" id="<?php echo $obj->get_field_id( 'mix_defaults' )?>" name="<?php echo $obj->get_field_name( 'mix_defaults' )?>" value="1" <?php checked( $instance['mix_defaults'], '1' );?> />
		<label for="<?php echo $obj->get_field_id( 'mix_defaults' )?>">Use Mix Defaults</label>
	</p>
	<?php } ?>
	<?php /* Render Mix Defaults block end */ ?>

	<?php /* Render Sort By block */ ?>
	<?php  if ( $content_type!='curatedfeed') { ?>
	<p>
		<label for="<?php echo $obj->get_field_id( 'sort_by' );?>">Sort By:</label>
		<select name="<?php echo $obj->get_field_name( 'sort_by' );?>" id="<?php echo $obj->get_field_id( 'sort_by' );?>">
			<?php
			global $pt_sort_by, $pt_tweets_sort_by;
			$_sort_by = ( $content_type=='tweet' ) ? $pt_tweets_sort_by : $pt_sort_by;

			foreach ( $_sort_by as $sb_key=>$sb_val ) {
				echo '<option value="'.$sb_key.'" '.( selected( $sb_key, $instance['sort_by'] ) ).'>'.$sb_val.'</option>';
			}
			?>
		</select>
	</p>
	<?php } ?>
	<?php /* Render Sort By block end */ ?>

	<?php /* Render Content Types block */ ?>
	<?php  if ( $content_type!='tweet' && $content_type!='feed' && $content_type!='curatedfeed') { ?>
	<p>
		<label for="<?php echo $obj->get_field_id( 'content_types' ); ?>">Content Types:</label>
		<select name="<?php echo $obj->get_field_name( 'content_types' ); ?>" id="<?php echo $obj->get_field_id( 'content_types' ); ?>">
		<?php
		global $pt_content_types;
		foreach ( $pt_content_types as $ct_key=>$ct_val ) {
			echo '<option value="'.$ct_key.'" '.( selected( $ct_key, $instance['content_types'] ) ).'>'.$ct_val.'</option>';
		}
		?>
		</select>
	</p>
	<?php } ?>
	<?php /* Render Content Types block end */ ?>

	<?php /* Render Cache Interval block */ ?>
	<p>
		<label for="<?php echo $obj->get_field_id( 'cache_interval' ); ?>">Cache Interval:</label>
		<select name="<?php echo $obj->get_field_name( 'cache_interval' ); ?>" id="<?php echo $obj->get_field_id( 'cache_interval' ); ?>">
			<?php
			global $pt_cache_interval;
			foreach ( $pt_cache_interval as $ci_key=>$ci_val ) {
				echo '<option value="'.$ci_key.'" '.( selected( $ci_key, $instance['cache_interval'] ) ).'>'.$ci_val.'</option>';
			}
			?>
		</select>
	</p>
	<?php /* Render Cache Interval block end */ ?>

	<?php  if ( $content_type!='tweet' && $content_type!='curatedfeed') { ?>
	<p>
		<input type="hidden" name="<?php echo $obj->get_field_name( 'remove_duplicates' )?>" value="0" />
		<input class="checkbox" type="checkbox" id="<?php echo $obj->get_field_id( 'remove_duplicates' )?>" name="<?php echo $obj->get_field_name( 'remove_duplicates' )?>" value="1" <?php checked( $instance['remove_duplicates'], '1' );?> />
		<label for="<?php echo $obj->get_field_id( 'remove_duplicates' )?>">Remove Duplicates</label>
	</p>

	<p>
		<input type="hidden" name="<?php echo $obj->get_field_name( 'remove_related' )?>" value="0" />
		<input class="checkbox" type="checkbox" id="<?php echo $obj->get_field_id( 'remove_related' )?>" name="<?php echo $obj->get_field_name( 'remove_related' )?>" value="1" <?php checked( $instance['remove_related'], '1' );?> />
		<label for="<?php echo $obj->get_field_id( 'remove_related' )?>">Remove Related</label>
	</p>
	<?php } ?>

	<p class="automated-title">Layout Options</p>

	<?php /* Render Number of Results to Display block */ ?>
	<p>
		<label for="<?php echo $obj->get_field_id( 'num_results' );?>">Number of Results to Display:</label>
		<select name="<?php echo $obj->get_field_name( 'num_results' );?>" id="<?php echo $obj->get_field_id( 'num_results' );?>">
			<?php
			global $pt_num_results;
			foreach ( $pt_num_results as $nr_key=>$nr_val ) {
				echo '<option value="'.$nr_key.'" '.( selected( $nr_key, $instance['num_results'] ) ).'>'.$nr_val.'</option>';
			}
			?>
		</select>
	</p>
	<?php /* Render Number of Results to Display block end */ ?>

	<?php /* Render Columns Count to Display block */ ?>
	<p>
		<label for="<?php echo $obj->get_field_id( 'columns_count' );?>">Columns Count to Display:</label>
		<select name="<?php echo $obj->get_field_name( 'columns_count' );?>" id="<?php echo $obj->get_field_id( 'columns_count' );?>">
		<?php
		global $pt_columns_count;
		foreach ( $pt_columns_count as $cc_key=>$cc_val ) {
			echo '<option value="'.$cc_key.'" '.( selected( $cc_key, $instance['columns_count'] ) ).'>'.$cc_val.'</option>';
		}
		?>
		</select>
	</p>
	<?php /* Render Columns Count to Display block end */ ?>

	<?php  if ( $content_type!='tweet' ) { ?>
	<hr />

	<?php /* Render options checkboxes block */ ?>
	<?php
	global $pt_call_options;
	foreach ( $pt_call_options as $key=>$val ) {
		echo '<p>'.
				'<input type="hidden" name="'.$obj->get_field_name( $key ).'" value="0" />'.
				'<input class="checkbox" type="checkbox" ';?><?php checked( $instance[$key], '1' );?> <?php echo ' id="'.$obj->get_field_name( $key ).'" name="'.$obj->get_field_name( $key ).'" value="'.$val['value'].'" /> '.
				'<label for="'.$obj->get_field_id( $key ).'">'.$val['label'].'</label>'.
			'</p>';
	}
	?>
	<?php /* Render options checkboxes block end */ ?>

	<hr />

	<?php /* Render Images block */ ?>
	<p class="automated-title">Image Options</p>

	<p>
		<input type="hidden" name="<?php echo $obj->get_field_name( 'show_photos' )?>" value="0" />
		<input class="checkbox" type="checkbox" id="<?php echo $obj->get_field_id( 'show_photos' )?>" name="<?php echo $obj->get_field_name( 'show_photos' )?>" value="1" <?php checked( $instance['show_photos'], '1' );?> />
		<label for="<?php echo $obj->get_field_id( 'show_photos' )?>">Show Photos</label>
	</p>

	<p>
		<label for="<?php echo $obj->get_field_id( 'image_align' );?>">Images Alignment:</label>
		<select id="<?php echo $obj->get_field_id( 'image_align' );?>" name="<?php echo $obj->get_field_name( 'image_align' );?>">
			<option value="left" <?php echo selected( 'left', $instance['image_align'] ); ?>>Left</option>
			<option value="center" <?php echo selected( 'center', $instance['image_align'] ); ?>>Center</option>
			<option value="right" <?php echo selected( 'right', $instance['image_align'] ); ?>>Right</option>
		</select>
	</p>
	<p>
		<label for="automated_width">Size for Images:</label>
	</p>
	
	<ul class="automated_width" id="automated_width">
		<li>
			<label>
				<input type="radio" name="<?php echo $obj->get_field_name( 'image_size' );?>" class="<?php echo $obj->get_field_id( 'image_size' );?>" value="default" <?php checked( $instance['image_size'], 'default' );?> /> Theme Default
			</label>
		</li>
		<li>
			<label>
				<input type="radio" name="<?php echo $obj->get_field_name( 'image_size' );?>" class="<?php echo $obj->get_field_id( 'image_size' );?>" value="custom" <?php checked( $instance['image_size'], 'custom' );?> /></label>
				Width <input type="text" name="<?php echo $obj->get_field_name( 'image_width' );?>" id="<?php echo $obj->get_field_id( 'image_width' );?>" class="check-for-int layout-inline-item" value="<?php echo intval($instance['image_width']); ?>" size="5" maxlength="4" />
				Height <input type="text" name="<?php echo $obj->get_field_name( 'image_height' );?>" id="<?php echo $obj->get_field_id( 'image_height' );?>" class="check-for-int layout-inline-item" value="<?php echo intval($instance['image_height']); ?>" size="5" maxlength="4" />
		</li>
		<li>
			<label>
				<input type="radio" name="<?php echo $obj->get_field_name( 'image_size' );?>" class="<?php echo $obj->get_field_id( 'image_size' );?>" value="custom_max" <?php checked( $instance['image_size'], 'custom_max' );?> /></label>
				Max Width <input type="text" name="<?php echo $obj->get_field_name( 'max_width_images' );?>" id="<?php echo $obj->get_field_id( 'max_width_images' );?>" class="check-for-int layout-inline-item" value="<?php echo intval($instance['max_width_images']); ?>" size="5" maxlength="4" />
		</li>
	</ul>
	<?php /* Render Images block end */ ?>

	<?php } ?>

</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		try {

		jQuery("input[id*='mix_defaults']").on('change', function() {
			var el = jQuery(this),
				parent = el.parents('div.widget'),
				disabled = el.is(':checked') ? true : false;

			parent.find("select[id*='sort_by'], input[id*='remove_duplicates'], input[id*='remove_related']").attr('disabled', disabled);
		});
		jQuery("input[id*='mix_defaults']").each(function(el){
			var el = jQuery(this),
				parent = el.parents('div.widget'),
				disabled = el.is(':checked') ? true : false;

			parent.find("select[id*='sort_by'], input[id*='remove_duplicates'], input[id*='remove_related']").attr('disabled', disabled);
		});
		} catch(e) {};
	});
</script>