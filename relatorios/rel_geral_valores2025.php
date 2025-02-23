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

	//**************************************************

	include_once ("../config/conecta_rel.php");
	include_once ("../config/cabecalho.php");

	$html = '<table border=1  width="100%"';	
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;MAT</th>';
	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;ATIRADOR(A)</th>';
	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;VALORES PAGOS</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	$result_transacoes = "SELECT * FROM tab_financeiro INNER JOIN tab_membros ON tab_financeiro.matricula = tab_membros.matricula";
 
	$valor_total = 0;
	$resultado_transacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_transacoes)){
		
$mensal = $row_transacoes['mens_jan2025'] + $row_transacoes['mens_fev2025'] + $row_transacoes['mens_mar2025'] + $row_transacoes['mens_abr2025'] + $row_transacoes['mens_mai2025'] + $row_transacoes['mens_jun2025'] + $row_transacoes['mens_jul2025'] + $row_transacoes['mens_ago2025'] + $row_transacoes['mens_set2025'] + $row_transacoes['mens_out2025'] + $row_transacoes['mens_nov2025'] + $row_transacoes['mens_dez2025'];

$html .= '<tr><td>&nbsp;<font size="2">'.$row_transacoes['matricula'] ."</td>";
$html .= '<td>&nbsp;<font size="2">'.$row_transacoes['nome'] ."</td>";
$html .= '<td align="left">&nbsp;<font size="2">'.number_format($row_transacoes['anuidade2025'],2,',','.') . 
" - Anuidade<br>&nbsp;" .number_format($mensal,2,',','.')." - Mensalidade</td></tr>";	

$valor_total += ($row_transacoes['anuidade2025']);

$mensal_total += ($row_transacoes['mens_jan2025']) + ($row_transacoes['mens_fev2025']) + ($row_transacoes['mens_mar2025']) + ($row_transacoes['mens_abr2025']) + ($row_transacoes['mens_mai2025']) + ($row_transacoes['mens_jun2025']) + ($row_transacoes['mens_jul2025']) + ($row_transacoes['mens_ago2025']) + ($row_transacoes['mens_set2025']) + ($row_transacoes['mens_out2025']) + ($row_transacoes['mens_nov2025']) + ($row_transacoes['mens_dez2025']);;

$geral_total += ($row_transacoes['anuidade2025']) + ($row_transacoes['mens_jan2025']) + ($row_transacoes['mens_fev2025']) + ($row_transacoes['mens_mar2025']) + ($row_transacoes['mens_abr2025']) + ($row_transacoes['mens_mai2025']) + ($row_transacoes['mens_jun2025']) + ($row_transacoes['mens_jul2025']) + ($row_transacoes['mens_ago2025']) + ($row_transacoes['mens_set2025']) + ($row_transacoes['mens_out2025']) + ($row_transacoes['mens_nov2025']) + ($row_transacoes['mens_dez2025']);;

	}

	$html .= '</tbody>';
	$html .= '</table';

	
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
                                     RELATÓRIO GERAL VALORES
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELATÓRIO GERAL VALORES - 2025</h3>

'. $html .'
         
<br><br>
<div align=right>Valor em Anuidades: <b>'.number_format($valor_total,2,',','.').'<br><br></b></div>

<div align=right>Valor em Mensalidades: <b>'.number_format($mensal_total,2,',','.').'<br><br></b></div>

<div align=right>Total Geral: <b>'.number_format($geral_total,2,',','.').'<br><br></b></div>

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relatorio_valores_2025", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>