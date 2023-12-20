<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XforWC_Product_Options_Themes {

	public static function ___get_compatibles() {
		return array(
			'divi', 'salient', 'astra', 'impreza', 'thegem', 'bb-theme', 'storefront', 'twentytwelve', 'twentythirteen', 'twentyfourteen', 'twentyfifteen', 'twentysixteen', 'twentyseventeen', 'twentynineteen', 'rehub', 'camelia', 'jupiterx', 'oceanwp', 'phlox', 'thegem', 'nantes', 'wr-nitro', 'box-shop', 'ciyashop', 'blance', 'anakual', 'airi', 'ronneby', 'betheme', 'x', 'kallyas', 'mediacenter', 'business-hub'
		);
	}

	public static function ___get_nosupport() {
		return array(
			'uncode',
		);
	}

	public static function get_theme() {

		$install = self::___options_default();

		$install['name'] = wp_get_theme()->get( 'Name' );
		$install['template'] = sanitize_title( strtolower( get_template() ) );

		if ( in_array( $install['template'], self::___get_compatibles() ) ) {
			$install['recognized'] = true;
			return $install;
		}

		if ( method_exists( 'XforWC_Product_Options_Themes', '__options_for_' . $install['template'] ) ) {
			$themeAjax = call_user_func( 'XforWC_Product_Options_Themes::__options_for_' . $install['template'] );
			if ( !empty( $themeAjax ) ) {
				$install = array_merge( $install, $themeAjax );
				$install['recognized'] = true;
			}
			return $install;
		}
	
		if ( in_array( $install['template'], self::___get_nosupport() ) ) {
			return false;
		}

		return $install;

	}
	
	public static function __options_for_bila() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item:9',
		);
	}
	
	public static function __options_for_shopkit() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item:9',
		);
	}
	
	public static function __options_for_avada() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item_title:20',
		);
	}
	
	public static function __options_for_atelier() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item_title',
		);
	}
	
	public static function __options_for_snsvicky() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item_title',
		);
	}
	
	public static function __options_for_snsevon() {
		$gridstyle = snsevon_woo_cat_option('woo_gridstyle');
		if ( $gridstyle == '' ) {
			$gridstyle = snsevon_getoption('woo_gridstyle');
		}

		switch ( $gridstyle ) {
			case '2' :
				$shop_hook = 'snsevon_grid2_after_item_title:20';
			break;

			case '3' :
				$shop_hook = 'snsevon_grid3_after_item_title:20';
			break;

			case '4' :
				$shop_hook = 'snsevon_grid4_after_item_title:20';
			break;

			case '5' :
				$shop_hook = 'snsevon_grid5_after_item_title:20';
			break;

			case '6' :
				$shop_hook = 'woocommerce_before_shop_loop_item';
			break;

			case '6' :
				$shop_hook = 'snsevon_grid7_after_shop_loop_item:20';
			break;

			case '' :
			default :
				$shop_hook = 'snsevon_grid1_after_item_title:20';
			break;
		}

		return array(
            'shop_hook' => $shop_hook,
		);
	}
	
	public static function __options_for_halena() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item_title',
		);
	}
	
	public static function __options_for_electro() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item',
            'product_summary' => '.product-actions-wrapper',
		);
	}

	public static function __options_for_nozama() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item',
            'product_summary' => '.entry-product-info',
		);
	}
	
	public static function __options_for_porto() {
		return array(
            'shop_hook' => 'woocommerce_after_shop_loop_item_title',
            'product_image' => '.product-summary-wrap .img-thumbnail .inner',
		);
	}
	
	
	public static function __options_for_shopkeeper() {
		return array(
			'product' => 'li',
            'shop_hook' => 'woocommerce_after_shop_loop_item_title',
            'product_summary' => '.product_infos',
		);
	}
	
	public static function __options_for_enfold() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title:20',
		);
	}

	public static function __options_for_flatsome() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title:20',
		);
	}

	public static function __options_for_merchandiser() {
		return array(
			'product' => 'li',
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
			'product_summary' => '.product_infos',
		);
	}

	public static function __options_for_cristiano() {
		return array(
			'product_hook' => 'woocommerce_single_product_summary:25',
			'product_summary' => '#product-single',
			'price' => '.product-price',
		);
	}

	public static function __options_for_bazar() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
			'product_summary' => '.product-box',
		);
	}

	public static function __options_for_legenda() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
			'product_summary' => '.product_meta',
		);
	}

	public static function __options_for_woodmart() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
		);
	}

	public static function __options_for_xstore() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item',
			'product_summary' => '.product-information-inner',
		);
	}

	public static function __options_for_royal() {
		return array(
			'product' => '.product',
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
			'product_summary' => '.product-information-inner',
		);
	}

	public static function __options_for_stockie() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title',
		);
	}

	public static function __options_for_antive() {
		return array(
			'product_hook' => 'woocommerce_before_variations_form',
		);
	}

	public static function __options_for_grotte() {
		return array(
			'shop_hook' => 'woocommerce_after_shop_loop_item_title:20',
		);
	}

	public static function __options_for_movedo() {
		return array(
			'product' => '.grve-column-wrapper',
		);
	}

	public static function ___options_default() {
		return apply_filters( 'xforwc_product_options_automatic_install', array(
			'product_hook' => 'woocommerce_before_add_to_cart_button',
			'product' => '.type-product',
			'product_images' => '',
			'product_summary' => '.summary',
			'shop_hook' => 'woocommerce_after_shop_loop_item:999',
			'add_to_cart' => '.add_to_cart_button',
			'price' => '.price',
		) );
	}

}


