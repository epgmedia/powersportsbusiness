(function($, pt, w) {
	'use strict';

	// Ready
	$(function() {
		var tweetsCount = parseInt( $("blockquote.twitter-tweet").size() );

		if( tweetsCount > 0 ) {
			publishthis.handlerID = window.setTimeout( function() {
				publishthis.applyGridFormating();
			}, 3000 );
		}
		else {
			publishthis.applyGridFormating();
		}

	});

	var publishthis = {
		handlerID: null, //setTimeout handler

		applyGridFormating: function() {
			
			var $container = $('.ptgrid-container');
		
			$container.each(function(){
				var $this = $(this),
					$columns = $this.data('columns-count'),
					params;

				if( $container.parents('#secondary').length > 0 ) {
					params = {
						itemSelector : '.pt-box',
						columnWidth: function( containerWidth ) {
							return containerWidth / $columns;
						}
					};	
				}
				else {
					params = {
						itemSelector : '.pt-box',
						gutterWidth : 10
					};	
				}

				if($columns && $columns>1) {
					$this.imagesLoaded( function() {
						$this.masonry( params );
					});	
				}
			});
			if( publishthis.handlerID ) window.clearInterval(publishthis.handlerID);
			return false;
		}
	};

})(window.jQuery, window.Publishthis, window);