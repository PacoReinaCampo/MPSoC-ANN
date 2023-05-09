<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_AddTabs_Frontend {

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		function __construct() {
            add_filter( 'woocommerce_product_tabs', array( $this, 'get_tabs' ) );
			add_filter( 'mnthemes_add_meta_information_used', array( &$this, 'info' ) );
		}

		function info( $val ) {
			return array_merge( $val, array( 'Add Tabs for WooCommerce' ) );
		}

		public static function get_tab( $key, $tab ) {
            if ( isset( $tab['tab'] ) ) {
                switch( $tab['tab']['type'] ) {
                    case 'product_meta' :
                        global $product;

                        if ( !method_exists( $product, 'get_id' ) ) {
                            return false;
                        }

                        $meta = get_post_meta( $product->get_id(), $tab['tab']['key'], true );
                        if ( $meta !== false ) {
                            switch ( $tab['tab']['meta_type'] ) {
                                case 'csv' :
                                    $tab['tab']['csv'] = $meta;
                                    self::_get_csv( $tab['tab'] );
                                break;
                                case 'image' :
                                    self::_get_image( $meta );
                                break;
                                break;
                                case 'video' :
                                    self::_get_video( $meta );
                                break;
                                case 'html' :
                                default :
                                    self::_get_html( $meta );
                                break;
                            }
                        }
                    break;
                    case 'csv' :
                        if ( isset( $tab['tab']['csv'] ) ) {
                            self::_get_csv( $tab['tab'] );
                        }
                    break;
                    case 'image' :
                        if ( isset( $tab['tab']['image'] ) ) {
                            self::_get_image( $tab['tab']['image'] );
                        }
                    break;
                    case 'video' :
                        if ( isset( $tab['tab']['video'] ) ) {
                            self::_get_video( $tab['tab']['video'] );
                        }
                    break;
                    case 'html' :
                    default :
                        if ( isset( $tab['tab']['html'] ) ) {
                            self::_get_html( $tab['tab']['html'] );
                        }
                    break;
                }
            }
        }

		public static function _get_csv( $tab ) {
            include_once( 'class-csv.php' );
            echo XforWC_AddTabs_CSV::get_table( $tab );
        }

		public static function _get_image( $image ) {
            echo '<img src="' . esc_url( $image ) . '" />';
        }

		public static function _get_video( $video ) {
			parse_str( parse_url( $video, PHP_URL_QUERY), $data );

			if ( isset( $data['v'] ) ) {
				echo '<iframe width="720" height="405" src="https://www.youtube.com/embed/' . esc_attr( $data['v'] ) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
			}
        }

		public static function _get_html( $tabs ) {
            echo do_shortcode( wp_kses_post( $tabs ) );
        }

		function get_tabs( $tabs ) {
			if ( is_array( SevenVXGet()->get_option( 'tabs', 'add_tabs_xforwc' ) ) ) {
                foreach( SevenVXGet()->get_option( 'tabs', 'add_tabs_xforwc' ) as $k => $v ) {
					if ( isset( $v['condition'] ) && $v['condition'] !== '' && $this->get_display_condition( $v['condition'] ) === false ) {
						continue;
					}

                    $tabs[sanitize_title( $v['name'] )] = array(
                        'title' => $v['name'],
                        'callback' => 'XforWC_AddTabs_Frontend::get_tab',
						'tab' => $v,
                    );
                }
			}

			$this->do_fix_tab_options( $tabs );

			return $tabs;
		}

		function do_fix_tab_options( &$tabs ) {
			$this->fix_tab_option( $tabs, 'description' );
			$this->fix_tab_option( $tabs, 'additional_information' );
		}

		function fix_tab_option( &$tabs, $tab ) {
			if ( isset( $tabs[$tab] ) ) {
				if ( SevenVXGet()->get_option( $tab . '_off', 'add_tabs_xforwc' ) == 'yes' ) {
					unset( $tabs[$tab] );
				}
				else if ( !empty( SevenVXGet()->get_option( $tab, 'add_tabs_xforwc' ) ) ) {
					$tabs[$tab]['title'] = esc_html( SevenVXGet()->get_option( $tab, 'add_tabs_xforwc' ) );
				}
			}
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

	add_action( 'init', array( 'XforWC_AddTabs_Frontend', 'init' ) );

	if ( !function_exists( 'mnthemes_add_meta_information' ) ) {
		function mnthemes_add_meta_information_action() {
			echo '<meta name="generator" content="' . esc_attr( implode( ', ', apply_filters( 'mnthemes_add_meta_information_used', array() ) ) ) . '"/>';
		}
		function mnthemes_add_meta_information() {
			add_action( 'wp_head', 'mnthemes_add_meta_information_action', 99 );
		}
		mnthemes_add_meta_information();
	}
