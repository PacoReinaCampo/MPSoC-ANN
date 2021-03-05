<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_SEO_Settings {

		public static $plugin;

		public static function init() {

			self::$plugin = array(
				'name' => 'Autopilot SEO for WooCommerce',
				'xforwc' => 'Seach Engine Optimization (SEO)',
				'slug' => 'seo-for-woocommerce',
				'label' => 'seo_for_woocommerce',
				'image' => XforWC_SEO()->plugin_url() . '/includes/images/autopilot-seo-for-woocommerce-elements.png',
				'path' => 'seo-for-woocommerce/seo-for-woocommerce',
				'version' => XforWC_SEO::$version,
			);

			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'seo_for_woocommerce' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_SEO_Settings', 'get_settings' ), 50 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_SEO_Settings', 'xforwc' ), 9999999131 );
				add_filter( 'xforwc_svx_get_seo_for_woocommerce', array( 'XforWC_SEO_Settings', '_get_settings_xforwc' ) );
			}

			add_action( 'admin_enqueue_scripts', array( 'XforWC_SEO_Settings', 'scripts' ) );

			add_filter( 'svx_plugins', array( 'XforWC_SEO_Settings', 'add_plugin' ), 0 );

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

		public static function scripts( $hook ) {

			if ( $hook == 'woocommerce_page_wc-settings' && isset( $_GET['page'], $_GET['tab'] ) && $_GET['page'] == 'wc-settings' && $_GET['tab'] == 'seo_for_woocommerce' ) {
				$init = true;
			}

			if ( isset( $_GET['page']) && $_GET['page'] == 'xforwoocommerce' ) {
				$init = true;
			}

			if ( in_array( $hook, array( 'post.php', 'post-new.php', 'woocommerce_page_wc-settings' ) ) ) {
				$init = true;
			}

			if ( !isset( $init ) ) {
				return false;
			}

			$autopilot['hook'] = $hook;
			$autopilot['site_name'] = get_bloginfo( 'name' );
			$autopilot['home_url'] = home_url( '/' );
			$autopilot['tools'] = array(
				array(
					'%site-title%',
					'common',
					esc_html__( 'Site title', 'xforwoocommerce' ),
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'%site-tagline%',
					'common',
					esc_html__( 'Site tagline', 'xforwoocommerce' ),
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'%title%',
					'common',
					esc_html__( 'Title', 'xforwoocommerce' ),
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'%content%',
					'common',
					esc_html__( 'Content', 'xforwoocommerce' ),
					array( 'page', 'post', 'shop', ),
				),
				array(
					'%excerpt%',
					'common',
					esc_html__( 'Excerpt', 'xforwoocommerce' ),
					array( 'page', 'post', 'shop', ),
				),
				array(
					'%separator%',
					'special',
					esc_html__( 'Separator', 'xforwoocommerce' ),
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'%archive-title%',
					'archive',
					esc_html__( 'Archive title', 'xforwoocommerce' ),
					array( 'blog','product_taxonomy', ),
				),
				array(
					'%archive-desc%',
					'archive',
					esc_html__( 'Archive description', 'xforwoocommerce' ),
					array( 'blog','product_taxonomy', ),
				),
				array(
					'%archive-name%',
					'archive',
					esc_html__( 'Archive name', 'xforwoocommerce' ),
					array( 'blog','product_taxonomy', ),
				),
				array(
					'%currency%',
					'woocommerce',
					esc_html__( 'Currency', 'xforwoocommerce' ),
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'%product-content%',
					'woocommerce',
					esc_html__( 'Product content', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%product-short-desc%',
					'woocommerce',
					esc_html__( 'Product short description', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%price%',
					'woocommerce',
					esc_html__( 'Price', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%regular-price%',
					'woocommerce',
					esc_html__( 'Regular price', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%sale-price%',
					'woocommerce',
					esc_html__( 'Sale price', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%product-cat%',
					'woocommerce',
					esc_html__( 'Product category', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%product-tag%',
					'woocommerce',
					esc_html__( 'Product tag', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%brand%',
					'woocommerce',
					esc_html__( 'Product brand', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%manufacturer%',
					'woocommerce',
					esc_html__( 'Product manufacturer', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%availability%',
					'woocommerce',
					esc_html__( 'Product availability', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%color%',
					'woocommerce',
					esc_html__( 'Product color', 'xforwoocommerce' ),
					array( 'product', ),
				),
				array(
					'%condition%',
					'woocommerce',
					esc_html__( 'Product condition', 'xforwoocommerce' ),
					array( 'product', ),
				),

				array(
					'←',
					'separator',
					'&larr;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'→',
					'separator',
					'&rarr;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'–',
					'separator',
					'&ndash;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'—',
					'separator',
					'&mdash;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'·',
					'separator',
					'&middot;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'⋅',
					'separator',
					'&middot;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'•',
					'separator',
					'&bull;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'♥',
					'separator',
					'&hearts;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'★',
					'separator',
					'&#x2605;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
				array(
					'☆',
					'separator',
					'&#x2606;',
					array( 'page', 'post', 'blog', 'shop', 'product', 'product_taxonomy', ),
				),
			);

			wp_register_script( 'seo-wc-js', XforWC_SEO()->plugin_url() . '/includes/js/admin.js', array( 'jquery' ), XforWC_SEO()->version(), true );
			wp_enqueue_script( 'seo-wc-js' );

			wp_localize_script( 'seo-wc-js', 'autopilot', $autopilot );

			wp_register_style( 'seo-wc-css', XforWC_SEO()->plugin_url() . '/includes/css/admin' . ( is_rtl() ? '-rtl' : '' ) . '.css', XforWC_SEO()->version() );
			wp_enqueue_style( 'seo-wc-css' );
		}

		public static function get_settings( $plugins ) {

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/search-engine-optimization-seo/' ),
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
					'social' => array(
						'name' => esc_html__( 'Social', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Social Options', 'xforwoocommerce' ),
					),
					'woocommerce' => array(
						'name' => esc_html__( 'WooCommerce', 'xforwoocommerce' ),
						'desc' => esc_html__( 'WooCommerce Options', 'xforwoocommerce' ),
					),
					'product' => array(
						'name' => esc_html__( 'Product Page', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Product Page Options', 'xforwoocommerce' ),
					),
					'shop' => array(
						'name' => esc_html__( 'Shop/Archive', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Shop/Archive Options', 'xforwoocommerce' ),
					),
					'pages' => array(
						'name' => esc_html__( 'Pages', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Pages Options', 'xforwoocommerce' ),
					),
					'blog' => array(
						'name' => esc_html__( 'Blog', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Blog Options', 'xforwoocommerce' ),
					),
					'meta' => array(
						'name' => esc_html__( 'Add Meta', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Add Meta Options', 'xforwoocommerce' ),
					),
				),
				'settings' => array(

					'wcmn_dashboard' => array(
						'type' => 'html',
						'id' => 'wcmn_dashboard',
						'desc' => '<div class="seo-alexa"><div id="seo-alexa-rating" class="seo-alexa-rating"><span class="seo-alexa-loading"></span></div><h1>' . esc_html__( 'Welcome to Autopilot SEO Dashboard', 'xforwoocommerce' ) . '</h1><p>' . get_bloginfo( 'sitename' ) . ' &rarr; ' . esc_html__( 'Site metrics are provided by', 'xforwoocommerce' ) . ' <a href="https://www.alexa.com/siteinfo/xforwoocommerce.com">Alexa.com</a></p><p class="check-options"></p></div>

						<img src="' . XforWC_SEO()->plugin_url() . '/includes/images/autopilot-seo-for-woocommerce.png" class="svx-dashboard-image" />
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

					'wcmn_seo_separator' => array(
						'name' => esc_html__( 'Separator', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_separator',
						'desc' => esc_html__( 'Select separator element for format builders', 'xforwoocommerce' ),
						'options' => array(
							'asd' => 'asd',
							'-' => '-',
							'&lt;' => '&lt;',
							'&gt;' => '&gt;',
							'&rarr;' => '&rarr;',
							'&ndash;' => '&ndash;',
							'&mdash;' => '&mdash;',
							'&middot;' => '&middot;',
							'&sdo;' => '&sdot;',
							'&bull;' => '&bull;',
							'&hearts;' => '&hearts;',
							'&#x2605;' => '&#x2605;',
							'&#x2606;' => '&#x2606;',
						),
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => '-',
					),

					'wcmn_seo_brand' => array(
						'name' => esc_html__( 'Brands', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_brand',
						'desc' => esc_html__( 'Select brands taxonomy or attribute to use', 'xforwoocommerce' ),
						'options' => 'ajax:product_taxonomies:has_none',
						'autoload' => true,
						'section' => 'woocommerce',
						'default' => false,
					),

					'wcmn_seo_manufacturer' => array(
						'name' => esc_html__( 'Manufacturer', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_manufacturer',
						'desc' => esc_html__( 'Select manufacturer taxonomy or attribute to use', 'xforwoocommerce' ),
						'options' => 'ajax:product_taxonomies:has_none',
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => false,
					),

					'wcmn_seo_color' => array(
						'name' => esc_html__( 'Color', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_color',
						'desc' => esc_html__( 'Select color taxonomy or attribute to use', 'xforwoocommerce' ),
						'options' => 'ajax:product_taxonomies:has_none',
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => false,
					),

					'wcmn_seo_condition' => array(
						'name' => esc_html__( 'Condition', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_condition',
						'desc' => esc_html__( 'Select condition taxonomy or attribute to use', 'xforwoocommerce' ),
						'options' => 'ajax:product_taxonomies:has_none',
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => false,
					),

					'wcmn_seo_material' => array(
						'name' => esc_html__( 'Material', 'xforwoocommerce' ),
						'type' => 'select',
						'id' => 'wcmn_seo_material',
						'desc' => esc_html__( 'Select material taxonomy or attribute to use', 'xforwoocommerce' ),
						'options' => 'ajax:product_taxonomies:has_none',
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => false,
					),

					'wcmn_seo_authors' => array(
						'name' => esc_html__( 'Use Authors (Vendors)', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'id' => 'wcmn_seo_authors',
						'desc' => esc_html__( 'If this option is checked authors (vendors) will be used for author in the metadata. If this option is unchecked only site publisher will be used', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => 'no',
					),

					'wcmn_seo_product_image' => array(
						'name' => esc_html__( 'Product Image', 'xforwoocommerce' ),
						'type' => 'text',
						'id'   => 'wcmn_seo_product_image',
						'desc' => esc_html__( 'If product has no featured image enter URL of the image to show', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'woocommerce',
						'default' => '',
					),

					'wcmn_seo_googleplus' => array(
						'name' => esc_html__( 'Google+ Profile ID', 'xforwoocommerce' ),
						'type' => 'text',
						'id'   => 'wcmn_seo_googleplus',
						'desc' => esc_html__( 'Enter your Google+ Profile ID. If not set, admin user Google+ option will be used', 'xforwoocommerce' ),
						'autoload' => true,
						'section' => 'social',
						'default' => '',
					),

					'wcmn_seo_facebook' => array(
						'name' => esc_html__( 'Facebook Profile ID', 'xforwoocommerce' ),
						'type' => 'text',
						'id'   => 'wcmn_seo_facebook',
						'desc' => esc_html__( 'Enter your Facebook Profile ID. If not set, admin user Facebook option will be used', 'xforwoocommerce' ),
						'autoload' => true,
						'section' => 'social',
						'default' => '',
					),

					'wcmn_seo_facebook_app' => array(
						'name' => esc_html__( 'Facebook APP ID', 'xforwoocommerce' ),
						'type' => 'text',
						'id'   => 'wcmn_seo_facebook_app',
						'desc' => esc_html__( 'Enter your Facebook APP ID', 'xforwoocommerce' ),
						'autoload' => true,
						'section' => 'social',
						'default' => '',
					),

					'wcmn_seo_twitter' => array(
						'name' => esc_html__( 'Twitter Username', 'xforwoocommerce' ),
						'type' => 'text',
						'id'   => 'wcmn_seo_twitter',
						'desc' => esc_html__( 'Enter your Twitter username (without @). If not set, admin user Twitter option will be used', 'xforwoocommerce' ),
						'autoload' => true,
						'section' => 'social',
						'default' => '',
					),

					'wcmn_seo_twitter_data' => array(
						'name' => esc_html__( 'Twitter Data', 'xforwoocommerce' ),
						'type' => 'multiselect',
						'id' => 'wcmn_seo_twitter_data',
						'class' => 'svx-selectize',
						'desc' => esc_html__( 'Set which Twitter labels to include', 'xforwoocommerce' ),
						'options' => array(
							'price' => esc_html__( 'Price', 'xforwoocommerce' ),
							'availability' => esc_html__( 'Availability', 'xforwoocommerce' ),
							'category' => esc_html__( 'Category', 'xforwoocommerce' ),
							'brand' => esc_html__( 'Brand', 'xforwoocommerce' ),
							'manufacturer' => esc_html__( 'Manufacturer', 'xforwoocommerce' ),
							'color' => esc_html__( 'Color', 'xforwoocommerce' ),
							'condition' => esc_html__( 'Condition', 'xforwoocommerce' ),
							'material' => esc_html__( 'Material', 'xforwoocommerce' ),
						),
						'autoload' => false,
						'section' => 'social',
						'default' => array( 'price', 'category' ),
					),

					'wcmn_seo_products_title' => array(
						'name' => esc_html__( 'Product Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_products_title',
						'desc' => esc_html__( 'Enter products title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'product',
						'default' => '%title% %separator% %price% %separator% %brand% %separator% %site-title%',
						'class' => 'seo-terms seo-type-product',
					),

					'wcmn_seo_products_desc' => array(
						'name' => esc_html__( 'Product Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_products_desc',
						'desc' => esc_html__( 'Enter products description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'product',
						'default' => '%brand% %separator% %price% %separator% %product-short-desc%',
						'class' => 'seo-terms seo-type-product',
					),

					'wcmn_seo_product_types' => array(
						'name' => esc_html__( 'Product Types', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => 'wcmn_seo_product_types',
						'desc' => esc_html__( 'Use manager to create product types title and description formats', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'product',
						'title' => esc_html__( 'Type Name', 'xforwoocommerce' ),
						'options' => 'list',
						'settings' => array(

							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),

							'type' => array(
								'name' => esc_html__( 'Select Type', 'xforwoocommerce' ),
								'type' => 'select',
								'id' => 'type',
								'desc' => esc_html__( 'Set search appearance format to the selected product type', 'xforwoocommerce' ),
								'options' => 'ajax:product_types',
								'default' => '',
							),

							'disable' => array(
								'name' => esc_html__( 'Disable Type', 'xforwoocommerce' ),
								'type' => 'checkbox',
								'id' => 'disable',
								'desc' => esc_html__( 'Check this option to remove this product type from search results', 'xforwoocommerce' ),
								'default' => 'no',
							),

							'title' => array(
								'name' => esc_html__( 'Title', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'title',
								'desc' => esc_html__( 'Enter product title format that will appear in search results', 'xforwoocommerce' ),
								'default' => '',
								'class' => 'seo-terms seo-type-product',
							),

							'desc' => array(
								'name' => esc_html__( 'Description', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'desc',
								'desc' => esc_html__( 'Enter product description format that will appear in search results', 'xforwoocommerce' ),
								'default' => '',
								'class' => 'seo-terms seo-type-product',
							),

						),
					),

					'wcmn_seo_shop_title' => array(
						'name' => esc_html__( 'Shop/Archive Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_shop_title',
						'desc' => esc_html__( 'Enter products title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'shop',
						'default' => '%title% %separator% %site-title%',
						'class' => 'seo-terms seo-type-shop',
					),

					'wcmn_seo_shop_desc' => array(
						'name' => esc_html__( 'Shop/Archive Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_shop_desc',
						'desc' => esc_html__( 'Enter products description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'shop',
						'default' => '%excerpt%',
						'class' => 'seo-terms seo-type-shop',
					),

					'wcmn_seo_taxonomies' => array(
						'name' => esc_html__( 'Shop/Archive Types', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => 'wcmn_seo_taxonomies',
						'desc' => esc_html__( 'Use manager to create Shop/Archive title and description formats', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'shop',
						'title' => esc_html__( 'Archive Name', 'xforwoocommerce' ),
						'options' => 'list',
						'settings' => array(

							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),

							'taxonomy' => array(
								'name' => esc_html__( 'Select Archive', 'xforwoocommerce' ),
								'type' => 'select',
								'id' => 'taxonomy',
								'desc' => esc_html__( 'Set search appearance format to the selected product archive type', 'xforwoocommerce' ),
								'options' => 'ajax:product_taxonomies',
								'default' => '',
							),

							'disable' => array(
								'name' => esc_html__( 'Disable Archive', 'xforwoocommerce' ),
								'type' => 'checkbox',
								'id' => 'disable',
								'desc' => esc_html__( 'Check this option to remove this archive from search results', 'xforwoocommerce' ),
								'default' => 'no',
							),

							'title' => array(
								'name' => esc_html__( 'Title', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'title',
								'desc' => esc_html__( 'Enter product title format that will appear in search results', 'xforwoocommerce' ),
								'default' => '',
								'class' => 'seo-terms seo-type-product_taxonomy',
							),

							'desc' => array(
								'name' => esc_html__( 'Description', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'desc',
								'desc' => esc_html__( 'Enter product description format that will appear in search results', 'xforwoocommerce' ),
								'default' => '',
								'class' => 'seo-terms seo-type-product_taxonomy',
							),

						),
					),

					'wcmn_seo_home_title' => array(
						'name' => esc_html__( 'Home Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_home_title',
						'desc' => esc_html__( 'Enter home title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'pages',
						'default' => '%title% %separator% %site-title%',
						'class' => 'seo-terms seo-type-page',
					),

					'wcmn_seo_home_desc' => array(
						'name' => esc_html__( 'Home Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_home_desc',
						'desc' => esc_html__( 'Enter home description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'pages',
						'default' => '%excerpt%',
						'class' => 'seo-terms seo-type-page',
					),

					'wcmn_seo_pages_title' => array(
						'name' => esc_html__( 'Pages Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_pages_title',
						'desc' => esc_html__( 'Enter pages title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'pages',
						'default' => '%title% %separator% %site-title%',
						'class' => 'seo-terms seo-type-page',
					),

					'wcmn_seo_pages_desc' => array(
						'name' => esc_html__( 'Pages Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_pages_desc',
						'desc' => esc_html__( 'Enter pages description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'pages',
						'default' => '%excerpt%',
						'class' => 'seo-terms seo-type-page',
					),

					'wcmn_seo_blog_title' => array(
						'name' => esc_html__( 'Blog Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_blog_title',
						'desc' => esc_html__( 'Enter blog title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'blog',
						'default' => '%archive-title% %separator% %site-title%',
						'class' => 'seo-terms seo-type-blog',
					),

					'wcmn_seo_blog_desc' => array(
						'name' => esc_html__( 'Blog Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_blog_desc',
						'desc' => esc_html__( 'Enter blog description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'blog',
						'default' => '%archive-desc%',
						'class' => 'seo-terms seo-type-blog',
					),

					'wcmn_seo_post_title' => array(
						'name' => esc_html__( 'Post Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_post_title',
						'desc' => esc_html__( 'Enter post title format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'blog',
						'default' => '%title% %separator% %site-title%',
						'class' => 'seo-terms seo-type-post',
					),

					'wcmn_seo_post_desc' => array(
						'name' => esc_html__( 'Post Description', 'xforwoocommerce' ),
						'type' => 'textarea',
						'id'   => 'wcmn_seo_post_desc',
						'desc' => esc_html__( 'Enter post description format that will appear in search results', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'blog',
						'default' => '%excerpt%',
						'class' => 'seo-terms seo-type-post',
					),

					'wcmn_seo_add_meta' => array(
						'name' => esc_html__( 'Add Meta', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => 'wcmn_seo_add_meta',
						'desc' => esc_html__( 'Use manager to create custom meta tags', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'meta',
						'title' => esc_html__( 'Meta Name', 'xforwoocommerce' ),
						'options' => 'list',
						'settings' => array(

							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),

							'type' => array(
								'name' => esc_html__( 'Select Type', 'xforwoocommerce' ),
								'type' => 'select',
								'id' => 'type',
								'desc' => esc_html__( 'Select which META tag property you want to set', 'xforwoocommerce' ),
								'options' => array(
									'name' => 'name=""',
									'property' => 'property=""',
									'itemprop' =>'itemprop=""',
								),
								'default' => '',
							),

							'property' => array(
								'name' => esc_html__( 'Property Value', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'property',
								'desc' => esc_html__( 'Enter property value', 'xforwoocommerce' ),
								'default' => '',
							),

							'content' => array(
								'name' => esc_html__( 'Property Content', 'xforwoocommerce' ),
								'type' => 'textarea',
								'id' => 'content',
								'desc' => esc_html__( 'Enter property content', 'xforwoocommerce' ),
								'default' => '',
								'class' => 'seo-terms seo-type-all'
							),

							'condition' => array(
								'name' => esc_html__( 'Conditional Functions', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'condition',
								'desc' => esc_html__( 'Enter conditional functions for this meta e.g.', 'xforwoocommerce' ) . ' is_woocommerce',
								'default' => '',
							),

						),
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_SEO_Settings::init();
