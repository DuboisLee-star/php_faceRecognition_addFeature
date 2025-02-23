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
    h.id_grupo,
    gp.nome AS grupo_nome,
    h.tipo,
    h.calibre,
    h.modelo,
    h.numsigma,
    m.nome,
    m.matricula,
    COUNT(h.datacadastro) AS qtde 
FROM 
    tab_habitualidade h 
    JOIN tab_membros m ON h.matricula = m.matricula 
    JOIN tab_grupos_armas gp ON h.id_grupo = gp.id
WHERE 
    h.datacadastro >= DATE_ADD(NOW(), INTERVAL -1 YEAR) 
    AND h.matricula = (SELECT matricula FROM tab_membros WHERE id = ?) 
GROUP BY 
    gp.id, gp.nome, h.tipo, h.calibre, h.modelo";

$stmt = mysqli_prepare($conn, $result_transacoes);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado_transacoes = mysqli_stmt_get_result($stmt);

$sequencia = 1;
$html = '';
$grupos = array(); // Armazena os grupos e suas armas
$total_grupo = array(); // Armazena o total de cada grupo

// Recupera "matricula" e "nome" da primeira linha do resultado
if ($row_membro = mysqli_fetch_assoc($resultado_transacoes)) {
    $matricula = $row_membro["matricula"];
    $nome = $row_membro["nome"];
} else {
    die("Nenhum dado encontrado para o ID fornecido.");
}

$html .= utf8_encode('<p align="center"><strong>Matrícula:</strong> ' . $matricula . ' - <strong>Nome:</strong> ' . $nome . '</p>');

// Reseta o ponteiro dos resultados
mysqli_data_seek($resultado_transacoes, 0);

// Processa os resultados agrupando por grupo de armas
while ($row = mysqli_fetch_assoc($resultado_transacoes)) {
    $grupo_nome = $row['grupo_nome'];
    $qtde = $row['qtde'];
    $armamento = $row['tipo'] . ' | ' . $row['calibre'] . ' | ' . $row['modelo'].' | '.$row['numsigma'];

    if (!isset($grupos[$grupo_nome])) {
        $grupos[$grupo_nome] = [];
        $total_grupo[$grupo_nome] = 0;
    }

    $grupos[$grupo_nome][] = [
        'nome' => $armamento,
        'qtde' => $qtde
    ];

    $total_grupo[$grupo_nome] += $qtde; // Soma total de armamentos do grupo
}

// Gera a tabela com os grupos de armas
$html .= '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
$html .= '<thead>';
$html .= '<tr>';
$html .= utf8_encode('<th bgcolor="#CCCCCC" width="5%"><div align="center">&nbsp;Nº</div></th>');
$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center">&nbsp;GRUPO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center">&nbsp;ARMAMENTOS</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center">&nbsp;CALIBRE</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center">&nbsp;STATUS CAL.</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center">&nbsp;GRUPO</div></th>';
$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center">&nbsp;STATUS GRUPO</div></th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$sequencia = 1;

foreach ($grupos as $grupo_nome => $armas) {
    $armamentos_list = '';
    $totais_list = '';
    $status_list = '';

    foreach ($armas as $arma) {
        $armamentos_list .= $arma['nome'] . "<br>";
        $totais_list .= $arma['qtde'] . "<br>";

        // Determinar status para cada arma
        $status = ($arma['qtde'] < 8) ? '<font color="red"><b>Negativo</b></font>' : '<font color="green"><b>Positivo</b></font>';
        $status_list .= $status . "<br>";
    }

    // Determinar status do grupo
    $status_grupo = ($total_grupo[$grupo_nome] < 8) ? '<font color="red"><b>Negativo</b></font>' : '<font color="green"><b>Positivo</b></font>';

    $html .= '<tr>';
    $html .= '<td width="5%"><div align="center">&nbsp;<font size="1">' . $sequencia . '</font></div></td>';
    $html .= '<td width="15%"><div align="center">&nbsp;<font size="1">' . $grupo_nome . '</font></div></td>';
    $html .= '<td width="20%"><div align="center">&nbsp;<font size="1">' . $armamentos_list . '</font></div></td>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1">' . $totais_list . '</font></div></td>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1">' . $status_list . '</div></td>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1"><b>' . $total_grupo[$grupo_nome] . '</b></font></div></td>';
    $html .= '<td width="10%"><div align="center">&nbsp;<font size="1">' . $status_grupo . '</div></td>';
    $html .= '</tr>';

    $sequencia++;
}

$html .= '</tbody>';
$html .= '</table><br><br>';
$html.='<div style="font-weight:bold";><bold><center><font size ="1">Legenda:</center></bold></div>';

$html.='<div><center><font size ="1">ACP - Arma Curta Permitida</center></div>';
$html.='<div><center><font size ="1">ACR - Arma Curta Restrita</center></div>';
$html.='<div><center><font size ="1">ALLP - Arma Longa Permitida</center></div>';
$html.='<div><center><font size ="1">ALLR - Arma Longa Restrita</center></div>';
$html.='<div><center><font size ="1">ALRP - Arma Longa Raiada Permitida</center></div>';
$html.='<div><center><font size ="1">ALRR - Arma Longa Raiada Restrita</center></div>';
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