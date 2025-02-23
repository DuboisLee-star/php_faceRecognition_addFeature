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

	include_once("../config/conexao.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura.php");

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

	// Valida se existe um id e se ele é numérico
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
                                  LISTAGEM SOBRE VALORES PAGOS PELO MEMBRO
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELATÓRIO FINANCEIRO DO MEMBRO</h3><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Segue abaixo relação de mensalidades pagas do membro <b>'.$cliente->nome.'</b>, matrícula <b>'.$cliente->matricula.'</b>, data de filiação <b>'.date('d/m/Y', strtotime($cliente->data_filiacao)).'</b>, referente ao ano _____________________.</p>

<br>

<div align="center">
  <center>
<table border="1" width="100%" bordercolor="#C0C0C0">
  <tr>
    <td width="26%" align="center" bgcolor="#C0C0C0"><b>JAN</b></td>
    <td width="26%" align="center" bgcolor="#C0C0C0"><b>FEV</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>MAR</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>ABR</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>MAI</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>JUN</b></td>
  </tr>
  <tr>
    <td width="26%" align="center">'.$cliente->mens_jan2021.'</td>
    <td width="26%" align="center">'.$cliente->mens_fev2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_mar2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_abr2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_mai2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_jun2021.'</td>
  </tr>
  <tr>
    <td width="26%" align="center" bgcolor="#C0C0C0"><b>JUL</b></td>
    <td width="26%" align="center" bgcolor="#C0C0C0"><b>AGO</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>SET</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>OUT</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>NOV</b></td>
    <td width="27%" align="center" bgcolor="#C0C0C0"><b>DEZ</b></td>
  </tr>
  <tr>
    <td width="26%" align="center">'.$cliente->mens_jul2021.'</td>
    <td width="26%" align="center">'.$cliente->mens_ago2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_set2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_out2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_nov2021.'</td>
    <td width="27%" align="center">'.$cliente->mens_dez2021.'</td>
  </tr>
</table>
<br><br>

<div align=right>Valor pago pela anuidade foi de <b>'.$cliente->anuidade2021.'.</b>.<br><br></div>

<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br>

<p align=center style="line-height: 110%; margin-left: 0; margin-right: 0"><font size="2"><br>
'.PDF_ASSINA.'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"mensalidades2021", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>