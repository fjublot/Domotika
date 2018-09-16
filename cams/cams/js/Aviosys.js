function Aviosys(BaseURL, Width, Height, DisplayWidth, DisplayHeight, Canal) {
var Flux = "axis-cgi/mjpg/video.cgi?camera=" + Canal + "&resolution=" + Width + "x" + Height;
var output = "";
var Cab = "WinWebPush.cab#version=1,0,1,3";
if ((navigator.appName == "Microsoft Internet Explorer" ) &&
   (navigator.platform != "MacPPC" ) && (navigator.platform != "Mac68k" ))
{
  // If Internet Explorer under Windows then use ActiveX 
  output  = ""
  output += "<OBJECT ID='Aviosys' CLASSID='CLSID:B0781EB7-16EA-49F1-9C1D-9716D88206CF' width='"
  output += DisplayWidth;
  output += "' height='";
  output += DisplayHeight;
  output += "' CODEBASE='";
  output += Cab;
  output += "<PARAM NAME='URL' VALUE='";
  output += BaseURL;
  output += "/view.cab#Version=1,0,0,56'>";
  //output += '<param name="MediaType" value="mjpeg-unicast">';
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
}
