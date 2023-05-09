(function($){
    "use strict";

    if ( u(fcart) === false ) {
        return;
    }

    fcart.options = {};

    add_events();

    function add_events() {
		$(document).on( 'click', function(e) {

			if (fcart.settings[0] == 'yes' ) {
                if (e.target && e.target.id == 'floating-cart') {
                    toggle_cart_contents(e);

                    return false;
                }

                if (e.target && e.target.matches('.floating-cart-overlay')) {
                    _hide_cart_contents(e.target.previousElementSibling);
                }
            }

        } );

        if (fcart.settings[1] == 'yes') {
            $(document.body).on( 'added_to_cart', function(f) {
                $('.floating-cart-message').html('<span>'+fcart.localize[0]+'</span>');

                setTimeout( function() {
                    $('.floating-cart-message').empty();
                }, parseInt(fcart.settings[2], 10)>0?parseInt(fcart.settings[2]):2500 );
            } );
        }
    }

    function toggle_cart_contents(e) {
        if (e.target.parentNode.parentNode && e.target.parentNode.parentNode.matches('.floating-cart-active')) {
            _hide_cart_contents(e.target.parentNode.parentNode);
        }
        else {
            _show_cart_contents(e.target.parentNode.parentNode);
        }
    }

    function _show_cart_contents(f) {
        fcart.options.open = true;
        f.classList.add('floating-cart-active');
    }

    function _hide_cart_contents(f) {
        fcart.options.open = true;
        f.classList.remove('floating-cart-active');
    }
    
    function u(e) {
		return typeof e == 'undefined' ? false : e;
	}

})(jQuery);