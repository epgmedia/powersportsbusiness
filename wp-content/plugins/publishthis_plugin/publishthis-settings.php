<?php
/*
* PublishThis constants definition
*/

define( "IS_VIP", false );

define( "CURATED_LOGO_PATH", "http://img.publishthis.com/images/ptbuttons/");

define( "IMPORT_MAX_ITEMS_COUNT", 15 );

$env = trim( file_get_contents( dirname( __FILE__ ) . '/environment' ) );

if( $env == 'dev' ) {
	define( "AUTO_UPDATE_JSON_INFO", "http://static.dev.publishthis.com/wpplugin/last/pluginInfo.json" );
	define( "PT_API_URL_3_0", "http://webapi.dev.publishthis.com/rest" );
	define( "MANAGER_API_URL", "https://manager.dev.publishthis.com/services/ex/auth/" );
	define( "GA_TRACKING_URL", "http://static.dev.publishthis.com/analytics/last/analytics.js" );
	define( "PT_ANALYTICS_URL", "http://analytics.dev.publishthis.com/analytics-embed?cid=" );
}
else {
	define( "AUTO_UPDATE_JSON_INFO", "http://static.publishthis.com/wpplugin/last/pluginInfo.json" );
	define( "PT_API_URL_3_0", "http://webapi.publishthis.com/rest" );
	define( "MANAGER_API_URL", "https://manager.publishthis.com/services/ex/auth/" );
	define( "GA_TRACKING_URL", "http://static.publishthis.com/analytics/last/analytics.js" );
	define( "PT_ANALYTICS_URL", "http://analytics.publishthis.com/analytics-embed?cid=" );
}

//define widgets and shortcodes options
global $pt_sort_by;
$pt_sort_by = array(
  "-1"              => "Default",
	"most_recent"       => "Most Recent",
	"trending_today"    => "Trending Today",
	"trending_pastweek" => "Trending Past Week" );

global $pt_tweets_sort_by;
$pt_tweets_sort_by = array(
  "-1"              => "Default",
	"date"              => "Most Recent",
	"date_asc"          => "Oldest First",
	"klout"             => "Klout Score",
	"followers"         => "Twitter Followers" );

global $pt_num_results;
$pt_num_results = array(
	"5"  => "5",
	"10" => "10",
	"15" => "15",
	"20" => "20",
	"25" => "25",
	"30" => "30" );

global $pt_cache_interval;
$pt_cache_interval = array(
	"1"    => "1 minute",
	"5"    => "5 minutes",
	"15"   => "15 minutes",
	"30"   => "30 minutes",
	"60"   => "1 hour",
	"120"  => "2 hours",
	"360"  => "12 hours",
	"1440" => "1 day" );

global $pt_columns_count;
$pt_columns_count = array(
	"1" => "1",
	"2" => "2",
	"3" => "3",
	"4" => "4",
	"5" => "5" );

global $pt_call_options;
$pt_call_options = array(
	"show_date" => array( "label" => "Show Publication Date/Time", "value" => "1" ),
	"show_links" => array( "label" => "Display Title with Link", "value" => "1" ),
	"show_summary" => array( "label" => "Show Summaries", "value" => "1" ),
	"show_source" => array( "label" => "Show Source Info", "value" => "1" ),
	"show_nofollow" => array( "label" => "All links add \"no follow\"", "value" => "1" ),
	"ok_resize_previews" => array( "label" => "Okay to Resize Previews", "value" => "1" ) );

global $pt_content_types;
$pt_content_types = array(
	"article,video,blog" => "All",
	"video" => "Video" );

global $import_options;
$import_options = array(
	"import_from_manager" => "PublishThis pushes to this CMS",
	"import_with_cron" => "This CMS polls PublishThis (with cron)",
	);
?>
