<?php

namespace AfzaliWP\GS_Gravity\Includes;

defined( 'ABSPATH' ) || die();

class Helper {
	public static function get_assets_images_url( $name ) {
		return AFZALIWP_GS_IMAGES . $name;
	}
}