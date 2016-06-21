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


<?php echo $module->content;?>

<div class="uk-panel">
  <div class="uk-container uk-container-center">
    <div class="uk-grid">
      <div class="uk-width-1-1">
        <form id="<?=$params->get('id_form');?>" class="uk-form" method="POST" action="" data-extension="<?=$idmodule; ?>" data-droidevalid='<?=($validacao)?$validacao:''; ?>' >
            <fieldset>
                <legend>Formulário de contato</legend>
                <div class="uk-form-row"><input type="text" name="nome" placeholder="Nome" /></div>
                <div class="uk-form-row"><input type="text" name="email" placeholder="email" /></div>
                <div class="uk-form-row"><input type="text" name="telefone" placeholder="telefone" /></div>
                <div class="uk-form-row"><input type="text" name="assunto" placeholder="assunto" /></div>
                <div class="uk-form-row"><textarea name="mensagem" placeholder="mensagem"></textarea></div>
                <div class="uk-form-row"><button class="uk-button uk-button-large uk-margin-large-top">Enviar</button></div>
            </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>




<script type="text/javascript">
var j = jQuery.noConflict();

j(document).ready(function(){
// my custm class
sendDroideForms.alert_class = 'uk-alert uk-alert-';
//my cystom load
 sendDroideForms.divLoad = function(){
  return "<p class='uk-text-center'><i class='uk-icon-spinner uk-icon-spin'></i></p>";
 };

 // my custom alert
 // sendDroideForms.alert = function(type, addtext){
 //   j(sendDroideForms.id_form+'_alert').remove();
 //   j(sendDroideForms.id_form).before(
 //     j('<div/>',{
 //           id: sendDroideForms.id_form.replace('#', '')+'_alert',
 //           class:sendDroideForms.alert_class+type,
 //           html: addtext
 //       })
 //     );
 // };
});
</script>
