<div class="isb_sale_badge isb_group_responsive <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="200" height="200" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 200 200" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
			<polygon class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" points="100,0 106,8 113,1 118,9 126,3 130,12 138,8 141,17 150,13 151,23 161,21 161,30 171,29 170,39 179,39 177,49 187,50 183,59 192,62 188,70 197,74 191,82 199,87 192,94 200,100 192,106 199,113 191,118 197,126 188,130 192,138 183,141 187,150 177,151 179,161 170,161 171,171 161,170 161,179 151,177 150,187 141,183 138,192 130,188 126,197 118,191 113,199 106,192 100,200 94,192 87,199 82,191 74,197 70,188 62,192 59,183 50,187 49,177 39,179 39,170 29,171 30,161 21,161 23,151 13,150 17,141 8,138 12,130 3,126 9,118 1,113 8,106 0,100 8,94 1,87 9,82 3,74 12,70 8,62 17,59 13,50 23,49 21,39 30,39 29,29 39,30 39,21 49,23 50,13 59,17 62,8 70,12 74,3 82,9 87,1 94,8 "/>
			<polygon fill="#000000" fill-opacity=".075" points="100,0 106,8 113,1 118,9 126,3 130,12 138,8 141,17 150,13 151,23 161,21 161,30 171,29 170,39 179,39 177,49 187,50 183,59 192,62 188,70 197,74 191,82 199,87 192,94 200,100 192,106 199,113 191,118 197,126 188,130 192,138 183,141 187,150 177,151 179,161 170,161 171,171 161,170 161,179 151,177 150,187 141,183 138,192 130,188 126,197 118,191 113,199 106,192 100,200 "/>
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