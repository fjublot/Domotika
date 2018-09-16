// JavaScript Document
function getElementTopLeft(id) {
    var ele = document.getElementById(id);
    var top = 0;
    var left = 0;
    
    while(ele != null && ele.tagName != "BODY") {
        top += ele.offsetTop;
        left += ele.offsetLeft;
        ele = ele.offsetParent;
    }
    return { top: top, left: left };
}

function affiche_help(id,lang,skin)
{
	if ( document.getElementById('help') ) 
	{
		xml = GetXML('help/'+lang+'/'+id+'.help');
		if ( xml != null && xml.getElementsByTagName('message') )
		{
			content = '<div class="help"><div class="bandeau_help">';
			if ( xml.getElementsByTagName('titre') )
				content = content + xml.getElementsByTagName('titre')[0].firstChild.nodeValue;
			content = content+'<img class="close_help" src="images/skins/'+skin+'/help.png" onclick="javascript:window.location=\'help.php\';"><img class="close_help" src="images/skins/'+skin+'/close.png" onclick="close_help()"></div>';
			content = content+xml.getElementsByTagName('message')[0].firstChild.nodeValue;
			content = content+'</div>';
			document.getElementById('help').innerHTML = content;
			href_id = 'help_'+id.replace(".", "_");
			Postion = getElementTopLeft(href_id);
			document.getElementById('help').style.top = parseInt(Postion.top + 10) + 'px';
			document.getElementById('help').style.left = parseInt(Postion.left + 10) + 'px';
			document.getElementById('help').style.display = 'inline';
			document.getElementById('help').visibility = "visible";
		}
		else
		{
			alert("Aide indisponible pour cette zone ("+id+").");
		}
	}
	else
	{
		alert("Impossible d'afficher l'aide.");
	}
}

function close_help()
{
	if ( document.getElementById('help') ) 
	{
		document.getElementById('help').style.display = "none";
	}
}