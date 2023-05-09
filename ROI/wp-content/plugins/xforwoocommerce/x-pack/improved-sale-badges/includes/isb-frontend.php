<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_Improved_Badges_Frontend {

		public static $settings;

		protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		function __construct() {

			$this->install_shop();
			$this->install_product_page();

			if ( SevenVXGet()->get_option_autoload( 'wc_settings_isb_template_overrides', 'yes' )  == 'yes' ) {
				add_filter( 'wc_get_template_part', array( &$this, 'isb_add_loop_filter' ), 10, 3 );
				add_filter( 'woocommerce_locate_template', array( &$this, 'isb_add_loop_filter' ), 10, 3 );
			}

			add_action( 'wp_enqueue_scripts', array( &$this, 'isb_scripts' ) );
			add_action( 'wp_footer', array( &$this, 'check_scripts' ) );

			add_action( 'xforwc_badges_loop', array( &$this, '_get_badge' ), 10 );
			add_action( 'xforwc_badges_product', array( &$this, '_get_badge' ), 10 );

			add_action( 'isb_get_loop_badge', array( &$this, 'isb_get_loop_badge' ), 10 );
			add_action( 'isb_get_single_badge', array( &$this, 'isb_get_single_badge' ), 10 );

			add_filter( 'mnthemes_add_meta_information_used', array( &$this, 'isb_info' ) );

		}

		function isb_info( $val ) {
			$val = array_merge ( $val, array( 'Improved Badges for WooCommerce' ) );
			return $val;
		}

		public static function isb_get_path() {
			return plugin_dir_path( __FILE__ );
		}

		function install_shop() {
			$setting = SevenVXGet()->get_option_autoload( 'wc_settings_isb_archive_action', '' );
		
			if ( $setting !== '' ) {
				$hook = array();

				$hook = explode( ':', $setting );
				$hook[1] = isset( $hook[1] ) ? intval( $hook[1] ) : 10;

				add_action( $hook[0], array( &$this, 'isb_get_loop_badge' ), $hook[1] );
			}
		}

		function install_product_page() {
			$setting = SevenVXGet()->get_option_autoload( 'wc_settings_isb_single_action', '' );
		
			if ( $setting !== '' ) {
				$hook = array();

				$hook = explode( ':', $setting );
				$hook[1] = isset( $hook[1] ) ? intval( $hook[1] ) : 10;

				add_action( $hook[0], array( &$this, 'isb_get_single_badge' ), $hook[1] );
			}
		}

		function isb_scripts() {

			//wp_enqueue_style( 'isb-style', ImprovedBadges()->plugin_url() . '/assets/css/style' . ( is_rtl() ? '-rtl' : '' ) . '.css', false, XforWC_Improved_Badges::$version );
			wp_enqueue_style( 'isb-style', ImprovedBadges()->plugin_url() . '/assets/css/style' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, XforWC_Improved_Badges::$version );

			wp_register_script( 'isb-scripts', ImprovedBadges()->plugin_url() . '/assets/js/scripts.js', array( 'jquery' ), XforWC_Improved_Badges::$version, true );
			wp_enqueue_script( 'isb-scripts' );

		}

		function check_scripts() {

			global $isb_set;

			if ( !isset( $isb_set['load_js'] ) && SevenVXGet()->get_option_autoload( 'wc_settings_isb_force_scripts', 'no' ) == 'no' ) {
				wp_dequeue_script( 'isb-scripts' );
			}
			else if ( wp_script_is( 'isb-scripts', 'enqueued' ) ) {

				$curr_args = array(
					'time' => self::$settings['time'],
					'localization' => array(
						'd' => esc_html__( 'd', 'xforwoocommerce' ),
						'days' => esc_html__( 'days', 'xforwoocommerce' )
					)
				);

				wp_localize_script( 'isb-scripts', 'isb', $curr_args );

			}

		}

		function isb_add_loop_filter( $template, $slug, $path ) {
			if ( in_array( $slug, array( 'loop/sale-flash.php', 'single-product/sale-flash.php' ) ) ) {
				$badge = ImprovedBadges()->plugin_path() . '/woocommerce/' . $slug;
				return file_exists( $badge ) ? $badge : $template;
			}
			return $template;
		}

		function isb_get_loop_badge() {

			$include = ImprovedBadges()->plugin_path() . '/woocommerce/loop/sale-flash.php';

			if ( file_exists( $include ) ) {
				include( $include );
			}

		}

		function isb_get_single_badge() {

			$include = ImprovedBadges()->plugin_path() . '/woocommerce/single-product/sale-flash.php';

			if ( file_exists( $include ) ) {
				include( $include );
			}

		}

		function make_a_set() {

			global $isb_set;

			$isb_set['style'] = SevenVXGet()->get_option( 'wc_settings_isb_style', 'improved_badges', 'isb_style_shopkit' );
			$isb_set['color'] = SevenVXGet()->get_option( 'wc_settings_isb_color', 'improved_badges', 'isb_sk_material' );
			$isb_set['position'] = ( $pos = SevenVXGet()->get_option( 'wc_settings_isb_position', 'improved_badges', 'isb_left' ) ) ? $pos : 'isb_left';
			$isb_set['special'] = SevenVXGet()->get_option( 'wc_settings_isb_special', 'improved_badges', '' );
			$isb_set['special_text'] = SevenVXGet()->get_option( 'wc_settings_isb_special_text', 'improved_badges', '' );
			$isb_set['time'] = strtotime( current_time( 'mysql' ) );

			self::$settings = $isb_set;

		}

		function _get_badge() {
					
			global $product, $isb_set;

			if ( $product === null ) {
				return '';
			}

			if ( $product->is_type( 'grouped' ) ) {
				return '';
			}

			if ( empty( $isb_set ) ) {
				$this->make_a_set();
			}

			$curr_badge = $this->get_badge();

			$badge_count = count( $curr_badge );

			$isb_set['load_js'] = true;

			if ( $badge_count > 1 ) {
?>
				<div class="isb_badges">
<?php
			}

			for ( $i = 0; $i < $badge_count; $i++ ) {
				if ( isset( $curr_badge[0]['type'] ) && in_array( $curr_badge[0]['type'], array( 'sale', 'special', 'image', 'image-sale', 'image-special' ) ) ) {
					$this->_print_badge( $curr_badge[0]['type'], $curr_badge );
				}
				else {
					if ( isset( $curr_badge[0]['special'] ) && $curr_badge[0]['special'] !== '' ) {
						$this->_print_badge( 'special', $curr_badge );
					}
					else {
						$this->_print_badge( 'sale', $curr_badge );
					}
				}

				array_shift( $curr_badge );
			}

			if ( $badge_count > 1 ) {
?>
				</div>
<?php
			}

		}

		public static function get_preset( $preset ) {

			if ( $preset == '' ) {
				return array();
			}

			if ( is_string( $preset ) ) {
				return self::__get_presets( array( $preset ) );
			}

			if ( is_array( $preset ) ) {
				if ( isset( $preset['preset'] ) ) {
					if ( is_string( $preset['preset'] ) ) {
						return self::__get_presets( array( sanitize_title( $preset['preset'] ) ) );
					}
					if ( is_array( $preset['preset'] ) ) {
						return self::__get_presets( $preset['preset'] );
					}
				}
				else {
					return self::__get_presets( $preset );
				}
			}
		}

		public static function __get_presets( $preset ) {
			$badges = array();
	
			foreach( $preset as $k => $v ) {
				$badge = get_option( '_wcmn_isb_preset_' . $v, array() );

				if ( isset( $badge['name'] ) ) {
					$badges[$k] = $badge;
				}
			}
	
			return $badges;
		}

		public static function is_old_post( $id, $days = 5 ) {
			$days = (int) $days;
			$offset = $days*60*60*24;
			if ( get_post_time( 'U', false, $id ) < date( 'U' ) - $offset )
				return true;
			
			return false;
		}

		public static function overrides() {

			if ( !isset( self::$settings['overrides'] ) ) {
				self::$settings['overrides'] = SevenVXGet()->get_option( 'wcmn_isb_overrides', 'improved_badges', array() );
			}

			if ( empty( self::$settings['overrides'] ) ) {
				return false;
			}

			$over = self::$settings['overrides'];

			if ( isset( $over['outofstock'] ) && $over['outofstock'] !== '' ) {
				global $product;

				if ( $product->is_in_stock() === false ) {
					return self::get_preset( $over['outofstock'] );
				}
			}

			if ( isset( $over['featured'] ) && $over['featured'] !== '' ) {
				if ( XforWC_Improved_Badges::version_check() === true ) {
					if ( has_term( 'featured', 'product_visibility', get_the_ID() ) ) {
						return self::get_preset( $over['featured'] );
					}
				}
				else {
					if ( get_post_meta( get_the_ID(), '_featured', true ) === 'yes' ) {
						return self::get_preset( $over['featured'] );
					}
				}
			}

			if ( isset( $over['new']['days'] ) && isset( $over['new']['preset'] ) && $over['new']['preset'] !== ''  ) {
				if ( !self::is_old_post( get_the_ID(), $over['new']['days'] ) ) {
					return self::get_preset( $over['new']['preset'] );
				}
			}

			if ( isset( $over['product_tag'] ) && is_array( $over['product_tag'] ) ) {
				foreach( $over['product_tag'] as $k => $v ) {
					$v = is_array( $v ) ? $v : array( 'term' => $k, 'preset' => $v );
					if ( !empty( $v['term'] ) && has_term( $v['term'], 'product_tag', get_the_ID() ) ) {
						return self::get_preset( $v['preset'] );
					}
				}
			}

			if ( isset( $over['product_cat'] ) && is_array( $over['product_cat'] ) ) {

				$term_ids = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

				if ( $term_ids && !is_wp_error( $term_ids ) ) {
					$term_parents = get_ancestors( $term_ids[0], 'product_cat' );

					$checks = array( $term_ids[0] );
					if ( !empty( $term_parents ) ) {
						$checks = array_merge( $checks, $term_parents );
					}

					foreach( $checks as $check ) {
						if ( array_key_exists( $check, $over['product_cat'] ) ) {
							return self::get_preset( $over['product_cat'][$check] );
						}
					}
				}
			}

			if ( isset( $over['sale'] ) && $over['sale'] !== '' ) {
				global $product;

				if ( $product->get_price() > 0 && $product->is_on_sale() ) {
					return self::get_preset( $over['sale'] );
				}
			}
			
			return array();

		}

		function _print_badge( $type, $curr_badge ) {

			switch ( $type ) {
				case 'special' :
					$this->_print_special_badge( $curr_badge );
				break;

				case 'sale' :
				case 'image-sale' :
					global $product;
					
					if ( $product->is_type( 'variable' ) ) {
						$this->_print_variable_sale_badge( $curr_badge );
					}
					else {
						$this->_print_standard_sale_badge( $curr_badge );
					}
				break;
				
				case 'image' :
					$this->_print_image_badge( $curr_badge );
				break;

				case 'image-special' :
					$this->_print_image_special_badge( $curr_badge );
				break;
				
				default :
				break;
			}

		}

		function _print_image_badge( $curr_badge ) {

			$isb_curr_set = $this->_build_current_badge_set( $curr_badge );
		
			$isb_class = $isb_curr_set['position'];

			if ( isset( $isb_curr_set['image'] ) ) {
				$include = ImprovedBadges()->plugin_path() . '/includes/images/isb_image.php';
	
				if ( file_exists ( $include ) ) {
					include( $include );
				}
			}
		}

		function _print_image_special_badge( $curr_badge ) {

			$isb_curr_set = $this->_build_current_badge_set( $curr_badge );

			$isb_curr_set['special_text'] = ( isset( $curr_badge[0]['special_text'] ) && $curr_badge[0]['special_text'] !== '' ? stripslashes( $curr_badge[0]['special_text'] ) : esc_html__( 'Text', 'xforwoocommerce' ) );
		
			$isb_class = $isb_curr_set['position'];

			if ( isset( $isb_curr_set['image'] ) ) {
				$include = ImprovedBadges()->plugin_path() . '/includes/images/isb_image_special.php';
	
				if ( file_exists ( $include ) ) {
					include( $include );
				}
			}
		}

		function _print_special_badge( $curr_badge ) {

			$isb_curr_set = $this->_build_current_badge_set( $curr_badge );
		
			$isb_class = $isb_curr_set['special'] . ' ' . $isb_curr_set['color'] . ' ' . $isb_curr_set['position'];

			$isb_curr_set['special_text'] = ( isset( $curr_badge[0]['special_text'] ) && $curr_badge[0]['special_text'] !== '' ? stripslashes( $curr_badge[0]['special_text'] ) : esc_html__( 'Text', 'xforwoocommerce' ) );

			if ( isset( $isb_curr_set['special'] ) ) {
				$include = ImprovedBadges()->plugin_path() . '/includes/specials/' . $isb_curr_set['special'] . '.php';
	
				if ( file_exists ( $include ) ) {
					include( $include );
				}
			}
		}

		function _print_variable_sale_badge( $curr_badge ) {
		
			global $product, $isb_set;

			$isb_variations = $product->get_available_variations();
			$isb_check = 0;
			$isb_check_time = 0;

			if ( !empty( $isb_variations ) ) {
				$isb_curr_set = $this->_build_current_badge_set( $curr_badge );
				$isb_class = $isb_curr_set['style'] . ' ' . $isb_curr_set['color'] . ' ' . $isb_curr_set['position'] . ' isb_variable';

				echo '<div class="isb_variable_group ' . esc_attr( $isb_curr_set['position'] ) . '">';
			}

			foreach( $isb_variations as $var ) {

				$curr_product[$var['variation_id']] = new WC_Product_Variation( $var['variation_id'] );
				$isb_price = array();

				$sale_price_dates_from = (int) get_post_meta( $var['variation_id'], '_sale_price_dates_from', true ) + (int) SevenVXGet()->get_option( 'wc_settings_isb_timer_adjust', 'improved_badges', 0 )*60;
				$sale_price_dates_to = (int) get_post_meta( $var['variation_id'], '_sale_price_dates_to', true ) + (int) SevenVXGet()->get_option( 'wc_settings_isb_timer_adjust', 'improved_badges', 0 )*60;

				if ( !empty( $sale_price_dates_from ) && !empty( $sale_price_dates_to ) ) {
					$current_time = current_time( 'mysql' );
					$newer_date = strtotime( $current_time );

					$since = $newer_date - $sale_price_dates_from;

					if ( 0 > $since ) {
						$check_time = $sale_price_dates_from;
						$check_time_mode = 'start';
					}

					if ( !isset( $check_time ) ) {
						$since = $newer_date - $sale_price_dates_to;
						if ( 0 > $since ) {
							$check_time = $sale_price_dates_to;
							$check_time_mode = 'end';
						}
					}

					if ( isset( $check_time ) ) {
						$isb_price['time'] = $check_time;
						$isb_price['time_mode'] = $check_time_mode;

						$timer = SevenVXGet()->get_option( 'wc_settings_isb_timer', 'improved_badges', array() );
						if ( !empty( $timer ) && is_array( $timer ) && isset( $isb_price['time_mode'] ) && in_array( $isb_price['time_mode'], $timer ) ) {
							unset( $isb_price['time'] );
							unset( $isb_price['time_mode'] );
						}
					}
				}

				if ( $curr_product[$var['variation_id']]->get_price() > 0 && ( $curr_product[$var['variation_id']]->is_on_sale() || isset( $isb_price['time'] ) ) !== false ) {

					$isb_var_regular_price = $curr_product[$var['variation_id']]->get_regular_price();
					$isb_var_sales_price = $curr_product[$var['variation_id']]->get_sale_price();

					$isb_diff = $isb_var_regular_price - $isb_var_sales_price ;

					if ( $isb_diff > $isb_check ) {
						$isb_check = $isb_diff;
						$isb_var = $var['variation_id'];
					}

					$this->__check_sale_labels( 'variable', $var['variation_id'], $curr_product[$var['variation_id']], $isb_price );

					if ( isset( $isb_curr_set['type'] ) && in_array( $isb_curr_set['type'], array( 'image-sale' ) ) ) {
						$include = ImprovedBadges()->plugin_path() . '/includes/images/isb_image_sale.php';
					}
					else if ( isset( $isb_curr_set['style'] ) ) {
						$include = ImprovedBadges()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
					}
					
					if ( isset( $include ) && file_exists ( $include ) ) {
						include( $include );
					}

				}

			}

			if ( isset( $isb_var ) ) {

				$this->__check_sale_labels( 'variable', 0, $curr_product[$isb_var], $isb_price );
				
				if ( isset( $isb_curr_set['type'] ) && in_array( $isb_curr_set['type'], array( 'image-sale' ) ) ) {
					$include = ImprovedBadges()->plugin_path() . '/includes/images/isb_image_sale.php';
				}
				else if ( isset( $isb_curr_set['style'] ) ) {
					$include = ImprovedBadges()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
				}
				
				if ( isset( $include ) && file_exists ( $include ) ) {
					include( $include );
				}

			}

			if ( !empty( $isb_variations ) ) {
				echo '</div>';
			}

		}
		
		function _build_current_badge_set( $curr_badge ) {
			global $isb_set;
		
			return array(
				'type' => isset( $curr_badge[0]['type'] ) && $curr_badge[0]['type'] !== '' ? $curr_badge[0]['type'] : '',
				'style' => isset( $curr_badge[0]['style'] ) && $curr_badge[0]['style'] !== '' ? $curr_badge[0]['style'] : '',
				'color' => isset( $curr_badge[0]['color'] ) && $curr_badge[0]['color'] !== '' ? $curr_badge[0]['color'] : '',
				'position' => isset( $curr_badge[0]['position'] ) && $curr_badge[0]['position'] !== '' ? $curr_badge[0]['position'] : $isb_set['position'],
				'special' => isset( $curr_badge[0]['special'] ) && $curr_badge[0]['special'] !== '' ? $curr_badge[0]['special'] : $isb_set['special'],
				'special_text' => isset( $curr_badge[0]['special_text'] ) && $curr_badge[0]['special_text'] !== '' ? $curr_badge[0]['special_text'] : '',
				'image' => isset( $curr_badge[0]['image'] ) && $curr_badge[0]['image'] !== '' ? $curr_badge[0]['image'] : '',
			);
		}

		function __check_sale_labels( $type, $id, $product, &$isb_price ) {

			$isb_price['type'] = $type;

			$isb_price['id'] = $id;

			$isb_price['regular'] = floatval( $product->get_regular_price() );

			$isb_price['sale'] = floatval( $product->get_sale_price() );

			$isb_price['difference'] = $isb_price['regular'] - $isb_price['sale'];

			$isb_price['percentage'] = round( ( $isb_price['regular'] - $isb_price['sale'] ) * 100 / $isb_price['regular'] );

		}

		function __get_dates_from( $id ) {
			return (int) get_post_meta( $id, '_sale_price_dates_from', true ) + (int) SevenVXGet()->get_option( 'wc_settings_isb_timer_adjust', 'improved_badges', 0 )*60;
		}

		function __get_dates_to( $id ) {
			return (int) get_post_meta( $id, '_sale_price_dates_to', true ) + (int) SevenVXGet()->get_option( 'wc_settings_isb_timer_adjust', 'improved_badges', 0 )*60;
		}

		function __check_sale_dates( &$isb_price ) {
			$sale_price_dates_from = $this->__get_dates_from( get_the_ID() );
			$sale_price_dates_to = $this->__get_dates_to( get_the_ID() );

			if ( !empty( $sale_price_dates_from ) && !empty( $sale_price_dates_to ) ) {
				$current_time = current_time( 'mysql' );
				$newer_date = strtotime( $current_time, $sale_price_dates_from );

				$since = $newer_date - $sale_price_dates_from;

				if ( 0 > $since ) {
					$isb_price['time'] = $sale_price_dates_from;
					$isb_price['time_mode'] = 'start';
				}

				if ( !isset( $isb_price['time'] ) ) {
					$since = $newer_date - $sale_price_dates_to;
					if ( 0 > $since ) {
						$isb_price['time'] = $sale_price_dates_to;
						$isb_price['time_mode'] = 'end';
					}
				}

				$timer = SevenVXGet()->get_option( 'wc_settings_isb_timer', 'improved_badges', array() );
				if ( !empty( $timer ) && is_array( $timer ) && isset( $isb_price['time_mode'] ) && in_array( $isb_price['time_mode'], $timer ) ) {
					unset( $isb_price['time'] );
					unset( $isb_price['time_mode'] );
				}
			}
		}

		function _print_standard_sale_badge( $curr_badge ) {

			global $product, $isb_set;

			$isb_price = array();

			$this->__check_sale_dates( $isb_price );

			if ( $product->get_price() > 0 && ( $product->is_on_sale() || isset( $isb_price['time'] ) ) !== false ) {

				$this->__check_sale_labels( 'simple', get_the_ID(), $product, $isb_price );

				if ( !isset( $curr_badge ) ) {
					$curr_badge = array();
				}

				$isb_curr_set = $this->_build_current_badge_set( $curr_badge );

				$isb_class = $isb_curr_set['style'] . ' ' . $isb_curr_set['color'] . ' ' . $isb_curr_set['position'];

				if ( isset( $isb_curr_set['type'] ) && in_array( $isb_curr_set['type'], array( 'image-sale' ) ) ) {
					$include = ImprovedBadges()->plugin_path() . '/includes/images/isb_image_sale.php';
				}
				else {
					$include = ImprovedBadges()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
				}

				if ( file_exists ( $include ) ) {
					include( $include );
				}

			}

		}

		function get_badge() {

			global $isb_set;

			$badge = array( array(
				'style'        => $isb_set['style'],
				'color'        => $isb_set['color'],
				'position'     => $isb_set['position'],
				'special'      => $isb_set['special'],
				'special_text' => $isb_set['special_text']
			) );

			$badge_meta = get_post_meta( get_the_ID(), '_isb_settings' );

			if ( isset( $badge_meta[0]['preset'] ) && $badge_meta[0]['preset'] !== '' ) {
				$preset = self::get_preset( $badge_meta[0]['preset'] );
				if ( !empty( $preset ) ) {
					return $preset;
				}
			}

			$override = self::overrides();
			$badge = empty( $override ) ? $badge : $override;

			if ( isset( $badge_meta[0] ) && is_array( $badge_meta[0] ) ) {

				$isbElements = array( 'style', 'color', 'position', 'special', 'special_text' );

				foreach( $isbElements as $v ) {
					if ( isset( $badge_meta[0][$v] ) && $badge_meta[0][$v] !== '' ) {
						$badge[0][$v] = $badge_meta[0][$v];
					}
				}
			}

			return $badge;

		}

	}

	add_action( 'init', array( 'XforWC_Improved_Badges_Frontend', 'instance' ), 998 );
	
	function XforWC_Improved_Badges_Frontend() {
		return XforWC_Improved_Badges_Frontend::instance();
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

