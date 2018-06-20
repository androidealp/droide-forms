/**
 * @package     Droideforms.Module
 * @subpackage  droideforms
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author 		André Luiz Pereira <[<andre@next4.com.br>]>
 */

var sendDroideForms = {
	next_erro:[],
	ob_form:'',
	id_form:'',
	fm_data:'',
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
			j(id_form).one('submit',function(event){
				sendDroideForms.fm_data = new FormData(this);
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
	allEvents:function()
	{



		// clear clone
		j('[data-droideclear]').on('click',function(e){
        e.preventDefault();
        bt = j(this);

        j(bt.data('droideclear')+' > *').remove();

      });

			// clone sys
			j(document).on('click','[data-droideclone]',function(e){
        e.preventDefault();
        bt = j(this);
        data = bt.data('droideclone');

        getBox = j(data.clonar).clone();

        grad = j("<div id='boxaleatorio' class='box-clone'>");
        grad.append(getBox);
        j(data.in).append(grad);

      });

			j('[data-droideenable]').on('click',function(e){
				var elemento = j(this);
				var data = elemento.data('droideenable');

				var elementsShow = (typeof data.show != 'undefined')?data.show.split(','):[];
				var elementsHide = (typeof data.hide != 'undefined')?data.hide.split(','):[];

				j.each(elementsShow,function(i, v) {
					j(v).show('slow');
				});

				j.each(elementsHide,function(i, v) {
					j(v).hide('slow');
				});

			});

			// turnonoff
			j(document).on('change','[data-droideonoff]', function(e){

       element =j(this);
       dados = element.data('droideonoff');
       valor = element.val();

       if(typeof dados.equal != 'undefined')
       {

         if(dados.equal.opt == valor)
         {
           sendDroideForms.setTurn(dados.equal);
         }

       }

       if(typeof dados.diferent != 'undefined')
       {

         if(dados.equal.opt != valor)
         {
           sendDroideForms.setTurn(dados.diferent);
         }

       }

      });


			//droide cep
			j('[data-droidecep]').on('click',function(e){
        e.preventDefault();
        bt = j(this);
        data = bt.data('droidecep');
        fieldcep = j(data.elcep);
        fields = data.fieldssearch;

        if(typeof fieldcep != 'undefined' && fieldcep.val().length > 0)
        {
          fieldtratada = fieldcep.val().replace('-','');
          fieldcep.attr('disabled',true);
          j.each(fields, function(i, e){

            j(e).attr('disabled',true);

          });


          j.get('https://viacep.com.br/ws/'+fieldtratada+'/json/',function(datajson){
            fieldcep.attr('disabled',false);
            objectmark = {};
            j.each(fields, function(i, e){
              objectmark[e] = i;
              j(e).attr('disabled',false);

            });

            j.each(objectmark, function(i, e){
              if(typeof datajson[e] != 'undefined' ){
                  j(i).val(datajson[e]);
              }
            });

          });

        }

     });

	},
	setTurn:function(dados){
          var retorno = false;
          if(typeof dados.on != 'undefined')
          {
            j(dados.on).show('slow');
          }

          if(typeof dados.off != 'undefined')
          {
            j(dados.off).hide('slow');
          }

  },
	logs:function(msn){
		if(typeof msn == "string"){
			console.log('return: '+msn);
		}
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
				}else{
					sendDroideForms.next_erro.push(mensagem);
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
				}
			};
		}
		if(type == 'f_custom'){
				sendDroideForms.custom_validator(obj,mensagem);
		}
	},
	validador_cpf:function(strCPF) {
		var Soma;
		var Resto;
		Soma = 0;
		var strCPF = strCPF.replace(/\.|\-/gi,"");

		if (strCPF == "00000000000") return false;
		 
		for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
		Resto = (Soma * 10) % 11;
		 
		if ((Resto == 10) || (Resto == 11))  Resto = 0;
		if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
		 
		Soma = 0;
		for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
		Resto = (Soma * 10) % 11;
		 
		if ((Resto == 10) || (Resto == 11))  Resto = 0;
		if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
		return true;
	},
	validar_cnpj:function(cnpj) {
 
		cnpj = cnpj.replace(/[^\d]+/g,'');
	 
		if(cnpj == '') return false;
		 
		if (cnpj.length != 14)
			return false;
	 
		// Elimina CNPJs invalidos conhecidos
		if (cnpj == "00000000000000" || 
			cnpj == "11111111111111" || 
			cnpj == "22222222222222" || 
			cnpj == "33333333333333" || 
			cnpj == "44444444444444" || 
			cnpj == "55555555555555" || 
			cnpj == "66666666666666" || 
			cnpj == "77777777777777" || 
			cnpj == "88888888888888" || 
			cnpj == "99999999999999")
			return false;
			 
		// Valida DVs
		tamanho = cnpj.length - 2
		numeros = cnpj.substring(0,tamanho);
		digitos = cnpj.substring(tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--) {
		  soma += numeros.charAt(tamanho - i) * pos--;
		  if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
			return false;
			 
		tamanho = tamanho + 1;
		numeros = cnpj.substring(0,tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--) {
		  soma += numeros.charAt(tamanho - i) * pos--;
		  if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
			  return false;
			   
		return true;
		
	},
	validador_data_nornal:function(value)
	{

		dt_var = value;
		temp = dt_var.split('/');
		d = new Date(temp[2]+'/'+temp[1]+'/'+temp[0]);

		
		if(d == 'Invalid Date')
		{
			return false;
		}else if(!(d && (d.getMonth() +1) == temp[1] && d.getDate()== Number(temp[0]) && d.getFullYear() == Number(temp[2])))
		{

			return false;
		}else{
			console.log('erro 3');
			 var hoje = new Date();
			var nascimento = new Date(temp[2]+'/'+temp[1]+'/'+temp[0]);
			// var aniversario = new Date(hoje.getFullYear(), nascimento.getMonth(), nascimento.getDate());
			var idade = (hoje.getFullYear() - nascimento.getFullYear());
			
			if( idade < 0)
			{
			 	return false
			}

		}

		return true;

	},
	validador_data:function(value)
	{
		dt_var = value;
		temp = dt_var.split('/');
		d = new Date(temp[2]+'/'+temp[1]+'/'+temp[0]);

		
		if(d == 'Invalid Date')
		{
			return false;
		}else if(!(d && (d.getMonth() +1) == temp[1] && d.getDate()== Number(temp[0]) && d.getFullYear() == Number(temp[2])))
		{

			return false;
		}else{
			console.log('erro 3');
			 var hoje = new Date();
			var nascimento = new Date(temp[2]+'/'+temp[1]+'/'+temp[0]);
			// var aniversario = new Date(hoje.getFullYear(), nascimento.getMonth(), nascimento.getDate());
			var idade = (hoje.getFullYear() - nascimento.getFullYear());
			
			if(idade > 105 || idade < 18)
			{
			 	return false
			}

		}

		return true;

	},
	custom_validator:function(obj,mensagem){
		return sendDroideForms.logs('Create instance of the custom_validator.');
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
		return "<div class='cssload-spin-box'></div> Load...";
	},
	__serializeAll:function(){
		sendDroideForms.fm_data.append('droide',1);
		sendDroideForms.fm_data.append('option','com_ajax');
		sendDroideForms.fm_data.append('module','droideforms');
		sendDroideForms.fm_data.append('id_ext',sendDroideForms.ob_form.data('extension'));
		sendDroideForms.fm_data.append('format','json');
		 return sendDroideForms.fm_data;
	},
	_sendajax:function(){

		data_ext = sendDroideForms.ob_form.data('extension');

		var formdata = sendDroideForms.__serializeAll();
		j.ajax({
			type   			: 'POST',
			data   			: formdata,
			dataTyoe:'JSON',
			contentType: false,
      processData: false,
			cache:false,
			//enctype			: 'multipart/form-data',
			beforeSend	:function(){
				sendDroideForms.alert(sendDroideForms.tipe_erros_class.info,sendDroideForms.divLoad());
				j(sendDroideForms.id_form+' input[type=submit], '+sendDroideForms.id_form+' button').each(function() {
					 j(this).attr('disabled',true);
				});
			},
			success: function (response) {
				dados = jQuery.parseJSON( response.data );
				if(dados.log != ''){
					sendDroideForms.logs(dados.log);
				}
				if(dados.error){
					sendDroideForms.alert(sendDroideForms.tipe_erros_class.danger,dados.msn);
					j(sendDroideForms.id_form+' input[type=submit], '+sendDroideForms.id_form+' button').each(function() {
						 j(this).removeAttr('disabled');
					});
				}else{

					sendDroideForms.alert(sendDroideForms.tipe_erros_class.success,dados.msn);
					if(sendDroideForms.success_disable_forms){
						sendDroideForms.ob_form.remove();
					}else{
						j.each(sendDroideForms.ob_form.find('[name]'),function(index, el) {
								j(this).val('');
								j(this).attr('disabled',true);
						});

						j(sendDroideForms.id_form+' input[type=submit], '+sendDroideForms.id_form+' button').each(function() {
						   j(this).attr('disabled',true);
						});

					}

				}

			},
			error:function(xhr, ajaxOptions, thrownError)
			{
				console.log(xhr);
				console.log(ajaxOptions);
				console.log(thrownError);

			}
		});
	}
}
