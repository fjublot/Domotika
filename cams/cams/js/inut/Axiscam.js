function Axiscam(BaseURL, Width, Height, DisplayWidth, DisplayHeight, Canal) {
var File = "axis-cgi/mjpg/video.cgi?camera=" + Canal + "&resolution=" + Width + "x" + Height;
var output = "";
if ((navigator.appName == "Microsoft Internet Explorer" ) &&
   (navigator.platform != "MacPPC" ) && (navigator.platform != "Mac68k" ))
{
  // If Internet Explorer under Windows then use ActiveX 
  output  = ""
  output += "<OBJECT ID='AxisCamControl' CLASSID='CLSID:917623D1-D8E5-11D2-BE8B-00104B06BDE3' width='"
  output += DisplayWidth;
  output += "' height='";
  output += DisplayHeight;
  output += "' CODEBASE='";
  output += BaseURL;
  output += "activex/AxisCamControl.cab#Version=2,20,0,21'>";
  output += "<PARAM NAME='URL' VALUE='";
  output += BaseURL;
  output += File + "'>";
  //output += '<param name="MediaType" value="mjpeg-unicast">';
  output += '<param name="ShowStatusBar" value="0">';
  output += '<param name="ShowToolbar" value="0">';
  output += '<param name="AutoStart" value="1">';
  output += '<param name="StretchToFit" value="1">';
  output += "</OBJECT>";
} else {
  // If not IE for Windows use the browser itself to display
  theDate = new Date();
  output  = '<IMG SRC="';
  output += BaseURL;
  output += File;
  output += '&dummy=' + theDate.getTime().toString(10);
  output += '" HEIGHT="';
  output += DisplayHeight;
  output += '" WIDTH="';
  output += DisplayWidth;
  output += '" ALT="Camera Image">';
}
  document.write(output);
}
