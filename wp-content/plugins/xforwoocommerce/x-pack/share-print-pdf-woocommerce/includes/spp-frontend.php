<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XforWC_PDF_Print_Share_Frontend {

	public static $version;
	public static $id;
	public static $dir;
	public static $path;
	public static $url_path;
	public static $settings;

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {
		self::$version = Wcmnspp()->version();

		self::$dir = trailingslashit( Wcmnspp()->plugin_path() );
		self::$path = trailingslashit( Wcmnspp()->plugin_path() );
		self::$url_path = trailingslashit( Wcmnspp()->plugin_url() );

		$enable = SevenVXGet()->get_option_autoload( 'wc_settings_spp_enable', 'override' );

		if ( $enable == 'override' ) {
			add_filter( 'wc_get_template_part', 'XforWC_PDF_Print_Share_Frontend::add_filter', 10, 3 );
			add_filter( 'woocommerce_locate_template', 'XforWC_PDF_Print_Share_Frontend::add_loop_filter', 10, 3 );
		}
		else {
			$action = SevenVXGet()->get_option_autoload( 'wc_settings_spp_action', 'woocommerce_single_product_summary:60' );
			if ( !empty( $action ) ) {
				$action = explode( ':', $action );
				$priority = isset( $action[1] ) ? floatval( $action[1] ) : 10;

				add_filter( $action[0], __CLASS__ . '::get_shares' , $priority );
			}
		}

		add_action( 'wp_enqueue_scripts', __CLASS__ . '::scripts' );
		add_action( 'init', __CLASS__ . '::setup_shares', 999 );

		add_action( 'wp_ajax_nopriv_wcspp_quickview', __CLASS__ . '::wcspp_quickview' );
		add_action( 'wp_ajax_wcspp_quickview', __CLASS__ . '::wcspp_quickview' );

		add_shortcode( 'shareprintpdf', __CLASS__ . '::shortcode' );

		add_action( 'wp_footer', __CLASS__ . '::check_scripts' );
		add_filter( 'wc_shareprintpdf_title', __CLASS__ . '::maybe_title' );

		add_filter( 'mnthemes_add_meta_information_used', __CLASS__ . '::sppdf_info' );

	}

	public static function sppdf_info( $val ) {
		return array_merge( $val, array( 'Share, Print and PDF for WooCommerce' ) );
	}

	public static function scripts() {

		wp_enqueue_style( 'wcspp', self::$url_path .'includes/css/style' . ( is_rtl() ? '-rtl' : '' ) . '.css', false, self::$version );
		//wp_enqueue_style( 'wcspp', self::$url_path .'includes/css/style' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, self::$version );

		wp_register_script( 'wcspp', self::$url_path .'includes/js/scripts.js', array( 'jquery' ), self::$version, true );
		wp_enqueue_script( 'wcspp' );

	}

	public static function maybe_title() {
		$title = SevenVXGet()->get_option( 'wc_settings_spp_title', 'share_print_pdf' );
		if ( !empty( $title ) ) {
			return wp_kses_post( stripslashes( $title ) );
		}
	}

	public static function check_scripts() {

		if ( !isset( self::$settings['init'] ) && SevenVXGet()->get_option_autoload( 'wc_settings_spp_force_scripts', 'no' ) == 'no' ) {
			wp_dequeue_script( 'wcspp' );
		}
		else if ( wp_script_is( 'wcspp', 'enqueued' ) ) {
			$args = array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'url' => self::$url_path,
				'rtl' => is_rtl() ? 'yes' : 'no',
				'product_url' => get_the_permalink(),
				'pdfmake' => self::$url_path .'includes/js/pdfmake.min.js',
				'pdffont' => self::$url_path .'includes/js/vfs_fonts.js',
				'pagesize' => apply_filters( 'wcmn_spp_pagesize', SevenVXGet()->get_option( 'wc_settings_spp_pagesize', 'share_print_pdf', 'letter' ) ),
				'localization' => array(
					'desc' => esc_html__( 'Product Description', 'xforwoocommerce' ),
					'info' => esc_html__( 'Product Information', 'xforwoocommerce' )
				)
			);

			wp_localize_script( 'wcspp', 'wcspp', $args );
		}

	}

	public static function add_filter( $template, $slug, $name ) {

		if ( in_array( $slug, array( 'single-product/share.php' ) ) ) {

			if ( $name ) {
				$path = self::$path . WC()->template_path() . "{$slug}-{$name}.php";
			} else {
				$path = self::$path . WC()->template_path() . "{$slug}.php";
			}

			return file_exists( $path ) ? $path : $template;

		}
		else {
			return $template;
		}

	}

	public static function add_loop_filter( $template, $template_name, $template_path ) {

		if ( in_array( $template_name, array( 'single-product/share.php' ) ) ) {

			$path = self::$path . $template_path . $template_name;

			return file_exists( $path ) ? $path : $template;

		}
		else {
			return $template;
		}

	}

	public static function get_shares() {

		include( self::$dir . 'woocommerce/single-product/share.php' );

	}

	public static function setup_shares() {

		$shares = array(
			'facebook',
			'twitter',
			'pin',
			'linked',
			'print',
			'pdf',
			'email',
		);

		$disallowed = SevenVXGet()->get_option( 'wc_settings_spp_shares', 'share_print_pdf', array() );

		if ( !is_array( $disallowed ) ) {
			$disallowed = array();
		}

		$priority = 5;

		foreach( $shares as $share ) {

			if ( in_array( $share, $disallowed ) ) {
				continue;
			}

			switch( $share ) {
				case 'facebook' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_facebook', $priority );
				break;
				case 'twitter' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_twitter', $priority );
				break;
				case 'pin' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_pin', $priority );
				break;
				case 'linked' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_linked', $priority );
				break;
				case 'print' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_print', $priority );
				break;
				case 'pdf' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_pdf', $priority );
				break;
				case 'email' :
					add_action( 'shareprintpdf_icons', __CLASS__ . '::get_icon_email', $priority );
				break;
				default :
				break;
			}
			

			$priority = $priority + 5;

		}

	}

	public static function get_icon_facebook() {

		$id = get_the_ID();
		$link = get_the_permalink( $id );
		$title = get_the_title( $id );

		$url = 'http://www.facebook.com/sharer.php?u=' . $link;

		$share = array(
			'type' => 'facebook',
			'url' => $url,
			'title' => esc_html__( 'Share on Facebook', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_twitter() {

		$id = get_the_ID();
		$link = get_the_permalink( $id );
		$title = get_the_title( $id );

		$url = 'http://twitter.com/home/?status=' . $title . ' - ' . wp_get_shortlink( $id );

		$share = array(
			'type' => 'twitter',
			'url' => $url,
			'title' => esc_html__( 'Share on Twitter', 'xforwoocommerce' ),
			'class' => 'wcspp-nocounts'
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_pin() {

		$id = get_the_ID();
		$link = get_the_permalink( $id );
		$title = get_the_title( $id );
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'large');
		$image = $large_image_url[0];

		$url = 'http://pinterest.com/pin/create/button/?url=' . $link . '&media=' . $image .'&description=' . $title;

		$share = array(
			'type' => 'pin',
			'url' => $url,
			'title' => esc_html__( 'Share on Pinterest', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_linked() {

		$id = get_the_ID();
		$link = get_the_permalink( $id );
		$title = get_the_title( $id );

		$url = 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title .'&amp;source=' . home_url( '/' );

		$share = array(
			'type' => 'linked',
			'url' => $url,
			'title' => esc_html__( 'Share on LinkedIn', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_print() {

		$share = array(
			'type' => 'print',
			'url' => '#',
			'title' => esc_html__( 'Print product', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_pdf() {

		$share = array(
			'type' => 'pdf',
			'url' => '#',
			'title' => esc_html__( 'Download PDF', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function get_icon_email() {

		$id = get_the_ID();
		$link = get_the_permalink( $id );
		$title = get_the_title( $id );
		$prepare = esc_html__( 'Enter email to share', 'xforwoocommerce' ) . '?subject=' . esc_html__( 'Hey! Check ', 'xforwoocommerce' ) . $title . '&body=' . esc_html__( 'Thought you might be interested in this', '' ) . ' ' . $title . '.' . esc_html__( 'Check this link link for more info', 'xforwoocommerce' ) . ' ' . $link;

		$share = array(
			'type' => 'email',
			'url' => 'mailto:' . esc_attr( $prepare ),
			'title' => esc_html__( 'Email to a friend', 'xforwoocommerce' ),
			'class' => ''
		);

		self::wrap_icon( $share );
	}

	public static function wrap_icon( $share ) {
?>
		<li class="<?php echo esc_attr( 'wcspp-' . $share['type'] ); ?>">
			<a href="<?php echo esc_url( $share['url'] ); ?>" class="<?php echo esc_attr( $share['class'] ); ?>" title="<?php echo esc_attr( $share['title'] ); //OK ?>"<?php echo !in_array( $share['type'], array( 'print', 'pdf', 'email' ) ) ? ' target="_blank"' : '' ; ?>>
			</a>
		</li>
<?php
	}

	public static function wcspp_quickview() {

		if ( isset( $_POST['product_id'] ) ) {

			$id = $_POST['product_id'];
			$type = $_POST['type'];

			global $product;

			$product = wc_get_product( $id );

			ob_start();
?>
			<div class="wcspp-quickview">
			<?php

				$cats = function_exists( 'wc_get_product_category_list' ) ? strip_tags( wc_get_product_category_list( $id, ', ', '', '' ) ) : strip_tags( $product->get_categories( ', ', '', '' ) );
				$tags = function_exists( 'wc_get_product_tag_list' ) ? strip_tags( wc_get_product_tag_list( $id, ', ', '', '' ) ) : strip_tags( $product->get_tags( ', ', '', '' ) );

				$site_title = get_bloginfo( 'name' );
				$site_desc = get_bloginfo( 'description' );

				$product_title = get_the_title( $id );
				$product_price = wc_price( $product->get_price() );

				$product_sku = $product->get_formatted_name();
				$product_link = get_the_permalink( $id );

				$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'shop_catalog' );

				$product_content = get_post_field( 'post_content', $id );
				$product_description = get_post_field( 'post_excerpt', $id );

				if ( function_exists( 'wc_format_dimensions' ) ) {
					$filter = array();
					if ( !empty( $product->get_length() ) && !empty( $product->get_length() ) && !empty( $product->get_length() ) ) {
						$dimensions = array(
							'length' => $product->get_length(),
							'width'  => $product->get_width(),
							'height' => $product->get_height()
						);
						$filter = array_filter( $dimensions );
					}
					$product_dimensions = !empty( $filter ) ? wc_format_dimensions( $dimensions ) : '';
				}
				else {
					$product_dimensions = !empty( $product->get_dimensions() ) ? $product->get_dimensions() : '';
				}

				$product_weight = $product->get_weight() !== '' ? $product->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ) : '';

				$attachment_ids = method_exists( $product, 'get_gallery_image_ids' ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
				$img = array( '', '', '', '' );
				$i = 0;
				foreach ( $attachment_ids as $attachment_id ) {
					$image = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );

					if ( !$image ) {
						continue;
					}
					$img[$i] = $image[0];

					if ( $i == 3 ) {
						break;
					}
					$i++;
				}

				$attributes = $product->get_attributes();
				$attribute_echo = '';
				$i=0;
				if ( !empty( $attributes ) ) {
					foreach( $attributes as $attribute ) {
						if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
							continue;
						}

						if ( $i !== 0 ) {
							$attribute_echo .= '
';
						}
						$attribute_echo .= wc_attribute_label( $attribute['name'] ) . ': ';
						if ( $attribute['is_taxonomy'] ) {
							$values = wc_get_product_terms( $id, $attribute['name'], array( 'fields' => 'names' ) );
							$attribute_echo .= apply_filters( 'woocommerce_attribute', implode( ', ', $values ), $attribute, $values );
						} else {
							$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
							$attribute_echo .= apply_filters( 'woocommerce_attribute', implode( ', ', $values ), $attribute, $values );
						}
						$i++;
					}
				}

				$logo = SevenVXGet()->get_option( 'wc_settings_spp_logo', 'share_print_pdf', '' );

				$header_after = strip_shortcodes( SevenVXGet()->get_option( 'wc_settings_spp_header_after', 'share_print_pdf', '' ) );
				$product_before = strip_shortcodes( SevenVXGet()->get_option( 'wc_settings_spp_product_before', 'share_print_pdf', '' ) );
				$product_after = strip_shortcodes( SevenVXGet()->get_option( 'wc_settings_spp_product_after', 'share_print_pdf', '' ) );

				if ( $type == 'pdf' ) {

					$pdf_product_image = '';
					if ( isset( $product_image[0] ) && $product_image[0] !== '' ) {
						$pdf_product_image = $product_image[0];
					}

					$pdf_vars = array(
						'site_logo' => esc_url( $logo ),
						'site_title' => esc_html( $site_title ),
						'site_description' => esc_html( $site_desc ),
						'product_title' => esc_html( $product_title ),
						'product_price' => esc_html( strip_tags( $product_price ) ),
						'product_meta' => esc_html__( 'SKU', 'xforwoocommerce' ) . ': ' . esc_html( $product_sku ),
						'product_link' => esc_html__( 'Link', 'xforwoocommerce' ) . ': ' . esc_url( $product_link ),
						'product_categories' => ( !empty( $cats ) ? esc_html__( 'Categories', 'xforwoocommerce' ) . ': '. esc_html( $cats ) . '' : '' ),
						'product_tags' => ( !empty( $tags ) ? esc_html__( 'Tags', 'xforwoocommerce' ) . ': '. esc_html( $tags ) . '' : '' ),
						'product_image' => esc_url( $pdf_product_image ),
						'product_description' => wpautop( strip_tags( strip_shortcodes( $product_description ), '<a><ul><ol><li><p><div><img><u><i><em><b><strong><table><tbody><tr><th><td><pre><blockquote><hr><span><h1><h2><h3><h4><h5><h6>' ) ),
						'product_attributes' => esc_html( $attribute_echo ),
						'product_dimensions' => $product_dimensions !== '' ? esc_html__( 'Dimensions', 'xforwoocommerce' ) . ': ' . esc_html( $product_dimensions ) : '',
						'product_weight' => $product_weight !== '' ? esc_html__( 'Weight', 'xforwoocommerce' ) . ': ' . esc_html( $product_weight ): '',
						'product_img0' => esc_url( $img[0] ),
						'product_img1' => esc_url( $img[1] ),
						'product_img2' => esc_url( $img[2] ),
						'product_img3' => esc_url( $img[3] ),
						'product_content' => wpautop( strip_tags( strip_shortcodes( $product_content ), '<a><ul><ol><li><p><div><img><u><i><em><b><strong><table><tbody><tr><th><td><pre><blockquote><hr><span><h1><h2><h3><h4><h5><h6>' ) ),
						'header_after' => esc_html( strip_shortcodes( $header_after ) ),
						'product_before' => esc_html( strip_shortcodes( $product_before ) ),
						'product_after' => esc_html( strip_shortcodes( $product_after ) ),
					);

					$pdf = ' data-wcspp-pdf="' . esc_attr( json_encode( $pdf_vars ) ) . '"';
				}
			?>
				<div class="wcspp-wrapper">
					<div class="wcspp-page-wrap" <?php echo isset( $pdf ) ? $pdf : ''; ?>>
						<?php
							if ( $logo !== '' ) {
								echo '<img src="' . esc_url( $logo ) . '" class="wcspp-logo" />';
							}
						?>
						<span class="wcspp-product-title"><?php echo esc_html( $site_title ); ?></span>
						<span class="wcspp-product-desc"><?php echo esc_html( $site_desc ); ?></span>
						<?php
							if ( $header_after !== '' ) {
								echo '<div class="wcspp-add">' . esc_html( strip_shortcodes( $header_after ) ) . '</div>';
							}
						?>
						<hr/>
						<?php
							if ( $product_before !== '' ) {
								echo '<div class="wcspp-add">' . esc_html( strip_shortcodes( $product_before ) ) . '</div>';
							}
						?>
						<h1>
							<span class="wcspp-title"><?php echo wp_kses_post( $product_title ); ?></span>
							<span class="wcspp-price"><?php echo wp_kses_post( $product_price ); ?></span>
						</h1>
						<div class="wcspp-meta">
							<p>
							<?php
								echo esc_html__( 'SKU', 'xforwoocommerce' ) . ': ' . esc_html( $product_sku ) . '<br/>';
								echo esc_html__( 'Link', 'xforwoocommerce' ) . ': ' . esc_url( $product_link ) . '<br/>';
							?>
							</p>
						</div>
						<div class="wcspp-main-image">
<?php
						if ( !empty( $product->get_image_id() ) ) {
							echo wp_kses_post( $product->get_image( 'shop_catalog' ) );
						}
?>
						</div>
						<div class="wcspp-images">
						<?php
							foreach ( $attachment_ids as $attachment_id ) {
								$image = wp_get_attachment_image( $attachment_id, 'shop_thumbnail' );

								if ( !$image ) {
									continue;
								}

								echo wp_kses_post( $image );
							}
						?>
						</div>
						<div class="wcspp-description">
							<h2><?php esc_html_e( 'Product Information', 'xforwoocommerce' ); ?></h2>
							<hr/>
							<div class="wcspp-block-wrap">
						<?php
							if ( !empty( $cats ) ) {
								echo '<strong class="wcspp-block">' . esc_html__( 'Category', 'xforwoocommerce' ) . ': '. $cats . '</strong>';
							}
							if ( !empty( $tags ) ) {
								echo '<strong class="wcspp-block">' . esc_html__( 'Tags', 'xforwoocommerce' ) . ': ' . $tags . '</strong>';
							}
							if ( !empty( $attributes ) ) {
								foreach( $attributes as $attribute ) {
									if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
										continue;
									}

									echo '<strong class="wcspp-block">';
										echo wc_attribute_label( $attribute['name'] ) . ': ';
										if ( $attribute['is_taxonomy'] ) {
											$values = wc_get_product_terms( $id, $attribute['name'], array( 'fields' => 'names' ) );
											echo apply_filters( 'woocommerce_attribute', implode( ', ', $values ), $attribute, $values );
										} else {
											$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
											echo apply_filters( 'woocommerce_attribute', implode( ', ', $values ), $attribute, $values );
										}
									echo '</strong>';
								}
							}
							if ( $product_dimensions !== '' ) {
								echo '<strong class="wcspp-block">' . esc_html__( 'Dimensions', 'xforwoocommerce' ) . ': ' . esc_html( $product_dimensions ) . '</strong>';
							}
							if ( $product_weight !== '' ) {
								echo '<strong class="wcspp-block">' . esc_html__( 'Weight', 'xforwoocommerce' ) . ': ' . esc_html( $product_weight ) . '</strong>';
							}
						?>
							</div>
							<div class="wcspp-content-short">
								<?php echo wpautop( strip_tags( strip_shortcodes( $product_description ), '<a><ul><ol><li><p><div><img><u><i><em><b><strong><table><tbody><tr><th><td><pre><blockquote><hr><span><h1><h2><h3><h4><h5><h6>' ) ); ?>
							</div>
						</div>
						<div class="wcspp-content">
							<h2><?php esc_html_e( 'Product Description', 'xforwoocommerce' ); ?></h2>
							<hr/>
							<?php echo wpautop( strip_tags( strip_shortcodes( $product_content ), '<a><ul><ol><li><p><div><img><u><i><em><b><strong><table><tbody><tr><th><td><pre><blockquote><hr><span><h1><h2><h3><h4><h5><h6>' ) ); ?>
						</div>
						<?php
							if ( $product_after !== '' ) {
								echo '<div class="wcspp-add">' . esc_html( strip_shortcodes( $product_after ) ) . '</div>';
							}
						?>
					</div>
					<div class="wcspp-quick-nav">
						<a href="javascript:void(0)" class="wcspp-go-back">&larr; <?php esc_html_e( 'Back', 'xforwoocommerce' ); ?></a>
						<a href="javascript:void(0)" class="wcspp-go-<?php echo esc_attr( $type ); ?>" <?php echo 'title="' . ( $type == 'pdf' ? esc_html__( 'Download PDF', 'xforwoocommerce' ) : esc_html__( 'Print products', 'xforwoocommerce' ) ) .'"'; ?>></a>
					</div>
				</div>
				<a href="javascript:void(0)" class="wcspp-quickview-close"></a>
			</div>
<?php
			$out = ob_get_clean();

			die( $out );
			exit;
		}
		die(0);
		exit;
	}

	public static function shortcode( $atts, $content = null ) {

		global $post;

		if ( $post->post_type == 'product') {
			ob_start();
			self::get_shares();
			return ob_get_clean();
		}

		return;

	}

}

add_action( 'init', array( 'XforWC_PDF_Print_Share_Frontend', 'instance' ), 998 );

if ( !function_exists( 'mnthemes_add_meta_information' ) ) {
	function mnthemes_add_meta_information_action() {
		echo '<meta name="generator" content="' . esc_attr( implode( ', ', apply_filters( 'mnthemes_add_meta_information_used', array() ) ) ) . '"/>';
	}
	function mnthemes_add_meta_information() {
		add_action( 'wp_head', 'mnthemes_add_meta_information_action', 99 );
	}
	mnthemes_add_meta_information();
}
