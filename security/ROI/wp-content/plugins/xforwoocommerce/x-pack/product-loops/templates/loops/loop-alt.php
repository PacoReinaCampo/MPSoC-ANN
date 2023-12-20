<?php

	add_action( 'product_loops_alt_before' , array( &$this, 'open' ), 0 );
	add_action( 'product_loops_alt_content' , array( &$this, 'wfsm_support' ), 0 );
	add_action( 'product_loops_alt_content', array( &$this, '__product_link_start' ), 0 );
	add_action( 'product_loops_alt_content' , array( &$this, 'start_alt' ), 10 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_image' ), 20 );
	add_action( 'product_loops_alt_content' , array( &$this, 'isb_support' ), 30 );
	add_action( 'product_loops_alt_content' , array( &$this, 'start_alt_inner' ), 40 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_title' ), 50 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_excerpt' ), 60 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_price' ), 70 );
	add_action( 'product_loops_alt_content' , array( &$this, '__product_link_close' ), 80 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_add_to_cart' ), 90 );
	add_action( 'product_loops_alt_content' , array( &$this, 'end_alt' ), 100 );
	add_action( 'product_loops_alt_content' , array( &$this, 'end_alt' ), 110 );
	add_action( 'product_loops_alt_content' , array( &$this, 'start_alt_after' ), 120 );
	add_action( 'product_loops_alt_content' , array( &$this, 'product_meta' ), 130 );
	add_action( 'product_loops_alt_content' , array( &$this, 'ivpa_support' ), 140 );
	add_action( 'product_loops_alt_content' , array( &$this, 'end_alt' ), 150 );
	add_action( 'product_loops_alt_after' , array( &$this, 'close' ), 9999 );

?>