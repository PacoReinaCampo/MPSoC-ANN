<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="74.28" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 74.28" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
		<?php
			if ( $isb_curr_set['position'] == 'isb_right' ) {
		?>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M-0 50.2072c0,-12.5518 0,-25.1037 0,-37.6554 0,-6.9304 5.6214,-12.5518 12.5517,-12.5518 13.6322,0 27.2643,0 40.8965,0 6.9304,0 12.5518,5.6214 12.5518,12.5518 0,12.5517 0,25.1036 0,37.6554 0,6.9304 -5.6214,12.5518 -12.5518,12.5518 -0.2161,0 -0.4322,0 -0.6483,0l13.2001 11.519 -28.05 -11.519c-8.4661,0 -16.9322,0 -25.3983,0 -6.9303,0 -12.5517,-5.6214 -12.5517,-12.5518z"/>
			<path fill="#fff" fill-opacity=".1" d="M66 39.1058l0 11.1014c0,6.9304 -5.6214,12.5518 -12.5518,12.5518 -0.2161,0 -0.4322,0 -0.6483,0l13.2001 11.519 -28.05 -11.519c-8.4661,0 -16.9322,0 -25.3983,0 -3.3999,0 -6.4842,-1.3535 -8.7449,-3.5503 11.2968,-12.4585 30.5427,-20.6651 52.384,-20.6651 3.3369,0 6.6125,0.1932 9.8092,0.5622z"/>
		<?php
			}
			else {
		?>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M66 50.2072c0,-12.5518 0,-25.1037 0,-37.6554 0,-6.9304 -5.6214,-12.5518 -12.5517,-12.5518 -13.6322,0 -27.2643,0 -40.8965,0 -6.9304,0 -12.5518,5.6214 -12.5518,12.5518 0,12.5517 0,25.1036 0,37.6554 0,6.9304 5.6214,12.5518 12.5518,12.5518 0.2161,0 0.4322,0 0.6483,0l-13.2001 11.519 28.05 -11.519c8.4661,0 16.9322,0 25.3983,0 6.9303,0 12.5517,-5.6214 12.5517,-12.5518z"/>
			<path fill="#fff" fill-opacity=".1" d="M-0 39.1058l0 11.1014c0,6.9304 5.6214,12.5518 12.5518,12.5518 0.2161,0 0.4322,0 0.6483,0l-13.2001 11.519 28.05 -11.519c8.4661,0 16.9322,0 25.3983,0 3.3999,0 6.4842,-1.3535 8.7449,-3.5503 -11.2968,-12.4585 -30.5427,-20.6651 -52.384,-20.6651 -3.3369,0 -6.6125,0.1932 -9.8092,0.5622z"/>
		<?php
			}
		?>
		</g>
	</svg>
	<div class="isb_sale_percentage">
		<span class="isb_percentage">
			<?php echo esc_html( $isb_price['percentage'] ); ?> 
		</span>
		<span class="isb_percentage_text">
			<?php esc_html_e('%', 'xforwoocommerce' ); ?>
		</span>
	</div>
	<div class="isb_money_saved">
		<span class="isb_saved_text">
			<?php
				if ( $isb_price['type'] == 'simple' || is_singular( 'product' ) && $isb_price['id'] != 0 ) {
					esc_html_e('Save', 'xforwoocommerce' );
				}
				else {
					esc_html_e('Up to', 'xforwoocommerce' );
				}
			?> 
		</span>
		<span class="isb_saved">
			<?php echo strip_tags( wc_price( $isb_price['difference'] ) ); ?>
		</span>
	</div>
<?php
	if ( isset($isb_price['time']) ) {
?>
	<div class="isb_scheduled_sale isb_scheduled_<?php echo esc_attr( $isb_price['time_mode'] ); ?> <?php echo esc_attr( $isb_curr_set['color'] ); ?>">
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