<div class="isb_sale_badge isb_group_responsive <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 200 200" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M100 0c-62,9 -94,66 -100,164l100 36 100 -36c-6,-98 -38,-155 -100,-164z"/>
			<path fill="#000000" fill-opacity=".075" d="M100 200l100 -36c-6,-98 -38,-155 -100,-164l0 200z"/>
		</g>
		<g>
			<path fill="#ffffff" d="M131 129c-1,1 -3,1 -5,0 -1,-1 -1,-4 0,-5l37 -37c1,-1 4,-1 5,0 1,2 1,4 0,5l-37 37z"/>
			<path fill="#ffffff" d="M136 86c2,0 5,1 7,3 2,2 3,5 3,8 0,2 -1,5 -3,7 -2,2 -5,3 -7,3 -3,0 -6,-1 -8,-3 -2,-2 -3,-5 -3,-7 0,-3 1,-6 3,-8 2,-2 5,-3 8,-3zm2 8c-1,0 -2,-1 -2,-1 -1,0 -2,1 -3,1 0,1 -1,2 -1,3 0,0 1,1 1,2 1,0 2,1 3,1 0,0 1,-1 2,-1 0,-1 1,-2 1,-2 0,-1 -1,-2 -1,-3z"/>
			<path fill="#ffffff" d="M159 109c3,0 5,2 7,4 2,1 3,4 3,7 0,3 -1,5 -3,7 -2,2 -4,3 -7,3 -3,0 -6,-1 -8,-3 -1,-2 -3,-4 -3,-7 0,-3 2,-6 3,-7 2,-2 5,-4 8,-4zm2 8c-1,0 -1,0 -2,0 -1,0 -2,0 -3,0 0,1 -1,2 -1,3 0,1 1,1 1,2 1,1 2,1 3,1 1,0 1,0 2,-1 1,-1 1,-1 1,-2 0,-1 0,-2 -1,-3z"/>
		</g>
	</svg>
	<div class="isb_sale_percentage">
		<?php echo esc_html( $isb_price['percentage'] ); ?>
	</div>
<?php
	if ( isset( $isb_price['time'] ) ) {
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