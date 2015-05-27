<?php
/**
 * Process Widgets and Shortcodes rendering
 */

global $pt_content;
global $publishthis;

$index = 1;
$max_width_images = isset($pt_content['max_width_images']) ? $pt_content['max_width_images'] : 120;

echo '<p class="widget-title">'.( isset( $pt_content['title'] ) ? $pt_content['title'] : '' ).'</p>';
if ( $pt_content['columns_count'] > 1 ) {
	echo '<div class="ptgrid-container"
			data-columns-count="' . $pt_content['columns_count'] . '"
			data-resize="' . $pt_content['ok_resize_previews'] . '"
			data-max-image-width="' . $max_width_images . '">';
}

foreach ( $pt_content['result'] as $row_key=>$row_val ) {
	if ( $row_val->contentType=='tweet' ) {
		if ( $pt_content['columns_count'] > 1 ) {
			echo '<div class="pt-box pt-' . $publishthis->utils->getNumberName($pt_content['columns_count']) . '-column">';
		}
		echo "<blockquote class=\"twitter-tweet\"><p>" . $row_val->statusText . "</p>";
		echo "&mdash; Twitter  (@" . $row_val->userScreenName . ") <a href=\"" . $row_val->statusUrl . "\" data-datetime=\"" . $row_val->publishDate . "\">" . $row_val->publishDate . "</a></blockquote>";
		if ( $pt_content['columns_count'] > 1 ) {
			echo "</div>";
		}
	}
	else {
		echo $publishthis->utils->drawContentItem( $index++, $row_val );
	}
}

if ( $pt_content['columns_count'] > 1 ) {
	echo '</div>';
}
?>
