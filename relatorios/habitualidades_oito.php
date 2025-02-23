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
$html .= '<th bgcolor="#CCCCCC" width="70%"><div align="center">&nbsp;ATIRADOR</th>';
$html .= '<th bgcolor="#CCCCCC" width="30%"><div align="center">&nbsp;QTDE DE VISITAS</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Realiza a consulta SQL nas tabelas
$result_transacoes = "SELECT m.matricula, m.nome, COUNT(DISTINCT h.data) as qtd_datas
                      FROM tab_membros m
                      LEFT JOIN tab_habitualidade h ON m.matricula = h.matricula AND h.data >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)
                      GROUP BY m.matricula, m.nome
                      HAVING qtd_datas < 8
                      ORDER BY m.matricula";

$resultado_trasacoes = mysqli_query($conn, $result_transacoes);

while ($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)) {
    $html .= '<tr><td width="70%"><div align="left">&nbsp;<font size="1"><b>' . $row_transacoes['matricula'] . '</b><BR>&nbsp;' .utf8_decode($row_transacoes['nome']) . "</td>";
    $html .= '<td width="30%"><div align="center">&nbsp;<font size="1">' . $row_transacoes['qtd_datas'] . '</td>';
    $html .= "</tr>";
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
                                  RELAÇÃO DE ATIRADORES E QUANTIDADE DE VISITAS
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS POR QUANTIDADE<br> DE 8 HABITUALIDADES MÍNIMAS EXIGIDAS</h3>

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