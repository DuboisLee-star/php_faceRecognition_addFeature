<?php

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

require_once('conexao.php');

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql1 = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql1);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);

$conexao = conexao::getInstance();
$sql2 = 'SELECT
            a.*
        FROM
            tab_autentique a,
            info_clube b
        WHERE
            a.id = b.id_autentique';

$stm = $conexao->prepare($sql2);
$stm->execute();
$assinante = $stm->fetch(PDO::FETCH_OBJ);

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

$html_assinatura_pdf = '
<div align="center" style="line-height: 110%; margin-left: 0; margin-right: 0">
<br>
 Natal (RN), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>
    
<!-- Requerente -->
<div style="width:50%; float: left; text-align:center;">
    <font size="2">REQUERENTE<br><b>'.$cliente->nome.'</b><br>CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>

<!-- Assinante -->
<div style="width:50%; float: left; text-align:center;">
    <font size="2">'.$assinante->funcao.'<br><b>'.$assinante->nome.'</b><br>CPF: '.formataCPFCNPJ($assinante->cpf).' 
</div>

';

define("PDF_ASSINA", $html_assinatura_pdf);
?>