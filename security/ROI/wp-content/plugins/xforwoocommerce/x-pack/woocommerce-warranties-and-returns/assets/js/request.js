(function($){
"use strict";

	$(document).on('change', 'input[name=item_id]', function() {

		$(this).closest('.wcwar_item ').find('input[name=wcwar_qty]').prop('disabled',true).attr('disabled','disabled').hide();
		$(this).closest('.wcwar_item_wrap').find('input[name=wcwar_qty]').removeAttr('disabled').show();

	});

	$(document).on('change', 'input[name=wcwar_return]', function() {
		if(this.checked) {
			$('#wcwar_warranty_form input[type="text"], #wcwar_warranty_form input[type="checkbox"], #wcwar_warranty_form input[type="radio"], #wcwar_warranty_form textarea, #wcwar_warranty_form select').prop('disabled', true);
			
			$('#wcwar_warranty_form').hide();

			$('#wcwar_return_form').fadeIn(100);
		}
		else {
			$('#wcwar_warranty_form input[type="text"], #wcwar_warranty_form input[type="checkbox"], #wcwar_warranty_form input[type="radio"], #wcwar_warranty_form textarea, #wcwar_warranty_form select').prop('disabled', false);

			$('#wcwar_return_form').hide();

			$('#wcwar_warranty_form').fadeIn(100);
		}
	});

})(jQuery);