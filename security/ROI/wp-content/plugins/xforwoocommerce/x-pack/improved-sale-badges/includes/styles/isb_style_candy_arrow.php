<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="92.99" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 92.99" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M6 0l54 0c3.3047,0 6,2.6953 6,6l0 54c0,1.6524 -0.6738,3.1524 -1.7607,4.2393l-26.9967 26.9964c-2.3368,2.3368 -6.1484,2.3368 -8.4852,0l-26.9885 -26.9884c-1.0917,-1.0876 -1.7689,-2.5908 -1.7689,-4.2473l0 -54c0,-3.3047 2.6953,-6 6,-6z"/>
			<path fill="#fff" fill-opacity=".1" d="M59.9966 68.4819l-22.754 22.7538c-2.3368,2.3368 -6.1484,2.3368 -8.4852,0l-22.7539 -22.7539c-2.3368,-2.3368 -2.3368,-6.1485 0,-8.4853l3.662 -3.662c12.8522,-12.8522 33.8168,-12.8522 46.669,0l3.6621 3.6621c2.3368,2.3368 2.3368,6.1485 0,8.4853z"/>
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
	<div class="isb_scheduled_sale isb_scheduled_<?php echo esc_attr( $isb_price['time_mode'] ); ?>">
		<span class="isb_scheduled_time isb_scheduled_compact">
			<?php echo esc_html( $isb_price['time'] ); ?>
		</span>
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
	</div>
<?php
	}
?>
</div>