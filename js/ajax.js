/*----------------------------------------------------------------*
* Titre : ajax.js                                                 *
* Méthode de manipulation via ajax                                *
*-----------------------------------------------------------------*
* Méthode AjaxLoadSelect pour charger automatiquement depuis XML  *
* une liste                                                       *
* Méthode GetXML pour downloader du XML                           *
*-----------------------------------------------------------------*/

	function UpdateStatus() {
		if ( jQuery('#UpdateStatus') == null || jQuery('#UpdateStatus').val() == 0 ) {
			jQuery('#UpdateStatus').val(1);
			GetXML('?app=Ws&page=xml&detail=false', AjaxUpdateStatus, '', true);
		}
	}

	function GetXML(fichier, callback, idLoader, asynchrone, event, MessagePopupId) {
		if ( typeof(idLoader) != "undefined" && idLoader != "" && document.getElementById(idLoader) ) {
			document.getElementById(idLoader).style.display = 'inline';
		}
		go = true;
		if ( typeof(asynchrone) == 'undefined' )
			asynchrone = false;
		if (window.XMLHttpRequest || window.ActiveXObject) {
			if (window.ActiveXObject) {
				try {
					xhr_object = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e) {
					xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			else {
				xhr_object = new XMLHttpRequest(); 
			}
		}
		else {
			alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
			$('#UpdateStatus').val(0);
			return null;
		}
		if ( typeof(callback) == "function" ) {
			xhr_object.onreadystatechange = function() {
	//			alert('Refresh '+xhr_object.readyState + ' ' + xhr_object.status);
				if ( xhr_object.readyState == 4 && (xhr_object.status == 200 || xhr_object.status == 0) ) {
	//				alert('Refresh');
					if ( typeof(idLoader) != "undefined" && idLoader != "" && document.getElementById(idLoader) ) {
						document.getElementById(idLoader).style.display = 'none';
					} 
					jQuery('#UpdateStatus').val(0);
					callback(xhr_object, event);
				}
				else if (xhr_object.readyState < 4) {
					if ( typeof(idLoader) != "undefined" && idLoader != "" && document.getElementById(idLoader) ) {
						document.getElementById(idLoader).style.display = 'inline';
					} 
				} 
				else {
					if ( typeof(idLoader) != "undefined" && idLoader != "" && document.getElementById(idLoader) ) {
						document.getElementById(idLoader).style.display = 'none';
					} 
					if ( document.getElementById('UpdateStatus') ) {
						jQuery('#UpdateStatus').val(1);
					}
					jQuery('#StatusMsg').html(xhr_object.statusText)
				}
			}
		}
		xhr_object.open("GET", fichier, asynchrone);
		xhr_object.send(null);

		if ( typeof(idLoader) != "undefined" && idLoader != "" && document.getElementById(idLoader) ) {
				document.getElementById(idLoader).style.display = 'none';
			}
		if ( xhr_object.readyState == 4 ) {  
			$('#UpdateStatus').val(0);
			if ( typeof(callback) == "function" ) {
				return callback(xhr_object,event);
			}
			else {
				return xhr_object.responseXML;
			}
		}
		else {
	//		if ( asynchrone == false )
	//			alert('Error '+ xhr_object.readyState);
			return true;
		}     
	}


	function AjaxLoadSelect(IdObject, request, vide, ref )
	{
		if ( ref != '' )
			{ request = request+'&default='+ref;}
		if ( vide )
			{request = request+'&vide=true';}
		//chargement des options dans le select
		$(IdObject).load(request, null, function() {
			if ( ref != '' )
				$(IdObject).val(ref);
			//$(IdObject).selectmenu();
			//$(IdObject).selectmenu('refresh', true);
		});
	}

	function AjaxGetModel(classname, numero) {
		param = '&class='+classname+'&numero='+numero;
		retour = "";
		$.ajax({
			url: "?app=Ws&page=getmodel.JSON"+param,
			type: "POST",
			async: false,
			//data: param, 
			contentType: "application/json; charset=utf-8",
			success: function(json) {
				$.each(json, function(j, item) { 
					retour = retour.concat(item.model);
				});
			}
		});
		return retour;
	}

	function AjaxLoadLastAlert(IdObject, nbalerts) {
		var object = $('[id="'+IdObject+'"]');
		param = '&nbalerts='+nbalerts;
		html = ""
		$.ajax({
			url: "?app=Ws&page=lastalerts.JSON",
			type: "GET",
			data: param, 
			contentType: "application/json; charset=utf-8",
			success: function(json) { 
				$.each(json, function(j, item) { 
					html = html.concat('<li><a>');
					html = html.concat('	<span class="user-profile">');
					html = html.concat('		<img src="images/cartes/'+item.model+'.png" alt="Profile Image" />');
					html = html.concat('	</span>');
					html = html.concat('	<span>');
					html = html.concat('		<span>'+item.label+'</span>');
					html = html.concat('		<span class="pull-right">');
					html = html.concat('			<span class="time2">'+item.localtime+'</span><br/>');
					html = html.concat('			<span class="timezone pull-right">('+item.timezone+')</span>');
					html = html.concat('		</span>'); 
					html = html.concat('	</span>');
					html = html.concat('	<span class="message">');
					html = html.concat(item.message);
					html = html.concat('	</span>');
/*
					html = html.concat('<button type="button" class="btn pull-right btn-success">');
					html = html.concat('	<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
					html = html.concat('</button>');
					html = html.concat('<button type="button" class="btn pull-right btn-danger">');
					html = html.concat('	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
					html = html.concat('</button>');
*/
					html = html.concat('</a></li>');    
				});
				html = html.concat('<li><div class="text-center"><a><strong>See All Alerts</strong><i class="fa fa-angle-right"></i></a></div></li>');
				object.html(html);
			}
		});
	}

	function formatselect(item) {
		   var result = "";
			if(item.id){
				result = '<img src="'+state.id+'"/>';
			}
			return result;    
	}

	function AjaxLoadSelectJson(IdObject, param, vide, ref, addone2idx )
	{	
		if (addone2idx)
		{param = param + '&addone2idx=true';}
		var nref = ref.split(",");
		var $select = $('[id="'+IdObject+'"]')
		//var $select = $(IdObject);
	  if ( vide )
		{param = param + '&vide=true';}
		$.ajax({
			url: "?app=Ws&page=list.JSON",
			type: "GET",
			data: param, 
			contentType: "application/json; charset=utf-8",
			success: function(json) {
				$select.find('option').remove().end(); 
				$select.select2({
					data: json,
					//templateResult: formatData,
					//templateSelection: formatDataSelection
				}); 
				$(".select2-container").css('width', '');
				$select.val(nref).trigger("change");
			},
		});

	}

	function AjaxLoadThumbnails(IdObject, param)
	{	var $select = $('[id="'+IdObject+'"]');
		htmltxt='';
		$.ajax({
			url: "?app=Ws&page=list.JSON",
			type: "GET",
			data: param, 
			contentType: "application/json; charset=utf-8",
			success: function(json) {
				htmltxt = htmltxt.concat('<div class = "row">');
				$.each(json, function(j, item) {
					newitem = '<div class = "col-xs-6 col-sm-3 col-md-1">';
					newitem = newitem.concat('<a href="#" onClick="javascript:ClickThumbnail(this);" class = "thumbnail" data-image="'+item.image+'">');
					newitem = newitem.concat('<img src = "'+item.image+'" alt = "'+item.text+'">');
					newitem = newitem.concat('</a></div>');
					htmltxt = htmltxt.concat(newitem);
					
 				});
				htmltxt = htmltxt.concat('</div>');
				$(IdObject).html(htmltxt);
			}
		});

	}	
	
	function ClickThumbnail (ObjectId) {
		jQuery(".selectedimage").html(jQuery(ObjectId).data("image"));
	}
	
	function SelectThumbnail (ObjectId) {
		jQuery("#"+jQuery(ObjectId).data("idinput")).val(jQuery(".selectedimage").html());
		jQuery("#preview_"+jQuery(ObjectId).data("idinput")).attr('src', jQuery(".selectedimage").html());
		jQuery("#modal_"+jQuery(ObjectId).data("idinput")).modal("hide");
	}

    function formatData (data) {
		//if (data.loading) return data.name;
        markup = "<h1>" + data.id + "</h1>" + "<p>" + data.text + "</p>";
        return markup;
    }
    
	function formatDataSelection (data) {
          return data.text;
    }	

	function AjaxUpdateMessage(xhr)
	{
		if ( xhr == null ) {
			//UpdateMessage("Aucune info");
		}
		else {
			var xml = xhr.responseXML;
			var objMessage = xml.getElementsByTagName("message");
			var objError = xml.getElementsByTagName("error");
			
			if ( xml != null && ( (objMessage != null) ||  (objError != null))) {
				alert(objError);
				if ( (objError != null) ) {
					alert('exist');
					if (xml.getElementsByTagName("error")[0].firstChild.nodeValue.length>0 ) {
						UpdateMessage(xml.getElementsByTagName("error")[0].firstChild.nodeValue, "error");
					}
				}
				if (xml.getElementsByTagName("message")[0].childNodes.length>0 ) {
						UpdateMessage(xml.getElementsByTagName("message")[0].firstChild.nodeValue);
				}
			}
			else {
				UpdateMessage("Aucune info.");
			}
		}
	}
	

	function AjaxUpdateCam(xhr)
	{
		if ( xhr == null )
		{
			//UpdateMessage("Aucune info");
		}
		else
		{
			xml = xhr.responseXML;
			if ( xml != null && xml.getElementsByTagName("flux") && xml.getElementsByTagName("flux")[0].childNodes.length>0 ) 
			{
				//UpdateMessage("Info Xml");
				$("flux").value = xml.getElementsByTagName("flux")[0].firstChild.nodeValue;
			}
			else
			{
				$("Msg").value ="Aucune info.";
			}
			if ( xml != null && xml.getElementsByTagName("image") && xml.getElementsByTagName("image")[0].childNodes.length>0 ) 
			{
				//UpdateMessage("Info Xml");
				$("Msg").value = xml.getElementsByTagName("image")[0].firstChild.nodeValue;
			}

		}
	}

	function AjaxUpdateImgValid(url, img, ctrlmess) { 
		if (!img)
			var IdObject='IpxValid';
		else
			var IdObject  = 'IpxValid_'+img;
		
		go = true;
		if (ctrlmess)
			go = confirm(ctrlmess);
		if ( go ) {
			jQuery("#"+IdObject).addClass("ipx-running").removeClass("ipx-success ipx-error");
			xml = GetXML(url);
			
			
			if (xml.getElementsByTagName("status")[0].childNodes.length>0) 
				var status  = xml.getElementsByTagName("status")[0].firstChild.nodeValue;
			else 
				var status = 'KO';
			
			if (xml.getElementsByTagName("statusmessage")[0].childNodes.length>0)
				var statusmessage  = xml.getElementsByTagName("statusmessage")[0].firstChild.nodeValue;
			else
				var statusmessage='';
				
			if (status!='OK') {	// C'est en erreur
					$("#"+IdObject).addClass("ipx-error").removeClass("ipx-success ipx-running");
					$("#"+IdObject+' .ipx-error-mark').attr("data-infobulle", statusmessage);
					$("#"+IdObject+' .ipx-success-mark').removeAttr("data-infobulle");
			}
			else {	// Tout est bon
					$("#"+IdObject).addClass("ipx-success").removeClass("ipx-error ipx-running");
					//$("#"+IdObject+' .ipx-success-mark').attr("data-infobulle", statusmessage);
					$("#"+IdObject+' .ipx-error-mark').removeAttr("data-infobulle");
					$("#"+IdObject+' .ipx-success-mark').removeAttr("data-infobulle");
			}
		}
	}

	function AjaxControl(url, object) { 
			if (!object)
				var IdObject='Valid';
			else
				var IdObject  = 'Valid_'+object;
			jQuery.ajax({
				async: true,
				type: "GET", 
				//data: data, 
		  contentType: "application/json; charset=utf-8",
				beforeSend : function(){jQuery("#"+IdObject).addClass("running").removeClass("success error");},
				url:"?app=Ws&page=control.JSON&"+url,
				success: function(json) { 
					$.each(json, function(i, item)  { 
						status=item.valid;
						statusmessage=item.message;
						console.log(object+' : '+status);
						if (status=="false") {	// C'est en erreur
							jQuery("#"+IdObject).addClass("error").removeClass("success running");
							//jQuery("#"+IdObject+' .error-mark').attr("data-infobulle", statusmessage);
							//jQuery("#"+IdObject+' .success-mark').removeAttr("data-infobulle");
							jQuery("#"+object).html(statusmessage.capitalize());
							}
						else {	// Tout est bon
							jQuery("#"+IdObject).addClass("success").removeClass("error running");
							//$("#"+IdObject+' .ipx-success-mark').attr("data-infobulle", statusmessage);
							//jQuery("#"+IdObject+' .error-mark').removeAttr("data-infobulle");
							//jQuery("#"+IdObject+' .success-mark').removeAttr("data-infobulle");
							jQuery("#"+object).html(statusmessage.capitalize());
							}
					});

					//if ( typeof(callback) == "function" )
					//	callback(json);
				},
				error: function() {
					UpdateMessage("Une erreur est survenue !"); 
					return "error";
				},
				complete: function () {
					jQuery("#"+IdObject).removeClass("running");
				}
			});
	}

	function BvControl(url) { 
			url="?app=Ws&page=control.JSON&"+url;
			return $.getJSON(url);
	}

	function AjaxUpdateStatus(xhr)
	{
		if ( document.getElementById('Msg') )
			document.getElementById('Msg').display = "none";
		xml = xhr.responseXML;
		
		if ( xml == null )
		{
	//		sleep(10);
			if ( document.getElementById('flag_stop_ajax').value == 0 )
				return UpdateMsg('Recuperation des informations impossible','alert');
		}

		 
		if (xml.getElementsByTagName('debug').firstChild)
	  { 
	  var debug = xml.getElementsByTagName('debug')[0].firstChild.nodeValue;
	  }

		if ( xml.getElementsByTagName('relai') )
		{
			for (i=0 ; i < xml.getElementsByTagName('relai').length ; i++)
			{
				var Collection   = xml.getElementsByTagName('relai')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					if ( Collection.childNodes[j].firstChild != null )
					{
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
						if ( tagName == "image" )
						$('.relai_'+tagName+'_'+numero).attr("src", Collection.childNodes[j].firstChild.nodeValue);
						else if ( tagName == "infobulle" )
						$('.relai_'+tagName+'_'+numero).attr("alt",Collection.childNodes[j].firstChild.nodeValue);
						else if ( tagName == "nextcommand" ) {
						$('.relai_image_'+numero ).attr("data-command",Collection.childNodes[j].firstChild.nodeValue);
						$('.relai_css_'+numero ).attr("data-command",Collection.childNodes[j].firstChild.nodeValue);
						}
						else
						$('.relai_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
					//	}
					}
				}
			}
		}
		if ( xml.getElementsByTagName('espdevice') ) {
			for (i=0 ; i < xml.getElementsByTagName('espdevice').length ; i++) {
				var Device   = xml.getElementsByTagName('espdevice')[i];
				var numero       = Device.getAttribute('numero');
				//console.log(xml.getElementsByTagName('espdevice')[i]['value']);
				
					for (j=0 ; j< Device.childNodes.length ; j++) {
						if ( Device.childNodes[j].firstChild != null) {
							tagName = Device.childNodes[j].firstChild.parentNode.tagName;
							if ( tagName == "image" )
								$('.espdevice_'+tagName+'_'+numero).attr("src", Device.childNodes[j].firstChild.nodeValue);
							else if ( tagName == "infobulle" )
								$('.espdevice_'+tagName+'_'+numero).attr("alt",Device.childNodes[j].firstChild.nodeValue);
							else
								$('.espdevice_'+tagName+'_'+numero).html(Device.childNodes[j].firstChild.nodeValue);
						}
					}
				
			}
		}
		
		if ( xml.getElementsByTagName('razdevice') )
		{
			for (i=0 ; i < xml.getElementsByTagName('razdevice').length ; i++)
			{
				var Collection   = xml.getElementsByTagName('razdevice')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					if ( Collection.childNodes[j].firstChild != null )
					{
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
					//	if ( document.getElementById('relai_'+tagName+'_'+numero) )
					//	{
							if ( tagName == "image" )
								//document.getElementById('relai_'+tagName+'_'+numero).src = Collection.childNodes[j].firstChild.nodeValue;
				  $('.razdevice_'+tagName+'_'+numero).attr("src", Collection.childNodes[j].firstChild.nodeValue);
							else if ( tagName == "infobulle" )
								$('.razdevice_'+tagName+'_'+numero).attr("alt",Collection.childNodes[j].firstChild.nodeValue);
							else
								$('.razdevice_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
					//	}
					}
				}
			}
		}

		if ( xml.getElementsByTagName('btn') )
		{
			for (i=0 ; i<xml.getElementsByTagName('btn').length ; i++)
			{
				var Collection = xml.getElementsByTagName('btn')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
						if ( tagName == "image" )
							$('.btn_'+tagName+'_'+numero).attr("src",Collection.childNodes[j].firstChild.nodeValue);
						else if ( tagName == "nextcommand" )
							$('.btn_image_'+numero).attr("data-command",Collection.childNodes[j].firstChild.nodeValue);
						else
							$('.btn_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
				}
			}
		}
		if ( xml.getElementsByTagName('an') ) {
			for (i=0 ; i<xml.getElementsByTagName('an').length ; i++) {
				var Collection = xml.getElementsByTagName('an')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++) {
					if ( Collection.childNodes[j].firstChild ) {
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
						$('.an_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
						/*
						i=0;
						var justgauges = {};
						console.log('.justgauge.an_'+tagName+'_'+numero);
						jQuery('.justgauge.an_'+tagName+'_'+numero).each(function() {
							currentid='justgauge_'+i;
							$(this).attr('id', currentid)
							console.log('Update justgauge '+currentid);
							justgauges[i] = new JustGage({
								id: currentid,
								value: Collection.childNodes[j].firstChild.nodeValue,
								relativeGaugeSize: true,
								levelColorsGradient: true,
								donut: false,
								levelColors : ["#a9d70b", "#f9c802", "#ff0000"],
								decimals:true,
								pointer:true   
							});
							i++;
						});
						*/
					}
				}
			}
		}
		
		if ( xml.getElementsByTagName('cnt') )
		{
			for (i=0 ; i<xml.getElementsByTagName('cnt').length ; i++)
			{
				var Collection   = xml.getElementsByTagName('cnt')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					if ( Collection.childNodes[j].firstChild != null )
					{
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
					//	if ( document.getElementById('cnt_'+tagName+'_'+numero) )
					//	{
							if ( tagName == "image" )
								$('.cnt_'+tagName+'_'+numero).attr("src",Collection.childNodes[j].firstChild.nodeValue);
							else
								$('.cnt_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
					//	}
					}
				}
			}
		}

		if ( xml.getElementsByTagName('variable') )
		{
			for (i=0 ; i<xml.getElementsByTagName('variable').length ; i++)
			{
				var Collection   = xml.getElementsByTagName('variable')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					if ( Collection.childNodes[j].firstChild != null )
					{
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
						//if ( document.getElementById('variable_'+tagName+'_'+numero) )
						//{
							if ( tagName == "image" )
								$('.variable_'+tagName+'_'+numero).attr("src", Collection.childNodes[j].firstChild.nodeValue);
							else
								$('.variable_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
						//}
					}
				}
			}
		}

		if ( xml.getElementsByTagName('vartxt') )
		{
			for (i=0 ; i<xml.getElementsByTagName('vartxt').length ; i++)
			{
				var Collection   = xml.getElementsByTagName('vartxt')[i];
				var numero       = Collection.getAttribute('numero');
				for (j=0 ; j< Collection.childNodes.length ; j++)
				{
					if ( Collection.childNodes[j].firstChild != null )
					{
						tagName = Collection.childNodes[j].firstChild.parentNode.tagName;
						//if ( document.getElementById('vartxt_'+tagName+'_'+numero) )
						//{
						if ( tagName == "image" )
							$('.vartxt_'+tagName+'_'+numero).attr("src", Collection.childNodes[j].firstChild.nodeValue);
						else
							$('.vartxt_'+tagName+'_'+numero).html(Collection.childNodes[j].firstChild.nodeValue);
						//}
					}
				}
			}
		}
		
		if ( xml.getElementsByTagName('erreur').length != 0 )
		{
			var error = new Array();
			for (i=0 ; i<xml.getElementsByTagName('erreur').length ; i++)
				error.push('<i class="fa fa-exclamation-triangle"></i>&nbsp;'+xml.getElementsByTagName('erreur')[i].firstChild.nodeValue);
			jQuery('#errorPanel').html(error.join('<br>'));
			jQuery('#errorPanel').fadeIn(500);
		}
		else
		{
			jQuery('#errorPanel').html('');
			jQuery('#errorPanel').fadeOut(500);
		}
	}

		function startAnimationTime() {
			var objDate = new Date();
			$('.time').html(objDate.toLocaleString());
			setTimeout(function() {
				startAnimationTime();
			}, 1000);
		}
		
		function startUpdateLastAlerts() {
			setInterval("AjaxLoadLastAlert('menu1');", 3000);
		}

	
	// Permet de lancer une action (déclencher relai, scenario...)
	// type      : de l'item à déclencher (relai, btn, rasp433, scenario)
	// numero    : n° de l'item
	// value     : valeur on/off pour rasp433
	// ctrlbtnno : btn à controler avant déclenchement 
	function AjaxAction(type, numero, value, ctrlbtnno) {
		MessagePopupId = new PNotify({
			text: "Demande en cours de traitement",
			type: 'info',
			icon: 'fa fa-spinner fa-spin',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			},
			opacity: .75,
			shadow: true,
			animation: 'none'			
			/*width: "170px"*/
		});
		
		switch(type) {
			case "espdevice":
				url = "?app=Ws&page=actionesp.JSON&asxml=1&espdevice="+numero;
				go = true;
				break;
			case "razdevice":
				url = "?app=Ws&page=actionrazberry.JSON&asxml=1&razdevice="+numero;
				go = true;
				break;
			case "btn":
				url = "?app=Ws&page=actionipx800.JSON&asxml=1&btn="+numero;
				go = true;
				break;
			case "rasp433":
				url = "?app=Ws&page=actionrasp433.JSON&asxml=1&rasp433="+numero+"&value="+value;
				go = true;
				break;
			case "scenario":
				url = "?app=Ws&page=scenariorun.JSON&numero="+numero;
				go = true;
				break;
				
			case "relai":
				url = "?app=Ws&page=actionipx800.JSON&asxml=1&relai="+numero;
				if ((typeof(ctrlbtnno) !== "undefined") &&(ctrlbtnno !== '')) {
					urlverif = "?app=Ws&page=verif.JSON&type=getctrl&relai="+numero;
					console.log(urlverif);
					var optionsverif = {
						beforeSend:   function() { 
						},
						async : false, 
						
						success: function(json) { 
								if (json.statusmessage != "")
								go = confirm(json.statusmessage);
						},                                
																

						data:       {},
						url:        urlverif,
						dataType:   'json',
						contentType:"application/json; charset=utf-8"
					};
					$.ajax(optionsverif);
				}
				else
					go = true;
				break;
				
			default:
				url = "";
				go = false;
		}
	
		var options = {
			beforeSend:   function() { 
			},
			
			success: function(json) { 
					if (json.message != "")
						UpdateMessage(json.message);
					if (json.error != "")
						UpdateMessage(json.error, "error");
					if ((json.error == "") && (json.message == "")) 
						UpdateMessage("none");

						
			},                                
													

			data:       {},
			url:        url,
			dataType:   'json',
			contentType:"application/json; charset=utf-8"
		};
		 
		if (go)
			console.log ("ajax : "+url);
			$.ajax(options)

}

	// Permet de lancer une action (déclencher relai, scenario...)
	// class      : de l'item à déclencher (relai, btn, rasp433, scenario)
	// numero    : n° de l'item
	// command     : SetOn, SetOff, SetSwitch
	// ctrlbtnno : btn à controler avant déclenchement 
	function AjaxApiAction(apikey, classname, numero, command, ctrlbtnno) {
		MessagePopupId = new PNotify({
			text: "Demande en cours de traitement",
			type: 'info',
			icon: 'fa fa-spinner fa-spin',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			},
			opacity: .75,
			shadow: true,
			animation: 'none'			
			/*width: "170px"*/
		});
		
		url = '?app=Api&apikey='+apikey+'&class='+classname+'&numero='+numero+'&command='+command;
		
		if ((typeof(ctrlbtnno) !== "undefined") &&(ctrlbtnno !== '')) {
			urlverif = "?app=Ws&page=verif.JSON&type=getctrl&relai="+numero;
			console.log(urlverif);
			var optionsverif = {
				beforeSend:   function() { 
				},
				async : false, 
				success: function(json) { 
					if (json.statusmessage != "")
					go = confirm(json.statusmessage);
				},                                
				data:       {},
				url:        urlverif,
				dataType:   'json',
				contentType:"application/json; charset=utf-8"
			};
			//$.ajax(optionsverif);
		}

	
		var options = {
			beforeSend:   function() {},
			success: function(json) { 
				if (json.message != "")
					UpdateMessage(json.message);
				if (json.error != "")
					UpdateMessage(json.error, "error");
				if ((json.error == "") && (json.message == "")) 
					UpdateMessage("none");
			},                                
			data:       {},
			url:        url,
			dataType:   'json',
			contentType:"application/json; charset=utf-8"
		};
		 
		if (go)
			console.log ("ajax : "+url);
			$.ajax(options)

	}

	function AjaxCompensation(typean, carteid)
	{
		xml = GetXML("?app=Ws&page=verifxml&type=compensation&typean="+typean);
		if (xml.getElementsByTagName("status")[0].childNodes.length>0) 
		{
			if ( xml.getElementsByTagName("status")[0].firstChild.nodeValue == "OK" )
			{
				AjaxLoadSelectJson('#compensation', 'class=an&filtre_carteid='+carteid+'&filtre_type='+ xml.getElementsByTagName("statusmessage")[0].firstChild.nodeValue );
				document.getElementById('compensation').disabled = false;
			}
			else 
			{
				document.getElementById('compensation').disabled = true;
			}
		}  
		else 
		{
			document.getElementById('compensation').disabled = true;
		}
	}

	function AjaxUpdateRecommandedFrequence(xhr)
	{
		xml = xhr.responseXML;
		if ( xml == null )
			return UpdateMsg('Recuperation des informations impossible','alert');
		if ( xml.getElementsByTagName('debugtime') && document.getElementById('recommanded_frequence') )
		{
			document.getElementById('recommanded_frequence').innerHTML = xml.getElementsByTagName('debugtime')[0].firstChild.nodeValue;
		}
	}
	function sleep(milliseconds)
	{
		var start = new Date().getTime();
		for (var i = 0; i < 1e7; i++)
		{
			if ((new Date().getTime() - start) > milliseconds)
			{
				break;
			}
		}
	}

	function AjaxLoadInput(IdObject, request )
	{
		//Object = document.getElementById(IdObject);
		
		xmlobject = GetXML(request);
			
		elements = xmlobject.getElementsByTagName('element');
		for(var i=0;i < elements.length;i++)
		{
			child = elements[i];
			$("#"+IdObject).val(child.getAttribute('status'));
		}
	}

	function AjaxUpdateTypeCnt(xhr)
	{
		if ( xhr == null )
		{
			//UpdateMessage("Aucune info.");
		}
		else
		{
			xml = xhr.responseXML;
			if ( xml != null && xml.getElementsByTagName("message") && xml.getElementsByTagName("message")[0].childNodes.length>0 ) 
			{
				//UpdateMessage("Info Xml");
				DisplayMessage(xml.getElementsByTagName("message")[0].firstChild.nodeValue);
				document.getElementById('formule').innerHTML = xml.getElementsByTagName('formule')[0].firstChild.nodeValue;
				document.getElementById('unite').innerHTML = xml.getElementsByTagName('unite')[0].firstChild.nodeValue;
			}
			else
			{
				DisplayMessage("Aucune info.");
			}
		}
	}

	function ChangeModeGraph()
	{
		mode = document.getElementById('mode');
		if ( mode.options[mode.options.selectedIndex].value == 'Chart' )
		{
			NoDisplay('tr_pane_option');
			Hide('tr_navigator_option');
			Hide('tr_rangeSelector_option');
			Hide('tr_scrollbar_option');
		}
		else
		{
			Hide('tr_pane_option');
			NoDisplay('tr_navigator_option');
			NoDisplay('tr_rangeSelector_option');
			NoDisplay('tr_scrollbar_option');
		}
	}


	function AjaxLoadAnneeLi(IdMajObject, IdObject, IdSubObject, request)
	{ 
	  Object = document.getElementById(IdMajObject);
		xmlobject = GetXML(request);
		Object.innerHTML = ' ';
		
	  elements = xmlobject.getElementsByTagName('element');
		for(var i=0;i < elements.length;i++)
		{
			child = elements[i];     
		OnClick = "AjaxLoadMoisLi('"+IdObject+"', '"+IdSubObject+"', ' ', '?app=Ws&page=listdir&type=dir&dossier="+child.getAttribute('dossier')+"')";
			Object.innerHTML = Object.innerHTML + '<li><a href="#" onClick="'+OnClick+'">'+child.getAttribute('value')+'</a></li>';
	  }
	}
	function AjaxLoadMoisLi(IdMajObject, IdObject, IdSubObject, request)
	{ 
	  Object = document.getElementById(IdMajObject);
		xmlobject = GetXML(request);
		Object.innerHTML = ' ';
		
	  elements = xmlobject.getElementsByTagName('element');
		for(var i=0;i < elements.length;i++)
		{
			child = elements[i];     
		OnClick = "AjaxLoadJoursLi('"+IdObject+"', '"+IdSubObject+"', 'app=Ws&page=listdir&type=dir&dossier="+child.getAttribute('dossier')+"')";
			Object.innerHTML = Object.innerHTML + '<li><a href="#" onClick="'+OnClick+'">'+child.getAttribute('value')+'</a></li>';
	  }
	}

	function AjaxLoadJoursLi(IdMajObject, IdObjectFlow, request)
	{ 
	  Object = document.getElementById(IdMajObject);
		xmlobject = GetXML(request);
		Object.innerHTML = "";
		
	  elements = xmlobject.getElementsByTagName('element');
		for(var i=0;i < elements.length;i++)
		{
			child = elements[i];  
		html =  '<li><a href="?app=Mn&page=ViewCapturesImages&dir='+child.getAttribute('dossier')+'" target="images">'+child.getAttribute('value')+'</a></li>';
		//OnClick = "AjaxUpdateCapturesImages('"+IdObjectFlow+"','ListDir.php?type=file&dossier="+child.getAttribute('dossier')+"')";
			Object.innerHTML = Object.innerHTML + html;
	  }
	}

	function AjaxMySql(url, object, idcontrol) { 
			if (!object)
				var IdObject='IpxValid';
			else
				var IdObject  = 'IpxValid_'+object;
			jQuery.ajax({
				async: false,
				type: "POST", 
				//data: data, 
		  contentType: "application/json; charset=utf-8",
				beforeSend : function(){jQuery("#"+IdObject).addClass("ipx-running").removeClass("ipx-success ipx-error");},
				url:"?app=Ws&page=mysql.JSON&"+url,
				success: function(json) { 
					$.each(json, function(i, item)  { 
						status=item.valid;
						statusmessage=item.message;
				
						if (status!="true") {	// C'est en erreur
							jQuery("#"+IdObject).addClass("ipx-error").removeClass("ipx-success ipx-running");
							jQuery("#"+IdObject+' .ipx-error-mark').attr("data-infobulle", statusmessage);
							jQuery("#"+IdObject+' .ipx-success-mark').removeAttr("data-infobulle");
							jQuery("#"+object+' .span-label').html(statusmessage);
							jQuery(idcontrol).val(eval(jQuery(idcontrol).val())+1);
							jQuery(idcontrol).change();
							}
						else {	// Tout est bon
							jQuery("#"+IdObject).addClass("ipx-success").removeClass("ipx-error ipx-running");
							//$("#"+IdObject+' .ipx-success-mark').attr("data-infobulle", statusmessage);
							jQuery("#"+IdObject+' .ipx-error-mark').removeAttr("data-infobulle");
							jQuery("#"+IdObject+' .ipx-success-mark').removeAttr("data-infobulle");
							jQuery("#"+object+' .span-label').html(statusmessage);
							jQuery(idcontrol).change();
							}
					});

					//if ( typeof(callback) == "function" )
					//	callback(json);
				},
				error: function() {
					UpdateMessage("Une erreur est survenue !"); 
					return "error";
				},
				complete: function () {
					jQuery("#"+IdObject).removeClass("ipx-running");
				}
			});
	}
	
	function select2images() {
		select = document.getElementById(".select2.previewimage");
		select.style.background = select.options[select.selectedIndex].style.background;
	}
	
	function logout() {
		MessagePopupId = new PNotify({
			text: "Demande en cours de traitement",
			type: 'info',
			icon: 'fa fa-spinner fa-spin',
			hide: false,
			buttons: {
				closer: false,
				sticker: false
			},
			opacity: .75,
			shadow: false,
			animation: 'none'
		});
		
		var options = {
			beforeSend:   function() { 
			},
			
			success: function(json) { 
					UpdateMessage(json.msg);
					setInterval("window.location.reload();", 750);
			},                                
													

			data:       {},
			url:        '?app=Ws&page=logout.JSON',
			dataType:   'json',
			contentType:"application/json; charset=utf-8"
		};
		 
		$.ajax(options)
		   

	}
	
	function session_info() {
		bootbox.dialog({
					title: $("#sessioninfo").children("#title").html(),
					message: $("#sessioninfo").children("#corps").html(),
					buttons: {
						success: {
							label: "Ok",
							className: "btn-primary btn col-sm-2 col-xs-12 pull-right",
							/*
							callback: function () {
								var name = $('#name').val();
								var answer = $("input[name='awesomeness']:checked").val()
								Example.show("Hello " + name + ". You've chosen <b>" + answer + "</b>");
							}
							*/
						}
					}
				}
			);
	}
	


