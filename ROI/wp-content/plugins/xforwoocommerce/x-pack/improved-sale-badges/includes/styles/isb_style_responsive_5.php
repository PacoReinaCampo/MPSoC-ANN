<div class="isb_sale_badge isb_group_responsive <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 200 200" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M107 3l11 10c3,3 6,4 10,3l14 -4c6,-1 11,2 13,7l4 15c1,3 4,6 7,7l15 4c5,2 8,7 7,13l-4 14c-1,4 0,7 3,10l10 11c4,4 4,10 0,14l-10 11c-3,3 -4,6 -3,10l4 14c1,6 -2,11 -7,13l-15 4c-3,1 -6,4 -7,7l-4 15c-2,5 -7,8 -13,7l-14 -4c-4,-1 -7,0 -10,3l-11 10c-4,4 -10,4 -14,0l-11 -10c-3,-3 -6,-4 -10,-3l-14 4c-6,1 -11,-2 -13,-7l-4 -15c-1,-3 -4,-6 -7,-7l-15 -4c-5,-2 -8,-7 -7,-13l4 -14c1,-4 0,-7 -3,-10l-10 -11c-4,-4 -4,-10 0,-14l10 -11c3,-3 4,-6 3,-10l-4 -14c-1,-6 2,-11 7,-13l15 -4c3,-1 6,-4 7,-7l4 -15c2,-5 7,-8 13,-7l14 4c4,1 7,0 10,-3l11 -10c4,-4 10,-4 14,0z"/>
			<path fill="#000000" fill-opacity=".075" d="M107 3l11 10c3,3 6,4 10,3l14 -4c6,-1 11,2 13,7l4 15c1,3 4,6 7,7l15 4c5,2 8,7 7,13l-4 14c-1,4 0,7 3,10l10 11c4,4 4,10 0,14l-10 11c-3,3 -4,6 -3,10l4 14c1,6 -2,11 -7,13l-15 4c-3,1 -6,4 -7,7l-4 15c-2,5 -7,8 -13,7l-14 -4c-4,-1 -7,0 -10,3l-11 10c-2,2 -4,3 -7,3l0 -200c3,0 5,1 7,3z"/>
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