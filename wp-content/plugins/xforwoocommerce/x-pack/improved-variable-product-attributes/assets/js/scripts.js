/*!
 * accounting.js v0.4.2, copyright 2014 Open Exchange Rates, MIT license, http://openexchangerates.github.io/accounting.js
 */
(function(p, z) {
    function q(a) { return !!("" === a || a && a.charCodeAt && a.substr) }

    function m(a) { return u ? u(a) : "[object Array]" === v.call(a) }

    function r(a) { return "[object Object]" === v.call(a) }

    function s(a, b) {
        var d, a = a || {},
            b = b || {};
        for (d in b) b.hasOwnProperty(d) && null == a[d] && (a[d] = b[d]);
        return a
    }

    function j(a, b, d) {
        var c = [],
            e, h;
        if (!a) return c;
        if (w && a.map === w) return a.map(b, d);
        for (e = 0, h = a.length; e < h; e++) c[e] = b.call(d, a[e], e, a);
        return c
    }

    function n(a, b) { a = Math.round(Math.abs(a)); return isNaN(a) ? b : a }

    function x(a) { var b = c.settings.currency.format; "function" === typeof a && (a = a()); return q(a) && a.match("%v") ? { pos: a, neg: a.replace("-", "").replace("%v", "-%v"), zero: a } : !a || !a.pos || !a.pos.match("%v") ? !q(b) ? b : c.settings.currency.format = { pos: b, neg: b.replace("%v", "-%v"), zero: b } : a }
    var c = { version: "0.4.1", settings: { currency: { symbol: "$", format: "%s%v", decimal: ".", thousand: ",", precision: 2, grouping: 3 }, number: { precision: 0, grouping: 3, thousand: ",", decimal: "." } } },
        w = Array.prototype.map,
        u = Array.isArray,
        v = Object.prototype.toString,
        o = c.unformat = c.parse = function(a, b) {
            if (m(a)) return j(a, function(a) { return o(a, b) });
            a = a || 0;
            if ("number" === typeof a) return a;
            var b = b || ".",
                c = RegExp("[^0-9-" + b + "]", ["g"]),
                c = parseFloat(("" + a).replace(/\((.*)\)/, "-$1").replace(c, "").replace(b, "."));
            return !isNaN(c) ? c : 0
        },
        y = c.toFixed = function(a, b) {
            var b = n(b, c.settings.number.precision),
                d = Math.pow(10, b);
            return (Math.round(c.unformat(a) * d) / d).toFixed(b)
        },
        t = c.formatNumber = c.format = function(a, b, d, i) {
            if (m(a)) return j(a, function(a) { return t(a, b, d, i) });
            var a = o(a),
                e = s(r(b) ? b : { precision: b, thousand: d, decimal: i }, c.settings.number),
                h = n(e.precision),
                f = 0 > a ? "-" : "",
                g = parseInt(y(Math.abs(a || 0), h), 10) + "",
                l = 3 < g.length ? g.length % 3 : 0;
            return f + (l ? g.substr(0, l) + e.thousand : "") + g.substr(l).replace(/(\d{3})(?=\d)/g, "$1" + e.thousand) + (h ? e.decimal + y(Math.abs(a), h).split(".")[1] : "")
        },
        A = c.formatMoney = function(a, b, d, i, e, h) {
            if (m(a)) return j(a, function(a) { return A(a, b, d, i, e, h) });
            var a = o(a),
                f = s(r(b) ? b : { symbol: b, precision: d, thousand: i, decimal: e, format: h }, c.settings.currency),
                g = x(f.format);
            return (0 < a ? g.pos : 0 > a ? g.neg : g.zero).replace("%s", f.symbol).replace("%v", t(Math.abs(a), n(f.precision), f.thousand, f.decimal))
        };
    c.formatColumn = function(a, b, d, i, e, h) {
        if (!a) return [];
        var f = s(r(b) ? b : { symbol: b, precision: d, thousand: i, decimal: e, format: h }, c.settings.currency),
            g = x(f.format),
            l = g.pos.indexOf("%s") < g.pos.indexOf("%v") ? !0 : !1,
            k = 0,
            a = j(a, function(a) {
                if (m(a)) return c.formatColumn(a, f);
                a = o(a);
                a = (0 < a ? g.pos : 0 > a ? g.neg : g.zero).replace("%s", f.symbol).replace("%v", t(Math.abs(a), n(f.precision), f.thousand, f.decimal));
                if (a.length > k) k = a.length;
                return a
            });
        return j(a, function(a) { return q(a) && a.length < k ? l ? a.replace(f.symbol, f.symbol + Array(k - a.length + 1).join(" ")) : Array(k - a.length + 1).join(" ") + a : a })
    };
    if ("undefined" !== typeof exports) {
        if ("undefined" !== typeof module && module.exports) exports = module.exports = c;
        exports.accounting = c
    } else "function" === typeof define && define.amd ? define([], function() { return c }) : (c.noConflict = function(a) {
        return function() {
            p.accounting = a;
            c.noConflict = z;
            return c
        }
    }(p.accounting), p.accounting = c)
})(this);

(function($) {

    "use strict";

    var ivpa_strings = {};

    ivpa_strings.variable = typeof ivpa !== 'undefined' ? ivpa.localization.variable : '';
    ivpa_strings.simple = typeof ivpa !== 'undefined' ? ivpa.localization.simple : '';
    ivpa_strings.injs = {};
    ivpa_strings.sizes = {};

    if (!Object.keys) {
        Object.keys = function(obj) {
            var keys = [],
                k;
            for (k in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, k)) {
                    keys.push(k);
                }
            }
            return keys;
        };
    }

    function getObjects(obj, key, val) {
        var objects = {};

        for (var i in obj) {
            if (!obj.hasOwnProperty(i)) continue;
            if (typeof obj[i] == 'object') {
                objects = objects.concat(getObjects(obj[i], key, val));
            } else if (i == key && obj[key] == val || obj[key] == '') {
                objects[i] = obj;
            }
        }

        return objects;
    }

    function baseNameHTTP(str) {
        var base = new String(str);
        if (base.lastIndexOf('.') != -1) {
            base = base.substring(0, base.lastIndexOf('.'));
        }
        return base.replace(/(^\w+:|^)\/\//, '');
    }

    function baseName(str) {
        var base = new String(str);
        if (base.lastIndexOf('.') != -1) {
            base = base.substring(0, base.lastIndexOf('.'));
        }
        return base;
    }

    var currVariations = {};

    function ivpa_register_310() {

        if ($('.ivpa-register:not(.ivpa_registered)').length > 0) {

            var $dropdowns = $('#ivpa-content .ivpa_term, .ivpa-info-box');

            $dropdowns
                .on('mouseover', function() {
                    var $this = $(this);

                    if ($this.prop('hoverTimeout')) {
                        $this.prop('hoverTimeout', clearTimeout($this.prop('hoverTimeout')));
                    }

                    $this.prop('hoverIntent', setTimeout(function() {
                        $this.addClass('ivpa_hover');
                    }, 250));
                })
                .on('mouseleave', function() {
                    var $this = $(this);

                    if ($this.prop('hoverIntent')) {
                        $this.prop('hoverIntent', clearTimeout($this.prop('hoverIntent')));
                    }

                    $this.prop('hoverTimeout', setTimeout(function() {
                        $this.removeClass('ivpa_hover');
                    }, 250));
                });

            $('.ivpa-register:not(.ivpa_registered):visible').each(function() {

                if ($(this).find('.ivpa_showonly').length == 0) {
                    var curr_element = $(this);
                    var curr_id = curr_element.attr('data-id');

                    if (typeof currVariations[curr_id] == 'undefined') {
                        currVariations[curr_id] = $.parseJSON(curr_element.attr('data-variations'));
                    }

                    curr_element.addClass('ivpa_registered');

                    _run_price(__get_container(curr_element));

                    if (curr_element.find('.ivpa_attribute .ivpa_term.ivpa_clicked').length > 0) {
                        curr_element.find('.ivpa_attribute .ivpa_term.ivpa_clicked').each(function() {
                            $(this).closest('.ivpa_attribute').addClass('ivpa_activated').addClass('ivpa_clicked');
                            call_ivpa($(this), curr_element, currVariations[curr_id], 'register');
                            if ($(this).hasClass('ivpa_outofstock')) {
                                $(this).removeClass('ivpa_clicked');
                            }
                        });
                    } else {
                        curr_element.find('.ivpa_attribute .ivpa_term:first').each(function() {
                            call_ivpa($(this), curr_element, currVariations[curr_id], 'register');
                        });
                    }
                }

            });

        }
    }

    $(document).ready(function() {
        setTimeout(function() { ivpa_register_310(); }, 250);
    });

    if (ivpa.outofstock == 'clickable') {
        var ivpaElements = '.ivpa_attribute:not(.ivpa_showonly) .ivpa_term';
    } else {
        var ivpaElements = '.ivpa_attribute:not(.ivpa_showonly) .ivpa_term:not(.ivpa_outofstock)';
    }
    if (ivpa.disableunclick == 'yes') {
        ivpaElements += ':not(.ivpa_clicked)';
    }

    var ivpaProcessing = false;
    $(document).on('click', ivpaElements, function() {

        if (ivpaProcessing === true) {
            return false;
        }

        ivpaProcessing = true;

        var curr_element = $(this).closest('.ivpa-register');
        var curr_id = curr_element.attr('data-id');

        if (typeof currVariations[curr_id] == 'undefined') {
            currVariations[curr_id] = $.parseJSON(curr_element.attr('data-variations'));
        }

        call_ivpa($(this), curr_element, currVariations[curr_id], 'default');
        __call_conditions($(this));
    });

    function call_ivpa(curr_this, curr_element, curr_variations, action) {

        var curr_el = curr_this;
        var curr_el_term = curr_el.attr('data-term');

        var curr = curr_el.closest('.ivpa_attribute');
        var curr_attr = curr.attr('data-attribute');

        var main = curr.closest('.ivpa-register');

        curr_element.attr('data-selected', '');

        if (ivpa.backorders == 'yes') {
            curr_element.find('.ivpa_attribute .ivpa_term.ivpa_backorder').removeClass('ivpa_backorder');
            curr_element.find('.ivpa_attribute .ivpa_term.ivpa_backorder_not').removeClass('ivpa_backorder_not');
        }

        if (action == 'default') {

            if (!curr.hasClass('ivpa_activated')) {
                curr.addClass('ivpa_activated');
            } else if (curr.find('.ivpa_term.ivpa_clicked').length == 0) {
                curr.removeClass('ivpa_activated');
            }

            var curr_selectbox = $(document.getElementById(curr_attr));
            if (!curr_el.hasClass('ivpa_clicked')) {
                curr.find('.ivpa_term').removeClass('ivpa_clicked');
                curr_el.addClass('ivpa_clicked');
                curr.addClass('ivpa_clicked');
                if (curr_element.attr('id') == 'ivpa-content') {
                    curr_selectbox.trigger('focusin');
                    if (curr_selectbox.find('option[value="' + curr_el_term + '"]').length > 0) {
                        curr_selectbox.val(curr_el_term).trigger('change');
                    } else {
                        curr_selectbox.val('').trigger('focusin').trigger('change').val(curr_el_term).trigger('change');
                    }
                }
                if (curr.hasClass('ivpa_selectbox')) {

                    curr.find('.ivpa_select_wrapper_inner').scrollTop(0).removeClass('ivpa_selectbox_opened');
                    var sel = curr.find('span[data-term="' + curr_el_term + '"]').text();
                    if (typeof ivpa_strings.injs[curr_attr] == 'undefined') {
                        ivpa_strings.injs[curr_attr] = curr.find('.ivpa_select_wrapper_inner .ivpa_title').text();
                    }
                    curr.find('.ivpa_select_wrapper_inner .ivpa_title').text(sel);
                }
            } else {
                curr_el.removeClass('ivpa_clicked');
                curr.removeClass('ivpa_clicked');
                if (curr_element.attr('id') == 'ivpa-content') {
                    curr_selectbox.find('option:selected').removeAttr('selected').trigger('change');
                }
                if (curr.hasClass('ivpa_selectbox')) {

                    curr.find('.ivpa_select_wrapper_inner').scrollTop(0).removeClass('ivpa_selectbox_opened');
                    if (typeof ivpa_strings.injs[curr_attr] !== 'undefined') {
                        curr.find('.ivpa_select_wrapper_inner .ivpa_title').text(ivpa_strings.injs[curr_attr]);
                    } else {
                        curr.find('.ivpa_select_wrapper_inner .ivpa_title').text(ivpa.localization.select);
                    }
                }
            }
        }

        $.each(main.find('.ivpa_attribute'), function() {

            var curr_keys = [];
            var curr_vals = [];
            var curr_objects = {};

            var ins_curr = $(this);
            var ins_curr_attr = ins_curr.attr('data-attribute');

            var ins_curr_par = ins_curr.closest('.ivpa-register');

            var m = 0;

            $.each(ins_curr_par.find('.ivpa_attribute:not([data-attribute="' + ins_curr_attr + '"]) .ivpa_term.ivpa_clicked'), function() {

                var sep_curr = $(this);
                var sep_curr_par = sep_curr.closest('.ivpa_attribute');

                var a = sep_curr_par.attr('data-attribute');
                var t = sep_curr.attr('data-term');

                curr_keys.push(a);
                curr_vals.push(t);

                m++;

            });

            $.each(curr_variations, function(vrl_curr_index, vrl_curr) {

                var found = false;

                var p = 0;

                $.each(curr_keys, function(l, b) {

                    var curr_set = getObjects(vrl_curr.attributes, 'attribute_' + b, curr_vals[l]);
                    if ($.isEmptyObject(curr_set) === false) {
                        p++;
                    }
                });

                if (p === m) {
                    found = true;
                }

                if (found === true && vrl_curr.is_in_stock === true) {
                    $.each(vrl_curr.attributes, function(hlp_curr_index, hlp_curr_item) {

                        var hlp_curr_attr = hlp_curr_index.replace('attribute_', '');

                        if (ins_curr_attr == hlp_curr_attr) {

                            if (typeof curr_objects[hlp_curr_attr] == 'undefined') {
                                curr_objects[hlp_curr_attr] = [];
                            }

                            if ($.inArray(hlp_curr_item, curr_objects[hlp_curr_attr]) == -1) {
                                curr_objects[hlp_curr_attr].push(hlp_curr_item);
                            }

                        }

                    });

                }

            });

            if ($.isEmptyObject(curr_objects) === false) {
                $.each(curr_objects, function(curr_stock_attr, curr_stock_item) {
                    curr_element.find('.ivpa_attribute[data-attribute="' + curr_stock_attr + '"] .ivpa_term').removeClass('ivpa_instock').removeClass('ivpa_outofstock');
                    if (curr_stock_item.length == 1 && curr_stock_item[0] == '') {
                        curr_element.find('.ivpa_attribute[data-attribute="' + curr_stock_attr + '"] .ivpa_term').addClass('ivpa_instock');
                    } else {
                        $.each(curr_stock_item, function(curr_stock_id, curr_stock_term) {
                            if (curr_stock_term !== '') {
                                curr_element.find('.ivpa_attribute[data-attribute="' + curr_stock_attr + '"] .ivpa_term[data-term="' + curr_stock_term + '"]').addClass('ivpa_instock');
                            } else {
                                curr_element.find('.ivpa_attribute[data-attribute="' + curr_stock_attr + '"] .ivpa_term:not(.ivpa_instock)').addClass('ivpa_instock');
                            }
                        });
                        curr_element.find('.ivpa_attribute[data-attribute="' + curr_stock_attr + '"] .ivpa_term:not(.ivpa_instock)').addClass('ivpa_outofstock');
                    }
                });
            }

        });

        if (curr_element.hasClass('ivpa-stepped')) {
            curr_element.find('.ivpa_attribute, .ivpa_custom_option').eq(0).show();
            check_steps(curr);
        }

        if (ivpa.backorders == 'yes') {

            if (curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length < 2) {

                if (curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length == 0) {
                    var activeElements = curr_element.find('.ivpa_attribute.ivpa_clicked:not([data-attribute="' + curr_attr + '"])');
                    var activeLook = '.ivpa_attribute[data-attribute="' + curr_attr + '"]';
                } else {
                    var activeElements = curr_element.find('.ivpa_attribute.ivpa_clicked');
                    var activeLook = '.ivpa_attribute:not(.ivpa_clicked)';
                }

                var activeVar = {};

                var activeCount = 0;
                $.each(activeElements, function() {
                    activeVar['attribute_' + $(this).attr('data-attribute')] = $(this).find('span.ivpa_clicked').attr('data-term');
                    activeCount++;
                });

                $.each(curr_variations, function(vrl_curr_index, vrl_curr) {

                    if ($.isEmptyObject(vrl_curr.attributes) === false) {
                        var cNt = 0;
                        $.each(activeVar, function(u3, o5) {
                            if (typeof vrl_curr.attributes[u3] !== 'undefined' && vrl_curr.attributes[u3] == o5 || typeof vrl_curr.attributes[u3] !== 'undefined' && vrl_curr.attributes[u3] == '') {
                                cNt++;
                            }
                        });

                        if (activeCount == cNt) {
                            if (vrl_curr.backorders_allowed === true && vrl_curr.is_in_stock === true && vrl_curr.stock == 0 ) {
                                var attrChek = 'attribute_' + curr_element.find(activeLook).attr('data-attribute');
                                if (typeof vrl_curr.attributes[attrChek] !== 'undefined') {
                                    if (vrl_curr.attributes[attrChek] == '') {
                                        curr_element.find(activeLook + ' .ivpa_term:not(.ivpa_backorder)').addClass('ivpa_backorder');
                                    } else {
                                        curr_element.find(activeLook + ' .ivpa_term[data-term="' + vrl_curr.attributes[attrChek] + '"]:not(.ivpa_backorder)').addClass('ivpa_backorder');
                                    }
                                }
                            } else {
                                var attrChek = 'attribute_' + $(activeLook).attr('data-attribute');
                                if (typeof vrl_curr.attributes[attrChek] !== 'undefined') {
                                    if (vrl_curr.attributes[attrChek] == '') {
                                        curr_element.find(activeLook + ' .ivpa_term:not(.ivpa_backorder_not)').addClass('ivpa_backorder_not');
                                    } else {
                                        curr_element.find(activeLook + ' .ivpa_term[data-term="' + vrl_curr.attributes[attrChek] + '"]:not(.ivpa_backorder_not)').addClass('ivpa_backorder_not');
                                    }
                                }
                                $.each(vrl_curr.attributes, function(j3, i6) {
                                    if (j3 !== attrChek) {
                                        if (i6 == '') {
                                            curr_element.find('.ivpa_attribute[data-attribute="' + j3.replace('attribute_', '') + '"] .ivpa_term:not(.ivpa_backorder_not)').addClass('ivpa_backorder_not');
                                        } else {
                                            curr_element.find('.ivpa_attribute[data-attribute="' + j3.replace('attribute_', '') + '"] .ivpa_term[data-term="' + i6 + '"]:not(.ivpa_backorder_not)').addClass('ivpa_backorder_not');
                                        }
                                    }
                                });
                            }
                        }
                    }
                });
            }

        }

        if (curr_element.attr('id') !== 'ivpa-content') {
            var container = curr_element.closest(ivpa.settings.archive_selector);

            if (curr_element.find('.ivpa_attribute').length > 0 && curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length == 0) {

                var curr_elements = curr_element.find('.ivpa_attribute.ivpa_clicked');
                var curr_var = {};

                curr_elements.each(function() {
                    curr_var['attribute_' + $(this).attr('data-attribute')] = $(this).find('span.ivpa_clicked').attr('data-term');
                });

                var i = curr_element.find('.ivpa_attribute').length;

                $.each(curr_variations, function(t, f) {

                    var o = 0;
                    var found = false;

                    $.each(curr_var, function(w, c) {
                        var curr_set = getObjects(f.attributes, w, c);

                        if ($.isEmptyObject(curr_set) === false) {
                            o++;
                        }
                    });

                    if (o === i) {
                        found = true;
                    }

                    if (found === true && f.is_in_stock === true) {

                        curr_element.attr('data-selected', f.variation_id);

                        var image = f.ivpa_image;

                        if (ivpa.imageattributes.length == 0 || $.inArray(curr_attr, ivpa.imageattributes) > -1) {

                            if (image != '') {

                                var imgPreload = new Image();
                                $(imgPreload).attr({
                                    src: image
                                });

                                if (imgPreload.complete || imgPreload.readyState === 4) {

                                } else {

                                    container.addClass('ivpa-image-loading');
                                    container.fadeTo(100, 0.7);

                                    $(imgPreload).load(function(response, status, xhr) {
                                        if (status == 'error') {
                                            console.log('101 Error!');
                                        } else {
                                            container.removeClass('ivpa-image-loading');
                                            container.fadeTo(100, 1);
                                        }
                                    });
                                }

                                if (container.find('img[data-default-image]').length > 0) {
                                    var archive_image = container.find('img[data-default-image]');
                                } else {
                                    var archive_image = container.find('img.attachment-woocommerce_thumbnail:first, img.wp-post-image:first');

                                    if (archive_image.next().is('img')) {
                                        archive_image.push(archive_image.next());
                                    }
                                }

                                var rmbrSet = '';
                                $.each(archive_image, function(i, e) {

                                    var defaultImg = curr_element.attr('data-image');
                                    var newImg = image;
                                    var srcset = $(this).attr('srcset');

                                    if (!$(this).attr('data-default-image')) {
                                        $(this).attr('data-default-image', (i == 0 ? defaultImg : $(this).attr('src')));
                                    }

                                    var thisRc = $(this).attr('src');
                                    var thisRcFixed = getUrlNoSuffix(thisRc);
                                    $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));
                                    //$(this).attr('src',newImg);

                                    var shopKitSupport = $(this).parent();
                                    if (shopKitSupport.is('.shopkit-loop-image-inner')) {
                                        if (!shopKitSupport.attr('data-default-bg')) {
                                            shopKitSupport.attr('data-default-bg', shopKitSupport.css('background'));
                                        }
                                        var stringRplc = shopKitSupport.css('background').split('"');
                                        shopKitSupport.css({ background: stringRplc[0] + newImg + stringRplc[2] });
                                    }

                                    if (typeof srcset != 'undefined') {

                                        var defaultSrc = $(this).attr('data-default-srcset');
                                        if (typeof defaultSrc == 'undefined') {
                                            $(this).attr('data-default-srcset', srcset);
                                            defaultSrc = srcset;
                                        }

                                        if (i == 0) {

                                            var re = new RegExp(baseNameHTTP(defaultImg), 'g');
                                            srcset = defaultSrc.replace(re, baseNameHTTP(newImg));
                                            $(this).attr('srcset', srcset);
                                            rmbrSet = srcset;
                                        } else {
                                            $(this).attr('srcset', rmbrSet);
                                        }

                                    }

                                });

                            } else {

                                var archive_image = container.find('img[data-default-image]');
                                if (archive_image.length > 0) {
                                    archive_image.each(function(i, e) {

                                        var defaultImg = $(this).attr('src');
                                        var newImg = $(this).attr('data-default-image');

                                        var thisRc = $(this).attr('src');
                                        var thisRcFixed = getUrlNoSuffix(thisRc);
                                        $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));
                                        //$(this).attr('src', newImg);

                                        var shopKitSupport = $(this).parent();
                                        if (shopKitSupport.attr('data-default-bg')) {
                                            shopKitSupport.css({ background: shopKitSupport.attr('data-default-bg') });
                                        }

                                        var srcset = $(this).attr('srcset');
                                        if (typeof srcset != 'undefined') {
                                            var re = new RegExp(defaultImg, 'g');
                                            srcset = srcset.replace(re, baseNameHTTP(newImg));
                                            $(this).attr('srcset', $(this).attr('data-default-srcset')).removeAttr('data-default-srcset');
                                        }

                                    });

                                }
                            }

                        }

                        if (ivpa.backorders == 'yes') {
                            if (curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length == 0 && curr_element.find('.ivpa_attribute[data-attribute="' + curr_attr + '"] .ivpa_term.ivpa_clicked.ivpa_backorder:not(.ivpa_backorder_not)').length > 0 && f.availability_html !== '' && curr_element.find('.ivpa_backorder_allow').length == 0) {
                                var avaHtml = '<div class="ivpa_backorder_allow">' + f.availability_html + '</div>';
                                curr_element.append($(avaHtml).fadeIn());
                            } else {
                                if (curr_element.find('.ivpa_attribute[data-attribute="' + curr_attr + '"] .ivpa_term.ivpa_clicked.ivpa_backorder:not(.ivpa_backorder_not)').length == 0) {
                                    if (curr_element.find('.ivpa_backorder_allow').length > 0) {
                                        curr_element.find('.ivpa_backorder_allow').remove();
                                    }
                                }
                            }
                        }

                        /*if ( action !== 'register' ) {*/
                        check_selections(curr_element);
                        /*}*/

                        _run_price(__get_container(curr_element));

                        ivpaProcessing = false;
                        return false;


                    }
                });

            } else {

                if (curr_element.find('.ivpa_attribute.ivpa_clicked').length > 0) {

                    var curr_elements = curr_element.find('.ivpa_attribute.ivpa_clicked');
                    var curr_var = {};

                    var vL = 0;
                    curr_elements.each(function() {
                        curr_var['attribute_' + $(this).attr('data-attribute')] = $(this).find('span.ivpa_clicked').attr('data-term');
                        vL++;
                    });

                    var i = curr_element.find('.ivpa_attribute').length;
                    var curr_variations_length = curr_variations.length;
                    var found = [];
                    var iL = 0;

                    var hasCount = 0;
                    curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').each(function() {
                        hasCount = $(this).find('.ivpa_term').length * (hasCount == 0 ? 1 : hasCount);
                    });

                    $.each(curr_variations, function(t, f) {

                        var o = 0;
                        $.each(curr_var, function(w, c) {
                            var curr_set = getObjects(f.attributes, w, c);
                            if ($.isEmptyObject(curr_set) === false) {
                                o++;
                            }
                        });

                        if (vL == o) {
                            if ($.inArray(f.ivpa_image, found) < 0) {
                                found.push(f.ivpa_image);
                                iL++;
                            }
                        }

                        if (!--curr_variations_length) {

                            if (typeof found[0] !== "undefined" && (hasCount !== iL || curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length == 1) !== false) {

                                var image = found[0];

                                if (ivpa.imageattributes.length == 0 || $.inArray(curr_attr, ivpa.imageattributes) > -1) {

                                    if (image != '') {

                                        var imgPreload = new Image();
                                        $(imgPreload).attr({
                                            src: image
                                        });

                                        if (imgPreload.complete || imgPreload.readyState === 4) {

                                        } else {

                                            container.addClass('ivpa-image-loading');
                                            container.fadeTo(100, 0.7);

                                            $(imgPreload).load(function(response, status, xhr) {
                                                if (status == 'error') {
                                                    console.log('101 Error!');
                                                } else {
                                                    container.removeClass('ivpa-image-loading');
                                                    container.fadeTo(100, 1);
                                                }
                                            });
                                        }

                                        if (container.find('img[data-default-image]').length > 0) {
                                            var archive_image = container.find('img[data-default-image]');
                                        } else {
                                            var archive_image = container.find('img.attachment-woocommerce_thumbnail:first, img.wp-post-image:first');
                                            if (archive_image.next().is('img')) {
                                                archive_image.push(archive_image.next());
                                            }
                                        }

                                        var rmbrSet = '';
                                        $.each(archive_image, function(i, e) {

                                            var defaultImg = curr_element.attr('data-image');
                                            var newImg = image;
                                            var srcset = $(this).attr('srcset');

                                            if (!$(this).attr('data-default-image')) {
                                                $(this).attr('data-default-image', defaultImg);
                                            }
                                            var thisRc = $(this).attr('src');
                                            var thisRcFixed = getUrlNoSuffix(thisRc);
                                            $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));

                                            var shopKitSupport = $(this).parent();
                                            if (shopKitSupport.is('.shopkit-loop-image-inner')) {
                                                if (!shopKitSupport.attr('data-default-bg')) {
                                                    shopKitSupport.attr('data-default-bg', shopKitSupport.css('background'));
                                                }
                                                var stringRplc = shopKitSupport.css('background').split('"');
                                                shopKitSupport.css({ background: stringRplc[0] + newImg + stringRplc[2] });
                                            }

                                            if (typeof srcset != 'undefined') {

                                                var defaultSrc = $(this).attr('data-default-srcset');
                                                if (typeof defaultSrc == 'undefined') {
                                                    $(this).attr('data-default-srcset', srcset);
                                                    defaultSrc = srcset;
                                                }

                                                if (i == 0) {

                                                    var re = new RegExp(baseNameHTTP(defaultImg), 'g');
                                                    srcset = defaultSrc.replace(re, baseNameHTTP(newImg));
                                                    $(this).attr('srcset', srcset);
                                                    rmbrSet = srcset;
                                                } else {
                                                    $(this).attr('srcset', rmbrSet);
                                                }

                                            }

                                        });

                                    } else {

                                        var archive_image = container.find('img[data-default-image]');
                                        if (archive_image.length > 0) {
                                            archive_image.each(function(i, e) {

                                                var defaultImg = $(this).attr('src');
                                                var newImg = $(this).attr('data-default-image');

                                                var thisRc = $(this).attr('src');
                                                var thisRcFixed = getUrlNoSuffix(thisRc);
                                                $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));

                                                var shopKitSupport = $(this).parent();
                                                if (shopKitSupport.attr('data-default-bg')) {
                                                    shopKitSupport.css({ background: shopKitSupport.attr('data-default-bg') });
                                                }

                                                var srcset = $(this).attr('srcset');
                                                if (typeof srcset != 'undefined') {

                                                    var re = new RegExp(defaultImg, 'g');
                                                    srcset = srcset.replace(re, baseNameHTTP(newImg));
                                                    $(this).attr('srcset', $(this).attr('data-default-srcset')).removeAttr('data-default-srcset');

                                                }

                                            });

                                        }

                                    }

                                }

                            }

                            /*if ( action !== 'register' ) {*/
                            check_selections(curr_element);
                            /*}*/

                            _run_price(__get_container(curr_element));

                        }

                    });

                    if (ivpa.backorders == 'yes' && curr_element.find('.ivpa_backorder_allow').length > 0) {
                        curr_element.find('.ivpa_backorder_allow').remove();
                    }

                    ivpaProcessing = false;
                    return false;

                } else {

                    if (ivpa.imageattributes.length == 0 || $.inArray(curr_attr, ivpa.imageattributes) > -1) {

                        var archive_image = container.find('img[data-default-image]');
                        if (archive_image.length > 0) {
                            archive_image.each(function(i, e) {

                                var defaultImg = $(this).attr('src');
                                var newImg = $(this).attr('data-default-image');

                                var thisRc = $(this).attr('src');
                                var thisRcFixed = getUrlNoSuffix(thisRc);
                                $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));

                                var shopKitSupport = $(this).parent();
                                if (shopKitSupport.attr('data-default-bg')) {
                                    shopKitSupport.css({ background: shopKitSupport.attr('data-default-bg') });
                                }

                                var srcset = $(this).attr('srcset');
                                if (typeof srcset != 'undefined') {

                                    var re = new RegExp(defaultImg, 'g');
                                    srcset = srcset.replace(re, baseNameHTTP(newImg));
                                    $(this).attr('srcset', $(this).attr('data-default-srcset')).removeAttr('data-default-srcset');

                                }

                            });
                        }

                    }

                    /*if ( action !== 'register' ) {*/
                    check_selections(curr_element);
                    /*}*/

                    _run_price(__get_container(curr_element));

                    if (ivpa.backorders == 'yes' && curr_element.find('.ivpa_backorder_allow').length > 0) {
                        curr_element.find('.ivpa_backorder_allow').remove();
                    }

                    ivpaProcessing = false;
                    return false;

                }

            }

            ivpaProcessing = false;
            return false;

        } else {

            if (ivpa.imageswitch == 'no') {
                /*if ( action !== 'register' ) {*/
                check_selections(curr_element);
                /*}*/

                _run_price(__get_container(curr_el));

                ivpaProcessing = false;
                return false;
            }

            if (curr_element.find('.ivpa_attribute.ivpa_clicked').length > 0) {

                var curr_elements = curr_element.find('.ivpa_attribute.ivpa_clicked');
                var curr_var = {};

                var vL = 0;
                curr_elements.each(function() {
                    curr_var['attribute_' + $(this).attr('data-attribute')] = $(this).find('span.ivpa_clicked').attr('data-term');
                    vL++;
                });

                var i = curr_element.find('.ivpa_attribute').length;
                var curr_variations_length = curr_variations.length;
                var found = [];
                var iL = 0;

                var hasCount = 0;
                curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').each(function() {
                    hasCount = $(this).find('.ivpa_term').length * (hasCount == 0 ? 1 : hasCount);
                });

                $.each(curr_variations, function(t, f) {

                    var o = 0;
                    $.each(curr_var, function(w, c) {
                        var curr_set = getObjects(f.attributes, w, c);
                        if ($.isEmptyObject(curr_set) === false) {
                            o++;
                        }
                    });

                    if (vL == o) {
                        if ($.inArray(f.ivpa_image, found) < 0) {
                            found.push(f.ivpa_image);
                            iL++;
                        }
                    }

                    if (!--curr_variations_length) {

                        if (ivpa.settings.single_selector == '') {
                            var container = curr_element.closest(ivpa.settings.archive_selector).find('.product-gallery');
                            if (container.length == 0) {
                                container = curr_element.closest(ivpa.settings.archive_selector).find('.images');
                            }
                        } else {
                            var container = $(ivpa.settings.single_selector);
                        }

                        if (typeof found[0] !== "undefined" && (hasCount !== iL || curr_element.find('.ivpa_attribute:not(.ivpa_clicked)').length == 1) !== false) {

                            var image = found[0];

                            if (ivpa.imageattributes.length == 0 || $.inArray(curr_attr, ivpa.imageattributes) > -1) {

                                if (image != '') {

                                    var imgPreload = new Image();
                                    $(imgPreload).attr({
                                        src: image
                                    });

                                    if (imgPreload.complete || imgPreload.readyState === 4) {

                                    } else {

                                        container.addClass('ivpa-image-loading');
                                        container.fadeTo(100, 0.7);

                                        $(imgPreload).load(function(response, status, xhr) {
                                            if (status == 'error') {
                                                console.log('101 Error!');
                                            } else {
                                                container.removeClass('ivpa-image-loading');
                                                container.fadeTo(100, 1);
                                            }
                                        });
                                    }

                                    var defaultImg = curr_element.attr('data-image');

                                    if (container.find('img[data-default-image]').length > 0) {
                                        var archive_image = container.find('img[data-default-image]');
                                    } else {
                                        var archive_image = container.find('img[src*="' + baseNameHTTP(defaultImg) + '"]');
                                        if (archive_image.length == 0) {
                                            archive_image = container.find('img:first');
                                        }
                                    }

                                    var productGallery = $('.woocommerce-product-gallery');
                                    if (productGallery.length > 0) {
                                        var flex = productGallery.data('flexslider');
                                        if (typeof flex != 'undefined' && typeof flex.currentSlide != 'undefined' && flex.currentSlide > 0) {
                                            productGallery.flexslider(0);
                                        }
                                    }

                                    var rmbrSet = '';
                                    $.each(archive_image, function(i, e) {

                                        var newImg = image;
                                        var srcset = $(this).attr('srcset');

                                        if (!$(this).attr('data-default-image')) {
                                            $(this).attr('data-default-image', (i == 0 ? defaultImg : $(this).attr('src')));
                                        }
                                        if (i == 0) {

                                            if (typeof flex != 'undefined' && productGallery.find('.flex-viewport').length > 0 && parseInt(productGallery.find('.flex-viewport').css('height'), 10) > 0) {
                                                var rmbrHeight = parseInt(productGallery.find('.flex-viewport').css('height'), 10);
                                            }

                                            $(this).attr('data-src', newImg);
                                            $(this).attr('data-large-image', newImg);

                                            var thisRc = $(this).attr('src');
                                            var thisRcFixed = getUrlNoSuffix(thisRc);
                                            $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));

                                        } else {
                                            var thisRc = $(this).attr('src');
                                            var thisRcFixed = getUrlNoSuffix(thisRc);

                                            $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));
                                        }

                                        if (typeof srcset != 'undefined') {

                                            var defaultSrc = $(this).attr('data-default-srcset');
                                            if (typeof defaultSrc == 'undefined') {
                                                $(this).attr('data-default-srcset', srcset);
                                                defaultSrc = srcset;
                                            }

                                            if (i == 0) {

                                                var re = new RegExp(baseNameHTTP(defaultImg), 'g');
                                                srcset = defaultSrc.replace(re, baseNameHTTP(newImg));
                                                $(this).attr('srcset', srcset);
                                                rmbrSet = srcset;
                                            } else {
                                                $(this).attr('srcset', rmbrSet);
                                            }

                                        }

                                    });

                                }

                            }

                        }

                    }

                });

            } else {

                if (ivpa.settings.single_selector == '') {
                    var container = curr_element.closest(ivpa.settings.archive_selector).find('.product-gallery');
                    if (container.length == 0) {
                        container = curr_element.closest(ivpa.settings.archive_selector).find('.images');
                    }
                } else {
                    var container = $(ivpa.settings.single_selector);
                }

                if (ivpa.imageattributes.length == 0 || $.inArray(curr_attr, ivpa.imageattributes) > -1) {

                    var archive_image = container.find('img[data-default-image]');
                    if (archive_image.length > 0) {
                        archive_image.each(function(i, e) {

                            var defaultImg = $(this).attr('src');
                            var newImg = $(this).attr('data-default-image');

                            var thisRc = $(this).attr('src');
                            var thisRcFixed = getUrlNoSuffix(thisRc);
                            $(this).attr('src', thisRc.replace(thisRcFixed, getUrlNoSuffix(newImg)));

                            if (i == 0) {
                                $(this).attr('data-src', newImg);
                                $(this).attr('data-large-image', newImg);
                            }

                            var srcset = $(this).attr('srcset');
                            if (typeof srcset != 'undefined') {
                                var re = new RegExp(defaultImg, 'g');
                                srcset = srcset.replace(re, baseNameHTTP(newImg));
                                $(this).attr('srcset', $(this).attr('data-default-srcset')).removeAttr('data-default-srcset');
                            }

                        });
                    }

                }

            }

            /*if ( action !== 'register' ) {*/
            check_selections(curr_element);
            /*}*/

            _run_price(__get_container(curr_el));

            ivpaProcessing = false;
            return false;

        }

    }

    function ___get_price(p) {
        return accounting.unformat(p, ivpa.price.decimal_separator);
    }

    function ___get_price_back(p) {
        return accounting.formatMoney(p, '', ivpa.price.decimals, ivpa.price.thousand_separator, ivpa.price.decimal_separator);
    }

    function __get_container(e) {
        var how = ''
        if (e.closest('.ivpa-register').is('#ivpa-content')) {
            switch (ivpa.settings.single_prices) {
                case 'plugin':
                case 'plugin-bottom':
                    how = '.ivpa-register';
                    break;
                case 'form':
                    how = 'form';
                    break;
                default:
                    how = ivpa.settings.summary_selector; // .summary,
                    break;
            }

        } else {
            switch (ivpa.settings.archive_prices) {
                case 'plugin':
                case 'plugin-bottom':
                    how = '.ivpa-register';
                    break;
                default:
                    how = ivpa.settings.archive_selector;
                    break;
            }
        }

        return e.closest(how);
    }

    function _replace_all(str, mapObj) {
        var re = new RegExp(Object.keys(mapObj).join("|"), "gi");

        return str.replace(re, function(matched) {
            return mapObj[matched.toLowerCase()];
        });
    }

    function __get_price_template(el, add) {
        el.each(function(i, oi) {
            oi = $(oi);
            var p = {};
            var html = $('<div />').append(oi.clone()).html();
            oi.find('.amount').each(function() {
                var clone = $('<div />').append($(this).clone()).html();
                clone = $(clone);
                clone.find('span').remove();
                clone = clone.text().replace(/(?:^[\s\u00a0]+)|(?:[\s\u00a0]+$)/g, '');

                p[clone] = ___get_price_back(___get_price(clone) + ___get_price(add));
            });
            oi.replaceWith(_replace_all(html, p));
        });

    }

    function shouldPrice(el) {

        if (el.closest('.ivpa-register').is('#ivpa-content')) {
            if (ivpa.settings.single_prices == 'disable') {
                return false;
            }
        } else {
            if (ivpa.settings.archive_prices == 'disable') {
                return false;
            }
        }

        return true;
    }


    function _run_price(el) {

        if (!shouldPrice(el)) {
            return false;
        }

        var varPrice = false;

        var elEl = el.is('.ivpa-register') ? el : el.find('.ivpa-register');

        if ( typeof currVariations[elEl.attr('data-id')] == 'undefined' ) {
            return false;
        }

        if ($('input[name="variation_id"]').val() !== '') {
            var f = currVariations[elEl.attr('data-id')].filter(function(obj) {
                return obj.variation_id == $('input[name="variation_id"]').val();
            });

            if (typeof f[0] !== 'undefined') {
                varPrice = f[0].price_html;
            }
        }

        if (varPrice === false && elEl.attr('data-selected') !== '') {

            var f = currVariations[elEl.attr('data-id')].filter(function(obj) {
                return obj.variation_id == elEl.attr('data-selected');
            });

            if (typeof f[0] !== 'undefined') {
                varPrice = f[0].price_html;
            }

        }

        if (!varPrice) {
            varPrice = el.find('.ivpa-hidden-price').html();
        }

        if (varPrice) {
            el.find(ivpa.settings.price_selector + ':not(.ivpa-hidden-price ' + ivpa.settings.price_selector + ')').each(function() {
                $(this).replaceWith(varPrice);
            });
        }

        var price = 0;
        el.find('.ivpa_term.ivpa_clicked').each(function() {
            __mark_selection($(this).closest('.ivpa-opt'))
            if ($(this).find('.ivpa-addprice').length > 0) {
                price = price + parseFloat($(this).find('.ivpa-addprice').attr('data-add'));
            }
        });;

        el.find('select.ivpac-change option[data-add]:selected').each(function() {
            __mark_selection($(this).closest('.ivpa-opt'))
            price = price + parseFloat($(this).attr('data-add'));
        });

        el.find('.ivpa-mark .ivpa-info-box .ivpa_price .ivpa-addprice').each(function() {
            price = price + parseFloat($(this).attr('data-add'));
        });

        __get_price_template(el.find(ivpa.settings.price_selector + ':not(.ivpa-hidden-price ' + ivpa.settings.price_selector + ')'), price);

        $('.ivpa-mark').removeClass('ivpa-mark');
    }

    function __mark_selection(e) {
        if (!e.hasClass('ivpa-mark')) {
            e.addClass('ivpa-mark');
        }
    }

    function getUrlNoSuffix(theUrl) {
        theUrl = theUrl.substring(0, theUrl.lastIndexOf('.'));
        var x = theUrl.lastIndexOf('x');
        var y = theUrl.lastIndexOf('-');
        if (x > y) {
            var s = theUrl.substring(y);
            s = s.split('x');
            $.each(s, function(i, e) {
                var test = /^\d+$/.test(s[i]);
                if (!test) {
                    var notWell = true;
                }
            });
            if (typeof notWell == 'undefined') {
                return theUrl.substring(0, y);
            }
        }
        return theUrl;
    }

    function __get_attr(g) {
        var item = {};

        g.find('.ivpa-content .ivpa_attribute').each(function() {
            var attribute = $(this).attr('data-attribute');
            var attribute_value = $(this).find('.ivpa_term.ivpa_clicked').attr('data-term');
            item['attribute_' + attribute] = attribute_value;
        });

        return item;
    }

    function __adding_to_cart(e, f, g) {
        var container = f.closest(ivpa.settings.archive_selector);
        var find = f.closest('.summary').length > 0 ? '#ivpa-content' : '.ivpa-content';

        if (container.find(find).length > 0) {
            var var_id = container.find(find).attr('data-selected');

            if (typeof var_id == 'undefined' || var_id == '') {
                var_id = container.find('[name="variation_id"]').val();
            }

            if (typeof var_id == 'undefined' || var_id == '') {
                var_id = container.find(find).attr('data-id');
            }

            var item = {};

            container.find(find + ' .ivpa_attribute').each(function() {
                var attribute = $(this).attr('data-attribute');
                var attribute_value = $(this).find('.ivpa_term.ivpa_clicked').attr('data-term');

                item['attribute_' + attribute] = attribute_value;
            });

            var ivpac = container.find(find + ' .ivpa_custom_option').length > 0 ? container.find(find + ' .ivpa_custom_option [name^="ivpac_"]').serialize() : '';

            var quantity = container.find('input.ivpa_qty');
            if (quantity.length > 0) {
                var qty = quantity.val();
            }
            var quantity = (typeof qty !== "undefined" ? qty : $(this).attr('data-quantity'));

            g.variation_id = var_id;
            g.variation = item;
            g.quantity = quantity;
            g.ivpac = ivpac;
        }

    }

    $(document).on('product_loops_add_to_cart', function(e, f, g) {
        __adding_to_cart(e, f, g);
    });

    $(document).on('adding_to_cart', function(e, f, g) {
        __adding_to_cart(e, f, g);
    });

    $(document).on('click', '.ivpac_checkbox .ivpa_name', function() {
        $(this).prev().prop('checked', ($(this).prev().prop('checked') === true ? false : true)).trigger('change');
    });

    $(document).on('click', ivpa.settings.addcart_selector + '.product_type_variable.is-addable', function() {

        var container = $(this).closest(ivpa.settings.archive_selector);
        var var_id = container.find('.ivpa-content').attr('data-selected');

        if (typeof var_id == 'undefined' || var_id == '') {
            var_id = container.find('[name="variation_id"]').val();
        }

        if (typeof var_id == 'undefined' || var_id == '') {
            var_id = container.find('.ivpa-content').attr('data-id');
        }

        if (var_id !== undefined && var_id !== '') {

            var product_id = $(this).attr('data-product_id');

            var quantity = container.find('input.ivpa_qty');
            if (quantity.length > 0) {
                var qty = quantity.val();
            }
            var quantity = (typeof qty !== "undefined" ? qty : $(this).attr('data-quantity'));

            var $thisbutton = $(this);

            if ($thisbutton.is(ivpa.settings.addcart_selector)) {

                $thisbutton.removeClass('added');
                $thisbutton.addClass('loading');

                var data = {
                    action: 'ivpa_add_to_cart_callback',
                    ipo_product_id: product_id,
                    ipo_quantity: quantity
                };

                $('body').trigger('adding_to_cart', [$thisbutton, data]);

                $.post(ivpa.ajax, data, function(response) {

                    if (!response)
                        return;

                    var this_page = window.location.toString();

                    this_page = this_page.replace('add-to-cart', 'added-to-cart');

                    $thisbutton.removeClass('loading');

                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    var fragments = response.fragments;
                    var cart_hash = response.cart_hash;

                    $thisbutton.addClass('added');

                    if (!ivpa.add_to_cart.is_cart && $thisbutton.parent().find('.added_to_cart').size() === 0) {
                        $thisbutton.after(' <a href="' + ivpa.add_to_cart.cart_url + '" class="added_to_cart wc-forward" title="' +
                            ivpa.add_to_cart.i18n_view_cart + '">' + ivpa.add_to_cart.i18n_view_cart + '</a>');
                    }

                    if (fragments) {
                        $.each(fragments, function(key) {
                            $(key)
                                .addClass('updating')
                                .fadeTo('400', '0.6')
                                .block({
                                    message: null,
                                    overlayCSS: {
                                        opacity: 0.6
                                    }
                                });
                        });

                        $.each(fragments, function(key, value) {
                            $(key).replaceWith(value);
                            $(key).stop(true).css('opacity', '1').unblock();
                        });

                        $(document.body).trigger('wc_fragments_loaded');
                    }

                    $('body').trigger('added_to_cart', [fragments, cart_hash]);
                });

                return false;

            } else {
                return true;
            }

        }

    });


    $(document).ajaxComplete(function() {
        setTimeout(function() { ivpa_register_310(); }, 250);
    });


    $(document).on('click', '.ivpa_selectbox .ivpa_title', function() {
        var el = $(this).closest('.ivpa_select_wrapper_inner');

        if (el.hasClass('ivpa_selectbox_opened')) {
            el.removeClass('ivpa_selectbox_opened');
        } else {
            el.addClass('ivpa_selectbox_opened').delay(200).queue(function(next) {});
        }

    });

    $('#ivpa-content .ivpa_selectbox, .ivpa-content .ivpa_selectbox').each(function(i, c) {
        $(c).css('z-index', 99 - i);
    });

    if (ivpa.singleajax == 'yes') {

        $(document).on('click', '.single_add_to_cart_button', function() {

            var item = {};

            var $thisbutton = $(this);
            var form = $(this).closest('form');
            var product_id = parseInt(form.find('input[name=product_id]').length > 0 ? form.find('input[name=product_id]').val() : form.find('button.single_add_to_cart_button[type="submit"]').val(), 10);

            var quantity = parseInt(form.find('input[name=quantity]').val(), 10);

            if (product_id < 1) {
                return false;
            }

            var data = {
                action: 'ivpa_add_to_cart_callback',
                ipo_product_id: product_id,
                ipo_quantity: quantity
            };

            $thisbutton.removeClass('added');
            $thisbutton.addClass('loading');

            $('body').trigger('adding_to_cart', [$thisbutton, data]);

            $.post(ivpa.ajax, data, function(response) {

                if (!response)
                    return;

                var this_page = window.location.toString();

                this_page = this_page.replace('add-to-cart', 'added-to-cart');

                $thisbutton.removeClass('loading');

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                var fragments = response.fragments;
                var cart_hash = response.cart_hash;

                $thisbutton.addClass('added');

                if (!ivpa.add_to_cart.is_cart && $thisbutton.parent().find('.added_to_cart').size() === 0) {
                    $thisbutton.after(' <a href="' + ivpa.add_to_cart.cart_url + '" class="added_to_cart button wc-forward" title="' +
                        ivpa.add_to_cart.i18n_view_cart + '">' + ivpa.add_to_cart.i18n_view_cart + '</a>');
                }

                if (fragments) {
                    $.each(fragments, function(key) {
                        $(key)
                            .addClass('updating')
                            .fadeTo('400', '0.6')
                            .block({
                                message: null,
                                overlayCSS: {
                                    opacity: 0.6
                                }
                            });
                    });

                    $.each(fragments, function(key, value) {
                        $(key).replaceWith(value);
                        $(key).stop(true).css('opacity', '1').unblock();
                    });

                    $(document.body).trigger('wc_fragments_loaded');
                }

                $('body').trigger('added_to_cart', [fragments, cart_hash]);
            });

            return false;

        });

    }

    $(document).on('change', '.ivpac-change', function() {
        __check_change($(this));
        var closest = $(this).closest('.ivpa-opt');

        if (closest.is('[data-required="yes"]')) {
            var doIt = false;

            if ($(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked')) {
                    doIt = true;
                }
            } else {
                if ($(this).val() !== '') {
                    doIt = true;
                }
            }

            if (doIt) {
                closest.addClass('ivpa-required');
            } else {
                closest.removeClass('ivpa-required');
            }

        }

        __call_me($(this));

    } );
    
    /*$(document).on('change', '.ivpac-change', function() {
        var closest = $(this).closest('.ivpa-opt');
        var closestRegister = closest.closest('.ivpa-register');

        if (closest.is('[data-required="yes"]')) {
            var doIt = false;

            if ($(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked')) {
                    doIt = true;
                }
            } else {
                if ($(this).val() !== '') {
                    doIt = true;
                }
            }

            if (doIt) {
                closest.addClass('ivpa-required');
            } else {
                closest.removeClass('ivpa-required');
            }

        }

        __check_change($(this));

        __call_link_change(closestRegister, closestRegister.closest(ivpa.settings.archive_selector));

        _run_price(__get_container(closestRegister));

    });*/

    function __check_change(e) {
        if (e.is('input[type="text"]')) {
            if (e.val() !== '') {
                e.closest('.ivpa_term').addClass('ivpa_clicked');
            } else {
                e.closest('.ivpa_term').removeClass('ivpa_clicked');
            }
        }
        if (e.is('input[type="checkbox"]')) {
            if (e.is(':checked')) {
                e.closest('.ivpa_term').addClass('ivpa_clicked');
            } else {
                e.closest('.ivpa_term').removeClass('ivpa_clicked');
            }
        }
        if (e.is('textarea')) {
            if (e.val() !== '') {
                e.closest('.ivpa_term').addClass('ivpa_clicked');
            } else {
                e.closest('.ivpa_term').removeClass('ivpa_clicked');
            }
        }
        if (e.is('select')) {
            if (e.val() !== '') {
                e.closest('.ivpa_term').addClass('ivpa_clicked');
            } else {
                e.closest('.ivpa_term').removeClass('ivpa_clicked');
            }
        }
    }

    function __call_me(eThis) {

        var clk = eThis.hasClass('ivpa_clicked');

        if (ivpa.disableunclick != 'no' && clk) {
            return false;
        }

        var wrp = eThis.closest('div[data-attribute]');
        var str = [];

        if (!wrp.is('.ivpac_input, .ivpac_textarea, .ivpac_system, .ivpa_attribute') || eThis.hasClass('ivpa_group_custom')) {
            if (!wrp.hasClass('ivpa_multiselect')) {
                if (clk) {
                    eThis.removeClass('ivpa_clicked');
                } else {
                    wrp.find('.ivpa_clicked').removeClass('ivpa_clicked');
                    eThis.addClass('ivpa_clicked');
                }
            } else {
                if (clk) {
                    eThis.removeClass('ivpa_clicked');
                } else {
                    eThis.addClass('ivpa_clicked');
                }
            }
        }

        if (wrp.closest('.ivpa-register').hasClass('ivpa-stepped')) {
            check_steps(wrp);
        }

        wrp.find('span.ivpa_clicked').each(function() {
            str.push($(this).attr('data-term'));
        });

        wrp.find('input[type="hidden"]:first').val(str.join(', ')).trigger('change');

        if (wrp.hasClass('ivpa_selectbox')) {
            wrp.find('.ivpa_select_wrapper_inner').scrollTop(0).removeClass('ivpa_selectbox_opened');
            var sel = wrp.find('span[data-term="' + eThis.attr('data-term') + '"]').text();
            wrp.find('.ivpa_select_wrapper_inner .ivpa_title').text(clk == true ? ivpa.localization.select : sel);
        }

        check_selections(wrp.closest('.ivpa-register'));
        _run_price(__get_container(wrp));
        __call_conditions(eThis);

    }

    $(document).on('click', '.ivpa-do span.ivpa_term', function() {

        __call_me($(this));

    });


    function __call_conditions(f) {
        var co = f.closest('.ivpa-register');
        if ( co.find('.ivpa_custom_option[data-condition]').length==0 ) {
            return false;
        }

        co.find('.ivpa_custom_option[data-condition]').each( function() {
            var c = $(this).attr('data-condition').split(':');

            if ( co.find('[data-attribute="'+c[0]+'"] .ivpa_clicked.ivpa_term[data-term="'+c[1]+'"]').length>0 ) {
                $(this).addClass('ivpa-condition-meet');
            }
            else {
                $(this).removeClass('ivpa-condition-meet');
                $(this).find('.ivpa_term.ivpa_clicked').trigger('click');
            }
         } );
    }

    function check_selections(e) {

        $.each(e.find('.ivpa-opt'), function(i, f) {
            f = $(f);
            if (f.find('.ivpac-change').length == 0) {
                if (f.find('.ivpa_clicked').length > 0) {
                    f.addClass('ivpa-required');
                } else {
                    f.removeClass('ivpa-required');
                }
            }
        });

        if (e.attr('id') !== 'ivpa-content') {
            var c = e.closest(ivpa.settings.archive_selector);
            var btn = c.find('[data-product_id="' + e.attr('data-id') + '"]');

            if (btn.hasClass('product_type_simple') || btn.hasClass('product_type_variable') || btn.hasClass('pl-product-type-simple') || btn.hasClass('pl-product-type-variable')) {

                if (e.find('.ivpa-opt[data-required="yes"]:not(.ivpa-required)').length == 0) {
                    if (!$.isEmptyObject(ivpa_strings) && btn.text().indexOf(ivpa_strings.variable) > -1) {
                        btn.html(btn.html().replace(ivpa_strings.variable, ivpa_strings.simple));
                    }

                    btn.addClass('is-addable');

                    var quantity = c.find('.ivpa_quantity');

                    if (quantity.length > 0) {
                        quantity.stop(true, true).slideDown();
                    }
                } else if (e.find('.ivpa-opt[data-required="yes"]:not(.ivpa-required)').length > 0) {
                    if (!$.isEmptyObject(ivpa_strings) && btn.text().indexOf(ivpa_strings.simple) > -1) {
                        btn.html(btn.html().replace(ivpa_strings.simple, ivpa_strings.variable));
                        if (btn.hasClass('product_type_simple')) {
                            btn.removeClass('product_type_simple').removeClass('ajax_add_to_cart').attr('href', e.attr('data-url')).addClass('product_type_variable');
                        }
                    }

                    btn.removeClass('is-addable');

                    var quantity = c.find('.ivpa_quantity');

                    if (quantity.length > 0) {
                        quantity.stop(true, true).slideUp();
                    }
                }

                __call_link_change(e, c);

            }

        } else {
            var c = e.closest('form').length > 0 ? e.closest('form') : e.closest(ivpa.settings.archive_selector);
            var btn = c.find('.single_add_to_cart_button');
            var qty = c.find('.quantity');

            if (e.attr('data-type') == 'simple' || e.attr('data-type') == 'variable') {
                if (e.find('.ivpa-opt[data-required="yes"]:not(.ivpa-required)').length == 0) {
                    btn.removeClass('disabled').removeClass('ivpa-hide');
                    qty.removeClass('ivpa-hide');
                    btn.addClass('is-addable');
                } else if (e.find('.ivpa-opt[data-required="yes"]:not(.ivpa-required)').length > 0) {
                    btn.addClass('disabled').addClass('ivpa-hide');
                    qty.addClass('ivpa-hide');
                    btn.removeClass('is-addable');
                }
            }
        }

    }

    function __get_link_change_url(u, e) {
        var q = '';

        e.find('.ivpa-opt').each(function(i, o) {

            o = $(o);

            if (o.find('.ivpa_clicked[data-term!=""]').length > 0) {

                q += (i > 0 ? '&' : '') + 'attribute_' + o.attr('data-attribute') + '=';

                o.find('.ivpa_clicked[data-term!=""]').each(function(n, t) {

                    t = $(t);

                    q += n > 0 ? ',' + t.attr('data-term') : t.attr('data-term');

                });

            }

            // Quick hack for select element
            if (o.find('option:selected[value!=""]').length > 0) {

                q += (i > 0 ? '&' : '') + 'attribute_' + o.attr('data-attribute') + '=';

                o.find('option:selected[value!=""]').each(function(n, t) {

                    t = $(t);

                    q += n > 0 ? ',' + t.val() : t.val();

                });

            }

        });

        return q == '' ? u : u + (u.indexOf('?') > -1 ? '&' : '?') + q;
    }

    function __call_link_change(e, c) {
        var u = e.attr('data-url');

        if (u !== '') {

            c.find('a[href^="' + u + '"]').each(function(n, t) {

                t = $(t);

                $(t).attr('href', __get_link_change_url(u, e));

            });

        }
    }

    function check_steps(curr) {
        if (!curr.hasClass('ivpa-step')) {
            curr.addClass('ivpa-step');
        }

        if (curr.find('.ivpa_clicked').length == 0) {
            curr.removeClass('ivpa-step').nextUntil('a').each(function() {
                $(this).removeClass('ivpa-step');
                $(this).find('.ivpa_clicked').trigger('click');
            });
        }
    }

})(jQuery);