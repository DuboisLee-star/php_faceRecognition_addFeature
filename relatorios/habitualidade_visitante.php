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
include_once ("../config/assinatura1.php");
include_once ("../config/texto.php");

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql1 = 'SELECT * FROM tab_habitualidade WHERE id = :id AND tipo_atirador = 2';
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
    
    // pega dados da habitualidade filtrados por nome_visitante
    $sql3 = "SELECT * FROM tab_habitualidade WHERE matricula = :matricula AND nome_visitante = :nome_visitante AND tipo_atirador = 2 ORDER BY calibre, datacadastro DESC";
    $stm = $conexao->prepare($sql3);
    $stm->bindValue(':matricula', $cliente->matricula);
    $stm->bindValue(':nome_visitante', $cliente->nome_visitante);
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

// HTML para habitualidade
$html_habitualidade = "";

if ($habitualidade) {
    // Agrupa habitualidades por calibre
    $habitualidades_por_calibre = [];
    foreach ($habitualidade as $hab) {
        
        $habitualidades_por_calibre[$hab->calibre][] = $hab;
        
                $tip=$hab->tipo;
                $calib=$hab->calibre;
                $numsig=$hab->numsigma;
                $munic=$hab->qtdemunicoes;
                $tipo_arr= explode(',',$tip);
                $calib_arr=explode(',',$calib);
                $numsig_arr=explode(',', $numsig);
                $munic_arr=explode(',',$munic);
                
                $array_armas=["tipo"=>$tipo_arr, "calibre"=>$calib_arr, "numsigma"=>$numsig_arr, "municao"=>$munic_arr];
                
                
    }
    
    

    // Inicialize o contador de ordem fora do loop
    $ordem = 1;

    // Para cada calibre, criar uma tabela separada no PDF
    foreach ($array_armas[tipo] as $key=> $tip) {
       
        $html_habitualidade .= '
                      <div align="center"><table border="1" cellspacing="0" cellpadding="5" width="100%">
                      <tr><td width="20%" valign="top"  colspan="4"><font size="1">CALIBRE DE USO: <b>' . $array_armas[calibre][$key] . '</b></font></td>
                      <td width="25%" valign="top"><b><font size="1">TIPO DE EVENTO:</font></b> </td></tr>
                      <tr><td align="center" width="8%" bgcolor="#C0C0C0"><font size="1"><b>ORDEM</b></font></td>
                      <td align="center" width="20%" bgcolor="#C0C0C0"><font size="1"><b>DATA</b></font></td>
                      <td align="center" width="15%" bgcolor="#C0C0C0"><font size="1"><b>SIGMA</b></font></td>
                      <td align="center" width="15%" bgcolor="#C0C0C0"><font size="1"><b>QTD MUNIÇÕES</b></font></td>
                      <td align="center" width="42%" bgcolor="#C0C0C0"><font size="1"><b>TREINAMENTO/COMPETIÇÃO</b></font></td></tr>
                      ';
        
        // Itera sobre as habitualidades
        foreach ($habitualidades_por_calibre as $calibre => $hab) {
            
           foreach($hab as $habs){
            
           
            
            $html_habitualidade .='
                <tr>
                
                    <td width="8%" align="center">&nbsp;<font size="1">' . $ordem . '</font></td>
                    <td width="20%" align="center">&nbsp;<font size="1">' . date('d/m/Y', strtotime($habs->datacadastro)) . '</font></td>
                    <td width="15%" align="center">&nbsp;<font size="1">' . $array_armas[numsigma][$key] . '</font></td>
                    <td width="15%" align="center">&nbsp;<font size="1">' . $array_armas[municao][$key] . '</font></td>
                    <td width="42%" align="left">&nbsp;<font size="1">' . strtoupper(utf8_decode($habs->evento)) . '</font></td>
                </tr>
            ';
 }
            $ordem++;
          
}

$html_habitualidade .= '
            <tr>
                <td colspan="5" align="left"><font size="1"><b>Total de Registros:</b> ' . count($habitualidades_por_calibre) . '</font> </td>
            </tr>
        </table>
    </div><br>';
    }
}


$html_habitualidade_registro = '';
$contador = 1;
foreach ($habitualidade as $registro) {
    $html_habitualidade_registro .= '
        <tr>
            <td align="center"><font size="1">' . $contador . '</font></td>
            <td align="center" colspan="3"><font size="1"> HOSTMARQ </font></td>
            <td align="center"><font size="1"> 000' . $registro->id . '</font></td>
            <td align="center"><font size="1">'. ($registro->data ? date('d/m/Y H:i', strtotime($registro->data)) : ''). '</font></td>
        </tr>
    ';
    $contador++;
}


// Carrega seu HTML
$dompdf->load_html(utf8_encode('

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
</div>
<div align="center"><font size="2"><b>COMPROVAÇÃO DE PARTICIPAÇÕES EM TREINAMENTOS E/OU COMPETIÇÕES DE TIRO</b><br>(H A B I T U A L I D A D E)<br><br><b>ANEXO E</b><br>(art. 35 do Decreto nº 11.615/2023)</font><br><br>
<div align="center"><font size="2">DADOS DA ENTIDADE DE TIRO DECLARANTE</font></div>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Nome:</b></font></td>
        <td width="50%" valign="top"><font size="2">'.strtoupper(utf8_decode($clube->clube_nome)).'</font></td>
        <td width="25%" valign="top"><font size="2"><b>CNPJ:</b></font><font size="2"> '.$clube->clube_cnpj.'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Certificado de Registro</b></font></td>
        <td width="50%" valign="top"><font size="2">'.$clube->clube_cr.'</font></td>
        <td width="25%" valign="top"><font size="2"><b>Data:</b></font><font size="2"> '. ($clube->clube_validade_cr ? date('d/m/Y', strtotime($clube->clube_validade_cr)) : ''). '</font></td>    </tr>
    <tr>
        <td width="30%" valign="top"><font size="2"><b>Endereço</b></font></td>
        <td width="75%" colspan="2" valign="top"><font size="2">'.strtoupper(utf8_decode($clube->clube_endereco)).'</font></td>
    </tr>
</table>
<br>
<div align="center"><font size="2">DADOS DO ATIRADOR DESPORTIVO</font></div>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</font></b></td>
        <td width="50%" valign="top"><font size="2">'.strtoupper(utf8_decode($cliente->nome_visitante)).'</font></td>
        <td width="25%" valign="top"><b><font size="2">CPF:</b></font><font size="2"> '.formataCPFCNPJ($cliente->cpf_visitante).'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</font></b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->cr_visitante.'</font></td>
        <td width="25%" valign="top"><b><font size="2">Data:</b></font><font size="2"> '. ($cliente->cr_visitante_validade ? date('d/m/Y', strtotime($cliente->cr_visitante_validade)) : ''). '</font></td>    </tr>
    </tr>
</table>
<br>
'.$html_habitualidade.'
<br>
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
<br><br><br>
<div align=center style="margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'
</div>
'));

	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"habitualidade_por_calibre", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>