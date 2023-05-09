<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


if ( ! defined( 'YITH_WACP' ) ) {
    exit; // Exit if accessed directly
}
?>

<div id="yith-wacp-mini-cart" class="<?php echo empty( $items ) ? 'empty' : '' ?>">
    <?php if( $show_counter ) : ?>
        <div class="yith-wacp-mini-cart-count"><?php echo $items ?></div>
    <?php endif; ?>
    <div class="yith-wacp-mini-cart-icon" style="background-image: url('<?php echo esc_url( $icon ); ?>');"></div>
</div>
