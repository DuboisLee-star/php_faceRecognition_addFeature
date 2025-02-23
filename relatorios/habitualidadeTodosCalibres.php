<?php	

//referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("dompdf/autoload.inc.php");

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
// include_once ("../config/assinatura1.php");
include_once ("../config/assinatura7.php");
include_once ("../config/texto.php");

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
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
    $conexao = conexao::getInstance();
    $sql2 = 'SELECT * FROM info_clube WHERE id = :id';
    $stm = $conexao->prepare($sql2);
    $stm->bindValue(':id', 1);
    $stm->execute();
    $clube = $stm->fetch(PDO::FETCH_OBJ);     
    
$sql3 = " SELECT 
        hab.*, 
        gpa.nome AS nome_grupo, 
        gpa.descricao,
        (SELECT COUNT(*) 
         FROM tab_habitualidade AS sub 
         WHERE sub.id_grupo = hab.id_grupo 
           AND sub.calibre = hab.calibre
        ) AS total_calibres
    FROM tab_habitualidade AS hab
    JOIN tab_grupos_armas AS gpa 
        ON hab.id_grupo = gpa.id
    WHERE hab.matricula = :matricula";

if ($datainicial && $datafinal) {
    // Ambos datainicial e datafinal fornecidos, inclui a data final até o último momento do dia
    $sql3 .= " AND hab.datacadastro BETWEEN :datainicial AND :datafinal";
} elseif ($datainicial) {
    // Apenas datainicial fornecida, filtra desde a data inicial
    $sql3 .= " AND hab.datacadastro >= :datainicial";
} elseif ($datafinal) {
    // Apenas datafinal fornecida, ajusta para garantir que seja até o final do dia (23:59:59)
    $sql3 .= " AND hab.datacadastro <= :datafinal";
}

// Ordenação final dos resultados
$sql3 .= " ORDER BY gpa.id ASC, hab.datacadastro DESC, hab.tipo ASC";

// Garantir que a data final seja considerada até o final do dia, caso seja fornecida
if ($datafinal) {
    // Ajuste para incluir o horário final do dia (23:59:59)
    $datafinal .= ' 23:59:59';
}
$stm = $conexao->prepare($sql3);
// Bind de valores
$stm->bindValue(':matricula', $cliente->matricula);
if ($datainicial) {
    $stm->bindValue(':datainicial', $datainicial);
}
if ($datafinal) {
    $stm->bindValue(':datafinal', $datafinal);
}

// Executa a consulta
$stm->execute();
$habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

    if(!empty($cliente)):

// Formata a data no formato nacional
        $array_data     = explode('-', $cliente->data_nascimento);
        $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
    endif;
endif;

//Criando a Instancia
$dompdf = new DOMPDF(array('enable_remote' => true, 'enable_html5_parser'=>true));
$dompdf->set_option('isHtml5ParserEnabled', true);

// Consulta os grupos distintos na tabela tab_armas

// HTML para habitualidade
$html_habitualidade = "";
if ($habitualidade) {

//Agrupa habitualidades por calibre
    $habitualidades_por_grupo = [];
    foreach ($habitualidade as $hab) {
        $habitualidades_por_grupo[$hab->id_grupo][] = $hab;
  
    }

// Inicialize o contador de ordem fora do loop
    $ordem = 1;

// Para cada calibre, criar uma tabela separada no PDF
    foreach ($habitualidades_por_grupo as $grupo => $habitualidades) {
    foreach ($habitualidades as $hab) {}
   $html_habitualidade .= '
   <div class="conteudoRelatorio" style="padding-bottom:35px">

  <div align="center">
  <table border="1" cellspacing="0" cellpadding="5" width="100%" style="margin-bottom:10px">
  <tr><td width="20%" valign="top"  colspan="4"><font size="1">GRUPO DE USO: <b>' . $hab->nome_grupo . ' - '.$hab->descricao.'</b></font></td>
  <td width="25%" valign="top"><b><font size="1">TIPO DE EVENTO:</font></b> </td></tr>
  <tr><td align="center" width="8%" bgcolor="#C0C0C0"><font size="1"><b>ORDEM</b></font></td>
  <td align="center" width="15%" bgcolor="#C0C0C0"><font size="1"><b>DATA : HORA</b></font></td>
  <td align="center" width="27%" bgcolor="#C0C0C0"><font size="1"><b>ARMAMENTO</b></font></td>
  <td align="center" width="10%" bgcolor="#C0C0C0"><font size="1"><b>QTD</b></font></td>
  <td align="center" width="40%" bgcolor="#C0C0C0"><font size="1"><b>TREINAMENTO/COMPETIÇÃO</b></font></td></tr>';

// Itera sobre as habitualidades
    foreach ($habitualidades as $hab) {
        
  $html_habitualidade .= '
  <tr><td width="8%" align="center">&nbsp;<font size="1">' . $ordem . '</font></td>                
  <td width="15%" align="center">&nbsp;<font size="1">' . date('d/m/Y H:i', strtotime($hab->datacadastro)) . '</font></td>
  <td width="27%" align="center">&nbsp;<font size="1">' . $hab->tipo.' | '.$hab->calibre.' | '.$hab->numsigma . '</font></td>
  <td width="10%" align="center">&nbsp;<font size="1">' . $hab->qtdemunicoes . '</font></td>
  <td width="40%" align="left">&nbsp;<font size="1">' . $hab->local . ' - ' . $hab->evento . '</font></td></tr>';

// Incrementa o contador de ordem
    $ordem++;
}



    $html_habitualidade .= '
            <tr>
                <td colspan="5" align="left"><font size="1"><b>Total de Registros:</b> ' . count($habitualidades).'<br></font> </td>
            </tr>
        </table>
    </div>
    </div>';
    } 
}

$html_habitualidade_registro = ''; // Inicializa a variável
$contador = 1; // Inicializa o contador
foreach ($habitualidade as $registro) {
    $html_habitualidade_registro .= '
        <tr>
            <td align="center"><font size="1">' . $contador . '</font></td>
            <td align="center" colspan="3"><font size="1"> HOSTMARQ </font></td>
            <td align="center"><font size="1"> 000' . $registro->id . '</font></td>
            <td align="center"><font size="1">'. ($registro->datacadastro ? date('d/m/Y', strtotime($registro->datacadastro)) : ''). '</font></td>
        </tr>
    ';
    $contador++; // Incrementa o contador
}

$html = '
<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
</div>
<div align="center"><font size="2"><b>COMPROVAÇÃO DE PARTICIPAÇÕES EM TREINAMENTOS E/OU COMPETIÇÕES DE TIRO</b><br>(H A B I T U A L I D A D E)<br><br><b>ANEXO E</b><br>(art. 35 do Decreto nº 11.615/2023)</font><br><br>
<div align="center"><font size="2">DADOS DA ENTIDADE DE TIRO DECLARANTE</font></div>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Nome:</b></font></td>
        <td width="50%" valign="top"><font size="2">'.$clube->clube_nome.'</font></td>
        <td width="25%" valign="top"><font size="2"><b>CNPJ:</b></font><font size="2"> '.$clube->clube_cnpj.'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Certificado de Registro</b></font><font size="2"></td>
        <td width="50%" valign="top"><font size="2">'.$clube->clube_cr.'</font></td>
        <td width="25%" valign="top"><font size="2"><b>Data:</b> </font><font size="2"> '. ($clube->clube_validade_cr ? date('d/m/Y', strtotime($clube->clube_validade_cr)) : ''). '</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Endereço</b></font></td>
        <td width="75%" colspan="2" valign="top"><font size="2">'.$clube->clube_endereco.'</font></td>
    </tr>
</table>
<br>
<div align="center"><font size="2">DADOS DO ATIRADOR DESPORTIVO</font></div>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</font></b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->nome.'</font></td>
        <td width="25%" valign="top"><b><font size="2">CPF:</b></font><font size="2"> '.formataCPFCNPJ($cliente->cpf).'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</font></b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->cr.'</font></td>
        <td width="25%" valign="top"><b><font size="2">Data:</b></font><font size="2"> '. ($cliente->data_renovacao ? date('d/m/Y', strtotime($cliente->data_renovacao)) : ''). '</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Endereço:</font></b></td>
        <td width="75%" colspan="2" valign="top"><font size="2">'.$cliente->rua.' nº '.$cliente->numero.', bairro: '.$cliente->bairro.', CEP '.$cliente->cep.' - '.$cliente->cidade.'/'.$cliente->siglauf.'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Filiação a Entidade de Tiro</font></b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->matricula.'</font></td>
        <td width="25%" valign="top"><b><font size="2">Data:</b></font><font size="2"> '. ($cliente->data_filiacao ? date('d/m/Y', strtotime($cliente->data_filiacao)) : ''). '</font></td>
    </tr>
</table>
<br>
'.$html_habitualidade.'
<br>
<div class="registrohab" style="padding-bottom:35px">
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td align="center" colspan="6"><font size="1"><b>REGISTRO ELETRÔNICO DE HABITUALIDADE</b></font></td>
    </tr>
    <tr>
        <td align="center"><b><font size="1">ORDEM</b></font></td>
        <td align="center" colspan="3"><font size="1"><b>SISTEMA</b></font></td>
        <td align="center"><font size="1"><b>Nº REGISTRO</b></font></td>
        <td align="center"><font size="1"><b>DATA DO LANÇAMENTO</b></font></td>
    </tr>
    '.$html_habitualidade_registro.'
    <tr>
       <td align="center" colspan="6"></td>
    </tr>
</table>
</div>
</div>
<br><br><br>
<div align=center style="margin-left: 0; margin-right: 0;">
'.PDF_ASSINA.'
</div>
';

$hash_sha256 = hash('sha256', $html);

$html_habitualidade_com_hash = str_replace('<label>hash</label>', '<label style="font-size: 12px">Hash SHA256 do original: '.$hash_sha256.'</label>', $html);

// Carrega seu HTML
$dompdf->load_html($html_habitualidade_com_hash);

//Renderizar o html
	$dompdf->render();
	$documento = base64_encode($dompdf->output());
	
	/* cria documento assinado */
// 	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
//  $aut = new AutentiqueH();
//  $aut->tipo_documento = 'Habitualidade'.' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
//  $aut->posicao_assinatura   = $dompdf->get_canvas()->get_page_number();
// 	$aut->criar_documento($documento, $cliente->matricula, true, array(15, 60, 99), array(70, 84, 1)); // (documento, matricula, exibirAssinaturaClube, posicao_assinatura_membro, posicao_assinatura)
// 	$aut->output();

    require_once($_SERVER['DOCUMENT_ROOT'].'/icpbrasil/icpbrasil.class.php');
    $icp = new icpBrasil();
    $icp->assinar_documento($documento);
    $icp->output();

//Exibibir a página
	$dompdf->stream(
		"habitualidade_todos_os_grupos", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>