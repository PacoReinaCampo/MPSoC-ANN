<div class="isb_sale_badge isb_group_responsive isb_group_border <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="-5 -5 210 210" xmlns:xlink="http://www.w3.org/1999/xlink">
		<circle class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" cx="100" cy="100" r="83"/>
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" d="M156 14c23,29 39,163 -15,171 -53,8 -157,-85 -139,-115 18,-30 130,-84 154,-56z"/>
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" d="M54 7c61,-2 123,5 138,46 14,42 -105,189 -141,135 -32,-48 -57,-178 3,-181z"/>
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" d="M26 177c-26,-29 -7,-161 48,-175 55,-15 145,80 122,134 -23,54 -145,69 -170,41z"/>
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