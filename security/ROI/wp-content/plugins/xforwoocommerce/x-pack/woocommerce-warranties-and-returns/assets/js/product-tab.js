(function($){
"use strict";

    $(document).on("woocommerce_variations_loaded", "body", function() {
        $(".wcwar_metaboxes.paid_warranty_items").sortable({handle:"a.move_paid_warranty"});
    });

    $(".wcwar_metaboxes.paid_warranty_items").sortable({handle:"a.move_paid_warranty"});

    var wcwar_metabox = "<div class=\"wcwar_metabox\">\
        <span class=\"wcwar_metabox_title\">"+wcwar.localization[18]+"</span>\
        <a href=\"#\" class=\"edit_paid_warranty\"><i class=\"wcwar-change\"></i></a>\
        <a href=\"#\" class=\"move_paid_warranty\"><i class=\"wcwar-move\"></i></a>\
        <a href=\"#\" class=\"remove_paid_warranty\"><i class=\"wcwar-close\"></i></a>\
        <div class=\"wcwar-opt\" data-group=\"included_warranty\">\
            <p class=\"wcwar_qp_title\">\
                <label for=\"wcwar_qp_title\">"+wcwar.localization[16]+"</label>\
                <input type=\"text\" class=\"option short\" name=\"wcwar_qp_title_single[]\" value=\"\" placeholder=\"\">\
                <em>"+wcwar.localization[17]+"</em>\
            </p>\
            <p class=\"wcwar_qp_desc\">\
                <label for=\"wcwar_qp_desc\">"+wcwar.localization[12]+"</label>\
                <textarea class=\"option short\" name=\"wcwar_qp_desc_single[]\" placeholder=\"\" step=\"any\"></textarea>\
                <em>"+wcwar.localization[13]+"</em>\
            </p>\
            <p class=\"wcwar_qp_thumb\">\
                <span class=\"thumb_preview\"></span>\
                <label for=\"wcwar_qp_thumb\">"+wcwar.localization[14]+"</label>\
                <input type=\"hidden\" class=\"option short\" name=\"wcwar_qp_thumb_single[]\" value=\"\" placeholder=\"\" step=\"any\">\
                <button type=\"button\" class=\"option button add_wcwar_qp_thumb\">"+wcwar.localization[15]+"</button>\
            </p>\
            <p class=\"wcwar_qp_period\">\
                <label for=\"wcwar_qp_period\">"+wcwar.localization[10]+"</label>\
                <input type=\"number\" class=\"option short\" name=\"wcwar_qp_period_single[]\" value=\"\" placeholder=\"\" step=\"any\">\
                <em>"+wcwar.localization[11]+"</em>\
            </p>\
            <p class=\"wcwar_qp_type\">\
                <label for=\"wcwar_qp_type\">"+wcwar.localization[2]+"</label>\
                <select name=\"wcwar_qp_type_single[]\" class=\"option select short\">\
                    <option value=\"\">"+wcwar.localization[3]+"</option>\
                    <option value=\"days\">"+wcwar.localization[4]+"</option>\
                    <option value=\"weeks\">"+wcwar.localization[5]+"</option>\
                    <option value=\"months\">"+wcwar.localization[6]+"</option>\
                    <option value=\"years\">"+wcwar.localization[7]+"</option>\
                    <option value=\"lifetime\">"+wcwar.localization[8]+"</option>\
                </select>\
                <em>"+wcwar.localization[9]+"</em>\
            </p>\
            <p class=\"wcwar_qp_price\">\
                <label for=\"wcwar_qp_price\">"+wcwar.localization[0]+"</label>\
                <input type=\"number\" class=\"option short wc_input_price\" name=\"wcwar_qp_price_single[]\" value=\"\" placeholder=\"\" step=\"any\">\
                <em>"+wcwar.localization[1]+"</em>\
            </p>\
        </div>\
    </div>";

    var wcwar_metabox_variable = "<div class=\"wcwar_metabox\">\
        <span class=\"wcwar_metabox_title\">"+wcwar.localization[18]+"</span>\
        <a href=\"#\" class=\"edit_paid_warranty\"><i class=\"wcwar-change\"></i></a>\
        <a href=\"#\" class=\"move_paid_warranty\"><i class=\"wcwar-move\"></i></a>\
        <a href=\"#\" class=\"remove_paid_warranty\"><i class=\"wcwar-close\"></i></a>\
        <div class=\"wcwar-opt\" data-group=\"included_warranty\">\
            <p class=\"wcwar_qp_title\">\
                <label for=\"wcwar_qp_title\">"+wcwar.localization[16]+"</label>\
                <input type=\"text\" class=\"option short\" name=\"wcwar_qp_title[%][]\" value=\"\" placeholder=\"\">\
                <em>"+wcwar.localization[17]+"</em>\
            </p>\
            <p class=\"wcwar_qp_desc\">\
                <label for=\"wcwar_qp_desc\">"+wcwar.localization[12]+"</label>\
                <textarea class=\"option short\" name=\"wcwar_qp_desc[%][]\" placeholder=\"\" step=\"any\"></textarea>\
                <em>"+wcwar.localization[13]+"</em>\
            </p>\
            <p class=\"wcwar_qp_thumb\">\
                <span class=\"thumb_preview\"></span>\
                <label for=\"wcwar_qp_thumb\">"+wcwar.localization[14]+"</label>\
                <input type=\"hidden\" class=\"option short\" name=\"wcwar_qp_thumb[%][]\" value=\"\" placeholder=\"\" step=\"any\">\
                <button type=\"button\" class=\"option button add_wcwar_qp_thumb\">"+wcwar.localization[15]+"</button>\
            </p>\
            <p class=\"wcwar_qp_period\">\
                <label for=\"wcwar_qp_period\">"+wcwar.localization[10]+"</label>\
                <input type=\"number\" class=\"option short\" name=\"wcwar_qp_period[%][]\" value=\"\" placeholder=\"\" step=\"any\">\
                <em>"+wcwar.localization[11]+"</em>\
            </p>\
            <p class=\"wcwar_qp_type\">\
                <label for=\"wcwar_qp_type\">"+wcwar.localization[2]+"</label>\
                <select name=\"wcwar_qp_type[%][]\" class=\"option select short\">\
                    <option value=\"\">"+wcwar.localization[3]+"</option>\
                    <option value=\"days\">"+wcwar.localization[4]+"</option>\
                    <option value=\"weeks\">"+wcwar.localization[5]+"</option>\
                    <option value=\"months\">"+wcwar.localization[6]+"</option>\
                    <option value=\"years\">"+wcwar.localization[7]+"</option>\
                    <option value=\"lifetime\">"+wcwar.localization[8]+"</option>\
                </select>\
                <em>"+wcwar.localization[9]+"</em>\
            </p>\
            <p class=\"wcwar_qp_price\">\
                <label for=\"wcwar_qp_price\">"+wcwar.localization[0]+"</label>\
                <input type=\"number\" class=\"option short wc_input_price\" name=\"wcwar_qp_price[%][]\" value=\"\" placeholder=\"\" step=\"any\">\
                <em>"+wcwar.localization[1]+"</em>\
            </p>\
        </div>\
    </div>";

    $(document).on("change", "#wcwar_type", function() {

        var curr = $("#wcwar_tab");
        curr.find(".wcwar-opt:not(.wcwar-opt-basic) .option").attr("disabled", "disabled").closest(".wcwar-opt").hide();

        if ( $(this).val() != "no_warranty") {
            curr.find(".wcwar-opt[data-group="+$(this).val()+"] .option").removeAttr("disabled").closest(".wcwar-opt").show();
            if ( $(this).val() == "preset_warranty" ) {
                curr.find(".paid_warranty_items .wcwar_metabox").remove();
            }
        }
        else {
            curr.find(".paid_warranty_items .wcwar_metabox").remove();
        }

    });

    $(document).on("change", ".wcwar_type", function() {

        if ( $(this).val() == "" ) {
            return;
        }

        var curr = $(this).closest(".wcwar_tab");
        curr.find(".wcwar-opt:not(.wcwar-opt-basic) .option").attr("disabled", "disabled").closest(".wcwar-opt").hide();

        if ( $(this).val() != "no_warranty") {
            curr.find(".wcwar-opt[data-group="+$(this).val()+"] .option").removeAttr("disabled").closest(".wcwar-opt").show();
            if ( $(this).val() == "preset_warranty" ) {
                curr.find(".paid_warranty_items .wcwar_metabox").remove();
            }
        }
        else {
            curr.find(".paid_warranty_items .wcwar_metabox").remove();
        }

    });

    $(document).on("change", "#wcwar_q_type", function() {

        var curr = $("#wcwar_tab");
        curr.find(".wcwar-opt:not(.wcwar-opt-basic):not(.wcwar-opt-preset):not(.wcwar-opt-type) .option").attr("disabled", "disabled").closest(".wcwar-opt").hide();

        if ( $(this).val() != "") {
            curr.find(".wcwar-opt[data-group="+$(this).val()+"] .option").removeAttr("disabled").closest(".wcwar-opt").show();
            if ( $(this).val() == "included_warranty" ) {
                curr.find(".paid_warranty_items .wcwar_metabox").remove();
            }
        }
        else {
            curr.find(".paid_warranty_items .wcwar_metabox").remove();
        }

    });

    $(document).on("change", ".wcwar_q_type", function() {
        if ( $(this).val() == "" ) {
            return;
        }

        var curr = $(this).closest(".wcwar_tab");
        curr.find(".wcwar-opt:not(.wcwar-opt-basic):not(.preset):not(.wcwar-opt-type) .option").attr("disabled", "disabled").closest(".wcwar-opt").hide();

        if ( $(this).val() != "") {
            curr.find(".wcwar-opt[data-group="+$(this).val()+"] .option").removeAttr("disabled").closest(".wcwar-opt").show();
            if ( $(this).val() == "included_warranty" ) {
                curr.find(".paid_warranty_items .wcwar_metabox").remove();
            }
        }
        else {
            curr.find(".paid_warranty_items .wcwar_metabox").remove();
        }

    });

    
    $(document).on("click", ".add_paid_warranty", function () {
        if ( $("#product-type").val() == "variable" ) {
            var curr = $(this).closest(".woocommerce_variation").index();
            $(this).parent().prev().append(wcwar_metabox_variable.replace(/%/g, curr));
        }
        else {
            $(this).parent().prev().append(wcwar_metabox);
        }
        return false;
    });

    $(document).on("click", ".edit_paid_warranty", function () {
        $(this).parent().find('div:first').addClass('active').toggle();
        $('.wcwar_metabox > .wcwar-opt:not(.active)').hide();
        $('.wcwar-opt.active').removeClass('active');
        
        return false;
    } );

    $(document).on("click", ".remove_paid_warranty", function () {
        $(this).parent().remove();
        return false;
    });

    $(document).on("click", ".add_wcwar_qi_thumb, .add_wcwar_qp_thumb", function () {

        var frame;
        var el = $(this);
        var curr = el.parent();

        if ( frame ) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: el.data("choose"),
            button: {
                text: el.data("update"),
                close: false
            }
        });

        frame.on( "select", function() {

            var attachment = frame.state().get("selection").first();
            frame.close();
            curr.find("input:hidden").val(attachment.attributes.url);
            if ( attachment.attributes.type == "image" ) {
                curr.find(".thumb_preview").empty().hide().append("<img width=\"64\" height=\"auto\" src=\""+attachment.attributes.url+"\">").slideDown("fast");
            }

        });

        frame.open();

        return false;
    });

    $('.wcwar_metabox').each( function() {
        _do_warranty_title($(this));
    } );

    function _do_warranty_title(e) {
        var a = e.find('input[name^="wcwar_qp_title"]').val(),
            b = (a!==''?a:e.find('input[name^="wcwar_qp_period"]').val()+' '+e.find('select[name^="wcwar_qp_type"] option:selected').text()),
            f = e.find('.wcwar_metabox_title');

        if ( b == '' ) {
            b = 'Select warranty';
        }

        if ( f.length>0 ) {
            f.text(b);
        }
        else {
            e.prepend('<span class="wcwar_metabox_title">'+b+'</span>');
        }
        
    }

    $(document).on( 'change', '.wcwar_metabox input[name^="wcwar_qp_title"], .wcwar_metabox input[name^="wcwar_qp_period"], .wcwar_metabox select[name^="wcwar_qp_type"]', function() {
        _do_warranty_title($(this).closest('.wcwar_metabox'));
    } );

})(jQuery);