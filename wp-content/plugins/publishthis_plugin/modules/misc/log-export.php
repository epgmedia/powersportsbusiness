<?php
session_start();
require_once "../../classes/class-publish.php";
$lines = $_SESSION['pt_local_messages'];

$txt = "version: ". $_SESSION['pt_version'] ."\r\n\r\n";

if ( empty( $lines ) ) {
	$txt .= 'No messages found.';
}
else {
	foreach ( $lines as $line ) {
		if ( !empty( $line['message'] ) ) {
			$txt .= $line['time'].': '.$line['message']." ".str_replace( array( '<br>', '<br/>', '<br />' ), " ", $line['details'] )."\r\n";
		}
	}
}

$txt .= "\r\n\r\n";
$txt .= "================================================\r\n";
$txt .= "=             Publishthis Settigns             =\r\n";
$txt .= "================================================\r\n";
foreach ( $_SESSION['pt_settings'] as $k=>$v ) {
	$txt .= str_replace( '_', ' ', $k).": ".$v."\r\n";
}

$txt .= "\r\n\r\n";
$txt .= "================================================\r\n";
$txt .= "=             Publishing Actions               =\r\n";
$txt .= "================================================\r\n";
foreach ( $_SESSION['pt_publishing_actions'] as $action_id=>$action ) {
	$txt .=  "ID #".$action_id." - ". $action['title'] ."\r\n";
	foreach ( $action as $k=>$v ) {
		if( !in_array( $k, array('title', '_edit_last') ) ) {
			$txt .= str_replace( array('publishthis', '_'), ' ', $k).": ".$v[0]."\r\n";
		}
	}
	$txt .=  "\r\n";
}

$txt .= "\r\n\r\n";
$txt .= "================================================\r\n";
$txt .= "=            Categories & Taxonomies           =\r\n";
$txt .= "================================================\r\n";
$txt .= $_SESSION['pt_categories'];

header( "Content-type: text/plain" );
if ($_GET['monitor'] === NULL) {
    header("Content-disposition: attachment; filename=publishthis_logs.txt");
}
echo $txt;
exit;

?>
