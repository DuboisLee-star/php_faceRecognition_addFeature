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
		
		// pega dados da habitualidade
		//$sql2 = " SELECT * FROM tab_habitualidade WHERE matricula = :matricula ORDER BY id ASC";
		$sql2 = " SELECT * FROM tab_habitualidade WHERE matricula = :matricula AND IFNULL(aprovado,0) = 1 ORDER BY id ASC";
		$stm = $conexao->prepare($sql2);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->execute();
		$habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

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
	
	// html habitualidade
	$html_habilitualidade = "";
	if($habitualidade){
		foreach($habitualidade as $idh => $value){
			$html_habilitualidade .= '
				<tr>
					<td width="12%" align="center">&nbsp;<font size="2">'.date('d/m/Y', strtotime($habitualidade[$idh]->data)).'</td>
					<td width="25%">&nbsp;<font size="2">'.utf8_decode($habitualidade[$idh]->local).'</td>
			     	<td width="13%" align="center">&nbsp;<font size="2">'.utf8_decode($habitualidade[$idh]->calibre).'</td>
					<td width="50%">&nbsp;<font size="2">'.utf8_decode($habitualidade[$idh]->evento).'</td>
				</tr>
			';
		}
	}

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true, 'enable_html5_parser'=>true));
	
	$dompdf->set_option('isHtml5ParserEnabled', true);
	
	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'
</div>
<h4 style="text-align: center;">DECLARA&Ccedil;&Atilde;O DE HABITUALIDADE</h4>

<div align=justify style="line-height: 135%; margin-left: 15; margin-right: 15"><FONT SIZE="2">'.PDF_TEXTO.' DECLARA, para fins de comprovação de habitualidade de prática de tiro desportivo junto ao Exército Brasileiro, que <B>'.utf8_decode($cliente->nome).'</b>, CR <B>'.utf8_decode($cliente->cr).'</b>, está regularmente inscrito nesta entidade sob o nº  <b>'.$cliente->matricula.'</b>, datado de <b>'.date('d/m/Y', strtotime($cliente->data_filiacao)).'</b>, O mesmo participou dos seguintes treinamentos ou competições nesta entidade de tiro:<br><br></div>

<div align="center">
	<table border="1" width="100%" cellspacing="1">
		<tr>
	     	<td align="center" width="12%" bgcolor="#C0C0C0"><font size="2"><b>DATA</b></td>
			<td align="center" width="25%" bgcolor="#C0C0C0"><font size="2"><b>LOCAL</b></td>
			<td align="center" width="13%" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
			<td align="center" width="50%" bgcolor="#C0C0C0"><font size="2"><b>EVENTO (TREINO/COMPETIÇÃO)</b></td>
		</tr>
		'.$html_habilitualidade.'
	</table>
</div>
<br>
<div align=justify style="line-height: 135%; margin-left: 15; margin-right: 15"><FONT SIZE="2">Os registros que comprovam as informações acima do referido atirador desportivo estão disponíveis, a qualquer momento, para a fiscalização de produtos controlados.</p></div>

<div align=justify style="line-height: 135%; margin-left: 15; margin-right: 15"><FONT SIZE="2">Esta declaração tem validade de 30 dias.</p><br><br></div>

<div align=center style="margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'
</div>

			
		'));

	//Renderizar o html
	$dompdf->render();
	$pagesCount = $dompdf->get_canvas()->get_page_number();
	
	// -------------------------------------------------------
	// grava log
	// -------------------------------------------------------
	$sql = 'INSERT INTO tab_declaracoes 
		(matricula, relatorio, data_emissao, ip_emissao) VALUES
		(:matricula, :relatorio, :data_emissao, :ip_emissao)
	';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->bindValue(':relatorio', utf8_encode('Declaração Habitualidade'));
	$stm->bindValue(':data_emissao', date('Y-m-d H:i:s'));
	$stm->bindValue(':ip_emissao', $_SERVER['REMOTE_ADDR']);
	$stm->execute();
	// -------------------------------------------------------

	$documento = base64_encode($dompdf->output());
 
	/* cria documento assinado */
	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
    $aut = new AutentiqueH();
    $aut->tipo_documento = utf8_encode('Habitualidade').' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
    $aut->posicao_assinatura = array(70, 85, $pagesCount);
	$aut->criar_documento($documento);
	$aut->output();
	
		//Exibibir a pÃ¡gina
	$dompdf->stream(
		"declaracao_habitualidade", 
		array(
			"Attachment" => true //Para realizar o download somente alterar para true
		)
	);
	
	
?>