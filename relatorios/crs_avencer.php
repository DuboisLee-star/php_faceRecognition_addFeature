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
$html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center">Nº</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="35%"><div align="center">ASSOCIADO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center">Nº CR</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center">VENCIMENTO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center">DIAS RESTANTES</div></th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$resultado_transacoes = "SELECT *, DATEDIFF(validade_cr, CURDATE()) AS dias_restantes FROM tab_membros WHERE bloqueio = 'Nao' AND cr <> '' ORDER BY matricula";

$resultado_transacoes = mysqli_query($conn, $resultado_transacoes);
$contador = 1; // Inicialize o contador

while ($row_transacoes = mysqli_fetch_assoc($resultado_transacoes)) {
    $html .= '<tr>';
    $html .= '<td width="5%"><div align="center">&nbsp;<font size="1">' . $contador . '</font></div></td>';
    $html .= '<td width="35%"><div align="left">&nbsp;<font size="1"><b>' . $row_transacoes['matricula'] . '</b>&nbsp;' . utf8_decode($row_transacoes['nome']) . '</font></div></td>';
    $html .= '<td width="15%"><div align="center">&nbsp;<font size="1">' . $row_transacoes['cr'] . '</font></div></td>';
    $html .= '<td width="20%"><div align="center">&nbsp;<font size="1">' . date('d/m/Y', strtotime($row_transacoes['validade_cr'])) . '</font></div></td>';
    $html .= '<td width="20%"><div align="center">&nbsp;<font size="1">' . $row_transacoes['dias_restantes'] . '</font></div></td>';
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

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS ATIVOS COM CRS A VENCER</h3>

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