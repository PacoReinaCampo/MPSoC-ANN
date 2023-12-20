<div class="isb_sale_badge <?php echo esc_attr( $isb_class ); ?>" data-id="<?php echo esc_attr( $isb_price['id'] ); ?>">
	<div class="isb_sale_text"><?php esc_html_e('SALE', 'xforwoocommerce' ); ?></div>
	<div class="isb_sale_percentage isb_color">
		<span class="isb_percentage">
			<?php echo esc_html( $isb_price['percentage'] ); ?> 
		</span>
		<span class="isb_percentage_text">
			<?php esc_html_e('%', 'xforwoocommerce' ); ?>
		</span>
	</div>
	<div class="isb_sale_text"><?php esc_html_e('PRICE OFF', 'xforwoocommerce' ); ?></div>
	<div class="isb_money_saved">
		<span class="isb_saved_old">
			<?php echo strip_tags( wc_price( $isb_price['regular'] ) ); ?>
		</span>
		<span class="isb_saved isb_color">
			<?php echo strip_tags( wc_price( $isb_price['sale'] ) ); ?>
		</span>
	</div>
<?php
	if ( isset($isb_price['time']) ) {
?>
	<div class="isb_scheduled_sale isb_scheduled_<?php echo esc_attr( $isb_price['time_mode'] ); ?>">
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