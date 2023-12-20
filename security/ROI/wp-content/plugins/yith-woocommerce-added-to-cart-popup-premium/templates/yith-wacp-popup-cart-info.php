<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
/**
 * Popup cart info template
 *
 * @version 1.2.1
 */

if( !defined( 'YITH_WACP' ) ) {
    exit;
}

?>

<div class="cart-info">
    <?php if( $cart_shipping && isset( $cart_info[ 'shipping' ] ) ) : ?>
        <div class="cart-shipping">
            <?php echo __( 'Shipping Cost', 'yith-woocommerce-added-to-cart-popup' ) . ':' ?>
            <span class="shipping-cost">
                <?php echo $cart_info[ 'shipping' ]; ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if( $cart_tax && isset( $cart_info[ 'tax' ] ) ) : ?>
        <div class="cart-tax">
            <?php echo __( 'Tax Amount', 'yith-woocommerce-added-to-cart-popup' ) . ':' ?>
            <span class="tax-cost">
                <?php echo $cart_info[ 'tax' ]; ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if( $cart_total && isset( $cart_info[ 'total' ] ) ) : ?>
        <?php if( !empty( $cart_info[ 'discount' ] ) ) : ?>
            <div class="cart-discount">
                <?php echo __( 'Discount', 'yith-woocommerce-added-to-cart-popup' ) . ':' ?>
                <span class="discount-cost">
                    <?php echo $cart_info[ 'discount' ]; ?>
                </span>
            </div>
        <?php endif; ?>
        <div class="cart-totals">
            <?php echo apply_filters( 'yith_wacp_cart_total_label',__( 'Cart Total', 'yith-woocommerce-added-to-cart-popup' )) . ':' ?>
            <span class="cart-cost">
                <?php echo $cart_info[ 'total' ]; ?>
            </span>
        </div>
    <?php endif; ?>
</div>