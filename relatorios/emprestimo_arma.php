<?php

	//**************************************************
	// função para formatar CPF e CNPJ | Tiago Moselli
	//**************************************************
	
	function formataCPFCNPJ($value){
		$cnpj_cpf = preg_replace("/\D/", '', $value);
		if(strlen($cnpj_cpf) === 11) {
			return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
		} 
		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
	}
	$meses = array(
		'01' => 'Janeiro',
		'02' => 'Fevereiro',
		'03' => 'Março',
		'04' => 'Abril',
		'05' => 'Maio',
		'06' => 'Junho',
		'07' => 'Julho',
		'08' => 'Agosto',
		'09' => 'Setembro',
		'10' => 'Outubro',
		'11' => 'Novembro',
		'12' => 'Dezembro'
	);
	
	//**************************************************
	include_once("../config/config.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura.php");	
	
	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
	
	// Valida se existe um id e se ele é numérico
	if (!empty($id_cliente) && is_numeric($id_cliente)):
	    
		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		if(!empty($cliente)):
		    
			// Formata a data no formato nacional
			$array_data     = explode('-', $cliente->data_nascimento);
			$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
		endif;
	endif;
       $html = '<table border=1>';	
	   $html .= '<tr><td>'.$cliente->matricula.'</td></tr>';
	$html .= '</tbody>';
	$html .= '</table>';
	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;
	
	// include autoloader
	require_once("dompdf/autoload.inc.php");
	
	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));
	
	// Carrega seu HTML
	$dompdf->load_html('
	
<!--
*****************************************************************************************************
                                DECLARAÇÃO DE EMPRÉSTIMO DE ARMA
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<h4 style="text-align: center;"><br><br>CESSÃO DE ARMAS DE FOGO PARA UTILIZAÇÃO NA PRÁTICA DE TIRO DESPORTIVO</h4>
<center>(art. 34 do Decreto nº 11.615/2023)<br><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<b>1. Objeto da cessão</b><br>
(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) arma de entidade de tiro desportivo cedida para pessoas com idade entre dezoito e vinte e cinco anos (inciso I, §2º, art. 34, Decreto nº 11.615/2023);<br>
(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) arma da entidade de tiro desportivo cedida para pessoas com idade superior a vinte e cinco anos (inciso II, §2º, art. 34, Decreto nº 11.615/2023);<br>
(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) arma da entidade de tiro desportivo ou do responsável legal cedida para maiores de quatorze anos e menores de dezoito (inciso III, §1º, art. 34, Decreto nº 11.615/2023);<br><br>
<b>CLUBE DE TIRO ADSUMUS</b>, CNPJ 33.143.717/0001-41, CR nº 291.228, declara
que autorizou a utilização da(s) seguintes(s) arma(s) de fogo para uso exclusivo no tiro desportivo, conforme previsto no art. 34 do Decreto nº 11.615/2023, nas condições abaixo especificadas:<br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<b>2. Arma cedida</b>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
  <tr>
    <td align="center" width="16.67%"><font size="2"><b>ARMA</b></td>
    <td align="center" width="16.67%"><font size="2"><b>MODELO</b></td>
    <td align="center" width="16.67%"><font size="2"><b>CALIBRE</b></td>
    <td align="center" width="16.67%"><font size="2"><b>Nº SÉRIE</b></td>
    <td align="center" width="16.67%"><font size="2"><b>MARCA</b></td>                    
    <td align="center" width="16.67%"><font size="2"><b>Nº SIGMA</b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<b>3. Pessoa autorizada a utilizá-la:</b><br>
<i>'.$cliente->nome.', CPF: '.formataCPFCNPJ($cliente->cpf).', CR nº '.utf8_decode($cliente->identidade).', Validade: '. ($cliente->validade_cr ? date('d/m/Y', strtotime($cliente->validade_cr)) : ''). '</i><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<b>4. Local de utilização:</b><br>
<i>CLUBE DE TIRO HOSTMARQ</i>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<b>5. Validade:</b><br>
365  dias, a contar da data da assinatura<br><br>

<div align="center" style="line-height: 110%; margin-left: 0; margin-right: 0">
<br>
  Jo&atilde;o Pessoa (PB), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br>
    
<!-- Assinante -->
<div style="width:100%; float: left; text-align:center;">
___________________________________<BR>
<div style="width:100%; float: left; text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF: 
</div>
    
	');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"emprestimo_arma", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>