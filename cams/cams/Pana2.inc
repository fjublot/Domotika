<script src="<?php echo $host; ?>/get_camera_params.cgi?user=<?php echo $camera->user; ?>&pwd=<?php echo $camera->password; ?>"></script>
<script src="<?php echo $host; ?>/check_user.cgi?user=<?php echo $camera->user; ?>&pwd=<?php echo $camera->password; ?>"></script>
<iframe name="action_zone" style="display:none"></iframe>
<center>
<table border="0" cellpadding="0" cellspacing="0" width="860" height="480" align="center">
    <tr>
        <td width="220" class="v1 pad1"  bgcolor="#f8f8f8">

            <table id = "operator_table"  border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr height="20">
                    <td align=right width="50%" class="f12b">Flip&nbsp;&nbsp;<input id="image_reversal" type="checkbox" onClick="set_flip()"></td>
                    <td  class="f12b">Mirroir<input id="image_mirror" type="checkbox" onClick="set_flip()"></td>
                </tr>
	            <tr height="20">
	                <td class="f12b" align=right>Resolution</td>

	                <td align=center><SELECT id="resolution_sel" class="s4" onChange="set_resolution()"><OPTION value="8">320*240</OPTION><OPTION value="32">640*480</OPTION></SELECT></td>
	            </tr>
	            <tr height="20">
	                <td class="f12b" align=right>Mode</td>
	                <td align=center><SELECT class="s4" id="mode_sel" onChange="camera_control(3,value)"><OPTION value="0">50 HZ</OPTION><OPTION value="1">60 HZ</OPTION><OPTION value="2">Fréquence</OPTION></SELECT></td>
	            </tr>

	            <tr height="20">
	                <td class="f12b" align=right>Luminosite</td>
	                <td align=center>
	                    <table class="h2">
	                        <tr>
	                            <td><img style="cursor:pointer" src="images/cam/plus_up.gif" onClick="plus_brightness()" onmouseover="this.src=plus_down.src" onMouseout="this.src=plus_up.src"></td>
	                            <td><input id="brightness_input" readonly maxLength="2" class="s3" size="2"></td>
	                            <td><img style="cursor:pointer" src="images/cam/minus_up.gif" onClick="minus_brightness()" onmouseover="this.src=minus_down.src" onMouseout="this.src=minus_up.src"></td>

	                        </tr>
	                    </table>
	                </td>
	            </tr>
	            <tr height="20">
	                <td class="f12b" align=right>Contraste</td>
	                <td align=center>
	                    <table class="h2">

	                        <tr>
	                            <td><img style="cursor:pointer" src="images/cam/plus_up.gif" onClick="plus_contrast()" onmouseover="this.src=plus_down.src" onMouseout="this.src=plus_up.src"></td>
	                            <td><input id="contrast_input" readonly maxLength="1" class="s3" size="2"></td>
	                            <td><img style="cursor:pointer" src="images/cam/minus_up.gif" onClick="minus_contrast()" onmouseover="this.src=minus_down.src" onMouseout="this.src=minus_up.src"></td>
	                        </tr>
	                    </table>
	                </td>
	            </tr>
                <tr  height="24">

                    <td></td>
                    <td >
                        <table height="24" border="0" cellpadding="0" cellspacing="0" width=100%>
                            <tr>
                                <td style="cursor:pointer" background=images/cam/default.gif align=center>
                                   <a id = default_link href = "javascript:default_all()" style="font-size:12px; color:#000000">Defaut</a>
                                </td>
                                <td width="59">

                                </td>
                            </tr>                         
                        </table>
                    </td>
                </tr>
	            <tr>
	                <td colspan="2">
	                    <table border="0" cellpadding="0" cellspacing="0" width=100%>
	                        <tr>

		                        <td>
			                        <div><img style="cursor:pointer" src="images/cam/leftup_up.gif"><img style="cursor:pointer" src="images/cam/up_up.gif" onMouseOver="this.src=up_down.src" onMouseOut="this.src=up_up.src" onMouseDown="up_onmousedown()" onMouseUp="up_onmouseup()"><img style="cursor:pointer" src="images/cam/rightup_up.gif"></div>
			                        <div><img style="cursor:pointer" src="images/cam/left_up.gif" onMouseOver="this.src=left_down.src" onMouseOut="this.src=left_up.src" onMouseDown="left_onmousedown()" onMouseUp="left_onmouseup()"><img style="cursor:pointer" id="gocenter" src="images/cam/center_up.gif"  onClick="center_onclick()"><img style="cursor:pointer" src="images/cam/right_up.gif" onMouseOver="this.src=right_down.src" onMouseOut="this.src=right_up.src" onMouseDown="right_onmousedown()" onMouseUp="right_onmouseup()"></div>
			                        <div><img style="cursor:pointer" src="images/cam/leftdown_up.gif"><img style="cursor:pointer" src="images/cam/down_up.gif" onMouseOver="this.src=down_down.src" onMouseOut="this.src=down_up.src" onMouseDown="down_onmousedown()" onMouseUp="down_onmouseup()"><img style="cursor:pointer" src="images/cam/rightdown_up.gif"></div>
		                        </td>
		                        <td>
				                    <div>
				                        <img style="cursor:pointer" id="vpatrol" src="images/cam/vpatrol_up.gif" onclick = "vpatrol_onclick()" onMouseOver="this.src=vpatrol_down.src" onMouseOut="this.src=vpatrol_up.src">
				                        &nbsp;<img style="cursor:pointer" id="vpatrolstop" src="images/cam/R_stop_up.gif" onClick="vpatrolstop_onclick()" onMouseOver="this.src=R_stop_down.src" onMouseOut="this.src=R_stop_up.src">

				                    </div>
				                    <div>
				                        <img style="cursor:pointer" id="hpatrol" src="images/cam/hpatrol_up.gif" onclick="hpatrol_onclick()" onMouseOver="this.src=hpatrol_down.src" onMouseUp="this.src=hpatrol_up.src">
				                        &nbsp;<img style="cursor:pointer" id="hpatrolstop" src="images/cam/R_stop_up.gif" onClick="hpatrolstop_onclick()" onMouseOver="this.src=R_stop_down.src" onMouseOut="this.src=R_stop_up.src"></div>
				                    </div>
                                </td>	
	                        </tr>
	                    </table>
	                </td>

	            </tr>
	            <tr height = 30 align =centerc>
	                <td>
	                    <table border="0" cellpadding="0" cellspacing="0" width=100% height=24 align=center>
	                        <tr>
	                            <td width = 40>
	                            </td>
	                            <td width = 51 style="cursor:pointer" background=images/cam/default.gif align = center>
	                                <a onMouseDown="javascript:decoder_control(PTZ_ZOOM_WIDE)" onMouseUp="javascript:decoder_control(PTZ_ZOOM_WIDE_STOP)">Zoom+</a>

	                            </td>
	                           <td width = 19></td>
	                        </tr>
	                    </table>
	                </td>
	                <td>
	                    <table border="0" cellpadding="0" cellspacing="0" width=100% height=24 align=center>
	                        <tr>
	                            <td width = 19>

	                            </td>
	                            <td width = 51 style="cursor:pointer" background=images/cam/default.gif align = center>
	                                <a onMouseDown="javascript:decoder_control(PTZ_ZOOM_TELE)" onMouseUp="javascript:decoder_control(PTZ_ZOOM_TELE_STOP)" >Zoom-</a>
	                            </td>
	                           <td width = 40></td>
	                        </tr>
	                    </table>
	                </td>

	            </tr>
                <tr height = 48>
                    <td colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width=100% align=center>
                            <tr>
                                <td width = 60 align =right>
                                    <strong>Preset</strong>
                                </td>

                                <td width = 40 align=center>
				                    <SELECT class="s7" id="preset"><OPTION value="30">1</OPTION><OPTION value="32">2</OPTION><OPTION value="34">3</OPTION><OPTION value="36">4</OPTION><OPTION value="38">5</OPTION><OPTION value="40">6</OPTION><OPTION value="42">7</OPTION><OPTION value="44">8</OPTION></SELECT>
                                </td>
	                            <td width = 2>
	                            </td>

                                <td width = 51 align=left>
                                    <table border="0" cellpadding="0" cellspacing="0" width=100% height =100%>
                                        <tr>
                                            <td style="cursor:hand" background=images/cam/default.gif align = center>
                                                <a href="javascript:preset_set()" style="font-size:12px; color:#000000"><strong>Set</a>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td style="cursor:hand" background=images/cam/default.gif align = center>
                                                <a href="javascript:preset_go()" style="font-size:12px; color:#000000"><strong>Go</strong></a>
                                            </td>
                                        </tr>
                                    </table>
	                            </td>
	                            <td width = 5>
	                            </td>

	                            <td width = 51>
	                                <table border="0" cellpadding="0" cellspacing="0" width=100% height =100%>
	                                    <tr height = 12>
	                                    </tr>
	                                    <tr  height = 24>
	                                    	<td style="cursor:hand" background=images/cam/default.gif align=center>
	                                            <a href="javascript:PT_patrol()" style="font-size:12px; color:#000000"><strong>Patrol</strong></a>
	                                        </td>

	                                    </tr>
	                                    <tr>
	                                    </tr>
	                                </table>
	                            </td>
	                            <td >
	                            </td>
                            </tr>
                        </table>

                    </td>
                </tr>   
	            <tr height = 10><td height = 10></td></tr>
                <tr>
                    <td colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width=100% >
                            <tr align="center" >
                                <td>
                                    <div>

                                        <img id="P_1" style="cursor:pointer" src="images/cam/P_1_up.gif" onmouseover="this.src=P_1_down.src;" onmouseout="this.src=P_1_up.src;" onClick="decoder_control(31)"/>
                                        &nbsp;<img id="P_2" style="cursor:pointer" src="images/cam/P_2_up.gif" onmouseover="this.src=P_2_down.src" onmouseout="this.src=P_2_up.src;" onClick="decoder_control(33)"/>
                                        &nbsp;<img id="P_3" style="cursor:pointer" src="images/cam/P_3_up.gif" onmouseover="this.src=P_3_down.src" onmouseout="this.src=P_3_up.src;" onClick="decoder_control(35)"/>
                                        &nbsp;<img id="P_4" style="cursor:pointer" src="images/cam/P_4_up.gif" onmouseover="this.src=P_4_down.src" onmouseout="this.src=P_4_up.src;" onClick="decoder_control(37)"/>
                                    </div>
                                    <div>
                                        <img style="cursor:pointer" id="P_5" src="images/cam/P_5_up.gif" onmouseover="this.src=P_5_down.src" onmouseout="this.src=P_5_up.src;" onClick="decoder_control(39)"/>
                                        &nbsp;<img id="P_6" style="cursor:pointer" src="images/cam/P_6_up.gif" onmouseover="this.src=P_6_down.src" onmouseout="this.src=P_6_up.src;" onClick="decoder_control(41)"/>
                                        &nbsp;<img id="P_7" style="cursor:pointer" src="images/cam/P_7_up.gif" onmouseover="this.src=P_7_down.src" onmouseout="this.src=P_7_up.src;" onClick="decoder_control(43)"/>

                                        &nbsp;<img id="P_8" style="cursor:pointer" src="images/cam/P_8_up.gif" onmouseover="this.src=P_8_down.src" onmouseout="this.src=P_8_up.src;" onClick="decoder_control(45)"/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <tr height = 35 id = "tr_admin">
                    <td colspan="2">

                        <table height = 35 border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width = 6></td>
                                
                                <td width = 6></td>

                            </tr>
                        </table>
                    </td>
                </tr>	            
            </table>
        </td>
        <td  width="640" height="480" bgcolor="black" align="center" valign="middle">
            <img id="live" src="">
        </td>

    </tr>
</table>
</center>
<div style=display:none;>
<img id = P_1_down       src = images/cam/P_1_down.gif       class="ih">
<img id = P_1_up         src = images/cam/P_1_up.gif         class="ih">
<img id = P_2_down       src = images/cam/P_2_down.gif       class="ih">
<img id = P_2_up         src = images/cam/P_2_up.gif         class="ih">
<img id = P_3_down       src = images/cam/P_3_down.gif       class="ih">
<img id = P_3_up         src = images/cam/P_3_up.gif         class="ih">
<img id = P_4_down       src = images/cam/P_4_down.gif       class="ih">
<img id = P_4_up         src = images/cam/P_4_up.gif         class="ih">
<img id = P_5_down       src = images/cam/P_5_down.gif       class="ih">
<img id = P_5_up         src = images/cam/P_5_up.gif         class="ih">
<img id = P_6_down       src = images/cam/P_6_down.gif       class="ih">
<img id = P_6_up         src = images/cam/P_6_up.gif         class="ih">
<img id = P_7_down       src = images/cam/P_7_down.gif       class="ih">
<img id = P_7_up         src = images/cam/P_7_up.gif         class="ih">
<img id = P_8_down       src = images/cam/P_8_down.gif       class="ih">
<img id = P_8_up         src = images/cam/P_8_up.gif         class="ih">
<img id = up_up          src = images/cam/up_up.gif          class="ih">
<img id = up_down        src = images/cam/up_down.gif        class="ih">
<img id = down_up        src = images/cam/down_up.gif        class="ih">
<img id = down_down      src = images/cam/down_down.gif      class="ih">
<img id = left_up        src = images/cam/left_up.gif        class="ih">
<img id = left_down      src = images/cam/left_down.gif      class="ih">
<img id = right_up       src = images/cam/right_up.gif       class="ih">
<img id = right_down     src = images/cam/right_down.gif     class="ih">
<img id = leftup_up      src = images/cam/leftup_up.gif      class="ih">
<img id = leftup_down    src = images/cam/leftup_down.gif    class="ih">
<img id = leftdown_up    src = images/cam/leftdown_up.gif    class="ih">
<img id = leftdown_down  src = images/cam/leftdown_down.gif  class="ih">
<img id = rightup_up     src = images/cam/rightup_up.gif     class="ih">
<img id = rightup_down   src = images/cam/rightup_down.gif   class="ih">
<img id = rightdown_up   src = images/cam/rightdown_up.gif   class="ih">
<img id = center_up      src = images/cam/center_up.gif      class="ih">
<img id = center_down    src = images/cam/center_down.gif    class="ih">
<img id = rightdown_down src = images/cam/rightdown_down.gif class="ih">
<img id = R_stop_down    src = images/cam/R_stop_down.gif    class="ih">
<img id = R_stop_up      src = images/cam/R_stop_up.gif      class="ih">
<img id = vpatrol_down   src = images/cam/vpatrol_down.gif   class="ih">
<img id = vpatrol_up     src = images/cam/vpatrol_up.gif     class="ih">
<img id = hpatrol_down   src = images/cam/hpatrol_down.gif   class="ih">
<img id = hpatrol_up     src = images/cam/hpatrol_up.gif     class="ih">
<img id = plus_up        src = images/cam/plus_up.gif        class="ih">
<img id = plus_down      src = images/cam/plus_down.gif      class="ih">
<img id = minus_down     src = images/cam/minus_down.gif     class="ih">
<img id = minus_up       src = images/cam/minus_up.gif       class="ih">
</div>
