/**
 * sendDroideForms
 * script para manipulação do formulário
 */
var sendDroideForms = {
	next_erro:[],
	ob_form:'',
	id_form:'',
	success_disable_forms:true, /* recommend true */
	alert_class:'alert alert-',
	tipe_erros_class:{
		danger:'danger',
		warning:'warning',
		info:'info',
		success:'success'
	},
	init: function(id_form){

			sendDroideForms.ob_form = j(id_form);
			sendDroideForms.id_form = id_form;

			j(id_form).submit(function(event){
				event.preventDefault();
				sendDroideForms.next_erro = [];
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
				j.each(sendDroideForms.ob_form.find('[name]'),function(index, el) {
					//var sear = j(el).prop('name').indexOf("["+name+"]");
					//console.log(j(el).prop('name')+' = '+name);

					if( j(el).prop('name') == name ){
						sendDroideForms._validate(validador,j(el),mensagem,condition);
					}
				});
				//fim do procurar por campos
			});
			//fim de procurar validacao

			if(sendDroideForms.next_erro.length != 0){
				sendDroideForms.alert('danger',sendDroideForms.next_erro.join("<br />"));
				sendDroideForms.next_erro = [];

			 }else{

				sendDroideForms._sendajax();

			 }

			return false;
		});
	},

	_validate:function(type, obj,mensagem,condition){

		if(type == 'f_required'){

			if(obj.val() == ''){
				sendDroideForms.next_erro.push(mensagem);
			}
		}

		if(type == 'f_email'){
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(obj.val() == '' || emailReg.test(obj.val()) == false){
				sendDroideForms.next_erro.push(mensagem);
			}
		}

		if(type == 'f_integer'){

			if(Math.floor(obj.val()) != obj.val() || j.isNumeric(obj.val()) != true){

				sendDroideForms.next_erro.push(mensagem);
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
					sendDroideForms.next_erro.push(mensagem);
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
					sendDroideForms.next_erro.push(mensagem);
						console.log('nao permite tamanho '+kbps);
				}

			};

		}

	},
	alert:function(type, addtext){
		//remove o ultimo alert
		j(sendDroideForms.id_form+'_alert').remove();
		//imprime o alert
		j(sendDroideForms.id_form).before(
			j('<div/>',{
				    id: sendDroideForms.id_form.replace('#', '')+'_alert',
				    class:sendDroideForms.alert_class+type,
				    html: addtext
				})
			);
	},

	divLoad:function(){
		return "<img src='../media/mod_droideforms/assets/ajax-loader.gif' /> Load...";
	},
	_sendajax:function(){

		data_ext = sendDroideForms.ob_form.data('extension');

		//var formdata   = JSON.stringify(sendDroideForms.ob_form.serializeArray());
		var formdata   = sendDroideForms.ob_form.serializeArray();
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
				sendDroideForms.alert(sendDroideForms.tipe_erros_class.info,sendDroideForms.divLoad());
			},
			success: function (response) {
				dados = jQuery.parseJSON( response.data );

				if(dados.error){
					sendDroideForms.alert(sendDroideForms.tipe_erros_class.danger,dados.msn);
				}else{

					sendDroideForms.alert(sendDroideForms.tipe_erros_class.success,dados.msn);
					if(sendDroideForms.success_disable_forms){
						sendDroideForms.ob_form.remove();
					}else{
						j.each(sendDroideForms.ob_form.find('[name]'),function(index, el) {
								j(this).val('');
								j(this).attr('disabled',true);
						});

						j('#'+sendDroideForms.id_form+' input[type=submit], #'+sendDroideForms.id_form+' button').each(function() {
						   j(this).attr('disabled',true);
						});

					}

				}

			}
		});
	}



}
