<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="81.88" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 81.88" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
		<?php
			if ( $isb_curr_set['position'] == 'isb_right' ) {
		?>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M-0.0001 61.396l0.0001 -55.396c0,-3.3047 2.6953,-6 6,-6l54 0c3.3047,0 6,2.6953 6,6l0 69.8652c0,1.9278 -0.8181,3.5867 -2.3475,4.7602 -1.5293,1.1735 -3.3434,1.5344 -5.2055,1.0354l-54 -14.4693c-2.66,-0.7128 -4.4471,-3.0416 -4.4471,-5.7955z"/>
			<path fill="#fff" fill-opacity=".1" d="M60 0l-54 0c-3.3047,0 -6,2.6953 -6,6l0 44.9451c9.8482,2.5027 21.0779,3.9225 33,3.9225 11.9221,0 23.1518,-1.4198 33,-3.9225l0 -44.9451c0,-3.3047 -2.6953,-6 -6,-6z"/>
		<?php
			}
			else {
		?>
			<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M66.0001 61.396l-0.0001 -55.396c0,-3.3047 -2.6953,-6 -6,-6l-54 0c-3.3047,0 -6,2.6953 -6,6l0 69.8652c0,1.9278 0.8181,3.5867 2.3475,4.7602 1.5293,1.1735 3.3434,1.5344 5.2055,1.0354l54 -14.4693c2.66,-0.7128 4.4471,-3.0416 4.4471,-5.7955z"/>
			<path fill="#fff" fill-opacity=".1" d="M60 0l-54 0c-3.3047,0 -6,2.6953 -6,6l0 44.9451c9.8482,2.5027 21.0779,3.9225 33,3.9225 11.9221,0 23.1518,-1.4198 33,-3.9225l0 -44.9451c0,-3.3047 -2.6953,-6 -6,-6z"/>
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