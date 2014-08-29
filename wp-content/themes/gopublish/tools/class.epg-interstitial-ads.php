<?php
/*
Plugin Name: Interstitial Ad
Description: Displays an ad on first visit. Resets after 6 hours.
Author: EPG Media LLC
Author URI: http://www.epgmediallc.com/
Version: 1.0
*/

class epg_interstitial_ads {
    /**
     * What it needs to do:
     *
     * 1. If an ad is available:
     *     I. If user hasn't seen ad in time(X)
     *     II. And user is off-site visitor
     *         A. Display a full screen cover
     *         B. Put an ad in that cover
     *         C. Set a cookie
     *         D. Close automatically after 15 seconds
     *         E. Hide cover until cookie expires
     *
     * Order:
     *     Check for ad
     *     Check for cookie
     *     Display add
     *     Set cookie
     *     Close
     */

    /** Variables */

    /** @var string source */
	private $referringURL = '';

	/** @var string Cookie seenAdPsb */
	private $visitCookie = '';

    /**
     * Constructor
     */
    public function __construct() {

		/** HTTP Referer */
        if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
            $this->referringURL = $_SERVER['HTTP_REFERER'];
        }

		/** Cookie */
        if ( isset( $_COOKIE['interstitial_ad_psb'] ) ) {
            $this->visitCookie = $_COOKIE['interstitial_ad_psb'];
        }

		add_action( 'wp_footer', array($this, 'print_to_foot'), 10, '');

		add_action('get_header', array($this, 'enqueueAdPosition'));

    }
    /**
     * Enqueues items in HEAD
     */
	public function enqueueAdPosition() {
		$this->referral_check();
    }
    /**
     * Checks to see if they're coming from Informz or outside URL
     * and whether they got cookies
     */
    protected function referral_check() {
		/**
		 * If:
		 *   Cookie or
		 *   Not on the site already or
		 *   Coming from Transition Page or
		 *   Coming from Informz
		 * then
		 *   TRUE
		 * Else
		 *   FALSE
		 */
		if (
			time() >= $this->visitCookie &&
			! preg_match( "/powersportsbusiness\.com/", $this->referringURL ) &&
			! preg_match( "/epgmedia\.s3\.amazonaws\.com/", $this->referringURL ) &&
			! preg_match( "/epgmediallc\.informz\.net/", $this->referringURL )
		) {
			add_action( 'wp_head', array($this, 'headerScript'), 10, '');
			add_action( 'after_header', array($this, 'adPosition'), 100, '' );
			$this->set_cookie();
        }
    }

	protected function set_cookie() {
		// Time cookie was set
		$set_time = time()+(60*60*6);

		// Ad cookie
		setcookie(
			'interstitial_ad_psb',
			$set_time,
			$set_time,
			COOKIEPATH,
			COOKIE_DOMAIN,
			FALSE
		);

		// Remove "SeenAd" cookie
		if ($_GET['unsetCookie'] && is_admin()) {
			setcookie( "interstitial_ad_psb", TRUE, time()-(60*60), COOKIEPATH, COOKIE_DOMAIN, false );
		}

	}

	public function print_to_foot() {

		echo "<!-- Referral URL -->";
		echo "<!-- " . $this->referringURL . " --!>";
		echo "<!-- Cookie -->";
		echo "<!-- " . $this->visitCookie . " --!>";

	}

	public function headerScript() {
        echo "
            <script type='text/javascript'>

                googletag.cmd.push(function() {

                	var slot = '/35190362/PSB_ROS_Roadblock';
                    googletag.defineSlot( slot, [1, 1], 'div-gpt-ad-1398116137114-0' ).addService( googletag.pubads() );
                    googletag.defineOutOfPageSlot( slot, 'div-gpt-ad-1398116137114-0-oop' ).addService( googletag.pubads() );
					googletag.pubads().addEventListener('slotRenderEnded', function(event) {
						var f_slot = event.slot.k;
						if ( ( f_slot === slot) && !event.isEmpty ) {
							console.log( f_slot + ' slot was rendered' );
							jQuery('.interstitialAd').show();
						}
						console.log( f_slot + ' Complete' );
						console.log( event );

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


            </script>";

        return;
    }

	public function adPosition() {
        echo "<div class='interstitialAd'>
                <div class='head'>
                    <div>
                        <img src='http://epgmedia.s3.amazonaws.com/email/powersportsbusiness/enewsletter/2013/images/PSBRedirectheader.jpeg' width='400' alt='PowersportsBusiness.com' />
                    </div>
                    <div>
                        <span>You will automatically be redirected in <span class='counter' id='countdownRedirect'>10</span> seconds. <a id='closeInterstitial'>Click here to proceed</a>.</span>
                    </div>
                </div>
                <!-- PSB_ROS_Roadblock -->
                <div id='div-gpt-ad-1398116137114-0' style='width:1px; height:1px;'>
                    <script type='text/javascript'>
                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1398116137114-0'); });
                    </script>
                </div>
                <!-- PSB_ROS_Roadblock out-of-page -->
                <div id='div-gpt-ad-1398116137114-0-oop'>
                    <script type='text/javascript'>
                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1398116137114-0-oop'); });
                    </script>
                </div>
            </div>";

        return;
    }
}