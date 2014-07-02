/* Copyright (c) 2006 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * $LastChangedDate: 2007-12-20 09:02:08 -0600 (Thu, 20 Dec 2007) $
 * $Rev: 4265 $
 *
 * Version: 3.0
 * 
 * Requires: $ 1.2.2+
 */

(function(a){a.event.special.mousewheel={setup:function(){var b=a.event.special.mousewheel.handler;if(a.browser.mozilla)a(this).bind("mousemove.mousewheel",function(b){a.data(this,"mwcursorposdata",{pageX:b.pageX,pageY:b.pageY,clientX:b.clientX,clientY:b.clientY})});if(this.addEventListener)this.addEventListener(a.browser.mozilla?"DOMMouseScroll":"mousewheel",b,false);else this.onmousewheel=b},teardown:function(){var b=a.event.special.mousewheel.handler;a(this).unbind("mousemove.mousewheel");if(this.removeEventListener)this.removeEventListener(a.browser.mozilla?"DOMMouseScroll":"mousewheel",b,false);else this.onmousewheel=function(){};a.removeData(this,"mwcursorposdata")},handler:function(b){var c=Array.prototype.slice.call(arguments,1);b=a.event.fix(b||window.event);a.extend(b,a.data(this,"mwcursorposdata")||{});var d=0,e=true;if(b.wheelDelta)d=b.wheelDelta/120;if(b.detail)d=-b.detail/3;if(a.browser.opera)d=-b.wheelDelta;b.data=b.data||{};b.type="mousewheel";c.unshift(d);c.unshift(b);return a.event.handle.apply(this,c)}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery)