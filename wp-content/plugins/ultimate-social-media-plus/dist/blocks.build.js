!function(e){function t(i){if(s[i])return s[i].exports;var o=s[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,t),o.l=!0,o.exports}var s={};t.m=e,t.c=s,t.d=function(e,s,i){t.o(e,s)||Object.defineProperty(e,s,{configurable:!1,enumerable:!0,get:i})},t.n=function(e){var s=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(s,"a",s),s},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});s(1)},function(e,t,s){"use strict";function i(e,t,s){return t in e?Object.defineProperty(e,t,{value:s,enumerable:!0,configurable:!0,writable:!0}):e[t]=s,e}function o(e){var t=e.jscode;return w("div",{className:"sfsi_plus_block_container"},"\n\t",w("div",{className:"sfsi_plus_block"},""),"\n\t",w("script",{},t),"\n")}function n(e){var t=e.jscode;"rectangle"===e.iconType&&(t=t.replace(/window.location.href/gi,'window.location.href+"&ractangle_icon=1"'));var s="yes",i="Please Share:";return e.showTextBeforeShare||""!==e.showTextBeforeShare?s=e.showTextBeforeShare:onAttrChange("showTextBeforeShare","yes"),e.textBeforeShare||""!==e.textBeforeShare?i=e.textBeforeShare:onAttrChange("textBeforeShare","Please Share:"),w("div",{className:"sfsi_plus_block_wrapper"},"\n\t","yes"==s&&w("span",{className:"sfsi_plus_block_text_before_icon"},i),"\n",w("div",{className:"sfsi_plus_block","data-count":e.maxPerRow,"data-align":e.iconAlignemt,"data-icon-type":e.iconType},""),"\n\t")}function l(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;null==t&&(t=jQuery(".wp-block.is-selected"));var s=t.find(" .sfsi_plus_wicons");if(console.log("timeset15",t,s,s.length),0==s.length)setTimeout(function(){var i=parseInt(s.css("width"))||40,o=parseInt(s.css("margin-left"))||0,n=(i+o)*e,l=t.find(".sfsi_plus_block_wrapper .sfsi_plus_wDiv img").first().height(),r=t.find(".sfsi_plus_block_text_before_icon").height();t.find(".sfsi_plus_block_text_before_icon").css({"margin-top":(r-l)/2-2+"px"});var c=t.find(" .sfsiplus_norm_row");console.log("timeset16",s,i,o,n,e,l,r,c,c.length),c.length<1?setTimeout(function(){c=t.find(".sfsiplus_norm_row"),c.css({width:n+"px"})},1e3):c.css({width:n+"px"}),a(t)});else{var i=parseInt(s.css("width"))||40,o=parseInt(s.css("margin-left"))||0,n=(i+o)*e,l=t.find(".sfsi_plus_block_wrapper .sfsi_plus_wDiv img").first().height(),r=t.find(".sfsi_plus_block_text_before_icon").height();t.find(".sfsi_plus_block_text_before_icon").css({"margin-top":(r-l)/2-2+"px"});var c=t.find(" .sfsiplus_norm_row");console.log("timeset11",i,o,n,e,l,r,c,c.length),c.length<1?setTimeout(function(){c=t.find(".sfsiplus_norm_row"),c.css({width:n+"px"})},1e3):c.css({width:n+"px"}),a(t)}}function a(e,t){console.log("timeset5",e,t);var s=e.find(".sfsi_plus_block").attr("data-align");jQuery(e).find(".sfsi_plus_block_text_before_icon").css({display:"inherit"}),jQuery(e).css({"text-align":s})}function r(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,s=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null;null!==e&&void 0!==e||(e="round"),null==s&&(s=$(document));var i="";return i=window.sfsi_plus_links&&window.sfsi_plus_links.rest_url?window.sfsi_plus_links.rest_url:window.sfsi_plus_links&&window.sfsi_plus_links.pretty_perma&&"no"===window.sfsi_plus_links.pretty_perma?"/index.php?rest_route=/":"/wp-json/",window.sfsi_plus_links&&window.sfsi_plus_links.pretty_perma&&"no"===window.sfsi_plus_links.pretty_perma?(i=i.replace(/\/$/,""),i+=encodeURI("/ultimate-social-media-plus/v1/icons/"),i+="&"):i+="ultimate-social-media-plus/v1/icons/?",i+="admin_refereal=true&ractangle_icon="+("round"==e?0:1),console.log("timeset6",s,t),jQuery.ajax({url:i,method:"GET"}).done(function(i){console.log("timeset1",s,t),jQuery(s).find(".sfsi_plus_block").length>0?(jQuery(s).find(".sfsi_plus_block").html(i),a(s,t),jQuery(s).find(".sfsi_plus_block_text_before_icon").css({display:"inherit"}),console.log("timeset2",s,t),l(t.maxPerRow,s),console.log("timeset8",s,t),console.log("timeset7",s,t),"round"!==e&&(console.log("timeset4"),c())):(setTimeout(function(){console.log("timeset3"),jQuery(".sfsi_plus_block").html(i),l(t.maxPerRow,s),jQuery(s).find(".sfsi_plus_block_text_before_icon").css({display:"inherit"})},5e3),console.log("timeset"))}).fail(function(e){jQuery(s).find(".sfsi_plus_block").html(e.responseText.replace("/\\/g",""))})}function c(){window.gapi&&(window.gapi.plusone.go(),window.gapi.plus.go(),window.gapi.ytsubscribe.go()),window.twttr&&window.twttr.widgets.load(),window.IN&&window.IN.parse&&window.IN.parse(),window.addthis&&(window.addthis.toolbox?window.addthis.toolbox(".addthis_button.sficn"):(window.addthis.init(),window.addthis.toolbox(".addthis_button.sficn"))),window.PinUtils&&window.PinUtils.build(),window.FB&&window.FB.XFBML&&window.FB.XFBML.parse()}var u=s(2),d=(s.n(u),s(3)),p=(s.n(d),wp.i18n.__),_=wp.blocks,f=_.registerBlockType,w=(_.RichText,_.TextControl,_.AlignmentToolbar,_.BlockControls,_.InspectorControls,wp.element.createElement),h=w("svg",{width:20,height:20},w("g",{transform:"translate(0.000000,20.000000) scale(0.0062,-0.0070)",fill:"#000000",stroke:"none"},w("path",{d:"M2055 2721 c-284 -83 -461 -332 -442 -624 l6 -89 -72 6 c-406 39 -818 246 -1090 548 l-66 73 -26 -60 c-101 -227 -55 -484 120 -661 l72 -74 -32 0 c-39 0 -127 26 -179 52 l-39 20 6 -74 c18 -224 178 -428 395 -504 58 -20 61 -22 35 -29 -15 -4 -72 -6 -126 -6 -98 1 -98 1 -92 -21 19 -62 77 -150 141 -214 88 -89 200 -148 317 -166 43 -7 77 -15 77 -18 0 -7 -152 -102 -205 -128 -72 -36 -216 -82 -302 -97 -46 -8 -146 -15 -221 -16 -159 -1 -160 2 10 -85 257 -131 542 -193 838 -181 209 8 392 45 572 115 l68 26 0 393 0 393 -100 0 c-93 0 -100 1 -100 20 0 11 -1 90 -1 175 0 85 1 160 1 165 0 6 40 10 100 10 l100 0 1 138 c1 144 3 170 20 241 34 147 165 265 319 288 49 8 174 9 358 5 l62 -2 0 -175 0 -175 -127 0 c-83 0 -137 -5 -153 -13 -35 -18 -46 -61 -49 -193 l-2 -114 165 0 166 0 0 -37 c0 -21 -7 -96 -15 -168 -8 -71 -15 -138 -15 -147 0 -16 -14 -18 -150 -18 l-150 0 0 -332 c1 -686 3 -637 -22 -642 -13 -3 -90 -8 -172 -12 -82 -3 -143 -10 -136 -14 6 -4 93 -8 191 -9 l179 -2 0 272 0 271 63 72 c119 134 198 250 273 397 113 225 184 512 184 745 l0 101 79 66 c64 54 226 235 217 244 -1 1 -26 -6 -55 -17 -59 -23 -176 -55 -235 -65 l-40 -7 50 39 c86 69 147 149 184 242 l19 49 -88 -43 c-69 -34 -199 -81 -276 -99 -5 -2 -37 19 -70 46 -150 122 -366 170 -540 119z"})));if(f("ultimate-social-media-plus/sfsi-plus-share-block",{title:p("Social Icons"),icon:h,category:"common",keywords:[p("Social Icons"),p("Social share"),p("Gutenberg Share")],attributes:{jscode:{default:"\n\t\tjQuery(document).ready(function($) {\n\t\t\tjQuery.ajax({\n\t\t\t\t'url': '/wp-json/ultimate-social-media-plus/v1/icons/?share_url='+window.location.href,\n\t\t\t\t'method': 'GET'\n\t\t\t}).done( function(response){\n\t\t\t\t$('.sfsi_plus_block_wrapper .sfsi_plus_block').html(response);sfsi_plus_update_iconcount();if(window.gapi){window.gapi.plusone.go();window.gapi.plus.go();window.gapi.ytsubscribe.go();};if(window.twttr){window.twttr.widgets.load();};if(window.IN){window.IN.parse();};if(window.addthis){if(window.addthis.toolbox){window.addthis.toolbox('.addthis_button.sficn');}else{window.addthis.init();window.addthis.toolbox('.addthis_button.sficn');}};if(window.PinUtils){window.PinUtils.build();};if(jQuery('.sfsi_plus_wDiv').length > 0) {setTimeout(function() { var s = parseInt(jQuery('.sfsi_plus_wDiv').height()) + 15 + 'px';jQuery('.sfsi_plus_holders').each(function() {jQuery(this).css('height', s);});jQuery('.sfsi_plus_widget').css('min-height', 'auto');}, 200);};if(window.FB){if(window.FB.XFBML){window.FB.XFBML.parse();}};\n\t\t\t});\n\t\t});\n\t",type:"string"},showTextBeforeShare:{type:"string",default:"yes"},textBeforeShare:{type:"string",default:"Please Share:"},iconType:{type:"string",default:"round"},iconAlignemt:{type:"string",default:"left"},maxPerRow:{type:"string",default:"5"}},edit:function(e){function t(t,s){e.setAttributes(i({},t,s))}var s=e.setAttributes,o=e.attributes,n="yes",a="Please Share:";o.showTextBeforeShare||""!==o.showTextBeforeShare?n=o.showTextBeforeShare:t("showTextBeforeShare","yes"),o.textBeforeShare||""!==o.textBeforeShare?a=o.textBeforeShare:t("textBeforeShare","Please Share:");var c=jQuery('div[data-block="'+e.clientId+'"]').find(".sfsi_plus_block_container");if(c.length>0){0===c.find(".sfsi_plus_block>div").length&&r(o.iconType,o,c)}else setTimeout(function(){var t=jQuery('div[data-block="'+e.clientId+'"]').find(".sfsi_plus_block_container");0===t.find(".sfsi_plus_block>div").length&&r(o.iconType,o,t)},3e3);return[w(wp.editor.InspectorControls,{key:"sfsi-plus-block-inspector"},w("div",{className:"sfsi_plus_block_inspector"},w("h3",{className:"sfsi_plus_block_icontype_header"},p("Type")),w("select",{className:"form-control sfsi_plus_block_icontype_selector",value:o.iconType,onChange:function(e){var t=jQuery(".wp-block.is-selected").find(".sfsi_plus_block_container");s({iconType:e.target.value}),r(e.target.value,o,t)}},w("option",{value:"round"},"Round / \xabmain\xbb icons"),w("option",{value:"rectangle"},"Rectangle icons")),("round"===e.attributes.iconType||void 0===e.attributes.iconType)&&w("p",{className:"sfsi_plus_block_icontype_desc"},p(" Those are the icons you selected under question 1 on the plugin\u2018s "),w("a",{target:"_blank",href:window.sfsi_plus_links.admin_url+"admin.php?page=sfsi-plus-options#ui-id-1"},p(" settings page."))),"rectangle"===e.attributes.iconType&&w("p",{className:"sfsi_plus_block_icontype_desc"},p("Those are the icons you selected "),w("a",{target:"_blank",href:window.sfsi_plus_links.admin_url+"admin.php?page=sfsi-plus-options#ui-id-5"},p("here."))),w("h3",{className:"sfsi_plus_block_icontype_header"},p("Alignment")),w("select",{className:"form-control sfsi_plus_block_iconalignment_selector",value:o.iconAlignemt,onChange:function(e){s({iconAlignemt:e.target.value});var t=jQuery(".wp-block.is-selected .sfsi_plus_block_container");"center"===e.target.value&&jQuery(t).find(".sfsi_plus_block_text_before_icon").css({display:"inherit"}),jQuery(t).css({"text-align":e.target.value})}},w("option",{value:"left"},"Left"),w("option",{value:"right"},"Right"),w("option",{value:"center"},"Center")),("round"===e.attributes.iconType||void 0===e.attributes.iconType)&&w("div",{className:"sfsi_plus_block_iconperrow_body"},w("span",{className:"label"},p("Max. icons per row")),w("input",{type:"text",value:o.maxPerRow,onChange:function(e){s({maxPerRow:(parseInt(e.target.value)||0)+""}),l(e.target.value)}})),w("label",{htmlFor:"sfsi-plus-text-before-icons",className:"sfsi_plus_block_textbeforeicons"},w("input",{className:"form-control",checked:"yes"==o.showTextBeforeShare,type:"checkbox",onChange:function(e){s({showTextBeforeShare:e.target.checked?"yes":"no"})}}),"Text before icons?"),"yes"==o.showTextBeforeShare&&w("input",{className:"form-input sfsi_plus_block_textbeforeicons_header",value:o.textBeforeShare,style:{"padding-top":"3px"},onChange:function(e){s({textBeforeShare:e.target.value})}}),"yes"===o.showTextBeforeShare&&w("div",{className:"form-input sfsi_plus_block_textbeforeicons_body"},p("Define the font size & type in our "),w("a",{href:"https://www.ultimatelysocial.com/usm-premium/",target:"_blank"},p("Premium plugin"))),w("h3",{className:"sfsi_plus_block_notes_heading"},p("Notes")),w("hr"),w("ul",{className:"sfsi_plus_block_notes_list"},w("li",{className:"sfsi_plus_block_notes_item"},p("For all other selections (What the icons should do etc.) please go to "),w("a",{href:(window.sfsi_plus_links?window.sfsi_plus_links.admin_url:"/wp-admin/admin.php")+"?page=sfsi-plus-options",target:"_blank"},p("settings page"))),w("li",{className:"sfsi_plus_block_notes_item"},p("To see the icons in \u201afull action\u2018 (with all features) please open the page in live or preview mode.")),w("li",{className:"sfsi_plus_block_notes_item"},p("If questions remain, please ask them in the "),w("a",{href:"https://goo.gl/ktAeDv",target:"_blank"},p("support forum")),p(" \u2013 we\u2018ll try to respond quickly."),w("img",{src:(window.sfsi_plus_links?window.sfsi_plus_links.plugin_dir_url:"/wp-content/plugins/ultimate-social-media-plus")+"/images/Ic_insert_emoticon_48px.svg",style:{width:"18px","vertical-align":"text-bottom"}}))),w("h3",{className:"sfsi_plus_block_ad_heading"},"Want (much) more?"),w("div",{className:"sfsi_plus_block_ad_body"},w("div",{},p("Check out our "),w("a",{href:"https://www.ultimatelysocial.com/usm-premium/?utm_source=plus_gutenberg_page&utm_campaign=side_widget&utm_medium=link",target:"_blank"},p("premium plugin\u2018s features")),p(". Watch a teaser: "))),w("div",{style:{"text-align":"center"}},w("iframe",{src:"https://video.inchev.com/videos/embed/c952d896-34be-45bc-8142-ba14694c1bd0",width:"640",frameborder:0,webkitallowfullscreen:"",mozallowfullscreen:"",allowfullscreen:""}),w("a",{href:"https://www.ultimatelysocial.com/usm-premium/?utm_source=plus_gutenberg_page&utm_campaign=side_widget&utm_medium=link",target:"_blank",style:{display:"inline-block",padding:"4px 10px","text-decoration":"none",background:"#00A15A",color:"#fff","font-size":"11px","font-weight":"900"}},p("Check out the Premium Plugin >>"))),w("br"),w("span",{className:"sfsi_plus_block_ad_footer"},p("..from 29.98 USD (includes support and updates for 6 months, and after that it will not be deactivated, so you can just keep using it!)")))),w("div",{key:"sfsi-plus-block-content",className:"sfsi_plus_block_container sfsi_plus_block_wrapper"},"\t","yes"==n&&w("span",{className:"sfsi_plus_block_text_before_icon","data-align":o.iconAlignemt},a),w("div",{className:"sfsi_plus_block","data-count":o.maxPerRow,"data-align":o.iconAlignemt,"data-icon-type":o.iconType},"loading...."))]},deprecated:[{attributes:{jscode:{default:"\n\t\tjQuery(document).ready(function($) {\n\t\t\tjQuery.ajax({\n\t\t\t\t'url': '/wp-json/ultimate-social-media-plus/v1/icons/?share_url='+window.location.href,\n\t\t\t\t'method': 'GET'\n\t\t\t}).done( function(response){\n\t\t\t\t$('.sfsi_plus_block_container .sfsi_plus_block').html(response);if(window.gapi){window.gapi.plusone.go();window.gapi.plus.go();window.gapi.ytsubscribe.go();};if(window.twttr){window.twttr.widgets.load();};if(window.IN){window.IN.parse();};if(window.addthis){if(window.addthis.toolbox){window.addthis.toolbox('.addthis_button.sficn');}else{window.addthis.init();window.addthis.toolbox('.addthis_button.sficn');}};if(window.PinUtils){window.PinUtils.build();};if(jQuery('.sfsi_plus_wDiv').length > 0) {setTimeout(function() { var s = parseInt(jQuery('.sfsi_plus_wDiv').height()) + 15 + 'px';jQuery('.sfsi_plus_holders').each(function() {jQuery(this).css('height', s);});jQuery('.sfsi_plus_widget').css('min-height', 'auto');}, 200);};if(window.FB){if(window.FB.XFBML){window.FB.XFBML.parse();}};\n\t\t\t});\n\t\t});\n\t",type:"string"}},isEligible:function(e){return console.log(e),!0},migrate:function(e){return console.log("migrate",e),[{jscode:"\n\t\tjQuery(document).ready(function($) {\n\t\t\tjQuery.ajax({\n\t\t\t\t'url': '/wp-json/ultimate-social-media-plus/v1/icons/?share_url='+window.location.href,\n\t\t\t\t'method': 'GET'\n\t\t\t}).done( function(response){\n\t\t\t\t$('.sfsi_plus_block_wrapper .sfsi_plus_block').html(response);sfsi_plus_update_iconcount();if(window.gapi){window.gapi.plusone.go();window.gapi.plus.go();window.gapi.ytsubscribe.go();};if(window.twttr){window.twttr.widgets.load();};if(window.IN){window.IN.parse();};if(window.addthis){if(window.addthis.toolbox){window.addthis.toolbox('.addthis_button.sficn');}else{window.addthis.init();window.addthis.toolbox('.addthis_button.sficn');}};if(window.PinUtils){window.PinUtils.build();};if(jQuery('.sfsi_plus_wDiv').length > 0) {setTimeout(function() { var s = parseInt(jQuery('.sfsi_plus_wDiv').height()) + 15 + 'px';jQuery('.sfsi_plus_holders').each(function() {jQuery(this).css('height', s);});jQuery('.sfsi_plus_widget').css('min-height', 'auto');}, 200);};if(window.FB){if(window.FB.XFBML){window.FB.XFBML.parse();}};\n\t\t\t});\n\t\t});\n\t",showTextBeforeShare:"yes",textBeforeShare:"Please Share:",iconType:"round",iconAlignemt:"left",maxPerRow:"5"}]},save:function(e){return console.log(e),o(e.attributes)}}],save:function(e){var t=e.attributes;return setTimeout(function(){l(t.maxPerRow)},300),n(t)}}),void 0===window.sfsi_plus_float_widget);},function(e,t){},function(e,t){}]);