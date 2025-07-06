<?php

namespace AfzaliWP\GS_Gravity\Includes\Backend;

defined( 'ABSPATH' ) || die();

class Integration_Guide {

	private $gs_code_file = '';

	public function __construct() {
		$this->gs_code_file = AFZALIWP_GS_DIR . 'gs-obfuscated.js';
	}

	public function get_gs_code() {
		return is_file( $this->gs_code_file ) ? file_get_contents( $this->gs_code_file ) : '';
	}

}