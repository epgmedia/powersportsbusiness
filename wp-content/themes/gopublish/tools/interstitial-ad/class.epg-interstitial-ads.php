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

	public $dir_uri;

    /** Variables */
	public $data = array();

    /** @var string source */
	private $referringURL = '';

	/** @var string Cookie seenAdPsb */
	private $visitCookie = '';

    /**
     * Constructor
     */
    public function __construct() {

		$this->dir_uri = get_template_directory_uri() . '/tools/interstitial-ad';

		/** HTTP Referer */
        if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
            $this->referringURL = $_SERVER['HTTP_REFERER'];
			$this->data['referring_url'] = $_SERVER['HTTP_REFERER'];
        }

		/** Cookie */
        if ( isset( $_COOKIE['interstitial_ad_psb'] ) ) {
            $this->visitCookie = $_COOKIE['interstitial_ad_psb'];
			$this->data['ad_cookie'] = $_COOKIE['interstitial_ad_psb'];
        }

		$this->data['ad_position'] = '/35190362/PSB_ROS_Roadblock';

		add_action( 'wp_footer', array($this, 'print_to_foot'), 10, '');
		add_action( 'after_header', array($this, 'adPosition'), 100, '' );

		wp_register_script(
			'epg_interstitial_ad',
			$this->dir_uri . '/interstitial_ad.js',
			array( 'jquery' ),
			false,
			false
		);

		wp_localize_script( 'epg_interstitial_ad', 'ad_data', $this->data );

		wp_enqueue_script( 'epg_interstitial_ad' );

		add_action( 'after_header', array($this, 'adPosition'), 100, '' );

    }

	public function adPosition() {
        ?>
		<div class='interstitialAd'>
			<div class="close-interstitial">X</div>
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
		</div>
		<?php
    }

	public function print_to_foot() {

		echo "<!-- Referral URL -->";
		echo "<!-- " . $this->referringURL . " --!>";
		echo "<!-- Cookie -->";
		echo "<!-- " . $this->visitCookie . " --!>";

	}
}