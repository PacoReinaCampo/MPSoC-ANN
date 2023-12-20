<?php
/**
 * Mini Cart class
 *
 * @author YITH
 * @package YITH WooCommerce Added to Cart Popup Premium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WACP_Mini_Cart' ) ) {
	/**
	 * Mini Cart class
	 * The class manage mini cart feature behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WACP_Mini_Cart {

        /**
         * Action update mini cart
         *
         * @var string
         * @since 1.4.0
         */
        public $action_update_cart = 'yith_wacp_update_mini_cart';

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

            add_action( 'wc_ajax_' . $this->action_update_cart, array( $this, 'update_mini_cart_ajax' ) );
            add_action( 'wp_ajax_nopriv' . $this->action_update_cart, array( $this, 'update_mini_cart_ajax' ) );
            add_filter( 'yith_wacp_frontend_script_localized_args', array( $this, 'add_mini_cart_args' ), 10, 1 );
            add_filter( 'yith_wacp_add_to_cart_success_data', array( $this, 'add_data_to_response' ), 10, 4 );
			add_action( 'wp_footer', array( $this, 'add_mini_cart_template' ) );
		}

        /**
         * Add mini data cart to response
         *
         * @since 1.4.0
         * @author Francesco Licandro
         * @param array $datas
         * @param \WC_Product $product
         * @param string $layout
         * @param integer|string $quantity
         * @return array
         */
        public function add_data_to_response( $datas, $product, $layout, $quantity ) {
            $datas['yith_wacp_message_cart']    = ( $layout != 'product' ) ? $datas['yith_wacp_message'] : YITH_WACP_Frontend_Premium()->get_popup_content( $product, 'cart', $quantity );
            $datas['yith_wacp_cart_items']      = ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
            return $datas;
        }

        /**
         * Add arguments to localized array
         *
         * @since 1.4.0
         * @author Francesco Licandro
         * @param array $args
         * @return array
         */
        public function add_mini_cart_args( $args ) {
            $args['actionUpdateMiniCart']   = $this->action_update_cart;
            $args['mini_cart_position']     = get_option( 'yith-wacp-mini-cart-position', array( 'top' => 20, 'left' => 97 ) );
            return $args;
        }

        /**
         * Update mini cart using Ajax
         *
         * @since 1.4.0
         * @author Francesco Licandro
         */
        public function update_mini_cart_ajax(){
            if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_update_cart ){
                die();
            }

            wp_send_json( array(
                'html'  => YITH_WACP_Frontend_Premium()->get_popup_content( false, 'cart' ),
                'items' => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0
            ) );
        }

        /**
         * Add mini cart template
         *
         * @since 1.4.0
         * @author Francesco Licandro
         */
		public function add_mini_cart_template(){

		    if( is_cart() || is_checkout() ) {
		        return;
            }

            wc_get_template( 'yith-wacp-mini-cart.php', array(
                'items'         => ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0,
                'icon'          => get_option( 'yith-wacp-mini-cart-icon', YITH_WACP_ASSETS_URL . '/images/mini-cart.png' ),
                'show_counter'  => get_option( 'yith-wacp-mini-cart-show-counter', 'yes' ) == 'yes'
            ), '', YITH_WACP_TEMPLATE_PATH . '/' );
        }
	}
}