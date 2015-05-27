<?php
/* Helper functions to render posts in Individual and Combined modes */

class Publishthis_Render {
	private $publishthis;
	private $pt_content;
	private $pt_content_features;

	function __construct() {
		global $publishthis;
		global $pt_content;
		global $pt_content_features;

		$this->publishthis = $publishthis;
		$this->pt_content = $pt_content;
		$this->pt_content_features = $pt_content_features;
	}

	/**
	 *   Title block
	 */
	function display_title() {
		//render title comment inside h4, otherwise that produce redundant <br> tags in output
		$html = '';
		if ( array_search( 'title', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}
		if ( strlen( $this->pt_content->title ) > 0 ) {
			$html .= '<h4 class="pt-title">';
			if ( isset ( $this->pt_content->url ) && ( ! empty ( $this->pt_content->url ) ) && $this->pt_content_features['styles']['title']->titleLayoutSettings->clickable == "1" ) {
				$nofollow = $this->pt_content_features['styles']['title']->titleLayoutSettings->nofollow == "1" ? 'rel="nofollow"' : '';
				$html .= '<a href="' . $this->publishthis->utils->build_url_with_tracking($this->pt_content->url, $this->pt_content->feedId,true,$this->pt_content->docId,$this->pt_content->contentType)  . '" target="_blank" ' . $nofollow . '>' . $this->pt_content->title . '</a>';
			} else {
				$html .= '' . $this->pt_content->title;
			}
			$html .= "</h4>";
		}
		
		return $html;
	}

	/**
	 *   Title basic block
	 */
	function display_basic_title() {
		$html = '';
		if ( array_search( 'title', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}
		if ( strlen( $this->pt_content->title ) > 0 ) {
			$html .= '<p class="pt-basictitle">';
			if ( isset ( $this->pt_content->url ) && ( ! empty ( $this->pt_content->url ) ) && $this->pt_content_features['styles']['title']->titleLayoutSettings->clickable == "1" ) {
				$nofollow = $this->pt_content_features['styles']['title']->titleLayoutSettings->nofollow == "1" ? 'rel="nofollow"' : '';
				$html .= '<a href="' . $this->publishthis->utils->build_url_with_tracking($this->pt_content->url, $this->pt_content->feedId,true,$this->pt_content->docId,$this->pt_content->contentType)  . '" target="_blank" ' . $nofollow . '>' . $this->pt_content->title . '</a>';
			} else {
				$html .= '' . $this->pt_content->title;
			}
			$html .= "</p>";
		}
		
		return $html;
	}
	

	/**
	 *   publishDate block
	 */
	function display_publishdate() {
		$html = '';
		if ( array_search( 'publishdate', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;

		}
		if ( isset ( $this->pt_content->publishDate ) ) {
			$html .= '<p class="pt-publishdate">' . $this->publishthis->utils->getElapsedPrettyTime( $this->pt_content->publishDate ) . '</p>';
		}

		$html .= "";
		return $html;
	}

	/**
	 *   Annotation block
	 */
	function display_annotation() {
		$html = '';
		if ( array_search( 'annotation', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}

		$settings = $this->pt_content_features['styles']['annotation']->annotationLayoutSettings;
		if ( isset ( $this->pt_content->annotations ) ) {
			if ( count( $this->pt_content->annotations ) > 0 ) {
				if ( isset( $settings->annotation_title_text ) && strlen( $settings->annotation_title_text )>0 ) {
					$html .= '<p class="pt-annotation-title pt-annotation-title-h-' . $settings->annotation_title_alignment->horizontal . ' pt-annotation-title-v-' . $settings->annotation_title_alignment->vertical . '">' . $settings->annotation_title_text . '</p>';
				}
				$html .= '<p class="pt-annotation">' . $this->pt_content->annotations[0]->annotation . '</p>';
			}
		}

		$html .= "";
		return $html;
	}

	/**
	 *   Image block
	 *   align left and right is easy, but align center
	 *   has wordpress adding some <p> tag with text alignment added to it
	 */
	function display_image() {
		global $pt_is_first;
		global $pt_found_featured_image;


		$html = '';
		// html_body_image=1 - Include image to post body
		// otherwise:
		// for individual post - not show image for the curated content;
		// for digest post - not place the image for the first curated item.
		if ( array_search( 'image', $this->pt_content_features['include_styles'] )===false || $this->pt_content_features['html_body_image'] != '1' ) {
			if ( $this->pt_content_features['format'] == 'individual' || $this->pt_content_features['format'] == 'combined' && $pt_found_featured_image ) {

				$html .= "";
				return $html;
			}
		}

		$settings = $this->pt_content_features['styles']['image']->imageLayoutSettings;

		$sourceImg = null;

		if ( isset( $this->pt_content->imageUrl ) || isset( $this->pt_content->imageUrlThumbnail ) ) {
			$sourceImg = $this->pt_content->imageUrl;
			if ( $settings->size !== 'default' && $settings->override_custom_images == "1" ) {
				//$sourceImg = $this->publishthis->utils->getContentPhotoUrl( $this->pt_content );
				//if we are allowing image resizing and we can over-ride any client images, just force the thumbnail view
				//for resizing
				$sourceImg = $this->pt_content->imageUrlThumbnail;
			}
		}

		$isClientImage = preg_match('/_clientimage_/i', $sourceImg) ? true : false;
		if ( $settings->override_custom_images == "1" || ( !isset( $this->pt_content->imageUrlPublisher ) && !$isClientImage ) ) {
			if ( $settings->size == 'custom' ) {
				$sourceImg = $this->publishthis->utils->getResizedPhotoUrl ( $sourceImg, $settings->width, $settings->ok_resize_previews, $settings->height );
			}
			elseif ( $settings->size == 'custom_max' ) {
				$sourceImg = $this->publishthis->utils->getResizedPhotoUrl ( $sourceImg, $settings->max_width, $settings->ok_resize_previews );
			}
		}

		if ( isset( $sourceImg ) && !empty( $sourceImg ) ) {
			if ( (isset ( $this->pt_content->photoCaption ) ) && (!empty($this->pt_content->photoCaption))) {
				if ( $settings->size == 'default' || strpos( $sourceImg, 'W=' ) === false ) {
					//get image real size
					$image = wp_get_image_editor( $sourceImg );
					if ( ! is_wp_error( $image ) ) {
						$dims = $image->get_size();
					}
					else {
						$dims['width'] = '100%';						
					}
					$caption_width_attr = 'width="' . $dims['width'] . '"';		
					$caption_width_pt_attr = ' style="width:' . $dims['width'] . 'px;"';
				}
				else {
					$caption_width = $settings->size == 'custom' ? $settings->width : $settings->max_width;
					$caption_width_attr = 'width="' . $this->publishthis->utils->getPhotoCaptionWidth( $sourceImg, $caption_width ) . '"';
					$caption_width_pt_attr = ' style="width:' . $this->publishthis->utils->getPhotoCaptionWidth( $sourceImg, $caption_width ) . 'px;"';
				}
				if( $settings->use_caption_shortcode == "1" ) {
					$html .= '[caption class="pt_image_caption" id="img-' . $this->pt_content->docId . '" align="align' . ( $settings->aligment!='default' ? $settings->aligment : 'left' ) . '" '. $caption_width_attr .' caption="' . $this->pt_content->photoCaption . '"]';					
				}
				else {
					$html .= '<div class="pt_image_caption wp-caption align' . ( $settings->aligment!='default' ? $settings->aligment : 'left' ) . '">';
				}				
			}

			if( ($settings->use_caption_shortcode == "0" ) || (empty($this->pt_content->photoCaption))) {
				$html .= '<p class="pt_image">';
			}
			
			if ( !empty( $this->pt_content->url ) ) {
				$html .= '<a href="' . $this->publishthis->utils->build_url_with_tracking($this->pt_content->url, $this->pt_content->feedId,true,$this->pt_content->docId,$this->pt_content->contentType)  . '" rel="nofollow" target="_blank">';
		
			}

			$class = $settings->aligment!='default' ? 'class="align' . $settings->aligment . '"' : '';

			$html .= '<img ' . $class . ' src="' . $sourceImg . '"/>';

			if ( !empty( $this->pt_content->url ) ) {
				$html .= '</a>';
			}

			if( ($settings->use_caption_shortcode == "0" ) || (empty($this->pt_content->photoCaption))) {
				$html .= '</p>';
			}
			

			if ( isset ( $this->pt_content->photoCaption ) && !empty($this->pt_content->photoCaption) && $settings->use_caption_shortcode == "1" ) {
				$html .= '[/caption]';
			}
			else {
				//render pt image caption
				if( (isset ( $this->pt_content->photoCaption ) ) && (!empty($this->pt_content->photoCaption))) {
					$html .= '<p class="pt_image_caption_text wp-caption-text ' . ($settings->aligment!='default' ? 'align' . $settings->aligment : '') . '">' . $this->pt_content->photoCaption . '</p>';	
				$html .= '</div>';
			}	
				
			}	
		}

		$html .= "";
		return $html;
	}

	/**
	 *   Embed Object block
	 */
	function display_embed_object() {
		$html = '';
		if ( array_search( 'embed', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}
		$settings = $this->pt_content_features['styles']['embed']->embedLayoutSettings;
		$use_caption_shortcode = $this->pt_content_features['styles']['image']->imageLayoutSettings->use_caption_shortcode;

		$embed = $this->pt_content->embed;

		switch ( $settings->size ) {
			case 'custom':
				$size = 'width="' . $settings->width . '" height="' . $settings->height . '"';
				$caption_width_pt_attr = ' style="width:' . $settings->width . 'px;"';

				$embed = preg_replace( '/width="(\d+)"/i', 'width="'.$settings->width.'"', $embed);
				$embed = preg_replace( '/height="(\d+)"/i', 'height="'.$settings->height.'"', $embed);
				break;
			case 'custom_max':
				$size = 'width="' . $settings->max_width . '"';
				$caption_width_pt_attr = ' style="width:' . $settings->max_width . 'px;"';
				
				preg_match( '/width="(\d+)"/i', $embed, $old_size);
				$old_width = intval( $old_size[1] );

				preg_match( '/height="(\d+)"/i', $embed, $old_size);
				$old_height = intval( $old_size[1] );

				$embed = preg_replace( '/width="(\d+)"/i', 'width="'.$settings->max_width.'"', $embed);
				$embed = preg_replace( '/height="(\d+)"/i', 'height="'.($old_height*$settings->max_width/$old_width).'"', $embed);
				break;
			default:
				$size = '';
				$caption_width_pt_attr = '';
				break;
		}

		$iAligment = $this->pt_content_features['styles']['image']->imageLayoutSettings->aligment!='default' ? $this->pt_content_features['styles']['image']->imageLayoutSettings->aligment : 'left';
		//open main div
		if( $settings->size == 'default' ) {
			$html = '<p class="pt-video pt-video-defaultwidth pt_embed align' . $iAligment . '">';
		}
		else {
			$html = '<p class="pt-video pt_embed align' . $iAligment . '"'.(strlen( $this->pt_content->photoCaption )>0 ? '' : $caption_width_pt_attr).'>';
		}


    /*
		if ( isset ( $this->pt_content->photoCaption ) ) {
			if( $use_caption_shortcode == "1" ) {
				$html .= '[caption align="'. $iAligment .'" '. $size . ' caption="' . esc_html($this->pt_content->photoCaption) . '"]';
			}
			else {
				$html .= '<div class="pt_image_caption wp-caption align' . $iAligment . '"'.$caption_width_pt_attr.'>';
			}				
		}
		
		if( $use_caption_shortcode == "0" ) {
			$html .= '<p class="pt_embed">';
		}
		*/
		
		
		$html .= '[ptraw]' . esc_html( $embed ) . '[/ptraw]';

    /*
		if( $use_caption_shortcode == "0" ) {
			$html .= '</p>';
		}

		

		if ( isset ( $this->pt_content->photoCaption ) ) {
			if( $use_caption_shortcode == "1" ) {
				$html .= '[/caption]';			}
			else {
				//render pt image caption
				$html .= '<p class="pt_image_caption_text wp-caption-text ' . ($settings->aligment!='default' ? 'align' . $settings->aligment : '') . '">' . $this->pt_content->photoCaption . '</p>';	
				$html .= '</div>';
			}				
		}
	  */
	  
	
		//close main div
		$html .= '</p>';

		return $html;
	}

	/**
	 *   Summary block
	 */
	function display_summary() {
		$html = '';
		if ( array_search( 'summary', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}

		$this->pt_content->summary = trim( $this->pt_content->summary );
		if ( isset( $this->pt_content->summary ) && strlen( $this->pt_content->summary )>0 && $this->pt_content->summary!="<br />" ) {
			$html .= '<p class="pt-summary">' . $this->pt_content->summary . '</p>';
		}

		$html .= "";
		return $html;
	}

	/**
	 *   Read more block
	 */
	function display_read_more() {
		$html = '';
		if ( array_search( 'readmore', $this->pt_content_features['include_styles'] )===false ) {
			$html .= "";
			return $html;
		}
		if ( isset( $this->pt_content->url ) ) {
			$settings = $this->pt_content_features['styles']['readmore']->readmoreLayoutSettings;
			$nofollow = $settings->nofollow == "1" ? 'rel="nofollow"' : '';
			$target = $settings->newwindow == "1" ? 'target="_blank"' : '';
			$publisher = $settings->publisher == "1" && isset( $this->pt_content->publisher ) ? ' at '.$this->pt_content->publisher : '';
			$html .= '<p class="pt-readmore"><a href="' . $this->publishthis->utils->build_url_with_tracking($this->pt_content->url, $this->pt_content->feedId,true,$this->pt_content->docId,$this->pt_content->contentType) . '" ' . $target . ' ' . $nofollow . '>' . $this->pt_content_features["read_more"] . $publisher . '</a></p>';
		}

		$html .= "";
		return $html;
	}

	/**
	 *   Tweet Summary Card block
	 *    https://dev.twitter.com/docs/cards/types/summary-card
	 */
	function display_tweet() {
		$html = '';
		// <blockquote class="twitter-tweet"><p>Search API will now always return
		// "real" Twitter user IDs. The with_twitter_user_id parameter is no longer
		// necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a
		// href="https://twitter.com/twitterapi/status/133640144317198338"
		// data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
		// <script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
		$html .= "<blockquote class=\"twitter-tweet\"><p>" . $this->pt_content->statusText . "</p>";
		$html .= "&mdash; Twitter  (@" . $this->pt_content->userScreenName . ") <a href=\"" . $this->pt_content->statusUrl . "\" data-datetime=\"" . $this->pt_content->publishDate . "\">" . $this->pt_content->publishDate . "</a></blockquote>";

		$html .= "";
		return $html;
	}

	/**
	 *   Text block
	 */
	function display_text() {
		$html = '';
		$html .= balanceTags ( '<p class="pt-text">' . $this->pt_content->text . '</p>', true );

		$html .= "";
		return $html;
	}



	/**
	 * Curated By logo block
	 */
	function display_curated_logo() {
		$html = "";

		if ( intval( $this->publishthis->get_option( 'curatedby' ) ) == 2 ) {
			$html .= $this->publishthis->utils->getCuratedByLogo();
		}

		$html .= "";
		return $html;
	}
}
