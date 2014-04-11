				</div><!-- #content -->
					
				<?php do_action( 'wptouch_body_bottom' ); ?>
						
				<?php if ( wptouch_show_switch_link() ) { ?>
					<div id="switch" class="rounded-corners-8px">
						<span class="switch-text">
							<?php _e( "Mobile Theme", "wptouch-pro" ); ?>
						</span>
						<a href="<?php wptouch_the_mobile_switch_link(); ?>"><input type="checkbox" checked="checked" /></a>
					</div>
				<?php } ?>
						
				<div class="<?php wptouch_footer_classes(); ?>">
					<?php wptouch_footer(); ?>
				</div>
	
				<?php do_action( 'wptouch_advertising_bottom' ); ?>
			</div> <!-- #inner-ajax -->
		</div> <!-- #outer-ajax -->
		<?php // include_once('web-app-bubble.php'); ?>
		<!-- <?php echo 'WPtouch Pro v.' . WPTOUCH_VERSION; ?> -->
	</body>
</html>