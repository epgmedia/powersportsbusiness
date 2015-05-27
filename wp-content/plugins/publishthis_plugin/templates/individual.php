<?php
// turn off errors displaying
error_reporting(0);

global $publishthis;
global $pt_content;
global $pt_content_features;

// include render helpers to display blocks
require_once $publishthis->plugin_path() . '/classes/class-render.php';
$render = new Publishthis_Render();

// work with the section content need to know the type,
// because each type has slightly different display needs
$sectionContent = '';
$sectionContent .= $render->display_publishdate();

//check that annotation is not set as post excerpt
if ( $pt_content_features ['annotation_placement'] != '1' ) {
	if ($pt_content_features['create_excerpt'] == '0'){
		$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
	}else{
		$sectionContent .= $render->display_annotation();
	}
}

switch ( $pt_content->contentType ) {
case 'video':
	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1') {
		$sectionContent .= '<!--more-->';
	}
	$sectionContent .= $render->display_read_more();
	break;

case 'tweet':
	$sectionContent .= $render->display_tweet();
	break;

case 'photo':
	
	
	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1' && strlen($pt_content->summary)>0) {
		$sectionContent .= '<!--more-->';
	}
	break;

case 'text':
	
	
	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	$sectionContent .= $render->display_text();
	break;

default:
	// do the default display. assume that it is an article, but could also just
	// be an unknown content type
	$sectionContent .= "<!-- default view -->";
	
	
	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1') {
		$sectionContent .= '<!--more-->';
	}
	$sectionContent .= $render->display_read_more();
	break;
}

//check that annotation is not set as post excerpt
if ( $pt_content_features ['annotation_placement'] == '1' ) {
	if ($pt_content_features['create_excerpt'] == '0'){
		$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
	}else{
		$sectionContent .= $render->display_annotation();
	}
}

echo $sectionContent;
?>
