<div style="clear:both"></div>
	<div id="leaderboardfooter">

	<?php if (get_theme_mod('leaderad-type-footer')=="Static Image") {

		 $leaderurl=get_theme_mod('leader-url-footer'); $leaderimage=get_theme_mod('leader-image-footer');
		 if ($leaderurl) echo '<a target="_blank" href="'.$leaderurl.'">'; if ($leaderimage) echo '<img src="'.$leaderimage.'" class="leaderimage" />'; if ($leaderurl) echo '</a>';

	 } else if (get_theme_mod('leaderad-type-footer')=="Ad Tag") {

		 //SWW Mod to target ads for Top 100 & MDCE sections
		$openxcode = get_theme_mod('openx-code-footer');
		$sww_uri = strtolower($_SERVER["REQUEST_URI"]);

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


		echo $openxcode;

		//SWW Mod - commented original code below
		//echo get_theme_mod('openx-code-footer');

	 } ?>


	</div>

