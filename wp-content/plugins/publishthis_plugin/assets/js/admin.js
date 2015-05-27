(function($, pt, w) {
	'use strict';

	// Ready
	$(function() {

		$('#submitdiv h3 span').text('Actions');
		$('#submitdiv #publish').val('Save Changes');
		if( $('#publishthis-html-body-image-field').is(':checked') ) $('#publishthis-include-imageLayoutSettings').attr('checked', true);
		if( $('#tax_all').is(':checked') ) { 
			$('input[name="publishthis_options[tax_mapping][]"]').each( function() {
				$(this).attr('checked', true);
			} );
		}

		publishing_actions.showHideSyncFields( $('.content_type_format:checked').val() );

		$('#publish-bottom').live('click', function() {
			$('#submitdiv #publish').click();
			return false;
		});

		//init color picker
		$('.choose_color').wpColorPicker();

		//postboxes.add_postbox_toggles('publishthis_style_options');
		
		publishing_actions.bindGeneralEvents();
		publishing_actions.bindOptionsEvents();
		publishing_actions.bindLayoutEvents();
		publishing_actions.bindTokenEvents();
		publishing_actions.bindCatsMappingEvents();	
	});

	var publishing_actions = {
		bindGeneralEvents: function() {

			$('#publishthis-options li label input[type="checkbox"]').live('click', function() {
				if($(this).hasClass('disabled')) return false;
				publishing_actions.updatePreviewData('');
				var a = $(this).closest('ul').find('a.customize-popup');
				if( !$(this).is(':checked') ) a.addClass('disabled');
				else a.removeClass('disabled');
			});

			$('#publishthis-html-body-image-field').live('click', function() {
				if( $(this).is(':checked') ) {
					$('#publishthis-include-imageLayoutSettings').attr('checked', true).addClass('disabled');
				}
				else {
					$('#publishthis-include-imageLayoutSettings').removeClass('disabled');
				}
				$('#publishthis-include-imageLayoutSettings').closest('ul').find('a.customize-popup').removeClass('disabled');
			});

			$('.customize-popup').live('click', function() {
				if($(this).attr('disabled') || $(this).hasClass('disabled')) return false;

				//check user can see popup
				if(!$('#publishthis-include-'+$(this).data('popup')).is(':checked')) {
					alert('Please select \'Include\' option');
					return false;
				}

				//open popup
				tb_show($(this).data('title'), '#TB_inline?height=' + $(this).data('popup-height') + '&width=' + $(this).data('popup-width') + '&inlineId=' + $(this).data('popup') );
			
				//set selected values
				var data;
				try {
					switch($(this).data('popup')) {
						case 'embedLayoutSettings':
							data = JSON.parse($('#publishthis-layout-embed-custom-styles').val()).embedLayoutSettings;
							$( ".publishthis-size" ).filter('[value='+data.size+']').prop('checked', true);
							$( "#publishthis-width" ).val(data.width);
							$( "#publishthis-height" ).val(data.height);
							$( "#publishthis-max-width" ).val(data.max_width);
							break;
						case 'imageLayoutSettings':
							data = JSON.parse($('#publishthis-layout-image-custom-styles').val()).imageLayoutSettings;
							$( ".publishthis-aligment" ).filter('[value='+data.aligment+']').prop('checked', true);
							$( "#publishthis-ok-resize-previews" ).prop('checked', (data.ok_resize_previews=="1"?true:false));
							$( "#publishthis-override-custom-images" ).prop('checked', (data.override_custom_images=="1"?true:false));							
							$( ".publishthis-post-image-size" ).filter('[value='+data.size+']').prop('checked', true);
							$( "#publishthis-post-image-width" ).val(data.width);
							$( "#publishthis-post-image-height" ).val(data.height);
							$( "#publishthis-post-image-max-width" ).val(data.max_width);
							$( "#publishthis-use-caption-shortcode" ).prop('checked', (data.use_caption_shortcode=="1"?true:false));
							break;
						case 'titleLayoutSettings': 
							data = JSON.parse($('#publishthis-layout-title-custom-styles').val()).titleLayoutSettings;
							
							$('.publishthis-title-clickable').filter('[value='+data.clickable+']').prop('checked', true);
							$('.publishthis-title-nofollow').filter('[value='+data.nofollow+']').prop('checked', true);							
							$('.publishthis-title-nofollow').attr('disabled', ( $('.publishthis-title-clickable:checked').val()=="0" ? true : false ) );							
							break;	
						case 'readmoreLayoutSettings': 
							data = JSON.parse($('#publishthis-layout-readmore-custom-styles').val()).readmoreLayoutSettings;
													
							$('.publishthis-readmore-newwindow').filter('[value='+data.newwindow+']').prop('checked', true);
							$('.publishthis-readmore-publisher').filter('[value='+data.publisher+']').prop('checked', true);	
							$('.publishthis-readmore-nofollow').filter('[value='+data.nofollow+']').prop('checked', true);
							break; 	
						case 'annotationLayoutSettings': 
							data = JSON.parse($('#publishthis-layout-annotation-custom-styles').val()).annotationLayoutSettings;

							$('.publishthis-annotation-placement').filter('[value='+data.annotation_placement+']').prop('checked', true);						
							$('#publishthis-annotation-title-text').val(data.annotation_title_text);
							$('#layout-annotation-title-custom-vertical').val(data.annotation_title_alignment.vertical);
							$('#layout-annotation-title-custom-horizontal').val(data.annotation_title_alignment.horizontal);									
							break;
					}
				}
				catch(e) {}

				return false;
			});

			$('.check-for-int').change(function() {
				var value = parseInt($(this).val());
				if( isNaN(value) ) {
					value = 0;
				}
				$(this).val( value );		
			});
		},

		bindCatsMappingEvents: function() {
			$('#edit_map_categories').live('click', function() {
				$(this).toggleClass('opened');
				return false;
			});

			$('#tax_all').live('change', function() {
				var isChecked = $(this).is(':checked') ? true : false;
				$('input[name="publishthis_options[tax_mapping][]"]').each( function() {
					$(this).attr('checked', isChecked);
				} );
				return false;
			});

			$('input[name="publishthis_options[tax_mapping][]"]').live('change', function() {
				if (!$(this).is(':checked') ) $('#tax_all').attr('checked', false);
			});
		},

		setMargingsValue: function( key, data ) {
			if(data=='default') {
				$( ".publishthis-"+key+"-margins" ).filter('[value=default]').prop('checked', true);
			}
			else {
				$( ".publishthis-"+key+"-margins" ).filter('[value=custom]').prop('checked', true);
				$( "#layout-"+key+"-custom-margin-left" ).val(data.left),
				$( "#layout-"+key+"-custom-margin-right" ).val(data.right),
				$( "#layout-"+key+"-custom-margin-top" ).val(data.top),
				$( "#layout-"+key+"-custom-margin-btm" ).val(data.btm)
			}
		},

		setPaddingsValue: function( key, data ) {
			if(!data || data=='default') {
				$( ".publishthis-"+key+"-paddings" ).filter('[value=default]').prop('checked', true);
			}
			else {
				$( ".publishthis-"+key+"-paddings" ).filter('[value=custom]').prop('checked', true);
				$( "#layout-"+key+"-custom-padding-left" ).val(data.left),
				$( "#layout-"+key+"-custom-padding-right" ).val(data.right),
				$( "#layout-"+key+"-custom-padding-top" ).val(data.top),
				$( "#layout-"+key+"-custom-padding-btm" ).val(data.btm)
			}
		},

		setValue: function( element, key, value ) {
			if(value=='default') {
				$( ".publishthis-"+element+"-"+key ).filter('[value=default]').prop('checked', true);
			}
			else {
				$( ".publishthis-"+element+"-"+key ).filter('[value=custom]').prop('checked', true);
				$( "#publishthis-"+element+"-custom-"+key ).val(value);
			}
		},

		bindOptionsEvents: function() {
			$('.publishthis-featured-image-size-field').change(function() {
				switch( $(this).val() ) {
					case 'theme_default':
						$('#publishthis-featured-image-width-field, #publishthis-up-to-max-width-field, #publishthis-featured-image-height-field, #publishthis-featured-max-image-width-field').val(300);
						break;

					case 'custom':
						$('#publishthis-featured-max-image-width-field, #publishthis-up-to-max-width-field').val(300);
						break;

					case 'custom_max':
						$('#publishthis-featured-image-width-field, #publishthis-up-to-max-width-field, #publishthis-featured-image-height-field').val(300);
						break;

					case 'custom_up_to_max':
						$('#publishthis-featured-image-width-field, #publishthis-featured-image-height-field, #publishthis-featured-max-image-width-field').val(300);
						break;

					default: break;
				}			
			});
					
			$('#publishthis-content-type-field').live('change', function() {
				var $this = $(this);
				
				var options = '<option value="category">WordPress Default</option>',
					currentTaxonomyId = $('#publishthis-taxonomy-field').data('taxonomy'),
					selected = '',
					taxonomies = pt.taxonomies[ $this.val() ];

				if( taxonomies ) {
					$.each( taxonomies, function( key, value) {
						selected = (key === currentTaxonomyId) ? ' selected="selected"' : '';
						options += '<option value="'
								+ key + '"' + selected
								+ '>' + value
								+ '</option>';
					});
				}
				$('#publishthis-taxonomy-field').html(options);			
			}).change();

			$('#publishthis-feed-template-field').live('change', function() {
				var $this = $(this);

				var options = '',
					sections = pt.templateSections, 
					selected = '', 
					templateId = $this.find('option:selected').val(), 
					currentSectionId = $('#publishthis-template-section-field').data('template-section');

				for ( var i in sections[templateId]) {
					var section = sections[templateId][i];
					selected = (section.sectionId === currentSectionId) ? ' selected="selected"' : '';
					options += '<option value="'
							+ section.sectionId + '"' + selected
							+ '>' + section.displayName
							+ '</option>';
				}
				$('#publishthis-template-section-field').html(options);

				// Fields
				var $publishthisCategoryField = $('#publishthis-category-field'), 
					fields = pt.templateFields, 
					currentField = $publishthisCategoryField.data('current'), 
					fieldOptions = '<option value="0">Do not categorize</option>';

				for ( var j in fields[templateId]) {
					var field = fields[templateId][j];
					var selectedField = (field.shortCode === currentField) ? ' selected="selected"'	: '';
					fieldOptions += '<option value="'
							+ field.shortCode + '"' + selectedField
							+ '>' + field.displayName + '</option>';
				}

				$publishthisCategoryField.html(fieldOptions);
			}).change();
		},

		/* @param action: 'close' - close popup after update; 'open' - open preview page */
		updatePreviewData: function( action ) {
			return;
			var data = {
				action : 'publishing_action_preview',
				_ajax_nonce: publishthis_publishing_action_ajax.nonce,
				content_type_format: $('.content_type_format:checked').val(),
				readmore: $('#publishthis-read-more-field').val(),
				annotation_title_text: $('#publishthis-annotation-title-text').val(),
				annotation_placement: $('#publishthis-annotation-placement-field:checked').val(),
				styles: {}
			};

			if($('#publishthis-include-imageLayoutSettings').is(':checked')) {
				data.styles.image = $('#publishthis-layout-image-custom-styles').val();
			}

			if($('#publishthis-include-annotationLayoutSettings').is(':checked')) {
				data.styles.annotation = $('#publishthis-layout-annotation-custom-styles').val();
			}

			if($('#publishthis-include-publishdateLayoutSettings').is(':checked')) {
				data.styles.publishdate = $('#publishthis-layout-publishdate-custom-styles').val();
			}

			if($('#publishthis-include-readmoreLayoutSettings').is(':checked')) {
				data.styles.readmore = $('#publishthis-layout-readmore-custom-styles').val();
			}

			if($('#publishthis-include-summaryLayoutSettings').is(':checked')) {
				data.styles.summary = $('#publishthis-layout-summary-custom-styles').val();
			}

			if($('#publishthis-include-titleLayoutSettings').is(':checked')) {
				data.styles.title = $('#publishthis-layout-title-custom-styles').val();
			}

			if($('#publishthis-include-embedLayoutSettings').is(':checked')) {
				data.styles.embed = $('#publishthis-layout-embed-custom-styles').val();	
			}	
			
			$.ajax({
				url : w.ajaxurl,
				type: "POST",
				dataType: "json",
				data : data,
				error: function() {
					
				},
				success: function( response ){
					if(response.success) {
						switch( action ) {
						case 'close':
							tb_remove();
							break;

						case 'open':
							window.open(response.post_url, '_blank');
							window.focus();
							break;

						default: break;
						}
					}
				}
			});
			return false;
		},

		bindLayoutEvents: function() {	
			$('#pt-layout-preview').live('click', function() {
				publishing_actions.updatePreviewData('open');
			
				return false;
			});

			$('.content_type_format').live('change', function() {
				publishing_actions.showHideSyncFields($(this).val());
				publishing_actions.updatePreviewData('');
			});

			publishing_actions.bindLayoutImageEvents();
			publishing_actions.bindLayoutTitleEvents();
			publishing_actions.bindLayoutReadmoreEvents();
			publishing_actions.bindLayoutAnnotationEvents();
			publishing_actions.bindLayoutEmbedEvents();
		},

		showHideSyncFields: function( content_type ) {
			if(content_type == 'individual') {
				$('td#title-row').find('input[type="checkbox"]').attr('checked', false);
				$('td#title-row').find('input, a').attr('disabled', true).addClass('disabled');

				$('tr#digest-synchronize-row').addClass('hidden');
				$('tr#individual-insert-row, tr#individual-update-row, tr#individual-delete-row').removeClass('hidden');
			}
			else {
				$('td#title-row').find('input[type="checkbox"]').attr('checked', true);
				$('td#title-row').find('input, a').attr('disabled', false).removeClass('disabled');

				$('tr#digest-synchronize-row').removeClass('hidden');
				$('tr#individual-insert-row, tr#individual-update-row, tr#individual-delete-row').addClass('hidden');
			}
		},

		saveCustomization: function() {
			$('#dont_redirect').val(1);
			$('#submitdiv #publish').click();
			$('#dont_redirect').val(0);
		},

		bindLayoutImageEvents: function() {
			$('#layout-image-save').live('click', function() {			
				var styles = { imageLayoutSettings: { 
								aligment: $( ".publishthis-aligment:checked" ).val(),
								ok_resize_previews: ($( "#publishthis-ok-resize-previews" ).is(":checked") ? "1" : "0"),
								override_custom_images: ($( "#publishthis-override-custom-images" ).is(":checked") ? "1" : "0"),								
								size: $( ".publishthis-post-image-size:checked" ).val(),
								width: $( "#publishthis-post-image-width" ).val(),
								use_caption_shortcode: ($( "#publishthis-use-caption-shortcode" ).is(":checked") ? "1" : "0"),
								height: $( "#publishthis-post-image-height" ).val(),
								max_width: $( "#publishthis-post-image-max-width" ).val()
							} };
				$('#publishthis-layout-image-custom-styles').val(JSON.stringify(styles));
				publishing_actions.updatePreviewData('close');
				publishing_actions.saveCustomization();
				return false;
			} );
		},

		bindLayoutTitleEvents: function() {
			$('.publishthis-title-clickable').live('change', function() {
				$('.publishthis-title-nofollow').attr('disabled', ( $(this).val()=="0" ? true : false ) );
			});

			$('#layout-title-save').live('click', function() {	
				var styles = { titleLayoutSettings: { 
								clickable: $('.publishthis-title-clickable:checked').val(),
								nofollow: $('.publishthis-title-nofollow:checked').val()
							} };
				$('#publishthis-layout-title-custom-styles').val(JSON.stringify(styles));
				publishing_actions.updatePreviewData('close');
				publishing_actions.saveCustomization();
				return false;
			} );
		},

		bindLayoutAnnotationEvents: function() {
			$('#layout-annotation-save').live('click', function() {	
				var styles = { annotationLayoutSettings: { 
								annotation_title_text: $('#publishthis-annotation-title-text').val(),
								annotation_placement: $('#publishthis-annotation-placement-field:checked').val(),								
								annotation_title_alignment: { vertical: $('#layout-annotation-title-custom-vertical').val(), horizontal: $('#layout-annotation-title-custom-horizontal').val() },
							} };
				$('#publishthis-layout-annotation-custom-styles').val(JSON.stringify(styles));
				$('#hidden-annotation-placement').val($('#publishthis-annotation-placement-field:checked').val());
				publishing_actions.updatePreviewData('close');
				publishing_actions.saveCustomization();
				return false;
			} );
		},

		bindLayoutReadmoreEvents: function() {
			$('#layout-readmore-save').live('click', function() {	
				var styles = { readmoreLayoutSettings: { 
								newwindow: $('.publishthis-readmore-newwindow:checked').val(),
								publisher: $('.publishthis-readmore-publisher:checked').val(),
								read_more: $('#publishthis-read-more-field').val(),
								nofollow: $('.publishthis-readmore-nofollow:checked').val()
							} };
				$('#publishthis-layout-readmore-custom-styles').val(JSON.stringify(styles));
				$('#hidden-read-more').val($('#publishthis-read-more-field').val());
				publishing_actions.updatePreviewData('close');
				publishing_actions.saveCustomization();
				return false;
			} );
		},

		bindLayoutEmbedEvents: function() {
			$('#layout-embed-save').live('click', function() {			
				var styles = { embedLayoutSettings: { 
								size: $( ".publishthis-size:checked" ).val(),
								width: $( "#publishthis-width" ).val(),
								height: $( "#publishthis-height" ).val(),
								max_width: $( "#publishthis-max-width" ).val(),
							} };
				$('#publishthis-layout-embed-custom-styles').val(JSON.stringify(styles));
				publishing_actions.updatePreviewData('close');
				publishing_actions.saveCustomization();
				return false;
			} );
		},

		bindTokenEvents: function() {
			$('#login-link').live('click', function() {
				//reset form
				$("#email, #pass").val('');
				$("#role").val('EDITOR');
							
				$('#login-info-block').show('fast');
				$('#client-info-block').hide('fast');

				$('img#token-login-as-ajax-img, img#token-login-ajax-img').remove();

				$('#token-setup-content p.error').html('').addClass('hidden');
				
				tb_show('Token Setup', '#TB_inline/?height=400&width=400&inlineId=token-setup');

				return false;
			});

			$('#email, #pass').keypress(function (e) {
				if (e.which == 13) {
					$('#token-login').click();
				}
			});

			$('#clear-cache').live('click', function() {
				$('.publishthis-ajax-img').removeClass('hidden');
				$.ajax({
					cache : false,
					dataType : 'json',
					type : 'POST',
					url : w.ajaxurl,
					data : {
						action : 'clear_pt_caches',
						_ajax_nonce: publishthis_clear_caches_ajax.nonce
					},
					success : function(response, textStatus, jqXHR) {
						$('#clear-cache-message').remove();

						var message = '',
							cssClass = '';

						if( response ) {
							message = 'All Publishthis caches were deleted';
						}
						else {
							message = 'Some error occurs';
							cssClass = 'class="error"'
						}

						$('#clear-cache').parent().append('<span id="clear-cache-message" ' + cssClass + '>' + message + '</span>');
						$('.publishthis-ajax-img').toggleClass('hidden');
					}
				});

				return false;

			});

			$('#token-login').live('click', function() {
				var img = '<img src="/wp-admin/images/wpspin_light.gif" id="token-login-ajax-img" style="vertical-align:middle;" />';
				$(this).parent().append(img);

				$.ajax({
					url : w.ajaxurl,
					type: "POST",
					dataType: "json",
					data : {
						action : 'publishthis_clients_list',
						_ajax_nonce: publishthis_clients_list_ajax.nonce,
						email: $("#email").val(),
						password: $.sha256($("#pass").val()),
						role: $("#role").val()
					},
					error: function() {
						$('img#token-login-ajax-img').remove();
					},
					success: function( response ){
						$('img#token-login-ajax-img').remove();

						var options = '';
						if( !response.errorCode ) {
							$('#token-setup-content #sessionid').val(response.sessionId);
							if(response.data) {
								$.each( response.data, function( key, value) {
									options += '<option value="' + value.clientId + '">' + value.displayName + '</option>';
								} );
								$('#token-setup-content p.error').html('').addClass('hidden');							
								$('#login-info-block').hide('fast');
								$('#client-info-block').show('fast');						
							}
							else {
								$('#token-login').parent().append(img);

								$.ajax({
									url : w.ajaxurl,
									type: "POST",
									dataType: "json",
									data : {
										action : 'publishthis_client_token',
										_ajax_nonce: publishthis_client_token_ajax.nonce,
										sessionid: $('#token-setup-content #sessionid').val()
									},
									success: function( response ){
										$('img#token-login-ajax-img').remove();

										if( !response.errorCode && response.tokenId && response.tokenId.length>0 ) {
											publishing_actions.displayToken( response.tokenId );																			
										}
										else {
											$('#token-setup-content p.error').html('Invalid API token value').show();
										}					
									}
								});
							}

						}
						else {
							if( response.errorCode=="Unknown error" ) {
								$('#token-setup-content p.error').html('There was an issue in logging you in. Please try again in a few minutes.').removeClass('hidden');
							}
							else {
								$('#token-setup-content p.error').html('Invalid login or password').removeClass('hidden');
							}						
						}
						$('#token-setup-content select#clients').html(options);
					}
				});
				return false;
			});

			$('#token-login-as').live('click', function() {
				var img = '<img src="/wp-admin/images/wpspin_light.gif" id="token-login-as-ajax-img" style="vertical-align:middle;" />';
				$(this).parent().append(img);

				$.ajax({
					url : w.ajaxurl,
					type: "POST",
					dataType: "json",
					data : {
						action : 'publishthis_select_client',
						_ajax_nonce: publishthis_select_client_ajax.nonce,
						clientId: $("#clients").val(),
						sessionid: $('#token-setup-content #sessionid').val()
					},
					error: function() {
						$('img#token-login-ajax-img').remove();
					},
					success: function( response ){
						$('img#token-login-as-ajax-img').remove();

						if( !response.errorCode && response.tokenId && response.tokenId.length>0 ) {
							publishing_actions.displayToken( response.tokenId );									
						}
						else {
							$('#token-setup-content p.error').html('Invalid API token value').show();
						}					
					}
				});
				return false;
			});

			$('#verify-token').live('click', function() {

				$.ajax({
					cache : false,
					dataType : 'json',
					type : 'POST',
					url : w.ajaxurl,
					data : {
						action : 'validate_publishthis_token',
						_ajax_nonce: publishthis_settings_ajax.nonce,
						token : $('#publishthis_api_token').val()
					},
					success : function(response, textStatus, jqXHR) {
						$('#verify-token-error').html(response.message)
						if ( !response.valid ) {
							$('#verify-token-error').addClass('error').removeClass('hidden updated');
						}
						else {
							$('#verify-token-error').addClass('updated').removeClass('hidden error');
						}
					}
				});

				return false;
			});
		},

		displayToken: function( tokenId ) {
			//set token value
			$('#publishthis_api_token').val( tokenId );

			//close popup
			tb_remove();
		}
	};

	
})(window.jQuery, window.Publishthis, window);
