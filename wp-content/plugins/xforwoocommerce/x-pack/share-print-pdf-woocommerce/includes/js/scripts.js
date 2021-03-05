(function($){
"use strict";

	function u(e) {
		return typeof e == 'undefined' ? false : e;
	}

	var imgSize = getSize();

	function getSize() {
		switch(wcspp.pagesize){
			case 'legal' :
			case 'letter' :
				return 530;
			case 'a3' :
				return 760;
			case 'a4' :
				return 515;
			default:
				return 530;
		}
	}

	var readyImgs = {};

	function getBase64FromImageUrl(url, name) {

		if ( url == '' ) {
			readyImgs[name] = '';
		}
		else {
			var img = new Image();

			img.setAttribute('crossOrigin', 'anonymous');
	
			img.onload = function () {
				var canvas = document.createElement("canvas");
				canvas.width =this.width;
				canvas.height =this.height;
	
				var ctx = canvas.getContext("2d");
				ctx.drawImage(this, 0, 0);
	
				var dataURL = canvas.toDataURL("image/png");
	
				readyImgs[name] = dataURL;
	
			};
	
			img.src = url;
		}

	}

	$.fn.print = function() {
		if (this.size() > 1){
			this.eq( 0 ).print();
			return;
		} else if (!this.size()){
			return;
		}

		var strFrameName = ("wpspp-printer-" + (new Date()).getTime());

		var jFrame = $( "<iframe name='" + strFrameName + "'>" );

		jFrame
			.css( "width", "1px" )
			.css( "height", "1px" )
			.css( "position", "absolute" )
			.css( "left", "-999px" )
			.appendTo( $( "body:first" ) )
		;

		var objFrame = window.frames[ strFrameName ];

		var objDoc = objFrame.document;

		objDoc.open();
		objDoc.write( "<!DOCTYPE html>" );
		objDoc.write( "<html>" );
		objDoc.write( "<head>" );
		objDoc.write( "<title>" );
		objDoc.write( document.title );
		objDoc.write( "</title>" );
		objDoc.write( "<style>" + ( wcspp.rtl == 'no' ? '@media print{*{background:0 0!important;color:#000!important;box-shadow:none!important;text-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="#"]:after,a[href^="javascript:"]:after{content:""}blockquote,pre{border:.5mm solid #999;page-break-inside:avoid}thead{display:table-header-group}img,tr{page-break-inside:avoid}img{max-width:100%!important}@page{margin:1cm 1cm 1cm 2cm}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}}body{font-family:"Trebuchet MS","Lucida Grande","Lucida Sans Unicode","Lucida Sans",Tahoma,sans-serif;line-height:.6cm;font-size:.45cm}h1,h2,h3,h4,h5,h6{font-weight:700}h1{font-size:1cm;line-height:1cm;margin:.5em 0 .25em}h1:after{content:"";display:table;clear:both}h2{font-size:.8cm;line-height:.8cm;margin:.5em 0 .25em}h3{font-size:.7cm;line-height:.7cm;margin:.5em 0 .25em}h4{font-size:.6cm;line-height:.6cm;margin:.4em 0 .25em}h5{font-size:.7cm;line-height:.7cm;margin:.3em 0 .15em;padding:0}h5{font-size:.6cm;line-height:.6cm;margin:.2em 0 .15em;padding:0}hr{display:block;border-style:solid;border-width:2px;border-bottom:0}del{font-size:.5cm}ins{text-decoration:none}blockquote{border:0;border-bottom:2px solid #000;background:#eee;padding:.125cm 0;margin:0;font-weight:600}pre{border:1px solid #000;padding:.25cm}ol,ul{margin:0;padding:0;list-style-position:inside}ol ol,ul ul{margin-left:.5cm}table{width:100%}table,td,th{border:2px solid #000}table{border-bottom:0;border-left:0}td,th{border-top:0;border-right:0;padding:.125cm}th{text-align:left;font-weight:700}.wcspp-go-print{display:none}.wcspp-logo{float:left;height:1.1cm;width:auto;margin-right:.25cm}.wcspp-product-title{display:block;font-size:1cm;line-height:1cm;margin-bottom:.25em;font-weight:600}.wcspp-title{max-width:65%;font-size:1cm;line-height:1cm;margin:0;font-weight:600;float:left}.wcspp-price{font-size:.8cm;line-height:.8cm;float:right;white-space:nowrap}.wcspp-price ins span{margin-left:.25em}.wcspp-price ins span:first-child{margin-right:.25em}.wcspp-main-image{float:left;width:50%;margin-bottom:1em}.wcspp-main-image img{width:100%;height:auto}.wcspp-images{margin-left:53%;margin-bottom:1em}.wcspp-images img{width:50%;height:auto;margin-bottom:0}.wcspp-block-wrap{margin-bottom:1em}.wcspp-block{display:block}.wcspp-description{margin-bottom:2em}.wcspp-description:before{content:"";display:table;clear:both}.wcspp-url{font-size:11pt;vertical-align:middle}.wcspp-meta{margin-bottom:2em}.wcspp-add{margin:1.5em 0 0}.wcspp-content img{display:block;margin:1em 0}.wcspp-quickview .wcspp-page-wrap h3{font-size:inherit;line-height:inherit}' : '@media print{*{background:0 0!important;color:#000!important;box-shadow:none!important;text-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="#"]:after,a[href^="javascript:"]:after{content:""}blockquote,pre{border:.5mm solid #999;page-break-inside:avoid}thead{display:table-header-group}img,tr{page-break-inside:avoid}img{max-width:100%!important}@page{margin:1cm 2cm 1cm 1cm}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}}body{font-family:"Trebuchet MS","Lucida Grande","Lucida Sans Unicode","Lucida Sans",Tahoma,sans-serif;line-height:.6cm;font-size:.45cm}h1,h2,h3,h4,h5,h6{font-weight:700}h1{font-size:1cm;line-height:1cm;margin:.5em 0 .25em}h1:after{content:"";display:table;clear:both}h2{font-size:.8cm;line-height:.8cm;margin:.5em 0 .25em}h3{font-size:.7cm;line-height:.7cm;margin:.5em 0 .25em}h4{font-size:.6cm;line-height:.6cm;margin:.4em 0 .25em}h5{font-size:.7cm;line-height:.7cm;margin:.3em 0 .15em;padding:0}h5{font-size:.6cm;line-height:.6cm;margin:.2em 0 .15em;padding:0}hr{display:block;border-style:solid;border-width:2px;border-bottom:0}del{font-size:.5cm}ins{text-decoration:none}blockquote{border:0;border-bottom:2px solid #000;background:#eee;padding:.125cm 0;margin:0;font-weight:600}pre{border:1px solid #000;padding:.25cm}ol,ul{margin:0;padding:0;list-style-position:inside}ol ol,ul ul{margin-right:.5cm}table{width:100%}table,td,th{border:2px solid #000}table{border-bottom:0;border-right:0}td,th{border-top:0;border-left:0;padding:.125cm}th{text-align:right;font-weight:700}.wcspp-go-print{display:none}.wcspp-logo{float:right;height:1.1cm;width:auto;margin-left:.25cm}.wcspp-product-title{display:block;font-size:1cm;line-height:1cm;margin-bottom:.25em;font-weight:600}.wcspp-title{max-width:65%;font-size:1cm;line-height:1cm;margin:0;font-weight:600;float:right}.wcspp-price{font-size:.8cm;line-height:.8cm;float:left;white-space:nowrap}.wcspp-price ins span{margin-right:.25em}.wcspp-price ins span:first-child{margin-left:.25em}.wcspp-main-image{float:right;width:50%;margin-bottom:1em}.wcspp-main-image img{width:100%;height:auto}.wcspp-images{margin-right:53%;margin-bottom:1em}.wcspp-images img{width:50%;height:auto;margin-bottom:0}.wcspp-block-wrap{margin-bottom:1em}.wcspp-block{display:block}.wcspp-description{margin-bottom:2em}.wcspp-description:before{content:"";display:table;clear:both}.wcspp-url{font-size:11pt;vertical-align:middle}.wcspp-meta{margin-bottom:2em}.wcspp-add{margin:1.5em 0 0}.wcspp-content img{display:block;margin:1em 0}.wcspp-quickview .wcspp-page-wrap h3{font-size:inherit;line-height:inherit}' ) + "</style>" );
		objDoc.write( "</head>" );
		objDoc.write( "<body>" );
		objDoc.write( this.html() );
		objDoc.write( "</body>" );
		objDoc.write( "</html>" );
		objDoc.close();

		objFrame.focus();
		objFrame.print();

		setTimeout(
			function(){
			jFrame.remove();
		},
		(60 * 1000)
		);
	};

	var pdfData = {};

	$.fn.printPdf = function(vars) {

		if ( vars.header_after == '' ) {
			pdfData.header_after = [];
		}
		else {
			pdfData.header_after = {
				text:vars.header_after,
				margin:[0,10,0,10]
			};
		}

		if ( vars.product_before == '' ) {
			pdfData.product_before = [];
		}
		else {
			pdfData.product_before = {
				text:vars.product_before,
				margin:[0,10,0,10]
			};
		}

		if ( vars.product_after == '' ) {
			pdfData.product_after = [];
		}
		else {
			pdfData.product_after = {
				text:vars.product_after,
				margin:[0,10,0,10]
			};
		}

		getBase64FromImageUrl(vars.site_logo, 'site_logo');
		getBase64FromImageUrl(vars.product_image, 'product_image');
		getBase64FromImageUrl(vars.product_img0, 'product_img0');
		getBase64FromImageUrl(vars.product_img1, 'product_img1');
		getBase64FromImageUrl(vars.product_img2, 'product_img2');
		getBase64FromImageUrl(vars.product_img3, 'product_img3');

		$('.wcspp-content img, .wcspp-content-short img').each( function() {
			getBase64FromImageUrl($(this).attr('src'), baseName($(this).attr('src')));
		});
		
		setTimeout( function() {
			waitForElement(vars);
		}, 333 );

	};

	function baseName(str) {
		var base = str.substring(str.lastIndexOf('/') + 1);
		if(base.lastIndexOf(".") != -1) {
			base = base.substring(0, base.lastIndexOf("."));
		}
		return base;
	}

	function getPdf(vars) {

		var site_logo = {};

		if ( vars.site_logo == '' || u(readyImgs.site_logo) === false ) {
			site_logo = {
				width:0,
				image:'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0, 0]
			};
		}
		else {
			site_logo = {
				width:45,
				image:readyImgs.site_logo,
				fit: [37, 37]
			};
		}

		var product_img0 = {};
		if ( vars.product_img0 == '' || u(readyImgs.product_img0) === false ) {
			product_img0 = {
				width:0,
				image:'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0, 0]
			};
		}
		else {
			product_img0 = {
				width:125,
				image:readyImgs.product_img0,
				fit: [125, 9999]
			};
		}

		var product_img1 = {};

		if ( vars.product_img1 == '' || u(readyImgs.product_img1) === false ) {
			product_img1 = {
				width:0,
				image:'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0, 0]
			};
		}
		else {
			product_img1 = {
				width:125,
				image:readyImgs.product_img1,
				fit: [125, 9999]
			};
		}

		var product_img2 = {};

		if ( vars.product_img2 == '' || u(readyImgs.product_img2) === false ) {
			product_img2 = {
				width:0,
				image:'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0, 0]
			};
		}
		else {
			product_img2 = {
				width:125,
				image:readyImgs.product_img2,
				fit: [125, 9999]
			};
		}

		var product_img3 = {};

		if ( vars.product_img3 == '' || u(readyImgs.product_img3) === false ) {
			product_img3 = {
				width:0,
				image:'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0, 0]
			};
		}
		else {
			product_img3 = {
				width:125,
				image:readyImgs.product_img3,
				fit: [125, 9999]
			};
		}

		var product_img = {};

		if ( vars.product_image == '' || u(readyImgs.product_image) === false ) {
			product_img = {
				width:0,
				image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=',
				fit: [0,0]
			};
		}
		else {
			product_img = {
				width:270,
				image: readyImgs.product_image,
				fit: [250,9999]
			};
		}

		var convertContentHTML = pdfForElement(vars.product_content);
		var convertDescHTML = pdfForElement(vars.product_description);

		var addImgs1 = vars.product_img0 == '' ? '' : {
			alignment: 'justify',
			columns: [
				product_img0,
				product_img1
			]
		};
		var addImgs2 = vars.product_img0 == '' ? '' : {
			alignment: 'justify',
			columns: [
				product_img2,
				product_img3
			]
		};

		var prdctCats = vars.product_categories == '' ? '' : {
			text: vars.product_categories,
			style: 'meta'
		};

		var prdctTags = vars.product_tags == '' ? '' : {
			text: vars.product_tags,
			style: 'meta'
		};

		var prdctAttr = vars.product_attributes == '' ? '' : {
			text: vars.product_attributes,
			style: 'meta'
		};

		var prdctDim = vars.product_dimensions == '' ? '' : {
			text: vars.product_dimensions,
			style: 'meta'
		};
	
		var prdctWei = vars.product_weight == '' ? '' : {
			text: vars.product_weight,
			style: 'meta'
		};

		var pdfcontent = {
			content: [
				{
					alignment: 'justify',
					columns: [
						site_logo,
						[
							{
								text: vars.site_title,
								style: 'header'
							},
							{
								text: vars.site_description,
								style: 'headerDesc'
							}
						]
					]
				},
				pdfData.header_after,
				{
					image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAYAAABytg0kAAAAEklEQVQIW2NkYGD4D8QMjDAGABMaAgFVG7naAAAAAElFTkSuQmCC',
					width:imgSize,
					height:0.5,
					alignment: 'center'
				},
				pdfData.product_before,
				{
					alignment: 'justify',
					columns: [
						{
							text: vars.product_title,
							style: 'header',
							alignment: 'left'
						},
						{
							text: vars.product_price,
							style: 'header',
							alignment: 'right'
						}
					]
				},
				'\n',
				vars.product_meta,
				{
					text: vars.product_link,
					color: '#6699ff'
				},
				'\n\n',
				{
					alignment: 'justify',
					columns: [
						product_img,
						[
							addImgs1,
							addImgs2,
						]
					]
				},
				{
					text: wcspp.localization.info,
					style: 'headerProduct'
				},
				{
					image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAYAAABytg0kAAAAEklEQVQIW2NkYGD4D8QMjDAGABMaAgFVG7naAAAAAElFTkSuQmCC',
					width:imgSize,
					height:0.5,
					alignment: 'center'
				},
				'\n',
				prdctCats,
				prdctTags,
				prdctAttr,
				prdctDim,
				prdctWei,
				'\n',
				convertDescHTML,
				{
					text: wcspp.localization.desc,
					style: 'headerProduct'
				},
				{
					image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAYAAABytg0kAAAAEklEQVQIW2NkYGD4D8QMjDAGABMaAgFVG7naAAAAAElFTkSuQmCC',
					width:imgSize,
					height:0.5,
					alignment: 'center',
					margin: [0,0,0,5]
				},
				convertContentHTML,
				pdfData.product_after
				
			],
			styles: {
				header: {
					fontSize: 20,
					bold: true,
					margin: [0,5,0,0]
				},
				headerDesc: {
					fontSize: 13,
					bold: true,
					margin: [0,0,0,10]
				},
				headerProduct: {
					fontSize: 20,
					bold: true,
					margin: [0,20,0,5]
				},
				meta: {
					fontSize: 13,
					bold: true
				}
			},
			defaultStyle: {
				fontSize: 11
			},
			pageSize: wcspp.pagesize

		};

		if ( typeof loaded == 'undefined' ) {

			$.loadScript(wcspp.pdfmake, function(){
				$.loadScript(wcspp.pdffont, function(){
					var loaded = true;
					pdfMake.createPdf(pdfcontent).download(vars.site_title+' - '+vars.product_title+'.pdf');
				});
			});

		}

	}

	$.loadScript = function (url, callback) {
		$.ajax({
			url: url,
			dataType: 'script',
			success: callback,
			async: true
		});
	};

	function waitForElement(vars) {
		var checked = false;
		$.each( readyImgs, function(i, o) {
			if ( typeof o !== "undefined" ) {
				checked = true;
			}
		});

		if ( checked === true ) {
			getPdf(vars);
		}
		else {
			setTimeout( function() {
				waitForElement(vars);
			}, 333 );
		}
	}

	var ajax = 'notactive';

	function wcspp_ajax( action, product_id, type ) {

		var data = {
			action: action,
			type: type,
			product_id: product_id
		};

		return $.ajax({
			type: 'POST',
			url: wcspp.ajax,
			data: data,
			success: function(response) {
				if (response) {
					ajax = 'notactive';
				}
			},
			error: function() {
				alert('Error!');
				ajax = 'notactive';
			}
		});

	}

	$(document).on('click', '.wcspp-navigation .wcspp-print a', function() {

		if ( ajax == 'active' ) {
			return false;
		}

		ajax = 'active';
		$(this).addClass('wcspp-ajax-active');

		$.when( wcspp_ajax( 'wcspp_quickview', $(this).closest('.wcspp-navigation').data('wcspp-id'), 'print' ) ).done( function(response) {

			response = $(response);

			response.find('img[srcset]').removeAttr('srcset');

			$('body').append(response);

		});

		return false;
	});

	$(document).on('click', '.wcspp-navigation .wcspp-pdf a', function() {

		if ( ajax == 'active' ) {
			return false;
		}

		ajax = 'active';
		$(this).addClass('wcspp-ajax-active');

		$.when( wcspp_ajax( 'wcspp_quickview', $(this).closest('.wcspp-navigation').data('wcspp-id'), 'pdf' ) ).done( function(response) {

			response = $(response);

			response.find('img[srcset]').removeAttr('srcset');

			$('body').append(response);

		});

		return false;
	});

	$(document).on( 'click', '.wcspp-quickview .wcspp-quickview-close, .wcspp-quickview .wcspp-go-back', function() {

		$('.wcspp-quickview').fadeOut(200, function() {
			$('.wcspp-ajax-active').removeClass('wcspp-ajax-active');
			$(this).remove();
		});

		return false;

	});

	$(document).on( 'click', '.wcspp-quickview .wcspp-go-print', function(e) {

		e.preventDefault();

		$('.wcspp-page-wrap').print();

		return false;

	});

	$(document).on( 'click', '.wcspp-quickview .wcspp-go-pdf', function(e) {

		e.preventDefault();

		var vars = $('.wcspp-page-wrap').data('wcspp-pdf');

		$('.wcspp-page-wrap').printPdf(vars);

		return false;

	});

	function pdfForElement(data) {
		function ParseContainer(cnt, e, p, styles) {
			var elements = [];
			var children = e.childNodes;
			if (children.length !== 0) {
				for (var i = 0; i < children.length; i++) p = ParseElement(elements, children[i], p, styles);
			}
			if (elements.length !== 0) {
				for (var i = 0; i < elements.length; i++) cnt.push(elements[i]);
			}
			return p;
		}

		function ComputeStyle(o, styles) {
			for (var i = 0; i < styles.length; i++) {
				var st = styles[i].trim().toLowerCase().split(":");
				if (st.length == 2) {
				switch (st[0]) {
					case "font-size":
					{
						o.fontSize = parseInt(st[1]);
						break;
					}
					case "text-align":
					{
						switch (st[1]) {
						case "right":
							o.alignment = 'right';
							break;
						case "center":
							o.alignment = 'center';
							break;
						}
						break;
					}
					case "font-weight":
					{
						switch (st[1]) {
						case "bold":
							o.bold = true;
							break;
						}
						break;
					}
					case "text-decoration":
					{
						switch (st[1]) {
						case "underline":
							o.decoration = "underline";
							break;
						}
						break;
					}
					case "font-style":
					{
						switch (st[1]) {
						case "italic":
							o.italics = true;
							break;
						}
						break;
					}
					case "color":
					{
						o.fillColor = st[1];
						break;
					}
				}
				}
			}
		}

		function ParseElement(cnt, e, p, styles) {
			if (!styles) styles = [];
			if (e.getAttribute) {
				var nodeStyle = e.getAttribute("style");
				if (nodeStyle) {
				var ns = nodeStyle.split(";");
				for (var k = 0; k < ns.length; k++) styles.push(ns[k]);
				}
			}

			switch (e.nodeName.toLowerCase()) {
				case "#text":
				{
					var t = {
						text: e.textContent.replace(/\n/g, "")
					};
					if (styles) ComputeStyle(t, styles);
					p.text.push(t);
					break;
				}
				case "b":
				case "strong":
				{
					ParseContainer(cnt, e, p, styles.concat(["font-weight:bold"]));
					break;
				}
				case "u":
				{
					ParseContainer(cnt, e, p, styles.concat(["text-decoration:underline"]));
					break;
				}
				case "i":
				case "em":
				{
					ParseContainer(cnt, e, p, styles.concat(["font-style:italic"]));
					break;
				}
				case "img":
				{
					p = CreateParagraph();

					var img = {
						width: imgSize,
						image: readyImgs[baseName($(e).attr('src'))]
					};
					cnt.push(img);
					break;
				}
				case "a":
				{
					var t = {
						text: '('+$(e).attr('href')+') ',
						color: '#6699ff'
					};
					ParseContainer(cnt, e, p, styles);

					p.text.push(t);

					break;
				}
				case "h1":
				case "h2":
				case "h3":
				case "h4":
				case "h5":
				case "h6":
				{
					p = CreateParagraph();
					var t = {
						text: $(e).text(),
						bold: true
					};
					switch (e.nodeName.toLowerCase()) {
						case "h1" :
							t.fontSize = 32;
							t.margin = [0,20,0,10];
						break;
						case "h2" :
							t.fontSize = 24;
							t.margin = [0,15,0,5];
						break;
						case "h3" :
							t.fontSize = 20;
							t.margin = [0,10,0,5];
						break;
						case "h4" :
							t.fontSize = 18;
							t.margin = [0,10,0,5];
						break;
						case "h5" :
							t.fontSize = 16;
							t.margin = [0,10,0,5];
						break;
						case "h6" :
							t.fontSize = 14;
							t.margin = [0,10,0,5];
						break;
					}

					cnt.push(t);
					break;
				}
				case "span":
				{
					ParseContainer(cnt, e, p, styles);
					break;
				}
				case "li":
				{
					p = CreateParagraph();
					var st = {
						stack: []
					};
					st.stack.push(p);

					ParseContainer(st.stack, e, p, styles);
					cnt.push(st);

					break;
				}
				case "ol":
				{
					var list = {
						ol: []
					};
					ParseContainer(list.ol, e, p, styles);
					cnt.push(list);

					break;
				}
				case "ul":
				{
					var list = {
						ul: []
					};
					ParseContainer(list.ul, e, p, styles);
					cnt.push(list);

					break;
				}
				case "br":
				{
					p = CreateParagraph();
					cnt.push(p);
					break;
				}
				case "table":
				{
					var t = {
						table: {
							widths: [],
							body: []
						}
					};

					ParseContainer(t.table.body, e, p, styles);

					var widths = e.getAttribute("widths");
					if (!widths) {
						if (t.table.body.length !== 0) {
							if (t.table.body[0].length !== 0)
							for (var k = 0; k < t.table.body[0].length; k++) t.table.widths.push("*");
						}
					} else {
						var w = widths.split(",");
						for (var k = 0; k < w.length; k++) t.table.widths.push(w[k]);
					}
					cnt.push(t);
					break;
				}
				case "tbody":
				{
					ParseContainer(cnt, e, p, styles);
					break;
				}
				case "tr":
				{
					var row = [];
					ParseContainer(row, e, p, styles);
					cnt.push(row);
					break;
				}
				case "th":
				{
					p = CreateParagraph();
					var st = {
						stack: []
					};
					st.stack.push(p);

					var rspan = e.getAttribute("rowspan");
					if (rspan) st.rowSpan = parseInt(rspan);
					var cspan = e.getAttribute("colspan");
					if (cspan) st.colSpan = parseInt(cspan);

					ParseContainer(st.stack, e, p, styles.concat(["font-weight:bold"]));
					cnt.push(st);
					break;
				}
				case "td":
				{
					p = CreateParagraph();
					var st = {
						stack: []
					};
					st.stack.push(p);

					var rspan = e.getAttribute("rowspan");
					if (rspan) st.rowSpan = parseInt(rspan);
					var cspan = e.getAttribute("colspan");
					if (cspan) st.colSpan = parseInt(cspan);

					ParseContainer(st.stack, e, p, styles);
					cnt.push(st);
					break;
				}
				case "div":
				case "p":
				{
					p = CreateParagraph();
					var st = {
						stack: []
					};
					st.stack.push(p);
					ComputeStyle(st, styles);
					ParseContainer(st.stack, e, p);

					cnt.push(st);

					break;
				}
				case "hr":
				{
					var splt = {
						image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAYAAABytg0kAAAAEklEQVQIW2NkYGD4D8QMjDAGABMaAgFVG7naAAAAAElFTkSuQmCC',
						width:imgSize,
						height:0.5,
						alignment: 'center',
						margin:[0,10,0,10]
					};
					cnt.push(splt);
					break;
				}
				case "pre":
				{
					p = CreateParagraph();
					ParseContainer(cnt, e, p, styles);
					break;
				}
				case "blockquote":
				{
					var splt = {
						image: 'data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAYAAABytg0kAAAAEklEQVQIW2NkYGD4D8QMjDAGABMaAgFVG7naAAAAAElFTkSuQmCC',
						width:imgSize,
						height:0.5,
						alignment: 'center',
						margin:[0,10,0,10]
					};
					p = CreateParagraph();
					cnt.push(splt);
					ParseContainer(cnt, e, p, styles.concat(["font-weight:bold"]));
					cnt.push(splt);
					break;
				}
				default:
				{
					break;
				}
			}
			return p;
		}

		function ParseHtml(cnt, htmlText) {
			var html = $(htmlText.replace(/\t/g, "").replace(/\n/g, ""));
			var p = CreateParagraph();
			for (var i = 0; i < html.length; i++) ParseElement(cnt, html.get(i), p);
		}

		function CreateParagraph() {
			var p = {
				text: []
			};
			return p;
		}

		var content = [];
		ParseHtml(content, data);
		return content;
	}


})(jQuery);