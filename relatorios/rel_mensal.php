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

	include_once("../config/conexao.php");
	include_once ("../config/cabecalho.php");

	$html = '<div align="center"><center><table border=1  width="100%"';	
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th>MAT</th>';
	$html .= '<th>NOME</th>';
	$html .= '<th>JAN</th>';
	$html .= '<th>FEV</th>';
	$html .= '<th>MAR</th>';
	$html .= '<th>ABR</th>';
	$html .= '<th>MAI</th>';
	$html .= '<th>JUN</th>';
	$html .= '<th>JUL</th>';
	$html .= '<th>AGO</th>';
	$html .= '<th>SET</th>';
	$html .= '<th>OUT</th>';
	$html .= '<th>NOV</th>';
	$html .= '<th>DEZ</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	$result_transacoes = "SELECT * FROM tab_membros";
	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){

		$html .= '<tr><td align="center">'.$row_transacoes['matricula'] ."</td>";
		$html .= '<td>&nbsp;'.$row_transacoes['nome'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_jan'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_fev'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_mar'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_abr'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_mai'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_jun'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_jul'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_ago'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_set'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_out'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_nov'] . "</td>";
		$html .= '<td align="center">'.$row_transacoes['mens_dez'] . "</td></tr>";		
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
	$dompdf->load_html('
			
<!--
*****************************************************************************************************
                                   RELAÇÃO DE MENSALIDADES EM ABERTO
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MENSALIDADES EM ABERTO</h3>

	');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_mensalidades", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>