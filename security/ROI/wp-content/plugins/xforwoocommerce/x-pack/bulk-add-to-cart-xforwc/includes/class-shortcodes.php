<?php

	class XforWC_BulkAddtoCart_Shortcodes {

		public static $settings;

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		function __construct() {
			$shortcodes = array(
				'products',
				'recent_products',
				'sale_products',
				'best_selling_products',
				'top_rated_products',
				'featured_products',
				'product_cat',
				'product_category',
				'product_attribute',
				'prdctfltr_sc_products'
			);

			foreach( $shortcodes as $shortcode ) {
				add_filter( 'shortcode_atts_' . $shortcode, array( &$this,'extend_atts' ), 10, 4 );
				if ( $shortcode !== 'prdctfltr_sc_products' ) {
					add_action( 'woocommerce_shortcode_' . $shortcode . '_loop_no_results', array( &$this, 'after_filter' ), 999, 1 );
					add_action( 'woocommerce_shortcode_after_' . $shortcode . '_loop', array( &$this, 'after_filter' ), 999, 1 );
				}
			}

            add_action( 'prdctfltr_reset_loop', array( &$this, 'after_filter' ), 999, 1 );
            
			add_shortcode( 'bulk_add_to_cart', array( &$this, 'show_tool' ) );
		}

		function extend_atts(  $out, $pairs, $atts, $shortcode ) {
			if ( !empty( $atts['bulk_add_to_cart'] ) ) {
				self::$settings['active'] = true;
			}

			return $out;
		}

		function after_filter( $atts ) {
			$this->fix_remains();
		}

		function fix_remains() {
			if ( isset( self::$settings['active'] ) ) {
				self::$settings['active'] = null;
			}
		}

		function show_tool( $atts, $content = null ) {
			ob_start();

            do_action( 'bulk_add_to_cart_shortcode' );
            
			return ob_get_clean();
		}



	}

	add_action( 'init', array( 'XforWC_BulkAddtoCart_Shortcodes', 'init' ), 999 );

?>