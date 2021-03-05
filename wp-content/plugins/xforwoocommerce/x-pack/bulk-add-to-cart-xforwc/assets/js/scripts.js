(function($){
    "use strict";

    start_ui();

    var o = [];

    function start_ui() {
        __add_events();
    }

    function __add_events() {

        $(document).on( 'click', function(e) {
            if (e.target && e.target.matches('.bulk-add-to-cart-checkbox')) {
                add_item(e);
            }

            if ( e.target && e.target.matches('.bulk-add-to-cart-now') ) {
                if ( o.length>0 ) {
                    add_to_cart(e);
                }
                else {
                    __show_notify(e,ba.localize.not_selected);
                }
            }

            if (e.target && e.target.matches('.bulk-add-to-cart-select-all') ) {
                select_all(e);
            }

            if (e.target && e.target.matches('.bulk-add-to-cart-deselect-all') ) {
                deselect_all(e);
            }
        } );

        $(document).on( 'change', function(e) {

			if ( e.target && e.target.matches('.bulk-add-to-cart-qty') ) {
				_set_new_qty(e);
            }

        } );


        $(document).on('prdctfltr-reload', function(e) {
            o = [];
        } );

    }

    function select_all(e) {
        $('.bulk-add-to-cart:not(.bulk-add-to-cart-active) .bulk-add-to-cart-checkbox').trigger('click');
    }
    
    function deselect_all(e) {
        $('.bulk-add-to-cart.bulk-add-to-cart-active .bulk-add-to-cart-checkbox').trigger('click');
    }

    function __update_qty(e,t) {
        o[t].qty = parseInt( e.target.value, 10 );
    }

    function _set_new_qty(e) {
        var t = _is_there(e.target.previousElementSibling.dataset.id);

        if ( t !== false ) {
            __update_qty(e,t);
        }
    }

    function _is_there(id) {
        var p = parseInt( id, 10 );

        for (var i = 0; i < o.length; i++) {
            if ( parseInt( o[i].id, 10 ) ===  p ) {
                return i;
            }
        }

        return false;
    }

    function __add(e) {
        if ( e.target.dataset.type == 'simple' ) {
            __add_simple_product(e);
        }

        if ( e.target.dataset.type == 'variable' ) {
            __add_variable_product(e);
        }
    }

    function __add_simple_product(e) {
        __add_to_o(e, false);
    }

    function get_p(e) {
        var p = $(e.target).closest('.pl-product')

        if ( p.length>0 === false ) {
            p = $(e.target).closest('.summary');
        }

        if ( p.length>0 === false ) {
            p = $(e.target).closest('.product');
        }

        if ( p.length>0 === false ) {
            p = $(e.target.parentNode).parent();
        }

        return p;
    }

    function u(e) {
        return ( typeof e === 'undefined' ? false : e );
    }

    function __get_custom_options(e) {
        var p = get_p(e);

        return p.find('.ivpa_custom_option').length > 0 ? p.find('.ivpa_custom_option [name^="ivpac_"]').serialize() : false;
    }

    function __add_to_o(e,p) {
        e.target.parentNode.classList.add('bulk-add-to-cart-active');

        o.push( {
            id: e.target.dataset.id,
            variation: p,
            qty: e.target.nextElementSibling && parseInt( e.target.nextElementSibling.value, 10 )>0 ? parseInt( e.target.nextElementSibling.value, 10 ) : 1,
            custom: __get_custom_options(e)
        } );
    }

    function __add_variable_product(e) {
        var p = get_p(e);
        if ( p.find('.ivpa-register').length==0 ) {
            __show_notify(e,ba.localize.select_options);

            return false;
        }

        if ( p.find('.ivpa-register').length>0 ) {
            if ( p.find('.add_to_cart_button.is-addable').length>0 && p.find('.ivpa-register').attr('data-selected') !== '' ) {
                __add_to_o(e, p.find('.ivpa-register').attr('data-selected'));

                return true;
            }
            else if ( p.find('.pl-add-to-cart.is-addable').length>0 && p.find('.ivpa-register').attr('data-selected') !== '' ) {
                __add_to_o(e, p.find('.ivpa-register').attr('data-selected'));

                return true;
            }
            else if ( p.find('.single_add_to_cart_button.is-addable').length>0 && p.find('input[name="variation_id"]').val() !== '' ) {
                __add_to_o(e, p.find('input[name="variation_id"]').val());

                return true;
            }
            else {
                __show_notify(e,ba.localize.select_options);

                return false;
            }
        }

        return false;
    }

    function __remove(e,t) {
        e.target.parentNode.classList.remove('bulk-add-to-cart-active');   
        o.splice(t,1);
    }

    function add_item(e) {
        var t = _is_there(e.target.dataset.id);

        if ( t === false ) {
            __add(e);
        }
        else {
            __remove(e,t);
        }
    }

    function __get_items_num(e) {
        var c = 0;

        for (var i = 0; i < o.length; i++) {
            if ( parseInt( o[i].qty, 10 ) > 0 ) {
                c=c+parseInt( o[i].qty, 10 );
            }
        }

        return c;
    }

    function __clear_now_button(e) {
        e.target.innerHTML = ba.localize.now_button;
    }

    var ajaxOn = false;
    function add_to_cart(e) {
        if ( ajaxOn === true ) {
            return false;
        }

        ajaxOn = true;

        if ( o.length === 0 ) {
            return false;
        }

        e.target.classList.add('bulk-add-to-cart-active');
        e.target.innerHTML = '';

        var data = {
			action: 'bulk_add_to_cart',
			products: o
		};

		$.ajax({
			type: 'POST',
			url: ba.ajax,
			data: data,
			success: function(response) {

				if ( !response ) {
					e.target.classList.remove('bulk-add-to-cart-active');
					return;
				}

				if ( response.error && response.product_url ) {
					window.location = response.product_url;
					return;
                }
                
                var itemsNum = __get_items_num();

                e.target.innerHTML = itemsNum == 1 ? ba.localize.one.replace( '%%' , 1 ) : ba.localize.many.replace( '%%' , itemsNum );

				var fragments = response.fragments;
				var cart_hash = response.cart_hash;

				if ( fragments ) {
					$.each(fragments, function(key, value) {
						$(key).replaceWith(value);
					});

                    $(document.body).trigger('wc_fragments_loaded');
                }

                e.target.classList.remove('bulk-add-to-cart-active');
                
                setTimeout( function() {
                    __clear_now_button(e);
                }, 2000 );

                $('body').trigger( 'added_to_cart', [ fragments, cart_hash ] );

                ajaxOn = false;
			},
			error: function() {
				alert('AJAX Error!');
			}
		});

		return false;

    }

    function __remove_notify() {
        $('.bulk-add-to-cart-tooltip').remove();
    }

    function __show_notify(e,t) {
        __remove_notify();

        var c = e.target.getBoundingClientRect();

        $('body').append('<div class="bulk-add-to-cart-tooltip" style="left:'+c.left+'px;top:'+c.top+'px">'+t+'</div>');

        setTimeout( function( ) {
           __remove_notify();
        }, 2000 );
    }

})(jQuery);