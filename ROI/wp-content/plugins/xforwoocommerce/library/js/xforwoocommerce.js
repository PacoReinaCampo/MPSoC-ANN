(function($){

	"use strict";

	if (!Element.prototype.matches) {
		Element.prototype.matches = Element.prototype.msMatchesSelector ||
		Element.prototype.webkitMatchesSelector;
	}

	if ( u(xforwc)===false ) {
		return false;
	}

	var xContainer = document.getElementById( 'xforwoocommerce' );

	if ( xContainer===null ) {
		return false;
	}

	start_xforwc();

	function start_xforwc() {
		create_plugins_menu();
	}

	function create_plugins_menu() {
		var html = '';

		for( var i = 0; i<xforwc.plugins.length; i++ ) {
			html += get_plugin_card(i);
		}

		xContainer.innerHTML = html;

		do_checks_after();
	}

	function get_plugin_card(i) {
		var template = wp.template( 'xforwc-plugin' );

		var tmplData = {
			plugin: xforwc.plugins[i],
		};

		var html = template( tmplData );

		return html;
	}

	$(document).on( 'click', function(e) {

		if (e.target && e.target.matches('.xforwc-back') ) {
			back_to_dashboard(e);
		}

		if (e.target && e.target.matches('.xforwc-configure')) {
			create_plugin_enviroment(e);
		}

		if (e.target && e.target.matches('.xforwc-disable') ) {
			disable_plugin(e);
		}

	} );

	function back_to_dashboard(e) {
		$('#svx-settings').remove();
		$('#xforwoocommerce-nav').removeClass('xforwc-plugin-screen');
		$('#xforwoocommerce').show();
	}

	function disable_plugin(e) {

		if ( ajaxOn == 'active' ) {
			return false;
		}

		ajaxOn = 'active';

		var settings = {
			'type' : 'plugin_switch',
			'plugin' : e.target.dataset.plugin,
			'state' : document.getElementById( 'xforwc-'+e.target.dataset.plugin ).classList.contains('disabled') ? 'yes' : 'no',
		};

		$.when( xforwc_ajax(settings) ).done( function(g) {
			$.each( xforwc.plugins, function(i,o) {
				if ( o.slug == e.target.dataset.plugin ) {
					o.state = settings.state;
				}
			} );

			create_plugins_menu();
		} );

	}

	function do_checks_after() {
		_check_warranties_and_returns();
	}

	function _check_warranties_and_returns() {
		if ( $('#xforwc-warranties-and-returns.disabled').length>0 ) {
			$('#menu-posts-wcwar_warranty_req:visible').hide();
		}
		else {
			$('#menu-posts-wcwar_warranty_req:hidden').show();
		}
	}

	function create_plugin_enviroment(e) {

		if ( ajaxOn == 'active' ) {
			return false;
		}

		ajaxOn = 'active';

		var settings = {
			'type' : 'get_svx',
			'plugin' : e.target.dataset.plugin,
		};

		$.when( xforwc_ajax(settings) ).done( function(response) {
			$.each( response, function(i,o) {
				svx[i] = o;
			} );

			$('#xforwoocommerce').hide().before('<div id="svx-settings"></div>');
			$('#xforwoocommerce-nav').addClass('xforwc-plugin-screen');

			svx.initOptions();

		} );

	}

	var ajaxOn = 'notactive';

	function xforwc_ajax( settings ) {

		var data = {
			action: 'xforwc_ajax_factory',
			xforwc: settings
		};

		return $.ajax( {
			type: 'POST',
			url: xforwc.ajax,
			data: data,
			success: function(response) {
				ajaxOn = 'notactive';
			},
			error: function() {
				alert( 'AJAX Error!' );
				ajaxOn = 'notactive';
			}
		} );

	}

	function u(e) {
		return typeof e == 'undefined' ? false : e;
	}

})(jQuery);