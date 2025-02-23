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

$datainicial1 = DateTime::createFromFormat('d/m/Y',$_GET["datainicial"])->format("Y-m-d");
$datafinal1 = DateTime::createFromFormat('d/m/Y',$_GET["datafinal"])->format("Y-m-d");

$datainicial_formatada = DateTime::createFromFormat('Y-m-d', $datainicial1)->format("d/m/Y");
$datafinal_formatada = DateTime::createFromFormat('Y-m-d', $datafinal1)->format("d/m/Y");

//**************************************************

include_once("../config/conecta_rel.php");
include_once("../config/cabecalho.php");

// Consulta SQL
$result_transacoes = "
    SELECT 
        a.datacadastro,
        a.evento,
        a.tipo,
        a.modelo,
        a.calibre,
        a.numsigma,
        b.cr,
        b.nome,
        b.matricula
    FROM 
        tab_habitualidade a
    INNER JOIN 
        tab_membros b ON a.matricula = b.matricula
    WHERE
        a.datacadastro BETWEEN '$datainicial1' AND '$datafinal1'
    ORDER BY
        a.matricula ASC,
        a.datacadastro ASC";

// Executar a consulta
$resultado_transacoes = mysqli_query($conn, $result_transacoes);

// Verificar se a consulta foi executada corretamente
if (!$resultado_transacoes) {
    die('Erro na consulta SQL: ' . mysqli_error($conn));
}

$html = '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center"><font size="2">Nº</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="35%"><div align="center"><font size="2">MATRÍCULA | ASSOCIADO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="2">DATA</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center"><font size="2">EVENTO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="2">SIGMA</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="25%"><div align="center"><font size="2">TIPO | MODELO | CALIBRE</div></th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$contador = 1; // Inicialize o contador

while ($row_transacoes = mysqli_fetch_assoc($resultado_transacoes)) {
    $html .= '<tr>';
    $html .= '<td width="5%"><div align="center"><font size="2">' . $contador . '</div></td>';
    $html .= '<td width="35%"><div align="left"><font size="2">&nbsp;<b>' . $row_transacoes['matricula'] . '</b> &nbsp;' . utf8_decode(strtoupper($row_transacoes['nome'])) . '</div></td>';
    $html .= '<td width="10%"><div align="center"><font size="2">' . (isset($row_transacoes['datacadastro']) ? date('d/m/Y', strtotime($row_transacoes['datacadastro'])) : '') . '</div></td>';
    $html .= '<td width="15%"><div align="center"><font size="2">' . (isset($row_transacoes['evento']) ? strtoupper($row_transacoes['evento']) : '') . '</div></td>';
    $html .= '<td width="10%"><div align="center"><font size="2">' . (isset($row_transacoes['numsigma']) ? strtoupper($row_transacoes['numsigma']) : '') . '</div></td>';
    $html .= '<td width="25%"><div align="center"><font size="2">' . (isset($row_transacoes['tipo']) ? strtoupper($row_transacoes['tipo']) : '') . ' | ' . (isset($row_transacoes['modelo']) ? strtoupper($row_transacoes['modelo']) : '') . ' | ' . (isset($row_transacoes['calibre']) ? strtoupper($row_transacoes['calibre']) : '') . '</div></td>';
    $html .= '</tr>';

    $contador++; // Incrementar o contador a cada linha
}

$html .= '</tbody>';
$html .= '</table>';

// Referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// Include autoloader
require_once("dompdf/autoload.inc.php");

// Criando a Instância
$dompdf = new Dompdf();

// Carrega seu HTML
$dompdf->load_html(utf8_encode('
<!--
*****************************************************************************************************
                                  RELAÇÃO DE ASSOCIADOS E HABITUALIDADES
*****************************************************************************************************
-->
<p align="center" style="line-height: 120%; margin-left: 15px; margin-right: 15px;">
    '.PDF_CABECALHO.'
</p>
<h3 style="text-align: center;">RELAÇÃO DE ASSOCIADOS COM SUAS HABITUALIDADES</h3>

<b>PERÍODO:</b> ' . $datainicial_formatada . ' À ' . $datafinal_formatada . '<br><br>

'. $html .'

'));

// Definir o papel e a orientação (paisagem)
$dompdf->setPaper('A4', 'landscape');

// Renderizar o HTML
$dompdf->render();

// Exibir a página
$dompdf->stream(
    "relacao_membros_e_habitualidades",
    array(
        "Attachment" => false // Para realizar o download, alterar para true
    )
);
?>