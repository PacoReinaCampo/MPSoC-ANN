<?php
/*
Plugin Name: Warranties and Returns for WooCommerce
Plugin URI: https://xforwoocommerce.com
Description: XforWooCommerce Themes and Plugins! Visit https://xforwoocommerce.com
Author: XforWooCommerce
License: Codecanyon Split Licence
Version: 5.1.1
Requires at least: 4.5
Tested up to: 5.5.9
WC requires at least: 3.0.0
WC tested up to: 4.1.9
Author URI: https://xforwoocommerce.com
Text Domain: woocommerce-warranties-and-returns
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !function_exists( 'XforWC' ) ) {
	require_once( 'includes/svx-settings/load.php' );

	if ( xforwccb() ) {	return false; }
}

$GLOBALS['svx'] = isset( $GLOBALS['svx'] ) && version_compare( $GLOBALS['svx'], '1.4.2') == 1 ? $GLOBALS['svx'] : '1.4.2';

if ( !class_exists( 'XforWC_Warranties_Returns' ) ) :

	class XforWC_Warranties_Returns {

		public static $version = '5.1.1';

		public static $dir;
		public static $path;
		public static $url_path;
		public static $settings;

		public static $scData;

		function __construct() {

			self::$dir = trailingslashit( dirname( __FILE__ ) );
			self::$path = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$url_path = trailingslashit( plugins_url( '/', __FILE__ ) );

			add_action( 'init', array( $this, 'init_plugin' ) );

			if ( is_admin() ) {
				register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
				register_activation_hook( __FILE__, array( $this, 'activate' ) );

				add_action( 'woocommerce_product_data_tabs', array( $this, 'wc_add_product_tab' ), 999 , 1 );
				add_action( 'woocommerce_product_data_panels', array( $this, 'wc_product_tab' ) );

				add_action( 'wcwar_warranty_pre_add_form_fields', array( &$this, 'war_add_presets' ), 10, 2 );
				add_action( 'wcwar_warranty_pre_edit_form_fields', array( &$this, 'war_edit_presets' ), 10, 2 );
				//add_filter( 'manage_edit-wcwar_warranty_pre_columns', array( &$this, 'war_preset_columns' ) );

				add_action( 'created_term', array( &$this, 'war_save_preset' ), 10, 3 );
				add_action( 'edit_term', array( &$this, 'war_save_preset' ), 10, 3 );
				add_action( 'admin_enqueue_scripts', array( &$this, 'war_admin_scripts' ), 9 );

				add_action( 'add_meta_boxes', array( &$this, 'war_register_request_metabox' ) );
				add_action( 'save_post', array( &$this, 'war_save_request_metabox' ), 10, 2 );
				add_action( 'save_post', array( &$this, 'wc_product_save' ), 10, 3 );

				add_filter( 'woocommerce_hidden_order_itemmeta', array( &$this, 'war_hide_core_fileds' ), 10, 1 );
				add_action( 'woocommerce_admin_order_item_headers', array( &$this, 'war_items_warranty_column_header' ) );
				add_action( 'woocommerce_admin_order_item_values', array( &$this, 'war_items_warranty_column' ), 10, 3);

				add_filter( 'manage_edit-wcwar_warranty_req_columns', array( &$this, 'war_request_warranty_column_header' ) );
				add_action( 'manage_wcwar_warranty_req_posts_custom_column' , array( &$this, 'war_request_warranty_column' ), 10, 2 );

				add_action( 'pre_get_posts', array( &$this, 'hlp_request_order' ), 10, 1 );
				
				add_filter( 'woocommerce_screen_ids', array( &$this, 'wc_add_screen_ids' ), 10, 1 );

				add_filter( 'woocommerce_admin_order_data_after_order_details', array( &$this, 'wc_add_order_request_status' ), 10, 1 );

				add_action( 'wp_ajax_wcwar_ajax_factory', array( &$this, '_ajax_factory' ) );

				add_action( 'admin_menu', array( &$this, 'war_pending_requests' ), 999 );

				add_action( 'admin_head', array( &$this, 'war_add_menu_icon_styles' ) );

				if ( SevenVXGet()->get_option( 'wcwar_email_disable', 'warranties_and_returns', 'no' ) == 'no' ) {
					add_action( 'woocommerce_email_after_order_table', array( &$this, 'war_email' ), 10, 3 );
				}

			}

			add_action( 'wp_enqueue_scripts', array( &$this, 'war_scripts' ) );

			add_filter( 'woocommerce_add_cart_item_data', array( &$this, 'wc_add_pa_warranty' ), 10, 3 );
			add_filter( 'woocommerce_add_cart_item', array( &$this, 'war_add_product_warranty' ), 10, 3 );

			add_filter('woocommerce_get_cart_item_from_session', array( &$this, 'wc_get_cart_item_from_session' ), 10, 3 );
			add_action( 'woocommerce_new_order_item', array( &$this, 'war_add_warranty_meta' ), 10, 3 );

			add_filter( 'woocommerce_cart_item_price', array( &$this, 'war_cart_price' ), 10, 3 ) ;
			add_action( 'woocommerce_after_cart_contents', array( &$this, 'war_cart_help' ) );

			add_filter( 'woocommerce_order_details_after_order_table', array( &$this, 'war_order' ), 999, 1 ) ;

			add_shortcode( 'wcwar_request', array( &$this, 'wcwar_sc_request' ) );

			add_filter( 'single_template', array( &$this, 'scr_view_request' ) );
			add_filter( 'comments_template', array( &$this, 'scr_comments' ) );

			$action = SevenVXGet()->get_option_autoload( 'wcwar_single_action', '' );

			if ( $action == '' ) {
				$action = 'woocommerce_after_add_to_cart_button';
			}

			if ( strpos( $action, ':' ) > 0 ) {
				$explode = explode( ':', $action );
				$curr_action = array(
					'action' => $explode[0],
					'priority' => intval( $explode[1] ) > -1 ? intval( $explode[1] ) : 10
				);
			}
			else {
				$curr_action = array(
					'action' => $action,
					'priority' => 10
				);
			}

			add_action( $curr_action['action'], array( &$this, 'war_product_warranty_output' ), $curr_action['priority'] );

			add_filter( 'mnthemes_add_meta_information_used', array( &$this, 'war_info' ) );

		}

		public function plugins_url( $val ) {
			return trailingslashit( plugins_url( '/', __FILE__ ) );
		}

		function war_info( $val ) {
			$val = array_merge( $val, array( 'Warranties and Returns for WooCommerce' ) );
			return $val;
		}


		function war_install() {

			if ( !SevenVXGet()->get_option_autoload( 'war_settings_page' ) ) {
				$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id', '' );

				$curr_page = array(
					'post_title' => esc_html__( 'Request Warranty', 'xforwoocommerce' ),
					'post_content' => '[wcwar_request]',
					'post_status' => 'publish',
					'post_type' => 'page',
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_category' => array( 1 ),
					'post_parent' => $myaccount_page_id
				);

				$curr_created = wp_insert_post( $curr_page );

				update_option( 'war_settings_page', $curr_created );

			}
		}

		function activate() {
			if ( !class_exists( 'WooCommerce' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );

				wp_die( esc_html__( 'This plugin requires WooCommerce. Download it from WooCommerce official website', 'xforwoocommerce' ) . ' &rarr; https://woocommerce.com' );
				exit;
			}

			flush_rewrite_rules();
		}

		function init_plugin() {

			add_action( 'woocommerce_product_after_variable_attributes', array( &$this, 'wc_add_variable_tab_24' ), 10, 3 );
			add_action( 'woocommerce_save_product_variation', array( &$this, 'wc_product_save_24' ), 10, 2 );

			$curr_args = array(
				'hierarchical'          => false,
				'update_count_callback' => '_update_post_term_count',
				'labels' => array(
					'name'              => esc_html__( 'Warranty Presets', 'xforwoocommerce' ),
					'singular_name'     => esc_html__( 'Warranty Preset', 'xforwoocommerce' ),
					'search_items'      => esc_html__( 'Search Warranty Presets', 'xforwoocommerce' ),
					'all_items'         => esc_html__( 'All Warranty Presets', 'xforwoocommerce' ),
					'parent_item'       => esc_html__( 'Parent Warranty Preset', 'xforwoocommerce' ),
					'parent_item_colon' => esc_html__( 'Parent Warranty Preset:', 'xforwoocommerce' ),
					'edit_item'         => esc_html__( 'Edit Warranty Preset', 'xforwoocommerce' ),
					'update_item'       => esc_html__( 'Update Warranty Preset', 'xforwoocommerce' ),
					'add_new_item'      => esc_html__( 'Add New Warranty Preset', 'xforwoocommerce' ),
					'new_item_name'     => esc_html__( 'New Warranty Name Preset', 'xforwoocommerce' )
				),
				'show_ui'               => true,
				'show_in_nav_menus'     => true,
				'query_var'             => true,
				'rewrite'               => false,
			);

			register_taxonomy( 'wcwar_warranty_pre', array( 'product' ), $curr_args );

			$curr_args = array(
				'hierarchical'          => false,
				'update_count_callback' => '_update_post_term_count',
				'labels' => array(
					'name'              => esc_html__( 'Warranty Status', 'xforwoocommerce' ),
					'singular_name'     => esc_html__( 'Warranty Status', 'xforwoocommerce' ),
					'search_items'      => esc_html__( 'Search Warranty', 'xforwoocommerce' ),
					'all_items'         => esc_html__( 'All Warranty', 'xforwoocommerce' ),
					'parent_item'       => esc_html__( 'Parent Warranty', 'xforwoocommerce' ),
					'parent_item_colon' => esc_html__( 'Parent Warranty:', 'xforwoocommerce' ),
					'edit_item'         => esc_html__( 'Edit Warranty', 'xforwoocommerce' ),
					'update_item'       => esc_html__( 'Update Warranty', 'xforwoocommerce' ),
					'add_new_item'      => esc_html__( 'Add New Warranty', 'xforwoocommerce' ),
					'new_item_name'     => esc_html__( 'New Warranty Name', 'xforwoocommerce' )
				),
				'show_ui'               => false,
				'show_in_nav_menus'     => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_nav_menus'     => false,
				'show_in_rest'          => false,
				'show_tagcloud'         => false,
				'show_in_quick_edit'    => false,
			);

			register_taxonomy( 'wcwar_warranty', array( 'wcwar_warranty_req' ), $curr_args );

			$curr_args = array(
				'label'                 => esc_html__( 'Warranties and Returns', 'xforwoocommerce' ),
				'labels'                => array(
					'name'              => esc_html__( 'Warranty and Returns Requests', 'xforwoocommerce' ),
					'singular_name'     => esc_html__( 'Warranty and Return Request', 'xforwoocommerce' ),
					'all_items'         => esc_html__( 'All Requests', 'xforwoocommerce' ),
					'menu_name'         => esc_html__( 'Warranties and Returns', 'xforwoocommerce' ),
					'not_found'         => esc_html__( 'No requests found', 'xforwoocommerce' ),
					'edit_item'         => esc_html__( 'Edit Request', 'xforwoocommerce' ),
					'add_new_item'      => esc_html__( 'Create a Request', 'xforwoocommerce' ),
					'new_item_name'     => esc_html__( 'Create a Request', 'xforwoocommerce' ),
					'parent_item_colon' => esc_html__( 'Parent Request', 'xforwoocommerce' ),
					'view_item'         => esc_html__( 'View Request', 'xforwoocommerce' ),
					'search_items'      => esc_html__( 'Search Requests', 'xforwoocommerce' )
				),
				'public'                => true,
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				'show_ui'               => true,
				'capability_type'       => 'post',
				'capabilities' => array(
					'create_posts' => true,
				),
				'map_meta_cap' => true,
				'hierarchical'          => true,
				'show_in_nav_menus'     => true,
				'menu_position'         => 56,
				'supports'              => array( 'title', 'editor', 'comments' ),
				'has_archive'           => false
			);

			register_post_type( 'wcwar_warranty_req', $curr_args );

			$curr_status = get_terms( 'wcwar_warranty', array( 'hide_empty' => false ) );

			if ( empty( $curr_status) ) {

				$curr_warranty_status = array(
					'new' => esc_html__( 'New', 'wc_warranty' ),
					'processing' => esc_html__( 'Processing', 'wc_warranty' ),
					'completed' => esc_html__( 'Completed', 'wc_warranty' ),
					'rejected' => esc_html__( 'Rejected', 'wc_warranty' )
				);

				foreach ( $curr_warranty_status as $k =>$v ) {
					if ( !get_term_by( 'slug', $k, 'wcwar_warranty' ) ) {

						wp_insert_term(
							$v,
							'wcwar_warranty',
							array(
								'slug' => $k
							)
						);

					}
				}

			}

			if ( !function_exists( 'XforWC' ) ) {
				$domain = 'woocommerce-warranties-and-returns';
				$dir = untrailingslashit( WP_LANG_DIR );
				$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

				if ( $loaded = load_textdomain( $domain, $dir . '/plugins/' . $domain . '-' . $locale . '.mo' ) ) {
					return $loaded;
				}
				else {
					load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
				}
			}

		}

		function wc_add_product_tab( $product_data_tabs ) {

			$product_data_tabs['wcwar_tab'] = array(
				'label' => esc_html__( 'Warranties and Returns', 'xforwoocommerce' ),
				'target' => 'wcwar_tab',
				'class' => 'wcwar_tab hide_if_external hide_if_variable'
			);
			return $product_data_tabs;

		}

		function wc_product_tab() {
			global $post;

			//$curr_warranty = get_post_meta( $post->ID, '_wcwar_warranty', true );
			$curr_warranty = $this->_get_warranty( $post->ID );

		?>
		<div id="wcwar_tab" class="panel woocommerce_options_panel">

			<div class="wcwar-opt wcwar-opt-basic">
				<p class="wcwar_type">
					<label for="wcwar_type"><?php esc_html_e( 'Select warranty type', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_type" name="wcwar_type_single" class="option select short">
						<option value="no_warranty" <?php if ( $curr_warranty['type'] == 'no_warranty') echo 'selected'; ?>><?php esc_html_e( 'No warranty', 'xforwoocommerce' ); ?></option>
						<option value="preset_warranty" <?php if ( $curr_warranty['type'] == 'preset_warranty') echo 'selected'; ?>><?php esc_html_e( 'Preset warranty', 'xforwoocommerce' ); ?></option>
						<option value="quick_warranty" <?php if ( $curr_warranty['type'] == 'quick_warranty') echo 'selected'; ?>><?php esc_html_e( 'Quick warranty', 'xforwoocommerce' ); ?></option>
					</select>
					<em><?php esc_html_e( 'Select preset warranty or add quick product warranty.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'preset_warranty' ? '' : 'disabled' ); // OK
			?>
			<div class="wcwar-opt wcwar-opt-preset" data-group="preset_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_preset">
					<label for="wcwar_preset"><?php esc_html_e( 'Select warranty preset', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_preset" name="wcwar_preset_single" class="option select short"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
						<option value="" <?php if (isset( $curr_warranty['preset'] ) && $curr_warranty['preset'] == '') echo 'selected'; ?>><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
					<?php
						$presets = get_terms( 'wcwar_warranty_pre', array('hide_empty' => false) );

						foreach ( $presets as $preset ) {
					?>
						<option value="<?php echo esc_attr( $preset->term_id ); ?>" <?php if ( isset( $curr_warranty['preset'] ) && $curr_warranty['preset'] == $preset->term_id ) echo 'selected'; ?>><?php echo esc_html( $preset->name ); ?></option>
					<?php
						}
					?>
					</select>
					<em><?php esc_html_e( 'Select warranty preset to use with the current product. Warranty presets can be set in Products &gt; Warranty Presets.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'quick_warranty' ? '' : 'disabled' ); // OK
			?>
			<div class="wcwar-opt wcwar-opt-type" data-group="quick_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_q_type">
					<label for="wcwar_q_type"><?php esc_html_e( 'Select quick warranty type', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_q_type" name="wcwar_q_type_single" class="option select short"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
						<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
						<option value="included_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty') echo 'selected'; ?>><?php esc_html_e( 'Included warranty', 'xforwoocommerce' ); ?></option>
						<option value="paid_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty') echo 'selected'; ?>><?php esc_html_e( 'Add-on warranty', 'xforwoocommerce' ); ?></option>
					</select>
					<em><?php esc_html_e( 'Warranties can be included or paid as an add-on. Choose your warranty type.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty' ? '' : 'disabled' ); // OK
				$curr_options = ( isset( $curr_warranty['included_warranty'] ) ? $curr_warranty['included_warranty'] : array() );
			?>
			<div class="wcwar-opt" data-group="included_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_qi_title">
					<label for="wcwar_qi_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
					<input type="text" class="option short" name="wcwar_qi_title_single" id="wcwar_qi_title" value="<?php if ( isset( $curr_options['title'] ) && $curr_options['title'] !== '' ) echo esc_attr( $curr_options['title'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_desc">
					<label for="wcwar_qi_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
					<textarea class="option short" name="wcwar_qi_desc_single" id="wcwar_qi_desc" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php if ( isset( $curr_options['desc'] ) && $curr_options['desc'] !== '' ) echo wp_kses_post( $curr_options['desc'] ); ?></textarea>
					<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_thumb">
					<span class="thumb_preview"><?php if (isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $curr_options['thumb'] ) . '" alt="' .esc_html__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
					<label for="wcwar_qi_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
					<input type="hidden" class="option short" name="wcwar_qi_thumb_single" id="wcwar_qi_thumb" value="<?php if ( isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '' ) echo esc_url( $curr_options['thumb'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<button type="button" class="option button add_wcwar_qi_thumb"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
				</p>
				<p class="wcwar_qi_period">
					<label for="wcwar_qi_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
					<input type="number" class="option short" name="wcwar_qi_period_single" id="wcwar_qi_period" value="<?php if ( isset( $curr_options['period'] ) && $curr_options['period'] !== '' ) echo esc_attr( $curr_options['period'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_type">
					<label for="wcwar_qi_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_qi_type" name="wcwar_qi_type_single" class="option select short"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
						<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
						<option value="days" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
						<option value="weeks" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
						<option value="months" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
						<option value="years" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
						<option value="lifetime" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
					</select>
					<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty' ? '' : 'disabled' ); // OK
			?>
			<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_qp_without_single">
					<label for="wcwar_qp_without_single"><?php esc_html_e( 'Add no warranty option', 'xforwoocommerce' ); ?></label>
					<input type="checkbox" name="wcwar_qp_without_single" id="wcwar_qp_without_single" value="yes" <?php if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) echo 'checked'; ?> class="option checkbox"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?> />
					<em><?php esc_html_e( 'To enable product purchases without a paid warranty check this option.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<div class="paid_warranty_items wcwar_metaboxes">
				<?php
					if ( isset( $curr_warranty['paid_warranty'] ) ) {

						foreach ( $curr_warranty['paid_warranty'] as $warranty ){ 

					?>
						<div class="wcwar_metabox">
							<a href="#" class="edit_paid_warranty"><i class="wcwar-change"></i></a>
							<a href="#" class="move_paid_warranty"><i class="wcwar-move"></i></a>
							<a href="#" class="remove_paid_warranty"><i class="wcwar-close"></i></a>
							<div class="wcwar-opt" data-group="included_warranty">
								<p class="wcwar_qp_title">
									<label for="wcwar_qp_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
									<input type="text" class="option short" name="wcwar_qp_title_single[]" value="<?php if ( isset( $warranty['title'] ) && $warranty['title'] !== '' ) echo wp_kses_post( $warranty['title'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_desc">
									<label for="wcwar_qp_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
									<textarea class="option short" name="wcwar_qp_desc_single[]" placeholder=""><?php if ( isset( $warranty['desc'] ) && $warranty['desc'] !== '') echo wp_kses_post( $warranty['desc'] ); ?></textarea>
									<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_thumb">
									<span class="thumb_preview"><?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $warranty['thumb'] ) . '" alt="' . esc_attr__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
									<label for="wcwar_qp_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
									<input type="hidden" class="option short" name="wcwar_qp_thumb_single[]" value="<?php if ( isset( $warranty['thumb'] ) && $warranty['thumb'] !== '' ) echo esc_url( $warranty['thumb'] ); ?>" placeholder="">
									<button type="button" class="option button add_wcwar_qp_thumb"><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
								</p>
								<p class="wcwar_qp_period">
									<label for="wcwar_qp_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
									<input type="number" class="option short" name="wcwar_qp_period_single[]" value="<?php if ( isset( $warranty['period'] ) && $warranty['period'] !== '' ) echo esc_attr( $warranty['period'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_type">
									<label for="wcwar_qp_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
									<select name="wcwar_qp_type_single[]" class="option select short">
										<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
										<option value="days" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
										<option value="weeks" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
										<option value="months" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
										<option value="years" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
										<option value="lifetime" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
									</select>
									<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_price">
									<label for="wcwar_qp_price"><?php esc_html_e( 'Add-on price', 'xforwoocommerce' ); ?></label>
									<input type="number" class="option short wc_input_price" name="wcwar_qp_price_single[]" value="<?php if ( isset( $warranty['price'] ) ) echo esc_attr( $warranty['price'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter add-on price for this warranty.', 'xforwoocommerce' ); ?></em>
								</p>
							</div>
						</div>
					<?php
						}
					}
				?>
				</div>
				<div class="wcwar-add-warranty">
					<button type="button" class="option button add_paid_warranty"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add new warranty option', 'xforwoocommerce' ); ?></button>
				</div>
			</div>

		</div>
		<?php

		}

		function wc_add_variable_tab_24( $loop, $data, $variation ) {

			//$curr_warranty = get_post_meta( $variation->ID, '_wcwar_warranty' );
			//$curr_warranty = is_array( $curr_warranty ) && !empty( $curr_warranty ) ? maybe_unserialize( $curr_warranty[0] ) : array();

			$curr_warranty = $this->_get_warranty( $variation->ID );

		?>
			<div class="wcwar_tab">
				<div class="options_group basic">
			<?php
				woocommerce_wp_select(
					array(
						'id'          => 'wcwar_type[' . $loop . ']',
						'label'       => esc_html__( 'Select warranty type', 'xforwoocommerce' ),
						'description' => esc_html__( 'Select preset warranty or add quick product warranty.', 'xforwoocommerce' ),
						'value'       => esc_attr( $curr_warranty['type'] ),
						'class'       => 'wcwar_type option select short',
						'wrapper_class' => 'form-row form-row-first',
						'options' => array(
							'no_warranty'   => esc_html__( 'No warranty', 'xforwoocommerce' ),
							'preset_warranty'   => esc_html__( 'Preset warranty', 'xforwoocommerce' ),
							'quick_warranty' => esc_html__( 'Quick warranty', 'xforwoocommerce' )
						)
					)
				);
			?>
				</div>
			<?php

				$curr_disable = $curr_warranty['type'] == 'preset_warranty' ? array() : array( 'disabled' => 'disabled' );
				$presets = get_terms( 'wcwar_warranty_pre', array('hide_empty' => false) );
				$ready_presets = array(
					'' => esc_html__( 'Not selected', 'xforwoocommerce' )
				);

				foreach ( $presets as $preset ) {
					$ready_presets[$preset->term_id] = $preset->name;
				}
			?>
				<div class="wcwar-opt wcwar-opt-preset" data-group="preset_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
			<?php
				woocommerce_wp_select(
					array(
						'id'          => 'wcwar_preset[' . $loop . ']',
						'label'       => esc_html__( 'Select warranty preset', 'xforwoocommerce' ),
						'description' => esc_html__( 'Select warranty preset to use with the current product. Warranty presets can be set in Products &gt; Warranty Presets.', 'xforwoocommerce' ),
						'value'       => isset( $curr_warranty['preset'] ) && $curr_warranty['preset'] !== '' ? esc_attr( $curr_warranty['preset'] ) : '',
						'class'       => 'wcwar_preset option select short',
						'wrapper_class' => 'form-row form-row-last',
						'custom_attributes' => $curr_disable,
						'options' => $ready_presets
					)
				);
			?>
				</div>
			<?php

				$curr_disable = $curr_warranty['type'] == 'quick_warranty' ? array() : array( 'disabled' => 'disabled' );
			?>
				<div class="wcwar-opt wcwar-opt-type" data-group="quick_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
			<?php
				woocommerce_wp_select(
					array(
						'id'          => 'wcwar_q_type[' . $loop . ']',
						'label'       => esc_html__( 'Select quick warranty type', 'xforwoocommerce' ),
						'description' => esc_html__( 'Warranties can be included or paid as an add-on. Choose your warranty type.', 'xforwoocommerce' ),
						'value'       => isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ? esc_attr( $curr_warranty['quick'] ) : '',
						'class'       => 'wcwar_q_type option select short',
						'wrapper_class' => 'form-row form-row-last',
						'custom_attributes' => $curr_disable,
						'options' => array(
							'' => esc_html__( 'Not selected', 'xforwoocommerce' ),
							'included_warranty' => esc_html__( 'Included warranty', 'xforwoocommerce' ),
							'paid_warranty' => esc_html__( 'Add-on warranty', 'xforwoocommerce' )
						)
					)
				);
			?>
				</div>
			<?php

				$curr_disable = $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty' ? array() : array( 'disabled' => 'disabled' );
				$curr_options = isset( $curr_warranty['included_warranty'] ) ? $curr_warranty['included_warranty'] : array();
			?>
				<div class="wcwar-opt" data-group="included_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
			<?php
				woocommerce_wp_select(
					array(
						'id'          => 'wcwar_qi_type[' . $loop . ']',
						'label'       => esc_html__( 'Warranty period type', 'xforwoocommerce' ),
						'description' => esc_html__( 'Select warranty period type.', 'xforwoocommerce' ),
						'value'       => isset( $curr_options['type'] ) && $curr_options['type'] !== '' ? esc_attr( $curr_options['type'] ) : '',
						'class'       => 'wcwar_qi_type option select short',
						'wrapper_class' => 'form-row form-row-first',
						'custom_attributes' => $curr_disable,
						'options' => array(
							'' => esc_html__( 'Not selected', 'xforwoocommerce' ),
							'days' => esc_html__( 'Days', 'xforwoocommerce' ),
							'weeks' => esc_html__( 'Weeks', 'xforwoocommerce' ),
							'months' => esc_html__( 'Months', 'xforwoocommerce' ),
							'years' => esc_html__( 'Years', 'xforwoocommerce' ),
							'lifetime' => esc_html__( 'Lifetime', 'xforwoocommerce' )
						)
					)
				);

				woocommerce_wp_text_input(
					array( 
						'id'          => 'wcwar_qi_period[' .  $loop . ']',
						'label'       => esc_html__( 'Warranty period', 'xforwoocommerce' ),
						'description' => esc_html__( 'Enter warranty period.', 'xforwoocommerce' ),
						'class'       => 'wcwar_qi_period option short',
						'wrapper_class' => 'form-row form-row-last',
						'value'       => isset( $curr_options['period'] ) && $curr_options['period'] !== '' ? esc_attr( $curr_options['period'] ) : '',
						'custom_attributes' => array_merge( array( 'step' => 'any', 'min' => '0' ), $curr_disable )
					)
				);

				woocommerce_wp_textarea_input(
					array( 
						'id'          => 'wcwar_qi_desc[' .  $loop . ']',
						'label'       => esc_html__( 'Warranty description (optional)', 'xforwoocommerce' ),
						'placeholder' => '',
						'description' => esc_html__( 'Enter warranty description.', 'xforwoocommerce' ),
						'class'       => 'wcwar_qi_desc option short',
						'wrapper_class' => 'form-row form-row-first',
						'custom_attributes' => $curr_disable,
						'value'       => isset( $curr_options['desc'] ) && $curr_options['desc'] !== '' ? wp_kses_post( $curr_options['desc'] ) : '',
					)
				);

			?>
				<span class="thumb_preview"><?php echo isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '' ? '<img width="64" height="auto" src="' . esc_url( $curr_options['thumb'] ) . '" alt="' . esc_attr__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />' : '<img width="64" height="auto" src="' . self::$url_path . 'assets/images/no_image.gif" alt="' . esc_attr__( 'No image', 'xforwoocommerce' ) . '" />'; ?></span>
				<button type="button" class="option button add_wcwar_qi_thumb"<?php echo !empty( $curr_disable ) ? ' disabled="disabled"' : ''; ?>><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
			<?php

				woocommerce_wp_hidden_input(
					array(
						'id'          => 'wcwar_qi_thumb[' .  $loop . ']',
						'label'       => esc_html__( 'Warranty thumbnail (optional)', 'xforwoocommerce' ),
						'placeholder' => '',
						'description' => esc_html__( 'Add a warranty thumbnail.', 'xforwoocommerce' ),
						'class'       => 'wcwar_qi_thumb option short',
						'wrapper_class' => 'form-row form-row-last',
						'custom_attributes' => $curr_disable,
						'value'       => isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '' ? esc_url( $curr_options['thumb'] ) : ''
					)
				);
			?>
				</div>
			<?php

				$curr_disable = $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty' ? array() : array( 'disabled' => 'disabled' );
			?>
				<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<?php
					woocommerce_wp_checkbox(
						array(
							'id'            => 'wcwar_qp_without[' .  $loop . ']',
							'label'         => esc_html__( 'Add no warranty option', 'xforwoocommerce' ),
							'description'   => esc_html__( 'To enable product purchases without a paid warranty check this option.', 'xforwoocommerce' ),
							'class'         => 'wcwar_qp_without option short',
							'wrapper_class' => 'form-row form-row-full',
							'custom_attributes' => $curr_disable,
							'value'         => isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ? 'yes' : 'no'
						)
					);

				?>
					<div class="paid_warranty_items wcwar_metaboxes">
					<?php
						if ( isset( $curr_warranty['paid_warranty'] ) ) {

							foreach ( $curr_warranty['paid_warranty'] as $warranty ){
							?>
								<div class="wcwar_metabox">
									<a href="#" class="edit_paid_warranty"><i class="wcwar-change"></i></a>
									<a href="#" class="move_paid_warranty"><i class="wcwar-move"></i></a>
									<a href="#" class="remove_paid_warranty"><i class="wcwar-close"></i></a>
									<div class="wcwar-opt" data-group="included_warranty">
										<p class="wcwar_qp_title">
											<label for="wcwar_qp_title"><?php esc_html_e( 'Warranty title (optional)', 'xforwoocommerce' ); ?></label>
											<input type="text" class="option short" name="wcwar_qp_title[<?php echo esc_attr( $loop ); ?>][]" value="<?php if ( isset( $warranty['title'] ) && $warranty['title'] !== '' ) echo wp_kses_post( $warranty['title'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_desc">
											<label for="wcwar_qp_desc"><?php esc_html_e( 'Warranty description (optional)', 'xforwoocommerce' ); ?></label>
											<textarea class="option short" name="wcwar_qp_desc[<?php echo esc_attr( $loop ); ?>][]" placeholder=""><?php if (isset( $warranty['desc'] ) && $warranty['desc'] !== '') echo wp_kses_post( $warranty['desc'] ); ?></textarea>
											<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_thumb">
											<span class="thumb_preview"><?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $warranty['thumb'] ) . '" alt="' . esc_html__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
											<label for="wcwar_qp_thumb"><?php esc_html_e( 'Warranty thumbnail (optional)', 'xforwoocommerce' ); ?></label>
											<input type="hidden" class="option short" name="wcwar_qp_thumb[<?php echo esc_attr( $loop ); ?>][]" value="<?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo esc_url( $warranty['thumb'] ); ?>" placeholder="">
											<button type="button" class="option button add_wcwar_qp_thumb"><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
										</p>
										<p class="wcwar_qp_period">
											<label for="wcwar_qp_period"><?php esc_html_e( 'Warranty period', 'xforwoocommerce' ); ?></label>
											<input type="number" class="option short" name="wcwar_qp_period[<?php echo esc_attr( $loop ); ?>][]" value="<?php if (isset( $warranty['period'] ) && $warranty['period'] !== '') echo esc_attr( $warranty['period'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter warranty period.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_type">
											<label for="wcwar_qp_type"><?php esc_html_e( 'Warranty period type', 'xforwoocommerce' ); ?></label>
											<select name="wcwar_qp_type[<?php echo esc_attr( $loop ); ?>][]" class="option select short">
												<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
												<option value="days" <?php if ( isset( $warranty['type'] ) && $warranty['type'] == 'days' ) echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
												<option value="weeks" <?php if ( isset( $warranty['type'] ) && $warranty['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
												<option value="months" <?php if ( isset( $warranty['type'] ) && $warranty['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
												<option value="years" <?php if ( isset( $warranty['type'] ) && $warranty['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
												<option value="lifetime" <?php if ( isset( $warranty['type'] ) && $warranty['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
											</select>
											<em><?php esc_html_e( 'Select warranty period type.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_price">
											<label for="wcwar_qp_price"><?php esc_html_e( 'Additional warranty price', 'xforwoocommerce' ); ?></label>
											<input type="number" class="option short wc_input_price" name="wcwar_qp_price[<?php echo esc_attr( $loop ); ?>][]" value="<?php if (isset( $warranty['price'] ) ) echo esc_attr( $warranty['price'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter additional price for this warranty.', 'xforwoocommerce' ); ?></em>
										</p>
									</div>
								</div>
							<?php
							}
						}
					?>
					</div>
					<div class="wcwar-add-warranty">
						<button type="button" class="option button add_paid_warranty"<?php echo !empty( $curr_disable ) ? ' disabled="disabled"' : ''; ?>><?php esc_html_e( 'Add new warranty option', 'xforwoocommerce' ); ?></button>
					</div>
				</div>
			</div>
		<?php

		}

		function wc_product_save_24( $curr_id, $i ) {

			if ( isset( $_POST['wcwar_type'][$i] ) ) {
				if ( $_POST['wcwar_type'][$i] == 'preset_warranty' ) {
					$curr['type'] = 'preset_warranty';
					$curr['preset'] = ( isset( $_POST['wcwar_preset'][$i] ) && $_POST['wcwar_preset'][$i] !== '' ? $_POST['wcwar_preset'][$i] : '' );
				}
				else if ( $_POST['wcwar_type'][$i] == 'quick_warranty' ) {
					$curr['type'] = 'quick_warranty';
					if ( isset( $_POST['wcwar_q_type'][$i] ) ){
						if ( $_POST['wcwar_q_type'][$i] == 'included_warranty' ) {
							$curr['quick'] = 'included_warranty';
							$curr['included_warranty'] = array(
								'type' => ( isset( $_POST['wcwar_qi_type'][$i] ) && $_POST['wcwar_qi_type'][$i] !== '' ? $_POST['wcwar_qi_type'][$i] : '' ),
								'period' => ( isset( $_POST['wcwar_qi_period'][$i] ) && $_POST['wcwar_qi_period'][$i] !== '' ? $_POST['wcwar_qi_period'][$i] : '' ),
								'desc' => ( isset( $_POST['wcwar_qi_desc'][$i] ) && $_POST['wcwar_qi_desc'][$i] !== '' ? $_POST['wcwar_qi_desc'][$i] : '' ),
								'thumb' => ( isset( $_POST['wcwar_qi_thumb'][$i] ) && $_POST['wcwar_qi_thumb'][$i] !== '' ? $_POST['wcwar_qi_thumb'][$i] : '' )
							);
						}
						else if ( $_POST['wcwar_q_type'][$i] == 'paid_warranty' ) {
							$curr['quick'] = 'paid_warranty';

							$curr_free = ( isset( $_POST['wcwar_qp_without'][$i] ) && $_POST['wcwar_qp_without'][$i] == 'yes' ? 'yes' : 'no' );
							$curr_prices = ( isset( $_POST['wcwar_qp_price'][$i] ) && !empty( $_POST['wcwar_qp_price'][$i] ) ? $_POST['wcwar_qp_price'][$i] : array() );
							$curr_types = ( isset( $_POST['wcwar_qp_type'][$i] ) && !empty( $_POST['wcwar_qp_type'][$i] ) ? $_POST['wcwar_qp_type'][$i] : array() );
							$curr_periods = ( isset( $_POST['wcwar_qp_period'][$i] ) && !empty( $_POST['wcwar_qp_period'][$i] ) ? $_POST['wcwar_qp_period'][$i] : array() );
							$curr_titles = ( isset( $_POST['wcwar_qp_title'][$i] ) && !empty( $_POST['wcwar_qp_title'][$i] ) ? $_POST['wcwar_qp_title'][$i] : array() );
							$curr_descs = ( isset( $_POST['wcwar_qp_desc'][$i] ) && !empty( $_POST['wcwar_qp_desc'][$i] ) ? $_POST['wcwar_qp_desc'][$i] : array() );
							$curr_thumbs = ( isset( $_POST['wcwar_qp_thumb'][$i] ) && !empty( $_POST['wcwar_qp_thumb'][$i] ) ? $_POST['wcwar_qp_thumb'][$i] : array() );

							$curr['paid_no_warranty'] = $curr_free;
							for ( $n = 0; $n < count( $curr_types); $n++ ) {
								if (!isset( $curr_types[$n] ) || !isset( $curr_periods[$n] ) ) continue;

								$curr['paid_warranty'][] = array(
									'price' => $curr_prices[$n],
									'type' => $curr_types[$n],
									'period' => $curr_periods[$n],
									'title' => ( isset( $curr_titles[$n] ) && $curr_titles[$n] !== '' ? $curr_titles[$n] : '' ),
									'desc' => ( isset( $curr_descs[$n] ) && $curr_descs[$n] !== '' ? $curr_descs[$n] : '' ),
									'thumb' => ( isset( $curr_thumbs[$n] ) && $curr_thumbs[$n] !== '' ? $curr_thumbs[$n] : '' )
								);
							}
						}
					}
				}
				else {
					$curr = array(
						'type' => 'no_warranty'
					);
				}

				update_post_meta( $curr_id, '_wcwar_warranty', $curr );

			}

		}

		function wc_product_save( $curr_id, $post, $update ) {

			$curr = array();

			if ( isset( $_POST['wcwar_type_single'] ) ) {
				if ( $_POST['wcwar_type_single'] == 'preset_warranty' ) {
					$curr['type'] = 'preset_warranty';
					$curr['preset'] = ( isset( $_POST['wcwar_preset_single'] ) && $_POST['wcwar_preset_single'] !== '' ? $_POST['wcwar_preset_single'] : '' );
				}
				else if ( $_POST['wcwar_type_single'] == 'quick_warranty' ) {
					$curr['type'] = 'quick_warranty';
					if ( isset( $_POST['wcwar_q_type_single'] ) ){
						if ( $_POST['wcwar_q_type_single'] == 'included_warranty' ) {
							$curr['quick'] = 'included_warranty';
							$curr['included_warranty'] = array(
								'type' => ( isset( $_POST['wcwar_qi_type_single'] ) && $_POST['wcwar_qi_type_single'] !== '' ? $_POST['wcwar_qi_type_single'] : '' ),
								'period' => ( isset( $_POST['wcwar_qi_period_single'] ) && $_POST['wcwar_qi_period_single'] !== '' ? $_POST['wcwar_qi_period_single'] : '' ),
								'desc' => ( isset( $_POST['wcwar_qi_desc_single'] ) && $_POST['wcwar_qi_desc_single'] !== '' ? $_POST['wcwar_qi_desc_single'] : '' ),
								'thumb' => ( isset( $_POST['wcwar_qi_thumb_single'] ) && $_POST['wcwar_qi_thumb_single'] !== '' ? $_POST['wcwar_qi_thumb_single'] : '' )
							);
						}
						else if ( $_POST['wcwar_q_type_single'] == 'paid_warranty' ) {
							$curr['quick'] = 'paid_warranty';

							$curr_free = ( isset( $_POST['wcwar_qp_without_single'] ) && $_POST['wcwar_qp_without_single'] == 'yes' ? 'yes' : 'no' );
							$curr_prices = ( isset( $_POST['wcwar_qp_price_single'] ) && !empty( $_POST['wcwar_qp_price_single'] ) ? $_POST['wcwar_qp_price_single'] : array() );
							$curr_types = ( isset( $_POST['wcwar_qp_type_single'] ) && !empty( $_POST['wcwar_qp_type_single'] ) ? $_POST['wcwar_qp_type_single'] : array() );
							$curr_periods = ( isset( $_POST['wcwar_qp_period_single'] ) && !empty( $_POST['wcwar_qp_period_single'] ) ? $_POST['wcwar_qp_period_single'] : array() );
							$curr_titles = ( isset( $_POST['wcwar_qp_title_single'] ) && !empty( $_POST['wcwar_qp_title_single'] ) ? $_POST['wcwar_qp_title_single'] : array() );
							$curr_descs = ( isset( $_POST['wcwar_qp_desc_single'] ) && !empty( $_POST['wcwar_qp_desc_single'] ) ? $_POST['wcwar_qp_desc_single'] : array() );
							$curr_thumbs = ( isset( $_POST['wcwar_qp_thumb_single'] ) && !empty( $_POST['wcwar_qp_thumb_single'] ) ? $_POST['wcwar_qp_thumb_single'] : array() );

							$curr['paid_no_warranty'] = $curr_free;
							for ( $i = 0; $i < count( $curr_types); $i++ ) {
								if (!isset( $curr_types[$i] ) || !isset( $curr_periods[$i] ) ) continue;

								$curr['paid_warranty'][] = array(
									'price' => $curr_prices[$i],
									'type' => $curr_types[$i],
									'period' => $curr_periods[$i],
									'titles' => ( isset( $curr_titles[$i] ) && $curr_titles[$i] !== '' ? $curr_titles[$i] : '' ),
									'desc' => ( isset( $curr_descs[$i] ) && $curr_descs[$i] !== '' ? $curr_descs[$i] : '' ),
									'thumb' => ( isset( $curr_thumbs[$i] ) && $curr_thumbs[$i] !== '' ? $curr_thumbs[$i] : '' )
								);
							}
						}
					}
				}
				else {
					$curr['type'] = 'no_warranty';
				}

				update_post_meta( $curr_id, '_wcwar_warranty', $curr );

			}

		}

		function war_add_presets() {
			$curr_warranty = array();
			$curr_disable = '';

			if ( empty( $curr_warranty) ) {
				$curr_warranty = array( 'type' => 'no_warranty' );
			}
	?>
	<div class="form-field">
		<label><?php esc_html_e( 'Warranty', 'xforwoocommerce' ); ?><label>
		<div id="wcwar_tab">

			<div class="wcwar-opt wcwar-opt-type" data-group="quick_warranty">
				<p class="wcwar_q_type">
					<label for="wcwar_q_type"><?php esc_html_e( 'Select warranty type', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_q_type" name="wcwar_q_type" class="option select short">
						<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
						<option value="included_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty') echo 'selected'; ?>><?php esc_html_e( 'Included warranty', 'xforwoocommerce' ); ?></option>
						<option value="paid_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty') echo 'selected'; ?>><?php esc_html_e( 'Add-on warranty', 'xforwoocommerce' ); ?></option>
					</select>
					<em><?php esc_html_e( 'Warranties can be included or paid as an add-on. Choose your warranty type.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty' ? '' : ' disabled="disabled"' ); // OK
				$curr_options = ( isset( $curr_warranty['included_warranty'] ) ? $curr_warranty['included_warranty'] : array() );
			?>
			<div class="wcwar-opt" data-group="included_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_qi_title">
					<label for="wcwar_qi_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
					<input type="text" class="option short" name="wcwar_qi_title" id="wcwar_qi_title" value="<?php if ( isset( $curr_options['title'] ) && $curr_options['title'] !== '' ) echo esc_attr( $curr_options['title'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_desc">
					<label for="wcwar_qi_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
					<textarea class="option short" name="wcwar_qi_desc" id="wcwar_qi_desc" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php if ( isset( $curr_options['desc'] ) && $curr_options['desc'] !== '') echo wp_kses_post( $curr_options['desc'] ); ?></textarea>
					<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_thumb">
					<span class="thumb_preview"><?php if ( isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $curr_options['thumb'] ) . '" alt="' .esc_html__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
					<label for="wcwar_qi_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
					<input type="hidden" class="option short" name="wcwar_qi_thumb" id="wcwar_qi_thumb" value="<?php if (isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '') echo esc_url( $curr_options['thumb'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<button type="button" class="option button add_wcwar_qi_thumb"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
				</p>
				<p class="wcwar_qi_period">
					<label for="wcwar_qi_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
					<input type="number" class="option short" name="wcwar_qi_period" id="wcwar_qi_period" value="<?php if ( isset( $curr_options['period'] ) && $curr_options['period'] !== '' ) echo esc_attr( $curr_options['period'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
					<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
				</p>
				<p class="wcwar_qi_type">
					<label for="wcwar_qi_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
					<select id="wcwar_qi_type" name="wcwar_qi_type" class="option select short"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
						<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
						<option value="days" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
						<option value="weeks" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
						<option value="months" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
						<option value="years" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
						<option value="lifetime" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
					</select>
					<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<?php
				$curr_disable = ( $curr_warranty['type'] == 'quick_warranty' && isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty' ? '' : 'disabled' ); // OK
			?>
			<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<p class="wcwar_qp_without">
					<label for="wcwar_qp_without"><?php esc_html_e( 'Add no warranty option', 'xforwoocommerce' ); ?></label>
					<input type="checkbox" name="wcwar_qp_without" id="wcwar_qp_without" value="yes" <?php if (isset( $curr_warranty['paid_warranty_exclude'] ) && $curr_warranty['paid_warranty_exclude'] == 'yes') echo 'checked'; ?> class="option checkbox"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?> />
					<em><?php esc_html_e( 'To enable product purchases without a paid warranty check this option.', 'xforwoocommerce' ); ?></em>
				</p>
			</div>

			<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
				<div class="paid_warranty_items wcwar_metaboxes">
				<?php
					if ( isset( $curr_warranty['paid_warranty'] ) ) {

						foreach ( $curr_warranty['paid_warranty'] as $warranty ){

					?>
						<div class="wcwar_metabox">
							<a href="#" class="edit_paid_warranty"><i class="wcwar-change"></i></a>
							<a href="#" class="move_paid_warranty"><i class="wcwar-move"></i></a>
							<a href="#" class="remove_paid_warranty"><i class="wcwar-close"></i></a>
							<div class="wcwar-opt" data-group="included_warranty">
								<p class="wcwar_qp_title">
									<label for="wcwar_qp_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
									<input type="text" class="option short" name="wcwar_qp_title[]" value="<?php if ( isset( $warranty['title'] ) && $warranty['title'] !== '' ) echo wp_kses_post( $warranty['title'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_desc">
									<label for="wcwar_qp_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
									<textarea class="option short" name="wcwar_qp_desc[]" placeholder=""><?php if (isset( $warranty['desc'] ) && $warranty['desc'] !== '') echo wp_kses_post( $warranty['desc'] ); ?></textarea>
									<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_thumb">
									<span class="thumb_preview"><?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $warranty['thumb'] ) . '" alt="' . esc_attr__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
									<label for="wcwar_qp_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
									<input type="hidden" class="option short" name="wcwar_qp_thumb[]" value="<?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo esc_url( $warranty['thumb'] ); ?>" placeholder="">
									<button type="button" class="option button add_wcwar_qp_thumb"><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
								</p>
								<p class="wcwar_qp_period">
									<label for="wcwar_qp_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
									<input type="number" class="option short" name="wcwar_qp_period[]" value="<?php if ( isset( $warranty['period'] ) && $warranty['period'] !== '' ) echo esc_attr( $warranty['period'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_type">
									<label for="wcwar_qp_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
									<select name="wcwar_qp_type[]" class="option select short">
										<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
										<option value="days" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
										<option value="weeks" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
										<option value="months" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
										<option value="years" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
										<option value="lifetime" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
									</select>
									<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
								</p>
								<p class="wcwar_qp_price">
									<label for="wcwar_qp_price"><?php esc_html_e( 'Add-on price', 'xforwoocommerce' ); ?></label>
									<input type="number" class="option short wc_input_price" name="wcwar_qp_price[]" value="<?php if (isset( $warranty['price'] ) ) echo esc_attr( $warranty['price'] ); ?>" placeholder="">
									<em><?php esc_html_e( 'Enter add-on price for this warranty.', 'xforwoocommerce' ); ?></em>
								</p>
							</div>
						</div>
					<?php
						}
					}
				?>
				</div>
				<div class="wcwar-add-warranty">
					<button type="button" class="option button add_paid_warranty"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add new warranty option', 'xforwoocommerce' ); ?></button>
				</div>
			</div>

		</div>

	</div>
	<?php

		}


		function war_edit_presets( $term, $taxonomy) {
			$curr_warranty = get_term_meta( $term->term_id, '_wcwar_warranty', true );

			$curr_disable = '';

	?>

		<tr class="wcwar-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Warranty', 'xforwoocommerce' ); ?></label></th>
			<td>
				<div id="wcwar_tab">

					<div class="wcwar-opt wcwar-opt-type" data-group="quick_warranty">
						<p class="wcwar_q_type">
							<label for="wcwar_q_type"><?php esc_html_e( 'Select warranty type', 'xforwoocommerce' ); ?></label>
							<select id="wcwar_q_type" name="wcwar_q_type" class="option select short">
								<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
								<option value="included_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty') echo 'selected'; ?>><?php esc_html_e( 'Included warranty', 'xforwoocommerce' ); ?></option>
								<option value="paid_warranty" <?php if (isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty') echo 'selected'; ?>><?php esc_html_e( 'Add-on warranty', 'xforwoocommerce' ); ?></option>
							</select>
							<em><?php esc_html_e( 'Warranties can be included or paid as an add-on. Choose your warranty type.', 'xforwoocommerce' ); ?></em>
						</p>
					</div>

					<?php
						$curr_disable = ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'included_warranty' ? '' : 'disabled' ); // OK
						$curr_options = ( isset( $curr_warranty['included_warranty'] ) ? $curr_warranty['included_warranty'] : array() );
					?>
					<div class="wcwar-opt" data-group="included_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
						<p class="wcwar_qi_title">
							<label for="wcwar_qi_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
							<input type="text" class="option short" name="wcwar_qi_title" id="wcwar_qi_title" value="<?php if (isset( $curr_options['title'] ) && $curr_options['title'] !== '') echo esc_attr( $curr_options['title'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
							<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
						</p>
						<p class="wcwar_qi_desc">
							<label for="wcwar_qi_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
							<textarea class="option short" name="wcwar_qi_desc" id="wcwar_qi_desc" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php if (isset( $curr_options['desc'] ) && $curr_options['desc'] !== '') echo wp_kses_post( $curr_options['desc'] ); ?></textarea>
							<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
						</p>
						<p class="wcwar_qi_thumb">
							<span class="thumb_preview"><?php if (isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $curr_options['thumb'] ) . '" alt="' .esc_attr__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
							<label for="wcwar_qi_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
							<input type="hidden" class="option short" name="wcwar_qi_thumb" id="wcwar_qi_thumb" value="<?php if (isset( $curr_options['thumb'] ) && $curr_options['thumb'] !== '') echo esc_url( $curr_options['thumb'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
							<button type="button" class="option button add_wcwar_qi_thumb"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
						</p>
						<p class="wcwar_qi_period">
							<label for="wcwar_qi_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
							<input type="number" class="option short" name="wcwar_qi_period" id="wcwar_qi_period" value="<?php if (isset( $curr_options['period'] ) && $curr_options['period'] !== '') echo esc_attr( $curr_options['period'] ); ?>" placeholder=""<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>
							<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
						</p>
						<p class="wcwar_qi_type">
							<label for="wcwar_qi_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
							<select id="wcwar_qi_type" name="wcwar_qi_type" class="option select short"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>>

								<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
								<option value="days" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
								<option value="weeks" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
								<option value="months" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
								<option value="years" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
								<option value="lifetime" <?php if (isset( $curr_options['type'] ) && $curr_options['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
							</select>
							<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
						</p>
					</div>

					<?php
						$curr_disable = ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] == 'paid_warranty' ? '' : 'disabled' ); // OK
					?>
					<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
						<p class="wcwar_qp_without">
							<label for="wcwar_qp_without"><?php esc_html_e( 'Add no warranty option', 'xforwoocommerce' ); ?></label>
							<input type="checkbox" name="wcwar_qp_without" id="wcwar_qp_without" value="yes" <?php if (isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes') echo 'checked'; ?> class="option checkbox"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?> />
							<em><?php esc_html_e( 'To enable product purchases without a paid warranty check this option.', 'xforwoocommerce' ); ?></em>
						</p>
					</div>

					<div class="wcwar-opt" data-group="paid_warranty"<?php echo !empty( $curr_disable ) ? ' style="display:none;"' : ''; ?>>
						<div class="paid_warranty_items wcwar_metaboxes">
						<?php
							if ( isset( $curr_warranty['paid_warranty'] ) ) {

								foreach ( $curr_warranty['paid_warranty'] as $warranty ){

							?>
								<div class="wcwar_metabox">
									<a href="#" class="edit_paid_warranty"><i class="wcwar-change"></i></a>
									<a href="#" class="move_paid_warranty"><i class="wcwar-move"></i></a>
									<a href="#" class="remove_paid_warranty"><i class="wcwar-close"></i></a>
									<div class="wcwar-opt" data-group="included_warranty">
										<p class="wcwar_qp_title">
											<label for="wcwar_qp_title"><?php esc_html_e( 'Title', 'xforwoocommerce' ); ?></label>
											<input type="text" class="option short" name="wcwar_qp_title[]" value="<?php if ( isset( $warranty['title'] ) && $warranty['title'] !== '' ) echo wp_kses_post( $warranty['title'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter warranty title.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_desc">
											<label for="wcwar_qp_desc"><?php esc_html_e( 'Description', 'xforwoocommerce' ); ?></label>
											<textarea class="option short" name="wcwar_qp_desc[]" placeholder=""><?php if (isset( $warranty['desc'] ) && $warranty['desc'] !== '') echo wp_kses_post( $warranty['desc'] ); ?></textarea>
											<em><?php esc_html_e( 'Enter warranty description.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_thumb">
											<span class="thumb_preview"><?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo '<img width="64" height="auto" src="' . esc_url( $warranty['thumb'] ) . '" alt="' . esc_html__( 'Thumbnail preview', 'xforwoocommerce' ) . '" />'; ?></span>
											<label for="wcwar_qp_thumb"><?php esc_html_e( 'Thumbnail', 'xforwoocommerce' ); ?></label>
											<input type="hidden" class="option short" name="wcwar_qp_thumb[]" value="<?php if (isset( $warranty['thumb'] ) && $warranty['thumb'] !== '') echo esc_url( $warranty['thumb'] ); ?>" placeholder="">
											<button type="button" class="option button add_wcwar_qp_thumb"><?php esc_html_e( 'Add image', 'xforwoocommerce' ); ?></button>
										</p>
										<p class="wcwar_qp_period">
											<label for="wcwar_qp_period"><?php esc_html_e( 'Length', 'xforwoocommerce' ); ?></label>
											<input type="number" class="option short" name="wcwar_qp_period[]" value="<?php if (isset( $warranty['period'] ) && $warranty['period'] !== '') echo esc_attr( $warranty['period'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter warranty length.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_type">
											<label for="wcwar_qp_type"><?php esc_html_e( 'Length period', 'xforwoocommerce' ); ?></label>
											<select name="wcwar_qp_type[]" class="option select short">
												<option value=""><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></option>
												<option value="days" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'days') echo 'selected'; ?>><?php esc_html_e( 'Days', 'xforwoocommerce' ); ?></option>
												<option value="weeks" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'weeks') echo 'selected'; ?>><?php esc_html_e( 'Weeks', 'xforwoocommerce' ); ?></option>
												<option value="months" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'months') echo 'selected'; ?>><?php esc_html_e( 'Months', 'xforwoocommerce' ); ?></option>
												<option value="years" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'years') echo 'selected'; ?>><?php esc_html_e( 'Years', 'xforwoocommerce' ); ?></option>
												<option value="lifetime" <?php if (isset( $warranty['type'] ) && $warranty['type'] == 'lifetime') echo 'selected'; ?>><?php esc_html_e( 'Lifetime', 'xforwoocommerce' ); ?></option>
											</select>
											<em><?php esc_html_e( 'Select warranty length period.', 'xforwoocommerce' ); ?></em>
										</p>
										<p class="wcwar_qp_price">
											<label for="wcwar_qp_price"><?php esc_html_e( 'Add-on price', 'xforwoocommerce' ); ?></label>
											<input type="number" class="option short wc_input_price" name="wcwar_qp_price[]" value="<?php if (isset( $warranty['price'] ) ) echo esc_attr( $warranty['price'] ); ?>" placeholder="">
											<em><?php esc_html_e( 'Enter add-on price for this warranty.', 'xforwoocommerce' ); ?></em>
										</p>
									</div>
								</div>
							<?php
								}
							}
						?>
						</div>
						<div class="wcwar-add-warranty">
							<button type="button" class="option button add_paid_warranty"<?php echo ( !empty( $curr_disable ) ? ' disabled="' . esc_attr( $curr_disable ) . '"' : '' ); ?>><?php esc_html_e( 'Add new warranty option', 'xforwoocommerce' ); ?></button>
						</div>
					</div>

				</div>
			</td>
		</tr>
	<?php
			
		}

		function war_save_preset( $term_id, $tt_id, $taxonomy ) {

			$curr = array();

			if ( isset( $_POST['wcwar_q_type'] ) ){
				if ( $_POST['wcwar_q_type'] == 'included_warranty' ) {
					$curr['quick'] = 'included_warranty';
					$curr['included_warranty'] = array(
						'type' => ( isset( $_POST['wcwar_qi_type'] ) && $_POST['wcwar_qi_type'] !== '' ? $_POST['wcwar_qi_type'] : '' ),
						'period' => ( isset( $_POST['wcwar_qi_period'] ) && $_POST['wcwar_qi_period'] !== '' ? $_POST['wcwar_qi_period'] : '' ),
						'desc' => ( isset( $_POST['wcwar_qi_title'] ) && $_POST['wcwar_qi_title'] !== '' ? $_POST['wcwar_qi_title'] : '' ),
						'desc' => ( isset( $_POST['wcwar_qi_desc'] ) && $_POST['wcwar_qi_desc'] !== '' ? $_POST['wcwar_qi_desc'] : '' ),
						'thumb' => ( isset( $_POST['wcwar_qi_thumb'] ) && $_POST['wcwar_qi_thumb'] !== '' ? $_POST['wcwar_qi_thumb'] : '' )
					);
				}
				else if ( $_POST['wcwar_q_type'] == 'paid_warranty' ) {
					$curr['quick'] = 'paid_warranty';

					$curr_free = ( isset( $_POST['wcwar_qp_without'] ) && $_POST['wcwar_qp_without'] == 'yes' ? 'yes' : 'no' );
					$curr_prices = ( isset( $_POST['wcwar_qp_price'] ) && !empty( $_POST['wcwar_qp_price'] ) ? $_POST['wcwar_qp_price'] : array() );
					$curr_types = ( isset( $_POST['wcwar_qp_type'] ) && !empty( $_POST['wcwar_qp_type'] ) ? $_POST['wcwar_qp_type'] : array() );
					$curr_periods = ( isset( $_POST['wcwar_qp_period'] ) && !empty( $_POST['wcwar_qp_period'] ) ? $_POST['wcwar_qp_period'] : array() );
					$curr_titles = ( isset( $_POST['wcwar_qp_title'] ) && !empty( $_POST['wcwar_qp_title'] ) ? $_POST['wcwar_qp_title'] : array() );
					$curr_descs = ( isset( $_POST['wcwar_qp_desc'] ) && !empty( $_POST['wcwar_qp_desc'] ) ? $_POST['wcwar_qp_desc'] : array() );
					$curr_thumbs = ( isset( $_POST['wcwar_qp_thumb'] ) && !empty( $_POST['wcwar_qp_thumb'] ) ? $_POST['wcwar_qp_thumb'] : array() );

					$curr['paid_no_warranty'] = $curr_free;
					for ( $i = 0; $i < count( $curr_types); $i++ ) {
						if (!isset( $curr_types[$i] ) || !isset( $curr_periods[$i] ) ) continue;

						$curr['paid_warranty'][] = array(
							'price' => $curr_prices[$i],
							'type' => $curr_types[$i],
							'period' => $curr_periods[$i],
							'title' => ( isset( $curr_titles[$i] ) && $curr_titles[$i] !== '' ? $curr_titles[$i] : '' ),
							'desc' => ( isset( $curr_descs[$i] ) && $curr_descs[$i] !== '' ? $curr_descs[$i] : '' ),
							'thumb' => ( isset( $curr_thumbs[$i] ) && $curr_thumbs[$i] !== '' ? $curr_thumbs[$i] : '' )
						);
					}
				}

				update_woocommerce_term_meta( $term_id, '_wcwar_warranty', $curr );
			}

		}

		function war_admin_scripts( $hook ) {

			$screen = get_current_screen();

			wp_enqueue_style( 'wcwar-font', self::$url_path . 'assets/fonts/styles.css' );
			wp_enqueue_style( 'wcwar-css', self::$url_path . 'assets/css/admin.css' );

			$localize = array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'localization' => array(
					esc_html__( 'Add-on price', 'xforwoocommerce' ),
					esc_html__( 'Enter add-on price for this warranty.', 'xforwoocommerce' ),
					esc_html__( 'Length period', 'xforwoocommerce' ),
					esc_html__( 'Not selected', 'xforwoocommerce' ),
					esc_html__( 'Days', 'xforwoocommerce' ),
					esc_html__( 'Weeks', 'xforwoocommerce' ),
					esc_html__( 'Months', 'xforwoocommerce' ),
					esc_html__( 'Years', 'xforwoocommerce' ),
					esc_html__( 'Lifetime', 'xforwoocommerce' ),
					esc_html__( 'Select warranty length period.', 'xforwoocommerce' ),
					esc_html__( 'Length', 'xforwoocommerce' ),
					esc_html__( 'Enter warranty length.', 'xforwoocommerce' ),
					esc_html__( 'Description', 'xforwoocommerce' ),
					esc_html__( 'Enter warranty description.', 'xforwoocommerce' ),
					esc_html__( 'Thumbnail', 'xforwoocommerce' ),
					esc_html__( 'Add image', 'xforwoocommerce' ),
					esc_html__( 'Title', 'xforwoocommerce' ),
					esc_html__( 'Enter warranty title.', 'xforwoocommerce' ),
					esc_html__( 'New warranty', 'xforwoocommerce' ),
				),
			);

			if ( $screen->taxonomy == 'wcwar_warranty_pre' && 'edit-tags.php' == $hook || 'term.php' == $hook ) {
				wp_register_script( 'wcwar-manager', self::$url_path . 'assets/js/edit-preset.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), self::$version, true );
				wp_enqueue_script( 'wcwar-manager' );
				
				wp_localize_script( 'wcwar-manager', 'wcwar', $localize );
			}

			if ( $screen->post_type == 'product' && $hook == 'post.php' || $hook == 'post-new.php' ) {

				wp_register_script( 'wcwar-manager', self::$url_path . 'assets/js/product-tab.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), self::$version, true );
				wp_enqueue_script( 'wcwar-manager' );

				wp_localize_script( 'wcwar-manager', 'wcwar', $localize );
			}

			if ( ( $screen->post_type == 'wcwar_warranty_req' && $hook == 'edit.php' || $hook == 'post.php' || $hook == 'post-new.php' ) || $screen->post_type == 'shop_order' && $hook == 'post.php' ) {
				wp_register_script( 'wcwar-js', self::$url_path . 'assets/js/admin.js', array( 'jquery' ), self::$version );
				wp_enqueue_script( 'wcwar-js' );

				$script = array(
					'ajax' => admin_url( 'admin-ajax.php' ),
					'localization' => array(
						'delete' => esc_html__( 'Delete?', 'xforwoocommerce' ),
						'notselected' => esc_html__( 'Not selected.', 'xforwoocommerce' ),
						'deleted' => esc_html__( 'Deleted!', 'xforwoocommerce' ),
						'error' => esc_html__( 'Error!', 'xforwoocommerce' ),
						'load' => esc_html__( 'Load?', 'xforwoocommerce' ),
						'loaded' => esc_html__( 'Loaded!', 'xforwoocommerce' ),
						'templatename' => esc_html__( 'Template name?', 'xforwoocommerce' ),
						'missing' => esc_html__( 'Missing email or name.', 'xforwoocommerce' ),
						'sendemail' => esc_html__( 'Send Email?', 'xforwoocommerce' ),
						'saved' => esc_html__( 'Saved!', 'xforwoocommerce' ),
						'emailsent' => esc_html__( 'Email sent!', 'xforwoocommerce' ),
					)
				);

				wp_localize_script( 'wcwar-js', 'wcwar', $script );
			}

			if ( isset( $_GET['page'], $_GET['tab'] ) && $_GET['page'] == 'wc-settings' && $_GET['tab'] == 'warranties_and_returns' || isset( $_GET['page'] ) && $_GET['page'] == 'xforwoocommerce' ) {
				wp_register_script( 'wcwar-svx-admin', self::$url_path . 'assets/js/svx-admin.js', array( 'jquery' ), self::$version, true );
				wp_enqueue_script( 'wcwar-svx-admin' );
			}

		}

		function war_scripts() {

			if ( SevenVXGet()->get_option( 'wcwar_force_scripts', 'warranties_and_returns', 'yes' ) || is_product() ) {
				wp_enqueue_style( 'wcwar-product-css', self::$url_path . 'assets/css/product.css' );
				wp_register_script( 'wcwar-product-js', self::$url_path . 'assets/js/product.js', array( 'jquery' ), self::$version, true );
				wp_enqueue_script( 'wcwar-product-js' );
			}

			if ( is_account_page() || is_checkout() ) {
				wp_enqueue_style( 'wcwar-font', self::$url_path . 'assets/fonts/styles.css' );
				wp_enqueue_style( 'wcwar-myaccount-css', self::$url_path . 'assets/css/myaccount.css' );
			}

			$request_page = self::wpml_get_id( SevenVXGet()->get_option_autoload( 'war_settings_page' ) );
			if ( is_page( $request_page ) ) {
				wp_enqueue_style( 'wcwar-font', self::$url_path . 'assets/fonts/styles.css' );
				wp_enqueue_style( 'wcwar-request-css', self::$url_path . 'assets/css/request.css' );
				wp_register_script( 'wcwar-request-js', self::$url_path . 'assets/js/request.js', array( 'jquery' ), self::$version, true );
				wp_enqueue_script( 'wcwar-request-js' );
			}

			if ( is_singular( 'wcwar_warranty_req' ) ) {
				wp_enqueue_style( 'wcwar-font', self::$url_path . 'assets/fonts/styles.css' );
				wp_enqueue_style( 'wcwar-warranty-css', self::$url_path . 'assets/css/warranty.css' );
			}

		}

		function war_product_warranty_output() {
			global $product;

			$type = method_exists( $product, 'get_type' ) ? $product->get_type() : $product->product_type;
			$ids = method_exists( $product, 'get_id' ) ? $product->get_children() : $product->children;

			if ( $type == 'variable' && $ids ) {
				$ids = isset( $ids['visible'] ) ? $ids['visible'] : $ids;
				$curr_class = ' war_variable'; // OK
			}
			else {
				$ids = array( method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id );
				$curr_class = ' war_simple'; // OK
			}

			foreach ( $ids as $id ) {

				//$curr_warranty = get_post_meta( $id, '_wcwar_warranty', true );
				$curr_warranty = $this->_get_warranty( $id );

				if ( $curr_warranty['type'] == 'preset_warranty' ) {
					$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
					$curr_warranty = array_merge( $curr_warranty, $curr_preset);
				}
				else if ( $curr_warranty['type'] == 'quick_warranty' ) {

				}

				$title_tag = SevenVXGet()->get_option( 'wcwar_single_titles', 'warranties_and_returns', 'h4' );

				if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
					if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {
				?>
					<div class="war_warranty<?php echo esc_attr( $curr_class ); ?>" data-id="<?php echo esc_attr( $id ); ?>">
						<<?php echo esc_html( $title_tag ); ?>><?php esc_html_e( 'Included Warranty', 'xforwoocommerce' ); ?></<?php echo esc_html( $title_tag ); ?>>
						<p>
							<?php if ( isset( $curr_warranty['included_warranty']['thumb'] ) ) { ?>
								<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['included_warranty']['thumb'] ); ?>" />
							<?php } ?>
							<strong>
								
							<?php
								if ( $curr_warranty['included_warranty']['type'] !== 'lifetime' ) {
							?>
								<span>
									<?php echo esc_html( $curr_warranty['included_warranty']['period'] ) . ' '; ?>
								</span>
								<?php
									echo self::hlp_get_warranty_string( $curr_warranty['included_warranty']['period'], $curr_warranty['included_warranty']['type'] );
								}
								else {
									esc_html_e( 'Lifetime Warranty', 'xforwoocommerce' );
								}
							?>
								
							</strong><br/>
							<?php if ( isset( $curr_warranty['included_warranty']['desc'] ) ) { ?>
								<small><?php echo wp_kses_post( $curr_warranty['included_warranty']['desc'] ); ?></small>
							<?php } ?>
						</p>
					</div>
				<?php
					}
					else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {
				?>
					<div class="war_warranty war_paid<?php echo esc_attr( $curr_class ); ?>" data-id="<?php echo esc_attr( $id ); ?>">
						<<?php echo esc_html( $title_tag ); ?>><?php esc_html_e( 'Select Warranty Options', 'xforwoocommerce' ); ?></<?php echo esc_html( $title_tag ); ?>>
						<p>
							<select name="wcwar_pa_warranty">
								<?php if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) { ?>
									<option value="no_warranty"><?php esc_html_e( 'No warranty' ); ?></option>
								<?php } ?>
								<?php for ( $i = 0; $i < count( $curr_warranty['paid_warranty'] ); $i++ ) { ?>
									<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $curr_warranty['paid_warranty'][$i]['period'] ) . ' ' . self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$i]['period'], $curr_warranty['paid_warranty'][$i]['type'] ); ?></option>
								<?php } ?>
							</select>
						</p>
						<?php if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) { ?>
						<p class="war_option" data-selected="no_warranty">
							<strong><?php esc_html_e( 'Not selected', 'xforwoocommerce' ); ?></strong><br/>
						<small><?php esc_html_e( 'Purchase this product without any warranty options.', 'xforwoocommerce' ); ?></small>
						</p>
						<?php } ?>
						<?php for ( $i = 0; $i < count( $curr_warranty['paid_warranty'] ); $i++ ) { ?>
						<p class="war_option" data-selected="<?php echo esc_attr( $i ); ?>">
							<?php if ( isset( $curr_warranty['paid_warranty'][$i]['thumb'] ) && $curr_warranty['paid_warranty'][$i]['thumb'] !== '' ) { ?>
								<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['paid_warranty'][$i]['thumb'] ); ?>" />
							<?php } ?>
							<strong>
							<?php
								if ( $curr_warranty['paid_warranty'][$i]['type'] !== 'lifetime' ) {
							?>
								<span>
									<?php echo esc_html( $curr_warranty['paid_warranty'][$i]['period'] ) . ' '; ?>
								</span>
							<?php
									echo self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$i]['period'], $curr_warranty['paid_warranty'][$i]['type'] );
								}
								else {
									esc_html_e( 'Lifetime Warranty', 'xforwoocommerce' );
								}
							?>
							</strong><br/>
							<?php if ( isset( $curr_warranty['paid_warranty'][$i]['desc'] ) ) { ?>
								<small><?php echo wp_kses_post( $curr_warranty['paid_warranty'][$i]['desc'] ); ?></small><br/>
							<?php } ?>
							<strong>+ <?php echo wc_price( $curr_warranty['paid_warranty'][$i]['price'] ); ?></strong> 
						</p>
						<?php } ?>
					</div>
				<?php
					}
				}

				
			}

		}

		function wc_add_pa_warranty( $product_data, $product_id ) {
			global $woocommerce;

			if ( isset( $_POST['wcwar_pa_warranty'] ) && $_POST['wcwar_pa_warranty'] !== '' ) {
				$product_data['wcwar_pa_warranty'] = $_POST['wcwar_pa_warranty'];
			}
			else {
				$product_data['wcwar_pa_warranty'] = 'not_selected';
			}

			return $product_data;
		}

		function _check_warranty_override( $product_id ) {

			if ( !isset( self::$settings['overrides'] ) ) {
				self::$settings['overrides'] = SevenVXGet()->get_option( 'wcwar_overrides', 'warranties_and_returns', array() );
			}

			if ( empty( self::$settings['overrides'] ) ) {
				return false;
			}

			$over = self::$settings['overrides'];

			if ( isset( $over['product_tag'] ) && is_array( $over['product_tag'] ) ) {
				foreach( $over['product_tag'] as $k => $v ) {
					$v = is_array( $v ) ? $v : array( 'term' => $k, 'preset' => $v );
					if ( !empty( $v['term'] ) && has_term( $v['term'], 'product_tag', $product_id ) ) {
						return $v['preset'];
					}
				}
			}

			if ( isset( $over['product_cat'] ) && is_array( $over['product_cat'] ) ) {

				$term_ids = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );

				if ( $term_ids && !is_wp_error( $term_ids ) ) {
					$term_parents = get_ancestors( $term_ids[0], 'product_cat' );

					$checks = array( $term_ids[0] );
					if ( !empty( $term_parents ) ) {
						$checks = array_merge( $checks, $term_parents );
					}

					foreach( $checks as $check ) {
						if ( array_key_exists( $check, $over['product_cat'] ) ) {
							return $over['product_cat'][$check];
						}
					}
				}
			}

			return false;

		}

		function _get_default_warranty( $product_id ) {
			$check_override = $this->_check_warranty_override( $product_id );

			if ( $check_override !== false ) {
				return $check_override;
			}

			return SevenVXGet()->get_option( 'wcwar_default_warranty', 'warranties_and_returns', '' );
		}

		function _get_warranty( $product_id ) {

			$warranty = get_post_meta( $product_id, '_wcwar_warranty', true );

			if ( empty( $warranty ) ) {

				$default_warranty = $this->_get_default_warranty( $product_id );

				if ( $default_warranty == '' ) {
					$warranty = array(
						'type' => 'no_warranty',
					);
				}
				else {
					$warranty = array(
						'type' => 'preset_warranty',
						'preset' => $default_warranty,
					);
				}
			}

			return $warranty;

		}

		function war_add_product_warranty( $product_data, $cart_item_key ) {
			global $woocommerce;

			$curr_product= $product_data['data'];
			$pa_warranty = false;

			if ( isset( $product_data['wcwar_pa_warranty'] ) && $product_data['wcwar_pa_warranty'] !== 'no_warranty' ) {
				$pa_warranty = $product_data['wcwar_pa_warranty'];
			}

			$product_id = ( isset( $product_data['variation_id'] ) && $product_data['variation_id'] !== 0 ? $product_data['variation_id'] : $product_data['product_id'] );

			$curr_warranty = $this->_get_warranty( $product_id );

			if ( $curr_warranty ) {

				if ( $curr_warranty['type'] == 'preset_warranty' ) {
					$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
					$curr_warranty = array_merge( $curr_warranty, $curr_preset);
				}
				else if ( $curr_warranty['type'] == 'quick_warranty' ) {

				}

				if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
					if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {
						if ( $pa_warranty === false ) {
							
						}
						else if ( $pa_warranty == 'not_selected' ) {
							if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) {

							}
							else {
								$product_data['wcwar_pa_price'] = $curr_warranty['paid_warranty'][0]['price'];
								if ( method_exists( $product_data['data'], 'set_price' ) ) {
									$product_data['data']->set_price( $product_data['data']->get_price() + $curr_warranty['paid_warranty'][0]['price'] );
								}
								else {
									$product_data['data']->adjust_price( $curr_warranty['paid_warranty'][0]['price'] );
								}
							}
						}
						else {
							if ( method_exists( $product_data['data'], 'set_price' ) ) {
								$product_data['data']->set_price( $product_data['data']->get_price() + $curr_warranty['paid_warranty'][$pa_warranty]['price'] );
							}
							else {
								$product_data['data']->adjust_price( $curr_warranty['paid_warranty'][$pa_warranty]['price'] );
							}
							$product_data['wcwar_pa_price'] = $curr_warranty['paid_warranty'][$product_data['wcwar_pa_warranty']]['price'];
						}
					}
				}

			}

			return $product_data;

		}

		function wc_get_cart_item_from_session( $session_data, $values, $key ) {

			if ( isset( $values['wcwar_pa_warranty'] ) ) {

				$session_data['wcwar_pa_warranty'] = $values['wcwar_pa_warranty'];

				$product_id = ( isset( $session_data['variation_id'] ) && $session_data['variation_id'] !== '' ? $session_data['variation_id'] : $session_data['product_id'] );

				$curr_warranty = get_post_meta( $product_id, '_wcwar_warranty', true );

				if ( $curr_warranty ) {

					if ( $curr_warranty['type'] == 'preset_warranty' ) {
						$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
						$curr_warranty = array_merge( $curr_warranty, $curr_preset);
					}
					else if ( $curr_warranty['type'] == 'quick_warranty' ) {

					}

					if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
						if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {
							if ( $session_data['wcwar_pa_warranty'] == '' || $session_data['wcwar_pa_warranty'] == 'no_warranty' ) {
								
							}
							else if ( $session_data['wcwar_pa_warranty'] == '' || $session_data['wcwar_pa_warranty'] == 'not_selected' ) {
								if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) {

								}
								else {
									$session_data['wcwar_pa_price'] = $curr_warranty['paid_warranty'][0]['price'];
								}
							}
							else {
								$session_data['wcwar_pa_price'] = $curr_warranty['paid_warranty'][$session_data['wcwar_pa_warranty']]['price'];
							}

						}
					}

				}

				$session_data = self::war_add_product_warranty( $session_data, $key );
			}

			return $session_data;

		}

		function war_order( $order ) {

			if ( sizeof( $order->get_items() ) > 0 ) {

				$curr_multi_req = SevenVXGet()->get_option( 'wcwar_enable_multi_requests', 'warranties_and_returns', 'no' );
				$curr_returns = SevenVXGet()->get_option( 'wcwar_returns_no_warranty', 'warranties_and_returns', 'no' );
				$war_pageid = self::wpml_get_id( SevenVXGet()->get_option_autoload( 'war_settings_page' ) );

				$id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

				?>
				<div class="wcwar_warranty war_order">
				<h2><?php esc_html_e( 'Available Warranties for this Order', 'xforwoocommerce' ); ?></h2>
				<?php
					if ( !in_array( $order->get_status(), apply_filters( 'wcwar_warranty_order_status', array( 'completed' ), $order ) ) ) {
				?>
				<p class="wcwar_status blue">
					<span class="wcwar_info_icon"><i class="wcwar-info"></i></span> <?php esc_html_e( 'Your warranties will be available once your order is complete.', 'xforwoocommerce' ); ?>
				</p>
				<?php
					$curr_complete = get_post_meta( $id, '_ordered_date', true );
					$warranty_status_hold[] = '<p class="wcwar_status blue"><span class="wcwar_info_icon"><i class="wcwar-info"></i></span>' . esc_html__( 'Pending order', 'xforwoocommerce' ) . '</p>';

					$curr_notallowed = true;
				}
				else {
					$curr_complete = get_post_meta( $id, '_completed_date', true );

					$curr_notallowed = false;
				}
				?>
				<table class="shop_table wcwarranty">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Product', 'xforwoocommerce' ); ?></th>
							<th><?php esc_html_e( 'Warranty', 'xforwoocommerce' ); ?></th>
							<th><?php esc_html_e( 'Status', 'xforwoocommerce' ); ?></th>
							<th><?php esc_html_e( 'Action', 'xforwoocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
				<?php

					$i=0;
					foreach( $order->get_items() as $key => $item ) {

						$i++;
						$request_button = true;
						$curr_link = null;
						$addon_msg = null;
						$warranty_status = array();

						if ( isset( $warranty_status_hold ) ) {
							$warranty_status = $warranty_status + $warranty_status_hold;
						}
					?>
						<tr>
							<td class="wcwar_myaccount_product"><span class="wcwar_item"><?php echo esc_html( $item['name'] ); ?></span></td>
							<td class="wcwar_myaccount_warranty">
								<p class="wcwar_description">
					<?php
						$curr_args = array(
							'post_type' => 'wcwar_warranty_req',
							'orderby'   => 'date',
							'order'     => 'ASC',
							'post_status' => 'any',
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => '_wcwar_warranty_order_id',
									'value' => $id,
								),
								array(
									'key' => '_wcwar_warranty_product_id',
									'value' => $key,
								)
							)
						 );
						$curr_req = get_posts( $curr_args );

						$curr_args = array(
							'order_id' => $id,
							'item_id' => $key,
							'multiple' => $item['qty'] > 1 ? true : false
						);

						$curr_request = esc_url( add_query_arg( $curr_args, get_permalink( $war_pageid ) ) ); // OK

						$curr_status = self::hlp_valid_warranty( $curr_complete, $item );

						$requested = false;

						if ( !empty( $curr_req ) ) {
		
							$i=0;

							$curr_addon = '';
							$req_count = count( $curr_req );

							foreach ( $curr_req as $req ) {

								$i++;

								$curr_numid = $curr_multi_req == 'yes' && $req_count > 1 ? ' #' . $i . ' ' : ''; // OK
								$curr_reqId = $req->ID;

								$curr_terms = get_the_terms( $curr_reqId, 'wcwar_warranty' );
								$curr_terms = reset( $curr_terms );

								$return = get_post_meta( $curr_reqId, '_wcwar_warranty_return_request', true );
								if ( $return !== '' && $return == 'return' ) {
									if ( !empty ( $curr_terms) && $curr_terms->slug == 'completed' ) {
										$curr_addon .= '<span class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span><span>' . esc_html__( 'This item has been returned to the store.', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '';
									}
									else if ( $req->post_status == 'pending' ) {
										$curr_addon .= '<span class="wcwar_status blue"><span class="wcwar_info_icon"><i class="wcwar-info"></i></span><span>' . esc_html__( 'Pending review', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '';
									}
									else if ( !empty ( $curr_terms) && $curr_terms->slug == 'rejected' ) {
										$curr_addon .= '<span class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span><span>' . esc_html__( 'Return rejected', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '<a href="' . get_permalink( $curr_reqId ) . '" class="wcwar_request_button button">' . esc_html__( 'View Request', 'xforwoocommerce' ) . '</a>';
									}
									else {
										$curr_addon .= '<span class="wcwar_status yellow"><span class="wcwar_info_icon"><i class="wcwar-info"></i></span><span>' . esc_html__( 'Return requested', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '<a href="' . get_permalink( $curr_reqId ) . '" class="wcwar_request_button button">' . esc_html__( 'View Request', 'xforwoocommerce' ) . '</a>';
									}
									$requested = true;
									$hlp_status = 'return-nowar';
								}
								else {
									if ( !empty ( $curr_terms ) && $curr_terms->slug == 'completed' ) {
										$curr_addon .= '<span class="wcwar_status blue"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span><span>' . esc_html__( 'Completed', 'xforwoocommerce' ) . $curr_numid . '</span></span>';
										$curr_link[] = '<a href="' . get_permalink( $curr_reqId ) . '" class="wcwar_request_button button">' . esc_html__( 'View Request', 'xforwoocommerce' ) . $curr_numid . '</a>';
									}
									else if( !empty ( $curr_terms ) && $curr_terms->slug == 'rejected' ) {
										$curr_addon .= '<span class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span><span>' . esc_html__( 'Warranty rejected', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '<a href="' . get_permalink( $curr_reqId ) . '" class="wcwar_request_button button">' . esc_html__( 'View Request', 'xforwoocommerce' ) . $curr_numid . '</a>';
									}
									else if ( !empty ( $curr_terms ) && $req->post_status !== 'pending' ) {
										$curr_addon .= '<span class="wcwar_status yellow"><span class="wcwar_info_icon"><i class="wcwar-info"></i></span><span>' . esc_html__( 'Requested', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '<a href="' . get_permalink( $curr_reqId ) . '" class="wcwar_request_button button">' . esc_html__( 'View Request', 'xforwoocommerce' ) . $curr_numid . '</a>';
										$requested = true;
									}
									else {
										$curr_addon .= '<span class="wcwar_status blue"><span class="wcwar_info_icon"><i class="wcwar-info"></i></span><span>' . esc_html__( 'Pending review', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '';
										$requested = true;
									}
								}

								$curr_addon .= '<br/>';

							}

							$warranty_status[] = $curr_addon;

						}

						if ( $curr_notallowed === false && $curr_status === 'nowar' && $curr_returns == 'yes' && empty( $curr_req ) ) {

							$addon_msg = esc_html__( 'This item was sold without warranty', 'xforwoocommerce' );

							if ( self::hlp_valid_return( $curr_complete, $item ) === true ) {
								$addon_msg .= esc_html__( ', however you can still return it to the store', 'xforwoocommerce' );
								$warranty_status[] = '<p class="wcwar_status green"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span>' . esc_html__( 'Return available', 'xforwoocommerce' ) . '</p>';
								$curr_link[] = '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request_button button">' . esc_html__( 'Request Return', 'xforwoocommerce' ) . '</a>';
							}
							else {
								$warranty_status[] = '<p class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span>' . esc_html__( 'No warranty', 'xforwoocommerce' ) . '</p>';
							}

							$request_button = false;

						}
						else if ( $curr_notallowed === false && $curr_status === 'nowar' && empty( $curr_req ) ) {
							$addon_msg = esc_html__( 'This item was sold without warranty', 'xforwoocommerce' );
							$warranty_status[] = '<p class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span>' . esc_html__( 'No warranty', 'xforwoocommerce' ) . '</p>';
							$request_button = false;
						}
						else if ( $curr_notallowed === false && $curr_status && empty( $curr_req ) ) {
							$addon_msg = esc_html__( 'Warranty for this item has expired', 'xforwoocommerce' );
							$warranty_status[] = '<p class="wcwar_status grey"><span class="wcwar_info_icon"><i class="wcwar-cross"></i></span>' . esc_html__( 'Expired', 'xforwoocommerce' ) . '</p>';
							$request_button = false;
						}
						if ( $curr_notallowed === false && $curr_status && $request_button === true ) {
							$request_button = false;
						}

						if ( isset( $item['wcwar_warranty'] ) ) {

							$curr_warranty = json_decode( $item['wcwar_warranty'], true );

							if ( $curr_warranty['type'] == 'preset_warranty' ) {
								$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
								$curr_warranty = array_merge( $curr_preset, $curr_warranty);
							}
							else if ( $curr_warranty['type'] == 'quick_warranty' ) {

							}

							if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {

								if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {

					?>
								<span class="wcwar_block_wrap">
									<?php if ( isset( $curr_warranty['included_warranty']['thumb'] ) ) { ?>
										<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['included_warranty']['thumb'] ); ?>" />
									<?php } ?>
									<span class="wcwar_warranty_period">
										<span>
											<?php echo esc_html__( 'Included Warranty', 'xforwoocommerce' ) . ' - ' . esc_html( $curr_warranty['included_warranty']['period'] ) . ' ' . self::hlp_get_warranty_string( $curr_warranty['included_warranty']['period'], $curr_warranty['included_warranty']['type'] ); ?>
										</span>
										<?php
											if ( isset( $curr_warranty['included_warranty']['desc'] ) ) {
												echo '<small>' . wp_kses_post( $curr_warranty['included_warranty']['desc'] ) . '</small>';
											}
										?>
									</span>
								</span>
								<?php
									if ( $curr_notallowed === false && !isset( $warranty_status ) && $request_button === true ) {

										$warranty_status[] = '<span class="wcwar_status green"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span><span>' . esc_html__( 'Valid', 'xforwoocommerce' ) . '</span></span>';

									}
									else if ( $curr_notallowed === false && $requested === false && $curr_multi_req == 'yes' && $request_button === true ) {
										$warranty_status[] = '<span class="wcwar_status green"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span><span>' . esc_html__( 'Valid', 'xforwoocommerce' ) . '</span></span>';
										$curr_link[] = '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request_button button">' . esc_html__( 'Request Warranty', 'xforwoocommerce' ) . '</a>';
									}

								}
								else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {

									$curr = isset( $curr_warranty['paid_warranty']['selected'] ) ? $curr_warranty['paid_warranty']['selected'] : 'no_warranty';

									if ( $curr !== 'no_warranty' ) {
							?>
										<span class="wcwar_block_wrap">
											<?php if ( isset( $curr_warranty['paid_warranty'][$curr]['thumb'] ) && $curr_warranty['paid_warranty'][$curr]['thumb'] !== '' ) { ?>
												<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['paid_warranty'][$curr]['thumb'] ); ?>" />
											<?php } ?>
											<span class="wcwar_warranty_period">
												<span>
													<?php echo esc_html__( 'Paid Warranty', 'xforwoocommerce' ) . ' (+' . wc_price( $curr_warranty['paid_warranty'][$curr]['price'] ) . ')' . ' - ' . esc_html( $curr_warranty['paid_warranty'][$curr]['period'] ) . ' ' . self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$curr]['period'], $curr_warranty['paid_warranty'][$curr]['type'] ); ?>
												</span>
												<?php
													if ( isset( $curr_warranty['paid_warranty'][$curr]['desc'] ) ) {
														echo '<small>' . wp_kses_post( $curr_warranty['paid_warranty'][$curr]['desc'] ) . '</small>';
													}
												?>
											</span>
										</span>
										<?php
											if ( $curr_notallowed === false && !isset( $warranty_status ) && $request_button === true ) {
												$warranty_status[] = '<span class="wcwar_status green"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span><span>' . esc_html__( 'Valid', 'xforwoocommerce' ) . '</span></span>';
											}
											else if ( $curr_notallowed === false && $requested === false && $curr_multi_req == 'yes' && $request_button === true ) {
												$warranty_status[] = '<span class="wcwar_status green"><span class="wcwar_info_icon"><i class="wcwar-check"></i></span><span>' . esc_html__( 'Valid', 'xforwoocommerce' ) . '</span></span>';
												$curr_link[] = '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request_button button">' . esc_html__( 'Request Warranty', 'xforwoocommerce' ) . '</a>';
											}
									}
									else if ( isset( $hlp_status ) || !isset( $curr_warranty['paid_warranty']['selected'] ) ) {
										esc_html_e( 'This item was sold without warranty', 'xforwoocommerce' );
									}
								}
							}
							else {
								esc_html_e( 'This item was sold without warranty', 'xforwoocommerce' );
							}
						}
						else {
							esc_html_e( 'This item was sold without warranty', 'xforwoocommerce' );
						}

								if ( isset( $addon_msg ) ) {
								?>
									<small class="wcwar_addonmsg">* <?php echo wp_kses_post( $addon_msg ); // OK ?></small>
								<?php
								}

						?>
								</p>
							</td>
							<td class="wcwar_myaccount_status">
							<?php
								if ( is_array( $warranty_status ) ) {
									foreach ( $warranty_status as $wcstatus ) {
										echo wp_kses_post( $wcstatus );
									}
								}
								else {
									echo wp_kses_post( $warranty_status );
								}
							?>
							</td>
							<td class="wcwar_myaccount_request">
							<?php
								if ( $curr_notallowed === false && $request_button === true && !isset( $curr_link ) ) {
									echo '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request_button button">' . esc_html__( 'Request Warranty', 'xforwoocommerce' ) . '</a>';
								}
								else {
									if ( is_array( $curr_link ) ) {
										foreach ( $curr_link as $clink ) {
											echo wp_kses_post( $clink );
										}
									}
									else {
										echo wp_kses_post( $curr_link );
									}
								}
							?>
							</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<?php
			}
		}

		function war_add_warranty_meta( $item_id, $item, $order_id ) {

			if ( is_object( $item ) ) {
				if ( ! property_exists( $item, 'legacy_values' ) ) {
					return;
				}
				$values = $item->legacy_values;
			}
			else {
				$values = $item;
			}


			$curr_product= $values['data'];

			$product_id = method_exists( $curr_product, 'get_id' ) ? absint( $curr_product->get_id() ) : ( absint( $product->variation_id ) !== 0 ? $product->variation_id : $product->id );

			//$curr_warranty = get_post_meta( $product_id, '_wcwar_warranty', true );
			$curr_warranty = $this->_get_warranty( $product_id );

			if ( $curr_warranty['type'] == 'preset_warranty' ) {
				$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
				$curr_warranty = array_merge( $curr_warranty, $curr_preset);
			}
			else if ( $curr_warranty['type'] == 'quick_warranty' ) {

			}

			if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
				if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {

				}
				else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {
					if ( isset( $values['wcwar_pa_warranty'] ) && $values['wcwar_pa_warranty'] == 'not_selected') {
						if ( isset( $curr_warranty['paid_no_warranty'] ) && $curr_warranty['paid_no_warranty'] == 'yes' ) {

						}
						else {
							$curr_warranty['paid_warranty']['selected'] = 0;
						}
					}
					else if ( isset( $values['wcwar_pa_warranty'] ) ) {
						$curr_warranty['paid_warranty']['selected'] = $values['wcwar_pa_warranty'];
					}
				}
			}

			wc_add_order_item_meta( $item_id, '_wcwar_warranty', json_encode( $curr_warranty ) );

		}

		function war_hide_core_fileds( $array ) {
			$array[] = '_wcwar_warranty';
			return $array;
		}
		
		function war_items_warranty_column_header() {
			echo '<th class="wcwar_warranty_item">' . esc_html__( 'Warranty Type', 'xforwoocommerce' ) . '</th>';
			echo '<th class="wcwar_warranty_status">' . esc_html__( 'Warranty Status', 'xforwoocommerce' ) . '</th>';
		}

		function war_items_warranty_column( $curr_product, $curr_item, $curr_item_id ) {
			if ( $curr_product == NULL ) {
				return;
			}

			$curr_order = ( isset( get_current_screen()->parent_file ) && get_current_screen()->parent_file !== 'edit.php?post_type=wcwar_warranty_req' ? get_the_ID() : get_post_meta( get_the_ID(), '_wcwar_warranty_order_id', true ) );
			if ( $curr_order !== false ) {

				$curr_new_set = true;

				$curr_complete = get_post_meta( $curr_order, '_completed_date', true );

				$curr_fields = array( 'warranty', 'status' );

				$status = self::hlp_valid_warranty( $curr_complete, $curr_item);

				if ( $status && $status === 'nowar' ) {
					$curr_fields['warranty'] = esc_html__( 'No Warranty', 'xforwoocommerce' );
					$curr_fields['status'] = '<span class="wcwar_badge warranty_nowar">' . esc_html__( 'No Warranty', 'xforwoocommerce' ) . '</span>';
				}
				else if ( $status ) {
					$curr_fields['status'] = '<span class="wcwar_badge warranty_rejected">' . esc_html__( 'Warranty Expired', 'xforwoocommerce' ) . '</span>';
				}

				$curr_args = array(
					'post_type' => 'wcwar_warranty_req',
					'post_status' => 'any',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => '_wcwar_warranty_order_id',
							'value' => $curr_order,
						),
						array(
							'key' => '_wcwar_warranty_product_id',
							'value' => $curr_item_id,
						)
					)
				);
				$curr_req = get_posts( $curr_args );

				if ( !empty( $curr_req) ) {
					$get_terms = get_the_terms( $curr_req[0]->ID, 'wcwar_warranty' );
					if ( !is_array( $get_terms) ) {
						$curr_terms = (object) array( 'slug' => 'new' );
					}
					else {
						$curr_terms = reset( $get_terms );
					}

					if ( !empty ( $curr_terms) ) {

						$return = get_post_meta( $curr_req[0]->ID, '_wcwar_warranty_return_request', true );
						if ( $return !== '' && $return == 'return' ) {
							$curr_fields['status'] = '<span class="wcwar_badge warranty_only">' . esc_html__( 'Return', 'xforwoocommerce' ) . '</span>';
						}
						else {
							$curr_fields['status'] = '<span class="wcwar_badge return_only">' . esc_html__( 'Warranty', 'xforwoocommerce' ) . '</span>';
						}

						$switch_slug = $curr_terms->slug;
						if ( $switch_slug == 'new' ) {
							$curr_fields['status'] .= '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_new" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'New', 'xforwoocommerce' ) . '</a>';
							$curr_fields['status'] .= '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button view_warranty" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'processing' ) {
							$curr_fields['status'] .= '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_processing" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Processing', 'xforwoocommerce' ) . '</a>';
							$curr_fields['status'] .= '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button view_warranty" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'completed' ) {
							$curr_fields['status'] .= '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_completed" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Completed', 'xforwoocommerce' ) . '</a>';
							$curr_fields['status'] .= '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button view_warranty" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'rejected' ) {
							$curr_fields['status'] .= '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_rejected" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Rejected', 'xforwoocommerce' ) . '</a>';
							$curr_fields['status'] .= '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button view_warranty" title="' . esc_attr__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
					}
					$curr_fields['status'] .= '<a href="#" class="wcwar_badge warranty_button warranty_change_status" data-id="' . $curr_req[0]->ID . '" title="' . esc_attr__( 'Change Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-change"></i></a>';
				}

				if ( isset( $curr_item['wcwar_warranty'] ) ) {

					$curr_warranty = json_decode( $curr_item['wcwar_warranty'], true );

					if ( $curr_warranty['type'] == 'preset_warranty' ) {
						$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
						$curr_warranty = array_merge( $curr_preset, $curr_warranty);
					}
					else if ( $curr_warranty['type'] == 'quick_warranty' ) {

					}
					
				}

				$curr_parent_args = array(
					'post_type' => 'wcwar_warranty_req',
					'post_status' => 'any',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => '_wcwar_warranty_order_id',
							'value' => $curr_order,
						),
						array(
							'key' => '_wcwar_warranty_product_id',
							'value' => '-1',
						)
					)
				);
				$curr_parent_req = get_posts( $curr_parent_args );

				if ( !empty( $curr_parent_req ) ) {
					$curr_parent = '&parent_id=' . esc_attr( $curr_parent_req[0]->ID );
				}
				else {
					$curr_parent = '';
				}

				$admin_url = esc_url( admin_url( 'post-new.php?post_type=wcwar_warranty_req&order_id=' . esc_attr( $curr_order ) . esc_attr( $curr_parent ) . '&item_id=' . esc_attr( $curr_item_id ) ) . '&post_title=' . esc_html__( 'Request for Order', 'xforwoocommerce' ) . ' %23' . esc_attr( $curr_order ) . ' - ' . esc_html__( 'Item', 'xforwoocommerce' ) . ' %23' . esc_attr( $curr_item_id ) );

				if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
					$curr_new_set = false;
					if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {
						$curr_fields['warranty'] = esc_html( $curr_warranty['included_warranty']['period'] ) . ' ' . self::hlp_get_warranty_string( $curr_warranty['included_warranty']['period'], $curr_warranty['included_warranty']['type'] ) . ' ' . esc_html__( 'included warranty', 'xforwoocommerce' );
						if ( !isset( $curr_fields['status'] ) ) {
							$curr_fields['status'] = '<a href="' . esc_url( $admin_url ) . '" class="wcwar_badge in_warranty" title="' . esc_html__( 'Create Warranty Request for this Item', 'xforwoocommerce' ) . '">' . esc_html__( 'In Warranty', 'xforwoocommerce' ) . '</a>';
							$curr_fields['status'] .= '<a href="' . esc_url( $admin_url ) . '" class="wcwar_badge warranty_button wcwar_create" data-ids="' . esc_attr( $curr_order ) . '|' . esc_attr( $curr_item_id ) . '" title="' . esc_html__( 'Create Warranty Request for this Item', 'xforwoocommerce' ) . '"><i class="wcwar-new"></i></a>';
						}
					}
					else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) && isset( $curr_warranty['paid_warranty']['selected'] ) ) {
						$curr = $curr_warranty['paid_warranty']['selected'];
						
						if ( $curr !== 'no_warranty' ) {

							$curr_fields['warranty'] = esc_html( $curr_warranty['paid_warranty'][$curr]['period'] ) . ' ' . self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$curr]['period'], $curr_warranty['paid_warranty'][$curr]['type'] ) . ' ' . esc_html__( 'paid warranty', 'xforwoocommerce' ) . ' <small>(+ ' . wc_price( $curr_warranty['paid_warranty'][$curr]['price'] ) . ')</small>';

							if ( !isset( $curr_fields['status'] ) ) {
								$curr_fields['status'] = '<a href="' . esc_url( $admin_url ) . '" class="wcwar_badge in_warranty" title="' . esc_html__( 'Create Warranty Request for this Item', 'xforwoocommerce' ) . '">' . esc_html__( 'In Warranty', 'xforwoocommerce' ) . '</a>';
								$curr_fields['status'] .= '<a href="' . esc_url( $admin_url ) . '" class="wcwar_badge warranty_button wcwar_create" data-ids="' . esc_attr( $curr_order ) . '|' . esc_attr( $curr_item_id ) . '" title="' . esc_html__( 'Create Warranty Request for this Item', 'xforwoocommerce' ) . '"><i class="wcwar-new"></i></a>';
							}
						}
					}
				}

				$curr_admin_mod = SevenVXGet()->get_option('wcwar_enable_multi_requests', 'warranties_and_returns', 'yes' );

				if ( $curr_admin_mod == 'yes' && $curr_new_set ) {
					$curr_fields['status'] .= '<a href="' . esc_url( $admin_url ) . '" class="wcwar_badge warranty_button wcwar_create" data-ids="' . esc_attr( $curr_order ) . '|' . esc_attr( $curr_item_id ) . '" title="' . esc_html__( 'Create Warranty Request for this Item', 'xforwoocommerce' ) . '"><i class="wcwar-new"></i></a>';
				}
				
			}
			else {
				$curr_fields['warranty'] = esc_html__( 'Please refresh', 'xforwoocommerce' ); // OK
				$curr_fields['status'] = esc_html__( 'Please refresh', 'xforwoocommerce' ); // OK
			}
	?>
			<td class="wcwar_warranty wcwar_warranty_item">
	<?php
				echo '<span class="wcwar_badge_war">' . $curr_fields['warranty'] . '</span>';
	?>
			</td>
			<td class="wcwar_warranty wcwar_warranty_status">
	<?php
				echo wp_kses_post( $curr_fields['status'] );
	?>
			</td>
	<?php
		}

		function war_request_warranty_column_header( $columns ) {

			$columns['status'] = esc_html__( 'Request Status', 'xforwoocommerce' );
			return $columns;

		}

		function war_request_warranty_column( $column, $post_id ) {

			if ( $column == 'status' ) {
				$get_terms = get_the_terms( $post_id, 'wcwar_warranty' );
				if ( !is_array( $get_terms ) ) {
					$curr_terms = (object) array( 'slug' => 'new' );
				}
				else {
					$curr_terms = reset( $get_terms );
				}
				if ( !empty( $curr_terms ) ) {
					echo '<span class="wcwar_warranty_status">';
					$return = get_post_meta( $post_id, '_wcwar_warranty_return_request', true );
					if ( $return !== '' && $return == 'return' ) {
						echo '<span class="wcwar_badge warranty_only">' . esc_html__( 'Return', 'xforwoocommerce' ) . '</span>';
					}
					else {
						echo '<span class="wcwar_badge return_only">' . esc_html__( 'Warranty', 'xforwoocommerce' ) . '</span>';
					}
					$switch_slug = $curr_terms->slug;
					if ( $switch_slug == 'new' ) {
						echo '<span class="wcwar_badge wcwar_change warranty_new">' . esc_html__( 'New', 'xforwoocommerce' ) . '</span>';
					}
					else if ( $switch_slug == 'processing' ) {
						echo '<span class="wcwar_badge wcwar_change warranty_processing">' . esc_html__( 'Processing', 'xforwoocommerce' ) . '</span>';
					}
					else if ( $switch_slug == 'completed' ) {
						echo '<span class="wcwar_badge wcwar_change warranty_completed">' . esc_html__( 'Completed', 'xforwoocommerce' ) . '</span>';
					}
					else if ( $switch_slug == 'rejected' ) {
						echo '<span class="wcwar_badge wcwar_change warranty_rejected">' . esc_html__( 'Rejected', 'xforwoocommerce' ) . '</span>';
					}

					echo '<a href="' . get_permalink( $post_id ) . '" class="wcwar_badge warranty_button view_warranty" title="' . esc_html__( 'View Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
					echo '<a href="#" class="wcwar_badge warranty_button warranty_change_status" data-id="' . esc_attr( $post_id ) . '" title="' . esc_html__( 'Change Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-change"></i></a>';
					echo '</span>';
				}

			}

		}

		function hlp_request_order( $query ){
			if( !is_admin() ) {
				return;
			}

			global $pagenow;

			if( 'edit.php' == $pagenow && isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'wcwar_warranty_req' && !isset( $_GET['orderby'] ) ) {
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
			}
		}

		function scr_view_request( $single_template ) {
			global $post;
			if ( $post->post_type == 'wcwar_warranty_req' ) {
				if ( SevenVXGet()->get_option( 'wcwar_single_mode', 'warranties_and_returns', 'new' ) == 'old' ) {
					$single_template = plugin_dir_path( __FILE__ ) . 'templates/content-request-old.php';
				}
				else{
					$single_template = plugin_dir_path( __FILE__ ) . 'templates/content-request.php';
				}
				return $single_template;
			}
			return $single_template;
		}

		function scr_comments( $comments_template ) {
			global $post;
			if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
				return;
			}
			if( $post->post_type == 'wcwar_warranty_req' ) {
				$comments_template = plugin_dir_path( __FILE__ ) . 'templates/comments-request.php';
				return $comments_template;
			}
			return $comments_template;
		}

		function hlp_get_warranty_string( $n, $t ) {
			switch ( $t ) {
				case 'days' :
					$out = _n( 'Day', 'Days', $n, 'xforwoocommerce' );
				break;
				case 'weeks' :
					$out = _n( 'Week', 'Weeks', $n, 'xforwoocommerce' );
				break;
				case 'months' :
					$out = _n( 'Month', 'Months', $n, 'xforwoocommerce' );
				break;
				case 'years' :
					$out = _n( 'Year', 'Years', $n, 'xforwoocommerce' );
				break;
				case 'lifetime' :
					$out = esc_html__( 'Lifetime', 'xforwoocommerce' );
				break;
				default :
					$out = '';
				break;
			}
			return $out;
		}

		function hlp_valid_warranty( $curr_complete, $item ) {

			if ( !isset( $item['wcwar_warranty'] ) ) {
				return true;
			}

			$curr_warranty = json_decode( $item['wcwar_warranty'], true );

			if ( $curr_warranty['type'] == 'preset_warranty' ) {
				$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true) ;
				$curr_warranty = array_merge( $curr_preset, $curr_warranty );
			}
			else if ( $curr_warranty['type'] == 'quick_warranty' ) {

			}

			if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
				if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {

					if ( $curr_warranty['included_warranty']['type'] !== 'lifetime' ) {
						$curr_period = $curr_warranty['included_warranty']['period'];
						$curr_period .= ' ' . self::hlp_get_warranty_string( $curr_warranty['included_warranty']['period'], $curr_warranty['included_warranty']['type'] );
					}
					else {
						$curr_period = 'lifetime';
					}

				}
				else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {

					$curr = isset( $curr_warranty['paid_warranty']['selected'] ) ? $curr_warranty['paid_warranty']['selected'] : 'no_warranty';

					if ( $curr !== 'no_warranty' ) {
						if ( isset( $curr_warranty['paid_warranty'][$curr]['type'] ) && $curr_warranty['paid_warranty'][$curr]['type'] !== 'lifetime' ) {
							$curr_period = $curr_warranty['paid_warranty'][$curr]['period'];
							$curr_period .= ' ' . self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$curr]['period'], $curr_warranty['paid_warranty'][$curr]['type'] );
						}
						else {
							$curr_period = 'lifetime';
						}
					}

				}
			}

			if ( !isset( $curr_period ) ) {
				return 'nowar';
			}

			if ( $curr_period == 'lifetime' ) {
				return false;
			}

			$curr_valid = strtotime( $curr_complete .' + ' . $curr_period );

			$curr_now = current_time( 'timestamp' );

			if ( $curr_valid && $curr_now > $curr_valid ) {
				return true;
			}

			return false;
		}

		function hlp_valid_return( $curr_complete, $item ) {

			$curr_returns = SevenVXGet()->get_option( 'wcwar_enable_returns', 'warranties_and_returns', 'no' );
			
			if ( $curr_returns == 'yes' ) {

				$curr_returns = SevenVXGet()->get_option( 'wcwar_returns_period', 'warranties_and_returns', '0' );

				if ( $curr_returns == '0' ) {
					return true;
				}

				$curr_period = $curr_returns . ' ' . self::hlp_get_warranty_string( $curr_returns, 'days' );

				$curr_valid = strtotime( $curr_complete .' + ' . $curr_period );

				$curr_now = current_time( 'timestamp' );

				if ( $curr_valid && $curr_now < $curr_valid ) {
					return true;
				}
			}

			return false;
		}

		function hlp_recursive_array_search( $needle, $haystack ) {
			foreach( $haystack as $key => $value ) {
				$current_key = $key;
				if( $needle === $value OR ( is_array( $value ) && self::hlp_recursive_array_search( $needle, $value ) !== false )) {
					return $current_key;
				}
			}
			return false;
		}

		function wc_add_screen_ids( $ids ) {
			$ids[] = 'edit-wcwar_warranty_req';
			$ids[] = 'wcwar_warranty_req';
			return $ids;
		}

		function war_register_request_metabox() {
			add_meta_box(
				'war-request',
				esc_html__( 'Warranties and Returns - Request Details', 'xforwoocommerce' ),
				array(&$this, 'war_request_metabox'),
				'wcwar_warranty_req',
				'normal',
				'high'
			);
		}

		function war_request_metabox( $object, $box ) {

			$curr_order = ( isset( $_GET['order_id'] ) ? $_GET['order_id'] : esc_attr( get_post_meta( $object->ID, '_wcwar_warranty_order_id', true ) ) );
			$curr_item = ( isset( $_GET['item_id'] ) ? $_GET['item_id'] : esc_attr( get_post_meta( $object->ID, '_wcwar_warranty_product_id', true ) ) );

			if ( $curr_order !== '' && $curr_item !== '' ) :

			if ( $object->post_parent !== '0' || $curr_item == '-1' ) {
				$parent = $object->post_parent;
				$curr_parent_order = ( $object->post_parent == '0' ? $object->ID : $object->post_parent );
				$str_type = '<span class="wcwar_badge warranty_type_multi">' . esc_html__( 'Multiple Item Order', 'xforwoocommerce' ) . '</span>'; // OK
			}
			else {
				$curr_parent_order = $object->ID;
				$str_type = '<span class="wcwar_badge warranty_type_single">' . esc_html__( 'Single Item Order', 'xforwoocommerce' ) . '</span>'; // OK
			}

			$order = wc_get_order( $curr_order );

			$id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

			$metadata = function_exists( 'wc_get_order_item_meta' ) ? wc_get_order_item_meta( $curr_order, 'woocommerce_hidden_order_itemmeta' ) : $order->has_meta( $curr_item );

			$curr_complete = get_post_meta( $id, '_completed_date', true );

			$get_terms = get_the_terms( $object->ID, 'wcwar_warranty' );
			if ( !is_array( $get_terms) ) {
				$curr_selected_term = (object) array( 'slug' => 'new' );
			}
			else {
				$curr_selected_term = reset( $get_terms );
			}

			$curr_selected = ( empty( $curr_selected_term ) ? 'new' : $curr_selected_term->slug );
	?>
		<div id="woocommerce-order-items">
			<div class="war_warranty woocommerce_order_items_wrapper">
				<input type="hidden" name="wcwar_warranty_nonce" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) . $object->ID ); ?>" />
				<p class="wcwar_warranty_details">
					<span class="wcwar_meta_order"><i class="wcwar-order"></i> <?php esc_html_e( 'Order ', 'xforwoocommerce' ); ?> #<?php echo absint( $curr_order ); ?> - <?php echo wp_kses_post( $str_type ); ?><a href="<?php echo esc_url( admin_url( 'post.php?post=' . absint( $curr_order ) . '&action=edit' ) ); ?>" class="wcwar_badge warranty_view_order" title="<?php esc_html_e( 'Manage Order', 'xforwoocommerce' ); ?>"><?php esc_html_e( 'Manage Order ', 'xforwoocommerce' ); ?></a></span>
				</p>
				<p class="wcwar_warranty_status">
					<span class="wcwar_badge_parent"><i class="wcwar-icon"></i> <?php echo ( isset( $parent ) ? esc_html__( 'Parent', 'xforwoocommerce' ) . ' ' : '' ) . esc_html__( 'Request Status', 'xforwoocommerce' ); ?>
					<?php
						$get_terms = get_the_terms( $curr_parent_order, 'wcwar_warranty' );
						if ( !is_array( $get_terms) ) {
							$curr_terms = (object) array( 'slug' => 'new' );
						}
						else {
							$curr_terms = reset( $get_terms );
						}
						if ( !empty ( $curr_terms) ) {
							$switch_slug = $curr_terms->slug;
							if ( $switch_slug == 'new' ) {
								echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $curr_parent_order ) . '&action=edit' ) ) . '" class="wcwar_badge wcwar_change warranty_new">' . esc_html__( 'New', 'xforwoocommerce' ) . '</a>';
							}
							else if ( $switch_slug == 'processing' ) {
								echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $curr_parent_order ) . '&action=edit' ) ) . '" class="wcwar_badge wcwar_change warranty_processing">' . esc_html__( 'Processing', 'xforwoocommerce' ) . '</a>';
							}
							else if ( $switch_slug == 'completed' ) {
								echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $curr_parent_order ) . '&action=edit' ) ) . '" class="wcwar_badge wcwar_change warranty_completed">' . esc_html__( 'Completed', 'xforwoocommerce' ) . '</a>';
							}
							else if ( $switch_slug == 'rejected' ) {
								echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $curr_parent_order ) . '&action=edit' ) ) . '" class="wcwar_badge wcwar_change warranty_rejected">' . esc_html__( 'Rejected', 'xforwoocommerce' ) . '</a>';
							}
							if ( $curr_item !== '-1' ) {
								echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $curr_parent_order ) . '&action=edit' ) ) . '" class="wcwar_badge warranty_button" title="' . esc_html__( 'View Parent Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
							}
							echo '<a href="#" class="wcwar_badge warranty_button warranty_change_status" data-id="' . absint( $curr_parent_order ) . '" title="' . esc_html__( 'Change Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-change"></i></a>';
						}
					?>
					</span>
				</p>
				<p class="wcwar_email">
					<?php esc_html_e( 'Quick E-Mail customer', 'xforwoocommerce' ); ?>
					<textarea class="war_message"></textarea>
					<small><?php echo esc_html__( 'Use these variables in Emails') . ' : <em>%order_id%, %order_date%, %completed_date%, %customer_name%, %warranty_link%</em>'; ?></small>
					<span class="wcwar_badge wcwar_label"><?php esc_html_e( 'Email Template', 'xforwoocommerce' ); ?></span>
					<select class="war_email_selected">
						<option value=""><?php esc_html_e( 'None', 'xforwoocommerce' ); ?></option>
						<?php
							$curr_presets = SevenVXGet()->get_option( 'wcwar_email_templates', 'warranties_and_returns' );
							if ( $curr_presets === false ) {
								$curr_presets = array();
							}
							if ( !empty( $curr_presets) ) {
								foreach ( $curr_presets as $k => $v ) {
							?>
									<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $k ); ?></option>
							<?php
								}
							}
						?>
					</select>
					<a href="#" class="wcwar_badge war_load"><?php esc_html_e( 'Load', 'xforwoocommerce' ); ?></a>
					<a href="#" class="wcwar_badge war_save"><?php esc_html_e( 'Save', 'xforwoocommerce' ); ?></a>
					<a href="#" class="wcwar_badge war_delete"><?php esc_html_e( 'Delete Selected', 'xforwoocommerce' ); ?></a>
					<a href="#" class="wcwar_badge war_send" data-ids="<?php echo esc_attr( $curr_order ) . '|' . esc_attr( $object->ID );?>"><?php esc_html_e( 'Send Email', 'xforwoocommerce' ); ?></a>
				</p>
				<p style="display:none">
					<label for="_wcwar_warranty_status">
					<?php esc_html_e( 'Change Request Status', 'xforwoocommerce' ); ?> : 
					<?php

						$curr_terms = get_terms( 'wcwar_warranty', array( 'hide_empty' => false ) );

						if ( !empty( $curr_terms) ) {
					?>
						<select name="_wcwar_warranty_status">
						<?php
							foreach ( $curr_terms as $k => $v ) {
						?>
							<option value="<?php echo esc_attr( $v->slug ); ?>"<?php echo ( $curr_selected == $v->slug ? ' selected="selected"' : '' ); ?>><?php echo esc_html( $v->name ); ?></option>
						<?php
							}
						?>
						</select>
					<?php
						}
					?>
					</label>
				</p>
				<p style="display:none">
					<label for="_wcwar_warranty_order_id">
						<?php esc_html_e( 'Order ID', 'xforwoocommerce' ); ?> : 
						<input name="_wcwar_warranty_order_id" value="<?php echo esc_attr( $curr_order ); ?>" />
					</label>
				</p>
				<p style="display:none">
					<label for="_wcwar_warranty_product_id">
						<?php esc_html_e( 'Item ID', 'xforwoocommerce' ); ?> : 
						<input name="_wcwar_warranty_product_id" value="<?php echo esc_attr( $curr_item ); ?>" />
					</label>
				</p>
				<?php
					if ( isset( $_GET['parent_id'] ) ) {
				?>
				<p style="display:none">
					<label for="parent_id">
						<?php esc_html_e( 'Parent ID', 'xforwoocommerce' ); ?> : 
						<input name="parent_id" value="<?php echo esc_attr( $_GET['parent_id'] ); ?>" />
					</label>
				</p>
				<?php
					}
				?>

				<table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
					<thead>
						<tr>
							<th class="item" colspan="2"><?php esc_html_e( 'Item', 'xforwoocommerce' ); ?></th>

							<?php do_action( 'woocommerce_admin_order_item_headers' ); ?>

							<th class="quantity"><?php esc_html_e( 'Qty', 'xforwoocommerce' ); ?></th>

							<th class="line_cost"><?php esc_html_e( 'Total', 'xforwoocommerce' ); ?></th>

							<th class="wc-order-edit-line-item" width="1%">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="order_line_items">
				<?php

				$curr_mod = (int) get_post_meta( get_the_ID(), '_wcwar_warranty_product_id', true );

				foreach( $order->get_items() as $item_id => $item ) {

					if ( $curr_mod !== -1 ) {
						if ( !( $curr_mod == $item_id) ) {
							$class = ' not_active';
						}
						else {
							$class = ' active';
						}
					}
					else {

						$curr_args = array(
							'post_type' => 'wcwar_warranty_req',
							'post_status' => 'any',
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => '_wcwar_warranty_order_id',
									'value' => $curr_order,
								),
								array(
									'key' => '_wcwar_warranty_product_id',
									'value' => $item_id,
								)
							)
						);
						
						$curr_req = get_posts( $curr_args );

					}

					$_product = wc_get_product( $item['product_id'] );
				?>
					<tr class="item <?php echo esc_attr( apply_filters( 'woocommerce_admin_html_order_item_class', ( ! empty( $class ) ? $class : '' ), $item ) ); ?>" data-order_item_id="<?php echo esc_attr( $item_id ); ?>">
						<td class="thumb">
							<?php
								if ( $_product ) :
									$id = method_exists( $_product, 'get_id' ) ? $_product->get_id() : $_product->id;
							?>
								<div class="wc-order-item-thumbnail">
									<?php echo wp_kses_post( $_product->get_image( 'shop_thumbnail', array( 'title' => '' ) ) ); ?>
								</div>
							<?php else : ?>
								<div class="wc-order-item-thumbnail">
									<?php echo wc_placeholder_img( 'shop_thumbnail' ); ?>
								</div>
							<?php endif; ?>
						</td>
						<td class="name">

							<?php echo ( $_product && $_product->get_sku() ) ? esc_html( $_product->get_sku() ) . ' &ndash; ' : ''; ?>

							<?php if ( $_product ) : ?>
								<a target="_blank" href="<?php echo esc_url( admin_url( 'post.php?post=' . absint( $id ) . '&action=edit' ) ); ?>">
									<?php echo esc_html( $item['name'] ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( $item['name'] ); ?>
							<?php endif; ?>

							<input type="hidden" class="order_item_id" name="order_item_id[]" value="<?php echo esc_attr( $item_id ); ?>" />
							<input type="hidden" name="order_item_tax_class[<?php echo absint( $item_id ); ?>]" value="<?php echo isset( $item['tax_class'] ) ? esc_attr( $item['tax_class'] ) : ''; ?>" />

							<div class="view">
								<?php
									global $wpdb;
									if ( $metadata ) {
										echo '<table cellspacing="0" class="display_meta">';
										foreach ( $metadata as $meta ) {

											if ( in_array( $meta['meta_key'], apply_filters( 'woocommerce_hidden_order_itemmeta', array(
												'_qty',
												'_tax_class',
												'_product_id',
												'_variation_id',
												'_line_subtotal',
												'_line_subtotal_tax',
												'_line_total',
												'_line_tax',
											) ) ) ) {
												continue;
											}

											if ( is_serialized( $meta['meta_value'] ) ) {
												continue;
											}

											if ( taxonomy_exists( $meta['meta_key'] ) ) {
												$term           = get_term_by( 'slug', $meta['meta_value'], $meta['meta_key'] );
												$attribute_name = str_replace( 'pa_', '', wc_clean( $meta['meta_key'] ) );
												$attribute      = $wpdb->get_var(
													$wpdb->prepare( "
															SELECT attribute_label
															FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
															WHERE attribute_name = %s;
														",
														$attribute_name
													)
												);

												$meta['meta_key']   = ( ! is_wp_error( $attribute ) && $attribute ) ? $attribute : $attribute_name;
												$meta['meta_value'] = ( isset( $term->name ) ) ? $term->name : $meta['meta_value'];
											}

											echo '<tr><th>' . wp_kses_post( urldecode( $meta['meta_key'] ) ) . ':</th><td>' . wp_kses_post( wpautop( urldecode( $meta['meta_value'] ) ) ) . '</td></tr>';
										}
										echo '</table>';
									}
								?>
							</div>
							<div class="edit" style="display: none;">
								<table class="meta" cellspacing="0">
									<tbody class="meta_items">
									<?php
										if ( $metadata ) {
											foreach ( $metadata as $meta ) {
												if ( in_array( $meta['meta_key'], apply_filters( 'woocommerce_hidden_order_itemmeta', array(
													'_qty',
													'_tax_class',
													'_product_id',
													'_variation_id',
													'_line_subtotal',
													'_line_subtotal_tax',
													'_line_total',
													'_line_tax',
												) ) ) ) {
													continue;
												}

												if ( is_serialized( $meta['meta_value'] ) ) {
													continue;
												}

												$meta['meta_key']   = urldecode( $meta['meta_key'] );
												$meta['meta_value'] = esc_textarea( urldecode( $meta['meta_value'] ) ); // using a <textarea />
												$meta['meta_id']    = absint( $meta['meta_id'] );

												echo '<tr data-meta_id="' . esc_attr( $meta['meta_id'] ) . '">
													<td>
														<input type="text" name="meta_key[' . esc_attr( $meta['meta_id'] ) . ']" value="' . esc_attr( $meta['meta_key'] ) . '" />
														<textarea name="meta_value[' . esc_attr( $meta['meta_id'] ) . ']">' . esc_html( $meta['meta_value'] ) . '</textarea>
													</td>
													<td width="1%"><button class="remove_order_item_meta button">&times;</button></td>
												</tr>';
											}
										}
									?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4"><button class="add_order_item_meta button"><?php esc_html_e( 'Add&nbsp;meta', 'xforwoocommerce' ); ?></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</td>

						<?php do_action( 'woocommerce_admin_order_item_values', $_product, $item, absint( $item_id ) ); ?>

						<td class="quantity" width="1%">
							<div class="view">
								<?php
									echo ( isset( $item['qty'] ) ) ? esc_html( $item['qty'] ) : '';

									if ( $refunded_qty = $order->get_qty_refunded_for_item( $item_id ) ) {
										echo '<small class="refunded">-' . esc_html( $refunded_qty ) . '</small>';
									}
								?>
							</div>
							<div class="edit" style="display: none;">
								<?php $item_qty = absint( $item['qty'] ); // OK ?>
								<input type="number" step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', '1', $_product ) ); ?>" min="0" autocomplete="off" name="order_item_qty[<?php echo absint( $item_id ); ?>]" placeholder="0" value="<?php echo intval( $item_qty ); ?>" data-qty="<?php echo intval( $item_qty ); ?>" size="4" class="quantity" />
							</div>
							<div class="refund" style="display: none;">
								<input type="number" step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', '1', $_product ) ); ?>" min="0" max="<?php echo intval( $item_qty ); ?>" autocomplete="off" name="refund_order_item_qty[<?php echo absint( $item_id ); ?>]" placeholder="0" size="4" class="refund_order_item_qty" />
							</div>
						</td>

						<td class="line_cost" width="1%">
							<div class="view">
								<?php
									if ( isset( $item['line_total'] ) ) {
										if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
											echo '<del>' . wc_price( $item['line_subtotal'] ) . '</del> ';
										}

										echo wc_price( $item['line_total'] );
									}

									if ( $refunded = $order->get_total_refunded_for_item( $item_id ) ) {
										echo '<small class="refunded">-' . wc_price( $refunded ) . '</small>';
									}
								?>
							</div>
							<div class="edit" style="display: none;">
								<div class="split-input">
									<?php $item_total = ( isset( $item['line_total'] ) ) ? esc_attr( wc_format_localized_price( $item['line_total'] ) ) : ''; // OK ?>
									<input type="text" name="line_total[<?php echo absint( $item_id ); ?>]" placeholder="<?php echo wc_format_localized_price( 0 ); ?>" value="<?php echo esc_attr( $item_total ); ?>" class="line_total wc_input_price tips" data-tip="<?php esc_html_e( 'After pre-tax discounts.', 'xforwoocommerce' ); ?>" data-total="<?php echo esc_attr( $item_total ); ?>" />

									<?php $item_subtotal = ( isset( $item['line_subtotal'] ) ) ? esc_attr( wc_format_localized_price( $item['line_subtotal'] ) ) : ''; // OK ?>
									<input type="text" name="line_subtotal[<?php echo absint( $item_id ); ?>]" value="<?php echo esc_attr( $item_subtotal ); ?>" />
								</div>
							</div>
							<div class="refund" style="display: none;">
								<input type="text" name="refund_line_total[<?php echo absint( $item_id ); ?>]" placeholder="<?php echo wc_format_localized_price( 0 ); ?>" class="refund_line_total wc_input_price" />
							</div>
						</td>
						<td class="wc-order-edit-line-item">
							<?php if ( $order->is_editable() ) : ?>
								<div class="wc-order-edit-line-item-actions">
									<a class="edit-order-item" href="#"></a><a class="delete-order-item" href="#"></a>
								</div>
							<?php endif; ?>
						</td>
					</tr>
				<?php
					}
				?>
					</tbody>
				</table>
			</div>
		</div>
	<?php
		else :
	?>
		<div id="woocommerce-order-items">
			<div class="war_warranty woocommerce_order_items_wrapper">
				<input type="hidden" name="wcwar_warranty_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) . $object->ID ); ?>" />
				<p>
					<label for="_wcwar_warranty_order_id">
						<?php esc_html_e( 'Please enter Order ID', 'xforwoocommerce' ); ?> : 
						<input name="_wcwar_warranty_order_id" value="<?php echo esc_attr( $curr_order ); ?>" />
					</label>
				</p>
			</div>
		</div>
	<?php
		
		endif;
		}

		function war_save_request_metabox( $post_id, $post ) {

			if ( isset( $_POST['wcwar_warranty_nonce'] ) && !wp_verify_nonce( $_POST['wcwar_warranty_nonce'], plugin_basename(__FILE__) . $post_id ) ) {
				return $post_id;
			}

			$post_type = get_post_type_object( $post->post_type );

			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

			if ( isset( $_POST['_wcwar_warranty_order_id'] ) && isset( $_POST['_wcwar_warranty_product_id'] ) ) {

				$get_terms = get_the_terms( $post_id, 'wcwar_warranty' );
				if ( !is_array( $get_terms) ) {
					$curr_selected_term = (object) array( 'slug' => 'new' );
				}
				else {
					$curr_selected_term = reset( $get_terms );
				}

				$curr_selected = $curr_selected_term->slug;

				wp_set_post_terms( $post_id, $curr_selected, 'wcwar_warranty' );

				$new_meta_values = array();

				$new_meta_values[] = ( isset( $_POST['_wcwar_warranty_order_id'] ) ? $_POST['_wcwar_warranty_order_id'] : '' );
				$new_meta_values[] = ( isset( $_POST['_wcwar_warranty_product_id'] ) ? $_POST['_wcwar_warranty_product_id'] : '' );

				$meta_keys = array();

				$meta_keys[] = '_wcwar_warranty_order_id';
				$meta_keys[] = '_wcwar_warranty_product_id';


				$meta_values = array();

				$i = 0;

				foreach ( $meta_keys as $meta_key ) {

					$meta_value = get_post_meta( $post_id, $meta_key, true );
					
					if ( $new_meta_values[$i] && '' == $meta_value )
						add_post_meta( $post_id, $meta_key, $new_meta_values[$i], true );

					elseif ( $new_meta_values[$i] && $new_meta_values[$i] != $meta_value )
						update_post_meta( $post_id, $meta_key, $new_meta_values[$i] );

					elseif ( '' == $new_meta_values[$i] && $meta_value )
						delete_post_meta( $post_id, $meta_key, $meta_value );

					$i++;

				}

			}
			else if ( isset( $_POST['_wcwar_warranty_order_id'] ) && !isset( $_POST['_wcwar_warranty_product_id'] ) ) {

				$order = wc_get_order( $_POST['_wcwar_warranty_order_id'] );


				if ( !empty( $order) ) {

					$curr_items = $order->get_items();

					if ( count( $curr_items) > 1 ) {
						if ( !update_post_meta ( $post_id, '_wcwar_warranty_order_id', $_POST['_wcwar_warranty_order_id'] ) ) {
							add_post_meta( $post_id, '_wcwar_warranty_order_id', $_POST['_wcwar_warranty_order_id'], true );
						};
						if ( !update_post_meta ( $post_id, '_wcwar_warranty_product_id', '-1' ) ) {
							add_post_meta( $post_id, '_wcwar_warranty_product_id', '-1', true );
						};
					}
					else {
						if ( !update_post_meta ( $post_id, '_wcwar_warranty_order_id', $_POST['_wcwar_warranty_order_id'] ) ) {
							add_post_meta( $post_id, '_wcwar_warranty_order_id', $_POST['_wcwar_warranty_order_id'], true );
						};
						if ( !update_post_meta ( $post_id, '_wcwar_warranty_product_id', current(array_keys( $curr_items)) ) ) {
							add_post_meta( $post_id, '_wcwar_warranty_product_id', current(array_keys( $curr_items)), true );
						};
					}

					$get_terms = get_the_terms( $post_id, 'wcwar_warranty' );
					if ( !is_array( $get_terms) ) {
						$curr_selected_term = (object) array( 'slug' => 'new' );
					}
					else {
						$curr_selected_term = reset( $get_terms );
					}

					$curr_selected = $curr_selected_term->slug;

					wp_set_post_terms( $post_id, $curr_selected, 'wcwar_warranty' );

				}
			}

		}

		function war_ajax_et_save() {
			if ( !isset( $_POST['wcwar']['name'] ) ) {
				$this->_ajax_factory_die();
			}

			$name = esc_html( $_POST['wcwar']['name'] );
			$slug = sanitize_title( $name );

			$presets = SevenVXGet()->get_option( 'wcwar_email_templates', 'warranties_and_returns' );

			if ( $presets === false ) {
				$presets = array();
			}

			if ( isset( $presets) && is_array( $presets) ) {
				if ( array_key_exists( $slug, $presets) ) {
					unset( $presets[$slug] );
				}

				$presets[$slug] = wp_kses_post( $_POST['wcwar']['email'] );

				update_option( 'wcwar_email_templates', $presets );

				$this->_ajax_factory_success();
			}

			$this->_ajax_factory_die();
		}

		function war_ajax_et_load() {
			if ( !isset( $_POST['wcwar']['name'] ) ) {
				$this->_ajax_factory_die();
			}

			$name = esc_html( $_POST['wcwar']['name'] );

			$presets = SevenVXGet()->get_option( 'wcwar_email_templates', 'warranties_and_returns' );

			if ( isset( $presets ) && !empty( $presets ) && is_array( $presets ) ) {
				if ( array_key_exists( $name, $presets) ) {
					wp_send_json( json_encode( $presets[$name] ) );
					exit;
				}
			}

			$this->_ajax_factory_die();
		}

		function war_ajax_et_delete() {
			if ( !isset( $_POST['wcwar']['name'] ) ) {
				$this->_ajax_factory_die();
			}

			$name = esc_html( $_POST['wcwar']['name'] );

			$presets = SevenVXGet()->get_option( 'wcwar_email_templates', 'warranties_and_returns' );

			if ( isset( $presets ) && !empty( $presets ) && is_array( $presets ) ) {
				if ( array_key_exists( $name, $presets) ) {
					unset( $presets[$name] );

					update_option( 'wcwar_email_templates', $presets );
				}

				$this->_ajax_factory_success();
			}

			$this->_ajax_factory_die();
		}

		function war_ajax_email_send() {
			if ( !isset( $_POST['wcwar']['order_id'] ) && !isset( $_POST['wcwar']['request_id'] ) && !isset( $_POST['wcwar']['email'] ) ) {
				wp_die(0);
				exit;
			}

			$id = absint( $_POST['wcwar']['order_id'] );
			$order = wc_get_order( $id );

			if ( !empty( $order ) ) {

				$request = absint( $_POST['wcwar']['request_id'] );
				$email = isset( $_POST['wcwar']['email'] ) ? esc_html( $_POST['wcwar']['email'] ) : '';

				if ( empty( $email ) ) {
					$this->_ajax_factory_die();
				}

				$curr_vars = array( '%order_id%', '%order_date%', '%completed_date%', '%customer_name%', '%warranty_link%' );

				$curr_vals = array(
					$id,
					get_post_meta( $id, '_ordered_date', true ),
					get_post_meta( $id, '_completed_date', true ),
					$order->billing_first_name . ' ' . $order->billing_last_name,
					'<a href="' . get_permalink( $request ) . '">' . get_permalink( $request ) . '</a>'
				);

				$ready_email = str_replace( $curr_vars, $curr_vals, $email);

				$curr_reply_from = SevenVXGet()->get_option( 'wcwar_email_name', 'warranties_and_returns', get_bloginfo( 'name' ) );
				$curr_reply_to = SevenVXGet()->get_option( 'wcwar_email_address', 'warranties_and_returns', get_bloginfo( 'admin_email' ) );

				$curr_headers = array();
				$curr_headers[] = 'From: ' . esc_html( $curr_reply_from ) . ' <' . esc_html( $curr_reply_to ) . '>';

				$curr_bcc = SevenVXGet()->get_option( 'wcwar_email_bcc', 'warranties_and_returns', '' );

				if ( $curr_bcc !== '' ) {
					$curr_headers[] = 'Bcc: ' . esc_html( $curr_bcc );
				}

				$curr_subject = esc_html__( 'Warranty Request for Order #', 'xforwoocommerce' ) . $id . ' - ' . get_bloginfo( 'name' );

				wc_mail( sanitize_email( $order->billing_email ), esc_html( $curr_subject ), wp_kses_post( $ready_email ), $curr_headers, '' );
				
				$this->_ajax_factory_success();
			}

			$this->_ajax_factory_die();
		}

		function war_ajax_create() {
			if ( !isset( $_POST['wcwar']['order_id'] ) && !isset( $_POST['wcwar']['item_id'] ) ) {
				$this->_ajax_factory_die();
			}

			$id = absint( $_POST['wcwar']['order_id'] );
			$item_id = absint( $_POST['wcwar']['item_id'] );
			$return = $_POST['wcwar']['wcwar_return'];

			$order = wc_get_order( $id );

			if ( !empty( $order ) ) {

				$curr_ordered = $order->get_items();

				if ( count( $curr_ordered ) > 1 ) {

					$curr_parent_args = array(
						'post_type' => 'wcwar_warranty_req',
						'post_status' => 'any',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => '_wcwar_warranty_order_id',
								'value' => $id,
							),
							array(
								'key' => '_wcwar_warranty_product_id',
								'value' => -1,
							)
						)
					);
					$curr_parent_req = get_posts( $curr_parent_args );

					if ( empty( $curr_parent_req ) ) {

						$curr_parent_create = array(
							'post_title'    => $this->_get_request_title( absint( $id ) ),
							'post_content'  => esc_html__( 'This is a parent warranty request. Check child requests for details.', 'xforwoocommerce' ),
							'post_name'     => 'r_' . absint( $id ),
							'post_status'   => SevenVXGet()->get_option( 'wcwar_default_post', 'warranties_and_returns', 'pending' ),
							'post_author'   => 1,
							'post_type'     => 'wcwar_warranty_req',
							'comment_status'=> 'open',
							'ping_status'   => 'closed'
						);
						$curr_parent_request = wp_insert_post( $curr_parent_create );

						wp_set_post_terms( $curr_parent_request, 'new', 'wcwar_warranty' );

						$curr_parent_meta = array(
							'order_id'      => $id,
							'product_id'    => -1,
							'warranty_id'   => $id . '-' . $curr_parent_request
						);

						foreach ( $curr_parent_meta as $k => $v ) {
							add_post_meta( $curr_parent_request, '_wcwar_warranty_' . $k, $v, true );
						}
					}

					
					$curr_create = array(
						'post_title'    => $this->_get_request_title( absint( $id ), absint( $item_id ) ),
						'post_content'  => '',
						'post_name'     => 'i_' . absint( $id ) . '_' . absint( $item_id ),
						'post_status'   => SevenVXGet()->get_option( 'wcwar_default_post', 'warranties_and_returns', 'pending' ),
						'post_author'   => 1,
						'post_type'     => 'wcwar_warranty_req',
						'comment_status'=> 'open',
						'ping_status'   => 'closed',
						'post_parent'   => ( isset( $curr_parent_request) ? $curr_parent_request : $curr_parent_req[0]->ID )
					);
					$curr_request = wp_insert_post( $curr_create );

					wp_set_post_terms( $curr_request, 'new', 'wcwar_warranty' );

					$curr_meta = array(
						'order_id'      => $id,
						'product_id'    => $item_id,
						'warranty_id'   => $id . '-' . $item_id . '-' . $curr_request
					);

					if ( isset( $return ) ) {
						$curr_meta= $curr_meta + array( 'return_request' => 'return' );
					}

					foreach ( $curr_meta as $k => $v ) {
						add_post_meta( $curr_request, '_wcwar_warranty_' . $k, $v, true );
					}

				}
				else {

					$curr_create = array(
						'post_title'    => $this->_get_request_title( absint( $id ), absint( $item_id ) ),
						'post_content'  => '',
						'post_name'     => 'r_' . absint( $id ) . '_' . absint( $item_id ),
						'post_status'   => SevenVXGet()->get_option( 'wcwar_default_post', 'warranties_and_returns', 'pending' ),
						'post_author'   => 1,
						'post_type'     => 'wcwar_warranty_req',
						'comment_status'=> 'open',
						'ping_status'   => 'closed'
					);
					$curr_request = wp_insert_post( $curr_create );

					wp_set_post_terms( $curr_request, 'new', 'wcwar_warranty' );

					$curr_meta = array(
						'order_id'      => $id,
						'product_id'    => $item_id,
						'warranty_id'   => $id . '-' . $item_id . '-' . $curr_request
					);

					if ( isset( $return ) ) {
						$curr_meta= $curr_meta + array( 'return_request' => 'return' );
					}

					foreach ( $curr_meta as $k => $v ) {
						add_post_meta( $curr_request, '_wcwar_warranty_' . $k, $v, true );
					}

				}

				$this->_ajax_factory_success();
			}

			$this->_ajax_factory_die();
		}

		function war_ajax_status() {
			if ( isset( $_POST['wcwar']['request_id'] ) === false ) {
				$this->_ajax_factory_die();
			}			

			$request_id = absint( $_POST['wcwar']['request_id'] );

			$terms = get_terms( 'wcwar_warranty', array('hide_empty' => false) );

			$selected_term = get_the_terms( $request_id, 'wcwar_warranty' );
			$selected_term = reset( $selected_term );
			$selected = ( empty( $selected_term) ? 'new' : $selected_term->slug );

			if ( !empty( $terms) ) {
?>
			<div id="wcwar_change_status">
				<ul>
					<li class="wcwar_close"><i class="wcwar-close"></i></li>
<?php
					foreach ( $terms as $k => $v ) {
?>
						<li data-key="<?php echo esc_attr( $v->slug ); ?>"<?php echo ( $selected == $v->slug ? ' class="wcwar_selected"' : '' ); ?>><?php echo esc_html( $v->name ); ?></li>
<?php
					}
?>
				</ul>
			</div>
<?php
			}

			$this->_ajax_factory_success();
		}


		function war_ajax_status_change() {
			if ( ( isset( $_POST['wcwar']['request_status'] ) && isset( $_POST['wcwar']['request_id'] ) ) === false ) {
				$this->_ajax_factory_die();
			}
			
			$request_id = absint( $_POST['wcwar']['request_id'] );
			$new_status = $_POST['wcwar']['request_status'];

			wp_set_post_terms( $request_id, $new_status, 'wcwar_warranty' );

			$this->_ajax_factory_success();
		}

		function _ajax_factory() {
			if ( isset( $_POST['wcwar']['type'] ) === false ) {
				$this->_ajax_factory_die();
			}

			switch ( $_POST['wcwar']['type'] ) {
				case 'create' :
					$this->war_ajax_create();
				break;

				case 'status' :
					$this->war_ajax_status();
				break;

				case 'status_change' :
					$this->war_ajax_status_change();
				break;

				case 'email_send' :
					$this->war_ajax_email_send();
				break;

				case 'et_save' :
					$this->war_ajax_et_save();
				break;

				case 'et_load' :
					$this->war_ajax_et_load();
				break;

				case 'et_delete' :
					$this->war_ajax_et_delete();
				break;

				default :
					$this->_ajax_factory_die();
				break;

			}
		}

		function _ajax_factory_success( $args = array() ) {
			wp_die( array_merge( array( 'success' => true ), $args ) );
			exit;
		}

		function _ajax_factory_die() {
			wp_die(0);
			exit;
		}

		function wc_add_order_request_status( $curr_order ) {

			$curr_items = $curr_order->get_items();
			$id = method_exists( $curr_order, 'get_id' ) ? $curr_order->get_id() : $curr_order->ID;

				if ( count( $curr_items) > 1 ) {
					$curr_args = array(
						'post_type' => 'wcwar_warranty_req',
						'post_status' => 'any',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => '_wcwar_warranty_order_id',
								'value' => $id,
							),
							array(
								'key' => '_wcwar_warranty_product_id',
								'value' => '-1',
							)
						)
					);
					echo '<p class="form-field form-field-wide multi-order">';
					echo '<label>' . esc_html__( 'Warranty Status (Multi Item Order):', 'xforwoocommerce' ) . '</label>';
				}
				else {
					$curr_args = array(
						'post_type' => 'wcwar_warranty_req',
						'post_status' => 'any',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => '_wcwar_warranty_order_id',
								'value' => $id,
							)
						)
					);
					echo '<p class="form-field form-field-wide wcwar_warranty_status single-order">';
					echo '<label>' . esc_html__( 'Warranty Status (Single Item Order):', 'xforwoocommerce' ) . '</label>';
				}

				$curr_complete = get_post_meta( $id, '_completed_date', true );

				$curr_fields = array( 'warranty', 'status' );

				$curr_req = get_posts( $curr_args );

				if ( !empty( $curr_req ) ) {
					$curr_terms = get_the_terms( $curr_req[0]->ID, 'wcwar_warranty' );
					$curr_terms = reset( $curr_terms );
					if ( !empty ( $curr_terms) ) {
						$switch_slug = $curr_terms->slug;
						if ( $switch_slug == 'new' ) {
							echo '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_new" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'New', 'xforwoocommerce' ) . '</a>';
							echo'<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'processing' ) {
							echo '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_processing" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Processing', 'xforwoocommerce' ) . '</a>';
							echo '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'completed' ) {
							echo '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_completed" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Completed', 'xforwoocommerce' ) . '</a>';
							echo '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
						else if ( $switch_slug == 'rejected' ) {
							$curr_fields['status'] = '<a href="' . esc_url( $curr_req[0]->guid ) . '" class="wcwar_badge wcwar_change warranty_rejected" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '">' . esc_html__( 'Rejected', 'xforwoocommerce' ) . '</a>';
							echo '<a href="' . get_edit_post_link( $curr_req[0]->ID ) . '" class="wcwar_badge warranty_button" title="' . esc_html__( 'View Request', 'xforwoocommerce' ) . '"><i class="wcwar-view"></i></a>';
						}
					}
					echo '<a href="#" class="wcwar_badge warranty_button warranty_change_status" data-id="' . esc_attr( $curr_req[0]->ID ) . '" title="' . esc_html__( 'Change Request Status', 'xforwoocommerce' ) . '"><i class="wcwar-change"></i></a>';
				}
				else {
					echo '<span class="wcwar_badge warranty_notrequested" >' . esc_html__( 'This order has no warranty requests', 'xforwoocommerce' ) . '</span>';
				}


		}


		function _check_for_multi_requests() {

			if ( SevenVXGet()->get_option( 'wcwar_enable_multi_requests', 'warranties_and_returns', 'no' ) == 'no' ) {

				$curr_args = array(
					'post_type' => 'wcwar_warranty_req',
					'post_status' => 'any',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => '_wcwar_warranty_order_id',
							'value' => $_POST['order_id'],
						),
						array(
							'key' => '_wcwar_warranty_product_id',
							'value' => $_POST['item_id'],
						)
					)
				);

				$curr_req = get_posts( $curr_args );

				if ( !empty( $curr_req ) ) {
					$m=0;
					foreach( $curr_req as $curr_reqq ) {
						$check_return = get_post_meta( $curr_reqq->ID, '_wcwar_warranty_return_request', true );
						
						if ( $check_return == 'return' ) {
							unset( $curr_req[$m] );
						}
						$m++;
					}
				}

				if ( !empty( $curr_req ) ) {
					$curr_req = array_values($curr_req);
				?>
					<p class="wcwar_form_error">
						<span class="wcwar_info_icon"><i class="wcwar-cross"></i></span>
					<?php
						echo esc_html__( 'Warranty already requested. View request status on this', 'xforwoocommerce' ) . ' <a href="' . esc_url( $curr_req[0]->guid ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.';
					?>
					</p>
				<?php
					return ob_get_clean();
				}

				return true;
			}

			return false;
		}

		function __get_return_form_option() {
			if ( isset( self::$settings['return_form'] ) ) {
				return self::$settings['return_form'];
			}

			self::$settings['return_form'] = json_decode( stripslashes( SevenVXGet()->get_option( 'wcwar_return_form', 'warranties_and_returns', false ) ), true );

			if ( !is_array( self::$settings['return_form'] ) ) {
				self::$settings['return_form'] = json_decode( stripslashes( '{"fields":[{"label":"Why are you returning this item?","field_type":"paragraph","required":true,"cid":"c10"}]}' ), true );
			}

			return self::$settings['return_form'];
		}

		function __get_warranty_form_option() {
			if ( isset( self::$settings['warranty_form'] ) ) {
				return self::$settings['warranty_form'];
			}

			self::$settings['warranty_form'] = json_decode( stripslashes( SevenVXGet()->get_option( 'wcwar_form', 'warranties_and_returns', false ) ), true );

			if ( !is_array( self::$settings['warranty_form'] ) ) {
				self::$settings['warranty_form'] = json_decode( stripslashes( '{"fields":[{"label":"Reason for requesting warranty","field_type":"radio","required":true,"field_options":{"options":[{"label":"Item was damaged","checked":false},{"label":"Item was broken","checked":false},{"label":"Nothing wrong, just returning","checked":false}],"include_other_option":true},"cid":"c10"}]}' ), true );
			}

			return self::$settings['warranty_form'];
		}

		function _build_submitted_data_warranty() {
			return $this->_build_submitted_data( $this->__get_warranty_form_option() ); 
		}

		function _build_submitted_data( $form ) {

			$request_content = '';
			$has_error = false;

			if ( isset( $_POST['wcwar_qty'] ) ) {
				$request_content .= '<strong>' . esc_html__( 'Item was ordered multiple times, the requested number of item warranties is: ', 'xforwoocommerce' ) . intval( $_POST['wcwar_qty'] ) . '</strong>
';
			}

			foreach ( $form['fields'] as $cfld ) {

				switch ( $cfld['field_type'] ) :

					case 'text' :
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid']] ) && $_POST['wcwar_' . $cfld['cid']] !== '' ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid']] ) ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else {
							$has_error = true;
						}
					break ;

					case 'paragraph' :
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid']] ) && $_POST['wcwar_' . $cfld['cid']] !== '' ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid']] ) ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else {
							$has_error = true;
						}
					break ;

					case 'radio' :
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid']] ) && $_POST['wcwar_' . $cfld['cid']] !== '' ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . esc_html( $cfld['field_options']['options'][$_POST['wcwar_' . $cfld['cid']]]['label'] ) . '
';
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid']] ) ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else {
							$has_error = true;
						}
					break ;

					case 'checkboxes' :
						$i=0;
						foreach ( $cfld['field_options']['options'] as $cf ) {
							if ( isset( $_POST['wcwar_' . $cfld['cid'] . '_' . $i] ) ) {
								$request_content .= esc_html( $cf['label'] ) . ' : ' . esc_html__( 'Checked', 'xforwoocommerce' ) . '
								';
							}
							$i++;
						}
						$request_content .= '';
					break ;

					case 'dropdown' :
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid']] ) && $_POST['wcwar_' . $cfld['cid']] !== '' ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . esc_html( $cfld['field_options']['options'][$_POST['wcwar_' . $cfld['cid']]]['label'] ) . '
';
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid']] ) ) {
							$request_content .= esc_html( $cfld['label'] ) . ' : ' . wp_strip_all_tags( $_POST['wcwar_' . $cfld['cid']] ) . '
';
						}
						else {
							$has_error = true;
						}
					break ;

					case 'address' :
						$request_content .= esc_html( $cfld['label'] ) . ' :
';

						$adr_content = '';
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid'] . '_address'] ) && $_POST['wcwar_' . $cfld['cid'] . '_address'] !== '' ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_address'] . ', ' );
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid'] . '_address'] ) ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_address']  . ', ' );
						}
						else {
							$has_error = true;
						}
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid'] . '_city'] ) && $_POST['wcwar_' . $cfld['cid'] . '_city'] !== '' ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_city'] . ', ' );
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid'] . '_city'] ) ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_city'] . ', ' );
						}
						else {
							$has_error = true;
						}
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid'] . '_state'] ) && $_POST['wcwar_' . $cfld['cid'] . '_state'] !== '' ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_state'] . ', ' );
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid'] . '_state'] ) ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_state'] . ', ' );
						}
						else {
							$has_error = true;
						}
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid'] . '_zip'] ) && $_POST['wcwar_' . $cfld['cid'] . '_zip'] !== '' ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_zip'] . ', ' );
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid'] . '_zip'] ) ) {
							$adr_content .= esc_html( $_POST['wcwar_' . $cfld['cid'] . '_zip'] . ', ' );
						}
						else {
							$has_error = true;
						}
						if ( $cfld['required'] === true && isset( $_POST['wcwar_' . $cfld['cid'] . '_country'] ) && $_POST['wcwar_' . $cfld['cid'] . '_country'] !== '' ) {
							$adr_content .= WC()->countries->countries[esc_html( $_POST['wcwar_' . $cfld['cid'] . '_country'] . '' )];
						}
						else if ( $cfld['required'] === false && isset( $_POST['wcwar_' . $cfld['cid'] . '_country'] ) ) {
							$adr_content .= WC()->countries->countries[esc_html( $_POST['wcwar_' . $cfld['cid'] . '_country'] . '' )];
						}
						else {
							$has_error = true;
						}
						$request_content .= $adr_content . '
';
					break ;

					case 'file':
						
						$do_upload = false;
						
						if ( $cfld['required'] === true && isset( $_FILES['wcwar_' . $cfld['cid']] ) && !empty( $_FILES['wcwar_' . $cfld['cid']] ) ) {
							$do_upload = true;
						}
						else if ( $cfld['required'] === false && isset( $_FILES['wcwar_' . $cfld['cid']] ) ) {
							$do_upload = true;
						}
						else {
							$has_error = true;
						}
						
						if ( $do_upload ) {

							$request_content .= esc_html( $cfld['label'] ) . ' :
';

							$uploadedfile = isset( $_FILES['wcwar_' . $cfld['cid']] ) ? $_FILES['wcwar_' . $cfld['cid']] : '';
							
							if ( !empty( $uploadedfile ) ) {
								if ( ! function_exists( 'wp_handle_upload' ) ) {
									require_once( ABSPATH . 'wp-admin/includes/file.php' );
								}

								$upload_overrides = array(
									'test_form' => false
								);
								
								$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
								
								if ( $movefile && !isset( $movefile['error'] ) ) {
									if ( isset( $movefile['url'] ) ) {
										$request_content .= '<img src="' . esc_url( $movefile['url'] ) . '" />
';
										$request_content .= esc_url( $movefile['url'] );
	
									}
	
								}
								else {
									$request_content .= esc_html__( 'Error uploading file.', 'xforwoocommerce' );
								}
							}

						}

					break;
					
					default :
					break ;

				endswitch;

			}

			return array(
				'content' => $request_content,
				'has_error' => $has_error,
			);

		}

		function __build_submitted_data_return() {
			return $this->_build_submitted_data( $this->__get_return_form_option() ); 
		}

		function _get_parent_request() {

			$curr_parent_args = array(
				'post_type' => 'wcwar_warranty_req',
				'post_status' =>  array( 'publish', 'pending' ),
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => '_wcwar_warranty_order_id',
						'value' => absint( $_POST['order_id'] ),
					),
					array(
						'key' => '_wcwar_warranty_product_id',
						'value' => -1,
					)
				)
			);

			$curr_parent_req = get_posts( $curr_parent_args );

			if ( empty( $curr_parent_req ) ) {

				$curr_parent_create = array(
					'post_title'    => $this->_get_request_title( absint( $_POST['order_id'] ) ),
					'post_content'  => esc_html__( 'This is a parent warranty request. Check child requests for details.', 'xforwoocommerce' ),
					'post_name'     => 'r_' . absint( $_POST['order_id'] ),
					'post_status'   => SevenVXGet()->get_option( 'wcwar_default_post', 'warranties_and_returns', 'pending' ),
					'post_author'   => 1,
					'post_type'     => 'wcwar_warranty_req',
					'comment_status'=> 'open',
					'ping_status'   => 'closed'
				);
				
				$curr_parent_request = wp_insert_post( $curr_parent_create );

				wp_set_post_terms( $curr_parent_request, 'new', 'wcwar_warranty' );

				$curr_parent_meta = array(
					'order_id'      => absint( $_POST['order_id'] ),
					'product_id'    => -1,
					'warranty_id'   => absint( $_POST['order_id'] ) . '-' . $curr_parent_request
				);

				foreach ( $curr_parent_meta as $k => $v ) {
					add_post_meta( $curr_parent_request, '_wcwar_warranty_' . $k, $v, true );
				}

			}

			return isset( $curr_parent_request) ? absint( $curr_parent_request ) : absint( $curr_parent_req[0]->ID );

		}

		function _build_request( $request_content, $parent = false ) {

			$curr_create = array(
				'post_title'    => $this->_get_request_title( absint( $_POST['order_id'] ), absint( $_POST['item_id'] ) ),
				'post_content'  => $request_content,
				'post_name'     => ($parent?'i_':'r_') . absint( $_POST['order_id'] ) . '_' . absint( $_POST['item_id'] ),
				'post_status'   => SevenVXGet()->get_option( 'wcwar_default_post', 'warranties_and_returns', 'pending' ),
				'post_author'   => 1,
				'post_type'     => 'wcwar_warranty_req',
				'comment_status'=> 'open',
				'ping_status'   => 'closed',
			);

			if ( $parent === true ) {
				$curr_create['post_parent'] = $this->_get_parent_request();
			}

			$curr_request = wp_insert_post( $curr_create );

			wp_set_post_terms( $curr_request, 'new', 'wcwar_warranty' );

			$curr_meta = array(
				'order_id'      => absint( $_POST['order_id'] ),
				'product_id'    => absint( $_POST['item_id'] ),
				'warranty_id'   => absint( $_POST['order_id'] ) . '-' . absint( $_POST['item_id'] ) . '-' . absint( $curr_request ),
			);

			if ( isset( $_POST['wcwar_return'] ) ) {
				$curr_meta['return_request'] = 'return';
			}

			foreach ( $curr_meta as $k => $v ) {
				add_post_meta( $curr_request, '_wcwar_warranty_' . $k, $v, true );
			}

		}

		function _get_request_title( $order_id = '', $item_id = '' ) {
			$order = wc_get_order( $order_id );
			$user = $order->get_user();

			$item_name = '';

			if ( !empty( $item_id ) ) {
				$item = $order->get_item( absint( $_POST['item_id'] ) );
				$item_name = $item->get_name() . ' / ';
			}

			return esc_html__( 'Request for Order #', 'xforwoocommerce' ) . absint( $order_id ) . ' - ' . $item_name . $user->data->user_nicename;
		}

		function _get_warranty_request( $order_id, $item_id ) {

			$args = array(
				'post_type' => 'wcwar_warranty_req',
				'orderby'   => 'date',
				'order'     => 'ASC',
				'post_status' => array(
					'publish',
					'pending',
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => '_wcwar_warranty_order_id',
						'value' => absint( $order_id ),
					),
					array(
						'key' => '_wcwar_warranty_product_id',
						'value' => absint( $item_id ),
					)
				)
			);

			return get_posts( $args );

		}

		function wcwar_sc_request() {

			ob_start();

			$has_error = false;

			if ( isset( $_POST['war_submit'] ) && $_POST['war_submit'] == 'true' ) {

				if ( !isset( $_POST['wcwar_return'] ) ) {

					$multiRequest = $this->_check_for_multi_requests();
					if ( $multiRequest ) {
						if ( $multiRequest !== true ) {
							return $multiRequest;
						}
					}

					$opt = $this->_build_submitted_data_warranty();

					$request_content = $opt['content'];
					$has_error = $opt['has_error'];

				}
				else {

					$opt = $this->__build_submitted_data_return();

					$request_content = $opt['content'];
					$has_error = $opt['has_error'];

				}

				if ( $has_error === false ) {

					$order = wc_get_order( absint( $_POST['order_id'] ) );

					$id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

					$curr_ordered = $order->get_items();

					$item = $curr_ordered[absint( $_POST['item_id'] )];

					if ( !in_array( $order->get_status(), apply_filters( 'wcwar_warranty_order_status', array( 'completed' ), $order ) ) ) {
?>
						<div class="wcwar_warranty">
							<?php $this->_dump_and_continue(1); ?>
						</div>
<?php
						$curr_notallowed = true;
						$curr_complete = get_post_meta( $id, '_ordered_date', true );
					}
					else {
						$curr_notallowed = false;
						$curr_complete = get_post_meta( $id, '_completed_date', true );
					}

					$curr_req = $this->_get_warranty_request( $id, $_POST['item_id'] );

					if ( !empty( $curr_req ) ) {

						$m=0;
						foreach( $curr_req as $curr_reqq ) {
							$check_return = get_post_meta( $curr_reqq->ID, '_wcwar_warranty_return_request', true );
							if ( $check_return == 'return' ) {
								$return = 'return';
								$return_id = $m;

								$curr_terms = get_the_terms( $curr_req[$m]->ID, 'wcwar_warranty' );
								$curr_terms = reset( $curr_terms );
							}
							$m++;
						}
					}

					$curr_status = self::hlp_valid_warranty( $curr_complete, $item );
					$curr_status_return = self::hlp_valid_return( $curr_complete, $item );

					$curr_returns = SevenVXGet()->get_option( 'wcwar_returns_no_warranty', 'warranties_and_returns', 'no' );
					$curr_multi_req = SevenVXGet()->get_option( 'wcwar_enable_multi_requests', 'warranties_and_returns', 'no' );

					if ( $curr_notallowed === false && $curr_status && $curr_status === 'nowar' && $curr_returns == 'yes' && empty( $curr_req ) && !$curr_status_return ) {
						return '<div class="wcwar_warranty">' . $this->_dump_and_close(4) . '</div>';
					}
					else if ( $curr_notallowed === false && $curr_status && $curr_status === 'nowar' && empty( $curr_req ) && $curr_returns == 'yes' && !$curr_status_return && !isset( $_POST['wcwar_return'] ) ) {
						return '<div class="wcwar_warranty">' . $this->_dump_and_close(2) . '</div>';
					}
					else if ( $curr_notallowed === false && $curr_status && empty( $curr_req ) && $curr_returns == 'yes' && !$curr_status_return ) {
						return '<div class="wcwar_warranty">' . $this->_dump_and_close(3) . '</div>';
					}
					else if ( !empty( $curr_req ) && isset( $return ) && $return == 'return' ) {

						if ( !empty( $curr_terms ) && $curr_terms->slug == 'completed' ) {
							return '<div class="wcwar_warranty">' . $this->_dump_and_close(16) . '</div>';
						}
						else if ( $curr_req[$return_id]->post_status == 'pending' ) {
							return '<div class="wcwar_warranty">' . $this->_dump_and_close(17) . '</div>';
						}
						else {
							return '<div class="wcwar_warranty">' . $this->_dump_and_close( 18, ' <a href="' . get_permalink( $curr_req[$return_id]->ID ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>' ) . '</div>';
						}

					}
					else if ( !empty( $curr_req ) && $curr_multi_req == 'no' ) {
						return '<div class="wcwar_warranty">' . $this->_dump_and_close( 11, ' <a href="' . esc_url( $curr_req[0]->guid ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.' ) . '</div>';
					}
					else if ( !empty( $curr_req ) && $curr_multi_req == 'yes' ) {
						if ( isset( $return_id ) ) {
							unset( $curr_req[$return_id] );
						}

						$curr_req_reverse = array_values( array_reverse ( $curr_req ) );

						$curr_terms = get_the_terms( $curr_req_reverse[0]->ID, 'wcwar_warranty' );
						$curr_terms = reset( $curr_terms );

						if ( $curr_terms->slug !== 'completed' ) {
							return '<div class="wcwar_warranty">' . $this->_dump_and_close( 11, ' <a href="' . esc_url( $curr_req_reverse[0]->guid ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.' ) . '</div>';
						}
					}

					if ( self::hlp_valid_warranty( $curr_complete, $item) && $curr_returns == 'yes' && !$curr_status_return ) {
						return '<div class="wcwar_warranty">' . $this->_dump_and_close( 12, $item['name'] ) . '</div>';
					}

					if ( isset( $_POST['wcwar_return'] ) ) {
						$curr_returns = SevenVXGet()->get_option( 'wcwar_enable_returns', 'warranties_and_returns', 'no' );

						if ( $curr_returns == 'yes' && self::hlp_valid_return( $curr_complete, $item ) === false ) {
							return '<div class="wcwar_warranty">' . $this->_dump_and_close( 13, $item['name'] ) . '</div>';
						}
					}

					$this->_build_request( $request_content, ( count( $curr_ordered ) > 1 ) );

					if ( isset( $_POST['wcwar_return'] ) ) {
?>
						<div class="wcwar_warranty">
							<?php $this->_dump_and_continue( 14, ' <a href="' . esc_url( $order->get_view_order_url() ) . '">' . esc_html__( 'here', 'xforwoocommerce' ) . '</a>.' ); ?>
						</div>
<?php
					}
					else {
?>
						<div class="wcwar_warranty">
							<?php $this->_dump_and_continue( 15, ' <a href="' . esc_url( $order->get_view_order_url() ) . '">' . esc_html__( 'here', 'xforwoocommerce' ) . '</a>.' ); ?>
						</div>
<?php
					}

				}

			}

			if ( !isset( $_POST['war_submit'] ) || $has_error === true ) {

				if ( isset( $_POST['war_submit'] ) ) {
					$_GET['order_id'] = absint( $_POST['order_id'] );
					$_GET['item_id'] = absint( $_POST['item_id'] );
					$_GET['multiple'] = isset( $_POST['multiple'] ) ? $_POST['multiple'] : null;
				}

				$curr_guests = SevenVXGet()->get_option( 'wcwar_enable_guest_requests', 'warranties_and_returns', 'no' );

				if ( $curr_guests == 'yes' ) {
					if ( isset( $_POST['war_guest'] ) ) {
						if ( isset( $_POST['email'] ) && isset( $_POST['order_id'] ) ) {
							$chck_order = wc_get_order( $_POST['order_id'] );
							if ( isset( $chck_order ) && !empty( $chck_order ) && $chck_order->billing_email == $_POST['email'] ) {
								$guest_request = true;
								$_GET['order_id'] = absint( $_POST['order_id'] );
								$_GET['item_id'] ='-1';
							}
						}
					}
				}

				if ( is_user_logged_in() || isset( $guest_request ) ) {

					if ( ( !isset( $_GET['order_id'] ) || !isset( $_GET['item_id'] ) ) ) {
						return $this->_dump_and_close( 5, ' <a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.' );
					}

					$current_user = wp_get_current_user();

					$curr_user = ( $curr_user = get_post_meta( $_GET['order_id'], '_customer_user', true ) ) ? absint( $curr_user ) : '';

					if ( current_user_can( 'manage_options' ) || $curr_user == $current_user->ID || isset( $guest_request ) ) {

						$order = wc_get_order( $_GET['order_id'] );

						if ( $order === false ) {
							return $this->_dump_and_close();
						}

						$id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

						if ( !in_array( $order->get_status(), apply_filters( 'wcwar_warranty_order_status', array( 'completed' ), $order ) ) ) {
							return $this->_dump_and_close(1);
						}
						else {
							$curr_notallowed = false;
						}

						$curr_complete = get_post_meta( $id, '_completed_date', true );

						$curr_ordered = $order->get_items();

						if ( $_GET['item_id'] == '-1' ) {

							$form_out = '<p class="wcwar_item wcwar_return"><strong>' . esc_html__( 'Select item', 'xforwoocommerce' ) . '</strong>';

							$first = true;
					
							foreach ( $curr_ordered as $key => $item ) {
								$form_out .= '<span class="wcwar_item_wrap"><label for="wcwar_item_' . esc_attr( $key ) .'"><input id="wcwar_item_' . esc_attr( $key ) .'" name="item_id" type="radio" value="' . esc_attr( $key ) . '" ' . ( $first === true ? ' checked="checked"' : '' ) . ' /> ' . esc_attr( $item['name'] ) . '';

								if ( $item['qty'] > 1 ) {
									$form_out .= '<input name="wcwar_qty" type="number" min="1" max="' . absint( $item['qty'] ) . '" value="1" ' . ( $first === true ? '' : ' disabled="disabled" style="display:none;"' ) . '/>';
									$form_out .= '<input name="multiple" type="hidden" value="1" />';
								}
								$form_out .= '</label></span>';

								$curr_stts = self::hlp_valid_warranty( $curr_complete, $item );
								$curr_stts_return = self::hlp_valid_return( $curr_complete, $item );

								if ( $curr_stts_return ) {
									$curr_returnfound = true;
								}
								if ( $curr_stts === false ) {
									$curr_warfound = true;
								}
								$first = false;
							}

							$form_out .= '</p>';

							if ( !isset( $curr_warfound ) && !isset( $curr_returnfound ) ) {
								return $this->_dump_and_close(6);
							}
							else if ( !isset( $curr_warfound ) && isset( $curr_returnfound ) ) {
								$this->_dump_and_continue(9);
								$only_return = true;
							}
							else if ( !isset( $curr_warfound ) ) {
								return $this->_dump_and_close(6);
							}

						}
						else {

							if ( !isset( $curr_ordered[$_GET['item_id']] ) ) {
								return $this->_dump_and_close();
							}

							$item = $curr_ordered[$_GET['item_id']];

							$curr_stts = self::hlp_valid_warranty( $curr_complete, $item );
							$curr_stts_return = self::hlp_valid_return( $curr_complete, $item );
							$curr_returns = SevenVXGet()->get_option( 'wcwar_returns_no_warranty', 'warranties_and_returns', 'no' );

							if ( $curr_notallowed === false && $curr_stts && $curr_stts === 'nowar' && $curr_returns == 'yes' && $curr_stts_return ) {
							?>
								<div class="wcwar_warranty">
									<p class="wcwar_form_info">
										<span class="wcwar_info_icon"><i class="wcwar-info"></i></span>
										<?php esc_html_e( 'There is no warranty for this item.', 'xforwoocommerce' ); ?>
<?php
											if ( self::hlp_valid_return( $curr_complete, $item ) === true ) {
												$only_return = true;
												echo esc_html__( 'However you can still return it to the store as its return warranty is still valid.', 'xforwoocommerce' );
											}
?>
									</p>
								</div>
							<?php
							}
							else if ( $curr_notallowed === false && $curr_stts && $curr_stts === 'nowar' ) {
								return '<div class="wcwar_warranty">' . $this->_dump_and_close(2) . '</div>';
							}
							else if ( $curr_notallowed === false && $curr_stts ) {
								return '<div class="wcwar_warranty">' . $this->_dump_and_close(3) . '</div>';
							}
						}

						if ( isset( $_GET['multiple'] ) && $_GET['multiple'] == '1' ) {
							if ( !isset( $form_out ) ) {
								$form_out = '';
							}

							$form_out .= '<p class="wcwar_item wcwar_multiple"><strong>' . esc_html__( 'Select Quantity', 'xforwoocommerce' ) . '</strong><label for="wcwar_qty">';
							$form_out .= '<input id="wcwar_qty" name="wcwar_qty" type="number" min="1" max="' . absint( $item['qty'] ) . '" value="1" />';
							$form_out .= '</label>';
							$form_out .= '<small>' . sprintf( esc_html__( 'Total number of items ordered is %1$s. Please select how many items will be included in your warranty request.', 'xforwoocommerce' ), absint( $item['qty'] ) ) . '</small>';
							$form_out .= '</p>';
						}
?>
						<div class="wcwar_warranty woocommerce">

							<h2><?php printf( esc_html__( 'Order #%s', 'xforwoocommerce' ), $order->get_order_number() ); ?> - <?php echo esc_html( $item['name'] ); ?></h2>
							
							<form action="<?php the_permalink(); ?>" method="POST" enctype="multipart/form-data">
<?php
								$this->_show_order_info( $order, $item );

								if ( $has_error === true ) {
									$this->_dump_and_continue(8);
								}

								if ( isset( $form_out ) ) {
									echo wp_kses_post( $form_out );
								}

								if ( SevenVXGet()->get_option( 'wcwar_enable_returns', 'warranties_and_returns', 'no' ) == 'yes' && self::hlp_valid_return( $curr_complete, $item ) === true ) {
									$this->__get_html_return_form();

								}

								if ( !isset( $only_return ) ) {
									$this->_get_html_warranty_form();
								}

								$this->_get_html_warranty_form_submit();
?>
							</form>

						</div>
<?php

					}
					else {
?>
						<div class="wcwar_warranty">
							<?php $this->_dump_and_continue(7); ?>
						</div>
<?php
					}

				}
				else if ( $curr_guests == 'yes' ) {
					$this->_get_html_warranty_guest_form();
				}
				else {
?>
					<div class="wcwar_warranty">
						<?php $this->_dump_and_continue(10); ?>
					</div>
<?php
				}

			}

			return ob_get_clean();

		}

		function _show_order_info( $order, $item ) {
?>
			<p class="order-info">
<?php
				printf(
					esc_html__( 'Order #%s was placed on %s and is currently %s. To request a warranty for %s please fill in the form below.', 'xforwoocommerce' ),
					'<mark class="order-number">' . esc_html( $order->get_order_number() ) . '</mark>',
					'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
					'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>',
					$item['name']
				);
?>
			</p>
<?php
		}

		function _get_html_warranty_guest_form() {
?>
			<div class="wcwar_warranty woocommerce">

				<form action="<?php the_permalink(); ?>" method="POST">

				<p class="wcwar_form_info">
						<span class="wcwar_info_icon"><i class="wcwar-info"></i></span> <?php esc_html_e( 'Warranty requests for users that are not logged in are allowed. Please fill in the form below to request a warranty for you order.', 'xforwoocommerce' ); ?>
					</p>

					<p>
						<label for="wcwar_guest_email"><strong><?php esc_html_e( 'Enter your E-Mail address', 'xforwoocommerce' ); ?><span class="wcwar_required"><?php esc_html_e( 'Required', 'xforwoocommerce' ); ?></span></strong>
						<input id="wcwar_guest_email" name="email" type="text"/></label>
					</p>

					<p>
						<label for="wcwar_guest_order_id"><strong><?php esc_html_e( 'Enter your order ID', 'xforwoocommerce' ); ?><span class="wcwar_required"><?php esc_html_e( 'Required', 'xforwoocommerce' ); ?></span></strong>
						<input id="wcwar_guest_order_id" name="order_id" type="text"/></label>
						<small><em><?php esc_html_e( '* Please fill in all required fields to continue.', 'xforwoocommerce' ); ?></em></small>
					</p>

					<p>
						<input name="war_guest" type="hidden" value="true" />
						<input value="<?php esc_html_e( 'Continue', 'xforwoocommerce' ); ?>" type="submit" class="button" />
					</p>

				</form>

			</div>
<?php
		}

		function _get_html_warranty_form_submit() {
?>
			<p class="wcwar_submit_fields">
				<input name="order_id" type="hidden" value="<?php echo absint( $_GET['order_id'] ); ?>" />
<?php
				if ( $_GET['item_id'] !== '-1' ) {
?>
					<input name="item_id" type="hidden" value="<?php echo absint( $_GET['item_id']  ); ?>" />
<?php
				}

				if ( isset( $_GET['multiple'] ) && $_GET['multiple'] == '1' ) {
?>
					<input name="multiple" type="hidden" value="1" />
<?php
				}
?>
				<input name="war_submit" type="hidden" value="true" />
				<input value="<?php esc_html_e( 'Submit Request', 'xforwoocommerce' ); ?>" type="submit" class="button" />
			</p>
<?php
		}

		function __get_html_return_form() {
?>
				<p class="wcwar_return">
					<strong><?php esc_html_e( 'Return is still available', 'xforwoocommerce' ); ?></strong>
					<label for="wcwar_return">
						<input type="checkbox" id="wcwar_return" name="wcwar_return" /> <?php esc_html_e( 'Return item', 'xforwoocommerce' ); ?>
					</label><br/>
					<small><?php esc_html_e( 'Item returns are available in this shop. If you just want to return an item to the store please check this option.', 'xforwoocommerce' ); ?></small>
				</p>
				
				<div id="wcwar_return_form">
					<?php $this->_get_form_html( $this->__get_return_form_option() ); ?>
				</div>
			<?php
		}

		function _get_html_warranty_form() {
?>
			<div id="wcwar_warranty_form">
				<?php $this->_get_form_html( $this->__get_warranty_form_option() ); ?>
			</div>
<?php
		}
		
		function _get_form_html( $form ) {
									
			foreach ( $form['fields'] as $cfld ) {
					if ( $cfld['required'] === true ) {
						$req = '<span class="wcwar_required">' . esc_html__( 'Required', 'xforwoocommerce' ) . '</span>'; // OK
					}
					else $req = '';
?>
				<p class="wcwar_form_field">
<?php
					switch ( $cfld['field_type'] ) {

						case 'text' :
?>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>"><strong><?php echo esc_html( $cfld['label'] ) . $req; ?></strong>
								<input type="text" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" />
							</label>
<?php
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
?>
								<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
<?php
							}

						break;

						case 'paragraph' :
						?>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>"><strong><?php echo esc_html( $cfld['label'] ) . $req; ?></strong>
								<textarea id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>"></textarea>
							</label>
						<?php
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
						?>
							<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
						<?php
							}
						break;

						case 'radio' :
						?>
							<strong><?php echo esc_html( $cfld['label'] ) . $req; ?></strong>
						<?php
							$i=0;
							foreach ( $cfld['field_options']['options'] as $cf ) {
							?>
								<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ) . $i; ?>">
									<input type="radio" id="wcwar_<?php echo esc_attr( $cfld['cid'] ) . $i; ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $cf['label'] ); ?>
								</label>
								<br/>
							<?php
							$i++;
							}
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
						?>
							<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
						<?php
						}
						break;

						case 'checkboxes' :
						?>
							<strong><?php echo esc_html( $cfld['label'] ) . $req; ?></strong>
						<?php
							$i=0;
							foreach ( $cfld['field_options']['options'] as $cf ) {
							?>
								<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ) . '_' . $i; ?>">
									<input type="checkbox" id="wcwar_<?php echo esc_attr( $cfld['cid'] ) . '_' . $i; ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ) . '_' . $i; ?>" /> <?php echo esc_html( $cf['label'] ); ?>
								</label>
								<br/>
							<?php
								$i++;
							}
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
						?>
							<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
						<?php
							}
						break;

						case 'dropdown' :
						?>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>">
								<strong><?php echo esc_attr( $cfld['label'] ) . $req; ?></strong>
								<select id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>">
								<?php
									$i=0;
									foreach ( $cfld['field_options']['options'] as $cf ) {
								?>
								<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $cf['label'] ); ?></option>
							<?php
								$i++;
								}
							?>
								</select>
							</label>
						<?php
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
						?>
							<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
						<?php
							}
						break;

						case 'address' :

							echo '<strong>' . esc_html( $cfld['label'] ) . $req . '</strong>';
						?>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_address" class="double"><?php esc_html_e( 'Address', 'xforwoocommerce' ); ?>
								<input type="text" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_address" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_address" />
							</label>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_city" class="single"><?php esc_html_e( 'City', 'xforwoocommerce' ); ?>
								<input type="text" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_city" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_city" />
							</label>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_zip" class="single"><?php esc_html_e( 'Zipcode', 'xforwoocommerce' ); ?>
								<input type="text" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_zip" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_zip" />
							</label>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_state" class="single"><?php esc_html_e( 'State / Province / Region', 'xforwoocommerce' ); ?>
								<input type="text" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_state" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_state" />
							</label>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_country" class="single"><?php esc_html_e( 'Country', 'xforwoocommerce' ); ?>
								<select id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_country" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>_country">
							<?php
								$countries = WC()->countries->get_allowed_countries();
								foreach ( $countries as $k => $v ) {
									printf( '<option value="%1$s">%2$s</option>', esc_attr( $k ), esc_html( $v ) );
								}
								
							?>
								</select>
							</label>
						<?php
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
						?>
							<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
						<?php
							}

						break;

						case 'file':
?>
							<label for="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>"><strong><?php echo esc_html( $cfld['label'] ) . $req; ?></strong>
								<input type="file" id="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" name="wcwar_<?php echo esc_attr( $cfld['cid'] ); ?>" accept="image/*" />
							</label>
<?php
							if ( isset( $cfld['description'] ) && $cfld['description'] !== '' ) {
?>
								<small><?php echo wp_kses_post( $cfld['description'] ); ?></small>
<?php
							}

						break;

						default :
						break;

					}
				?>
				</p>
			<?php

			}
		}

		function __get_strings() {
			return array(

				0 => array(
					'info',
					esc_html__( 'Invalid request. Please try again.', 'xforwoocommerce' ),
				),

				1 => array(
					'info',
					esc_html__( 'Your warranties will be available once your order is complete.', 'xforwoocommerce' ),
				),

				2 => array(
					'error',
					esc_html__( 'There is no warranty for this item.', 'xforwoocommerce' ),
				),

				3 => array(
					'error',
					esc_html__( 'Warranty for this item has expired.', 'xforwoocommerce' )
				),

				4 => array(
					'error',
					esc_html__( 'There is no warranty or return policy for this item.', 'xforwoocommerce' )
				),

				5 => array(
					'info',
					esc_html__( 'Product is not selected. Please visit your account page at this', 'xforwoocommerce' )
				),

				6 => array(
					'error',
					esc_html__( 'There is no valid warranty for any of the ordered items.', 'xforwoocommerce' )
				),

				7 => array(
					'error',
					esc_html__( 'You cannot make this request.', 'xforwoocommerce' )
				),

				8 => array(
					'error',
					esc_html__( 'Please fill in the required fields.', 'xforwoocommerce' )
				),

				9 => array(
					'error',
					esc_html__( 'There is no warranty for this item.', 'xforwoocommerce' ) . ' ' . esc_html__( 'However you can still return it to the store as its return warranty is still valid.', 'xforwoocommerce' )
				),

				10 => array(
					'error',
					esc_html__( 'Warranty requests for users that are not logged in are not allowed. Please login to continue.', 'xforwoocommerce' )
				),

				11 => array(
					'error',
					esc_html__( 'Warranty already requested. View request status on this', 'xforwoocommerce' )
				),

				12 => array(
					'error',
					esc_html__( 'Warranty has expired for item', 'xforwoocommerce' )
				),

				13 => array(
					'error',
					esc_html__( 'Return period has expired for item', 'xforwoocommerce' )
				),

				14 => array(
					'success',
					esc_html__( 'Return request accepted. Go back to your order', 'xforwoocommerce' )
				),

				15 => array(
					'success',
					esc_html__( 'Warranty request accepted. Go back to your order', 'xforwoocommerce' )
				),

				16 => array(
					'error',
					esc_html__( 'This item has already been returned to the store.', 'xforwoocommerce' )
				),

				17 => array(
					'error',
					esc_html__( 'Return already requested but is still pending review. Please be patient.', 'xforwoocommerce' )
				),

				18 => array(
					'error',
					esc_html__( 'Return already requested. View request status on this', 'xforwoocommerce' )
				),

			);
		}

		function _get_notice_string( $n=0 ) {
			if ( !isset( self::$settings['strings'][$n] ) ) {
				self::$settings['strings'] = $this->__get_strings();
			}

			return isset( self::$settings['strings'][$n] ) ? self::$settings['strings'][$n] : false;
		}

		function _dump_and_close( $n=0, $a='' ) {
			$s = $this->_get_notice_string($n);
?>
			<p class="wcwar_form_<?php echo esc_attr( $s[0] ); ?>">
				<span class="wcwar_info_icon"><i class="wcwar-<?php echo esc_attr( $s[0] ); ?>"></i></span> 
				<?php echo esc_html( $s[1] ) . wp_kses_post( $a ); ?>
			</p>
<?php
			return ob_get_clean();
		}
		
		function _dump_and_continue( $n=0, $a='' ) {
			$s = $this->_get_notice_string($n);
?>
			<p class="wcwar_form_<?php echo esc_attr( $s[0] ); ?>">
				<span class="wcwar_info_icon"><i class="wcwar-<?php echo esc_attr( $s[0] ); ?>"></i></span> 
				<?php echo esc_html( $s[1] ) . wp_kses_post( $a ); ?>
			</p>
<?php
		}

		function war_pending_requests() {
			global $menu;

			$found = get_term_by( 'slug', 'new', 'wcwar_warranty' );

			if ( $found->count ) {

				$suffix = "?post_type=wcwar_warranty_req";

				$key = self::hlp_recursive_array_search( "edit.php$suffix", $menu );

				if( !$key ) {
					return;
				}

				$menu[$key][0] .= sprintf(
					'<span class="update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
					absint( $found->count )
				);
			}

		}

		function war_add_menu_icon_styles() {
	?>
		<style type="text/css">
		#menu-posts-wcwar_warranty_req .dashicons-before:before {font-family:'wcwar'!important;content: '\f008'!important;}
		</style>
	<?php
		}
		
		function war_email( $order, $sent_to_admin, $plain_text ) {

			if ( sizeof( $order->get_items() ) > 0 ) {

				$id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

				?>
				<div class="war_warranty war_order">
				<h3><?php esc_html_e( 'Available Warranties for this Order', 'xforwoocommerce' ); ?></h3>
				<?php
					if ( !in_array( $order->get_status(), apply_filters( 'wcwar_warranty_order_status', array( 'completed' ), $order ) ) ) {
				?>
				<p>
					<?php esc_html_e( 'Your warranties will be available once your order is complete.', 'xforwoocommerce' ); ?>
				</p>
				<?php
					$curr_complete = get_post_meta( $id, '_ordered_date', true );

					$curr_notallowed = true;
				}
				else {
					$curr_complete = get_post_meta( $id, '_completed_date', true );

					$curr_notallowed = false;
				}

				$i=0;
				foreach( $order->get_items() as $key => $item ) {
					$i++;

					$curr_status = self::hlp_valid_warranty( $curr_complete, $item);

					if ( $curr_notallowed == false && $curr_status && $curr_status === 'nowar' ) {
				?>
					<p>
						<?php esc_html_e( 'There is no warranty for this item.', 'xforwoocommerce' ); ?>
					</p>
				<?php
						return;
					}
					else if ( $curr_notallowed == false && $curr_status ) {
				?>
					<p>
						<?php esc_html_e( 'Warranty for this item has expired.', 'xforwoocommerce' ); ?>
					</p>
				<?php
						return;
					}

					$curr_args = array(
						'post_type' => 'wcwar_warranty_req',
						'post_status' => 'any',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => '_wcwar_warranty_order_id',
								'value' => $id,
							),
							array(
								'key' => '_wcwar_warranty_product_id',
								'value' => $key,
							)
						)
					 );
					$curr_req = get_posts( $curr_args );

					if ( !empty( $curr_req ) ) {
						echo esc_html__( 'Warranty already requested. View request status on this', 'xforwoocommerce' ) . ' <a href="' . esc_url( $curr_req[0]->guid ) . '">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.';
						continue;
					}

					$curr_warranty = json_decode( $item['wcwar_warranty'], true );

					if ( $curr_warranty['type'] == 'preset_warranty' ) {
						$curr_preset = get_term_meta( $curr_warranty['preset'], '_wcwar_warranty', true );
						$curr_warranty = array_merge( $curr_preset, $curr_warranty);
					}
					else if ( $curr_warranty['type'] == 'quick_warranty' ) {

					}

					if ( isset( $curr_warranty['quick'] ) && $curr_warranty['quick'] !== '' ) {
						if ( $curr_warranty['quick'] == 'included_warranty' && isset( $curr_warranty['included_warranty'] ) ) {
					?>
							<h3><?php echo '#' . $i . ' ' . esc_html( $item['name'] ) . ' ' . esc_html__( 'Warranty', 'xforwoocommerce' ) . ' - ' . esc_html__( 'Included Warranty', 'xforwoocommerce' ); ?></h3>
							<p>
								<?php if ( isset( $curr_warranty['included_warranty']['thumb'] ) ) { ?>
									<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['included_warranty']['thumb'] ); ?>" />
								<?php } ?>
								<span>
									<span>
										<?php echo esc_html( $curr_warranty['included_warranty']['period'] ) . ' '; ?>
									</span>
									<?php echo self::hlp_get_warranty_string( $curr_warranty['included_warranty']['period'], $curr_warranty['included_warranty']['type'] ); ?>
								</span><br/>
								<?php if ( isset( $curr_warranty['included_warranty']['desc'] ) ) { ?>
									<small><?php echo wp_kses_post( $curr_warranty['included_warranty']['desc'] ); ?></small><br/>
								<?php } ?>
								<?php
									if ( $curr_notallowed === false ) {
										$curr_args = array(
											'order_id' => $id,
											'item_id' => $key
										);
										$curr_request = esc_url( add_query_arg( $curr_args, get_permalink( self::wpml_get_id( SevenVXGet()->get_option_autoload( 'war_settings_page' ) ) ) ) ); // OK

										echo esc_html( $item['name'] ) . ' ' . esc_html__( 'warranty is still valid. Request your warranty at this', 'xforwoocommerce' ) . ' ' . '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.';
									}
								?>
							</p>
					<?php
						}
						else if ( $curr_warranty['quick'] == 'paid_warranty' && isset( $curr_warranty['paid_warranty'] ) ) {
							$curr = $curr_warranty['paid_warranty']['selected'];
							if ( $curr !== 'no_warranty' ) {
					?>
							<h3><?php echo '#' . $i . ' ' . esc_html( $item['name'] ) . ' ' . esc_html__( 'Warranty', 'xforwoocommerce' ) . ' - ' . esc_html__( 'Included Warranty', 'xforwoocommerce' ); ?></h3>
							<p>
								<?php if ( isset( $curr_warranty['paid_warranty'][$curr]['thumb'] ) ) { ?>
									<img width="64" height="auto" src="<?php echo esc_url( $curr_warranty['paid_warranty'][$curr]['thumb'] ); ?>" />
								<?php } ?>
								<span>
									<span>
										<?php echo esc_html( $curr_warranty['paid_warranty'][$curr]['period'] ) . ' '; ?>
									</span>
									<?php echo self::hlp_get_warranty_string( $curr_warranty['paid_warranty'][$curr]['period'], $curr_warranty['paid_warranty'][$curr]['type'] ); ?>
								</span><br/>
								<?php if ( isset( $curr_warranty['paid_warranty'][$curr]['desc'] ) ) { ?>
									<small><?php echo wp_kses_post( $curr_warranty['paid_warranty'][$curr]['desc'] ); ?></small><br/>
								<?php } ?>
								<?php
									if ( $curr_notallowed === false ) {
										$curr_args = array(
											'order_id' => $id,
											'item_id' => $key
										);
										$curr_request = esc_url( add_query_arg( $curr_args, get_permalink( self::wpml_get_id( SevenVXGet()->get_option_autoload( 'war_settings_page' ) ) ) ) );
										echo esc_html( $item['name'] ) . ' ' . esc_html__( 'warranty is still valid. Request your warranty at this', 'xforwoocommerce' ) . ' ' . '<a href="' . esc_url( $curr_request ) . '" class="wcwar_request">' . esc_html__( 'link', 'xforwoocommerce' ) . '</a>.';
									}
								?>
							</p>
					<?php
							}
						}
					}
				}
			}
		?>
		</div>
		<?php
		}

		function war_cart_price( $val, $cart_item, $cart_item_key ) {

			if ( isset( $cart_item['wcwar_pa_price'] ) ) {
				self::$settings['add_msg'] = true;
				return $val . '<small> *(+ ' . wc_price( $cart_item['wcwar_pa_price'] ) . ')</small>';
			}

			return( $val );

		}

		function war_cart_help() {
			if ( isset( self::$settings['add_msg'] ) ) {
				printf( '
					<tr>
						<td colspan="6">
							<p><small>* %1$s</small></p>
						</td>
					</tr>', esc_html__( 'Your purchase has included paid warranties!', 'xforwoocommerce' ) );
			}
		}

		function wpml_get_id( $id ) {
			if( function_exists( 'icl_object_id' ) ) {
				return icl_object_id( $id, 'page', true );
			}
			else {
				return $id;
			}
		}

		public static function version_check( $version = '3.0.0' ) {
			if ( class_exists( 'WooCommerce' ) ) {
				global $woocommerce;
				if( version_compare( $woocommerce->version, $version, ">=" ) ) {
					return true;
				}
			}
			return false;
		}

	}

	if ( !function_exists( 'XforWC' ) ) {
		include_once( 'includes/svx-settings/svx-get.php' );
	}

	//function wcwar_init_plugin() {
		include_once( 'includes/war-public.php' );
		$GLOBALS['wc_warranties_and_returns'] = new XforWC_Warranties_Returns();
	//}
	//add_action( 'woocommerce_init', 'wcwar_init_plugin');


	function svx_warranties_and_returns_load_settings() {
		if ( !function_exists( 'XforWC' ) ) {
			include_once( 'includes/svx-settings/svx-settings.php' );
		}
	}

	if ( is_admin() ) {
		add_action( 'init', 'svx_warranties_and_returns_load_settings', 100 );
		include_once( 'includes/war-settings.php' );
	}

	if ( !function_exists( 'mnthemes_add_meta_information' ) ) {
		function mnthemes_add_meta_information_action() {
			echo '<meta name="generator" content="' . esc_attr( implode( ', ', apply_filters( 'mnthemes_add_meta_information_used', array() ) ) ) . '"/>';
		}
		function mnthemes_add_meta_information() {
			add_action( 'wp_head', 'mnthemes_add_meta_information_action', 99 );
		}
		mnthemes_add_meta_information();
	}

endif;
