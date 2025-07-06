<?php

namespace AfzaliWP\GS_Gravity\Includes;

use GFAPI;

defined( 'ABSPATH' ) || exit;

class GForm {
	const META_SENT = 'gsheets_sent';

	public function __construct() {
		add_action( 'gform_loaded', function () {
			add_action( 'gform_after_submission', [ $this, 'maybe_send_data' ], 10, 2 );
			add_action( 'gform_post_payment_completed', [ $this, 'maybe_send_data' ], 10, 2 );
			add_action( 'all', [ $this, 'catch_post_update_entry' ], 10, 3 );
		} );
	}

	protected function can_send( array $entry, array $form ) {
		if ( empty( $form[ 'gsheets_webhook' ] ) ) {
			return false;
		}

//		if ( rgar( $form, 'gsheets_require_payment' ) && ! $this->is_payment_complete( $entry ) ) {
//			return false;
//		}

		return true;
	}

	/**
	 * Maybe send (and prevent duplicates).
	 */
	public function maybe_send_data( array $entry, array $form ) {
		mylog($this->can_send( $entry, $form ), '$this->can_send( $entry, $form )');
		if ( ! $this->can_send( $entry, $form ) ) {
			return;
		}

		if ( $this->has_been_sent( $entry[ 'id' ] ) ) {
			return;
		}

		$this->send_to_sheets( $entry, $form );
	}

	/**
	 * Catch GF post‐update hooks (e.g. payment update).
	 */
	public function catch_post_update_entry( $hook_name ) {
		if ( strpos( $hook_name, 'gform_post_update_entry_' ) !== 0 ) {
			return;
		}

		$args = func_get_args();
		if ( count( $args ) < 3 || ! is_array( $args[ 1 ] ) ) {
			return;
		}

		$updated = $args[ 1 ];
		$form    = GFAPI::get_form( $updated[ 'form_id' ] );
		$this->maybe_send_data( $updated, $form );
	}

	/**
	 * Build & POST payload to Sheets.
	 */
	protected function send_to_sheets( array $entry, array $form ) {
		$webhook  = rgar( $form, 'gsheets_webhook' );
		$mapped   = rgar( $form, 'fields_mapping', [] );
		$combined = [];

		foreach ( $mapped as $item ) {
			if ( empty( $item[ 'enabled' ] ) ) {
				continue;
			}

			$order = intval( $item[ 'order' ] ?? 999 );
			$key   = $this->unique_key( $combined, $order );

			$raw = trim( $item[ 'value' ] ?? '' );

			if ( $raw !== '' ) {
				if ( $this->is_dynamic_token( $raw ) ) {
					$value = $this->resolve_dynamic_token( $raw, $entry );
				} else {
					$value = $raw;
				}
			} else {
				$value = rgar( $entry, $item[ 'id' ] );
			}

			$header = ! empty( $item[ 'label' ] )
				? $item[ 'label' ]
				: $this->get_label( $form, $item[ 'id' ] );

			$combined[ $key ] = [
				'header' => $header,
				'value'  => $value,
			];
		}

		uksort( $combined, function ( $a, $b ) {
			return $this->extract_order( $a ) <=> $this->extract_order( $b );
		} );

		$payload = [];
		foreach ( $combined as $key => $col ) {
			$payload[ $key ]            = $col[ 'value' ];
			$payload[ 'label_' . $key ] = $col[ 'header' ];
		}

		mylog($payload);

		$response = wp_remote_post( $webhook, [
			'body'      => wp_json_encode( $payload ),
			'headers'   => [ 'Content-Type' => 'application/json' ],
			'timeout'   => 15,
			'sslverify' => false,
		] );

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$json = json_decode( $body, true );

		$api_success = is_array($json) && ! empty( $json['success'] );

		$status = $api_success
			? "OK (HTTP {$code})"
			: "ERROR (HTTP {$code})";

		$message = is_array($json) && isset($json['message'])
			? $json['message']
			: wp_strip_all_tags( $body );

		if ( $api_success ) {
			$this->mark_as_sent( $entry['id'] );
		}

		\GFFormsModel::add_note(
			$entry['id'],
			0,
			'Google Sheets Sync',
			sprintf( 'Sheets Sync %s: %s', $status, $message )
		);


	}

	/**
	 * Simple check: does this string look exactly like "{something}"?
	 */
	protected function is_dynamic_token( string $str ): bool {
		return preg_match( '/^\{[a-z0-9_]+\}$/i', trim( $str ) ) === 1;
	}

	/**
	 * Unique key builder for duplicate orders.
	 */
	protected function unique_key( array &$all, int $order ): string {
		$base   = 'field_' . $order;
		$key    = $base;
		$suffix = 1;
		while ( isset( $all[ $key ] ) ) {
			$key = "{$base}_{$suffix}";
			$suffix ++;
		}

		return $key;
	}

	/**
	 * Extract numeric order from a "field_<n>..." key.
	 */
	protected function extract_order( string $key ): int {
		$parts = explode( '_', str_replace( 'field_', '', $key ) );

		return intval( $parts[ 0 ] );
	}

	/**
	 * Lookup GF field label by ID.
	 */
	protected function get_label( array $form, $field_id ): string {
		foreach ( $form[ 'fields' ] as $f ) {
			if ( $f->id == $field_id ) {
				return $f->label;
			}
		}

		return '';
	}

	/**
	 * Has this entry already been sent?
	 */
	protected function has_been_sent( int $entry_id ): bool {
		return (bool) gform_get_meta( $entry_id, self::META_SENT );
	}

	/**
	 * Mark entry as sent.
	 */
	protected function mark_as_sent( int $entry_id ): void {
		gform_update_meta( $entry_id, self::META_SENT, '1' );
	}

	/**
	 * Is payment complete?
	 */
	protected function is_payment_complete( array $entry ): bool {
		$status = strtolower( rgar( $entry, 'payment_status' ) );
		$paid   = [ 'paid', 'approved', 'completed', 'پرداخت شد', 'موفق' ];

		return in_array( $status, $paid, true );
	}

	/**
	 * Resolve a dynamic {token}.
	 */
	protected function resolve_dynamic_token( string $token, array $entry ) {
		switch ( $token ) {
			case '{current_date}':
				return date( 'Y-m-d' );
			case '{current_time}':
				return date( 'H:i:s' );
			case '{created_by}':
				return rgar( $entry, 'created_by' );
			case '{entry_id}':
				return rgar( $entry, 'id' );
			case '{date_created}':
				return rgar( $entry, 'date_created' );
			case '{date_updated}':
				return rgar( $entry, 'date_updated' );
			case '{source_url}':
				return rgar( $entry, 'source_url' );
			case '{transaction_id}':
				return rgar( $entry, 'transaction_id' );
			case '{payment_amount}':
				return rgar( $entry, 'payment_amount' );
			case '{payment_date}':
				return rgar( $entry, 'payment_date' );
			case '{payment_status}':
				return rgar( $entry, 'payment_status' );
			case '{post_id}':
				return rgar( $entry, 'post_id' );
			case '{user_agent}':
				return rgar( $entry, 'user_agent' );
			case '{ip}':
				return rgar( $entry, 'ip' );
			case '{payment_date_only}':
				$pd = rgar( $entry, 'payment_date' );

				return $pd ? date( 'Y-m-d', strtotime( $pd ) ) : '';
			case '{payment_time_only}':
				$pd = rgar( $entry, 'payment_date' );

				return $pd ? date( 'H:i:s', strtotime( $pd ) ) : '';
			case '{date_created_only}':
				$dc = rgar( $entry, 'date_created' );

				return $dc ? date( 'Y-m-d', strtotime( $dc ) ) : '';
			case '{time_created_only}':
				$dc = rgar( $entry, 'date_created' );

				return $dc ? date( 'H:i:s', strtotime( $dc ) ) : '';
			default:
				return $token;
		}
	}

	/**
	 * Safe date formatter.
	 */
	protected function format_date( $str, $format ) {
		if ( ! $str ) {
			return '';
		}
		$ts = strtotime( $str );

		return $ts ? date( $format, $ts ) : '';
	}
}
