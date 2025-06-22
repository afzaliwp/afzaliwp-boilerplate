<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

defined( 'ABSPATH' ) || die();

class Connection_Test {

	private $url;

	public function __construct() {
		add_action( 'wp_ajax_afzaliwp_test_connection', [ $this, 'get_result' ] );
		add_action( 'wp_ajax_nopriv_afzaliwp_test_connection', [ $this, 'get_result' ] );
	}

	private function test( $url ) {
		if ( empty( $url ) ) {
			return [
				'success' => false,
				'message' => esc_html__( 'Please add your Google Apps Scripts url.', 'afzaliwp-gs' ),
			];
		}

		if ( ! $this->is_google_apps_script_url( $url ) ) {
			return [
				'success' => false,
				'message' => esc_html__( 'Only Google Apps Scripts url is allowed.', 'afzaliwp-gs' ),
			];
		}

		$response = wp_remote_get( $url, [ 'timeout' => 10 ] );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$code = wp_remote_retrieve_response_code( $response );

			if ( $code === 200 ) {
				$data = json_decode( $body );
				// Expecting the Google Apps Script to return a JSON with a "success" property
				if ( $data && $data->success ) {
					return [
						'success' => true,
						'message' => sprintf(
							__( 'Connection Succeed! Sheet: <strong>%s</strong>', 'afzaliwp-gs' ),
							$data->sheet
						),
					];
				} else {
					return [
						'success' => false,
						'message' => esc_html__( 'An unexpected error happened. Please try again', 'afzaliwp-gs' ),
					];
				}
			} else {
				return [
					'success' => false,
					'message' => sprintf(
						esc_html__( 'Something went wrong! Error code: %s', 'afzaliwp-gs' ),
						$code
					),
				];
			}
		} else {
			return [
				'success' => false,
				'message' => $response->get_error_message(),
			];
		}
	}

	public function get_result() {
		$url     = sanitize_url( $_POST[ 'url' ] );
		$form_id = isset( $_POST[ 'form_id' ] ) ? absint( $_POST[ 'form_id' ] ) : 0;

		if ( ! $form_id ) {
			wp_send_json_error( esc_html__( 'Form ID is missing.', 'afzaliwp-gs' ) );

			return;
		}

		$result = $this->test( $url );

		if ( $result[ 'success' ] ) {
			// Retrieve the form meta using Gravity Forms' API.
			$form = \GFAPI::get_form( $form_id );
			if ( is_wp_error( $form ) ) {
				wp_send_json_error( $form->get_error_message() );

				return;
			}

			// Save the provided Google Apps Script URL into the form meta.
			// The standard way to add custom settings is by adding a new key
			// to the form meta JSON stored in wp_gf_form_meta.
			$form[ 'gsheets_webhook' ] = $url;

			$update_result = \GFAPI::update_form( $form );
			if ( is_wp_error( $update_result ) ) {
				wp_send_json_error( $update_result->get_error_message() );

				return;
			}
			wp_send_json_success( $result[ 'message' ] );

			return;
		} else {
			wp_send_json_error( $result[ 'message' ] );
		}
	}

	private function is_google_apps_script_url( $url ) {
		$pattern = '/^https:\/\/script\.google\.com\/macros\/s\/([a-zA-Z0-9_-]+)\/exec\/?$/';

		return preg_match( $pattern, $url ) === 1;
	}

}
