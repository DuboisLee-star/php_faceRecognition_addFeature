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
                                DECLARAÇÃO DE ENDEREÇO DE GUARDA DO ACERVO
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<br><br><br><Br>

<h3 style="text-align: center;">DECLARAÇÃO DE ENDEREÇO DE GUARDA DO ACERVO</h3><br><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu, <B>'.mb_strtoupper($cliente->nome).'</b>, portador da carteira de identidade nº <B>'.$cliente->identidade.'</b> - <B>'.mb_strtoupper($cliente->orgaouf).'</b>, <B>'.mb_strtoupper($cliente->nacionalidade).'</b>, natural de <B>'.mb_strtoupper($cliente->naturalidade).'</b>, CPF nº <B>'.formataCPFCNPJ($cliente->cpf).'</b>, nascido em <B>'. ($cliente->data_nascimento ? date('d/m/Y', strtotime($cliente->data_nascimento)) : '') . '</b>, filho de  <B>'.mb_strtoupper($cliente->pai).'</b> e <B>'.mb_strtoupper($cliente->mae).'</b>, com segundo endereço à '.mb_strtoupper($cliente->rua).' '.mb_strtoupper($cliente->segundo_num).', '.mb_strtoupper($cliente->segundo_bairro).' - CEP: '.mb_strtoupper($cliente->segundo_cep).', '.mb_strtoupper($cliente->segundo_cidade).'/'.mb_strtoupper($cliente->segundo_estado).', Telefone: <B>'.utf8_decode($cliente->telefone).'</b>, e-mail: <B>'.utf8_decode($cliente->email).'</b>.<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DECLARO junto ao SFPC do Exercito Brasileiro, que  este  local  de  guarda  do  meu  acervo  de  ATIRADOR  DESPORTIVO,  também atende  as condições de segurança previstas no Anexo A da Portaria Nº 166 -
COLOG/C EX, de 22 de dezembro de 2023 e art. 13 da Lei nº 10.826, de 2003.</p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DECLARO ainda que este endereço é o mesmo da guarda do acervo.</p>
<br><br><br><br><div align="center">
<table width="100%" style="border-collapse: collapse; margin-top: 5px;">
    <tr>
    <p align="center">'. $cliente->cidade .'/'. $cliente->siglauf.', '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br><br>
    
<!-- Requerente -->
____________________________________________<br>
REQUERENTE<br><b>
'.$cliente->nome.'</b><br>
CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>
    
	');
	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"DGA2", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>