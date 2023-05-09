<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_PDF_Print_Share_Settings {

		public static $plugin;

		public static function init() {
			self::$plugin = array(
				'name' => 'Share, Print and PDF for WooCommerce',
				'xforwc' => 'Print, PDF and Share',
				'slug' => 'share-print-pdf',
				'label' => 'share_print_pdf',
				'image' => Wcmnspp()->plugin_url() . '/includes/images/share-print-pdf-for-woocommerce-elements.png',
				'path' => 'share-print-pdf-woocommerce/share-woo',
				'version' => XforWC_PDF_Print_Share::$version,
			);

			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'share_print_pdf' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_PDF_Print_Share_Settings', 'get_settings' ), 50 );
			}			

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_PDF_Print_Share_Settings', 'xforwc' ), 9999999141 );
				add_filter( 'xforwc_svx_get_share_print_pdf', array( 'XforWC_PDF_Print_Share_Settings', '_get_settings_xforwc' ) );
			}
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
				'link' => esc_url( 'https://xforwoocommerce.com/store/pdf-print-and-share/' ),
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
					'print_pdf_setup' => array(
						'name' => esc_html__( 'Print/PDF Setup', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Print/PDF Setup Options', 'xforwoocommerce' ),
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
						<img src="' . Wcmnspp()->plugin_url() . '/includes/images/share-print-pdf-for-woocommerce-shop.png" class="svx-dashboard-image" />
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

					'wc_settings_spp_enable' => array(
						'name' => esc_html__( 'Installation Method', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select method for installing the Share, Print and PDF template in your Shop.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_enable',
						'autoload' => true,
						'options' => array(
							'override' => esc_html__( 'Override WooCommerce Template', 'xforwoocommerce' ),
							'action' => esc_html__( 'Init Action', 'xforwoocommerce' )
						),
						'default' => 'yes',
						'section' => 'installation'
					),

					'wc_settings_spp_action' => array(
						'name' => esc_html__( 'Init Action', 'xforwoocommerce' ),
						'type' => 'text',
						'desc' => esc_html__( 'Change default plugin initialization action on single product pages. Use actions done in your content-single-product.php file. Please enter action in the following format action_name:priority.', 'xforwoocommerce' ) . ' ( default: woocommerce_single_product_summary:60 )' . ' (default: :60)',
						'id'   => 'wc_settings_spp_action',
						'autoload' => true,
						'default' => 'woocommerce_single_product_summary:60',
						'section' => 'installation'
					),

					'wc_settings_spp_force_scripts' => array(
						'name' => esc_html__( 'Plugin Scripts', 'xforwoocommerce' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable plugin scripts in all pages. This option fixes issues in Quick Views.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_force_scripts',
						'autoload' => true,
						'default' => 'no',
						'section' => 'installation'
					),

					'wc_settings_spp_logo' => array(
						'name' => esc_html__( 'Site Logo', 'xforwoocommerce' ),
						'type' => 'file',
						'desc' => esc_html__( 'Use site logo on print and PDF templates. Enter URL.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_logo',
						'autoload' => false,
						'default' => '',
						'section' => 'general'
					),

					'wc_settings_spp_title' => array(
						'name' => esc_html__( 'Replace Title', 'xforwoocommerce' ),
						'type' => 'textarea',
						'desc' => esc_html__( 'Replace title with any HTML.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_title',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'general'
					),

					'wc_settings_spp_style' => array(
						'name' => esc_html__( 'Icons Style', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Choose share icons style.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_style',
						'autoload' => false,
						'options' => array(
							'line-icons' => esc_html__( 'Gray', 'xforwoocommerce' ),
							'background-colors' => esc_html__( 'Backgrounds', 'xforwoocommerce' ),
							'border-colors' => esc_html__( 'Borders', 'xforwoocommerce' ),
							'flat' => esc_html__( 'Flat Colors', 'xforwoocommerce' )
							
						),
						'default' => 'line-icons',
						'section' => 'general'
					),

					'wc_settings_spp_shares' => array(
						'name' => esc_html__( 'Hide Icons', 'xforwoocommerce' ),
						'type' => 'multiselect',
						'desc' => esc_html__( 'Select icons to hide on your webiste.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_shares',
						'autoload' => false,
						'options' => array(
							'facebook' => esc_html__( 'Facebook', 'xforwoocommerce' ),
							'twitter' => esc_html__( 'Twitter', 'xforwoocommerce' ),
							'pin' => esc_html__( 'Pinterest', 'xforwoocommerce' ),
							'linked' => esc_html__( 'LinkedIn', 'xforwoocommerce' ),
							'print' => esc_html__( 'Print', 'xforwoocommerce' ),
							'pdf' => esc_html__( 'PDF', 'xforwoocommerce' ),
							'email' => esc_html__( 'Email', 'xforwoocommerce' ),
						),
						'default' => array(),
						'section' => 'general',
						'class' => 'svx-selectize'
					),

					'wc_settings_spp_pagesize' => array(
						'name' => esc_html__( 'Page Size', 'xforwoocommerce' ),
						'type' => 'select',
						'desc' => esc_html__( 'Select PDF page format.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_pagesize',
						'autoload' => false,
						'options' => array(
							'letter' => esc_html__( 'Letter', 'xforwoocommerce' ),
							'legal' => esc_html__( 'Legal', 'xforwoocommerce' ),
							'a4' => 'A4',
							'a3' => 'A3'
						),
						'default' => 'letter',
						'section' => 'print_pdf_setup'
					),
					'wc_settings_spp_header_after' => array(
						'name' => esc_html__( 'Header After', 'xforwoocommerce' ),
						'type' => 'textarea',
						'desc' => esc_html__( 'Set custom content after header in print and PDF mode.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_header_after',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'print_pdf_setup'
					),
					'wc_settings_spp_product_before' => array(
						'name' => esc_html__( 'Product Before', 'xforwoocommerce' ),
						'type' => 'textarea',
						'desc' => esc_html__( 'Set custom content before product content in print and PDF mode.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_product_before',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'print_pdf_setup'
					),
					'wc_settings_spp_product_after' => array(
						'name' => esc_html__( 'Product After', 'xforwoocommerce' ),
						'type' => 'textarea',
						'desc' => esc_html__( 'Set custom content after product content in print and PDF mode.', 'xforwoocommerce' ),
						'id'   => 'wc_settings_spp_product_after',
						'autoload' => false,
						'translate' => true,
						'default' => '',
						'section' => 'print_pdf_setup'
					),

				)
			);

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

	}

	XforWC_PDF_Print_Share_Settings::init();
