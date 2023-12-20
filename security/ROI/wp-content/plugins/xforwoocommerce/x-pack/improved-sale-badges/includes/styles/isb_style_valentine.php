<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="66" height="66" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 66 66" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g>
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" class="fil0" d="M36.4406 42.9678l-2.3664 -3.6422c-8.3648,-13.2698 -13.9892,-21.7957 -11.7611,-30.1109 1.8104,-6.7565 8.3997,-10.5608 15.0522,-8.7783 3.8458,1.0305 6.9066,3.7441 8.5447,7.1912 3.143,-2.166 7.1496,-2.9859 10.9955,-1.9554 6.6525,1.7826 10.4569,8.3719 8.6465,15.1283 -2.2281,8.3153 -11.3625,12.8856 -25.2409,20.1963l-3.8705 1.971z"/>
		<path class="isb_style_valentine_fill" d="M36.3658 42.8528l-2.2916 -3.5273c-7.0834,-11.2371 -12.201,-19.0722 -12.2498,-26.2585 0.3686,0.2065 0.728,0.4304 1.0767,0.6708 1.639,-3.4474 4.6988,-6.1608 8.5448,-7.1913 6.6525,-1.7824 13.2418,2.0219 15.0521,8.7782 2.0801,7.763 -2.685,15.7092 -10.1322,27.5281z"/>
		<path class="<?php echo esc_attr( $isb_curr_set['color'] ); ?>" d="M29.5593 51.8892l-3.8705 -1.9711c-13.879,-7.3096 -23.0128,-11.881 -25.2409,-20.1962 -1.8103,-6.7565 1.994,-13.3458 8.6465,-15.1283 3.8459,-1.0305 7.8533,-0.2108 10.9955,1.9554 1.639,-3.4474 4.6989,-6.1607 8.5447,-7.1912 6.6526,-1.7826 13.2419,2.0218 15.0522,8.7782 2.2281,8.3153 -3.3974,16.8405 -11.7611,30.1109l-2.3664 3.6423z"/>
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