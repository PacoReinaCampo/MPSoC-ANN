<div class="isb_sale_badge isb_group_responsive isb_group_border <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="-5 -5 210 210" xmlns:xlink="http://www.w3.org/1999/xlink">
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?> isb_stroke" d="M107 3l11 10c3,3 6,4 10,3l14 -4c6,-1 11,2 13,7l4 15c1,3 4,6 7,7l15 4c5,2 8,7 7,13l-4 14c-1,4 0,7 3,10l10 11c4,4 4,10 0,14l-10 11c-3,3 -4,6 -3,10l4 14c1,6 -2,11 -7,13l-15 4c-3,1 -6,4 -7,7l-4 15c-2,5 -7,8 -13,7l-14 -4c-4,-1 -7,0 -10,3l-11 10c-4,4 -10,4 -14,0l-11 -10c-3,-3 -6,-4 -10,-3l-14 4c-6,1 -11,-2 -13,-7l-4 -15c-1,-3 -4,-6 -7,-7l-15 -4c-5,-2 -8,-7 -7,-13l4 -14c1,-4 0,-7 -3,-10l-10 -11c-4,-4 -4,-10 0,-14l10 -11c3,-3 4,-6 3,-10l-4 -14c-1,-6 2,-11 7,-13l15 -4c3,-1 6,-4 7,-7l4 -15c2,-5 7,-8 13,-7l14 4c4,1 7,0 10,-3l11 -10c4,-4 10,-4 14,0z"/>
	</svg>
	<div class="isb_sale_percentage isb_color">
		<?php echo esc_attr( $isb_price['percentage'] ); ?>
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