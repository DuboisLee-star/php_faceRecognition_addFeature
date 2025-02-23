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
	
	$result_transacoes = "select * from tab_membros order by matricula";

	$valor_total = 0;
	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){
		
$mensal = $row_transacoes['mens_jan2022'] + $row_transacoes['mens_fev2022'] + $row_transacoes['mens_mar2022'] + $row_transacoes['mens_abr2022'] + $row_transacoes['mens_mai2022'] + $row_transacoes['mens_jun2022'] + $row_transacoes['mens_jul2022'] + $row_transacoes['mens_ago2022'] + $row_transacoes['mens_set2022'] + $row_transacoes['mens_out2022'] + $row_transacoes['mens_nov2022'] + $row_transacoes['mens_dez2022'];

$html .= '<tr><td>&nbsp;'.$row_transacoes['matricula'] ."</td>";
$html .= '<td>&nbsp;'.$row_transacoes['nome'] ."</td>";
$html .= '<td align="left">'.number_format($row_transacoes['anuidade2022'],2,',','.') . 
" - Anuidade<br>" .number_format($mensal,2,',','.')." - Mensalidades</td></tr>";	

$valor_total += ($row_transacoes['anuidade2022']);

$mensal_total += ($row_transacoes['mens_jan2022']) + ($row_transacoes['mens_fev2022']) + ($row_transacoes['mens_mar2022']) + ($row_transacoes['mens_abr2022']) + ($row_transacoes['mens_mai2022']) + ($row_transacoes['mens_jun2022']) + ($row_transacoes['mens_jul2022']) + ($row_transacoes['mens_ago2022']) + ($row_transacoes['mens_set2022']) + ($row_transacoes['mens_out2022']) + ($row_transacoes['mens_nov2022']) + ($row_transacoes['mens_dez2022']);;

$geral_total += ($row_transacoes['anuidade2022']) + ($row_transacoes['mens_jan2022']) + ($row_transacoes['mens_fev2022']) + ($row_transacoes['mens_mar2022']) + ($row_transacoes['mens_abr2022']) + ($row_transacoes['mens_mai2022']) + ($row_transacoes['mens_jun2022']) + ($row_transacoes['mens_jul2022']) + ($row_transacoes['mens_ago2022']) + ($row_transacoes['mens_set2022']) + ($row_transacoes['mens_out2022']) + ($row_transacoes['mens_nov2022']) + ($row_transacoes['mens_dez2022']);;

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

<h3 style="text-align: center;">RELATÓRIO GERAL VALORES - 2022</h3>

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
		"relatorio_valores", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>