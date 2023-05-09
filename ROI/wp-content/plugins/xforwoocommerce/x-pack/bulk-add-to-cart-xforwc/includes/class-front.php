<?php

	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_BulkAddtoCart_Frontend {

		protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
            }
            
            return self::$_instance;
            
		}

		function __construct() {
            $this->init_plugin();
        }

        function init_plugin() {
            add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
            add_action( 'wp_footer', array( &$this, 'footer_scripts' ) );

            add_action( 'wp_ajax_nopriv_bulk_add_to_cart', array( &$this, 'add_to_cart' ) );
			add_action( 'wp_ajax_bulk_add_to_cart', array( &$this, 'add_to_cart' ) );
            
            add_action( 'woocommerce_add_to_cart_fragments', array( &$this, 'add_ajax_fragment' ) );

            add_action( 'wp_head', array( &$this, 'do_init' ) );

            if ( is_ajax() && isset( $_POST['pf_bulk'] ) && $_POST['pf_bulk'] === 'true' ) {
                return $this->activate();
            }
        }

        function do_init() {
            if ( is_woocommerce() && SevenVXGet()->get_option( 'woocommerce', 'bulk_add_to_cart_xforwc', 'yes' ) == 'yes' ) {
                return $this->activate();
            }
            
            $pages = SevenVXGet()->get_option( 'pages', 'bulk_add_to_cart_xforwc' );
            if ( !empty( $pages ) ) {
                $pages = explode( '|', $pages );
                if ( is_array( $pages ) && in_array( get_the_ID(), $pages ) ) {
                    return $this->activate();
                }
            }
        }
        
        function activate() {
            switch( SevenVXGet()->get_option( 'position', 'bulk_add_to_cart_xforwc', 'top-right' ) ) {

                case 'after-description' :
                    add_action( 'woocommerce_archive_description', array( &$this, 'add_bulk_button' ), 0 );
                break;
                case 'before-loop' :
                    add_action( 'woocommerce_before_shop_loop', array( &$this, 'add_bulk_button' ), 0 );
                break;
                case 'top-right' :
                case 'top-left' :
                case 'bottom-right' :
                case 'bottom-left' :
                default:
                    add_action( 'wp_footer', array( &$this, 'add_bulk_button' ), 0 );
                break;
            }
            
            add_action( 'woocommerce_single_product_summary', array( &$this, 'add_marker' ), 0 );
            
            add_action( 'woocommerce_before_shop_loop_item', array( &$this, 'add_marker' ), 0 );

            add_action( 'product_loops_before', array( &$this, 'add_marker' ), 1 );
            add_action( 'product_loops_alt_before', array( &$this, 'add_marker' ), 1 );
            add_action( 'product_loops_out_before', array( &$this, 'add_marker' ), 1 );
        }

        function scripts() {
			wp_register_script( 'bulk-add-to-cart-xforwc-js', XforWC_BulkAddtoCart()->plugin_url() . '/assets/js/scripts.js', array( 'jquery' ), XforWC_BulkAddtoCart()->version(), true );
            wp_enqueue_script( 'bulk-add-to-cart-xforwc-js' );
            
            wp_register_style( 'bulk-add-to-cart-xforwc-css', XforWC_BulkAddtoCart()->plugin_url() . '/assets/css/styles' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, XforWC_BulkAddtoCart()->version() );
            wp_enqueue_style( 'bulk-add-to-cart-xforwc-css' );
        }

        function footer_scripts() {
            if ( wp_script_is( 'bulk-add-to-cart-xforwc-js', 'enqueued' ) ) {
                $vars = array(
                    'ajax' => admin_url( 'admin-ajax.php' ),
                    'localize' => array(
                        'select_options' => esc_html__( 'Select options', 'bulk-add-to-cart-xforwc' ),
                        'not_selected' => esc_html__( 'Not selected', 'bulk-add-to-cart-xforwc' ),
                        'now_button' => esc_html__( 'Bulk add to cart', 'bulk-add-to-cart-xforwc' ),
                        'one' => esc_html__( 'Added %% item', 'bulk-add-to-cart-xforwc' ),
                        'many' => esc_html__( 'Added %% items', 'bulk-add-to-cart-xforwc' ),
                    ),
                );

                wp_localize_script( 'bulk-add-to-cart-xforwc-js', 'ba', $vars );
            }
        }
        
        function add_bulk_button() {
?>
            <div class="bulk-add-to-cart-tool bulk-add-to-cart-<?php echo esc_attr( SevenVXGet()->get_option( 'position', 'bulk_add_to_cart_xforwc', 'top-right' ) ); ?><?php echo esc_attr( ' bulk-add-to-cart-tool-' . SevenVXGet()->get_option( 'style', 'bulk_add_to_cart_xforwc', 'dark' ) ); ?>">
<?php
                if ( SevenVXGet()->get_option( 'bulk', 'bulk_add_to_cart_xforwc', 'yes' ) == 'yes' ) {
?>
                    <div class="bulk-add-to-cart-bulk">
                        <a href="javascript:void(0)" class="bulk-add-to-cart-select-all"><?php esc_html_e( 'Select all', 'bulk-add-to-cart-xforwc' ); ?></a>
                        <a href="javascript:void(0)" class="bulk-add-to-cart-deselect-all"><?php esc_html_e( 'Deselect all', 'bulk-add-to-cart-xforwc' ); ?></a>
                    </div>
<?php
                }

                if ( SevenVXGet()->get_option( 'cart', 'bulk_add_to_cart_xforwc', 'yes' ) == 'yes' ) {
                    $this->get_cart();
                }

                if ( SevenVXGet()->get_option( 'checkout', 'bulk_add_to_cart_xforwc', 'yes' ) == 'yes' ) {
                    $this->get_checkout();
                }
?>
                <div class="bulk-add-to-cart-now"><?php esc_html_e( 'Bulk add to cart', 'bulk-add-to-cart-xforwc' ); ?></div>
            </div>
<?php
        }

        function get_cart() {
?>
            <div class="bulk-add-to-cart-cart">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php $this->get_cart_count(); ?></a>
            </div>
<?php
        }

        function get_cart_count() {
            if ( function_exists( 'WC' ) && intval( WC()->cart->get_cart_contents_count() ) > 0 ) {
?>
                <span class="bulk-add-to-cart-cart-total"><?php echo intval( WC()->cart->get_cart_contents_count() ); ?></span>
<?php
            }
            else {
?>
                <span class="bulk-add-to-cart-cart-total">0</span>
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
            <div class="bulk-add-to-cart-checkout">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"></a>
            </div>
<?php

        }

        function _get_marker_class( $class = array() ) {
            $classes = array();

            $classes[] = 'bulk-add-to-cart';
            $classes[] = 'bulk-add-to-cart-' . SevenVXGet()->get_option( 'style', 'bulk_add_to_cart_xforwc', 'dark' );

            return implode( ' ', $classes );
        }

        function add_marker() {
            global $product;

            $show = false;
            switch ( $product->get_type() ) {
                case 'simple' :
                    if ( $product->is_in_stock() ) {
                        $show = true;
                    }
                break;
                case 'variable' :
                    $show = true;
                break;

                default :
                break;

            }

            if ( $show ) {
?>
                <div class="<?php echo esc_attr( $this->_get_marker_class() ); ?>">
                    <div class="bulk-add-to-cart-checkbox" data-id="<?php echo $product->get_id(); ?>" data-type="<?php echo $product->get_type(); ?>"></div>
<?php
                    if ( SevenVXGet()->get_option( 'qty', 'bulk_add_to_cart_xforwc', 'yes' ) == 'yes' ) {
?>
                        <input name="qty" type="text" inputmode="numeric" pattern="[0-9]*" class="bulk-add-to-cart-qty" value="1" />
<?php
                    }
?>
                </div>
<?php
            }
        }

        function add_ajax_fragment( $fragments ) {
            $fragments['.bulk-add-to-cart-cart-total'] = '<span class="bulk-add-to-cart-cart-total">' . intval( $this->_get_cart_count() ) . '</span>';

            return $fragments;
        }

        function add_products_to_cart( $products ) {
            foreach( $products as $product ) {
                $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product['id'] ) );

                $product_data = array();
                if ( isset( $product['custom'] ) ) {
                    $custom = array();
                    parse_str( $product['custom'], $custom );
    
    
                    foreach ( $custom as $k => $v ) {
                        if ( substr( $k, 0, 6 ) == 'ivpac_' && !empty( $v ) ) {
                            if ( is_array( $v ) ) {
                                $v = array_filter( $v );
                            }
                            if ( !empty( $v ) ) {
                                $product_data['ivpac'][substr( $k, 6 )] = is_array( $v ) ? implode( ', ', $v ) : $v;
                            }
                        }
                    }
    
                }

                $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $product['qty'] );
                $id = WC()->cart->add_to_cart( $product_id, $product['qty'], $product['variation'], false, $product_data );

                if ( $passed_validation && $id ) {
                    do_action( 'woocommerce_ajax_added_to_cart', $product_id );
    
                    if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
                        wc_add_to_cart_message( $product_id );
                    }
                }
                else {
                    $data = array(
                        'error' => true,
                        'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
                    );

                    $data = json_encode( $data );

                    wp_die( $data );
                    exit();
                }
            }

            $data = WC_AJAX::get_refreshed_fragments();

            wp_die( $data );
            exit();
        }

        function add_to_cart() {
            $products = isset( $_POST['products'] ) ? $_POST['products'] : array();

            if ( !empty( $products ) ) {
                $this->add_products_to_cart( $products );
            }            
        }

    }

    add_action( 'init', array( 'XforWC_BulkAddtoCart_Frontend', 'instance' ) );
