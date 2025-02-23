<?php

//**************************************************
// Função para formatar CPF e CNPJ | Tiago Moselli
//**************************************************

function formataCPFCNPJ($value)
{
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

//**************************************************

include_once("../config/conecta_rel.php");
include_once("../config/cabecalho.php");

// Capturar o ID a partir da URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Certifique-se de que é um número inteiro
} else {
    // Lidar com o caso em que o ID não está presente na URL
    die("ID não fornecido na URL");
}

// Realiza a consulta SQL nas tabelas usando o ID capturado da URL
// Atualizei a consulta para incluir o campo "nome" e os campos necessários para exibir "ano"
$result_transacoes = "
SELECT 
    h.matricula, 
    m.nome, 
    YEAR(h.datacadastro) as anohabitualidade, 
    COUNT(h.datacadastro) as qtde 
FROM 
    tab_habitualidade h 
    JOIN tab_membros m ON h.matricula = m.matricula 
WHERE 
    h.datacadastro >= DATE_ADD(NOW(), INTERVAL -1 YEAR) 
    AND h.matricula = (SELECT matricula FROM tab_membros WHERE id = $id) 
GROUP BY 
    h.matricula, anohabitualidade";

$resultado_transacoes = mysqli_query($conn, $result_transacoes);

$sequencia = 1; // Inicializa o número sequencial
$html = '';
$anosContagem = array(); // Array para rastrear a contagem dos anos

// Recuperar "matricula" e "nome" da primeira linha do resultado
if ($row_membro = mysqli_fetch_assoc($resultado_transacoes)) {
    $matricula = $row_membro["matricula"];
    $nome = $row_membro["nome"];
} else {
    die("Nenhum dado encontrado para o ID fornecido.");
}

$html .= '<p align="center"><strong>Matr&iacute;cula:</strong> ' . $matricula . ' - <strong>Nome:</strong> ' . $nome . '</p>';

// Reseta o ponteiro dos resultados
mysqli_data_seek($resultado_transacoes, 0);

// Percorrer os resultados da consulta para contar os anos que aparecem
while ($row_membro = mysqli_fetch_assoc($resultado_transacoes)) {
    $ano = $row_membro['anohabitualidade'];
    $qtde = $row_membro['qtde'];

    if (!isset($anosContagem[$ano])) {
        $anosContagem[$ano] = $qtde;
    } else {
        $anosContagem[$ano] += $qtde;
    }
}

$html .= '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center">&nbsp;Nº</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="30%"><div align="center">&nbsp;ANO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="30%"><div align="center">&nbsp;QTDE</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="30%"><div align="center">&nbsp;STATUS</div></th>'; // Novo campo RESULTADO
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$sequencia = 1;

foreach ($anosContagem as $ano => $qtde) {
    $html .= '<tr>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1">' . $sequencia . '</font></div></td>';
    $html .= '<td width="30%"><div align="center">&nbsp;<font size="1">' . $ano . '</font></div></td>';
    $html .= '<td width="30%"><div align="center">&nbsp;<font size="1">' . $qtde . '</font></div></td>';
    // Adicionar lógica para determinar o resultado
    $resultado = ($qtde < 8) ? '<font size="1" color="red"><b>Negativo</b></font>' : '<font size="1" color="green"><b>Positivo</b></font>';
    $html .= '<td width="30%"><div align="center">&nbsp;' . $resultado . '</div></td>'; // Novo campo RESULTADO
    $html .= '</tr>';
    $sequencia++; // Incrementa o número sequencial
}

$html .= '</tbody>';
$html .= '</table>';

// referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("dompdf/autoload.inc.php");

// Criando a Instancia
$dompdf = new DOMPDF(array('enable_remote' => true));

// Carrega seu HTML
$dompdf->load_html('
<!--
*****************************************************************************************************
CONTAGEM DE HABITUALIDADES
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
' . PDF_CABECALHO . '

<h3 style="text-align: center;">CONTAGEM DE HABITUALIDADES POR ANO</h3><br>

' . $html . '

');

// Renderizar o html
$dompdf->render();

// Exibir a página
$dompdf->stream(
    "contagem_habitualidades",
    array(
        "Attachment" => false // Para realizar o download somente alterar para true
    )
);
?>