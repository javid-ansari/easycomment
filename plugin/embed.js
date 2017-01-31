/*
Script Name: easyComment
Script URI: http://easycomment.akbilisim.com/
Author: akbilisim.com
Author URI: http://www.akbilisim.com
Version: 1.0
License: GNU General Public License
License URI: http://easycomment.akbilisim.com/license.html
Tags: comment, php comment, ajax comment, comment script, comment system, jquery comment, php ajax comment, user management, comment system, comments, discussions, comment themes, rating system, admin panel 

(C) 2015 akbilisim.com.
*/
Array.prototype.forEach||(Array.prototype.forEach=function(a){"use strict";if(void 0===this||null===this||"function"!=typeof a)throw new TypeError;for(var b=Object(this),c=b.length>>>0,d=arguments.length>=2?arguments[1]:void 0,e=0;c>e;e++)e in b&&a.call(d,b[e],e,b)}),Function.prototype.bind||(Function.prototype.bind=function(a){if("function"!=typeof this)throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");var b=Array.prototype.slice.call(arguments,1),c=this,d=function(){},e=function(){return c.apply(this instanceof d?this:a,b.concat(Array.prototype.slice.call(arguments)))};return d.prototype=this.prototype,e.prototype=new d,e}),function(a,b){function c(a){var c=b[a];b[a]=function(a){return e(c(a))}}function d(b,c,d){return(d=this).attachEvent("on"+b,function(b){b=b||a.event,b.preventDefault=b.preventDefault||function(){b.returnValue=!1},b.stopPropagation=b.stopPropagation||function(){b.cancelBubble=!0},c.call(d,b)})}function e(a,b){if(b=a.length)for(;b--;)a[b].addEventListener=d;else a.addEventListener=d;return a}a.addEventListener||(e([b,a]),"Element"in a?a.Element.prototype.addEventListener=d:(b.attachEvent("onreadystatechange",function(){e(b.all)}),c("getElementsByTagName"),c("getElementById"),c("createElement"),e(b.all)))}(window,document);!function(a){"use strict";function b(b,c,d){"addEventListener"in a?b.addEventListener(c,d,!1):"attachEvent"in a&&b.attachEvent("on"+c,d)}function c(){var b,c=["moz","webkit","o","ms"];for(b=0;b<c.length&&!E;b+=1)E=a[c[b]+"RequestAnimationFrame"];E||f(" RequestAnimationFrame not supported")}function d(){var b="Host page";return a.top!==a.self&&(b=a.parentIFrame?a.parentIFrame.getId():"Nested host page"),b}function e(a){return B+"["+d()+"]"+a}function f(b){x&&"object"==typeof a.console&&console.log(e(b))}function g(b){"object"==typeof a.console&&console.warn(e(b))}function h(b){function c(){function a(){l(K),j()}h("Height"),h("Width"),m(a,K,"resetPage")}function d(a){var b=a.id;f(" Removing iFrame: "+b),a.parentNode.removeChild(a),G[b].closedCallback(b),delete G[b],f(" --")}function e(){var a=J.substr(C).split(":");return{iframe:G[a[0]].iframe,id:a[0],height:a[1],width:a[2],type:a[3]}}function h(a){var b=Number(G[L]["max"+a]),c=Number(G[L]["min"+a]),d=a.toLowerCase(),e=Number(K[d]);if(c>b)throw new Error("Value for min"+a+" can not be greater than max"+a);f(" Checking "+d+" is in range "+c+"-"+b),c>e&&(e=c,f(" Set "+d+" to min value")),e>b&&(e=b,f(" Set "+d+" to max value")),K[d]=""+e}function p(){function a(){function a(){f(" Checking connection is from allowed list of origins: "+d);var a;for(a=0;a<d.length;a++)if(d[a]===c)return!0;return!1}function b(){var a=G[L].remoteHost;return f(" Checking connection is from: "+a),c===a}return d.constructor===Array?a():b()}var c=b.origin,d=G[L].checkOrigin;if(d&&""+c!="null"&&!a())throw new Error("Unexpected message received from: "+c+" for "+K.iframe.id+". Message was: "+b.data+". This error can be disabled by setting the checkOrigin: false option or by providing of array of trusted domains.");return!0}function q(){return B===(""+J).substr(0,C)&&J.substr(C).split(":")[0]in G}function r(){var a=K.type in{"true":1,"false":1,undefined:1};return a&&f(" Ignoring init message from meta parent page"),a}function s(a){return J.substr(J.indexOf(":")+A+a)}function t(a){f(" MessageCallback passed: {iframe: "+K.iframe.id+", message: "+a+"}"),G[L].messageCallback({iframe:K.iframe,message:JSON.parse(a)}),f(" --")}function u(){return null===K.iframe?(g(" IFrame ("+K.id+") not found"),!1):!0}function v(a){var b=a.getBoundingClientRect();return i(),{x:parseInt(b.left,10)+parseInt(D.x,10),y:parseInt(b.top,10)+parseInt(D.y,10)}}function w(b){function c(){D=h,y(),f(" --")}function d(){return{x:Number(K.width)+e.x,y:Number(K.height)+e.y}}var e=b?v(K.iframe):{x:0,y:0},h=d();f(" Reposition requested from iFrame (offset x:"+e.x+" y:"+e.y+")"),a.top!==a.self?a.parentIFrame?a.parentIFrame["scrollTo"+(b?"Offset":"")](h.x,h.y):g(" Unable to scroll to requested position, window.parentIFrame not found"):c()}function y(){!1!==G[L].scrollCallback(D)&&j()}function z(b){function c(a){var b=v(a);f(" Moving to in page link (#"+d+") at x: "+b.x+" y: "+b.y),D={x:b.x,y:b.y},y(),f(" --")}var d=b.split("#")[1]||"",e=decodeURIComponent(d),g=document.getElementById(e)||document.getElementsByName(e)[0];a.top!==a.self?a.parentIFrame?a.parentIFrame.moveToAnchor(d):f(" In page link #"+d+" not found and window.parentIFrame not found"):g?c(g):f(" In page link #"+d+" not found")}function E(){switch(G[L].firstRun&&I(),K.type){case"close":d(K.iframe);break;case"message":t(s(6));break;case"scrollTo":w(!1);break;case"scrollToOffset":w(!0);break;case"inPageLink":z(s(9));break;case"reset":k(K);break;case"init":c(),G[L].initCallback(K.iframe),G[L].resizedCallback(K);break;default:c(),G[L].resizedCallback(K)}}function F(a){var b=!0;return G[a]||(b=!1,g(K.type+" No settings for "+a+". Message was: "+J)),b}function H(){for(var a in G)n("iFrame requested init",o(a),document.getElementById(a),a)}function I(){G[L].firstRun=!1,Function.prototype.bind&&(G[L].iframe.iFrameResizer={close:d.bind(null,G[L].iframe),resize:n.bind(null,"Window resize","resize",G[L].iframe),moveToAnchor:function(a){n("Move to anchor","inPageLink:"+a,G[L].iframe,L)},sendMessage:function(a){a=JSON.stringify(a),n("Send Message","message:"+a,G[L].iframe,L)}})}var J=b.data,K={},L=null;"[iFrameResizerChild]Ready"===J?H():q()?(K=e(),L=K.id,!r()&&F(L)&&(x=G[L].log,f(" Received: "+J),u()&&p()&&E())):f(" Ignored: "+J)}function i(){null===D&&(D={x:void 0!==a.pageXOffset?a.pageXOffset:document.documentElement.scrollLeft,y:void 0!==a.pageYOffset?a.pageYOffset:document.documentElement.scrollTop},f(" Get page position: "+D.x+","+D.y))}function j(){null!==D&&(a.scrollTo(D.x,D.y),f(" Set page position: "+D.x+","+D.y),D=null)}function k(a){function b(){l(a),n("reset","reset",a.iframe,a.id)}f(" Size reset requested by "+("init"===a.type?"host page":"iFrame")),i(),m(b,a,"init")}function l(a){function b(b){a.iframe.style[b]=a[b]+"px",f(" IFrame ("+e+") "+b+" set to "+a[b]+"px")}function c(b){y||"0"!==a[b]||(y=!0,f(" Hidden iFrame detected, creating visibility listener"),s())}function d(a){b(a),c(a)}var e=a.iframe.id;G[e].sizeHeight&&d("height"),G[e].sizeWidth&&d("width")}function m(a,b,c){c!==b.type&&E?(f(" Requesting animation frame"),E(a)):a()}function n(a,b,c,d){d=d||c.id,c&&c.contentWindow?(f("["+a+"] Sending msg to iframe["+d+"] ("+b+")"),c.contentWindow.postMessage(B+b,G[d].targetOrigin)):(g("["+a+"] IFrame("+d+") not found"),G[d]&&delete G[d])}function o(a){return a+":"+G[a].bodyMarginV1+":"+G[a].sizeWidth+":"+G[a].log+":"+G[a].interval+":"+G[a].enablePublicMethods+":"+G[a].autoResize+":"+G[a].bodyMargin+":"+G[a].heightCalculationMethod+":"+G[a].bodyBackground+":"+G[a].bodyPadding+":"+G[a].tolerance+":"+G[a].inPageLinks+":"+G[a].resizeFrom+":"+G[a].widthCalculationMethod}function p(a,c){function d(){function b(b){1/0!==G[t][b]&&0!==G[t][b]&&(a.style[b]=G[t][b]+"px",f(" Set "+b+" = "+G[t][b]+"px"))}b("maxHeight"),b("minHeight"),b("maxWidth"),b("minWidth")}function e(b){return""===b&&(a.id=b="iFrameResizer"+w++,x=(c||{}).log,f(" Added missing iframe ID: "+b+" ("+a.src+")")),b}function h(){f(" IFrame scrolling "+(G[t].scrolling?"enabled":"disabled")+" for "+t),a.style.overflow=!1===G[t].scrolling?"hidden":"auto",a.scrolling=!1===G[t].scrolling?"no":"yes"}function i(){("number"==typeof G[t].bodyMargin||"0"===G[t].bodyMargin)&&(G[t].bodyMarginV1=G[t].bodyMargin,G[t].bodyMargin=""+G[t].bodyMargin+"px")}function j(){var b=G[t].firstRun,c=G[t].heightCalculationMethod in F;!b&&c&&k({iframe:a,height:0,width:0,type:"init"})}function l(c){function d(){n("iFrame.onload",c,a),j()}b(a,"load",d),n("init",c,a)}function m(a){if("object"!=typeof a)throw new TypeError("Options is not an object.")}function p(a){for(var b in I)I.hasOwnProperty(b)&&(G[t][b]=a.hasOwnProperty(b)?a[b]:I[b])}function q(a){return(""===a||"file://"===a)&&(a="*"),a}function r(b){b=b||{},G[t]={firstRun:!0,iframe:a,remoteHost:a.src.split("/").slice(0,3).join("/")},m(b),p(b),G[t].targetOrigin=!0===G[t].checkOrigin?q(G[t].remoteHost):"*",x=G[t].log}function s(){return t in G&&"iFrameResizer"in a}var t=e(a.id);s()?g(" Ignored iFrame, already setup."):(r(c),h(),d(),i(),l(o(t)))}function q(a,b){null===H&&(H=setTimeout(function(){H=null,a()},b))}function r(a){return null!==a.offsetParent}function s(){function b(){function a(a){function b(b){return"0px"===G[a].iframe.style[b]}r(G[a].iframe)&&(b("height")||b("width"))&&n("Visibility change","resize",G[a].iframe,a)}for(var b in G)a(b)}function c(a){f(" Mutation observed: "+a[0].target+" "+a[0].type),q(b,16)}function d(){var a=document.querySelector("body"),b={attributes:!0,attributeOldValue:!1,characterData:!0,characterDataOldValue:!1,childList:!0,subtree:!0},d=new e(c);d.observe(a,b)}var e=a.MutationObserver||a.WebKitMutationObserver;e&&d()}function t(){function c(a){function b(){e("Window "+a,"resize")}f(" Trigger event: "+a),q(b,16)}function d(){function a(){e("Tab Visable","resize")}"hidden"!==document.visibilityState&&(f(" Trigger event: Visiblity change"),q(a,16))}function e(a,b){function c(a){return"parent"===G[a].resizeFrom&&G[a].autoResize&&!G[a].firstRun}for(var d in G)c(d)&&n(a,b,document.getElementById(d),d)}b(a,"message",h),b(a,"resize",function(){c("resize")}),b(document,"visibilitychange",d),b(document,"-webkit-visibilitychange",d),b(a,"focusin",function(){c("focus")}),b(a,"focus",function(){c("focus")})}function u(){function a(a,b){if(!b.tagName)throw new TypeError("Object is not a valid DOM element");if("IFRAME"!==b.tagName.toUpperCase())throw new TypeError("Expected <IFRAME> tag, found <"+b.tagName+">.");p(b,a)}return c(),t(),function(b,c){switch(typeof c){case"undefined":case"string":Array.prototype.forEach.call(document.querySelectorAll(c||"iframe"),a.bind(void 0,b));break;case"object":a(b,c);break;default:throw new TypeError("Unexpected data type ("+typeof c+").")}}}function v(a){a.fn.iFrameResize=function(a){return this.filter("iframe").each(function(b,c){p(c,a)}).end()}}var w=0,x=!1,y=!1,z="message",A=z.length,B="[iFrameSizer]",C=B.length,D=null,E=a.requestAnimationFrame,F={max:1,scroll:1,bodyScroll:1,documentElementScroll:1},G={},H=null,I={autoResize:!0,bodyBackground:null,bodyMargin:null,bodyMarginV1:8,bodyPadding:null,checkOrigin:!0,inPageLinks:!1,enablePublicMethods:!0,heightCalculationMethod:"bodyOffset",interval:32,log:!1,maxHeight:1/0,maxWidth:1/0,minHeight:0,minWidth:0,resizeFrom:"parent",scrolling:!1,sizeHeight:!0,sizeWidth:!1,tolerance:0,widthCalculationMethod:"scroll",closedCallback:function(){},initCallback:function(){},messageCallback:function(){g("MessageCallback function not defined")},resizedCallback:function(){},scrollCallback:function(){return!0}};a.jQuery&&v(jQuery),"function"==typeof define&&define.amd?define([],u):"object"==typeof module&&"object"==typeof module.exports?module.exports=u():a.iFrameResize=a.iFrameResize||u()}(window||{});(function() {function createiframe(){

    if (typeof easyComment_Domain !== "undefined") {var d=easyComment_Domain;}else{$("#easyComment_Content").html("<center>Could not find valid Domain name!  Please see documentation file in <a href='/doc/index.html#config' target=_blank>here</a></center>"); return false;}

    if (typeof easyComment_Theme !== "undefined") {var t="theme="+easyComment_Theme+"&"; }else{t="";}

    if (typeof easyComment_Title !== "undefined") {var title="title="+easyComment_Title+"&"; }else{title="";}

    if (typeof easyComment_ContentID !== "undefined") {var id="C_id="+easyComment_ContentID+"&"; }else{$("#easyComment_Content").html("<center>Could not find valid ID! Please see documentation file in <a href='http://easycomment.akbilisim.com/doc.html' target=_blank>here</a></center>"); return false;}

    if (typeof easyComment_ContentURL !== "undefined") {var u="C_url="+btoa(easyComment_ContentURL)+"&"; }else{var u="C_url="+btoa(location.href)+"&";}

    if (typeof easyComment_ByUserID !== "undefined") {var go="comments/usercomments.php?"; }else{var go="comments?";}

    if (typeof easyComment_FooterLinks !== "undefined") {var footeroff="FooterLinks=off&"; }else{var footeroff=null;}

    var h="access="+btoa(window.location.hostname);

    var CUSER_ID="";var CUSER_NAME="";var CUSER_ICON="";var CUSER_LINK="";
    if (typeof easyComment_userid !== "undefined") { CUSER_ID="CUSER_ID="+easyComment_userid+"&"; }
    if (typeof easyComment_username !== "undefined") { CUSER_NAME="CUSER_NAME="+easyComment_username+"&"; }
    if (typeof easyComment_usericon !== "undefined"){ CUSER_ICON="CUSER_ICON="+easyComment_usericon+"&"; }
    if (typeof easyComment_profillink !== "undefined") { CUSER_LINK="CUSER_LINK="+easyComment_profillink+"&"; }


    var hashuser="";
    if (typeof easyComment_userid !== "undefined" && typeof easyComment_username !== "undefined" && typeof easyComment_usericon !== "undefined" && typeof easyComment_profillink !== "undefined") {

    hashuser = "user="+ btoa(CUSER_ID + CUSER_NAME + CUSER_ICON + CUSER_LINK);

    h=h+'|';

    }else if (typeof easyComment_userid == "undefined" && typeof easyComment_username == "undefined" && typeof easyComment_usericon == "undefined" && typeof easyComment_profillink == "undefined") {

    hashuser = "undefined";
    }

    if (typeof d == "undefined") {return "Domain name is not correct";}else{

     if (typeof t == "undefined") {t="default";}

        src = d + 'app/' + go + t + h+"&" + id + title + u + footeroff + hashuser;

        var a=document.createElement("iframe");a.setAttribute("src",src),a.setAttribute("id","easyComment"),a.setAttribute("allowTransparency","true"),a.setAttribute("frameBorder","0"),a.setAttribute("width","100%"),a.setAttribute("scrolling","no"),a.setAttribute("horizontalscrolling","no"),a.setAttribute("verticalscrolling","no");document.getElementById("easyComment_Content").innerHTML  =  "";document.getElementById("easyComment_Content").appendChild(a);a.onload = function() {iFrameResize({checkOrigin:false, enablePublicMethods     : true});}}}window.onload = function(){document.getElementById("easyComment_Content").innerHTML  =  "<center><img src='"+easyComment_Domain+"app/assets/images/ajax-loader.gif'></center>";setTimeout(function(){ createiframe(); }, 150);};})();