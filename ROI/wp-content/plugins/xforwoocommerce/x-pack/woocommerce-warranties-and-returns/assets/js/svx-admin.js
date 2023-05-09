(function($){
    "use strict";

	$(document).on( 'svx-wcwar_overrides-save', function(e,f) {

		var r = {};
		svx.skips = [];

        svx.skips.push('_wcwar_tags');
		svx.skips.push('_wcwar_categories');

        r.product_tag = {};
		r.product_cat = {};

		var c=0;
		$.each( svx.settings['_wcwar_tags'].val, function(i,g) {
			r.product_tag[c] = {
				name:u(g.name)?g.name:'Not set',
				term:u(g.term),
				preset:u(g.preset)?g.preset:false,
				order:c
			}
			c++;
		} );

		var c=0;
		$.each( svx.settings['_wcwar_categories'].val, function(i,g) {
			r.product_cat[u(g.term)] = {
				name:u(g.name)?g.name:'Not set',
				term:u(g.term),
				preset:u(g.preset)?g.preset:false,
				order:c
			}
			c++;
		} );

		f.save_val = r;

    } );

    $(document).on( 'svx-wcwar_overrides-load', function(e,f) {

		svx.settings['_wcwar_tags'].val = [];
		svx.settings['_wcwar_categories'].val = [];

		var c=0;
		$.each( f.val.product_tag, function(i,g) {
			svx.settings['_wcwar_tags'].val[c] = {
				name:u(g.name)!==false?g.name:'Not set',
				term:u(g.term)!==false?g.term:i,
				preset:u(g.preset)!==false?g.preset:g,
			}
			c++;
		} );

		c=0;
		$.each( f.val.product_cat, function(i,g) {
			svx.settings['_wcwar_categories'].val[typeof g.order!=='undefined'?g.order:c] = {
				name:u(g.name)!==false?g.name:g,
				term:u(g.term)!==false?g.term:i,
				preset:u(g.preset)!==false?g.preset:g,
			}
			c++;
		} );

    } );

    $(document).on('svx-wcwar_return_form-save', function(e,f) {
        _build_from_save_q(e,f);
    } );

    $(document).on('svx-wcwar_form-save', function(e,f) {
        _build_from_save_q(e,f);
    } );

    function _build_from_save_q(e,f) {
        var s = {
            fields: [],
        };

        $.each( f.val, function(i,o) {
            s.fields.push(_build_save_q(i,o));
        } );

        f.save_val = JSON.stringify(s,null,2);
    };

    function _build_save_q(i,o) {
        var q = {
            'cid' : 'wf'+i,
            'field_type' : u(o.type)?o.type:'text',
            'label' : u(o.name)?o.name:'',
            'description' : u(o.desc)?o.desc:'',
            'required' : u(o.required)=='yes'? true : false,
        };

        if ( typeof o.options !== 'undefined' ) {
            q.field_options = {
                options : [],
            };

            $.each( o.options, function(n,w) {
                q.field_options.options.push( {
                    'label' : u(w.name)?w.name:'',
                    'type' : '',
                } );
            } );
        }

        return q;
    }


    $(document).on('svx-wcwar_return_form-load', function(e,f) {
        if ( !f.val ) {
            f.val = '{"fields":[{"label":"Why are you returning this item?","field_type":"paragraph","required":true,"cid":"c10"}]}';
        }

        _get_from_json(e,f,f.val);
    } );

    $(document).on('svx-wcwar_form-load', function(e,f) {
        if ( !f.val ) {
            f.val = '{"fields":[{"label":"Reason for requesting warranty","field_type":"radio","required":true,"field_options":{"options":[{"label":"Item was damaged","checked":false},{"label":"Item was broken","checked":false},{"label":"Nothing wrong, just returning","checked":false}]},"cid":"c10"}]}';
        }

        _get_from_json(e,f,f.val);
    } );

    function _get_from_json(e,f,d) {
        d = $.parseJSON(d);

        f.val = [];

        $.each( d.fields, function(i,o) {
            f.val.push(_build_load_q(i,o));
        } );
    }

    function _build_load_q(i,o) {
        var q = {
            'type' : u(o.field_type)?o.field_type:'text',
            'name' : u(o.label)?o.label:'',
            'desc' : u(o.description)?o.description:'',
            'required' : u(o.required)? 'yes' : 'no',
        };

        if ( typeof o.field_options !== 'undefined' ) {
            q.options = [];

            $.each( o.field_options.options, function(n,w) {
                q.options.push( {
                    'name' : u(w.label)?w.label:'',
                    'type' : '',
                } );
            } );
        }

        return q;
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

    function u(e) {
        return typeof e == 'undefined' || e == 'false' ? false : e;
    }

    function uE(e) {
        return typeof e == 'undefined' || e == '' ? false : e;
    }

})(jQuery);