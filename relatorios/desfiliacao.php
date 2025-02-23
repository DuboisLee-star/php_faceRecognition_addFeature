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
	include_once ("../config/assinatura2.php");
	include_once ("../config/texto.php");
	include_once ("../config/desfilia.php");	

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

	// Valida se existe um id e se ele � num�rico
	if (!empty($id_cliente) && is_numeric($id_cliente)):

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql = 'SELECT *  FROM tab_membros WHERE id = :id';
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
                                       DECLARAÇÃO DE DESFILIAÇÃO
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<br><br><br>
<h4 style="text-align: center;">DECLARAÇÃO DE DESFILIAÇÃO A ENTIDADE DE TIRO DESPORTIVO/CAÇA</h4><br>

<p align=justify style="line-height: 170%; margin-left: 10; margin-right: 10">
'.PDF_TEXTO.' DECLARA, para fim de comprovação junto ao Exército Brasileiro, que o Sr(a) <B>'.mb_strtoupper($cliente->nome).'</b>, CPF nº: <B>'.formataCPFCNPJ($cliente->cpf).'</b>, requereu a <b>DESFILIAÇÃO</b> na data de '.PDF_DATADESFILIA.'.</p><br>

<div align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
Esta declaração tem validade de 90 dias.</p><br><br><br>

<p align=center style="line-height: 110%; margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'

</p>
			
	');
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());

	//Exibibir a página
	$dompdf->stream(
		"desfiliacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>