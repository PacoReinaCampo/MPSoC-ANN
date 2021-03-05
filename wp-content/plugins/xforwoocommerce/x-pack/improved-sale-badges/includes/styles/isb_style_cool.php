<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="66" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 66" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
		<?php
			if ( $isb_curr_set['position'] == 'isb_right' ) {
		?>
			<polygon class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" points="-0,8.9999 9.8385,8.9999 16.3759,34.2407 10.9173,54.5866 62.5263,53.0978 66,2.9775 7.4436,-0.0001 "/>
			<polygon class="isb_style_cool_fill" points="-0,8.9999 9.8384,8.9999 7.4436,0 "/>
		<?php
			}
			else {
		?>
			<polygon class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" points="66,8.9999 56.1615,8.9999 49.6241,34.2407 55.0827,54.5866 3.4737,53.0978 -0,2.9775 58.5564,-0.0001 "/>
			<polygon class="isb_style_cool_fill" points="66,8.9999 56.1616,8.9999 58.5564,0 "/>
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
				if ( $isb_price['type'] == 'simple' || is_singular('product') ) {
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