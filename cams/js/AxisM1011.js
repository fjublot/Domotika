function AxisM1011(BaseURL, Width, Height, DisplayWidth, DisplayHeight, Canal) {
var Flux = "axis-cgi/mjpg/video.cgi?resolution=640x480";
//+ "&resolution=" + Width + "x" + Height;
var output = "";
if ((navigator.appName == "Microsoft Internet Explorer" ) &&
   (navigator.platform != "MacPPC" ) && (navigator.platform != "Mac68k" ))
{
  // If Internet Explorer under Windows then use ActiveX 
  output  = ""
  output += "<OBJECT ID='AxisCamControl' CLASSID='CLSID:DE625294-70E6-45ED-B895-CFFA13AEB044' width='";
  output += DisplayWidth;
  output += "' height='";
  output += DisplayHeight;
  output += "' CODEBASE='";
  output += BaseURL;
  output += "activex/AMC.cab#version=5,3,20,0'>";
  output += "<PARAM NAME='MediaURL' VALUE='";
  output += BaseURL;
  output += Flux + "'>";
  output += '<param name="MediaType" value="mjpeg-unicast">';
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
  output += Flux;
  output += '&dummy=' + theDate.getTime().toString(10);
  output += '" HEIGHT="';
  output += DisplayHeight;
  output += '" WIDTH="';
  output += DisplayWidth;
  output += '" ALT="Camera Image">';
}
  document.write(output);
  //alert(output);
}
