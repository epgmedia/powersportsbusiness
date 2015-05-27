var shortcodesFunc = (function(w, $) {
	var adminUrl = '';
	
	/**
	 * @desc Get values from shortcode setup popup
	 */
	function initVars() {  
		return params = {
			title: jQuery('#add-feed-dialog input#add-feed-title').val(),
			feed_id: jQuery('#add-feed-dialog select#add-feed-published_feeds').val(),
			topic_id: jQuery('#add-feed-dialog select#add-feed-topic').val(),
			bundle_id: jQuery('#add-feed-dialog select#add-feed-bundle_id').val(),
			sort_by: jQuery('#add-feed-dialog select#add-feed-sort_by').val(),
			num_results: jQuery('#add-feed-dialog select#add-feed-num_results').val(),
			columns_count: jQuery('#add-feed-dialog select#add-feed-columns_count').val(),
			image_size: jQuery('#add-feed-dialog input.add-feed-image_size:checked').val(),
			image_align: jQuery('#add-feed-dialog select#add-feed-image_align').val(),
			image_width: parseInt(jQuery('#add-feed-dialog input#add-feed-image_width').val()),
			image_height: parseInt(jQuery('#add-feed-dialog input#add-feed-image_height').val()),
			max_width_images: parseInt(jQuery('#add-feed-dialog input#add-feed-max_width_images').val()),
			show_photos: jQuery('#add-feed-dialog input#add-feed-show_photos').is(':checked'),
			ok_resize_previews: jQuery('#add-feed-dialog input#add-feed-ok_resize_previews').is(':checked'),
			show_links: jQuery('#add-feed-dialog input#add-feed-show_links').is(':checked'),
			show_summary: jQuery('#add-feed-dialog input#add-feed-show_summary').is(':checked'),
			show_source: jQuery('#add-feed-dialog input#add-feed-show_source').is(':checked'),
			remove_duplicates: jQuery('#add-feed-dialog input#add-feed-remove_duplicates').is(':checked'),
			remove_related: jQuery('#add-feed-dialog input#add-feed-remove_related').is(':checked'),
			mix_defaults: jQuery('#add-feed-dialog input#add-feed-mix_defaults').is(':checked'),
			show_date: jQuery('#add-feed-dialog input#add-feed-show_date').is(':checked'),
			show_nofollow: jQuery('#add-feed-dialog input#add-feed-show_nofollow').is(':checked'),
			content_types: jQuery('#add-feed-dialog select#add-feed-content_types').val(),
			cache_interval: jQuery('#add-feed-dialog select#add-feed-cache_interval').val()
		};
	}

	/**
	 * @desc Compose shortcode output results string
	 */
	function addOutputOptions(output, params) {
		if(params.sort_by) output += 'sort_by="' + params.sort_by + '" ';
		if(params.num_results) output += 'num_results="' + params.num_results + '" ';
		if(params.columns_count) output += 'columns_count="' + params.columns_count + '" ';
		       
		if(params.image_size) {
			output += 'image_size="' + params.image_size + '" ';
			if(params.image_size=='custom') {
				if(params.image_width > 0) output += 'image_width="' + params.image_width + '" ';
				if(params.image_height > 0) output += 'image_height="' + params.image_height + '" ';
			}
			else if(params.image_size=='custom_max') {
				if(params.max_width_images > 0) output += 'max_width_images="' + params.max_width_images + '" ';
			}
		}

		if(params.image_align) output += 'image_align="' + params.image_align + '" ';
		if(params.show_photos) output += 'show_photos="1" ';
		if(params.ok_resize_previews) output += 'ok_resize_previews="1" ';
		if(params.show_links) output += 'show_links="1" ';
		if(params.show_summary) output += 'show_summary="1" ';
		if(params.show_source) output += 'show_source="1" ';
		if(params.remove_duplicates) output += 'remove_duplicates="1" ';
		if(params.remove_related) output += 'remove_related="1" ';
		if(params.mix_defaults) output += 'mix_defaults="1" ';
		if(params.show_date) output += 'show_date="1" ';
		if(params.show_nofollow) output += 'show_nofollow="1" ';
		if(params.content_types) output += 'content_types="' + params.content_types + '" ';
		
		output += 'cache_interval="' + params.cache_interval + '" '; 
                               
		return output;
	}

	/**
	 * @desc Feeds Shortcode
	 */
	function composeFeedOutput() {
		// set up variables to contain our input values
		var params = initVars();
		var output = '';
		var tagName = 'ptfeed';
 
		// setup the output of our shortcode
		if(params.feed_id > 0) {
			output = '[' + tagName + ' ';
			output += 'feed_id="' + params.feed_id + '" '; 			
			output = addOutputOptions(output, params); 
			output += ']'+ (params.title ? params.title : '') + '[/' + tagName + ']';
		}

		// show error if output is empty
		if(output.length == 0) {
			jQuery("#add-feed-dialog select#add-feed-published_feeds").addClass("error");
		}
		
		return output;
	};

	/**
	 * @desc Curated Feeds Shortcode
	 */
	function composeCuratedFeedOutput() {
		// set up variables to contain our input values
		var params = initVars();
		var output = '';
		var tagName = 'ptcuratedfeed';
 
		// setup the output of our shortcode
		if(params.feed_id > 0) {
			output = '[' + tagName + ' ';
			output += 'feed_id="' + params.feed_id + '" '; 			
			output = addOutputOptions(output, params); 
			output += ']'+ (params.title ? params.title : '') + '[/' + tagName + ']';
		}

		// show error if output is empty
		if(output.length == 0) {
			jQuery("#add-feed-dialog select#add-feed-published_feeds").addClass("error");
		}
		
		return output;
	};

	/**
	 * @desc Topics Shortcode
	 */
	function composeTopicOutput() {
		// set up variables to contain our input values
		var params = initVars();
		var output = '';
		var tagName = 'pttopic';
                
		// setup the output of our shortcode
		if(params.topic_id > 0) {
			output = '[' + tagName + ' ';
			output += 'topic_id="' + params.topic_id + '" '; 			
			output = addOutputOptions(output, params); 
			output += ']'+ (params.title ? params.title : '') + '[/' + tagName + ']'; 						
		}

		// show error if output is empty
		if(output.length == 0) {
			jQuery("#add-feed-dialog select#add-feed-topic").addClass("error");
		}
		
		return output;
	};

	/**
	 * @desc Saved Searches Shortcode
	 */
	function composeSavedsearchOutput() {
		// set up variables to contain our input values
		var params = initVars();
		var output = '';
		var tagName = 'ptsavedsearch';
                
		// setup the output of our shortcode
		if(params.bundle_id > 0) {
			output = '[' + tagName + ' ';
			output += 'bundle_id="' + params.bundle_id + '" '; 			
			output = addOutputOptions(output, params); 
			output += ']'+ (params.title ? params.title : '') + '[/' + tagName + ']'; 						
		}

		// show error if output is empty
		if(output.length == 0) {
			jQuery("#add-feed-dialog select#add-feed-bundle_id").addClass("error");
		}
		
		return output;
	};

	/**
	 * @desc Tweets Shortcode
	 */
	function composeTweetOutput() {
		// set up variables to contain our input values
		var params = initVars();
		var output = '';
		var tagName = 'pttweet';
 
		// setup the output of our shortcode
		if(params.feed_id > 0) {
			output = '[' + tagName + ' ';
			output += 'feed_id="' + params.feed_id + '" '; 			
			output = addOutputOptions(output, params); 
			output += ']'+ (params.title ? params.title : '') + '[/' + tagName + ']';
		}

		// show error if output is empty
		if(output.length == 0) {
			jQuery("#add-feed-dialog select#add-feed-published_feeds").addClass("error");
		}
		
		return output;
	};

	return { 
		// Actions when user click on Insert button
		initInsertButton: function(type) {
			jQuery("#add-feed-dialog select#add-feed-published_feeds").removeClass("error");
			
			// Try and remove existing style / blockquote
			tinyMCEPopup.execCommand('mceRemoveNode', false, null);
 
			// get output string
			var output = '';
			switch(type) {
				case 'curatedfeed': output = composeCuratedFeedOutput(); break;
				case 'feed': output = composeFeedOutput(); break;
				case 'topic': output = composeTopicOutput(); break;
				case 'savedsearch': output = composeSavedsearchOutput(); break;
				case 'tweet': output = composeTweetOutput(); break;
				default: break;
			}
            
			// close editor if nothing to output
			if(output.length > 0) {
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
                tinyMCEPopup.close();
			}
			return false;
		},
		 
		// Init ajax calls and buttons actions
		initDOM: function(type) {
			var ButtonDialog = {
				local_ed : 'ed',
				init : function(ed) {
					ButtonDialog.local_ed = ed;
					tinyMCEPopup.resizeToInnerSize();
				},
				insert : function insertButton(ed) {
					shortcodesFunc.initInsertButton(type);
					return false;           		    	
				}
			};
            tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);
	
			jQuery(document).ready(function(){
				
				if(type=='feed' || type=='curatedfeed' || type=='tweet') {
					try {
						jQuery("#add-feed-published_feeds").removeClass('error');
						jQuery('img.publishthis-ajax-img').css('visibility', 'visible');
						
						jQuery.getJSON( ajaxurl, 
							{ action: 'get_publishthis_feeds' },
							function(data) {
								jQuery("#add-feed-published_feeds").html('').append(new Option("None", -1));
								if(data.feeds && data.feeds.length>0) {
									jQuery.each(data.feeds, function(key, value){
										var skip = false;
										if(type=='feed' && !value.automatedContentOn) skip = true;
										if(type=='tweet' && !value.automatedTwitterOn) skip = true;
														
										if( !skip ) {
											var displayFeed = value.displayName;
											if( displayFeed.length > 50 ) displayFeed = displayFeed.substr(0,50)+'...';
											jQuery("#add-feed-published_feeds").append(new Option(displayFeed, value.feedId));
										}
									});
								}
								else {
									jQuery("#add-feed-published_feeds").addClass('error');
								}
								jQuery('img.publishthis-ajax-img').css('visibility', 'hidden');
						});
					}
					catch(e) {
						jQuery("#add-feed-published_feeds").parent().append('<font color="red">Can\'t retrieve mix\'s list</font>');
					}
				}
				if(type=='savedsearch') {
					try {
						jQuery('img.publishthis-ajax-img').css('visibility', 'visible');
						jQuery.getJSON( ajaxurl, 
							{ action: 'get_publishthis_savedsearches' },
							function(data) {
								jQuery("#add-feed-bundle_id").html('').append(new Option("None", -1));
								jQuery.each(data.saved_searches, function(key, value){
									var displaySearch = value.displayName;
									if( displaySearch.length > 50 ) displaySearch = displaySearch.substr(0,50)+'...';					
									jQuery("#add-feed-bundle_id").append(new Option(displaySearch, value.bundleId));
								});
								jQuery('img.publishthis-ajax-img').css('visibility', 'hidden');
						});
					}
					catch(e) {
						jQuery("#add-feed-bundle_id").parent().append('<font color="red">Can\'t retrieve saved searches list</font>');
					}
				}
                if(type=='topic') {
					$('a.topic-name-button').live('click', function(e) {
						var $this   = $(this),
				
						$widget = $this.closest('div');

						var $ajaxImg  = $widget.find('.publishthis-ajax-img'),
						$topicId  = $widget.find('.topic-id-field'),
						topicName = $widget.find('.topic-name-field').val(),
						nonce = $('#topic-nonce').val();
				 
						if( topicName.length > 0 ) {
							jQuery('#topics-error').remove();
							try {
								$ajaxImg.css({'visibility': 'visible'});
		
								jQuery.getJSON( ajaxurl, 
									{ action: 'get_publishthis_topics', _ajax_nonce: nonce, topic_name: topicName },
									function(data) {
										$ajaxImg.css({'visibility': 'hidden'});
										jQuery("#add-feed-topic").html('');
										if(!data.topics || data.topics.length==0) {
											jQuery("#add-feed-topic").append(new Option("No topics found", 0));
										} else {
											jQuery.each(data.topics, function(key, value){
												var displayTopic = value.displayName + ' (' + value.shortLabel + ')';
												if( displayTopic.length > 50 ) displayTopic = displayTopic.substr(0,50)+'...';
										
												jQuery("#add-feed-topic").append(new Option(displayTopic, value.topicId));
											});
										}
								})
								.error(function(jqXHR, textStatus, errorThrown) {
									$ajaxImg.css({'visibility': 'hidden'});
								});	
							}
							catch(e) {
								jQuery("#add-feed-topic").parent().append('<font id="topics-error" color="red">Can\'t retrieve topics list</font>');
							}
						}
						return false;
					}).click();		
				}
				jQuery("a#insert").live('click', function(){
					ButtonDialog.insert(ButtonDialog.local_ed);
					return false;
				});

				jQuery("select#add-feed-published_feeds, select#add-feed-topic, select#add-feed-bundle_id").live('change', function(){
					jQuery(this).removeClass('error');
					return false;
				});		

				jQuery("input[id*='mix_defaults']").live('change', function() {					
					var parent = jQuery(this).parents('div.automated-dialog'),
						disabled = jQuery(this).is(':checked') ? true : false;
				
					parent.find("select[id*='sort_by'], input[id*='remove_duplicates'], input[id*='remove_related']").attr('disabled', disabled);
				}).change();
		
			});
		}
	}
})(window, window.jQuery);