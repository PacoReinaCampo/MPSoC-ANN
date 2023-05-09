<?php

	add_action( 'product_loops_before' , array( &$this, 'open' ), 0 );
	add_action( 'product_loops_content' , array( &$this, 'wfsm_support' ), 0 );
	add_action( 'product_loops_content', array( &$this, '__product_link_start' ), 0 );
	add_action( 'product_loops_content' , array( &$this, 'product_image' ), 10 );
	add_action( 'product_loops_content' , array( &$this, 'isb_support' ), 20 );
	add_action( 'product_loops_content' , array( &$this, 'product_title' ), 30 );
	add_action( 'product_loops_content' , array( &$this, '__product_link_close' ), 40 );
	add_action( 'product_loops_content' , array( &$this, 'product_meta' ), 50 );
	add_action( 'product_loops_content' , array( &$this, '__product_link_start' ), 60 );
	add_action( 'product_loops_content' , array( &$this, 'product_excerpt' ), 70 );
	add_action( 'product_loops_content' , array( &$this, 'product_price' ), 80 );
	add_action( 'product_loops_content' , array( &$this, '__product_link_close' ), 90 );
	add_action( 'product_loops_content' , array( &$this, 'ivpa_support' ), 100 );
	add_action( 'product_loops_content' , array( &$this, 'product_add_to_cart' ), 110 );
	add_action( 'product_loops_after' , array( &$this, 'close' ), 9999 );

?>