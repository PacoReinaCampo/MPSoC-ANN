<?php

    if ( $isAdmin || !isset( $options['product-filters'] ) || $options['product-filters'] == 'yes' ) {
        include_once( 'prdctfltr/prdctfltr.php' );
    }

    if ( $isAdmin || !isset( $options['product-options'] ) || $options['product-options'] == 'yes' ) {
        include_once( 'improved-variable-product-attributes/improved-variable-product-attributes.php' );
    }

    if ( $isAdmin || !isset( $options['product-badges'] ) || $options['product-badges'] == 'yes' ) {
        include_once( 'improved-sale-badges/improved-sale-badges.php' );
    }

    if ( $isAdmin || !isset( $options['seo-for-woocommerce'] ) || $options['seo-for-woocommerce'] == 'yes' ) {
        include_once( 'seo-for-woocommerce/seo-for-woocommerce.php' );
    }

    if ( $isAdmin || !isset( $options['product-loops'] ) || $options['product-loops'] == 'yes' ) {
        include_once( 'product-loops/product-loops.php' );
    }

    if ( $isAdmin || !isset( $options['share-print-pdf'] ) || $options['share-print-pdf'] == 'yes' ) {
        include_once( 'share-print-pdf-woocommerce/share-woo.php' );
    }

    if ( $isAdmin || !isset( $options['live-editor'] ) || $options['live-editor'] == 'yes' ) {
        include_once( 'woocommerce-frontend-shop-manager/woocommerce-frontend-shop-manager.php' );
    }

    if ( $isAdmin || !isset( $options['warranties-and-returns'] ) || $options['warranties-and-returns'] == 'yes' ) {
        include_once( 'woocommerce-warranties-and-returns/woocommerce-warranties-and-returns.php' );
    }

    if ( $isAdmin || !isset( $options['add-tabs-xforwc'] ) || $options['add-tabs-xforwc'] == 'yes' ) {
        include_once( 'add-tabs-xforwc/add-tabs-xforwc.php' );
    }

    if ( $isAdmin || !isset( $options['spam-control-xforwc'] ) || $options['spam-control-xforwc'] == 'yes' ) {
        include_once( 'spam-control-xforwc/spam-control-xforwc.php' );
    }

    if ( $isAdmin || !isset( $options['price-commander-xforwc'] ) || $options['price-commander-xforwc'] == 'yes' ) {
        include_once( 'price-commander-xforwc/price-commander-xforwc.php' );
    }

    if ( $isAdmin || !isset( $options['bulk-add-to-cart-xforwc'] ) || $options['bulk-add-to-cart-xforwc'] == 'yes' ) {
        include_once( 'bulk-add-to-cart-xforwc/bulk-add-to-cart-xforwc.php' );
    }

    if ( $isAdmin || !isset( $options['live-search-xforwc'] ) || $options['live-search-xforwc'] == 'yes' ) {
        include_once( 'live-search-xforwc/live-search-xforwc.php' );
    }

    if ( $isAdmin || !isset( $options['floating-cart-xforwc'] ) || $options['floating-cart-xforwc'] == 'yes' ) {
        include_once( 'floating-cart-xforwc/floating-cart-xforwc.php' );
    }
