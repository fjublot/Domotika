// JavaScript Document
document.write('<script src="'+BaseURL+'/check_user.cgi?user='+User+'&pwd='+Password+'"></script>');
document.write('<script src="'+BaseURL+'/get_status.cgi?user='+User+'&pwd='+Password+'"></script>');
document.write('<script src="'+BaseURL+'/get_camera_params.cgi?user='+User+'&pwd='+Password+'"></script>');

var sSnapUrl=BaseURL+Flux;
var img = new Image();
var imgObj;

function preload()
{
	img.src=sSnapUrl+new Date;
}

function changesrc()
{
	live.src=img.src;
	preload();
	setTimeout(changesrc,3500);
}

function update()
{
	imgObj = document.getElementById('live');
	
	imgObj.src = img.src;
	img.src = sSnapUrl + '&'+(new Date()).getTime();
}

function takeError()
{
	img.src = sSnapUrl + (new Date()).getTime();
}

function startonload()
{
	img.src = sSnapUrl + '&'+(new Date()).getTime();
	img.onerror=takeError;
	img.onload=update;
}

function Update_Live()
{
	if (navigator.appName.indexOf("Microsoft IE Mobile") != -1)
	{
		preload();
		changesrc();
		return;
	}
	startonload();
}

var szCmdUrl=BaseURL+'/decoder_control.cgi?onestep=1&user='+User+'&pwd='+Password;

function ptzUpSubmit()
{
	new Image().src = szCmdUrl + "&command=0&" + (new Date()).getTime();
}

function ptzDownSubmit()
{
	new Image().src = szCmdUrl + "&command=2&" + (new Date()).getTime();
}

function ptzLeftSubmit()
{
	new Image().src = szCmdUrl + "&command=4&" + (new Date()).getTime();
}

function ptzRightSubmit()
{
	new Image().src = szCmdUrl + "&command=6&" + (new Date()).getTime();
}
function OnSwitchOff()
{
	new Image().src =szCmdUrl + "&command=94&" + (new Date()).getTime();
}
function OnSwitchOn()
{
	new Image().src = szCmdUrl + "&command=95&" + (new Date()).getTime();
}
//-->

function callcmd(cmd){
new Image().src=BaseURL+'/decoder_control.cgi?user='+User+'&pwd='+Password+'&command='+cmd;
}
function getcookie(name)
{
	var strCookie=document.cookie;
  var arrCookie=strCookie.split('; ');
  for (var i=0;i<arrCookie.length;i++)
  {
		var arr=arrCookie[i].split('=');
    if (arr[0]==name)
			return unescape(arr[1]);
  }
  return '';
}
function setcookie(name,value,expirehours)
{
	var cookieString=name+'='+escape(value);
  if (expirehours>0)
  {
		var date=new Date();
    date.setTime(date.getTime()+expirehours*3600*1000);
    cookieString=cookieString+'; expires='+date.toGMTString();
	}
  document.cookie=cookieString;
}
function showerror(camera,msg,err)
{
	var err_info;
	switch(err)
	{
	case 0:
		err_info='';
		break;
	case -1:
		err_info=_err_connect;
		break;
	case -2:
		err_info=_err_socket;
		break;
	case -3:
		err_info=_err_timeout;
		break;
	case -4:
		err_info=_err_version;
		break;
	case -5:
		err_info=_err_cancel;
		break;
	case -6:
		err_info=_err_closed;
		break;
	case -8:
		err_info=_err_file;
		break;
	case -9:
		err_info=_err_param;
		break;
	case -10:
		err_info=_err_thread;
		break;
	case -11:
		err_info=_err_status;
		break;
	case -12:
		err_info=_err_id;
		break;
	case 1:
		err_info=_fail_user;
		break;
	case 2:
		err_info=_fail_maxconns;
		break;
	case 3:
		err_info=_fail_version;
		break;
	case 4:
		err_info=_fail_id;
		break;
	case 5:
		err_info=_fail_pwd;
		break;
	case 6:
		err_info=_fail_pri;
		break;
	case 7:
		err_info=_fail_unsupport;
		break;	
	case 8:
		err_info=_fail_forbidden;
		break;	
	default:
		err_info=_err_unknown;
		break;
	}
	if (camera=='')
		alert(msg+' : '+err_info);
	else
		alert(camera+' : '+msg+' : '+err_info);
}

function  isIE(){ 
  if  (window.navigator.userAgent.toString().toLowerCase().indexOf("msie") >=1)
   return  true;
  else
   return  false;
}

if(!isIE()){  //firefox  innerText  define
   HTMLElement.prototype.__defineGetter__(      "innerText",
    function(){
     var  anyString  =  "";
     var  childS  =  this.childNodes;
     for(var  i=0;  i <childS.length;  i++)  {
      if(childS[i].nodeType==1)
       anyString  +=  childS[i].tagName=="BR"  ?  '\n'  :  childS[i].innerText;
      else  if(childS[i].nodeType==3)
       anyString  +=  childS[i].nodeValue;
     }
     return  anyString;
    }
   );
   HTMLElement.prototype.__defineSetter__(      "innerText",
    function(sText){
     this.textContent=sText;
    }
   ); 
} 

var language=getcookie('language');
var rspeed=getcookie('ratespeed');
if(rspeed==''){
	rspeed=0;
}
document.write('<script src="cams/js/foscamptzmobile/var.js"><\/script>');
