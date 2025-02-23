<?php

require_once('conexao.php');

$conexao = conexao::getInstance();
$sql = 'SELECT
            a.*
        FROM
            tab_autentique a,
            info_clube b
        WHERE
            a.id = b.id_autentique';
$stm = $conexao->prepare($sql);
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

$html_desfilia_pdf = utf8_decode('

'.date('d').' de '.$meses[date('m')].' de '.date('Y').'

');

define("PDF_DATADESFILIA", $html_desfilia_pdf);
?>