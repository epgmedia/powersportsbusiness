<?php
// this file contains the contents of the popup window
require_once realpath( dirname( __FILE__ ) . "/../publishthis-settings.php" );
require_once realpath( dirname( __FILE__ ) . "/../../../../wp-load.php" );
require_once realpath( dirname( __FILE__ ) . "/../../../../wp-includes/general-template.php" );

//generate ajax nonce
$nonce = wp_create_nonce( 'publishthis_admin_widgets_nonce' );

// set popup dafaults according to feed type
$content_type = trim( $_REQUEST['type'] );
switch ( $content_type ) {
case 'feed':
	$popup_title = 'Insert Mix';
	$default_title = 'Automated Content - PublishThis';
	break;

case 'topic':
	$popup_title = 'Insert Topic';
	$default_title = 'Topic Content - PublishThis';
	break;

case 'savedsearch':
	$popup_title = 'Insert Saved Search';
	$default_title = 'Automated Content - PublishThis';
	break;

case 'tweet':
	$popup_title = 'Insert Tweets';
	$default_title = 'Automated Content - PublishThis';
	break;
case 'curatedfeed':
  $popup_title = 'Insert Curated';
	$default_title = 'Curated Content - PublishThis';
	break;

default:
	$popup_title = '';
	$default_title = '';
	break;
}

$obj = new PublishThis_Shortcodes_Name();
$page_id = "add-feed";

class PublishThis_Shortcodes_Name {
	public $field_pref = 'add-feed';

	function get_field_id( $id ) {
		return $this->field_pref . '-' . $id;
	}

	function get_field_name( $name ) {
		return $this->field_pref . '-' . $name;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $popup_title; ?></title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="../assets/js/admin-shortcodes.js"></script>
	<style type="text/css" src="../../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css"></style>
	<link rel="stylesheet" href="../assets/css/shortcodes-buttons.css" />
</head>
<body>
	<div id="<?php echo $obj->field_pref; ?>-dialog">
		<form action="/" method="get" accept-charset="utf-8">
			<?php
				global $publishthis;
				$status = $publishthis->api->validate_token( sanitize_text_field( $publishthis->get_option( 'api_token' ) ) );
				if ( !$status['valid'] ) {
					echo '<div class="error">API Token is empty or invalid</div>';
				}
			?>

			<a href="#" class="button insert-shortcode" id="insert">Insert</a><br /><br />
			
			<?php  if ( $content_type=='topic' ) { ?>
				<input type="hidden" id="topic-nonce" name="nonce" value="<?php echo $nonce; ?>" />
			<?php } ?>

			<?php
				$instance = array( 
					'title'              => $default_title,
					'sort_by'            => '-1',
					'num_results'        => 10,
					'columns_count'      => 1,
					'image_align'        => 'left',
					'image_size'         => 'default',
					'image_width'        => '0',
					'image_height'       => '0',
					'max_width_images'   => '300',
					'show_links'         => '1',
					'show_photos'        => '1',
					'show_source'        => '1',
					'show_summary'       => '1',
					'remove_duplicates'  => '1',
					'remove_related'     => '0',
					'mix_defaults'		 => '1',
					'show_date'          => '0',
					'show_nofollow'      => '0',
					'content_types'      => 'article,video,blog',
					'ok_resize_previews' => '1',
					'cache_interval'     => 60
			 );
				include realpath( dirname( __FILE__ ) . "/../templates/automated-popup.php" );
			?>
			
			<a href="#" class="button insert-shortcode" id="insert">Insert</a>			
			<p><br/><br/></p>
		</form>
	</div>
<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
	shortcodesFunc.initDOM('<?php echo $content_type; ?>');
</script>
</body>
</html>
