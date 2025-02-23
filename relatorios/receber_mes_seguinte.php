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
	
$html = '<table border="1" cellspacing="0" cellpadding="0" width="100%" class="data-vermelha">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center">Nº</th>';
$html .= '<th bgcolor="#CCCCCC" width="65%"><div align="center">ASSOCIADO</th>';
$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center">DATA</th>';
$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center">VALOR</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$mes_corrente = date('m'); // Obtém o mês atual (formato numérico)
$ano_corrente = date('Y'); // Obtém o ano atual

// Adicione um mês ao mês corrente
$mes_corrente = ($mes_corrente % 12) + 1; // Adiciona 1 e volta para 1 se for dezembro
if ($mes_corrente == 1) {
    $ano_corrente++; // Incrementa o ano se o mês for janeiro
}

$result_transacoes = "
    SELECT m.*, f.anuidade2024
    FROM tab_membros AS m
    LEFT JOIN tab_financeiro AS f ON m.matricula = f.matricula
    WHERE m.bloqueio LIKE 'Nao'
    AND YEAR(m.data_renovacao) = $ano_corrente
    AND MONTH(m.data_renovacao) = $mes_corrente
    ORDER BY m.matricula
";

$resultado_transacoes = mysqli_query($conn, $result_transacoes);
$contador = 1; // Inicialize o contador
$total_valores = 0; // Inicialize o total de valores

while ($row_transacoes = mysqli_fetch_assoc($resultado_transacoes)) {
    $html .= '<tr>';
    $html .= '<td width="5%"><div align="center"><font size="1">' . $contador . '</td>';
    $html .= '<td width="65%"><div align="left">&nbsp;<font size="1"><b>' . $row_transacoes['matricula'] . '</b>&nbsp;' . utf8_decode($row_transacoes['nome']) . '</td>';

    $data_renovacao = strtotime($row_transacoes['data_renovacao']);
    $data_atual = strtotime(date('Y-m-d'));

    // Verifica se a data é igual ou anterior à data atual e aplica a classe CSS
    if ($data_renovacao <= $data_atual) {
        $html .= '<td width="15%"><div align="center"><font size="1" class="data-vermelha">' . date('d/m/Y', $data_renovacao) . '</td>';
    } else {
        $html .= '<td width="15%"><div align="center"><font size="1">' . date('d/m/Y', $data_renovacao) . '</td>';
    }

    $valor = $row_transacoes['anuidade2024'];
    $html .= '<td width="15%"><div align="center"><font size="1">' . $valor . '</td>';
    $html .= '</tr>';

    $contador++; // Incrementar o contador a cada linha
    $total_valores += $valor; // Adicionar o valor ao total
}

$html .= '<tr>';
$html .= '<td colspan="3" align="right"><font size="1"><b>Total:</b>&nbsp;</td>';
$html .= '<td align="center"><font size="1"><b>R$ ' . number_format($total_valores, 2, ',', '.') . '</b></td>';
$html .= '</tr>';
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
                                  RELAÇÃO DE MEMBROS POR ANUIDADE QUE VENCE
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS & ANUIDADES MÊS SEGUINTE</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_membros_anuidade_a_vencer", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>