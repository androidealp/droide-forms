<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_droideforms
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * modDroideformsHelper contrala as validacoes e os ajax e recursos externos
 */
class modDroideformsHelper
{
	private $pass_cript_decript = 'droideFomrs@@_645A';
	public $errors = array();

	/**
	 * Pega o ajax
	 */
	public static function getAjax()
	{

		$input = JFactory::getApplication()->input;
		// valor em array inforando name = campo, value=valor
		$data  = $input->get('droideform');

		$id_extension = $input->get('droideform',0,'STRING');
		$post = $app->input->post->get('droideform');
		$params = $this->getModule($id_extension);

		if($this->validateField($params,$post)){
				return 'validado';
		}else{
				return 'Erro localizado ' . implode('<br />',$this->errors).'!';
		}

	}
	/**
	 * Pega o modulo
	 * @param int $id id do modulo
	 */
	private function getModule($id){
		$id = $this->Decrypt($id);
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
	/**
	 * Validar os elementos do field
	 * @param array $data lista de validacões
	 * @return bolean true false
	 */
	private function validateField($data,$post){
		$validFiltros = json_decode($data->get('filtros'));
		$validator = array();
		$return = true;
		$erros = array();
		$validador = $validFiltros;
		// foreach ($validFiltros->field_name as $k => $name) {
		// 	$validator[$name] =array(
		// 		'condition'=>$validFiltros->field_condition[$k],
		// 		'type'=>$validFiltros->tipo[$k],
		// 		'field_name'=>$validFiltros->field_name[$k],
		// 	);
		// }
/*
		foreach ($validator as $name => $tipo) {
			if(array_key_exists($name, $post)){
				$erro = $this->__detectValidate($tipo,$post[$name]);
				if($erro){
					$erros[] = $erro;
					$return = false;
				}
			}
		}*/

		$this->__erros = $validator;

		return false;

	}
	/**
	 * Enviar post
	 */
	private function _sendEmail($module, $post){
			$config = JFactory::getConfig();

			$frommail = $post[$module->get('email_de_cliente')];
			$fromname = $post[$module->get('nome_de_cliente')];
			$sender = array(
			   $frommail,
			   $fromname
			);

			$layout = $module->get('layout_envio');

			foreach ($post as $k => $field) {
				if(strrpos($layout, '{'.$k.'}')){
					$layout = str_replace('{'.$k.'}', $field, $layout);
				}
			}

			$emailTO = $config->get( 'mailfrom' );
			$mailmodulepara = $module->get('para');
			if(!empty($mailmodulepara)){
				$emailTO = $module->get('para');
			}

			$mail = JFactory::getMailer();
			$mail->isHTML(true);
			$mail->Encoding = 'base64';
			$mail->addRecipient($emailTO);
			$mail->setSender($sender);
			$mail->setSubject($module->get('assunto'));
			$mail->setBody($layout);
			try {
				$sent = $mail->Send();
			} catch (Exception $e) {
				$this->__erros[] = $e;
			}



			return $sent;
	}


/**
 * Encrypt text
 * @param int $data id para criptar
 * @return string criptada
 */
private function Encrypt($data)
{
	$password = $this->pass_cript_decript;
    $salt = substr(md5(mt_rand(), true), 8);

    $key = md5($password . $salt, true);
    $iv  = md5($key . $password . $salt, true);

    $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

    return base64_encode('Salted__' . $salt . $ct);
}

/**
 * Encrypt text
 * @param int $data id para decriptar
 * @return string decriptada
 */
function Decrypt($data)
{
		$password = $this->pass_cript_decript;

    $data = base64_decode($data);
    $salt = substr($data, 8, 8);
    $ct   = substr($data, 16);

    $key = md5($password . $salt, true);
    $iv  = md5($key . $password . $salt, true);

    $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);

    return $pt;
}



}
