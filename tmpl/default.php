<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>


<?php echo $module->content;?>

<form id="<?=$params->get('id_form');?>" class="uk-form" method="POST" action="index.php?nexturl=<?=$module->id;?>&position=<?=$module->position;?>" data-nextformvalid='<?=($validacao)?$validacao:''; ?>' >

<div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-3 uk-grid-width-1-1">
	<div class="uk-form-icon">
		<i class="uk-icon-calendar"></i><input type="text" placeholder="DATA" title="Data" name="formact[data]"  data-uk-datepicker="{format:'DD.MM.YYYY'}" class="uk-width-1-1">
	</div>
	<div>
		<div data-uk-timepicker="{format:'12h'}" class="uk-form-icon uk-display-block">
			<i class="uk-icon-clock-o"></i> <input type="text" placeholder="HORA" title="Hora" name="formact[hora]" class="uk-width-1-1">
		</div>
	</div>
	<div class="uk-form-icon">
		<i class="uk-icon-users"></i> <input type="text" name="formact[quantas_pessoas]" title="Quantas Pessoas" placeholder="QTD. DE PESSOAS" class="uk-width-1-1" >
	</div>
	<div class="uk-form-icon uk-grid-margin">
		<i class="uk-icon-user"></i> <input type="text" placeholder="NOME" name="formact[nome]" title="Nome" class="uk-width-1-1">
	</div>
	<div class="uk-form-icon uk-grid-margin">
		<i class="uk-icon-envelope"></i> <input type="text" placeholder="E-MAIL" name="formact[email]" title="E-mail" class="uk-width-1-1">
	</div>
	<div class="uk-form-icon uk-grid-margin">
		<i class="uk-icon-phone"></i> <input type="text" placeholder="TELEFONE" name="formact[telefone]" title="Telefone" class="uk-width-1-1">
	</div>
</div>
<div class="uk-grid">
	<div class="uk-width-1-1">
		<div id="nextinfo" style="display:none; text-shandow:#fff;">
		</div>
	</div>
</div>
<button class="uk-button uk-button-large uk-margin-large-top">FAZER RESERVA</button></form>
</form>


<script type="text/javascript">
var j = jQuery.noConflict();

j(document).ready(function(){
   sendNext.init('#<?=$params->get('id_form');?>','#nextinfo');
});

</script>