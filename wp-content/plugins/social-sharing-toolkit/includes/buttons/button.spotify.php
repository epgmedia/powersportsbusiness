<?php
class MR_Social_Sharing_Toolkit_Spotify extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->follow_buttons[] = array('name' => 'follow_spotify', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'Spotify';
		$this->icon = 'spotify';
	}
	
	function follow_spotify($type, $id, $text = '', $icon = '') {
		$url = 'http://open.spotify.com/user/'.$id;
		$text = ($text == '') ? __('My profile on','mr_social_sharing_toolkit').' Spotify' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>