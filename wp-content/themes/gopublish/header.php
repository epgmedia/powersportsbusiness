<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="distribution" content="global" />
<meta name="robots" content="follow, all" />
<meta name="language" content="en, sv" />
<meta name="google-site-verification" content="9EEzgkok8W1ZOtoE_fhrWStc02xXbFrE4Cz5U_MN0EE" />
<meta name="google-site-verification" content="rysPv8aiH9hp_BUOHv4iB00waWf_8wz3HDhwW6jHmoE" />

<title><?php bloginfo('name'); ?><?php if(wp_title('', false)) { echo ' : '; wp_title(''); } else { echo ' : '; bloginfo('description'); } ?></title>
<meta name="description" content="<?php bloginfo('description'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<!-- leave this for stats please -->

<link rel="Shortcut Icon" href="<?php echo get_theme_mod('favicon'); ?>" type="image/x-icon" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>

<?php wp_enqueue_style('thickbox'); ?> <!-- inserting style sheet for Thickbox.  -->

<?php wp_enqueue_script('thickbox'); ?> <!--  including Thickbox javascript. -->

<!-- begin content slider -->
<script src="<?php bloginfo('template_url'); ?>/javascript/jquery-latest.pack.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/javascript/jquery.cycle.all.min.js"></script>

<script type="text/javascript">
	var $ = jQuery.noConflict();
		$(document).ready(function(){
		 	$('#slideshow').cycle({
			fx:      "<?php echo get_theme_mod('top-stories-transition'); ?>", // choose your transition type, ex: fade, scrollUp, shuffle, etc...
			pager:   '#pager',  // selector for element to use as pager container
			speed: <?php echo get_theme_mod('top-stories-trans-speed'); ?>,
			<?php if (get_theme_mod('top-stories-automate')=="On") { ?>
			timeout: <?php echo get_theme_mod('top-stories-speed'); ?>,  // milliseconds between slide transitions (0 to disable auto advance)
			pause:   true,	  // true to enable "pause on hover"
			pauseOnPagerHover: true // true to pause when hovering over pager link
			<?php } else { ?>
			timeout: 0
			<?php } ?>
			});

		});
</script>


<script src="<?php bloginfo('template_url'); ?>/javascript/jcarousellite_1.0.1c4.js" type="text/javascript"></script>

<!--
<script type="text/javascript">
$(function() {
$(".newsticker-jcarousellite").jCarouselLite({
        <?php echo get_theme_mod('sports-scroll-style'); ?>: true,
        visible: <?php echo get_theme_mod('sports-scrollbox-visible'); ?>,
        auto: <?php echo get_theme_mod('sports-speed'); ?>,
        speed:<?php echo get_theme_mod('sports-transition'); ?>,
	<?php if (get_theme_mod('sports-scroll-style') == "vertical") {?>scroll: <?php echo get_theme_mod('sports-scrollbox-number'); ?><?php } ?>
    });
});
</script>
<script type="text/javascript">
$(function() {
$(".newsticker2-jcarousellite").jCarouselLite({
        <?php echo get_theme_mod('breaking-scroll-style'); ?>: true,
        visible: <?php echo get_theme_mod('breaking-visible'); ?>,
        auto:<?php echo get_theme_mod('breakingnews-speed'); ?>,
        speed:<?php echo get_theme_mod('breakingnews-transition'); ?>,
	<?php if (get_theme_mod('breaking-scroll-style') == "vertical") {?>scroll: <?php echo get_theme_mod('breaking-scroll-number'); ?><?php } ?>
    });
});
</script>

<script type="text/javascript">
$(function() {
$(".newsticker3-jcarousellite").jCarouselLite({
        <?php echo get_theme_mod('breaking-scroll-style'); ?>: true,
        visible: 1,
        auto:<?php if (get_theme_mod('breakingnews-scrollbox')=="1") { echo '0'; } else { echo get_theme_mod('breakingnews-speed'); } ?>,
        speed:<?php echo get_theme_mod('breakingnews-transition'); ?>
    });
});
</script>
-->

<style type="text/css" media="screen"><!-- @import url( <?php bloginfo('stylesheet_url'); ?> ); --></style>
<?php include(TEMPLATEPATH . "/customstyles.php"); ?>

<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls = document.getElementById("nav").getElementsByTagName("li");

	// if you only have one main menu - delete the line below //
	var sfEls1 = document.getElementById("subnav").getElementsByTagName("li");
	//

	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}

	// if you only have one main menu - delete the "for" loop below //
	for (var i=0; i<sfEls1.length; i++) {
		sfEls1[i].onmouseover=function() {
			this.className+=" sfhover1";
		}
		sfEls1[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover1\\b"), "");
		}
	}
	//

}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]>
</script>
<script type='text/javascript'>
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
</script>

<?php
$sww_uri = strtolower($_SERVER["REQUEST_URI"]);
$openxcode = "
    googletag.cmd.push(function() {
        googletag.defineSlot('/35190362/PSB_ROS_160_SB1', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-0').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB2', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-1').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB3', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-2').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB4', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-3').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB5', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-4').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB6', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-5').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_160_SB7', [[120, 240], [125, 125], [120, 600], [160, 160], [160, 240], [160, 600], [160, 300]], 'div-gpt-ad-1375801013938-6').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_Kolpin_Targeted_300', [300, 250], 'div-gpt-ad-1382561591995-0').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_Kolpin_Targeted_728', [728, 90], 'div-gpt-ad-1382561591995-1').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_300_LR', [300, 250], 'div-gpt-ad-1375801013938-7').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_300_Mid', [300, 250], 'div-gpt-ad-1375801013938-8').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_300_Mid2', [300, 250], 'div-gpt-ad-1375801013938-9').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_300_UR', [300, 250], 'div-gpt-ad-1375801013938-10').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_Footerboard', [[468, 60],[728, 90]], 'div-gpt-ad-1375801013938-11').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_Leaderboard', [728, 90], 'div-gpt-ad-1375801013938-12').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_middle468', [468, 60], 'div-gpt-ad-1375801013938-13').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_ROS_middle468_2', [468, 60], 'div-gpt-ad-1375801013938-14').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_Fox_Targeted_728', [728, 90], 'div-gpt-ad-1391092863898-0').addService(googletag.pubads());
        googletag.defineSlot('/35190362/PSB_Fox_Targeted_300', [300, 250], 'div-gpt-ad-1391029424174-0').addService(googletag.pubads());
        googletag.pubads().collapseEmptyDivs(true);
        googletag.enableServices();
    });
";

if (strpos($sww_uri,'/power-50/') !== false) {
    $searchstr = "div-gpt-ad-1375801013938";
    $replacestr = "div-gpt-ad-1375817072470";
    $openxcode = str_ireplace($searchstr, $replacestr, $openxcode);

    $searchstr = "PSB_ROS";
    $replacestr = "PSB_P50";
    $openxcode = str_ireplace($searchstr, $replacestr, $openxcode);

}

if (strpos($sww_uri,'/institute/') !== false) {
    $searchstr = "div-gpt-ad-1375801013938";
    $replacestr = "div-gpt-ad-1375816978942";
    $openxcode = str_ireplace($searchstr, $replacestr, $openxcode);

    $searchstr = "PSB_ROS";
    $replacestr = "PSB_AIM";
    $openxcode = str_ireplace($searchstr, $replacestr, $openxcode);
}
?>
<script type='text/javascript'>
    <?php echo $openxcode ?>
</script>

<?php wp_head(); ?>

</head>


<body>

<?php
/** After the header hook */
after_header();
?>

<div id="rightcolumnads">
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(6) ) : else : endif; ?>
</div>
<div id="pagetop"></div>

	<?php if (get_theme_mod('leaderboard-location') == "Above Header") include(TEMPLATEPATH . "/leaderboardhead.php"); ?>



<?php if (get_theme_mod('breakingnews-location') == "Header") { ?>
<?php $scrollspeed = get_theme_mod('breakingnews-speed'); ?>

<?php $bncheck = get_option('bsno'); if ($bncheck == "bsno837625b")

	{ include(TEMPLATEPATH."/tools/breakingnews.php"); } ?>

<?php } ?>

<div id="wrap">

<div id="header">

  <div id="headerleft" <?php if(get_theme_mod('header_blog_title') == 'Image' || 'SubscribeAd') { ?>style="padding-left:0px;width:960px;" <?php } ?>>

    <?php if(get_theme_mod('header_blog_title') == 'Image') { ?>

            <a href="<?php echo get_settings('home'); ?>/"><img src="<?php echo get_theme_mod('header-image'); ?>" style="width:<?php echo get_theme_mod('header-width'); ?>px;height:<?php echo get_theme_mod('header-height'); ?>px" alt="<?php bloginfo('description'); ?>" /></a>

    <?php } else if (get_theme_mod('header_blog_title')=='SubscribeAd') { ?>

	            <div style="float:left;"><a href="<?php echo get_settings('home'); ?>/"><img src="<?php echo get_theme_mod('header-image'); ?>" style="width:<?php echo get_theme_mod('header-width'); ?>px;height:<?php echo get_theme_mod('header-height'); ?>px" alt="<?php bloginfo('description'); ?>" /></a></div><div style="float:right;"><a href="<?php echo get_theme_mod('subscribe-link'); ?>"><img src="<?php echo get_theme_mod('subscribe-image'); ?>" style="width:<?php echo get_theme_mod('subscribe-width'); ?>px;height:<?php echo get_theme_mod('subscribe-height'); ?>px;border:none;" alt="Subscribe Today" /></a></div>

	    <?php } else  { ?>
	
                <a href="<?php echo get_settings('home'); ?>/"><h1><?php bloginfo('name'); ?></h1></a>
                <p><?php bloginfo('description'); ?></p>  


    <?php } ?>
	</div>

</div>

<div id="navbar">

	<div id="navbarleft">
		<ul id="nav">
   			<?php wp_nav_menu( array('menu' => 'Top Menu') ); ?>
		</ul>
	</div>

	<div id="navbarright">
		<form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="text" value="search our site..." name="s" id="searchbox" onfocus="if (this.value == 'search our site...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search our site...';}" /><input type="image" style="vertical-align:top;" src="<?php bloginfo('template_url'); ?>/images/search.png" alt="Search" /></form>
	</div>

</div>


<?php if (get_theme_mod('bottomnav')=="Show") { ?>

<div id="subnavbar">
	<ul id="subnav">
   			<?php wp_nav_menu( array('menu' => 'Bottom Menu') ); ?>
	</ul>


</div>


<?php } ?>

	<?php if (get_theme_mod('leaderboard-location') == "Below Header") include(TEMPLATEPATH . "/leaderboardhead.php"); ?>




<div class="innerwrap">