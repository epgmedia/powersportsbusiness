<?php
//turn off errors displaying
error_reporting(0);

global $publishthis;
global $pt_content;
global $pt_content_features;
global $pt_break_page;
global $pt_is_first;

// include render helpers to display blocks
require_once $publishthis->plugin_path() . '/classes/class-render.php';
$render = new Publishthis_Render();


if( !$pt_is_first ) {
	$sectionContent = '<div class="pt-box pt-two-column panel-grid-cell-'.$pt_content->curatedContentIndex.'">';
}
else {
	$sectionContent .= '<div class="pt-digest-top-element">';
	if ('text' == $pt_content->contentType){
		//having the first item be text does not need a title	
	}else{
		//$sectionContent .= $render->display_title();
	}
		
}
$sectionContent .= $render->display_publishdate();

if ( !$pt_is_first  && $pt_content_features ['annotation_placement'] != '1' ) {
	if ($pt_content_features['create_excerpt'] == '0'){
		$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
	}else{
		$sectionContent .= $render->display_annotation();
	}
}

switch ( $pt_content->contentType ) {
case 'video':

  if ( $pt_is_first  && $pt_content_features ['annotation_placement'] != '1' ) {
		if ($pt_content_features['create_excerpt'] == '0'){
			$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
		}else{
			$sectionContent .= $render->display_annotation();
		}
	}
	
	if( !$pt_is_first ) {
		$sectionContent .= '<div class="pt-image-with-title">';
		$sectionContent .= $render->display_title();
	}

	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	if( !$pt_is_first ) {
		$sectionContent .= '</div>';
	}



  if ($pt_is_first){
		$sectionContent .= $render->display_basic_title();	
	}
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1'){
		$sectionContent .= '<!--more-->';	
	}
	$sectionContent .= $render->display_read_more();
	break;

case 'tweet':

  if ( $pt_is_first  && $pt_content_features ['annotation_placement'] != '1'  ) {
		if ($pt_content_features['create_excerpt'] == '0'){
			$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
		}else{
			$sectionContent .= $render->display_annotation();
		}
	}

	$sectionContent .= $render->display_tweet();
	break;

case 'photo':
	if( !$pt_is_first ) {
		$sectionContent .= '<div class="pt-image-with-title">';
		$sectionContent .= $render->display_title();
	}

	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	
	if( !$pt_is_first ) {
		$sectionContent .= '</div>';
	}
	
	if ( $pt_is_first  && $pt_content_features ['annotation_placement'] != '1' ) {
		if ($pt_content_features['create_excerpt'] == '0'){
			$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
		}else{
			$sectionContent .= $render->display_annotation();
		}
	}
	if ($pt_is_first){
		$sectionContent .= $render->display_basic_title();	
	}
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1'){
		$sectionContent .= '<!--more-->';	
	}
	break;

case 'text':

  if ( $pt_is_first  && $pt_content_features ['annotation_placement'] != '1' ) {
		if ($pt_content_features['create_excerpt'] == '0'){
			$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
		}else{
			$sectionContent .= $render->display_annotation();
		}
	}
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
	$sectionContent .= "";
	
	if ( $pt_is_first  && $pt_content_features ['annotation_placement'] != '1' ) {
		if ($pt_content_features['create_excerpt'] == '0'){
			$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
		}else{
			$sectionContent .= $render->display_annotation();
		}
	}
	
	if( !$pt_is_first ) {
		$sectionContent .= '<div class="pt-image-with-title">';
		$sectionContent .= $render->display_title();
	}

	if ( (!(array_search( 'embed', $pt_content_features['include_styles'] ) === false)) && (isset( $pt_content->embed ) && ! empty( $pt_content->embed )) ) {
		$sectionContent .= $render->display_embed_object();
	} else {
		$sectionContent .= $render->display_image();
	}
	
	if( !$pt_is_first ) {
		$sectionContent .= '</div>';
	}

  
	if ($pt_is_first){
		$sectionContent .= $render->display_basic_title();	
		}
	$sectionContent .= $render->display_summary();
	if ($pt_content_features['excerpt_more_tag'] == '1'){
		$sectionContent .= '<!--more-->';	
	}
	$sectionContent .= $render->display_read_more();
	break;
}

//if ( !$pt_is_first && $pt_content_features ['annotation_placement'] == '1' ) {
if ( $pt_content_features ['annotation_placement'] == '1' ) {
	if ($pt_content_features['create_excerpt'] == '0'){
		$sectionContent .= "<!--startptremove-->" . $render->display_annotation() . "<!--endptremove-->";
	}else{
		$sectionContent .= $render->display_annotation();
	}
}

//provide space for the next entry after this
$sectionContent = $sectionContent . '<p class="clear pt-spacer"><img src="http://img.publishthis.com/images/empty.gif" alt="" style="border:none;" /></p>';
if ( $pt_break_page ) {
	if ($pt_content_features['excerpt_more_tag'] == '0'){
		$sectionContent .= '<!--more-->';	
	}
}

if( $pt_is_first ) {
	$sectionContent .= '</div><div class="ptgrid-container ptgrid-cols-2 pt-digest-grid">';
}
else {
	$sectionContent .= '</div>';
}

if( $pt_content->curatedContentIndex == $pt_content->curatedContentCount ) {
	$sectionContent .= '</div>';
}
echo $sectionContent; 
?>