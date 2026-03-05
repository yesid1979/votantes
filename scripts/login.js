$('document').ready(function() { 
	/* handling form validation */
	$("#login-form").validate({
		rules: {
			password: {
				required: true,
			},
			user_email: {
				required: true,
				//email: true
			},
		},
		messages: {
			password:{
			  required: "Por favor digite su contraseña"
			 },
			user_email: "Por favor digite su usuario",
		},
		submitHandler: submitForm	
	});	   
	/* Handling login functionality */
	function submitForm() {		
		var data = $("#login-form").serialize();				
		$.ajax({				
			type : 'POST',
			url  : 'login.php',
			data : data,
			beforeSend: function(){	
				$("#error").fadeOut();
				$("#login_button").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Enviando ...');
			},
			success : function(response)
			{						
				if(response=="ok")
				{									
					$("#validando").html('<img src="ajax-loader.gif" /> &nbsp; Iniciando sesión ...');
					setTimeout(' window.location.href = "welcome.php"; ',4000);
				}
				else 
				{									
					$("#error").fadeIn(500, function()
					{						
						$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+' !</div>');
						$("#login_button").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Enviar');
					});
				}
			}
		});
		return false;
	}   
});