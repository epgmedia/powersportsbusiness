<?php
/**
 * Wallpaper Ad
 */

class epg_wallpaper_ads {
	/**
	 * What it needs to do:
	 *
	 * Display wallpaper ad.
	 */

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('init', array($this, 'enqueueAdPosition'));
	}
	/**
	 * Enqueues items in HEAD
	 */
	public function enqueueAdPosition() {
		add_action( 'wp_head', array($this, 'headerScript'), 10, '');
		add_action( 'after_header', array($this, 'adPosition'), 100, '' );
	}

	public function headerScript() {
		echo "<script type='text/javascript'>
				googletag.cmd.push(function() {
					googletag.defineSlot('/35190362/PSB_Wallpaper', [1, 1], 'div-gpt-ad-1392153511566-0').addService(googletag.pubads());
					googletag.defineOutOfPageSlot('/35190362/PSB_Wallpaper', 'div-gpt-ad-1392153511566-0-oop').addService(googletag.pubads());
					googletag.pubads().enableSingleRequest();
					googletag.enableServices();
				});
            </script>";

		return;
	}

	public function adPosition() {
		echo "<!-- PSB_ROS_Wallpaper -->
				<div id='div-gpt-ad-1392153511566-0' style='width:1px; height:1px;'>
					<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1392153511566-0'); });
					</script>
				</div>
				<!-- PSB_ROS_Wallpaper out-of-page -->
				<div id='div-gpt-ad-1392153511566-0-oop'>
					<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1392153511566-0-oop'); });
					</script>
				</div>";

		return;
	}
}

new epg_wallpaper_ads();
