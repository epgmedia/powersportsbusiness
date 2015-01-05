<?php
/*
Plugin Name: Interstitial Ad
Description: Displays an interstitial ad. Frequency is controlled within Doubleclick for Publishers
Author: Christopher Gerber
Author URI: http://www.chriswgerber.com/
Version: 1.0
*/

class epg_interstitial_ads {

	public $dir_uri;

    /** Variables */
	public $data = array();

	public $page_code_id = '';

    /**
     * Constructor
     */
    public function __construct() {

	    $this->page_code_id = get_option('epg-ad-code-id');
	    $this->dir_uri      = get_template_directory_uri() . '/tools/interstitial-ad';

	    $this->data['ad_position'] = '/35190362/' . $this->page_code_id;
	    $this->data['position_tag'] = 'div-gpt-ad-1398116137114-0';

		wp_register_script(
			'epg_interstitial_ad',
			$this->dir_uri . '/interstitial_ad.js',
			array( 'jquery' ),
			false,
			false
		);

		wp_localize_script( 'epg_interstitial_ad', 'ad_data', $this->data );
		wp_enqueue_script( 'epg_interstitial_ad' );

	    add_action( 'admin_init', array( $this, 'setting_init' ) );
	    add_action( 'after_header', array($this, 'adPosition'), 100, '' );

    }

	public function adPosition() {
        ?>
		<div class='interstitialAd'>
			<div class="close-interstitial">X</div>
			<!-- Roadblock -->
			<div id='<?php echo $this->data['position_tag']; ?>' style='width:1px; height:1px;'>
				<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('<?php echo $this->data['position_tag']; ?>'); });
				</script>
			</div>
			<!-- Roadblock out-of-page -->
			<div id='<?php echo $this->data['position_tag']; ?>-oop'>
				<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('<?php echo $this->data['position_tag']; ?>-oop'); });
				</script>
			</div>
		</div>
		<?php
    }

	public function setting_init() {

		register_setting( 'general', 'epg-ad-code-id', 'esc_attr' );

		add_settings_field(
			'epg-ad-code-id',
			'Interstitial Ad Position ID',
			array( $this, 'setting_callback' ),
			'general',
			'default',
			array(
				'label_for' => 'epg-ad-code-id',
			    'id' => 'epg-ad-code-id',
			    'type' => 'text',
			    'value' => $this->page_code_id,
			)
		);

	}

	public function setting_callback($args) {
		?>
		<input name="<?php echo $args['id'] ?>" type="<?php echo $args['type'] ?>" id="<?php echo $args['id'] ?>" value="<?php echo $args['value'] ?>" class="regular-text">
		<?php
	}
}

