<?php
/**
 * Render automated content as div layout.
 * Used for shortcodes and widgets output rendering
 */

global $pt_content;
global $content_item_id;
global $content_item;
global $publishthis;

$result = $content_item;

$alt = isset( $result->title ) ? $result->title : 'PublishThis image';

$result->url = isset($result->url) ? $result->url : '';
$result->title = isset($result->title) ? $result->title : '';

$strImageUrl = null;
if ( isset( $result->imageUrl ) && !empty( $result->imageUrl ) ) {
	if( $pt_content['image_size'] == 'default' ) {
		$strImageUrl = $result->imageUrl;
	}
	else {
		$imageUrl = $publishthis->utils->getContentPhotoUrl( $result );
		if( $pt_content['image_size'] == 'custom' ) {
			$strImageUrl = $publishthis->utils->getResizedPhotoUrl ( $imageUrl, $pt_content['image_width'], $pt_content['ok_resize_previews'], $pt_content['image_height'] );	
		}
		elseif( $pt_content['image_size'] == 'custom_max' ) {
			$strImageUrl = $publishthis->utils->getResizedPhotoUrl ( $imageUrl, $pt_content['max_width_images'], $pt_content['ok_resize_previews'] );	
		}
	}
}

//put tracking on our link
$pt_content['feedId'] = isset($pt_content['feedId']) ? $pt_content['feedId'] : -1;
$ptItemLink = $publishthis->utils->build_url_with_tracking($result->url, $pt_content['feedId'],false,$result->docId,$result->contentType,$pt_content['type']);

?>
<?php if ( $pt_content['columns_count'] > 1 ) { ?>
<div class="pt-box pt-<?php echo $publishthis->utils->getNumberName($pt_content['columns_count'])?>-column">
<?php } ?>
<?php $no_follow = $pt_content['show_nofollow'] ? 'rel="nofollow"' : ''; ?>
	<?php if ( isset( $strImageUrl ) && $pt_content['show_photos'] ) { ?>
		<p class="pt-automated pt-image">
			<a href="<?php echo esc_url( $ptItemLink ); ?>" target="_blank" <?php echo $no_follow; ?> class="pt-imgcontent-link">
				<img src="<?php echo esc_url( $strImageUrl ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="pt-image <?php echo esc_attr( 'align'.$pt_content['image_align'] ); ?>" />
			</a>
		</p>
	<?php } ?>
	<?php if ( $pt_content['show_links'] ) { ?>
		<p class="pt-automated pt-title">
			<a href="<?php echo esc_url( $ptItemLink  ) ?>" target="_blank" <?php echo $no_follow; ?> class="pt-content-link"><?php echo esc_html( $result->title ) ?></a>
		</p>
	<?php } else { ?>
		<p class="pt-automated pt-title"><?php echo esc_html( $result->title ); ?></p>
	<?php } ?>

	<?php if(( isset( $result->publishDate ) && $pt_content['show_date'] ) || ( isset( $result->publisher ) && $pt_content['show_source'] )) { ?>
	<p class="pt-automated pt-publishdate">
		<?php 
		if ( isset( $result->publishDate ) || isset( $result->publisher ) ) {
			echo '<span>';
			if ( isset( $result->publishDate ) && $pt_content['show_date'] )  echo $publishthis->utils->getElapsedPrettyTime( $result->publishDate );				
			if ( isset( $result->publisher ) && $pt_content['show_source'] )  echo '&nbsp;via <strong>' . esc_html( $result->publisher ) . '</strong>';
			echo '</span>';
		}?>
	</p>
	<?php } ?>
	<?php 
		if ( isset( $result->summary ) && $pt_content['show_summary'] ) {
			echo '<p class="pt-automated pt-summary">' . esc_html( $result->summary ) . '</p>';
		} 
	?>

	<p class="clear">&nbsp;</p>
<?php if ( $pt_content['columns_count'] > 1 ) { ?>
</div>

<?php } ?>
