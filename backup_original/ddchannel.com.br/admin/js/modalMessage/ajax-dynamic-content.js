/************************************************************************************************************

Ajax dynamic content

Copyright (C) 2006  DTHMLGoodies.com, Alf Magne Kalleland



This library is free software; you can redistribute it and/or

modify it under the terms of the GNU Lesser General Public

License as published by the Free Software Foundation; either

version 2.1 of the License, or (at your option) any later version.



This library is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU

Lesser General Public License for more details.



You should have received a copy of the GNU Lesser General Public

License along with this library; if not, write to the Free Software

Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA



Dhtmlgoodies.com., hereby disclaims all copyright interest in this script

written by Alf Magne Kalleland.



Alf Magne Kalleland, 2006

Owner of DHTMLgoodies.com





************************************************************************************************************/	



function ajax_installScript(a){if(!a)return;if(window.execScript){window.execScript(a)}else if(window.jQuery&&jQuery.browser.safari){window.setTimeout(a,0)}else{window.setTimeout(a,0)}}function ajax_parseJs(a){var b=a.getElementsByTagName("SCRIPT");var c="";var d="";for(var e=0;e<b.length;e++){if(b[e].src){var f=document.getElementsByTagName("head")[0];var g=document.createElement("script");g.setAttribute("type","text/javascript");g.setAttribute("src",b[e].src)}else{if(navigator.userAgent.indexOf("Opera")>=0){d=d+b[e].text+"\n"}else d=d+b[e].innerHTML}}if(d)ajax_installScript(d)}function ajax_loadContent(a,b){if(enableCache&&jsCache[b]){document.getElementById(a).innerHTML=jsCache[b];return}var c=dynamicContent_ajaxObjects.length;document.getElementById(a).innerHTML='<div class="FonteGeralCarregando">     Carregando...     [<a href="#" onClick="closeMessage();">Cancelar<a/>]</div>';dynamicContent_ajaxObjects[c]=new sack;dynamicContent_ajaxObjects[c].requestFile=b;dynamicContent_ajaxObjects[c].onCompletion=function(){ajax_showContent(a,c,b)};dynamicContent_ajaxObjects[c].runAJAX()}function ajax_showContent(a,b,c){var d=document.getElementById(a);d.innerHTML=dynamicContent_ajaxObjects[b].response;if(enableCache){jsCache[c]=dynamicContent_ajaxObjects[b].response}dynamicContent_ajaxObjects[b]=false;ajax_parseJs(d)}var enableCache=true;var jsCache=new Array;var dynamicContent_ajaxObjects=new Array