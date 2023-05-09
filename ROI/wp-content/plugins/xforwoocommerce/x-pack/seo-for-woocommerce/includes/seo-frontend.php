<?php

	if ( ! defined( 'ABSPATH' ) ) exit;

	class XforWC_SEO_Frontend {

		public static $settings;

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		function __construct() {
			add_action( 'wp_head', array( &$this, 'get_seo' ), 2 );
			add_filter( 'mnthemes_add_meta_information_used', array( &$this, 'info' ) );
		}

		function info( $val ) {
			return array_merge( $val, array( 'Autopilot - SEO for WooCommerce' ) );
		}

		function set_state( $state ) {
			self::$settings['state'] = $state;
		}

		function get_product_type() {
			$product = $this->get_product();
			return $product->get_type();
		}

		function check_state() {

			if ( class_exists( 'WooCommerce' ) ) {
				if ( is_product() ) {
					return $this->set_state( array( 'type' => 'product', 'product_type' => $this->get_product_type() ) );
				}

				if ( is_shop() ) {
					return $this->set_state( array( 'type' => 'shop' ) );
				}

				if ( is_product_taxonomy() ) {
					$taxonomy = get_queried_object();
					return $this->set_state( array( 'type' => 'product_taxonomy', 'product_taxonomy' => $taxonomy->taxonomy, 'term_ID' => $taxonomy->term_id ) );
				}
			}

			if ( is_page() ) {
				return $this->set_state( array( 'type' => 'page' ) );
			}

			if ( is_single() ) {
				return $this->set_state( array( 'type' => 'post' ) );
			}

			if ( is_home() ) {
				return $this->set_state( array( 'type' => 'blog' ) );
			}

			if ( is_front_page() ) {
				return $this->set_state( array( 'type' => 'home' ) );
			}

		}

		function get_seo() {
?>
<!-- XforWooCommerce SEO - https://xforwoocommerce.com :START -->
<?php
			$include = apply_filters( 'autopilot_seo_include', array( 'common', 'canonical', 'google', 'ograph', 'facebook', 'twitter', 'meta', 'robots' ) );

			$this->check_state();

			foreach( $include as $k ) {
				switch( $k ) {
					case 'common' :
						$this->get_common();
					break;
					case 'canonical' :
						$this->get_canonical();
					break;
					case 'google' :
						$this->get_google();
					break;
					case 'ograph' :
						$this->get_ograph();
					break;
					case 'facebook' :
						$this->get_facebook();
					break;
					case 'twitter' :
						$this->get_twitter();
					break;
					case 'meta' :
						$this->get_metas();
					break;
					case 'robots' :
						$this->get_robots();
					break;
					default :
					break;
				}
			}
?>
<!-- XforWooCommerce :END -->
<?php
		}

		function _set_link( $link ) {
			self::$settings['link'] = $link['href'] ? $link : array();
		}

		function _reset_link( $link ) {
			self::$settings['link'] = array();
		}

		function _call_link( $link ) {
			$this->_set_link( $link );

			if ( !empty( self::$settings['link']['href'] ) ) {
?>
<link<?php $this->_get_properties_link(); ?> />
<?php
				$this->_reset_link( $link );
			}
		}

		function _set_meta( $meta ) {
			self::$settings['meta'] = isset( $meta['content'] ) ? $meta : array();
		}

		function _reset_meta( $meta ) {
			self::$settings['meta'] = array();
		}

		function _call_meta( $meta ) {
			$this->_set_meta( $meta );

			if ( !empty( self::$settings['meta']['content'] ) ) {
?>
<meta<?php $this->_get_properties(); ?> />
<?php
				$this->_reset_meta( $meta );
			}
		}

		function _get_properties_link() {

			if ( !empty( self::$settings['link'] ) ) {
				foreach( self::$settings['link'] as $k => $v ) {
					if ( $k == 'href' ) {
						echo ' ' . esc_attr( $k ) . '="' . esc_url( $v ) . '"';
					}
					else {
						echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
					}
				}
			}

		}

		function _get_properties() {

			if ( !empty( self::$settings['meta'] ) ) {
				foreach( self::$settings['meta'] as $k => $v ) {
					echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
				}
			}

		}

		function _get_metas_type( $type ) {
			switch ( $type ) {
				case 'name' :
					return 'name';
				break;
				case 'itemprop' :
					return 'itemprop';
				break;
				default :
					return 'property';
				break;
			}
		}

		function get_metas() {
			$metas = SevenVXGet()->get_option( 'wcmn_seo_add_meta', 'seo_for_woocommerce' );
			if ( is_array( $metas ) ) {
				foreach( $metas as $k => $v ) {
					if ( isset( $v['type'] ) && isset( $v['property'] ) && isset( $v['content'] ) ) {
						if ( isset( $v['condition'] ) && $v['condition'] !== '' && $this->get_display_condition( $v['condition']) === true ) {
							$this->_call_meta( array( $this->_get_metas_type( $v['type'] ) => $v['property'], 'content' => $this->make_seo( $v['content'] ) ) );
						}
						else if ( isset( $v['condition'] ) && $v['condition'] == '' ) {
							$this->_call_meta( array( $this->_get_metas_type( $v['type'] ) => $v['property'], 'content' => $this->make_seo( $v['content'] ) ) );
						}
					}
				}
			}
		}

		function get_common() {

			if ( is_single() ) {
				$keywords = get_post_meta( get_the_ID(), '_autopilot_seo_keywords', true );
				if ( !empty( $keywords ) ) {
					$this->_call_meta( array( 'name' => 'keywords', 'content' => $keywords ) );
				}
			}

			$this->_call_meta( array( 'name' => 'description', 'content' => $this->get_description() ) );

			self::$settings['protocol'] = is_ssl() ? 'https:' : 'http:';
			$this->get_featured_image();

		}

		function get_google() {

			$this->_call_meta( array( 'itemprop' => 'name', 'content' => $this->get_title() ) );
			$this->_call_meta( array( 'itemprop' => 'description', 'content' => $this->get_description() ) );
			$this->_call_meta( array( 'itemprop' => 'image', 'content' => $this->get_image() ) );

			$this->get_google_publisher();

		}

		function get_google_publisher() {

			$publisher = SevenVXGet()->get_option_autoload( 'wcmn_seo_googleplus', '' );

			if ( $publisher !== '' ) {
				$this->_call_link( array( 'rel' => 'publisher', 'href' => self::$settings['protocol'] . '//plus.google.com/' . $publisher . '/posts' ) );
			}

			$author_support = SevenVXGet()->get_option( 'wcmn_seo_authors', 'seo_for_woocommerce', 'no' );
			$author = ( $author_support == 'yes' ? get_the_author_meta( 'googleplus', intval( get_post_field( 'post_author', get_queried_object_id() ) ) ) : '' );

			if ( $author_support == 'yes' && $author !== '' ) {
				$this->_call_link( array( 'rel' => 'author', 'href' => self::$settings['protocol'] . '//plus.google.com/' . $author . '/posts' ) );
			}
			else if ( $publisher !== '' ) {
				$this->_call_link( array( 'rel' => 'author', 'href' => self::$settings['protocol'] . '//plus.google.com/' . $publisher . '/posts' ) );
			}

		}

		function get_ograph() {

			$this->_call_meta( array( 'property' => 'og:locale', 'content' => $this->get_locale() ) );
			$this->_call_meta( array( 'property' => 'og:type', 'content' => $this->get_ograph_type() ) );
			$this->_call_meta( array( 'property' => 'og:url', 'content' => $this->get_url() ) );
			$this->_call_meta( array( 'property' => 'og:site_name', 'content' => $this->get_site_name() ) );
			$this->_call_meta( array( 'property' => 'og:title', 'content' => $this->get_title() ) );
			$this->_call_meta( array( 'property' => 'og:description', 'content' => $this->get_description() ) );
			$this->_call_meta( array( 'property' => 'og:updated_time', 'content' => $this->get_updated_time() ) );

			$this->get_ograph_images();
			$this->get_ograph_info( $this->get_ograph_type() );

		}

		function get_featured_image() {

			if ( has_post_thumbnail() ) {
				self::$settings['featured_image'] = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			}

		}

		function get_ograph_images() {

			if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_facebook_image', true ) ) ) {
				$this->_call_meta( array( 'property' => 'og:image', 'content' => esc_url( get_post_meta( get_the_ID(), '_autopilot_seo_facebook_image', true ) ) ) );
				return false;
			}

			if ( isset( self::$settings['featured_image'] ) ) {

				if ( isset( self::$settings['featured_image'][0] ) ) {
					$this->_call_meta( array( 'property' => 'og:image', 'content' => esc_url( self::$settings['featured_image'][0] ) ) );
				}

				if ( isset( self::$settings['featured_image'][1] ) ) {
					$this->_call_meta( array( 'property' => 'og:width', 'content' => esc_attr( self::$settings['featured_image'][1] ) ) );
				}

				if ( isset( self::$settings['featured_image'][2] ) ) {
					$this->_call_meta( array( 'property' => 'og:height', 'content' => esc_attr( self::$settings['featured_image'][2] ) ) );
				}

			}

		}

		function get_ograph_info( $type ) {
			switch( $type ) {
				case 'product' :

					$this->_call_meta( array( 'property' => 'product:retailer', 'content' => SevenVXGet()->get_option_autoload( 'wcmn_seo_facebook', '' ) ) );
					$this->_call_meta( array( 'property' => 'product:price:amount', 'content' => $this->get_price() ) );
					$this->_call_meta( array( 'property' => 'product:price:currency', 'content' => $this->get_currency() ) );
					$this->_call_meta( array( 'property' => 'product:availability', 'content' => $this->get_availability() ) );
					$this->_call_meta( array( 'property' => 'product:category', 'content' => $this->get_category() ) );
					$this->_call_meta( array( 'property' => 'product:brand', 'content' => $this->get_brand() ) );
					$this->_call_meta( array( 'property' => 'product:manufacturer', 'content' => $this->get_manufacturer() ) );
					$this->_call_meta( array( 'property' => 'product:color', 'content' => $this->get_color() ) );
					$this->_call_meta( array( 'property' => 'product:condition', 'content' => $this->get_condition() ) );
					$this->_call_meta( array( 'property' => 'product:material', 'content' => $this->get_material() ) );

					$this->get_ograph_sale_price();

				break;
				default :

					$this->_call_meta( array( 'property' => 'article:published_time', 'content' => $this->get_published_time() ) );
					$this->_call_meta( array( 'property' => 'article:modified_time', 'content' => $this->get_updated_time() ) );

					$this->get_ograph_publisher();
					$this->get_ograph_taxonomies();

				break;
			}
		}

		function get_ograph_type() {
			if ( $this->get_state_type() == 'product' ) {
				return 'product';
			}
			return 'article';
		}

		function get_ograph_sale_price() {
			$product = $this->get_product();

			if ( $product->is_on_sale() ) {
				$this->_call_meta( array( 'property' => 'product:sale_price', 'content' => $product->get_sale_price() ) );
			}

			$sale_price_dates_from = (int) get_post_meta( get_the_ID(), '_sale_price_dates_from', true );
			if ( !empty( $sale_price_dates_from ) ) {
				$this->_call_meta( array( 'property' => 'product:sale_price_dates:start', 'content' => $sale_price_dates_from ) );
			}

			$sale_price_dates_to = (int) get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
			if ( !empty( $sale_price_dates_to ) ) {
				$this->_call_meta( array( 'property' => 'product:sale_price_dates:end', 'content' => $sale_price_dates_to ) );
			}

		}

		function get_ograph_publisher() {

			$publisher = SevenVXGet()->get_option_autoload( 'wcmn_seo_facebook', '' );

			if ( $publisher !== '' ) {
				$this->_call_meta( array( 'property' => 'article:publisher', 'content' => self::$settings['protocol'] . '//www.facebook.com/' . $publisher ) );
			}

			$author_support = SevenVXGet()->get_option( 'wcmn_seo_authors', 'seo_for_woocommerce', 'no' );
			$author = ( $author_support == 'yes' ? get_the_author_meta( 'facebook', intval( get_post_field( 'post_author', get_queried_object_id() ) ) ) : '' );

			if ( $author_support == 'yes' && $author !== '' ) {
				$this->_call_meta( array( 'property' => 'article:author', 'content' => esc_url( $author ) ) );
			}
			else if ( $publisher !== '' ) {
				$this->_call_meta( array( 'property' => 'article:author', 'content' => self::$settings['protocol'] . '//www.facebook.com/' . $publisher ) );
			}

		}

		function get_ograph_taxonomies() {
			if ( is_singular( 'post' ) ) {
				$this->_call_meta( array( 'property' => 'article:section', 'content' => $this->get_categories() ) );
				$this->_call_meta( array( 'property' => 'article:tag', 'content' => $this->get_tags() ) );
			}
		}

		function get_facebook() {
			$this->get_facebook_app_id();
		}

		function get_facebook_app_id() {
			$this->_call_meta( array( 'property' => 'fb:app_id', 'content' => SevenVXGet()->get_option_autoload( 'wcmn_seo_facebook_app', '' ) ) );
		}

		function get_twitter() {

			$this->_call_meta( array( 'name' => 'twitter:card', 'content' => $this->get_twitter_type() ) );
			$this->_call_meta( array( 'name' => 'twitter:title', 'content' => $this->get_title() ) );
			$this->_call_meta( array( 'name' => 'twitter:description', 'content' => $this->get_description() ) );

			$this->get_twitter_publisher();
			$this->get_twitter_images();
			$this->get_twitter_info( $this->get_twitter_type() );

		}

		function get_twitter_publisher() {

			$publisher = SevenVXGet()->get_option_autoload( 'wcmn_seo_twitter', '' );

			if ( $publisher !== '' ) {
				$this->_call_meta( array( 'name' => 'twitter:site', 'content' => '@' . $publisher ) );
			}

			$author_support = SevenVXGet()->get_option( 'wcmn_seo_authors', 'seo_for_woocommerce', 'no' );
			$author = ( $author_support == 'yes' ? get_the_author_meta( 'twitter', intval( get_post_field( 'post_author', get_queried_object_id() ) ) ) : '' );

			if ( $author_support == 'yes' && $author_support !== '' ) {
				$this->_call_meta( array( 'name' => 'twitter:creator', 'content' => '@' . $author ) );
			}
			else if ( $publisher !== '' ) {
				$this->_call_meta( array( 'name' => 'twitter:creator', 'content' => '@' . $publisher ) );
			}

		}

		function get_twitter_images() {
			if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_twitter_image', true ) ) ) {
				$this->_call_meta( array( 'name' => 'twitter:image', 'content' => esc_url( get_post_meta( get_the_ID(), '_autopilot_seo_twitter_image', true ) ) ) );
				return false;
			}

			if ( isset( self::$settings['featured_image'] ) ) {
				if ( isset( self::$settings['featured_image'][0] ) ) {
					$this->_call_meta( array( 'name' => 'twitter:image', 'content' => esc_url(  self::$settings['featured_image'][0] ) ) );
				}
			}
		}

		function get_twitter_info( $type ) {
			switch( $type ) {
				case 'product' :
					$option = SevenVXGet()->get_option( 'wcmn_seo_twitter_data', 'seo_for_woocommerce', array( 'price', 'category' ) );

					if ( is_array( $option ) ) {
						$n=1;
						foreach( $option as $meta ) {
							$this->_build_twitter_meta( $meta, $n );
							$n++;
						}
					}
				break;
				default :
				break;
			}
		}

		function get_twitter_type() {
			if ( $this->get_state_type() == 'product' ) {
				return 'product';
			}
			return 'summary';
		}

		function _build_twitter_meta( $meta, $n ) {

			$text = '';

			switch( $meta ) {
				case 'price' :
					$text = esc_html__( 'Price', 'xforwoocommerce' );
				break;
				case 'category' :
					$text = esc_html__( 'Category', 'xforwoocommerce' );
				break;
				case 'color' :
					$text = esc_html__( 'Color', 'xforwoocommerce' );
				break;
				case 'condition' :
					$text = esc_html__( 'Condition', 'xforwoocommerce' );
				break;
				case 'availability' :
					$text = esc_html__( 'Availability', 'xforwoocommerce' );
				break;
				case 'brand' :
					$text = esc_html__( 'Brand', 'xforwoocommerce' );
				break;
				case 'manufacturer' :
					$text = esc_html__( 'Manufacturer', 'xforwoocommerce' );
				break;
				case 'material' :
					$text = esc_html__( 'Material', 'xforwoocommerce' );
				break;
				default :
				break;
			}

			if ( $text !== '' ) {
				$this->_call_meta( array( 'property' => 'twitter:data' . $n, 'content' => call_user_func_array( array( $this, 'get_' . $meta ), array() ) ) );
				$this->_call_meta( array( 'property' => 'twitter:label' . $n, 'content' => $text ) );
			}

		}

		function get_canonical() {
			$this->_call_link( array( 'rel' => 'canonical', 'href' => $this->get_url() ) );
		}

		function get_robots() {
?>
<?php
		}

		function get_locale() {
			return get_locale();
		}

		function get_state_product_taxonomy() {
			if ( isset( self::$settings['state']['product_taxonomy'] ) ) {
				return self::$settings['state']['product_taxonomy'];
			}
			return false;
			
		}

		function get_state_product_type() {
			if ( isset( self::$settings['state']['product_type'] ) ) {
				return self::$settings['state']['product_type'];
			}
			return false;
		}
		function get_state_type() {
			if ( isset( self::$settings['state']['type'] ) ) {
				return self::$settings['state']['type'];
			}
			return false;
		}

		function ready_make( $text ) {
			return $text;
		}

		function make_seo( $elements ) {

			if ( $elements == '' ) {
				return $elements;
			}

			$out = array();

			$elements = strip_tags( $elements );

			if ( strpos( $elements, '%' ) === false ) {
				return $this->ready_make( $elements );
			}

			if ( preg_match_all( '/%([^%]+)%/', $elements, $matches ) ) {
				if ( is_array( $matches[0] ) ) {
					$matches[0] = array_map( 'strtolower', $matches[0] );
					foreach( $matches[0] as $match ) {
						switch( $match ) {
							case '%site-title%' :
								$out[] = get_bloginfo( 'title' );
							break;
							case '%site-tagline%' :
								$out[] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_bloginfo( 'description' ) ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) );
							break;
							case '%title%' :
								$out[] = get_the_title();
							break;
							case '%product-content%' :
							case '%content%' :
								$out[] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) );
							break;
							case '%product-short-desc%' :
							case '%excerpt%' :
								$excerpt = wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_post_field( 'post_excerpt', get_the_ID() ) ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) );
								if ( empty( $excerpt ) ) {
									$excerpt = $out[] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) );
								}
								$out[] = $excerpt;
							break;
							case '%price%' :
								$out[] = wp_strip_all_tags( wc_price( $this->get_price() ) );
							break;
							case '%regular-price%' :
								$product = $this->get_product();
								$out[] = wp_strip_all_tags( wc_price( $product->get_regular_price() ) );
							break;
							case '%sale-price%' :
								$product = $this->get_product();
								$out[] = wp_strip_all_tags( wc_price( $product->get_sale_price() ) );
							break;
							case '%currency%' :
								$out[] = $this->get_currency();
							break;
							case '%product-cat%' :
								$out[] = $this->get_category();
							break;
							case '%product-tag%' :
								$out[] = $this->get_tag();
							break;
							case '%brand%' :
								$out[] = $this->get_brand();
							break;
							case '%manufacturer%' :
								$out[] = $this->get_manufacturer();
							break;
							case '%availability%' :
								$out[] = $this->get_availability();
							break;
							case '%color%' :
								$out[] = $this->get_color();
							break;
							case '%condition%' :
								$out[] = $this->get_condition();
							break;
							case '%archive-title%' :
								$out[] = get_the_archive_title();
							break;
							case '%archive-desc%' :
								$out[] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( $this->get_archive_desc() ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) );
							break;
							case '%archive-name%' :
								$out[] = $this->get_single_term();
							break;
							case '%separator%' :
								$out[] = SevenVXGet()->get_option( 'wcmn_seo_separator', 'seo_for_woocommerce', '-' );
							break;
							default :
							break;
						}
					}
				}
			}

			return ( empty( $out ) ? $elements : str_replace( $matches[0], $out, $elements ) );

		}

		function get_single_term() {
			return single_term_title( '', false );
		}

		function check_post_meta_replaced( $type ) {
			return $this->make_seo( wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ), apply_filters( 'autopilot_seo_excerpt_length', 60 ) ) );
		}

		function get_option_default( $type ) {
			switch ( $type ) {
				case 'wcmn_seo_pages_title' :
					return '%title% %separator% %site-title%';
				break;
				case 'wcmn_seo_pages_desc' :
					return '%excerpt%';
				break;
				case 'wcmn_seo_shop_title' :
					return '%title% %separator% %site-title%';
				break;
				case 'wcmn_seo_shop_desc' :
					return '%excerpt%';
				break;
				case 'wcmn_seo_products_title' :
					return '%title% %separator% %price% %separator% %brand% %separator% %site-title%';
				break;
				case 'wcmn_seo_products_desc' :
					return '%brand% %separator% %price% %separator% %product-short-desc%';
				break;
				case 'wcmn_seo_home_title' :
					return '%title% %separator% %site-title%';
				break;
				case 'wcmn_seo_home_desc' :
					return '%excerpt%';
				break;
				case 'wcmn_seo_blog_title' :
					return '%archive-title% %separator% %site-title%';
				break;
				case 'wcmn_seo_blog_desc' :
					return '%archive-desc%';
				break;
				case 'wcmn_seo_post_title' :
					return '%title% %separator% %site-title%';
				break;
				case 'wcmn_seo_post_desc' :
					return '%excerpt%';
				break;
				default :
					return false;
				break;
			}
			return false;
		}

		function get_replaced( $type ) {
			switch( $this->get_state_type() ) {
				case 'page' :
					if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
						return $this->check_post_meta_replaced( $type );
					}
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_pages_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_pages_' . $type ) ) );
				break;
				case 'shop' :
					if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
						return $this->check_post_meta_replaced( $type );
					}
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_shop_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_shop_' . $type ) ) );
				break;
				case 'product_taxonomy' :
					$product_taxonomies = SevenVXGet()->get_option( 'wcmn_seo_taxonomies', 'seo_for_woocommerce', array() );
					if ( !empty( $product_taxonomies ) ) {
						$product_taxonomy = $this->get_state_product_taxonomy();
						if ( array_key_exists( $product_taxonomy, $product_taxonomies ) ) {
							if ( isset( $product_taxonomies[$product_taxonomy][$type] ) && $product_taxonomies[$product_taxonomy][$type] !== '' ) {
								return $this->make_seo( $product_taxonomies[$product_taxonomy][$type] );
							}
						}
					}
					return $this->make_seo( $this->get_option_default( 'wcmn_seo_blog_' . $type ) );
				break;
				case 'product' :
					if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
						return $this->check_post_meta_replaced( $type );
					}
					$product_types = SevenVXGet()->get_option( 'wcmn_seo_product_types', 'seo_for_woocommerce', array() );
					if ( !empty( $product_types ) ) {
						$product_type = $this->get_state_product_type();
						if ( array_key_exists( $product_type, $product_types ) ) {
							if ( isset( $product_types[$product_type][$type] ) && $product_types[$product_type][$type] !== '' ) {
								return $this->make_seo( $product_types[$product_type][$type] );
							}
						}
					}
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_products_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_products_' . $type ) ) );
				break;
				case 'home' :
					if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
						return $this->check_post_meta_replaced( $type );
					}
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_home_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_home_' . $type ) ) );
				break;
				case 'blog' :
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_blog_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_blog_' . $type ) ) );
				break;
				case 'post' :
					if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
						return $this->check_post_meta_replaced( $type );
					}
					return $this->make_seo( SevenVXGet()->get_option( 'wcmn_seo_post_' . $type, 'seo_for_woocommerce', $this->get_option_default( 'wcmn_seo_post_' . $type ) ) );
				break;
				default :
					if ( is_single() ) {
						if ( !empty( get_post_meta( get_the_ID(), '_autopilot_seo_' . $type, true ) ) ) {
							return $this->check_post_meta_replaced( $type );
						}
					}
					return $this->make_seo( $type=='title'?'%title%':'excerpt' );
				break;
			}
		}

		function get_title() {
			return $this->get_replaced( 'title' );
		}

		function get_description() {
			return $this->get_replaced( 'desc' );
		}
		function get_archive_desc() {
			return term_description();
		}

		function get_image() {
			if ( isset( self::$settings['featured_image'] ) ) {
				if ( isset( self::$settings['featured_image'][0] ) ) {
					return esc_url( self::$settings['featured_image'][0] );
				}
			}
			return '';
		}

		function get_availability() {
			$product = $this->get_product();;
			return $product->is_in_stock() ? 'instock' : 'outofstock';
		}

		function get_terms( $taxonomy ) {

			$content = array();
			$terms = get_the_terms( get_the_ID(), $taxonomy );

			if ( is_wp_error( $terms ) ) {
				return array();
			}

			if ( empty( $terms ) ) {
				return array();
			}

			foreach ( $terms as $term ) {
				$content[] = $term->name;
			}

			return !empty( $content ) ? implode( ', ', $content ) : '';

		}

		function get_set_terms( $set ) {
			$taxonomy = SevenVXGet()->get_option( 'wcmn_seo_' . $set, 'seo_for_woocommerce', '' );
			if ( !empty( $taxonomy ) && taxonomy_exists( $taxonomy ) ) {
				return $this->get_terms( $taxonomy );
			}
		}

		function get_tags() {
			return $this->get_terms( 'post_tag' );
		}

		function get_categories() {
			return $this->get_terms( 'category' );
		}

		function get_category() {
			return $this->get_terms( 'product_cat' );
		}

		function get_tag() {
			return $this->get_terms( 'product_tag' );
		}

		function get_brand() {
			return $this->get_set_terms( 'brand' );
		}

		function get_manufacturer() {
			return $this->get_set_terms( 'manufacturer' );
		}

		function get_color() {
			return $this->get_set_terms( 'color' );
		}

		function get_condition() {
			return $this->get_set_terms( 'condition' );
		}

		function get_material() {
			return $this->get_set_terms( 'material' );
		}

		function get_site_name() {
			return get_bloginfo( 'name' );
		}

		function get_url() {
			return esc_url( get_permalink() );
		}

		function get_published_time() {
			return get_the_time( 'Y-m-dTH:i:sO' );
		}

		function get_updated_time() {
			return get_the_modified_date( 'Y-m-dTH:i:sO' );
		}

		function get_price() {
			$product = $this->get_product();
			return $product->get_price();
		}

		function get_currency() {
			return get_option( 'woocommerce_currency' );
		}

		function get_currency_symbol() {
			return get_woocommerce_currency_symbol();
		}

		function get_fallbacks() {
			return get_bloginfo( 'description' );
		}

		function get_product() {
			if ( isset( self::$settings['product'] ) ) {
				return self::$settings['product'];
			}

			global $product;

			if ( empty( $product ) || is_string( $product ) ) {
				$product = wc_get_product( get_the_ID() );
			}

			self::$settings['product'] = $product;

			return $product;
		}

		function get_display_condition( $condition ) {

			$condition_result = false;

			$condition_function = null;
			$inverse = null;
			$condition_parameters = null;

			if ( substr_count( $condition, ':' ) == 1 ) {
				$condition = explode( ':', $condition );
				$condition_function = $condition[0];
				$condition_parameters = strpos( $condition[1], ',' ) > 0 ? array_diff( explode( ',', $condition[1] ), array( '') ) : array( $condition[1] );
			}
			else if ( substr_count( $condition, ':' ) == 0 ) {
				$condition_function = $condition;
			}

			if ( isset( $condition_function ) ) {
				if ( substr( $condition_function, 0, 1 ) == '!' ) {
					$condition_function = substr( $condition_function, 1 );
					$inverse = true;
				}

				if ( function_exists( $condition_function ) ) {
					if ( isset( $inverse ) ) {
						if ( isset( $condition_parameters ) ) {
							$condition_result = !call_user_func( $condition_function, $condition_parameters );
						}
						else {
							$condition_result = !call_user_func( $condition_function );
						}
					}
					else {
						if ( isset( $condition_parameters ) ) {
							$condition_result = call_user_func( $condition_function, $condition_parameters );
						}
						else {
							$condition_result = call_user_func( $condition_function );
						}
					}
					
				}

			}

			return $condition_result;

		}

	}

	add_action( 'init', array( 'XforWC_SEO_Frontend', 'init' ) );

	if ( !function_exists( 'mnthemes_add_meta_information' ) ) {
		function mnthemes_add_meta_information_action() {
			echo '<meta name="generator" content="' . esc_attr( implode( ', ', apply_filters( 'mnthemes_add_meta_information_used', array() ) ) ) . '"/>';
		}
		function mnthemes_add_meta_information() {
			add_action( 'wp_head', 'mnthemes_add_meta_information_action', 99 );
		}
		mnthemes_add_meta_information();
	}

?>