function Panasonic(BaseURL, Width, Height, DisplayWidth, DisplayHeight) {
var File = "nphMotionJpeg?Resolution=640x480&Quality=Standard";

//+ "&resolution=" + Width + "x" + Height;
var output = "";
if ((navigator.appName == "Microsoft Internet Explorer" ) &&
   (navigator.platform != "MacPPC" ) && (navigator.platform != "Mac68k" ))
{
  // If Internet Explorer under Windows then use ActiveX 
  output  = ''
  output += '<OBJECT ID="bl_camera" ' 
  output += 'WIDTH="640" ' 
  output += 'HEIGHT="480" ' 
  output += 'CLASSID="CLSID:87BE3784-6977-4E84-AA08-55A96B9CEAC5" ' 
  output += 'CODEBASE="'
  output += BaseURL;
  output += 'bl_camera.cab#Version=1,2,0,84" > '
  output += '<PARAM NAME="Authorize"   VALUE="9785cad45e">'
  output += '<PARAM NAME="TaskId"      VALUE="50">'
  output += '<PARAM NAME="ImageWidth"  VALUE="640">'
  output += '<PARAM NAME="ImageHeight" VALUE="480">'
  output += '<PARAM NAME="ImagePath"   VALUE="?Resolution=640x480&Quality=Standard">'
  output += '<PARAM NAME="ImageHeader" VALUE="839fa2a884a96f6f">'
  output += '<PARAM NAME="ClickAndCenter" VALUE="Real">'
  output += '<PARAM NAME="ControlTarget" VALUE="nphControlCamera">'
  output += '<PARAM NAME="DigitalZoom" VALUE="Enable">'
  output += '<PARAM NAME="UseChangeResolution" VALUE="Enable">'
  output += '<PARAM NAME="Language" VALUE="7">'                 
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
  alert(output);
}
