/*!
 * imagesLoaded PACKAGED v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return n.indexOf(t)==-1&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return n!=-1&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){i=i.slice(0),t=t||[];for(var n=this._onceEvents&&this._onceEvents[e],o=0;o<i.length;o++){var r=i[o],s=n&&n[r];s&&(this.off(e,r),delete n[r]),r.apply(this,t)}return this}},t.allOff=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){if(Array.isArray(e))return e;var t="object"==typeof e&&"number"==typeof e.length;return t?d.call(e):[e]}function o(e,t,r){if(!(this instanceof o))return new o(e,t,r);var s=e;return"string"==typeof e&&(s=document.querySelectorAll(e)),s?(this.elements=n(s),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(this.check.bind(this))):void a.error("Bad element for imagesLoaded "+(s||e))}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console,d=Array.prototype.slice;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&u[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var u={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});


(function($){

	"use strict";

	if ( pl.options.isotope == 'disable' ) {
		$('body').addClass('pl-fluid');
	}
	if ( $('body').hasClass('shopkit') || $('body').hasClass('bila') ) {
		$('.products').addClass('pl-fix');
	}

	$(document).on( 'click', function(e) {
		if ( e.target && e.target.matches('.pl-add-to-cart') ) {
			if ( e.target.matches('.pl-product-type-external') ) {
				return false;
			}
			else if ( e.target.matches('.pl-product-type-variable') ) {
				if ( e.target.matches('.is-addable') ) {
					add_to_cart(e);
					return false;
				}
			}
			else {
				add_to_cart(e);
				return false;
			}
		}
		if ( e.target && e.target.matches('.pl-grid') ) {
			switch_loop_display(e,'grid');
		}
		if ( e.target && e.target.matches('.pl-table') ) {
			switch_loop_display(e,'table');
		}
		if ( e.target && e.target.matches('.pl-gallery-thumbnail') ) {
			switch_image(e);
			return false;
		}
	} );

	function u(e) {
		return typeof e == 'undefined' ? false : e;
	}

	function switch_image(e) {
		$(e.target).closest('.pl-figure').find('.pl-product-image').replaceWith(e.target.parentNode.children[1].outerHTML);
		$(e.target).closest('.pl-figure').find('.pl-gallery-img-main:first').replaceWith(e.target.parentNode.children[1].outerHTML);

		var place =  u(e.target.parentNode.dataset.id) === false ? '' : '#'+e.target.parentNode.dataset.id+' ';
		call_isotopes(place);
	}

	function switch_loop_display(e,f) {
		var place =  u(e.target.parentNode.dataset.id) === false ? '' : '#'+e.target.parentNode.dataset.id+' ';

		$(place+'.pl-cart, '+place+'.pl-checkout').remove();
		$(place+'.pl-buttons-added').removeClass('pl-buttons-added');
		if ( f == 'grid' ) {
			$(place+'.pl-product').addClass('pl-grid');
			$(place+'.pl-product').removeClass('pl-table');
		}
		else if ( f == 'table' ) {
			$(place+'.pl-product').addClass('pl-table');
			$(place+'.pl-product').removeClass('pl-grid');
		}
		if ( pl.options.isotope !== 'disable' ) {
			call_isotopes(place);
		}
		if ( u(pl.options.session) == 'yes' ) {
			$.when( pl_ajax( [ 'grid_table', f ] ) ).done( function(response) { } );
		}
	}

	function call_isotopes(e) {
		setTimeout( function() {
			if ( e == '' ) {
				$.each( isotopes, function(i,g) {
					isotopes[i].isotope('layout');
				} );
			}
			else {
				isotopes[$(e+'.pl-loops[data-id]:first').attr('data-id')].isotope('layout');
			}
		}, 250 );
	}
	
	function add_to_cart(e) {

		e.target.classList.add('pl-adding-to-cart');

		var k = e.target.classList.contains(pl.options.button);

		var data = {
			action: 'pl_add_to_cart',
			product_id: e.target.dataset.product_id,
			quantity: 1
		};

		$(document).trigger( 'product_loops_add_to_cart', [$(e.target), data] );

		$.ajax({
			type: 'POST',
			url: pl.ajax,
			data: data,
			success: function(response) {

				if ( !response ) {
					e.target.classList.remove('pl-adding-to-cart');
					return;
				}

				window.location.toString().replace( 'add-to-cart', 'added-to-cart' );

				if ( response.error && response.product_url ) {
					window.location = response.product_url;
					e.target.classList.remove('pl-adding-to-cart');
					return;
				}

				var fragments = response.fragments;
				var cart_hash = response.cart_hash;

				if ( fragments ) {
					$.each(fragments, function(key, value) {
						$(key).replaceWith(value);
					});
				}

				e.target.classList.remove('pl-adding-to-cart');
	
				var f = e.target.innerHTML.toString();
		
				setTimeout( function() {
					e.target.innerHTML = f;
				}, 2000 );
		
				e.target.innerHTML = pl.localize.added;

				if ( !e.target.classList.contains('pl-buttons-added') ) {
					if ( $(e.target).closest('.pl-table').length>0 ) {
						$(e.target).before('<a href="'+pl.cart+'" class="pl-button pl-cart '+(u(k)?pl.options.button:'')+'">&nbsp;</a><a href="'+pl.checkout+'" class="pl-button pl-checkout '+(u(k)?pl.options.button:'')+'">&nbsp;</a>');
					}
					else {
						$(e.target).after('<a href="'+pl.cart+'" class="pl-button pl-cart '+(u(k)?pl.options.button:'')+'">&nbsp;</a><a href="'+pl.checkout+'" class="pl-button pl-checkout '+(u(k)?pl.options.button:'')+'">&nbsp;</a>');
					}
					e.target.classList.add('pl-buttons-added');
				}

				$('body').trigger( 'added_to_cart', [ fragments, cart_hash ] );
			},
			error: function() {
				alert('AJAX Error!');
			}
		});

		return false;

	}


	var ajax = 'not_active';

	function pl_ajax( opt ) {

		var data = {
			action: 'wcmnplajax',
			data: opt
		};

		return $.ajax({
			type: 'POST',
			url: pl.ajax,
			data: data,
			success: function(response) {
				if (response) {
					ajax = 'notactive';
				}
			},
			error: function() {
				//alert(shopkit.locale.ajax_error);
				ajax = 'notactive';
			}
		});

	}

	$(document).on( 'click', '.pl-quickview-close', function() {
		$(this).parent().fadeOut(200, function() {
			$('.pl-quickview-trigger.pl-active').removeClass('pl-active');
			$(this).remove();
		});
	});

	$(document).on( 'click', '.pl-quickview-trigger', function() {

		if ( $('.pl-quickview').length>0 ) {
			return false;
		}

		if ( ajax == 'active' ) {
			return false;
		}

		$(this).addClass('pl-active');
		ajax = 'active';

		$.when( pl_ajax( [ 'quickview', $(this).data('id'), $(this).closest('.pl-product').attr('class').match(/pllp[\w-]*\b/)[0] ] ) ).done( function(response) {
			$('body').append(response);
			$('.pl-quickview-product').addClass( $('.pl-quickview-trigger.pl-active').closest('.pl-product').attr('class').match(/pllp[\w-]*\b/)[0] );
			$('.pl-quickview-product').addClass( $('.pl-quickview-trigger.pl-active').closest('.pl-product').attr('class').match(/pl[\w-]loop[\w-]*\b/)[0] );
		});

		return false;
	});

	if ( pl.options.isotope !== 'disable' ) {
		var isotopes = [];
		$.each( $('.pl-loops'), function(i,e) {
			var container = $(this);
			container.imagesLoaded( function() {
				if ( container.find('.pl-product').length>0 ) {
					isotopes.push(container.isotope({layoutMode:pl.options.isotope}));
				}
			} ).attr( 'data-id', i );
		} );
	}


	$.loadScript = function (url, callback) {
		$.ajax({
			url: url,
			dataType: 'script',
			success: callback,
			async: false
		});
	};

})(jQuery);