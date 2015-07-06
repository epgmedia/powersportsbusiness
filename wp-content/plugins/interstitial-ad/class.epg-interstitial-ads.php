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
	    $this->dir_uri      = plugins_url( null, __FILE__ );

	    $this->data['ad_position'] = '/35190362/' . $this->page_code_id;
	    $this->data['position_tag'] = 'interstitial_ad_pos';

	    add_action( 'admin_init', array( $this, 'setting_init' ) );
	    add_action( 'wp_enqueue_scripts', array($this, 'scripts_and_styles') );

    }

	public function scripts_and_styles() {

		wp_register_style( 'interstitial_css', $this->dir_uri . '/interstitial_ad.css' );
		wp_enqueue_style( 'interstitial_css' );

		wp_register_script(
			'epg_interstitial_ad',
			$this->dir_uri . '/interstitial_ad.js',
			array( 'jquery' ),
			false,
			false
		);

		wp_localize_script( 'epg_interstitial_ad', 'interstitial_ad', $this->data );
		wp_enqueue_script( 'epg_interstitial_ad' );

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

new epg_interstitial_ads();
