<?php
   /**
   * @package     Droideforms.Module
   * @subpackage  droideforms
   *
   * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
   * @license     GNU General Public License version 2 or later; see LICENSE.txt
   * @author    André Luiz Pereira <[<andre@next4.com.br>]>
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
                   <?php
                     $stel = [
                       'show'=>'#telefone_com',
                       'hide'=>'#telefone_pes',
                     ];

                     $ntel = [
                       'hide'=>'#telefone_com',
                       'show'=>'#telefone_pes',
                     ];
                    ?>
                   <p><label class="uk-margin-small-top"><input type="radio" name="check_tel" data-droideenable='<?=json_encode($stel)?>' checked value="Tenho Telefone"> Tem telefone Comercial?</label>
                   <label class="uk-margin-small-top"><input type="radio" name="check_tel" data-droideenable='<?=json_encode($ntel)?>' value="Não tenho telefone"> Não só pessoal</label></p>

                   <div class="uk-form-row">
                     <input type="text" id="telefone_com"  name="telefonecom" placeholder="telefone Comercial" />
                     <input type="text" id="telefone_pes" style="display:none"  name="telefonepes" placeholder="Telefone pessoal" />
                   </div>
                   <div class="uk-panel">
                     <h2>Endereço</h2>

                        <div class="uk-grid uk-grid-small">
                           <div class="uk-width-1-2">
                             <?php
                              $buscacep = [
                                'elcep'=>'.busca-cep',
                                'fieldssearch'=>[
                                  'logradouro'=>'.logradouro',
                                  'bairro'=>'.bairro',
                                  'localidade'=>'.localidade',
                                  'uf'=>'.uf'
                                ]
                              ];
                              ?>
                              <input class="uk-width-3-4 busca-cep" name="cep" type="text" placeholder="Buscar o cep" />
                              <span data-droidecep='<?php echo json_encode($buscacep) ?>' class="uk-button uk-button-primary">validar cep</span>
                           </div>
                           <div class="uk-width-1-2">
                              <input class="uk-width-1-1 bairro" name="bairro" type="text" placeholder="Bairro">
                           </div>
                        </div>

                        <div class="uk-grid uk-grid-small">
                           <div class="uk-width-8-10">
                              <input class="uk-width-1-1 logradouro" name="endereco" type="text" placeholder="Endereço">
                           </div>
                           <div class="uk-width-2-10">
                              <input class="uk-width-1-1" name="numero" type="text" placeholder="Nº">
                           </div>
                        </div>
                        <div class="uk-grid uk-grid-small">
                           <div class="uk-width-4-10">
                              <input class="uk-width-1-1 complemento" name="complemento" type="text" placeholder="Complemento">
                           </div>
                           <div class="uk-width-4-10">
                              <input class="uk-width-1-1 localidade" name="cidade" type="text" placeholder="Cidade">
                           </div>
                           <div class="uk-width-2-10">
                              <input class="uk-width-1-1 uf" name="uf" type="text" placeholder="UF">
                           </div>
                        </div>

                   </div>
                   <!-- end endereco -->

                   <div class="uk-panel">
                      <h3>Lista de Usuários </h3>
                      <div id="object_clone" style="margin-top:10px;">
                          <input class="uk-width-1-1" name="Usuarios[nome][]" type="text" placeholder="Nome do usuário" />
                          <input class="uk-width-1-1" name="Usuarios[id][]" type="text" placeholder="Id do usuário" />
                      </div>
                      <div id='areas'>

                      </div>

                      <?php
                        $clone = [
                          'clonar'=>'#object_clone',
                          'in'=>'#areas',
                          'classbox'=>'.uk-panel-box uk-panel-primary'
                        ];

                       ?>

                       <p><button type="button" name="button" class="uk-button uk-button-primary" data-droideclone='<?=json_encode($clone)?>'>Adicionar nova área</button>
                         <button type="button" name="button" class="uk-button uk-button-default" data-droideclear='#areas'>Limpar</button></p>
                   </div>

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
     // enableEffects
     sendDroideForms.allEvents();
     sendDroideForms.alert_class = '';
     sendDroideForms.divLoad = function(){
       return "<p class='uk-text-center'><i class='uk-icon-spinner uk-icon-spin'></i></p>";
     };

   });
</script>
