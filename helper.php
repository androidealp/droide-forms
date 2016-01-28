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
		$data  = $input->get('data');
		return 'peguei os dados, ' . $data . '!';
	}
}