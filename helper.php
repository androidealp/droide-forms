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

//woking layout dinamic
require_once __DIR__ . '/libs/DroideLayout.php';

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

		//  para testes sem envio
		// $return = json_encode(array(
		// 		'error'=>0,
		// 		'msn'=>'ok teste',
		// 		'log'=>self::$log
		// 	));

		}else{
			$return = json_encode(array(
			 		'error'=>1,
			 		'msn'=>self::$errors,
					'log'=>self::$log
				));
		}

		return  $return;



	}
	/**
	 * Return params module
	 * @param int $id id do modulo
	 */
	private function getModule($id){
		//decript id of the module
		$id = (int)self::Decrypt($id);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__modules');
		$query->where('id = '.$id);
		$db->setQuery($query);
		$module = $db->loadObject();
		$params = new JRegistry();
		if($module){
				$params->loadString($module->params);
				$params->id = $id;
				$params->title = $module->title;
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

		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('droideforms');


		$dispatcher->trigger('onDroideformsAddvalidate', array(&$post, &$validFiltros, &self::$errors, &self::$log));
		// solução nycollas
		if(self::__checkValidationPost($post,$validFiltros) == true){
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

		} // fim do if __checkValidationPost


		//Check errors

		if(count(self::$errors)){
			$return = false;
		}


		return $return;

	}

	/**
	 * Verifica se veido dados pelo post para tratamento,
	 * @param  Array $post Lista de campos enviados via form
	 * @param  array $filtro lista de dados vindos do filtro
	 * @author Nycollas Email@anonimos.com
	 * @return bool verdadeiro ou falso
	 */
	public function __checkValidationPost($post,$filtro)
{

	$check = false;
	foreach ($filtro['field_name'] as $k => $fild_name) {

		foreach(array_keys($post) as $nome){

			$nome = str_replace(" ","", $nome);
			$fild_name = str_replace(" ","", $fild_name);

			if(strcasecmp($fild_name,$nome) == 0){
				$check = true;
				break;
			}else{

			}

		}

		if($check == true){
			break;

		}

	}

	if(!$check)
	{
		self::$errors[] = 'Dados incompletos favor verificar.';
	}

	return $check;

}

  /**
   * Validate the form posts
   */
	private function __validate($value, $validate){

		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('droideforms');
		$dispatcher->trigger('onDroideformsAddrules', array(&$value, &$validate, &self::$errors, &self::$log));

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
			$clear_file = preg_replace("/[^A-Za-z0-9-.-_]/i", "-", $filename);
			$unique_file = rand(1,9999999).'-'.$clear_file;
			$dest = JPATH_SITE.'/images/form_files/'.$unique_file;
			if(!JFile::upload($file['tmp_name'], $dest)){
				self::$errors[] = "Erro ao enviar o arquivo $unique_file";

			}else{
				$url_destino = JURI::base().'images/form_files/'.$unique_file;
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
			$returnTrigger = [];
			$layout = $module->get('layout_envio');
			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('droideforms');

			foreach ($post as $k => $field) {

				if($k == $module->get('nome_de_cliente')){
					$fromname = $field;
				}

				if($k  == $module->get('email_de_cliente')){
					$frommail = $field;
				}

				// if(strrpos($layout, '{'.$k.'}')){
				// 	$limpar= trim(strip_tags($k));
				// 	$regex	= '/{'.$k.'}/i';
				// 	$layout = preg_replace($regex, $field, $layout);
				// }
			}

			$dr_layout = new DroideLayout;
			$dr_layout->post = $post;
			$dr_layout->layout = $layout;
			$dr_layout->init();
			$layout = $dr_layout->printLayoutFinal();

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

			if(strpos($emailTO, ';') !== false)
			{
				$emailTO = explode(';',$emailTO);
			}

			
			$dispatcher->trigger('onDroideformsBeforePublisheLayout', array(&$module, &$layout, &$post, &self::$log,&$returnTrigger));

			if($module->get('remetente_mensagem',0))
			{
				$dr_layout->layout = $remetente_msg;
				$dr_layout->post['mensagem_retorno'] = $module->get('resp_sucesso',JText::_('MOD_DROIDEFORMS_RESP_SUCESSO_DEFAULT'));

				$dispatcher->trigger('onDroideformsbeforeInitRemententeMensagem', array(&$module, &$dr_layout, &$returnTrigger, &$post, &self::$log));

				$dr_layout->init();

				$remetente_msg = $dr_layout->printLayoutFinal();	
			}

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
			$envio = $mail->Send();
			if($envio == 1){

				// sendMail($from, $fromname, $recipient, $subject, $body, $htmlmode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null )
				if($module->get('remetente_mensagem',0))
				{
					JFactory::getMailer()->sendMail($config->get( 'mailfrom' ), $config->get('fromname') ,$frommail, $module->get('assunto'), $remetente_msg,true);	
				}

				$sucesso = array(
				'error'=>0,
				'msn'=>$module->get('resp_sucesso',JText::_('MOD_DROIDEFORMS_RESP_SUCESSO_DEFAULT')),
				'log'=>self::$log
				);

				$dispatcher->trigger('onDroideformsPosSend', array(&$module,  &$post, &$sucesso,  &self::$log, &$returnTrigger));

				$return = json_encode($sucesso);

				

			}else{
				self::$errors[] = JText::_('MOD_DROIDEFORMS_RESP_ERROR_DEFAULT').' '.$envio->__toString();
				$error = array(
					'error'=>1,
					'msn'=>self::$errors,
					'log'=>self::$log
				);

				$dispatcher->trigger('onDroideformsPosSendError', array(&$module,  &$post, &$error,  &self::$log, &$returnTrigger));

				$return = json_encode($error);
			}

			$dispatcher->trigger('onDroideformsBeforeReturn', array(&$module,  &$return, &$returnTrigger,  &self::$log));

			return $return;
	}

	/**
 * This encrypt id the module in frondend
 * @param int $data id the module
 * @return string criptada
 */
public function Encrypt($data)
{
	// $password = self::$pass_cript_decript;
    // $salt = substr(md5(mt_rand(), true), 8);

    // $key = md5($password . $salt, true);
    // $iv  = md5($key . $password . $salt, true);

    // $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

	// return base64_encode('Salted__' . $salt . $ct);
	
	$first_key = base64_decode(self::$pass_cript_decript);
	$second_key = base64_decode(self::$pass_cript_decript);
	$method = "aes-256-cbc"; 

	$iv_length = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($iv_length);
	$first_encrypted = openssl_encrypt($data,$method,$first_key, OPENSSL_RAW_DATA ,$iv);
	$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
	$output = base64_encode($iv.$second_encrypted.$first_encrypted);   
	
	return $output;  
}

/**
 * This decripty id the module in backend
 * @param int $data id the module
 * @return string decriptada
 */
private function Decrypt($input)
{
	// 	$password = self::$pass_cript_decript;

    // $data = base64_decode($data);
    // $salt = substr($data, 8, 8);
    // $ct   = substr($data, 16);

    // $key = md5($password . $salt, true);
    // $iv  = md5($key . $password . $salt, true);

    // $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);

	// return $pt;
	
	$first_key = base64_decode(self::$pass_cript_decript);
	$second_key = base64_decode(self::$pass_cript_decript); 
	$mix = base64_decode($input);
	$method = "aes-256-cbc"; 
	$iv_length = openssl_cipher_iv_length($method);

	$iv = substr($mix,0,$iv_length);
	$second_encrypted = substr($mix,$iv_length,64);
	$first_encrypted = substr($mix,$iv_length+64);
	
	$data = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
	$second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

	if (hash_equals($second_encrypted,$second_encrypted_new))
	{
		return $data;
	}

	return false;
}

}
