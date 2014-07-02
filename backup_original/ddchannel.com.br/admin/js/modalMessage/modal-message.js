/************************************************************************************************************

*	DHTML modal dialog box

*

*	Created:						August, 26th, 2006

*	@class Purpose of class:		Display a modal dialog box on the screen.

*			

*	Css files used by this script:	modal-message.css

*

*	Demos of this class:			demo-modal-message-1.html

*

* 	Update log:

*

************************************************************************************************************/





/**

* @constructor

*/



DHTML_modalMessage=function(){var a;var b;var c;var d;var e;var f;var g;var h;var i;var j;var k;var l;var m;var n;this.url="";this.htmlOfModalMessage="";this.layoutCss="modal-message.css";this.height=200;this.width=400;this.cssClassOfMessageBox=false;this.shadowDivVisible=true;this.shadowOffset=5;this.MSIE=false;if(navigator.userAgent.indexOf("MSIE")>=0)this.MSIE=true};DHTML_modalMessage.prototype={setSource:function(a){this.url=a},setHtmlContent:function(a){this.htmlOfModalMessage=a},setSize:function(a,b){if(a)this.width=a;if(b)this.height=b},setCssClassMessageBox:function(a){this.cssClassOfMessageBox=a;if(this.divs_content){if(this.cssClassOfMessageBox)this.divs_content.className=this.cssClassOfMessageBox;else this.divs_content.className="modalDialog_contentDiv"}},setShadowOffset:function(a){this.shadowOffset=a},display:function(){if(!this.divs_transparentDiv){this.__createDivs()}this.divs_transparentDiv.style.display="block";this.divs_content.style.display="block";if(this.divs_shadow)this.divs_shadow.style.display="block";if(this.MSIE)this.iframe.style.display="block";this.__resizeDivs();window.refToThisModalBoxObj=this;setTimeout("window.refToThisModalBoxObj.__resizeDivs()",150);this.__insertContent()},setShadowDivVisible:function(a){this.shadowDivVisible=a},close:function(){this.divs_transparentDiv.style.display="none";this.divs_content.style.display="none";this.divs_shadow.style.display="none";if(this.MSIE)this.iframe.style.display="none"},addEvent:function(a,b,c,d){if(!d)d="";if(a.attachEvent){a["e"+b+c+d]=c;a[b+c+d]=function(){a["e"+b+c+d](window.event)};a.attachEvent("on"+b,a[b+c+d])}else a.addEventListener(b,c,false)},__createDivs:function(){this.divs_transparentDiv=document.createElement("DIV");this.divs_transparentDiv.className="modalDialog_transparentDivs";this.divs_transparentDiv.style.left="0px";this.divs_transparentDiv.style.top="0px";this.divs_transparentDiv.style.zIndex=8e4;document.body.appendChild(this.divs_transparentDiv);this.divs_content=document.createElement("DIV");this.divs_content.className="modalDialog_contentDiv";this.divs_content.id="DHTMLSuite_modalBox_contentDiv";this.divs_content.style.zIndex=1e5;if(this.MSIE){this.iframe=document.createElement("iframe");this.iframe.frameBorder=0;this.iframe.src="about:blank";this.iframe.style.zIndex=9e4;this.iframe.style.position="absolute";document.body.appendChild(this.iframe)}document.body.appendChild(this.divs_content);this.divs_shadow=document.createElement("DIV");this.divs_shadow.className="modalDialog_contentDiv_shadow";this.divs_shadow.style.zIndex=95e3;document.body.appendChild(this.divs_shadow);window.refToModMessage=this;this.addEvent(window,"scroll",function(a){window.refToModMessage.__repositionTransparentDiv()});this.addEvent(window,"resize",function(a){window.refToModMessage.__repositionTransparentDiv()})},__getBrowserSize:function(){var a=document.documentElement.clientWidth;var b=document.documentElement.clientHeight;var a,b;if(self.innerHeight){a=self.innerWidth;b=self.innerHeight}else if(document.documentElement&&document.documentElement.clientHeight){a=document.documentElement.clientWidth;b=document.documentElement.clientHeight}else if(document.body){a=document.body.clientWidth;b=document.body.clientHeight}return[a,b]},__resizeDivs:function(){var a=Math.max(document.body.scrollTop,document.documentElement.scrollTop);if(this.cssClassOfMessageBox)this.divs_content.className=this.cssClassOfMessageBox;else this.divs_content.className="modalDialog_contentDiv";if(!this.divs_transparentDiv)return;var b=Math.max(document.body.scrollTop,document.documentElement.scrollTop);var c=Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);window.scrollTo(c,b);setTimeout("window.scrollTo("+c+","+b+");",10);this.__repositionTransparentDiv();var d=this.__getBrowserSize();var e=d[0];var f=d[1];this.divs_content.style.width=this.width+"px";this.divs_content.style.height=this.height+"px";var g=this.divs_content.offsetWidth;var h=this.divs_content.offsetHeight;this.divs_content.style.left=Math.ceil((e-g)/2)+"px";this.divs_content.style.top=Math.ceil((f-h)/2)+a+"px";if(this.MSIE){this.iframe.style.left=this.divs_content.style.left;this.iframe.style.top=this.divs_content.style.top;this.iframe.style.width=this.divs_content.style.width;this.iframe.style.height=this.divs_content.style.height}this.divs_shadow.style.left=this.divs_content.style.left.replace("px","")/1+this.shadowOffset+"px";this.divs_shadow.style.top=this.divs_content.style.top.replace("px","")/1+this.shadowOffset+"px";this.divs_shadow.style.height=h+"px";this.divs_shadow.style.width=g+"px";if(!this.shadowDivVisible)this.divs_shadow.style.display="none"},__repositionTransparentDiv:function(){this.divs_transparentDiv.style.top=Math.max(document.body.scrollTop,document.documentElement.scrollTop)+"px";this.divs_transparentDiv.style.left=Math.max(document.body.scrollLeft,document.documentElement.scrollLeft)+"px";var a=this.__getBrowserSize();var b=a[0];var c=a[1];this.divs_transparentDiv.style.width=b+"px";this.divs_transparentDiv.style.height=c+"px"},__insertContent:function(){if(this.url){ajax_loadContent("DHTMLSuite_modalBox_contentDiv",this.url)}else{this.divs_content.innerHTML=this.htmlOfModalMessage}}}