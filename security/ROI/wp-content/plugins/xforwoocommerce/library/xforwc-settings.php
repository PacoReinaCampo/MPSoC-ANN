<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'X_for_WooCommerce_Settings' ) ) :

	final class X_for_WooCommerce_Settings {

		protected static $_instance = null;
		protected static $_s = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			do_action( 'xforwc_settings_loading' );

			self::$_s = apply_filters( 'xforwc_setup_dashboard', array(
				'logo' => XforWC()->plugin_url() . '/library/images/x-for-woocommerce-logo.png',
				'text' => esc_html__( 'Hi! Welcome back to XforWooCommerce Dashboard v', 'xforwoocommerce' ),
				'back' => esc_html__( 'Click the button to go back to XforWooCommerce Dashboard.', 'xforwoocommerce' ),
			) );

			$this->includes();

			do_action( 'xforwc_settings_loaded' );
		}

		public function includes() {
			add_action( 'admin_menu', array( $this, 'load_settings_page' ), 9999999999 );

			$page = isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'xforwoocommerce' ? true: false;

			if ( $page ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'load_script' ), 0 );
				add_action( 'admin_footer', array( $this, 'add_templates' ), 9999999999 );

				add_filter( 'xforwc_settings', array( $this, 'get_settings' ), 9999999999 );
			}

			add_action( 'wp_ajax_xforwc_ajax_factory', array( $this, 'ajax_factory' ), 9999999999 );
		}

		function load_script() {
			//wp_enqueue_style( 'xforwc-style', XforWC()->plugin_url() .'/library/css/xforwoocommerce' . ( is_rtl() ? '-rtl' : '' ) . '.css', false, '' );
			wp_enqueue_style( 'xforwc-style', XforWC()->plugin_url() .'/library/css/xforwoocommerce' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, '' );

			wp_register_script( 'xforwc-script', XforWC()->plugin_url() . '/library/js/xforwoocommerce.js', array( 'jquery', 'wp-util' ), '', true, 0 );
			wp_enqueue_script( 'xforwc-script' );

			wp_localize_script( 'xforwc-script', 'xforwc', apply_filters( 'xforwc_settings', array() ) );
			wp_localize_script( 'xforwc-script', 'svx', apply_filters( 'xforwc_svx_settings', array() ) );
		}

		function load_settings_page() {
			$page = 'XforWooCommerce';

			if ( class_exists( 'XforWC_Whitelabel' ) ) {
				$whitelabel = get_option( '_xforwc_whitelabel' );

				if ( !empty( $whitelabel['menu'] ) ) {
					$page = $whitelabel['menu'];
				}
			}

			add_submenu_page( 'woocommerce', $page, $page, 'manage_woocommerce', 'xforwoocommerce', array( $this, 'show_settings' ) );
		}

		function show_settings() {
?>
			<div id="xforwoocommerce-page" class="<?php echo apply_filters( 'xforwc_dashboard_class', 'xforwc-dashboard' ); ?>">
				<div id="xforwoocommerce-dashboard">
					<a href="https://xforwoocommerce.com/" target="_blank"><img id="xforwoocommerce-logo" src="<?php echo esc_url( self::$_s['logo'] ); ?>" /></a>
				</div>
				<div id="xforwoocommerce-nav">
					<span class="xforwc-button-primary xforwc-back">&larr; <?php esc_html_e( 'Back', 'xforwoocommerce' ); ?></span>
					<span class="xforwc-dashboard-text"><?php echo esc_html( self::$_s['text'] ); ?><?php echo XforWC()->version(); ?></span>
					<span class="xforwc-plugin-text"><?php echo esc_html( self::$_s['back'] ); ?></span>
<?php
					if ( SevenVX()->get_key() == 'true' ) {
?>
						<a href="javascript:void(0)" id="xforwc-license-details" class="xforwc-button-primary x-color"><?php esc_html_e( 'License details', 'xforwoocommerce' ); ?></a>
<?php
					}
?>
					<a href="https://xforwoocommerce.com/" class="xforwc-button-primary x-color" target="_blank"><?php esc_html_e( 'Visit XforWooCommerce.com', 'xforwoocommerce' ); ?></a>
					<a href="https://help.xforwoocommerce.com/" class="xforwc-button-primary red" target="_blank"><?php esc_html_e( 'Get help', 'xforwoocommerce' ); ?></a>
				</div>
				<div id="xforwoocommerce"></div>
			</div>
<?php
		}

		function get_settings( $settings ) {
			$options = get_option( '_xforwoocommerce', array() );

			$settings['ajax'] = esc_url( admin_url( 'admin-ajax.php' ) );
			$settings['key'] = SevenVX()->get_key();

			if ( !empty( $settings['plugins'] ) ) {
				foreach( $settings['plugins'] as $plugin => $data ) {
					$settings['plugins'][$plugin]['state'] = !isset( $options[$data['slug']] ) || $options[$data['slug']] == 'yes' ? 'yes' : 'no';
				} 
			}

			return $settings;
		}

		function add_templates() {
		?>
			<script type="text/template" id="tmpl-xforwc-plugin">
				<# if ( data.plugin.state && data.plugin.state == 'no' ) { #>
					<div id="xforwc-{{ data.plugin.slug }}" class="xforwc-plugin disabled">
						<img src="{{ data.plugin.image }}" />
						<h2>{{ data.plugin.xforwc }}</h2>
						<span class="xforwc-button xforwc-disable" data-plugin="{{ data.plugin.slug }}"><?php esc_html_e( 'Activate', 'xforwoocommerce' ); ?></span>

						<span class="xforwc-plugin-label">{{ data.plugin.name }} <span class="xforwc-plugin-version">{{ data.plugin.version }}</span></span>
					</div>
				<# } else { #>
					<div id="xforwc-{{ data.plugin.slug }}" class="xforwc-plugin">
						<img src="{{ data.plugin.image }}" />
						<h2>{{ data.plugin.xforwc }}</h2>
						<span class="xforwc-button-primary xforwc-configure" data-plugin="{{ data.plugin.slug }}"><?php esc_html_e( 'Dashboard', 'xforwoocommerce' ); ?></span>
						<span class="xforwc-plugin-label">{{ data.plugin.name }} <span class="xforwc-plugin-version">{{ data.plugin.version }}</span><br/><a href="javascript:void(0)" class="xforwc-disable activate" data-plugin="{{ data.plugin.slug }}"><?php esc_html_e( 'Deactivate module', 'xforwoocommerce' ); ?></a></span>
					</div>
				<# } #>
			</script>
		<?php
		}

		public function ajax_factory() {

			$opt = array(
				'success' => true
			);

			if ( !isset( $_POST['xforwc']['type'] ) ) {
				$this->ajax_die($opt);
			}

			if ( apply_filters( 'xforwc_can_you_save', false ) ) {
				$this->ajax_die($opt);
			}

			switch( $_POST['xforwc']['type'] ) {

				case 'get_svx' :
					wp_send_json( $this->_get_svx() );
					exit;
				break;

				case 'plugin_switch' :
					wp_send_json( $this->_plugin_switch() );
					exit;
				break;

				default :
					$this->ajax_die($opt);
				break;

			}

		}

		public function _plugin_switch() {
			$plugin = isset( $_POST['xforwc']['plugin'] ) ? $_POST['xforwc']['plugin'] : '';
			$state = isset( $_POST['xforwc']['state'] ) ? $_POST['xforwc']['state'] : '';

			if ( empty( $plugin ) || empty( $state ) ) {
				return false;
			}

			$options = get_option( '_xforwoocommerce', array() );

			$options[$plugin] = $state;

			update_option( '_xforwoocommerce', $options, true );

			return true;
		}

		public function _get_svx() {
			$plugin = isset( $_POST['xforwc']['plugin'] ) ? $_POST['xforwc']['plugin'] : '';
			
			if ( empty( $plugin ) ) {
				return array();
			}

			$plugin = str_replace( '-', '_', sanitize_title( $plugin ) );

			$settings = apply_filters( 'xforwc_svx_get_' . $plugin, array() );

			if ( !is_array( $settings ) ) {
				return array();
			}

			return $settings;
		}

		public function ajax_die($opt) {
			$opt['success'] = false;
			wp_send_json( $opt );
			exit;
		}

	}

	X_for_WooCommerce_Settings::instance();

endif;
