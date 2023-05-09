(function($){

	"use strict";
	
	$(document).on( 'svx-wcmn_pl_overrides-load', function(e,f) {

		svx.settings['_wcmn_outofstock'].val = u(f.val.outofstock)!==false?f.val.outofstock:'';
		svx.settings['_wcmn_sale'].val = u(f.val.sale)!==false?f.val.sale:'';

		svx.settings['_wcmn_featured'].val = u(f.val.featured)!==false?f.val.featured:'';

		if ( u(f.val.new) ) {
			svx.settings['_wcmn_expire_in'].val = u(f.val.new.days)!==false?f.val.new.days:'';
			svx.settings['_wcmn_expire_in_preset'].val = u(f.val.new.preset)!==false?f.val.new.preset:'';
		}
		else {
			svx.settings['_wcmn_expire_in'].val = '';
			svx.settings['_wcmn_expire_in_preset'].val = '';
		}

		svx.settings['_wcmn_tags'].val = [];
		svx.settings['_wcmn_categories'].val = [];

		var c=0;
		if ( u(f.val) && u (f.val.product_tag) ) {
			c=0;
			$.each( f.val.product_tag, function(i,g) {
				svx.settings['_wcmn_tags'].val[c] = {
					name:u(g.name)!==false?g.name:g,
					term:u(g.term)!==false?g.term:i,
					preset:u(g.preset)!==false?g.preset:g,
				}
				c++;
			} );
		}

		if ( u(f.val) && u (f.val.product_cat) ) {
			c=0;
			$.each( f.val.product_cat, function(i,g) {
				svx.settings['_wcmn_categories'].val[typeof g.order!=='undefined'?g.order:c] = {
					name:u(g.name)!==false?g.name:g,
					term:u(g.term)!==false?g.term:i,
					preset:u(g.preset)!==false?g.preset:g,
				}
				c++;
			} );
		}

	} );

	$(document).on( 'svx-wcmn_pl_overrides-save', function(e,f) {

		var r = {};
		svx.skips = [];

		svx.skips.push('_wcmn_featured');
		svx.skips.push('_wcmn_expire_in');
		svx.skips.push('_wcmn_expire_in_preset');
		svx.skips.push('_wcmn_tags');
		svx.skips.push('_wcmn_categories');

		svx.skips.push('_wcmn_outofstock');
		svx.skips.push('_wcmn_sale');

		r.outofstock = u(svx.settings['_wcmn_featured'].val);
		r.sale = u(svx.settings['_wcmn_featured'].val);

		r.featured = u(svx.settings['_wcmn_featured'].val);

		r.new = {};
		r.new.days = u(svx.settings['_wcmn_expire_in'].val);
		r.new.preset = u(svx.settings['_wcmn_expire_in_preset'].val);

		r.product_tag = {};
		r.product_cat = {};

		if ( u(svx.settings['_wcmn_tags'].val) ) {
			var c=0;
			$.each( svx.settings['_wcmn_tags'].val, function(i,g) {
				r.product_tag[c] = {
					name:u(g.name)?g.name:'Name',
					term:u(g.term),
					preset:u(g.preset),
					order:c
				}
				c++;
			} );
		}

		if ( u(svx.settings['_wcmn_categories'].val) ) {
			var c=0;
			$.each( svx.settings['_wcmn_categories'].val, function(i,g) {
				r.product_cat[g.term] = {
					name:u(g.name)?g.name:'Name',
					term:u(g.term),
					preset:u(g.preset),
					order:c
				}
				c++;
			} );
		}

		f.save_val = r;

	} );

	$(document).on( 'svx-wcmn_pl_presets-load', function(e,f) {

		var r = [];
		var w = {};

		var c=0;
		$.each( f.val, function(i,b) {

			r[c] = {
				name:u(b)
			};

			w[i] = u(b);

			c++;

		});

		f.val = r;
		svx.presets = w;

	} );

	$(document).on( 'svx-wcmn_pl_presets-save', function(e,f) {

		var r = {};
		svx.less = {
			'option' : 'wcmn_pl_less',
			'solids' : {
				'name' : 'wcmn_pl_presets',
				'solid' : '_wcmn_pl_preset_',
				'url' : svx.imgs,
				'file' : 'product-loops',
				'option' : 'wcmn_pl_less',
				'options' : [ 'name', 'loop', 'accent_color', 'hover_color', 'column', 'text_size', 'gap', 'excerpt_grid', 'excerpt_table', 'image_width_table', 'button', 'font_size' ]
			},
			'length' : f.val.length,
			'names' : '',
			'loops' : '',
			'accent_colors' : '',
			'hover_colors' : '',
			'columns' : '',
			'text_sizes' : '',
			'gaps' : '',
			'excerpt_grids' : '',
			'excerpt_tables' : '',
			'image_width_tables' : '',
			'buttons' : '',
			'font_sizes' : ''
		};
		svx.solids = {};

		$.each( f.val, function(i,b) {
			if ( u(b.name) === false || b.name == '' ) {
				b.name = 'Not set';
			}

			r[sanitize_title(b.name)] = b.name;
			if ( Object.keys(b).length>1 ) {
				svx.solids['_wcmn_pl_preset_'+sanitize_title(b.name)] = {
					val: b,
					autoload: 'solid'
				};
			}
			else if ( svx.utility_mode ) {
				svx.solids['_wcmn_pl_preset_'+sanitize_title(b.name)] = {};
			}
		});

		f.save_val = r;

	} );

	function sanitize_title(s) {
		if ( u(s) ) {
			s = s.toString().replace(/^\s+|\s+$/g, '');
			s = s.toLowerCase();

			var from = "ąàáäâèéëêęìíïîłòóöôùúüûñńçěšśčřžźżýúůďťňćđ·/_,:;#";
			var to   = "aaaaaeeeeeiiiiloooouuuunncesscrzzzyuudtncd-------";

			for (var i=0, l=from.length ; i<l ; i++)
			{
				s = s.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
			}

			s = s.replace('.', '-')
				.replace(/[^a-z0-9 -]/g, '')
				.replace(/\s+/g, '-')
				.replace(/-+/g, '-');
		}
		else {
			s = '';
		}

		return s;
	}

	function u(e) {
		return typeof e == 'undefined' ? false : e;
	}

})(jQuery);