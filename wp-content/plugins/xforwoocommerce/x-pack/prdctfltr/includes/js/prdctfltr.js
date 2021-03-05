/*! selectize.js - v0.12.6 | https://github.com/selectize/selectize.js | Apache License (v2) */
!function(a,b){"function"==typeof define&&define.amd?define("sifter",b):"object"==typeof exports?module.exports=b():a.Sifter=b()}(this,function(){var a=function(a,b){this.items=a,this.settings=b||{diacritics:!0}};a.prototype.tokenize=function(a){if(!(a=e(String(a||"").toLowerCase()))||!a.length)return[];var b,c,d,g,i=[],j=a.split(/ +/);for(b=0,c=j.length;b<c;b++){if(d=f(j[b]),this.settings.diacritics)for(g in h)h.hasOwnProperty(g)&&(d=d.replace(new RegExp(g,"g"),h[g]));i.push({string:j[b],regex:new RegExp(d,"i")})}return i},a.prototype.iterator=function(a,b){var c;c=g(a)?Array.prototype.forEach||function(a){for(var b=0,c=this.length;b<c;b++)a(this[b],b,this)}:function(a){for(var b in this)this.hasOwnProperty(b)&&a(this[b],b,this)},c.apply(a,[b])},a.prototype.getScoreFunction=function(a,b){var c,e,f,g,h;c=this,a=c.prepareSearch(a,b),f=a.tokens,e=a.options.fields,g=f.length,h=a.options.nesting;var i=function(a,b){var c,d;return a?(a=String(a||""),-1===(d=a.search(b.regex))?0:(c=b.string.length/a.length,0===d&&(c+=.5),c)):0},j=function(){var a=e.length;return a?1===a?function(a,b){return i(d(b,e[0],h),a)}:function(b,c){for(var f=0,g=0;f<a;f++)g+=i(d(c,e[f],h),b);return g/a}:function(){return 0}}();return g?1===g?function(a){return j(f[0],a)}:"and"===a.options.conjunction?function(a){for(var b,c=0,d=0;c<g;c++){if((b=j(f[c],a))<=0)return 0;d+=b}return d/g}:function(a){for(var b=0,c=0;b<g;b++)c+=j(f[b],a);return c/g}:function(){return 0}},a.prototype.getSortFunction=function(a,c){var e,f,g,h,i,j,k,l,m,n,o;if(g=this,a=g.prepareSearch(a,c),o=!a.query&&c.sort_empty||c.sort,m=function(a,b){return"$score"===a?b.score:d(g.items[b.id],a,c.nesting)},i=[],o)for(e=0,f=o.length;e<f;e++)(a.query||"$score"!==o[e].field)&&i.push(o[e]);if(a.query){for(n=!0,e=0,f=i.length;e<f;e++)if("$score"===i[e].field){n=!1;break}n&&i.unshift({field:"$score",direction:"desc"})}else for(e=0,f=i.length;e<f;e++)if("$score"===i[e].field){i.splice(e,1);break}for(l=[],e=0,f=i.length;e<f;e++)l.push("desc"===i[e].direction?-1:1);return j=i.length,j?1===j?(h=i[0].field,k=l[0],function(a,c){return k*b(m(h,a),m(h,c))}):function(a,c){var d,e,f;for(d=0;d<j;d++)if(f=i[d].field,e=l[d]*b(m(f,a),m(f,c)))return e;return 0}:null},a.prototype.prepareSearch=function(a,b){if("object"==typeof a)return a;b=c({},b);var d=b.fields,e=b.sort,f=b.sort_empty;return d&&!g(d)&&(b.fields=[d]),e&&!g(e)&&(b.sort=[e]),f&&!g(f)&&(b.sort_empty=[f]),{options:b,query:String(a||"").toLowerCase(),tokens:this.tokenize(a),total:0,items:[]}},a.prototype.search=function(a,b){var c,d,e,f,g=this;return d=this.prepareSearch(a,b),b=d.options,a=d.query,f=b.score||g.getScoreFunction(d),a.length?g.iterator(g.items,function(a,e){c=f(a),(!1===b.filter||c>0)&&d.items.push({score:c,id:e})}):g.iterator(g.items,function(a,b){d.items.push({score:1,id:b})}),e=g.getSortFunction(d,b),e&&d.items.sort(e),d.total=d.items.length,"number"==typeof b.limit&&(d.items=d.items.slice(0,b.limit)),d};var b=function(a,b){return"number"==typeof a&&"number"==typeof b?a>b?1:a<b?-1:0:(a=i(String(a||"")),b=i(String(b||"")),a>b?1:b>a?-1:0)},c=function(a,b){var c,d,e,f;for(c=1,d=arguments.length;c<d;c++)if(f=arguments[c])for(e in f)f.hasOwnProperty(e)&&(a[e]=f[e]);return a},d=function(a,b,c){if(a&&b){if(!c)return a[b];for(var d=b.split(".");d.length&&(a=a[d.shift()]););return a}},e=function(a){return(a+"").replace(/^\s+|\s+$|/g,"")},f=function(a){return(a+"").replace(/([.?*+^$[\]\\(){}|-])/g,"\\$1")},g=Array.isArray||"undefined"!=typeof $&&$.isArray||function(a){return"[object Array]"===Object.prototype.toString.call(a)},h={a:"[aḀḁĂăÂâǍǎȺⱥȦȧẠạÄäÀàÁáĀāÃãÅåąĄÃąĄ]",b:"[b␢βΒB฿𐌁ᛒ]",c:"[cĆćĈĉČčĊċC̄c̄ÇçḈḉȻȼƇƈɕᴄＣｃ]",d:"[dĎďḊḋḐḑḌḍḒḓḎḏĐđD̦d̦ƉɖƊɗƋƌᵭᶁᶑȡᴅＤｄð]",e:"[eÉéÈèÊêḘḙĚěĔĕẼẽḚḛẺẻĖėËëĒēȨȩĘęᶒɆɇȄȅẾếỀềỄễỂểḜḝḖḗḔḕȆȇẸẹỆệⱸᴇＥｅɘǝƏƐε]",f:"[fƑƒḞḟ]",g:"[gɢ₲ǤǥĜĝĞğĢģƓɠĠġ]",h:"[hĤĥĦħḨḩẖẖḤḥḢḣɦʰǶƕ]",i:"[iÍíÌìĬĭÎîǏǐÏïḮḯĨĩĮįĪīỈỉȈȉȊȋỊịḬḭƗɨɨ̆ᵻᶖİiIıɪＩｉ]",j:"[jȷĴĵɈɉʝɟʲ]",k:"[kƘƙꝀꝁḰḱǨǩḲḳḴḵκϰ₭]",l:"[lŁłĽľĻļĹĺḶḷḸḹḼḽḺḻĿŀȽƚⱠⱡⱢɫɬᶅɭȴʟＬｌ]",n:"[nŃńǸǹŇňÑñṄṅŅņṆṇṊṋṈṉN̈n̈ƝɲȠƞᵰᶇɳȵɴＮｎŊŋ]",o:"[oØøÖöÓóÒòÔôǑǒŐőŎŏȮȯỌọƟɵƠơỎỏŌōÕõǪǫȌȍՕօ]",p:"[pṔṕṖṗⱣᵽƤƥᵱ]",q:"[qꝖꝗʠɊɋꝘꝙq̃]",r:"[rŔŕɌɍŘřŖŗṘṙȐȑȒȓṚṛⱤɽ]",s:"[sŚśṠṡṢṣꞨꞩŜŝŠšŞşȘșS̈s̈]",t:"[tŤťṪṫŢţṬṭƮʈȚțṰṱṮṯƬƭ]",u:"[uŬŭɄʉỤụÜüÚúÙùÛûǓǔŰűŬŭƯưỦủŪūŨũŲųȔȕ∪]",v:"[vṼṽṾṿƲʋꝞꝟⱱʋ]",w:"[wẂẃẀẁŴŵẄẅẆẇẈẉ]",x:"[xẌẍẊẋχ]",y:"[yÝýỲỳŶŷŸÿỸỹẎẏỴỵɎɏƳƴ]",z:"[zŹźẐẑŽžŻżẒẓẔẕƵƶ]"},i=function(){var a,b,c,d,e="",f={};for(c in h)if(h.hasOwnProperty(c))for(d=h[c].substring(2,h[c].length-1),e+=d,a=0,b=d.length;a<b;a++)f[d.charAt(a)]=c;var g=new RegExp("["+e+"]","g");return function(a){return a.replace(g,function(a){return f[a]}).toLowerCase()}}();return a}),function(a,b){"function"==typeof define&&define.amd?define("microplugin",b):"object"==typeof exports?module.exports=b():a.MicroPlugin=b()}(this,function(){var a={};a.mixin=function(a){a.plugins={},a.prototype.initializePlugins=function(a){var c,d,e,f=this,g=[];if(f.plugins={names:[],settings:{},requested:{},loaded:{}},b.isArray(a))for(c=0,d=a.length;c<d;c++)"string"==typeof a[c]?g.push(a[c]):(f.plugins.settings[a[c].name]=a[c].options,g.push(a[c].name));else if(a)for(e in a)a.hasOwnProperty(e)&&(f.plugins.settings[e]=a[e],g.push(e));for(;g.length;)f.require(g.shift())},a.prototype.loadPlugin=function(b){var c=this,d=c.plugins,e=a.plugins[b];if(!a.plugins.hasOwnProperty(b))throw new Error('Unable to find "'+b+'" plugin');d.requested[b]=!0,d.loaded[b]=e.fn.apply(c,[c.plugins.settings[b]||{}]),d.names.push(b)},a.prototype.require=function(a){var b=this,c=b.plugins;if(!b.plugins.loaded.hasOwnProperty(a)){if(c.requested[a])throw new Error('Plugin has circular dependency ("'+a+'")');b.loadPlugin(a)}return c.loaded[a]},a.define=function(b,c){a.plugins[b]={name:b,fn:c}}};var b={isArray:Array.isArray||function(a){return"[object Array]"===Object.prototype.toString.call(a)}};return a}),function(a,b){"function"==typeof define&&define.amd?define("selectize",["jquery","sifter","microplugin"],b):"object"==typeof exports?module.exports=b(require("jquery"),require("sifter"),require("microplugin")):a.Selectize=b(a.jQuery,a.Sifter,a.MicroPlugin)}(this,function(a,b,c){"use strict";var d=function(a,b){if("string"!=typeof b||b.length){var c="string"==typeof b?new RegExp(b,"i"):b,d=function(a){var b=0;if(3===a.nodeType){var e=a.data.search(c);if(e>=0&&a.data.length>0){var f=a.data.match(c),g=document.createElement("span");g.className="highlight";var h=a.splitText(e),i=(h.splitText(f[0].length),h.cloneNode(!0));g.appendChild(i),h.parentNode.replaceChild(g,h),b=1}}else if(1===a.nodeType&&a.childNodes&&!/(script|style)/i.test(a.tagName)&&("highlight"!==a.className||"SPAN"!==a.tagName))for(var j=0;j<a.childNodes.length;++j)j+=d(a.childNodes[j]);return b};return a.each(function(){d(this)})}};a.fn.removeHighlight=function(){return this.find("span.highlight").each(function(){this.parentNode.firstChild.nodeName;var a=this.parentNode;a.replaceChild(this.firstChild,this),a.normalize()}).end()};var e=function(){};e.prototype={on:function(a,b){this._events=this._events||{},this._events[a]=this._events[a]||[],this._events[a].push(b)},off:function(a,b){var c=arguments.length;return 0===c?delete this._events:1===c?delete this._events[a]:(this._events=this._events||{},void(a in this._events!=!1&&this._events[a].splice(this._events[a].indexOf(b),1)))},trigger:function(a){if(this._events=this._events||{},a in this._events!=!1)for(var b=0;b<this._events[a].length;b++)this._events[a][b].apply(this,Array.prototype.slice.call(arguments,1))}},e.mixin=function(a){for(var b=["on","off","trigger"],c=0;c<b.length;c++)a.prototype[b[c]]=e.prototype[b[c]]};var f=/Mac/.test(navigator.userAgent),g=f?91:17,h=f?18:17,i=!/android/i.test(window.navigator.userAgent)&&!!document.createElement("input").validity,j=function(a){return void 0!==a},k=function(a){return void 0===a||null===a?null:"boolean"==typeof a?a?"1":"0":a+""},l=function(a){return(a+"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;")},m={};m.before=function(a,b,c){var d=a[b];a[b]=function(){return c.apply(a,arguments),d.apply(a,arguments)}},m.after=function(a,b,c){var d=a[b];a[b]=function(){var b=d.apply(a,arguments);return c.apply(a,arguments),b}};var n=function(a){var b=!1;return function(){b||(b=!0,a.apply(this,arguments))}},o=function(a,b){var c;return function(){var d=this,e=arguments;window.clearTimeout(c),c=window.setTimeout(function(){a.apply(d,e)},b)}},p=function(a,b,c){var d,e=a.trigger,f={};a.trigger=function(){var c=arguments[0];if(-1===b.indexOf(c))return e.apply(a,arguments);f[c]=arguments},c.apply(a,[]),a.trigger=e;for(d in f)f.hasOwnProperty(d)&&e.apply(a,f[d])},q=function(a,b,c,d){a.on(b,c,function(b){for(var c=b.target;c&&c.parentNode!==a[0];)c=c.parentNode;return b.currentTarget=c,d.apply(this,[b])})},r=function(a){var b={};if("selectionStart"in a)b.start=a.selectionStart,b.length=a.selectionEnd-b.start;else if(document.selection){a.focus();var c=document.selection.createRange(),d=document.selection.createRange().text.length;c.moveStart("character",-a.value.length),b.start=c.text.length-d,b.length=d}return b},s=function(a,b,c){var d,e,f={};if(c)for(d=0,e=c.length;d<e;d++)f[c[d]]=a.css(c[d]);else f=a.css();b.css(f)},t=function(b,c){return b?(w.$testInput||(w.$testInput=a("<span />").css({position:"absolute",top:-99999,left:-99999,width:"auto",padding:0,whiteSpace:"pre"}).appendTo("body")),w.$testInput.text(b),s(c,w.$testInput,["letterSpacing","fontSize","fontFamily","fontWeight","textTransform"]),w.$testInput.width()):0},u=function(a){var b=null,c=function(c,d){var e,f,g,h,i,j,k,l;c=c||window.event||{},d=d||{},c.metaKey||c.altKey||(d.force||!1!==a.data("grow"))&&(e=a.val(),c.type&&"keydown"===c.type.toLowerCase()&&(f=c.keyCode,g=f>=48&&f<=57||f>=65&&f<=90||f>=96&&f<=111||f>=186&&f<=222||32===f,46===f||8===f?(l=r(a[0]),l.length?e=e.substring(0,l.start)+e.substring(l.start+l.length):8===f&&l.start?e=e.substring(0,l.start-1)+e.substring(l.start+1):46===f&&void 0!==l.start&&(e=e.substring(0,l.start)+e.substring(l.start+1))):g&&(j=c.shiftKey,k=String.fromCharCode(c.keyCode),k=j?k.toUpperCase():k.toLowerCase(),e+=k)),h=a.attr("placeholder"),!e&&h&&(e=h),(i=t(e,a)+4)!==b&&(b=i,a.width(i),a.triggerHandler("resize")))};a.on("keydown keyup update blur",c),c()},v=function(a){var b=document.createElement("div");return b.appendChild(a.cloneNode(!0)),b.innerHTML},w=function(c,d){var e,f,g,h,i=this;h=c[0],h.selectize=i;var j=window.getComputedStyle&&window.getComputedStyle(h,null);if(g=j?j.getPropertyValue("direction"):h.currentStyle&&h.currentStyle.direction,g=g||c.parents("[dir]:first").attr("dir")||"",a.extend(i,{order:0,settings:d,$input:c,tabIndex:c.attr("tabindex")||"",tagType:"select"===h.tagName.toLowerCase()?1:2,rtl:/rtl/i.test(g),eventNS:".selectize"+ ++w.count,highlightedValue:null,isBlurring:!1,isOpen:!1,isDisabled:!1,isRequired:c.is("[required]"),isInvalid:!1,isLocked:!1,isFocused:!1,isInputHidden:!1,isSetup:!1,isShiftDown:!1,isCmdDown:!1,isCtrlDown:!1,ignoreFocus:!1,ignoreBlur:!1,ignoreHover:!1,hasOptions:!1,currentResults:null,lastValue:"",caretPos:0,loading:0,loadedSearches:{},$activeOption:null,$activeItems:[],optgroups:{},options:{},userOptions:{},items:[],renderCache:{},onSearchChange:null===d.loadThrottle?i.onSearchChange:o(i.onSearchChange,d.loadThrottle)}),i.sifter=new b(this.options,{diacritics:d.diacritics}),i.settings.options){for(e=0,f=i.settings.options.length;e<f;e++)i.registerOption(i.settings.options[e]);delete i.settings.options}if(i.settings.optgroups){for(e=0,f=i.settings.optgroups.length;e<f;e++)i.registerOptionGroup(i.settings.optgroups[e]);delete i.settings.optgroups}i.settings.mode=i.settings.mode||(1===i.settings.maxItems?"single":"multi"),"boolean"!=typeof i.settings.hideSelected&&(i.settings.hideSelected="multi"===i.settings.mode),i.initializePlugins(i.settings.plugins),i.setupCallbacks(),i.setupTemplates(),i.setup()};return e.mixin(w),void 0!==c?c.mixin(w):function(a,b){b||(b={});console.error("Selectize: "+a),b.explanation&&(console.group&&console.group(),console.error(b.explanation),console.group&&console.groupEnd())}("Dependency MicroPlugin is missing",{explanation:'Make sure you either: (1) are using the "standalone" version of Selectize, or (2) require MicroPlugin before you load Selectize.'}),a.extend(w.prototype,{setup:function(){var b,c,d,e,j,k,l,m,n,o,p=this,r=p.settings,s=p.eventNS,t=a(window),v=a(document),w=p.$input;if(l=p.settings.mode,m=w.attr("class")||"",b=a("<div>").addClass(r.wrapperClass).addClass(m).addClass(l),c=a("<div>").addClass(r.inputClass).addClass("items").appendTo(b),d=a('<input type="text" autocomplete="off" />').appendTo(c).attr("tabindex",w.is(":disabled")?"-1":p.tabIndex),k=a(r.dropdownParent||b),e=a("<div>").addClass(r.dropdownClass).addClass(l).hide().appendTo(k),j=a("<div>").addClass(r.dropdownContentClass).appendTo(e),(o=w.attr("id"))&&(d.attr("id",o+"-selectized"),a("label[for='"+o+"']").attr("for",o+"-selectized")),p.settings.copyClassesToDropdown&&e.addClass(m),b.css({width:w[0].style.width}),p.plugins.names.length&&(n="plugin-"+p.plugins.names.join(" plugin-"),b.addClass(n),e.addClass(n)),(null===r.maxItems||r.maxItems>1)&&1===p.tagType&&w.attr("multiple","multiple"),p.settings.placeholder&&d.attr("placeholder",r.placeholder),!p.settings.splitOn&&p.settings.delimiter){var x=p.settings.delimiter.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&");p.settings.splitOn=new RegExp("\\s*"+x+"+\\s*")}w.attr("autocorrect")&&d.attr("autocorrect",w.attr("autocorrect")),w.attr("autocapitalize")&&d.attr("autocapitalize",w.attr("autocapitalize")),d[0].type=w[0].type,p.$wrapper=b,p.$control=c,p.$control_input=d,p.$dropdown=e,p.$dropdown_content=j,e.on("mouseenter mousedown click","[data-disabled]>[data-selectable]",function(a){a.stopImmediatePropagation()}),e.on("mouseenter","[data-selectable]",function(){return p.onOptionHover.apply(p,arguments)}),e.on("mousedown click","[data-selectable]",function(){return p.onOptionSelect.apply(p,arguments)}),q(c,"mousedown","*:not(input)",function(){return p.onItemSelect.apply(p,arguments)}),u(d),c.on({mousedown:function(){return p.onMouseDown.apply(p,arguments)},click:function(){return p.onClick.apply(p,arguments)}}),d.on({mousedown:function(a){a.stopPropagation()},keydown:function(){return p.onKeyDown.apply(p,arguments)},keyup:function(){return p.onKeyUp.apply(p,arguments)},keypress:function(){return p.onKeyPress.apply(p,arguments)},resize:function(){p.positionDropdown.apply(p,[])},blur:function(){return p.onBlur.apply(p,arguments)},focus:function(){return p.ignoreBlur=!1,p.onFocus.apply(p,arguments)},paste:function(){return p.onPaste.apply(p,arguments)}}),v.on("keydown"+s,function(a){p.isCmdDown=a[f?"metaKey":"ctrlKey"],p.isCtrlDown=a[f?"altKey":"ctrlKey"],p.isShiftDown=a.shiftKey}),v.on("keyup"+s,function(a){a.keyCode===h&&(p.isCtrlDown=!1),16===a.keyCode&&(p.isShiftDown=!1),a.keyCode===g&&(p.isCmdDown=!1)}),v.on("mousedown"+s,function(a){if(p.isFocused){if(a.target===p.$dropdown[0]||a.target.parentNode===p.$dropdown[0])return!1;p.$control.has(a.target).length||a.target===p.$control[0]||p.blur(a.target)}}),t.on(["scroll"+s,"resize"+s].join(" "),function(){p.isOpen&&p.positionDropdown.apply(p,arguments)}),t.on("mousemove"+s,function(){p.ignoreHover=!1}),this.revertSettings={$children:w.children().detach(),tabindex:w.attr("tabindex")},w.attr("tabindex",-1).hide().after(p.$wrapper),a.isArray(r.items)&&(p.setValue(r.items),delete r.items),i&&w.on("invalid"+s,function(a){a.preventDefault(),p.isInvalid=!0,p.refreshState()}),p.updateOriginalInput(),p.refreshItems(),p.refreshState(),p.updatePlaceholder(),p.isSetup=!0,w.is(":disabled")&&p.disable(),p.on("change",this.onChange),w.data("selectize",p),w.addClass("selectized"),p.trigger("initialize"),!0===r.preload&&p.onSearchChange("")},setupTemplates:function(){var b=this,c=b.settings.labelField,d=b.settings.optgroupLabelField,e={optgroup:function(a){return'<div class="optgroup">'+a.html+"</div>"},optgroup_header:function(a,b){return'<div class="optgroup-header">'+b(a[d])+"</div>"},option:function(a,b){return'<div class="option">'+b(a[c])+"</div>"},item:function(a,b){return'<div class="item">'+b(a[c])+"</div>"},option_create:function(a,b){return'<div class="create">Add <strong>'+b(a.input)+"</strong>&hellip;</div>"}};b.settings.render=a.extend({},e,b.settings.render)},setupCallbacks:function(){var a,b,c={initialize:"onInitialize",change:"onChange",item_add:"onItemAdd",item_remove:"onItemRemove",clear:"onClear",option_add:"onOptionAdd",option_remove:"onOptionRemove",option_clear:"onOptionClear",optgroup_add:"onOptionGroupAdd",optgroup_remove:"onOptionGroupRemove",optgroup_clear:"onOptionGroupClear",dropdown_open:"onDropdownOpen",dropdown_close:"onDropdownClose",type:"onType",load:"onLoad",focus:"onFocus",blur:"onBlur"};for(a in c)c.hasOwnProperty(a)&&(b=this.settings[c[a]])&&this.on(a,b)},onClick:function(a){var b=this;b.isFocused&&b.isOpen||(b.focus(),a.preventDefault())},onMouseDown:function(b){var c=this,d=b.isDefaultPrevented();a(b.target);if(c.isFocused){if(b.target!==c.$control_input[0])return"single"===c.settings.mode?c.isOpen?c.close():c.open():d||c.setActiveItem(null),!1}else d||window.setTimeout(function(){c.focus()},0)},onChange:function(){this.$input.trigger("change")},onPaste:function(b){var c=this;if(c.isFull()||c.isInputHidden||c.isLocked)return void b.preventDefault();c.settings.splitOn&&setTimeout(function(){var b=c.$control_input.val();if(b.match(c.settings.splitOn))for(var d=a.trim(b).split(c.settings.splitOn),e=0,f=d.length;e<f;e++)c.createItem(d[e])},0)},onKeyPress:function(a){if(this.isLocked)return a&&a.preventDefault();var b=String.fromCharCode(a.keyCode||a.which);return this.settings.create&&"multi"===this.settings.mode&&b===this.settings.delimiter?(this.createItem(),a.preventDefault(),!1):void 0},onKeyDown:function(a){var b=(a.target,this.$control_input[0],this);if(b.isLocked)return void(9!==a.keyCode&&a.preventDefault());switch(a.keyCode){case 65:if(b.isCmdDown)return void b.selectAll();break;case 27:return void(b.isOpen&&(a.preventDefault(),a.stopPropagation(),b.close()));case 78:if(!a.ctrlKey||a.altKey)break;case 40:if(!b.isOpen&&b.hasOptions)b.open();else if(b.$activeOption){b.ignoreHover=!0;var c=b.getAdjacentOption(b.$activeOption,1);c.length&&b.setActiveOption(c,!0,!0)}return void a.preventDefault();case 80:if(!a.ctrlKey||a.altKey)break;case 38:if(b.$activeOption){b.ignoreHover=!0;var d=b.getAdjacentOption(b.$activeOption,-1);d.length&&b.setActiveOption(d,!0,!0)}return void a.preventDefault();case 13:return void(b.isOpen&&b.$activeOption&&(b.onOptionSelect({currentTarget:b.$activeOption}),a.preventDefault()));case 37:return void b.advanceSelection(-1,a);case 39:return void b.advanceSelection(1,a);case 9:return b.settings.selectOnTab&&b.isOpen&&b.$activeOption&&(b.onOptionSelect({currentTarget:b.$activeOption}),b.isFull()||a.preventDefault()),void(b.settings.create&&b.createItem()&&a.preventDefault());case 8:case 46:return void b.deleteSelection(a)}return!b.isFull()&&!b.isInputHidden||(f?a.metaKey:a.ctrlKey)?void 0:void a.preventDefault()},onKeyUp:function(a){var b=this;if(b.isLocked)return a&&a.preventDefault();var c=b.$control_input.val()||"";b.lastValue!==c&&(b.lastValue=c,b.onSearchChange(c),b.refreshOptions(),b.trigger("type",c))},onSearchChange:function(a){var b=this,c=b.settings.load;c&&(b.loadedSearches.hasOwnProperty(a)||(b.loadedSearches[a]=!0,b.load(function(d){c.apply(b,[a,d])})))},onFocus:function(a){var b=this,c=b.isFocused;if(b.isDisabled)return b.blur(),a&&a.preventDefault(),!1;b.ignoreFocus||(b.isFocused=!0,"focus"===b.settings.preload&&b.onSearchChange(""),c||b.trigger("focus"),b.$activeItems.length||(b.showInput(),b.setActiveItem(null),b.refreshOptions(!!b.settings.openOnFocus)),b.refreshState())},onBlur:function(a,b){var c=this;if(c.isFocused&&(c.isFocused=!1,!c.ignoreFocus)){if(!c.ignoreBlur&&document.activeElement===c.$dropdown_content[0])return c.ignoreBlur=!0,void c.onFocus(a);var d=function(){c.close(),c.setTextboxValue(""),c.setActiveItem(null),c.setActiveOption(null),c.setCaret(c.items.length),c.refreshState(),b&&b.focus&&b.focus(),c.isBlurring=!1,c.ignoreFocus=!1,c.trigger("blur")};c.isBlurring=!0,c.ignoreFocus=!0,c.settings.create&&c.settings.createOnBlur?c.createItem(null,!1,d):d()}},onOptionHover:function(a){this.ignoreHover||this.setActiveOption(a.currentTarget,!1)},onOptionSelect:function(b){var c,d,e=this;b.preventDefault&&(b.preventDefault(),b.stopPropagation()),d=a(b.currentTarget),d.hasClass("create")?e.createItem(null,function(){e.settings.closeAfterSelect&&e.close()}):void 0!==(c=d.attr("data-value"))&&(e.lastQuery=null,e.setTextboxValue(""),e.addItem(c),e.settings.closeAfterSelect?e.close():!e.settings.hideSelected&&b.type&&/mouse/.test(b.type)&&e.setActiveOption(e.getOption(c)))},onItemSelect:function(a){var b=this;b.isLocked||"multi"===b.settings.mode&&(a.preventDefault(),b.setActiveItem(a.currentTarget,a))},load:function(a){var b=this,c=b.$wrapper.addClass(b.settings.loadingClass);b.loading++,a.apply(b,[function(a){b.loading=Math.max(b.loading-1,0),a&&a.length&&(b.addOption(a),b.refreshOptions(b.isFocused&&!b.isInputHidden)),b.loading||c.removeClass(b.settings.loadingClass),b.trigger("load",a)}])},setTextboxValue:function(a){var b=this.$control_input;b.val()!==a&&(b.val(a).triggerHandler("update"),this.lastValue=a)},getValue:function(){return 1===this.tagType&&this.$input.attr("multiple")?this.items:this.items.join(this.settings.delimiter)},setValue:function(a,b){p(this,b?[]:["change"],function(){this.clear(b),this.addItems(a,b)})},setActiveItem:function(b,c){var d,e,f,g,h,i,j,k,l=this;if("single"!==l.settings.mode){if(b=a(b),!b.length)return a(l.$activeItems).removeClass("active"),l.$activeItems=[],void(l.isFocused&&l.showInput());if("mousedown"===(d=c&&c.type.toLowerCase())&&l.isShiftDown&&l.$activeItems.length){for(k=l.$control.children(".active:last"),g=Array.prototype.indexOf.apply(l.$control[0].childNodes,[k[0]]),h=Array.prototype.indexOf.apply(l.$control[0].childNodes,[b[0]]),g>h&&(j=g,g=h,h=j),e=g;e<=h;e++)i=l.$control[0].childNodes[e],-1===l.$activeItems.indexOf(i)&&(a(i).addClass("active"),l.$activeItems.push(i));c.preventDefault()}else"mousedown"===d&&l.isCtrlDown||"keydown"===d&&this.isShiftDown?b.hasClass("active")?(f=l.$activeItems.indexOf(b[0]),l.$activeItems.splice(f,1),b.removeClass("active")):l.$activeItems.push(b.addClass("active")[0]):(a(l.$activeItems).removeClass("active"),l.$activeItems=[b.addClass("active")[0]]);l.hideInput(),this.isFocused||l.focus()}},setActiveOption:function(b,c,d){var e,f,g,h,i,k=this;k.$activeOption&&k.$activeOption.removeClass("active"),k.$activeOption=null,b=a(b),b.length&&(k.$activeOption=b.addClass("active"),!c&&j(c)||(e=k.$dropdown_content.height(),f=k.$activeOption.outerHeight(!0),c=k.$dropdown_content.scrollTop()||0,g=k.$activeOption.offset().top-k.$dropdown_content.offset().top+c,h=g,i=g-e+f,g+f>e+c?k.$dropdown_content.stop().animate({scrollTop:i},d?k.settings.scrollDuration:0):g<c&&k.$dropdown_content.stop().animate({scrollTop:h},d?k.settings.scrollDuration:0)))},selectAll:function(){var a=this;"single"!==a.settings.mode&&(a.$activeItems=Array.prototype.slice.apply(a.$control.children(":not(input)").addClass("active")),a.$activeItems.length&&(a.hideInput(),a.close()),a.focus())},hideInput:function(){var a=this;a.setTextboxValue(""),a.$control_input.css({opacity:0,position:"absolute",left:a.rtl?1e4:-1e4}),a.isInputHidden=!0},showInput:function(){this.$control_input.css({opacity:1,position:"relative",left:0}),this.isInputHidden=!1},focus:function(){var a=this;a.isDisabled||(a.ignoreFocus=!0,a.$control_input[0].focus(),window.setTimeout(function(){a.ignoreFocus=!1,a.onFocus()},0))},blur:function(a){this.$control_input[0].blur(),this.onBlur(null,a)},getScoreFunction:function(a){return this.sifter.getScoreFunction(a,this.getSearchOptions())},getSearchOptions:function(){var a=this.settings,b=a.sortField;return"string"==typeof b&&(b=[{field:b}]),{fields:a.searchField,conjunction:a.searchConjunction,sort:b,nesting:a.nesting}},search:function(b){var c,d,e,f=this,g=f.settings,h=this.getSearchOptions();if(g.score&&"function"!=typeof(e=f.settings.score.apply(this,[b])))throw new Error('Selectize "score" setting must be a function that returns a function');if(b!==f.lastQuery?(f.lastQuery=b,d=f.sifter.search(b,a.extend(h,{score:e})),f.currentResults=d):d=a.extend(!0,{},f.currentResults),g.hideSelected)for(c=d.items.length-1;c>=0;c--)-1!==f.items.indexOf(k(d.items[c].id))&&d.items.splice(c,1);return d},refreshOptions:function(b){var c,e,f,g,h,i,j,l,m,n,o,p,q,r,s,t;void 0===b&&(b=!0);var u=this,w=a.trim(u.$control_input.val()),x=u.search(w),y=u.$dropdown_content,z=u.$activeOption&&k(u.$activeOption.attr("data-value"));for(g=x.items.length,"number"==typeof u.settings.maxOptions&&(g=Math.min(g,u.settings.maxOptions)),h={},i=[],c=0;c<g;c++)for(j=u.options[x.items[c].id],l=u.render("option",j),m=j[u.settings.optgroupField]||"",n=a.isArray(m)?m:[m],e=0,f=n&&n.length;e<f;e++)m=n[e],u.optgroups.hasOwnProperty(m)||(m=""),h.hasOwnProperty(m)||(h[m]=document.createDocumentFragment(),i.push(m)),h[m].appendChild(l);for(this.settings.lockOptgroupOrder&&i.sort(function(a,b){return(u.optgroups[a].$order||0)-(u.optgroups[b].$order||0)}),o=document.createDocumentFragment(),c=0,g=i.length;c<g;c++)m=i[c],u.optgroups.hasOwnProperty(m)&&h[m].childNodes.length?(p=document.createDocumentFragment(),p.appendChild(u.render("optgroup_header",u.optgroups[m])),p.appendChild(h[m]),o.appendChild(u.render("optgroup",a.extend({},u.optgroups[m],{html:v(p),dom:p})))):o.appendChild(h[m]);if(y.html(o),u.settings.highlight&&(y.removeHighlight(),x.query.length&&x.tokens.length))for(c=0,g=x.tokens.length;c<g;c++)d(y,x.tokens[c].regex);if(!u.settings.hideSelected)for(c=0,g=u.items.length;c<g;c++)u.getOption(u.items[c]).addClass("selected");q=u.canCreate(w),q&&(y.prepend(u.render("option_create",{input:w})),t=a(y[0].childNodes[0])),u.hasOptions=x.items.length>0||q,u.hasOptions?(x.items.length>0?(s=z&&u.getOption(z),s&&s.length?r=s:"single"===u.settings.mode&&u.items.length&&(r=u.getOption(u.items[0])),r&&r.length||(r=t&&!u.settings.addPrecedence?u.getAdjacentOption(t,1):y.find("[data-selectable]:first"))):r=t,u.setActiveOption(r),b&&!u.isOpen&&u.open()):(u.setActiveOption(null),b&&u.isOpen&&u.close())},addOption:function(b){var c,d,e,f=this;if(a.isArray(b))for(c=0,d=b.length;c<d;c++)f.addOption(b[c]);else(e=f.registerOption(b))&&(f.userOptions[e]=!0,f.lastQuery=null,f.trigger("option_add",e,b))},registerOption:function(a){var b=k(a[this.settings.valueField]);return void 0!==b&&null!==b&&!this.options.hasOwnProperty(b)&&(a.$order=a.$order||++this.order,this.options[b]=a,b)},registerOptionGroup:function(a){var b=k(a[this.settings.optgroupValueField]);return!!b&&(a.$order=a.$order||++this.order,this.optgroups[b]=a,b)},addOptionGroup:function(a,b){b[this.settings.optgroupValueField]=a,(a=this.registerOptionGroup(b))&&this.trigger("optgroup_add",a,b)},removeOptionGroup:function(a){this.optgroups.hasOwnProperty(a)&&(delete this.optgroups[a],this.renderCache={},this.trigger("optgroup_remove",a))},clearOptionGroups:function(){this.optgroups={},this.renderCache={},this.trigger("optgroup_clear")},updateOption:function(b,c){var d,e,f,g,h,i,j,l=this;if(b=k(b),f=k(c[l.settings.valueField]),null!==b&&l.options.hasOwnProperty(b)){if("string"!=typeof f)throw new Error("Value must be set in option data");j=l.options[b].$order,f!==b&&(delete l.options[b],-1!==(g=l.items.indexOf(b))&&l.items.splice(g,1,f)),c.$order=c.$order||j,l.options[f]=c,h=l.renderCache.item,i=l.renderCache.option,h&&(delete h[b],delete h[f]),i&&(delete i[b],delete i[f]),-1!==l.items.indexOf(f)&&(d=l.getItem(b),e=a(l.render("item",c)),d.hasClass("active")&&e.addClass("active"),d.replaceWith(e)),l.lastQuery=null,l.isOpen&&l.refreshOptions(!1)}},removeOption:function(a,b){var c=this;a=k(a);var d=c.renderCache.item,e=c.renderCache.option;d&&delete d[a],e&&delete e[a],delete c.userOptions[a],delete c.options[a],c.lastQuery=null,c.trigger("option_remove",a),c.removeItem(a,b)},clearOptions:function(){var b=this;b.loadedSearches={},b.userOptions={},b.renderCache={};var c=b.options;a.each(b.options,function(a,d){-1==b.items.indexOf(a)&&delete c[a]}),b.options=b.sifter.items=c,b.lastQuery=null,b.trigger("option_clear")},getOption:function(a){return this.getElementWithValue(a,this.$dropdown_content.find("[data-selectable]"))},getAdjacentOption:function(b,c){var d=this.$dropdown.find("[data-selectable]"),e=d.index(b)+c;return e>=0&&e<d.length?d.eq(e):a()},getElementWithValue:function(b,c){if(void 0!==(b=k(b))&&null!==b)for(var d=0,e=c.length;d<e;d++)if(c[d].getAttribute("data-value")===b)return a(c[d]);return a()},getItem:function(a){return this.getElementWithValue(a,this.$control.children())},addItems:function(b,c){this.buffer=document.createDocumentFragment();for(var d=this.$control[0].childNodes,e=0;e<d.length;e++)this.buffer.appendChild(d[e]);for(var f=a.isArray(b)?b:[b],e=0,g=f.length;e<g;e++)this.isPending=e<g-1,this.addItem(f[e],c);var h=this.$control[0];h.insertBefore(this.buffer,h.firstChild),this.buffer=null},addItem:function(b,c){p(this,c?[]:["change"],function(){var d,e,f,g,h,i=this,j=i.settings.mode;if(b=k(b),-1!==i.items.indexOf(b))return void("single"===j&&i.close());i.options.hasOwnProperty(b)&&("single"===j&&i.clear(c),"multi"===j&&i.isFull()||(d=a(i.render("item",i.options[b])),h=i.isFull(),i.items.splice(i.caretPos,0,b),i.insertAtCaret(d),(!i.isPending||!h&&i.isFull())&&i.refreshState(),i.isSetup&&(f=i.$dropdown_content.find("[data-selectable]"),i.isPending||(e=i.getOption(b),g=i.getAdjacentOption(e,1).attr("data-value"),i.refreshOptions(i.isFocused&&"single"!==j),g&&i.setActiveOption(i.getOption(g))),!f.length||i.isFull()?i.close():i.isPending||i.positionDropdown(),i.updatePlaceholder(),i.trigger("item_add",b,d),i.isPending||i.updateOriginalInput({silent:c}))))})},removeItem:function(b,c){var d,e,f,g=this;d=b instanceof a?b:g.getItem(b),b=k(d.attr("data-value")),-1!==(e=g.items.indexOf(b))&&(d.remove(),d.hasClass("active")&&(f=g.$activeItems.indexOf(d[0]),g.$activeItems.splice(f,1)),g.items.splice(e,1),g.lastQuery=null,!g.settings.persist&&g.userOptions.hasOwnProperty(b)&&g.removeOption(b,c),e<g.caretPos&&g.setCaret(g.caretPos-1),g.refreshState(),g.updatePlaceholder(),g.updateOriginalInput({silent:c}),g.positionDropdown(),g.trigger("item_remove",b,d))},createItem:function(b,c){var d=this,e=d.caretPos;b=b||a.trim(d.$control_input.val()||"");var f=arguments[arguments.length-1];if("function"!=typeof f&&(f=function(){}),"boolean"!=typeof c&&(c=!0),!d.canCreate(b))return f(),!1;d.lock();var g="function"==typeof d.settings.create?this.settings.create:function(a){var b={};return b[d.settings.labelField]=a,b[d.settings.valueField]=a,b},h=n(function(a){if(d.unlock(),!a||"object"!=typeof a)return f();var b=k(a[d.settings.valueField]);if("string"!=typeof b)return f();d.setTextboxValue(""),d.addOption(a),d.setCaret(e),d.addItem(b),d.refreshOptions(c&&"single"!==d.settings.mode),f(a)}),i=g.apply(this,[b,h]);return void 0!==i&&h(i),!0},refreshItems:function(){this.lastQuery=null,this.isSetup&&this.addItem(this.items),this.refreshState(),this.updateOriginalInput()},refreshState:function(){this.refreshValidityState(),this.refreshClasses()},refreshValidityState:function(){if(!this.isRequired)return!1;var a=!this.items.length;this.isInvalid=a,this.$control_input.prop("required",a),this.$input.prop("required",!a)},refreshClasses:function(){var b=this,c=b.isFull(),d=b.isLocked;b.$wrapper.toggleClass("rtl",b.rtl),b.$control.toggleClass("focus",b.isFocused).toggleClass("disabled",b.isDisabled).toggleClass("required",b.isRequired).toggleClass("invalid",b.isInvalid).toggleClass("locked",d).toggleClass("full",c).toggleClass("not-full",!c).toggleClass("input-active",b.isFocused&&!b.isInputHidden).toggleClass("dropdown-active",b.isOpen).toggleClass("has-options",!a.isEmptyObject(b.options)).toggleClass("has-items",b.items.length>0),b.$control_input.data("grow",!c&&!d)},isFull:function(){return null!==this.settings.maxItems&&this.items.length>=this.settings.maxItems},updateOriginalInput:function(a){var b,c,d,e,f=this;if(a=a||{},1===f.tagType){for(d=[],b=0,c=f.items.length;b<c;b++)e=f.options[f.items[b]][f.settings.labelField]||"",d.push('<option value="'+l(f.items[b])+'" selected="selected">'+l(e)+"</option>");d.length||this.$input.attr("multiple")||d.push('<option value="" selected="selected"></option>'),f.$input.html(d.join(""))}else f.$input.val(f.getValue()),f.$input.attr("value",f.$input.val());f.isSetup&&(a.silent||f.trigger("change",f.$input.val()))},updatePlaceholder:function(){if(this.settings.placeholder){var a=this.$control_input;this.items.length?a.removeAttr("placeholder"):a.attr("placeholder",this.settings.placeholder),a.triggerHandler("update",{force:!0})}},open:function(){var a=this;a.isLocked||a.isOpen||"multi"===a.settings.mode&&a.isFull()||(a.focus(),a.isOpen=!0,a.refreshState(),a.$dropdown.css({visibility:"hidden",display:"block"}),a.positionDropdown(),a.$dropdown.css({visibility:"visible"}),a.trigger("dropdown_open",a.$dropdown))},close:function(){var a=this,b=a.isOpen;"single"===a.settings.mode&&a.items.length&&(a.hideInput(),a.isBlurring||a.$control_input.blur()),a.isOpen=!1,a.$dropdown.hide(),a.setActiveOption(null),a.refreshState(),b&&a.trigger("dropdown_close",a.$dropdown)},positionDropdown:function(){var a=this.$control,b="body"===this.settings.dropdownParent?a.offset():a.position();b.top+=a.outerHeight(!0),this.$dropdown.css({width:a[0].getBoundingClientRect().width,top:b.top,left:b.left})},clear:function(a){var b=this;b.items.length&&(b.$control.children(":not(input)").remove(),b.items=[],b.lastQuery=null,b.setCaret(0),b.setActiveItem(null),b.updatePlaceholder(),b.updateOriginalInput({silent:a}),b.refreshState(),b.showInput(),b.trigger("clear"))},insertAtCaret:function(a){var b=Math.min(this.caretPos,this.items.length),c=a[0],d=this.buffer||this.$control[0];0===b?d.insertBefore(c,d.firstChild):d.insertBefore(c,d.childNodes[b]),this.setCaret(b+1)},deleteSelection:function(b){var c,d,e,f,g,h,i,j,k,l=this;if(e=b&&8===b.keyCode?-1:1,f=r(l.$control_input[0]),l.$activeOption&&!l.settings.hideSelected&&(i=l.getAdjacentOption(l.$activeOption,-1).attr("data-value")),g=[],l.$activeItems.length){for(k=l.$control.children(".active:"+(e>0?"last":"first")),h=l.$control.children(":not(input)").index(k),e>0&&h++,c=0,d=l.$activeItems.length;c<d;c++)g.push(a(l.$activeItems[c]).attr("data-value"));b&&(b.preventDefault(),b.stopPropagation())}else(l.isFocused||"single"===l.settings.mode)&&l.items.length&&(e<0&&0===f.start&&0===f.length?g.push(l.items[l.caretPos-1]):e>0&&f.start===l.$control_input.val().length&&g.push(l.items[l.caretPos]));if(!g.length||"function"==typeof l.settings.onDelete&&!1===l.settings.onDelete.apply(l,[g]))return!1;for(void 0!==h&&l.setCaret(h);g.length;)l.removeItem(g.pop());return l.showInput(),l.positionDropdown(),l.refreshOptions(!0),i&&(j=l.getOption(i),j.length&&l.setActiveOption(j)),!0},advanceSelection:function(a,b){var c,d,e,f,g,h=this;0!==a&&(h.rtl&&(a*=-1),c=a>0?"last":"first",d=r(h.$control_input[0]),h.isFocused&&!h.isInputHidden?(f=h.$control_input.val().length,(a<0?0===d.start&&0===d.length:d.start===f)&&!f&&h.advanceCaret(a,b)):(g=h.$control.children(".active:"+c),g.length&&(e=h.$control.children(":not(input)").index(g),h.setActiveItem(null),h.setCaret(a>0?e+1:e))))},advanceCaret:function(a,b){var c,d,e=this;0!==a&&(c=a>0?"next":"prev",e.isShiftDown?(d=e.$control_input[c](),d.length&&(e.hideInput(),e.setActiveItem(d),b&&b.preventDefault())):e.setCaret(e.caretPos+a))},setCaret:function(b){var c=this;if(b="single"===c.settings.mode?c.items.length:Math.max(0,Math.min(c.items.length,b)),!c.isPending){var d,e,f,g;for(f=c.$control.children(":not(input)"),d=0,e=f.length;d<e;d++)g=a(f[d]).detach(),d<b?c.$control_input.before(g):c.$control.append(g)}c.caretPos=b},lock:function(){this.close(),this.isLocked=!0,this.refreshState()},unlock:function(){this.isLocked=!1,this.refreshState()},disable:function(){var a=this;a.$input.prop("disabled",!0),a.$control_input.prop("disabled",!0).prop("tabindex",-1),a.isDisabled=!0,a.lock()},enable:function(){var a=this;a.$input.prop("disabled",!1),a.$control_input.prop("disabled",!1).prop("tabindex",a.tabIndex),a.isDisabled=!1,a.unlock()},destroy:function(){var b=this,c=b.eventNS,d=b.revertSettings;b.trigger("destroy"),b.off(),b.$wrapper.remove(),b.$dropdown.remove(),b.$input.html("").append(d.$children).removeAttr("tabindex").removeClass("selectized").attr({tabindex:d.tabindex}).show(),b.$control_input.removeData("grow"),b.$input.removeData("selectize"),0==--w.count&&w.$testInput&&(w.$testInput.remove(),w.$testInput=void 0),a(window).off(c),a(document).off(c),a(document.body).off(c),delete b.$input[0].selectize},render:function(b,c){var d,e,f="",g=!1,h=this;return"option"!==b&&"item"!==b||(d=k(c[h.settings.valueField]),g=!!d),g&&(j(h.renderCache[b])||(h.renderCache[b]={}),h.renderCache[b].hasOwnProperty(d))?h.renderCache[b][d]:(f=a(h.settings.render[b].apply(this,[c,l])),"option"===b||"option_create"===b?c[h.settings.disabledField]||f.attr("data-selectable",""):"optgroup"===b&&(e=c[h.settings.optgroupValueField]||"",f.attr("data-group",e),c[h.settings.disabledField]&&f.attr("data-disabled","")),"option"!==b&&"item"!==b||f.attr("data-value",d||""),g&&(h.renderCache[b][d]=f[0]),f[0])},clearCache:function(a){var b=this;void 0===a?b.renderCache={}:delete b.renderCache[a]},canCreate:function(a){var b=this;if(!b.settings.create)return!1;var c=b.settings.createFilter;return a.length&&("function"!=typeof c||c.apply(b,[a]))&&("string"!=typeof c||new RegExp(c).test(a))&&(!(c instanceof RegExp)||c.test(a))}}),w.count=0,w.defaults={options:[],optgroups:[],plugins:[],delimiter:",",splitOn:null,persist:!0,diacritics:!0,create:!1,createOnBlur:!1,createFilter:null,highlight:!0,openOnFocus:!0,maxOptions:1e3,maxItems:null,hideSelected:null,addPrecedence:!1,selectOnTab:!1,preload:!1,allowEmptyOption:!1,closeAfterSelect:!1,scrollDuration:60,loadThrottle:300,loadingClass:"loading",dataAttr:"data-data",optgroupField:"optgroup",valueField:"value",labelField:"text",disabledField:"disabled",optgroupLabelField:"label",optgroupValueField:"value",lockOptgroupOrder:!1,sortField:"$order",searchField:["text"],searchConjunction:"and",mode:null,wrapperClass:"selectize-control",inputClass:"selectize-input",dropdownClass:"selectize-dropdown",dropdownContentClass:"selectize-dropdown-content",dropdownParent:null,copyClassesToDropdown:!0,render:{}},a.fn.selectize=function(b){var c=a.fn.selectize.defaults,d=a.extend({},c,b),e=d.dataAttr,f=d.labelField,g=d.valueField,h=d.disabledField,i=d.optgroupField,j=d.optgroupLabelField,l=d.optgroupValueField,m=function(b,c){var h,i,j,k,l=b.attr(e);if(l)for(c.options=JSON.parse(l),h=0,i=c.options.length;h<i;h++)c.items.push(c.options[h][g]);else{var m=a.trim(b.val()||"");if(!d.allowEmptyOption&&!m.length)return;for(j=m.split(d.delimiter),h=0,i=j.length;h<i;h++)k={},k[f]=j[h],k[g]=j[h],c.options.push(k);c.items=j}},n=function(b,c){var m,n,o,p,q=c.options,r={},s=function(a){var b=e&&a.attr(e);return"string"==typeof b&&b.length?JSON.parse(b):null},t=function(b,e){b=a(b);var j=k(b.val());if(j||d.allowEmptyOption)if(r.hasOwnProperty(j)){if(e){var l=r[j][i];l?a.isArray(l)?l.push(e):r[j][i]=[l,e]:r[j][i]=e}}else{var m=s(b)||{};m[f]=m[f]||b.text(),m[g]=m[g]||j,m[h]=m[h]||b.prop("disabled"),m[i]=m[i]||e,r[j]=m,q.push(m),b.is(":selected")&&c.items.push(j)}};for(c.maxItems=b.attr("multiple")?null:1,p=b.children(),m=0,n=p.length;m<n;m++)o=p[m].tagName.toLowerCase(),"optgroup"===o?function(b){var d,e,f,g,i;for(b=a(b),f=b.attr("label"),f&&(g=s(b)||{},g[j]=f,g[l]=f,g[h]=b.prop("disabled"),c.optgroups.push(g)),i=a("option",b),d=0,e=i.length;d<e;d++)t(i[d],f)}(p[m]):"option"===o&&t(p[m])};return this.each(function(){if(!this.selectize){var e=a(this),f=this.tagName.toLowerCase(),g=e.attr("placeholder")||e.attr("data-placeholder");g||d.allowEmptyOption||(g=e.children('option[value=""]').text());var h={placeholder:g,options:[],optgroups:[],items:[]};"select"===f?n(e,h):m(e,h),new w(e,a.extend(!0,{},c,h,b))}})},a.fn.selectize.defaults=w.defaults,a.fn.selectize.support={validity:i},w.define("drag_drop",function(b){if(!a.fn.sortable)throw new Error('The "drag_drop" plugin requires jQuery UI "sortable".');if("multi"===this.settings.mode){var c=this;c.lock=function(){var a=c.lock;return function(){var b=c.$control.data("sortable");return b&&b.disable(),a.apply(c,arguments)}}(),c.unlock=function(){var a=c.unlock;return function(){var b=c.$control.data("sortable");return b&&b.enable(),a.apply(c,arguments)}}(),c.setup=function(){var b=c.setup;return function(){b.apply(this,arguments);var d=c.$control.sortable({items:"[data-value]",forcePlaceholderSize:!0,disabled:c.isLocked,start:function(a,b){b.placeholder.css("width",b.helper.css("width")),d.css({overflow:"visible"})},stop:function(){d.css({overflow:"hidden"});var b=c.$activeItems?c.$activeItems.slice():null,e=[];d.children("[data-value]").each(function(){e.push(a(this).attr("data-value"))}),c.setValue(e),c.setActiveItem(b)}})}}()}}),w.define("dropdown_header",function(b){var c=this;b=a.extend({title:"Untitled",headerClass:"selectize-dropdown-header",titleRowClass:"selectize-dropdown-header-title",labelClass:"selectize-dropdown-header-label",closeClass:"selectize-dropdown-header-close",html:function(a){return'<div class="'+a.headerClass+'"><div class="'+a.titleRowClass+'"><span class="'+a.labelClass+'">'+a.title+'</span><a href="javascript:void(0)" class="'+a.closeClass+'">&times;</a></div></div>'}},b),c.setup=function(){var d=c.setup;return function(){d.apply(c,arguments),c.$dropdown_header=a(b.html(b)),c.$dropdown.prepend(c.$dropdown_header)}}()}),w.define("optgroup_columns",function(b){var c=this;b=a.extend({equalizeWidth:!0,equalizeHeight:!0},b),this.getAdjacentOption=function(b,c){var d=b.closest("[data-group]").find("[data-selectable]"),e=d.index(b)+c;return e>=0&&e<d.length?d.eq(e):a()},this.onKeyDown=function(){var a=c.onKeyDown;return function(b){var d,e,f,g;return!this.isOpen||37!==b.keyCode&&39!==b.keyCode?a.apply(this,arguments):(c.ignoreHover=!0,g=this.$activeOption.closest("[data-group]"),d=g.find("[data-selectable]").index(this.$activeOption),g=37===b.keyCode?g.prev("[data-group]"):g.next("[data-group]"),f=g.find("[data-selectable]"),e=f.eq(Math.min(f.length-1,d)),void(e.length&&this.setActiveOption(e)))}}();var d=function(){var a,b=d.width,c=document;return void 0===b&&(a=c.createElement("div"),a.innerHTML='<div style="width:50px;height:50px;position:absolute;left:-50px;top:-50px;overflow:auto;"><div style="width:1px;height:100px;"></div></div>',a=a.firstChild,c.body.appendChild(a),b=d.width=a.offsetWidth-a.clientWidth,c.body.removeChild(a)),b},e=function(){var e,f,g,h,i,j,k;if(k=a("[data-group]",c.$dropdown_content),(f=k.length)&&c.$dropdown_content.width()){if(b.equalizeHeight){for(g=0,e=0;e<f;e++)g=Math.max(g,k.eq(e).height());k.css({height:g})}b.equalizeWidth&&(j=c.$dropdown_content.innerWidth()-d(),h=Math.round(j/f),k.css({width:h}),f>1&&(i=j-h*(f-1),k.eq(f-1).css({width:i})))}};(b.equalizeHeight||b.equalizeWidth)&&(m.after(this,"positionDropdown",e),m.after(this,"refreshOptions",e))}),w.define("remove_button",function(b){b=a.extend({label:"&times;",title:"Remove",className:"remove",append:!0},b);if("single"===this.settings.mode)return void function(b,c){c.className="remove-single";var d=b,e='<a href="javascript:void(0)" class="'+c.className+'" tabindex="-1" title="'+l(c.title)+'">'+c.label+"</a>",f=function(b,c){return a("<span>").append(b).append(c)};b.setup=function(){var g=d.setup;return function(){if(c.append){var h=a(d.$input.context).attr("id"),i=(a("#"+h),d.settings.render.item);d.settings.render.item=function(a){return f(i.apply(b,arguments),e)}}g.apply(b,arguments),b.$control.on("click","."+c.className,function(a){a.preventDefault(),d.isLocked||d.clear()})}}()}(this,b);!function(b,c){var d=b,e='<a href="javascript:void(0)" class="'+c.className+'" tabindex="-1" title="'+l(c.title)+'">'+c.label+"</a>",f=function(a,b){var c=a.search(/(<\/[^>]+>\s*)$/);return a.substring(0,c)+b+a.substring(c)};b.setup=function(){var g=d.setup;return function(){if(c.append){var h=d.settings.render.item;d.settings.render.item=function(a){return f(h.apply(b,arguments),e)}}g.apply(b,arguments),b.$control.on("click","."+c.className,function(b){if(b.preventDefault(),!d.isLocked){var c=a(b.currentTarget).parent();d.setActiveItem(c),d.deleteSelection()&&d.setCaret(d.items.length)}})}}()}(this,b)}),w.define("restore_on_backspace",function(a){var b=this;a.text=a.text||function(a){return a[this.settings.labelField]},this.onKeyDown=function(){var c=b.onKeyDown;return function(b){var d,e;return 8===b.keyCode&&""===this.$control_input.val()&&!this.$activeItems.length&&(d=this.caretPos-1)>=0&&d<this.items.length?(e=this.options[this.items[d]],this.deleteSelection(b)&&(this.setTextboxValue(a.text.apply(this,[e])),this.refreshOptions(!0)),void b.preventDefault()):c.apply(this,arguments)}}()}),w});

/* == malihu jquery custom scrollbar plugin == Version: 3.1.5, License: MIT License (MIT) */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"undefined"!=typeof module&&module.exports?module.exports=e:e(jQuery,window,document)}(function(e){!function(t){var o="function"==typeof define&&define.amd,a="undefined"!=typeof module&&module.exports,n="https:"==document.location.protocol?"https:":"http:",i="cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js";o||(a?require("jquery-mousewheel")(e):e.event.special.mousewheel||e("head").append(decodeURI("%3Cscript src="+n+"//"+i+"%3E%3C/script%3E"))),t()}(function(){var t,o="mCustomScrollbar",a="mCS",n=".mCustomScrollbar",i={setTop:0,setLeft:0,axis:"y",scrollbarPosition:"inside",scrollInertia:950,autoDraggerLength:!0,alwaysShowScrollbar:0,snapOffset:0,mouseWheel:{enable:!0,scrollAmount:"auto",axis:"y",deltaFactor:"auto",disableOver:["select","option","keygen","datalist","textarea"]},scrollButtons:{scrollType:"stepless",scrollAmount:"auto"},keyboard:{enable:!0,scrollType:"stepless",scrollAmount:"auto"},contentTouchScroll:25,documentTouchScroll:!0,advanced:{autoScrollOnFocus:"input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",updateOnContentResize:!0,updateOnImageLoad:"auto",autoUpdateTimeout:60},theme:"light",callbacks:{onTotalScrollOffset:0,onTotalScrollBackOffset:0,alwaysTriggerOffsets:!0}},r=0,l={},s=window.attachEvent&&!window.addEventListener?1:0,c=!1,d=["mCSB_dragger_onDrag","mCSB_scrollTools_onDrag","mCS_img_loaded","mCS_disabled","mCS_destroyed","mCS_no_scrollbar","mCS-autoHide","mCS-dir-rtl","mCS_no_scrollbar_y","mCS_no_scrollbar_x","mCS_y_hidden","mCS_x_hidden","mCSB_draggerContainer","mCSB_buttonUp","mCSB_buttonDown","mCSB_buttonLeft","mCSB_buttonRight"],u={init:function(t){var t=e.extend(!0,{},i,t),o=f.call(this);if(t.live){var s=t.liveSelector||this.selector||n,c=e(s);if("off"===t.live)return void m(s);l[s]=setTimeout(function(){c.mCustomScrollbar(t),"once"===t.live&&c.length&&m(s)},500)}else m(s);return t.setWidth=t.set_width?t.set_width:t.setWidth,t.setHeight=t.set_height?t.set_height:t.setHeight,t.axis=t.horizontalScroll?"x":p(t.axis),t.scrollInertia=t.scrollInertia>0&&t.scrollInertia<17?17:t.scrollInertia,"object"!=typeof t.mouseWheel&&1==t.mouseWheel&&(t.mouseWheel={enable:!0,scrollAmount:"auto",axis:"y",preventDefault:!1,deltaFactor:"auto",normalizeDelta:!1,invert:!1}),t.mouseWheel.scrollAmount=t.mouseWheelPixels?t.mouseWheelPixels:t.mouseWheel.scrollAmount,t.mouseWheel.normalizeDelta=t.advanced.normalizeMouseWheelDelta?t.advanced.normalizeMouseWheelDelta:t.mouseWheel.normalizeDelta,t.scrollButtons.scrollType=g(t.scrollButtons.scrollType),h(t),e(o).each(function(){var o=e(this);if(!o.data(a)){o.data(a,{idx:++r,opt:t,scrollRatio:{y:null,x:null},overflowed:null,contentReset:{y:null,x:null},bindEvents:!1,tweenRunning:!1,sequential:{},langDir:o.css("direction"),cbOffsets:null,trigger:null,poll:{size:{o:0,n:0},img:{o:0,n:0},change:{o:0,n:0}}});var n=o.data(a),i=n.opt,l=o.data("mcs-axis"),s=o.data("mcs-scrollbar-position"),c=o.data("mcs-theme");l&&(i.axis=l),s&&(i.scrollbarPosition=s),c&&(i.theme=c,h(i)),v.call(this),n&&i.callbacks.onCreate&&"function"==typeof i.callbacks.onCreate&&i.callbacks.onCreate.call(this),e("#mCSB_"+n.idx+"_container img:not(."+d[2]+")").addClass(d[2]),u.update.call(null,o)}})},update:function(t,o){var n=t||f.call(this);return e(n).each(function(){var t=e(this);if(t.data(a)){var n=t.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container"),l=e("#mCSB_"+n.idx),s=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];if(!r.length)return;n.tweenRunning&&Q(t),o&&n&&i.callbacks.onBeforeUpdate&&"function"==typeof i.callbacks.onBeforeUpdate&&i.callbacks.onBeforeUpdate.call(this),t.hasClass(d[3])&&t.removeClass(d[3]),t.hasClass(d[4])&&t.removeClass(d[4]),l.css("max-height","none"),l.height()!==t.height()&&l.css("max-height",t.height()),_.call(this),"y"===i.axis||i.advanced.autoExpandHorizontalScroll||r.css("width",x(r)),n.overflowed=y.call(this),M.call(this),i.autoDraggerLength&&S.call(this),b.call(this),T.call(this);var c=[Math.abs(r[0].offsetTop),Math.abs(r[0].offsetLeft)];"x"!==i.axis&&(n.overflowed[0]?s[0].height()>s[0].parent().height()?B.call(this):(G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}),n.contentReset.y=null):(B.call(this),"y"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[1]&&G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}))),"y"!==i.axis&&(n.overflowed[1]?s[1].width()>s[1].parent().width()?B.call(this):(G(t,c[1].toString(),{dir:"x",dur:0,overwrite:"none"}),n.contentReset.x=null):(B.call(this),"x"===i.axis?k.call(this):"yx"===i.axis&&n.overflowed[0]&&G(t,c[0].toString(),{dir:"y",dur:0,overwrite:"none"}))),o&&n&&(2===o&&i.callbacks.onImageLoad&&"function"==typeof i.callbacks.onImageLoad?i.callbacks.onImageLoad.call(this):3===o&&i.callbacks.onSelectorChange&&"function"==typeof i.callbacks.onSelectorChange?i.callbacks.onSelectorChange.call(this):i.callbacks.onUpdate&&"function"==typeof i.callbacks.onUpdate&&i.callbacks.onUpdate.call(this)),N.call(this)}})},scrollTo:function(t,o){if("undefined"!=typeof t&&null!=t){var n=f.call(this);return e(n).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l={trigger:"external",scrollInertia:r.scrollInertia,scrollEasing:"mcsEaseInOut",moveDragger:!1,timeout:60,callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},s=e.extend(!0,{},l,o),c=Y.call(this,t),d=s.scrollInertia>0&&s.scrollInertia<17?17:s.scrollInertia;c[0]=X.call(this,c[0],"y"),c[1]=X.call(this,c[1],"x"),s.moveDragger&&(c[0]*=i.scrollRatio.y,c[1]*=i.scrollRatio.x),s.dur=ne()?0:d,setTimeout(function(){null!==c[0]&&"undefined"!=typeof c[0]&&"x"!==r.axis&&i.overflowed[0]&&(s.dir="y",s.overwrite="all",G(n,c[0].toString(),s)),null!==c[1]&&"undefined"!=typeof c[1]&&"y"!==r.axis&&i.overflowed[1]&&(s.dir="x",s.overwrite="none",G(n,c[1].toString(),s))},s.timeout)}})}},stop:function(){var t=f.call(this);return e(t).each(function(){var t=e(this);t.data(a)&&Q(t)})},disable:function(t){var o=f.call(this);return e(o).each(function(){var o=e(this);if(o.data(a)){o.data(a);N.call(this,"remove"),k.call(this),t&&B.call(this),M.call(this,!0),o.addClass(d[3])}})},destroy:function(){var t=f.call(this);return e(t).each(function(){var n=e(this);if(n.data(a)){var i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx),s=e("#mCSB_"+i.idx+"_container"),c=e(".mCSB_"+i.idx+"_scrollbar");r.live&&m(r.liveSelector||e(t).selector),N.call(this,"remove"),k.call(this),B.call(this),n.removeData(a),$(this,"mcs"),c.remove(),s.find("img."+d[2]).removeClass(d[2]),l.replaceWith(s.contents()),n.removeClass(o+" _"+a+"_"+i.idx+" "+d[6]+" "+d[7]+" "+d[5]+" "+d[3]).addClass(d[4])}})}},f=function(){return"object"!=typeof e(this)||e(this).length<1?n:this},h=function(t){var o=["rounded","rounded-dark","rounded-dots","rounded-dots-dark"],a=["rounded-dots","rounded-dots-dark","3d","3d-dark","3d-thick","3d-thick-dark","inset","inset-dark","inset-2","inset-2-dark","inset-3","inset-3-dark"],n=["minimal","minimal-dark"],i=["minimal","minimal-dark"],r=["minimal","minimal-dark"];t.autoDraggerLength=e.inArray(t.theme,o)>-1?!1:t.autoDraggerLength,t.autoExpandScrollbar=e.inArray(t.theme,a)>-1?!1:t.autoExpandScrollbar,t.scrollButtons.enable=e.inArray(t.theme,n)>-1?!1:t.scrollButtons.enable,t.autoHideScrollbar=e.inArray(t.theme,i)>-1?!0:t.autoHideScrollbar,t.scrollbarPosition=e.inArray(t.theme,r)>-1?"outside":t.scrollbarPosition},m=function(e){l[e]&&(clearTimeout(l[e]),$(l,e))},p=function(e){return"yx"===e||"xy"===e||"auto"===e?"yx":"x"===e||"horizontal"===e?"x":"y"},g=function(e){return"stepped"===e||"pixels"===e||"step"===e||"click"===e?"stepped":"stepless"},v=function(){var t=e(this),n=t.data(a),i=n.opt,r=i.autoExpandScrollbar?" "+d[1]+"_expand":"",l=["<div id='mCSB_"+n.idx+"_scrollbar_vertical' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_vertical"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_vertical' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>","<div id='mCSB_"+n.idx+"_scrollbar_horizontal' class='mCSB_scrollTools mCSB_"+n.idx+"_scrollbar mCS-"+i.theme+" mCSB_scrollTools_horizontal"+r+"'><div class='"+d[12]+"'><div id='mCSB_"+n.idx+"_dragger_horizontal' class='mCSB_dragger' style='position:absolute;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>"],s="yx"===i.axis?"mCSB_vertical_horizontal":"x"===i.axis?"mCSB_horizontal":"mCSB_vertical",c="yx"===i.axis?l[0]+l[1]:"x"===i.axis?l[1]:l[0],u="yx"===i.axis?"<div id='mCSB_"+n.idx+"_container_wrapper' class='mCSB_container_wrapper' />":"",f=i.autoHideScrollbar?" "+d[6]:"",h="x"!==i.axis&&"rtl"===n.langDir?" "+d[7]:"";i.setWidth&&t.css("width",i.setWidth),i.setHeight&&t.css("height",i.setHeight),i.setLeft="y"!==i.axis&&"rtl"===n.langDir?"989999px":i.setLeft,t.addClass(o+" _"+a+"_"+n.idx+f+h).wrapInner("<div id='mCSB_"+n.idx+"' class='mCustomScrollBox mCS-"+i.theme+" "+s+"'><div id='mCSB_"+n.idx+"_container' class='mCSB_container' style='position:relative; top:"+i.setTop+"; left:"+i.setLeft+";' dir='"+n.langDir+"' /></div>");var m=e("#mCSB_"+n.idx),p=e("#mCSB_"+n.idx+"_container");"y"===i.axis||i.advanced.autoExpandHorizontalScroll||p.css("width",x(p)),"outside"===i.scrollbarPosition?("static"===t.css("position")&&t.css("position","relative"),t.css("overflow","visible"),m.addClass("mCSB_outside").after(c)):(m.addClass("mCSB_inside").append(c),p.wrap(u)),w.call(this);var g=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")];g[0].css("min-height",g[0].height()),g[1].css("min-width",g[1].width())},x=function(t){var o=[t[0].scrollWidth,Math.max.apply(Math,t.children().map(function(){return e(this).outerWidth(!0)}).get())],a=t.parent().width();return o[0]>a?o[0]:o[1]>a?o[1]:"100%"},_=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx+"_container");if(n.advanced.autoExpandHorizontalScroll&&"y"!==n.axis){i.css({width:"auto","min-width":0,"overflow-x":"scroll"});var r=Math.ceil(i[0].scrollWidth);3===n.advanced.autoExpandHorizontalScroll||2!==n.advanced.autoExpandHorizontalScroll&&r>i.parent().width()?i.css({width:r,"min-width":"100%","overflow-x":"inherit"}):i.css({"overflow-x":"inherit",position:"absolute"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width:Math.ceil(i[0].getBoundingClientRect().right+.4)-Math.floor(i[0].getBoundingClientRect().left),"min-width":"100%",position:"relative"}).unwrap()}},w=function(){var t=e(this),o=t.data(a),n=o.opt,i=e(".mCSB_"+o.idx+"_scrollbar:first"),r=oe(n.scrollButtons.tabindex)?"tabindex='"+n.scrollButtons.tabindex+"'":"",l=["<a href='#' class='"+d[13]+"' "+r+" />","<a href='#' class='"+d[14]+"' "+r+" />","<a href='#' class='"+d[15]+"' "+r+" />","<a href='#' class='"+d[16]+"' "+r+" />"],s=["x"===n.axis?l[2]:l[0],"x"===n.axis?l[3]:l[1],l[2],l[3]];n.scrollButtons.enable&&i.prepend(s[0]).append(s[1]).next(".mCSB_scrollTools").prepend(s[2]).append(s[3])},S=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[n.height()/i.outerHeight(!1),n.width()/i.outerWidth(!1)],c=[parseInt(r[0].css("min-height")),Math.round(l[0]*r[0].parent().height()),parseInt(r[1].css("min-width")),Math.round(l[1]*r[1].parent().width())],d=s&&c[1]<c[0]?c[0]:c[1],u=s&&c[3]<c[2]?c[2]:c[3];r[0].css({height:d,"max-height":r[0].parent().height()-10}).find(".mCSB_dragger_bar").css({"line-height":c[0]+"px"}),r[1].css({width:u,"max-width":r[1].parent().width()-10})},b=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")],l=[i.outerHeight(!1)-n.height(),i.outerWidth(!1)-n.width()],s=[l[0]/(r[0].parent().height()-r[0].height()),l[1]/(r[1].parent().width()-r[1].width())];o.scrollRatio={y:s[0],x:s[1]}},C=function(e,t,o){var a=o?d[0]+"_expanded":"",n=e.closest(".mCSB_scrollTools");"active"===t?(e.toggleClass(d[0]+" "+a),n.toggleClass(d[1]),e[0]._draggable=e[0]._draggable?0:1):e[0]._draggable||("hide"===t?(e.removeClass(d[0]),n.removeClass(d[1])):(e.addClass(d[0]),n.addClass(d[1])))},y=function(){var t=e(this),o=t.data(a),n=e("#mCSB_"+o.idx),i=e("#mCSB_"+o.idx+"_container"),r=null==o.overflowed?i.height():i.outerHeight(!1),l=null==o.overflowed?i.width():i.outerWidth(!1),s=i[0].scrollHeight,c=i[0].scrollWidth;return s>r&&(r=s),c>l&&(l=c),[r>n.height(),l>n.width()]},B=function(){var t=e(this),o=t.data(a),n=o.opt,i=e("#mCSB_"+o.idx),r=e("#mCSB_"+o.idx+"_container"),l=[e("#mCSB_"+o.idx+"_dragger_vertical"),e("#mCSB_"+o.idx+"_dragger_horizontal")];if(Q(t),("x"!==n.axis&&!o.overflowed[0]||"y"===n.axis&&o.overflowed[0])&&(l[0].add(r).css("top",0),G(t,"_resetY")),"y"!==n.axis&&!o.overflowed[1]||"x"===n.axis&&o.overflowed[1]){var s=dx=0;"rtl"===o.langDir&&(s=i.width()-r.outerWidth(!1),dx=Math.abs(s/o.scrollRatio.x)),r.css("left",s),l[1].css("left",dx),G(t,"_resetX")}},T=function(){function t(){r=setTimeout(function(){e.event.special.mousewheel?(clearTimeout(r),W.call(o[0])):t()},100)}var o=e(this),n=o.data(a),i=n.opt;if(!n.bindEvents){if(I.call(this),i.contentTouchScroll&&D.call(this),E.call(this),i.mouseWheel.enable){var r;t()}P.call(this),U.call(this),i.advanced.autoScrollOnFocus&&H.call(this),i.scrollButtons.enable&&F.call(this),i.keyboard.enable&&q.call(this),n.bindEvents=!0}},k=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=".mCSB_"+o.idx+"_scrollbar",l=e("#mCSB_"+o.idx+",#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,"+r+" ."+d[12]+",#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal,"+r+">a"),s=e("#mCSB_"+o.idx+"_container");n.advanced.releaseDraggableSelectors&&l.add(e(n.advanced.releaseDraggableSelectors)),n.advanced.extraDraggableSelectors&&l.add(e(n.advanced.extraDraggableSelectors)),o.bindEvents&&(e(document).add(e(!A()||top.document)).unbind("."+i),l.each(function(){e(this).unbind("."+i)}),clearTimeout(t[0]._focusTimeout),$(t[0],"_focusTimeout"),clearTimeout(o.sequential.step),$(o.sequential,"step"),clearTimeout(s[0].onCompleteTimeout),$(s[0],"onCompleteTimeout"),o.bindEvents=!1)},M=function(t){var o=e(this),n=o.data(a),i=n.opt,r=e("#mCSB_"+n.idx+"_container_wrapper"),l=r.length?r:e("#mCSB_"+n.idx+"_container"),s=[e("#mCSB_"+n.idx+"_scrollbar_vertical"),e("#mCSB_"+n.idx+"_scrollbar_horizontal")],c=[s[0].find(".mCSB_dragger"),s[1].find(".mCSB_dragger")];"x"!==i.axis&&(n.overflowed[0]&&!t?(s[0].add(c[0]).add(s[0].children("a")).css("display","block"),l.removeClass(d[8]+" "+d[10])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[0].css("display","none"),l.removeClass(d[10])):(s[0].css("display","none"),l.addClass(d[10])),l.addClass(d[8]))),"y"!==i.axis&&(n.overflowed[1]&&!t?(s[1].add(c[1]).add(s[1].children("a")).css("display","block"),l.removeClass(d[9]+" "+d[11])):(i.alwaysShowScrollbar?(2!==i.alwaysShowScrollbar&&c[1].css("display","none"),l.removeClass(d[11])):(s[1].css("display","none"),l.addClass(d[11])),l.addClass(d[9]))),n.overflowed[0]||n.overflowed[1]?o.removeClass(d[5]):o.addClass(d[5])},O=function(t){var o=t.type,a=t.target.ownerDocument!==document&&null!==frameElement?[e(frameElement).offset().top,e(frameElement).offset().left]:null,n=A()&&t.target.ownerDocument!==top.document&&null!==frameElement?[e(t.view.frameElement).offset().top,e(t.view.frameElement).offset().left]:[0,0];switch(o){case"pointerdown":case"MSPointerDown":case"pointermove":case"MSPointerMove":case"pointerup":case"MSPointerUp":return a?[t.originalEvent.pageY-a[0]+n[0],t.originalEvent.pageX-a[1]+n[1],!1]:[t.originalEvent.pageY,t.originalEvent.pageX,!1];case"touchstart":case"touchmove":case"touchend":var i=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],r=t.originalEvent.touches.length||t.originalEvent.changedTouches.length;return t.target.ownerDocument!==document?[i.screenY,i.screenX,r>1]:[i.pageY,i.pageX,r>1];default:return a?[t.pageY-a[0]+n[0],t.pageX-a[1]+n[1],!1]:[t.pageY,t.pageX,!1]}},I=function(){function t(e,t,a,n){if(h[0].idleTimer=d.scrollInertia<233?250:0,o.attr("id")===f[1])var i="x",s=(o[0].offsetLeft-t+n)*l.scrollRatio.x;else var i="y",s=(o[0].offsetTop-e+a)*l.scrollRatio.y;G(r,s.toString(),{dir:i,drag:!0})}var o,n,i,r=e(this),l=r.data(a),d=l.opt,u=a+"_"+l.idx,f=["mCSB_"+l.idx+"_dragger_vertical","mCSB_"+l.idx+"_dragger_horizontal"],h=e("#mCSB_"+l.idx+"_container"),m=e("#"+f[0]+",#"+f[1]),p=d.advanced.releaseDraggableSelectors?m.add(e(d.advanced.releaseDraggableSelectors)):m,g=d.advanced.extraDraggableSelectors?e(!A()||top.document).add(e(d.advanced.extraDraggableSelectors)):e(!A()||top.document);m.bind("contextmenu."+u,function(e){e.preventDefault()}).bind("mousedown."+u+" touchstart."+u+" pointerdown."+u+" MSPointerDown."+u,function(t){if(t.stopImmediatePropagation(),t.preventDefault(),ee(t)){c=!0,s&&(document.onselectstart=function(){return!1}),L.call(h,!1),Q(r),o=e(this);var a=o.offset(),l=O(t)[0]-a.top,u=O(t)[1]-a.left,f=o.height()+a.top,m=o.width()+a.left;f>l&&l>0&&m>u&&u>0&&(n=l,i=u),C(o,"active",d.autoExpandScrollbar)}}).bind("touchmove."+u,function(e){e.stopImmediatePropagation(),e.preventDefault();var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;t(n,i,r,l)}),e(document).add(g).bind("mousemove."+u+" pointermove."+u+" MSPointerMove."+u,function(e){if(o){var a=o.offset(),r=O(e)[0]-a.top,l=O(e)[1]-a.left;if(n===r&&i===l)return;t(n,i,r,l)}}).add(p).bind("mouseup."+u+" touchend."+u+" pointerup."+u+" MSPointerUp."+u,function(){o&&(C(o,"active",d.autoExpandScrollbar),o=null),c=!1,s&&(document.onselectstart=null),L.call(h,!0)})},D=function(){function o(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,b=0,C=0,d=1,y.removeClass("mCS_touch_action");var o=I.offset();u=O(e)[0]-o.top,f=O(e)[1]-o.left,z=[O(e)[0],O(e)[1]]}function n(e){if(te(e)&&!c&&!O(e)[2]&&(T.documentTouchScroll||e.preventDefault(),e.stopImmediatePropagation(),(!C||b)&&d)){g=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left,n="mcsLinearOut";if(E.push(o),W.push(a),z[2]=Math.abs(O(e)[0]-z[0]),z[3]=Math.abs(O(e)[1]-z[1]),B.overflowed[0])var i=D[0].parent().height()-D[0].height(),r=u-o>0&&o-u>-(i*B.scrollRatio.y)&&(2*z[3]<z[2]||"yx"===T.axis);if(B.overflowed[1])var l=D[1].parent().width()-D[1].width(),h=f-a>0&&a-f>-(l*B.scrollRatio.x)&&(2*z[2]<z[3]||"yx"===T.axis);r||h?(U||e.preventDefault(),b=1):(C=1,y.addClass("mCS_touch_action")),U&&e.preventDefault(),w="yx"===T.axis?[u-o,f-a]:"x"===T.axis?[null,f-a]:[u-o,null],I[0].idleTimer=250,B.overflowed[0]&&s(w[0],R,n,"y","all",!0),B.overflowed[1]&&s(w[1],R,n,"x",L,!0)}}function i(e){if(!te(e)||c||O(e)[2])return void(t=0);t=1,e.stopImmediatePropagation(),Q(y),p=K();var o=M.offset();h=O(e)[0]-o.top,m=O(e)[1]-o.left,E=[],W=[]}function r(e){if(te(e)&&!c&&!O(e)[2]){d=0,e.stopImmediatePropagation(),b=0,C=0,v=K();var t=M.offset(),o=O(e)[0]-t.top,a=O(e)[1]-t.left;if(!(v-g>30)){_=1e3/(v-p);var n="mcsEaseOut",i=2.5>_,r=i?[E[E.length-2],W[W.length-2]]:[0,0];x=i?[o-r[0],a-r[1]]:[o-h,a-m];var u=[Math.abs(x[0]),Math.abs(x[1])];_=i?[Math.abs(x[0]/4),Math.abs(x[1]/4)]:[_,_];var f=[Math.abs(I[0].offsetTop)-x[0]*l(u[0]/_[0],_[0]),Math.abs(I[0].offsetLeft)-x[1]*l(u[1]/_[1],_[1])];w="yx"===T.axis?[f[0],f[1]]:"x"===T.axis?[null,f[1]]:[f[0],null],S=[4*u[0]+T.scrollInertia,4*u[1]+T.scrollInertia];var y=parseInt(T.contentTouchScroll)||0;w[0]=u[0]>y?w[0]:0,w[1]=u[1]>y?w[1]:0,B.overflowed[0]&&s(w[0],S[0],n,"y",L,!1),B.overflowed[1]&&s(w[1],S[1],n,"x",L,!1)}}}function l(e,t){var o=[1.5*t,2*t,t/1.5,t/2];return e>90?t>4?o[0]:o[3]:e>60?t>3?o[3]:o[2]:e>30?t>8?o[1]:t>6?o[0]:t>4?t:o[2]:t>8?t:o[3]}function s(e,t,o,a,n,i){e&&G(y,e.toString(),{dur:t,scrollEasing:o,dir:a,overwrite:n,drag:i})}var d,u,f,h,m,p,g,v,x,_,w,S,b,C,y=e(this),B=y.data(a),T=B.opt,k=a+"_"+B.idx,M=e("#mCSB_"+B.idx),I=e("#mCSB_"+B.idx+"_container"),D=[e("#mCSB_"+B.idx+"_dragger_vertical"),e("#mCSB_"+B.idx+"_dragger_horizontal")],E=[],W=[],R=0,L="yx"===T.axis?"none":"all",z=[],P=I.find("iframe"),H=["touchstart."+k+" pointerdown."+k+" MSPointerDown."+k,"touchmove."+k+" pointermove."+k+" MSPointerMove."+k,"touchend."+k+" pointerup."+k+" MSPointerUp."+k],U=void 0!==document.body.style.touchAction&&""!==document.body.style.touchAction;I.bind(H[0],function(e){o(e)}).bind(H[1],function(e){n(e)}),M.bind(H[0],function(e){i(e)}).bind(H[2],function(e){r(e)}),P.length&&P.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(H[0],function(e){o(e),i(e)}).bind(H[1],function(e){n(e)}).bind(H[2],function(e){r(e)})})})},E=function(){function o(){return window.getSelection?window.getSelection().toString():document.selection&&"Control"!=document.selection.type?document.selection.createRange().text:0}function n(e,t,o){d.type=o&&i?"stepped":"stepless",d.scrollAmount=10,j(r,e,t,"mcsLinearOut",o?60:null)}var i,r=e(this),l=r.data(a),s=l.opt,d=l.sequential,u=a+"_"+l.idx,f=e("#mCSB_"+l.idx+"_container"),h=f.parent();f.bind("mousedown."+u,function(){t||i||(i=1,c=!0)}).add(document).bind("mousemove."+u,function(e){if(!t&&i&&o()){var a=f.offset(),r=O(e)[0]-a.top+f[0].offsetTop,c=O(e)[1]-a.left+f[0].offsetLeft;r>0&&r<h.height()&&c>0&&c<h.width()?d.step&&n("off",null,"stepped"):("x"!==s.axis&&l.overflowed[0]&&(0>r?n("on",38):r>h.height()&&n("on",40)),"y"!==s.axis&&l.overflowed[1]&&(0>c?n("on",37):c>h.width()&&n("on",39)))}}).bind("mouseup."+u+" dragend."+u,function(){t||(i&&(i=0,n("off",null)),c=!1)})},W=function(){function t(t,a){if(Q(o),!z(o,t.target)){var r="auto"!==i.mouseWheel.deltaFactor?parseInt(i.mouseWheel.deltaFactor):s&&t.deltaFactor<100?100:t.deltaFactor||100,d=i.scrollInertia;if("x"===i.axis||"x"===i.mouseWheel.axis)var u="x",f=[Math.round(r*n.scrollRatio.x),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.width()?.9*l.width():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetLeft),p=c[1][0].offsetLeft,g=c[1].parent().width()-c[1].width(),v="y"===i.mouseWheel.axis?t.deltaY||a:t.deltaX;else var u="y",f=[Math.round(r*n.scrollRatio.y),parseInt(i.mouseWheel.scrollAmount)],h="auto"!==i.mouseWheel.scrollAmount?f[1]:f[0]>=l.height()?.9*l.height():f[0],m=Math.abs(e("#mCSB_"+n.idx+"_container")[0].offsetTop),p=c[0][0].offsetTop,g=c[0].parent().height()-c[0].height(),v=t.deltaY||a;"y"===u&&!n.overflowed[0]||"x"===u&&!n.overflowed[1]||((i.mouseWheel.invert||t.webkitDirectionInvertedFromDevice)&&(v=-v),i.mouseWheel.normalizeDelta&&(v=0>v?-1:1),(v>0&&0!==p||0>v&&p!==g||i.mouseWheel.preventDefault)&&(t.stopImmediatePropagation(),t.preventDefault()),t.deltaFactor<5&&!i.mouseWheel.normalizeDelta&&(h=t.deltaFactor,d=17),G(o,(m-v*h).toString(),{dir:u,dur:d}))}}if(e(this).data(a)){var o=e(this),n=o.data(a),i=n.opt,r=a+"_"+n.idx,l=e("#mCSB_"+n.idx),c=[e("#mCSB_"+n.idx+"_dragger_vertical"),e("#mCSB_"+n.idx+"_dragger_horizontal")],d=e("#mCSB_"+n.idx+"_container").find("iframe");d.length&&d.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind("mousewheel."+r,function(e,o){t(e,o)})})}),l.bind("mousewheel."+r,function(e,o){t(e,o)})}},R=new Object,A=function(t){var o=!1,a=!1,n=null;if(void 0===t?a="#empty":void 0!==e(t).attr("id")&&(a=e(t).attr("id")),a!==!1&&void 0!==R[a])return R[a];if(t){try{var i=t.contentDocument||t.contentWindow.document;n=i.body.innerHTML}catch(r){}o=null!==n}else{try{var i=top.document;n=i.body.innerHTML}catch(r){}o=null!==n}return a!==!1&&(R[a]=o),o},L=function(e){var t=this.find("iframe");if(t.length){var o=e?"auto":"none";t.css("pointer-events",o)}},z=function(t,o){var n=o.nodeName.toLowerCase(),i=t.data(a).opt.mouseWheel.disableOver,r=["select","textarea"];return e.inArray(n,i)>-1&&!(e.inArray(n,r)>-1&&!e(o).is(":focus"))},P=function(){var t,o=e(this),n=o.data(a),i=a+"_"+n.idx,r=e("#mCSB_"+n.idx+"_container"),l=r.parent(),s=e(".mCSB_"+n.idx+"_scrollbar ."+d[12]);s.bind("mousedown."+i+" touchstart."+i+" pointerdown."+i+" MSPointerDown."+i,function(o){c=!0,e(o.target).hasClass("mCSB_dragger")||(t=1)}).bind("touchend."+i+" pointerup."+i+" MSPointerUp."+i,function(){c=!1}).bind("click."+i,function(a){if(t&&(t=0,e(a.target).hasClass(d[12])||e(a.target).hasClass("mCSB_draggerRail"))){Q(o);var i=e(this),s=i.find(".mCSB_dragger");if(i.parent(".mCSB_scrollTools_horizontal").length>0){if(!n.overflowed[1])return;var c="x",u=a.pageX>s.offset().left?-1:1,f=Math.abs(r[0].offsetLeft)-u*(.9*l.width())}else{if(!n.overflowed[0])return;var c="y",u=a.pageY>s.offset().top?-1:1,f=Math.abs(r[0].offsetTop)-u*(.9*l.height())}G(o,f.toString(),{dir:c,scrollEasing:"mcsEaseInOut"})}})},H=function(){var t=e(this),o=t.data(a),n=o.opt,i=a+"_"+o.idx,r=e("#mCSB_"+o.idx+"_container"),l=r.parent();r.bind("focusin."+i,function(){var o=e(document.activeElement),a=r.find(".mCustomScrollBox").length,i=0;o.is(n.advanced.autoScrollOnFocus)&&(Q(t),clearTimeout(t[0]._focusTimeout),t[0]._focusTimer=a?(i+17)*a:0,t[0]._focusTimeout=setTimeout(function(){var e=[ae(o)[0],ae(o)[1]],a=[r[0].offsetTop,r[0].offsetLeft],s=[a[0]+e[0]>=0&&a[0]+e[0]<l.height()-o.outerHeight(!1),a[1]+e[1]>=0&&a[0]+e[1]<l.width()-o.outerWidth(!1)],c="yx"!==n.axis||s[0]||s[1]?"all":"none";"x"===n.axis||s[0]||G(t,e[0].toString(),{dir:"y",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i}),"y"===n.axis||s[1]||G(t,e[1].toString(),{dir:"x",scrollEasing:"mcsEaseInOut",overwrite:c,dur:i})},t[0]._focusTimer))})},U=function(){var t=e(this),o=t.data(a),n=a+"_"+o.idx,i=e("#mCSB_"+o.idx+"_container").parent();i.bind("scroll."+n,function(){0===i.scrollTop()&&0===i.scrollLeft()||e(".mCSB_"+o.idx+"_scrollbar").css("visibility","hidden")})},F=function(){var t=e(this),o=t.data(a),n=o.opt,i=o.sequential,r=a+"_"+o.idx,l=".mCSB_"+o.idx+"_scrollbar",s=e(l+">a");s.bind("contextmenu."+r,function(e){e.preventDefault()}).bind("mousedown."+r+" touchstart."+r+" pointerdown."+r+" MSPointerDown."+r+" mouseup."+r+" touchend."+r+" pointerup."+r+" MSPointerUp."+r+" mouseout."+r+" pointerout."+r+" MSPointerOut."+r+" click."+r,function(a){function r(e,o){i.scrollAmount=n.scrollButtons.scrollAmount,j(t,e,o)}if(a.preventDefault(),ee(a)){var l=e(this).attr("class");switch(i.type=n.scrollButtons.scrollType,a.type){case"mousedown":case"touchstart":case"pointerdown":case"MSPointerDown":if("stepped"===i.type)return;c=!0,o.tweenRunning=!1,r("on",l);break;case"mouseup":case"touchend":case"pointerup":case"MSPointerUp":case"mouseout":case"pointerout":case"MSPointerOut":if("stepped"===i.type)return;c=!1,i.dir&&r("off",l);break;case"click":if("stepped"!==i.type||o.tweenRunning)return;r("on",l)}}})},q=function(){function t(t){function a(e,t){r.type=i.keyboard.scrollType,r.scrollAmount=i.keyboard.scrollAmount,"stepped"===r.type&&n.tweenRunning||j(o,e,t)}switch(t.type){case"blur":n.tweenRunning&&r.dir&&a("off",null);break;case"keydown":case"keyup":var l=t.keyCode?t.keyCode:t.which,s="on";if("x"!==i.axis&&(38===l||40===l)||"y"!==i.axis&&(37===l||39===l)){if((38===l||40===l)&&!n.overflowed[0]||(37===l||39===l)&&!n.overflowed[1])return;"keyup"===t.type&&(s="off"),e(document.activeElement).is(u)||(t.preventDefault(),t.stopImmediatePropagation(),a(s,l))}else if(33===l||34===l){if((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type){Q(o);var f=34===l?-1:1;if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=Math.abs(c[0].offsetLeft)-f*(.9*d.width());else var h="y",m=Math.abs(c[0].offsetTop)-f*(.9*d.height());G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}else if((35===l||36===l)&&!e(document.activeElement).is(u)&&((n.overflowed[0]||n.overflowed[1])&&(t.preventDefault(),t.stopImmediatePropagation()),"keyup"===t.type)){if("x"===i.axis||"yx"===i.axis&&n.overflowed[1]&&!n.overflowed[0])var h="x",m=35===l?Math.abs(d.width()-c.outerWidth(!1)):0;else var h="y",m=35===l?Math.abs(d.height()-c.outerHeight(!1)):0;G(o,m.toString(),{dir:h,scrollEasing:"mcsEaseInOut"})}}}var o=e(this),n=o.data(a),i=n.opt,r=n.sequential,l=a+"_"+n.idx,s=e("#mCSB_"+n.idx),c=e("#mCSB_"+n.idx+"_container"),d=c.parent(),u="input,textarea,select,datalist,keygen,[contenteditable='true']",f=c.find("iframe"),h=["blur."+l+" keydown."+l+" keyup."+l];f.length&&f.each(function(){e(this).bind("load",function(){A(this)&&e(this.contentDocument||this.contentWindow.document).bind(h[0],function(e){t(e)})})}),s.attr("tabindex","0").bind(h[0],function(e){t(e)})},j=function(t,o,n,i,r){function l(e){u.snapAmount&&(f.scrollAmount=u.snapAmount instanceof Array?"x"===f.dir[0]?u.snapAmount[1]:u.snapAmount[0]:u.snapAmount);var o="stepped"!==f.type,a=r?r:e?o?p/1.5:g:1e3/60,n=e?o?7.5:40:2.5,s=[Math.abs(h[0].offsetTop),Math.abs(h[0].offsetLeft)],d=[c.scrollRatio.y>10?10:c.scrollRatio.y,c.scrollRatio.x>10?10:c.scrollRatio.x],m="x"===f.dir[0]?s[1]+f.dir[1]*(d[1]*n):s[0]+f.dir[1]*(d[0]*n),v="x"===f.dir[0]?s[1]+f.dir[1]*parseInt(f.scrollAmount):s[0]+f.dir[1]*parseInt(f.scrollAmount),x="auto"!==f.scrollAmount?v:m,_=i?i:e?o?"mcsLinearOut":"mcsEaseInOut":"mcsLinear",w=!!e;return e&&17>a&&(x="x"===f.dir[0]?s[1]:s[0]),G(t,x.toString(),{dir:f.dir[0],scrollEasing:_,dur:a,onComplete:w}),e?void(f.dir=!1):(clearTimeout(f.step),void(f.step=setTimeout(function(){l()},a)))}function s(){clearTimeout(f.step),$(f,"step"),Q(t)}var c=t.data(a),u=c.opt,f=c.sequential,h=e("#mCSB_"+c.idx+"_container"),m="stepped"===f.type,p=u.scrollInertia<26?26:u.scrollInertia,g=u.scrollInertia<1?17:u.scrollInertia;switch(o){case"on":if(f.dir=[n===d[16]||n===d[15]||39===n||37===n?"x":"y",n===d[13]||n===d[15]||38===n||37===n?-1:1],Q(t),oe(n)&&"stepped"===f.type)return;l(m);break;case"off":s(),(m||c.tweenRunning&&f.dir)&&l(!0)}},Y=function(t){var o=e(this).data(a).opt,n=[];return"function"==typeof t&&(t=t()),t instanceof Array?n=t.length>1?[t[0],t[1]]:"x"===o.axis?[null,t[0]]:[t[0],null]:(n[0]=t.y?t.y:t.x||"x"===o.axis?null:t,n[1]=t.x?t.x:t.y||"y"===o.axis?null:t),"function"==typeof n[0]&&(n[0]=n[0]()),"function"==typeof n[1]&&(n[1]=n[1]()),n},X=function(t,o){if(null!=t&&"undefined"!=typeof t){var n=e(this),i=n.data(a),r=i.opt,l=e("#mCSB_"+i.idx+"_container"),s=l.parent(),c=typeof t;o||(o="x"===r.axis?"x":"y");var d="x"===o?l.outerWidth(!1)-s.width():l.outerHeight(!1)-s.height(),f="x"===o?l[0].offsetLeft:l[0].offsetTop,h="x"===o?"left":"top";switch(c){case"function":return t();case"object":var m=t.jquery?t:e(t);if(!m.length)return;return"x"===o?ae(m)[1]:ae(m)[0];case"string":case"number":if(oe(t))return Math.abs(t);if(-1!==t.indexOf("%"))return Math.abs(d*parseInt(t)/100);if(-1!==t.indexOf("-="))return Math.abs(f-parseInt(t.split("-=")[1]));if(-1!==t.indexOf("+=")){var p=f+parseInt(t.split("+=")[1]);return p>=0?0:Math.abs(p)}if(-1!==t.indexOf("px")&&oe(t.split("px")[0]))return Math.abs(t.split("px")[0]);if("top"===t||"left"===t)return 0;if("bottom"===t)return Math.abs(s.height()-l.outerHeight(!1));if("right"===t)return Math.abs(s.width()-l.outerWidth(!1));if("first"===t||"last"===t){var m=l.find(":"+t);return"x"===o?ae(m)[1]:ae(m)[0]}return e(t).length?"x"===o?ae(e(t))[1]:ae(e(t))[0]:(l.css(h,t),void u.update.call(null,n[0]))}}},N=function(t){function o(){return clearTimeout(f[0].autoUpdate),0===l.parents("html").length?void(l=null):void(f[0].autoUpdate=setTimeout(function(){return c.advanced.updateOnSelectorChange&&(s.poll.change.n=i(),s.poll.change.n!==s.poll.change.o)?(s.poll.change.o=s.poll.change.n,void r(3)):c.advanced.updateOnContentResize&&(s.poll.size.n=l[0].scrollHeight+l[0].scrollWidth+f[0].offsetHeight+l[0].offsetHeight+l[0].offsetWidth,s.poll.size.n!==s.poll.size.o)?(s.poll.size.o=s.poll.size.n,void r(1)):!c.advanced.updateOnImageLoad||"auto"===c.advanced.updateOnImageLoad&&"y"===c.axis||(s.poll.img.n=f.find("img").length,s.poll.img.n===s.poll.img.o)?void((c.advanced.updateOnSelectorChange||c.advanced.updateOnContentResize||c.advanced.updateOnImageLoad)&&o()):(s.poll.img.o=s.poll.img.n,void f.find("img").each(function(){n(this)}))},c.advanced.autoUpdateTimeout))}function n(t){function o(e,t){return function(){return t.apply(e,arguments)}}function a(){this.onload=null,e(t).addClass(d[2]),r(2)}if(e(t).hasClass(d[2]))return void r();var n=new Image;n.onload=o(n,a),n.src=t.src}function i(){c.advanced.updateOnSelectorChange===!0&&(c.advanced.updateOnSelectorChange="*");var e=0,t=f.find(c.advanced.updateOnSelectorChange);return c.advanced.updateOnSelectorChange&&t.length>0&&t.each(function(){e+=this.offsetHeight+this.offsetWidth}),e}function r(e){clearTimeout(f[0].autoUpdate),u.update.call(null,l[0],e)}var l=e(this),s=l.data(a),c=s.opt,f=e("#mCSB_"+s.idx+"_container");return t?(clearTimeout(f[0].autoUpdate),void $(f[0],"autoUpdate")):void o()},V=function(e,t,o){return Math.round(e/t)*t-o},Q=function(t){var o=t.data(a),n=e("#mCSB_"+o.idx+"_container,#mCSB_"+o.idx+"_container_wrapper,#mCSB_"+o.idx+"_dragger_vertical,#mCSB_"+o.idx+"_dragger_horizontal");n.each(function(){Z.call(this)})},G=function(t,o,n){function i(e){return s&&c.callbacks[e]&&"function"==typeof c.callbacks[e]}function r(){return[c.callbacks.alwaysTriggerOffsets||w>=S[0]+y,c.callbacks.alwaysTriggerOffsets||-B>=w]}function l(){var e=[h[0].offsetTop,h[0].offsetLeft],o=[x[0].offsetTop,x[0].offsetLeft],a=[h.outerHeight(!1),h.outerWidth(!1)],i=[f.height(),f.width()];t[0].mcs={content:h,top:e[0],left:e[1],draggerTop:o[0],draggerLeft:o[1],topPct:Math.round(100*Math.abs(e[0])/(Math.abs(a[0])-i[0])),leftPct:Math.round(100*Math.abs(e[1])/(Math.abs(a[1])-i[1])),direction:n.dir}}var s=t.data(a),c=s.opt,d={trigger:"internal",dir:"y",scrollEasing:"mcsEaseOut",drag:!1,dur:c.scrollInertia,overwrite:"all",callbacks:!0,onStart:!0,onUpdate:!0,onComplete:!0},n=e.extend(d,n),u=[n.dur,n.drag?0:n.dur],f=e("#mCSB_"+s.idx),h=e("#mCSB_"+s.idx+"_container"),m=h.parent(),p=c.callbacks.onTotalScrollOffset?Y.call(t,c.callbacks.onTotalScrollOffset):[0,0],g=c.callbacks.onTotalScrollBackOffset?Y.call(t,c.callbacks.onTotalScrollBackOffset):[0,0];if(s.trigger=n.trigger,0===m.scrollTop()&&0===m.scrollLeft()||(e(".mCSB_"+s.idx+"_scrollbar").css("visibility","visible"),m.scrollTop(0).scrollLeft(0)),"_resetY"!==o||s.contentReset.y||(i("onOverflowYNone")&&c.callbacks.onOverflowYNone.call(t[0]),s.contentReset.y=1),"_resetX"!==o||s.contentReset.x||(i("onOverflowXNone")&&c.callbacks.onOverflowXNone.call(t[0]),s.contentReset.x=1),"_resetY"!==o&&"_resetX"!==o){if(!s.contentReset.y&&t[0].mcs||!s.overflowed[0]||(i("onOverflowY")&&c.callbacks.onOverflowY.call(t[0]),s.contentReset.x=null),!s.contentReset.x&&t[0].mcs||!s.overflowed[1]||(i("onOverflowX")&&c.callbacks.onOverflowX.call(t[0]),s.contentReset.x=null),c.snapAmount){var v=c.snapAmount instanceof Array?"x"===n.dir?c.snapAmount[1]:c.snapAmount[0]:c.snapAmount;o=V(o,v,c.snapOffset)}switch(n.dir){case"x":var x=e("#mCSB_"+s.idx+"_dragger_horizontal"),_="left",w=h[0].offsetLeft,S=[f.width()-h.outerWidth(!1),x.parent().width()-x.width()],b=[o,0===o?0:o/s.scrollRatio.x],y=p[1],B=g[1],T=y>0?y/s.scrollRatio.x:0,k=B>0?B/s.scrollRatio.x:0;break;case"y":var x=e("#mCSB_"+s.idx+"_dragger_vertical"),_="top",w=h[0].offsetTop,S=[f.height()-h.outerHeight(!1),x.parent().height()-x.height()],b=[o,0===o?0:o/s.scrollRatio.y],y=p[0],B=g[0],T=y>0?y/s.scrollRatio.y:0,k=B>0?B/s.scrollRatio.y:0}b[1]<0||0===b[0]&&0===b[1]?b=[0,0]:b[1]>=S[1]?b=[S[0],S[1]]:b[0]=-b[0],t[0].mcs||(l(),i("onInit")&&c.callbacks.onInit.call(t[0])),clearTimeout(h[0].onCompleteTimeout),J(x[0],_,Math.round(b[1]),u[1],n.scrollEasing),!s.tweenRunning&&(0===w&&b[0]>=0||w===S[0]&&b[0]<=S[0])||J(h[0],_,Math.round(b[0]),u[0],n.scrollEasing,n.overwrite,{onStart:function(){n.callbacks&&n.onStart&&!s.tweenRunning&&(i("onScrollStart")&&(l(),c.callbacks.onScrollStart.call(t[0])),s.tweenRunning=!0,C(x),s.cbOffsets=r())},onUpdate:function(){n.callbacks&&n.onUpdate&&i("whileScrolling")&&(l(),c.callbacks.whileScrolling.call(t[0]))},onComplete:function(){if(n.callbacks&&n.onComplete){"yx"===c.axis&&clearTimeout(h[0].onCompleteTimeout);var e=h[0].idleTimer||0;h[0].onCompleteTimeout=setTimeout(function(){i("onScroll")&&(l(),c.callbacks.onScroll.call(t[0])),i("onTotalScroll")&&b[1]>=S[1]-T&&s.cbOffsets[0]&&(l(),c.callbacks.onTotalScroll.call(t[0])),i("onTotalScrollBack")&&b[1]<=k&&s.cbOffsets[1]&&(l(),c.callbacks.onTotalScrollBack.call(t[0])),s.tweenRunning=!1,h[0].idleTimer=0,C(x,"hide")},e)}}})}},J=function(e,t,o,a,n,i,r){function l(){S.stop||(x||m.call(),x=K()-v,s(),x>=S.time&&(S.time=x>S.time?x+f-(x-S.time):x+f-1,S.time<x+1&&(S.time=x+1)),S.time<a?S.id=h(l):g.call())}function s(){a>0?(S.currVal=u(S.time,_,b,a,n),w[t]=Math.round(S.currVal)+"px"):w[t]=o+"px",p.call()}function c(){f=1e3/60,S.time=x+f,h=window.requestAnimationFrame?window.requestAnimationFrame:function(e){return s(),setTimeout(e,.01)},S.id=h(l)}function d(){null!=S.id&&(window.requestAnimationFrame?window.cancelAnimationFrame(S.id):clearTimeout(S.id),S.id=null)}function u(e,t,o,a,n){switch(n){case"linear":case"mcsLinear":return o*e/a+t;case"mcsLinearOut":return e/=a,e--,o*Math.sqrt(1-e*e)+t;case"easeInOutSmooth":return e/=a/2,1>e?o/2*e*e+t:(e--,-o/2*(e*(e-2)-1)+t);case"easeInOutStrong":return e/=a/2,1>e?o/2*Math.pow(2,10*(e-1))+t:(e--,o/2*(-Math.pow(2,-10*e)+2)+t);case"easeInOut":case"mcsEaseInOut":return e/=a/2,1>e?o/2*e*e*e+t:(e-=2,o/2*(e*e*e+2)+t);case"easeOutSmooth":return e/=a,e--,-o*(e*e*e*e-1)+t;case"easeOutStrong":return o*(-Math.pow(2,-10*e/a)+1)+t;case"easeOut":case"mcsEaseOut":default:var i=(e/=a)*e,r=i*e;return t+o*(.499999999999997*r*i+-2.5*i*i+5.5*r+-6.5*i+4*e)}}e._mTween||(e._mTween={top:{},left:{}});var f,h,r=r||{},m=r.onStart||function(){},p=r.onUpdate||function(){},g=r.onComplete||function(){},v=K(),x=0,_=e.offsetTop,w=e.style,S=e._mTween[t];"left"===t&&(_=e.offsetLeft);var b=o-_;S.stop=0,"none"!==i&&d(),c()},K=function(){return window.performance&&window.performance.now?window.performance.now():window.performance&&window.performance.webkitNow?window.performance.webkitNow():Date.now?Date.now():(new Date).getTime()},Z=function(){var e=this;e._mTween||(e._mTween={top:{},left:{}});for(var t=["top","left"],o=0;o<t.length;o++){var a=t[o];e._mTween[a].id&&(window.requestAnimationFrame?window.cancelAnimationFrame(e._mTween[a].id):clearTimeout(e._mTween[a].id),e._mTween[a].id=null,e._mTween[a].stop=1)}},$=function(e,t){try{delete e[t]}catch(o){e[t]=null}},ee=function(e){return!(e.which&&1!==e.which)},te=function(e){var t=e.originalEvent.pointerType;return!(t&&"touch"!==t&&2!==t)},oe=function(e){return!isNaN(parseFloat(e))&&isFinite(e)},ae=function(e){var t=e.parents(".mCSB_container");return[e.offset().top-t.offset().top,e.offset().left-t.offset().left]},ne=function(){function e(){var e=["webkit","moz","ms","o"];if("hidden"in document)return"hidden";for(var t=0;t<e.length;t++)if(e[t]+"Hidden"in document)return e[t]+"Hidden";return null}var t=e();return t?document[t]:!1};e.fn[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o]=function(t){return u[t]?u[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):u.init.apply(this,arguments)},e[o].defaults=i,window[o]=!0,e(window).bind("load",function(){e(n)[o](),e.extend(e.expr[":"],{mcsInView:e.expr[":"].mcsInView||function(t){var o,a,n=e(t),i=n.parents(".mCSB_container");if(i.length)return o=i.parent(),a=[i[0].offsetTop,i[0].offsetLeft],a[0]+ae(n)[0]>=0&&a[0]+ae(n)[0]<o.height()-n.outerHeight(!1)&&a[1]+ae(n)[1]>=0&&a[1]+ae(n)[1]<o.width()-n.outerWidth(!1)},mcsInSight:e.expr[":"].mcsInSight||function(t,o,a){var n,i,r,l,s=e(t),c=s.parents(".mCSB_container"),d="exact"===a[3]?[[1,0],[1,0]]:[[.9,.1],[.6,.4]];if(c.length)return n=[s.outerHeight(!1),s.outerWidth(!1)],r=[c[0].offsetTop+ae(s)[0],c[0].offsetLeft+ae(s)[1]],i=[c.parent()[0].offsetHeight,c.parent()[0].offsetWidth],l=[n[0]<i[0]?d[0]:d[1],n[1]<i[1]?d[0]:d[1]],r[0]-i[0]*l[0][0]<0&&r[0]+n[0]-i[0]*l[0][1]>=0&&r[1]-i[1]*l[1][0]<0&&r[1]+n[1]-i[1]*l[1][1]>=0},mcsOverflow:e.expr[":"].mcsOverflow||function(t){var o=e(t).data(a);if(o)return o.overflowed[0]||o.overflowed[1]}})})})});

/*!
 * Isotope PACKAGED v3.0.6
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * https://isotope.metafizzy.co
 * Copyright 2010-2018 Metafizzy
 */
!function(e,i){"function"==typeof define&&define.amd?define("jquery-bridget/jquery-bridget",["jquery"],function(t){return i(e,t)}):"object"==typeof module&&module.exports?module.exports=i(e,require("jquery")):e.jQueryBridget=i(e,e.jQuery)}(window,function(t,e){"use strict";function i(u,n,c){(c=c||e||t.jQuery)&&(n.prototype.option||(n.prototype.option=function(t){c.isPlainObject(t)&&(this.options=c.extend(!0,this.options,t))}),c.fn[u]=function(t){return"string"!=typeof t?(function(t,o){t.each(function(t,e){var i=c.data(e,u);i?(i.option(o),i._init()):(i=new n(e,o),c.data(e,u,i))})}(this,t),this):function(t,s,r){var a,h="$()."+u+'("'+s+'")';return t.each(function(t,e){var i=c.data(e,u);if(i){var o=i[s];if(o&&"_"!=s.charAt(0)){var n=o.apply(i,r);a=void 0===a?n:a}else d(h+" is not a valid method")}else d(u+" not initialized. Cannot call methods, i.e. "+h)}),void 0!==a?a:t}(this,t,s.call(arguments,1))},o(c))}function o(t){!t||t&&t.bridget||(t.bridget=i)}var s=Array.prototype.slice,n=t.console,d=void 0===n?function(){}:function(t){n.error(t)};return o(e||t.jQuery),i}),function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}("undefined"!=typeof window?window:this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},o=i[t]=i[t]||[];return-1==o.indexOf(e)&&o.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{};return(i[t]=i[t]||{})[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var o=i.indexOf(e);return-1!=o&&i.splice(o,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){i=i.slice(0),e=e||[];for(var o=this._onceEvents&&this._onceEvents[t],n=0;n<i.length;n++){var s=i[n];o&&o[s]&&(this.off(t,s),delete o[s]),s.apply(this,e)}return this}},e.allOff=function(){delete this._events,delete this._onceEvents},t}),function(t,e){"function"==typeof define&&define.amd?define("get-size/get-size",e):"object"==typeof module&&module.exports?module.exports=e():t.getSize=e()}(window,function(){"use strict";function y(t){var e=parseFloat(t);return-1==t.indexOf("%")&&!isNaN(e)&&e}function v(t){var e=getComputedStyle(t);return e||i("Style returned "+e+". Are you running this code in a hidden iframe on Firefox? See https://bit.ly/getsizebug1"),e}function _(t){if(function(){if(!b){b=!0;var t=document.createElement("div");t.style.width="200px",t.style.padding="1px 2px 3px 4px",t.style.borderStyle="solid",t.style.borderWidth="1px 2px 3px 4px",t.style.boxSizing="border-box";var e=document.body||document.documentElement;e.appendChild(t);var i=v(t);x=200==Math.round(y(i.width)),_.isBoxSizeOuter=x,e.removeChild(t)}}(),"string"==typeof t&&(t=document.querySelector(t)),t&&"object"==typeof t&&t.nodeType){var e=v(t);if("none"==e.display)return function(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0;e<I;e++){t[z[e]]=0}return t}();var i={};i.width=t.offsetWidth,i.height=t.offsetHeight;for(var o=i.isBorderBox="border-box"==e.boxSizing,n=0;n<I;n++){var s=z[n],r=e[s],a=parseFloat(r);i[s]=isNaN(a)?0:a}var h=i.paddingLeft+i.paddingRight,u=i.paddingTop+i.paddingBottom,c=i.marginLeft+i.marginRight,d=i.marginTop+i.marginBottom,l=i.borderLeftWidth+i.borderRightWidth,f=i.borderTopWidth+i.borderBottomWidth,p=o&&x,m=y(e.width);!1!==m&&(i.width=m+(p?0:h+l));var g=y(e.height);return!1!==g&&(i.height=g+(p?0:u+f)),i.innerWidth=i.width-(h+l),i.innerHeight=i.height-(u+f),i.outerWidth=i.width+c,i.outerHeight=i.height+d,i}}var x,i="undefined"==typeof console?function(){}:function(t){console.error(t)},z=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"],I=z.length,b=!1;return _}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("desandro-matches-selector/matches-selector",e):"object"==typeof module&&module.exports?module.exports=e():t.matchesSelector=e()}(window,function(){"use strict";var i=function(){var t=window.Element.prototype;if(t.matches)return"matches";if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0;i<e.length;i++){var o=e[i]+"MatchesSelector";if(t[o])return o}}();return function(t,e){return t[i](e)}}),function(e,i){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["desandro-matches-selector/matches-selector"],function(t){return i(e,t)}):"object"==typeof module&&module.exports?module.exports=i(e,require("desandro-matches-selector")):e.fizzyUIUtils=i(e,e.matchesSelector)}(window,function(u,s){var c={extend:function(t,e){for(var i in e)t[i]=e[i];return t},modulo:function(t,e){return(t%e+e)%e}},e=Array.prototype.slice;c.makeArray=function(t){return Array.isArray(t)?t:null==t?[]:"object"==typeof t&&"number"==typeof t.length?e.call(t):[t]},c.removeFrom=function(t,e){var i=t.indexOf(e);-1!=i&&t.splice(i,1)},c.getParent=function(t,e){for(;t.parentNode&&t!=document.body;)if(t=t.parentNode,s(t,e))return t},c.getQueryElement=function(t){return"string"==typeof t?document.querySelector(t):t},c.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},c.filterFindElements=function(t,o){t=c.makeArray(t);var n=[];return t.forEach(function(t){if(t instanceof HTMLElement){if(!o)return void n.push(t);s(t,o)&&n.push(t);for(var e=t.querySelectorAll(o),i=0;i<e.length;i++)n.push(e[i])}}),n},c.debounceMethod=function(t,e,o){o=o||100;var n=t.prototype[e],s=e+"Timeout";t.prototype[e]=function(){var t=this[s];clearTimeout(t);var e=arguments,i=this;this[s]=setTimeout(function(){n.apply(i,e),delete i[s]},o)}},c.docReady=function(t){var e=document.readyState;"complete"==e||"interactive"==e?setTimeout(t):document.addEventListener("DOMContentLoaded",t)},c.toDashed=function(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()};var d=u.console;return c.htmlInit=function(a,h){c.docReady(function(){var t=c.toDashed(h),n="data-"+t,e=document.querySelectorAll("["+n+"]"),i=document.querySelectorAll(".js-"+t),o=c.makeArray(e).concat(c.makeArray(i)),s=n+"-options",r=u.jQuery;o.forEach(function(e){var t,i=e.getAttribute(n)||e.getAttribute(s);try{t=i&&JSON.parse(i)}catch(t){return void(d&&d.error("Error parsing "+n+" on "+e.className+": "+t))}var o=new a(e,t);r&&r.data(e,h,o)})})},c}),function(t,e){"function"==typeof define&&define.amd?define("outlayer/item",["ev-emitter/ev-emitter","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("ev-emitter"),require("get-size")):(t.Outlayer={},t.Outlayer.Item=e(t.EvEmitter,t.getSize))}(window,function(t,e){"use strict";function i(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}var o=document.documentElement.style,n="string"==typeof o.transition?"transition":"WebkitTransition",s="string"==typeof o.transform?"transform":"WebkitTransform",r={WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[n],a={transform:s,transition:n,transitionDuration:n+"Duration",transitionProperty:n+"Property",transitionDelay:n+"Delay"},h=i.prototype=Object.create(t.prototype);h.constructor=i,h._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},h.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},h.getSize=function(){this.size=e(this.element)},h.css=function(t){var e=this.element.style;for(var i in t){e[a[i]||i]=t[i]}},h.getPosition=function(){var t=getComputedStyle(this.element),e=this.layout._getOption("originLeft"),i=this.layout._getOption("originTop"),o=t[e?"left":"right"],n=t[i?"top":"bottom"],s=parseFloat(o),r=parseFloat(n),a=this.layout.size;-1!=o.indexOf("%")&&(s=s/100*a.width),-1!=n.indexOf("%")&&(r=r/100*a.height),s=isNaN(s)?0:s,r=isNaN(r)?0:r,s-=e?a.paddingLeft:a.paddingRight,r-=i?a.paddingTop:a.paddingBottom,this.position.x=s,this.position.y=r},h.layoutPosition=function(){var t=this.layout.size,e={},i=this.layout._getOption("originLeft"),o=this.layout._getOption("originTop"),n=i?"paddingLeft":"paddingRight",s=i?"left":"right",r=i?"right":"left",a=this.position.x+t[n];e[s]=this.getXValue(a),e[r]="";var h=o?"paddingTop":"paddingBottom",u=o?"top":"bottom",c=o?"bottom":"top",d=this.position.y+t[h];e[u]=this.getYValue(d),e[c]="",this.css(e),this.emitEvent("layout",[this])},h.getXValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&!e?t/this.layout.size.width*100+"%":t+"px"},h.getYValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&e?t/this.layout.size.height*100+"%":t+"px"},h._transitionTo=function(t,e){this.getPosition();var i=this.position.x,o=this.position.y,n=t==this.position.x&&e==this.position.y;if(this.setPosition(t,e),!n||this.isTransitioning){var s=t-i,r=e-o,a={};a.transform=this.getTranslate(s,r),this.transition({to:a,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})}else this.layoutPosition()},h.getTranslate=function(t,e){return"translate3d("+(t=this.layout._getOption("originLeft")?t:-t)+"px, "+(e=this.layout._getOption("originTop")?e:-e)+"px, 0)"},h.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},h.moveTo=h._transitionTo,h.setPosition=function(t,e){this.position.x=parseFloat(t),this.position.y=parseFloat(e)},h._nonTransition=function(t){for(var e in this.css(t.to),t.isCleaning&&this._removeStyles(t.to),t.onTransitionEnd)t.onTransitionEnd[e].call(this)},h.transition=function(t){if(parseFloat(this.layout.options.transitionDuration)){var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);this.element.offsetHeight;null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0}else this._nonTransition(t)};var u="opacity,"+s.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()});h.enableTransition=function(){if(!this.isTransitioning){var t=this.layout.options.transitionDuration;t="number"==typeof t?t+"ms":t,this.css({transitionProperty:u,transitionDuration:t,transitionDelay:this.staggerDelay||0}),this.element.addEventListener(r,this,!1)}},h.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},h.onotransitionend=function(t){this.ontransitionend(t)};var c={"-webkit-transform":"transform"};h.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,i=c[t.propertyName]||t.propertyName;if(delete e.ingProperties[i],function(t){for(var e in t)return!1;return!null}(e.ingProperties)&&this.disableTransition(),i in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[i]),i in e.onEnd)e.onEnd[i].call(this),delete e.onEnd[i];this.emitEvent("transitionEnd",[this])}},h.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(r,this,!1),this.isTransitioning=!1},h._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var d={transitionProperty:"",transitionDuration:"",transitionDelay:""};return h.removeTransitionStyles=function(){this.css(d)},h.stagger=function(t){t=isNaN(t)?0:t,this.staggerDelay=t+"ms"},h.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},h.remove=function(){return n&&parseFloat(this.layout.options.transitionDuration)?(this.once("transitionEnd",function(){this.removeElem()}),void this.hide()):void this.removeElem()},h.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options,e={};e[this.getHideRevealTransitionEndProperty("visibleStyle")]=this.onRevealTransitionEnd,this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0,onTransitionEnd:e})},h.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},h.getHideRevealTransitionEndProperty=function(t){var e=this.layout.options[t];if(e.opacity)return"opacity";for(var i in e)return i},h.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options,e={};e[this.getHideRevealTransitionEndProperty("hiddenStyle")]=this.onHideTransitionEnd,this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:e})},h.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},h.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},i}),function(n,s){"use strict";"function"==typeof define&&define.amd?define("outlayer/outlayer",["ev-emitter/ev-emitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(t,e,i,o){return s(n,t,e,i,o)}):"object"==typeof module&&module.exports?module.exports=s(n,require("ev-emitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):n.Outlayer=s(n,n.EvEmitter,n.getSize,n.fizzyUIUtils,n.Outlayer.Item)}(window,function(t,e,n,s,o){"use strict";function r(t,e){var i=s.getQueryElement(t);if(i){this.element=i,u&&(this.$element=u(this.element)),this.options=s.extend({},this.constructor.defaults),this.option(e);var o=++c;this.element.outlayerGUID=o,(d[o]=this)._create(),this._getOption("initLayout")&&this.layout()}else h&&h.error("Bad element for "+this.constructor.namespace+": "+(i||t))}function a(t){function e(){t.apply(this,arguments)}return(e.prototype=Object.create(t.prototype)).constructor=e}function i(){}var h=t.console,u=t.jQuery,c=0,d={};r.namespace="outlayer",r.Item=o,r.defaults={containerStyle:{position:"relative"},initLayout:!0,originLeft:!0,originTop:!0,resize:!0,resizeContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}};var l=r.prototype;s.extend(l,e.prototype),l.option=function(t){s.extend(this.options,t)},l._getOption=function(t){var e=this.constructor.compatOptions[t];return e&&void 0!==this.options[e]?this.options[e]:this.options[t]},r.compatOptions={initLayout:"isInitLayout",horizontal:"isHorizontal",layoutInstant:"isLayoutInstant",originLeft:"isOriginLeft",originTop:"isOriginTop",resize:"isResizeBound",resizeContainer:"isResizingContainer"},l._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),s.extend(this.element.style,this.options.containerStyle),this._getOption("resize")&&this.bindResize()},l.reloadItems=function(){this.items=this._itemize(this.element.children)},l._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,o=[],n=0;n<e.length;n++){var s=new i(e[n],this);o.push(s)}return o},l._filterFindItemElements=function(t){return s.filterFindElements(t,this.options.itemSelector)},l.getItemElements=function(){return this.items.map(function(t){return t.element})},l.layout=function(){this._resetLayout(),this._manageStamps();var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;this.layoutItems(this.items,e),this._isLayoutInited=!0},l._init=l.layout,l._resetLayout=function(){this.getSize()},l.getSize=function(){this.size=n(this.element)},l._getMeasurement=function(t,e){var i,o=this.options[t];o?("string"==typeof o?i=this.element.querySelector(o):o instanceof HTMLElement&&(i=o),this[t]=i?n(i)[e]:o):this[t]=0},l.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},l._getItemsForLayout=function(t){return t.filter(function(t){return!t.isIgnored})},l._layoutItems=function(t,i){if(this._emitCompleteOnItems("layout",t),t&&t.length){var o=[];t.forEach(function(t){var e=this._getItemLayoutPosition(t);e.item=t,e.isInstant=i||t.isLayoutInstant,o.push(e)},this),this._processLayoutQueue(o)}},l._getItemLayoutPosition=function(){return{x:0,y:0}},l._processLayoutQueue=function(t){this.updateStagger(),t.forEach(function(t,e){this._positionItem(t.item,t.x,t.y,t.isInstant,e)},this)},l.updateStagger=function(){var t=this.options.stagger;return null==t?void(this.stagger=0):(this.stagger=function(t){if("number"==typeof t)return t;var e=t.match(/(^\d*\.?\d*)(\w*)/),i=e&&e[1],o=e&&e[2];return i.length?(i=parseFloat(i))*(f[o]||1):0}(t),this.stagger)},l._positionItem=function(t,e,i,o,n){o?t.goTo(e,i):(t.stagger(n*this.stagger),t.moveTo(e,i))},l._postLayout=function(){this.resizeContainer()},l.resizeContainer=function(){if(this._getOption("resizeContainer")){var t=this._getContainerSize();t&&(this._setContainerMeasure(t.width,!0),this._setContainerMeasure(t.height,!1))}},l._getContainerSize=i,l._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},l._emitCompleteOnItems=function(e,t){function i(){n.dispatchEvent(e+"Complete",null,[t])}function o(){++r==s&&i()}var n=this,s=t.length;if(t&&s){var r=0;t.forEach(function(t){t.once(e,o)})}else i()},l.dispatchEvent=function(t,e,i){var o=e?[e].concat(i):i;if(this.emitEvent(t,o),u)if(this.$element=this.$element||u(this.element),e){var n=u.Event(e);n.type=t,this.$element.trigger(n,i)}else this.$element.trigger(t,i)},l.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},l.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},l.stamp=function(t){(t=this._find(t))&&(this.stamps=this.stamps.concat(t),t.forEach(this.ignore,this))},l.unstamp=function(t){(t=this._find(t))&&t.forEach(function(t){s.removeFrom(this.stamps,t),this.unignore(t)},this)},l._find=function(t){if(t)return"string"==typeof t&&(t=this.element.querySelectorAll(t)),s.makeArray(t)},l._manageStamps=function(){this.stamps&&this.stamps.length&&(this._getBoundingRect(),this.stamps.forEach(this._manageStamp,this))},l._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},l._manageStamp=i,l._getElementOffset=function(t){var e=t.getBoundingClientRect(),i=this._boundingRect,o=n(t);return{left:e.left-i.left-o.marginLeft,top:e.top-i.top-o.marginTop,right:i.right-e.right-o.marginRight,bottom:i.bottom-e.bottom-o.marginBottom}},l.handleEvent=s.handleEvent,l.bindResize=function(){t.addEventListener("resize",this),this.isResizeBound=!0},l.unbindResize=function(){t.removeEventListener("resize",this),this.isResizeBound=!1},l.onresize=function(){this.resize()},s.debounceMethod(r,"onresize",100),l.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},l.needsResizeLayout=function(){var t=n(this.element);return this.size&&t&&t.innerWidth!==this.size.innerWidth},l.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},l.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},l.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},l.reveal=function(t){if(this._emitCompleteOnItems("reveal",t),t&&t.length){var i=this.updateStagger();t.forEach(function(t,e){t.stagger(e*i),t.reveal()})}},l.hide=function(t){if(this._emitCompleteOnItems("hide",t),t&&t.length){var i=this.updateStagger();t.forEach(function(t,e){t.stagger(e*i),t.hide()})}},l.revealItemElements=function(t){var e=this.getItems(t);this.reveal(e)},l.hideItemElements=function(t){var e=this.getItems(t);this.hide(e)},l.getItem=function(t){for(var e=0;e<this.items.length;e++){var i=this.items[e];if(i.element==t)return i}},l.getItems=function(t){t=s.makeArray(t);var i=[];return t.forEach(function(t){var e=this.getItem(t);e&&i.push(e)},this),i},l.remove=function(t){var e=this.getItems(t);this._emitCompleteOnItems("remove",e),e&&e.length&&e.forEach(function(t){t.remove(),s.removeFrom(this.items,t)},this)},l.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="",this.items.forEach(function(t){t.destroy()}),this.unbindResize();var e=this.element.outlayerGUID;delete d[e],delete this.element.outlayerGUID,u&&u.removeData(this.element,this.constructor.namespace)},r.data=function(t){var e=(t=s.getQueryElement(t))&&t.outlayerGUID;return e&&d[e]},r.create=function(t,e){var i=a(r);return i.defaults=s.extend({},r.defaults),s.extend(i.defaults,e),i.compatOptions=s.extend({},r.compatOptions),i.namespace=t,i.data=r.data,i.Item=a(o),s.htmlInit(i,t),u&&u.bridget&&u.bridget(t,i),i};var f={ms:1,s:1e3};return r.Item=o,r}),function(t,e){"function"==typeof define&&define.amd?define("isotope-layout/js/item",["outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.Item=e(t.Outlayer))}(window,function(t){"use strict";function e(){t.Item.apply(this,arguments)}var i=e.prototype=Object.create(t.Item.prototype),o=i._create;i._create=function(){this.id=this.layout.itemGUID++,o.call(this),this.sortData={}},i.updateSortData=function(){if(!this.isIgnored){this.sortData.id=this.id,this.sortData["original-order"]=this.id,this.sortData.random=Math.random();var t=this.layout.options.getSortData,e=this.layout._sorters;for(var i in t){var o=e[i];this.sortData[i]=o(this.element,this)}}};var n=i.destroy;return i.destroy=function(){n.apply(this,arguments),this.css({display:""})},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope-layout/js/layout-mode",["get-size/get-size","outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("get-size"),require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.LayoutMode=e(t.getSize,t.Outlayer))}(window,function(e,i){"use strict";function o(t){(this.isotope=t)&&(this.options=t.options[this.namespace],this.element=t.element,this.items=t.filteredItems,this.size=t.size)}var n=o.prototype;return["_resetLayout","_getItemLayoutPosition","_manageStamp","_getContainerSize","_getElementOffset","needsResizeLayout","_getOption"].forEach(function(t){n[t]=function(){return i.prototype[t].apply(this.isotope,arguments)}}),n.needsVerticalResizeLayout=function(){var t=e(this.isotope.element);return this.isotope.size&&t&&t.innerHeight!=this.isotope.size.innerHeight},n._getMeasurement=function(){this.isotope._getMeasurement.apply(this,arguments)},n.getColumnWidth=function(){this.getSegmentSize("column","Width")},n.getRowHeight=function(){this.getSegmentSize("row","Height")},n.getSegmentSize=function(t,e){var i=t+e,o="outer"+e;if(this._getMeasurement(i,o),!this[i]){var n=this.getFirstItemSize();this[i]=n&&n[o]||this.isotope.size["inner"+e]}},n.getFirstItemSize=function(){var t=this.isotope.filteredItems[0];return t&&t.element&&e(t.element)},n.layout=function(){this.isotope.layout.apply(this.isotope,arguments)},n.getSize=function(){this.isotope.getSize(),this.size=this.isotope.size},o.modes={},o.create=function(t,e){function i(){o.apply(this,arguments)}return(i.prototype=Object.create(n)).constructor=i,e&&(i.options=e),o.modes[i.prototype.namespace=t]=i},o}),function(t,e){"function"==typeof define&&define.amd?define("masonry-layout/masonry",["outlayer/outlayer","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window,function(t,u){var e=t.create("masonry");e.compatOptions.fitWidth="isFitWidth";var i=e.prototype;return i._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns(),this.colYs=[];for(var t=0;t<this.cols;t++)this.colYs.push(0);this.maxY=0,this.horizontalColIndex=0},i.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],e=t&&t.element;this.columnWidth=e&&u(e).outerWidth||this.containerWidth}var i=this.columnWidth+=this.gutter,o=this.containerWidth+this.gutter,n=o/i,s=i-o%i;n=Math[s&&s<1?"round":"floor"](n),this.cols=Math.max(n,1)},i.getContainerWidth=function(){var t=this._getOption("fitWidth")?this.element.parentNode:this.element,e=u(t);this.containerWidth=e&&e.innerWidth},i._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,i=Math[e&&e<1?"round":"ceil"](t.size.outerWidth/this.columnWidth);i=Math.min(i,this.cols);for(var o=this[this.options.horizontalOrder?"_getHorizontalColPosition":"_getTopColPosition"](i,t),n={x:this.columnWidth*o.col,y:o.y},s=o.y+t.size.outerHeight,r=i+o.col,a=o.col;a<r;a++)this.colYs[a]=s;return n},i._getTopColPosition=function(t){var e=this._getTopColGroup(t),i=Math.min.apply(Math,e);return{col:e.indexOf(i),y:i}},i._getTopColGroup=function(t){if(t<2)return this.colYs;for(var e=[],i=this.cols+1-t,o=0;o<i;o++)e[o]=this._getColGroupY(o,t);return e},i._getColGroupY=function(t,e){if(e<2)return this.colYs[t];var i=this.colYs.slice(t,t+e);return Math.max.apply(Math,i)},i._getHorizontalColPosition=function(t,e){var i=this.horizontalColIndex%this.cols;i=1<t&&i+t>this.cols?0:i;var o=e.size.outerWidth&&e.size.outerHeight;return this.horizontalColIndex=o?i+t:this.horizontalColIndex,{col:i,y:this._getColGroupY(i,t)}},i._manageStamp=function(t){var e=u(t),i=this._getElementOffset(t),o=this._getOption("originLeft")?i.left:i.right,n=o+e.outerWidth,s=Math.floor(o/this.columnWidth);s=Math.max(0,s);var r=Math.floor(n/this.columnWidth);r-=n%this.columnWidth?0:1,r=Math.min(this.cols-1,r);for(var a=(this._getOption("originTop")?i.top:i.bottom)+e.outerHeight,h=s;h<=r;h++)this.colYs[h]=Math.max(a,this.colYs[h])},i._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this._getOption("fitWidth")&&(t.width=this._getContainerFitWidth()),t},i._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},i.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!=this.containerWidth},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope-layout/js/layout-modes/masonry",["../layout-mode","masonry-layout/masonry"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode"),require("masonry-layout")):e(t.Isotope.LayoutMode,t.Masonry)}(window,function(t,e){"use strict";var i=t.create("masonry"),o=i.prototype,n={_getElementOffset:!0,layout:!0,_getMeasurement:!0};for(var s in e.prototype)n[s]||(o[s]=e.prototype[s]);var r=o.measureColumns;o.measureColumns=function(){this.items=this.isotope.filteredItems,r.call(this)};var a=o._getOption;return o._getOption=function(t){return"fitWidth"==t?void 0!==this.options.isFitWidth?this.options.isFitWidth:this.options.fitWidth:a.apply(this.isotope,arguments)},i}),function(t,e){"function"==typeof define&&define.amd?define("isotope-layout/js/layout-modes/fit-rows",["../layout-mode"],e):"object"==typeof exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("fitRows"),i=e.prototype;return i._resetLayout=function(){this.x=0,this.y=0,this.maxY=0,this._getMeasurement("gutter","outerWidth")},i._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth+this.gutter,i=this.isotope.size.innerWidth+this.gutter;0!==this.x&&e+this.x>i&&(this.x=0,this.y=this.maxY);var o={x:this.x,y:this.y};return this.maxY=Math.max(this.maxY,this.y+t.size.outerHeight),this.x+=e,o},i._getContainerSize=function(){return{height:this.maxY}},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope-layout/js/layout-modes/vertical",["../layout-mode"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("vertical",{horizontalAlignment:0}),i=e.prototype;return i._resetLayout=function(){this.y=0},i._getItemLayoutPosition=function(t){t.getSize();var e=(this.isotope.size.innerWidth-t.size.outerWidth)*this.options.horizontalAlignment,i=this.y;return this.y+=t.size.outerHeight,{x:e,y:i}},i._getContainerSize=function(){return{height:this.y}},e}),function(r,a){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","desandro-matches-selector/matches-selector","fizzy-ui-utils/utils","isotope-layout/js/item","isotope-layout/js/layout-mode","isotope-layout/js/layout-modes/masonry","isotope-layout/js/layout-modes/fit-rows","isotope-layout/js/layout-modes/vertical"],function(t,e,i,o,n,s){return a(r,t,e,i,o,n,s)}):"object"==typeof module&&module.exports?module.exports=a(r,require("outlayer"),require("get-size"),require("desandro-matches-selector"),require("fizzy-ui-utils"),require("isotope-layout/js/item"),require("isotope-layout/js/layout-mode"),require("isotope-layout/js/layout-modes/masonry"),require("isotope-layout/js/layout-modes/fit-rows"),require("isotope-layout/js/layout-modes/vertical")):r.Isotope=a(r,r.Outlayer,r.getSize,r.matchesSelector,r.fizzyUIUtils,r.Isotope.Item,r.Isotope.LayoutMode)}(window,function(t,i,e,o,s,n,r){var a=t.jQuery,h=String.prototype.trim?function(t){return t.trim()}:function(t){return t.replace(/^\s+|\s+$/g,"")},u=i.create("isotope",{layoutMode:"masonry",isJQueryFiltering:!0,sortAscending:!0});u.Item=n,u.LayoutMode=r;var c=u.prototype;c._create=function(){for(var t in this.itemGUID=0,this._sorters={},this._getSorters(),i.prototype._create.call(this),this.modes={},this.filteredItems=this.items,this.sortHistory=["original-order"],r.modes)this._initLayoutMode(t)},c.reloadItems=function(){this.itemGUID=0,i.prototype.reloadItems.call(this)},c._itemize=function(){for(var t=i.prototype._itemize.apply(this,arguments),e=0;e<t.length;e++){t[e].id=this.itemGUID++}return this._updateItemsSortData(t),t},c._initLayoutMode=function(t){var e=r.modes[t],i=this.options[t]||{};this.options[t]=e.options?s.extend(e.options,i):i,this.modes[t]=new e(this)},c.layout=function(){return!this._isLayoutInited&&this._getOption("initLayout")?void this.arrange():void this._layout()},c._layout=function(){var t=this._getIsInstant();this._resetLayout(),this._manageStamps(),this.layoutItems(this.filteredItems,t),this._isLayoutInited=!0},c.arrange=function(t){this.option(t),this._getIsInstant();var e=this._filter(this.items);this.filteredItems=e.matches,this._bindArrangeComplete(),this._isInstant?this._noTransition(this._hideReveal,[e]):this._hideReveal(e),this._sort(),this._layout()},c._init=c.arrange,c._hideReveal=function(t){this.reveal(t.needReveal),this.hide(t.needHide)},c._getIsInstant=function(){var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;return this._isInstant=e},c._bindArrangeComplete=function(){function t(){e&&i&&o&&n.dispatchEvent("arrangeComplete",null,[n.filteredItems])}var e,i,o,n=this;this.once("layoutComplete",function(){e=!0,t()}),this.once("hideComplete",function(){i=!0,t()}),this.once("revealComplete",function(){o=!0,t()})},c._filter=function(t){var e=this.options.filter;e=e||"*";for(var i=[],o=[],n=[],s=this._getFilterTest(e),r=0;r<t.length;r++){var a=t[r];if(!a.isIgnored){var h=s(a);h&&i.push(a),h&&a.isHidden?o.push(a):h||a.isHidden||n.push(a)}}return{matches:i,needReveal:o,needHide:n}},c._getFilterTest=function(e){return a&&this.options.isJQueryFiltering?function(t){return a(t.element).is(e)}:"function"==typeof e?function(t){return e(t.element)}:function(t){return o(t.element,e)}},c.updateSortData=function(t){var e;e=t?(t=s.makeArray(t),this.getItems(t)):this.items,this._getSorters(),this._updateItemsSortData(e)},c._getSorters=function(){var t=this.options.getSortData;for(var e in t){var i=t[e];this._sorters[e]=d(i)}},c._updateItemsSortData=function(t){for(var e=t&&t.length,i=0;e&&i<e;i++){t[i].updateSortData()}};var d=function(t){if("string"!=typeof t)return t;var e=h(t).split(" "),i=e[0],o=i.match(/^\[(.+)\]$/),n=function(e,i){return e?function(t){return t.getAttribute(e)}:function(t){var e=t.querySelector(i);return e&&e.textContent}}(o&&o[1],i),s=u.sortDataParsers[e[1]];return s?function(t){return t&&s(n(t))}:function(t){return t&&n(t)}};u.sortDataParsers={parseInt:function(t){return parseInt(t,10)},parseFloat:function(t){return parseFloat(t)}},c._sort=function(){if(this.options.sortBy){var t=s.makeArray(this.options.sortBy);this._getIsSameSortBy(t)||(this.sortHistory=t.concat(this.sortHistory));var e=function(r,a){return function(t,e){for(var i=0;i<r.length;i++){var o=r[i],n=t.sortData[o],s=e.sortData[o];if(s<n||n<s)return(s<n?1:-1)*((void 0!==a[o]?a[o]:a)?1:-1)}return 0}}(this.sortHistory,this.options.sortAscending);this.filteredItems.sort(e)}},c._getIsSameSortBy=function(t){for(var e=0;e<t.length;e++)if(t[e]!=this.sortHistory[e])return!1;return!0},c._mode=function(){var t=this.options.layoutMode,e=this.modes[t];if(!e)throw new Error("No layout mode: "+t);return e.options=this.options[t],e},c._resetLayout=function(){i.prototype._resetLayout.call(this),this._mode()._resetLayout()},c._getItemLayoutPosition=function(t){return this._mode()._getItemLayoutPosition(t)},c._manageStamp=function(t){this._mode()._manageStamp(t)},c._getContainerSize=function(){return this._mode()._getContainerSize()},c.needsResizeLayout=function(){return this._mode().needsResizeLayout()},c.appended=function(t){var e=this.addItems(t);if(e.length){var i=this._filterRevealAdded(e);this.filteredItems=this.filteredItems.concat(i)}},c.prepended=function(t){var e=this._itemize(t);if(e.length){this._resetLayout(),this._manageStamps();var i=this._filterRevealAdded(e);this.layoutItems(this.filteredItems),this.filteredItems=i.concat(this.filteredItems),this.items=e.concat(this.items)}},c._filterRevealAdded=function(t){var e=this._filter(t);return this.hide(e.needHide),this.reveal(e.matches),this.layoutItems(e.matches,!0),e.matches},c.insert=function(t){var e=this.addItems(t);if(e.length){var i,o,n=e.length;for(i=0;i<n;i++)o=e[i],this.element.appendChild(o.element);var s=this._filter(e).matches;for(i=0;i<n;i++)e[i].isLayoutInstant=!0;for(this.arrange(),i=0;i<n;i++)delete e[i].isLayoutInstant;this.reveal(s)}};var l=c.remove;return c.remove=function(t){t=s.makeArray(t);var e=this.getItems(t);l.call(this,t);for(var i=e&&e.length,o=0;i&&o<i;o++){var n=e[o];s.removeFrom(this.filteredItems,n)}},c.shuffle=function(){for(var t=0;t<this.items.length;t++){this.items[t].sortData.random=Math.random()}this.options.sortBy="random",this._sort(),this._layout()},c._noTransition=function(t,e){var i=this.options.transitionDuration;this.options.transitionDuration=0;var o=t.apply(this,e);return this.options.transitionDuration=i,o},c.getFilteredItemElements=function(){return this.filteredItems.map(function(t){return t.element})},u}),function(t,e){"function"==typeof define&&define.amd?define("packery/js/rect",e):"object"==typeof module&&module.exports?module.exports=e():(t.Packery=t.Packery||{},t.Packery.Rect=e())}(window,function(){function a(t){for(var e in a.defaults)this[e]=a.defaults[e];for(e in t)this[e]=t[e]}a.defaults={x:0,y:0,width:0,height:0};var t=a.prototype;return t.contains=function(t){var e=t.width||0,i=t.height||0;return this.x<=t.x&&this.y<=t.y&&this.x+this.width>=t.x+e&&this.y+this.height>=t.y+i},t.overlaps=function(t){var e=this.x+this.width,i=this.y+this.height,o=t.x+t.width,n=t.y+t.height;return this.x<o&&e>t.x&&this.y<n&&i>t.y},t.getMaximalFreeRects=function(t){if(!this.overlaps(t))return!1;var e,i=[],o=this.x+this.width,n=this.y+this.height,s=t.x+t.width,r=t.y+t.height;return this.y<t.y&&(e=new a({x:this.x,y:this.y,width:this.width,height:t.y-this.y}),i.push(e)),s<o&&(e=new a({x:s,y:this.y,width:o-s,height:this.height}),i.push(e)),r<n&&(e=new a({x:this.x,y:r,width:this.width,height:n-r}),i.push(e)),this.x<t.x&&(e=new a({x:this.x,y:this.y,width:t.x-this.x,height:this.height}),i.push(e)),i},t.canFit=function(t){return this.width>=t.width&&this.height>=t.height},a}),function(t,e){if("function"==typeof define&&define.amd)define("packery/js/packer",["./rect"],e);else if("object"==typeof module&&module.exports)module.exports=e(require("./rect"));else{var i=t.Packery=t.Packery||{};i.Packer=e(i.Rect)}}(window,function(e){function t(t,e,i){this.width=t||0,this.height=e||0,this.sortDirection=i||"downwardLeftToRight",this.reset()}var i=t.prototype;i.reset=function(){this.spaces=[];var t=new e({x:0,y:0,width:this.width,height:this.height});this.spaces.push(t),this.sorter=o[this.sortDirection]||o.downwardLeftToRight},i.pack=function(t){for(var e=0;e<this.spaces.length;e++){var i=this.spaces[e];if(i.canFit(t)){this.placeInSpace(t,i);break}}},i.columnPack=function(t){for(var e=0;e<this.spaces.length;e++){var i=this.spaces[e];if(i.x<=t.x&&i.x+i.width>=t.x+t.width&&i.height>=t.height-.01){t.y=i.y,this.placed(t);break}}},i.rowPack=function(t){for(var e=0;e<this.spaces.length;e++){var i=this.spaces[e];if(i.y<=t.y&&i.y+i.height>=t.y+t.height&&i.width>=t.width-.01){t.x=i.x,this.placed(t);break}}},i.placeInSpace=function(t,e){t.x=e.x,t.y=e.y,this.placed(t)},i.placed=function(t){for(var e=[],i=0;i<this.spaces.length;i++){var o=this.spaces[i],n=o.getMaximalFreeRects(t);n?e.push.apply(e,n):e.push(o)}this.spaces=e,this.mergeSortSpaces()},i.mergeSortSpaces=function(){t.mergeRects(this.spaces),this.spaces.sort(this.sorter)},i.addSpace=function(t){this.spaces.push(t),this.mergeSortSpaces()},t.mergeRects=function(t){var e=0,i=t[e];t:for(;i;){for(var o=0,n=t[e+o];n;){if(n==i)o++;else{if(n.contains(i)){t.splice(e,1),i=t[e];continue t}i.contains(n)?t.splice(e+o,1):o++}n=t[e+o]}i=t[++e]}return t};var o={downwardLeftToRight:function(t,e){return t.y-e.y||t.x-e.x},rightwardTopToBottom:function(t,e){return t.x-e.x||t.y-e.y}};return t}),function(t,e){"function"==typeof define&&define.amd?define("packery/js/item",["outlayer/outlayer","./rect"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("./rect")):t.Packery.Item=e(t.Outlayer,t.Packery.Rect)}(window,function(t,e){function i(){t.Item.apply(this,arguments)}var o="string"==typeof document.documentElement.style.transform?"transform":"WebkitTransform",n=i.prototype=Object.create(t.Item.prototype),s=n._create;n._create=function(){s.call(this),this.rect=new e};var r=n.moveTo;return n.moveTo=function(t,e){var i=Math.abs(this.position.x-t),o=Math.abs(this.position.y-e);return this.layout.dragItemCount&&!this.isPlacing&&!this.isTransitioning&&i<1&&o<1?void this.goTo(t,e):void r.apply(this,arguments)},n.enablePlacing=function(){this.removeTransitionStyles(),this.isTransitioning&&o&&(this.element.style[o]="none"),this.isTransitioning=!1,this.getSize(),this.layout._setRectSize(this.element,this.rect),this.isPlacing=!0},n.disablePlacing=function(){this.isPlacing=!1},n.removeElem=function(){this.element.parentNode.removeChild(this.element),this.layout.packer.addSpace(this.rect),this.emitEvent("remove",[this])},n.showDropPlaceholder=function(){var t=this.dropPlaceholder;t||((t=this.dropPlaceholder=document.createElement("div")).className="packery-drop-placeholder",t.style.position="absolute"),t.style.width=this.size.width+"px",t.style.height=this.size.height+"px",this.positionDropPlaceholder(),this.layout.element.appendChild(t)},n.positionDropPlaceholder=function(){this.dropPlaceholder.style[o]="translate("+this.rect.x+"px, "+this.rect.y+"px)"},n.hideDropPlaceholder=function(){this.layout.element.removeChild(this.dropPlaceholder)},i}),function(t,e){"function"==typeof define&&define.amd?define("packery/js/packery",["get-size/get-size","outlayer/outlayer","./rect","./packer","./item"],e):"object"==typeof module&&module.exports?module.exports=e(require("get-size"),require("outlayer"),require("./rect"),require("./packer"),require("./item")):t.Packery=e(t.getSize,t.Outlayer,t.Packery.Rect,t.Packery.Packer,t.Packery.Item)}(window,function(c,t,f,e,i){function o(t,e){return t.position.y-e.position.y||t.position.x-e.position.x}function n(t,e){return t.position.x-e.position.x||t.position.y-e.position.y}f.prototype.canFit=function(t){return this.width>=t.width-1&&this.height>=t.height-1};var s=t.create("packery");s.Item=i;var r=s.prototype;r._create=function(){t.prototype._create.call(this),this.packer=new e,this.shiftPacker=new e,this.isEnabled=!0,this.dragItemCount=0;var i=this;this.handleDraggabilly={dragStart:function(){i.itemDragStart(this.element)},dragMove:function(){i.itemDragMove(this.element,this.position.x,this.position.y)},dragEnd:function(){i.itemDragEnd(this.element)}},this.handleUIDraggable={start:function(t,e){e&&i.itemDragStart(t.currentTarget)},drag:function(t,e){e&&i.itemDragMove(t.currentTarget,e.position.left,e.position.top)},stop:function(t,e){e&&i.itemDragEnd(t.currentTarget)}}},r._resetLayout=function(){var t,e,i;this.getSize(),this._getMeasurements(),i=this._getOption("horizontal")?(t=1/0,e=this.size.innerHeight+this.gutter,"rightwardTopToBottom"):(t=this.size.innerWidth+this.gutter,e=1/0,"downwardLeftToRight"),this.packer.width=this.shiftPacker.width=t,this.packer.height=this.shiftPacker.height=e,this.packer.sortDirection=this.shiftPacker.sortDirection=i,this.packer.reset(),this.maxY=0,this.maxX=0},r._getMeasurements=function(){this._getMeasurement("columnWidth","width"),this._getMeasurement("rowHeight","height"),this._getMeasurement("gutter","width")},r._getItemLayoutPosition=function(t){if(this._setRectSize(t.element,t.rect),this.isShifting||0<this.dragItemCount){var e=this._getPackMethod();this.packer[e](t.rect)}else this.packer.pack(t.rect);return this._setMaxXY(t.rect),t.rect},r.shiftLayout=function(){this.isShifting=!0,this.layout(),delete this.isShifting},r._getPackMethod=function(){return this._getOption("horizontal")?"rowPack":"columnPack"},r._setMaxXY=function(t){this.maxX=Math.max(t.x+t.width,this.maxX),this.maxY=Math.max(t.y+t.height,this.maxY)},r._setRectSize=function(t,e){var i=c(t),o=i.outerWidth,n=i.outerHeight;(o||n)&&(o=this._applyGridGutter(o,this.columnWidth),n=this._applyGridGutter(n,this.rowHeight)),e.width=Math.min(o,this.packer.width),e.height=Math.min(n,this.packer.height)},r._applyGridGutter=function(t,e){if(!e)return t+this.gutter;var i=t%(e+=this.gutter);return Math[i&&i<1?"round":"ceil"](t/e)*e},r._getContainerSize=function(){return this._getOption("horizontal")?{width:this.maxX-this.gutter}:{height:this.maxY-this.gutter}},r._manageStamp=function(t){var e,i=this.getItem(t);if(i&&i.isPlacing)e=i.rect;else{var o=this._getElementOffset(t);e=new f({x:this._getOption("originLeft")?o.left:o.right,y:this._getOption("originTop")?o.top:o.bottom})}this._setRectSize(t,e),this.packer.placed(e),this._setMaxXY(e)},r.sortItemsByPosition=function(){var t=this._getOption("horizontal")?n:o;this.items.sort(t)},r.fit=function(t,e,i){var o=this.getItem(t);o&&(this.stamp(o.element),o.enablePlacing(),this.updateShiftTargets(o),e=void 0===e?o.rect.x:e,i=void 0===i?o.rect.y:i,this.shift(o,e,i),this._bindFitEvents(o),o.moveTo(o.rect.x,o.rect.y),this.shiftLayout(),this.unstamp(o.element),this.sortItemsByPosition(),o.disablePlacing())},r._bindFitEvents=function(t){function e(){2==++o&&i.dispatchEvent("fitComplete",null,[t])}var i=this,o=0;t.once("layout",e),this.once("layoutComplete",e)},r.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&(this.options.shiftPercentResize?this.resizeShiftPercentLayout():this.layout())},r.needsResizeLayout=function(){var t=c(this.element),e=this._getOption("horizontal")?"innerHeight":"innerWidth";return t[e]!=this.size[e]},r.resizeShiftPercentLayout=function(){var t=this._getItemsForLayout(this.items),e=this._getOption("horizontal"),i=e?"y":"x",o=e?"height":"width",n=e?"rowHeight":"columnWidth",s=e?"innerHeight":"innerWidth",r=this[n];if(r=r&&r+this.gutter){this._getMeasurements();var a=this[n]+this.gutter;t.forEach(function(t){var e=Math.round(t.rect[i]/r);t.rect[i]=e*a})}else{var h=c(this.element)[s]+this.gutter,u=this.packer[o];t.forEach(function(t){t.rect[i]=t.rect[i]/u*h})}this.shiftLayout()},r.itemDragStart=function(t){if(this.isEnabled){this.stamp(t);var e=this.getItem(t);e&&(e.enablePlacing(),e.showDropPlaceholder(),this.dragItemCount++,this.updateShiftTargets(e))}},r.updateShiftTargets=function(t){this.shiftPacker.reset(),this._getBoundingRect();var n=this._getOption("originLeft"),s=this._getOption("originTop");this.stamps.forEach(function(t){var e=this.getItem(t);if(!e||!e.isPlacing){var i=this._getElementOffset(t),o=new f({x:n?i.left:i.right,y:s?i.top:i.bottom});this._setRectSize(t,o),this.shiftPacker.placed(o)}},this);var h=this._getOption("horizontal"),e=h?"rowHeight":"columnWidth",u=h?"height":"width";this.shiftTargetKeys=[],this.shiftTargets=[];var c,d=this[e];if(d=d&&d+this.gutter){var i=Math.ceil(t.rect[u]/d),o=Math.floor((this.shiftPacker[u]+this.gutter)/d);c=(o-i)*d;for(var r=0;r<o;r++)this._addShiftTarget(r*d,0,c)}else c=this.shiftPacker[u]+this.gutter-t.rect[u],this._addShiftTarget(0,0,c);var a=this._getItemsForLayout(this.items),l=this._getPackMethod();a.forEach(function(t){var e=t.rect;this._setRectSize(t.element,e),this.shiftPacker[l](e),this._addShiftTarget(e.x,e.y,c);var i=h?e.x+e.width:e.x,o=h?e.y:e.y+e.height;if(this._addShiftTarget(i,o,c),d)for(var n=Math.round(e[u]/d),s=1;s<n;s++){var r=h?i:e.x+d*s,a=h?e.y+d*s:o;this._addShiftTarget(r,a,c)}},this)},r._addShiftTarget=function(t,e,i){var o=this._getOption("horizontal")?e:t;if(!(0!==o&&i<o)){var n=t+","+e;-1!=this.shiftTargetKeys.indexOf(n)||(this.shiftTargetKeys.push(n),this.shiftTargets.push({x:t,y:e}))}},r.shift=function(t,e,i){var o,n=1/0,s={x:e,y:i};this.shiftTargets.forEach(function(t){var e=function(t,e){var i=e.x-t.x,o=e.y-t.y;return Math.sqrt(i*i+o*o)}(t,s);e<n&&(o=t,n=e)}),t.rect.x=o.x,t.rect.y=o.y};r.itemDragMove=function(t,e,i){function o(){s.shift(n,e,i),n.positionDropPlaceholder(),s.layout()}var n=this.isEnabled&&this.getItem(t);if(n){e-=this.size.paddingLeft,i-=this.size.paddingTop;var s=this,r=new Date;this._itemDragTime&&r-this._itemDragTime<120?(clearTimeout(this.dragTimeout),this.dragTimeout=setTimeout(o,120)):(o(),this._itemDragTime=r)}},r.itemDragEnd=function(t){function e(){2==++o&&(i.element.classList.remove("is-positioning-post-drag"),i.hideDropPlaceholder(),n.dispatchEvent("dragItemPositioned",null,[i]))}var i=this.isEnabled&&this.getItem(t);if(i){clearTimeout(this.dragTimeout),i.element.classList.add("is-positioning-post-drag");var o=0,n=this;i.once("layout",e),this.once("layoutComplete",e),i.moveTo(i.rect.x,i.rect.y),this.layout(),this.dragItemCount=Math.max(0,this.dragItemCount-1),this.sortItemsByPosition(),i.disablePlacing(),this.unstamp(i.element)}},r.bindDraggabillyEvents=function(t){this._bindDraggabillyEvents(t,"on")},r.unbindDraggabillyEvents=function(t){this._bindDraggabillyEvents(t,"off")},r._bindDraggabillyEvents=function(t,e){var i=this.handleDraggabilly;t[e]("dragStart",i.dragStart),t[e]("dragMove",i.dragMove),t[e]("dragEnd",i.dragEnd)},r.bindUIDraggableEvents=function(t){this._bindUIDraggableEvents(t,"on")},r.unbindUIDraggableEvents=function(t){this._bindUIDraggableEvents(t,"off")},r._bindUIDraggableEvents=function(t,e){var i=this.handleUIDraggable;t[e]("dragstart",i.start)[e]("drag",i.drag)[e]("dragstop",i.stop)};var a=r.destroy;return r.destroy=function(){a.apply(this,arguments),this.isEnabled=!1},s.Rect=f,s.Packer=e,s}),function(t,e){"function"==typeof define&&define.amd?define(["isotope-layout/js/layout-mode","packery/js/packery"],e):"object"==typeof module&&module.exports?module.exports=e(require("isotope-layout/js/layout-mode"),require("packery")):e(t.Isotope.LayoutMode,t.Packery)}(window,function(t,e){var i=t.create("packery"),o=i.prototype,n={_getElementOffset:!0,_getMeasurement:!0};for(var s in e.prototype)n[s]||(o[s]=e.prototype[s]);var r=o._resetLayout;o._resetLayout=function(){this.packer=this.packer||new e.Packer,this.shiftPacker=this.shiftPacker||new e.Packer,r.apply(this,arguments)};var a=o._getItemLayoutPosition;o._getItemLayoutPosition=function(t){return t.rect=t.rect||new e.Rect,a.call(this,t)};var h=o.needsResizeLayout;o.needsResizeLayout=function(){return this._getOption("horizontal")?this.needsVerticalResizeLayout():h.call(this)};var u=o._getOption;return o._getOption=function(t){return"horizontal"==t?void 0!==this.options.isHorizontal?this.options.isHorizontal:this.options.horizontal:u.apply(this.isotope,arguments)},i});

// Ion.RangeSlider
// version 2.3.0 Build: 381
// © Denis Ineshin, 2018
// https://github.com/IonDen
//
// Project page:    http://ionden.com/a/plugins/ion.rangeSlider/en.html
// GitHub page:     https://github.com/IonDen/ion.rangeSlider
//
// Released under MIT licence:
// http://ionden.com/a/plugins/licence-en.html
!function(i){"function"==typeof define&&define.amd?define(["jquery"],function(t){return i(t,document,window,navigator)}):"object"==typeof exports?i(require("jquery"),document,window,navigator):i(jQuery,document,window,navigator)}(function(h,r,n,t,a){var i,s,o=0,e=(i=t.userAgent,s=/msie\s\d+/i,0<i.search(s)&&(i=(i=s.exec(i).toString()).split(" ")[1])<9&&(h("html").addClass("lt-ie9"),!0));Function.prototype.bind||(Function.prototype.bind=function(s){var o=this,e=[].slice;if("function"!=typeof o)throw new TypeError;var h=e.call(arguments,1),r=function(){if(this instanceof r){(t=function(){}).prototype=o.prototype;var t=new t,i=o.apply(t,h.concat(e.call(arguments)));return Object(i)===i?i:t}return o.apply(s,h.concat(e.call(arguments)))};return r}),Array.prototype.indexOf||(Array.prototype.indexOf=function(t,i){if(null==this)throw new TypeError('"this" is null or not defined');var s=Object(this),o=s.length>>>0;if(0==o)return-1;var e=+i||0;if(1/0===Math.abs(e)&&(e=0),o<=e)return-1;for(e=Math.max(0<=e?e:o-Math.abs(e),0);e<o;){if(e in s&&s[e]===t)return e;e++}return-1});function c(t,i,s){this.VERSION="2.2.0",this.input=t,this.plugin_count=s,this.old_to=this.old_from=this.update_tm=this.calc_count=this.current_plugin=0,this.raf_id=this.old_min_interval=null,this.no_diapason=this.force_redraw=this.dragging=!1,this.has_tab_index=!0,this.is_update=this.is_key=!1,this.is_start=!0,this.is_click=this.is_resize=this.is_active=this.is_finish=!1,i=i||{},this.$cache={win:h(n),body:h(r.body),input:h(t),cont:null,rs:null,min:null,max:null,from:null,to:null,single:null,bar:null,line:null,s_single:null,s_from:null,s_to:null,shad_single:null,shad_from:null,shad_to:null,edge:null,grid:null,grid_labels:[]},this.coords={x_gap:0,x_pointer:0,w_rs:0,w_rs_old:0,w_handle:0,p_gap:0,p_gap_left:0,p_gap_right:0,p_step:0,p_pointer:0,p_handle:0,p_single_fake:0,p_single_real:0,p_from_fake:0,p_from_real:0,p_to_fake:0,p_to_real:0,p_bar_x:0,p_bar_w:0,grid_gap:0,big_num:0,big:[],big_w:[],big_p:[],big_x:[]},this.labels={w_min:0,w_max:0,w_from:0,w_to:0,w_single:0,p_min:0,p_max:0,p_from_fake:0,p_from_left:0,p_to_fake:0,p_to_left:0,p_single_fake:0,p_single_left:0};var o,e=this.$cache.input;for(o in t=e.prop("value"),s={type:"single",min:10,max:100,from:null,to:null,step:1,min_interval:0,max_interval:0,drag_interval:!1,values:[],p_values:[],from_fixed:!1,from_min:null,from_max:null,from_shadow:!1,to_fixed:!1,to_min:null,to_max:null,to_shadow:!1,prettify_enabled:!0,prettify_separator:" ",prettify:null,force_edges:!1,keyboard:!0,grid:!1,grid_margin:!0,grid_num:4,grid_snap:!1,hide_min_max:!1,hide_from_to:!1,prefix:"",postfix:"",max_postfix:"",decorate_both:!0,values_separator:" — ",input_values_separator:";",disable:!1,block:!1,extra_classes:"",scope:null,onStart:null,onChange:null,onFinish:null,onUpdate:null},"INPUT"!==e[0].nodeName&&console&&console.warn&&console.warn("Base element should be <input>!",e[0]),(e={type:e.data("type"),min:e.data("min"),max:e.data("max"),from:e.data("from"),to:e.data("to"),step:e.data("step"),min_interval:e.data("minInterval"),max_interval:e.data("maxInterval"),drag_interval:e.data("dragInterval"),values:e.data("values"),from_fixed:e.data("fromFixed"),from_min:e.data("fromMin"),from_max:e.data("fromMax"),from_shadow:e.data("fromShadow"),to_fixed:e.data("toFixed"),to_min:e.data("toMin"),to_max:e.data("toMax"),to_shadow:e.data("toShadow"),prettify_enabled:e.data("prettifyEnabled"),prettify_separator:e.data("prettifySeparator"),force_edges:e.data("forceEdges"),keyboard:e.data("keyboard"),grid:e.data("grid"),grid_margin:e.data("gridMargin"),grid_num:e.data("gridNum"),grid_snap:e.data("gridSnap"),hide_min_max:e.data("hideMinMax"),hide_from_to:e.data("hideFromTo"),prefix:e.data("prefix"),postfix:e.data("postfix"),max_postfix:e.data("maxPostfix"),decorate_both:e.data("decorateBoth"),values_separator:e.data("valuesSeparator"),input_values_separator:e.data("inputValuesSeparator"),disable:e.data("disable"),block:e.data("block"),extra_classes:e.data("extraClasses")}).values=e.values&&e.values.split(","),e)e.hasOwnProperty(o)&&(e[o]!==a&&""!==e[o]||delete e[o]);t!==a&&""!==t&&((t=t.split(e.input_values_separator||i.input_values_separator||";"))[0]&&t[0]==+t[0]&&(t[0]=+t[0]),t[1]&&t[1]==+t[1]&&(t[1]=+t[1]),i&&i.values&&i.values.length?(s.from=t[0]&&i.values.indexOf(t[0]),s.to=t[1]&&i.values.indexOf(t[1])):(s.from=t[0]&&+t[0],s.to=t[1]&&+t[1])),h.extend(s,i),h.extend(s,e),this.options=s,this.update_check={},this.validate(),this.result={input:this.$cache.input,slider:null,min:this.options.min,max:this.options.max,from:this.options.from,from_percent:0,from_value:null,to:this.options.to,to_percent:0,to_value:null},this.init()}c.prototype={init:function(t){this.no_diapason=!1,this.coords.p_step=this.convertToPercent(this.options.step,!0),this.target="base",this.toggleInput(),this.append(),this.setMinMax(),t?(this.force_redraw=!0,this.calc(!0),this.callOnUpdate()):(this.force_redraw=!0,this.calc(!0),this.callOnStart()),this.updateScene()},append:function(){this.$cache.input.before('<span class="irs js-irs-'+this.plugin_count+" "+this.options.extra_classes+'"></span>'),this.$cache.input.prop("readonly",!0),this.$cache.cont=this.$cache.input.prev(),this.result.slider=this.$cache.cont,this.$cache.cont.html('<span class="irs"><span class="irs-line" tabindex="0"><span class="irs-line-left"></span><span class="irs-line-mid"></span><span class="irs-line-right"></span></span><span class="irs-min">0</span><span class="irs-max">1</span><span class="irs-from">0</span><span class="irs-to">0</span><span class="irs-single">0</span></span><span class="irs-grid"></span><span class="irs-bar"></span>'),this.$cache.rs=this.$cache.cont.find(".irs"),this.$cache.min=this.$cache.cont.find(".irs-min"),this.$cache.max=this.$cache.cont.find(".irs-max"),this.$cache.from=this.$cache.cont.find(".irs-from"),this.$cache.to=this.$cache.cont.find(".irs-to"),this.$cache.single=this.$cache.cont.find(".irs-single"),this.$cache.bar=this.$cache.cont.find(".irs-bar"),this.$cache.line=this.$cache.cont.find(".irs-line"),this.$cache.grid=this.$cache.cont.find(".irs-grid"),"single"===this.options.type?(this.$cache.cont.append('<span class="irs-bar-edge"></span><span class="irs-shadow shadow-single"></span><span class="irs-slider single"></span>'),this.$cache.edge=this.$cache.cont.find(".irs-bar-edge"),this.$cache.s_single=this.$cache.cont.find(".single"),this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.shad_single=this.$cache.cont.find(".shadow-single")):(this.$cache.cont.append('<span class="irs-shadow shadow-from"></span><span class="irs-shadow shadow-to"></span><span class="irs-slider from"></span><span class="irs-slider to"></span>'),this.$cache.s_from=this.$cache.cont.find(".from"),this.$cache.s_to=this.$cache.cont.find(".to"),this.$cache.shad_from=this.$cache.cont.find(".shadow-from"),this.$cache.shad_to=this.$cache.cont.find(".shadow-to"),this.setTopHandler()),this.options.hide_from_to&&(this.$cache.from[0].style.display="none",this.$cache.to[0].style.display="none",this.$cache.single[0].style.display="none"),this.appendGrid(),this.options.disable?(this.appendDisableMask(),this.$cache.input[0].disabled=!0):(this.$cache.input[0].disabled=!1,this.removeDisableMask(),this.bindEvents()),this.options.disable||(this.options.block?this.appendDisableMask():this.removeDisableMask()),this.options.drag_interval&&(this.$cache.bar[0].style.cursor="ew-resize")},setTopHandler:function(){var t=this.options.max,i=this.options.to;this.options.from>this.options.min&&i===t?this.$cache.s_from.addClass("type_last"):i<t&&this.$cache.s_to.addClass("type_last")},changeLevel:function(t){switch(t){case"single":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_single_fake),this.$cache.s_single.addClass("state_hover");break;case"from":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.$cache.s_from.addClass("state_hover"),this.$cache.s_from.addClass("type_last"),this.$cache.s_to.removeClass("type_last");break;case"to":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_to_fake),this.$cache.s_to.addClass("state_hover"),this.$cache.s_to.addClass("type_last"),this.$cache.s_from.removeClass("type_last");break;case"both":this.coords.p_gap_left=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.coords.p_gap_right=this.toFixed(this.coords.p_to_fake-this.coords.p_pointer),this.$cache.s_to.removeClass("type_last"),this.$cache.s_from.removeClass("type_last")}},appendDisableMask:function(){this.$cache.cont.append('<span class="irs-disable-mask"></span>'),this.$cache.cont.addClass("irs-disabled")},removeDisableMask:function(){this.$cache.cont.remove(".irs-disable-mask"),this.$cache.cont.removeClass("irs-disabled")},remove:function(){this.$cache.cont.remove(),this.$cache.cont=null,this.$cache.line.off("keydown.irs_"+this.plugin_count),this.$cache.body.off("touchmove.irs_"+this.plugin_count),this.$cache.body.off("mousemove.irs_"+this.plugin_count),this.$cache.win.off("touchend.irs_"+this.plugin_count),this.$cache.win.off("mouseup.irs_"+this.plugin_count),e&&(this.$cache.body.off("mouseup.irs_"+this.plugin_count),this.$cache.body.off("mouseleave.irs_"+this.plugin_count)),this.$cache.grid_labels=[],this.coords.big=[],this.coords.big_w=[],this.coords.big_p=[],this.coords.big_x=[],cancelAnimationFrame(this.raf_id)},bindEvents:function(){this.no_diapason||(this.$cache.body.on("touchmove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.body.on("mousemove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.win.on("touchend.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.win.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.line.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("focus.irs_"+this.plugin_count,this.pointerFocus.bind(this)),this.options.drag_interval&&"double"===this.options.type?(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"both")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"both"))):(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),"single"===this.options.type?(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.shad_single.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.edge.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_single.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))):(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),this.options.keyboard&&this.$cache.line.on("keydown.irs_"+this.plugin_count,this.key.bind(this,"keyboard")),e&&(this.$cache.body.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.body.on("mouseleave.irs_"+this.plugin_count,this.pointerUp.bind(this))))},pointerFocus:function(t){if(!this.target){var i="single"===this.options.type?this.$cache.single:this.$cache.from;t=i.offset().left,t+=i.width()/2-1,this.pointerClick("single",{preventDefault:function(){},pageX:t})}},pointerMove:function(t){this.dragging&&(this.coords.x_pointer=(t.pageX||t.originalEvent.touches&&t.originalEvent.touches[0].pageX)-this.coords.x_gap,this.calc())},pointerUp:function(t){this.current_plugin===this.plugin_count&&this.is_active&&(this.is_active=!1,this.$cache.cont.find(".state_hover").removeClass("state_hover"),this.force_redraw=!0,e&&h("*").prop("unselectable",!1),this.updateScene(),this.restoreOriginalMinInterval(),(h.contains(this.$cache.cont[0],t.target)||this.dragging)&&this.callOnFinish(),this.dragging=!1)},pointerDown:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&("both"===t&&this.setTempMinInterval(),t=t||(this.target||"from"),this.current_plugin=this.plugin_count,this.target=t,this.dragging=this.is_active=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=s-this.coords.x_gap,this.calcPointerPercent(),this.changeLevel(t),e&&h("*").prop("unselectable",!0),this.$cache.line.trigger("focus"),this.updateScene())},pointerClick:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&(this.current_plugin=this.plugin_count,this.target=t,this.is_click=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=+(s-this.coords.x_gap).toFixed(),this.force_redraw=!0,this.calc(),this.$cache.line.trigger("focus"))},key:function(t,i){if(!(this.current_plugin!==this.plugin_count||i.altKey||i.ctrlKey||i.shiftKey||i.metaKey)){switch(i.which){case 83:case 65:case 40:case 37:i.preventDefault(),this.moveByKey(!1);break;case 87:case 68:case 38:case 39:i.preventDefault(),this.moveByKey(!0)}return!0}},moveByKey:function(t){var i=this.coords.p_pointer,s=(this.options.max-this.options.min)/100;s=this.options.step/s;this.coords.x_pointer=this.toFixed(this.coords.w_rs/100*(t?i+s:i-s)),this.is_key=!0,this.calc()},setMinMax:function(){if(this.options)if(this.options.hide_min_max)this.$cache.min[0].style.display="none",this.$cache.max[0].style.display="none";else{if(this.options.values.length)this.$cache.min.html(this.decorate(this.options.p_values[this.options.min])),this.$cache.max.html(this.decorate(this.options.p_values[this.options.max]));else{var t=this._prettify(this.options.min),i=this._prettify(this.options.max);this.result.min_pretty=t,this.result.max_pretty=i,this.$cache.min.html(this.decorate(t,this.options.min)),this.$cache.max.html(this.decorate(i,this.options.max))}this.labels.w_min=this.$cache.min.outerWidth(!1),this.labels.w_max=this.$cache.max.outerWidth(!1)}},setTempMinInterval:function(){var t=this.result.to-this.result.from;null===this.old_min_interval&&(this.old_min_interval=this.options.min_interval),this.options.min_interval=t},restoreOriginalMinInterval:function(){null!==this.old_min_interval&&(this.options.min_interval=this.old_min_interval,this.old_min_interval=null)},calc:function(t){if(this.options&&(this.calc_count++,10!==this.calc_count&&!t||(this.calc_count=0,this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.calcHandlePercent()),this.coords.w_rs)){switch(this.calcPointerPercent(),t=this.getHandleX(),"both"===this.target&&(this.coords.p_gap=0,t=this.getHandleX()),"click"===this.target&&(this.coords.p_gap=this.coords.p_handle/2,t=this.getHandleX(),this.target=this.options.drag_interval?"both_one":this.chooseHandle(t)),this.target){case"base":var i=(this.options.max-this.options.min)/100;t=(this.result.from-this.options.min)/i,i=(this.result.to-this.options.min)/i,this.coords.p_single_real=this.toFixed(t),this.coords.p_from_real=this.toFixed(t),this.coords.p_to_real=this.toFixed(i),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real),this.target=null;break;case"single":if(this.options.from_fixed)break;this.coords.p_single_real=this.convertToRealPercent(t),this.coords.p_single_real=this.calcWithStep(this.coords.p_single_real),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real);break;case"from":if(this.options.from_fixed)break;this.coords.p_from_real=this.convertToRealPercent(t),this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real>this.coords.p_to_real&&(this.coords.p_from_real=this.coords.p_to_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_real=this.checkMaxInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real);break;case"to":if(this.options.to_fixed)break;this.coords.p_to_real=this.convertToRealPercent(t),this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real<this.coords.p_from_real&&(this.coords.p_to_real=this.coords.p_from_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_real=this.checkMaxInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both":if(this.options.from_fixed||this.options.to_fixed)break;t=this.toFixed(t+.001*this.coords.p_handle),this.coords.p_from_real=this.convertToRealPercent(t)-this.coords.p_gap_left,this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.convertToRealPercent(t)+this.coords.p_gap_right,this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both_one":if(!this.options.from_fixed&&!this.options.to_fixed){var s=this.convertToRealPercent(t),o=(t=this.result.to_percent-this.result.from_percent)/2;i=s-o,s=s+o;i<0&&(s=(i=0)+t),100<s&&(i=(s=100)-t),this.coords.p_from_real=this.calcWithStep(i),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.calcWithStep(s),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real)}}"single"===this.options.type?(this.coords.p_bar_x=this.coords.p_handle/2,this.coords.p_bar_w=this.coords.p_single_fake,this.result.from_percent=this.coords.p_single_real,this.result.from=this.convertToValue(this.coords.p_single_real),this.result.from_pretty=this._prettify(this.result.from),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from])):(this.coords.p_bar_x=this.toFixed(this.coords.p_from_fake+this.coords.p_handle/2),this.coords.p_bar_w=this.toFixed(this.coords.p_to_fake-this.coords.p_from_fake),this.result.from_percent=this.coords.p_from_real,this.result.from=this.convertToValue(this.coords.p_from_real),this.result.from_pretty=this._prettify(this.result.from),this.result.to_percent=this.coords.p_to_real,this.result.to=this.convertToValue(this.coords.p_to_real),this.result.to_pretty=this._prettify(this.result.to),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from],this.result.to_value=this.options.values[this.result.to])),this.calcMinMax(),this.calcLabels()}},calcPointerPercent:function(){this.coords.w_rs?(this.coords.x_pointer<0||isNaN(this.coords.x_pointer)?this.coords.x_pointer=0:this.coords.x_pointer>this.coords.w_rs&&(this.coords.x_pointer=this.coords.w_rs),this.coords.p_pointer=this.toFixed(this.coords.x_pointer/this.coords.w_rs*100)):this.coords.p_pointer=0},convertToRealPercent:function(t){return t/(100-this.coords.p_handle)*100},convertToFakePercent:function(t){return t/100*(100-this.coords.p_handle)},getHandleX:function(){var t=100-this.coords.p_handle,i=this.toFixed(this.coords.p_pointer-this.coords.p_gap);return i<0?i=0:t<i&&(i=t),i},calcHandlePercent:function(){this.coords.w_handle="single"===this.options.type?this.$cache.s_single.outerWidth(!1):this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100)},chooseHandle:function(t){return"single"===this.options.type?"single":t>=this.coords.p_from_real+(this.coords.p_to_real-this.coords.p_from_real)/2?this.options.to_fixed?"from":"to":this.options.from_fixed?"to":"from"},calcMinMax:function(){this.coords.w_rs&&(this.labels.p_min=this.labels.w_min/this.coords.w_rs*100,this.labels.p_max=this.labels.w_max/this.coords.w_rs*100)},calcLabels:function(){this.coords.w_rs&&!this.options.hide_from_to&&("single"===this.options.type?(this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=this.coords.p_single_fake+this.coords.p_handle/2-this.labels.p_single_fake/2):(this.labels.w_from=this.$cache.from.outerWidth(!1),this.labels.p_from_fake=this.labels.w_from/this.coords.w_rs*100,this.labels.p_from_left=this.coords.p_from_fake+this.coords.p_handle/2-this.labels.p_from_fake/2,this.labels.p_from_left=this.toFixed(this.labels.p_from_left),this.labels.p_from_left=this.checkEdges(this.labels.p_from_left,this.labels.p_from_fake),this.labels.w_to=this.$cache.to.outerWidth(!1),this.labels.p_to_fake=this.labels.w_to/this.coords.w_rs*100,this.labels.p_to_left=this.coords.p_to_fake+this.coords.p_handle/2-this.labels.p_to_fake/2,this.labels.p_to_left=this.toFixed(this.labels.p_to_left),this.labels.p_to_left=this.checkEdges(this.labels.p_to_left,this.labels.p_to_fake),this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=(this.labels.p_from_left+this.labels.p_to_left+this.labels.p_to_fake)/2-this.labels.p_single_fake/2,this.labels.p_single_left=this.toFixed(this.labels.p_single_left)),this.labels.p_single_left=this.checkEdges(this.labels.p_single_left,this.labels.p_single_fake))},updateScene:function(){this.raf_id&&(cancelAnimationFrame(this.raf_id),this.raf_id=null),clearTimeout(this.update_tm),this.update_tm=null,this.options&&(this.drawHandles(),this.is_active?this.raf_id=requestAnimationFrame(this.updateScene.bind(this)):this.update_tm=setTimeout(this.updateScene.bind(this),300))},drawHandles:function(){this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&(this.coords.w_rs!==this.coords.w_rs_old&&(this.target="base",this.is_resize=!0),this.coords.w_rs===this.coords.w_rs_old&&!this.force_redraw||(this.setMinMax(),this.calc(!0),this.drawLabels(),this.options.grid&&(this.calcGridMargin(),this.calcGridLabels()),this.force_redraw=!0,this.coords.w_rs_old=this.coords.w_rs,this.drawShadow()),this.coords.w_rs&&(this.dragging||this.force_redraw||this.is_key)&&((this.old_from!==this.result.from||this.old_to!==this.result.to||this.force_redraw||this.is_key)&&(this.drawLabels(),this.$cache.bar[0].style.left=this.coords.p_bar_x+"%",this.$cache.bar[0].style.width=this.coords.p_bar_w+"%","single"===this.options.type?this.$cache.s_single[0].style.left=this.coords.p_single_fake+"%":(this.$cache.s_from[0].style.left=this.coords.p_from_fake+"%",this.$cache.s_to[0].style.left=this.coords.p_to_fake+"%",this.old_from===this.result.from&&!this.force_redraw||(this.$cache.from[0].style.left=this.labels.p_from_left+"%"),this.old_to===this.result.to&&!this.force_redraw||(this.$cache.to[0].style.left=this.labels.p_to_left+"%")),this.$cache.single[0].style.left=this.labels.p_single_left+"%",this.writeToInput(),this.old_from===this.result.from&&this.old_to===this.result.to||this.is_start||(this.$cache.input.trigger("change"),this.$cache.input.trigger("input")),this.old_from=this.result.from,this.old_to=this.result.to,this.is_resize||this.is_update||this.is_start||this.is_finish||this.callOnChange(),(this.is_key||this.is_click)&&(this.is_click=this.is_key=!1,this.callOnFinish()),this.is_finish=this.is_resize=this.is_update=!1),this.force_redraw=this.is_click=this.is_key=this.is_start=!1))},drawLabels:function(){if(this.options){var t=this.options.values.length,i=this.options.p_values;if(!this.options.hide_from_to)if("single"===this.options.type){if(t)t=this.decorate(i[this.result.from]);else{var s=this._prettify(this.result.from);t=this.decorate(s,this.result.from)}this.$cache.single.html(t),this.calcLabels(),this.$cache.min[0].style.visibility=this.labels.p_single_left<this.labels.p_min+1?"hidden":"visible",this.$cache.max[0].style.visibility=this.labels.p_single_left+this.labels.p_single_fake>100-this.labels.p_max-1?"hidden":"visible"}else{i=t?(this.options.decorate_both?(t=this.decorate(i[this.result.from]),t+=this.options.values_separator,t+=this.decorate(i[this.result.to])):t=this.decorate(i[this.result.from]+this.options.values_separator+i[this.result.to]),s=this.decorate(i[this.result.from]),this.decorate(i[this.result.to])):(s=this._prettify(this.result.from),i=this._prettify(this.result.to),this.options.decorate_both?(t=this.decorate(s,this.result.from),t+=this.options.values_separator,t+=this.decorate(i,this.result.to)):t=this.decorate(s+this.options.values_separator+i,this.result.to),s=this.decorate(s,this.result.from),this.decorate(i,this.result.to)),this.$cache.single.html(t),this.$cache.from.html(s),this.$cache.to.html(i),this.calcLabels(),t=Math.min(this.labels.p_single_left,this.labels.p_from_left),s=this.labels.p_single_left+this.labels.p_single_fake;i=this.labels.p_to_left+this.labels.p_to_fake;var o=Math.max(s,i);this.labels.p_from_left+this.labels.p_from_fake>=this.labels.p_to_left?(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",o=this.result.from===this.result.to?("from"===this.target?this.$cache.from[0].style.visibility="visible":"to"===this.target?this.$cache.to[0].style.visibility="visible":this.target||(this.$cache.from[0].style.visibility="visible"),this.$cache.single[0].style.visibility="hidden",i):(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",Math.max(s,i))):(this.$cache.from[0].style.visibility="visible",this.$cache.to[0].style.visibility="visible",this.$cache.single[0].style.visibility="hidden"),this.$cache.min[0].style.visibility=t<this.labels.p_min+1?"hidden":"visible",this.$cache.max[0].style.visibility=o>100-this.labels.p_max-1?"hidden":"visible"}}},drawShadow:function(){var t=this.options,i=this.$cache,s="number"==typeof t.from_min&&!isNaN(t.from_min),o="number"==typeof t.from_max&&!isNaN(t.from_max),e="number"==typeof t.to_min&&!isNaN(t.to_min),h="number"==typeof t.to_max&&!isNaN(t.to_max);"single"===t.type?t.from_shadow&&(s||o)?(s=this.convertToPercent(s?t.from_min:t.min),o=this.convertToPercent(o?t.from_max:t.max)-s,s=this.toFixed(s-this.coords.p_handle/100*s),o=this.toFixed(o-this.coords.p_handle/100*o),s+=this.coords.p_handle/2,i.shad_single[0].style.display="block",i.shad_single[0].style.left=s+"%",i.shad_single[0].style.width=o+"%"):i.shad_single[0].style.display="none":(t.from_shadow&&(s||o)?(s=this.convertToPercent(s?t.from_min:t.min),o=this.convertToPercent(o?t.from_max:t.max)-s,s=this.toFixed(s-this.coords.p_handle/100*s),o=this.toFixed(o-this.coords.p_handle/100*o),s+=this.coords.p_handle/2,i.shad_from[0].style.display="block",i.shad_from[0].style.left=s+"%",i.shad_from[0].style.width=o+"%"):i.shad_from[0].style.display="none",t.to_shadow&&(e||h)?(e=this.convertToPercent(e?t.to_min:t.min),t=this.convertToPercent(h?t.to_max:t.max)-e,e=this.toFixed(e-this.coords.p_handle/100*e),t=this.toFixed(t-this.coords.p_handle/100*t),e+=this.coords.p_handle/2,i.shad_to[0].style.display="block",i.shad_to[0].style.left=e+"%",i.shad_to[0].style.width=t+"%"):i.shad_to[0].style.display="none")},writeToInput:function(){"single"===this.options.type?(this.options.values.length?this.$cache.input.prop("value",this.result.from_value):this.$cache.input.prop("value",this.result.from),this.$cache.input.data("from",this.result.from)):(this.options.values.length?this.$cache.input.prop("value",this.result.from_value+this.options.input_values_separator+this.result.to_value):this.$cache.input.prop("value",this.result.from+this.options.input_values_separator+this.result.to),this.$cache.input.data("from",this.result.from),this.$cache.input.data("to",this.result.to))},callOnStart:function(){this.writeToInput(),this.options.onStart&&"function"==typeof this.options.onStart&&(this.options.scope?this.options.onStart.call(this.options.scope,this.result):this.options.onStart(this.result))},callOnChange:function(){this.writeToInput(),this.options.onChange&&"function"==typeof this.options.onChange&&(this.options.scope?this.options.onChange.call(this.options.scope,this.result):this.options.onChange(this.result))},callOnFinish:function(){this.writeToInput(),this.options.onFinish&&"function"==typeof this.options.onFinish&&(this.options.scope?this.options.onFinish.call(this.options.scope,this.result):this.options.onFinish(this.result))},callOnUpdate:function(){this.writeToInput(),this.options.onUpdate&&"function"==typeof this.options.onUpdate&&(this.options.scope?this.options.onUpdate.call(this.options.scope,this.result):this.options.onUpdate(this.result))},toggleInput:function(){this.$cache.input.toggleClass("irs-hidden-input"),this.has_tab_index?this.$cache.input.prop("tabindex",-1):this.$cache.input.removeProp("tabindex"),this.has_tab_index=!this.has_tab_index},convertToPercent:function(t,i){var s=this.options.max-this.options.min;return s?this.toFixed((i?t:t-this.options.min)/(s/100)):(this.no_diapason=!0,0)},convertToValue:function(t){var i,s,o=this.options.min,e=this.options.max,h=o.toString().split(".")[1],r=e.toString().split(".")[1],n=0,a=0;return 0===t?this.options.min:100===t?this.options.max:(h&&(n=i=h.length),r&&(n=s=r.length),i&&s&&(n=s<=i?i:s),o<0&&(o=+(o+(a=Math.abs(o))).toFixed(n),e=+(e+a).toFixed(n)),t=(e-o)/100*t+o,t=(o=this.options.step.toString().split(".")[1])?+t.toFixed(o.length):(t/=this.options.step,+(t*=this.options.step).toFixed(0)),a&&(t-=a),(a=o?+t.toFixed(o.length):this.toFixed(t))<this.options.min?a=this.options.min:a>this.options.max&&(a=this.options.max),a)},calcWithStep:function(t){var i=Math.round(t/this.coords.p_step)*this.coords.p_step;return 100<i&&(i=100),100===t&&(i=100),this.toFixed(i)},checkMinInterval:function(t,i,s){var o=this.options;return o.min_interval?(t=this.convertToValue(t),i=this.convertToValue(i),"from"===s?i-t<o.min_interval&&(t=i-o.min_interval):t-i<o.min_interval&&(t=i+o.min_interval),this.convertToPercent(t)):t},checkMaxInterval:function(t,i,s){var o=this.options;return o.max_interval?(t=this.convertToValue(t),i=this.convertToValue(i),"from"===s?i-t>o.max_interval&&(t=i-o.max_interval):t-i>o.max_interval&&(t=i+o.max_interval),this.convertToPercent(t)):t},checkDiapason:function(t,i,s){t=this.convertToValue(t);var o=this.options;return"number"!=typeof i&&(i=o.min),"number"!=typeof s&&(s=o.max),t<i&&(t=i),s<t&&(t=s),this.convertToPercent(t)},toFixed:function(t){return+(t=t.toFixed(20))},_prettify:function(t){return this.options.prettify_enabled?this.options.prettify&&"function"==typeof this.options.prettify?this.options.prettify(t):this.prettify(t):t},prettify:function(t){return t.toString().replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g,"$1"+this.options.prettify_separator)},checkEdges:function(t,i){return this.options.force_edges&&(t<0?t=0:100-i<t&&(t=100-i)),this.toFixed(t)},validate:function(){var t,i=this.options,s=this.result,o=i.values,e=o.length;if("string"==typeof i.min&&(i.min=+i.min),"string"==typeof i.max&&(i.max=+i.max),"string"==typeof i.from&&(i.from=+i.from),"string"==typeof i.to&&(i.to=+i.to),"string"==typeof i.step&&(i.step=+i.step),"string"==typeof i.from_min&&(i.from_min=+i.from_min),"string"==typeof i.from_max&&(i.from_max=+i.from_max),"string"==typeof i.to_min&&(i.to_min=+i.to_min),"string"==typeof i.to_max&&(i.to_max=+i.to_max),"string"==typeof i.grid_num&&(i.grid_num=+i.grid_num),i.max<i.min&&(i.max=i.min),e)for(i.p_values=[],i.min=0,i.max=e-1,i.step=1,i.grid_num=i.max,i.grid_snap=!0,t=0;t<e;t++){var h=+o[t];h=isNaN(h)?o[t]:(o[t]=h,this._prettify(h)),i.p_values.push(h)}"number"==typeof i.from&&!isNaN(i.from)||(i.from=i.min),"number"==typeof i.to&&!isNaN(i.to)||(i.to=i.max),"single"===i.type?(i.from<i.min&&(i.from=i.min),i.from>i.max&&(i.from=i.max)):(i.from<i.min&&(i.from=i.min),i.from>i.max&&(i.from=i.max),i.to<i.min&&(i.to=i.min),i.to>i.max&&(i.to=i.max),this.update_check.from&&(this.update_check.from!==i.from&&i.from>i.to&&(i.from=i.to),this.update_check.to!==i.to&&i.to<i.from&&(i.to=i.from)),i.from>i.to&&(i.from=i.to),i.to<i.from&&(i.to=i.from)),("number"!=typeof i.step||isNaN(i.step)||!i.step||i.step<0)&&(i.step=1),"number"==typeof i.from_min&&i.from<i.from_min&&(i.from=i.from_min),"number"==typeof i.from_max&&i.from>i.from_max&&(i.from=i.from_max),"number"==typeof i.to_min&&i.to<i.to_min&&(i.to=i.to_min),"number"==typeof i.to_max&&i.from>i.to_max&&(i.to=i.to_max),s&&(s.min!==i.min&&(s.min=i.min),s.max!==i.max&&(s.max=i.max),(s.from<s.min||s.from>s.max)&&(s.from=i.from),(s.to<s.min||s.to>s.max)&&(s.to=i.to)),("number"!=typeof i.min_interval||isNaN(i.min_interval)||!i.min_interval||i.min_interval<0)&&(i.min_interval=0),("number"!=typeof i.max_interval||isNaN(i.max_interval)||!i.max_interval||i.max_interval<0)&&(i.max_interval=0),i.min_interval&&i.min_interval>i.max-i.min&&(i.min_interval=i.max-i.min),i.max_interval&&i.max_interval>i.max-i.min&&(i.max_interval=i.max-i.min)},decorate:function(t,i){var s="",o=this.options;return o.prefix&&(s+=o.prefix),s+=t,o.max_postfix&&(o.values.length&&t===o.p_values[o.max]?(s+=o.max_postfix,o.postfix&&(s+=" ")):i===o.max&&(s+=o.max_postfix,o.postfix&&(s+=" "))),o.postfix&&(s+=o.postfix),s},updateFrom:function(){this.result.from=this.options.from,this.result.from_percent=this.convertToPercent(this.result.from),this.result.from_pretty=this._prettify(this.result.from),this.options.values&&(this.result.from_value=this.options.values[this.result.from])},updateTo:function(){this.result.to=this.options.to,this.result.to_percent=this.convertToPercent(this.result.to),this.result.to_pretty=this._prettify(this.result.to),this.options.values&&(this.result.to_value=this.options.values[this.result.to])},updateResult:function(){this.result.min=this.options.min,this.result.max=this.options.max,this.updateFrom(),this.updateTo()},appendGrid:function(){if(this.options.grid){var t,i=this.options,s=i.max-i.min,o=i.grid_num,e=4,h="";if(this.calcGridMargin(),i.grid_snap)if(50<s){o=50/i.step;var r=this.toFixed(i.step/.5)}else o=s/i.step,r=this.toFixed(i.step/(s/100));else r=this.toFixed(100/o);for(4<o&&(e=3),7<o&&(e=2),14<o&&(e=1),28<o&&(e=0),s=0;s<o+1;s++){var n=e,a=this.toFixed(r*s);100<a&&(a=100);var c=((this.coords.big[s]=a)-r*(s-1))/(n+1);for(t=1;t<=n&&0!==a;t++){h+='<span class="irs-grid-pol small" style="left: '+this.toFixed(a-c*t)+'%"></span>'}h+='<span class="irs-grid-pol" style="left: '+a+'%"></span>',t=this.convertToValue(a),h+='<span class="irs-grid-text js-grid-text-'+s+'" style="left: '+a+'%">'+(t=i.values.length?i.p_values[t]:this._prettify(t))+"</span>"}this.coords.big_num=Math.ceil(o+1),this.$cache.cont.addClass("irs-with-grid"),this.$cache.grid.html(h),this.cacheGridLabels()}},cacheGridLabels:function(){var t,i=this.coords.big_num;for(t=0;t<i;t++){var s=this.$cache.grid.find(".js-grid-text-"+t);this.$cache.grid_labels.push(s)}this.calcGridLabels()},calcGridLabels:function(){var t,i=[],s=[],o=this.coords.big_num;for(t=0;t<o;t++)this.coords.big_w[t]=this.$cache.grid_labels[t].outerWidth(!1),this.coords.big_p[t]=this.toFixed(this.coords.big_w[t]/this.coords.w_rs*100),this.coords.big_x[t]=this.toFixed(this.coords.big_p[t]/2),i[t]=this.toFixed(this.coords.big[t]-this.coords.big_x[t]),s[t]=this.toFixed(i[t]+this.coords.big_p[t]);for(this.options.force_edges&&(i[0]<-this.coords.grid_gap&&(i[0]=-this.coords.grid_gap,s[0]=this.toFixed(i[0]+this.coords.big_p[0]),this.coords.big_x[0]=this.coords.grid_gap),s[o-1]>100+this.coords.grid_gap&&(s[o-1]=100+this.coords.grid_gap,i[o-1]=this.toFixed(s[o-1]-this.coords.big_p[o-1]),this.coords.big_x[o-1]=this.toFixed(this.coords.big_p[o-1]-this.coords.grid_gap))),this.calcGridCollision(2,i,s),this.calcGridCollision(4,i,s),t=0;t<o;t++)i=this.$cache.grid_labels[t][0],this.coords.big_x[t]!==Number.POSITIVE_INFINITY&&(i.style.marginLeft=-this.coords.big_x[t]+"%")},calcGridCollision:function(t,i,s){var o,e=this.coords.big_num;for(o=0;o<e;o+=t){var h=o+t/2;if(e<=h)break;this.$cache.grid_labels[h][0].style.visibility=s[o]<=i[h]?"visible":"hidden"}},calcGridMargin:function(){this.options.grid_margin&&(this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&(this.coords.w_handle="single"===this.options.type?this.$cache.s_single.outerWidth(!1):this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100),this.coords.grid_gap=this.toFixed(this.coords.p_handle/2-.1),this.$cache.grid[0].style.width=this.toFixed(100-this.coords.p_handle)+"%",this.$cache.grid[0].style.left=this.coords.grid_gap+"%"))},update:function(t){this.input&&(this.is_update=!0,this.options.from=this.result.from,this.options.to=this.result.to,this.update_check.from=this.result.from,this.update_check.to=this.result.to,this.options=h.extend(this.options,t),this.validate(),this.updateResult(t),this.toggleInput(),this.remove(),this.init(!0))},reset:function(){this.input&&(this.updateResult(),this.update())},destroy:function(){this.input&&(this.toggleInput(),this.$cache.input.prop("readonly",!1),h.data(this.input,"ionRangeSlider",null),this.remove(),this.options=this.input=null)}},h.fn.ionRangeSlider=function(t){return this.each(function(){h.data(this,"ionRangeSlider")||h.data(this,"ionRangeSlider",new c(this,t,o++))})},function(){for(var h=0,t=["ms","moz","webkit","o"],i=0;i<t.length&&!n.requestAnimationFrame;++i)n.requestAnimationFrame=n[t[i]+"RequestAnimationFrame"],n.cancelAnimationFrame=n[t[i]+"CancelAnimationFrame"]||n[t[i]+"CancelRequestAnimationFrame"];n.requestAnimationFrame||(n.requestAnimationFrame=function(t,i){var s=(new Date).getTime(),o=Math.max(0,16-(s-h)),e=n.setTimeout(function(){t(s+o)},o);return h=s+o,e}),n.cancelAnimationFrame||(n.cancelAnimationFrame=function(t){clearTimeout(t)})}()});

/**
 * History.js Core
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @copyright 2010-2011 Benjamin Arthur Lupton <contact@balupton.com>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 */
//(function(e,t){"use strict";var n=e.History=e.History||{};if(typeof n.Adapter!="undefined")throw new Error("History.js Adapter has already been loaded...");n.Adapter={handlers:{},_uid:1,uid:function(e){return e._uid||(e._uid=n.Adapter._uid++)},bind:function(e,t,r){var i=n.Adapter.uid(e);n.Adapter.handlers[i]=n.Adapter.handlers[i]||{},n.Adapter.handlers[i][t]=n.Adapter.handlers[i][t]||[],n.Adapter.handlers[i][t].push(r),e["on"+t]=function(e,t){return function(r){n.Adapter.trigger(e,t,r)}}(e,t)},trigger:function(e,t,r){r=r||{};var i=n.Adapter.uid(e),s,o;n.Adapter.handlers[i]=n.Adapter.handlers[i]||{},n.Adapter.handlers[i][t]=n.Adapter.handlers[i][t]||[];for(s=0,o=n.Adapter.handlers[i][t].length;s<o;++s)n.Adapter.handlers[i][t][s].apply(this,[r])},extractEventData:function(e,n){var r=n&&n[e]||t;return r},onDomLoad:function(t){var n=e.setTimeout(function(){t()},2e3);e.onload=function(){clearTimeout(n),t()}}},typeof n.init!="undefined"&&n.init()})(window),function(e,t){"use strict";var n=e.console||t,r=e.document,i=e.navigator,s=!1,o=e.setTimeout,u=e.clearTimeout,a=e.setInterval,f=e.clearInterval,l=e.JSON,c=e.alert,h=e.History=e.History||{},p=e.history;try{s=e.sessionStorage,s.setItem("TEST","1"),s.removeItem("TEST")}catch(d){s=!1}l.stringify=l.stringify||l.encode,l.parse=l.parse||l.decode;if(typeof h.init!="undefined")throw new Error("History.js Core has already been loaded...");h.init=function(e){return typeof h.Adapter=="undefined"?!1:(typeof h.initCore!="undefined"&&h.initCore(),typeof h.initHtml4!="undefined"&&h.initHtml4(),!0)},h.initCore=function(d){if(typeof h.initCore.initialized!="undefined")return!1;h.initCore.initialized=!0,h.options=h.options||{},h.options.hashChangeInterval=h.options.hashChangeInterval||100,h.options.safariPollInterval=h.options.safariPollInterval||500,h.options.doubleCheckInterval=h.options.doubleCheckInterval||500,h.options.disableSuid=h.options.disableSuid||!1,h.options.storeInterval=h.options.storeInterval||1e3,h.options.busyDelay=h.options.busyDelay||250,h.options.debug=h.options.debug||!1,h.options.initialTitle=h.options.initialTitle||r.title,h.options.html4Mode=h.options.html4Mode||!1,h.options.delayInit=h.options.delayInit||!1,h.intervalList=[],h.clearAllIntervals=function(){var e,t=h.intervalList;if(typeof t!="undefined"&&t!==null){for(e=0;e<t.length;e++)f(t[e]);h.intervalList=null}},h.debug=function(){(h.options.debug||!1)&&h.log.apply(h,arguments)},h.log=function(){var e=typeof n!="undefined"&&typeof n.log!="undefined"&&typeof n.log.apply!="undefined",t=r.getElementById("log"),i,s,o,u,a;e?(u=Array.prototype.slice.call(arguments),i=u.shift(),typeof n.debug!="undefined"?n.debug.apply(n,[i,u]):n.log.apply(n,[i,u])):i="\n"+arguments[0]+"\n";for(s=1,o=arguments.length;s<o;++s){a=arguments[s];if(typeof a=="object"&&typeof l!="undefined")try{a=l.stringify(a)}catch(f){}i+="\n"+a+"\n"}return t?(t.value+=i+"\n-----\n",t.scrollTop=t.scrollHeight-t.clientHeight):e||c(i),!0},h.getInternetExplorerMajorVersion=function(){var e=h.getInternetExplorerMajorVersion.cached=typeof h.getInternetExplorerMajorVersion.cached!="undefined"?h.getInternetExplorerMajorVersion.cached:function(){var e=3,t=r.createElement("div"),n=t.getElementsByTagName("i");while((t.innerHTML="<!--[if gt IE "+ ++e+"]><i></i><![endif]-->")&&n[0]);return e>4?e:!1}();return e},h.isInternetExplorer=function(){var e=h.isInternetExplorer.cached=typeof h.isInternetExplorer.cached!="undefined"?h.isInternetExplorer.cached:Boolean(h.getInternetExplorerMajorVersion());return e},h.options.html4Mode?h.emulated={pushState:!0,hashChange:!0}:h.emulated={pushState:!Boolean(e.history&&e.history.pushState&&e.history.replaceState&&!/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i.test(i.userAgent)&&!/AppleWebKit\/5([0-2]|3[0-2])/i.test(i.userAgent)),hashChange:Boolean(!("onhashchange"in e||"onhashchange"in r)||h.isInternetExplorer()&&h.getInternetExplorerMajorVersion()<8)},h.enabled=!h.emulated.pushState,h.bugs={setHash:Boolean(!h.emulated.pushState&&i.vendor==="Apple Computer, Inc."&&/AppleWebKit\/5([0-2]|3[0-3])/.test(i.userAgent)),safariPoll:Boolean(!h.emulated.pushState&&i.vendor==="Apple Computer, Inc."&&/AppleWebKit\/5([0-2]|3[0-3])/.test(i.userAgent)),ieDoubleCheck:Boolean(h.isInternetExplorer()&&h.getInternetExplorerMajorVersion()<8),hashEscape:Boolean(h.isInternetExplorer()&&h.getInternetExplorerMajorVersion()<7)},h.isEmptyObject=function(e){for(var t in e)if(e.hasOwnProperty(t))return!1;return!0},h.cloneObject=function(e){var t,n;return e?(t=l.stringify(e),n=l.parse(t)):n={},n},h.getRootUrl=function(){var e=r.location.protocol+"//"+(r.location.hostname||r.location.host);if(r.location.port||!1)e+=":"+r.location.port;return e+="/",e},h.getBaseHref=function(){var e=r.getElementsByTagName("base"),t=null,n="";return e.length===1&&(t=e[0],n=t.href.replace(/[^\/]+$/,"")),n=n.replace(/\/+$/,""),n&&(n+="/"),n},h.getBaseUrl=function(){var e=h.getBaseHref()||h.getBasePageUrl()||h.getRootUrl();return e},h.getPageUrl=function(){var e=h.getState(!1,!1),t=(e||{}).url||h.getLocationHref(),n;return n=t.replace(/\/+$/,"").replace(/[^\/]+$/,function(e,t,n){return/\./.test(e)?e:e+"/"}),n},h.getBasePageUrl=function(){var e=h.getLocationHref().replace(/[#\?].*/,"").replace(/[^\/]+$/,function(e,t,n){return/[^\/]$/.test(e)?"":e}).replace(/\/+$/,"")+"/";return e},h.getFullUrl=function(e,t){var n=e,r=e.substring(0,1);return t=typeof t=="undefined"?!0:t,/[a-z]+\:\/\//.test(e)||(r==="/"?n=h.getRootUrl()+e.replace(/^\/+/,""):r==="#"?n=h.getPageUrl().replace(/#.*/,"")+e:r==="?"?n=h.getPageUrl().replace(/[\?#].*/,"")+e:t?n=h.getBaseUrl()+e.replace(/^(\.\/)+/,""):n=h.getBasePageUrl()+e.replace(/^(\.\/)+/,"")),n.replace(/\#$/,"")},h.getShortUrl=function(e){var t=e,n=h.getBaseUrl(),r=h.getRootUrl();return h.emulated.pushState&&(t=t.replace(n,"")),t=t.replace(r,"/"),h.isTraditionalAnchor(t)&&(t="./"+t),t=t.replace(/^(\.\/)+/g,"./").replace(/\#$/,""),t},h.getLocationHref=function(e){return e=e||r,e.URL===e.location.href?e.location.href:e.location.href===decodeURIComponent(e.URL)?e.URL:e.location.hash&&decodeURIComponent(e.location.href.replace(/^[^#]+/,""))===e.location.hash?e.location.href:e.URL.indexOf("#")==-1&&e.location.href.indexOf("#")!=-1?e.location.href:e.URL||e.location.href},h.store={},h.idToState=h.idToState||{},h.stateToId=h.stateToId||{},h.urlToId=h.urlToId||{},h.storedStates=h.storedStates||[],h.savedStates=h.savedStates||[],h.normalizeStore=function(){h.store.idToState=h.store.idToState||{},h.store.urlToId=h.store.urlToId||{},h.store.stateToId=h.store.stateToId||{}},h.getState=function(e,t){typeof e=="undefined"&&(e=!0),typeof t=="undefined"&&(t=!0);var n=h.getLastSavedState();return!n&&t&&(n=h.createStateObject()),e&&(n=h.cloneObject(n),n.url=n.cleanUrl||n.url),n},h.getIdByState=function(e){var t=h.extractId(e.url),n;if(!t){n=h.getStateString(e);if(typeof h.stateToId[n]!="undefined")t=h.stateToId[n];else if(typeof h.store.stateToId[n]!="undefined")t=h.store.stateToId[n];else{for(;;){t=(new Date).getTime()+String(Math.random()).replace(/\D/g,"");if(typeof h.idToState[t]=="undefined"&&typeof h.store.idToState[t]=="undefined")break}h.stateToId[n]=t,h.idToState[t]=e}}return t},h.normalizeState=function(e){var t,n;if(!e||typeof e!="object")e={};if(typeof e.normalized!="undefined")return e;if(!e.data||typeof e.data!="object")e.data={};return t={},t.normalized=!0,t.title=e.title||"",t.url=h.getFullUrl(e.url?e.url:h.getLocationHref()),t.hash=h.getShortUrl(t.url),t.data=h.cloneObject(e.data),t.id=h.getIdByState(t),t.cleanUrl=t.url.replace(/\??\&_suid.*/,""),t.url=t.cleanUrl,n=!h.isEmptyObject(t.data),(t.title||n)&&h.options.disableSuid!==!0&&(t.hash=h.getShortUrl(t.url).replace(/\??\&_suid.*/,""),/\?/.test(t.hash)||(t.hash+="?"),t.hash+="&_suid="+t.id),t.hashedUrl=h.getFullUrl(t.hash),(h.emulated.pushState||h.bugs.safariPoll)&&h.hasUrlDuplicate(t)&&(t.url=t.hashedUrl),t},h.createStateObject=function(e,t,n){var r={data:e,title:t,url:n};return r=h.normalizeState(r),r},h.getStateById=function(e){e=String(e);var n=h.idToState[e]||h.store.idToState[e]||t;return n},h.getStateString=function(e){var t,n,r;return t=h.normalizeState(e),n={data:t.data,title:e.title,url:e.url},r=l.stringify(n),r},h.getStateId=function(e){var t,n;return t=h.normalizeState(e),n=t.id,n},h.getHashByState=function(e){var t,n;return t=h.normalizeState(e),n=t.hash,n},h.extractId=function(e){var t,n,r,i;return e.indexOf("#")!=-1?i=e.split("#")[0]:i=e,n=/(.*)\&_suid=([0-9]+)$/.exec(i),r=n?n[1]||e:e,t=n?String(n[2]||""):"",t||!1},h.isTraditionalAnchor=function(e){var t=!/[\/\?\.]/.test(e);return t},h.extractState=function(e,t){var n=null,r,i;return t=t||!1,r=h.extractId(e),r&&(n=h.getStateById(r)),n||(i=h.getFullUrl(e),r=h.getIdByUrl(i)||!1,r&&(n=h.getStateById(r)),!n&&t&&!h.isTraditionalAnchor(e)&&(n=h.createStateObject(null,null,i))),n},h.getIdByUrl=function(e){var n=h.urlToId[e]||h.store.urlToId[e]||t;return n},h.getLastSavedState=function(){return h.savedStates[h.savedStates.length-1]||t},h.getLastStoredState=function(){return h.storedStates[h.storedStates.length-1]||t},h.hasUrlDuplicate=function(e){var t=!1,n;return n=h.extractState(e.url),t=n&&n.id!==e.id,t},h.storeState=function(e){return h.urlToId[e.url]=e.id,h.storedStates.push(h.cloneObject(e)),e},h.isLastSavedState=function(e){var t=!1,n,r,i;return h.savedStates.length&&(n=e.id,r=h.getLastSavedState(),i=r.id,t=n===i),t},h.saveState=function(e){return h.isLastSavedState(e)?!1:(h.savedStates.push(h.cloneObject(e)),!0)},h.getStateByIndex=function(e){var t=null;return typeof e=="undefined"?t=h.savedStates[h.savedStates.length-1]:e<0?t=h.savedStates[h.savedStates.length+e]:t=h.savedStates[e],t},h.getCurrentIndex=function(){var e=null;return h.savedStates.length<1?e=0:e=h.savedStates.length-1,e},h.getHash=function(e){var t=h.getLocationHref(e),n;return n=h.getHashByUrl(t),n},h.unescapeHash=function(e){var t=h.normalizeHash(e);return t=decodeURIComponent(t),t},h.normalizeHash=function(e){var t=e.replace(/[^#]*#/,"").replace(/#.*/,"");return t},h.setHash=function(e,t){var n,i;return t!==!1&&h.busy()?(h.pushQueue({scope:h,callback:h.setHash,args:arguments,queue:t}),!1):(h.busy(!0),n=h.extractState(e,!0),n&&!h.emulated.pushState?h.pushState(n.data,n.title,n.url,!1):h.getHash()!==e&&(h.bugs.setHash?(i=h.getPageUrl(),h.pushState(null,null,i+"#"+e,!1)):r.location.hash=e),h)},h.escapeHash=function(t){var n=h.normalizeHash(t);return n=e.encodeURIComponent(n),h.bugs.hashEscape||(n=n.replace(/\%21/g,"!").replace(/\%26/g,"&").replace(/\%3D/g,"=").replace(/\%3F/g,"?")),n},h.getHashByUrl=function(e){var t=String(e).replace(/([^#]*)#?([^#]*)#?(.*)/,"$2");return t=h.unescapeHash(t),t},h.setTitle=function(e){var t=e.title,n;t||(n=h.getStateByIndex(0),n&&n.url===e.url&&(t=n.title||h.options.initialTitle));try{r.getElementsByTagName("title")[0].innerHTML=t.replace("<","&lt;").replace(">","&gt;").replace(" & "," &amp; ")}catch(i){}return r.title=t,h},h.queues=[],h.busy=function(e){typeof e!="undefined"?h.busy.flag=e:typeof h.busy.flag=="undefined"&&(h.busy.flag=!1);if(!h.busy.flag){u(h.busy.timeout);var t=function(){var e,n,r;if(h.busy.flag)return;for(e=h.queues.length-1;e>=0;--e){n=h.queues[e];if(n.length===0)continue;r=n.shift(),h.fireQueueItem(r),h.busy.timeout=o(t,h.options.busyDelay)}};h.busy.timeout=o(t,h.options.busyDelay)}return h.busy.flag},h.busy.flag=!1,h.fireQueueItem=function(e){return e.callback.apply(e.scope||h,e.args||[])},h.pushQueue=function(e){return h.queues[e.queue||0]=h.queues[e.queue||0]||[],h.queues[e.queue||0].push(e),h},h.queue=function(e,t){return typeof e=="function"&&(e={callback:e}),typeof t!="undefined"&&(e.queue=t),h.busy()?h.pushQueue(e):h.fireQueueItem(e),h},h.clearQueue=function(){return h.busy.flag=!1,h.queues=[],h},h.stateChanged=!1,h.doubleChecker=!1,h.doubleCheckComplete=function(){return h.stateChanged=!0,h.doubleCheckClear(),h},h.doubleCheckClear=function(){return h.doubleChecker&&(u(h.doubleChecker),h.doubleChecker=!1),h},h.doubleCheck=function(e){return h.stateChanged=!1,h.doubleCheckClear(),h.bugs.ieDoubleCheck&&(h.doubleChecker=o(function(){return h.doubleCheckClear(),h.stateChanged||e(),!0},h.options.doubleCheckInterval)),h},h.safariStatePoll=function(){var t=h.extractState(h.getLocationHref()),n;if(!h.isLastSavedState(t))return n=t,n||(n=h.createStateObject()),h.Adapter.trigger(e,"popstate"),h;return},h.back=function(e){return e!==!1&&h.busy()?(h.pushQueue({scope:h,callback:h.back,args:arguments,queue:e}),!1):(h.busy(!0),h.doubleCheck(function(){h.back(!1)}),p.go(-1),!0)},h.forward=function(e){return e!==!1&&h.busy()?(h.pushQueue({scope:h,callback:h.forward,args:arguments,queue:e}),!1):(h.busy(!0),h.doubleCheck(function(){h.forward(!1)}),p.go(1),!0)},h.go=function(e,t){var n;if(e>0)for(n=1;n<=e;++n)h.forward(t);else{if(!(e<0))throw new Error("History.go: History.go requires a positive or negative integer passed.");for(n=-1;n>=e;--n)h.back(t)}return h};if(h.emulated.pushState){var v=function(){};h.pushState=h.pushState||v,h.replaceState=h.replaceState||v}else h.onPopState=function(t,n){var r=!1,i=!1,s,o;return h.doubleCheckComplete(),s=h.getHash(),s?(o=h.extractState(s||h.getLocationHref(),!0),o?h.replaceState(o.data,o.title,o.url,!1):(h.Adapter.trigger(e,"anchorchange"),h.busy(!1)),h.expectedStateId=!1,!1):(r=h.Adapter.extractEventData("state",t,n)||!1,r?i=h.getStateById(r):h.expectedStateId?i=h.getStateById(h.expectedStateId):i=h.extractState(h.getLocationHref()),i||(i=h.createStateObject(null,null,h.getLocationHref())),h.expectedStateId=!1,h.isLastSavedState(i)?(h.busy(!1),!1):(h.storeState(i),h.saveState(i),h.setTitle(i),h.Adapter.trigger(e,"statechange"),h.busy(!1),!0))},h.Adapter.bind(e,"popstate",h.onPopState),h.pushState=function(t,n,r,i){if(h.getHashByUrl(r)&&h.emulated.pushState)throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(i!==!1&&h.busy())return h.pushQueue({scope:h,callback:h.pushState,args:arguments,queue:i}),!1;h.busy(!0);var s=h.createStateObject(t,n,r);return h.isLastSavedState(s)?h.busy(!1):(h.storeState(s),h.expectedStateId=s.id,p.pushState(s.id,s.title,s.url),h.Adapter.trigger(e,"popstate")),!0},h.replaceState=function(t,n,r,i){if(h.getHashByUrl(r)&&h.emulated.pushState)throw new Error("History.js does not support states with fragement-identifiers (hashes/anchors).");if(i!==!1&&h.busy())return h.pushQueue({scope:h,callback:h.replaceState,args:arguments,queue:i}),!1;h.busy(!0);var s=h.createStateObject(t,n,r);return h.isLastSavedState(s)?h.busy(!1):(h.storeState(s),h.expectedStateId=s.id,p.replaceState(s.id,s.title,s.url),h.Adapter.trigger(e,"popstate")),!0};if(s){try{h.store=l.parse(s.getItem("History.store"))||{}}catch(m){h.store={}}h.normalizeStore()}else h.store={},h.normalizeStore();h.Adapter.bind(e,"unload",h.clearAllIntervals),h.saveState(h.storeState(h.extractState(h.getLocationHref(),!0))),s&&(h.onUnload=function(){var e,t,n;try{e=l.parse(s.getItem("History.store"))||{}}catch(r){e={}}e.idToState=e.idToState||{},e.urlToId=e.urlToId||{},e.stateToId=e.stateToId||{};for(t in h.idToState){if(!h.idToState.hasOwnProperty(t))continue;e.idToState[t]=h.idToState[t]}for(t in h.urlToId){if(!h.urlToId.hasOwnProperty(t))continue;e.urlToId[t]=h.urlToId[t]}for(t in h.stateToId){if(!h.stateToId.hasOwnProperty(t))continue;e.stateToId[t]=h.stateToId[t]}h.store=e,h.normalizeStore(),n=l.stringify(e);try{s.setItem("History.store",n)}catch(i){if(i.code!==DOMException.QUOTA_EXCEEDED_ERR)throw i;s.length&&(s.removeItem("History.store"),s.setItem("History.store",n))}},h.intervalList.push(a(h.onUnload,h.options.storeInterval)),h.Adapter.bind(e,"beforeunload",h.onUnload),h.Adapter.bind(e,"unload",h.onUnload));if(!h.emulated.pushState){h.bugs.safariPoll&&h.intervalList.push(a(h.safariStatePoll,h.options.safariPollInterval));if(i.vendor==="Apple Computer, Inc."||(i.appCodeName||"")==="Mozilla")h.Adapter.bind(e,"hashchange",function(){h.Adapter.trigger(e,"popstate")}),h.getHash()&&h.Adapter.onDomLoad(function(){h.Adapter.trigger(e,"hashchange")})}},(!h.options||!h.options.delayInit)&&h.init()}(window)

/*!
 * imagesLoaded PACKAGED v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */
!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return-1==n.indexOf(t)&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{};return(i[e]=i[e]||{})[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return-1!=n&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){i=i.slice(0),t=t||[];for(var n=this._onceEvents&&this._onceEvents[e],o=0;o<i.length;o++){var r=i[o];n&&n[r]&&(this.off(e,r),delete n[r]),r.apply(this,t)}return this}},t.allOff=function(){delete this._events,delete this._onceEvents},e}),function(t,i){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(e){return i(t,e)}):"object"==typeof module&&module.exports?module.exports=i(t,require("ev-emitter")):t.imagesLoaded=i(t,t.EvEmitter)}("undefined"!=typeof window?window:this,function(t,e){function o(e,t){for(var i in t)e[i]=t[i];return e}function r(e,t,i){if(!(this instanceof r))return new r(e,t,i);var n=e;return"string"==typeof e&&(n=document.querySelectorAll(e)),n?(this.elements=function(e){return Array.isArray(e)?e:"object"==typeof e&&"number"==typeof e.length?a.call(e):[e]}(n),this.options=o({},this.options),"function"==typeof t?i=t:o(this.options,t),i&&this.on("always",i),this.getImages(),s&&(this.jqDeferred=new s.Deferred),void setTimeout(this.check.bind(this))):void h.error("Bad element for imagesLoaded "+(n||e))}function i(e){this.img=e}function n(e,t){this.url=e,this.element=t,this.img=new Image}var s=t.jQuery,h=t.console,a=Array.prototype.slice;(r.prototype=Object.create(e.prototype)).options={},r.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},r.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),!0===this.options.background&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&d[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var d={1:!0,9:!0,11:!0};return r.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},r.prototype.addImage=function(e){var t=new i(e);this.images.push(t)},r.prototype.addBackground=function(e,t){var i=new n(e,t);this.images.push(i)},r.prototype.check=function(){function t(e,t,i){setTimeout(function(){n.progress(e,t,i)})}var n=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(e){e.once("progress",t),e.check()}):void this.complete()},r.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&h&&h.log("progress: "+i,e,t)},r.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},(i.prototype=Object.create(e.prototype)).check=function(){return this.getIsImageComplete()?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},i.prototype.getIsImageComplete=function(){return this.img.complete&&this.img.naturalWidth},i.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},i.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},i.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},i.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},i.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},(n.prototype=Object.create(i.prototype)).check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url,this.getIsImageComplete()&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},n.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},n.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},r.makeJQueryPlugin=function(e){(e=e||t.jQuery)&&((s=e).fn.imagesLoaded=function(e,t){return new r(this,e,t).jqDeferred.promise(s(this))})},r.makeJQueryPlugin(),r});

(function($) {
    "use strict";


    function prdctfltr_sort_classes() {
        if (prdctfltr.ajax_class == '') {
            prdctfltr.ajax_class = '.products';
        }
        if (prdctfltr.ajax_category_class == '') {
            prdctfltr.ajax_category_class = '.product-category';
        }
        if (prdctfltr.ajax_product_class == '') {
            prdctfltr.ajax_product_class = '.type-product';
        }
        if (prdctfltr.ajax_pagination_class == '') {
            prdctfltr.ajax_pagination_class = '.woocommerce-pagination';
        }
        if (prdctfltr.ajax_count_class == '') {
            prdctfltr.ajax_count_class = '.woocommerce-result-count';
        }
        if (prdctfltr.ajax_orderby_class == '') {
            prdctfltr.ajax_orderby_class = '.woocommerce-ordering';
        }
    }
    prdctfltr_sort_classes();

    // $(prdctfltr.ajax_pagination_class).children().off();
    // $('.prdctfltr_woocommerce_ordering').off();
    //$('.prdctfltr_wc').children().off();

    function mobile() {
        var css = '';
        $('.prdctfltr_mobile').each(function() {
            var id = $(this).prev().attr('data-id');
            css += '@media screen and (min-width: ' + $(this).prev().attr('data-mobile') + 'px) {.prdctfltr_wc[data-id="' + id + '"] {display:block;}.prdctfltr_wc[data-id="' + id + '"] + .prdctfltr_mobile {display:none;}}@media screen and (max-width: ' + $(this).prev().attr('data-mobile') + 'px) {.prdctfltr_wc[data-id="' + id + '"] {display:none;}.prdctfltr_wc[data-id="' + id + '"] +.prdctfltr_mobile {display:block;}}';
        });

        $('.prdctfltr_mobile_show').each(function() {
            var id = $(this).attr('data-id');
            css += '@media screen and (min-width: ' + $(this).attr('data-mobile') + 'px) {.prdctfltr_wc[data-id="' + id + '"] {display:block;}}';
        });

        $('.prdctfltr_mobile_hide').each(function() {
            var id = $(this).attr('data-id');
            css += '@media screen and (min-width: ' + $(this).attr('data-mobile') + 'px) {.prdctfltr_wc[data-id="' + id + '"] {display:none;}}>';
        });

        $('head').append('<style type="text/css">' + css + '</style>');
    }
    mobile();

    var pf_singlesc = false;
    if ($('.prdctfltr_sc_products.prdctfltr_ajax ' + prdctfltr.ajax_class).length == 1 && $('.prdctfltr_wc:not(.prdctfltr_step_filter)').length > 0) {
        $('body').addClass('prdctfltr-sc');
        pf_singlesc = 1;
    } else {
        prdctfltr.active_sc = '';
    }

    var pf_failsafe = false;

    function ajax_failsafe() {
        if (prdctfltr.ajax_failsafe.length == 0) {
            return false;
        }
        if ($('.prdctfltr_sc_products').length > 0) {
            return false;
        }
        if ($('body').hasClass('prdctfltr-ajax')) {
            pf_failsafe = false;
            if ($.inArray('wrapper', prdctfltr.ajax_failsafe) !== -1) {
                if ($(prdctfltr.ajax_class).length < 1) {
                    pf_failsafe = true;
                }
            }
            if ($.inArray('product', prdctfltr.ajax_failsafe) !== -1) {
                if ($(prdctfltr.ajax_class + ' ' + prdctfltr.ajax_product_class).length < 1 && $(prdctfltr.ajax_class + ' ' + prdctfltr.ajax_category_class).length < 1) {
                    pf_failsafe = true;
                }
            }

            if ($.inArray('pagination', prdctfltr.ajax_failsafe) !== -1) {
                if ($(prdctfltr.ajax_pagination_class).length < 1) {
                    pf_failsafe = true;
                }
            }

            if (pf_failsafe === true) {
                console.log('PF: AJAX Failsafe active.');
            }
        }
    }
    ajax_failsafe();

    prdctfltr.clearall = ($.isArray(prdctfltr.clearall) === true ? prdctfltr.clearall : false);

    var archiveAjax = false;
    if ($('body').hasClass('prdctfltr-ajax') && pf_failsafe === false) {
        archiveAjax = true;
    }

    if (archiveAjax === true || pf_singlesc) {
        var pageFilters = {};
        var makeHistory = {};

        $('.prdctfltr_wc').each(function() {
            pageFilters[$(this).attr('data-id')] = $("<div />").append($(this).clone()).html();
        });

        if (prdctfltr.rangefilters) {
            pageFilters.ranges = prdctfltr.rangefilters;
        }

        pageFilters.products = $("<div />").append($(prdctfltr.ajax_class).clone()).html();
        pageFilters.pagination = $("<div />").append($(prdctfltr.ajax_pagination_class).clone()).html();
        pageFilters.count = $("<div />").append($(prdctfltr.ajax_count_class).clone()).html();
        pageFilters.orderby = $("<div />").append($(prdctfltr.ajax_orderby_class).clone()).html();
        pageFilters.title = $("<div />").append($('h1.page-title').clone()).html();
        pageFilters.desc = $("<div />").append($('.term-description:first, .page-description:first').clone()).html();
        pageFilters.loop_start = $('<ul class="products">');
        pageFilters.prdctfltr = prdctfltr;

        var historyId = guid();

        makeHistory[historyId] = pageFilters;
        history.replaceState({ filters: historyId, archiveAjax: true, shortcodeAjax: false }, document.title, '');
    }

    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    }

    var ajaxActive = false;

    $.expr[':'].Contains = function(a, i, m) {
        return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    String.prototype.getValueByKey = function(k) {
        var p = new RegExp('\\b' + k + '\\b', 'gi');
        return this.search(p) != -1 ? decodeURIComponent(this.substr(this.search(p) + k.length + 1).substr(0, this.substr(this.search(p) + k.length + 1).search(/(&|;|$)/))) : "";
    };

    var startInit = false;

    function __call_meta_range(i, obj3) {

        var currTax = $('#' + i).attr('data-filter');

        obj3.prettify_enabled = false;

        if (u(obj3.prettyValues)) {
            obj3.prettify_enabled = true;

            obj3.prettify = function(num) {
                return obj3.prettyValues[num];
            };
        }

        obj3.onChange = function(data) {
            startInit = true;
        };

        obj3.onFinish = function(data) {
            if (startInit === true) {

                startInit = false;

                var iValue = '';

                if (data.min == data.from && data.max == data.to) {

                    var ourObj = prdctfltr_get_obj_580($('#' + i).closest('.prdctfltr_wc'));

                    $.each(ourObj, function(i, obj) {

                        $(obj).find('input[name="' + currTax + '"]').val('');
                        $(obj).find('.prdctfltr_range input[data-filter="' + currTax + '"]:not(#' + i + ')').each(function() {
                            var range = $(this).data("ionRangeSlider");
                            range.update({
                                from: data.min,
                                to: data.max
                            });
                        });

                    });

                    $('#' + i).closest('.prdctfltr_filter').find('input[name="' + currTax + '"]:first').trigger('change');

                } else {

                    if (obj3.prettify_enabled === true) {
                        $.each(obj3.prettyValues.slice(data.from, data.to + 1), function(i, e) {
                            iValue += (i == 0 ? '' : ',') + $(obj3.prettyValues[data.from + i]).text();
                        })

                    } else {
                        iValue = data.from + ',' + data.to;
                    }

                    var ourObj = prdctfltr_get_obj_580($('#' + i).closest('.prdctfltr_wc'));

                    $.each(ourObj, function(i, obj) {

                        $(obj).find('input[name="' + currTax + '"]').val(iValue);

                        $(obj).find('.prdctfltr_range input[data-filter="' + currTax + '"]:not(#' + i + ')').each(function() {
                            var range = $(this).data("ionRangeSlider");

                            if (typeof range !== 'undefined') {
                                range.update({
                                    from: data.from,
                                    to: data.to
                                });
                            }

                        });

                    });

                    $('#' + i).closest('.prdctfltr_filter').find('input[name="' + currTax + '"]:first').trigger('change');

                }

                var curr_filter = $('#' + i).closest('.prdctfltr_wc');

                if (curr_filter.hasClass('prdctfltr_tabbed_selection') && curr_filter.hasClass('prdctfltr_click')) {
                    curr_filter.find('.prdctfltr_filter').each(function() {
                        if ($(this).find('input[type="hidden"]:first').length > 0 && $(this).find('input[type="hidden"]:first').val() !== '') {
                            if (!$(this).hasClass('prdctfltr_has_selection')) {
                                $(this).addClass('prdctfltr_has_selection');
                            }

                        } else {
                            if ($(this).hasClass('prdctfltr_has_selection')) {
                                $(this).removeClass('prdctfltr_has_selection');
                            }
                        }
                    });
                }

                var ourObj = prdctfltr_get_obj_580(curr_filter);

                $.each(ourObj, function(i, obj) {
                    var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + currTax + '"]');
                    pfObj.each(function() {
                        check_selection_boxes($(this), 'look');
                    });
                });

            }
        };

        $('#' + i).ionRangeSlider(obj3);
        ranges[i] = $('#' + i).data('ionRangeSlider');

    }

    function __call_taxonomy_range(i, obj3) {

        var currTax = $('#' + i).attr('data-filter');

        if (currTax !== 'price') {
            obj3.prettify_enabled = true;

            obj3.prettify = function(num) {
                return obj3.prettyValues[num];
            };
        }

        obj3.onChange = function(data) {
            startInit = true;
        };

        obj3.onFinish = function(data) {
            if (startInit === true) {

                startInit = false;

                if (data.min == data.from && data.max == data.to) {

                    var ourObj = prdctfltr_get_obj_580($('#' + i).closest('.prdctfltr_wc'));

                    $.each(ourObj, function(i, obj) {

                        $(obj).find('input[name="rng_min_' + currTax + '"]').val('');
                        $(obj).find('input[name="rng_max_' + currTax + '"]').val('');
                        $(obj).find('.prdctfltr_range input[data-filter="' + currTax + '"]:not(#' + i + ')').each(function() {
                            var range = $(this).data("ionRangeSlider");
                            range.update({
                                from: data.min,
                                to: data.max
                            });
                        });

                    });

                    $('#' + i).closest('.prdctfltr_filter').find('input[name="rng_max_' + currTax + '"]:first').trigger('change');


                } else {

                    var minVal = (currTax == 'price' ?
                        data.from :
                        $(obj3.prettyValues[data.from]).text());

                    var maxVal = (currTax == 'price' ?
                        data.to :
                        $(obj3.prettyValues[data.to]).text());

                    var ourObj = prdctfltr_get_obj_580($('#' + i).closest('.prdctfltr_wc'));

                    $.each(ourObj, function(i, obj) {

                        $(obj).find('input[name="rng_min_' + currTax + '"]').val(minVal);
                        $(obj).find('input[name="rng_max_' + currTax + '"]').val(maxVal);

                        $(obj).find('.prdctfltr_range input[data-filter="' + currTax + '"]:not(#' + i + ')').each(function() {
                            var range = $(this).data("ionRangeSlider");

                            if (typeof range !== 'undefined') {
                                range.update({
                                    from: data.from,
                                    to: data.to
                                });
                            }

                        });

                    });

                    $('#' + i).closest('.prdctfltr_filter').find('input[name="rng_max_' + currTax + '"]:first').trigger('change');

                }

                var curr_filter = $('#' + i).closest('.prdctfltr_wc');
                if (curr_filter.hasClass('prdctfltr_tabbed_selection') && curr_filter.hasClass('prdctfltr_click')) {
                    curr_filter.find('.prdctfltr_filter').each(function() {
                        if ($(this).find('input[type="hidden"]:first').length > 0 && $(this).find('input[type="hidden"]:first').val() !== '') {
                            if (!$(this).hasClass('prdctfltr_has_selection')) {
                                $(this).addClass('prdctfltr_has_selection');
                            }

                        } else {
                            if ($(this).hasClass('prdctfltr_has_selection')) {
                                $(this).removeClass('prdctfltr_has_selection');
                            }
                        }
                    });
                }

                var ourObj = prdctfltr_get_obj_580(curr_filter);

                $.each(ourObj, function(i, obj) {
                    var pfObj = $(obj).find('.prdctfltr_filter[data-filter="rng_' + currTax + '"]');
                    pfObj.each(function() {
                        check_selection_boxes($(this), 'look');
                    });
                });

            }
        };

        $('#' + i).ionRangeSlider(obj3);
        ranges[i] = $('#' + i).data('ionRangeSlider');

    }

    function init_ranges() {
        $.each(prdctfltr.rangefilters, function(i, obj3) {

            if ($('#' + i).length > 0) {
                if ($('#' + i).closest('.prdctfltr_filter').hasClass('prdctfltr_meta_range')) {
                    __call_meta_range(i, obj3);
                } else {
                    __call_taxonomy_range(i, obj3);
                }
            }
        });
    }

    var ranges = {};
    init_ranges();

    function reorder_selected(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        if (curr.find('label.prdctfltr_active').length == 0) {
            return;
        }
        
        curr.each(function() {
            if ($(this).hasClass('prdctfltr_selected_reorder')) {
                $(this).find('.prdctfltr_filter.prdctfltr_attributes .prdctfltr_checkboxes, .prdctfltr_filter.prdctfltr_vendor .prdctfltr_checkboxes, .prdctfltr_filter.prdctfltr_byprice .prdctfltr_checkboxes, .prdctfltr_filter.prdctfltr_orderby .prdctfltr_checkboxes').each(function() {
                    var checkboxes = $(this);
                    if (checkboxes.find('label.prdctfltr_active').length > 0) {
                        $(checkboxes.find('label.prdctfltr_active').get().reverse()).each(function() {
                            var addThis = $(this);
                            $(this).remove();
                            if (checkboxes.find('label.prdctfltr_ft_none:first').length > 0) {
                                checkboxes.find('label.prdctfltr_ft_none:first').after(addThis);
                            } else {
                                checkboxes.prepend(addThis);
                            }
                        });
                    }
                });
            }
        });
    }
    reorder_selected();

    function reorder_adoptive(curr) {

        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.each(function() {

            var currEl = $(this);

            if ($(this).hasClass('prdctfltr_adoptive_reorder')) {
                currEl.find('.prdctfltr_adoptive').each(function() {
                    var filter = $(this);
                    if (filter.find('.pf_adoptive_hide').length > 0) {
                        var checkboxes = filter.find('.prdctfltr_checkboxes');
                        filter.find('.pf_adoptive_hide').each(function() {
                            var addThis = $(this);
                            $(this).remove();
                            checkboxes.append(addThis);
                        });
                    }
                });
            }

        });

    }
    reorder_adoptive();

    $(document).on('click', '.pf_more:not(.pf_activated)', function() {
        var filter = $(this).closest('.prdctfltr_attributes, .prdctfltr_meta');
        var checkboxes = filter.find('.prdctfltr_checkboxes');

        if (filter.hasClass('pf_adptv_default')) {
            var searchIn = 'label:not(.pf_adoptive_hide)';
        } else {
            var searchIn = 'label';
        }

        var displayType = checkboxes.find(searchIn + ':first').css('display');

        checkboxes.find(searchIn).attr('style', 'display:' + displayType + ' !important');
        checkboxes.find('.pf_more').addClass('pf_activated').html('<span>' + prdctfltr.localization.show_less + '</span>');

        if (filter.closest('.prdctfltr_wc').hasClass('pf_mod_masonry')) {
            filter.closest('.prdctfltr_filter_inner').isotope('layout');
        }
    });

    $(document).on('click', '.pf_more.pf_activated', function() {
        var filter = $(this).closest('.prdctfltr_attributes, .prdctfltr_meta');
        var checkboxes = filter.find('.prdctfltr_checkboxes');

        if (filter.hasClass('pf_adptv_default')) {
            var searchIn = 'label:not(.pf_adoptive_hide)';
        } else {
            var searchIn = 'label';
        }
        checkboxes.each(function() {
            var max = parseInt(filter.attr('data-limit'), 10);
            if (max > 0 && $(this).find(searchIn).length > max ) {

                $(this).find(searchIn).slice(max).attr('style', 'display:none !important');
                $(this).find('.pf_more').html('<span>' + prdctfltr.localization.show_more + '</span>').removeClass('pf_activated');

                if (filter.closest('.prdctfltr_wc').hasClass('pf_mod_masonry')) {
                    filter.closest('.prdctfltr_filter_inner').isotope('layout');
                }
            }
        });
    });

    function set_select_index(curr) {

        curr = (curr == null ? $('.prdctfltr_woocommerce') : curr);

        curr.each(function() {

            var curr_el = $(this);

            var selects = curr_el.find('.pf_select .prdctfltr_filter');
            if (selects.length > 0) {
                var zIndex = selects.length;
                selects.each(function() {
                    $(this).css({ 'z-index': zIndex });
                    zIndex--;
                });
            }
        });

    }
    set_select_index();

    function init_search(curr) {

        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.each(function() {

            var curr_el = $(this);

            curr_el.find('input.pf_search').each(function() {

                $(this).keyup(function() {
                    if ($(this).next().is(':hidden')) {
                        $(this).next().show();
                    }
                });

            });
        });
    }
    init_search();

    $(document).on('keydown', '.pf_search', function() {
        if (event.which == 13) {
            $(this).next().trigger('click');
            return false;
        }
    });

    $(document).on('click', '.pf_search_trigger', function() {
        var wc = $(this).closest('.prdctfltr_wc');

        if ($(this).prev().val() == '') {
            $('.prdctfltr_filter input[name="s"], .prdctfltr_add_inputs input[name="s"]').remove();
        }

        if (!wc.hasClass('prdctfltr_click_filter')) {
            wc.find('.prdctfltr_woocommerce_filter_submit').trigger('click');
        } else {
            var obj = wc.find('.prdctfltr_woocommerce_ordering');
            prdctfltr_respond_550(obj);
        }

        return false;
    });


    function is_touch_device() {
        return 'ontouchstart' in window || navigator.maxTouchPoints;
    }

    function prdctfltr_init_tooltips(c) {

        if (is_touch_device() !== true) {

            c = (c == null ? $('.prdctfltr_woocommerce') : c);

            c.each(function() {

                var $tooltips = $(this).find('.prdctfltr_filter :not(.prdctfltr_terms_customized_select) label');

                $tooltips.each(function() {

                    var $l = $(this);
                    var $t = $l.find('.prdctfltr_tooltip');

                    if ($t.length > 0) {

                        var f = {

                            timeout: 150,

                            over: function() {
                                if ($('body > .pf_fixtooltip').length > 0) {
                                    $('body > .pf_fixtooltip').remove();
                                }

                                var p = getCoords($l);

                                $('body').append('<div class="pf_fixtooltip" style="z-index:999999;position:fixed;top:' + p.top + 'px;left:' + (p.left + $l.width() / 2) + 'px;">' + $('<div></div>').append($t.clone()).html() + '</div>');

                                setTimeout(function() {
                                    $('body > .pf_fixtooltip').addClass('prdctfltr_hover');
                                }, 10);

                            },

                            out: function() {

                                $('body > .prdctfltr_hover').removeClass('prdctfltr_hover').addClass('prdctfltr_removeme');

                                setTimeout(function() {
                                    $('body > .prdctfltr_removeme').remove();
                                }, 150);

                            },

                        };

                        $l.hoverIntent(f);

                    }

                });

            });

        }

    }
    prdctfltr_init_tooltips();

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

    function prdctfltr_init_scroll(curr) {

        curr = (curr == null ? $('.prdctfltr_wc') : curr);
        var wrap = curr.find('.prdctfltr_filter_wrapper');

        if (curr.hasClass('pf_mod_row') && curr.hasClass('prdctfltr_scroll_default')) {
            if (curr.find('.prdctfltr_filter').length > parseInt(wrap.attr('data-columns'), 10)) {
                wrap.css('overflow-x', 'scroll');
            }
        }

        if (curr.hasClass('prdctfltr_scroll_active')) {

            var wrapper = curr.find('.prdctfltr_filter:not(.prdctfltr_range,.prdctfltr_search) .prdctfltr_add_scroll');

            wrapper.mCustomScrollbar({
                axis: 'y',
                scrollInertia: 550,
                autoExpandScrollbar: true,
                advanced: {
                    updateOnBrowserResize: true,
                    updateOnContentResize: true
                }
            });

            if (!curr.hasClass('prdctfltr_wc_widget') && curr.hasClass('pf_mod_row') && (curr.find('.prdctfltr_checkboxes').length > $('.prdctfltr_filter_wrapper:first').attr('data-columns'))) {

                if (curr.hasClass('prdctfltr_slide')) {
                    curr.find('.prdctfltr_woocommerce_ordering').show();
                }

                var curr_scroll_column = curr.find('.prdctfltr_filter:first').outerWidth();
                var curr_columns = curr.find('.prdctfltr_filter').length;

                curr.find('.prdctfltr_filter_inner').css('width', curr_columns * curr_scroll_column);
                curr.find('.prdctfltr_filter').css('width', curr_scroll_column);

                wrap.mCustomScrollbar({
                    axis: 'x',
                    scrollInertia: 550,
                    scrollbarPosition: 'outside',
                    autoExpandScrollbar: true,
                    advanced: {
                        updateOnBrowserResize: true,
                        updateOnContentResize: false
                    }
                });

                if (curr.hasClass('prdctfltr_slide')) {
                    curr.find('.prdctfltr_woocommerce_ordering').hide();
                }

            }

            if ($('.prdctfltr-widget').length == 0 || $('.prdctfltr-widget .prdctfltr_error').length == 1) {
                curr.find('.prdctfltr_slide .prdctfltr_woocommerce_ordering').hide();
            }

        }

    }

    function prdctfltr_cats_mode_700(curr) {

        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.each(function(i, obj) {

            obj = $(obj);
            var checkFilters = obj.find('.prdctfltr_attributes');

            checkFilters.each(function() {

                var mode = false;

                if ($(this).hasClass('prdctfltr_drill')) {
                    mode = 'drill';
                }
                if ($(this).hasClass('prdctfltr_drillback')) {
                    mode = 'drillback';
                }
                if ($(this).hasClass('prdctfltr_subonly')) {
                    mode = 'subonly';
                }
                if ($(this).hasClass('prdctfltr_subonlyback')) {
                    mode = 'subonlyback';
                }
                if (mode === false) {
                    return true;
                }

                var doIt = true;
                var checkCheckboxes = $(this).find('.prdctfltr_checkboxes');

                if (mode == 'subonly' || mode == 'subonlyback') {
                    if (checkCheckboxes.find('label.prdctfltr_active').length > 1) {
                        if (checkCheckboxes.find('> label.prdctfltr_active').length > 1) {
                            doIt = false;
                        }
                        var checkParents = '';
                        checkCheckboxes.find('label.prdctfltr_active input[type="checkbox"]').each(function() {
                            if (checkParents == '') {
                                checkParents = ($(this).attr('data-parent') ? $(this).attr('data-parent') : '%toplevel');
                            } else {
                                if ($(this).attr('data-parent') !== checkParents) {
                                    doIt = false;
                                }
                            }
                        });

                    }
                }

                if (doIt === false) {
                    return;
                }

                var ourEl = checkCheckboxes.find('label.prdctfltr_active');

                if (ourEl.length == 0) {
                    if (mode == 'drill' || mode == 'drillback') {
                        checkCheckboxes.find('> .prdctfltr_sub').remove();
                    }
                } else {
                    ourEl.each(function() {

                        if ($(this).next().is('.prdctfltr_sub')) {
                            var subParent = $(this).next();
                        } else {
                            var subParent = $(this).closest('.prdctfltr_sub');
                        }

                        if (subParent.length == 0) {
                            if (mode == 'drill' || mode == 'drillback') {
                                checkCheckboxes.find('> .prdctfltr_sub').remove();
                            }
                        } else {

                            if (mode == 'drill' || mode == 'drillback') {
                                subParent.find('.prdctfltr_sub').remove();
                            }

                            var subParentCon = $('<div></div>').append(subParent.clone()).html();
                            if (mode.indexOf('back') !== -1 && subParent.prev().is('label')) {
                                subParentCon += $('<div></div>').append(subParent.prev().addClass('prdctfltr_hiddenparent').clone()).html();
                            }
                        }

                        if (typeof subParentCon != 'undefined') {
                            checkCheckboxes.empty();
                            checkCheckboxes.append(subParentCon);
                        }

                    });

                }

            });

        });

    }

    function get_category_mode(setView) {
        if (typeof setView == 'undefined') {
            prdctfltr_cats_mode_700();
        } else {
            prdctfltr_cats_mode_700(setView);
        }
    }
    get_category_mode();

    function prdctfltr_show_opened_cats(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.find('.prdctfltr_hierarchy label.prdctfltr_active').each(function() {
            if ($(this).next().is('.prdctfltr_sub')) {
                if (!$(this).hasClass('prdctfltr_show_subs')) {
                    $(this).addClass('prdctfltr_show_subs');
                }
            }

            $(this).parents('.prdctfltr_sub').each(function() {
                if (!$(this).prev().hasClass('prdctfltr_show_subs')) {
                    $(this).prev().addClass('prdctfltr_show_subs');
                }
            });

        });
    }

    function prdctfltr_all_cats(curr) {
        return false;
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.find('.prdctfltr_expand_parents .prdctfltr_sub').each(function() {
            var curr = $(this);
            if (!curr.is(':visible')) {
                if (!curr.prev().hasClass('prdctfltr_show_subs')) {
                    curr.prev().addClass('prdctfltr_show_subs');
                }
            }
        });
    }

    function prdctfltr_make_clears(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        var clearActive = false;
        var currEls = curr.find('.prdctfltr_filter label.prdctfltr_active');
        var currElLength = currEls.length;

        if (curr.find('input[name^="mtar"]').filter(function() { return this.value !== ''; }).length > 0) {
            __get_clear_all_button_loop(curr);
        } else if (curr.find('input[name^="rng_m"]').filter(function() { return this.value !== ''; }).length > 0) {
            __get_clear_all_button_loop(curr);
        } else if (currElLength > 0) {
            currEls.each(function() {

                var currEl = $(this);
                var currElPrnt = currEl.closest('.prdctfltr_filter');
                var currElFilter = currElPrnt.attr('data-filter');

                if (prdctfltr.clearall[0] != null) {
                    if ($.inArray(currElFilter, prdctfltr.clearall) > -1) {

                    } else {
                        clearActive = true;
                    }
                } else {
                    clearActive = true;
                }

                if (!--currElLength) {
                    if (clearActive === true) {
                        __get_clear_all_button_loop(curr);
                    }
                }

            });
        } else if (curr.find('.prdctfltr_buttons label.prdctfltr_active').length > 0) {
            __get_clear_all_button_loop(curr);
        } else if (curr.find('.prdctfltr_add_inputs input.pf_added_orderby').length > 0) {
            __get_clear_all_button_loop(curr);
        }
    }

    function __get_clear_all_button_loop(e) {
        e.each(function() {
            if (!$(this).hasClass('pf_remove_clearall')) {
                __get_clear_all_button($(this));
            }
        });
    }

    function __get_clear_all_button(e) {
        e.find('.prdctfltr_buttons').append('<span class="prdctfltr_reset"><label><input name="reset_filter" type="checkbox" /><span>' + prdctfltr.localization.clearall + '</span></label></span>');
    }

    function prdctfltr_submit_form(curr_filter) {
        if (curr_filter.hasClass('prdctfltr_click_filter') || $('.prdctfltr_wc input[name="reset_filter"]:checked').length > 0) {
            prdctfltr_respond_550(curr_filter.find('form'));
        }
    }

    $('.prdctfltr_wc').each(function() {

        var curr = $(this);

        prdctfltr_filter_terms_init(curr);

        prdctfltr_init_scroll(curr);

        if (curr.find('.prdctfltr_expand_parents').length > 0) {
            prdctfltr_all_cats(curr);
        }
        prdctfltr_show_opened_cats(curr);

        if (curr.hasClass('pf_mod_masonry')) {
            curr.find('.prdctfltr_filter_inner').isotope({
                resizable: false,
                masonry: {}
            });
            if (!curr.hasClass('prdctfltr_always_visible')) {
                curr.find('.prdctfltr_woocommerce_ordering').hide();
            }
        }

        if (curr.attr('class').indexOf('pf_sidebar_css') > 0) {
            if (curr.hasClass('pf_sidebar_css_right')) {
                $('body').css('right', '0px');
            } else {
                $('body').css('left', '0px');
            }
            if (!$('body').hasClass('wc-prdctfltr-active-overlay')) {
                $('body').addClass('wc-prdctfltr-active-overlay');
            }
        }

        if (curr.hasClass('prdctfltr_step_filter')) {
            var checkStep = curr.find('.prdctfltr_woocommerce_filter_submit');
            if (curr.find('.prdctfltr_woocommerce_filter_submit').length > 0) {
                curr.find('.prdctfltr_woocommerce_filter_submit').remove();
            }
            curr.find('.prdctfltr_buttons').prepend('<a class="button prdctfltr_woocommerce_filter_submit pf_stopajax" href="#">' + (prdctfltr.js_filters[curr.attr('data-id')].button_text == '' ? prdctfltr.localization.getproducts : prdctfltr.js_filters[curr.attr('data-id')].button_text) + '</a>');
            curr.closest('.prdctfltr_sc').addClass('prdctfltr_sc_step_filter');
        }

        if ($(this).attr('data-loader') !== 'none' && $(this).attr('data-loader').substr(0, 4) !== 'css-') {
            pf_preload_image(prdctfltr.url + 'lib/images/svg-loaders/' + $(this).attr('data-loader') + '.svg');
        }

        check_selection_boxes_wrapper(curr);
        prdctfltr_make_clears(curr);

    });

    function pf_preload_image(url) {
        var img = new Image();
        img.src = url;
    }

    $(document).on('change', '.prdctfltr_range input[name^="rng_"], .prdctfltr_meta_range input[name^="mtar"]', function() {
        var curr = $(this).closest('.prdctfltr_woocommerce');

        if (curr.hasClass('prdctfltr_click_filter')) {
            prdctfltr_respond_550(curr.find('.prdctfltr_woocommerce_ordering'));
        }
    });

    var stopAjax = false;
    $(document).on('click', '.prdctfltr_woocommerce_filter_submit', function() {

        if ($(this).hasClass('pf_stopajax')) {
            stopAjax = true;
        }

        var curr = $(this).closest('.prdctfltr_woocommerce_ordering');

        prdctfltr_respond_550(curr);

        return false;

    });

    $(document).on('click', '.prdctfltr_woocommerce_filter_title, .prdctfltr_showing', function() {
        $(this).parent().find('.prdctfltr_woocommerce_filter').trigger('click');
    });

    $(document).on('click', '.prdctfltr_woocommerce_filter:not(.pf_ajax_loading)', function() {

        var curr_filter = $(this).closest('.prdctfltr_woocommerce');

        if (!curr_filter.hasClass('prdctfltr_always_visible')) {

            if (curr_filter.hasClass('pf_mod_masonry') && curr_filter.find('.prdctfltr_woocommerce_ordering:hidden').length > 0) {
                if (curr_filter.hasClass('prdctfltr_active') === false) {
                    var curr_check = curr_filter.find('.prdctfltr_woocommerce_ordering');
                    curr_check.show().find('.prdctfltr_filter_inner').isotope('layout');
                    curr_check.hide();
                }
            }

            var curr = curr_filter.find('.prdctfltr_woocommerce_ordering');

            if ($(this).hasClass('prdctfltr_active')) {
                if (curr_filter.attr('class').indexOf('pf_sidebar') == -1) {
                    if (curr_filter.hasClass('pf_fullscreen')) {
                        curr.stop(true, true).fadeOut(200, function() {
                            curr.find('.prdctfltr_close_sidebar').remove();
                        });
                    } else {
                        if (!curr_filter.hasClass('prdctfltr_wc_widget') && !curr_filter.hasClass('prdctfltr_always_visible')) {
                            curr.stop(true, true).slideUp(200);
                        }
                    }
                } else {
                    curr.stop(true, true).fadeOut(200, function() {
                        curr.find('.prdctfltr_close_sidebar').remove();
                    });
                    if (curr_filter.attr('class').indexOf('pf_sidebar_css') > 0) {
                        if (curr_filter.hasClass('pf_sidebar_css_right')) {
                            $('body').css({ 'right': '0px', 'bottom': 'auto', 'top': 'auto', 'left': 'auto' });
                        } else {
                            $('body').css({ 'right': 'auto', 'bottom': 'auto', 'top': 'auto', 'left': '0px' });
                        }
                        $('.prdctfltr_overlay').remove();
                    }
                }
                $(this).removeClass('prdctfltr_active');
                $('body').removeClass('wc-prdctfltr-active');
            } else {
                $(this).addClass('prdctfltr_active');
                if (curr_filter.attr('class').indexOf('pf_sidebar') == -1) {
                    $('body').addClass('wc-prdctfltr-active');
                    if (curr_filter.hasClass('pf_fullscreen')) {
                        curr.prepend('<div class="prdctfltr_close_sidebar"><i class="prdctfltr-delete"></i> ' + prdctfltr.localization.close_filter + '</div>');
                        curr.stop(true, true).fadeIn(200);

                        var curr_height = $(window).height() - curr.find('.prdctfltr_filter_inner').outerHeight() - curr.find('.prdctfltr_close_sidebar').outerHeight() - curr.find('.prdctfltr_buttons').outerHeight();

                        if (curr_height > 128) {
                            var curr_diff = curr_height / 2;
                            curr_height = curr.outerHeight();
                            curr.css({ 'padding-top': curr_diff + 'px' });
                        } else {
                            curr_height = $(window).height() - curr.find('.prdctfltr_close_sidebar').outerHeight() - curr.find('.prdctfltr_buttons').outerHeight() - 128;
                        }
                        curr_filter.find('.prdctfltr_filter_wrapper').css({ 'max-height': curr_height });
                    } else {
                        if (!curr_filter.hasClass('prdctfltr_wc_widget') && !curr_filter.hasClass('prdctfltr_always_visible')) {
                            curr.stop(true, true).slideDown(200);
                        }
                    }
                } else {
                    curr.prepend('<div class="prdctfltr_close_sidebar"><i class="prdctfltr-delete"></i> ' + prdctfltr.localization.close_filter + '</div>');
                    curr.stop(true, true).fadeIn(200);
                    if (curr_filter.attr('class').indexOf('pf_sidebar_css') > 0) {
                        $('body').append('<div class="prdctfltr_overlay"></div>');
                        if (prdctfltr.rtl == '') {
                            if (curr_filter.hasClass('pf_sidebar_css_right')) {
                                $('body').css({ 'right': '160px', 'bottom': 'auto', 'top': 'auto', 'left': 'auto' });
                                $('.prdctfltr_overlay').css({ 'right': '310px' }).delay(200).animate({ 'opacity': 0.33 }, 200, 'linear');
                            } else {
                                $('body').css({ 'right': 'auto', 'bottom': 'auto', 'top': 'auto', 'left': '160px' });
                                $('.prdctfltr_overlay').css({ 'left': '310px' }).delay(200).animate({ 'opacity': 0.33 }, 200, 'linear');
                            }
                        } else {
                            if (curr_filter.hasClass('pf_sidebar_css_right')) {
                                $('body').css({ 'left': '160px', 'bottom': 'auto', 'top': 'auto', 'right': 'auto' });
                                $('.prdctfltr_overlay').css({ 'left': '310px' }).delay(200).animate({ 'opacity': 0.33 }, 200, 'linear');
                            } else {
                                $('body').css({ 'left': 'auto', 'bottom': 'auto', 'top': 'auto', 'right': '160px' });
                                $('.prdctfltr_overlay').css({ 'right': '310px' }).delay(200).animate({ 'opacity': 0.33 }, 200, 'linear');
                            }
                        }
                    }
                    $('body').addClass('wc-prdctfltr-active');
                }
            }
        }

        return false;
    });

    $(document).on('click', '.prdctfltr_overlay, .prdctfltr_close_sidebar', function() {

        if ($(this).closest('.prdctfltr_woocommerce').length > 0) {
            $(this).closest('.prdctfltr_woocommerce').find('.prdctfltr_woocommerce_filter.prdctfltr_active').trigger('click');
        } else {
            $('.pf_sidebar_css .prdctfltr_woocommerce_filter.prdctfltr_active, .pf_sidebar_css_right .prdctfltr_woocommerce_filter.prdctfltr_active').trigger('click');
        }

    });

    $(document).on('click', '.pf_default_select .prdctfltr_widget_title, .prdctfltr_terms_customized_select .prdctfltr_widget_title', function() {

        var curr = $(this).closest('.prdctfltr_filter').find('.prdctfltr_add_scroll');

        if (!curr.hasClass('prdctfltr_down')) {
            $(this).find('.prdctfltr-down').attr('class', 'prdctfltr-up');
            curr.addClass('prdctfltr_down');
            curr.slideDown(100);
        } else {
            curr.slideUp(100);
            curr.removeClass('prdctfltr_down');
            $(this).find('.prdctfltr-up').attr('class', 'prdctfltr-down');
        }

    });

    var pf_select_opened = false;
    $(document).on('click', '.pf_select .prdctfltr_filter .prdctfltr_regular_title, .prdctfltr_terms_customized_select.prdctfltr_filter .prdctfltr_regular_title', function() {
        pf_select_opened = true;
        var curr = $(this).closest('.prdctfltr_filter').find('.prdctfltr_add_scroll');

        if (!curr.hasClass('prdctfltr_down')) {
            $(this).find('.prdctfltr-down').attr('class', 'prdctfltr-up');
            curr.addClass('prdctfltr_down');
            curr.slideDown(100, function() {
                pf_select_opened = false;
            });

            if (!$('body').hasClass('wc-prdctfltr-select')) {
                $('body').addClass('wc-prdctfltr-select');
            }
        } else {
            curr.slideUp(100, function() {
                pf_select_opened = false;

            });
            curr.removeClass('prdctfltr_down');
            $(this).find('.prdctfltr-up').attr('class', 'prdctfltr-down');
            if (curr.closest('.prdctfltr_woocommerce').find('.prdctfltr_down').length == 0) {
                $('body').removeClass('wc-prdctfltr-select');
            }
        }

    });

    $(document).on('click', 'body.wc-prdctfltr-select', function(e) {

        var curr_target = $(e.target);

        if ($('.prdctfltr_wc.pf_select .prdctfltr_down, .prdctfltr_terms_customized_select .prdctfltr_down').length > 0 && pf_select_opened === false && !curr_target.is('span, input, i')) {
            $('.prdctfltr_wc.pf_select .prdctfltr_down, .prdctfltr_wc:not(.prdctfltr_wc_widget.pf_default_select) .prdctfltr_terms_customized_select .prdctfltr_down').each(function() {
                var curr = $(this);
                if (curr.is(':visible')) {
                    curr.slideUp(100);
                    curr.removeClass('prdctfltr_down');
                    curr.closest('.prdctfltr_filter').find('span .prdctfltr-up').attr('class', 'prdctfltr-down');
                }
            });
            $('body').removeClass('wc-prdctfltr-select');
        }
    });

    $(document).on('click', 'span.prdctfltr_sale label, span.prdctfltr_instock label, span.prdctfltr_reset label', function() {

        var field = $(this).children('input:first');

        var curr_name = field.attr('name');
        var curr_filter = $(this).closest('.prdctfltr_wc');

        var ourObj = prdctfltr_get_obj_580(curr_filter);
        var pf_length = prdctfltr_count_obj_580(ourObj);

        if ($('body').hasClass('prdctfltr-ajax') && field.attr('name') == 'reset_filter') {
            $.each(ourObj, function(i, obj) {
                if (obj.find('.prdctfltr_buttons input[name="reset_filter"]').length == 0) {
                    obj.find('.prdctfltr_buttons').append('<input name="reset_filter" type="checkbox" checked />');
                }
            });
        }

        $.each(ourObj, function(i, obj) {

            obj = $(obj);

            var curr_obj = obj.find('.prdctfltr_buttons input[name="' + curr_name + '"]');
            if (curr_obj.length > 0) {
                curr_obj.each(function(i5, obj24) {
                    var obj25 = $(obj24);
                    if (!obj25.parent().hasClass('prdctfltr_active')) {
                        obj25.prop('checked', true).attr('checked', true).parent().addClass('prdctfltr_active');
                        de_check_buttons(obj25, 'notactive');
                    } else {
                        obj25.prop('checked', false).attr('checked', false).parent().removeClass('prdctfltr_active');
                        de_check_buttons(obj25, 'active');
                    }
                });
            }

            if (obj.find('.prdctfltr_filter.prdctfltr_instock').length > 0) {
                obj.find('.prdctfltr_filter.prdctfltr_instock input[name="instock_products"]').remove();
            }

            if (!--pf_length) {
                prdctfltr_submit_form(curr_filter);
            }

        });

    });

    $(document).on('click', '.prdctfltr_byprice label', function() {

        var curr_chckbx = $(this).find('input[type="checkbox"]');
        var curr = curr_chckbx.closest('.prdctfltr_filter');
        var curr_var = curr_chckbx.val().split('-');
        var curr_filter = curr_chckbx.closest('.prdctfltr_wc');

        if (curr_filter.hasClass('prdctfltr_tabbed_selection')) {
            var currVal = curr.find('input[name="min_price"]').val() + '-' + curr.find('input[name="max_price"]').val();
            if (currVal == curr_chckbx.val()) {
                return false;
            }
        }

        var ourObj = prdctfltr_get_obj_580(curr_filter);
        var pf_length = prdctfltr_count_obj_580(ourObj);

        if (curr_var[0] == '' && curr_var[1] == '' || curr_chckbx.closest('label').hasClass('prdctfltr_active')) {

            $.each(ourObj, function(i, obj) {
                var pfObj = $(obj).find('.prdctfltr_filter.prdctfltr_byprice');
                pfObj.find('.prdctfltr_active input[type="checkbox"]').prop('checked', false).attr('checked', false).closest('label').removeClass('prdctfltr_active');
                pfObj.find('input[name="min_price"]').val('');
                pfObj.find('input[name="max_price"]').val('');
                if (!--pf_length) {
                    prdctfltr_submit_form(curr_filter);
                }
            });

        } else {

            $.each(ourObj, function(i, obj) {
                var pfObj = $(obj).find('.prdctfltr_filter.prdctfltr_byprice');
                pfObj.find('.prdctfltr_active input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                pfObj.find('input[name="min_price"]').val(curr_var[0]);
                pfObj.find('input[name="max_price"]').val(curr_var[1]);
                pfObj.find('input[value="' + curr_var[0] + '-' + curr_var[1] + '"][type="checkbox"]').prop('checked', true).attr('checked', true).change().closest('label').addClass('prdctfltr_active');
                if (!--pf_length) {
                    prdctfltr_submit_form(curr_filter);
                }
            });

        }

        if (curr_filter.hasClass('prdctfltr_tabbed_selection') && curr_filter.hasClass('prdctfltr_click')) {
            curr_filter.find('.prdctfltr_filter').each(function() {
                if ($(this).find('input[type="hidden"]:first').length > 0 && $(this).find('input[type="hidden"]:first').val() !== '') {
                    if (!$(this).hasClass('prdctfltr_has_selection')) {
                        $(this).addClass('prdctfltr_has_selection');
                    }

                } else {
                    if ($(this).hasClass('prdctfltr_has_selection')) {
                        $(this).removeClass('prdctfltr_has_selection');
                    }
                }
            });
        }

        if (!curr_chckbx.closest('.prdctfltr_wc').hasClass('prdctfltr_wc_widget') && (curr_chckbx.closest('.prdctfltr_wc').hasClass('pf_select') || curr.hasClass('prdctfltr_terms_customized_select'))) {

            if (curr.hasClass('prdctfltr_terms_customized_select') && curr_chckbx.closest('.prdctfltr_wc').hasClass('prdctfltr_wc_widget') && curr_chckbx.closest('.prdctfltr_wc').hasClass('pf_default_select')) {
                return false;
            }
            curr_chckbx.closest('.prdctfltr_filter').find('.prdctfltr_add_scroll').slideUp(250).removeClass('prdctfltr_down');
            curr_chckbx.closest('.prdctfltr_filter').find('.prdctfltr_regular_title i.prdctfltr-up').removeClass('prdctfltr-up').addClass('prdctfltr-down');

        }


        $.each(ourObj, function(i, obj) {
            var pfObj = $(obj).find('.prdctfltr_filter[data-filter="price"]');
            pfObj.each(function() {
                check_selection_boxes($(this), 'look');
            });
        });


        return false;

    });

    $(document).on('click', '.prdctfltr_filter:not(.prdctfltr_byprice) label', function(event) {

        if ($(event.target).is('input')) {
            return false;
        }

        var curr_chckbx = $(this).find('input[type="checkbox"]');
        var curr = curr_chckbx.closest('.prdctfltr_filter');
        var curr_var = curr_chckbx.val();
        var curr_filter = curr.closest('.prdctfltr_wc');

        if (curr.hasClass('pf_adptv_unclick')) {
            if (curr_chckbx.parent().hasClass('pf_adoptive_hide')) {
                return false;
            }
        }

        prdctfltr_check_580(curr, curr_chckbx, curr_var, curr_filter);

        return false;

    });

    var shortcodeAjax = false;
    var prodcutsWrapper = false;
    var hasFilter = false;
    var hasProducts = false;
    var isAjax = false;
    var isStep = false;
    var hasWidget = false;

    function resetVars() {
        shortcodeAjax = false;
        prodcutsWrapper = false;
        hasFilter = false;
        hasProducts = false;
        isAjax = false;
        isStep = false;
        hasWidget = false;
    }

    function prdctfltr_get_obj_580(filter) {
        var ourObj = {};
        resetVars();

        if (filter.closest('.prdctfltr_sc').length > 0) {
            var scWrap = filter.closest('.prdctfltr_sc');
            var scMode = scWrap.is('.prdctfltr_sc_filter') ? 'sc_filter' : 'sc_shortcode';
            if (scWrap.find('.prdctfltr_wc').length > 0) {
                hasFilter = true;
            }
            if (scWrap.find(prdctfltr.ajax_class).length > 0) {
                hasProducts = true;
            }
            if (scWrap.hasClass('prdctfltr_ajax')) {
                isAjax = true;
                shortcodeAjax = true;
            }
            if (scWrap.find('.prdctfltr_wc').hasClass('prdctfltr_step_filter')) {
                isStep = true;
            }
            if ($('.prdctfltr_wc_widget').length > 0) {
                hasWidget = true;
            }
        } else if (filter.closest('.prdctfltr_wcsc').length > 0) {

        } else if (archiveAjax === true) {

        } else if (filter.closest('.prdctfltr_wc_widget').length > 0) {
            hasWidget = true;
            if ($('.prdctfltr_sc:not(.prdctfltr_sc_step_filter)').length > 0) {
                if ($('.prdctfltr_sc:not(.prdctfltr_sc_step_filter)').find(prdctfltr.ajax_class).length > 0) {
                    var scWrap = $('.prdctfltr_sc:not(.prdctfltr_sc_step_filter)');
                    var scMode = scWrap.is('.prdctfltr_sc_filter') ? 'sc_filter' : 'sc_shortcode';
                    hasFilter = true;
                    hasProducts = true;
                    prodcutsWrapper = scWrap;
                    shortcodeAjax = prodcutsWrapper.hasClass('prdctfltr_ajax');
                }
            }
        }

        if (isStep) {
            scWrap.find('.prdctfltr_wc').each(function() {
                ourObj[$(this).attr('data-id')] = $(this);
            });
        } else if (hasProducts && hasFilter) {
            prodcutsWrapper = scWrap;
            if (hasWidget) {
                scWrap.find('.prdctfltr_wc:not(.prdctfltr_step_filter)').each(function() {
                    ourObj[$(this).attr('data-id')] = $(this);
                });
                $('.prdctfltr_wc_widget:not(.prdctfltr_step_filter)').each(function() {
                    ourObj[$(this).attr('data-id')] = $(this);
                });
            } else {
                scWrap.find('.prdctfltr_wc:not(.prdctfltr_step_filter)').each(function() {
                    ourObj[$(this).attr('data-id')] = $(this);
                });
            }
        } else {
            $('.prdctfltr_wc:not([data-id="' + filter.attr('data-id') + '"]):not(.prdctfltr_step_filter)').each(function() {
                if ($(this).closest('.prdctfltr_sc_products').length == 0) {
                    ourObj[$(this).attr('data-id')] = $(this);
                }
            });
            ourObj[filter.attr('data-id')] = $('.prdctfltr_wc[data-id="' + filter.attr('data-id') + '"]');
        }

        return ourObj;

    }

    function prdctfltr_count_obj_580(ourObj) {
        var pf_length = 0;
        var i;
        for (i in ourObj) {
            if (ourObj.hasOwnProperty(i)) {
                pf_length++;
            }
        }
        return pf_length;
    }

    function prdctfltr_check_parent_helper_590(termParent, pfObj) {
        if (termParent) {
            var found = pfObj.find('input[value="' + termParent + '"]');
            if (found.length > 0) {
                pfObj.find('input[value="' + termParent + '"][type="checkbox"]').prop('checked', true).attr('checked', true).change().closest('label').addClass('prdctfltr_active');
            } else {
                pfObj.closest('.prdctfltr_wc').find('.prdctfltr_add_inputs').append('<input type="hidden" name="' + pfObj.attr('data-filter') + '" value="' + termParent + '" class="pf_added_input" />');

            }
        }
    }

    function prdctfltr_check_580(curr, curr_chckbx, curr_var, curr_filter) {

        var ourObj = prdctfltr_get_obj_580(curr_filter);
        var pf_length = prdctfltr_count_obj_580(ourObj);

        var field = curr.children('input[type="hidden"]:first');

        var curr_name = field.attr('name');
        var curr_val = field.val();

        if (curr_filter.hasClass('prdctfltr_tabbed_selection')) {
            if (curr_val == curr_chckbx.val()) {
                // return false;
            }
        }

        if ($('.pf_added_input[name="' + curr_name + '"]').length > 0) {
            $('.pf_added_input[name="' + curr_name + '"]').remove();
        }

        if (curr.hasClass('prdctfltr_selection')) {
            var checkLength = pf_length;
            $.each(ourObj, function(i, obj) {
                var pfObj1 = $(obj).find('.prdctfltr_filter:not(.prdctfltr_range):not([data-filter="' + curr_name + '"]) label.prdctfltr_active');
                if (pfObj1.length > 0) {
                    $.each(pfObj1, function(i3, ob5) {
                        $('.pf_added_input[name="' + $(ob5).closest('.prdctfltr_filter').attr('data-filter') + '"]').remove();
                        $(ob5).removeClass('prdctfltr_active').find('input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('.prdctfltr_filter').find('input[type="hidden"]').val('');
                    });
                }
                var pfObj = $(obj).find('.prdctfltr_filter.prdctfltr_range input[type="hidden"][val!=""]');
                if (pfObj.length > 0) {
                    $.each(pfObj, function(i2, obj4) {
                        $('.pf_added_input[name="' + $(obj4).attr('name') + '"]').remove();
                        $(obj4).closest('.prdctfltr_filter').find('input[type="hidden"]').val('');
                    });
                }

                if (!--checkLength) {
                    $.each(ourObj, function(i4, obj47) {

                        $(obj47).find('.prdctfltr_buttons input[name="sale_products"], .prdctfltr_buttons input[name="instock_products"]').each(function() {
                            $(this).prop('checked', false).attr('checked', false).closest('label').removeClass('prdctfltr_active');
                            de_check_buttons($(this), 'active');
                        });

                        $(obj47).find('input.pf_search').val('');

                        $(obj47).find('input[id^="prdctfltr_rng_"]').each(function() {
                            var setRng = $(this).data('ionRangeSlider');
                            ranges[$(this).attr('id')].update({
                                from: setRng.options.min,
                                to: setRng.options.max
                            });
                        });

                        $(obj47).find('.prdctfltr_filter').each(function() {
                            check_selection_boxes($(this), 'init');
                        });

                    });
                }

            });
        }

        if (!curr.hasClass('prdctfltr_multi')) {

            if (curr_var == '' || curr_chckbx.closest('label').hasClass('prdctfltr_active')) {

                var termParent = curr_chckbx.attr('data-parent');

                $.each(ourObj, function(i, obj) {
                    var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');
                    pfObj.find('.prdctfltr_active input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');

                    if (termParent) {
                        prdctfltr_check_parent_helper_590(termParent, pfObj);
                        pfObj.find('input[name="' + curr_name + '"]').val(termParent);
                    } else {
                        pfObj.find('input[name="' + curr_name + '"]').val('');
                    }

                    if (!--pf_length) {
                        prdctfltr_submit_form(curr_filter);
                    }
                });

            } else {

                $.each(ourObj, function(i, obj) {
                    var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');
                    pfObj.find('.prdctfltr_active input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                    pfObj.find('input[name="' + curr_name + '"]').val(curr_var);
                    pfObj.find('input[value="' + curr_var + '"][type="checkbox"]').prop('checked', true).attr('checked', true).change().closest('label').addClass('prdctfltr_active');
                    if (!--pf_length) {
                        prdctfltr_submit_form(curr_filter);
                    }
                });

            }

            if (curr_chckbx.closest('.prdctfltr_wc').hasClass('pf_select') || curr.hasClass('prdctfltr_terms_customized_select')) {
                if (curr.hasClass('prdctfltr_terms_customized_select') && curr_chckbx.closest('.prdctfltr_wc').hasClass('prdctfltr_wc_widget') && curr_chckbx.closest('.prdctfltr_wc').hasClass('pf_default_select')) {
                    return false;
                }
                curr_chckbx.closest('.prdctfltr_filter').find('.prdctfltr_add_scroll').slideUp(250).removeClass('prdctfltr_down');
                curr_chckbx.closest('.prdctfltr_filter').find('.prdctfltr_regular_title i.prdctfltr-up').removeClass('prdctfltr-up').addClass('prdctfltr-down');
            }

        } else {

            if (curr_chckbx.val() !== '') {

                if (curr_chckbx.closest('label').hasClass('prdctfltr_active')) {

                    if (curr.hasClass('prdctfltr_merge_terms')) {
                        var curr_settings = (curr_val.indexOf('+') > 0 ? curr_val.replace('+' + curr_var, '').replace(curr_var + '+', '') : '');

                        $.each(prdctfltr.js_filters, function(n18, obj43) {
                            if (typeof obj43.adds !== 'undefined' && obj43.adds[curr_name] !== null) {
                                var check = prdctfltr.js_filters[n18].adds[curr_name];
                                prdctfltr.js_filters[n18].adds[curr_name] = (typeof check !== 'undefined' && check.indexOf('+') > 0 ? check.replace('+' + curr_var, '').replace(curr_var + '+', '') : '');
                            }
                        });
                    } else {
                        var curr_settings = (curr_val.indexOf(',') > 0 ? curr_val.replace(',' + curr_var, '').replace(curr_var + ',', '') : '');

                        $.each(prdctfltr.js_filters, function(n18, obj43) {
                            if (typeof obj43.adds !== 'undefined' && obj43.adds[curr_name] !== null) {
                                var check = prdctfltr.js_filters[n18].adds[curr_name];
                                prdctfltr.js_filters[n18].adds[curr_name] = (typeof check !== 'undefined' && check.indexOf(',') > 0 ? check.replace(',' + curr_var, '').replace(curr_var + ',', '') : '');
                            }
                        });
                    }

                    var termParent = curr_chckbx.attr('data-parent');

                    $.each(ourObj, function(i, obj) {
                        var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');
                        pfObj.find('input[name="' + curr_name + '"]').val(curr_settings);
                        pfObj.find('input[value="' + curr_var + '"][type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');

                        if (termParent) {
                            if (curr_settings == '') {
                                prdctfltr_check_parent_helper_590(termParent, pfObj);
                                pfObj.find('input[name="' + curr_name + '"]').val(termParent);
                            }
                        }

                        if (!--pf_length) {
                            prdctfltr_submit_form(curr_filter);
                        }

                    });

                } else {

                    $('.prdctfltr_filter[data-filter="' + curr_name + '"] .prdctfltr_sub[data-sub="' + curr_var + '"]').find('.prdctfltr_active input[type="checkbox"]').each(function() {

                        var checkVal = $(this).val();
                        if (curr.hasClass('prdctfltr_merge_terms')) {
                            if (curr_val.indexOf('+') > 0) {
                                curr_val = curr_val.replace('+' + checkVal, '').replace(checkVal + '+', '');
                            } else {
                                curr_val = curr_val.replace(checkVal, '');
                            }
                        } else {
                            if (curr_val.indexOf(',') > 0) {
                                curr_val = curr_val.replace(',' + checkVal, '').replace(checkVal + ',', '');
                            } else {
                                curr_val = curr_val.replace(checkVal, '');
                            }
                        }
                        $(this).prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                    });

                    if (curr.hasClass('prdctfltr_merge_terms')) {

                        if (curr.closest('.prdctfltr_wc').find('.prdctfltr_filter[data-filter="' + curr_name + '"]').length > 1) {
                            curr.find('.prdctfltr_active').each(function() {
                                var val12 = $(this).find('input[type="checkbox"]').val();
                                if (curr_val.indexOf('+') > 0) {
                                    curr_val = curr_val.replace('+' + val12, '').replace(val12 + '+', '');
                                } else {
                                    curr_val = curr_val.replace(val12, '');
                                }
                                $(this).find('input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                            });
                        }

                        var curr_settings = (curr_val == '' ? curr_var : curr_val + '+' + curr_var);
                    } else {
                        var curr_settings = (curr_val == '' ? curr_var : curr_val + ',' + curr_var);
                    }

                    var termParent = curr_chckbx.attr('data-parent');

                    $.each(ourObj, function(i, obj) {
                        var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');
                        pfObj.find('input[name="' + curr_name + '"]').val(curr_settings);
                        pfObj.find('input[value="' + curr_var + '"][type="checkbox"]').prop('checked', true).attr('checked', true).change().closest('label').addClass('prdctfltr_active');

                        if (termParent) {
                            if (pfObj.find('input[value="' + termParent + '"][type="checkbox"]:checked').length > 0) {
                                pfObj.find('input[value="' + termParent + '"][type="checkbox"]:checked').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                                if (curr_settings.indexOf(termParent) > -1) {
                                    if (curr.hasClass('prdctfltr_merge_terms')) {
                                        var makeNew = (curr_settings.indexOf('+') > 0 ? curr_settings.replace('+' + termParent, '').replace(termParent + '+', '') : '');
                                    } else {
                                        var makeNew = (curr_settings.indexOf(',') > 0 ? curr_settings.replace(',' + termParent, '').replace(termParent + ',', '') : '');
                                    }
                                    pfObj.find('input[name="' + curr_name + '"]').val(makeNew);
                                }
                            } else {
                                var remTermParent = pfObj.find('input[value="' + termParent + '"][type="checkbox"]').attr('data-parent');
                                if (remTermParent) {
                                    while (remTermParent !== false) {
                                        pfObj.find('input[value="' + remTermParent + '"][type="checkbox"]:checked').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                                        if (curr_settings.indexOf(remTermParent) > -1) {
                                            if (curr.hasClass('prdctfltr_merge_terms')) {
                                                var makeNew = (curr_settings.indexOf('+') > 0 ? curr_settings.replace('+' + remTermParent, '').replace(remTermParent + '+', '') : '');
                                            } else {
                                                var makeNew = (curr_settings.indexOf(',') > 0 ? curr_settings.replace(',' + remTermParent, '').replace(remTermParent + ',', '') : '');
                                            }
                                            pfObj.find('input[name="' + curr_name + '"]').val(makeNew);
                                        }
                                        remTermParent = (pfObj.find('input[value="' + remTermParent + '"][type="checkbox"]').attr('data-parent') ? pfObj.find('input[value="' + remTermParent + '"][type="checkbox"]').attr('data-parent') : false);
                                    }
                                }
                            }
                        }

                        if (!--pf_length) {
                            prdctfltr_submit_form(curr_filter);
                        }
                    });

                }
            } else {

                $.each(ourObj, function(i, obj) {
                    var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');

                    if (pfObj.find('label.prdctfltr_active input[data-parent]').length > 0) {
                        if (pfObj.find('label.prdctfltr_active input[data-parent]').length == pfObj.find('label.prdctfltr_active input[data-parent="' + pfObj.find('label.prdctfltr_active input[data-parent]:first').attr('data-parent') + '"]').length) {
                            pfObj.find('input[name="' + curr_name + '"]').val(pfObj.find('label.prdctfltr_active input[data-parent]:first').attr('data-parent'));
                            pfObj.find('input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                        }
                    } else {
                        pfObj.find('input[name="' + curr_name + '"]').val('');
                        pfObj.find('input[type="checkbox"]').prop('checked', false).attr('checked', false).change().closest('label').removeClass('prdctfltr_active');
                    }

                    if (!--pf_length) {
                        prdctfltr_submit_form(curr_filter);
                    }
                });

            }

        }

        if (curr_filter.hasClass('prdctfltr_tabbed_selection') && curr_filter.hasClass('prdctfltr_click')) {
            curr_filter.find('.prdctfltr_filter').each(function() {
                if ($(this).find('input[type="hidden"]:first').length > 0 && $(this).find('input[type="hidden"]:first').val() !== '') {
                    if (!$(this).hasClass('prdctfltr_has_selection')) {
                        $(this).addClass('prdctfltr_has_selection');
                    }
                } else {
                    if ($(this).hasClass('prdctfltr_has_selection')) {
                        $(this).removeClass('prdctfltr_has_selection');
                    }
                }
            });
        }


        $.each(ourObj, function(i, obj) {
            var pfObj = $(obj).find('.prdctfltr_filter[data-filter="' + curr_name + '"]');
            pfObj.each(function() {
                check_selection_boxes($(this), 'look');
            });
        });

    }

    function check_selection_boxes_wrapper(curr) {

        curr.find('.prdctfltr_filter').each(function() {
            check_selection_boxes($(this), 'init');
        });

        curr.find('.prdctfltr_buttons:first label.prdctfltr_active').each(function() {
            check_buttons($(this), 'init');
        });

    }

    function de_check_buttons(curr, mode) {

        var collectors = prdctfltr.js_filters[curr.closest('.prdctfltr_wc').attr('data-id')].collectors;
        var collectorStyle = prdctfltr.js_filters[curr.closest('.prdctfltr_wc').attr('data-id')].collector_style;

        if (mode == 'active') {

            $.each(collectors, function(i, e) {
                switch (e) {

                    case 'collector':
                        var wrap = curr.closest('.prdctfltr_woocommerce_ordering');

                        var collector = wrap.find('.prdctfltr_collector');
                        if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').length > 0) {
                            collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                        }

                        break;

                    case 'topbar':
                        var wrap = curr.closest('.prdctfltr_wc');

                        var collector = wrap.find('.prdctfltr_topbar');
                        if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').length > 0) {
                            collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                        }

                        break;

                    default:

                        break;
                }

            });
        } else {

            var input = '<span class="prdctfltr_title_selected"><span class="prdctfltr_title_added prdctfltr_title_remove" data-key="' + curr.attr('name') + '"><i class="prdctfltr-check"></i></span> <span class="prdctfltr_selected_title">' + curr.parent().text() + '</span><span class="prdctfltr_title_selected_separator"></span></span>';

            $.each(collectors, function(i, e) {
                switch (e) {

                    case 'collector':
                        var wrap = curr.closest('.prdctfltr_woocommerce_ordering');

                        if (wrap.find('.prdctfltr_collector').length == 0) {
                            wrap.prepend('<div class="prdctfltr_collector prdctfltr_collector_' + collectorStyle + '"></div>');
                            wrap.find('.prdctfltr_collector').html(input);
                        } else {
                            var collector = wrap.find('.prdctfltr_collector');
                            if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').length > 0) {
                                collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                            }
                            wrap.find('.prdctfltr_collector').append(input);
                        }
                        break;

                    case 'topbar':

                        var wrap = curr.closest('.prdctfltr_wc');

                        if (wrap.find('.prdctfltr_topbar').length == 0) {
                            wrap.find('.prdctfltr_woocommerce_filter_title').after('<div class="prdctfltr_topbar"></div>');
                            wrap.find('.prdctfltr_topbar').html(input);
                        } else {
                            var collector = wrap.find('.prdctfltr_topbar');
                            if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').length > 0) {
                                collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                            }
                            wrap.find('.prdctfltr_topbar').append(input);
                        }

                        break;

                    default:

                        break;
                }

            });

        }

        if (curr.closest('.prdctfltr_wc').hasClass('pf_mod_masonry')) {
            curr.closest('.prdctfltr_filter_inner').isotope('layout');
        }

    }

    function check_buttons(curr, mode) {

        var collectors = prdctfltr.js_filters[curr.closest('.prdctfltr_wc').attr('data-id')].collectors;
        var collectorStyle = prdctfltr.js_filters[curr.closest('.prdctfltr_wc').attr('data-id')].collector_style;

        var input = '<span class="prdctfltr_title_selected">' + (mode == 'init' ? '<a href="#" class="prdctfltr_title_remove" data-key="' + curr.find('input:first').attr('name') + '"><i class="prdctfltr-delete"></i></a>' : '<span class="prdctfltr_title_added prdctfltr_title_remove" data-key="' + curr.find('input:first').attr('name') + '"><i class="prdctfltr-check"></i></span>') + ' <span class="prdctfltr_selected_title">' + curr.text() + '</span><span class="prdctfltr_title_selected_separator"></span></span>';

        $.each(collectors, function(i, e) {
            switch (e) {

                case 'collector':
                    var wrap = curr.closest('.prdctfltr_woocommerce_ordering');

                    if (wrap.find('.prdctfltr_collector').length == 0) {
                        wrap.prepend('<div class="prdctfltr_collector prdctfltr_collector_' + collectorStyle + '"></div>');
                        wrap.find('.prdctfltr_collector').html(input);
                    } else {
                        var collector = wrap.find('.prdctfltr_collector');
                        if (collector.find('.prdctfltr_title_remove[data-key="' + curr.find('input:first').attr('name') + '"]').length > 0) {
                            collector.find('.prdctfltr_title_remove[data-key="' + curr.find('input:first').attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                        }
                        wrap.find('.prdctfltr_collector').append(input);
                    }
                    break;

                case 'topbar':

                    var wrap = curr.closest('.prdctfltr_wc');

                    if (wrap.find('.prdctfltr_topbar').length == 0) {
                        wrap.find('.prdctfltr_woocommerce_filter_title').after('<div class="prdctfltr_topbar"></div>');
                        wrap.find('.prdctfltr_topbar').html(input);
                    } else {
                        var collector = wrap.find('.prdctfltr_topbar');
                        if (collector.find('.prdctfltr_title_remove[data-key="' + curr.find('input:first').attr('name') + '"]').length > 0) {
                            collector.find('.prdctfltr_title_remove[data-key="' + curr.find('input:first').attr('name') + '"]').closest('.prdctfltr_title_selected').remove();
                        }
                        wrap.find('.prdctfltr_topbar').append(input);
                    }

                    break;

                default:

                    break;
            }

        });

        if (curr.closest('.prdctfltr_wc').hasClass('pf_mod_masonry')) {
            curr.closest('.prdctfltr_filter_inner').isotope('layout');
        }

    }

    function get_input_delete(selectedTerms, mode, curr, slug) {
        return '<span class="prdctfltr_title_selected">' + (mode == 'init' ? '<a href="#" class="prdctfltr_title_remove" data-key="' + curr.attr('data-filter') + '"' + slug + '><i class="prdctfltr-delete"></i></a>' : '<span class="prdctfltr_title_added prdctfltr_title_remove" data-key="' + curr.attr('data-filter') + '"' + slug + '><i class="prdctfltr-check"></i></span>') + ' <span class="prdctfltr_selected_title">' + selectedTerms + '</span><span class="prdctfltr_title_selected_separator"></span></span>';
    }

    function check_selection_boxes(curr, mode) {

        var selectedTerms = [];
        var selectedItms = [];
        curr.find('label.prdctfltr_active').each(function() {
            if ($(this).find('.prdctfltr_customization_search').length > 0) {
                selectedTerms.push($(this).find('.prdctfltr_customization_search').text());
            } else if ($(this).find('.prdctfltr_customize_name').length > 0) {
                selectedTerms.push($(this).find('.prdctfltr_customize_name').text());
            } else {
                selectedTerms.push($(this).find('span:first').contents().filter(function() { return 3 == this.nodeType; }).text());
            }
            if ($(this).closest('.prdctfltr_filter').hasClass('prdctfltr_attributes') || $(this).closest('.prdctfltr_filter').hasClass('prdctfltr_meta')) {
                selectedItms.push($(this).find('input[type="checkbox"]:first').val());
            }
        });

        if (typeof selectedTerms[0] == 'undefined' && curr.hasClass('prdctfltr_range')) {
            var rngData = curr.find('[id^="prdctfltr_rng_"]:first').data('ionRangeSlider');

            if (typeof rngData !== 'undefined') {
                if ((rngData.result.from == rngData.options.min && rngData.result.to == rngData.options.max) === false) {
                    if (curr.attr('data-filter') == 'rng_price') {
                        selectedTerms.push(rngData.options.prefix + rngData.result.from + rngData.options.postfix + ' &longleftrightarrow; ' + rngData.options.prefix + rngData.result.to + rngData.options.postfix);
                    } else {
                        selectedTerms.push(rngData.options.prefix + rngData.options.prettyValues[rngData.result.from] + rngData.options.postfix + ' &longleftrightarrow; ' + rngData.options.prefix + rngData.options.prettyValues[rngData.result.to] + rngData.options.postfix);
                    }

                }
            }
        }

        if (typeof selectedTerms[0] == 'undefined' && curr.hasClass('prdctfltr_meta_range')) {
            var rngData = curr.find('[id^="prdctfltr_rng_"]:first').data('ionRangeSlider');

            if (typeof rngData !== 'undefined') {
                if ((rngData.result.from == rngData.options.min && rngData.result.to == rngData.options.max) === false) {

                    if (u(rngData.options.prettify_enabled) === true) {
                        selectedTerms.push(rngData.options.prefix + rngData.options.prettyValues[rngData.result.from] + rngData.options.postfix + ' &longleftrightarrow; ' + rngData.options.prefix + rngData.options.prettyValues[rngData.result.to] + rngData.options.postfix);
                    } else {
                        selectedTerms.push(rngData.options.prefix + rngData.result.from + rngData.options.postfix + ' &longleftrightarrow; ' + rngData.options.prefix + rngData.result.to + rngData.options.postfix);
                    }

                }
            }
        }

        if (typeof selectedTerms[0] !== 'undefined') {

            var col = prdctfltr.js_filters[curr.closest('.prdctfltr_wc').attr('data-id')];

            var collectors = typeof col !== 'undefined' ? col.collectors : [];
            var collectorStyle = typeof col !== 'undefined' ? col.collector_style : [];

            var slug = '';
            if (curr.hasClass('prdctfltr_attributes') || curr.hasClass('prdctfltr_meta')) {
                if (1 == 1 && typeof selectedTerms[1] !== 'undefined') {
                    var input = '';
                    $.each(selectedItms, function(o23, k23) {
                        slug = ' data-slug="' + selectedItms[o23] + '"';
                        input += get_input_delete(selectedTerms[o23], mode, curr, slug);
                    });
                } else {
                    var value = curr.find('input[type="hidden"]:first').val();
                    var parent = curr.find('input[type="hidden"]:first').attr('data-parent');
                    slug = ' data-slug="' + (typeof parent !== 'undefined' ? parent + '>' : '') + value + '"';
                    var input = get_input_delete(selectedTerms.join(', '), mode, curr, slug);
                }
            } else {
                var input = '<span class="prdctfltr_title_selected">' + (mode == 'init' ? '<a href="#" class="prdctfltr_title_remove" data-key="' + curr.attr('data-filter') + '"' + slug + '><i class="prdctfltr-delete"></i></a>' : '<span class="prdctfltr_title_added prdctfltr_title_remove" data-key="' + curr.attr('data-filter') + '"' + slug + '><i class="prdctfltr-check"></i></span>') + ' <span class="prdctfltr_selected_title">' + selectedTerms.join(', ') + '</span><span class="prdctfltr_title_selected_separator"></span></span>';
            }

            $.each(collectors, function(i, e) {
                switch (e) {
                    case 'intitle':
                        curr.find('.prdctfltr_regular_title .prdctfltr_title_selected, .prdctfltr_widget_title  .prdctfltr_title_selected').remove();
                        curr.find('.prdctfltr_regular_title, .prdctfltr_widget_title').prepend(input);
                        break;

                    case 'aftertitle':
                        curr.find('.prdctfltr_aftertitle').remove();
                        curr.find('.prdctfltr_add_scroll').before('<div class="prdctfltr_aftertitle prdctfltr_collector_' + collectorStyle + '">' + input + '</div>');
                        break;

                    case 'collector':
                        var wrap = curr.closest('.prdctfltr_woocommerce_ordering');

                        if (wrap.find('.prdctfltr_collector').length == 0) {
                            wrap.prepend('<div class="prdctfltr_collector prdctfltr_collector_' + collectorStyle + '"></div>');
                            wrap.find('.prdctfltr_collector').html(input);
                        } else {
                            var collector = wrap.find('.prdctfltr_collector');
                            if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').length > 0) {
                                collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').closest('.prdctfltr_title_selected').remove();
                            }
                            wrap.find('.prdctfltr_collector').append(input);
                        }
                        break;

                    case 'topbar':

                        var wrap = curr.closest('.prdctfltr_wc');

                        if (wrap.find('.prdctfltr_topbar').length == 0) {
                            wrap.find('.prdctfltr_woocommerce_filter_title').after('<div class="prdctfltr_topbar"></div>');
                            wrap.find('.prdctfltr_topbar').html(input);
                        } else {
                            var collector = wrap.find('.prdctfltr_topbar');
                            if (collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').length > 0) {
                                collector.find('.prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').closest('.prdctfltr_title_selected').remove();
                            }
                            wrap.find('.prdctfltr_topbar').append(input);
                        }

                        break;

                    default:

                        break;
                }

            });

        } else if (typeof selectedTerms[0] == 'undefined') {
            if (curr.closest('.prdctfltr_wc').find('.prdctfltr_attributes[data-filter="' + curr.attr('data-filter') + '"] label.prdctfltr_active').length == 0) {
                curr.find('.prdctfltr_title_selected').remove();
                curr.closest('.prdctfltr_wc').find('.prdctfltr_collector .prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').closest('.prdctfltr_title_selected').remove();
                curr.closest('.prdctfltr_wc').find('.prdctfltr_topbar .prdctfltr_title_remove[data-key="' + curr.attr('data-filter') + '"]').closest('.prdctfltr_title_selected').remove();
            }
        }

        if (curr.closest('.prdctfltr_wc').hasClass('pf_mod_masonry')) {
            curr.closest('.prdctfltr_filter_inner').isotope('layout');
        }

    }

    function clear_filters_after(filter) {
        filter.nextAll('.prdctfltr_filter').each(function() {
            $(this).find('input[type="hidden"]').val('');
        });
    }

    function clicked_remove(obj, mode, term) {

        switch (term) {
            case 's':
            case 'search':
            case 'search_products':
                var srchStr = 'input[name="s"],input[name="search_products"]';
                break;

            case 'price':
                var srchStr = 'input[name="min_price"],input[name="max_price"]';
                break;

            default:
                var srchStr = 'input[name="' + term + '"]';
                break;
        }

        if (srchStr == 'input[name="s"],input[name="search_products"]') {
            if (mode === true) {
                obj.closest('.prdctfltr_sc_products').find('.prdctfltr_filter, .prdctfltr_buttons').find(srchStr).val('');

                if ($('.prdctfltr_wc_widget').length > 0) {
                    $('.prdctfltr_wc_widget').find('.prdctfltr_filter, .prdctfltr_buttons').find(srchStr).val('');
                }
                $('.prdctfltr_add_inputs').find(srchStr).val('');
            } else {
                $('.prdctfltr_filter, .prdctfltr_add_inputs, .prdctfltr_buttons').find(srchStr).val('');
            }
        } else {
            if (mode === true) {
                obj.closest('.prdctfltr_sc_products').find('.prdctfltr_filter, .prdctfltr_buttons').find(srchStr).remove();

                if ($('.prdctfltr_wc_widget').length > 0) {
                    $('.prdctfltr_wc_widget').find('.prdctfltr_filter, .prdctfltr_buttons').find(srchStr).remove();
                }
                $('.prdctfltr_add_inputs').find(srchStr).remove();
            } else {
                $('.prdctfltr_filter, .prdctfltr_add_inputs, .prdctfltr_buttons').find(srchStr).remove();
            }
        }


    }

    $(document).on('click', 'a.prdctfltr_title_remove', function() {

        var filter = $(this).attr('data-key');

        if (filter.substr(0, 4) !== 'rng_') {
            var selectedRemove = $(this).attr('data-slug');
            if (typeof selectedRemove !== 'undefined' && selectedRemove.indexOf('>') > 0) {
                selectedRemove = selectedRemove.substr(selectedRemove.indexOf('>') + 1);
            }

            var checkRemove = $(this).closest('.prdctfltr_wc').find('.prdctfltr_filter[data-filter="' + filter + '"] input[value="' + selectedRemove + '"]');

            if (checkRemove.length > 0) {
                checkRemove.closest('label').trigger('click');
                var checkSubmit = checkRemove.closest('.prdctfltr_wc').find('.prdctfltr_woocommerce_filter_submit');
                if (checkSubmit.length > 0) {
                    checkSubmit.trigger('click');
                }

                return false;
            }
        }

        if ($(this).closest('.prdctfltr_filter').hasClass('prdctfltr_has_selection')) {
            clear_filters_after($(this).closest('.prdctfltr_filter'));
        }

        var mode = $(this).closest('.prdctfltr_sc_products').length > 0;

        if (filter == 's' || filter == 'search_products' || filter == 'search') {
            clicked_remove($(this), mode, filter);
        } else if (filter == 'price') {
            clicked_remove($(this), mode, filter);
        } else if (filter == 'orderby' || filter == 'sale_products' || filter == 'instock_products') {
            clicked_remove($(this), mode, filter);
        } else if (filter == 'vendor' || filter == 'instock' || filter == 'products_per_page') {
            clicked_remove($(this), mode, filter);
        } else if (filter.substr(0, 4) == 'mtar') {
            clicked_remove($(this), mode, filter);
        } else if (filter.substr(0, 4) !== 'rng_') {

            if ($(this).closest('.prdctfltr_sc_products').length > 0) {
                var curr_els = $(this).closest('.prdctfltr_sc_products').find('input[name="' + filter + '"]');
                if ($('.prdctfltr_wc_widget').length > 0) {
                    curr_els.push($('.prdctfltr_wc_widget').find('input[name="' + filter + '"]'));
                }
            } else {
                var curr_els = $('.prdctfltr_filter, .prdctfltr_add_inputs').find('input[name="' + filter + '"]');
            }

            var selectedString = $(this).attr('data-slug');
            if (selectedString.indexOf('>') > 0) {
                var termParent = selectedString.substr(0, selectedString.indexOf('>'));
                selectedString = selectedString.substr(selectedString.indexOf('>') + 1);
            }

            var cur_vals = [];
            if (selectedString.indexOf(',') > 0) {
                cur_vals = selectedString.split(',');
            } else if (selectedString.indexOf('+') > 0) {
                cur_vals = selectedString.split('+');
            } else {
                cur_vals[0] = selectedString;
            }

            var cv_lenght = cur_vals.length;

            $.each(cur_vals, function(i, val23) {

                var curr_value = val23;

                curr_els.each(function() {

                    var curr_chckd = $(this);
                    var curr_chckdval = $(this).val();

                    if (curr_chckdval.indexOf(',') > 0) {
                        curr_chckd.val(curr_chckdval.replace(',' + curr_value, '').replace(curr_value + ',', ''));
                    } else if (curr_chckdval.indexOf('+') > 0) {
                        curr_chckd.val(curr_chckdval.replace('+' + curr_value, '').replace(curr_value + '+', ''));
                    } else {
                        curr_chckd.val(curr_chckdval.replace(curr_value, '').replace(curr_value, ''));
                    }

                });

                if (!--cv_lenght) {

                    curr_els.each(function() {

                        var curr_chckd = $(this);

                        if (termParent) {
                            curr_chckd.val(termParent);
                            if (curr_chckd.val() == '') {
                                curr_chckd.val(termParent);
                            }

                        }

                    });

                }

            });

        } else {
            if ($(this).closest('.prdctfltr_sc_products').length > 0) {
                if (filter == 'rng_price') {
                    $(this).closest('.prdctfltr_sc_products').find('.prdctfltr_range.prdctfltr_rng_price input[type="hidden"]').each(function() {
                        $(this).remove();
                    });
                    $('.prdctfltr_wc_widget').find('.prdctfltr_range.prdctfltr_rng_price input[type="hidden"]').remove()
                } else {
                    $(this).closest('.prdctfltr_sc_products').find('.prdctfltr_range input[type="hidden"][name$="' + filter.substr(4, filter.length) + '"]').each(function() {
                        $(this).remove();
                    });
                    $('.prdctfltr_wc_widget').find('.prdctfltr_range input[type="hidden"][name$="' + filter.substr(4, filter.length) + '"]').remove();
                }

            } else {
                if (filter == 'rng_price') {
                    $('.prdctfltr_wc').find('.prdctfltr_range.prdctfltr_rng_price input[type="hidden"]').each(function() {
                        $(this).remove();
                    });
                } else {
                    $('.prdctfltr_wc').find('.prdctfltr_range input[type="hidden"][name$="' + filter.substr(4, filter.length) + '"]').each(function() {
                        $(this).remove();
                    });
                }
            }
        }

        prdctfltr_respond_550($(this).closest('.prdctfltr_wc').find('form.prdctfltr_woocommerce_ordering'));

        return false;

    });

    $(document).on('click', 'i.prdctfltr-plus', function() {

        $(this).closest('label').toggleClass('prdctfltr_show_subs');

        if ($(this).closest('.prdctfltr_woocommerce').hasClass('pf_mod_masonry')) {
            $(this).closest('.prdctfltr_filter_inner').isotope('layout');
        }

        return false;

    });

    function prdctfltr_get_loader(curr) {

        var t = curr.closest('.prdctfltr_wc');
        if (t.is(':visible') === false) {
            return false;
        }

        var l = t.attr('data-loader');

        if (l == 'none') {
            return false;
        }

        if (typeof l !== 'undefined') {
            if (l.substr(0, 4) == 'css-') {
                if (t.find('.prdctfltr_woocommerce_filter:visible').length > 0) {
                    if ($('.pf_ajax_loading').length == 0) {
                        t.find('.prdctfltr_woocommerce_filter').addClass('pf_ajax_loading');
                    }
                } else {
                    t.prepend('<span class="prdctfltr_added_loader prdctfltr_woocommerce_filter pf_ajax_' + l + ' pf_ajax_loading"><i class="prdctfltr-bars ' + l + '"></i></span>');
                }
            } else {
                if (t.find('.prdctfltr_woocommerce_filter:visible i').length > 0 && t.find('.prdctfltr_woocommerce_filter img').length == 0) {
                    t.find('.prdctfltr_woocommerce_filter').addClass('pf_ajax_loading');
                    t.find('.prdctfltr_woocommerce_filter i').replaceWith('<img src="' + prdctfltr.url + 'lib/images/svg-loaders/' + l + '.svg" class="prdctfltr_reset_this prdctfltr_loader" />');
                } else {
                    t.prepend('<div class="prdctfltr_added_loader"><img src="' + prdctfltr.url + 'lib/images/svg-loaders/' + l + '.svg" class="prdctfltr_reset_this prdctfltr_loader" /></div>');
                }
            }
        }

    }

    function prdctfltr_reset_filters_550(obj) {

        checkAddInputs(obj);

        obj.find('.prdctfltr_filter input[type="hidden"]').each(function() {
            if (prdctfltr.clearall[0] !== null) {
                if ($.inArray(this.name, prdctfltr.clearall) > -1) {
                    if (!$(this).val()) {
                        if ($(this).attr('data-parent')) {
                            $(this).val($(this).attr('data-parent'));
                        } else {
                            $(this).remove();
                        }
                    }
                } else {
                    if ($(this).attr('data-parent')) {
                        $(this).val($(this).attr('data-parent'));
                    } else {
                        $(this).remove();
                    }
                }
            } else {
                if ($(this).attr('data-parent')) {
                    $(this).val($(this).attr('data-parent'));
                } else {
                    $(this).remove();
                }
            }
        });

        obj.find('.prdctfltr_filter input.pf_search').val('').prop('disabled', true).attr('disabled', 'true');

        if (obj.find('input[name="s"]').length > 0) {
            obj.find('input[name="s"]').val('');
        }
        if (obj.find('.prdctfltr_buttons input[name="sale_products"]').length > 0) {
            obj.find('.prdctfltr_buttons input[name="sale_products"]').remove();
        }
        if (obj.find('.prdctfltr_buttons input[name="instock_products"]').length > 0) {
            obj.find('.prdctfltr_buttons input[name="instock_products"]').remove();
        }
        if (obj.find('.prdctfltr_add_inputs input[name="orderby"]').length > 0) {
            obj.find('.prdctfltr_add_inputs input[name="orderby"]').remove();
        }

        obj.find('input[name="reset_filter"]').remove();

    }

    function checkAddInputs(obj) {

        obj.find('.prdctfltr_attributes label.prdctfltr_active input[value]').each(function() {

            var eVal = $(this).val();
            var nVal = $(this).closest('.prdctfltr_attributes').attr('data-filter');

            $('.prdctfltr_wc .prdctfltr_add_inputs .pf_added_input[name="' + nVal + '"]').each(function() {
                if ($(this).val().indexOf(eVal) > -1) {
                    if ($(this).val().indexOf(',') > -1 || $(this).val().indexOf('+') > -1) {
                        $(this).val($(this).val().replace(',' + eVal, '').replace(eVal + ',', ''));
                    } else {
                        $(this).val('');
                    }

                }
            });

            $.each(prdctfltr.js_filters, function(n18, obj43) {
                if (typeof obj43.adds !== 'undefined' && typeof obj43.adds[nVal] !== 'undefined') {
                    delete prdctfltr.js_filters[n18].adds[nVal];
                }
            });

        });

    }

    function prdctfltr_remove_empty_inputs_550(obj) {

        obj.find('.prdctfltr_filter input[type="hidden"], .prdctfltr_filter input.pf_search, .prdctfltr_add_inputs input[type="hidden"]').each(function() {

            var curr_val = $(this).val();

            if (curr_val == '') {
                if ($(this).is(':visible')) {
                    $(this).prop('disabled', true).attr('disabled', 'true');
                } else {
                    $(this).remove();
                }
            }

        });

    }

    function prdctfltr_remove_ranges_550(obj) {
        obj.find('.prdctfltr_filter.prdctfltr_range').each(function() {
            var curr_rng = $(this);
            if (curr_rng.find('[name^="rng_min_"]').val() == undefined || curr_rng.find('[name^="rng_max_"]').val() == undefined) {
                curr_rng.find('input').remove();
            }
        });
    }

    function prdctfltr_check_display_550(obj) {

        if ($('body').hasClass('wc-prdctfltr-active')) {

            if (obj.attr('class').indexOf('pf_sidebar') == -1) {
                if (obj.hasClass('pf_fullscreen')) {
                    obj.find('form').stop(true, true).fadeOut(200, function() {
                        obj.find('.prdctfltr_close_sidebar').remove();
                    });
                } else {
                    if (!obj.hasClass('prdctfltr_wc_widget') && !obj.hasClass('prdctfltr_always_visible')) {
                        obj.find('form').stop(true, true).slideUp(200);
                    }
                }
            } else {
                obj.find('form').fadeOut(200);

                if (obj.attr('class').indexOf('pf_sidebar_css') > 0) {
                    if (obj.hasClass('pf_sidebar_css_right')) {
                        $('body').css({ 'right': '0px', 'bottom': 'auto', 'top': 'auto', 'left': 'auto' });
                    } else {
                        $('body').css({ 'right': 'auto', 'bottom': 'auto', 'top': 'auto', 'left': '0px' });
                    }
                    $('.prdctfltr_overlay').remove();
                }
                obj.find('form').removeClass('prdctfltr_active');
                $('body').removeClass('wc-prdctfltr-active');

            }

        }

    }

    function prdctfltr_post_analytics(curr_fields) {
        if ($.isEmptyObject(curr_fields) === false) {
            var data = {};

            $.each(curr_fields, function(i, o) {
                if ($.isEmptyObject(o) === false) {
                    $.each(o, function(i2, o2) {
                        if ($.inArray(i2, ['rng_min_price', 'rng_max_price', 'sale_products', 'instock_products', 'orderby', 'vendor', 'min_price', 'max_price', 'products_per_page']) == -1 && i2.substring(0, 4) !== 'rng_' && i2.substring(0, 4) !== 'mta_') {
                            if (typeof data[i2] == 'undefined') {
                                data[i2] = o2;
                            }
                        }
                    });
                }
            });

            var analyticsData = {
                action: 'prdctfltr_analytics',
                filters: data,
                pf_nonce: $('.prdctfltr_wc[data-nonce]:first').attr('data-nonce'),
            };

            $.ajax({
                type: 'POST',
                url: prdctfltr.ajax,
                data: analyticsData,
                success: function() {},
                error: function() {},
            });
        }
    }

    function prdctfltr_get_fields_550(obj) {

        var curr_fields = {};

        if (obj.css('display') == 'none') {
            return curr_fields;
        }

        var lookAt = '.prdctfltr_filter input[type="hidden"], .prdctfltr_filter input.pf_search, .prdctfltr_add_inputs input[name="orderby"], .prdctfltr_add_inputs input[name="s"], .prdctfltr_add_inputs input.pf_added_input';

        obj.find(lookAt).each(function() {
            if ($(this).val() !== '') {
                curr_fields[$(this).attr('name')] = $(this).val();
            }
        });

        if (obj.find('.prdctfltr_buttons input[name="sale_products"]:checked').length > 0) {
            curr_fields.sale_products = 'on';
        }
        if (obj.find('.prdctfltr_buttons input[name="instock_products"]:checked').length > 0) {
            curr_fields.instock_products = obj.find('.prdctfltr_buttons:first input[name="instock_products"]:checked').val();
        }

        return curr_fields;

    }

    var infiniteWasReset = false;

    function after_ajax(curr_next) {

        function AAscrollHandler() {

            if (infiniteLoad.find('a.disabled').length == 0 && $(window).scrollTop() >= infiniteLoad.position().top - $(window).height() * 0.8) {
                infiniteLoad.find('a:not(.disabled)').trigger('click');
            }

        };

        $.each(curr_next, function(b, setView) {
            setView = $(setView);

            infiniteLoad = $('.prdctfltr-pagination-infinite-load');
            if (infiniteLoad.length > 0) {
                if (infiniteLoad.find('.button.disabled').length > 0) {

                    scrollInterval = null;
                    infiniteWasReset = true;
                } else {
                    if (infiniteWasReset) {
                        scrollInterval = setInterval(function() {
                            if (didScroll) {
                                didScroll = false;
                                if (ajaxActive !== false || historyActive !== false) {
                                    return false;
                                }
                                AAscrollHandler();
                            }
                        }, 250);
                    }
                }
            }


            if (setView.hasClass('pf_after_ajax')) {
                return false;
            }
            setView.addClass('pf_after_ajax');

            if (setView.hasClass('pf_mod_masonry')) {

                setView.find('.prdctfltr_woocommerce_ordering').show();
                setView.find('.prdctfltr_filter_inner').isotope({
                    resizable: false,
                    masonry: {}
                });
            }

            if (setView.find('.prdctfltr_expand_parents').length > 0) {
                prdctfltr_all_cats(setView);
            } else {
                prdctfltr_show_opened_cats(setView);
            }
            prdctfltr_init_scroll(setView);
            prdctfltr_init_tooltips(setView);
            reorder_selected(setView);
            reorder_adoptive(setView);
            set_select_index(setView);
            init_search(setView);
            init_ranges(setView);
            do_zindexes(setView);
            prdctfltr_tabbed_selection(setView);
            if ($('body').hasClass('wc-prdctfltr-active')) {
                $('body').removeClass('wc-prdctfltr-active');
            }

            if (setView.hasClass('prdctfltr_step_filter')) {
                if (setView.find('.prdctfltr_woocommerce_filter_submit').length > 0) {
                    setView.find('.prdctfltr_woocommerce_filter_submit').remove();
                }
                setView.find('.prdctfltr_buttons').prepend('<a class="button prdctfltr_woocommerce_filter_submit pf_stopajax" href="#">' + (prdctfltr.js_filters[setView.attr('data-id')].button_text == '' ? prdctfltr.localization.getproducts : prdctfltr.js_filters[setView.attr('data-id')].button_text) + '</a>');
                setView.closest('prdctfltr_sc').addClass('prdctfltr_sc_step_filter');
            }

            prdctfltr_filter_terms_init(setView);

            get_category_mode(setView);
            prdctfltr_added_check(setView);
            prdctfltr_make_clears(setView);

            setView.find('.prdctfltr_filter').each(function() {
                check_selection_boxes($(this), 'init');
            });

            setView.find('.prdctfltr_buttons:first label.prdctfltr_active').each(function() {
                check_buttons($(this), 'init');
            });

            prdctfltr_show_opened_widgets(setView);

            if (setView.hasClass('pf_mod_masonry')) {
                setView.find('.prdctfltr_filter_inner').isotope('layout');
                if (!setView.hasClass('prdctfltr_always_visible')) {
                    setView.find('.prdctfltr_woocommerce_ordering').hide();
                }
            }

            _fix_system_selects(setView);
            _fix_search_selects(setView);
            reorder_limit(setView);
        });

    }

    var pf_paged = 1;
    var pf_offset = 0;
    var pf_restrict = '';

    $(document).on('click', '.prdctfltr_sc_products.prdctfltr_ajax ' + prdctfltr.ajax_pagination_class + ' a, body.prdctfltr-ajax.prdctfltr-shop ' + prdctfltr.ajax_pagination_class + ' a, .prdctfltr-pagination-default a, .prdctfltr-pagination-load-more a', function() {

        if (ajaxActive === true) {
            return false;
        }

        ajaxActive = true;

        var loadMore = ($(this).closest('.prdctfltr-pagination-load-more').length > 0 ? true : false);
        var curr_link = $(this);

        var shortcodeAjax = false;
        var checkShortcode = curr_link.closest('.prdctfltr_sc_products');

        if (archiveAjax === false && checkShortcode.length > 0 && checkShortcode.hasClass('prdctfltr_ajax')) {
            shortcodeAjax = true;
            var obj = checkShortcode.find('form:first');
        } else {
            var obj = $('div:not(.prdctfltr_sc_products) .prdctfltr_wc:not(.prdctfltr_step_filter):first form');
        }

        if (obj.length == 0) {
            obj = $('.prdctfltr_wc_widget').find('form:first');
        }

        var curr_href = curr_link.attr('href');

        if (loadMore === true) {
            $(this).closest('.prdctfltr-pagination-load-more').addClass('prdctfltr-ignite');
            if (shortcodeAjax === false) {
                pf_offset = parseInt($(prdctfltr.ajax_class).find(prdctfltr.ajax_product_class).length, 10);
            } else {
                pf_offset = parseInt(checkShortcode.find(prdctfltr.ajax_product_class).length, 10);
            }
        } else {
            if (curr_href.indexOf('paged=') >= 0) {
                pf_paged = parseInt(curr_href.getValueByKey('paged'), 10);
            } else if (curr_href.indexOf('product-page=') >= 0) {
                pf_paged = parseInt(curr_href.getValueByKey('product-page'), 10);
            } else {
                var arrUrl = curr_href.split('/' + prdctfltr.page_rewrite + '/');
                if (typeof arrUrl[1] !== 'undefined') {
                    if (arrUrl[1].indexOf('/') > 0) {
                        arrUrl[1] = arrUrl[1].substr(0, arrUrl[1].indexOf('/'));
                    }
                    pf_paged = parseInt(arrUrl[1], 10);
                }
            }
        }

        pf_restrict = 'pagination';

        ajaxActive = false;
        prdctfltr_respond_550(obj);

        return false;

    });

    function get_shortcode(id) {
        var wrf = {};
        if (typeof prdctfltr.pagefilters[id].wcsc !== 'undefined' && prdctfltr.pagefilters[id].wcsc === true) {
            wrf = prdctfltr.pagefilters[id].atts;
        }
        $.each(prdctfltr.pagefilters, function(i, o) {
            if (i !== id) {
                if (typeof prdctfltr.pagefilters[i].wcsc !== 'undefined' && prdctfltr.pagefilters[i].wcsc === true) {
                    wrf = prdctfltr.pagefilters[i].atts;
                }
            }
        });
        return wrf;
    }

    prdctfltr.widgetTitle = null;
    function __get_widget_title() {
        if ( prdctfltr.widgetTitle == null ) {
            
        }
    }

    function prdctfltr_respond_550(curr) {

        if (ajaxActive === true) {
            return false;
        }

        ajaxActive = true;

        var curr_filter = curr.closest('.prdctfltr_wc');

        var ourObj = prdctfltr_get_obj_580(curr_filter);
        var pf_length = prdctfltr_count_obj_580(ourObj);
        var or_length = pf_length;

        if (!curr.closest('.prdctfltr_wc').hasClass('prdctfltr_step_filter') && archiveAjax === true) {
            $(prdctfltr.ajax_class + ':first').fadeTo(200, 0.5).addClass('prdctfltr_faded');
        }

        if (prodcutsWrapper !== false) {
            prodcutsWrapper.fadeTo(200, 0.5).addClass('prdctfltr_faded');
        }

        if (stopAjax === true) {
            shortcodeAjax = false;
            archiveAjax = false;
            stopAjax = false;
        }

        var curr_fields = {};
        var requested_filters = {};

        prdctfltr_get_loader(curr);

        $.each(ourObj, function(i, obj) {

            obj = $(obj);

            if (obj.find('input[name="reset_filter"]:checked').length > 0) {
                prdctfltr_reset_filters_550(obj);
            } else {
                prdctfltr_remove_empty_inputs_550(obj);
            }

            var pf_id = obj.attr('data-id');

            prdctfltr_remove_ranges_550(obj);

            prdctfltr_check_display_550(obj);

            if (!obj.hasClass('prdctfltr_mobile')) {
                requested_filters[pf_id] = pf_id;
            }

            if (!--pf_length) {

                $.each(ourObj, function(i, obj1) {
                    curr_fields[$(obj1).attr('data-id')] = prdctfltr_get_fields_550(obj1);
                });

                if (prdctfltr.analytics == 'yes') {
                    setTimeout(function() {
                        prdctfltr_post_analytics(curr_fields);
                    }, 250);
                }

                if (archiveAjax === true || shortcodeAjax === true) {

                    var pf_set = 'archive';
                    if (archiveAjax === true && !$('body').hasClass('prdctfltr-shop')) {
                        pf_set = 'shortcode';
                    } else {
                        pf_set = (archiveAjax === true ? 'archive' : 'shortcode');
                    }

                    var data = {
                        action: 'prdctfltr_respond_550',
                        pf_url: location.protocol + '//' + location.host + location.pathname,
                        pf_request: prdctfltr.js_filters,
                        pf_requested: requested_filters,
                        pf_shortcode: prdctfltr.js_filters[pf_id].atts,
                        pf_filters: curr_fields,
                        pf_set: pf_set,
                        pf_id: pf_id,
                        pf_paged: pf_paged,
                        pf_pagefilters: prdctfltr.pagefilters,
                        pf_restrict: pf_restrict,
                        pf_bulk: _get_pf_bulk()
                    };

                    if ( prdctfltr.wigetTitle != null ) {
                        data.pf_widget_title = __get_widget_title();
                    }

                    if ($('.prdctfltr_wc_widget').length > 0) {

                        var widget = $('.prdctfltr_wc_widget:first');

                        var rpl = $('<div></div>').append(widget.find('.pf-help-title:first').clone()).html().toString().replace(/\t/g, '');
                        var rpl_off = $('<div></div>').append(widget.find('.pf-help-title:first').find('.prdctfltr_widget_title').clone()).html().toString().replace(/\t/g, '');

                        rpl = rpl.replace(rpl_off, '%%%');

                        rpl = rpl.replace('<div class="pf-help-title">', '');
                        rpl = rpl.substr(0, rpl.length - 6);

                        data.pf_widget_title = $.trim(rpl);

                    }

                    if (typeof obj.attr('data-lang') !== 'undefined') {
                        data.lang = obj.attr('data-lang');
                    }

                    if (pf_offset > 0) {
                        data.pf_offset = pf_offset;
                    }

                    if ($(prdctfltr.ajax_orderby_class).length > 0) {
                        data.pf_orderby_template = 'set';
                    }

                    if ($(prdctfltr.ajax_count_class).length > 0) {
                        data.pf_count_template = 'set';
                    }

                    if (or_length == 1 && obj.hasClass('prdctfltr_step_filter')) {
                        data.pf_step = 1;
                        data.pf_set = 'shortcode';
                    }

                    if (pf_set == 'shortcode') {
                        if (prdctfltr.active_sc !== '') {
                            data.pf_active = prdctfltr.active_sc;
                        }
                    }

                    curr_filter.find('.pf_added_input').each(function() {
                        if (typeof data.pf_adds == 'undefined') {
                            data.pf_adds = {};
                        }
                        data.pf_adds[$(this).attr('name')] = $(this).val();
                    });

                    $.ajax({
                        type: 'POST',
                        url: prdctfltr.ajax,
                        data: data,
                        success: function(response) {
                            if (response) {
                                if (pf_offset > 0) {
                                    response.offset = pf_offset;
                                }
                                var getElement = shortcodeAjax === true ? prodcutsWrapper : false;
                                prdctfltr_handle_response_580(response, archiveAjax, shortcodeAjax, getElement);
                            }
                        },
                        error: function(response) {
                            alert('Error!');
                        }
                    });

                } else {

                    obj.find('.prdctfltr_filter input[type="hidden"]:not([name="post_type"]), .prdctfltr_filter input[name="s"], .prdctfltr_filter input[name="sale_products"], .prdctfltr_filter input[name="instock_products"]').each(function() {
                        obj.find('input[name="' + this.name + '"]:gt(0)').remove();
                    });

                    var cf_length = $.pfcount(curr_fields);

                    if (cf_length > 1) {
                        var notEmpty = false;
                        $.each(curr_fields, function(e1, w1) {
                            $.each(w1, function(k02, s02) {
                                notEmpty = true;
                                if (k02 != 's' && obj.find('input[name="' + k02 + '"]').length == 0) {
                                    obj.find('.prdctfltr_add_inputs').append('<input type="hidden" name="' + k02 + '" value="' + s02 + '" class="pf_added_input" />');
                                } else if (k02 != 's' && obj.find('input[name="' + k02 + '"]').length > 0) {
                                    obj.find('input[type="hidden"][name="' + k02 + '"]').val(s02);
                                }
                                if (k02 == 's' && obj.find('input[name="s"]').length == 0) {
                                    obj.find('.prdctfltr_add_inputs').append('<input type="hidden" name="s" value="' + s02 + '" class="pf_added_input" />');
                                }
                            });
                        });
                    }

                    if ($('.prdctfltr_wc input[name="orderby"][value="' + prdctfltr.orderby + '"]').length > 0) {
                        $('.prdctfltr_wc input[name="orderby"][value="' + prdctfltr.orderby + '"]').remove();
                    }

                    obj.find('.prdctfltr_woocommerce_ordering').submit();

                }

            }

        });

    }

    function _get_pf_bulk() {
        if ( typeof prdctfltr.bulk == 'undefined' ) {
            prdctfltr.bulk = $('.bulk-add-to-cart-tool').length > 0 ? true : false;
        }
    
        return prdctfltr.bulk;
    }

    function u(e) {
        return typeof e == 'undefined' ? false : e;
    }

    function prdctfltr_handle_response_580(response, archiveAjax, shortcodeAjax, getElement) {

        var ajax_length = prdctfltr_count_obj_580(response);
        var ajaxRefresh = {};
        var query = '';

        for (var n in response) {
            if (response.hasOwnProperty(n)) {
                var obj2 = response[n];

                if (n == 'products') {

                    if (!isStep) {

                        var obj3 = ($(obj2).find(prdctfltr.ajax_class).length > 0 ? $(obj2).find(prdctfltr.ajax_class) : $(obj2));

                        if (archiveAjax === true) {
                            var products = $(prdctfltr.ajax_class + ':first');
                        } else if (shortcodeAjax === true) {
                            var products = getElement === false ? $(prdctfltr.ajax_class + ':first') : getElement.find(prdctfltr.ajax_class);
                        } else {
                            var products = $(prdctfltr.ajax_class + ':first');
                        }

                        if (products.find('.pl-loops').length > 0) {
                            products = products.find('.pl-loops:first');
                        }

                        if (products.is('.pl-loops') && products.data('isotope')) {
                            if (typeof response.offset == 'undefined') {
                                products.isotope('remove', products.data('isotope').element.children);
                            }

                            if (obj3.find(prdctfltr.ajax_product_class).length > 0) {
                                products.isotope('insert', obj3.find(prdctfltr.ajax_product_class));
                            } else {
                                products.isotope('insert', obj3);
                            }

                            var container = products;
                            container.imagesLoaded(function() {
                                products.isotope('layout');
                            });
                        } else {
                            if (obj3.length < 1) {
                                products.empty();
                            } else {
                                if (typeof response.offset == 'undefined') {
                                    if (obj3.find(prdctfltr.ajax_product_class).length > 0 || obj3.find(prdctfltr.ajax_category_class).length > 0) {
                                        pf_animate_products(products, obj3, 'replace');
                                    } else {
                                        if (products.data('isotope')) {
                                            products.isotope('remove', products.data('isotope').element.children);
                                            products.isotope('insert', obj3);
                                        } else {
                                            products.empty().append(obj3[0].innerHTML);
                                        }
                                    }
                                } else {
                                    if (obj3.find(prdctfltr.ajax_product_class).length > 0 || obj3.find(prdctfltr.ajax_category_class).length > 0) {
                                        pf_animate_products(products, obj3, historyActive === false ? 'append' : 'replace');
                                    }
                                    response.products = $('<div></div>').append(products.clone().removeAttr('style').removeClass('prdctfltr_faded')).html();
                                }
                            }
                        }

                        if ($(obj2).find(prdctfltr.ajax_count_class).length > 0) {
                            response.count = $(obj2).find(prdctfltr.ajax_count_class).outerHTML();
                        }

                        $('.prdctfltr_faded').fadeTo(200, 1).removeClass('prdctfltr_faded');

                        setTimeout(function() {
                            pf_get_scroll(products, 0);
                        }, 200);

                    }
                } else if (n == 'pagination') {

                    getElement === false ? $(prdctfltr.ajax_class + ':first') : getElement.find(prdctfltr.ajax_class);

                    if (archiveAjax === true && $('body').hasClass('prdctfltr-shop')) {
                        var pagination = (prdctfltr.ajax_pagination_type == 'default' ? $(prdctfltr.ajax_pagination_class) : $('.' + prdctfltr.ajax_pagination_type));
                    } else if (shortcodeAjax === true) {
                        if (getElement === false) {
                            getElement = $(prdctfltr.ajax_class + ':first');
                        }

                        var pagination = getElement.find(prdctfltr.ajax_pagination_class);
                        if (pagination.length < 1) {
                            pagination = getElement.find('.prdctfltr-pagination-default');
                        }
                        if (pagination.length < 1) {
                            pagination = getElement.find('.prdctfltr-pagination-load-more');
                        }
                        if (pagination.length < 1) {
                            pagination = $(prdctfltr.ajax_pagination_class);
                        }
                    } else if (shortcodeAjax === false) {
                        var pagination = $(prdctfltr.ajax_pagination_class);
                        if (pagination.length < 1) {
                            pagination = $('.prdctfltr-pagination-default');
                        }
                        if (pagination.length < 1) {
                            pagination = $('.prdctfltr-pagination-load-more');
                        }
                    }

                    if (!isStep && typeof products !== 'undefined' && products.find(prdctfltr.ajax_product_class).length > 0) {

                        obj2 = $(obj2);

                        if (obj2 !== '') {
                            if (pagination.length < 1) {
                                if ($('.pf_pagination_dummy').length == 0) {
                                    if (shortcodeAjax === true) {
                                        getElement.find(prdctfltr.ajax_class + ':first').after('<div class="pf_pagination_dummy"></div>');
                                    } else {
                                        $(prdctfltr.ajax_class + ':first').after('<div class="pf_pagination_dummy"></div>');
                                    }
                                }

                                pagination = $('.pf_pagination_dummy');
                            }
                        }

                        if (obj2.length < 1) {
                            pagination.empty();
                        } else {
                            $.each(pagination, function() {
                                $(this).replaceWith(obj2[0].outerHTML);
                            });
                        }

                    } else {
                        pagination.empty();
                    }
                } else if (n == 'js_filters') {
                    obj2 = $(obj2);
                    $.each(obj2[0], function(i, f) {
                        prdctfltr.js_filters[i] = f;
                    });
                } else if (n == 'prdctfltr') {
                    obj2 = $(obj2);
                    prdctfltr = obj2[0];
                } else if (n == 'ranges') {
                    obj2 = $(obj2);
                    prdctfltr.rangefilters = obj2[0];
                } else if (n == 'orderby') {

                    obj2 = $(obj2);

                    $.each($(prdctfltr.ajax_orderby_class), function() {
                        $(this).replaceWith(obj2[0].outerHTML);
                    });

                } else if (n == 'count') {

                    obj2 = $(obj2);

                    if (obj2.length < 1) {
                        $(prdctfltr.ajax_count_class).html(prdctfltr.localization.noproducts);
                    } else {
                        $.each($(prdctfltr.ajax_count_class), function() {
                            $(this).replaceWith(obj2[0].outerHTML);
                        });
                    }

                } else if (n == 'query') {
                    if (prdctfltr.permalinks !== 'yes') {
                        query = (obj2 == '' ? location.protocol + '//' + location.host + location.pathname : obj2);
                    } else {
                        query = location.protocol + '//' + location.host + location.pathname;
                    }
                } else if (n.substring(0, 9) == 'prdctfltr') {
                    obj2 = $(obj2);

                    if (obj2.hasClass('prdctfltr_wc')) {
                        if (pf_offset > 0 && $(response.products).find(prdctfltr.ajax_product_class).length > 0 || pf_offset == 0) {
                            if ($('.prdctfltr_wc[data-id="' + n + '"]').length > 0) {
                                $('.prdctfltr_wc[data-id="' + n + '"]').replaceWith(obj2);
                                ajaxRefresh[n] = n;
                            }
                        } else {
                            $('.prdctfltr_wc[data-id="' + n + '"]').find('.prdctfltr_woocommerce_filter').replaceWith(obj2.find('.prdctfltr_woocommerce_filter'));
                        }
                        if ($('.prdctfltr_wc[data-id="' + n + '"] + .prdctfltr_mobile + .prdctfltr_mobile').length > 0) {
                            $('.prdctfltr_wc[data-id="' + n + '"] + .prdctfltr_mobile + .prdctfltr_mobile').remove();
                        }
                    } else if (obj2.hasClass('prdctfltr-widget')) {
                        if ($('.prdctfltr_wc[data-id="' + n + '"]').length > 0) {
                            $('.prdctfltr_wc[data-id="' + n + '"]').closest('.prdctfltr-widget').replaceWith(obj2);
                            ajaxRefresh[n] = n;
                        }
                    }
                } else if (n == 'title' && obj2 !== '') {
                    if ($('h1').length > 0) {

                        $.each($('h1'), function() {
                            $(this).html(obj2[0].innerHTML);
                        });

                    }
                } else if (n == 'desc') {
                    if (pf_paged < 2 && obj2 !== '') {
                        if ($('.term-description').length > 0) {

                            $.each($('.term-description'), function() {
                                $(this).html(obj2[0].innerHTML);
                            });

                        } else if ($('.page-description').length > 0) {

                            $.each($('.page-description'), function() {
                                $(this).html(obj2[0].innerHTML);
                            });

                        }
                    } else {
                        if ($('.term-description').length > 0) {

                            $.each($('.term-description'), function() {
                                $(this).html('');
                            });

                        }
                        if ($('.page-description').length > 0) {

                            $.each($('.page-description'), function() {
                                $(this).html('');
                            });

                        }
                    }
                }

            }

            if (!--ajax_length) {

                if (!$.isEmptyObject(ajaxRefresh)) {
                    $.each(ajaxRefresh, function(m, obj4) {
                        after_ajax($('.prdctfltr_wc[data-id="' + m + '"]'));
                        if ($('.prdctfltr_wc[data-id="' + m + '"]').next().is('.prdctfltr_mobile')) {
                            after_ajax($('.prdctfltr_wc[data-id="' + m + '"]').next());
                        }
                    });
                }

                $(document.body).trigger('post-load');

                $(document).trigger('prdctfltr-reload');

                if (prdctfltr.js !== '') {
                    eval(prdctfltr.js);
                }

                if (historyActive === false && (archiveAjax || $('body').hasClass('prdctfltr-sc')) === true /*&& pf_offset == 0*/ ) {
                    if (query.indexOf('https:') > -1 && location.protocol != 'https:') {
                        query = query.replace('https:', 'http:');
                    } else if (query.indexOf('http:') > -1 && location.protocol != 'http:') {
                        query = query.replace('http:', 'https:');
                    }

                    if (pf_offset > 0) {
                        query += query.indexOf('?') > -1 ? '&offset=' + pf_offset : '?offset=' + pf_offset;
                    }

                    var historyId = guid();

                    makeHistory[historyId] = response;
                    makeHistory[historyId].prdctfltr = prdctfltr;
                    history.pushState({ filters: historyId, archiveAjax: archiveAjax, shortcodeAjax: shortcodeAjax }, document.title, query);
                }

                ajaxActive = false;
                pf_paged = 1;
                pf_offset = 0;
                pf_restrict = '';

            }

        }

    }

    var historyActive = false;

    if (archiveAjax === true || $('body').hasClass('prdctfltr-sc')) {

        window.addEventListener('popstate', function(e) {
            if (ajaxActive === false && historyActive === false) {
                historyActive = true;
                ajaxActive = true;
                var state = typeof history.state != 'undefined' ? history.state : null;
                if (state != null) {
                    if (typeof state.filters !== 'undefined' && typeof makeHistory[state.filters] !== 'undefined') {
                        prdctfltr_handle_response_580(makeHistory[state.filters], state.archiveAjax, state.shortcodeAjax, false);
                    } else if (typeof pageFilters !== 'undefined') {
                        prdctfltr_handle_response_580(pageFilters, ($('body').hasClass('prdctfltr-ajax') || $('body').hasClass('prdctfltr-sc') ? true : false), false, false);
                    }
                }
                setTimeout(function() {
                    historyActive = false;
                }, 500);
            }
        });
    }

    $(window).load(function() {
        $('.pf_mod_masonry .prdctfltr_filter_inner').each(function() {
            $(this).isotope('layout');
        });
    });

    if ($('.prdctfltr-widget').length == 0 || $('.prdctfltr-widget .prdctfltr_error').length == 1) {

        $(window).on('resize', function() {

            $('.prdctfltr_woocommerce').each(function() {

                var curr = $(this);

                if (curr.hasClass('pf_mod_row')) {

                    if (window.matchMedia('(max-width: 768px)').matches) {
                        curr.find('.prdctfltr_filter_inner').css('width', 'auto');
                    } else {
                        var curr_columns = curr.find('.prdctfltr_filter_wrapper:first').attr('data-columns');

                        var curr_scroll_column = curr.find('.prdctfltr_woocommerce_ordering').width();
                        var curr_columns_length = curr.find('.prdctfltr_filter').length;

                        curr.find('.prdctfltr_filter_inner').css('width', curr_columns_length * curr_scroll_column / curr_columns);
                        curr.find('.prdctfltr_filter').css('width', curr_scroll_column / curr_columns);
                    }
                }
            });
        });
    }

    if ((/Trident\/7\./).test(navigator.userAgent)) {
        $(document).on('click', '.prdctfltr_checkboxes label img', function() {
            $(this).parents('label').children('input:first').change().click();
        });
    }

    if ((/Trident\/4\./).test(navigator.userAgent)) {
        $(document).on('click', '.prdctfltr_checkboxes label > span > img, .prdctfltr_checkboxes label > span', function() {
            $(this).parents('label').children('input:first').change().click();
        });
    }

    function prdctfltr_filter_results(currThis, list, searchIn, curr_filter) {

        var filter = currThis.val();
        var curr = currThis.closest('.prdctfltr_filter');

        if (filter) {

            if (curr.find('div.prdctfltr_sub').length > 0) {
                $(list).find('.prdctfltr_sub').prev().addClass('prdctfltr_show_subs');
                if (curr.hasClass('prdctfltr_searching') === false) {
                    curr.addClass('prdctfltr_searching');
                }
            }
            $(list).find(searchIn + ' > span:not(:Contains(' + filter + '))').closest('label').attr('style', 'display:none !important');
            $(list).find(searchIn + ' > span:Contains(' + filter + ')').closest('label').show();
            curr.find('.pf_more').hide();

        } else {

            if (curr.find('div.prdctfltr_sub').length > 0) {
                $(list).find('.prdctfltr_sub').prev().removeClass('prdctfltr_show_subs');
            }

            curr.removeClass('prdctfltr_searching');
            $(list).find(searchIn).show();

            var checkboxes = curr.find('.prdctfltr_checkboxes');

            checkboxes.each(function() {
                var max = parseInt(curr.attr('data-limit'), 10);
                if (max > 0 && $(list).find(searchIn).length > max) {
                    $(list).find(searchIn).slice(max).attr('style', 'display:none !important');
                    $(list).find('.pf_more').html('<span>' + prdctfltr.localization.show_more + '</span>').removeClass('pf_activated');
                }
            });
            curr.find('.pf_more').show();
        }

        if (curr_filter.hasClass('pf_mod_masonry')) {
            curr_filter.find('.prdctfltr_filter_inner').isotope('layout');
        }
        if (curr.hasClass('prdctfltr_expand_parents')) {
            prdctfltr_all_cats(curr_filter);
        }

        return false;
    }

    function prdctfltr_filter_terms_init(curr) {
        curr = (curr == null ? $('.prdctfltr_woocommerce') : curr);

        curr.find('.prdctfltr_add_search:not(.prdctfltr_terms_customized_system,.prdctfltr_terms_customized_selectize) .prdctfltr_add_scroll').each(function() {
            var list = $(this);
            prdctfltr_filter_terms(list);
        });
    }

    function prdctfltr_filter_terms(list) {

        var curr_filter = list.closest('.prdctfltr_wc');
        var form = $("<div>").attr({ "class": "prdctfltr_search_terms", "action": "#" });
        var input = $("<input>").attr({ "class": "prdctfltr_search_terms_input prdctfltr_reset_this", "type": "text", "placeholder": prdctfltr.localization.filter_terms });

        if (curr_filter.hasClass('pf_select') || curr_filter.hasClass('pf_default_select') || list.closest('.prdctfltr_filter').hasClass('prdctfltr_terms_customized_select')) {
            $(form).append(input).prependTo(list);
        } else {
            $(form).append(input).insertBefore(list);
        }

        if (list.closest('.prdctfltr_filter').hasClass('pf_adptv_default')) {
            var searchIn = 'label:not(.pf_adoptive_hide)';
        } else {
            var searchIn = 'label';
        }

        var timeoutId = 0;

        $(input).change(function() {

                var filter = $(this);

                clearTimeout(timeoutId);
                timeoutId = setTimeout(function() { prdctfltr_filter_results(filter, list, searchIn, curr_filter); }, 500);

            })
            .keyup(function() {
                $(this).change();
            });

    }

    $(document).on('click', '.prdctfltr_sc_products ' + prdctfltr.ajax_class + ' ' + prdctfltr.ajax_category_class + ' a, .prdctfltr-shop.prdctfltr-ajax ' + prdctfltr.ajax_class + ' ' + prdctfltr.ajax_category_class + ' a', function() {

        var curr = $(this).closest(prdctfltr.ajax_category_class);

        var curr_sc = (curr.closest('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter)').length > 0 ? curr.closest('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter)') : $('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter):first').length > 0 ? $('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter):first') : $('.prdctfltr_woocommerce:not(.prdctfltr_step_filter):first').length > 0 ? $('.prdctfltr_woocommerce:not(.prdctfltr_step_filter):first') : 'none');

        if (curr_sc == 'none') {
            return;
        }

        if (curr_sc.hasClass('prdctfltr_sc_products')) {
            var curr_filter = (curr_sc.find('.prdctfltr_woocommerce:not(.prdctfltr_step_filter)').length > 0 ? curr_sc.find('.prdctfltr_woocommerce:not(.prdctfltr_step_filter):not(.prdctfltr_mobile)') : $('.prdctfltr-widget').find('.prdctfltr_woocommerce:not(.prdctfltr_mobile)'));
        } else if ($('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter)').length == 0) {
            var curr_filter = curr_sc;
        } else {
            return;
        }

        var cat = curr.find('.prdctfltr_cat_support').data('slug');

        var hasFilter = curr_filter.find('.prdctfltr_filter[data-filter="product_cat"] input[type="checkbox"][value="' + cat + '"]:first');

        if (hasFilter.length > 0) {
            ajaxActive = true;
            $.each(curr_filter.find('.prdctfltr_filter[data-filter="product_cat"] label.prdctfltr_active'), function() {
                $(this).trigger('click');
            });
            setTimeout(function() {
                ajaxActive = false;
                hasFilter.closest('label').trigger('click');
                if (!curr_filter.hasClass('prdctfltr_click_filter')) {
                    curr_filter.find('.prdctfltr_woocommerce_filter_submit').trigger('click');
                }
            }, 25);
        } else {
            var hasField = curr_filter.find('.prdctfltr_filter[data-filter="product_cat"]');

            if (hasField.length > 0) {
                hasField.find('input[name="product_cat"]').val(cat);
            } else {
                var append = $('<input name="product_cat" type="hidden" value="' + cat + '" />');
                curr_filter.find('.prdctfltr_add_inputs').append(append);
            }

            if (!curr_filter.hasClass('prdctfltr_click_filter')) {
                curr_filter.find('.prdctfltr_woocommerce_filter_submit').trigger('click');
            } else {
                prdctfltr_respond_550(curr_filter.find('form'));
            }
        }

        return false;

    });

    if ($('body.prdctfltr-ajax ' + prdctfltr.ajax_orderby_class).length > 0) {

        $(document).on('submit', 'body.prdctfltr-ajax ' + prdctfltr.ajax_orderby_class, function() {
            return false;
        });

        $(document).on('change', 'body.prdctfltr-ajax ' + prdctfltr.ajax_orderby_class + ' select', function() {

            var orderVal = $(this).val();

            $('.prdctfltr_wc form').each(function() {

                if ($(this).closest('.prdctfltr_sc').length == 0) {

                    if ($(this).find('.prdctfltr_orderby input[type="checkbox"][val="' + orderVal + '"]').length > 0) {
                        $(this).find('.prdctfltr_orderby input[type="checkbox"][val="' + orderVal + '"]').trigger('click');
                    } else {
                        $(this).find('.prdctfltr_add_inputs').append('<input name="orderby" value="' + orderVal + '" />');
                        prdctfltr_respond_550($(this));
                    }

                }

            });

        });

    }

    if ($('.prdctfltr_sc.prdctfltr_ajax ' + prdctfltr.ajax_orderby_class).length > 0) {
        $(document).on('submit', '.prdctfltr_sc.prdctfltr_ajax ' + prdctfltr.ajax_orderby_class, function() {
            return false;
        });

        $(document).on('change', '.prdctfltr_sc.prdctfltr_ajax ' + prdctfltr.ajax_orderby_class + ' select', function() {

            var orderVal = $(this).val();
            var shortcodeForm = $(this).closest('.prdctfltr_sc').find('form');

            shortcodeForm.find('.prdctfltr_add_inputs').append('<input name="orderby" value="' + orderVal + '" />');
            prdctfltr_respond_550(shortcodeForm);

        });
    }



    function pf_get_scroll(products, offset) {

        var objOffset = -1;

        if (products.length == 0) {
            objOffset = $('.prdctfltr_wc:first').offset().top;
        } else {
            if (offset > 0) {

                var thisWrap = (products.find(prdctfltr.ajax_product_class + ':gt(' + (offset - 1) + ')').length > 0 ? products.find(prdctfltr.ajax_product_class + ':gt(' + (offset - 1) + ')') : products.find(prdctfltr.ajax_product_class + ':last'));

                objOffset = thisWrap.offset().top;

            } else {
                if (prdctfltr.ajax_scroll == 'products') {
                    objOffset = (products.find(prdctfltr.ajax_product_class + ':first').length > 0 ? products.find(prdctfltr.ajax_product_class + ':first').offset().top : products.offset().top);
                } else if (prdctfltr.ajax_scroll == 'top') {
                    objOffset = 0;
                } else if (prdctfltr.ajax_scroll == 'filter') {
                    if (products.closest('.prdctfltr_sc_products').find('.prdctfltr_wc').length > 0) {
                        objOffset = products.closest('.prdctfltr_sc_products').find('.prdctfltr_wc').offset().top;
                    } else {
                        objOffset = $('.prdctfltr_wc:first').offset().top;
                    }
                }
            }
        }

        if (objOffset > -1) {
            scrollTo(parseInt(objOffset, 10));
        }

    }

    function pf_animate_products(products, obj2, type) {
        var newProducts = obj2.find(prdctfltr.ajax_product_class);

        if (newProducts.length > 0) {

            if (products.data('isotope')) {
                if (type == 'replace') {
                    products.isotope('remove', products.data('isotope').element.children);
                }

                products.isotope('insert', newProducts);

                var container = products;
                container.imagesLoaded(function() {
                    products.isotope('layout');
                    $('.prdctfltr_faded').fadeTo(200, 1).removeClass('prdctfltr_faded');
                });
            } else {

                var beforeLength = products.find(prdctfltr.ajax_product_class).length;

                if (type == 'replace') {
                    products.empty();

                    var hasCats = obj2.find(prdctfltr.ajax_category_class);

                    if (hasCats.length > 0) {
                        products.append(hasCats);
                    }
                }

                products.append(newProducts);

                var addedProducts = (type == 'replace' || historyActive === true ? products.find(prdctfltr.ajax_product_class) : products.find(prdctfltr.ajax_product_class + ':gt(' + beforeLength + ')'));
                if (typeof addedProducts !== 'undefined') {

                    var dr = parseInt(prdctfltr.animation.duration, 10);
                    var dl = parseInt(prdctfltr.animation.delay, 10);

                    switch (prdctfltr.ajax_animation) {
                        case 'slide':
                            addedProducts.hide();
                            addedProducts.each(function(i) {
                                $(this).delay((i++) * dl).slideDown({ duration: dr, easing: 'linear' });
                            });
                            break;
                        case 'random':
                            addedProducts.not('.pf_faded').css('opacity', '0');
                            var interval = setInterval(function() {
                                var $ds = addedProducts.not('.pf_faded');
                                $ds.eq(Math.floor(Math.random() * $ds.length)).fadeTo(dr, 1).addClass('pf_faded');
                                if ($ds.length == 1) {
                                    clearInterval(interval);
                                }
                            }, dl);
                            break;
                        case 'none':
                            break;
                        default:
                            addedProducts.css('opacity', '0');
                            addedProducts.each(function(i) {
                                $(this).delay((i++) * dl).fadeTo(dr, 1);
                            });
                            break;
                    }
                }
            }
        }

    }

    function do_zindexes(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.each(function() {
            if ($(this).hasClass('pf_select')) {
                var objCount = $(this).find('.prdctfltr_filter');
            } else {
                var objCount = $(this).find('.prdctfltr_terms_customized_select');
            }


            var c = objCount.length;
            objCount.css('z-index', function(i) {
                return c - i + 10;
            });

        });
    }
    do_zindexes();

    function prdctfltr_show_opened_widgets() {

        if ($('.prdctfltr-widget').length > 0 && $('.prdctfltr-widget .prdctfltr_error').length !== 1) {
            $('.prdctfltr-widget .prdctfltr_filter').each(function() {

                var curr = $(this);

                if (curr.find('input[type="checkbox"]:checked').length > 0) {

                    curr.find('.prdctfltr_widget_title .prdctfltr-down').removeClass('prdctfltr-down').addClass('prdctfltr-up');
                    curr.find('.prdctfltr_add_scroll').addClass('prdctfltr_down').css({ 'display': 'block' });

                } else if (curr.find('input[type="hidden"]:first').length == 1 && curr.find('input[type="hidden"]:first').val() !== '') {

                    curr.find('.prdctfltr_widget_title .prdctfltr-down').removeClass('prdctfltr-down').addClass('prdctfltr-up');
                    curr.find('.prdctfltr_add_scroll').addClass('prdctfltr_down').css({ 'display': 'block' });

                } else if (curr.find('input[type="text"]').length > 0 && curr.find('input[type="text"]').val() !== '') {

                    curr.find('.prdctfltr_widget_title .prdctfltr-down').removeClass('prdctfltr-down').addClass('prdctfltr-up');
                    curr.find('.prdctfltr_add_scroll').addClass('prdctfltr_down').css({ 'display': 'block' });

                }

            });
        }

    }
    prdctfltr_show_opened_widgets();


    function prdctfltr_tabbed_selection(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.each(function() {
            if ($(this).hasClass('prdctfltr_tabbed_selection')) {
                $(this).find('.prdctfltr_filter').each(function() {
                    if ($(this).find('input[type="hidden"]:first').length > 0 && $(this).find('input[type="hidden"]').val() !== '') {
                        $(this).addClass('prdctfltr_has_selection');
                    }
                    if ($(this).find('input[type="text"]:first').length > 0 && $(this).find('input[type="text"]').val() !== '') {
                        $(this).addClass('prdctfltr_has_selection');
                    }
                });
            }
        });
    }
    prdctfltr_tabbed_selection();

    $.pfcount = function(array) {
        if (array.length) {
            return array.length;
        } else {
            var length = 0;
            for (var p in array) {
                if (array.hasOwnProperty(p)) length++;
            }
            return length;
        }
    };

    function check_shortcode_search() {
        var wg = $('.prdctfltr_wc_widget');
        var sc = $('.prdctfltr_sc_products:not(.prdctfltr_sc_step_filter)');
        if (wg.length > 0 && sc.length > 0) {
            wg.each(function() {
                $(this).find('input[name="s"]').each(function() {
                    $(this).attr('name', 'search_products');
                });
                if (!$(this).hasClass('prdctfltr_mobile')) {
                    var id = $(this).attr('data-id');
                    if (typeof prdctfltr.pagefilters[id] == 'undefined') {
                        var done = false;
                        $.each(prdctfltr.pagefilters, function(i, e) {
                            if (!done) {
                                if (typeof e.wcsc !== 'undefined') {
                                    prdctfltr.pagefilters[id] = e;
                                    done = true;
                                }
                                if (typeof e.wc !== 'undefined' && typeof e.query_vars.show_products !== 'undefined' && e.query_vars.show_products == 'yes') {
                                    prdctfltr.pagefilters[id] = e;
                                    done = true;
                                }
                            }
                        });
                    }
                }
            });
        }
    }
    check_shortcode_search();

    var infiniteLoad = $('.prdctfltr-pagination-infinite-load');

    function fixScroll() {
        didScroll = true;
    }

    function scrollHandler() {

        if (infiniteLoad.find('a.disabled').length == 0 && $(window).scrollTop() >= infiniteLoad.position().top - $(window).height() * 0.8) {
            infiniteLoad.find('a:not(.disabled)').trigger('click');
        }

    };

    if (infiniteLoad.length > 0) {

        var scrollTimeout;

        $(window).on({
            'scroll': fixScroll
        });

        var didScroll = false;

        var scrollInterval = setInterval(function() {
            if (didScroll) {
                didScroll = false;
                if (ajaxActive !== false || historyActive !== false) {
                    return false;
                }
                scrollHandler();
            }
        }, 250);

    }

    function scrollTo(to) {
        to = to > -1 ? to - 130 : 0;
        var start = $(window).scrollTop(),
            duration = parseInt((Math.abs(to - start) + 1000) / 7.5, 10),
            change = to - start,
            currentTime = 0,
            increment = 20;
        var animateScroll = function() {
            currentTime += increment;
            var val = Math.easeInOutQuad(currentTime, start, change, duration);
            window.scrollTo(0, val);

            if (currentTime < duration) {
                setTimeout(animateScroll, increment);
            }
        };
        animateScroll();
    }

    Math.easeInOutQuad = function(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    };

    function prdctfltr_added_check(curr) {

        curr = (curr == null ? $('.prdctfltr_wc:visible') : curr);

        curr.each(function() {
            var adds = {};
            var obj = $(this);

            obj.find('.prdctfltr_attributes').each(function() {

                var attribute = $(this);
                var valOf = attribute.find('input[type="hidden"]:first');
                var makeVal = valOf.val();

                if (typeof makeVal !== 'undefined' && makeVal !== '') {

                    var vals = [];

                    if (makeVal.indexOf(',') > 0) {
                        vals = makeVal.split(',');
                    } else if (makeVal.indexOf('+') > 0) {
                        vals = makeVal.split('+');
                    } else {
                        vals[0] = makeVal;
                    }

                    var filter = $(this);

                    var lenght = vals.length;

                    $.each(vals, function(i, val23) {

                        if (curr.find('.prdctfltr_filter[data-filter="' + valOf.attr('name') + '"] input[type="checkbox"][value="' + val23 + '"]').length == 0) {

                            var dataFilter = filter.attr('data-filter');

                            if (typeof adds[dataFilter] == 'undefined') {
                                adds[dataFilter] = [];
                            }
                            if ($.inArray(val23, adds[dataFilter]) == -1) {
                                adds[dataFilter].push(val23);
                                valOf.val('');
                            }
                            obj.each(function() {
                                var wrap = $(this);
                                wrap.find('.prdctfltr_add_inputs').append('<input name="' + dataFilter + '" value="' + makeVal + '" class="pf_added_input" />');
                            });
                        }

                    });
                }

            });
        });
    }
    prdctfltr_added_check();

    prdctfltr.goRespond = function() {
        prdctfltr_respond_550($('.prdctfltr_wc:first form'));
    }

    jQuery.fn.outerHTML = function(s) {
        return s ?
            this.before(s).remove() :
            jQuery("<p>").append(this.eq(0).clone()).html();
    };

    function __do_adoptive_search_term(o,d,m) {
        if ( m ) {
            o.addClass('pf_adoptive_hide');
            if ( d == 'unclick' || d == 'default' ) {
                o.attr('disabled', true);
            }
        }
    }
    function __do_adoptive_select_term(o,d,m) {
        if ( m ) {
            o.addClass('pf_adoptive_hide');
            if ( d == 'unclick' ) {
                o.attr('disabled', true);
            }
        }
    }

    function __get_adoptive(e) {
        var c = e.closest('.prdctfltr_filter');
        if ( c.length>0 ) {
            if ( c.hasClass('pf_adptv_unclick') ) {
                return 'unclick';
            }

            if ( c.hasClass('pf_adptv_default') ) {
                return 'default';
            }

            if ( c.hasClass('pf_adptv_click') ) {
                return 'click';
            }
        }
        return false;
    }

    function _fix_system_selects(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.find('.prdctfltr_terms_customized_system').each(function() {
            var t = $(this);
            var s = $('<select/>').addClass('pf-system-select');
            var d = __get_adoptive($(this));

            t.find('.prdctfltr_checkboxes label').each(function() {
                var p = $(this).parents('.prdctfltr_sub').length;

                var o = $('<option>').attr('value', $(this).find('input[type="checkbox"]:first').val()).text((p>0?'-'.repeat(p)+' ':'')+$(this).text());

                s.append(o);

                if ( d !== false ) {
                    __do_adoptive_select_term(o,d,$(this).hasClass('pf_adoptive_hide'));
                }
            });

            t.find('.prdctfltr_checkboxes label.prdctfltr_active').each(function() {
                s.find('option[value="' + $(this).find('input[type="checkbox"]:first').val() + '"]').attr('selected', true).addClass('pf-selected').prepend('✓ ');
            });

            t.find('.prdctfltr_add_scroll').prepend(s);
        });
    }
    _fix_system_selects();

    $(document).on('change', '.pf-system-select', function() {
        if ($(this).val() === null || $(this).val() == '') {
            $(this).find('.pf-selected').each(function() {
                $(this).removeClass('pf-selected').text($(this).text().replace('✓ ', ''));
            });

            $(this).parent().find('.prdctfltr_checkboxes label.prdctfltr_active').each(function() {
                $(this).trigger('click');
            });
        } else {
            var o = $(this).find('option[value="' + $(this).val() + '"]');

            if (o.hasClass('pf-selected')) {
                o.removeClass('pf-selected').text(o.text().replace('✓ ', ''));
            } else {
                o.addClass('pf-selected').prepend('✓ ');
            }

            $(this).parent().find('input[value="' + $(this).val() + '"]').parent().trigger('click');
        }
    });


    function _fix_search_selects(curr) {
        curr = (curr == null ? $('.prdctfltr_wc') : curr);

        curr.find('.prdctfltr_terms_customized_selectize').each(function() {
            var t = $(this);
            var s = $('<select/>').addClass('pf-search-select');
            var d = __get_adoptive($(this));

            if ($(this).hasClass('prdctfltr_multi')) {
                s.attr('multiple', true);
            }

            t.find('.prdctfltr_checkboxes label').each(function() {
                var p = $(this).parents('.prdctfltr_sub').length;

                var o = $('<option>').attr('value', $(this).find('input[type="checkbox"]:first').val()).text((p>0?'-'.repeat(p)+' ':'')+$(this).text());
              
                s.append(o);

                if ( d !== false ) {
                    __do_adoptive_search_term(o,d,$(this).hasClass('pf_adoptive_hide'));
                }
            });

            t.find('.prdctfltr_checkboxes label.prdctfltr_active').each(function() {
                s.find('option[value="' + $(this).find('input[type="checkbox"]:first').val() + '"]').attr('selected', true);
            });

            t.find('.prdctfltr_add_scroll').prepend(s);
        });

        curr.find('.prdctfltr_terms_customized_selectize select').each(function() {
            var s = $(this);
            var sf = $(this).closest('.prdctfltr_filter');

            s.selectize({
                plugins: s.prop('multiple')?['remove_button']:[],
                delimiter: ',',
            
                onChange: function(i) {
                    if ( i == '' || i === null ) {
                        sf.find('label.prdctfltr_active').trigger('click');
                    }
                    else {
                        if ( typeof i == 'string' ) {
                            i = [i];
                        }

                        $.each( i, function(b,c) {
                            sf.find('label:not(.prdctfltr_active) input[value="' + c + '"]').parent().trigger('click');
                        } );
                        
                        $.each( sf.find('label.prdctfltr_active input[type="checkbox"]'), function() {
                            if ( i.indexOf($(this).val()) == -1 ) {
                                $(this).parent().trigger('click');
                            }
                        } );
                    }
                }
            });
        });

    }
    _fix_search_selects();

    Array.prototype.diff = function(a) {
        return this.filter(function(i) {return a.indexOf(i) < 0;});
    };

    function reorder_limit(curr) {
        curr = (typeof curr == 'undefined' ? $('.prdctfltr_wc') : curr);

        curr.each(function() {

            $(this).find('.prdctfltr_attributes, .prdctfltr_meta').each(function() {

                var max = parseInt($(this).attr('data-limit'), 10);

                if (max < 1) {
                    return false;
                }

                var searchIn = $(this).hasClass('pf_adptv_default') ? 'label:not(.pf_adoptive_hide)' : 'label';

                $(this).find('.prdctfltr_checkboxes').each(function() {
                    if ($(this).find(searchIn).length > max) {
                        $(this).find(searchIn).slice(max).attr('style', 'display:none !important')
                        $(this).append($('<div class="pf_more"><span>' + prdctfltr.localization.show_more + '</span></div>'));
                    }
                });
            });
        });
    }
   reorder_limit();

})(jQuery);