<?php
function afzaliwp_boilerplate_autoload( $class_name ) {
	if ( ! str_contains( $class_name, 'Afzaliwp\Boiler_Plate' ) ) {
		return;
	}

	$file = str_replace( [ 'Afzaliwp\Boiler_Plate', '_', '\\' ], [ __DIR__, '-', '/' ], $class_name ) . '.php';

	require_once strtolower( $file );
}