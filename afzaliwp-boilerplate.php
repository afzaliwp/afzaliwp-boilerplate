<?php
/**
 * Plugin Name:       AfzaliWP BoilerPlate
 * Plugin URI:        https://afzaliwp.com
 * Description:       It's a boilerplate for plugin development.
 * Version:           0.1.0
 * Author:            Mohammad Afzali
 * Author URI:        https://afzaliwp.com
 */

namespace AfzaliWP;

use Exception;
use Afzaliwp\Boiler_Plate\Includes\Control_Panel\Control_Panel;

defined( 'ABSPATH' ) || die();

require_once 'functions.php';

final class Boiler_Plate {

	private static $instances = [];

	protected function __construct() {
		spl_autoload_register( 'afzaliwp_boilerplate_autoload' );

		$this->define_constants();
		add_action( 'admin_menu', [ $this, 'admin_menus' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles_and_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_styles_and_scripts' ] );

		/**
		 * TODO: Other stuff like method calls or hooks goes here.
		 */
		// $this->render_page();
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
		$cls = Boiler_Plate::class;

		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new Boiler_Plate();
		}

		return self::$instances[ $cls ];
	}

	public function activation() {
		/**
		 * TODO: Cron jobs can be setup here like the commented example.
		 */
		// if ( ! wp_next_scheduled( 'afzaliwp_bp_schedule_event' ) ) {
		// 	wp_schedule_event( strtotime( date( 'Y-m-d 01:00:00' ) ), 'daily', 'afzaliwp_bp_schedule_event' );
		// }

		/**
		 * TODO: Make sure changing text domain to what is appropriate for your plugin.
		 */
		load_plugin_textdomain(
			'afzaliwp-bp',
			false,
			AFZALIWP_BP_LANGUAGES
		);
	}

	public function deactivation() {
		/**
		 * TODO: Make sure you clear the scheduled events when plugin is deactivated.
		 */
		// wp_clear_scheduled_hook( 'afzaliwp_bp_schedule_event' );
	}

	public function admin_menus() {
		/**
		 * TODO: You can add admin menus and option pages here.
		 * Hint: Call this method in constructor.
		 */
		add_options_page(
			esc_html__( 'Admin menu Title', 'afzaliwp-bp' ),
			esc_html__( 'Admin menu Title', 'afzaliwp-bp' ),
			'manage_options',
			'afzaliwp_bp',
			[ Control_Panel::class, 'render_template' ]
		);
	}

	public function register_styles_and_scripts() {
		wp_enqueue_style(
			'afzaliwp-bp-style',
			AFZALIWP_BP_ASSETS_URL . 'frontend.min.css',
			'',
			AFZALIWP_BP_ASSETS_VERSION
		);

		wp_enqueue_script(
			'afzaliwp-bp-script',
			AFZALIWP_BP_ASSETS_URL . 'frontend.min.js',
			AFZALIWP_BP_ASSETS_VERSION,
			null,
			true
		);

		wp_localize_script(
			'afzaliwp-bp-script',
			'afzaliwpBpAJAX',
			[
				'homeUrl' => get_bloginfo( 'url' ),
				// 'checkoutUrl' => wc_get_checkout_url(), //If WooCommerce is included in your works and need to use checkout url in ajax.
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'afzaliwp-bp-nonce' ),
			]
		);
	}

	public function register_admin_styles_and_scripts() {
		wp_enqueue_style(
			'afzaliwp-bp-admin-style',
			AFZALIWP_BP_ASSETS_URL . 'admin.min.css',
			'',
			AFZALIWP_BP_ASSETS_VERSION
		);

		wp_enqueue_style(
			'afzaliwp-bp-control-panel-style',
			AFZALIWP_BP_URL . 'includes/control-panel/dist/control-panel.css',
			'',
			AFZALIWP_BP_ASSETS_VERSION
		);

		wp_enqueue_script(
			'afzaliwp-bp-admin-script',
			AFZALIWP_BP_ASSETS_URL . 'admin.min.js',
			[],
			AFZALIWP_BP_ASSETS_VERSION,
			true
		);

		wp_enqueue_script(
			'afzaliwp-bp-control-panel-script',
			AFZALIWP_BP_URL . 'includes/control-panel/dist/control-panel.js',
			[],
			AFZALIWP_BP_ASSETS_VERSION,
			true
		);
	}

	public function woocommerce_related() {
		/**
		 * TODO: If WooCommerce is icluded in you works, you can create the classes related to WC here.
		 * Hint:
		 *        - Just create an instance of the class like new WC_Handler(); and add your needed functionality to the costructor of the class.
		 *        - Call this method in the constructor.
		 */
	}

	public function define_constants() {
		define( 'AFZALIWP_BP_DEVELOPMENT', true );
		define( 'AFZALIWP_BP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'AFZALIWP_BP_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'AFZALIWP_BP_TPL_DIR', trailingslashit( AFZALIWP_BP_DIR . 'templates' ) );
		define( 'AFZALIWP_BP_WC_TPL_DIR', trailingslashit( AFZALIWP_BP_DIR . 'woocommerce' ) );
		define( 'AFZALIWP_BP_INC_DIR', trailingslashit( AFZALIWP_BP_DIR . 'includes' ) );
		define( 'AFZALIWP_BP_LANGUAGES', trailingslashit( AFZALIWP_BP_DIR . 'languages' ) );
		define( 'AFZALIWP_BP_ASSETS_URL', trailingslashit( AFZALIWP_BP_URL . 'assets/dist' ) );
		define( 'AFZALIWP_BP_IMAGES', trailingslashit( AFZALIWP_BP_URL . 'assets/images' ) );
		define( 'AFZALIWP_BP_JSON', trailingslashit( AFZALIWP_BP_ASSETS_URL . 'json' ) );

		if ( str_contains( get_bloginfo( 'wpurl' ), 'localhost' ) ) {
			define( 'AFZALIWP_BP_IS_LOCAL', true );
			define( 'AFZALIWP_BP_ASSETS_VERSION', time() );
		} else {
			define( 'AFZALIWP_BP_IS_LOCAL', false );
			define( 'AFZALIWP_BP_ASSETS_VERSION', '4.1.10' );
		}
	}
}

Boiler_Plate::get_instance();
