/**
 *
 * Interstitial Ad Javascript
 *
 */
googletag.cmd.push( function() {
	googletag.defineSlot( ad_data.ad_position, [1, 1], ad_data.position_tag ).addService( googletag.pubads() );
	googletag.defineOutOfPageSlot( ad_data.ad_position, ad_data.position_tag + '-oop' ).addService( googletag.pubads() );
	googletag.pubads().addEventListener('slotRenderEnded', function(event) {
		var f_slot = event.slot.k;
		if ( ( f_slot === ad_data.ad_position) && !event.isEmpty ) {
			jQuery( '.interstitialAd' ).show();
		}
	});
	googletag.enableServices();
});

jQuery(document).ready( function( $ ) {

	$ad_postition = $('.interstitialAd');
	$close_button = $('.close-interstitial');

	var close_overlay = function() {
		$(this).hide();
	};

	$ad_postition.on("click", close_overlay);
	$close_button.on("click", close_overlay);

});