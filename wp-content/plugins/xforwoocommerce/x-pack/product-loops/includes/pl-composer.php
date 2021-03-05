<?php

	class XforWC_Shop_Design_WP_Bakery {

		public static $presets;

		public static function add_vc_params() {
			if ( function_exists( 'vc_add_param' ) ) {

				if ( empty( self::$presets ) ) {
					$presets = array( 'none' );
					$plugin = get_option( 'svx_settings_product_loops', array() );
					$std = isset( $plugin['wcmn_pl_presets'] ) && is_array( $plugin['wcmn_pl_presets'] ) ? $plugin['wcmn_pl_presets'] : array();
					foreach( $std as $k => $v ) {
						$presets[] = $k;
					}
					self::$presets = $presets;
				}

				$shortcodes = array(
					'products',
					'recent_products',
					'sale_products',
					'best_selling_products',
					'top_rated_products',
					'featured_products',
					'product_category',
					'product_attribute',
					'prdctfltr_sc_products'
				);

				foreach( $shortcodes as $shortcode ) {
					$params = array(
						array(
							'type'        => 'dropdown',
							'class'       => '',
							'heading'     => esc_html__( 'Product Loops', 'xforwoocommerce' ),
							'param_name'  => 'product_loops',
							'value'       => self::$presets,
							'description' => '',
							'std'         => 'none'
						),
					);
					foreach( $params as $param ) {
						vc_add_param( $shortcode, $param );
					}
					$params = array(
						array(
							'type'        => 'textarea',
							'class'       => '',
							'heading'     => esc_html__( 'Product Loops Overrides', 'xforwoocommerce' ),
							'param_name'  => 'product_loops_overrides',
							'value'       => '',
							'description' => '<a href="https://xforwoocommerce.com" target="_blank">' . esc_html__( 'Product Loops for WooCommerce Documentation', 'xforwoocommerce' ) . '</a>',
							'std'         => ''
						),
					);
					foreach( $params as $param ) {
						vc_add_param( $shortcode, $param );
					}
				}

			}
		}

	}

	add_action( 'init', 'XforWC_Shop_Design_WP_Bakery::add_vc_params', 101 );

?>