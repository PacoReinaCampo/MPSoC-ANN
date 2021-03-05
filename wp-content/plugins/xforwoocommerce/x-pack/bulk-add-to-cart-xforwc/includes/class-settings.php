<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_BulkAddtoCart_Settings {

		public static $plugin;
		
		public static function init() {

			self::$plugin = array(
				'name' => 'Bulk Add to Cart for WooCommerce',
				'xforwc' => 'Bulk Add to Cart',
				'slug' => 'bulk-add-to-cart-xforwc',
				'label' => 'bulk_add_to_cart_xforwc',
				'image' => XforWC_BulkAddtoCart()->plugin_url() . '/assets/images/bulk-add-to-cart-xforwoocommerce.png',
				'path' => 'bulk-add-to-cart-xforwc/bulk-add-to-cart-xforwc',
				'version' => XforWC_BulkAddtoCart::$version,
			);

			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'bulk_add_to_cart_xforwc' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_BulkAddtoCart_Settings', 'get_settings' ), 50 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_BulkAddtoCart_Settings', 'xforwc' ), 9999999201 );
				add_filter( 'xforwc_svx_get_bulk_add_to_cart_xforwc', array( 'XforWC_BulkAddtoCart_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_BulkAddtoCart_Settings', 'add_plugin' ), 0 );
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
				'link' => esc_url( 'https://xforwoocommerce.com/store/bulk-add-to-cart/' ),
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
					'settings' => array(
						'name' => esc_html__( 'General', 'xforwoocommerce' ),
						'desc' => esc_html__( 'General Overview', 'xforwoocommerce' ),
					),
					'bulk_tool' => array(
						'name' => esc_html__( 'Bulk Tool', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Bulk Tool Overview', 'xforwoocommerce' ),
					),
					'install' => array(
						'name' => esc_html__( 'Installation', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Installation Overview', 'xforwoocommerce' ),
					),
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
                        'desc' => '	
                            <img src="' . XforWC_BulkAddtoCart()->plugin_url() . '/assets/images/bulk-add-to-cart-for-woocommerce.png" class="svx-dashboard-image" />
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

					'style' => array(
						'name' => esc_html__( 'Style', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select general style', 'xforwoocommerce' ),
						'id'   => 'style',
						'autoload' => false,
						'options' => array(
							'fun' => esc_html__( 'Green', 'xforwoocommerce' ),
							'blue' => esc_html__( 'Blue', 'xforwoocommerce' ),
							'dark' => esc_html__( 'Dark', 'xforwoocommerce' ),
							'gray' => esc_html__( 'Gray', 'xforwoocommerce' ),
						),
						'default' => 'dark',
						'section' => 'settings'
					),

					'qty' => array(
						'name' => esc_html__( 'Use Quantities', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show product quantity fields', 'xforwoocommerce' ),
						'id'   => 'qty',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'settings'
					),

					'position' => array(
						'name' => esc_html__( 'Bulk Tool Position', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select Bulk Add to Cart button position', 'xforwoocommerce' ),
						'id'   => 'position',
						'autoload' => false,
						'options' => array(
							'top-right' => esc_html__( 'Fixed - Top, right', 'xforwoocommerce' ),
							'top-left' => esc_html__( 'Fixed - Top, left', 'xforwoocommerce' ),
							'bottom-right' => esc_html__( 'Fixed - Bottom, right', 'xforwoocommerce' ),
							'bottom-left' => esc_html__( 'Fixed - Top, left', 'xforwoocommerce' ),
							'before-loop' => esc_html__( 'Before Shop Products', 'xforwoocommerce' ),
							'after-description' => esc_html__( 'After Shop Description', 'xforwoocommerce' ),
						),
						'default' => 'top-right',
						'section' => 'bulk_tool'
					),

					'bulk' => array(
						'name' => esc_html__( 'Show Select/Deselect All', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show Select/Deselect all items in Bulk Tool', 'xforwoocommerce' ),
						'id'   => 'bulk',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'bulk_tool'
					),

					'cart' => array(
						'name' => esc_html__( 'Show Cart', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show cart in Bulk Tool', 'xforwoocommerce' ),
						'id'   => 'cart',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'bulk_tool'
					),

					'checkout' => array(
						'name' => esc_html__( 'Show Checkout', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show Checkout in Bulk Tool', 'xforwoocommerce' ),
						'id'   => 'checkout',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'bulk_tool'
					),

					'woocommerce' => array(
						'name' => esc_html__( 'Active in Shop', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show bulk add to cart fields in WooCommerce pages', 'xforwoocommerce' ),
						'id'   => 'woocommerce',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'install'
					),

					'pages' => array(
						'name' => esc_html__( 'Active in Pages', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'To activate plugin in pages enter page IDs separated by | Sample: &rarr; ', 'xforwoocommerce' ) . '<code>7|55</code>',
						'id'   => 'pages',
						'autoload' => false,
						'default' => '',
						'section' => 'install'
					),

				),
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
			
		}

	}

	XforWC_BulkAddtoCart_Settings::init();
