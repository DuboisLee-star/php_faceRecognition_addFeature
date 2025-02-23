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
                                DECLARAÇÃO DE COMPROVAÇÃO RESIDENCIA 
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<h3 style="text-align: center;"><br><br>DECLARAÇÃO DE COMPROVAÇÃO RESIDENCIA</h3><br><br>
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
Eu, <B>'.strtoupper($cliente->nome).'</b>, portador do RG nº <B>'.$cliente->identidade.'</b>/<B>'.strtoupper($cliente->orgaouf).'</b> e do CPF nº <B>'.formataCPFCNPJ($cliente->cpf).'</b>, <B>'.strtoupper($cliente->estadocivil).'</b>, residente à <B>'.strtoupper($cliente->rua).'</b> nº <B>'.$cliente->numero.'</b>, bairro <B>'.strtoupper($cliente->bairro).'</b>, CEP: <B>'.$cliente->cep.'</b>, <B>'.strtoupper($cliente->cidade).'</b>/<B>'.strtoupper($cliente->siglauf).'</b>, visando a CONCESSÃO/REVALIDAÇÃO de certificado de registro ou AQUISIÇÃO DE PCE através do Exercito Brasileiro, <b>DECLARO</b> sob as penas de lei, (art. 2º da lei 7.115/83), que tive reresidencia e domicilio, nos ultimos cinco anos, no(s) endereço(s) abaixo mencionado(s):<br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
1 - '.strtoupper($cliente->rua).' nº '.$cliente->numero.', bairro '.strtoupper($cliente->bairro).', CEP: '.$cliente->cep.', '.strtoupper($cliente->cidade).'/'.strtoupper($cliente->siglauf).' - CONFORME COMPROVANTES ANEXOS.<br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
Por ser verdade firmo a presente declaração para que se produza os efeitos legais, ciente de que a falsidade de seu conteudo pode implicar na imputação de sanções civis, administrativas bem como na  sanção penal prevista no art. 299 do Código Penal. Conforme trascrição abaixo:<br>

<i>“ art. 299<br>
_<br>
Omitir, em documento público ou particular, declaração que nele deveria constar, ou nele inserir ou fazer inserir declaração falsa ou diversa  do que devia ser escrita, com fim de prejudicar direito, criar obrigação ou alterar  a verdade sobre o fato juridicamente relevante.<br><br>

Pena : reclusão de 1(um) ano a 5(cinco) anos e multa, se o documento é público, e reclusão de 1(um )ano a 3( três) anos, se o documento é particular.”<br><br></i>

<table width="100%" style="border-collapse: collapse; margin-top: 5px;">
        <tr>
        <p align="center">'. $cliente->cidade .'/'. $cliente->siglauf.', '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br><br>
    
<!-- Requerente -->
____________________________________________<br>
REQUERENTE<br><b>
'.$cliente->nome.'</b><br>
CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>
        </tr>
    </table>
    
	');
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"dic", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>