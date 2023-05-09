<?php

	class XforWC_Shop_Design_Shortcodes {

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
			add_shortcode( 'product_loops_grid_table', array( &$this, 'grid_table' ) );
			add_action( 'woocommerce_before_shop_loop', array( &$this, 'add_grid_table_switcher' ), 31 );
		}

		function extend_atts(  $out, $pairs, $atts, $shortcode ) {
			if ( !empty( $atts['product_loops'] ) ) {
				self::$settings['fix_loop'] = $atts['product_loops'];
				$out['product_loops'] = $atts['product_loops'];
				add_filter( 'product_loops_default_loop', array( &$this, 'fix_shortcode_loop' ) );
			}

			if ( !empty( $atts['product_loops_overrides'] ) ) {
				$this->build_overrides( $atts['product_loops_overrides'] );
				$out['product_loops_overrides'] = $atts['product_loops_overrides'];
			}

			return $out;
		}

		function fix_remains() {
			if ( isset( self::$settings['fix_loop'] ) ) {
				self::$settings['fix_loop'] = null;
			}

			if ( isset( XforWC_Shop_Design_Frontend::$settings['overrides'] ) ) {
				XforWC_Shop_Design_Frontend::$settings['overrides'] = SevenVXGet()->get_option( 'wcmn_pl_overrides', 'product_loops', array() );
			}

			remove_filter( 'product_loops_default_loop', array( &$this, 'fix_shortcode_loop' ) );
		}

		function after_filter( $atts ) {
			$this->fix_remains();
		}

		function fix_shortcode_loop( $loop ) {
			return self::$settings['fix_loop'];
		}

		function grid_table( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'look_in' => ''
			), $atts );

			ob_start();

			$this->add_grid_table_switcher( $atts['look_in'] );

			return ob_get_clean();
		}

		function add_grid_table_switcher( $look_in = '' ) {
			?>
			<div class="pl-grid-table"<?php echo ( $look_in == '' ? '' : ' data-id="' . esc_attr( $look_in ) . '"' );?>>
				<span class="pl-grid"></span>
				<span class="pl-separator"></span>
				<span class="pl-table"></span>
			</div>
			<?php
		}

		function build_overrides( $string ) {

			if ( $string == 'disable' ) {
				XforWC_Shop_Design_Frontend::$settings['overrides'] = array();
			}

			$return = array();

			$options = explode( ';', $string );

			foreach( $options as $k => $v ) {
				$option = explode( ':', $v );

				if ( isset( $option[0], $option[1] ) ) {
					switch( $option[0] ) {
						case 'featured' :
							$return['featured'] = $option[1];
						break;
						case 'expire' :
							$return['new']['preset'] = $option[1];
						break;
						case 'expire-in' :
							$return['new']['days'] = $option[1];
						break;
						default :
							if ( in_array( $option[0], array( 'product_cat', 'product_tag' ) ) ) {
								if ( isset( $option[2] ) && term_exists( $option[1], $option[0] ) ) {
									$return[$option[0]][] = array(
										'term' => $option[1],
										'preset' => $option[2]
									);
								}
							}
						break;
					}
				}
			}

			if ( !empty( $return ) ) {
				XforWC_Shop_Design_Frontend::$settings['overrides'] = $return;
			}

		}

	}

	add_action( 'init', array( 'XforWC_Shop_Design_Shortcodes', 'init' ), 999 );

?>