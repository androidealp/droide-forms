
var sendNext = {
	next_erro:[],
	ob_form:'',
	id_form:'',
	init: function(id_form){

			sendNext.ob_form = j(id_form);
			sendNext.id_form = id_form;

			j(id_form).submit(function(event){
				event.preventDefault();
				sendNext.next_erro = [];
				$formdata 	= j(this).data('droidevalid');

			//validate
			j.each($formdata,function(index, el) {
				//console.log(el);
				mensagem = el['mensagem'];
				condition = el['condition'];
				//delete el['mensagem'];
				validador = Object.keys(el).join(",");

				validador = validador.replace(',mensagem','');

				validador = validador.replace(',condition','');
				//name do validader nome / email / teste
				name = el[validador];
				

				//procurar nos campos
				j.each(sendNext.ob_form.find('[name]'),function(index, el) {
					//var sear = j(el).prop('name').indexOf("["+name+"]");
					//console.log(j(el).prop('name')+' = '+name);

					if( j(el).prop('name') == name ){
						sendNext._validate(validador,j(el),mensagem,condition);
					}
				});
				//fim do procurar por campos
			});
			//fim de procurar validacao

			if(sendNext.next_erro.length != 0){
				sendNext.alert('danger',sendNext.next_erro.join("<br />"));				
				sendNext.next_erro = [];

			}else{
				
				//sendNext._ajax($action, $form,id_alert);
				sendNext._sendajax();
				
			}
			
			return false;
		});
	},

	_validate:function(type, obj,mensagem,condition){

		if(type == 'f_required'){

			if(obj.val() == ''){
				sendNext.next_erro.push(mensagem);
			}
		}

		if(type == 'f_email'){
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(obj.val() == '' || emailReg.test(obj.val()) == false){
				sendNext.next_erro.push(mensagem);
			}
		}

		if(type == 'f_integer'){

			if(Math.floor(obj.val()) != obj.val() || j.isNumeric(obj.val()) != true){
				
				sendNext.next_erro.push(mensagem);	
			}

		}

		if(type=='f_file'){
			//add contiion

			var file = obj.val();

			var exts = condition.split(';');

			if ( file ) {
				var get_ext = file.split('.');
				get_ext = get_ext.reverse();
				if ( j.inArray( get_ext[0].toLowerCase(), exts) > -1 ){
					console.log('permite '+get_ext[0].toLowerCase());
				}else{
					sendNext.next_erro.push(mensagem);
					console.log('nao permite '+get_ext[0].toLowerCase());
				}
			}
			
		}


		if(type=='f_size'){
			//add condition

			if(typeof obj[0].files[0] !== 'undefined'){

				var file = obj[0].files[0].size;

				kbps = (file/1024);

				if(kbps>condition){
					sendNext.next_erro.push(mensagem);
						console.log('nao permite tamanho '+kbps);
				}
   
			};

			


		}

	},
	alert:function(type, addtext){
		//remove o ultimo alert
		j(sendNext.id_form+'_alert').remove();
		//imprime o alert
		j(sendNext.id_form).prepend(
			j('<div/>',{
				    id: sendNext.id_form.replace('#', '')+'_alert',
				    class:'alert alert-'+type,
				    html: addtext
				})
			);
	},
	_sendajax:function(){

		data_ext = sendNext.ob_form.data('extension');

		//var formdata   = JSON.stringify(sendNext.ob_form.serializeArray());
		var formdata   = sendNext.ob_form.serializeArray();
			request = {
					'option' : 'com_ajax',
					'module' : 'droideforms',
					'droideform': formdata,
					'id_ext': data_ext,
					'format' : 'json'
				};
		j.ajax({
			type   : 'POST',
			data   : request,
			beforeSend:function(){
				sendNext.alert('info','Aguarde o envio');
			},
			success: function (response) {
				sendNext.alert('info',response.data);
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
