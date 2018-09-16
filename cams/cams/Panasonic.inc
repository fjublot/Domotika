<SCRIPT language="JavaScript">
<!--
function ChangeRPeriod(RPeriod) {
	top.location.href = "<?php echo $host; ?>/CgiStart?page=Single&Resolution=640x480&Quality=Standard&RPeriod="+RPeriod+"&Size=STD&PresetOperation=Move&Language=7";
}
//-->
</SCRIPT>
<table border="0" cellpadding="0" cellspacing="0" width="860" height="480" align="center">
    <tr>
        <td width="94" class="v1 pad1"  bgcolor="#f8f8f8">	
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 			WIDTH="94">	
              <TR>		<TD>
                  <FONT FACE="Arial" STYLE="font-size:11px" 				COLOR="#000000">&nbsp;Horizontal/ Vertical
                  </FONT></TD>	
              </TR>	
              <TR>		
                <TD HEIGHT=1></TD>	
              </TR>	
              <TR>		
                <TD BGCOLOR="#C4CEEF" ALIGN=center>
                  <img 				src="cams/images/panasonic/pad.gif" usemap="#pad" width="90" height=98 				border=0>
                  <map name="pad">
                    <area shape="circle" coords="12,12 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=PanScan&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Balayage horizontal" TITLE="Balayage horizontal">
                    <area shape="circle" coords="78,12 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=TiltScan&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Balayage vertical" TITLE="Balayage vertical">
                    <area shape="circle" coords="45,33 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=TiltUp&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Haut" TITLE="Haut">
                    <area shape="circle" coords="20,58 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=PanLeft&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Gauche" TITLE="Gauche">
                    <area shape="circle" coords="45,58 10" 		  		href="<?php echo $host; ?>/nphControlCamera?Direction=HomePosition&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Position initiale" TITLE="Position initiale">
                    <area shape="circle" coords="70,58 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=PanRight&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Droite" TITLE="Droite">
                  <img src="../images/skins/commun/bg-categories.png" alt="bg-categories.png, 1,1kB" title="Bg categories" border="0" height="51" width="1">  <area shape="circle" coords="45,83 10" 					href="<?php echo $host; ?>/nphControlCamera?Direction=TiltDown&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message" ALT="Bas" TITLE="Bas">	
                  </map></TD>	
              </TR>	
            </TABLE>
            <TABLE cellSpacing="0" cellPadding="0" width="94" border="0">
              <TR>
                <TD height="3"></TD>
              </TR>
            </TABLE>
            <!-- ** Preset ** -->	
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 				WIDTH="94">	
              <TR>		
                <TD HEIGHT=11 ALIGN=left 				VALIGN=middle>
                  <FONT FACE="Arial" STYLE="font-size:11px" 				COLOR="#000000">Préréglé 
                  </FONT></TD>	
              </TR>	
            </TABLE>
            <!-- ** Preset Control ** -->	
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 					BGCOLOR="#9AAACA" WIDTH="220">	
              <TR>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <A HREF="<?php echo $host; ?>/nphControlCamera?Direction=Preset&PresetOperation=Move&Data=1&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&Language=7" TARGET="Message">
                    <IMG SRC="cams/images/panasonic/PresetOFF01.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="Entree" TITLE="Entree"></A></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <A HREF="<?php echo $host; ?>/nphControlCamera?Direction=Preset&PresetOperation=Move&Data=2&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&Language=7" TARGET="Message">
                    <IMG SRC="cams/images/panasonic/PresetOFF02.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="Table" TITLE="Table"></A></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <A HREF="<?php echo $host; ?>/nphControlCamera?Direction=Preset&PresetOperation=Move&Data=3&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&Language=7" TARGET="Message">
                    <IMG SRC="cams/images/panasonic/PresetOFF03.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="canapé" TITLE="canapé"></A></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <A HREF="<?php echo $host; ?>/nphControlCamera?Direction=Preset&PresetOperation=Move&Data=4&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&Language=7" TARGET="Message">
                    <IMG SRC="cams/images/panasonic/PresetOFF04.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="Piano" TITLE="Piano"></A></TD>	
              </TR>	
              <TR>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <IMG SRC="cams/images/panasonic/PresetON05.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="" TITLE=""></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <IMG SRC="cams/images/panasonic/PresetON06.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="" TITLE=""></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <A HREF="<?php echo $host; ?>/nphControlCamera?Direction=Preset&PresetOperation=Move&Data=7&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&Language=7" TARGET="Message">
                    <IMG SRC="cams/images/panasonic/PresetOFF07.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="canape1" TITLE="canape1"></A></TD>		
                <TD WIDTH=18 HEIGHT=18 ALIGN=center 						VALIGN=middle>
                  <IMG SRC="<?php echo $host; ?>/cams/images/panasonic/PresetON08.gif" WIDTH=18 HEIGHT=18 BORDER=0 ALT="" TITLE=""></TD>	
              </TR>
            </TABLE>
            <TABLE cellSpacing="0" cellPadding="0" width="94" border="0">
              <TR>
                <TD height="3"></TD>
              </TR>
            </TABLE>
            <!-- ** Brightness ** -->
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH="94">	
              <TR>		
                <TD ALIGN=left VALIGN=middle>
                  <FONT FACE="Arial" 				STYLE="font-size:11px" COLOR="#000000">&nbsp;Luminosité 
                  </FONT></TD>	
              </TR>	
              <TR>		<TD>	
                  <!-- ** Brightness map ** -->		
                  <TABLE BORDER=0 CELLSPACING=1 CELLPADDING=0 				WIDTH="92">		
                    <TR>			
                      <TD WIDTH=28 HEIGHT=17 ALIGN=center VALIGN=middle 				BGCOLOR="#1961DF">
                        <A STYLE="width:28px;text-decoration:none" HREF="<?php echo $host; ?>/nphControlCamera?Direction=Darker&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message">
                          <FONT STYLE="font-size:11px" FACE="Impact">&nbsp;<B>-</B>&nbsp;
                          </FONT></A></TD>			
                      <TD WIDTH=28 HEIGHT=17 ALIGN=center VALIGN=middle 				BGCOLOR="#1961DF">
                        <A STYLE="width:28px;text-decoration:none" HREF="<?php echo $host; ?>/nphControlCamera?Direction=DefaultBrightness&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message">
                          <FONT STYLE="font-size:11px" FACE="Arial">STD
                          </FONT></A></TD>			
                      <TD WIDTH=28 HEIGHT=17 ALIGN=center VALIGN=middle 				BGCOLOR="#1961DF">
                        <A STYLE="width:28px;text-decoration:none" HREF="<?php echo $host; ?>/nphControlCamera?Direction=Brighter&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="Message">
                          <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;<B>+</B>&nbsp;
                          </FONT></A></TD>		
                    </TR>		
                  </TABLE>		</TD>	
              </TR>
            </TABLE>		
            <!-- ** Refresh Rate ** -->          
            <FORM method="GET" action="#" target="_parent" style="margin: 0px;">		
              <TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="94">		
                <TR>			<TD>
                    <FONT FACE="Arial" STYLE="font-size:11px" COLOR="#000000">&nbsp;Int. rafraîch.
                    </FONT></TD>		
                </TR>		
                <TR>			
                  <TD HEIGHT=1></TD>		
                </TR>		
                <TR>			
                  <TD ALIGN=center>			
                    <SELECT NAME=RPeriod STYLE="color:#FFFFFF;background:#1961DF;width: 94px;" onChange="ChangeRPeriod(this.value)">				
                      <OPTION VALUE="0" selected>Animé 
                      </OPTION>				
                      <OPTION VALUE="3" >3s
                      </OPTION>				
                      <OPTION VALUE="5" >5s
                      </OPTION>				
                      <OPTION VALUE="10" >10s
                      </OPTION>				
                      <OPTION VALUE="30" >30s
                      </OPTION>				
                      <OPTION VALUE="60" >60s
                      </OPTION>			
                    </SELECT>		</TD>		
                </TR>		
                <TR>			
                  <TD COLSPAN="2" HEIGHT="1"></TD>		
                </TR>		
              </TABLE>		
            </FORM>	
            <!-- ** Resolution ** -->		
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH="94">		
              <TR>			
                <TD COLSPAN=2>
                  <FONT FACE="Arial" STYLE="font-size:11px" COLOR="#000000">&nbsp;Résolution
                  </FONT></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=2 HEIGHT=1></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=1 BGCOLOR="#CC6666" 				WIDTH=18 HEIGHT=17 VALIGN=middle ALIGN=center>*</TD>			
                <TD COLSPAN=1 BGCOLOR="#34769c" WIDTH=72 HEIGHT=17 VALIGN=middle>
                  <A STYLE="width:72px;text-decoration:none" HREF="<?php echo $host; ?>/CgiStart?page=Single&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="_top">
                    <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;640x480&nbsp;&nbsp;&nbsp;
                    </FONT></A></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=2 HEIGHT=3></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=1 BGCOLOR="#34769c" 			WIDTH=18 HEIGHT=17 VALIGN=middle ALIGN=center>&nbsp;</TD>			
                <TD COLSPAN=1 BGCOLOR="#34769c" WIDTH=72 HEIGHT=17 VALIGN=middle>
                  <A STYLE="width:72px;text-decoration:none" HREF="<?php echo $host; ?>/CgiStart?page=Single&Resolution=320x240&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="_top">
                    <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;320x240&nbsp;&nbsp;&nbsp;
                    </FONT></A></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN="2" HEIGHT="1"></TD>		
              </TR>		
            </TABLE>		
            <!-- ** Image quality ** -->		
            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 			WIDTH="94">		
              <TR>			
                <TD COLSPAN=2 VALIGN=middle>
                  <FONT FACE="Arial" STYLE="font-size:11px" COLOR="#000000">&nbsp;Qualité Image	
                  </FONT></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=2 HEIGHT=1></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=1 BGCOLOR="#34769c" 				WIDTH=18 HEIGHT=17 VALIGN=middle ALIGN=center>&nbsp;</TD>			
                <TD COLSPAN=1 BGCOLOR="#34769c" WIDTH=72 HEIGHT=17 VALIGN=middle>
                  <A STYLE="width:72px;text-decoration:none" HREF="<?php echo $host; ?>/CgiStart?page=Single&Resolution=640x480&Quality=Clarity&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="_top">
                    <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;Clarté 
                    </FONT></A></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=2 HEIGHT=3></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=1 BGCOLOR="#CC6666" 				WIDTH=18 HEIGHT=17 VALIGN=middle ALIGN=center>*</TD>			
                <TD COLSPAN=1 BGCOLOR="#34769c" WIDTH=72 HEIGHT=17 VALIGN=middle>
                  <A STYLE="width:72px;text-decoration:none" HREF="<?php echo $host; ?>/CgiStart?page=Single&Resolution=640x480&Quality=Standard&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="_top">
                    <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;Standard
                    </FONT></A></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=2 HEIGHT=3></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN=1 BGCOLOR="#34769c" 				WIDTH=18 HEIGHT=17 VALIGN=middle ALIGN=center>&nbsp;</TD>			
                <TD COLSPAN=1 BGCOLOR="#34769c" WIDTH=72 HEIGHT=17 VALIGN=middle>
                  <A STYLE="width:72px;text-decoration:none" HREF="<?php echo $host; ?>/CgiStart?page=Single&Resolution=640x480&Quality=Motion&RPeriod=0&Size=STD&PresetOperation=Move&Language=7" TARGET="_top">
                    <FONT STYLE="font-size:11px" FACE="Arial">&nbsp;Mouvement
                    </FONT></A></TD>		
              </TR>		
              <TR>			
                <TD COLSPAN="2" HEIGHT="1"></TD>		
              </TR>		
        </td>
        </table>
        <td  width="640" height="480" bgcolor="black" align="center" valign="middle">
            <img id="live" src="" alt="Camera Image">
        </td>
        </tr>
</TABLE>