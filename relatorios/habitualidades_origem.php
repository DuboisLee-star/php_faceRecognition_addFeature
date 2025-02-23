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

$html = '<table border=1 cellspacing="0" cellpadding="0" width="100%"';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th rowspan="2" bgcolor="#CCCCCC"><div align="center">&nbsp;ATIRADOR</th>';
$html .= '<th colspan="2" bgcolor="#CCCCCC"><div align="center">&nbsp;TIPO DE MUNIÇÃO</th>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;Original</th>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;Recarregada</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$result_transacoes = "SELECT m.matricula, m.nome, 
    SUM(CASE WHEN h.tipomunicao = 'O' THEN h.qtdemunicoes ELSE 0 END) AS totaloriginal,
    SUM(CASE WHEN h.tipomunicao = 'R' THEN h.qtdemunicoes ELSE 0 END) AS totalrecarregada
FROM tab_membros AS m
LEFT JOIN tab_habitualidade AS h ON m.matricula = h.matricula
GROUP BY m.matricula, m.nome
ORDER BY m.matricula";

$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    $html .= '<tr>';
    $html .= '<td><div align="left">&nbsp;<font size="1"><b>' . $row_transacoes['matricula'] . '</b><BR>&nbsp;' . utf8_decode($row_transacoes['nome']) . "</td>";
    $html .= '<td><div align="center">&nbsp;<font size="1">' . $row_transacoes['totaloriginal'] . '</td>';
    $html .= '<td><div align="center">&nbsp;<font size="1">' . $row_transacoes['totalrecarregada'] . '</td>';
    $html .= "</tr>";
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
                            RRELATÓRIO HABITUALIDADES POR MUNIÇÕES ADQUIRIDAS
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE ATIRADORES E MUNIÇÕES ADQUIRIDAS</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_membros_data_filiacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>