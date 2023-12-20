<div class="isb_sale_badge isb_group_responsive isb_group_border <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="//www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="-5 -5 210 210" xmlns:xlink="//www.w3.org/1999/xlink">
		<polygon class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" points="0,0 200,0 200,200 0,200 "/>
	</svg>
	<div class="isb_sale_percentage isb_color">
		<?php echo esc_html( $isb_price['percentage'] ); ?>
	</div>
<?php
	if ( isset( $isb_price['time'] ) ) {
?>
	<div class="isb_scheduled_sale isb_scheduled_<?php echo esc_attr( $isb_price['time_mode'] ); ?> <?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_color">
		<span class="isb_scheduled_text">
			<?php
				if ( $isb_price['time_mode'] == 'start' ) {
					esc_html_e('Starts in', 'xforwoocommerce' );
				}
				else {
					esc_html_e('Ends in', 'xforwoocommerce' );
				}
			?> 
		</span>
		<span class="isb_scheduled_time isb_scheduled_compact">
			<?php echo esc_html( $isb_price['time'] ); ?>
		</span>
	</div>
<?php
	}
?>
</div>