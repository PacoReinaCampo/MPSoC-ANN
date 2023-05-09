<div class="isb_sale_badge isb_image <?php echo esc_attr( $isb_class ); ?>">
    <img src="<?php echo esc_url( $isb_curr_set['image']); ?>" />
    
    <div class="isb_image_text">
        <span><?php echo wp_kses_post( $isb_curr_set['special_text'] ); ?></span>
    </div>
</div>