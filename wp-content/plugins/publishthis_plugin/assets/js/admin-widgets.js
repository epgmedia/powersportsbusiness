(function(w, $) {
	'use strict';

	$(function() {
		$("input[id*='mix_defaults']").on('change', function() {
			var parent = $(this).parents('div.widget'),
				disabled = $(this).is(':checked') ? true : false;

			parent.find("select[id*='sort_by'], input[id*='remove_duplicates'], input[id*='remove_related']").attr('disabled', disabled);
		}).change();
		$('a.topic-name-button').live( 'click', function(e) {
			var $this = $(this), $widget = $this.closest('.widget');

			var $ajaxImg = $widget.find('.publishthis-ajax-img'), 
				$topicId = $widget.find('.topic-id-field'), 
				topicName = $widget.find('.topic-name-field').val(), 
				currentTopicId = $topicId.data('current');

			if( topicName.length > 0 ) {
				$ajaxImg.css({
					'visibility' : 'visible'
				});
				
				$.ajax({
						cache : false,
						dataType : 'json',
						type : 'GET',
						url : w.ajaxurl,
						data : {
							action : 'get_publishthis_topics',
							_ajax_nonce: publishthis_widgets_ajax.nonce,
							topic_name : topicName
						},
						error : function(jqXHR, textStatus, errorThrown) {
							$ajaxImg.css({
								'visibility' : 'hidden'
							});
						},
						success : function(response, textStatus, jqXHR) {
							if (response.status === 'success') {
								var options = '';
								for ( var i = 0; i < response.topics.length; i++) {
									var topic = response.topics[i], selected = (currentTopicId === topic.topicId) ? ' selected="selected"' : '';
									var displayTopic = topic.displayName + ' (' + topic.shortLabel + ')';
									if( displayTopic.length > 30 ) displayTopic = displayTopic.substr(0,30)+'...';
									options += '<option value="' + topic.topicId + '"' + selected + '>' + displayTopic + '</option>';
								}

								$topicId.html(options);
							}
							else {
								options = '<option value="0">No topics found</option>';
								$topicId.html(options);
							}

							$ajaxImg.css({
								'visibility' : 'hidden'
							});
						}
					});
			}
			e.preventDefault();
		}).click();		
	});
})(window, window.jQuery);
