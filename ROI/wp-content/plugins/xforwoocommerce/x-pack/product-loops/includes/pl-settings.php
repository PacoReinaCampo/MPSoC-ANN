<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	class XforWC_Shop_Design_Settings {

		public static $plugin;

		public static function init() {

			self::$plugin = array(
				'name' => 'Product Loops for WooCommerce',
				'xforwc' => 'Shop Design',
				'slug' => 'product-loops',
				'label' => 'product_loops',
				'image' => XforWC_Shop_Design()->plugin_url() . '/assets/images/product-loops-for-woocommerce-elements.png',
				'path' => 'product-loops/product-loops',
				'version' => XforWC_Shop_Design::$version,
			);

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'product_loops' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_Shop_Design_Settings', 'get_settings' ), 50 );
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::scripts', 9 );
			}

			if ( isset($_GET['page']) && ($_GET['page'] == 'xforwoocommerce' ) ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::scripts', 9 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_Shop_Design_Settings', 'xforwc' ), 9999999136 );
				add_filter( 'xforwc_svx_get_product_loops', array( 'XforWC_Shop_Design_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_Shop_Design_Settings', 'add_plugin' ), 0 );

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

		public static function scripts() {
				wp_register_script( 'product-loops', XforWC_Shop_Design()->plugin_url() . '/assets/js/admin.js', array( 'jquery' ), XforWC_Shop_Design()->version(), true );
				wp_enqueue_script( 'product-loops' );
		}

		public static function get_settings( $plugins ) {

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/shop-design/' ),
				'imgs' => XforWC_Shop_Design()->plugin_url(),
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
					'presets' => array(
						'name' => esc_html__( 'Product Loops', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Product Loop Options', 'xforwoocommerce' ),
					),
					'loops' => array(
						'name' => esc_html__( 'Assign Product Loops', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Assign Product Loop Options', 'xforwoocommerce' ),
					),
					'quickview' => array(
						'name' => esc_html__( 'Product Quickview', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Product Quickview Options', 'xforwoocommerce' ),
					),
					'installation' => array(
						'name' => esc_html__( 'Installation', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Installation Options', 'xforwoocommerce' ),
					),
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
						'desc' => '
						<img src="' . XforWC_Shop_Design()->plugin_url() . '/assets/images/product-loops-for-woocommerce-shop.png" class="svx-dashboard-image" />
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

					'wcmn_pl_presets' => array(
						'name' => esc_html__( 'Loop Manager', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => 'wcmn_pl_presets',
						'desc' => esc_html__( 'Create product loop presets', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'presets',
						'title' => esc_html__( 'Loop Name', 'xforwoocommerce' ),
						'options' => 'list',
						'ajax_options' => 'ajax:wp_options:_wcmn_pl_preset_%NAME%',
						'settings' => array(

							'name' => array(
								'name' => esc_html__( 'Loop Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter loop name', 'xforwoocommerce' ),
								'default' => '',
								'column' => '2'
							),

							'loop' => array(
								'name' => esc_html__( 'Loop Type', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select loop type', 'xforwoocommerce' ),
								'id'   => 'loop',
								'options' => array(
									'loop-1' => esc_html__( 'Classic', 'xforwoocommerce' ),
									'loop-2' => esc_html__( 'Elegant', 'xforwoocommerce' ),
									'loop-3' => esc_html__( 'Boxed', 'xforwoocommerce' ),
									'loop-4' => esc_html__( 'Boxed and Colored', 'xforwoocommerce' ),
									'loop-5' => esc_html__( 'Inside bottom', 'xforwoocommerce' ),
									'loop-6' => esc_html__( 'Inside bottom with Meta/Options hover', 'xforwoocommerce' ),
									'loop-7' => esc_html__( 'Inside top', 'xforwoocommerce' ),
									'loop-8' => esc_html__( 'Inside top with Meta/Options hover', 'xforwoocommerce' ),
									'loop-9' => esc_html__( 'Inside top with Meta/Options slide in from bottom', 'xforwoocommerce' ),
									'loop-10' => esc_html__( 'Outside with hover', 'xforwoocommerce' ),
								),
								'default' => 'loop-1',
								'class' => '',
								'column' => '2 last'
							),

							'mode' => array(
								'name' => esc_html__( 'Default Display', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select default display mode', 'xforwoocommerce' ),
								'id'   => 'mode',
								'options' => array(
									'grid' => esc_html__( 'Grid', 'xforwoocommerce' ),
									'table' => esc_html__( 'List', 'xforwoocommerce' ),
								),
								'default' => 'grid',
								'class' => '',
								'column' => '2'
							),

							'button' => array(
								'name' => esc_html__( 'Button', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set button style', 'xforwoocommerce' ),
								'id'   => 'button',
								'options' => array(
									'none' => esc_html__( 'Inherit from theme', 'xforwoocommerce' ),
									'flat' => esc_html__( 'Flat', 'xforwoocommerce' ),
									'flat3d' => esc_html__( 'Flat 3D', 'xforwoocommerce' ),
									'hue' => esc_html__( 'Hue', 'xforwoocommerce' ),
									'hue3d' => esc_html__( 'Hue 3D', 'xforwoocommerce' ),
								),
								'default' => 'hue',
								'class' => '',
								'column' => '2 last'
							),

							'accent_color' => array(
								'name' => esc_html__( 'Accent Color', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'accent_color',
								'desc' => esc_html__( 'Set loop accent color', 'xforwoocommerce' ),
								'default' => '#007ae5',
								'class' => 'svx-color',
								'column' => '2'
							),

							'hover_color' => array(
								'name' => esc_html__( 'Hover Color', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'hover_color',
								'desc' => esc_html__( 'Set loop hover color', 'xforwoocommerce' ),
								'default' => '#5000e5',
								'class' => 'svx-color',
								'column' => '2 last'
							),

							'size' => array(
								'name' => esc_html__( 'Image Size', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set image size', 'xforwoocommerce' ),
								'id'   => 'size',
								'options' => 'ajax:image_sizes',
								'default' => 'shop_catalog',
								'class' => '',
								'column' => '2'
							),

							'ratio' => array(
								'name' => esc_html__( 'Image Ratio', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set image ratio', 'xforwoocommerce' ),
								'id'   => 'ratio',
								'options' => array(
									'none' => esc_html__( 'Use WooCommerce image size', 'xforwoocommerce' ) . ' (shop_catalog)',
									'full' => esc_html__( 'Full Resolution', 'xforwoocommerce' ) . ' (full)',
									'large' => esc_html__( 'Large WordPress', 'xforwoocommerce' ) . ' (large)',
									'medium' => esc_html__( 'Medium WordPress', 'xforwoocommerce' ) . ' (medium)',
									'1-1' => '1 : 1',
									'2-1' => '2 : 1',
									'1-2' => '1 : 2',
									'3-1' => '3 : 1',
									'1-3' => '1 : 3',
									'4-3' => '4 : 3',
									'3-4' => '3 : 4',
									'16-9' => '16 : 9',
									'9-16' => '9 : 16',
									'5-3' => '5 : 3',
									'3-5' => '3 : 5',
								),
								'default' => 'none',
								'class' => '',
								'column' => '2 last'
							),

							'align' => array(
								'name' => esc_html__( 'Image Align', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set image align', 'xforwoocommerce' ),
								'id'   => 'align',
								'options' => array(
									'start' => esc_html__( 'Starting point', 'xforwoocommerce' ),
									'center' => esc_html__( 'Center', 'xforwoocommerce' ),
									'end' => esc_html__( 'Ending point', 'xforwoocommerce' ),
								),
								'default' => 'start',
								'class' => '',
								'column' => '2'
							),

							'effect' => array(
								'name' => esc_html__( 'Image Effect', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set  image effect', 'xforwoocommerce' ),
								'id'   => 'effect',
								'options' => array(
									'none' => esc_html__( 'None', 'xforwoocommerce' ),
									'gallery' => esc_html__( 'Image gallery', 'xforwoocommerce' ),
									'fade' => esc_html__( 'Fade in second image', 'xforwoocommerce' ),
									'slide-up' => esc_html__( 'Slide up second image', 'xforwoocommerce' ),
									'slide-down' => esc_html__( 'Slide down second image', 'xforwoocommerce' ),
									'slide-left' => esc_html__( 'Slide left second image', 'xforwoocommerce' ),
									'slide-right' => esc_html__( 'Slide right second image', 'xforwoocommerce' ),
									'zoom-in' => esc_html__( 'Zoom in second image', 'xforwoocommerce' ),
									'fade-overlay' => esc_html__( 'Fade in overlay', 'xforwoocommerce' ),
									'slide-up-overlay' => esc_html__( 'Slide up overlay', 'xforwoocommerce' ),
									'slide-down-overlay' => esc_html__( 'Slide down overlay', 'xforwoocommerce' ),
									'slide-left-overlay' => esc_html__( 'Slide left overlay', 'xforwoocommerce' ),
									'slide-right-overlay' => esc_html__( 'Slide right overlay', 'xforwoocommerce' ),
									'zoom-in-overlay' => esc_html__( 'Zoom in overlay', 'xforwoocommerce' )
								),
								'default' => 'none',
								'class' => '',
								'column' => '2 last'
							),

							'excerpt_grid' => array(
								'name' => esc_html__( 'Grid Description Rows', 'xforwoocommerce' ),
								'type' => 'number',
								'desc' => esc_html__( 'Set the product short description row length in grid mode', 'xforwoocommerce' ),
								'id'   => 'excerpt_grid',
								'default' => 1,
								'column' => '2'
							),

							'excerpt_table' => array(
								'name' => esc_html__( 'List Description Rows', 'xforwoocommerce' ),
								'type' => 'number',
								'desc' => esc_html__( 'Set the product short description row length in list mode', 'xforwoocommerce' ),
								'id'   => 'excerpt_table',
								'default' => 3,
								'column' => '2 last'
							),

							'hide_grid' => array(
								'name' => esc_html__( 'Grid Elements', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select loop elements to hide in grid mode', 'xforwoocommerce' ),
								'id'   => 'hide_grid',
								'options' => array(
									'img' => esc_html__( 'Product Image', 'xforwoocommerce' ),
									'ttl' => esc_html__( 'Product Title', 'xforwoocommerce' ),
									'dsc' => esc_html__( 'Product Short Description', 'xforwoocommerce' ),
									'mta' => esc_html__( 'Product Meta', 'xforwoocommerce' ),
									'atr' => esc_html__( 'Product Attributes', 'xforwoocommerce' ),
									'prc' => esc_html__( 'Product Price', 'xforwoocommerce' ),
									'add' => esc_html__( 'Product Add to Cart', 'xforwoocommerce' ),
									'qck' => esc_html__( 'Quickview', 'xforwoocommerce' ),
								),
								'default' => array(),
								'class'   => 'svx-selectize',
								'column' => '2'
							),

							'hide_table' => array(
								'name' => esc_html__( 'List Elements', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select loop elements to hide in list mode', 'xforwoocommerce' ),
								'id'   => 'hide_table',
								'options' => array(
									'img' => esc_html__( 'Product Image', 'xforwoocommerce' ),
									'ttl' => esc_html__( 'Product Title', 'xforwoocommerce' ),
									'dsc' => esc_html__( 'Product Short Description', 'xforwoocommerce' ),
									'mta' => esc_html__( 'Product Meta', 'xforwoocommerce' ),
									'atr' => esc_html__( 'Product Attributes', 'xforwoocommerce' ),
									'prc' => esc_html__( 'Product Price', 'xforwoocommerce' ),
									'add' => esc_html__( 'Product Add to Cart', 'xforwoocommerce' ),
									'qck' => esc_html__( 'Quickview', 'xforwoocommerce' ),
								),
								'default' => array(),
								'class' => 'svx-selectize',
								'column' => '2 last'
							),

							'meta_grid' => array(
								'name' => esc_html__( 'Grid Meta', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select meta elements to hide in grid mode', 'xforwoocommerce' ),
								'id'   => 'meta_grid',
								'options' => array(
									'sales' => esc_html__( 'Sales', 'xforwoocommerce' ),
									'author' => esc_html__( 'Author', 'xforwoocommerce' ),
									'date' => esc_html__( 'Date', 'xforwoocommerce' ),
									'category' => esc_html__( 'Category', 'xforwoocommerce' ),
									'tags' => esc_html__( 'Tag', 'xforwoocommerce' ),
									'sku' => esc_html__( 'SKU', 'xforwoocommerce' ),
								),
								'default' => array(),
								'class' => 'svx-selectize',
								'column' => '2'
							),

							'meta_table' => array(
								'name' => esc_html__( 'List Meta', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select meta elements to hide in table mode', 'xforwoocommerce' ),
								'id'   => 'meta_table',
								'options' => array(
									'sales' => esc_html__( 'Sales', 'xforwoocommerce' ),
									'author' => esc_html__( 'Author', 'xforwoocommerce' ),
									'date' => esc_html__( 'Date', 'xforwoocommerce' ),
									'category' => esc_html__( 'Category', 'xforwoocommerce' ),
									'tags' => esc_html__( 'Tag', 'xforwoocommerce' ),
									'sku' => esc_html__( 'SKU', 'xforwoocommerce' ),
								),
								'default' => array(),
								'class' => 'svx-selectize',
								'column' => '2 last'
							),

							'attributes_grid' => array(
								'name' => esc_html__( 'Grid Attributes', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select attributes to show in grid mode', 'xforwoocommerce' ),
								'id'   => 'attributes_grid',
								'options' => 'ajax:product_attributes',
								'default' => array(),
								'class' => 'svx-selectize',
								'column' => '2'
							),

							'attributes_table' => array(
								'name' => esc_html__( 'List Attributes', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select attributes to show in table mode', 'xforwoocommerce' ),
								'id'   => 'attributes_table',
								'options' => 'ajax:product_attributes',
								'default' => array(),
								'class' => 'svx-selectize',
								'column' => '2 last'
							),

							'column' => array(
								'name' => esc_html__( 'Grid Columns', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set grid product columns', 'xforwoocommerce' ),
								'id'   => 'column',
								'options' => array(
									'inherit' => esc_html( 'inherit', 'xforwoocommerce' ),
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
									'9' => '9',
									'10' => '10',
								),
								'default' => '3',
								'class' => '',
								'column' => '2'
							),
							
							'text_size' => array(
								'name' => esc_html__( 'Text Size', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set text size', 'xforwoocommerce' ),
								'id'   => 'text_size',
								'options' => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
								),
								'default' => '3',
								'class' => '',
								'column' => '2 last'
							),

							'image_width_table' => array(
								'name' => esc_html__( 'List Image Width', 'xforwoocommerce' ),
								'type' => 'number',
								'desc' => esc_html__( 'Set the image width in list mode (em)', 'xforwoocommerce' ),
								'id'   => 'image_width_table',
								'default' => 15,
								'column' => '2'
							),

							'gap' => array(
								'name' => esc_html__( 'Grid Gap', 'xforwoocommerce' ),
								'type' => 'select',
								'desc' => esc_html__( 'Set gap between grid columns', 'xforwoocommerce' ),
								'id'   => 'gap',
								'options' => array(
									'0' => '0em',
									'1' => '.25em',
									'2' => '.5em',
									'3' => '.75em',
									'4' => '1em',
									'5' => '1.25em',
									'6' => '1.5em',
									'7' => '1.75em',
									'8' => '2em',
									'9' => '2.25em',
									'10' => '2.5em',
									'11' => '2.75em',
									'12' => '3em',
									'13' => '3.25em',
									'14' => '3.5em',
									'15' => '3.75em',
									'16' => '4em',
									'17' => '4.25em',
									'18' => '4.5em',
									'19' => '4.75em',
									'20' => '5em',
								),
								'default' => '3',
								'class' => '',
								'column' => '2 last'
							),

						),
					),

					'wcmn_pl_presets_default_loop' => array(
						'name' => esc_html__( 'Shop Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select shop loop preset', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_default_loop',
						'autoload' => false,
						'options' => 'read:wcmn_pl_presets',
						'default' => '',
						'section' => 'loops',
						'class' => 'svx-selectize',
					),

					'wcmn_pl_presets_related_loop' => array(
						'name' => esc_html__( 'Related Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select related loop preset', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_related_loop',
						'autoload' => false,
						'options' => 'read:wcmn_pl_presets',
						'default' => '',
						'section' => 'loops',
						'class' => 'svx-selectize',
					),

					'wcmn_pl_presets_upsells_loop' => array(
						'name' => esc_html__( 'Upsells Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select upsells loop preset', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_upsells_loop',
						'autoload' => false,
						'options' => 'read:wcmn_pl_presets',
						'default' => '',
						'section' => 'loops',
						'class' => 'svx-selectize',
					),

					'wcmn_pl_presets_cross_sells_loop' => array(
						'name' => esc_html__( 'Cross-Sells Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select cross-sells loop preset', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_cross_sells_loop',
						'autoload' => false,
						'options' => 'read:wcmn_pl_presets',
						'default' => '',
						'section' => 'loops',
						'class' => 'svx-selectize',
					),

					'wcmn_pl_overrides' => array(
						'name' => esc_html__( 'Product Loop Overrides', 'xforwoocommerce' ),
						'type' => 'hidden',
						'id'   => 'wcmn_pl_overrides',
						'desc' => esc_html__( 'Set loop overrides', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'hidden',
					),

					'_wcmn_outofstock' => array(
						'name' => esc_html__( 'Out of Stock Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'id'   => '_wcmn_outofstock',
						'desc' => esc_html__( 'Set out of stock product loop preset', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'options' => 'read:wcmn_pl_presets',
						'class' => 'svx-selectize',
					),

					'_wcmn_sale' => array(
						'name' => esc_html__( 'On Sale Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'id'   => '_wcmn_sale',
						'desc' => esc_html__( 'Set on sale product loop preset', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'options' => 'read:wcmn_pl_presets',
						'class' => 'svx-selectize',
					),

					'_wcmn_expire_in' => array(
						'name' => esc_html__( 'New Product Loop Period', 'xforwoocommerce' ),
						'type' => 'number',
						'id'   => '_wcmn_expire_in',
						'desc' => esc_html__( 'Set new product loop expire in period (days)', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
					),

					'_wcmn_expire_in_preset' => array(
						'name' => esc_html__( 'New Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'id'   => '_wcmn_expire_in_preset',
						'desc' => esc_html__( 'Set new product loop preset', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'options' => 'read:wcmn_pl_presets',
						'class' => 'svx-selectize',
					),

					'_wcmn_featured' => array(
						'name' => esc_html__( 'Featured Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'id'   => '_wcmn_featured',
						'desc' => esc_html__( 'Set featured product loop preset', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'options' => 'read:wcmn_pl_presets',
						'class' => 'svx-selectize',
					),

					'_wcmn_tags' => array(
						'name' => esc_html__( 'Tag Product Loops', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => '_wcmn_tags',
						'desc' => esc_html__( 'Add tag product loop presets', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'title' => esc_html__( 'Name', 'xforwoocommerce' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter override name', 'xforwoocommerce' ),
								'default' => ''
							),
							'term' => array(
								'name' => esc_html__( 'Term', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Set override term', 'xforwoocommerce' ),
								'options' => 'ajax:taxonomy:product_tag:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Set override preset', 'xforwoocommerce' ),
								'options' => 'read:wcmn_pl_presets',
								'default' => '',
								'class' => 'svx-selectize',
							),
						),
					),

					'_wcmn_categories' => array(
						'name' => esc_html__( 'Category Product Loops', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => '_wcmn_categories',
						'desc' => esc_html__( 'Add category product loop presets', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'loops',
						'title' => esc_html__( 'Name', 'xforwoocommerce' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter override name', 'xforwoocommerce' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Term', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Set override term', 'xforwoocommerce' ),
								'options' => 'ajax:taxonomy:product_cat:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Set override preset', 'xforwoocommerce' ),
								'options' => 'read:wcmn_pl_presets',
								'default' => '',
								'class' => 'svx-selectize',
							),
						),
					),

					'wcmn_pl_quickview_related' => array(
						'name' => esc_html__( 'Related Products', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Show related products in quick view', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_quickview_related',
						'default' => 'no',
						'autoload' => false,
						'section' => 'quickview',
					),

					'wcmn_pl_presets_quickview_related_loop' => array(
						'name' => esc_html__( 'Related Product Loop', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select quickview related product loop preset', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_quickview_related_loop',
						'autoload' => false,
						'options' => 'read:wcmn_pl_presets',
						'default' => '',
						'section' => 'quickview',
						'class' => 'svx-selectize',
					),

					'wcmn_pl_presets_quickview_related_limit' => array(
						'name' => esc_html__( 'Related Product Limit', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Select quickview related product limit', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_presets_quickview_related_limit',
						'autoload' => false,
						'default' => '4',
						'section' => 'quickview'
					),

					'wcmn_pl_isotope' => array(
						'name' => esc_html__( 'Isotope Mode', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Isotope.js library settings', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_isotope',
						'options' => array(
							'disable' => esc_html__( 'Disable library and use CSS layout mode', 'xforwoocommerce' ),
							'packery' => esc_html__( 'Packery', 'xforwoocommerce' ),
							'masonry' => esc_html__( 'Masonry (Default)', 'xforwoocommerce' ),
							'fitRows' => esc_html__( 'Fit Rows', 'xforwoocommerce' ),

						),
						'default' => 'packery',
						'autoload' => false,
						'section' => 'installation'
					),

					'wcmn_pl_session' => array(
						'name' => esc_html__( 'Grid/List Session', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Remember if users switch grid to list view and vice versa', 'xforwoocommerce' ),
						'id'   => 'wcmn_pl_session',
						'default' => 'no',
						'autoload' => false,
						'section' => 'installation'
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_Shop_Design_Settings::init();
