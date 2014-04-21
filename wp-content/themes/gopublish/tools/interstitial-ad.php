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
    private $referringURL;
    /** @var string Cookie seenAdPsb */
    private $visitCookie;

    /**
     * Constructor
     */
    function __constructor() {
        $this->referringURL = $_SERVER['HTTP_REFERER'];
        $this->visitCookie = $_COOKIE['seenAdPsb'];
        add_action('init', $this->enqueueAdPosition());
    }
    /**
     * Enqueues items in HEAD
     */
    private function enqueueAdPosition() {
        $this->cookieCheck();
    }
    /**
     * Checks to see if they're coming from Informz or outside URL
     * and whether they got cookies
     */
    private function cookieCheck() {
        if (!$this->visitCookie) {
            $this->referralSource();
        }
    }

    private function referralSource() {
        if ($this->referringURL &&
            !preg_match("/\/\/epgmediallc\.informz\.net/ig", $this->referringURL) ) {
            echo 'enqueue scripts and position';
            add_action( 'wp_head', $this->headerScript() );
            add_action( 'after_header', $this->adPosition() );
        }

    }

    private function headerScript() {
        echo $headerScript = "<script type='text/javascript'>
            googletag.cmd.push(function() {
            googletag.defineSlot('/35190362/PSB_Roadblock', [595, 430], 'div-gpt-ad-1397853377694-0').addService(googletag.pubads());
            googletag.defineOutOfPageSlot('/35190362/PSB_Roadblock', 'div-gpt-ad-1397853377694-0-oop').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
            });
            </script>";

        return;
    }

    private function adPosition() {
        echo $position = "
            <!-- PSB_Roadblock -->
            <div id='div-gpt-ad-1397853377694-0' style='width:595px; height:430px;'>
                <script type='text/javascript'>
                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1397853377694-0'); });
                </script>
            </div>
            <!-- PSB_Roadblock out-of-page -->
            <div id='div-gpt-ad-1397853377694-0-oop'>
                <script type='text/javascript'>
                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1397853377694-0-oop'); });
                </script>
            </div>";

        return;
    }
}

new epg_interstitial_ads();