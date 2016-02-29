<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


class modDroideformsHelper
{
	public static function getAjax()
	{

		$input = JFactory::getApplication()->input;
		// valor em array inforando name = campo, value=valor
		$data  = $input->get('droideform');

		$id_extension = $input->get('droideform',0,'INT');

		$params = $this->getModule($id_extension);

		//$validar = $this->validateField($data);
		return 'peguei os dados, ' . print_r($params,true) . '!';
	}

	private function getModule($id){
		jimport('joomla.application.module.helper');
		$module = JModuleHelper::getModule('droideforms');
		$params = new JRegistry();
		if(is_array($module)){
			foreach ($module as $k => $mod) {
				if($mod->id == $id){
						$params->loadString($mod->params);
				}
			}
		}else{
			$params->loadString($module->params);
		}

		return $params;
		
	}

	private function validateField($data){
		$filtros = json_decode($params->get('filtros'));

		return $filtros;

	}
}