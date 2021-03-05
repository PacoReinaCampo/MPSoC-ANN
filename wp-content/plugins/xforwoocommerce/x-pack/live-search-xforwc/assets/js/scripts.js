(function($){
    "use strict";

    var doAction = false;
    var willDestroy = true;
    var ajaxOn = 'notactive';
    
    $(document).on( 'keyup', '.xwc--ls-element', function(e) {
        if ( e.target && e.target.matches('.xwc--ls-input') ) {
            if ( doAction ) {
                clearTimeout( doAction );
            }

            doAction = setTimeout( function() {
                _do_live_search(e);
            }, 250 );
        }
    } );

    $(document).on( 'click', function(e) {
        if ( willDestroy !== false ) {
            $('.xwc--ls-focus').removeClass('xwc--ls-focus');

            setTimeout( function() {
                $('.xwc--ls-results').remove();
            }, 250 );
        }
    } );

    $(document).on( 'focusin', '.xwc--ls-element', function(e) {
        if ( e.target && e.target.matches('.xwc--ls-input') ) {
            e.target.classList.add('xwc--ls-focus');

            if ( e.target.value !== '' ) {
                _do_live_search(e);
            }
        }
    } );

    $('.xwc--ls-element').mouseover(function(e){ 
        willDestroy = false;
    }); 
      
    $('.xwc--ls-element').mouseleave(function(){ 
        willDestroy = true;
    });

    $(document).on( 'focusout', '.xwc--ls-element', function(e) {
        if ( willDestroy !== false ) {
            if ( e.target && e.target.matches('.xwc--ls-input') ) {
                e.target.classList.remove('xwc--ls-focus');

                setTimeout( function() {
                    _destroy(e);
                }, 500 );
            }
        }
    } );

    function _destroy(e) {
        $(e.target.parentNode).find('.xwc--ls-results').remove();
    }
    
    function _do_live_search(e) {
        if ( ajaxOn == 'active' ) {
            return false;
        }

        if ( e.target.value.length<3 ) {
            _destroy(e);

            return false;
        }
        
        ajaxOn = 'active';
        
        var s = [ e.target.value, $(e.target).closest('.xwc--ls-element').attr('data-category') ];

        $.when( _ajax(s) ).done( function(f) {
			_do_after_search(e,f,s);
		} );
    }

    function _do_after_search(e,f,s) {
        _build_results(e,f);

        doAction = false;
    }

    function _build_results(e,f) {
        if ( f.length>0 ) {
            var p = '';
            $.each( f, function(i,o) {
                p += '<div class="xwc--ls-result"><div class="xwc--ls-path">'+o.path+'</div><div class="xwc--ls-title">'+o.title+'<div class="xwc--ls-price">'+o.price+'</div><div class="xwc--ls-image">'+o.image+'</div></div></div>';
            } );
           
            _destroy(e);
            $(e.target.parentNode).append('<div class="xwc--ls-results">'+p+'</div>');
        }
        else {
            _destroy(e);
            $(e.target.parentNode).append('<div class="xwc--ls-results"><div class="xwc--ls-result">'+ls.localize.notfound+'</div></div>');
        }
    }

    function _ajax(s) {

        var data = {
			action: 'xwc_live_search',
            settings: s,
		};

		return $.ajax( {
			type: 'POST',
			url: ls.ajax,
			data: data,
			success: function(r) {
				ajaxOn = 'notactive';
			},
			error: function() {
				alert( 'AJAX Error!' );
				ajaxOn = 'notactive';
			}
		} );

    }

})(jQuery);