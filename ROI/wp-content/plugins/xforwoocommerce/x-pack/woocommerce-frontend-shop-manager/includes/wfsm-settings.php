<?php

	class XforWC_Live_Editor_Settings {

		public static $plugin;
		public static $settings;

		public static function init() {
			self::$plugin = array(
				'name' => 'Live Product Editor for WooCommerce',
				'xforwc' => 'Live Product Editor',
				'slug' => 'live-editor',
				'label' => 'live_editor',
				'image' => XforWC_Live_Editor()->plugin_url() . '/assets/images/live-product-editor-for-woocommerce-elements.png',
				'path' => 'woocommerce-frontend-shop-manager/woocommerce-frontend-shop-manager',
				'version' => XforWC_Live_Editor::$version,
			);

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'live_editor' ) {
				add_filter( 'svx_plugins_settings', __CLASS__ . '::get_settings', 50 );
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::wfsm_settings_scripts', 9 );
			}

			if ( isset($_GET['page']) && $_GET['page'] == 'xforwoocommerce' ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::wfsm_settings_scripts', 9 );
			}

			self::$settings['restrictions'] = array(
				'create_simple_product' => esc_html__( 'Create Simple Products', 'xforwoocommerce' ),
				'create_grouped_product' => esc_html__( 'Create Grouped Products', 'xforwoocommerce' ),
				'create_external_product' => esc_html__( 'Create External Products', 'xforwoocommerce' ),
				'create_variable_product' => esc_html__( 'Create Variable Products', 'xforwoocommerce' ),
				'create_custom_product' => esc_html__( 'Create Custom Products', 'xforwoocommerce' ),
				'product_status' => esc_html__( 'Product Status', 'xforwoocommerce' ),
				'product_feature' => esc_html__( 'Feature Product', 'xforwoocommerce' ),
				'product_content' => esc_html__( 'Product Content and Description', 'xforwoocommerce' ),
				'product_featured_image' => esc_html__( 'Featured Image', 'xforwoocommerce' ),
				'product_gallery' => esc_html__( 'Product Gallery', 'xforwoocommerce' ),
				'product_downloadable' => esc_html__( 'Downloadable Products', 'xforwoocommerce' ),
				'product_virtual' => esc_html__( 'Virtual Products', 'xforwoocommerce' ),
				'product_name' => esc_html__( 'Product Name', 'xforwoocommerce' ),
				'product_slug' => esc_html__( 'Product Slug', 'xforwoocommerce' ),
				'external_product_url' => esc_html__( 'Product External URL (External/Affilate)', 'xforwoocommerce' ),
				'external_button_text' => esc_html__( 'Product External Button Text', 'xforwoocommerce' ),
				'product_sku' => esc_html__( 'Product SKU', 'xforwoocommerce' ),
				'product_taxes' => esc_html__( 'Product Tax', 'xforwoocommerce' ),
				'product_prices' => esc_html__( 'Product Prices', 'xforwoocommerce' ),
				'product_sold_individually' => esc_html__( 'Sold Individually', 'xforwoocommerce' ),
				'product_stock' => esc_html__( 'Product Stock', 'xforwoocommerce' ),
				'product_schedule_sale' => esc_html__( 'Product Schedule Sale', 'xforwoocommerce' ),
				'product_grouping' => esc_html__( 'Product Grouping', 'xforwoocommerce' ),
				'product_note' => esc_html__( 'Product Purchase Note', 'xforwoocommerce' ),
				'product_shipping' => esc_html__( 'Product Shipping', 'xforwoocommerce' ),
				'product_downloads' => esc_html__( 'Manage Downloads', 'xforwoocommerce' ),
				'product_download_settings' => esc_html__( 'Manage Download Extended Settings', 'xforwoocommerce' ),
				'product_cat' => esc_html__( 'Edit Product Categories', 'xforwoocommerce' ),
				'product_tag' => esc_html__( 'Edit Product Tags', 'xforwoocommerce' ),
				'product_attributes' => esc_html__( 'Edit Product Attributes', 'xforwoocommerce' ),
				'product_new_terms' => esc_html__( 'Add New Taxonomy Terms', 'xforwoocommerce' ),
				'variable_add_variations' => esc_html__( 'Add Variation (Variable)', 'xforwoocommerce' ),
				'variable_edit_variations' => esc_html__( 'Edit Variations (Variable)', 'xforwoocommerce' ),
				'variable_delete' => esc_html__( 'Delete Variation (Variable)', 'xforwoocommerce' ),
				'variable_product_attributes' => esc_html__( 'Edit Product Attributes (Variable)', 'xforwoocommerce' ),
				'product_clone' => esc_html__( 'Duplicate Products', 'xforwoocommerce' ),
				'product_delete' => esc_html__( 'Delete Products', 'xforwoocommerce' ),
				'backend_buttons' => esc_html__( 'Backend Buttons', 'xforwoocommerce' ),
			);

			self::$settings['vendor_groups'] = SevenVXGet()->get_option( 'wc_settings_wfsm_vendor_groups', 'live_editor', array() );
			self::$settings['custom_settings'] = SevenVXGet()->get_option( 'wc_settings_wfsm_custom_settings', 'live_editor', array() );

			if ( is_array( self::$settings['custom_settings'] ) ) {
				foreach( self::$settings['custom_settings'] as $set ) {
					$set['name'] = isset( $set['name'] ) ? $set['name'] : esc_html__( 'Opton Name', 'xforwoocommerce' );
		
					$slug = sanitize_title( $set['name'] );
					self::$settings['restrictions']['wfsm_custom_' . $slug] = $set['name'];
				}
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_Live_Editor_Settings', 'xforwc' ), 9999999151 );
				add_filter( 'xforwc_svx_get_live_editor', array( 'XforWC_Live_Editor_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_Live_Editor_Settings', 'add_plugin' ), 0 );
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

		public static function wfsm_settings_scripts( $settings_tabs ) {
			wp_register_script( 'wfsm-admin', XforWC_Live_Editor()->plugin_url() . '/assets/js/admin.js', array( 'jquery' ), XforWC_Live_Editor()->version(), true );
			wp_enqueue_script( array( 'wfsm-admin' ) );
		}

		public static function get_settings( $plugins ) {

			$wfsm_styles = apply_filters( 'wfsm_editor_styles', array(
				'wfsm_style_default' => esc_html__( 'Default', 'xforwoocommerce' ),
				'wfsm_style_flat' => esc_html__( 'Flat', 'xforwoocommerce' ),
				'wfsm_style_dark' => esc_html__( 'Dark', 'xforwoocommerce' )
			) );

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/live-product-editing/' ),
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
						'desc' => esc_html__( 'General Options', 'xforwoocommerce' ),
					),
					'products' => array(
						'name' => esc_html__( 'Products', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Products Options', 'xforwoocommerce' ),
					),
					'vendors' => array(
						'name' => esc_html__( 'Vendors', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Vendors Options', 'xforwoocommerce' ),
					),
					'custom' => array(
						'name' => esc_html__( 'Custom Options', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Custom Options Settings', 'xforwoocommerce' ),
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
						<img src="' . XforWC_Live_Editor()->plugin_url() . '/assets/images/live-manager-for-woocommerce-shop.png" class="svx-dashboard-image" />
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

					'wc_settings_wfsm_logo' => array(
						'name' => esc_html__( 'Custom Logo', 'xforwoocommerce' ),
						'type' => 'file',
						'desc' => esc_html__( 'Use custom logo and enter logo URL. Use square images (200x200px)!', 'xforwoocommerce' ),
						'id'   => 'wc_settings_wfsm_logo',
						'default' => '',
						'autoload' => false,
						'section' => 'general'
					),
					'wc_settings_wfsm_mode' => array(
						'name' => esc_html__( 'Show Logo/User', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select what to show in the Live Product Editor header, logo or logged in user.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_mode',
						'options' => array(
							'wfsm_mode_logo' => esc_html__( 'Show Logo', 'xforwoocommerce' ),
							'wfsm_mode_user' => esc_html__( 'Show Logged User', 'xforwoocommerce' )
						),
						'default' => 'wfsm_logo',
						'autoload' => false,
						'section' => 'general'
					),
					'wc_settings_wfsm_style' => array(
						'name' => esc_html__( 'Live Editor Style', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select Live Product Editor style/skin.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_style',
						'options' => $wfsm_styles,
						'default' => 'wfsm_style_default',
						'autoload' => false,
						'section' => 'general'
					),

					'wc_settings_wfsm_archive_action' => array(
						'name' => esc_html__( 'Shop Init Action', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Use custom initialization action for Shop/Product Archives. Use actions initiated in your content-product.php template. Please enter action name in following format action_name:priority', 'xforwoocommerce' ) . ' ( default: woocommerce_before_shop_loop_item:0 )',
						'id' => 'wc_settings_wfsm_archive_action',
						'autoload' => true,
						'section' => 'installation'
					),
					'wc_settings_wfsm_single_action' => array(
						'name' => esc_html__( 'Single Product Init Action', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Use custom initialization action on Single Product Pages. Use actions initiated in your content-single-product.php template. Please enter action name in following format action_name:priority', 'xforwoocommerce' ) . ' ( default: woocommerce_before_single_product_summary:5 )',
						'id' => 'wc_settings_wfsm_single_action',
						'autoload' => true,
						'section' => 'installation'
					),
					'wc_settings_wfsm_force_scripts' => array(
						'name' => esc_html__( 'Plugin Scripts', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to load plugin scripts in all pages. This option fixes issues in Quick Views, AJAX loads and similar.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_wfsm_force_scripts',
						'default' => 'no',
						'autoload' => true,
						'section' => 'installation'
					),

					'wc_settings_wfsm_show_hidden_products' => array(
						'name' => esc_html__( 'Show Hidden Products', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable pending and draft products in Shop/Product Archives.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_show_hidden_products',
						'default' => 'yes',
						'autoload' => true,
						'section' => 'products'
					),
					'wc_settings_wfsm_new_button' => array(
						'name' => esc_html__( 'New Product Button', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to hide the New Product Button (Create Product). Use [wfsm_new_product] shortcode if you need a custom New Product Button.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_new_button',
						'default' => 'no',
						'autoload' => false,
						'section' => 'products'
					),
					'wc_settings_wfsm_create_status' => array(
						'name' => esc_html__( 'New Product Status', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select the default status for newly created products.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_create_status',
						'options' => array(
							'publish' => esc_html__( 'Published', 'xforwoocommerce' ),
							'pending' => esc_html__( 'Pending', 'xforwoocommerce' ),
							'draft' => esc_html__( 'Draft', 'xforwoocommerce' )
						),
						'default' => 'pending',
						'autoload' => false,
						'section' => 'products'
					),
					'wc_settings_wfsm_create_virtual' => array(
						'name' => esc_html__( 'New Product is Virtual', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to set virtual by default (not shipped) for new products.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_create_virtual',
						'default' => 'no',
						'autoload' => false,
						'section' => 'products'
					),
					'wc_settings_wfsm_create_downloadable' => array(
						'name' => esc_html__( 'New Product is Downloadable', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to set downloadable by default for new products.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_create_downloadable',
						'default' => 'no',
						'autoload' => false,
						'section' => 'products'
					),

					'wc_settings_wfsm_custom_settings' => array(
						'name' => esc_html__( 'Custom Product Options', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => 'wc_settings_wfsm_custom_settings',
						'desc' => esc_html__( 'Click Add Custom Settings Group button to add special product options in the Live Product Editor.', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'custom',
						'title' => esc_html__( 'Group Name', 'xforwoocommerce' ),
						'translate' => true,
						'options' => 'list',
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Group Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter group name', 'xforwoocommerce' ),
								'default' => '',
							),
							'options' => array(
								'name' => esc_html__( 'Options', 'xforwoocommerce' ),
								'type' => 'list-select',
								'id' => 'options',
								'desc' => esc_html__( 'Add options to options group', 'xforwoocommerce' ),
								'default' => array(),
								'title' => esc_html__( 'Option Name', 'xforwoocommerce' ),
								'options' => 'list',
								'selects' => array(
									'input' => esc_html__( 'Input', 'xforwoocommerce' ),
									'checkbox' => esc_html__( 'Checkbox', 'xforwoocommerce' ),
									'select' => esc_html__( 'Select Box', 'xforwoocommerce' ),
									'textarea' => esc_html__( 'Textarea', 'xforwoocommerce' ),
								),
								'settings' => array(
									'input' => array(
										'name' => array(
											'name' => esc_html__( 'Name', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'name',
											'desc' => esc_html__( 'Enter option name', 'xforwoocommerce' ),
											'default' =>'',
										),
										'key' => array(
											'name' => esc_html__( 'Key', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'key',
											'desc' => esc_html__( 'Enter database key', 'xforwoocommerce' ),
											'default' => '',
										),
										'default' => array(
											'name' => esc_html__( 'Default Value', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'default',
											'desc' => esc_html__( 'Enter default value', 'xforwoocommerce' ),
											'default' => '',
										),
									),
									'textarea' => array(
										'name' => array(
											'name' => esc_html__( 'Name', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'name',
											'desc' => esc_html__( 'Enter option name', 'xforwoocommerce' ),
											'default' => '',
										),
										'key' => array(
											'name' => esc_html__( 'Key', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'key',
											'desc' => esc_html__( 'Enter database key', 'xforwoocommerce' ),
											'default' => '',
										),
										'default' => array(
											'name' => esc_html__( 'Default Value', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'default',
											'desc' => esc_html__( 'Enter default value', 'xforwoocommerce' ),
											'default' => '',
										),
									),
									'checkbox' => array(
										'name' => array(
											'name' => esc_html__( 'Name', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'name',
											'desc' => esc_html__( 'Enter option name', 'xforwoocommerce' ),
											'default' => '',
										),
										'key' => array(
											'name' => esc_html__( 'Key', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'key',
											'desc' => esc_html__( 'Enter database key', 'xforwoocommerce' ),
											'default' => '',
										),
										'options' => array(
											'name' => esc_html__( 'Options', 'xforwoocommerce' ),
											'type' => 'textarea',
											'id' => 'options',
											'desc' => esc_html__( 'Enter options (JSON string)', 'xforwoocommerce' ),
											'default' => '{
		"yes" : "This option is now checked",
		"no" : "You have unchecked this option"
	}',
										),
										'default' => array(
											'name' => esc_html__( 'Default Value', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'default',
											'desc' => esc_html__( 'Enter default value', 'xforwoocommerce' ),
											'default' => '',
										),
									),
									'select' => array(
										'name' => array(
											'name' => esc_html__( 'Name', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'name',
											'desc' => esc_html__( 'Enter option name', 'xforwoocommerce' ),
											'default' => '',
										),
										'key' => array(
											'name' => esc_html__( 'Key', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'key',
											'desc' => esc_html__( 'Enter database key', 'xforwoocommerce' ),
											'default' => '',
										),
										'options' => array(
											'name' => esc_html__( 'Options', 'xforwoocommerce' ),
											'type' => 'textarea',
											'id' => 'options',
											'desc' => esc_html__( 'Enter options (JSON string)', 'xforwoocommerce' ),
											'default' => '{
		"apple" : "Citric Apple",
		"pear" : "Sweet Pear",
		"bannana" : "Yellow Bananna"
	}',
										),
										'default' => array(
											'name' => esc_html__( 'Default Value', 'xforwoocommerce' ),
											'type' => 'text',
											'id' => 'default',
											'desc' => esc_html__( 'Enter default value', 'xforwoocommerce' ),
											'default' => '',
										),
									)
								)
							),
						),
					),

					'wc_settings_wfsm_vendor_max_products' => array(
						'name' => esc_html__( 'Products per Vendor', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Maximum number of products vendor can create.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_vendor_max_products',
						'default' => '',
						'autoload' => false,
						'section' => 'vendors'
					),
					'wc_settings_wfsm_default_permissions' => array(
						'name' => esc_html__( 'Default Vendor Restrictions', 'xforwoocommerce' ),
						'type' => 'multiselect',
						'desc' => esc_html__( 'Selected product options vendors will not be able to edit.', 'xforwoocommerce' ),
						'id' => 'wc_settings_wfsm_default_permissions',
						'options' => self::$settings['restrictions'],
						'default' => array(),
						'autoload' => false,
						'section' => 'vendors',
						'class' => 'svx-selectize'
					),
					'wc_settings_wfsm_vendor_groups' => array(
						'name' => esc_html__( 'Vendor Groups Manager', 'xforwoocommerce' ),
						'type' => 'list',
						'id' => 'wc_settings_wfsm_vendor_groups',
						'desc' => esc_html__( 'Click Add Vendor Premission Group button to customize user editing permissions for specified users.', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'vendors',
						'title' => esc_html__( 'Group Name', 'xforwoocommerce' ),
						'options' => 'list',
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Group Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter group name', 'xforwoocommerce' ),
								'default' => ''
							),
							'users' => array(
								'name' => esc_html__( 'Select Users', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'id' => 'users',
								'desc' => esc_html__( 'Select users', 'xforwoocommerce' ),
								'default' => '',
								'options' => 'ajax:users',
								'class' => 'svx-selectize'
							),
							'permissions' => array(
								'name' => esc_html__( 'Select Options', 'xforwoocommerce' ),
								'type' => 'multiselect',
								'id' => 'permissions',
								'desc' => esc_html__( 'Selected product options vendors from this group will not be able to edit', 'xforwoocommerce' ),
								'options' => self::$settings['restrictions'],
								'default' => '',
								'class' => 'svx-selectize'
							)
						)
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_Live_Editor_Settings::init();

	add_action( 'svx_ajax_saved_settings_live_editor', 'svx_add_live_editor_user_groups' );

	function svx_add_live_editor_user_groups( $opt ) {
		if ( $_POST['svx']['plugin']=='live_editor') {
			$opt = $opt['std']['wc_settings_wfsm_vendor_groups'];
			if ( !empty( $opt ) ) {
				foreach( $opt as $k => $v ) {
					foreach( $v['users'] as $user ) {
						update_user_meta( $user, 'wfsm_group', $k );
					}
				}
			}
		}
	}
