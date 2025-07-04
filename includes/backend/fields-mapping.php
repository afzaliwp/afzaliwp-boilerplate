<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

defined( 'ABSPATH' ) || die();

class Fields_Mapping {

	public function __construct() {
		add_action( 'wp_ajax_afzaliwp_save_fields', [ $this, 'save_fields' ] );
		add_action( 'wp_ajax_nopriv_afzaliwp_save_fields', [ $this, 'save_fields' ] );
	}

	public function save_fields() {

		$form_id = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0;
		if ( ! $form_id ) {
			wp_send_json_error( esc_html__( 'Form ID is missing.', 'afzaliwp-gs' ) );
		}

		$fields_mapping = isset( $_POST['fields'] ) && is_array( $_POST['fields'] ) ? $_POST['fields'] : [];
		// Sanitize and validate each field entry.
		foreach ( $fields_mapping as &$field ) {
			$field['id']      = isset( $field['id'] ) ? sanitize_text_field( $field['id'] ) : '';
			$field['enabled'] = ! empty( $field['enabled'] );
			$field['order']   = isset( $field['order'] ) ? absint( $field['order'] ) : 0;
		}
		unset( $field );

		$form = \GFAPI::get_form( $form_id );
		if ( is_wp_error( $form ) || empty( $form ) ) {
			wp_send_json_error( esc_html__( 'Unable to retrieve the form.', 'afzaliwp-gs' ) );
		}

		$form['fields_mapping'] = $fields_mapping;

		$update_result = \GFAPI::update_form( $form );
		if ( is_wp_error( $update_result ) ) {
			wp_send_json_error( $update_result->get_error_message() );
		}

		wp_send_json_success( [
			'message'        => esc_html__( 'Fields saved successfully.', 'afzaliwp-gs' ),
			'fields_mapping' => $fields_mapping,
		] );
	}
}