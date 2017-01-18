<?php
/**
 * @package     Droideforms.Module
 * @subpackage  droideforms
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author 		André Luiz Pereira <[<and4563@gmail.com>]>
 */

defined('_JEXEC') or die;
$custom_vars = [];

//instance plugin
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('droideforms');
$dispatcher->trigger('onDroideformsInit',[&$custom_vars]);

require_once __DIR__ . '/helper.php';

$helper = new modDroideformsHelper();

//$helper->getAjax();

$doc = JFactory::getDocument();

//ler script php
//$doc =& JDocument::getInstance( 'mytype' );
//$renderer =& $doc->loadRenderer( 'myrenderer' );

$doc->addScript(JUri::base() . 'media/mod_droideforms/assets/enviar.js');

$loadJquery = $params->get('loadJquery', 0);
$loadCss = $params->get('loadCss', 1);

//Encrypt id of the module
$idmodule = $helper->Encrypt($module->id);

if($loadJquery){
	$doc->addScript(JUri::base() . 'media/mod_droideforms/assets/jquery-1.12.0.min.js');
}

if($loadCss){
	$doc->addStyleSheet(JUri::base() . 'media/mod_droideforms/assets/load.css');
}

$id_form = $params->get('id_form');

$js = <<<JS

var j = jQuery.noConflict();

j(document).ready(function(){
	j('#$id_form button').on('click',function(){
		 sendDroideForms.init('#$id_form');
	});
});

JS;
$doc->addScriptDeclaration($js);

if(!$params->get('id_form',0)){
	$rand_id = 'form_'.rand(10,99999999);
	$params->set('id_form',$rand_id);
}

$filtros = json_decode($params->get('filtros'));
$validacao = array();
foreach ($filtros->tipo as $k => $tipo) {
	$validacao[] = array($tipo=>$filtros->field_name[$k],'condition'=>$filtros->field_condition[$k],'mensagem'=>$filtros->text_validador[$k]);
}

if($validacao){
 $validacao= json_encode($validacao);
}

if ($params->def('prepare_content', 1))
{
	JPluginHelper::importPlugin('content');
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_custom.content');
}


$dispatcher->trigger('onDroideformsBeforeLayout', array(&$id_form, &$js, &$params, &$validacao,&$custom_vars));

require JModuleHelper::getLayoutPath('mod_droideforms', $params->get('layout', 'default'));
