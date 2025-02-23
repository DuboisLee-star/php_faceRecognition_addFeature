<?php	

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

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
	include_once ("../config/assinatura7.php");
	include_once ("../config/texto.php");


// Recebe o id do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
$grupo = (isset($_GET['calibre'])) ? $_GET['calibre'] : '';
$datainicial = isset($_GET['datainicial']) ? date('Y-m-d', strtotime($_GET['datainicial'])) : null;
$datafinal = isset($_GET['datafinal']) ? date('Y-m-d', strtotime($_GET['datafinal'])) : null;

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql1 = 'SELECT * FROM tab_membros WHERE id = :id';
    $stm = $conexao->prepare($sql1);
    $stm->bindValue(':id', $id_cliente);
    $stm->execute();
    $cliente = $stm->fetch(PDO::FETCH_OBJ);
    
    // Faz a conexão com o banco
    $sql2 = 'SELECT * FROM info_clube WHERE id = :id';
    $stm = $conexao->prepare($sql2);
    $stm->bindValue(':id', 1);
    $stm->execute();
    $clube = $stm->fetch(PDO::FETCH_OBJ);
    
    // Consulta os grupos distintos na tabela tab_armas
    $sql_grupo = 'SELECT * FROM tab_grupos_armas WHERE id = :id';
    $stm_grupo = $conexao->prepare($sql_grupo);
    $stm_grupo->bindValue(':id', $grupo);
    $stm_grupo->execute();
    $grupo1 = $stm_grupo->fetchAll(PDO::FETCH_OBJ);
    
    
// Montagem da query para habitualidade
$sql3 = "SELECT * FROM tab_habitualidade 
         WHERE matricula = :matricula 
         AND id_grupo = :grupo 
         AND data >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";

// Aplica filtro de datas, se fornecido
if ($datainicial && $datafinal) {
    // Ajusta a consulta para comparar apenas a data, sem a parte da hora
    $sql3 .= " AND DATE(datacadastro) BETWEEN :datainicial AND :datafinal";
} elseif ($datainicial) {
    $sql3 .= " AND DATE(datacadastro) >= :datainicial";
} elseif ($datafinal) {
    // Ajusta para comparar a data final e garantir que o dia inteiro seja considerado
    $sql3 .= " AND DATE(datacadastro) <= :datafinal";
}
    // Ordena os resultados
    $sql3 .= " ORDER BY datacadastro DESC";

    // Prepara a execução da consulta
    $stm = $conexao->prepare($sql3);
    $stm->bindValue(':matricula', $cliente->matricula);
    $stm->bindValue(':grupo', $grupo);

    // Vincula as variáveis de data
    if ($datainicial) {
        $stm->bindValue(':datainicial', $datainicial);
    }
    if ($datafinal) {
        $stm->bindValue(':datafinal', $datafinal);
    }

    $stm->execute();
    $habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

    if(!empty($cliente)):

        // Formata a data no formato nacional
        $array_data     = explode('-', $cliente->data_nascimento);
        $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

    endif;

endif;

// Gerando o HTML para o relatório
$html = '<table border=1>';
$html .= '<tr><td>' . $cliente->matricula . '</td></tr>';

// HTML habitualidade
$contador = 1;
$quebraPagina = false;
$html_habitualidade = "";

if ($habitualidade) {
    foreach ($habitualidade as $idh => $value) {
        
        // Verifica se atingiu o limite de 15 registros e realiza a quebra de página
        if ($contador == 16 && !$quebraPagina) {
            $html_habitualidade .= '</table>';
            $html_habitualidade .= '<div style="page-break-before: always;"></div>';
            $html_habitualidade .= '<table border=1 cellspacing=0 cellpadding=5 width=100%>';
            $html_habitualidade .= '
                <tr>
                    <td align="center" width="8%" bgcolor="#C0C0C0"><font size="1"><b>ORDEM</b></font></td>
                    <td align="center" width="15%" bgcolor="#C0C0C0"><font size="1"><b>DATA</b></font></td>
                    <td align="center" width="27%" bgcolor="#C0C0C0"><font size="1"><b>ARMAMENTO</b></font></td>
                    <td align="center" width="10%" bgcolor="#C0C0C0"><font size="1"><b>QTD</b></font></td>
                    <td align="center" width="40%" bgcolor="#C0C0C0"><font size="1"><b>TREINAMENTO/COMPETIÇÃO</b></font></td>
                </tr>
            ';
            $quebraPagina = true;
        }

        $html_habitualidade .= '
            <tr>
                <td width="8%" align="center">&nbsp;<font size="1">' . $contador . '</font></td>
                <td width="15%" align="center">&nbsp;<font size="1">' . date('d/m/Y H:i', strtotime($value->datacadastro)) . '</font></td>
                <td width="27%" align="center">&nbsp;<font size="1">' . $value->tipo . ' | ' . $value->calibre . ' | ' . $value->numsigma . '</font></td>
                <td width="10%" align="center">&nbsp;<font size="1">' . $value->qtdemunicoes . '</font></td>
                <td width="40%" align="left">&nbsp;<font size="1">' . mb_strtoupper($value->local) . ' - ' . mb_strtoupper($value->evento) . '</font></td>
            </tr>
        ';

        $contador++;
    }
}

$html_habitualidade_registro = '';
foreach ($habitualidade as $registro) {
    $html_habitualidade_registro .= '
        <tr>
            <td align="center" colspan="3"><font size="1">HOSTMARQ</font></td>
            <td align="center"><font size="1">000' . $registro->id . '</font></td>
            <td align="center"><font size="1">' . ($registro->datacadastro ? date('d/m/Y', strtotime($registro->datacadastro)) : '') . '</font></td>
        </tr>
    ';
}

// Criando a Instância do DOMPDF
$dompdf = new DOMPDF(array('enable_remote' => true, 'enable_html5_parser'=>true));
$dompdf->set_option('isHtml5ParserEnabled', true);

// Carrega seu HTML
$dompdf->load_html('
<div align="center" style="line-height: 120%; margin-left: 15; margin-right: 15">
    <font size="2"><b>COMPROVAÇÃO DE PARTICIPAÇÕES EM TREINAMENTOS E/OU COMPETIÇÕES DE TIRO</b><br>(H A B I T U A L I D A D E)<br><br><b>ANEXO E</b><br>(art. 35 do Decreto nº 11.615/2023)</font><br><br>
    <font size="2">DADOS DA ENTIDADE DE TIRO DECLARANTE</font>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <tr><td width="30%" valign="top"><font size="2"><b>Nome:</b></font></td><td width="50%" valign="top"><font size="2">' . mb_strtoupper($clube->clube_nome) . '</font></td><td width="25%" valign="top"><font size="2"><b>CNPJ:</b></font><font size="2">' . $clube->clube_cnpj . '</font></td></tr>
        <tr><td width="30%" valign="top"><font size="2"><b>Certificado de Registro</b></font></td><td width="50%" valign="top"><font size="2">' . $clube->clube_cr . '</font></td><td width="25%" valign="top"><font size="2"><b>Data:</b> ' . ($clube->clube_validade_cr ? date('d/m/Y', strtotime($clube->clube_validade_cr)) : '') . '</font></td></tr>
        <tr><td width="30%" valign="top"><font size="2"><b>Endereço</b></font></td><td width="75%" colspan="2" valign="top"><font size="2">' . mb_strtoupper($clube->clube_endereco) . '</font></td></tr>
    </table>
    <br>
    <font size="2">DADOS DO ATIRADOR DESPORTIVO</font>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <tr><td width="30%" valign="top"><b><font size="2">Nome:</font></b></td><td width="50%" valign="top"><font size="2">' . mb_strtoupper($cliente->nome) . '</font></td><td width="25%" valign="top"><b><font size="2">CPF:</font></b><font size="2"> ' . $cliente->cpf . '</font></td></tr>
        <tr><td width="30%" valign="top"><b><font size="2">Certificado de Registro</font></b></td><td width="50%" valign="top"><font size="2">' . $cliente->cr . '</font></td><td width="25%" valign="top"><b><font size="2">Data:</font></b><font size="2"> ' . ($cliente->data_renovacao ? date('d/m/Y', strtotime($cliente->data_renovacao)) : '') . '</font></td></tr>
        <tr><td width="30%" valign="top"><b><font size="2">Endereço:</font></b></td><td width="75%" colspan="2" valign="top"><font size="2">' . mb_strtoupper($cliente->rua) . ' nº ' . $cliente->numero . ', bairro: ' . mb_strtoupper($cliente->bairro) . ', CEP ' . $cliente->cep . ' - ' . mb_strtoupper($cliente->cidade) . '/' . mb_strtoupper($cliente->siglauf) . '</font></td></tr>
    </table>
    <br>
    <font size="2">DADOS DA HABITUALIDADE</font>
    <div align="center" style="padding-bottom:35px">
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr><td width="20%" valign="top" colspan="3"><font size="1">GRUPO DE USO: ' . $grupo1[0]->descricao . ' </font></td><td width="10%" valign="top" align="center"><font size="1">' . $grupo1[0]->nome . '</font></td><td width="25%" valign="top"><b><font size="1">TIPO DE EVENTO:</font></b></td></tr>
            <tr><td align="center" width="8%" bgcolor="#C0C0C0"><font size="1"><b>ORDEM</b></font></td><td align="center" width="15%" bgcolor="#C0C0C0"><font size="1"><b>DATA : HORA</b></font></td><td align="center" width="27%" bgcolor="#C0C0C0"><font size="1"><b>ARMAMENTO</b></font></td><td align="center" width="10%" bgcolor="#C0C0C0"><font size="1"><b>QTD</b></font></td><td align="center" width="40%" bgcolor="#C0C0C0"><font size="1"><b>TREINAMENTO/COMPETIÇÃO</b></font></td></tr>
            ' . $html_habitualidade . '
        </table><br>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr><td align="center" colspan="5"><font size="1"><b>REGISTRO ELETRÔNICO DE HABITUALIDADE</b></font></td></tr>
            <tr><td align="center" colspan="3"><font size="1"><b>SISTEMA</b></font></td><td align="center"><font size="1"><b>Nº REGISTRO</b></font></td><td align="center"><font size="1"><b>DATA DO LANÇAMENTO</b></font></td></tr>
            ' . $html_habitualidade_registro . '
            <tr><td align="center" colspan="5"></td></tr>
        </table>
    </div>
</div>
<br><br><br>
<div align="center" style="margin-left: 0; margin-right: 0;">
' . PDF_ASSINA . '
</div>
');

// Renderizar o HTML
$dompdf->render();

// Gerar o documento
$dompdf->stream("habitualidade_por_grupo", array("Attachment" => false));

?>
