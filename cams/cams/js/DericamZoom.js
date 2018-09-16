var R320_240=8;
var R640_480=32;
var ptz_type=0;	
if(top.client_minor==4) ptz_type=1;//485 decoder
if(top.client_minor==5) ptz_type=2;//=0,but center and v-patrol
if(top.client_minor==6) ptz_type=3;//=2,and up-down flip
var PTZ_STOP=1;
var TILT_UP=0;
var TILT_UP_STOP=1;
var TILT_DOWN=2;
var TILT_DOWN_STOP=3;
var PAN_LEFT=4;
var PAN_LEFT_STOP=5;
var PAN_RIGHT=6;
var PAN_RIGHT_STOP=7;
var PTZ_LEFT_UP=90;
var PTZ_RIGHT_UP=91;
var PTZ_LEFT_DOWN=92;
var PTZ_RIGHT_DOWN=93;
var PTZ_CENTER=25;
var PTZ_VPATROL=26;
var PTZ_VPATROL_STOP=27;
var PTZ_HPATROL=28;
var PTZ_HPATROL_STOP=29;
var PTZ_PELCO_D_HPATROL=20;
var PTZ_PELCO_D_HPATROL_STOP=21;
var PTZ_ZOOM_WIDE=16;
var PTZ_ZOOM_TELE=18;
var PTZ_ZOOM_WIDE_STOP=17;
var PTZ_ZOOM_TELE_STOP=19;
var P_1=31;
var P_2=33;
var P_3=35;
var P_4=37;
var P_5=39;
var P_6=41;
var P_7=43;
var P_8=45;
var IO_ON=94;
var IO_OFF=95;

function decoder_control(command)
{
	action_zone.location=BaseURL+'/decoder_control.cgi?command='+command+'&user='+User+'&pwd='+Password;
}
function camera_control(param,value)
{
	action_zone.location=BaseURL+'/camera_control.cgi?param='+param+'&value='+value+'&user='+User+'&pwd='+Password;
}
function preset_set()
{
	decoder_control(parseInt(preset.value));
}
function preset_go()
{
	decoder_control(parseInt(preset.value)+1);
}
function PT_patrol()
{
    decoder_control(P_1);
    
    setTimeout('p1()',10000);
}
function p1()
{
    decoder_control(P_2);
    setTimeout('p2()',10000);
}
function p2()
{
    decoder_control(P_3);
    setTimeout('p3()',10000);
}

function p3()
{
    decoder_control(P_4);
    setTimeout('p4()',10000);
}

function p4()
{
    decoder_control(P_5);
    setTimeout('p5()',10000);
}

function p5()
{
    decoder_control(P_6);
    setTimeout('p6()',10000);
}

function p6()
{
    decoder_control(P_7);
    setTimeout('p7()',10000);
}

function p7()
{
    decoder_control(P_8);
    setTimeout('p0()',10000);
}
function p0()
{
    decoder_control(P_1);
}

function set_flip()
{
	if (image_reversal.checked)
		flip|=1;
	else
		flip&=2;
	if (image_mirror.checked)
		flip|=2;
	else
		flip&=1;	
	camera_control(5,flip);
}
function up_onmousedown() 
{
	if(flip&0x01)
	    decoder_control(TILT_DOWN);
    else
        decoder_control(TILT_UP);
}
function up_onmouseup() 
{
	if (!ptz_type)
		decoder_control(PTZ_STOP);
	else if (flip&0x01)
		decoder_control(TILT_DOWN_STOP);
	else	
		decoder_control(TILT_UP_STOP);
}
function down_onmousedown() 
{
	if(flip&0x01)
	    decoder_control(TILT_UP);
	else
	    decoder_control(TILT_DOWN);
}
function down_onmouseup() 
{
	if (!ptz_type)
		decoder_control(PTZ_STOP);
	else if (flip&0x01)
		decoder_control(TILT_UP_STOP);
	else
		decoder_control(TILT_DOWN_STOP);	
}
function left_onmousedown() 
{
	(flip&0x02)?decoder_control(PAN_RIGHT):decoder_control(PAN_LEFT);
}
function left_onmouseup() 
{
	if (!ptz_type)
		decoder_control(PTZ_STOP);
	else if (flip&0x02)
		decoder_control(PAN_RIGHT_STOP);
	else	
		decoder_control(PAN_LEFT_STOP);	
}
function right_onmousedown() 
{
	(flip&0x02)?decoder_control(PAN_LEFT):decoder_control(PAN_RIGHT);
}
function right_onmouseup() 
{
	if (!ptz_type)
		decoder_control(PTZ_STOP);
	else if (flip&0x02)
		decoder_control(PAN_LEFT_STOP);
	else	
		decoder_control(PAN_RIGHT_STOP);
}
function center_onclick() 
{
	if (!ptz_type) decoder_control(PTZ_CENTER);
}
function vpatrol_onclick() 
{
	if (!ptz_type) decoder_control(PTZ_VPATROL);
}
function vpatrolstop_onclick() 
{
	if (!ptz_type) decoder_control(PTZ_VPATROL_STOP);
}
function hpatrol_onclick() 
{
	ptz_type?decoder_control(PTZ_PELCO_D_HPATROL):decoder_control(PTZ_HPATROL);
}
function hpatrolstop_onclick() 
{
	ptz_type?decoder_control(PTZ_PELCO_D_HPATROL_STOP):decoder_control(PTZ_HPATROL_STOP);
}
function set_resolution()
{
	camera_control(0,resolution_sel.value);
	setTimeout('location.reload()',2000);
}
function plus_brightness()
{
	val=brightness_input.value;
	if (val++<15)
	{
		brightness_input.value=val;
		camera_control(1,val*16);
	}
}
function minus_brightness()
{
	val=brightness_input.value;
	if (val-->0)
	{
		brightness_input.value=val;
		camera_control(1,val*16);
	}	
}
function plus_contrast()
{
	val=contrast_input.value;
	if (val++<6)
	{
		contrast_input.value=val;
		camera_control(2,val);
	}
}
function minus_contrast()
{
	val=contrast_input.value;
	if (val-->0)
	{
		contrast_input.value=val;
		camera_control(2,val);
	}
}
function Update_Live()
{
  document.getElementById("live").src = BaseURL+Flux;
}

function default_all()
{
	camera_control(1,100);
	camera_control(2,4);
	camera_control(3,0)
	camera_control(0,8);
	setTimeout('location.reload()',1000);
}



//}