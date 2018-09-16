<script type="text/javascript">
    $(document).ready(function() { 
		formLogin();
		$("#username").focus();
		function	formLogin() {
			//Set the center alignment padding + border
       		var popMargTop = ($('#login-box').height() + 24) / 2; 
            var popMargLeft = ($('#login-box').width() + 24) / 2; 
             //$('.btn_close').attr('src','images/login/close_pop.png');
            $('#login-box').css({ 
            	'margin-top' : -popMargTop,
            	'margin-left' : -popMargLeft
            });
            $("#login-box").fadeIn();
            // Add the mask to body
            $('body').append('<div id="mask"></div>');
	        $('#mask').fadeIn(300);
            $('.login_wait').hide();	                                  
            
            //$('.ajax_spinner').fadeOut();
            //AjaxLoadInput('ajax', '?app=Ws&page=testajax');
        }
        $(function() {
            $("form#loginform").keypress(function (e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					$('.btn-sign').click();
					return false;
				} 
				else {
					return true;
				}
			});
        });                                
  
  
		$('form#loginform').submit(function() {
		var options = {
			beforeSend:   function() { 
				$('.login_notify').hide();
				$('.login_wait').show();
			},
			
			success: function(json) { 
					$('.login_notify').html(json.msg);
					$('.login_wait').hide();
					if (json.login == true) {
						 setTimeout(function() {window.location="?app=Mn";},1000);
						 $('.login_notify').css({'color': 'black'});  
					}   
					else { 
						setTimeout(function() {$('.login_notify').fadeOut();}, 2000);
						$('.login_notify').css({'color': 'red'});
					}
					$('.login_notify').fadeIn();
			},                                
													

			data:       {username: jQuery('#username').val(), 
						password: jQuery('#password').val(), 
						ajax:     jQuery('#ajax').val()},
			url:        ' ?app=Ws&page=logincheck.JSON',
			//iframe:   true,
			dataType:   'json',
			contentType:"application/json; charset=utf-8"
		};
		 
		$.ajax(options)
		   
		 });
    });
       
</script>              
