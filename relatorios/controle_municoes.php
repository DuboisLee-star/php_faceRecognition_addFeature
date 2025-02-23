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

// Consulta para obter os dados de membros e seus totais de entrada e saída
$result_transacoes = "SELECT m.matricula, m.nome, c.compra_calibre, SUM(c.compra_qtdecalibre) AS total_entrada, SUM(h.qtdemunicoes) AS total_saida
FROM tab_membros AS m
LEFT JOIN tab_compras AS c ON m.matricula = c.matricula
LEFT JOIN tab_habitualidade AS h ON m.matricula = h.matricula
GROUP BY m.matricula, m.nome, c.compra_calibre
ORDER BY m.matricula";

$resultado_trasacoes = mysqli_query($conn, $result_transacoes);

// Inicialize um array para armazenar as informações dos atiradores
$atiradores = array();

while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    $matricula = $row_transacoes['matricula'];
    $calibre = $row_transacoes['compra_calibre'];
    $entrada = $row_transacoes['total_entrada'];
    $saida = $row_transacoes['total_saida'];

    if (!isset($atiradores[$matricula])) {
        $atiradores[$matricula] = array(
            'nome' => utf8_decode($row_transacoes['nome']),
            'calibres' => array(),
        );
    }

    // Adicione as informações do calibre atual ao atirador correspondente
    $atiradores[$matricula]['calibres'][] = array(
        'calibre' => $calibre,
        'entrada' => $entrada,
        'saida' => $saida,
        'saldo' => $entrada - $saida,
    );
}

// Inicialize o HTML da tabela
$html = '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;ATIRADOR</th>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;CALIBRE</th>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;ENTRADA</th>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;SA&Iacute;DA</th>';
$html .= '<th bgcolor="#CCCCCC"><div align="center">&nbsp;SALDO</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Percorra o array de atiradores e seus calibres
foreach ($atiradores as $matricula => $atirador) {
    $firstCalibre = true;
    foreach ($atirador['calibres'] as $calibreData) {
        $html .= '<tr>';
        if ($firstCalibre) {
            $html .= '<td rowspan="' . count($atirador['calibres']) . '"><div align="left">&nbsp;<font size="1"><b>' . $matricula . '</b><BR>&nbsp;' . $atirador['nome'] . '</td>';
            $firstCalibre = false;
        }
        $html .= '<td><div align="center">&nbsp;<font size="1">' . $calibreData['calibre'] . '</td>';
        $html .= '<td><div align="center">&nbsp;<font size="1">' . $calibreData['entrada'] . '</td>';
        $html .= '<td><div align="center">&nbsp;<font size="1">' . $calibreData['saida'] . '</td>';
        $html .= '<td><div align="center">&nbsp;<font size="1">' . $calibreData['saldo'] . '</td>';
        $html .= '</tr>';
    }
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
                                    RELATÓRIO CONTROLE DE MUNIÇÕES
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE ATIRADORES E MUNI&Ccedil;&Otilde;ES</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relatorio_municoes_entrada_saida", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>