<?php

	if ( ! defined( 'ABSPATH' ) ) exit;

	class XforWC_Shop_Design_Frontend {

		public static $settings;

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		function __construct() {
			add_filter( 'woocommerce_locate_template', array( &$this, 'add_filter' ), 0, 3 );
			add_filter( 'wc_get_template_part', array( &$this, 'add_filter' ), 0, 3 );

			add_action( 'wp_enqueue_scripts', array( &$this, 'scripts' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'footer_actions' ) );

			add_action( 'wp_ajax_nopriv_pl_add_to_cart', array( &$this, 'add_to_cart' ) );
			add_action( 'wp_ajax_pl_add_to_cart', array( &$this, 'add_to_cart' ) );

			add_filter( 'woocommerce_product_loop_start', array( &$this, 'open_wrap' ), 9999 );
			add_filter( 'woocommerce_product_loop_end', array( &$this, 'close_wrap' ), 0 );

			add_action( 'woocommerce_add_to_cart' , array(&$this, 'repair_cart') );

			add_filter( 'product_loops_have_a_loop', array( &$this, 'fix_loop' ) );

			add_action( 'product_loops_inside_image' , array( &$this, 'quickview' ), 10 );
			add_action( 'wp_ajax_nopriv_wcmnplajax', array( &$this, 'ajax' ) );
			add_action( 'wp_ajax_wcmnplajax', array( &$this, 'ajax' ) );

			add_filter( 'product_loops_image_size', array( &$this, 'image_size' ), 10 );

			add_action( 'product_loops_go', array( &$this, 'make_loops' ), 0 );
			add_action( 'svx_before_solid_wcmn_pl_preset_', array( &$this, 'check_default' ), 0 );

			add_filter( 'mnthemes_add_meta_information_used', array( &$this, 'info' ) );

			$this->out_loop();
			$this->alt_loop();
			$this->default_loop();

		}

		function wfsm_support() {
			do_action( 'wfsm_get_loop_buttons' );
		}

		function ivpa_support() {
			do_action( 'ivpa_get_loop_options' );
		}

		function ivpa_support_single() {
			do_action( 'ivpa_get_single_options' );
		}

		function isb_support() {
			do_action( 'isb_get_loop_badge' );
		}

		function open_wrap( $html ) {
			do_action( 'product_loops_go' );

			if ( isset( self::$settings['loop']['name'] ) ) {
				return $html . '<div class="pl-loops pllp-' . sanitize_title( self::$settings['loop']['name'] ) . '">';
			}
			else {
				return $html;
			}
		}
		function close_wrap( $html ) {
			if ( isset( self::$settings['loop']['name'] ) ) {
				return '</div>' . $html;
			}
			else {
				return $html;
			}
		}

		function fix_loop() {
			return ( isset( self::$settings['fix_loop'] ) ? self::$settings['fix_loop'] : array() );
		}

		function set_size_quickview( $size ) {
			return 'shop_single';
		}

		function return_false( $true ) {
			return false;
		}

		function ajax() {
			if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
				switch( $_POST['data'][0] ) {
					case 'quickview' :
						$this->quickview_ajax();
					break;
					case 'grid_table' :
						$this->_ajax_grid_table();
					break;
					default :
						die(0);
						exit;
					break;
				}
			}
			die(0);
			exit;
		}

		function _ajax_grid_table() {

			if ( !isset( $_POST['data'][1] ) ) {
				die(0);
				exit;
			}

			$mode = ( $_POST['data'][1] == 'table' ? 'table' : 'grid' );

			$_SESSION['wcmnpl_mode'] = $mode;

		}

		function quickview_ajax() {

			if ( !isset( $_POST['data'][1] ) ) {
				die(0);
				exit;
			}

			$id = intval( $_POST['data'][1] );
			if ( isset( $_POST['data'][2] ) ) {
				$check = get_option( '_wcmn_pl_preset_' . substr( $_POST['data'][2], 5 ), array() );
				if ( !empty( $check ) ) {
					self::$settings['fix_loop'] = $check;
				}
			}

			global $post, $post_id, $product, $withcomments;

			$withcomments = true;
			$post_id = $id;

			$post = get_post( $id );

			if ( $post ) {
				setup_postdata( $post );
				$product = wc_get_product( $id );

				do_action( 'product_loops_go' );

				remove_action( 'product_loops_inside_image' , array( &$this, 'quickview' ), 10 );
				add_filter( 'product_loops_image_size' , array( &$this, 'set_size_quickview' ), 10 );
				add_filter( 'product_loops_disable_effect' , array( &$this, 'return_false' ), 10 );

				add_action( 'product_loops_quickview_image', array( &$this, 'wfsm_support' ), 0 );
				add_action( 'product_loops_quickview_image', array( &$this, 'product_image' ), 10 );
				add_action( 'product_loops_quickview_image', array( &$this, 'isb_support' ), 20 );

				add_action( 'product_loops_quickview_summary', array( &$this, 'product_title' ), 10 );
				add_action( 'product_loops_quickview_summary', array( &$this, 'product_content' ), 20 );
				add_action( 'product_loops_quickview_summary', array( &$this, 'product_meta' ), 30 );
				add_action( 'product_loops_quickview_summary' , array( &$this, 'product_attributes' ), 40 );
				add_action( 'product_loops_quickview_summary' , array( &$this, 'product_price' ), 50 );

				add_action( 'product_loops_quickview_summary' , array( &$this, 'product_add_to_cart' ), 60 );

				if ( SevenVXGet()->get_option( 'wcmn_pl_quickview_related', 'product_loops', 'no' ) == 'yes' ) {
					add_action( 'product_loops_quickview_after', array( &$this, 'related' ), 10 );
				}

				ob_start();

				if ( post_password_required() ) {
					echo get_the_password_form();
					return;
				}

				do_action( 'product_loops_quickview_before' );
			?>
				<div id="pl-product-<?php the_ID(); ?>" <?php $this->__post_class( array( 'type-product', 'pl-quickview-product' ) ); ?>>
					<div class="pl-images">
						<?php do_action( 'product_loops_quickview_image' ); ?>
					</div>
					<div class="pl-summary">
						<?php do_action( 'product_loops_quickview_summary' ); ?>
					</div>
					<?php do_action( 'product_loops_quickview_summary_after' ); ?>
				</div>
			<?php
				do_action( 'product_loops_quickview_after' );

				die( '<div class="pl-quickview"><a href="javascript:void(0)" class="pl-quickview-close"><span class="pl-quickview-close-button">' . esc_html__( 'Click to close product quick view!', 'xforwoocommerce' ) . '</span></a><div class="pl-quickview-inner">' . wp_kses_post( ob_get_clean() ) . '</div></div>' );
				exit;
			}

		}

		function related() {

			global $product;

			if ( !$product ) {
				return;
			}

			$loop = SevenVXGet()->get_option( 'wcmn_pl_presets_quickview_related_loop', 'product_loops', '' );
			if ( $loop == '' ) {
				return;
			}

			self::$settings['fix_loop'] = self::get_preset( $loop );

			do_action( 'product_loops_go' );

			$args = array(
				'posts_per_page' => SevenVXGet()->get_option( 'wcmn_pl_presets_quickview_related_limit', 'product_loops', 4 ),
				'columns'        => self::$settings['loop']['column'],
				'orderby'        => 'rand',
				'order'        => 'DESC',
			);

			$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
			$related_products = wc_products_array_orderby( $related_products, $args['orderby'], $args['order'] );

			wc_set_loop_prop( 'name', 'related' );
			wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_related_products_columns', $args['columns'] ) );

			if ( $related_products ) : ?>

				<div class="pl-loops pl-related<?php echo esc_attr( ' pllp-' . sanitize_title( self::$settings['loop']['name'] ) ); ?>">

					<?php foreach ( $related_products as $related_product ) : ?>

						<?php
							$post_object = get_post( $related_product->get_id() );

							setup_postdata( $GLOBALS['post'] =& $post_object );

							wc_get_template_part( 'content', 'product' );
						?>

					<?php endforeach; ?>

				</div>

			<?php endif;

			wp_reset_postdata();
		}

		function open() {
?>
	<div <?php $this->__post_class( array( 'type-product', 'pllp-' . sanitize_title( self::$settings['loop']['name'] ), 'pl-column-' . self::$settings['loop']['column'], 'pl-' . self::$settings['loop']['loop'], 'pl-' . self::$settings['loop']['mode'], 'pl-loop' ) ); ?>>
		<div class="pl-magic">
<?php
		}

		function product_image() {
			if ( ( $visibility = $this->check_visibility( 'img' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'img', array( 'pl-figure-wrapper' ), $visibility ); ?>>
					<?php $this->__post_thumbnail( apply_filters( 'product_loops_image_size', 'shop_catalog' ) ); ?>
				</div>
<?php
			}
		}

		function _meta_sales() {
			global $product;
			$this->_get_meta( esc_html__( 'Sales', 'xforwoocommerce' ), array( get_post_meta( $product->get_id(), 'total_sales', true ) ) );
		}

		function _meta_date() {
			global $product;
			$this->_get_meta( esc_html__( 'Date', 'xforwoocommerce' ), array( get_the_date( false, $product->get_id() ) ) );
		}

		function _meta_category() {
			global $product;
			$this->_get_meta( esc_html__( 'Category', 'xforwoocommerce' ), array( wc_get_product_category_list( $product->get_id(), ', ', '' . '', '', '' ) ) );
		}

		function _meta_tags() {
			global $product;
			$this->_get_meta( esc_html__( 'Tags', 'xforwoocommerce' ), array( wc_get_product_tag_list( $product->get_id(), ', ', '' . '', '', '' ) ) );
		}

		function _meta_sku() {
			global $product;
			$this->_get_meta( esc_html__( 'SKU', 'xforwoocommerce' ), array( $product->get_sku() ) );
		}

		function _get_meta( $name, $data ) {
			if ( ( $visibility = $this->check_visibility_meta( sanitize_title( $name ) ) ) !== false && isset( $data[0] ) && !empty( $data[0] ) ) {
?>
				<span class="pl-product-meta pl-product-meta-<?php echo sanitize_title( $name ); ?><?php echo esc_attr( ( !empty( $visibility ) ? ' ' . implode(' ', $visibility ) : '' ) ); ?>"><?php echo esc_html( $name ); ?><span class="pl-product-meta-separator"></span><?php echo wp_kses_post( implode( ', ', $data ) ); ?></span>
<?php
			}
		}

		function _attributes(){
			global $product;
			$attributes = $product->get_attributes();
			if ( ! $attributes ) {
				return;
			}

			$display_result = '';

			foreach ( $attributes as $attribute ) {

				if ( $attribute->get_variation() ) {
					continue;
				}
				$name = $attribute->get_name();

				if ( ( $visibility = $this->check_visibility_attributes( sanitize_title( $name ) ) ) !== false ) {
					continue;
				}

				if ( $attribute->is_taxonomy() ) {

					$terms = wp_get_post_terms( $product->get_id(), $name, 'all' );

					$cwtax = $terms[0]->taxonomy;

					$cw_object_taxonomy = get_taxonomy($cwtax);

					if ( isset ($cw_object_taxonomy->labels->singular_name) ) {
						$tax_label = $cw_object_taxonomy->labels->singular_name;
					} elseif ( isset( $cw_object_taxonomy->label ) ) {
						$tax_label = $cw_object_taxonomy->label;
						if ( 0 === strpos( $tax_label, 'Product ' ) ) {
							$tax_label = substr( $tax_label, 8 );
						}
					}
					$display_result .= '<span class="pl-product-attribute pl-product-attribute-' . esc_attr( ( $name . ( !empty( $visibility ) ? ' ' . implode(' ', $visibility ) : '' ) ) ) . '">' . esc_html( $tax_label ) . '<span class="pl-product-attribute-separator"></span>';
					$tax_terms = array();
					foreach ( $terms as $term ) {
						$single_term = esc_html( $term->name );
						array_push( $tax_terms, $single_term );
					}
					$display_result .= esc_html( implode(', ', $tax_terms ) ) . '</span>';

				} else {
					$display_result .= esc_html( $name ) . '<span class="pl-product-attribute-separator"></span>';
					$display_result .= esc_html( implode( ', ', $attribute->get_options() ) ) . '</span>';
				}
			}
			echo wp_kses_post( $display_result );
		}

		function product_attributes() {
			if ( ( $visibility = $this->check_visibility( 'atr' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'atr', array( 'pl-meta-wrap' ), $visibility ); ?>>
				<?php
					call_user_func( array( &$this, '_attributes' ) );
				?>
				</div>
<?php

			}
		}
		function product_meta() {
			if ( ( $visibility = $this->check_visibility( 'mta' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'mta', array( 'pl-meta-wrap' ), $visibility ); ?>>
				<?php
					$elements = array(
						'sales' => esc_html__( 'Sales', 'xforwoocommerce' ),
						'date' => esc_html__( 'Date', 'xforwoocommerce' ),
						'category' => esc_html__( 'Category', 'xforwoocommerce' ),
						'tags' => esc_html__( 'Tag', 'xforwoocommerce' ),
						'sku' => esc_html__( 'SKU', 'xforwoocommerce' ),
					);

					foreach( $elements as $k =>$v ) {
						call_user_func( array( &$this, '_meta_' . $k ) );
					}
				?>
				</div>
<?php

			}
		}

		function product_title() {
			if ( ( $visibility = $this->check_visibility( 'ttl' ) ) !== false ) {
?>
				<<?php $this->__title_tag(); ?><?php $this->element_class( 'ttl', array( 'pl-title' ), $visibility ); ?>>
					<?php the_title(); ?>
				</<?php $this->__title_tag(); ?>>
<?php
			}
		}

		function product_content() {
			echo '<div class="pl-excerpt">' . wp_strip_all_tags( strip_shortcodes( get_the_excerpt() ) ) . '</div>';
		}

		function product_excerpt() {
			if ( ( $visibility = $this->check_visibility( 'dsc' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'dsc', array( 'pl-excerpt' ), $visibility ); ?>>
					<?php $this->__get_excerpt(); ?>
				</div>
<?php
			}
		}

		function product_price() {
			if ( ( $visibility = $this->check_visibility( 'prc' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'prc', array( 'pl-price' ), $visibility ); ?>>
					<?php $this->__product_data_price(); ?>
				</div>
<?php
			}
		}
		function product_add_to_cart() {
			if ( ( $visibility = $this->check_visibility( 'add' ) ) !== false ) {
?>
				<div<?php $this->element_class( 'add', array( 'pl-addtocart' ), $visibility ); ?>>
					<?php $this->__product_data_add_to_cart(); ?>
				</div>
<?php
			}
		}

		function quickview() {
			
			if ( ( $visibility = $this->check_visibility( 'qck' ) ) !== false ) {
?>
				<span<?php $this->element_class( 'qck', array( 'pl-quickview-trigger' ), $visibility ); ?> data-id="<?php the_ID(); ?>">
					<?php esc_html_e( 'Quickview', 'xforwoocommerce' ); ?>
				</span>
<?php
			}
		}

		function close() {
?>
		</div>
	</div>
<?php
		}

		function element_class( $type, $classes, $visibility ) {
			if ( count( $classes )>0 || count( $visibility )>0 ) {
				$return = ( is_array( $classes ) ? implode( ' ', $classes ) : $classes ) . ' ' . ( is_array( $visibility ) ? implode( ' ', $visibility ) : $visibility );
				if ( $return !== ' ' ) {
					echo ' class="' . esc_attr( $return ) . '"';
				}
			}
			return false;
		}

		function check_visibility( $type ) {
			$return = array();
			if ( isset( self::$settings['loop'] ) ) {
				if ( isset( self::$settings['loop']['hide_grid'] ) && is_array( self::$settings['loop']['hide_grid'] ) && in_array( $type, self::$settings['loop']['hide_grid'] ) ) {
					$return[] = 'pl-hide-if-grid';
				}
				if ( isset( self::$settings['loop']['hide_table'] ) && is_array( self::$settings['loop']['hide_table'] ) && in_array( $type, self::$settings['loop']['hide_table'] ) ) {
					$return[] = 'pl-hide-if-table';
				}
			}
			return count( $return )>1 ? false : $return;
		}

		function check_visibility_meta( $type ) {
			$return = array();
			if ( isset( self::$settings['loop'] ) ) {
				if ( isset( self::$settings['loop']['meta_grid'] ) && is_array( self::$settings['loop']['meta_grid'] ) && in_array( $type, self::$settings['loop']['meta_grid'] ) ) {
					$return[] = 'pl-hide-if-grid';
				}
				if ( isset( self::$settings['loop']['meta_table'] ) && is_array( self::$settings['loop']['meta_table'] ) && in_array( $type, self::$settings['loop']['meta_table'] ) ) {
					$return[] = 'pl-hide-if-table';
				}
			}

			return count( $return )>1 ? false : $return;
		}

		function check_visibility_attributes( $type ) {
			$return = array();
			if ( isset( self::$settings['loop'] ) ) {
				if ( isset( self::$settings['loop']['attributes_grid'] ) && is_array( self::$settings['loop']['attributes_grid'] ) && in_array( $type, self::$settings['loop']['attributes_grid'] ) ) {
					$return[] = 'pl-hide-if-grid';
				}
				if ( isset( self::$settings['loop']['attributes_table'] ) && is_array( self::$settings['loop']['attributes_table'] ) && in_array( $type, self::$settings['loop']['attributes_table'] ) ) {
					$return[] = 'pl-hide-if-table';
				}
			}

			return count( $return )>1 ? false : $return;
		}

		function __product_link_start() {
			global $product;

			$link = apply_filters( 'product_loops_product_link', get_the_permalink(), $product );

			echo '<a href="' . esc_url( $link ) . '" class="pl-loop-product-link">';
		}

		function __product_link_close() {
			echo '</a>';
		}

		function __title_tag( $tag = 'div' ) {

			if ( in_array( $tag, array( 'div', 'h1','h2','h3','h4','h5','h6' ) ) ) {
				$output = $tag;
			}
			else {
				$output = 'div';
			}
			echo apply_filters( 'product_loops_title', $output, $tag );

		}

		function __get_excerpt() {
			if ( apply_filters( 'product_loops_excerpt_length', 150 ) > 0 ) {
				$excerpt = wp_strip_all_tags( strip_shortcodes( get_the_excerpt() ) );
				$excerpt = implode( ' ', array_slice( explode( ' ', $excerpt ), 0, apply_filters( 'product_loops_excerpt_length', 150 ) ) );
				if ( $excerpt !== '' ) {
					echo esc_html( $excerpt . apply_filters( 'product_loops_excerpt_more', '' ) );
				}
				
			}
		}

		function image_size( $size ) {
			return isset( self::$settings['loop'], self::$settings['loop']['size'] ) ? self::$settings['loop']['size'] : 'shop_catalog' ;
		}

		function __post_thumbnail( $size = 'shop_catalog' ) {

			if ( has_post_thumbnail() ) {
				$thumb_id = get_post_thumbnail_id();
			}

			if ( !isset( $thumb_id ) ) {
				$cache = wp_get_attachment_image_src( get_the_ID(), $size );
				if ( !empty( $cache ) ) {
					$thumb_id = get_the_ID();
				}
			}
			
			if ( isset( $thumb_id ) ) {

				$effect = isset( self::$settings['loop']['effect'] ) ? esc_attr( 'pl-imghvr-' . self::$settings['loop']['effect'] ) : 'pl-imghvr-none';
				$align = isset( self::$settings['loop']['align'] ) ? esc_attr( 'pl-image-' . self::$settings['loop']['align'] ) : 'pl-image-start';
				$size_ratio = isset( self::$settings['loop']['ratio'] ) ? esc_attr( self::$settings['loop']['ratio'] ) : 'none';

				$size = esc_attr( apply_filters( 'product_loops_image_size', $size ) );

				$orientation = '';
				
				if ( !in_array( $size_ratio, array( 'none', 'full', 'large', 'medium' ) ) ) {
					$set = isset( $cache ) ? $cache : wp_get_attachment_image_src( $thumb_id, $size, true );
					$url = $set[0];

					$w = $set[1];
					$h = $set[2];

					$ratio = explode( '-', $size_ratio );

					$x = isset( $ratio[0] ) ? $ratio[0] : 4;
					$y = isset( $ratio[1] ) ? $ratio[1] : 3;

					if ( in_array( $size_ratio, array( '2-1', '1-2', '3-1', '1-3' ) ) ) {
						$orientation = $w/$h > $x/$y ? 'pl-figure-y' : 'pl-figure-x';
					}
					else {
						$orientation = $w/$h > $x/$y ? 'pl-figure-x' : 'pl-figure-y';
					}
				}

				global $product;
				if ( has_post_thumbnail( $product->get_id() ) ) {
					$image = get_the_post_thumbnail( $product->get_id(), $size, array( 'class' => "attachment-$size size-$size pl-product-image" ) );
				} elseif ( ( $parent_id = wp_get_post_parent_id( $product->get_id() ) ) && has_post_thumbnail( $parent_id ) ) {
					$image = get_the_post_thumbnail( $parent_id, $size, array( 'class' => "attachment-$size size-$size pl-product-image" ) );
				} else {
					$image = '';
				}
				$image = apply_filters( 'product_loops_product_get_image', wc_get_relative_url( $image ), $product, $size );
				$image2 = '';

				if ( $effect !== 'pl-imghvr-none' && apply_filters( 'product_loops_disable_effect', true ) ) {
					if ( strpos( $effect, 'overlay' ) === false ) {
						$image_ids = method_exists( $product, 'get_gallery_image_ids' ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
						if ( $image_ids && isset( $image_ids[0] ) ) {
							if ( $effect == 'pl-imghvr-gallery' ) {
								foreach( $image_ids as $v ) {
									$image2 .= '<div class="pl-gallery-img">' . $this->get_the_post_thumbnail( $product, $v, apply_filters( 'product_loops_image_size_thumbnail', 'medium' ), array( 'class' => "attachment-$size size-$size pl-gallery-thumbnail" ) ) . $this->get_the_post_thumbnail( $product, $v, apply_filters( 'product_loops_image_size', 'shop_catalog' ), array( 'class' => "attachment-$size size-$size pl-gallery-img-main" ) ) . '</div>';
								}
							}
							else {
								$image2 .= $this->get_the_post_thumbnail( $product, $image_ids[0], apply_filters( 'product_loops_image_size', 'shop_catalog' ), array( 'class' => "attachment-$size size-$size pl-gallery-img-main" ) );
							}
						}
					}
					else {
						$overlay = true;
						$effect = substr( $effect, 0, -8 );
					}
				}
			?>
				<div class="pl-figure<?php echo in_array( $size_ratio, array( 'none', 'full', 'large', 'medium' ) ) ? '' : esc_attr( ' pl-image-ratio pl-image-ratio-' . $size_ratio ); ?>">
					<div class="pl-figure-in <?php echo esc_attr( $orientation ); ?> <?php echo esc_attr( $align ); ?> <?php echo esc_attr( ( in_array( $effect, array( 'pl-imghvr-none', 'pl-imghvr-gallery') ) ? 'pl-gallery' : $effect ) ); ?>">
					<?php
						echo wp_kses_post( $image );

						if ( $effect !== 'pl-imghvr-none' && apply_filters( 'product_loops_disable_effect', true ) ) {
						?>
							<div class="pl-effect <?php echo esc_attr( $orientation ); ?> <?php echo esc_attr( $align ); ?> <?php echo ( isset( $overlay ) ? 'pl-overlay-effect' : 'pl-image-effect' ); ?>">
								<?php echo wp_kses_post( $image2 ); ?>
							</div>
						<?php
						}
					?>
					</div>
				</div>
				<?php do_action( 'product_loops_inside_image' ); ?>
			<?php
			}

		}

		function get_the_post_thumbnail( $post = null, $post_thumbnail_id, $size = 'post-thumbnail', $attr = '' ) {
			$post = get_post( $post );
			if ( ! $post ) {
				return '';
			}

			$size = apply_filters( 'post_thumbnail_size', $size, $post->ID );
		 
			if ( $post_thumbnail_id ) {
				do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
				if ( in_the_loop() )
					update_post_thumbnail_cache();
				$html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );

				do_action( 'end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
		 
			} else {
				$html = '';
			}

			return apply_filters( 'post_thumbnail_html', $html, $post->ID, $post_thumbnail_id, $size, $attr );
		}

		function __product_data_price() {
			global $product;

			if ( $product->is_type( 'variable' ) ) {
				$prices = array(
					$product->get_variation_regular_price('min'),
					$product->get_variation_regular_price('max'),
					$product->get_variation_sale_price('min'),
					$product->get_variation_sale_price('max')
				);

				$min = min( $prices );
				$max = max( $prices );
				$price = ( $min == $max ? $this->__get_taxed_price( $min ) : $this->__get_taxed_price( $min ) . ' - ' . $this->__get_taxed_price( $max ) );
				$sale_price = '';
			}
			else {
				$price = $product->get_regular_price();
				$sale_price = $product->get_sale_price();
				$price = $price > 0 ? $this->__get_taxed_price( $price ) : 0;
				$sale_price = $sale_price > 0 ? $this->__get_taxed_price( $sale_price ) : 0;
			}

			$price = !empty( $price ) ? '<span class="pl-regular-price">' . strip_tags( $price ) . '</span>' : '';
			$sale_price = !empty( $sale_price ) ? '<span class="pl-sale-price">' . strip_tags( $sale_price ) . '</span>' : '';

			if ( !empty( $price ) || !empty( $sale_price ) ) {
				printf( '%2$s%1$s', $price, $sale_price );
			}
		}

		function __get_taxed_price( $price ) {
			if ( empty( $price ) ) {
				return $price;
			}

			if ( !isset( self::$settings['taxes'] ) ) {
				self::$settings['taxes'] = false;
				if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && !wc_prices_include_tax() ) {
					self::$settings['taxes'] = true;
				}
			}

			if ( self::$settings['taxes'] ) {
				global $product;
				$price = wc_get_price_including_tax( $product, array( 'price' => $price ) );
			}

			return wc_price( $price );
		}

		function __product_data_add_to_cart() {
			global $product;

			$type = method_exists( $product, 'get_type' ) ? $product->get_type() : $product->product_type;

			printf( '<a href="%s" rel="nofollow" data-product_id="%s" data-sku="%s" class="pl-button %s pl-product-type-%s %s">%s</a>',
				esc_url( $product->add_to_cart_url() ),
				absint( $product->get_id() ),
				esc_attr( $product->get_sku() ),
				$product->is_purchasable() && $product->is_in_stock() ? 'pl-add-to-cart' : '',
				esc_attr( $type ),
				esc_attr( self::$settings['loop']['button'] == 'none' ? apply_filters( 'product_loops_button_class', 'button' ) : '' ),
				esc_html( $product->add_to_cart_text() )
			);
		}

		function __post_class( $class = '', $post_id = null ) {
			echo 'class="' . esc_attr( join( ' ', $this->__get_post_class( $class, $post_id ) ) ) . '"';
		}

		function __get_post_class( $class = '', $post_id = null ) {
			$post = get_post( $post_id );

			$classes = array();

			if ( $class ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_map( 'esc_attr', $class );
			}

			if ( ! $post ) {
				return $classes;
			}

			$classes[] = 'pl-post-' . $post->ID;
			$classes[] = 'pl-' . $post->post_type;
			$classes[] = 'pl-type-' . $post->post_type;
			$classes[] = 'pl-status-' . $post->post_status;

			if ( post_type_supports( $post->post_type, 'post-formats' ) ) {
				$post_format = get_post_format( $post->ID );

				if ( $post_format && !is_wp_error($post_format) )
					$classes[] = 'pl-format-' . sanitize_html_class( $post_format );
				else
					$classes[] = 'pl-format-standard';
			}

			if ( post_password_required( $post->ID ) ) {
				$classes[] = 'pl-post-password-required';
			} elseif ( ! is_attachment( $post ) && current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) ) {
				$classes[] = 'pl-has-post-thumbnail';
			}

			if ( is_sticky( $post->ID ) ) {
				if ( is_home() && ! is_paged() ) {
					$classes[] = 'pl-sticky';
				} elseif ( is_admin() ) {
					$classes[] = 'pl-status-sticky';
				}
			}

			$classes[] = 'pl-hentry';

			$taxonomies = get_taxonomies( array( 'public' => true ) );
			foreach ( (array) $taxonomies as $taxonomy ) {
				if ( is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
					foreach ( (array) get_the_terms( $post->ID, $taxonomy ) as $term ) {
						if ( empty( $term->slug ) ) {
							continue;
						}

						$term_class = sanitize_html_class( $term->slug, $term->term_id );
						if ( is_numeric( $term_class ) || ! trim( $term_class, '-' ) ) {
							$term_class = $term->term_id;
						}

						if ( 'post_tag' == $taxonomy ) {
							$classes[] = 'tag-' . $term_class;
						} else {
							$classes[] = sanitize_html_class( $taxonomy . '-' . $term_class, $taxonomy . '-' . $term->term_id );
						}
					}
				}
			}

			$classes = array_map( 'esc_attr', $classes );

			$classes = apply_filters( 'product_loops_post_class', $classes, $class, $post->ID );

			return array_unique( $classes );
		}

		function info( $val ) {
			return array_merge( $val, array( 'Product Loops for WooCommerce' ) );
		}

		function scripts() {
			$enqueued = false;
			$settings = apply_filters( 'product_loops_less_styles', SevenVXGet()->get_option_autoload( 'wcmn_pl_less', array() ) );

			if ( !empty( $settings ) && is_array( $settings ) && isset( $settings ) ) {
				$upload = wp_upload_dir();
				$style = untrailingslashit( $upload['basedir'] ) . '/' . sanitize_file_name( 'product-loops-' . $settings['id'] . '.css' );

				if ( file_exists( $style ) ) {
					$enqueued = true;
					$url = untrailingslashit( $upload['baseurl'] ) . '/' . sanitize_file_name( 'product-loops-' . $settings['id'] . '.css' );
					wp_register_style( 'product-loops-' . $settings['id'], $url, false, $settings['id'] );
					wp_enqueue_style( 'product-loops-' . $settings['id'] );
				}
				else if ( $settings['last_known'] !== '' ) {
					$style_cached = untrailingslashit( $upload['basedir'] ) . '/' . sanitize_file_name( 'product-loops-' . $settings['last_known'] . '.css' );
					$style_cached_url = untrailingslashit( $upload['baseurl'] ) . '/' . sanitize_file_name( 'product-loops-' . $settings['last_known'] . '.css' );
					if ( file_exists( $style_cached ) ) {
						$enqueued = true;
						wp_register_style( 'product-loops-' . $settings['last_known'], $style_cached_url, false, $settings['last_known'] );
						wp_enqueue_style( 'product-loops-' . $settings['last_known']);
					}
				}
				else if ( isset( $settings['url'] ) ) {
					wp_register_style( 'product-loops-precompiled', esc_url( $settings['url'] ), false, '1.0.0' );
					wp_enqueue_style( 'product-loops-precompiled' );
				}
			}

			if ( $enqueued === false ) {
				wp_register_style( 'product-loops', XforWC_Shop_Design()->plugin_url() . '/assets/css/product-loops.css', false, XforWC_Shop_Design()->version() );
				wp_enqueue_style( 'product-loops' );
			}

			wp_register_script( 'product-isotope', XforWC_Shop_Design()->plugin_url() .'/assets/js/isotope.js', array( 'jquery' ), XforWC_Shop_Design()->version(), true );
			wp_enqueue_script( 'product-isotope' );
			wp_register_script( 'product-loops', XforWC_Shop_Design()->plugin_url() .'/assets/js/product-loops.js', array( 'jquery' ), XforWC_Shop_Design()->version(), true );
			wp_enqueue_script( 'product-loops' );
		}

		function add_filter( $template, $slug, $name ) {
			do_action( 'product_loops_go' );

			if ( !empty( self::$settings['loop'] ) && ( $slug == 'content' && $name == 'product' || $slug == 'content-product.php' ) ) {
				switch( self::$settings['loop']['loop'] ) {
					case 'loop-10' :
						$setting = 'loop-out';
					break;
					case 'loop-5' :
					case 'loop-6' :
					case 'loop-7' :
					case 'loop-8' :
					case 'loop-9' :
						$setting = 'loop-alt';
					break;
					default :
						$setting = 'loop';
					break;
				}
				return trailingslashit( XforWC_Shop_Design()->plugin_path() ) . 'templates/' . $setting . '.php';
			}

			return $template;
		}

		function footer_actions() {
			if ( 1==0 ) {
				wp_dequeue_script( 'product-loops' );
			}
			else {
				$vars = array(
					'ajax' => admin_url( 'admin-ajax.php' ),
					'cart' =>  wc_get_cart_url(),
					'checkout' =>  wc_get_checkout_url(),
					'localize' => array(
						'added' => esc_html__( 'Added!', 'xforwoocommerce' )
					),
					'options' => array(
						'isotope' => SevenVXGet()->get_option( 'wcmn_pl_isotope', 'product_loops', 'masonry' ),
						'session' => SevenVXGet()->get_option( 'wcmn_pl_session', 'product_loops', 'no' ),
						'button' => apply_filters( 'product_loops_button_class', 'button' ),
					)
				);

				wp_localize_script( 'product-loops', 'pl', $vars );

				if ( $vars['options']['session'] == 'no' && isset( $_SESSION['wcmnpl_mode'] ) ) {
					unset( $_SESSION['wcmnpl_mode'] );
				}
			}
		}

		function repair_cart() {
			if ( defined( 'DOING_AJAX' ) ) {
				wc_setcookie( 'woocommerce_items_in_cart', 1 );
				wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
				do_action( 'woocommerce_set_cart_cookies', true );
			}
		}

		function utf8_urldecode($str) {
			$str = preg_replace( "/%u([0-9a-f]{3,4})/i","&#x\\1;", urldecode( $str ) );
			return html_entity_decode( $str, null, 'UTF-8' );
		}

		function add_to_cart() {

			if ( !isset( $_POST['product_id'] ) ) {
				die( false );
				exit();
			}

			$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity = isset( $_POST['quantity'] )>1 ? absint( $_POST['quantity'] ) : 1;
			$variation_id = isset( $_POST['variation_id'] ) && !empty( $_POST['variation_id'] ) ? $_POST['variation_id'] : '';

			$variation = array();
			if ( isset( $_POST['variation'] ) && is_array( $_POST['variation' ] ) ) {
				foreach ( $_POST['variation'] as $k => $v ) {
					$variation[$k] = $this->utf8_urldecode($v);
				}
			}

			$product_data = array();
			if ( isset( $_POST['ivpac'] ) ) {
				$ivpac = array();
				parse_str( $_POST['ivpac'], $ivpac );
				foreach ( $ivpac as $k => $v ) {
					if ( substr( $k, 0, 6 ) == 'ivpac_' && !empty( $v ) ) {
						if ( is_array( $v ) ) {
							$v = array_filter( $v );
						}
						if ( !empty( $v ) ) {
							$product_data['ivpac'][substr( $k, 6 )] = is_array( $v ) ? implode( ', ', $v ) : $v;
						}
					}
				}
			}

			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			$id = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $product_data );

			if ( $passed_validation && $id ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}

				$data = WC_AJAX::get_refreshed_fragments();
			}
			else {

					WC_AJAX::json_headers();

					$data = array(
						'error' => true,
						'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
					);

					$data = json_encode( $data );
			}

			wp_die( $data );
			exit();

		}

		public static function get_preset( $preset ) {

			if ( $preset == '' ) {
				return array();
			}

			if ( is_array( $preset ) ) {
				$preset = sanitize_title( $preset['preset'] );
			}

			$process = apply_filters( 'svx_before_solid_wcmn_pl_preset_', get_option( '_wcmn_pl_preset_' . $preset, array() ) );

			if ( isset( $process['name'] ) ) {
				return $process;
			}
			else {
				return array();
			}

		}

		function check_default( $set ) {
			return $this->check_atts( $set, true );
		}

		function check_atts( $atts ) {
			$atts = (array) $atts;
			$defaults = array(
				'name' => esc_html__( 'Loop Name', 'xforwoocommerce' ),
				'loop' => 'loop-1',
				'mode' => 'grid',
				'button' => 'hue',
				'accent_color' => '#007ae5',
				'hover_color' => '#5000e5',
				'size' => 'shop_catalog',
				'ratio' => 'none',
				'align' => 'start',
				'effect' => 'none',
				'column' => '3',
				'text_size' => '3',
				'gap' => '3',
				'excerpt_grid' => 1,
				'hide_grid' => array(),
				'meta_grid' => array(),
				'attributes_grid' => array(),
				'excerpt_table' => 3,
				'hide_table' => array(),
				'meta_table' => array(),
				'attributes_table' => array(),
				'image_width_table' => 15,
			);
			$out = array();
			foreach ( $defaults as $name => $default ) {
				if ( array_key_exists( $name, $atts ) ) {
					$out[$name] = $atts[$name];
				}
				else {
					$out[$name] = $default;
				}

			}
			return $out;
		}

		function default_loop() {
			add_action( 'product_loops_before', array( &$this, 'open' ), 0 );
			add_action( 'product_loops_content', array( &$this, 'wfsm_support' ), 0 );
			add_action( 'product_loops_content', array( &$this, '__product_link_start' ), 0 );
			add_action( 'product_loops_content', array( &$this, 'product_image' ), 10 );
			add_action( 'product_loops_content', array( &$this, 'isb_support' ), 20 );
			add_action( 'product_loops_content', array( &$this, 'product_title' ), 30 );
			add_action( 'product_loops_content', array( &$this, '__product_link_close' ), 40 );
			add_action( 'product_loops_content', array( &$this, 'product_meta' ), 50 );
			add_action( 'product_loops_content', array( &$this, '__product_link_start' ), 60 );
			add_action( 'product_loops_content', array( &$this, 'product_excerpt' ), 70 );
			add_action( 'product_loops_content', array( &$this, 'product_price' ), 80 );
			add_action( 'product_loops_content', array( &$this, '__product_link_close' ), 90 );
			add_action( 'product_loops_content', array( &$this, 'product_attributes' ), 100 );
			add_action( 'product_loops_content', array( &$this, 'ivpa_support' ), 110 );
			add_action( 'product_loops_content', array( &$this, 'product_add_to_cart' ), 120 );
			add_action( 'product_loops_after', array( &$this, 'close' ), 9999 );
		}

		function alt_loop() {
			add_action( 'product_loops_alt_before', array( &$this, 'open' ), 0 );
			add_action( 'product_loops_alt_content', array( &$this, 'wfsm_support' ), 0 );
			add_action( 'product_loops_alt_content', array( &$this, '__product_link_start' ), 0 );
			add_action( 'product_loops_alt_content', array( &$this, 'start_alt' ), 10 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_image' ), 20 );
			add_action( 'product_loops_alt_content', array( &$this, 'isb_support' ), 30 );
			add_action( 'product_loops_alt_content', array( &$this, 'start_alt_inner' ), 40 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_title' ), 50 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_excerpt' ), 60 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_price' ), 70 );
			add_action( 'product_loops_alt_content', array( &$this, 'end_alt' ), 80 );
			add_action( 'product_loops_alt_content', array( &$this, '__product_link_close' ), 90 );
			add_action( 'product_loops_alt_content', array( &$this, 'end_alt' ), 100 );
			add_action( 'product_loops_alt_content', array( &$this, 'start_alt_after' ), 110 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_meta' ), 120 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_attributes' ), 140 );
			add_action( 'product_loops_alt_content', array( &$this, 'ivpa_support' ), 150 );
			add_action( 'product_loops_alt_content', array( &$this, 'product_add_to_cart' ), 160 );
			add_action( 'product_loops_alt_content', array( &$this, 'end_alt' ), 170 );
			add_action( 'product_loops_alt_after', array( &$this, 'close' ), 9999 );
		}

		function out_loop() {
			add_action( 'product_loops_out_before' , array( &$this, 'open' ), 0 );
			add_action( 'product_loops_out_content', array( &$this, 'wfsm_support' ), 0 );
			add_action( 'product_loops_out_content', array( &$this, '__product_link_start' ), 0 );
			add_action( 'product_loops_out_content', array( &$this, 'start_alt' ), 10 );
			add_action( 'product_loops_out_content', array( &$this, 'product_image' ), 20 );
			add_action( 'product_loops_out_content', array( &$this, 'isb_support' ), 30 );
			add_action( 'product_loops_out_content', array( &$this, 'end_alt' ), 100 );
			add_action( 'product_loops_out_content', array( &$this, '__product_link_close' ), 101 );
			add_action( 'product_loops_out_content', array( &$this, 'start_alt_after' ), 110 );
			add_action( 'product_loops_out_content', array( &$this, 'product_title' ), 112 );
			add_action( 'product_loops_out_content', array( &$this, 'product_excerpt' ), 113 );
			add_action( 'product_loops_out_content', array( &$this, 'product_meta' ), 120 );
			add_action( 'product_loops_out_content', array( &$this, 'product_attributes' ), 140 );
			add_action( 'product_loops_out_content', array( &$this, 'ivpa_support' ), 150 );
			add_action( 'product_loops_out_content', array( &$this, 'product_price' ), 153 );
			add_action( 'product_loops_out_content', array( &$this, 'product_add_to_cart' ), 160 );
			add_action( 'product_loops_out_content', array( &$this, 'end_alt' ), 170 );
			add_action( 'product_loops_out_after', array( &$this, 'close' ), 9999 );
		}

		function start_alt() {
		?>
			<div class="pl-alt">
		<?php
		}

		function start_alt_inner() {
		?>
			<div class="pl-alt-inner">
		<?php
		}


		function start_alt_after() {
		?>
			<div class="pl-alt-after">
		<?php
		}


		function end_alt() {
		?>
			</div>
		<?php
		}

		function is_old_post( $id, $days = 5 ) {
			$days = (int) $days;
			$offset = $days*60*60*24;
			if ( get_post_time( 'U', false, $id ) < date( 'U' ) - $offset )
				return true;
			
			return false;
		}


		function make_loops() {
			$loop = ( $in = $this->get_loop() ) && !empty( $in ) ? $this->check_atts( $in ) : array();

			if ( isset( $loop['name'] ) ) {
				self::$settings['loop'] = $loop;
				if ( isset( $_SESSION['wcmnpl_mode'] ) && self::$settings['loop']['mode'] !== $_SESSION['wcmnpl_mode'] ) {
					self::$settings['loop']['mode'] = $_SESSION['wcmnpl_mode'];
				}
			}
			else {
				self::$settings['loop'] = null;
			}

		}

		function get_loop() {

			$loop = apply_filters( 'product_loops_have_a_loop', array() );

			if ( !empty( $loop ) ) {
				return $loop;
			}

			$set = self::get_preset( apply_filters( 'product_loops_default_loop', SevenVXGet()->get_option( 'wcmn_pl_presets_default_loop', 'product_loops', '' ) ) );
			$loop = function_exists( 'wc_get_loop_prop' ) && isset( $GLOBALS['woocommerce_loop']['name'] ) ? $GLOBALS['woocommerce_loop']['name'] : false;

			if ( $loop ) {
				$check = '';
				switch( $loop ){
					case 'cross-sells':
						return self::get_preset( SevenVXGet()->get_option( 'wcmn_pl_presets_cross_sells_loop', 'product_loops', '' ) );
					break;
					case 'up-sells':
						return self::get_preset( SevenVXGet()->get_option( 'wcmn_pl_presets_upsells_loop', 'product_loops', '' ) );
					break;
					case 'related':
						return self::get_preset( SevenVXGet()->get_option( 'wcmn_pl_presets_related_loop', 'product_loops', '' ) );
					break;
					default:
					break;
				}
			}

			if ( !isset( self::$settings['overrides'] ) ) {
				self::$settings['overrides'] = SevenVXGet()->get_option( 'wcmn_pl_overrides', 'product_loops', array() );
			}

			if ( empty( self::$settings['overrides'] ) ) {
				return $set;
			}

			$over = self::$settings['overrides'];

			global $product;

			if ( empty( $product ) ) {
				return $set;
			}

			if ( isset( $over['outofstock'] ) && $over['outofstock'] !== '' ) {
				if ( $product->is_in_stock() === false ) {
					return self::get_preset( $over['outofstock'] );
				}
			}
			
			if ( isset( $over['sale'] ) && $over['sale'] !== '' ) {
				if ( $product->get_price() > 0 && $product->is_on_sale() ) {
					return self::get_preset( $over['sale'] );
				}
			}

			if ( isset( $over['featured'] ) && $over['featured'] !== '' ) {
				if ( XforWC_Shop_Design::version_check() === true ) {
					if ( has_term( 'featured', 'product_visibility', get_the_ID() ) ) {
						return self::get_preset( $over['featured'] );
					}
				}
				else {
					if ( get_post_meta( get_the_ID(), '_featured', true ) === 'yes' ) {
						return self::get_preset( $over['featured'] );
					}
				}
			}

			if ( isset( $over['new']['days'] ) && isset( $over['new']['preset'] ) && $over['new']['preset'] !== ''  ) {
				if ( !$this->is_old_post( get_the_ID(), $over['new']['days'] ) ) {
					return self::get_preset( $over['new']['preset'] );
				}
			}

			if ( isset( $over['product_tag'] ) && is_array( $over['product_tag'] ) ) {
				foreach( $over['product_tag'] as $k => $v ) {
					$v = is_array( $v ) ? $v : array( 'term' => $k, 'preset' => $v );
					if ( has_term( $v['term'], 'product_tag', get_the_ID() ) ) {
						return self::get_preset( $v['preset'] );
					}
				}
			}

			if ( isset( $over['product_cat'] ) && is_array( $over['product_cat'] ) ) {

				$term_ids = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

				if ( $term_ids && !is_wp_error( $term_ids ) ) {
					$term_parents = get_ancestors( $term_ids[0], 'product_cat' );

					$checks = array( $term_ids[0] );
					if ( !empty( $term_parents ) ) {
						$checks = array_merge( $checks, $term_parents );
					}

					foreach( $checks as $check ) {
						if ( array_key_exists( $check, $over['product_cat'] ) ) {
							return self::get_preset( $over['product_cat'][$check] );
						}
					}
				}
			}

			return $set;

		}

	}

	add_action( 'init', array( 'XforWC_Shop_Design_Frontend', 'init' ) );

	if ( !function_exists( 'mnthemes_add_meta_information' ) ) {
		function mnthemes_add_meta_information_action() {
			echo '<meta name="generator" content="' . esc_attr( implode( ', ', apply_filters( 'mnthemes_add_meta_information_used', array() ) ) ) . '"/>';
		}
		function mnthemes_add_meta_information() {
			add_action( 'wp_head', 'mnthemes_add_meta_information_action', 99 );
		}
		mnthemes_add_meta_information();
	}

?>