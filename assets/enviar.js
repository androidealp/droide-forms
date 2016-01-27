
var sendNext = {
	next_erro:[],
	init: function(id_form, id_alert){

			j(id_form).submit(function(event){
				event.preventDefault();
				j(id_alert).show('hide');
				$form 		= j(this);
				$formdata 	= j(this).data('nextformvalid');
				$action 	= $form.prop('action');
			//validate
			j.each($formdata,function(index, el) {
				validador = Object.keys(el).join(",");
				//name do validader nome / email / teste
				name = el[validador];
				//procurar nos campos
				j.each($form.find('[name]'),function(index, el) {
					var sear = j(el).prop('name').indexOf("["+name+"]");
					if( sear != -1 ){
						sendNext._validate(validador,j(el));
					}
				});
				//fim do procurar por campos
			});
			//fim de procurar validacao

			if(sendNext.next_erro.length != 0){

				j(id_alert).removeClass('uk-alert-success');

				j(id_alert).addClass('uk-alert-danger');
				j(id_alert).html('<a href="" class="uk-alert-close uk-close"></a>'+sendNext.next_erro.join("<br />"));
				j(id_alert).show('slow');
				sendNext.next_erro = [];
			}else{
				
				sendNext._ajax($action, $form,id_alert);
			}
			
			return false;
		});
	},

	_validate:function(type, obj){

		if(type == 'f_required'){

			if(obj.val() == ''){
				this.next_erro.push('O campo '+obj.prop('title')+' é obrigatório');
			}
		}

		if(type == 'f_email'){
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(obj.val() == '' || emailReg.test(obj.val()) == false){
				this.next_erro.push('O campo de E-mail deve ser válido e é obrigatório');
			}
		}

	},
	_ajax:function(geturl, getdataform, id_alert){
		formserialize = getdataform.serialize();
		j.ajax({
				url: geturl,
				data: formserialize,
				type: "POST",
				beforeSend: function(){
					j(id_alert).removeClass('uk-alert-danger');
					j(id_alert).removeClass('uk-alert-success');
					j(id_alert).addClass('uk-alert').html('Aguarde um momento estamos enviado seu email...').fadeIn(1000);
				},
			  	success: function(response) {
			  		console.log( response );
			  		 response = JSON.parse(response);
			  	
					if(response.erro) {
						
						j(id_alert).removeClass('uk-alert-success');


						j(id_alert).addClass('uk-alert-danger').html(response.msn).fadeIn(1000);
						
					} else {

						j.each(getdataform.find('[name]'),function(index, el) {
							j(el).val('');
						});

						j(id_alert).removeClass('uk-alert-danger');
						j(id_alert).addClass('uk-alert-success').html(response.msn).fadeIn(1000); 
					}
			  	}
			});	

	}



}




/*
		var send = function() {
			
			$.ajax({
				url: 'acoes/contato.php',
				data: 'contato=' + JSON.stringify($formData),
				type: "POST",
				beforeSend: function(){

					if($('#dbdContactResponse').hasClass('alert-error')){
						$('#dbdContactResponse').removeClass('alert-error');
					}

					$('#dbdContactResponse').addClass('alert-success').html('Aguarde um momento estamos enviado seu email...').fadeIn(1000);
				},
			  	success: function(response) {

			  		 response = JSON.parse(response);
			  	
					if(response.erro) {
						
						if($('#dbdContactResponse').hasClass('alert-success')){
							$('#dbdContactResponse').removeClass('alert-success');
						}

						$('#dbdContactResponse').addClass('alert-error').html(response.status).fadeIn(1000);
						
					} else {

						if($('#dbdContactResponse').hasClass('alert-error')){
							$('#dbdContactResponse').removeClass('alert-error');
						}

						$mainElement.fadeOut(1000, function() {
							$('#dbdContactResponse').addClass('alert-success').html(response.status).fadeIn(1000);
						});
						 
					}
			  	}
			});	
		}

		*/
