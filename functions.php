<?php
function afzaliwp_gs_gravity_autoload( $class_name ) {
	if ( ! str_contains( $class_name, 'AfzaliWP\GS_Gravity' ) ) {
		return;
	}

		$file = str_replace(
		        [
			        '_',
			        strtolower( 'AfzaliWP\GS-Gravity' ),
			        '\\',
		        ],
		        [
			        '-',
			        __DIR__,
			        DIRECTORY_SEPARATOR,
		        ],
		        strtolower( $class_name ) ) . '.php';

	require_once $file;
}

if ( ! function_exists( 'mylog' ) ) {
	function mylog( $data, $other_data = '' ) {
		error_log( PHP_EOL . '-----------------------------------' );
		error_log( '-------------$data: .' . $other_data . '---------------' );
		if ( is_array( $data ) || is_object( $data ) ) {
			error_log( print_r( $data, true ) );
		} else {
			error_log( $data );
		}
		error_log( '-----------------------------------' );
		error_log( '-----------------------------------' . PHP_EOL );
	}
}
