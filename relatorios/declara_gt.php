
<?php	

//**************************************************
	// fun��o para formatar CPF e CNPJ | Tiago Moselli
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
		'03' => 'Mar�o',
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

	include_once("../config/conexao.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura.php");

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
	$dompdf->load_html(utf8_encode('

<!--
*****************************************************************************************************
                                    DECLARA��O DO CLUBE SOLICITANDO A GT
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">DECLARA&Ccedil;&Atilde;O PARA SOLICITA&Ccedil;&Atilde;O DE GUIA DE TR&Aacute;FEGO</h3><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">
O <b>CTC - CLUBE DE TIRO CARCARA</b>, inscrito no CNPJ/MF sob o n� 23.321.962/0001-48 e Certificado de Registro n� 120994, com sede na R. Em�lio Castelar N� 2010, Barrocas, CEP: 59.621-150 - Mossor�/RN, DECLARA, PARA FIM DE COMPROVA��O para solicita��o de Guia de Tr�fego junto ao Ex�rcito Brasileiro, que <B>'.utf8_decode($cliente->nome).'</b>, CR n�: <B>'.utf8_decode($cliente->cr).'</b>, est� regularmente inscrito nesta Entidade sob o n� de registro <b>'.$cliente->matricula.'</b>, datado de <b>'.date('d/m/Y', strtotime($cliente->data_filiacao)).'</b> e que participou de treinamentos/competi��es que justificam a solicita��o de Guia de Tr�fego pleiteada.</p>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">O <b>CTC - CLUBE DE TIRO CARCARA</b> disp�e dos registros que comprova a participa��o do referido atirador desportivo em treinamentos/competi��es.</p>
 
<p style="line-height: 150%; margin-left: 15; margin-right: 15">Esta declara��o tem validade de 90 dias.</p><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 110%; margin-left: 0; margin-right: 0"><font size="2">
'.PDF_ASSINA.'

<div style="page-break-after: always"></div>


		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"solicitacao_gt", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>

