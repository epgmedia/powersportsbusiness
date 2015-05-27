<?php
/**
 * used to show debug or error messages from our plugin on the
 * clients dashboard. Used for debugging any issues
 * with the client.
 */
PublishThis_Debug_Dashboard_Widget::on_load();

class PublishThis_Debug_Dashboard_Widget {

	static function on_load() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
	}

	static function admin_init() {
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'wp_dashboard_setup' ) );
	}

	static function wp_dashboard_setup() {
		wp_add_dashboard_widget( 'ptdebug-log-widget', __ ( 'PublishThis Message Log', 'ptdebug-log-widget' ), array( __CLASS__, 'ptwidgetlog_callback' ) );
	}

	static function ptwidgetlog_callback() {
		global $publishthis;

		@session_start();
		$lines =  $_SESSION['pt_local_messages'] = $publishthis->log->getMessages(); //get_transient( "pt_local_messages" );
		
		$_SESSION['pt_version'] = $publishthis->version;
		$_SESSION['pt_categories'] = "";
		//get taxonomies categories
		$all_taxonomies = get_taxonomies( array( 'public'   => true ), $output = 'objects', $operator = 'and' );
		$taxonomies_keys = array_keys( $all_taxonomies );

		$all_terms = get_terms( $taxonomies_keys, array( 'orderby' => 'id', 'hide_empty' => 0, 'exclude' => array(1) ) );

		foreach ( $all_terms as $term ) {
			$_SESSION['pt_categories'] .= 'Category #' . intval( $term->term_id ) . " " . $term->name . " (slug: " . $term->slug . ")\r\n" .
										'Taxonomy #' . intval( $term->term_taxonomy_id ) . " " . $term->taxonomy . "\r\n" .
										'Parent: ' . intval( $term->parent ) . "\r\n\r\n";
		}

		$actions = $publishthis->publish->get_publishing_actions();
		foreach( $actions as $action ) {
			$_SESSION['pt_publishing_actions'][$action->ID] = array_merge( array( 'title' => $action->post_title ), get_post_meta( $action->ID ) );
		}

		$_SESSION['pt_settings'] = $publishthis->get_options();

		if ( empty( $lines ) ) {
			echo '<p>' . __ ( 'No messages found.', 'ptdebug-log-widget' ) . '</p>';
			return;
		}

?>
	<div class="widefat" style="height: 400px; overflow-x:scroll; overflow-y:visible;">
		<table class="widefat" id="example1">
		<tr>
			<th width="20">&nbsp;</th>
			<th>Date</th>
			<th>Message <a style="float:right;" href="<?php echo $publishthis->plugin_url(); ?>/modules/misc/log-export.php">Download .txt</a></th>
		</tr>
		<?php
		foreach ( $lines as $line ) {
			if ( empty( $line['message']  ) ) continue;

			switch ( $line['status'] ) {
			case 'info':
				$status_img = '<img src="'.$publishthis->plugin_url().'/assets/img/info.png" title="'.esc_html( ucfirst( $line['status'] ) ).'"/>';
				break;

			case 'warn':
				$status_img = '<img src="'.$publishthis->plugin_url().'/assets/img/warning.png" title="'.esc_html( ucfirst( $line['status'] ) ).'"/>';
				break;

			case 'error':
				$status_img = '<img src="'.$publishthis->plugin_url().'/assets/img/error.png" title="'.esc_html( ucfirst( $line['status'] ) ).'"/>';
				break;

			default:
				$status_img = '';
				break;
			}

			$debugMessage = '<b>'.esc_html( $line['message'] ).'</b>';
			if ( !empty( $line['details'] ) ) {
				$debugMessage .= '<br/><font style="font-size:11px;">'. $line['details'] .'</font>';
			}

			echo '<tr>'.
				'<td align="center">'.$status_img.'</td>'.
				'<td>'.esc_html( $line['time'] ).'</td>'.
				'<td>'.$debugMessage.'</td>'.
				'</tr>';

		}

		?></table></div><?php
	}

}
