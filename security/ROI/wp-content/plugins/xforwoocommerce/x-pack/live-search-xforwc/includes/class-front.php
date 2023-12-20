<?php

	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_LiveSearch_Frontend {

		protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
            }
            
            return self::$_instance;
            
		}

		function __construct() {
            $this->init_front();
        }

        function init_front() {
            add_shortcode( 'live_search_xforwc', array( &$this, 'shortcode' ) );

            add_action( 'wp_ajax_nopriv_xwc_live_search', array( &$this, 'respond' ) );
            add_action( 'wp_ajax_xwc_live_search', array( &$this, 'respond' ) );

            add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
            add_action( 'wp_footer', array( &$this, 'check_scripts' ) );
        }

        function scripts() {
			wp_register_script( 'live-search-xforwc-js', XforWC_LiveSearch()->plugin_url() . '/assets/js/scripts.js', array( 'jquery' ), XforWC_LiveSearch()->version(), true );
            wp_enqueue_script( 'live-search-xforwc-js' );
            
            wp_register_style ( 'live-search-xforwc-css', XforWC_LiveSearch()->plugin_url() . '/assets/css/styles' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, XforWC_LiveSearch()->version() );
            wp_enqueue_style( 'live-search-xforwc-css' );
        }

        function check_scripts() {
            if ( wp_script_is( 'live-search-xforwc-js', 'enqueued' ) ) {
                $args = array(
                    'ajax' => admin_url( 'admin-ajax.php' ),
                    'characters' => absint( SevenVXGet()->get_option( 'characters', 'live_search_xforwc', 2 ) ),
                    'localize' => array(
                        'notfound' => esc_html( $this->_get_notfound() ),
                    ),
                );
    
                wp_localize_script( 'live-search-xforwc-js', 'ls', $args );
            }
    
        }

        function shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
                'id' => '',
                'class' => '',
                'category' => '',
            ), $atts );
            
            return $this->_get_live_search_element( $atts );
        }

        function _get_notfound() {
            $notfound = SevenVXGet()->get_option( 'notfound', 'live_search_xforwc', '' );

            if ( empty( $notfound ) ) {
                return esc_html__( 'No products found', 'live-search-xforwc' );
            }

            return $notfound;
        }

        function _get_placeholder() {
            $placeholder = SevenVXGet()->get_option( 'placeholder', 'live_search_xforwc', '' );

            if ( empty( $placeholder ) ) {
                return esc_html__( 'Enter keywords', 'live-search-xforwc' );
            }
            
            return $placeholder;
        }

        function _get_products() {
            $num = absint( SevenVXGet()->get_option( 'products', 'live_search_xforwc', 10 ) );
          
            return $num > 0 ? $num : 10;
        }

        function _get_separator() {
            $separator = SevenVXGet()->get_option( 'separator', 'live_search_xforwc', ( is_rtl() ? '<' : '>' ) );

            return '<span class="xwc--ls-separator">' . esc_html( $separator ) . '</span>';
        }

        function _build_query() {
            $string = '';
            $category = '';

            if ( isset( $_POST['settings'] ) ) {
                $string = esc_html( $_POST['settings'][0] );
                $category = sanitize_title( $_POST['settings'][1] );
            }

            $query = array(
                's' => $string,
                'orderby' => 'relevance',
                'limit' => $this->_get_products(),
            );

            if ( !empty( $category ) ) {
                $query['category'] = array( $category );
            }

            return apply_filters( 'live_search_xforwc_query', $query );
        }

        function respond() {
            $products = array();

            $query = wc_get_products( $this->_build_query() );

            if ( $query ) {
                foreach( $query as $product ) {
                    $products[] = array(
                        'id' => absint( $product->get_id() ),
                        'path' => wp_kses_post( $this->_get_trail( $product->get_category_ids() ) ),
                        'title' => wp_kses_post( $this->_get_separator() . '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_title() ) . '</a>' ),
                        'image' => wp_kses_post( '<a href="' . esc_url( $product->get_permalink() ) . '">' . $product->get_image() . '</a>' ),
                        'price' => strip_tags( $product->get_price_html(), '<del>' ),
                    );
                }
            }

            wp_send_json( $products );
            exit; 
        }

        function _get_trail( $ids ) {
            if ( $ids[0] ) {
                $term_id = $ids[0];
            }

            while ( $term_id ) {
                $term = get_term( $term_id, 'product_cat' );

                $parents[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, 'product_cat' ) ), $term->name );

                $term_id = $term->parent;
            }

            array_reverse( $parents );

            return implode( $this->_get_separator(), $parents );
        }
        
        function _get_live_search_element( $atts ) {
            ob_start();
?>
            <div id="<?php echo esc_attr( $this->_get_element_id( $atts['id'] ) ); ?>" class="<?php echo esc_attr( $this->_get_element_class( $atts['class'] ) ); ?>" data-category="<?php echo esc_attr( $this->_get_element_category( $atts['category'] ) ); ?>">
                <input class="xwc--ls-input" type="text" placeholder="<?php echo esc_attr( $this->_get_placeholder() ); ?>"/>
                <button class="xwc--ls-button"></button>
            </div>
<?php
            return ob_get_clean();
        }

        function _get_element_id( $id ) {
            if ( !empty(  $id ) ) {
               return  $id;
            }

            return uniqid( 'xwc--ls-' );
        }

        function _get_element_class( $class ) {
            if ( !empty( $class ) ) {
                return 'xwc--ls-element ' . $class;
            }

            return 'xwc--ls-element';
        }

        function _get_element_category( $category ) {
            $category = sanitize_title( $category );

            if ( term_exists( $category, 'product_cat' ) ) {
                return $category;
            }

            return '';
        }

    }

    add_action( 'init', array( 'XforWC_LiveSearch_Frontend', 'instance' ) );
