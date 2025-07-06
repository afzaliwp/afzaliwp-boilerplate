<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

defined( 'ABSPATH' ) || die();

class Sheet_Options {
	public function __construct() {
		add_action( 'wp_ajax_afzaliwp_save_sheet_options', [ $this, 'save_options' ] );
		add_action( 'wp_ajax_nopriv_afzaliwp_save_sheet_options', [ $this, 'save_options' ] );
	}

	public function save_options() {
		$options = $_POST[ 'options' ];
		$form_id = isset( $_POST[ 'form_id' ] ) ? absint( $_POST[ 'form_id' ] ) : 0;

		mylog($options);
		if ( ! $form_id ) {
			wp_send_json_error( esc_html__( 'Form ID is missing.', 'afzaliwp-gs' ) );

			return;
		}

		$form = \GFAPI::get_form( $form_id );
		if ( is_wp_error( $form ) ) {
			wp_send_json_error( $form->get_error_message() );

			return;
		}

		foreach ( $options as $option => $value ) {
			$form[ esc_html( $option ) ] = $value;
		}

		$update_result = \GFAPI::update_form( $form );
		if ( is_wp_error( $update_result ) ) {
			wp_send_json_error( $update_result->get_error_message() );

			return;
		}
		wp_send_json_success();

	}

}
