<?php

	class XforWC_Warranties_Returns_Settings {

		public static $plugin;
		
		public static function init() {

			self::$plugin = array(
				'name' => 'Warranties and Returns for WooCommerce',
				'xforwc' => 'Warranties and Returns',
				'slug' => 'warranties-and-returns',
				'label' => 'warranties_and_returns',
				'image' => XforWC_Warranties_Returns::$url_path . 'assets/images/warranties-and-returns-for-woocommerce-elements.png',
				'path' => 'woocommerce-warranties-and-returns/woocommerce-warranties-and-returns',
				'version' => XforWC_Warranties_Returns::$version,
			);

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'warranties_and_returns' ) {
				add_filter( 'svx_plugins_settings', __CLASS__ . '::get_settings', 50 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_Warranties_Returns_Settings', 'xforwc' ), 9999999161 );
				add_filter( 'xforwc_svx_get_warranties_and_returns', array( 'XforWC_Warranties_Returns_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_Warranties_Returns_Settings', 'add_plugin' ), 0 );
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

			$presets = get_terms( 'wcwar_warranty_pre', array('hide_empty' => false) );

			$ready_presets = array(
				'' => esc_html__( 'None', 'xforwoocommerce' )
			);

			foreach ( $presets as $preset ) {
				$ready_presets[$preset->term_id] = $preset->name;
			}

			$pages = get_pages();

			$ready_pages = array(
				'' => esc_html__( 'None', 'xforwoocommerce' )
			);

			foreach ( $pages as $page ) {
				$ready_pages[$page->ID] = $page->post_title;
			}

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/warranties-and-returns/' ),
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
					'warranties' => array(
						'name' => esc_html__( 'Warranties', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Warranties Options', 'xforwoocommerce' ),
					),
					'manager' => array(
						'name' => esc_html__( 'Warranty Manager', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Warranty Manager Options', 'xforwoocommerce' ),
					),
					'returns' => array(
						'name' => esc_html__( 'Returns', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Returns Options', 'xforwoocommerce' ),
					),
					'email' => array(
						'name' => esc_html__( 'E-Mail', 'xforwoocommerce' ),
						'desc' => esc_html__( 'E-Mail Options', 'xforwoocommerce' ),
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
						<img src="' . XforWC_Warranties_Returns::$url_path . 'assets/images/warranties-and-returns-for-woocommerce-shop.png" class="svx-dashboard-image" />
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

					'war_settings_page' => array(
						'name'    => esc_html__( 'Request Page', 'xforwoocommerce' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Please select the page for requesting warranties. Check Documentation FAQ if the page was not created automatically.', 'xforwoocommerce' ),
						'id'      => 'war_settings_page',
						'options' => $ready_pages,
						'default' => '',
						'autoload' => true,
						'section' => 'installation'
					),

					'wcwar_single_action' => array(
						'name'    => esc_html__( 'Product Page Hook', 'xforwoocommerce' ),
						'type'    => 'text',
						'desc'    => esc_html__( 'Change default plugin initialization action on single product pages. Use actions done in your content-single-product.php file. Please enter action in the following format action_name:priority.', 'xforwoocommerce' ) . ' ( default: woocommerce_before_add_to_cart_form )',
						'id'      => 'wcwar_single_action',
						'default' => '',
						'autoload' => true,
						'section' => 'installation'
					),

					'wcwar_single_titles' => array(
						'name'    => esc_html__( 'Heading Size', 'xforwoocommerce' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select heading size of warranty titles on single product pages.', 'xforwoocommerce' ),
						'id'      => 'wcwar_single_titles',
						'default' => 'h4',
						'options' => array(
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6'
						),
						'autoload' => false,
						'section' => 'installation'
					),

					'wcwar_single_mode' => array(
						'name'    => esc_html__( 'Request Page Display', 'xforwoocommerce' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select display for the Warranty/Return customer post.', 'xforwoocommerce' ),
						'id'      => 'wcwar_single_mode',
						'default' => 'new',
						'options' => array(
							'old' => 'Old - WooThemes, Basic Themes',
							'new' => 'New - Most Supported in Premium Themes'
						),
						'autoload' => true,
						'section' => 'installation'
					),

					'wcwar_force_scripts' => array(
						'name' => esc_html__( 'Plugin Scripts', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable plugin scripts in all pages. This option fixes issues in Quick Views.', 'xforwoocommerce' ),
						'id'   => 'wcwar_force_scripts',
						'default' => 'no',
						'autoload' => true,
						'section' => 'installation'
					),

					'wcwar_form' => array(
						'name'    => esc_html__( 'Warranty Request Form', 'xforwoocommerce' ),
						'type'    => 'list-select',
						'desc'    => esc_html__( 'Use the manager to create a warranty form.', 'xforwoocommerce' ),
						'id'      => 'wcwar_form',
						'title'   => esc_html__( 'Field', 'xforwoocommerce' ),
						'options' => 'list',
						'selects' => array(
							'text' => esc_html__( 'Input', 'xforwoocommerce' ),
							'paragraph' => esc_html__( 'Textarea', 'xforwoocommerce' ),
							'checkboxes' => esc_html__( 'Checkboxes', 'xforwoocommerce' ),
							'radio' => esc_html__( 'Radio', 'xforwoocommerce' ),
							'dropdown' => esc_html__( 'Select', 'xforwoocommerce' ),
							'address' => esc_html__( 'Address', 'xforwoocommerce' ),
							'file' => esc_html__( 'File upload', 'xforwoocommerce' ),
						),
						'settings' => self::_build_form_fields(),
						'default' => '',
						'autoload' => false,
						'section' => 'warranties'
					),

					'wcwar_overrides' => array(
						'name' => esc_html__( 'Warranty Overrides', 'xforwoocommerce' ),
						'type' => 'hidden',
						'id'   => 'wcwar_overrides',
						'desc' => esc_html__( 'Set warranty overrides', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'hidden',
					),

					'_wcwar_tags' => array(
						'name' => esc_html__( 'Warranty by Tag', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => '_wcwar_tags',
						'desc' => esc_html__( 'Add default warranty by product tag', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'manager',
						'title' => esc_html__( 'Name', 'xforwoocommerce' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Tag', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Select tag', 'xforwoocommerce' ),
								'options' => 'ajax:taxonomy:product_tag:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Select warranty preset', 'xforwoocommerce' ),
								'options' => 'ajax:taxonomy:wcwar_warranty_pre:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
						),
					),

					'_wcwar_categories' => array(
						'name' => esc_html__( 'Warranty by Category', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => '_wcwar_categories',
						'desc' => esc_html__( 'Add default warranty by product category', 'xforwoocommerce' ),
						'autoload' => false,
						'section' => 'manager',
						'title' => esc_html__( 'Name', 'xforwoocommerce' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Category', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Select category', 'xforwoocommerce' ),
								'autoload' => false,
								'section' => 'manager',
								'options' => 'ajax:taxonomy:product_cat:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Select warranty preset', 'xforwoocommerce' ),
								'autoload' => false,
								'section' => 'manager',
								'options' => 'ajax:taxonomy:wcwar_warranty_pre:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
						),
					),

					'wcwar_default_warranty' => array(
						'name'    => esc_html__( 'Default Warranty', 'xforwoocommerce' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Products without warranties can have a default warranty. Please select warranty preset.', 'xforwoocommerce' ),
						'id'      => 'wcwar_default_warranty',
						'default' => '',
						'options' => $ready_presets,
						'autoload' => false,
						'section' => 'manager'
					),

					'wcwar_default_post' => array(
						'name'    => esc_html__( 'New Warranty Status', 'xforwoocommerce' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select status for the newly submitted warranty request posts.', 'xforwoocommerce' ),
						'id'      => 'wcwar_default_post',
						'default' => 'pending',
						'options' => array(
							'draft' => esc_html__( 'Draft', 'xforwoocommerce' ),
							'publish' => esc_html__( 'Published', 'xforwoocommerce' ),
							'pending' => esc_html__( 'Pending', 'xforwoocommerce' )
						),
						'autoload' => false,
						'section' => 'warranties'
					),

					'wcwar_enable_multi_requests' => array(
						'name'    => esc_html__( 'Multi Requests', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'Check this option to enable multi requests in the defined warranty period. New requests will available upon completing the previous requests.', 'xforwoocommerce' ),
						'id'      => 'wcwar_enable_multi_requests',
						'default' => 'no',
						'autoload' => false,
						'section' => 'warranties'
					),

					'wcwar_enable_guest_requests' => array(
						'name'    => esc_html__( 'Guest Requests', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'Guests can access warranties using their Order ID and an E-Mail address to confirm their identity. Check this option if you want to allow guests to request warranties and returns.', 'xforwoocommerce' ),
						'id'      => 'wcwar_enable_guest_requests',
						'default' => 'no',
						'autoload' => false,
						'section' => 'warranties'
					),

					'wcwar_enable_admin_requests' => array(
						'name'    => esc_html__( 'Admin Warranties', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'If checked admins will have the ability to create warranty requests for items without warranties.', 'xforwoocommerce' ),
						'id'      => 'wcwar_enable_admin_requests',
						'default' => 'yes',
						'autoload' => false,
						'section' => 'warranties'
					),

					'wcwar_email_disable' => array(
						'name'    => esc_html__( 'Show/Hide Warranty Info', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'Check this option to hide warranty information in WooCommerce Order E-Mails notifications.', 'xforwoocommerce' ),
						'id'      => 'wcwar_email_disable',
						'default' => 'no',
						'autoload' => true,
						'section' => 'email'
					),

					'wcwar_email_desc' => array(
						'name' => esc_html__( 'Quick Email', 'xforwoocommerce' ),
						'type' => 'html',
						'desc' => '
						<div class="svx-option-header"><h3>' . esc_html__( 'Quick Email', 'xforwoocommerce' ) . '</h3></div><div class="svx-option-wrapper"><div class="svx-notice svx-info">' . esc_html__( 'Following options are related to the Warranties Quick Email manager.', 'xforwoocommerce' ) . '</div></div>',
						'section' => 'email',
						'id'   => 'wcwar_email_desc',
					),

					'wcwar_email_name' => array(
						'name'    => esc_html__( 'From Name', 'xforwoocommerce' ),
						'type'    => 'text',
						'desc'    => esc_html__( 'Enter email From Name: which should appear in quick emails.', 'xforwoocommerce' ),
						'id'      => 'wcwar_email_name',
						'default' => get_bloginfo( 'name' ),
						'autoload' => false,
						'section' => 'email'
					),

					'wcwar_email_address' => array(
						'name'    => esc_html__( 'Reply To', 'xforwoocommerce' ),
						'type'    => 'text',
						'desc'    => esc_html__( 'Enter email address that will appear as a Reply To: address in quick emails.', 'xforwoocommerce' ),
						'id'      => 'wcwar_email_address',
						'default' => get_bloginfo( 'admin_email' ),
						'autoload' => false,
						'section' => 'email'
					),

					'wcwar_email_bcc' => array(
						'name'    => esc_html__( 'BCC Copies', 'xforwoocommerce' ),
						'type'    => 'text',
						'desc'    => esc_html__( 'Enter E-Mail addresses separated by comma to send BCC copies of quick emails.', 'xforwoocommerce' ),
						'id'      => 'wcwar_email_bcc',
						'default' => '',
						'autoload' => false,
						'section' => 'email'
					),

					'wcwar_enable_returns' => array(
						'name'    => esc_html__( 'Enable Returns', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'This option will enable the in store returns. Set your return period in which the items can be sent back by customers with a refund.', 'xforwoocommerce' ),
						'id'      => 'wcwar_enable_returns',
						'default' => 'no',
						'autoload' => false,
						'section' => 'returns',
						'class' => 'svx-refresh-active-tab',
					),

					'wcwar_return_form' => array(
						'name'    => esc_html__( 'Return Request Form', 'xforwoocommerce' ),
						'type'    => 'list-select',
						'desc'    => esc_html__( 'Use the manager to create a return form.', 'xforwoocommerce' ),
						'id'      => 'wcwar_return_form',
						'title'   => esc_html__( 'Field', 'xforwoocommerce' ),
						'options' => 'list',
						'selects' => array(
							'text' => esc_html__( 'Input', 'xforwoocommerce' ),
							'paragraph' => esc_html__( 'Textarea', 'xforwoocommerce' ),
							'checkboxes' => esc_html__( 'Checkboxes', 'xforwoocommerce' ),
							'radio' => esc_html__( 'Radio', 'xforwoocommerce' ),
							'dropdown' => esc_html__( 'Select', 'xforwoocommerce' ),
							'address' => esc_html__( 'Address', 'xforwoocommerce' ),
							'file' => esc_html__( 'File upload', 'xforwoocommerce' ),
						),
						'settings' => self::_build_form_fields(),
						'default' => '',
						'autoload' => false,
						'section' => 'returns',
						'condition' => 'wcwar_enable_returns:yes',
					),

					'wcwar_returns_period' => array(
						'name' => esc_html__( 'Return Limit', 'xforwoocommerce' ),
						'type' => 'number',
						'desc' => esc_html__( 'Number of days for returning items upon order completition. If 0 is set, items will have a lifetime return period.', 'xforwoocommerce' ),
						'id'   => 'wcwar_returns_period',
						'default' => 0,
						'custom_attributes' => array(
							'min' 	=> 0,
							'max' 	=> 1826,
							'step' 	=> 1
						),
						'autoload' => false,
						'section' => 'returns',
						'condition' => 'wcwar_enable_returns:yes',
					),

					'wcwar_returns_no_warranty' => array(
						'name'    => esc_html__( 'Return Without a Warranty', 'xforwoocommerce' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'If checked, returns will be available for items that have no warranty.', 'xforwoocommerce' ),
						'id'      => 'wcwar_returns_no_warranty',
						'default' => 'no',
						'autoload' => false,
						'section' => 'returns',
						'condition' => 'wcwar_enable_returns:yes',
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

		public static function _build_form_fields() {
			return array(
				'text' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
				),
				'paragraph' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
				),
				'checkboxes' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
					'options' => self::___get_options(),
				),
				'radio' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
					'options' => self::___get_options(),
				),
				'dropdown' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
					'options' => self::___get_options(),
				),
				'address' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
				),
				'file' => array(
					'name' => self::___get_title_option(),
					'desc' => self::___get_desc_option(),
					'required' => self::___get_required_option(),
				),
			);
		}

		public static function ___get_title_option() {
			return array(
				'name' => esc_html__( 'Title', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Use alternative title', 'xforwoocommerce' ),
				'id'   => 'name',
				'default' => '',
			);
		}

		public static function ___get_desc_option() {
			return array(
				'name' => esc_html__( 'Description', 'xforwoocommerce' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Enter filter description', 'xforwoocommerce' ),
				'id'   => 'desc',
				'default' => '',
			);
		}

		public static function ___get_required_option() {
			return array(
				'name' => esc_html__( 'Required', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Option is required', 'xforwoocommerce' ),
				'id'   => 'required',
				'default' => 'no',
			);
		}

		public static function ___get_options() {
			return array(
				'name' => esc_html__( 'Options', 'xforwoocommerce' ),
				'type' => 'list',
				'desc' => esc_html__( 'Add options', 'xforwoocommerce' ),
				'id'   => 'options',
				'title' => esc_html__( 'Option', 'xforwoocommerce' ),
				'default' => '',
				'options' => 'list',
				'settings' => array(
					'name' => array(
						'name' => esc_html__( 'Option', 'xforwoocommerce' ),
						'type' => 'text',
						'id' => 'name',
						'desc' => esc_html__( 'Enter option title', 'xforwoocommerce' ),
						'default' => '',
					),
				),
			);
		}

	}

	XforWC_Warranties_Returns_Settings::init();

