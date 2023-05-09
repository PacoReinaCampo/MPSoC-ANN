(function($){
"use strict";

	$(document).on('click', '#wcwar_change_status .wcwar_close', function() {
		$('#wcwar_change_status').remove();

		return false;
	});

	$(document).on('click', '.war_delete', function() {

		if ( confirm(wcwar.localization.delete) === false ) {
			return false;
		}

		if ( $('select.war_email_selected option:selected').val() == '' ) {
			alert(wcwar.localization.notselected);
			return false;
		}

		ajaxOn = 'active';

		var settings = {
			type: 'et_delete',
			name: $('select.war_email_selected option:selected').val(),
		};

		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				$('select.war_email_selected option[value="'+settings.name+'"]').remove();
				alert(wcwar.localization.deleted);
			}
		} );

		return false;

	});

	$(document).on('click', '.war_load', function() {
		if ( confirm(wcwar.localization.load) === false ) {
			return false;
		}

		if ( $('select.war_email_selected option:selected').val() == '' ) {
			alert(wcwar.localization.notselected);
			return false;
		}

		ajaxOn = 'active';

		var settings = {
			type: 'et_load',
			name: $('select.war_email_selected option:selected').val(),
		};

		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				$('textarea.war_message').val($.parseJSON(response));

				alert(wcwar.localization.loaded);
			}
		} );

		return false;
	});

	$(document).on('click', '.war_save', function() {
		
		var curr_email = $('.war_message').val();
		var curr_name = prompt(wcwar.localization.templatename);

		if ( curr_name == '' || curr_email == '' ) {
			alert(wcwar.localization.missing);
			return false;
		}

		if ( curr_name === null ) {
			return false;
		}

		ajaxOn = 'yes';

		var settings = {
			type: 'et_save',
			name: curr_name,
			email: curr_email,
		};

		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				$('select.war_email_selected').append('<option value="'+sanitize_title(settings.name)+'">'+settings.name+'</option>');

				alert(wcwar.localization.saved);
			}
		} );

		return false;
	});

	$(document).on('click', '.war_send', function() {
		
		if ( confirm(wcwar.localization.sendemail) === false ) {
			return false;
		}

		ajaxOn = 'active';

		var id = $(this).attr('data-ids').split('|');

		var settings = {
			type: 'email_send',
			order_id: id[0],
			request_id: id[1],
			email: $('.war_message').val(),
		};

		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				alert(wcwar.localization.emailsent);
			}
		} );

		return false;

	});

	$(document).on('click', '.wcwar_create', function() {

		ajaxOn = 'active';

		var id = $(this).attr('data-ids').split('|');

		var settings = {
			type: 'create',
			order_id: id[0],
			item_id: id[1],
		};
		
		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				location.reload();
			}
		} );

		return false;

	});


	$(document).on('click', '#wcwar_change_status li', function() {

		ajaxOn = 'yes';
	
		var t = $(this);

		var settings = {
			type: 'status_change',
			request_id: $('#wcwar_change_status').prev().attr('data-id'),
			request_status: t.attr('data-key'),
		};

		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {

				var ct = t.closest('.wcwar_warranty_status').hasClass('single-order') || $('#order_line_items > tr.item').length == 1 ? $('.wcwar_warranty_status').find('.wcwar_change') : t.closest('.wcwar_warranty_status').find('.wcwar_change');

				ct.each( function() {
					$(this).removeClass($(this).attr('class').split(' ').pop()).addClass('warranty_'+t.attr('data-key')).text(t.text());
				} );

				$('#wcwar_change_status').remove();
			
			}
		} );

	} );

	$(document).on('click', '.warranty_change_status', function() {

		ajaxOn = 'yes';

		if ( $('#wcwar_change_status').length > 0 ) {
			$('#wcwar_change_status').remove();
		}

		var t = $(this);

		var settings = {
			type: 'status',
			request_id: t.attr('data-id')
		};


		$.when( war_ajax(settings) ).done( function(response) {
			if (response) {
				response = $(response).css(getCoords(t));
				t.after(response);
			}
		} );

		return false;
	});

	var ajaxOn = 'active';

	function war_ajax( settings ) {

		var data = {
			action: 'wcwar_ajax_factory',
			wcwar: settings
		};

		return $.ajax( {
			type: 'POST',
			url: wcwar.ajax,
			data: data,
			success: function(response) {
				ajaxOn = 'notactive';
			},
			error: function() {
				alert( wcwar.localization.error );
				ajaxOn = 'notactive';
			}
		} );

	}

	function getCoords(elem) {
        var box = elem[0].getBoundingClientRect();

        var body = document.body;
        var docEl = document.documentElement;

        var clientTop = docEl.clientTop || body.clientTop || 0;
        var clientLeft = docEl.clientLeft || body.clientLeft || 0;

        var top = box.top - clientTop;
        var left = box.left - clientLeft;

        return { top: Math.round(top), left: Math.round(left) };
    }

	function sanitize_title(s) {
        if (u(s)) {
            s = s.toString().replace(/^\s+|\s+$/g, '');
            s = s.toLowerCase();

            var from = "ąàáäâèéëêęìíïîłòóöôùúüûñńçěšśčřžźżýúůďťňćđ·/_,:;#";
            var to = "aaaaaeeeeeiiiiloooouuuunncesscrzzzyuudtncd-------";

            for (var i = 0, l = from.length; i < l; i++) {
                s = s.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            s = s.replace('.', '-')
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        } else {
            s = '';
        }

        return s;
    }

})(jQuery);