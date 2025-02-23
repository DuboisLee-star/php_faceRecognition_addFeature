<?php

include "config/config.php";
require 'config/url.php';

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}
require 'config/conexao.php';
require 'config/assinatura_recibo.php';
require 'config/url_painel.php';
require 'config/url.php';

ini_set('display_errors', true);
error_reporting(-1);
function valorPorExtenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
$u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

$z = 0;

$valor = number_format($valor, 2, ".", ".");
$inteiro = explode(".", $valor);
$fim = count($inteiro);

$rt = "";
for ($i = 0; $i < $fim; $i++) {
    $valor = str_pad($inteiro[$i], 3, "0", STR_PAD_LEFT);
    $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
    $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
    $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
    
    $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
    $t = count($inteiro) - 1 - $i;
    $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
    if ($valor == "000") {
        $z++;
    } elseif ($z > 0) {
        $z--;
    }
    if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
        $r .= (($z > 1) ? " de " : "") . $plural[$t];
    }
    if ($r) {
        $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim - 1) ? ", " : " e ") : " ") . $r;
    }
}

	
return $rt ? ucfirst($rt) : "zero reais"; 
}
function dataPorExtenso($data) {
  // separa a data em dia, mês e ano
  $dataArr = explode('/', $data);
  $dia = (int)$dataArr[0];
  $mes = (int)$dataArr[1];
  $ano = (int)$dataArr[2];

  // define os nomes dos meses e dias da semana
  $meses = array("", "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
  $dias = array("domingo", "segunda-feira", "terça-feira", "quarta-feira", "quinta-feira", "sexta-feira", "sábado");

  // obtém o nome do mês e do dia da semana
  $mesNome = $meses[$mes];
  $diaSemana = $dias[date('w', strtotime($data))];

  // retorna a data por extenso
  return $dia . ' de ' . $mesNome . ' de ' . $ano;
}


// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$valor_plano = isset($_POST['valor_plano']) ? $_POST['valor_plano'] : '';
$financeiro = isset($_POST['financeiro']) ? $_POST['financeiro'] : '';
$mes_competencia = isset($_POST['mes_competencia']) ? $_POST['mes_competencia'] : '';
$ano_competencia = isset($_POST['ano_competencia']) ? $_POST['ano_competencia'] : '';
$forma_pgto = isset($_POST['forma_pgto']) ? $_POST['forma_pgto'] : '';
$referente_a = isset($_POST['referente_a']) ? $_POST['referente_a'] : '';

if($id_cliente == "" || $tipo == ""){
	exit("<script>alert('Dados inválidos.'); window.location='painel.php';</script>");
}


// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);

	if(empty($cliente)):
		exit("<script>alert('Registro não localizado.'); window.location='painel.php';</script>");
	endif;

endif;

$numero = date('Ym').str_pad($cliente->id, 5, '0', STR_PAD_LEFT);

// configurações do clube
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$config = $stm->fetch(PDO::FETCH_OBJ);

//referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// include autoloader
require_once("relatorios/dompdf/autoload.inc.php");

//Criando a Instancia
$dompdf = new DOMPDF(array('enable_remote' => true));


if($tipo == "A"){
	$valor_plano = str_replace(',', '.', str_replace('.', '', $valor_plano));
}else{
	
	if($config->plano == "semplano"){
		exit("<script>alert('Nenhum plano configurado.'); window.location='painel.php';</script>");
	}else
	if($config->plano == "personalizado"){
		$valor_plano = $config->valor_plano_personalizado;
	}else
	if($config->plano == "fixo"){
		$valor_plano = $config->valor_plano_fixo;
	}else
	if($config->plano == "todos"){
		
		switch(substr($cliente->plano,0,1)){
			case "O":
				$valor_plano = $config->valor_plano_ouro;
			break;
			case "P":
				$valor_plano = $config->valor_plano_prata;
			break;
			case "B":
				$valor_plano = $config->valor_plano_bronze;
			break;
			default:
				$valor_plano = "0.00";
			break;
		}
		
		
	}
}




if($cliente->plano_pgto == "A"){
	$data_inicio = date('Y-m-d');
	$data = new DateTime(date('Y-m-d'));
	$data->add(new DateInterval('P1Y'));
	$data_renovacao = $data->format('Y-m-d');
}else{
	$data_inicio = $ano_competencia.'-'.$mes_competencia.'-01';
	$data = new DateTime($ano_competencia.'-'.$mes_competencia.'-01');
	$data->add(new DateInterval('P1M'));
	$data_renovacao = "";
}
$data_fim = $data->format('d/m/Y');



$cabecalho = "<div style='border-bottom:1px dashed #000000 !important; padding: 0 0 15px 0; text-align: center; margin-bottom: 15px;'>{$config->clube_nome} - CNPJ: {$config->clube_cnpj}<br>
{$config->clube_endereco}</div>";
$html_recibo = $cabecalho."
<table width='100%'>
	<tr>
		<td align='left' valign='middle'><img alt='Logo' style='height: 90px;' src='{$url_site}img/logo_site_black.png' height='50'></td>
		<td align='right' valign='middle'><strong>Nº {$numero}</strong></td>
	</tr>
</table><br>

<div align=justify>Recebemos de <b>{$cliente->nome}</b>, CPF nº: <b>{$cliente->cpf}</b>, a importância no valor de <b>R$ ".number_format($valor_plano,2,',','.')." (".valorPorExtenso($valor_plano)." )</b>, referente ao pagamento de <b>{$referente_a}</b>.<br>
<br>
<b>Valor pago ao clube:</b> (R$ ".number_format($valor_plano,2,',','.').")<br>
<b>Forma de pgto:</b> {$forma_pgto}
<br><br>
Para maior clareza, firmamos o presente, dando plena e total quitação.<br>
<br>
<div style='text-align: center'>
".PDF_ASSINA."<p>
</div>
";

$dompdf->load_html("<style>*{font-size: 13px; font-family: sans-serif;}</style>".$html_recibo.'<br><br><br><br>'.$html_recibo);

//Renderizar o html
$dompdf->render();

// da baixa no financeiro
if($financeiro == "F"){
	
	// verifica se já existe registro
	$conexao = conexao::getInstance();
	$sql = 'SELECT COUNT(id) qtde FROM tab_financeiro WHERE matricula = :matricula';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->execute();
	$existe = $stm->fetch(PDO::FETCH_OBJ);
	
	$meses = array("janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
	switch($cliente->plano_pgto){
		case 'A':
			// $campo_tabela = "anuidade".date('Y');
			$campo_tabela = "anuidade".$ano_competencia;
		break;
		case 'M':
			// $campo_tabela = "mens_".substr($meses[((int)date('m'))-1],0,3).date('Y');
			$campo_tabela = "mens_".substr($meses[(int)$mes_competencia-1],0,3).$ano_competencia;
		break;
	}
	
	// atualiza a data da renovação caso plano seja anual
	if($cliente->plano_pgto == 'A'){
		$sql = "
			UPDATE
				tab_membros
			SET
				data_renovacao = :data_renovacao
			WHERE
				matricula = :matricula
		";
		$conexao = conexao::getInstance();
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':data_renovacao', $data_renovacao);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->execute();
	}
	
	if($existe->qtde > 0){
		
		$sql = "
			UPDATE 
				tab_financeiro 
			SET 
				{$campo_tabela} = :campo_tabela
			WHERE
				matricula = :matricula
		";
		$conexao = conexao::getInstance();
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->bindValue(':campo_tabela', $valor_plano);
		
		$retorno = $stm->execute();
		
	}else{

		$sql = " INSERT INTO tab_financeiro
			(
				matricula,
				{$campo_tabela}
			) VALUES (
				:matricula,
				:campo_tabela
			)
		";
		$conexao = conexao::getInstance();
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->bindValue(':campo_tabela', $valor_plano);
		
		$retorno = $stm->execute();
		
	}

}

	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());

	/* cria documento assinado */
	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
    $aut = new AutentiqueH();
    $aut->tipo_documento = 'Recibo'.' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
    $aut->posicao_assinatura   = $dompdf->get_canvas()->get_page_number();
	$aut->criar_documento($documento, $cliente->matricula, true, array(98, 97), array(68, 87)); // (documento, matricula, exibirAssinaturaClube, posicao_assinatura_membro, posicao_assinatura)
	$aut->output();
	
	//Exibibir a página
	$dompdf->stream(
		"recibo", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>