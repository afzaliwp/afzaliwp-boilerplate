<?php
function afzaliwp_boilerplate_autoload( $class_name ) {
	if ( ! str_contains( $class_name, 'Afzaliwp\Boiler_Plate' ) ) {
		return;
	}

	$file = str_replace( [ '_', 'Afzaliwp\Boiler_Plate', '\\' ], [ '-', __DIR__, '/' ], $class_name ) . '.php';

	require_once strtolower( $file );
}