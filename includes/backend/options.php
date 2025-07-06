<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

use GFFormSettings;

defined( 'ABSPATH' ) || die();

class Options {
	public function __construct() {
		add_filter( 'gform_form_settings_menu', [ $this, 'add_new_tabs' ], 10, 2 );
		add_action( 'gform_form_settings_page_afzaliwp_gs_options', [ $this, 'render_settings' ] );
	}

	public function add_new_tabs( $tabs ) {
		$tabs[] = [
			'name'  => 'afzaliwp_gs_options',
			'icon'  => 'gform-icon--afzaliwp-logo',
			'label' => __( 'Google Sheets', 'afzaliwp-gs' ),
		];

		return $tabs;
	}

	public function render_settings() {
		GFFormSettings::page_header();
		require_once AFZALIWP_GS_DIR . 'templates/options.php';
		GFFormSettings::page_footer();
	}
}