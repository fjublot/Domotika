String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
   }

    function UpdateTitle(Message) {
        if ( document.getElementById("Title") )  {
            jQuery("Title").html(Message);
        }
    }


    function HideMessage(lienhref) {

      if (!lienhref)
       {
         lienhref = " ";
       }
          jQuery(".PopupStatusMsg").delay(1500).slideUp(300, function() {
              jQuery(this).remove();
              if (lienhref!=" ") {
                window.location.href = lienhref;
              }
           });

    }

    function UpdateMessage(Message, TypeMessage) {
                if (typeof(TypeMessage) == "undefined") {
                    TypeMessage = "success";
                }
               var options = {
                    text: Message
                };
                    options.title = "Terminé";
                    options.type = TypeMessage;
                    options.hide = true;
                    options.buttons = {
                        closer: true,
                        sticker: true
                    };
                    options.icon = "fa fa-check";
                    options.opacity = 1;
                    options.shadow = true;
                    MessagePopupId.update(options);
    }

    function DisplayMessage(Message, HideAfter) {

        new PNotify({
            title: "Information",
            text: Message,
            type: "info",
            nonblock: false
        });
    }

    function UpdateImage(IdObject)
    {
      jQuery("#preview_"+IdObject).attr("src",jQuery("#"+IdObject+" option:selected").val());
    }

    function DonnerFocus(Id) {
        document.getElementById(Id).focus();
    }


    function UpdateMsg(Message, Type) {
            if ( Message != "" )
            {
                jQuery(".Msg").html(Message);
                jQuery(".Msg").attr( "class", Type );
          jQuery(".Msg").css("display","block");
            }
            else
            {
                jQuery(".Msg").css("display","none");
            }
    }


    function UpdateTime() {
        if ( jQuery("#UpdateStatus") == null || jQuery("#UpdateStatus").val() == 0 )
        {
            jQuery("#UpdateStatus").val(1);
            GetXML("?app=Mn&page=xml&op=xml&detail=time", AjaxUpdateStatus, "", true);
        }
    }

    function ToogleDisp(object) {
        if ( document.getElementById(object).style.display == "block" )
        {
            document.getElementById(object).style.display = "none";
        }
        else
        {
            document.getElementById(object).style.display = "block";
        }
    }

    debut = new Date();
    debut = debut.getTime();


    function DoCheckAll(val) {
        inputs = document.getElementsByTagName("input");
        var i;
        for (i = 0;i < inputs.length;i++) {
            input = inputs[i];
            if ( input.type == "checkbox" ) {
                input.checked = val;
            }
        }
    }

    function HTMLEncode(wText) {
        if(typeof(wText)!="string")
        {
            wText=wText.toString();
        }
        wText=wText.replace(/&/g, "&amp;") ;
        wText=wText.replace(/"/g, "&quot;") ;
        wText=wText.replace(/</g, "&lt;") ;
        wText=wText.replace(/>/g, "&gt;") ;
        wText=wText.replace(/'/g, "&#146;") ;
        return wText;
    }

    //Fonction appelé au moment de fermer la page
    function closeIt() {
        document.getElementById("flag_stop_ajax").value = 1;
    }

    function sleep(milliseconds)
    {
      var start = new Date().getTime();
      var i;
      for (i = 0; i < 1e7; i++)
      {
        if ((new Date().getTime() - start) > milliseconds)
        {
          break;
        }
      }
    }

    function Disp(object) {
        if (document.getElementById(object)) {
            if ( document.getElementById(object).style.display != "block" )
            {
                document.getElementById(object).style.display = "block";
            }
        }
    }

    function NoDisplay(object) {
        if (document.getElementById(object)) {
            if ( document.getElementById(object).style.display != "" ) {
                document.getElementById(object).style.display = "";
            }
        }
    }
    
    function Hide(object) {
        if (document.getElementById(object)) {
            if ( document.getElementById(object).style.display != "none" ) {
                document.getElementById(object).style.display = "none";
            }
        }
    }

    function monter_tr(id) {
        position = parseInt(document.getElementById("pos_"+id).value);
        if ( document.getElementById("tr_ordre_"+(position-1)) ) {
            old_data = document.getElementById("tr_ordre_"+position).innerHTML;
            up_id = document.getElementById("pos_"+(position-1)).value;
            // Mise a jour du précédent
            document.getElementById("pos_"+up_id).value = position;
            document.getElementById("pos_"+position).value = up_id;
            document.getElementById("pos_"+id).value = position-1;
            document.getElementById("pos_"+(position-1)).value = id;
            document.getElementById("tr_ordre_"+position).innerHTML = document.getElementById("tr_ordre_"+(position-1)).innerHTML;
            document.getElementById("tr_ordre_"+(position-1)).innerHTML = old_data;
        }
    }

    function descendre_tr(id) {
        position = parseInt(document.getElementById("pos_"+id).value);
        if ( document.getElementById("tr_ordre_"+(position+1)) ) {
            old_data = document.getElementById("tr_ordre_"+position).innerHTML;
            down_id = document.getElementById("pos_"+(position+1)).value;
            // Mise a jour du suivant
            document.getElementById("pos_"+down_id).value = position;
            document.getElementById("pos_"+position).value = down_id;
            document.getElementById("pos_"+id).value = position+1;
            document.getElementById("pos_"+(position+1)).value = id;
            document.getElementById("tr_ordre_"+position).innerHTML = document.getElementById("tr_ordre_"+(position+1)).innerHTML;
            document.getElementById("tr_ordre_"+(position+1)).innerHTML = old_data;
        }
    }
