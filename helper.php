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

/**
 * modDroideformsHelper control send and control submit forms
 */
class modDroideformsHelper
{
	private static $pass_cript_decript = 'droideFomrs@@_645A'; // scret key
	public static $errors = array();
	public static $log = ""; //self::$log .= "retornou: __size val= ".$valor['size']." || size = ".$attr['condition'];

	/**
	 * return submit result ajax.
	 */
	public static function getAjax()
	{

		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$input = $app->input;
		$id_extension  = $input->get('id_ext',0,'STRING');
		$post_check = $input->post->get('droide',0,'INT');
		$params = self::getModule($id_extension);
		$files = $input->files->getArray();
		$posts = $input->post->getArray();
		$return = array();
		if(self::validateField($params,$posts,$files)){

			$return = self::_sendEmail($params, $posts, $files);

		}else{
			$return = json_encode(array(
			 		'error'=>1,
			 		'msn'=>self::$errors,
					'log'=>self::$log
				));
		}

		return  $return;


		// if($post_check && $post_check == 1){
		//
		// 	$files = $input->files->getArray();
		// 	$posts = $input->post->getArray();
		//
		//
		//
		// 	$doc->setMimeEncoding('application/json');
		// 	 			JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		// 	 			print_r($files);
		// 	 			$app->close();
		// }


		//
		//
		//
		// //$files = $input->files->get('droideform',0,'ARRAY');
		// $files = $_FILES;
		// $params = self::getModule($id_extension);
		//
		// // /self::validateField($params,$post) &&
		// if(self::validateFiles($files)){
		//
		// 	return  self::_sendEmail($params, $post);
		//
		// }else{
		//
		// 	$error = array(
		// 		'error'=>1,
		// 		'msn'=>self::$errors,
		// 		'log'=>self::$log
		// 		);
		//
		// 		$doc->setMimeEncoding('application/json');
		// 			JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		// 			echo json_encode($error);
		// 			$app->close();
		//
		// 	//return  json_encode($error);
		// }

	}
	/**
	 * Return params module
	 * @param int $id id do modulo
	 */
	private function getModule($id){
		//decript id of the module
		$id = self::Decrypt($id);
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
	 * Valid Elements post
	 * @param array $data validations List
	 * @return bolean true or false
	 */
	private function validateField($data,$post, $files = array()){
		$validFiltros = json_decode($data->get('filtros'),true);
		$return = true;
		$tratamento = array();
		$org_Errors = array();
		//organizo os erros listados no adm e organizando em uma lista com o indice do field name
		foreach ($validFiltros['field_name'] as $k => $fild_name) {

			$validador = array(
				'tipo'=>$validFiltros['tipo'][$k],
				'condition'=>$validFiltros['field_condition'][$k],
				'msn'=>$validFiltros['text_validador'][$k]
			);

			foreach ($post as $index => $attr) {
				if($fild_name == $index){
					self::__validate($attr,$validador);
				}
			}

			foreach ($files as $i => $att) {

				if($fild_name == $i){
					self::__validate($att,$validador);
				}
			}
		}

		//Check errors

		if(count(self::$errors)){
			$return = false;
		}


		return $return;

	}

  /**
   * Validate the form posts
   */
	private function __validate($value, $validate){

		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('droideforms');
		$dispatcher->trigger('onDroideformsAddvalidate', array(&$value, &$validate, &self::$log));
		if($validate['tipo'] == 'f_required' ){
			self::_required($value, $validate['msn']);
		}

		if($validate['tipo'] == 'f_email' ){
			self::__checkEmail($value, $validate['msn']);
		}

		if($validate['tipo'] == 'f_integer'){
			self::_interger($value, $validate['msn']);
		}

		 if($validate['tipo'] == 'f_file'){
		 	self::__file($value, $validate);
		 }

		if($validate['tipo'] == 'f_size'){
			self::__size($value, $validate);
		 }

	}

	private function _interger($valor, $msn){
		$filter_options = array(
		    'options' => array( 'min_range' => 0)
		);
		$validatedValue = filter_var($valor, FILTER_VALIDATE_INT, $filter_options);

		if($validatedValue === FALSE){
			self::$errors[] = $msn;
		}
	}

	/**
	 * Verico se o campo é requirido
	 * @param  [type] $valor [description]
	 * @param  [type] $msn   [description]
	 * @return [type]        [description]
	 */
	private function _required($valor, $msn){
		if(empty($valor)){
			self::$errors[] = $msn;
		}
	}

	/**
	 * Verifico se o email é valido
	 * @param  [type] $valor [description]
	 * @param  [type] $msn   [description]
	 * @return [type]        [description]
	 */
	private function __checkEmail($valor, $msn){
		if(empty($valor) ||  !filter_var($valor, FILTER_VALIDATE_EMAIL)){
			self::$errors[] = $msn;
		}
	}

	/**
	 * Verifco se a extensao do file é valido
	 * @param  [type] $valor [description]
	 * @param  [type] $attr  [description]
	 * @return [type]        [description]
	 */
	private function __file($valor, $attr){
		$ext = JFile::getExt($valor['name']);
		$condition = explode(';',$attr['condition']);

		if(!in_array($ext, $condition)){
			self::$errors[] = $attr['msn'];
		}
	}

	/**
	 * Verifico se o tamanho do file é valido
	 * @param  [type] $valor [description]
	 * @param  [type] $attr  [description]
	 * @return [type]        [description]
	 */
	private function __size($valor, $attr){

		if($valor['size'] >  $attr['condition']){
			self::$errors[] = $attr['msn'];
		}
	}



private function _uploadFile($files){
	$return = array();
	if(count($files)){

		foreach ($files as $name => $file) {
			$filename = JFile::makeSafe($file['name']);
			$filename = preg_replace("/[^A-Za-z0-9-.-_]/i", "-", $filename);
			$filename = rand(1,9999999).'-'.$filename;
			$dest = JPATH_SITE.'/images/form_files/'.$filename;
			if(!JFile::upload($file['tmp_name'], $dest)){
				self::$errors[] = "Erro ao enviar o arquivo $filename";

			}else{
				$url_destino = JURI::base().'images'.DS.'form_files'.DS.$filename;
				$return[] = array(
						'root'=>$url_destino,
						'path'=>$dest,
				);
			}

		}
	}

	return $return;
}


	/**
	 * Envio de e-mail
	 * @param  object $module Objeto com o conteúdo do módulo
	 * @param  array $post   post do formulário
	 * @return string         mensagem de sucesso ou de erro
	 */
	private function _sendEmail($module, $post, $files){
			$config = JFactory::getConfig();
			$return = '';
			$frommail = "";
			$fromname = "";
			$layout = $module->get('layout_envio');

			foreach ($post as $k => $field) {

				if($k == $module->get('nome_de_cliente')){
					$fromname = $field;
				}

				if($k  == $module->get('email_de_cliente')){
					$frommail = $field;
				}

				if(strrpos($layout, '{'.$k.'}')){
					$limpar= trim(strip_tags($k));
					$regex	= '/{'.$k.'}/i';
					$layout = preg_replace($regex, $field, $layout);
				}
			}

			$attachments_file = self::_uploadFile($files);


			$sender = array(
			   $frommail,
			   $fromname
			);

			$emailTO = $config->get( 'mailfrom' );
			$mailmodulepara = $module->get('para');
			if(!empty($mailmodulepara)){
				$emailTO = $module->get('para');
			}

			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('droideforms');
			$dispatcher->trigger('onDroideformsBeforePublisheLayout', array(&$module, &$layout, &$post, &self::$log));
			$mail = JFactory::getMailer();
			$mail->isHTML(true);
			$mail->Encoding = 'base64';
			$mail->addRecipient($emailTO);
			$mail->setSender($sender);
			$mail->setSubject($module->get('assunto'));
			$mail->setBody($layout);
			if($attachments_file ){
					foreach ($attachments_file as $k => $file) {
							$mail->addAttachment($file['path']);
					}
			}

			if($mail->Send()){
				$sucesso = array(
				'error'=>0,
				'msn'=>$module->get('resp_sucesso',JText::_('MOD_DROIDEFORMS_RESP_SUCESSO_DEFAULT')),
				'log'=>self::$log
				);

				$return = json_encode($sucesso);

				$dispatcher->trigger('onDroideformsPosSend', array(&$module,  &$post, &$sucesso,  &self::$log));

			}else{
				self::$errors[] = JText::_('MOD_DROIDEFORMS_RESP_ERROR_DEFAULT');
				$error = array(
					'error'=>1,
					'msn'=>self::$errors,
					'log'=>self::$log
				);

				$dispatcher->trigger('onDroideformsPosSendError', array(&$module,  &$post, &$error,  &self::$log));

				$return = json_encode($error);
			}

			return $return;
	}

	/**
 * This encrypt id the module in frondend
 * @param int $data id the module
 * @return string criptada
 */
public function Encrypt($data)
{
	$password = self::$pass_cript_decript;
    $salt = substr(md5(mt_rand(), true), 8);

    $key = md5($password . $salt, true);
    $iv  = md5($key . $password . $salt, true);

    $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

    return base64_encode('Salted__' . $salt . $ct);
}

/**
 * This decripty id the module in backend
 * @param int $data id the module
 * @return string decriptada
 */
private function Decrypt($data)
{
		$password = self::$pass_cript_decript;

    $data = base64_decode($data);
    $salt = substr($data, 8, 8);
    $ct   = substr($data, 16);

    $key = md5($password . $salt, true);
    $iv  = md5($key . $password . $salt, true);

    $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);

    return $pt;
}

}
