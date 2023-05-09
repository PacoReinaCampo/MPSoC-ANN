<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class XforWC_Product_Filters_Settings {

		public static $settings = null;
		public static $presets = null;

		public static $plugin = array();

		public static function init() {

			self::$plugin = array(
				'name' => esc_html__( 'Product Filter for WooCommerce', 'xforwoocommerce' ),
				'xforwc' => esc_html__( 'Product Filters', 'xforwoocommerce' ),
				'slug' => 'product-filters',
				'label' => 'product_filter',
				'image' => Prdctfltr()->plugin_url() . '/includes/images/product-filter-for-woocommerce-elements.png',
				'path' => 'prdctfltr/prdctfltr',
				'version' => XforWC_Product_Filters::$version,
			);

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'product_filter' ) {
				add_filter( 'svx_plugins_settings', array( 'XforWC_Product_Filters_Settings', 'get_settings' ), 50 );
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::scripts', 99999 );
			}

			if ( isset($_GET['page']) && ($_GET['page'] == 'xforwoocommerce' )) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::scripts', 99999 );
			}

			if ( function_exists( 'XforWC' ) ) {
				add_filter( 'xforwc_settings', array( 'XforWC_Product_Filters_Settings', 'xforwc' ), 9999999101 );
				add_filter( 'xforwc_svx_get_product_filters', array( 'XforWC_Product_Filters_Settings', '_get_settings_xforwc' ) );
			}

			add_filter( 'svx_plugins', array( 'XforWC_Product_Filters_Settings', 'add_plugin' ), 0 );

			add_action( 'wp_ajax_prdctfltr_analytics_reset', __CLASS__ . '::analytics_reset' );
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
			wp_register_script( 'google-api', (is_ssl()?'https://':'http://') . 'www.google.com/jsapi', array(), false, true );
			wp_enqueue_script( 'google-api' );

			wp_register_script( 'product-filter', Prdctfltr()->plugin_url() . '/includes/js/svx-admin.js', array( 'jquery' ), Prdctfltr()->version(), true );
			wp_enqueue_script( 'product-filter' );
		}

		public static function ___get_taxonomy_option() {
			return array(
				'name' => esc_html__( 'Select Taxonomy', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select taxonomy for this filter', 'xforwoocommerce' ),
				'id'   => 'taxonomy',
				'options' => 'ajax:product_taxonomies:has_none',
				'default' => '',
				'class' => 'svx-update-list-title svx-selectize',
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
		
		public static function ___get_include_option() {
			return array(
				'name' => esc_html__( 'Include/Exclude', 'xforwoocommerce' ),
				'type' => 'include',
				'desc' => esc_html__( 'Select terms to include/exclude', 'xforwoocommerce' ),
				'id'   => 'include',
				'default' => false,
			);
		}

		public static function ___get_orderby_option() {
			return array(
				'name' => esc_html__( 'Order By', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select term order', 'xforwoocommerce' ),
				'id'   => 'orderby',
				'default' => '',
				'options' => array(
					'' => esc_html__( 'None (Custom Menu Order)', 'xforwoocommerce' ),
					'id' => esc_html__( 'ID', 'xforwoocommerce' ),
					'name' => esc_html__( 'Name', 'xforwoocommerce' ),
					'number' => esc_html__( 'Number', 'xforwoocommerce' ),
					'slug' => esc_html__( 'Slug', 'xforwoocommerce' ),
					'count' => esc_html__( 'Count', 'xforwoocommerce' )
				),
			);
		}

		public static function ___get_order_option() {
			return array(
				'name' => esc_html__( 'Order', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select ascending/descending', 'xforwoocommerce' ),
				'id'   => 'order',
				'default' => 'ASC',
				'options' => array(
					'ASC' => esc_html__( 'ASC', 'xforwoocommerce' ),
					'DESC' => esc_html__( 'DESC', 'xforwoocommerce' )
				),
			);
		}

		public static function ___get_limit_option() {
			return array(
				'name' => esc_html__( 'Show more', 'xforwoocommerce' ),
				'type' => 'number',
				'desc' => esc_html__( 'Show more button on term', 'xforwoocommerce' ),
				'id'   => 'limit',
				'default' => '',
			);
		}

		public static function ___get_hierarchy_option() {
			return array(
				'name' => esc_html__( 'Hierarchy', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Use hierarchy.', 'xforwoocommerce' ),
				'id'   => 'hierarchy',
				'default' => 'no',
			);
		}

		public static function ___get_hierarchy_mode_option() {
			return array(
				'name' => esc_html__( 'Hierarchy Mode', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select hierarchy mode', 'xforwoocommerce' ),
				'id'   => 'hierarchy_mode',
				'default' => 'showall',
				'options' => array(
					'showall' => esc_html__( 'Show all terms', 'xforwoocommerce' ),
					'drill' => esc_html__( 'Show current level terms (Drill filter)', 'xforwoocommerce' ),
					'drillback' => esc_html__( 'Show current level terms with parent term support (Drill filter)', 'xforwoocommerce' ),
					'subonly' => esc_html__( 'Show lower level hierarchy terms', 'xforwoocommerce' ),
					'subonlyback' => esc_html__( 'Show lower level hierarchy terms with parent term support', 'xforwoocommerce' )
				),
			);
		}

		public static function ___get_hierarchy_expand_option() {
			return array(
				'name' => esc_html__( 'Hierarchy Expand', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Expand hierarchy tree on load', 'xforwoocommerce' ),
				'id'   => 'hierarchy_expand',
				'default' => 'no',
			);
		}

		public static function ___get_multiselect_option() {
			return array(
				'name' => esc_html__( 'Multiselect', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Use multiselect', 'xforwoocommerce' ),
				'id'   => 'multiselect',
				'default' => 'no',
			);
		}

		public static function ___get_multiselect_relation_option() {
			return array(
				'name' => esc_html__( 'Multiselect Relation', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select multiselect relation', 'xforwoocommerce' ),
				'id'   => 'multiselect_relation',
				'default' => 'IN',
				'options' => array(
					'IN' => 'IN',
					'AND' => 'AND',
				),
			);
		}

		public static function ___get_selection_reset_option() {
			return array(
				'name' => esc_html__( 'Selection Reset', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Reset filters on select', 'xforwoocommerce' ),
				'id'   => 'selection_reset',
				'default' => 'no',
			);
		}

		public static function ___get_adoptive_option() {
			return array(
				'name' => esc_html__( 'Adoptive', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select adoptive filtering', 'xforwoocommerce' ),
				'id'   => 'adoptive',
				'default' => 'no',
				'options' => array(
					'no' => esc_html__( 'Not active on this filter', 'xforwoocommerce' ),
					'pf_adptv_default' => esc_html__( 'Terms will be hidden', 'xforwoocommerce' ),
					'pf_adptv_unclick' => esc_html__( 'Terms will be shown, but unclickable', 'xforwoocommerce' ),
					'pf_adptv_click' => esc_html__( 'Terms will be shown and clickable', 'xforwoocommerce' ),
				),
				'condition' => 'a_enable:yes',
			);
		}

		public static function ___get_adoptive_for_range_option() {
			return array(
				'name' => esc_html__( 'Adoptive', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select adoptive filtering', 'xforwoocommerce' ),
				'id'   => 'adoptive',
				'default' => 'no',
				'options' => array(
					'no' => esc_html__( 'Not active on this filter', 'xforwoocommerce' ),
					'pf_adptv_default' => esc_html__( 'Terms will be hidden', 'xforwoocommerce' ),
				),
				'condition' => 'a_enable:yes',
			);
		}

		public static function ___get_term_count_option() {
			return array(
				'name' => esc_html__( 'Term Count', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Show term count', 'xforwoocommerce' ),
				'id'   => 'term_count',
				'default' => 'no',
			);
		}

		public static function ___get_term_search_option() {
			return array(
				'name' => esc_html__( 'Term Search', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Show term search input', 'xforwoocommerce' ),
				'id'   => 'term_search',
				'default' => 'no',
			);
		}

		public static function ___get_term_display_option() {
			return array(
				'name' => esc_html__( 'Term Display', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select terms display style', 'xforwoocommerce' ),
				'id'   => 'term_display',
				'default' => 'none',
				'options' => array(
					'none' => esc_html__( 'Default', 'xforwoocommerce' ),
					'inline' => esc_html__( 'Inline', 'xforwoocommerce' ),
					'2_columns' => esc_html__( 'Split into two columns', 'xforwoocommerce' ),
					'3_columns' => esc_html__( 'Split into three columns', 'xforwoocommerce' ),
				),
			);
		}

		public static function ___get_none_text() {
			return array(
				'name' => esc_html__( 'None Text', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Change none text', 'xforwoocommerce' ),
				'id'   => 'none_text',
				'default' => '',
			);
		}

		public static function ___get_hide_elements_option() {
			return array(
				'name' => esc_html__( 'Hide Elements', 'xforwoocommerce' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select elements to hide', 'xforwoocommerce' ),
				'id'   => 'hide_elements',
				'default' => '',
				'options' => array(
					'title' => esc_html__( 'Title', 'prdctflr' ),
					'none' => esc_html__( 'None', 'xforwoocommerce' ),
				),
				'class' => 'svx-selectize',
			);
		}

		public static function ___get_hide_elements_for_range_option() {
			return array(
				'name' => esc_html__( 'Hide Elements', 'xforwoocommerce' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select elements to hide', 'xforwoocommerce' ),
				'id'   => 'hide_elements',
				'default' => '',
				'options' => array(
					'title' => esc_html__( 'Title', 'prdctflr' ),
				),
				'class' => 'svx-selectize',
			);
		}

		public static function ___get_range_style_option() {
			return array(
				'name' => esc_html__( 'Style', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Style', 'xforwoocommerce' ),
				'id'   => 'design',
				'default' => 'thin',
				'options' => array(
					'flat' => esc_html__( 'Flat', 'xforwoocommerce' ),
					'modern' => esc_html__( 'Modern', 'xforwoocommerce' ),
					'html5' => esc_html__( 'HTML5', 'xforwoocommerce' ),
					'white' => esc_html__( 'White', 'xforwoocommerce' ),
					'thin' => esc_html__( 'Thin', 'xforwoocommerce' ),
					'knob' => esc_html__( 'Knob', 'xforwoocommerce' ),
					'metal' => esc_html__( 'Metal', 'xforwoocommerce' )
				),
			);
		}

		public static function ___get_range_grid_option() {
			return array(
				'name' => esc_html__( 'Grid', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Show grid', 'xforwoocommerce' ),
				'id'   => 'grid',
				'default' => '',
			);
		}

		public static function ___get_range_start_option() {
			return array(
				'name' => esc_html__( 'Start', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Range start', 'xforwoocommerce' ),
				'id'   => 'start',
				'default' => '',
			);
		}

		public static function ___get_range_end_option() {
			return array(
				'name' => esc_html__( 'End', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Range end', 'xforwoocommerce' ),
				'id'   => 'end',
				'default' => '',
			);
		}

		public static function ___get_range_prefix_option() {
			return array(
				'name' => esc_html__( 'Prefix', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Terms prefix', 'xforwoocommerce' ),
				'id'   => 'prefix',
				'default' => '',
			);
		}

		public static function ___get_range_postfix_option() {
			return array(
				'name' => esc_html__( 'Postfix', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Terms postfix', 'xforwoocommerce' ),
				'id'   => 'postfix',
				'default' => '',
			);
		}

		public static function ___get_range_step_option() {
			return array(
				'name' => esc_html__( 'Step', 'xforwoocommerce' ),
				'type' => 'number',
				'desc' => esc_html__( 'Step value', 'xforwoocommerce' ),
				'id'   => 'step',
				'default' => '',
			);
		}

		public static function ___get_range_grid_num_option() {
			return array(
				'name' => esc_html__( 'Grid density', 'xforwoocommerce' ),
				'type' => 'number',
				'desc' => esc_html__( 'Grid density value', 'xforwoocommerce' ),
				'id'   => 'grid_num',
				'default' => '',
			);
		}

		public static function ___get_meta_key_option() {
			return array(
				'name' => esc_html__( 'Meta key', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter meta key', 'xforwoocommerce' ),
				'id'   => 'meta_key',
				'default' => '',
			);
		}

		public static function ___get_meta_compare_option() {
			return array(
				'name' => esc_html__( 'Meta compare', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select meta compare', 'xforwoocommerce' ),
				'id'   => 'meta_compare',
				'default' => '=',
				'options' => array(
					'=' => '=',
					'!=' => '!=',
					'>' => '>',
					'<' => '<',
					'>=' => '>=',
					'<=' => '<=',
					'LIKE' => 'LIKE',
					'NOT LIKE' => 'NOT LIKE',
					'IN' => 'IN',
					'NOT IN' => 'NOT IN',
					'EXISTS' => 'EXISTS',
					'NOT EXISTS' => 'NOT EXISTS',
					'BETWEEN' => 'BETWEEN',
					'NOT BETWEEN' => 'NOT BETWEEN',
				),
			);
		}

		public static function ___get_meta_numeric() {
			return array(
				'name' => esc_html__( 'Numeric', 'xforwoocommerce' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Meta values are numeric', 'xforwoocommerce' ),
				'id'   => 'meta_numeric',
				'default' => '',
			);
		}

		public static function ___get_meta_type_option() {
			return array(
				'name' => esc_html__( 'Meta type', 'xforwoocommerce' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select meta type', 'xforwoocommerce' ),
				'id'   => 'meta_type',
				'default' => 'CHAR',
				'options' => array(
					'NUMERIC' => 'NUMERIC',
					'BINARY' => 'BINARY',
					'CHAR' => 'CHAR',
					'DATE' => 'DATE',
					'DATETIME' => 'DATETIME',
					'DECIMAL' => 'DECIMAL',
					'SIGNED' => 'SIGNED',
					'TIME' => 'TIME',
					'UNSIGNED' => 'UNSIGNED',
				),
			);
		}

		public static function ___get_placeholder_option() {
			return array(
				'name' => esc_html__( 'Placeholder', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Placeholder text', 'xforwoocommerce' ),
				'id'   => 'placeholder',
				'default' => '',
			);
		}

		public static function ___get_label_option() {
			return array(
				'name' => esc_html__( 'Label', 'xforwoocommerce' ),
				'type' => 'text',
				'desc' => esc_html__( 'Label text', 'xforwoocommerce' ),
				'id'   => 'label',
				'default' => '',
			);
		}

		public static function __build_filters() {

			$array = array();

			$array['taxonomy'] = array(
				'taxonomy' => self::___get_taxonomy_option(),
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'include' => self::___get_include_option(),
				'orderby' => self::___get_orderby_option(),
				'order' => self::___get_order_option(),
				'limit' => self::___get_limit_option(),
				'hierarchy' => self::___get_hierarchy_option(),
				'hierarchy_mode' => self::___get_hierarchy_mode_option(),
				'hierarchy_expand' => self::___get_hierarchy_expand_option(),
				'multiselect' => self::___get_multiselect_option(),
				'multiselect_relation' => self::___get_multiselect_relation_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'adoptive' => self::___get_adoptive_option(),
				'term_count' => self::___get_term_count_option(),
				'term_search' => self::___get_term_search_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);


			$array['range'] = array(
				'taxonomy' => self::___get_taxonomy_option(),
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'include' => self::___get_include_option(),
				'orderby' => self::___get_orderby_option(),
				'order' => self::___get_order_option(),
				'design' => self::___get_range_style_option(),
				'start' => self::___get_range_start_option(),
				'end' => self::___get_range_end_option(),
				'prefix' => self::___get_range_prefix_option(),
				'postfix' => self::___get_range_postfix_option(),
				'step' => self::___get_range_step_option(),
				'grid' => self::___get_range_grid_option(),
				'grid_num' => self::___get_range_grid_num_option(),
				'adoptive' => self::___get_adoptive_for_range_option(),
				'hide_elements' => self::___get_hide_elements_for_range_option(),
			);

			$array['meta'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'meta_key' => self::___get_meta_key_option(),
				'meta_compare' => self::___get_meta_compare_option(),
				'meta_type' => self::___get_meta_type_option(),
				'limit' => self::___get_limit_option(),
				'multiselect' => self::___get_multiselect_option(),
				'multiselect_relation' => self::___get_multiselect_relation_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_search' => self::___get_term_search_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);


			$array['meta_range'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'meta_key' => self::___get_meta_key_option(),
				'meta_numeric' => self::___get_meta_numeric(),
				'design' => self::___get_range_style_option(),
				'start' => self::___get_range_start_option(),
				'end' => self::___get_range_end_option(),
				'prefix' => self::___get_range_prefix_option(),
				'postfix' => self::___get_range_postfix_option(),
				'step' => self::___get_range_step_option(),
				'grid' => self::___get_range_grid_option(),
				'grid_num' => self::___get_range_grid_num_option(),
				'hide_elements' => self::___get_hide_elements_for_range_option(),
			);

			$array['vendor'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'include' => self::___get_include_option(),
				'limit' => self::___get_limit_option(),
				'multiselect' => self::___get_multiselect_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_search' => self::___get_term_search_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array['orderby'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'include' => self::___get_include_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array['search'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'placeholder' => self::___get_placeholder_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array['instock'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'include' => self::___get_include_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array['price'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array['price_range'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'design' => self::___get_range_style_option(),
				'grid' => self::___get_range_grid_option(),
				'start' => self::___get_range_start_option(),
				'end' => self::___get_range_end_option(),
				'prefix' => self::___get_range_prefix_option(),
				'postfix' => self::___get_range_postfix_option(),
				'step' => self::___get_range_step_option(),
				'grid_num' => self::___get_range_grid_num_option(),
				'hide_elements' => self::___get_hide_elements_for_range_option(),
			);

			$array['per_page'] = array(
				'name' => self::___get_title_option(),
				'desc' => self::___get_desc_option(),
				'selection_reset' => self::___get_selection_reset_option(),
				'term_display' => self::___get_term_display_option(),
				'none_text' => self::___get_none_text(),
				'hide_elements' => self::___get_hide_elements_option(),
			);

			$array = apply_filters( 'prdctflr_supported_filters', $array );

			return $array;

		}

		public static function get_key() {
			return get_option( 'xforwc_key_product_filter' ) === false ? 'false' : 'true';
		}

		public static function get_settings( $plugins ) {
 
			self::$settings['options'] = Prdctfltr()->get_default_options();

			self::$settings['preset'] = Prdctfltr()->___get_preset( 'default' );

			$saved = isset( self::$settings['options']['presets'] ) && is_array ( self::$settings['options']['presets'] ) ? self::$settings['options']['presets'] : array();
			foreach( $saved as $preset ) {
				self::$presets[$preset['slug']] = $preset['name'];
			}

			if ( empty( self::$presets ) ) {
				self::$presets = false;
			}

			$attributes = get_object_taxonomies( 'product' );
			foreach( $attributes as $k ) {
				if ( !in_array( $k, array() ) ) {
					if ( substr( $k, 0, 3 ) == 'pa_' ) {
						$ready_attributes[$k] = wc_attribute_label( $k );
					}
					else {
						$taxonomy = get_taxonomy( $k );
						$ready_attributes[$k] = $taxonomy->label;
					}
				}
			}

			if ( empty( $ready_attributes ) ) {
				$ready_attributes = false;
			}

			include_once( 'class-themes.php' );
			$ajax = XforWC_Product_Filters_Themes::get_theme();

			$plugins[self::$plugin['label']] = array(
				'slug' => self::$plugin['label'],
				'name' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['xforwc'] : self::$plugin['name'] ),
				'desc' => esc_html( function_exists( 'XforWC' ) ? self::$plugin['name'] . ' v' . self::$plugin['version'] : esc_html__( 'Settings page for', 'xforwoocommerce' ) . ' ' . self::$plugin['name'] ),
				'link' => esc_url( 'https://xforwoocommerce.com/store/product-filters/' ),
				'imgs' => esc_url( Prdctfltr()->plugin_url() ),
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
						'name' => esc_html__( 'Filter Presets', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Filter Presets Options', 'xforwoocommerce' ),
					),
					'manager' => array(
						'name' => esc_html__( 'Presets Manager', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Presets Manager Options', 'xforwoocommerce' ),
					),
					'integration' => array(
						'name' => esc_html__( 'Shop Integration', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Shop Integration Options', 'xforwoocommerce' ),
					),
					'ajax' => array(
						'name' => esc_html__( 'AJAX', 'xforwoocommerce' ),
						'desc' => esc_html__( 'AJAX Options', 'xforwoocommerce' ),
					),
					'general' => array(
						'name' => esc_html__( 'Advanced', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Advanced Options', 'xforwoocommerce' ),
					),
					'analytics' => array(
						'name' => esc_html__( 'Analytics', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Filtering Analytics', 'xforwoocommerce' ),
					),
				),
				'extras' => array(
					'product_attributes' => $ready_attributes,
					'more_titles' => array(
						'orderby' => esc_html__( 'Order by', 'xforwoocommerce' ),
						'per_page' => esc_html__( 'Per page', 'xforwoocommerce' ),
						'vendor' => esc_html__( 'Vendor', 'xforwoocommerce' ),
						'search' => esc_html__( 'Search', 'xforwoocommerce' ),
						'instock' => esc_html__( 'Avalibility', 'xforwoocommerce' ),
						'price' => esc_html__( 'Price', 'xforwoocommerce' ),
						'price_range' => esc_html__( 'Price range', 'xforwoocommerce' ),
						'meta' => esc_html__( 'Meta filter', 'xforwoocommerce' ),
					),
					'options' => self::$settings['options'],
					'presets' => array(
						'loaded' => 'default',
						'loaded_settings' => self::$settings['preset'],
						'set' => self::$presets,
					),
					'terms' => array(
						'orderby' => array(
							array(
								'id' => 'menu_order',
								'slug' => 'menu_order',
								'default_name' => 'Default',
							),
							array(
								'id' => 'comment_count',
								'slug' => 'comment_count',
								'default_name' => 'Review Count',
							),
							array(
								'id' => 'popularity',
								'slug' => 'popularity',
								'default_name' => 'Popularity',
							),
							array(
								'id' => 'rating',
								'slug' => 'rating',
								'default_name' => 'Average rating',
							),
							array(
								'id' => 'date',
								'slug' => 'date',
								'default_name' => 'Newness',
							),
							array(
								'id' => 'price',
								'slug' => 'price',
								'default_name' => 'Price: low to high',
							),
							array(
								'id' => 'price-desc',
								'slug' => 'price-desc',
								'default_name' => 'Price: high to low',
							),
							array(
								'id' => 'rand',
								'slug' => 'rand',
								'default_name' => 'Random Products',
							),
							array(
								'id' => 'title',
								'slug' => 'title',
								'default_name' => 'Product Name',
							),
						),
						'instock' => array(
							array(
								'id' => 'out',
								'slug' => 'out',
								'default_name' => 'Out of stock',
							),
							array(
								'id' => 'in',
								'slug' => 'in',
								'default_name' => 'In stock',
							),
							array(
								'id' => 'both',
								'slug' => 'both',
								'default_name' => 'All products',
							),
						),
					),
				),
				'settings' => array(),
			);

			$plugins['product_filter']['settings']['wcmn_dashboard'] = array(
				'type' => 'html',
				'id' => 'wcmn_dashboard',
				'desc' => '
				<img src="' . Prdctfltr()->plugin_url() . '/includes/images/product-filter-for-woocommerce-shop.png" class="svx-dashboard-image" />
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
			);

			$plugins['product_filter']['settings']['wcmn_utility'] = array(
				'name' => esc_html__( 'Plugin Options', 'xforwoocommerce' ),
				'type' => 'utility',
				'id' => 'wcmn_utility',
				'desc' => esc_html__( 'Quick export/import, backup and restore, or just reset your optons here', 'xforwoocommerce' ),
				'section' => 'dashboard',
			);

			$plugins['product_filter']['settings'] = array_merge( $plugins['product_filter']['settings'], array(

				'_filter_preset_manager' => array(
					'name' => esc_html__( 'Filter Preset', 'xforwoocommerce' ),
					'type' => 'select',
					'id' => '_filter_preset_manager',
					'desc' => esc_html__( 'Editing selected filter preset', 'xforwoocommerce' ),
					'section' => 'presets',
					'options' => 'function:__make_presets',
					'default' => 'default',
					'class' => '',
					'val' => 'default',
				),

				'_filter_preset_options' => array(
					'name' => esc_html__( 'Filter Options', 'xforwoocommerce' ),
					'type' => 'select',
					'id' => '_filter_preset_options',
					'desc' => esc_html__( 'Select options group for the current preset', 'xforwoocommerce' ),
					'section' => 'presets',
					'options' => array(
						'filters' => esc_html__( 'Filters' , 'xforwoocommerce' ),
						'general' => esc_html__( 'General' , 'xforwoocommerce' ),
						'style' => esc_html__( 'Style' , 'xforwoocommerce' ),
						'adoptive' => esc_html__( 'Adoptive' , 'xforwoocommerce' ),
						'responsive' => esc_html__( 'Responsive' , 'xforwoocommerce' ),
					),
					'default' => 'filters',
					'class' => 'svx-make-group svx-refresh-active-tab',
					'val' => 'filters',
				),

				'g_instant' => array(
					'name' => esc_html__( 'Filter on Click', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to filter on click', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_instant',
					'default' => 'no',
					'condition' => '_filter_preset_options:general',
				),

				'g_step_selection' => array(
					'name' => esc_html__( 'Stepped Selection', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to use stepped selection', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_step_selection',
					'default' => 'no',
					'condition' => '_filter_preset_options:general',
				),

				'g_collectors' => array(
					'name' => esc_html__( 'Show Selected Terms In', 'xforwoocommerce' ),
					'type' => 'multiselect',
					'desc' => esc_html__( 'Select areas where to show the selected terms', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_collectors',
					'options'   => array(
						'topbar' => esc_html__( 'Top bar', 'xforwoocommerce' ),
						'collector' => esc_html__( 'Collector', 'xforwoocommerce' ),
						'intitle' => esc_html__( 'Filter title', 'xforwoocommerce' ),
						'aftertitle' => esc_html__( 'After filter title', 'xforwoocommerce' ),
					),
					'default' => array( 'collector' ),
					'condition' => '_filter_preset_options:general',
					'class' => 'svx-selectize',
				),

				'g_collector_style' => array(
					'name' => esc_html__( 'Selected Terms Style', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select selected terms style', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_collector_style',
					'options'   => array(
						'flat' => esc_html__( 'Flat', 'xforwoocommerce' ),
						'border' => esc_html__( 'Border', 'xforwoocommerce' ),
					),
					'default' => 'flat',
					'condition' => '_filter_preset_options:general',
				),

				'g_reorder_selected' => array(
					'name' => esc_html__( 'Reorder Selected', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to bring selected terms to the top', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_reorder_selected',
					'default' => 'no',
					'condition' => '_filter_preset_options:general',
				),

				'g_form_action' => array(
					'name' => esc_html__( 'Filter Form Action', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter custom filter form action="" parameter', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'g_form_action',
					'default' => '',
					'condition' => '_filter_preset_options:general',
				),

				's_style' => array(
					'name' => esc_html__( 'Select Design', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select filter design style', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_style',
					'options'   => array(
						'pf_default' => esc_html__( 'Default', 'xforwoocommerce' ),
						'pf_arrow' => esc_html__( 'Pop Up', 'xforwoocommerce' ),
						'pf_select' => esc_html__( 'Select Boxes', 'xforwoocommerce' ),
						'pf_sidebar' => esc_html__( 'Fixed Sidebar Left', 'xforwoocommerce' ),
						'pf_sidebar_right' => esc_html__( 'Fixed Sidebar Right', 'xforwoocommerce' ),
						'pf_sidebar_css' => esc_html__( 'Fixed Sidebar Left With Overlay', 'xforwoocommerce' ),
						'pf_sidebar_css_right' => esc_html__( 'Fixed Sidebar Right With Overlay', 'xforwoocommerce' ),
						'pf_fullscreen' => esc_html__( 'Full Screen Overlay', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_always_visible' => array(
					'name' => esc_html__( 'Always Visible', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Disable slide in/out animation', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_always_visible',
					'default' => 'no',
					'condition' => '_filter_preset_options:style',
				),

				's_mode' => array(
					'name' => esc_html__( 'Row Display', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select row display mode', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_mode',
					'options'   => array(
						'pf_mod_row' => esc_html__( 'One row', 'xforwoocommerce' ),
						'pf_mod_multirow' => esc_html__( 'Multiple rows', 'xforwoocommerce' ),
						'pf_mod_masonry' => esc_html__( 'Masonry Filters', 'xforwoocommerce' ),
					),
					'default' => 'pf_mod_multirow',
					'condition' => '_filter_preset_options:style',
				),

				's_columns' => array(
					'name' => esc_html__( 'Max Columns', 'xforwoocommerce' ),
					'type' => 'number',
					'desc' => esc_html__( 'Set max filter columns', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_columns',
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_max_height' => array(
					'name' => esc_html__( 'Max Height', 'xforwoocommerce' ),
					'type' => 'number',
					'desc' => esc_html__( 'Set max filter height', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_max_height',
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_js_scroll' => array(
					'name' => esc_html__( 'JS Scroll Bars', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Enable JavaScript scroll bars for nicer display', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_js_scroll',
					'default' => 'no',
					'condition' => '_filter_preset_options:style',
				),

				's_checkbox_style' => array(
					'name' => esc_html__( 'Checkbox Style', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select term checkbox style', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_checkbox_style',
					'options'   => array(
						'prdctfltr_bold' => esc_html__( 'Hide', 'xforwoocommerce' ),
						'prdctfltr_round' => esc_html__( 'Round', 'xforwoocommerce' ),
						'prdctfltr_square' => esc_html__( 'Square', 'xforwoocommerce' ),
						'prdctfltr_checkbox' => esc_html__( 'Checkbox', 'xforwoocommerce' ),
						'prdctfltr_system' => esc_html__( 'System Checkboxes', 'xforwoocommerce' ),
					),
					'default' => 'prdctfltr_round',
					'condition' => '_filter_preset_options:style',
				),

				's_hierarchy_style' => array(
					'name' => esc_html__( 'Hierarchy Style', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select hierarchy style', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_hierarchy_style',
					'options'   => array(
						'prdctfltr_hierarchy_hide' => esc_html__( 'Hide', 'xforwoocommerce' ),
						'prdctfltr_hierarchy_circle' => esc_html__( 'Circle', 'xforwoocommerce' ),
						'prdctfltr_hierarchy_filled' => esc_html__( 'Circle Solid', 'xforwoocommerce' ),
						'prdctfltr_hierarchy_lined' => esc_html__( 'Lined', 'xforwoocommerce' ),
						'prdctfltr_hierarchy_arrow' => esc_html__( 'Arrows', 'xforwoocommerce' ),
					),
					'default' => 'prdctfltr_hierarchy_lined',
					'condition' => '_filter_preset_options:style',
				),

				's_filter_icon' => array(
					'name' => esc_html__( 'Filter Icon', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter icon class. Use icon class e.g. prdctfltr-filter or FontAwesome fa fa-shopping-cart or any other', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_filter_icon',
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_filter_title' => array(
					'name' => esc_html__( 'Filter Title Text', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter title text', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_filter_title',
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_filter_button' => array(
					'name' => esc_html__( 'Filter Button Text', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter filter button text', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_filter_button',
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_button_position' => array(
					'name' => esc_html__( 'Button Position', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select button position', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_button_position',
					'options'   => array(
						'bottom' => esc_html__( 'Bottom', 'xforwoocommerce' ),
						'top' => esc_html__( 'Top', 'xforwoocommerce' ),
						'both' => esc_html__( 'Both', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_content_align' => array(
					'name' => esc_html__( 'Content Align', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Set content align', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_content_align',
					'options'   => array(
						'left' => esc_html__( 'Left', 'xforwoocommerce' ),
						'center' => esc_html__( 'Center', 'xforwoocommerce' ),
						'right' => esc_html__( 'Right', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => '_filter_preset_options:style',
				),

				's_hide_elements' => array(
					'name' => esc_html__( 'Hide Elements', 'xforwoocommerce' ),
					'type' => 'multiselect',
					'desc' => esc_html__( 'Select elements to hide', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_hide_elements',
					'options' => array(
						'hide_icon' => esc_html__( 'Filter icon', 'xforwoocommerce' ),
						'hide_top_bar' => esc_html__( 'The whole top bar', 'xforwoocommerce' ),
						'hide_showing' => esc_html__( 'Showing text in top bar', 'xforwoocommerce' ),
						'hide_sale_button' => esc_html__( 'Sale button', 'xforwoocommerce' ),
						'hide_instock_button' => esc_html__( 'Instock button', 'xforwoocommerce' ),
						'hide_reset_button' => esc_html__( 'Reset button', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => '_filter_preset_options:style',
					'class' => 'svx-selectize',
				),

				's_loading_animation' => array(
					'name' => esc_html__( 'Loader Animation', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select loader animation', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 's_loading_animation',
					'options'   => array(
						'css-spinner-full' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '1' ),
						'css-spinner-full-01' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '2' ),
						'css-spinner-full-02' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '3' ),
						'css-spinner-full-03' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '4' ),
						'css-spinner-full-04' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '5' ),
						'css-spinner-full-05' => sprintf( esc_html__( 'Overlay #%s', 'xforwoocommerce' ), '6' ),
						'css-spinner' => sprintf( esc_html__( 'In Title #%s', 'xforwoocommerce' ), '1' ),
						'css-spinner-01' => sprintf( esc_html__( 'In title #%s', 'xforwoocommerce' ), '2' ),
						'css-spinner-02' => sprintf( esc_html__( 'In title #%s', 'xforwoocommerce' ), '3' ),
						'css-spinner-03' => sprintf( esc_html__( 'In title #%s', 'xforwoocommerce' ), '4' ),
						'css-spinner-04' => sprintf( esc_html__( 'In title #%s', 'xforwoocommerce' ), '5' ),
						'css-spinner-05' => sprintf( esc_html__( 'In title #%s', 'xforwoocommerce' ), '6' ),
						'none' => esc_html__( 'None', 'xforwoocommerce' ),
					),
					'default' => 'css-spinner-full',
					'condition' => '_filter_preset_options:style',
				),

				'a_enable' => array(
					'name' => esc_html__( 'Enable', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to use adoptive filtering in current preset', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'a_enable',
					'default' => 'no',
					'condition' => '_filter_preset_options:adoptive',
				),

				'a_active_on' => array(
					'name' => esc_html__( 'Active On', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select when to activate adoptive filtering', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'a_active_on',
					'options'   => array(
						'always' => esc_html__( 'Always active', 'xforwoocommerce' ),
						'permalink' => esc_html__( 'Active on permalinks and filters', 'xforwoocommerce' ),
						'filter' => esc_html__( 'Active on filters', 'xforwoocommerce' ),
					),
					'default' => 'no',
					'condition' => '_filter_preset_options:adoptive',
				),

				'a_depend_on' => array(
					'name' => esc_html__( 'Depend On', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select taxonomy terms can depend on', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'a_depend_on',
					'options' => 'ajax:product_taxonomies:has_none',
					'default' => '',
					'condition' => '_filter_preset_options:adoptive',
				),

				'a_term_counts' => array(
					'name' => esc_html__( 'Product Count', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select adoptive product count display', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'a_term_counts',
					'options'   => array(
						'default' => esc_html__( 'Filtered count / Total', 'xforwoocommerce' ),
						'count' => esc_html__( 'Filtered count', 'xforwoocommerce' ),
						'total' => esc_html__( 'Total', 'xforwoocommerce' ),
					),
					'default' => 'default',
					'condition' => '_filter_preset_options:adoptive',
				),

				'a_reorder_selected' => array(
					'name' => esc_html__( 'Reorder Terms', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Reorder remaining terms to the top', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'a_reorder_selected',
					'default' => 'no',
					'condition' => '_filter_preset_options:adoptive',
				),

				'r_behaviour' => array(
					'name' => esc_html__( 'Responsive Behaviour', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Set filter preset behaviour on defined resolution', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'r_behaviour',
					'options'   => array(
						'none' => esc_html__( 'Do not do a thing', 'xforwoocommerce' ),
						'switch' => esc_html__( 'Switch with filter preset', 'xforwoocommerce' ),
						'hide' => esc_html__( 'Show on screen resolution smaller than', 'xforwoocommerce' ),
						'show' => esc_html__( 'Show on screen resolution larger than', 'xforwoocommerce' ),
					),
					'default' => 'none',
					'condition' => '_filter_preset_options:responsive',
					'class' => 'svx-refresh-active-tab',
				),

				'r_resolution' => array(
					'name' => esc_html__( 'Responsive Resolution', 'xforwoocommerce' ),
					'type' => 'number',
					'desc' => esc_html__( 'Set screen resolution in pixels that will trigger the responsive behaviour', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'r_resolution',
					'default' => '768',
					'condition' => '_filter_preset_options:responsive',
				),

				'r_preset' => array(
					'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Set filter preset', 'xforwoocommerce' ),
					'section' => 'presets',
					'id'   => 'r_preset',
					'options' => 'function:__make_presets',
					'default' => '',
					'condition' => '_filter_preset_options:responsive&&r_behaviour:switch',
				),

				'filters' => array(
					'name' => esc_html__( 'Filters', 'xforwoocommerce' ),
					'type' => 'list-select',
					'id'   => 'filters',
					'desc' => esc_html__( 'Add more filters to the current preset', 'xforwoocommerce' ),
					'section' => 'presets',
					'title' => esc_html__( 'Filter', 'xforwoocommerce' ),
					'supports' => array( 'customizer' ),
					'options' => 'list',
					'selects' => array(
						'taxonomy' => esc_html__( 'Taxonomy', 'xforwoocommerce' ),
						'range' => esc_html__( 'Taxonomy Range', 'xforwoocommerce' ),
						'meta' => esc_html__( 'Meta', 'xforwoocommerce' ),
						'meta_range' => esc_html__( 'Meta Range', 'xforwoocommerce' ),
						'vendor' => esc_html__( 'Vendor', 'xforwoocommerce' ),
						'orderby' => esc_html__( 'Order by', 'xforwoocommerce' ),
						'search' => esc_html__( 'Search', 'xforwoocommerce' ),
						'instock' => esc_html__( 'Avalibility', 'xforwoocommerce' ),
						'price' => esc_html__( 'Price', 'xforwoocommerce' ),
						'price_range' => esc_html__( 'Price Range', 'xforwoocommerce' ),
						'per_page' => esc_html__( 'Per page', 'xforwoocommerce' ),
					),
					'settings' => self::__build_filters(),
					'condition' => '_filter_preset_options:filters',
					'val' => '',
				)

			) );

			$plugins['product_filter']['settings'] = array_merge( $plugins['product_filter']['settings'], self::__build_overrides() );

			$plugins['product_filter']['settings']['supported_overrides'] = array(
				'name' => esc_html__( 'Select Taxonomies', 'xforwoocommerce' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select supported taxonomies for Presets Manager (needs a Save and page refresh to take effect!)', 'xforwoocommerce' ),
				'section' => 'manager',
				'id'   => 'supported_overrides',
				'options' => 'ajax:product_taxonomies',
				'default' => '',
				'class' => '',
			);

			$plugins['product_filter']['settings'] = array_merge( $plugins['product_filter']['settings'], array(

				'variable_images' => array(
					'name' => esc_html__( 'Switch Variable Images', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to switch variable images when filtering attributes', 'xforwoocommerce' ),
					'section' => 'general',
					'id'   => 'variable_images',
					'default' => 'no',
				),

				'clear_all' => array(
					'name' => esc_html__( 'Clear All', 'xforwoocommerce' ),
					'type' => 'multiselect',
					'desc' => esc_html__( 'Select taxonomies which Clear All button cannot clear', 'xforwoocommerce' ),
					'section' => 'general',
					'id'   => 'clear_all',
					'options' => 'ajax:product_taxonomies',
					'default' => '',
					'class' => 'svx-selectize',
				),

				'register_taxonomy' => array(
					'name' => esc_html__( 'Register Taxonomy', 'xforwoocommerce' ),
					'type' => 'list',
					'id'   => 'register_taxonomy',
					'desc' => esc_html__( 'Register custom product taxonomies (needs a Save and page refresh to take effect!)', 'xforwoocommerce' ),
					'section' => 'general',
					'title' => esc_html__( 'Name', 'xforwoocommerce' ),
					'options' => 'list',
					'default' => '',
					'settings' => array(
						'name' => array(
							'name' => esc_html__( 'Plural name', 'xforwoocommerce' ),
							'type' => 'text',
							'id' => 'name',
							'desc' => esc_html__( 'Enter plural taxonomy name', 'xforwoocommerce' ),
							'default' => '',
						),
						'single_name' => array(
							'name' => esc_html__( 'Singular name', 'xforwoocommerce' ),
							'type' => 'text',
							'id' => 'single_name',
							'desc' => esc_html__( 'Enter singular taxonomy name', 'xforwoocommerce' ),
							'default' => '',
						),
						'hierarchy' => array(
							'name' => esc_html__( 'Use hierarchy', 'xforwoocommerce' ),
							'type' => 'checkbox',
							'id'   => 'hierarchy',
							'desc' => esc_html__( 'Enable hierarchy for this taxonomy', 'xforwoocommerce' ),
							'default' => 'no',
						),
					),
				),

				'hide_empty' => array(
					'name' => esc_html__( 'Hide Empty Terms', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Hide empty terms', 'xforwoocommerce' ),
					'section' => 'general',
					'id'   => 'hide_empty',
					'default' => 'no',
				),

				'enable' => array(
					'name' => esc_html__( 'Use AJAX', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to use AJAX in Shop', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'enable',
					'default' => 'no',
					'class' => 'svx-refresh-active-tab',
				),

				'automatic' => array(
					'name' => esc_html__( 'Automatic AJAX', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to use automatic AJAX installation.', 'xforwoocommerce' ) . ' <strong>' . ( isset( $ajax['recognized'] ) ? esc_html__( 'Theme supported! AJAX is set for', 'xforwoocommerce' ) . ' ' . esc_html( $ajax['name'] ) : esc_html__( 'Theme not found in database. Using default settings.', 'xforwoocommerce' ) ) . '</strong>',
					'section' => 'ajax',
					'id'   => 'automatic',
					'default' => 'yes',
					'class' => 'svx-refresh-active-tab',
					'condition' => 'enable:yes',
				),

				'wrapper' => array(
					'name' => esc_html__( 'Product Wrapper', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter product wrapper jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['wrapper'] ) ? esc_html( $ajax['wrapper'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'wrapper',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'category' => array(
					'name' => esc_html__( 'Product Category', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter product category jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['category'] ) ? esc_html( $ajax['category'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'category',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'product' => array(
					'name' => esc_html__( 'Product', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter product jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['product'] ) ? esc_html( $ajax['product'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'product',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'columns' => array(
					'name' => esc_html__( 'Columns', 'xforwoocommerce' ),
					'type' => 'number',
					'desc' => esc_html__( 'Fix columns problems', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'columns',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'rows' => array(
					'name' => esc_html__( 'Rows', 'xforwoocommerce' ),
					'type' => 'number',
					'desc' => esc_html__( 'Fix rows problems', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'rows',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'result_count' => array(
					'name' => esc_html__( 'Result Count', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter result count jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['result_count'] ) ? esc_html( $ajax['result_count'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'result_count',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'order_by' => array(
					'name' => esc_html__( 'Order By', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter order by jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['order_by'] ) ? esc_html( $ajax['order_by'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'order_by',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'pagination' => array(
					'name' => esc_html__( 'Pagination', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter pagination jQuery selector.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['pagination'] ) ? esc_html( $ajax['pagination'] ) : '' ),
					'section' => 'ajax',
					'id'   => 'pagination',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'pagination_function' => array(
					'name' => esc_html__( 'Pagination Function', 'xforwoocommerce' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter pagination function.', 'xforwoocommerce' ) . ' ' . esc_html( 'Currently set to', 'xforwoocommerce' ) . ': ' . ( isset( $ajax['pagination_function'] ) ? esc_html( $ajax['pagination_function'] ) : 'woocommerce_pagination' ),
					'section' => 'ajax',
					'id'   => 'pagination_function',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'pagination_type' => array(
					'name' => esc_html__( 'Pagination Type', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select pagination type', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'pagination_type',
					'options' => array(
						'default' => esc_html__( 'Default (Theme)', 'xforwoocommerce' ),
						'prdctfltr-pagination-default' => esc_html__( 'Custom pagination (Product Filter)', 'xforwoocommerce' ),
						'prdctfltr-pagination-load-more' => esc_html__( 'Load more (Product Filter)', 'xforwoocommerce' ),
						'prdctfltr-pagination-infinite-load' => esc_html__( 'Infinite load (Product Filter)', 'xforwoocommerce' ),
					),
					'default' => 'default',
					'condition' => 'enable:yes',
				),

				'failsafe' => array(
					'name' => esc_html__( 'Failsafe', 'xforwoocommerce' ),
					'type' => 'multiselect',
					'desc' => esc_html__( 'Select which missing element will not trigger AJAX and will reload the page', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'failsafe',
					'options' => array(
						'wrapper' => esc_html__( 'Products wrapper', 'xforwoocommerce' ),
						'product' => esc_html__( 'Products', 'xforwoocommerce' ),
						'pagination' => esc_html__( 'Pagination', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'js' => array(
					'name' => esc_html__( 'After AJAX JS', 'xforwoocommerce' ),
					'type' => 'textarea',
					'desc' => esc_html__( 'Enter JavaScript or jQuery code to execute after AJAX', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'js',
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'animation' => array(
					'name' => esc_html__( 'Product Animation', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select product animation after AJAX', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'animation',
					'options' => array(
						'none' => esc_html__( 'No animation', 'xforwoocommerce' ),
						'default' => esc_html__( 'Fade in products', 'xforwoocommerce' ),
						'random' => esc_html__( 'Fade in random products', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => 'enable:yes',
				),

				'scroll_to' => array(
					'name' => esc_html__( 'Scroll To', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select scroll to after AJAX', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'scroll_to',
					'options' => array(
							'none' => esc_html__( 'Disable scroll', 'xforwoocommerce' ),
							'filter' => esc_html__( 'Filter', 'xforwoocommerce' ),
							'products' => esc_html__( 'Products', 'xforwoocommerce' ),
							'top' => esc_html__( 'Page top', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => 'enable:yes',
				),

				'permalinks' => array(
					'name' => esc_html__( 'Browser URL/Permalinks', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Select how to display browser URLs or permalinks on AJAX', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'permalinks',
					'options' => array(
						'no' => esc_html__( 'Use Product Filter redirects', 'xforwoocommerce' ),
						'query' => esc_html__( 'Only add query parameters', 'xforwoocommerce' ),
						'yes' => esc_html__( 'Disable URL changes', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => 'enable:yes',
				),

				'dont_load' => array(
					'name' => esc_html__( 'Disable Elements', 'xforwoocommerce' ),
					'type' => 'multiselect',
					'desc' => esc_html__( 'Select which elements will not be used with AJAX', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'dont_load',
					'options' => array(
						'title' => esc_html__( 'Shop title', 'xforwoocommerce' ),
						'desc' => esc_html__( 'Shop description', 'xforwoocommerce' ),
						'result' => esc_html__( 'Result count', 'xforwoocommerce' ),
						'orderby' => esc_html__( 'Order By', 'xforwoocommerce' ),
					),
					'default' => '',
					'condition' => 'enable:yes&&automatic:no',
				),

				'force_product' => array(
					'name' => esc_html__( 'Post Type', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to add the ?post_type=product parameter when filtering', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'force_product',
					'default' => 'no',
					'condition' => 'enable:no',
				),

				'force_action' => array(
					'name' => esc_html__( 'Stay on Permalink ', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to force filtering on same permalink (URL)', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'force_action',
					'default' => 'no',
					'condition' => 'enable:no',
				),

				'force_redirects' => array(
					'name' => esc_html__( 'Permalink Structure', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Set permalinks structure', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'force_redirects',
					'options' => array(
						'no' => esc_html__( 'Use Product Filter redirects', 'xforwoocommerce' ),
						'yes' => esc_html__( 'Use .htaccess and native WordPress redirects', 'xforwoocommerce' ),
					),
					'default' => 'no',
					'condition' => 'enable:no',
				),

				'remove_single_redirect' => array(
					'name' => esc_html__( 'Single Product', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to remove redirect when only one product is found', 'xforwoocommerce' ),
					'section' => 'ajax',
					'id'   => 'remove_single_redirect',
					'default' => 'no',
					'condition' => 'enable:no',
				),

				'actions' => array(
					'name' => esc_html__( 'Integration Hooks', 'xforwoocommerce' ),
					'type' => 'list',
					'id'   => 'actions',
					'desc' => esc_html__( 'Add filter presets to hooks', 'xforwoocommerce' ),
					'section' => 'integration',
					'title' => esc_html__( 'Name', 'xforwoocommerce' ),
					'options' => 'list',
					'default' => '',
					'settings' => array(
						'name' => array(
							'name' => esc_html__( 'Name', 'xforwoocommerce' ),
							'type' => 'text',
							'id' => 'name',
							'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
							'default' => '',
						),
						'hook' => array(
							'name' => esc_html__( 'Common Hooks', 'xforwoocommerce' ),
							'type' => 'select',
							'id'   => 'hook',
							'desc' => esc_html__( 'Select a common hook', 'xforwoocommerce' ),
							'options' => array(
								'' => esc_html__( 'Use custom hook', 'xforwoocommerce' ),
								'woocommerce_before_main_content' => 'woocommerce_before_main_content',
								'woocommerce_archive_description' => 'woocommerce_archive_description',
								'woocommerce_before_shop_loop' => 'woocommerce_before_shop_loop',
								'woocommerce_after_shop_loop' => 'woocommerce_after_shop_loop',
								'woocommerce_after_main_content' => 'woocommerce_after_main_content',
							),
							'default' => '',
						),
						'action' => array(
							'name' => esc_html__( 'Custom Hook', 'xforwoocommerce' ),
							'type' => 'text',
							'id'   => 'action',
							'desc' => esc_html__( 'If you use custom hook, rather than common hooks, please enter it here', 'xforwoocommerce' ),
							'default' => '',
						),
						'priority' => array(
							'name' => esc_html__( 'Priority', 'xforwoocommerce' ),
							'type' => 'number',
							'id'   => 'priority',
							'desc' => esc_html__( 'Set hook priority', 'xforwoocommerce' ),
							'default' => '',
						),
						'preset' => array(
							'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
							'type' => 'select',
							'id'   => 'preset',
							'desc' => esc_html__( 'Set filter preset', 'xforwoocommerce' ),
							'options' => 'function:__make_presets',
							'default' => '',
							'class' => 'svx-selectize',
						),
						'disable_overrides' => array(
							'name' => esc_html__( 'Presets Manager', 'xforwoocommerce' ),
							'type' => 'checkbox',
							'id'   => 'disable_overrides',
							'desc' => esc_html__( 'Disable presets manager settings', 'xforwoocommerce' ),
							'default' => '',
						),
						'id' => array(
							'name' => esc_html__( 'ID', 'xforwoocommerce' ),
							'type' => 'text',
							'id'   => 'id',
							'desc' => esc_html__( 'Enter filter element ID attribute', 'xforwoocommerce' ),
							'default' => '',
						),
						'class' => array(
							'name' => esc_html__( 'Class', 'xforwoocommerce' ),
							'type' => 'text',
							'id'   => 'class',
							'desc' => esc_html__( 'Enter filter element class attribute', 'xforwoocommerce' ),
							'default' => '',
						),
					),
				),

				'el_result_count' => array(
					'name' => esc_html__( 'Result Count Integration', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Replace WooCommerce result count element with a product filter', 'xforwoocommerce' ),
					'section' => 'integration',
					'id'   => 'el_result_count',
					'options' => 'function:__make_presets:template',
					'default' => '_do_not',
				),

				'el_orderby' => array(
					'name' => esc_html__( 'Order By Integration', 'xforwoocommerce' ),
					'type' => 'select',
					'desc' => esc_html__( 'Replace WooCommerce order by element with a product filter', 'xforwoocommerce' ),
					'section' => 'integration',
					'id'   => 'el_orderby',
					'options' => 'function:__make_presets:template',
					'default' => '_do_not',
				),

				'widget_notice' => array(
					'name' => esc_html__( 'Widget Integration', 'xforwoocommerce' ),
					'type' => 'html',
					'desc' => '
					<div class="svx-option-header"><h3>' . esc_html__( 'Widget Integration', 'xforwoocommerce' ) . '</h3></div><div class="svx-option-wrapper"><div class="svx-notice svx-info">' . esc_html__( 'Looking for widget integration options? Product Filter widgets are added to sidebars in the WordPress Widgets screen.', 'xforwoocommerce' ) . ' <a href="' . admin_url( 'widgets.php' ) . '">' . esc_html__( 'Click here to navigate to WordPress Widgets', 'xforwoocommerce' ) . '</a><br /><br />' . esc_html__( 'If theme that you are using has limited sidebar options, try plugins such as', 'xforwoocommerce' ) . ' ' . '<a href="https://wordpress.org/plugins/woosidebars/">WooSidebars</a>, <a href="https://wordpress.org/plugins/custom-sidebars/">Custom Sidebars</a></div></div>',
					'section' => 'integration',
					'id'   => 'widget_notice',
				),

				'analytics' => array(
					'name' => esc_html__( 'Use Analytics', 'xforwoocommerce' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check this option to use filtering analytics', 'xforwoocommerce' ),
					'section' => 'analytics',
					'id'   => 'analytics',
					'default' => 'no',
				),

				'analytics_ui' => array(
					'name' => esc_html__( 'Filtering Analytics', 'xforwoocommerce' ),
					'type' => 'html',
					'desc' => '
					<div class="svx-option-header"><h3>' . esc_html__( 'Filtering Analytics', 'xforwoocommerce' ) . '</h3></div><div class="svx-option-wrapper">' . self::filtering_ananlytics() . '</div>',
					'section' => 'analytics',
					'id'   => 'analytics_ui',
				),

			) );

			return SevenVX()->_do_options( $plugins, self::$plugin['label'] );
		}

		public static function __build_overrides() {
			if ( empty( self::$settings['options']['general']['supported_overrides'] ) ) {
				return array();
			}

			$array = array();

			foreach( self::$settings['options']['general']['supported_overrides'] as $taxonomy ) {

				if ( taxonomy_exists( $taxonomy ) ) {

					$taxonomy = get_taxonomy( $taxonomy );

					$array['_pf_manager_' . $taxonomy->name] = array(
						'name' => $taxonomy->label . ' ' . esc_html__( 'Presets', 'xforwoocommerce' ),
						'type' => 'list',
						'id'   => '_pf_manager_' . $taxonomy->name,
						'desc' => esc_html__( 'Add filter presets for', 'xforwoocommerce' ) . ' ' . $taxonomy->label,
						'section' => 'manager',
						'title' => esc_html__( 'Name', 'xforwoocommerce' ),
						'options' => 'list',
						'default' => '',
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'xforwoocommerce' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter name', 'xforwoocommerce' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Term', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Choose term, that when selected, will show the set filter preset', 'xforwoocommerce' ),
								'options' => 'ajax:taxonomy:' . $taxonomy->name . ':has_none:no_lang',
								'default' => '',
								'class' => 'svx-selectize',
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'xforwoocommerce' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Set filter preset', 'xforwoocommerce' ),
								'options' => 'function:__make_presets:has_none',
								'default' => '',
								'class' => 'svx-selectize',
							),
						),
					);
				}
			}

			return $array;

		}

		public static function filtering_ananlytics() {
			ob_start();

			$stats = get_option( '_prdctfltr_analytics', array() );

			if ( empty( $stats ) ) {
			?>
				<div class="svx-notice svx-info">
				<?php
					esc_html_e( 'Filtering analytics are empty!', 'xforwoocommerce' );
				?>
				</div>
			<?php
			}
			else {
			?>
				<div class="pf-analytics-wrapper">
			<?php
				foreach( $stats as $k => $v ) {
					$show = array();
				?>
					<div class="pf-analytics">
					<?php
						$mode = 'default';
						if ( taxonomy_exists( $k ) ) {
							$mode = 'taxonomy';
							if ( substr( $k, 0, 3 ) == 'pa_' ) {
								$label = wc_attribute_label( $k );
							}
							else {
								if ( $k == 'product_cat' ) {
									$label = esc_html__( 'Categories', 'xforwoocommerce' );
								}
								else if ( $k == 'product_tag' ) {
									$label = esc_html__( 'Tags', 'xforwoocommerce' );
								}
								else if ( $k == 'characteristics' ) {
									$label = esc_html__( 'Characteristics', 'xforwoocommerce' );
								}
								else {
									$curr_term = get_taxonomy( $k );
									$label = $curr_term->name;
								}
							}
						}

						if ( $mode == 'taxonomy' ) {
							if ( !empty( $v ) && is_array( $v ) ) {
								foreach( $v as $vk => $vv ) {
									$term = get_term_by( 'slug', $vk, $k );

									if ( isset( $term->name ) ) {
										$term_name = ucfirst( $term->name ) . ' ( ' . $v[$vk] .' )';
									}
									else {
										$term_name = 'Unknown';
									}

									$show[$term_name] = $v[$vk];
								}
								$title = ucfirst( $label );
							}
						}
						else {
							$title = ucfirst( $k );
						}

					?>
					<div class="pf-analytics-info">
						<strong><?php echo esc_html( $title ); ?></strong>
					</div>
					<div id="<?php echo uniqid( 'pf-analytics-chart-' ); ?>" class="pf-analytics-chart" data-chart="<?php echo esc_attr( json_encode( $show ) ); ?>"></div>
				</div>
			<?php
				}
		?>
			</div>
			<div class="pf-analytics-settings">
				<div class="svx-notice svx-info">
					<?php esc_html_e( 'Click the button to reset filtering analytics.', 'xforwoocommerce' ); ?><br /><br />
					<span id ="pf-analytics-reset" class="svx-button"><?php esc_html_e( 'Reset analytics', 'xforwoocommerce' ); ?></span>
				</div>
			</div>
		<?php
			}
			return ob_get_clean();
		}

		public static function analytics_reset() {
			delete_option( '_prdctfltr_analytics' );

			wp_die(1);
			exit;
		}

		public static function stripslashes_deep( $value ) {
			$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
			return $value;
		}

	}

	XforWC_Product_Filters_Settings::init();

