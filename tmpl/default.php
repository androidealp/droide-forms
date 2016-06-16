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

<form id="<?=$params->get('id_form');?>" class="form" method="POST" action="" data-extension="<?=$idmodule; ?>" data-droidevalid='<?=($validacao)?$validacao:''; ?>' >

<div>
	<input type="text" name="nome" />
</div>

<div>
	<input type="text" name="telefone" />
</div>

<div>
	<input type="text" name="assunto" />
</div>

<div>
	<textarea name="mensagem"></textarea>
</div>

<button class="uk-button uk-button-large uk-margin-large-top">Enviar</button>

</form>
