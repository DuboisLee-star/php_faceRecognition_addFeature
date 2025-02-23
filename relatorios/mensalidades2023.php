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
		$sql = 'SELECT * FROM tab_financeiro INNER JOIN tab_membros ON tab_financeiro.matricula = tab_membros.matricula';
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

$mensal = $cliente->mens_jan2023 + $cliente->mens_fev2023 + $cliente->mens_mar2023 + $cliente->mens_abr2023 + $cliente->mens_mai2023 + $cliente->mens_jun2023 + $cliente->mens_jul2023 + $cliente->mens_ago2023 + $cliente->mens_set2023 + $cliente->mens_out2023 + $cliente->mens_nov2023 + $cliente->mens_dez2023;

$mensal_total = $mensal; // Não é necessário somar novamente

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

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Segue abaixo relação de mensalidades pagas do membro <b>'.$cliente->nome.'</b>, matrícula <b>'.$cliente->matricula.'</b>, data de filiação <b>'.date('d/m/Y', strtotime($cliente->data_filiacao)).'</b>, referente ao ano <b>2023</b>.</p>

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
    <td width="26%" align="center">'.$cliente->mens_jan2023.'</td>
    <td width="26%" align="center">'.$cliente->mens_fev2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_mar2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_abr2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_mai2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_jun2023.'</td>
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
    <td width="26%" align="center">'.$cliente->mens_jul2023.'</td>
    <td width="26%" align="center">'.$cliente->mens_ago2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_set2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_out2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_nov2023.'</td>
    <td width="27%" align="center">'.$cliente->mens_dez2023.'</td>
  </tr>
</table>
<br><br>

<div align=right>Valor pago no plano mensalidade foi de <b>'.number_format($mensal_total,2,',','.').'<br><br></b></div>

<div align=right>Valor pago no plano anuidade foi de <b>'.$cliente->anuidade2023.'.</b><br><br><br><br></div>

<p align=center style="line-height: 110%; margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'



	'));



	//Renderizar o html
	$dompdf->render();


	//Exibibir a pÃ¡gina

	$dompdf->stream(

		"mensalidades2023", 

		array(

			"Attachment" => false //Para realizar o download somente alterar para true

		)

	);

?>