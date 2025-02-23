<?php

function formataCPFCNPJ($value)
{
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
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
include_once("../config/cabecalho.php");
include_once("../config/assinatura5.php");
include_once("../config/texto.php");

$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : false;
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : false;
$ordem = isset($_POST['ordem']) ? $_POST['ordem'] : false;

$conexao = conexao::getInstance();
$sql = '
        SELECT
            a.matricula,
            a.nome,
            b.datahora
        FROM
            tab_membros a
            inner join tab_registro_presenca b on b.matricula = a.matricula AND DATE_FORMAT(datahora, \'%Y-%m\') = \'' . $periodo . '\'
        ' . ((strlen(trim($matricula)) > 0) ? " WHERE a.matricula = '{$matricula}' " : '') . '
        ' . (($ordem == 1) ? ' ORDER BY b.datahora DESC ' : ' ORDER BY a.matricula ASC, b.datahora DESC ') . '
    ';

$stm = $conexao->prepare($sql);
$stm->execute();
$cliente = $stm->fetchAll(PDO::FETCH_OBJ);

$html  = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Presença Biométrica</title>
    <style>
        /* Adicione estilos CSS para o cabeçalho aqui */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    ' . PDF_CABECALHO . '
</div>

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
<b>RELATÓRIO DE FREQUÊNCIA E HABITUALIDADES COM BIOMETRIA</b><br>
';

$html .= '<table border=1 cellspacing=0 cellpadding=5 width=100%>';
$html .= '<thead><tr>';
$html .= '<td bgcolor="#f0f0f0" width="60" align="center"><font size="2"><b>MATRICULA</b></font></td>';
$html .= '<td bgcolor="#f0f0f0"><font size="2"><b>NOME DO ATIRADOR</b></font></td>';
$html .= '<td bgcolor="#f0f0f0" width="140" align="center"><font size="2"><b>HORÁRIO</b></font></td>';
$html .= '</tr>';
$html .= '</thead><tbody>';

foreach ($cliente as $key => $Atirador) {
    $html .= '<tr>';
    $html .= '<td bgcolor="#ffffff" align="center"><font size="2">' . $Atirador->matricula . '</font></td>';
    $html .= '<td bgcolor="#ffffff"><font size="2">' . $Atirador->nome . '</font></td>';
    $html .= '<td bgcolor="#ffffff" align="center"><font size="2">' . date('d/m/Y H:i:s', strtotime($Atirador->datahora)) . '</font></td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>'.PDF_ASSINA.'';
$html .= '</body></html>';

//referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("dompdf/autoload.inc.php");

//Criando a Instancia
$dompdf = new DOMPDF(array('enable_remote' => true));
$dompdf->load_html($html);
// Carrega seu HTML

	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());

	/* cria documento assinado */
	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
    $aut = new AutentiqueH();
    $aut->tipo_documento = 'Biometria'.' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
    $aut->posicao_assinatura   = $dompdf->get_canvas()->get_page_number();
	$aut->criar_documento($documento, $cliente->matricula, true, array(15, 82, 1), array(66, 82, 1)); // (documento, matricula, exibirAssinaturaClube, posicao_assinatura_membro, posicao_assinatura)
	$aut->output();
	
	//Exibibir a página
	$dompdf->stream(
		"relatorio_biometria", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>