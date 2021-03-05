<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_FloatingCart_Settings {

		public static $plugin;
		
		public static function init() {

			self::$plugin = array(
				'name' => 'Floating Cart for WooCommerce',
				'xforwc' => 'Floating Cart',
				'slug' => 'floating-cart-xforwc',
				'label' => 'floating_cart_xforwc',
				'image' => XforWC_FloatingCart()->plugin_url() . '/assets/images/floating-cart-for-woocommerce-xforwoocommerce.png',
				'path' => 'floating-cart-xforwc/floating-cart-xforwc',
				'version' => XforWC_FloatingCart::$version,
			);

			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'floating_cart_xforwc' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_FloatingCart_Settings', 'get_settings' ), 50 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_FloatingCart_Settings', 'xforwc' ), 9999999221 );
				add_filter( 'xforwc_svx_get_floating_cart_xforwc', array( 'XforWC_FloatingCart_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_FloatingCart_Settings', 'add_plugin' ), 0 );
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
				'link' => esc_url( 'https://xforwoocommerce.com/store/floating-cart/' ),
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
					'cart_contents' => array(
						'name' => esc_html__( 'Cart Contents', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Cart Contents Overview', 'xforwoocommerce' ),
					),
					'message' => array(
						'name' => esc_html__( 'Cart Message', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Cart Message Overview', 'xforwoocommerce' ),
					),
					'texts' => array(
						'name' => esc_html__( 'Texts', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Texts Overview', 'xforwoocommerce' ),
					),
					'install' => array(
						'name' => esc_html__( 'Install', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Install Overview', 'xforwoocommerce' ),
					),
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
                        'desc' => '	
                            <img src="' . XforWC_FloatingCart()->plugin_url() . '/assets/images/floating-cart-for-woocommerce.png" class="svx-dashboard-image" />
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

					'position' => array(
						'name' => esc_html__( 'Cart Position', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select Cart position', 'xforwoocommerce' ),
						'id'   => 'position',
						'autoload' => false,
						'options' => array(
							'inline' => esc_html__( 'Shortcode', 'xforwoocommerce' ),
							'top-right' => esc_html__( 'Top right', 'xforwoocommerce' ),
							'top-left' => esc_html__( 'Top left', 'xforwoocommerce' ),
							'bottom-right' => esc_html__( 'Bottom right', 'xforwoocommerce' ),
							'bottom-left' => esc_html__( 'Bottom left', 'xforwoocommerce' ),
						),
						'default' => 'top-right',
						'section' => 'general'
					),

					'checkout' => array(
						'name' => esc_html__( 'Show Checkout', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show Checkout', 'xforwoocommerce' ),
						'id'   => 'checkout',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'general'
					),

					'cart_content' => array(
						'name' => esc_html__( 'Show Cart Contents', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show Cart contents', 'xforwoocommerce' ),
						'id'   => 'cart_content',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'cart_contents'
					),

					'cart_overlay' => array(
						'name' => esc_html__( 'Use Overlay', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Display dark overlay over website when showing cart contents', 'xforwoocommerce' ),
						'id'   => 'cart_overlay',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'cart_contents'
					),

					'cart_message' => array(
						'name' => esc_html__( 'Show Cart Message', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Display added to cart message', 'xforwoocommerce' ),
						'id'   => 'cart_message',
						'autoload' => false,
						'default' => 'yes',
						'section' => 'message'
					),

					'cart_message_delay' => array(
						'name' => esc_html__( 'Cart Message Life', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Display added to cart message for how long in miliseconds', 'xforwoocommerce' ),
						'id'   => 'cart_message_delay',
						'autoload' => false,
						'default' => '',
						'section' => 'message'
					),

					'cart_message_text' => array(
						'name' => esc_html__( 'Cart Message Text', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter added to cart message text', 'xforwoocommerce' ),
						'id'   => 'cart_message_text',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'texts'
					),

					'cart_empty_text' => array(
						'name' => esc_html__( 'Cart Empty Text', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Enter cart empty text', 'xforwoocommerce' ),
						'id'   => 'cart_empty_text',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'texts'
					),

					'install_type' => array(
						'name' => esc_html__( 'Display Cart', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Where to show the Cart', 'xforwoocommerce' ),
						'id'   => 'install_type',
						'options' => array(
							'everywhere' => esc_html__( 'Everywhere', 'xforwoocommerce' ),
							'woocommerce' => esc_html__( 'WooCommerce Pages', 'xforwoocommerce' ),
							'disable' => esc_html__( 'Disabled', 'xforwoocommerce' ),
						),
						'autoload' => false,
						'default' => 'everywhere',
						'section' => 'install'
					),

					'pages' => array(
						'name' => esc_html__( 'Active Only in Pages', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'To activate plugin only in pages enter page IDs separated by | Sample: &rarr; ', 'xforwoocommerce' ) . '<code>7|55</code>',
						'id'   => 'pages',
						'autoload' => false,
						'default' => '',
						'section' => 'install'
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_FloatingCart_Settings::init();
