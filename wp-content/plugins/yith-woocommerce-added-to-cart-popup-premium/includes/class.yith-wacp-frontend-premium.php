<?php
/**
 * Frontend Premium class
 *
 * @author YITH
 * @package YITH WooCommerce Added to Cart Popup Premium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WACP_Frontend_Premium' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WACP_Frontend_Premium extends YITH_WACP_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WACP_Frontend_Premium
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WACP_VERSION;

		/**
		 * Plugin enable on single product page
		 *
		 * @var boolean
		 * @since 1.1.0
		 */
		public $enable_single = false;

		/**
		 * Plugin enable on archive
		 *
		 * @var boolean
		 * @since 1.1.0
		 */
		public $enable_loop = false;

		/**
		 * Remove action
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_remove = 'yith_wacp_remove_item_cart';

		/**
		 * Add to cart action
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_add = 'yith_wacp_add_item_cart';

        /**
         * Add to cart action for YITH WooCommerce Frequently Bought Together
         *
         * @var string
         * @since 1.4.0
         */
        public $action_add_wfbt = 'yith_wacp_add_wfbt_cart';

		/**
		 * Update cart popup action
		 *
		 * @var string
		 * @since 1.3.0
		 */
		public $action_update = 'yith_wacp_update_item_cart';

        /**
         * Floating cart class instance
         *
         * @var \YITH_WACP_Floating_Cart|null
         * @since 1.4.0
         */
        public $floating_cart = null;

        /**
         * Last cart item key added
         *
         * @var string
         * @since 1.4.7
         */
        public $last_cart_item = '';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WACP_Frontend_Premium
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct();

			class_exists( 'YITH_WACP_Mini_Cart' ) && $this->floating_cart = new YITH_WACP_Mini_Cart();
			$this->enable_single = get_option( 'yith-wacp-enable-on-single' ) == 'yes';
			$this->enable_loop   = get_option( 'yith-wacp-enable-on-archive' ) == 'yes';

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_premium' ), 20 );

			if( version_compare( WC()->version, '2.4', '>=' ) ){
				add_action( 'wc_ajax_' . $this->action_remove, array( $this, 'remove_item_cart_ajax' ) );
				add_action( 'wc_ajax_' . $this->action_update, array( $this, 'update_item_cart_ajax' ) );
			}
			else {
				add_action( 'wp_ajax_' . $this->action_remove, array( $this, 'remove_item_cart_ajax' ) );
				add_action( 'wp_ajax_' . $this->action_update, array( $this, 'update_item_cart_ajax' ) );
			}
			// no priv actions
			add_action( 'wp_ajax_nopriv' . $this->action_remove, array( $this, 'remove_item_cart_ajax' ) );
			add_action( 'wp_ajax_nopriv' . $this->action_update, array( $this, 'update_item_cart_ajax' ) );
			// Add product from single product page Ajax
			add_action( 'wp_loaded', array( $this, 'add_item_cart_ajax' ), 30 );
			// Add YITH WooCommerce Frequently Bought Products
            add_action( 'yith_wfbt_group_added_to_cart', array( $this, 'add_wfbt_cart_ajax' ), 100, 3 );
			// prevent redirect after ajax add to cart
			add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'prevent_redirect_url' ), 100, 1 );
			// prevent woocommerce option Redirect to the cart page after successful addition
			add_filter( 'pre_option_woocommerce_cart_redirect_after_add', array( $this, 'prevent_cart_redirect' ), 10, 2 );
			// prevent add to cart ajax
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'exclude_single' ) );
			// add popup message
			add_action( 'yith_wacp_before_popup_content', array( $this, 'add_message' ), 10, 1 );
			// add action button to popup
			add_action( 'yith_wacp_after_popup_content', array( $this, 'add_actions_button' ), 10, 1 );
			// add related to popup
			add_action( 'yith_wacp_after_popup_content', array( $this, 'add_related' ), 20, 1 );
			// add cart info
            add_action( 'yith_wacp_add_cart_info', array( $this, 'add_cart_info' ), 10 );
			// add args to popup template
			add_filter( 'yith_wacp_popup_template_args', array( $this, 'popup_args' ), 10, 1 );
			// store last cart item key
			add_action( 'woocommerce_add_to_cart', array( $this, 'store_cart_item_key' ), 10, 6 );
			// let's filter item data for cart
            add_filter( 'woocommerce_product_variation_get_name', array( $this, 'filter_get_name_product' ), 1, 2 );
			// add param to related query args
			add_filter( 'yith_wacp_related_products_args', array( $this, 'add_related_query_args' ), 10, 1 );
		}

		/**
		 * Enqueue scripts and styles premium
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function enqueue_premium() {

			$min        = ( ! defined('SCRIPT_DEBUG') || ! SCRIPT_DEBUG ) ? '.min' : '';
			$inline_css = yith_wacp_get_style_options();

			wp_add_inline_style( 'yith-wacp-frontend', $inline_css );

			// scroll plugin
			wp_enqueue_style( 'wacp-scroller-plugin-css', YITH_WACP_ASSETS_URL . '/css/perfect-scrollbar.css' );
			wp_enqueue_script( 'wacp-scroller-plugin-js', YITH_WACP_ASSETS_URL . '/js/perfect-scrollbar' . $min .'.js', array('jquery'), '1.3.0', true );

			$selectors = array(
				'body.single-product form.cart:not(.in_loop)',
				'body.single-product form.bundle_form',
				'body.singular-product form.cart',
				'.yith-quick-view.yith-inline form.cart'
			);

			$localized = array(
                'ajaxurl'                => version_compare( WC()->version, '2.4', '>=' ) ? WC_AJAX::get_endpoint( "%%endpoint%%" ) : admin_url( 'admin-ajax.php', 'relative' ),
                'actionAdd'              => $this->action_add,
                'actionRemove'           => $this->action_remove,
                'actionUpdate'           => $this->action_update,
                'loader'                 => apply_filters( 'yith_wacp_loader_image_url', YITH_WACP_ASSETS_URL . '/images/loader.gif' ),
                'enable_single'          => $this->enable_single,
                'is_mobile'              => wp_is_mobile(),
                'popup_size'             => get_option( 'yith-wacp-box-size' ),
                'form_selectors'         => apply_filters( 'yith_wacp_form_selectors_filter', implode(',', $selectors ) ),
                'allow_automatic_popup'  => apply_filters( 'yith_wacp_allow_automatic_popup',true ),
                'open_popup_selectors'   => apply_filters( 'yith_wacp_open_popup_selectors','#yith-wacp-mini-cart' )
            );

			if( get_option( 'yith-wacp-enable-wfbt', 'no' ) == 'yes' ) {
                $localized[ 'actionAddFBT' ]    = $this->action_add_wfbt;
                $localized[ 'nonceFBT' ]        = wp_create_nonce( 'yith_bought_together' );
            }

			wp_localize_script( 'yith-wacp-frontend-script', 'yith_wacp', apply_filters( 'yith_wacp_frontend_script_localized_args', $localized ) );
		}

		/**
		 * Add args to popup template
		 *
		 * @since 1.0.0
		 * @param mixed $args
		 * @return mixed
		 * @author Francesco Licandro
		 */
		public function popup_args( $args ) {
			// set new animation
			$args['animation'] = get_option( 'yith-wacp-box-animation' );

			return $args;
		}

		/**
		 * Get content html for added to cart popup
		 *
		 * @access public
		 * @since 1.0.0
		 * @param boolean|object $product current product added
		 * @param string $layout Layout to load
		 * @param string|integer $quantity Product quantity added
		 * @return mixed
		 * @author Francesco Licandro
		 */
		public function get_popup_content( $product, $layout = '', $quantity = 1 ) {

			! $layout && $layout = get_option( 'yith-wacp-layout-popup', 'product' );

			// set args
			$args = apply_filters( 'yith_wacp_get_popup_content', array(
				'thumb'          		=> get_option( 'yith-wacp-show-thumbnail', 'yes' ) == 'yes',
				'product_info'          => get_option( 'yith-wacp-show-info', 'yes' ) == 'yes',
				'cart_total'     		=> get_option( 'yith-wacp-show-cart-totals', 'yes' ) == 'yes', // leave for retro compatibility
				'cart_shipping'  		=> get_option( 'yith-wacp-show-cart-shipping', 'yes' ) == 'yes',
				'cart_tax'       		=> get_option( 'yith-wacp-show-cart-tax', 'yes' ) == 'yes',
				'product'        		=> $product,
				'quantity'        		=> $quantity,
				'last_cart_item_key'	=> $this->last_cart_item
			) );

			// if $layout is cart just define constant WOOCOMMERCE_CART to get full compatibility
            ( $layout == 'cart' && ! defined( 'WOOCOMMERCE_CART' ) ) && define( 'WOOCOMMERCE_CART', true );

			// add to cart popup
			ob_start();

            do_action( 'yith_wacp_before_popup_content', $product );

			wc_get_template( 'yith-wacp-popup-' . $layout . '.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );

			do_action( 'yith_wacp_after_popup_content', $product );

			return ob_get_clean();
		}

		/**
		 * Added to cart success popup box
		 *
		 * @param array
		 * @return array
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_to_cart_success_ajax( $datas ) {

			if ( ! isset( $_REQUEST['product_id' ] ) || ( ! isset( $_REQUEST['ywacp_is_single'] ) && ! $this->enable_loop  ) ) {
				return $datas;
			}

			$product_id = ( isset( $_REQUEST['variation_id' ] ) && intval( $_REQUEST['variation_id' ] ) ) ? intval( $_REQUEST['variation_id' ] ) : intval( $_REQUEST['product_id' ] );
			// get product
			$product    = wc_get_product( $product_id );
			if( ! $product ) {
                return $datas;
            }
			$product_id = yit_get_base_product_id( $product );
			// check if is excluded
			if( $this->is_in_exclusion( $product_id ) ) {
				return $datas;
			}
			
			// set quantity
			$quantity                   = isset( $_REQUEST['quantity' ] ) ? $_REQUEST['quantity'] : 1;
            $layout                     = get_option( 'yith-wacp-layout-popup', 'product' );
            $datas['yith_wacp_message'] = $this->get_popup_content( $product, $layout, $quantity );

			return apply_filters( 'yith_wacp_add_to_cart_success_data', $datas, $product, $layout, $quantity );
		}

		/**
		 * Action ajax for remove item from cart
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function remove_item_cart_ajax() {

			if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove || ! isset( $_REQUEST['item_key'] ) ) {
				die();
			}

			$item_key = sanitize_text_field( $_REQUEST['item_key'] );

			// remove item
			WC()->cart->remove_cart_item( $item_key );
			$return = '';
			
			if( ! WC()->cart->is_empty() ) {
				$cart = WC()->cart->get_cart();
				$last = end( $cart );
				$product = isset( $last['product_id'] ) ? wc_get_product( $last['product_id'] ) : false;

				// remove popup message
				remove_action( 'yith_wacp_before_popup_content', array( $this, 'add_message' ), 10 );

				$return = $this->get_popup_content( $product, 'cart' );
			}
			
			wp_send_json( array(
                'html'  => $return,
                'items'  => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0
            ) );
		}

		/**
		 * Action ajax for update item from cart
		 *
		 * @access public
		 * @since 1.3.0
		 * @author Francesco Licandro
		 */
		public function update_item_cart_ajax() {

			if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_update || ! isset( $_REQUEST['item_key'] ) ) {
				die();
			}

			$item_key = sanitize_text_field( $_REQUEST['item_key'] );
			$qty      = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( "/[^0-9\.]/", '', $_REQUEST['qty'] ) ), $item_key );;

			$item = WC()->cart->get_cart_item( $item_key );
			if( ! empty( $item ) ) {
				$passed_validation 	= apply_filters( 'woocommerce_update_cart_validation', true, $item_key, $item, $qty );
				// if validation passed -> set new quantity
				$passed_validation && WC()->cart->set_quantity( $item_key, $qty, true );
			}
			else {
				$cart = WC()->cart->get_cart();
				$item = end( $cart );
			}

			$product = isset( $item['product_id'] ) ? wc_get_product( $item['product_id'] ) : false;

			// remove popup message
			remove_action( 'yith_wacp_before_popup_content', array( $this, 'add_message' ), 10 );
			add_action( 'yith_wacp_before_popup_content', 'wc_print_notices', 10 ); // print notices

            wp_send_json( array(
                'html'  => $this->get_popup_content( $product, 'cart' ),
                'items'  => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0
            ) );
		}

		/**
		 * Action ajax for add to cart in single product page
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function add_item_cart_ajax() {

			if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add || ! isset( $_REQUEST['add-to-cart'] ) ) {
				return;
			}

			$error = $popup_html = '';
            $product_id = isset( $_REQUEST['product_id' ] ) ? $_REQUEST['product_id' ] : $_REQUEST['add-to-cart'];

			// get WooCommerce error notice
			$error_notices = wc_get_notices( 'error' );

            if( ! $this->last_cart_item && function_exists( 'wc_gfpa' ) && method_exists( wc_gfpa(), 'get_gravity_form_data' ) ){
                // Check if we need a gravity form!
                $context           = ( isset( $_POST['add-variations-to-cart'] ) && $_POST['add-variations-to-cart'] ) ? 'bulk' : 'single';
                $gravity_form_data = wc_gfpa()->get_gravity_form_data( $product_id, $context );
                if( ! empty( $gravity_form_data ) ){
                    $error_notices = array( __( 'There was a problem with your submission. Please check required fields below.', 'yith-woocommerce-added-to-cart-popup' ) );
                }
            }

			if( $error_notices ){
				// print notice
				ob_start();
				foreach( $error_notices as $value ) {
					wc_print_notice( $value, 'error' );
				}
				$error = ob_get_clean();
			}
			else {
				// trigger action for added to cart in ajax
				do_action( 'woocommerce_ajax_added_to_cart', intval( $_REQUEST['add-to-cart'] ) );

				$product_id = isset( $_REQUEST['product_id' ] ) ? $_REQUEST['product_id' ] : $_REQUEST['add-to-cart'];
                ! empty( $_REQUEST['variation_id' ] ) && $product_id = $_REQUEST['variation_id' ];
                // get product
                $product = wc_get_product( intval( $product_id ) );

                // set quantity
                $quantity   = isset( $_REQUEST['quantity' ] ) ? $_REQUEST['quantity'] : 1;
                $layout     = get_option( 'yith-wacp-layout-popup', 'product' );
                $popup_html = $this->get_popup_content( $product, $layout, $quantity );
			}

			// clear other notice
			wc_clear_notices();

			wp_send_json( array(
				'error'             => $error,
				'html'              => $popup_html,
                'cart_html'         => ( ! $popup_html || $layout != 'product' ) ? $popup_html : YITH_WACP_Frontend_Premium()->get_popup_content( $product, 'cart', $quantity ),
                'items'             => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0
			) );
		}

        /**
         * Action ajax for frequently bought together form
         *
         * @access public
         * @since 1.4.0
         * @author Francesco Licandro
         * @param array $products_added
         * @oaram \WC_Product $main_product
         * @param array $offered
         */
        public function add_wfbt_cart_ajax( $products_added, $main_product, $offered ) {

            if( ! isset( $_REQUEST['actionAjax'] ) || $_REQUEST['actionAjax'] != $this->action_add_wfbt ) {
                return;
            }

            $error = $popup_html = '';

            // get woocommerce error notice
            $error_notices = wc_get_notices( 'error' );
            if( $error_notices ){
                // print notice
                ob_start();
                foreach( $error_notices as $value ) {
                    wc_print_notice( $value, 'error' );
                }
                $error = ob_get_clean();
            }
            else {
                $product = wc_get_product( $main_product );
                $product->is_type( 'variation' ) && $product = wc_get_product( $product->get_parent_id() );
                $popup_html = $this->get_popup_content( $product, 'cart' );
            }

            // clear other notice
            wc_clear_notices();

            wp_send_json( array(
                'error' => $error,
                'html'  => $popup_html,
                'items' => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0
            ) );
        }

		/**
		 * Prevent url redirect in add to cart ajax for single product page
		 *
		 * @since 1.0.0
		 * @param $url
		 * @return boolean | string
		 * @author Francesco Licandro
		 */
		public function prevent_redirect_url( $url ) {

			if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == $this->action_add ) {
				return false;
			}

			return $url;
		}

		/**
		 * Add message before popup content
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object $product
		 * @author Francesco Licandro
		 */
		public function add_message( $product ) {
			// get message
			$message = get_option( 'yith-wacp-popup-message' );

			if( ! $message || ! $product ) {
				return;
			}

			ob_start();
			?>

			<div class="yith-wacp-message">
				<span><?php echo $message ?></span>
			</div>

			<?php
			$html = ob_get_clean();

			echo apply_filters( 'yith_wacp_message_popup_html', $html, $product );
		}

		/**
		 * Add action button to popup
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object $product
		 * @author Francesco Licandro
		 */
		public function add_actions_button( $product) {

			$cart = get_option( 'yith-wacp-show-go-cart' ) == 'yes';
			$checkout = get_option( 'yith-wacp-show-go-checkout' ) == 'yes';
			$continue = get_option( 'yith-wacp-show-continue-shopping' ) == 'yes';

			if( ! $cart && ! $checkout && ! $continue ) {
				return;
			}

			$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
			$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : WC()->cart->get_checkout_url();

			// let user filter url
			$args = array(
				'cart'                  => $cart,
				'cart_url'              => apply_filters( 'yith_wacp_go_cart_url', $cart_url ),
				'checkout'              => $checkout,
				'checkout_url'          => apply_filters( 'yith_wacp_go_checkout_url', $checkout_url ),
				'continue'              => $continue,
				'continue_shopping_url' => apply_filters( 'yith_wacp_continue_shopping_url', '#' )
			);

			ob_start();
			wc_get_template( 'yith-wacp-popup-action.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );
			$html = ob_get_clean();

			echo apply_filters( 'yith_wacp_actions_button_html', $html, $product );
		}

		/**
		 * Add suggested/related product to popup
		 *
		 * @access public
		 * @since 1.1.0
		 * @param WC_Product $product
		 * @author Francesco Licandro
		 */
		public function add_related( $product ) {

			if( get_option( 'yith-wacp-show-related' ) != 'yes' || ! $product ) {
				return;
			}

			$number_of_suggested = get_option( 'yith-wacp-related-number', 4 );
			// first check in custom list
			$suggested = get_option( 'yith-wacp-related-products', array() );
			! is_array( $suggested ) && $suggested = explode( ',', $suggested );
			$suggested = array_filter( $suggested ); // remove empty if any

            $product_id = yit_get_base_product_id( $product );
            // get correct product if original is variation
            $product->is_type('variation') && $product = wc_get_product( $product_id );

			// get standard WC related if option is empty
			if( empty( $suggested ) ) {

				$suggested_type = get_option( 'yith-wacp-suggested-products-type', 'related' );

				switch( $suggested_type ) {
					case 'crossell' :
						$suggested = WC()->cart->get_cross_sells();
						break;
					case 'upsell' :
						$suggested = is_callable( array( $product, 'get_upsell_ids' ) ) ? $product->get_upsell_ids() : $product->get_upsells();
						break;
					default :
						$suggested = function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $product_id, $number_of_suggested ) : $product->get_related( $number_of_suggested );
						break;
				}
			}

			if( empty( $suggested ) ) {
				return;
			}

			$args = apply_filters( 'yith_wacp_popup_related_args', array(
				'title'                 => get_option( 'yith-wacp-related-title', '' ),
				'items'                 => $suggested,
				'posts_per_page'        => $number_of_suggested,
				'columns'               => get_option( 'yith-wacp-related-columns', 4 ),
				'current_product_id'    => $product_id,
				'show_add_to_cart'      => get_option( 'yith-wacp-suggested-add-to-cart', 'yes' ) == 'yes'
			), $product );

			wc_get_template( 'yith-wacp-popup-related.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );

		}

		/**
		 * Exclude product from added to cart popup process in single product page
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function exclude_single(){
			global $product;

			$product_id = yit_get_base_product_id( $product );

			if( $this->is_in_exclusion( $product_id ) ) {
				echo '<input type="hidden" name="yith-wacp-is-excluded" value="1" />';
			}
		}

		/**
		 * Check if product is in exclusion list
		 *
		 * @since 1.0.0
		 * @param int $product_id
		 * @return boolean
		 * @author Francesco Licandro
		 */
		public function is_in_exclusion( $product_id ){

			$exclusion_prod = array_filter( explode( ',', get_option( 'yith-wacp-exclusions-prod-list', '' ) ) );
			$exclusion_cat  = array_filter( explode( ',', get_option( 'yith-wacp-exclusions-cat-list', '' ) ) );

			$product_cat = array();
			$product_categories = get_the_terms( $product_id, 'product_cat' );

			if( ! empty( $product_categories ) ) {
				foreach( $product_categories as $cat ) {
					$product_cat[] = $cat->term_id;
				}
			}

            $intersect = array_intersect( $product_cat, $exclusion_cat );
			if( in_array( $product_id, $exclusion_prod) || ! empty( $intersect ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Prevent cart redirect. WC Option Redirect to the cart page after successful addition
		 *
		 * @since 1.1.0
		 * @author Francesco Licandro
		 * @param mixed $value
		 * @param string $option
		 * @return mixed
		 */
		public function prevent_cart_redirect( $value, $option ){
			if( ( is_product() && $this->enable_single ) || $this->enable_loop ) {
				return 'no';
			}

			return $value;
		}

		/**
		 * Store last cart item key
		 *
		 * @since 1.1.1
		 * @author Francesco Licandro
		 * @param string $cart_item_key
		 * @param string|integer $product_id
		 * @param string|integer $quantity
		 * @param string|integer $variation_id
		 * @param array $variation
		 * @param array $cart_item_data
		 */
		public function store_cart_item_key( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

		    if( in_array( '_wjecf_free_product_coupon',$cart_item_data ) )
		        return;

            $this->last_cart_item = $cart_item_key;
		}

		/**
         * Filter item data and add item
         *
         * @since 1.2.1
         * @author Francesco Licandro
         * @param string $value
         * @param object $product
         * @return string
         */
		public function filter_get_name_product( $value, $product ) {
		    // do it only for WC 3.0 or more and if is popup
            // get id
            $id = $product->get_id();

            if( isset( $_REQUEST['ywacp_is_single'] ) && $_REQUEST['ywacp_is_single'] == 'yes'
                && isset( $_REQUEST['variation_id'] ) && intval( $_REQUEST['variation_id'] ) === intval( $id )
                && get_option( 'yith-wacp-layout-popup', 'product' ) == 'product' ){
                // return parent name
                return $product->get_title();
            }

            return $value;
		}

		/**
		 * Add param to related query args
		 *
		 * @since 1.2.2
		 * @author Francesco Licandro
		 * @param array $args
		 * @return array
		 */
		public function add_related_query_args( $args ) {
			
			if( version_compare( WC()->version, '3.0', '>=' ) ) {
				$args['tax_query'] = WC()->query->get_tax_query();
			}
			else {
				$args['meta_query'] = WC()->query->get_meta_query();
			}
			
			return $args;
		}

		/**
         * Add cart info (total, discount, shipping..) to popup template
         *
         * @since 1.4.0
         * @author Francesco Licandro
         */
		public function add_cart_info(){
            $cart_total     = get_option( 'yith-wacp-show-cart-totals', 'yes' ) == 'yes';
            $cart_shipping  = get_option( 'yith-wacp-show-cart-shipping', 'yes' ) == 'yes';
            $cart_tax       = get_option( 'yith-wacp-show-cart-tax', 'yes' ) == 'yes';

            if( ! $cart_total && ! $cart_shipping && ! $cart_tax ){
                return;
            }

            wc_get_template( 'yith-wacp-popup-cart-info.php', array(
                    'cart_info'     => yith_wacp_get_cart_info(),
                    'cart_total'    => $cart_total,
                    'cart_shipping' => $cart_shipping,
                    'cart_tax'      => $cart_tax,
            ), '', YITH_WACP_TEMPLATE_PATH . '/' );
        }
	}
}

/**
 * Unique access to instance of YITH_WACP_Frontend_Premium class
 *
 * @return \YITH_WACP_Frontend_Premium
 * @since 1.0.0
 */
function YITH_WACP_Frontend_Premium(){
	return YITH_WACP_Frontend_Premium::get_instance();
}