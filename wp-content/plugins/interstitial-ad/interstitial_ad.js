
jQuery(document).ready( function( $ ) {


    var googletag = googletag || {};
    googletag.cmd = googletag.cmd || [];
    (function() {
        var gads = document.createElement('script');
        gads.async = true;
        gads.type = 'text/javascript';
        var useSSL = 'https:' == document.location.protocol;
        gads.src = (useSSL ? 'https:' : 'http:') +
        '//www.googletagservices.com/tag/js/gpt.js';
        var node = document.getElementsByTagName('script')[0];
        node.parentNode.insertBefore(gads, node);
    })();


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


    var html_tag = '<div class="interstitialAd">' +
        '<div class="close-interstitial">X</div>' +
        '<!-- Roadblock -->' +
        '<div id=' + ad_data.position_tag + ' style="width:1px; height:1px;">' +
        '<script type="text/javascript">' +
        'googletag.cmd.push(function() { googletag.display("' + ad_data.position_tag + '"); });' +
        '</script>' +
        '</div>' +
        '<!-- Roadblock out-of-page -->' +
        '<div id="' + ad_data.position_tag + '-oop">' +
        '<script type="text/javascript">' +
        'googletag.cmd.push(function() { googletag.display("' + ad_data.position_tag + '-oop"); });' +
        '</script>' +
        '</div>' +
        '</div>';

	$ad_postition = $('.interstitialAd');
	$close_button = $('.close-interstitial');

    $('body').prepend(html_tag);

	var close_overlay = function() {
		$(this).hide();
	};

	$ad_postition.on("click", close_overlay);
	$close_button.on("click", close_overlay);

});