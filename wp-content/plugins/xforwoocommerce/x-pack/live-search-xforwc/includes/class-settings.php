<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_LiveSearch_Settings {

		public static $plugin;

		public static function init() {

			self::$plugin = array(
				'name' => 'Live Search for WooCommerce',
				'xforwc' => 'Live Search',
				'slug' => 'live-search-xforwc',
				'label' => 'live_search_xforwc',
				'image' => XforWC_LiveSearch()->plugin_url() . '/assets/images/live-search-xforwoocommerce.png',
				'path' => 'live-search-xforwc/live-search-xforwc',
				'version' => XforWC_LiveSearch::$version,
			);

			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'live_search_xforwc' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_LiveSearch_Settings', 'get_settings' ), 50 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_LiveSearch_Settings', 'xforwc' ), 9999999211 );
				add_filter( 'xforwc_svx_get_live_search_xforwc', array( 'XforWC_LiveSearch_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_LiveSearch_Settings', 'add_plugin' ), 0 );

		}

		public static function xforwc( $settings ) {
			$settings['plugins'][] = self::$plugin;

			return $settings;
		}

		public static function add_plugin( $plugins ) {
			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => self::$plugin['xforwc']
			);

			return $plugins;
		}

		public static function _get_settings_xforwc() {
			$settings = self::get_settings( array() );
			return $settings[self::$plugin['label']];
		}
		
		public static function get_settings( $plugins ) {

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/live-search/' ),
				'ref' => array(
					'name' => esc_html__( 'Visit XforWooCommerce.com', 'xforwoocommerce' ),
					'url' => 'https://xforwoocommerce.com'
				),
				'doc' => array(
					'name' => esc_html__( 'Get help', 'xforwoocommerce' ),
					'url' => 'https://help.xforwoocommerce.com'
				),
				'sections' => array(
					'dashboard' => array(
						'name' => esc_html__( 'Dashboard', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Dashboard Overview', 'xforwoocommerce' ),
					),
					'general' => array(
						'name' => esc_html__( 'General', 'xforwoocommerce' ),
						'desc' => esc_html__( 'General Overview', 'xforwoocommerce' ),
					),
					'texts' => array(
						'name' => esc_html__( 'Texts', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Texts Overview', 'xforwoocommerce' ),
					),
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
                        'desc' => '	
                            <img src="' . XforWC_LiveSearch()->plugin_url() . '/assets/images/live-search-for-woocommerce.png" class="svx-dashboard-image" />
                            <h3><span class="dashicons dashicons-store"></span> XforWooCommerce</h3>
                            <p>' . esc_html__( 'Visit XforWooCommerce.com store, demos and knowledge base.', 'xforwoocommerce' ) . '</p>
                            <p><a href="https://xforwoocommerce.com" class="xforwc-button-primary x-color" target="_blank">XforWooCommerce.com</a></p>

                            <br /><hr />

                            <h3><span class="dashicons dashicons-admin-tools"></span> ' . esc_html__( 'Help Center', 'xforwoocommerce' ) . '</h3>
                            <p>' . esc_html__( 'Need support? Visit the Help Center.', 'xforwoocommerce' ) . '</p>
                            <p><a href="https://help.xforwoocommerce.com" class="xforwc-button-primary red" target="_blank">XforWooCommerce.com HELP</a></p>
                            
                            <br /><hr />

                            <h3><span class="dashicons dashicons-update"></span> ' . esc_html__( 'Automatic Updates', 'xforwoocommerce' ) . '</h3>
                            <p>' . esc_html__( 'Get automatic updates, by downloading and installing the Envato Market plugin.', 'xforwoocommerce' ) . '</p>
                            <p><a href="https://envato.com/market-plugin/" class="svx-button" target="_blank">Envato Market Plugin</a></p>
                            
                            <br />',
						'section' => 'dashboard',
					),

					'wcmn_utility' => array(
						'name' => esc_html__( 'Plugin Options', 'xforwoocommerce' ),
						'type' => 'utility',
						'id' => 'wcmn_utility',
						'desc' => esc_html__( 'Quick export/import, backup and restore, or just reset your optons here', 'xforwoocommerce' ),
						'section' => 'dashboard',
					),

					'characters' => array(
						'name' => esc_html__( 'Characters to Search', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Trigger search when number of characters is reached', 'xforwoocommerce' ),
						'id'   => 'characters',
						'autoload' => false,
						'default' => '',
						'section' => 'general'
					),

					'separator' => array(
						'name' => esc_html__(  'Category Separator', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter category separator', 'xforwoocommerce' ),
						'id'   => 'separator',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'general'
					),

					'products' => array(
						'name' => esc_html__( 'Products to Display', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Enter how many product to display after search', 'xforwoocommerce' ),
						'id'   => 'products',
						'autoload' => false,
						'default' => '',
						'section' => 'general'
					),

					'placeholder' => array(
						'name' => esc_html__(  'Placeholder', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter placeholder text', 'xforwoocommerce' ),
						'id'   => 'placeholder',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'texts'
					),

					'notfound' => array(
						'name' => esc_html__(  'No Products Found', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter no products found message', 'xforwoocommerce' ),
						'id'   => 'notfound',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'texts'
					),

				),
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_LiveSearch_Settings::init();
