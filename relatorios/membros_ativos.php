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

$html = '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center">Nº</th>';
$html .= '<th bgcolor="#CCCCCC" width="45%"><div align="center"><font size="1">ASSOCIADO</th>';
$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center"><font size="1">CR | VALIDADE</th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="1">CPF</th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="1">FILIAÇÃO</th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="1"><font size="1">RENOVAÇÃO</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$result_transacoes = "SELECT * FROM tab_membros WHERE bloqueio LIKE 'Nao' AND CR IS NOT NULL AND CR <> '' ORDER BY matricula";

$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
$contador = 1; // Inicialize o contador

while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    $html .= '<tr><td width="5%"><div align="center">&nbsp;<font size="1">' . $contador . '</td>';
    $html .= '<td width="45%"><div align="left">&nbsp;<font size="1"><b>' . $row_transacoes['matricula'] . '</b>&nbsp;' . utf8_decode($row_transacoes['nome']) . '</td>';
    $html .= '<td width="20%"><div align="center">&nbsp;<font size="1">' . $row_transacoes['cr'] . ' &nbsp;|&nbsp; ' . date('d/m/Y', strtotime($row_transacoes['validade_cr'])) . '</td>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1">' . $row_transacoes['cpf'] . ' </td>';
    $html .= '<td width="10%"><div align="center"><font size="1">' . date('d/m/Y', strtotime($row_transacoes['data_filiacao'])) . '</td>';
    $html .= '<td width="10%"><div align="center"><font size="1">' . date('d/m/Y', strtotime($row_transacoes['data_renovacao'])) . '</td>';
    $html .= '</tr>';

    $contador++; // Incrementar o contador a cada linha
}

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
                                  RELAÇÃO DE MEMBROS POR DATA DE FILIAÇÃO
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS ATIVOS</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_membros_ativos", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>