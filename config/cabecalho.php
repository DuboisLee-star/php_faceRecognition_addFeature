<?php

require 'conexao.php';

// Faz a conexão com o banco
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);	

// Carregar a imagem do logo e codificar em base64
$logoPath = $_SERVER['DOCUMENT_ROOT'].'/img/logo_site_black.png';
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$data = file_get_contents($logoPath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$html_cabecalho_pdf = utf8_decode('

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15"></div>
<div align=center><img src="' . $base64 . '" height="100" id="foto-cliente"><br>
<div align=center><font size="3"><b>'.utf8_encode($clube->clube_nome).'</b><br></div>
<div align=center><font size="3">CNPJ: '.utf8_decode($clube->clube_cnpj).'<br></div>
<div align=center><font size="2">'.utf8_encode($clube->clube_endereco).'</font><br></div>
<div align=center><font size="2">N&#176; CR: '.utf8_decode($clube->clube_cr).' -  Telefone(s): '.utf8_decode($clube->clube_telefone).'</font><br></div>
<div align=center><font size="2">Email: '.utf8_decode($clube->clube_email).'</font><br><br></div>

');

//echo $html_cabecalho_pdf;
//exit();
define("PDF_CABECALHO", $html_cabecalho_pdf);
?>