<?php
/*
Plugin Name: XforWooCommerce
Plugin URI: https://xforwoocommerce.com
Description: XforWooCommerce Themes and Plugins! Visit https://xforwoocommerce.com
Author: XforWooCommerce
License: Codecanyon Split Licence
Version: 1.4.1
Requires at least: 4.5
Tested up to: 5.5.9
WC requires at least: 3.0.0
WC tested up to: 4.1.9
Author URI: https://xforwoocommerce.com
Text Domain: xforwoocommerce
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'svx-settings/load.php' );
if ( xforwccb() ) {	return false; }

$GLOBALS['svx'] = isset( $GLOBALS['svx'] ) && version_compare( $GLOBALS['svx'], '1.4.2') == 1 ? $GLOBALS['svx'] : '1.4.2';

if ( !class_exists( 'X_for_WooCommerce' ) ) :

	final class X_for_WooCommerce {

		public static $version = '1.4.1';

		protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			if ( is_admin() ) {
				register_activation_hook( __FILE__, array( $this, 'activate' ) );
			}

			if ( $this->check_woocommerce() === false ) {
				return false;
			}

			do_action( 'xforwc_loading' );

			$this->init_hooks();

			$this->includes();

			do_action( 'xforwc_loaded' );
		}

		private function init_hooks() {
			add_action( 'init', array( $this, 'textdomain' ), 0 );

			include_once( 'svx-settings/svx-get.php' );
			add_action( 'init', array( $this, 'load_svx' ), 100 );
		}

		private function check_woocommerce() {
			if ( class_exists( 'WooCommerce' ) ) {
				return true;
			}
			else {
				return false;
			}
		}

		public function activate() {
			if ( !class_exists('WooCommerce') ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );

				wp_die( esc_html__( 'This plugin requires WooCommerce. Download it from WooCommerce official website', 'xforwoocommerce' ) . ' &rarr; https://woocommerce.com' );
				exit;
			}
		}

		public static function load_demo() {
			include_once( 'svx-settings/svx-settings.php' );
		}

		public function load_svx() {
			if ( $this->is_request( 'admin' ) ) {
				include_once( 'svx-settings/svx-settings.php' );
			}
		}

		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		public function includes() {
	
			$isAdmin = false;
			$page = isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'xforwoocommerce' ? true: false;

			if ( $page && is_admin() ) {
				$isAdmin = true;
			}
	
			$options = get_option( '_xforwoocommerce', array() );

			include_once( 'x-pack/load.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'library/xforwc-settings.php' );
			}

		}

		public function textdomain() {
			$this->load_plugin_textdomain();
		}

		public function load_plugin_textdomain() {

			$domain = 'xforwoocommerce';
			$dir = untrailingslashit( WP_LANG_DIR );
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			if ( $loaded = load_textdomain( $domain, $dir . '/plugins/' . $domain . '-' . $locale . '.mo' ) ) {
				return $loaded;
			}
			else {
				load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
			}

		}

		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		public function plugin_basename() {
			return untrailingslashit( plugin_basename( __FILE__ ) );
		}

		public function ajax_url() {
			return admin_url( 'admin-ajax.php', 'relative' );
		}

		public function version() {
			return self::$version;
		}

	}

	function XforWC() {
		return X_for_WooCommerce::instance();
	}

	X_for_WooCommerce::instance();

endif;
