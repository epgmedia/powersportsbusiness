<?php
/**
 * Theme Functions
 */
/* Includes */
include 'tools/theme-options.php';
include 'tools/enews.php';
include 'tools/snotext.php';
include 'tools/audio.php';
include 'tools/video.php';
include 'tools/videoembed.php';
include 'tools/advertisement.php';
include 'tools/categorywidget.php';
include 'tools/productshowcase.php';
include 'tools/pagewidget.php';
/** Wallpaper Ads */
// include( TEMPLATEPATH . '/tools/wallpaper-ad.php' );


add_action( 'after_setup_theme', 'gopublish_theme_setup' );

function gopublish_theme_setup() {
	/* Filters, actions, and theme-supported features. */
	/**
	 * Theme options
	 */
	add_option("home_left_column", '280', '', 'yes');
	add_option("home_center_column", '280', '', 'yes');
	add_option("home_right_column", '300', '', 'yes');
	add_option("home_narrow_column", '160', '', 'yes');
	add_option("home_wide_column", '400', '', 'yes');
	add_option("home_full_width_column", '590', '', 'yes');
	add_option("non_home_right_column", '300', '', 'yes');
	add_option("bsno", 'bsno837625', 'yes');
	add_option("bussno", 'bussno379657', 'yes');
	update_option("bsno", 'bsno837625b', 'yes');
	update_option("bussno", 'bussno379657b', 'yes');

	/**
	 * Theme Support
	 */
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'nav-menus' );

	/**
	 * Image Sizes
	 */
	add_image_size( 'topstories', 608, 300, true );
	add_image_size( 'widesliderimage', 938, 300, true );
	add_image_size( 'home400', 400, 9999 );
	add_image_size( 'permalink', 298, 9999 );
	add_image_size( 'home280', 278, 9999 );
	add_image_size( 'archive', 200, 9999 );
	add_image_size( 'home160', 158, 9999 );
	add_image_size( 'homefeature', 158, 110, true );
	add_image_size( 'home120', 120, 9999 );
	add_image_size( 'ae', 60, 90, true );
	add_image_size( 'homethumb', 70, 70, true );
	add_image_size( 'videothumb', 90, 60, true );

	/** Filters */
	add_filter( 'post_thumbnail_html', 'my_post_image_html', 10, 3 );

	/** Actions */
	add_action( 'init', 'sno_farbtastic_script' );
	add_action( 'init', 'sno_thickbox_script' );
	add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

	// SNO Stuff
	add_action( 'admin_head', 'sno_css' );
	//add_action( 'wp_dashboard_setup', 'sno_add_dashboard_widgets' );
	//add_action( 'admin_bar_menu', 'my_admin_bar_menu');

	// Add EPG Media menu links
	add_action( 'admin_bar_menu', 'add_admin_bar_link', 50 );

	// Set max number of post revisions to hold
	if ( ! defined( 'WP_POST_REVISIONS' ) ) {
		define( 'WP_POST_REVISIONS', 5 );
	}

	/** Various Functions */
	// Register the sidebars
	epgmedia_register_sidebars();

}

function epgmedia_register_sidebars() {

	if ( function_exists('register_sidebars') ) {

		register_sidebar(
			array(
				'name'          => 'Non-Home Sidebar',
				'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
				'after_widget'  => '</div><div class="widgetfooter"></div></div>',
				'before_title'  => '</div><div class="titlewrap300"><h2>',
				'after_title'   => '</h2></div><div class="widgetbody">',
			)
		);

		register_sidebar(
			array(
				'name'          => 'Home Main Column',
				'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
				'after_widget'  => '</div><div class="widgetfooter"></div></div>',
				'before_title'  => '</div><div class="titlewrap610"><h2>',
				'after_title'   => '</h2></div><div class="widgetbody">',
			)
		);

		register_sidebar(
			array(
				'name'          => 'Ads Sidebar',
				'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
				'after_widget'  => '</div><div class="widgetfooter"></div></div>',
				'before_title'  => '</div><div class="titlewrap160"><h2>',
				'after_title'   => '</h2></div><div class="widgetbody">',
			)
		);

		register_sidebar(
			array(
				'name'          => 'Home Sidebar',
				'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
				'after_widget'  => '</div><div class="widgetfooter"></div></div>',
				'before_title'  => '</div><div class="titlewrap300"><h2>',
				'after_title'   => '</h2></div><div class="widgetbody">',
			)
		);

		if (
			( get_theme_mod( 'sno-layout' ) == "Option 3" ) ||
			( get_theme_mod('sno-layout') == "Option 6")
		) {

			register_sidebar(
				array(
					'name'          =>'Home Bottom Left',
					'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
					'after_widget'  => '</div><div class="widgetfooter"></div></div>',
					'before_title'  => '</div><div class="titlewrap280"><h2>',
					'after_title'   => '</h2></div><div class="widgetbody">',
				)
			);

			register_sidebar(
				array(
					'name'          => 'Home Bottom Right',
					'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
					'after_widget'  => '</div><div class="widgetfooter"></div></div>',
					'before_title'  => '</div><div class="titlewrap280"><h2>',
					'after_title'   => '</h2></div><div class="widgetbody">',
				)
			);

		} else {

			register_sidebar(
				array(
					'name'          => 'Home Bottom Narrow',
					'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
					'after_widget'  => '</div><div class="widgetfooter"></div></div>',
					'before_title'  => '</div><div class="titlewrap160"><h2>',
					'after_title'   => '</h2></div><div class="widgetbody">',
				)
			);

			register_sidebar(
				array(
					'name'          => 'Home Bottom Wide',
					'before_widget' => '<div style="clear:both"></div><div class="widgetwrap"><div>',
					'after_widget'  => '</div><div class="widgetfooter"></div></div>',
					'before_title'  => '</div><div class="titlewrap400"><h2>',
					'after_title'   => '</h2></div><div class="widgetbody">',
				)
			);

		}

		register_sidebar(
			array(
				'name'          => 'showcases',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '</h2>'
			)
		);

	}

}

/**
 * Hook for running functions after < head >
 */
function after_header() {
	do_action( 'after_header', '' );
}

function my_admin_bar_menu() {
	/**
	 * @var $wp_admin_bar WP_Admin_Bar
	 */
	global $wp_admin_bar;
	if ( !is_user_logged_in() || !is_admin_bar_showing() )
		return;
	$wp_admin_bar->add_menu( array(
			'id' => 'custom_menu',
			'title' => __( 'GoPublish Framework'),
			'href' => FALSE ) );
	$wp_admin_bar->add_menu( array(
			'parent' => 'custom_menu',
			'title' => __( 'Add a Story'),
			'href' => '/wp-admin/post-new.php?custom-write-panel-id=1' ) );
	$wp_admin_bar->add_menu( array(
			'parent' => 'custom_menu',
			'title' => __( 'Add, Delete, or Rearrange Widgets'),
			'href' => '/wp-admin/widgets.php' ) );
	$wp_admin_bar->add_menu( array(
			'parent' => 'custom_menu',
			'title' => __( 'Edit Navigation Menus'),
			'href' => '/wp-admin/nav-menus.php' ) );
	$wp_admin_bar->add_menu( array(
			'parent' => 'custom_menu',
			'title' => __( 'Change Colors, Columns, or Appearance'),
			'href' => '/wp-admin/themes.php?page=theme-options' ) );
	$wp_admin_bar->add_menu( array(
			'id' => 'custom_menu_help',
			'title' => __( 'GoPublish Help and Support'),
			'href' => FALSE ) );
	$wp_admin_bar->add_menu( array(
			'parent' => 'custom_menu_help',
			'title' => __( 'Instruction Manual and Videos'),
			'meta' => array( 'target' => '_blank' ),
			'href' => 'http://www.schoolnewspapersonline.com/instruction-manual-4-2/' ) );
	$wp_admin_bar->add_menu( array(
			'id' => 'custom_menu_logout',
			'title' => __( 'Logout'),
			'href' => wp_logout_url( home_url() ) ) );
}
function sno_add_dashboard_widgets() {
	wp_add_dashboard_widget('sno_announcements', 'GoPublish News & Announcements', 'sno_dashboard_widget');
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$sno_widget_backup = array('sno_announcements' => $normal_dashboard['sno_announcements']);
	unset($normal_dashboard['sno_announcements']);
	$sorted_dashboard = array_merge($sno_widget_backup, $normal_dashboard);
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

function sno_css() {
	$favicon = get_theme_mod('favicon');
	echo '<link rel="Shortcut Icon" href="'.$favicon.'" type="image/x-icon" />
   <style type="text/css">
		#sno_announcements h3 { background:#990000; color:#ffffff;text-shadow:none;}
		#sno_announcements {border-color:#990000; -moz-box-shadow: 1px 1px 5px #888; -webkit-box-shadow: 1px 1px 5px #888; box-shadow: 1px 1px 5px #888;}
		#sno_announcements a {color:#990000;}
		#sno_announcements a:hover {color:#990000;text-decoration:underline;}
		.sno_options_page .postbox {border: 1px solid #777777; -moz-box-shadow: 1px 1px 5px #888; -webkit-box-shadow: 1px 1px 5px #888; box-shadow: 1px 1px 5px #888; }   
		.sno_options_page h3, .sno_options_page h3:hover { background:#777777; color:#ffffff;text-shadow:none;cursor:default;}
		.sno_options_page .divline {clear:both;border-top:1px solid #888888;margin:25px 0px;}
		.sno_options_page p {margin: 0 0 1em 0}
		#snocolorpicker { position:fixed;border:1px solid #aaaaaa;}
		input.save-button { position:fixed;margin-top:240px;margin-left:15px;font-size:18px!important;}
		.optionsbox {padding:10px;border:1px solid #aaaaaa;background:#ffffff;width:260px;float:left;margin-right:10px;}
		.optionsboxright {padding:10px;border:1px solid #aaaaaa;background:#ffffff;width:260px;float:left;}
		.headingtext { font-weight:bold;font-size:14px;}
		.glossymenu{ margin: 5px 0; padding: 0; border: 1px solid #cccccc; border-bottom-width: 0; }
		.glossymenu a.menuitem { background: black url(/wp-content/themes/gopublish/images/glossyback.gif) repeat-x bottom left; font: 18px "Lucida Grande", "Trebuchet MS", Verdana, Helvetica, sans-serif; color: white; display: block; position: relative; width: auto; padding: 6px 0; padding-left: 10px; text-decoration: none; }
		.glossymenu a.menuitem:visited, .glossymenu .menuitem:active { color: white; }
		.glossymenu a.menuitem .statusicon{ margin-right:10px; border: none; }
		.glossymenu a.menuitem:hover { background-image: url(/wp-content/themes/gopublish/images/glossyback2.gif); }
		.glossymenu div.submenu{ padding:10px 10px 10px 10px; background: #f5f5f5; }
		h2:before {
			content: "\f108";
			display: inline-block;
			-webkit-font-smoothing: antialiased;
			font: normal 29px/1 "dashicons";
			vertical-align: middle;
			margin-right: 0.3em;
			}
	</style>';
}



function sno_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('sno-upload', get_bloginfo('template_url').'/tools/sno-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('sno-upload');
	wp_enqueue_script('jquery.js');
}

function sno_admin_styles() {
	wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'theme-options') {
	add_action('admin_print_scripts', 'sno_admin_scripts');
	add_action('admin_print_styles', 'sno_admin_styles');
}

function sno_farbtastic_script() {
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}

function sno_thickbox_script() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'thickbox' );
}

function remove_admin_bar_links() {
	/**
	 * @var $wp_admin_bar WP_Admin_Bar
	 */
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('new-content');
	$wp_admin_bar->remove_menu('updates');
	$wp_admin_bar->remove_menu('appearance');
	$wp_admin_bar->remove_menu('wp-logo');
}

/**
 * @var $wp_admin_bar WP_Admin_Bar
 */
function add_admin_bar_link( $wp_admin_bar ) {
	$class = 'epg-media-link';
	$wp_admin_bar->add_menu( array(
			'id' => $class,
			'title' => __( 'EPG Media, LLC Homepage' ),
			'href' => __('http://www.epgmediallc.com'),

		) );
	$wp_admin_bar->add_menu( array(
			'parent' => $class,
			'id' => 'epg-media-time-off',
			'title' => __( 'Time Off Request' ),
			'href' => __('http://www.epgmediallc.com/time-off-request/'),
		) );
	$wp_admin_bar->add_menu( array(
			'parent' => $class,
			'id' => 'epg-media-support',
			'title' => __( 'IT Request' ),
			'href' => __('http://www.epgmediallc.com/it-request/'),
		) );

}

function my_post_image_html( $html, $post_id ) {
	global $post;
	$customlink = get_post_meta($post->ID, 'customlink', true);
	$click      = get_post_meta($post->ID, 'click_tracker_code', true);
	if ($customlink) {
		$photolink = $customlink . $click;
		$target    = 'target="_blank" ';
	} else {
		$photolink = get_permalink ($post_id);
		$target    = '';
	}
	$html = '<a '. $target.'href="' . $photolink . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';

	return $html;
}

/* turns a category ID to a Name */
function cat_id_to_name( $id ) {
	foreach( get_categories() as $category ) {
		if ( $id == $category->cat_ID ) {

			return $category->cat_name;
		}
	}

	return null;
}

/* turns a category ID to a Slug */
function cat_id_to_slug( $id ) {
	foreach( get_categories() as $category ) {
		if ( $id == $category->cat_ID ) {

			return $category->category_nicename;
		}
	}

	return null;
}

function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0) {
	$content = get_the_content( $more_link_text, $stripteaser );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);

	if (strlen($_GET['p']) > 0) {
		echo "<p>";
		echo $content;
		echo "</p>";
	}
	else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $espacio);
		echo "<p>";
		echo $content;
		echo "...";
		echo "&nbsp;<a href='";
		the_permalink();
		echo "'>".$more_link_text."</a>";
		echo "</p>";
	}
	else {
		echo "<p>";
		echo $content;
		echo "</p>";
	}
}

function snowriter() {
	global $post;
	$writer = get_post_meta($post->ID, 'writer', true);
	$jobtitle = get_post_meta($post->ID, 'jobtitle', true);
	if ($writer != "") {
		$args = array( 'meta_key' => 'name', 'meta_value' => $writer, 'numberposts' => 1 );
		$queried_posts = get_posts( $args );

		if ($queried_posts) {
			foreach ($queried_posts as $queried_post) {
				$thePostID = $queried_post->ID;
				$link = get_permalink($thePostID);
				echo '<a href="'.$link.'">'.$writer.'</a>'; if ($jobtitle) echo ', '.$jobtitle; echo '<br />';
			}
		} else {
			echo $writer; if ($jobtitle) echo ', '.$jobtitle; echo '<br />';
		}
	}
}

function targetted_ad_code($string) {

	$sww_uri = strtolower( $_SERVER["REQUEST_URI"] );

	if ( strpos( $sww_uri, '/power-50/') !== false ) {
		$searchstr = "div-gpt-ad-1375801013938";
		$replacestr = "div-gpt-ad-1375817072470";
		$string = str_ireplace($searchstr, $replacestr, $string);

		$searchstr = "PSB_ROS";
		$replacestr = "PSB_P50";
		$string = str_ireplace($searchstr, $replacestr, $string);

	}

	if ( strpos( $sww_uri, '/institute/') !== false ) {
		$searchstr = "div-gpt-ad-1375801013938";
		$replacestr = "div-gpt-ad-1375816978942";
		$string = str_ireplace($searchstr, $replacestr, $string);

		$searchstr = "PSB_ROS";
		$replacestr = "PSB_AIM";
		$string = str_ireplace($searchstr, $replacestr, $string);
	}

	return $string;
}