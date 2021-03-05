<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION


add_filter( 'woocommerce_single_product_carousel_options', 'sf_update_woo_flexslider_options' );


//Agregar flechas de navegación en la imagen de producto
function sf_update_woo_flexslider_options( $options ) {

    $options['directionNav'] = true;

    return $options;
}




add_filter( 'woocommerce_variable_price_html', 'variation_price_format', 10, 2 );
add_action( 'woocommerce_after_shop_loop_item_title', 'dcms_show_description_item_product', 10, 0 );

function dcms_show_description_item_product() { 
	global $product;
	$chars_quantity = 500; //cantidad de caracteres a mostrar
	
	//Obtenemos la información del producto
	$product_details = $product->get_data();
	$short_description = $product_details['short_description'];

	//limpieza
	$short_description = strip_shortcodes($short_description);
	$short_description = wp_strip_all_tags($short_description);

	//recorte caracteres
	//$short_description = substr($short_description, 0, $chars_quantity);
	//$short_description = substr($short_description, 0, strripos($short_description, ' '));

	//mostrar descripción
	echo "<div class='dcms-item-description'>".$short_description."</div>";
}
function remove_add_to_cart_button($product)
{  
     remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
     remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	 
}
add_action('init','remove_add_to_cart_button');
 
function variation_price_format( $price, $product ) {
 
// 1. Get min/max regular and sale variation prices
 
$min_var_reg_price = $product->get_variation_regular_price( 'min', true );
$min_var_sale_price = $product->get_variation_sale_price( 'min', true );
$max_var_reg_price = $product->get_variation_regular_price( 'max', true );
$max_var_sale_price = $product->get_variation_sale_price( 'max', true );
 
// 2. New $price, unless all variations have exact same prices
 
if ( ! ( $min_var_reg_price == $max_var_reg_price && $min_var_sale_price == $max_var_sale_price ) ) {   
   if ( $min_var_sale_price < $min_var_reg_price ) {
      $price = sprintf( __( '<span class="desde">Desde:</span> <del>%1$s</del><ins>%2$s</ins>', 'woocommerce' ), wc_price( $min_var_reg_price ), wc_price( $min_var_sale_price ) );
   } else {
      $price = sprintf( __( '<span class="desde">Desde:</span> %1$s', 'woocommerce' ), wc_price( $min_var_reg_price ) );
   }
}
 
// 3. Return $price
 
return $price;
}

// Cambiar h2 por h3
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'changeProductsTitle', 10 );
function changeProductsTitle() {
echo '<h3 class="woocommerce-loop-product_title">' . get_the_title() . '</h3>';
}



add_action( 'wp_footer', 'cart_update_qty_script' );
function cart_update_qty_script() {
  if (is_cart()) :
   ?>
    <script>
    	jQuery(window).on('load', function(){
    		jQuery("[name='update_cart']").removeAttr('disabled');
    	});
    	jQuery( document.body ).on( 'updated_cart_totals', function(){
		    jQuery("[name='update_cart']").removeAttr('disabled');
		});
		jQuery('div.woocommerce').on('change', '.qty', function(){
           jQuery("[name='update_cart']").trigger("click"); 
        });
   </script>
<?php
endif;
}


add_action('wp_ajax_cart_count', 'custom_cart_count');
add_action('wp_ajax_nopriv_cart_count', 'custom_cart_count');
function custom_cart_count() {
    echo WC()->cart->cart_contents_count;
    wp_die();
}

add_action('wp_enqueue_scripts', 'custom_scripts');
function custom_scripts()
{
    wc_enqueue_js( "
        $.ajax({
            url: '".admin_url('admin-ajax.php')."',
            data: {
                'action': 'cart_count'
            },
            success: function(resp) {
                $('.elementor-menu-cart__product-remove>a').text(resp);
            }
        });
    ");
}

add_action( 'init', function() {

	add_shortcode( 'site_url', function( $atts = null, $content = null ) {
		return site_url();
	} );

} );

add_shortcode('bloginfo', function($atts) {

   $atts = shortcode_atts(array('filter'=>'', 'info'=>''), $atts, 'bloginfo');

   $infos = array(
     'name', 'description',
     'wpurl', 'url', 'pingback_url',
     'admin_email', 'charset', 'version', 'html_type', 'language',
     'atom_url', 'rdf_url','rss_url', 'rss2_url',
     'comments_atom_url', 'comments_rss2_url',
   );

   $filter = in_array(strtolower($atts['filter']), array('raw', 'display'), true)
     ? strtolower($atts['filter'])
     : 'display';

   return in_array($atts['info'], $infos, true) ? get_bloginfo($atts['info'], $filter) : '';
});

