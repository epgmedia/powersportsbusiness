</div><!--innerwrap-->

<?php if (get_theme_mod('display-leader-footer') == "Yes") include(TEMPLATEPATH . "/leaderboardfoot.php"); ?>

<!-- begin footer -->

<div style="clear:both;"></div>
</div><!--wrap-->

<div id="footer">
<?php wp_reset_query(); ?>
	<p>Copyright &copy; <?php echo date('Y'); ?> &bull; <a href="<?php if (get_theme_mod('google-apps')) { echo get_theme_mod('google-apps'); } else { bloginfo('url'); } ?>"><?php bloginfo('name'); ?></a> &bull; Design</a> by <a href="http://www.schoolnewspapersonline.com" >SNO Sites</a> and <a href="http://www.godengo.com" >Godengo</a> <?php wp_register(' &bull; ', ''); ?>


</p>

</div>

<!-- Footer --><?php do_action('wp_footer'); ?>


		<?php if(get_theme_mod('analytics') == 'Yes') { ?>
        <!-- Analytics -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', <?php echo get_theme_mod('analytics_code'); ?>, 'auto');
			ga('require', 'displayfeatures');
			ga('send', 'pageview');

		</script>

		<?php } ?>

		<!-- Quantcast Tag -->
		<script type="text/javascript">
			var _qevents = _qevents || [];

			(function() {
				var elem = document.createElement('script');
				elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
				elem.async = true;
				elem.type = "text/javascript";
				var scpt = document.getElementsByTagName('script')[0];
				scpt.parentNode.insertBefore(elem, scpt);
			})();

			_qevents.push({
				qacct:"p-0948HkAy_Q_06"
			});
		</script>

		<noscript>
			<div style="display:none;">
				<img src="//pixel.quantserve.com/pixel/p-0948HkAy_Q_06.gif" border="0" height="1" width="1" alt="Quantcast"/>
			</div>
		</noscript>
		<!-- End Quantcast tag -->

		<SCRIPT LANGUAGE='JAVASCRIPT'>
			if( document.URL.indexOf('zmsg=1') > -1)
			{
				alert('Thank you for subscribing.')
			}
		</SCRIPT>
		<!-- Email confirmation -->

</body>
</html>