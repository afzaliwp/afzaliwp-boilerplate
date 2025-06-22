<?php
/**
 * Plugin Name:       Gravity Forms Integration With Google Sheet
 * Plugin URI:        https://afzaliwp.com
 * Description:       A plugin to sync the gravity forms entries to the Google sheet easily.
 * Version:           0.0.1
 * Author:            Mohammad Afzali
 * Author URI:        https://afzaliwp.com
 * Text Domain:       afzaliwp-gs
 * Languages:         /languages
 */

namespace AfzaliWP;

use AfzaliWP\GS_Gravity\Includes\Backend\Connection_Test;
use AfzaliWP\GS_Gravity\Includes\Backend\Options;
use Exception;

defined( 'ABSPATH' ) || die();

require 'functions.php';

final class GS_Gravity {

	private static $instances = [];

	protected function __construct() {
		spl_autoload_register( 'afzaliwp_gs_gravity_autoload' );
		load_plugin_textdomain(
			'afzaliwp-gs',
			false,
			basename( dirname( __FILE__ ) ) . '/languages'
		);

		$this->define_constants();

		if ( is_admin() ) {
			$this->handle_backend();
		} else {
			$this->handle_frontend();
		}

		add_action( 'admin_enqueue_scripts', [
			$this,
			'register_admin_styles_and_scripts',
		] );
	}

	protected function __clone() {
	}

	/**
	 * @throws Exception
	 */
	public function __wakeup() {
		throw new Exception( "Cannot unserialize a singleton." );
	}

	public static function get_instance() {
		$cls = GS_Gravity::class;

		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new GS_Gravity();
		}

		return self::$instances[ $cls ];
	}

	public function activation() {

	}

	public function deactivation() {
	}

	public function register_admin_styles_and_scripts() {
		if ( ! (
			isset( $_GET[ 'page' ] ) &&
			'gf_edit_forms' === $_GET[ 'page' ]
		) ) {
			return;
		}
		$version = AFZALIWP_GS_ASSETS_VERSION;
		if ( str_contains( get_bloginfo( 'wpurl' ), 'local' ) ) {
			$version = time();
		}

		wp_enqueue_style(
			'afzaliwp-gs-style-' . AFZALIWP_GS_ASSETS_VERSION,
			AFZALIWP_GS_ASSETS_URL . 'admin.min.css',
			'',
			$version,
			'all'
		);

		wp_enqueue_script(
			'afzaliwp-gs-script-' . AFZALIWP_GS_ASSETS_VERSION,
			AFZALIWP_GS_ASSETS_URL . 'admin.min.js',
			[ 'jquery' ],
			$version,
			''
		);

		wp_localize_script(
			'afzaliwp-gs-script-' . AFZALIWP_GS_ASSETS_VERSION,
			'AfzGsObj',
			[
				'homeUrl' => get_bloginfo( 'url' ),
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'afzaliwp-gs-nonce' ),
			]
		);
	}

	public function define_constants() {
		define( 'AFZALIWP_GS_DEVELOPMENT', true );
		define( 'AFZALIWP_GS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'AFZALIWP_GS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'AFZALIWP_GS_TPL_DIR', trailingslashit( AFZALIWP_GS_DIR . 'templates' ) );
		define( 'AFZALIWP_GS_WC_TPL_DIR', trailingslashit( AFZALIWP_GS_DIR . 'woocommerce' ) );
		define( 'AFZALIWP_GS_INC_DIR', trailingslashit( AFZALIWP_GS_DIR . 'includes' ) );
		define( 'AFZALIWP_GS_ASSETS_URL', trailingslashit( AFZALIWP_GS_URL . 'assets/dist' ) );
		define( 'AFZALIWP_GS_IMAGES', trailingslashit( AFZALIWP_GS_URL . 'assets/images' ) );
		define( 'AFZALIWP_GS_JSON', trailingslashit( AFZALIWP_GS_ASSETS_URL . 'json' ) );
		define( 'AFZALIWP_GS_LANGUAGES', basename( dirname( __FILE__ ) ) . '/languages' );
		define( 'AFZALIWP_GS_ASSETS_VERSION', '0.0.1' );
	}

	private function handle_backend() {
		new Options();
		new Connection_Test();
	}

	private function handle_frontend() {
	}
}

GS_Gravity::get_instance();
