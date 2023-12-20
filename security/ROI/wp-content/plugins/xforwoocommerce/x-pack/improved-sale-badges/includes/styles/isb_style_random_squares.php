<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="66" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 66" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
		<?php
			$curr_svgs = array(
				'3.9815,10.0087 57.0211,3.0396 64.0725,55.6403 12.5773,62.7761 ',
				'5.4839,0.9951 57.5001,5.3141 63.7392,57.8937 2.2915,58.6448 ',
				'12.2863,6.253 58.8568,2.6851 66,61.814 -0,47.7763 ',
				'6.2772,5.4174 58.1691,7.6309 66,55.6662 13.7252,59.3326 ',
			);
		?>
			<polygon class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" points="<?php echo esc_attr( $curr_svgs[array_rand( $curr_svgs )] ); ?>"/>
		</g>
	</svg>
	<div class="isb_sale_percentage">
		<span class="isb_percentage">
			<?php echo esc_html( $isb_price['percentage'] ); ?> 
		</span>
		<span class="isb_percentage_text">
			<?php esc_html_e('OFF', 'xforwoocommerce' ); ?> 
			<?php esc_html_e('%', 'xforwoocommerce' ); ?>
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