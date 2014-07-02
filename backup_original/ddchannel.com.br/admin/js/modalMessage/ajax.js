/* Simple AJAX Code-Kit (SACK) v1.6.1 */

/* �2005 Gregory Wild-Smith */

/* www.twilightuniverse.com */

/* Software licenced under a modified X11 licence,

   see documentation or authors website for more details */



function sack(file){this.xmlhttp=null;this.resetData=function(){this.method="POST";this.queryStringSeparator="?";this.argumentSeparator="&";this.URLString="";this.encodeURIString=true;this.execute=false;this.element=null;this.elementObj=null;this.requestFile=file;this.vars=new Object;this.responseStatus=new Array(2)};this.resetFunctions=function(){this.onLoading=function(){};this.onLoaded=function(){};this.onInteractive=function(){};this.onCompletion=function(){};this.onError=function(){};this.onFail=function(){}};this.reset=function(){this.resetFunctions();this.resetData()};this.createAJAX=function(){try{this.xmlhttp=new ActiveXObject("Msxml2.XMLHTTP")}catch(a){try{this.xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")}catch(b){this.xmlhttp=null}}if(!this.xmlhttp){if(typeof XMLHttpRequest!="undefined"){this.xmlhttp=new XMLHttpRequest}else{this.failed=true}}};this.setVar=function(a,b){this.vars[a]=Array(b,false)};this.encVar=function(a,b,c){if(true==c){return Array(encodeURIComponent(a),encodeURIComponent(b))}else{this.vars[encodeURIComponent(a)]=Array(encodeURIComponent(b),true)}};this.processURLString=function(a,b){encoded=encodeURIComponent(this.argumentSeparator);regexp=new RegExp(this.argumentSeparator+"|"+encoded);varArray=a.split(regexp);for(i=0;i<varArray.length;i++){urlVars=varArray[i].split("=");if(true==b){this.encVar(urlVars[0],urlVars[1])}else{this.setVar(urlVars[0],urlVars[1])}}};this.createURLString=function(a){if(this.encodeURIString&&this.URLString.length){this.processURLString(this.URLString,true)}if(a){if(this.URLString.length){this.URLString+=this.argumentSeparator+a}else{this.URLString=a}}this.setVar("rndval",(new Date).getTime());urlstringtemp=new Array;for(key in this.vars){if(false==this.vars[key][1]&&true==this.encodeURIString){encoded=this.encVar(key,this.vars[key][0],true);delete this.vars[key];this.vars[encoded[0]]=Array(encoded[1],true);key=encoded[0]}urlstringtemp[urlstringtemp.length]=key+"="+this.vars[key][0]}if(a){this.URLString+=this.argumentSeparator+urlstringtemp.join(this.argumentSeparator)}else{this.URLString+=urlstringtemp.join(this.argumentSeparator)}};this.runResponse=function(){eval(this.response)};this.runAJAX=function(a){if(this.failed){this.onFail()}else{this.createURLString(a);if(this.element){this.elementObj=document.getElementById(this.element)}if(this.xmlhttp){var b=this;if(this.method=="GET"){totalurlstring=this.requestFile+this.queryStringSeparator+this.URLString;this.xmlhttp.open(this.method,totalurlstring,true)}else{this.xmlhttp.open(this.method,this.requestFile,true);try{this.xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded")}catch(c){}}this.xmlhttp.onreadystatechange=function(){switch(b.xmlhttp.readyState){case 1:b.onLoading();break;case 2:b.onLoaded();break;case 3:b.onInteractive();break;case 4:b.response=b.xmlhttp.responseText;b.responseXML=b.xmlhttp.responseXML;b.responseStatus[0]=b.xmlhttp.status;b.responseStatus[1]=b.xmlhttp.statusText;if(b.execute){b.runResponse()}if(b.elementObj){elemNodeName=b.elementObj.nodeName;elemNodeName.toLowerCase();if(elemNodeName=="input"||elemNodeName=="select"||elemNodeName=="option"||elemNodeName=="textarea"){b.elementObj.value=b.response}else{b.elementObj.innerHTML=b.response}}if(b.responseStatus[0]=="200"){b.onCompletion()}else{b.onError()}b.URLString="";break}};this.xmlhttp.send(this.URLString)}}};this.reset();this.createAJAX()}