// This code was written by Tyler Akins and has been placed in the
// public domain.  It would be nice if you left this header intact.
// Base64 code from Tyler Akins -- http://rumkin.com
function decode64(a){var b=new StringMaker;var c,d,e;var f,g,h,i;var j=0;a=a.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(j<a.length){f=keyStr.indexOf(a.charAt(j++));g=keyStr.indexOf(a.charAt(j++));h=keyStr.indexOf(a.charAt(j++));i=keyStr.indexOf(a.charAt(j++));c=f<<2|g>>4;d=(g&15)<<4|h>>2;e=(h&3)<<6|i;b.append(String.fromCharCode(c));if(h!=64){b.append(String.fromCharCode(d))}if(i!=64){b.append(String.fromCharCode(e))}}return b.toString()}function encode64(a){var b=new StringMaker;var c,d,e;var f,g,h,i;var j=0;while(j<a.length){c=a.charCodeAt(j++);d=a.charCodeAt(j++);e=a.charCodeAt(j++);f=c>>2;g=(c&3)<<4|d>>4;h=(d&15)<<2|e>>6;i=e&63;if(isNaN(d)){h=i=64}else if(isNaN(e)){i=64}b.append(keyStr.charAt(f)+keyStr.charAt(g)+keyStr.charAt(h)+keyStr.charAt(i))}return b.toString()}var StringMaker=function(){this.parts=[];this.length=0;this.append=function(a){this.parts.push(a);this.length+=a.length};this.prepend=function(a){this.parts.unshift(a);this.length+=a.length};this.toString=function(){return this.parts.join("")}};var keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="