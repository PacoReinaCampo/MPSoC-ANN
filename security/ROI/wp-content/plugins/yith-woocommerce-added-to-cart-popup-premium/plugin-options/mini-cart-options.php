<?php
/**
 * MINI CART ARRAY OPTIONS
 */

$style = array(

	'mini-cart'  => array(

		array(
			'title' => __( 'Mini Cart Options', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-mini-cart-options'
		),

        array(
            'name'          => __( 'Enable Popup', 'yith-woocommerce-added-to-cart-popup' ),
            'desc'          => _x( 'On Desktop', 'Option to enable mini cart on desktop', 'yith-woocommerce-added-to-cart-popup' ),
            'type'          => 'checkbox',
            'default'       => 'yes',
            'id'            => 'yith-wacp-mini-cart-enable',
            'checkboxgroup' => 'start'
        ),

        array(
            'name'          => '',
            'desc'          => _x( 'On Mobile', 'Option to enable mini cart on mobile devices', 'yith-woocommerce-added-to-cart-popup' ),
            'type'          => 'checkbox',
            'default'       => 'yes',
            'id'            => 'yith-wacp-mini-cart-enable-mobile',
            'checkboxgroup' => 'end'
        ),

		array(
			'name'  => __( 'Mini Cart Icon', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'  => __( 'Upload a mini cart icon (suggested size 32x32 px)', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'yith_wacp_upload',
			'default' => YITH_WACP_ASSETS_URL . '/images/mini-cart.png',
			'id'    => 'yith-wacp-mini-cart-icon'
		),

        array(
            'name'          => __( 'Show counter', 'yith-woocommerce-added-to-cart-popup' ),
            'desc'          => __( 'Choose to show a counter with the number of items in cart.', 'yith-woocommerce-added-to-cart-popup' ),
            'type'          => 'checkbox',
            'default'       => 'yes',
            'id'            => 'yith-wacp-mini-cart-show-counter',
        ),

        array(
            'name'          => __( 'Hide if empty', 'yith-woocommerce-added-to-cart-popup' ),
            'desc'          => __( 'Choose to hide the mini cart if it is empty.', 'yith-woocommerce-added-to-cart-popup' ),
            'type'          => 'checkbox',
            'default'       => 'no',
            'id'            => 'yith-wacp-mini-cart-hide-empty',
        ),

        array(
            'name'          => __( 'Mini Cart position', 'yith-woocommerce-added-to-cart-popup' ),
            'desc'          => __( 'Drag the mini cart at desired position.', 'yith-woocommerce-added-to-cart-popup' ),
            'type'          => 'yith_wacp_drag_pos',
            'id'            => 'yith-wacp-mini-cart-position',
            'default'       => array(
                'top'   => '20',
                'left'  => '97'
            )
        ),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-mini-cart-options'
		)
	)
);

return apply_filters( 'yith_wacp_panel_style_options', $style );