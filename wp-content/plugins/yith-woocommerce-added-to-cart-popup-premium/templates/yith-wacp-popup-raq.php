<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 */
/**
 * Popup raq template
 *
 * @version 1.3.0
 */

if ( ! defined( 'YITH_WACP' ) ) {
	exit; // Exit if accessed directly
}

?>

<h3 class="raq-list-title"><?php echo apply_filters( 'yith_wacp_raq_popup_title', __( 'Your Quote', 'yith-woocommerce-added-to-cart-popup' ) ); ?></h3>

<?php echo do_shortcode('[yith_ywraq_request_quote]');