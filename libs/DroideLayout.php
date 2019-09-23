<?php

/**
 * DroideLayout customiza o layout antes do envio
 * para utilização no corpo do layout são restritos os parametros html: data-elemento, data-turnon, data-boxforeach
 *
 * @author André Luiz Pereira <andre@next4.com.br>
 */
class DroideLayout {

public $html = '';
private $divs = [];
private $clones = [];
public $post = [
  'tp_pessoa' =>'Pessoa Física', // Pessoa Jurídica, Pessoa Física
   'cpf'=>'333.333.555.11',
   'cnpj'=>'000.000.0001-10',
   'ie'=>'Next4 LTDA',
   'isento'=>'Sim',
   'dd_certificado'=>[
     'area_informada'=>[
       '455x100','15x15','50x32'
     ],
     'material-a-proteger'=>[
       'material 1','material 2','material 3'
     ],
     'outros_proteger'=>[
       '','',''
     ],
     'local-do-material'=>[
       'local 1','local 2','local 3'
     ],
     'outros_local'=>[
       '','',''
     ],
     'ambiente'=>[
       'ambi 1','ambi 2','ambi 3'
     ],
   ],

   'telefone-comercial'=>'45411115',
   'telefone-celular'=>'6465454654564',
   'email-ntf'=>'teste@teste.com.br',
];
public $layout = "";

/**
 * Inicializo DOMDocument e trata o layout para construção do layout de envio dinamicamente
 * @author André Luiz Pereira <andre@next4.com.br>
 * @return void
 */
public function init()
{

  $html = $this->getLayout();

$doc = new \DOMDocument();
$doc->loadHTML($html);

 $this->html = $doc;

 $this->divs = $this->html->getElementsByTagName('div');

 $this->execTratamentos();

}

/**
 * Verifica se existe layout caso contrario pega o exemplo
 * @author André Luiz Pereira <andre@next4.com.br>
 * @return string layout para trabamento
 */
public function getLayout()
{
  if(empty($this->layout))
  {
    $this->layout = <<<HTML
    <h3>Dados do Faturamento</h3>
  <p data-elemento="tp_pessoa">Tipo de pessoa: {tp_pessoa}</p>

  <div data-turnon="tp_pessoa==Pessoa Física">
    <p data-elemento="cpf">CPF: {cpf}</p>
  </div>

  <div data-turnon="tp_pessoa==Pessoa Jurídica">
        <p data-elemento="cnpj"><strong>CNPJ:</strong> {cnpj}</p>
        <p data-elemento="ie">Inscrição Estadual: {ie}</p>
        <p data-elemento="isento">Isento: {isento}</p>
  </div>

  <div data-boxforeach="dd_certificado"  style='background:#eee; padding:5px; margin-top:5px; margin:bottom:5px;'>
        <p data-foreach="area_informeada">Area: {area_informeada}</p>
        <p data-foreach="material-a-proteger">Material Proteger: {material-a-proteger}</p>
        <p data-foreach="outros_proteger">Se selecionou Outros no proteger: {outros_proteger}</p>
        <p data-foreach="local-do-material">Local do material: {local-do-material}</p>
        <p data-foreach="outros_proteger">Se selecionou outros em local do material: {outros_proteger}</p>
        <p data-foreach="ambiente">Ambiente: {ambiente}</p>
  </div>

  <p data-elemento="telefone-comercial">Tel. Comercial: {telefone-comercial}</p>
  <p data-elemento="telefone-celular">Tel. Celular: {telefone-celular}</p>
  <p data-elemento="email-ntf">E-mail para envio NF-e: {email-ntf}</p>
HTML;
  }

  return $this->layout;
}

/**
 * executa o tratamento do html informado no layout
 * @author André Luiz Pereira <andre@next4.com.br>
 * @return void
 */
public function execTratamentos()
{
  	$xp = new \DOMXPath($this->html);

  foreach ($this->post as $name => $val ) {

    if(!is_array($val))
    {
      $node = $xp->query('//*[@data-elemento="'.$name.'"]/text()')->item(0);

      if(isset($node->textContent))
      {
        //print_r($node->tagName);

        $getFragment = $node->textContent;

        $regex	= '/{'.$name.'}/i';
        $getFragment = preg_replace($regex, $val, $getFragment);

        $atualizar  = $this->html->createDocumentFragment();
        $atualizar->appendXML($getFragment);

        $node->parentNode->replaceChild($atualizar, $node);

      } //endif isset


      foreach ($this->divs as $k => $box) {

         $this->setTurOnOff($box, $name, $val);

      } // endforeach divs

    }else{ // endif is not array

      $this->clones[$name] = $this->organizeClones($val);

         //$this->foreachBox($name, $val);

    } // end is array

  } // end foreach

  $this->foreachBox($xp);



  //

  //$html->saveHtml();
}

/**
 * Retorna o layout com o tratamento
 * @author André Luiz Pereira <andre@next4.com.br>
 * @return string - layout
 */
public function printLayoutFinal()
{
  return utf8_decode($this->html->saveHtml());
}

/**
 * Organiza os dados para post
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param array $post - post vindo do formulário separado para clone
 * @return array - dados tratados para impressão no formulário
 */
public function organizeClones($post)
{
  $box = [];
  $fields = [];
  $box =[];
  $contador = 0;

  foreach ($post as $name => $clones) {

    foreach ($clones as $key => $value) {
      $box[$key][$name] = $value;

    }

  }

    return $box;

}
/**
 * Recurso para inserção de clones no layout utilização do recurso  DomXpath($this->html);
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param object $xpath - object DomXpath que feio da ultima segumentação
 * @return void - trata o html para impressão
 */
public function foreachBox($xpath)
{
  $inserthtml = '';

  foreach ($this->clones as $k => $clone) {
     $boxclone = $xpath->query('//*[@data-boxforeach="'.$k.'"]')->item(0);
     $style = $boxclone->getAttribute('style');
    foreach ($clone as $key => $fields) {

        $inserthtml .= "<div style='{$style}'>";
        foreach ($fields as $name => $value) {
          $ElField = $xpath->query('//*[@data-boxforeach="'.$k.'"]/*[@data-foreach="'.$name.'"]')->item(0);
          if(isset($ElField->textContent))
          {
            $conteudo = utf8_decode($ElField->textContent);
            $regex	= '/{'.$name.'}/i';
            $getFragment = preg_replace($regex, $value, $conteudo);
            $inserthtml .= '<'.$ElField->tagName.'>'.$getFragment.'</'.$ElField->tagName.'>';

            if($value == '')
            {
              $ElField->parentNode->removeChild($ElField);
            }

          }



        }

        $inserthtml .= "</div>";
    }

    $clonar  = $this->html->createDocumentFragment();
    $clonar->appendXML($inserthtml);
    if($boxclone && $clonar)
    {
        $boxclone->parentNode->replaceChild($clonar,$boxclone);
    }

  }




}

/**
 * Aplica turon ou of no layout
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param object $box - objeto dom para tratamento
 * @param string $key - campo name do post
 * @param string $val - campo value do post
 * @return void - trata o html para o layout
 */
public function setTurOnOff($box, $key, $val)
{
    $getattr = $box->getAttribute('data-turnon');

    $turnon = utf8_decode($getattr);

    if($turnon)
    {
      $turn_egual = explode('==', $turnon);
      if(isset($turn_egual[1])){
        if($key == $turn_egual[0] && trim($val) != trim($turn_egual[1]))
        {
          $box->parentNode->removeChild($box);
        }
      }
    }
}

}

// $teste = new DroideLayout;
// $teste->init();
//
// echo $teste->printLayoutFinal();
