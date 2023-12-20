<?php

	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_FloatingCart_Frontend {

        protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
            }
            
            return self::$_instance;
            
		}

		function __construct() {
            add_shortcode( 'floating_cart_xforwc', array( &$this, 'shortcode' ) );

            add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
            add_action( 'wp_footer', array( &$this, 'footer_scripts' ) );

            add_action( 'woocommerce_add_to_cart_fragments', array( &$this, 'add_ajax_fragment' ) );

            add_action( 'wp_footer', array( &$this, 'add_floating_cart' ), 0 );
        }

        function scripts() {
			wp_register_script( 'floating-cart-xforwc-js', XforWC_FloatingCart()->plugin_url() . '/assets/js/scripts.js', array( 'jquery' ), XforWC_FloatingCart()->version(), true );
            wp_enqueue_script( 'floating-cart-xforwc-js' );
            
            wp_register_style ( 'floating-cart-xforwc-css', XforWC_FloatingCart()->plugin_url() . '/assets/css/styles' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, XforWC_FloatingCart()->version() );
            wp_enqueue_style( 'floating-cart-xforwc-css' );
        }

        function footer_scripts() {
            if ( wp_script_is( 'floating-cart-xforwc-js', 'enqueued' ) ) {
                $vars = array(
                    'ajax' => admin_url( 'admin-ajax.php' ),
                    'settings' => array(
                        SevenVXGet()->get_option( 'cart_content', 'floating_cart_xforwc', 'yes' ),
                        SevenVXGet()->get_option( 'cart_message', 'floating_cart_xforwc', 'yes' ),
                        SevenVXGet()->get_option( 'cart_message_delay', 'floating_cart_xforwc', 2500 ),
                    ),
                    'localize' => array(
                        esc_html( SevenVXGet()->get_option( 'cart_empty_text', 'floating_cart_xforwc', '' ) == '' ? esc_html__( 'Added to cart', 'xforwoocommerce' ) : SevenVXGet()->get_option( 'cart_empty_text', 'floating_cart_xforwc', '' ) ),
                    ),
                );

                wp_localize_script( 'floating-cart-xforwc-js', 'fcart', $vars );
            }
        }

        function dequeue_scripts() {
            wp_dequeue_script( 'floating-cart-xforwc-js' );
        }

        function add_ajax_fragment( $fragments ) {
            $fragments['.floating-cart-total'] = '<span class="floating-cart-total">' . intval( $this->_get_cart_count() ) . '</span>';

            if ( SevenVXGet()->get_option( 'cart_content', 'floating_cart_xforwc', 'yes' ) == 'yes' ) {
                $fragments['.floating-cart-content'] = $this->get_cart_contents_ajax();
            }

            return $fragments;
        }

        function add_floating_cart() {
            $position = SevenVXGet()->get_option( 'position', 'floating_cart_xforwc', 'top-right' );

            switch ( SevenVXGet()->get_option( 'install_type', 'floating_cart_xforwc', 'everywhere' ) ) {
                case 'everywhere':
                    $this->go_cart( $position );

                    return;
                break;

                case 'woocommerce':
                    if ( is_woocommerce() ) {
                        $this->go_cart( $position );

                        return;
                    }
                break;

                default :
                break;
            }

            $pages = SevenVXGet()->get_option( 'pages', 'floating_cart_xforwc' );

            if ( !empty( $pages ) ) {
                $pages = explode( '|', $pages );

                if ( in_array( get_the_ID(), $pages ) ) {
                    $this->go_cart( $position );

                    return;
                }
            }

            if ( $position !== 'inline' ) {
                $this->dequeue_scripts();
            }
        }

        function go_cart( $position ) {
?>
            <div class="floating-cart floating-cart-<?php echo esc_attr( $position ); ?>">
<?php
                if ( SevenVXGet()->get_option( 'cart_message', 'floating_cart_xforwc', 'yes' ) == 'yes' ) {
                    $this->get_add_to_cart_message();
                }

                $this->get_cart();

                if ( SevenVXGet()->get_option( 'checkout', 'floating_cart_xforwc', 'yes' ) == 'yes' ) {
                    $this->get_checkout();
                }

                if ( SevenVXGet()->get_option( 'cart_content', 'floating_cart_xforwc', 'yes' ) == 'yes' ) {
                    $this->get_cart_contents();
                }
?>
            </div>
<?php
            if ( SevenVXGet()->get_option( 'cart_overlay', 'floating_cart_xforwc', 'yes' ) == 'yes' ) {
?>
                <div class="floating-cart-overlay"></div>
<?php
            }
        }

        function get_cart_content_items() {

            if ( WC()->cart->is_empty() ) {
                $this->get_cart_content_empty();
            }
            else {
?>
                <ul class="floating-cart-items">
<?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            
                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
?>
                            <li class="floating-cart-item">
<?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $cart_item_key ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
?>
                                <div class="floating-cart-item-summary">
<?php
                                if ( empty( $product_permalink ) ) {
                                    echo wp_kses_post( $thumbnail . $product_name );
                                }
                                else {
?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>">
                                        <?php echo wp_kses_post( $thumbnail . $product_name ); ?>
                                    </a>
<?php
                                }

                                    $this->get_item_data( $cart_item );
?>
                                </div>
<?php
                                echo wp_kses_post( '<span class="floating-cart-item-total">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>' );
?>
                            </li>
<?php
                        }
                    }
?>
                </ul>
<?php
                $this->get_total();
            }
        }

        function get_item_data( $cart_item ) {
?>
            <div class="floating-cart-item-data">
<?php
                echo wp_strip_all_tags( wc_get_formatted_cart_item_data( $cart_item ), true );
?>
            </div>
<?php
        }
        
        function get_cart_contents() {
?>
            <div class="floating-cart-content">
<?php
                $this->get_cart_content_items();
?>
            </div>
<?php
        }

        function get_cart_contents_ajax() {

            ob_start();
?>
            <div class="floating-cart-content">
<?php
                $this->get_cart_content_items();
?>
            </div>
<?php
            return ob_get_clean();
        }

        function get_cart_content_empty() {
?>
            <div class="floating-cart-items"><?php echo esc_html( SevenVXGet()->get_option( 'cart_message_text', 'floating_cart_xforwc', '' ) == '' ? esc_html__( 'Your Cart is empty', 'xforwoocommerce' ) : SevenVXGet()->get_option( 'cart_message_text', 'floating_cart_xforwc', '' ) ); ?></div>
<?php
            do_action( 'floating_cart_empty_xforwc' );
        }

        function get_total() {
?>
            <div class="floating-cart-payment">
<?php
                    esc_html_e( 'Total:', 'xforwoocommerce');
?>
                <span>
<?php
                    echo wp_strip_all_tags( wc_price( WC()->cart->get_cart_contents_total() + WC()->cart->get_shipping_total() + WC()->cart->get_taxes_total( false, false ) ), true );
?>
                </span>
            </div>
<?php
        }

        function get_add_to_cart_message() {
?>
            <div class="floating-cart-message">

            </div>
<?php
        }
        
        function get_cart() {
?>
            <div class="floating-cart-cart">
                <a id="floating-cart" href="<?php echo SevenVXGet()->get_option( 'cart_content', 'floating_cart_xforwc', 'yes' ) ? esc_js( 'javascript:void(0)' ) : esc_url( wc_get_cart_url() ); ?>"><?php $this->get_cart_count(); ?></a>
            </div>
<?php
        }

        function get_cart_count() {
            if ( function_exists( 'WC' ) && intval( WC()->cart->get_cart_contents_count() ) > 0 ) {
?>
                <span class="floating-cart-total"><?php echo intval( WC()->cart->get_cart_contents_count() ); ?></span>
<?php
            }
            else {
?>
                <span class="floating-cart-total">0</span>
<?php
            }
        }

        function _get_cart_count() {
            if ( function_exists( 'WC' ) && intval( WC()->cart->get_cart_contents_count() ) > 0 ) {
                return WC()->cart->get_cart_contents_count();
            }

            return 0;
        }

        function get_checkout() {
?>
            <div class="floating-cart-checkout">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"></a>
            </div>
<?php
        }

        function shortcode( $atts, $content = null ) {
            $atts = shortcode_atts( array(
                'id' => '',
                'class' => '',
                'position' => 'inline'
            ), $atts );
            
            return $this->_get_floating_cart_element( $atts );
        }

        function _get_floating_cart_element( $atts ) {
            ob_start();
?>
            <div id="<?php echo esc_attr( $this->_get_element_id( $atts['id'] ) ); ?>" class="<?php echo esc_attr( $this->_get_element_class( $atts['class'] ) ); ?>">
                <?php $this->go_cart( 'inline' ); ?>
            </div>
<?php
            return ob_get_clean();
        }

        
        function _get_element_id( $id ) {
            if ( !empty(  $id ) ) {
               return  $id;
            }

            return uniqid( 'xwc--fc-' );
        }

        function _get_element_class( $class ) {
            if ( !empty( $class ) ) {
                return 'xwc--ls-fc ' . $class;
            }

            return 'xwc--ls-fc';
        }

    }

    add_action( 'init', array( 'XforWC_FloatingCart_Frontend', 'instance' ) );
