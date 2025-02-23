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
// 	include_once ("../config/assinatura4.php");
    include_once ("../config/assinatura7.php");
	include_once ("../config/texto.php");

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
	
	#$result_membros = "SELECT * FROM tab_membros order by matricula";
	#$resultado_membros = mysqli_query($conn, $result_membros);
	#while($row_membros = mysqli_fetch_assoc($resultado_membros)){
    #    
    #        	$html .= '<tr><td>'.$row_membros['matricula'] . "</td>";
	#}
	
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
                                     DECLARAÇÃO DE HABITUALIDADE
*****************************************************************************************************
-->

<div align=center style="line-height: 150%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<div align="center"><br><b>DECLARAÇÃO DE HABITUALIDADE INICIAL<br></b><br><br>

<div align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">

'.PDF_TEXTO.' DECLARA, que <b>'.mb_strtoupper($cliente->nome).'</b>,  CPF:<b>'.$cliente->cpf.'</b>, RG: <b>'.$cliente->identidade.'</b>, <b>'.mb_strtoupper($cliente->orgaouf).'</b>, CR: <b>'.$cliente->cr.'</b>, está  regularmente  FILIADO  nesta  Entidade  sob  o  Nº <b>'.$cliente->matricula.'</b>, datado de <b>'. ($cliente->data_filiacao ? date('d/m/Y', strtotime($cliente->data_filiacao)) : '') . '</b>, e que o mesmo está iniciando a pratica de tiro na atividade Atirador Desportivo, portanto, ainda não possui as participações mínimas.<br><br>

<div align=justify style="line-height: 140%; margin-left: 5; margin-right: 5">Esta declaração tem validade de 90 dias.<br><br><br><br><br><br>

'.PDF_ASSINA.'

</p>

	');
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	require_once($_SERVER['DOCUMENT_ROOT'].'/icpbrasil/icpbrasil.class.php');
    $icp = new icpBrasil();
    $icp->assinar_documento($documento);
    $icp->output();
	
	//Exibibir a página
	$dompdf->stream(
		"dic", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>