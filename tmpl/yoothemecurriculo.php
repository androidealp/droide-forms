<?php
/**
 * @package     Droideforms.Module
 * @subpackage  droideforms
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author 		André Luiz Pereira <[<andre@next4.com.br>]>
 */

defined('_JEXEC') or die;

?>


<div class="uk-grid uk-margin-large-top">
	<div class="uk-width-medium-4-10">
    <form id="<?=$params->get('id_form');?>" class="uk-form formulario-next4" enctype="multipart/form-data" method="POST"  data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat: true,delay:2000}" action="" data-extension="<?=$idmodule; ?>" data-droidevalid='<?=($validacao)?$validacao:''; ?>' >
		   <div class="uk-grid uk-grid-width-1-1" data-uk-grid-margin="">
				 <div class="uk-row-first">
					 <div class="uk-form-icon" data-uk-tooltip="{pos:'right',animation:'true'}" title="O nome é obrigatório">
						    <i class="uk-icon-exclamation uk-text-danger" data-uk-tooltip title="O nome é obrigatório"></i>
						    <input type="text" placeholder="Nome" value="André" name="nome" class="uk-width-1-1">
						</div>

				 </div>
				 <div class="uk-grid-margin uk-row-first">
					 <div class="uk-form-icon" data-uk-tooltip="{pos:'right',animation:'true'}" title="O e-mail é obrigatório e deve ser válido">
							 <i class="uk-icon-exclamation uk-text-danger" data-uk-tooltip title="O nome é obrigatório"></i>
							 <input type="email" name="email" value="" placeholder="E-mail" class="uk-width-1-1">
					 </div>
				</div>
				<div class="uk-grid-margin uk-row-first">
					<div class="uk-form-icon" data-uk-tooltip="{pos:'right',animation:'true'}" title="O Idade só pode ser numérico">
							<i class="uk-icon-exclamation uk-text-danger" data-uk-tooltip title="O nome é obrigatório"></i>
							<input type="number" name="idade" value="25" placeholder="Idade" class="uk-width-1-3"> <label>Anos</label>
					</div>
			 </div>
					<div class="uk-grid-margin uk-row-first">
						<div class="uk-form-file">
						    <a href="#" class="uk-button">Enviar Curriculo</a>
						    <input type="file" name="curriculo" id="form-file" accept=".doc, .docx, .pdf">
						</div>
						<label id="curriculo">(Somente doc, docx, pdf)</label>
					</div>

		       <div class="uk-grid-margin uk-row-first" data-uk-tooltip="{pos:'right',animation:'true'}" title="O campo de mensagem é obrigatório e deve ser válido">
						 <textarea rows="10" placeholder="Mensagem" name="msn" class="uk-width-1-1">Teste de envio</textarea>
					 </div>
		       <div class="uk-grid-margin uk-row-first"><button class="uk-button uk-button-large uk-width-1-1">Enviar</button></div>
		   </div>

		</form>
	</div>
	<div class="uk-width-medium-6-10">
	<?php echo $module->content;?>
	</div>
</div>


<script type="text/javascript">
var j = jQuery.noConflict();

j(document).ready(function(){
 // $('input[name=curriculo]')[0].files[0].name

	j('input[name=curriculo]').on('change',function(e){
		j('#curriculo').html( e.target.files[0].name);
	});

sendDroideForms.alert_class = 'uk-alert uk-alert-';
 sendDroideForms.divLoad = function(){
  return "<p class='uk-text-center'><i class='uk-icon-spinner uk-icon-spin'></i></p>";
 };
});
</script>
