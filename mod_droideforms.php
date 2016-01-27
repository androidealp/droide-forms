<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();

$doc->addScript(JUri::base() . 'media/mod_nextform/assets/enviar.js');

if(!$params->get('id_form',0)){
	$rand_id = 'form_'.rand(10,99999999);
	$params->set('id_form',$rand_id);
}

$filtros = json_decode($params->get('filtros'));
$validacao = array();
foreach ($filtros->tipo as $k => $tipo) {
	$validacao[] = array($tipo=>$filtros->field_name[$k]);
}

if($validacao){
 $validacao= json_encode($validacao);	
}


require JModuleHelper::getLayoutPath('mod_nextform', $params->get('layout', 'default'));
