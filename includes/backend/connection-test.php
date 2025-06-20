<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

defined( 'ABSPATH' ) || die();

class Connection_Test {

	private $url;

	public function __construct( $url ) {
		$this->url = $url;
	}

	private function test() {
		$response = wp_remote_get( 'https://example.com' );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$code = wp_remote_retrieve_response_code( $response );

			if ( $code === 200 ) {
				return [
					'success' => true,
				];
			} else {
				return [
					'success' => false,
					'message' => sprintf(
						esc_html__(
							'Something went wrong! Error code: %s', 'afzaliwp-gs'
						),
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

	public function render() {
		$result = $this->test();

		if ( $result[ 'success' ] ) {
			return $this->success_alert();
		}

		return $this->failed_alert( $result[ 'message' ] );
	}

	private function success_alert() {
		return '';
	}

	private function failed_alert( $message ) {
		return '';
	}
}