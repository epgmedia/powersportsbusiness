/**
 *
 * Interstitial Ad Javascript
 *
 */

console.log(ad_data);

googletag.cmd.push(function() {
	googletag.defineSlot( ad_data.ad_position, [1, 1], 'div-gpt-ad-1398116137114-0' ).addService( googletag.pubads() );
	googletag.defineOutOfPageSlot( ad_data.ad_position, 'div-gpt-ad-1398116137114-0-oop' ).addService( googletag.pubads() );
	googletag.pubads().addEventListener('slotRenderEnded', function(event) {
		var f_slot = event.slot.k;
		if ( ( f_slot === ad_data.ad_position) && !event.isEmpty ) {
			//console.log( f_slot + ' slot was rendered' );
			jQuery( '.interstitialAd' ).show();
		}
		//console.log( f_slot + ' Complete' );
		//console.log( event );

	});
	googletag.enableServices();
});

jQuery( '.interstitialAd' ).ready( function( $ ) {
	var ad = '.interstitialAd';
	$( '#closeInterstitial' ).click( function() {
		$(ad).hide();
	} );
	var e = $( '#countdownRedirect' ).html();
	if( ! e ) {
		throw new Error( 'COUNTDOWN_REDIRECT element id not found' );
	}
	var cTicks = e;
	setInterval( function() {
		if( cTicks ) {
			$( '#countdownRedirect' ).html( --cTicks );
		} else {
			clearInterval( e );
			$( ad ).hide();
		}
	}, 1000 );
});