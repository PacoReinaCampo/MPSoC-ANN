<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_Improved_Badges_Shortcodes {

		public static function init() {
			add_shortcode( 'ib_badge', __CLASS__ . '::get_badge' );
		}

		public static function get_badge( $atts, $content = null ) {

			$atts = shortcode_atts( array(
				'preset' => '',
				'style' => '',
				'color' => '',
				'position' => '',
				'special' => '',
				'special_text' => '',
				'price' => 10,
				'sale_price' => 5,
				'type' => '',
				'class' => '',
				'shortcode_id' => ''
			), $atts );

			global $isb_set;

			$isb_set['style'] = $atts['style'] !== '' ? 'isb_' . $atts['style'] : SevenVXGet()->get_option( 'wc_settings_isb_style', 'improved_badges', 'isb_style_shopkit' );
			$isb_set['color'] = $atts['color'] !== '' ? 'isb_' . $atts['color'] : SevenVXGet()->get_option( 'wc_settings_isb_color', 'improved_badges', 'isb_sk_material' );
			$isb_set['position'] = $atts['position'] !== '' ? 'isb_' . $atts['position'] : SevenVXGet()->get_option( 'wc_settings_isb_position', 'improved_badges', 'isb_left' );
			$isb_set['special'] = $atts['special'] !== '' ? 'isb_' . $atts['special'] : SevenVXGet()->get_option( 'wc_settings_isb_special', 'improved_badges', '' );
			$isb_set['special_text'] = $atts['special_text'] !== '' ? $atts['special_text'] : SevenVXGet()->get_option( 'wc_settings_isb_special_text', 'improved_badges', '' );

			$isb_price['type'] = 'simple';
			//$isb_price['id'] = get_the_ID();
			$isb_price['regular'] = $atts['price'];
			$isb_price['sale'] = $atts['sale_price'];;
			$isb_price['difference'] = $isb_price['regular'] - $isb_price['sale'];
			$isb_price['percentage'] = round( ( $isb_price['regular'] - $isb_price['sale'] ) * 100 / $isb_price['regular'] );

			$isb_curr_set = $isb_set;


			if ( $isb_set['special'] !== '' ) {
				$isb_class = $isb_set['special'] . ' ' . $isb_set['color'] . ' ' . $isb_set['position'];
				$include = ImprovedBadges()->plugin_path() . '/includes/specials/' . $isb_curr_set['special'] . '.php';
			}
			else {
				$isb_class = $isb_set['style'] . ' ' . $isb_set['color'] . ' ' . $isb_set['position'];
				$include = ImprovedBadges()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
			}

			$class = $atts['class'] == '' ? '' : ' ' . esc_attr( $atts['class'] );
			if ( $atts['type'] !== '' && in_array( $atts['type'], array( 'absolute','inline' ) ) ) {
				$class .= ' isb-sc-' . $atts['type'];
			}

			ob_start();

			if ( file_exists ( $include ) ) {
				include( $include );
			}

			return '<div' . ( $atts['shortcode_id'] !== '' ? ' id="' . esc_attr( $atts['shortcode_id'] ) .'"' : '' ) . ' class="isb-sc' . esc_attr( $class ) . '">' . wp_kses_post( ob_get_clean() ) . '</div>';

		}

	}

	add_action( 'init', array( 'XforWC_Improved_Badges_Shortcodes', 'init' ) );
