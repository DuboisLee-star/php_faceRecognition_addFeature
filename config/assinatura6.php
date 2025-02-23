<?php

// Recebe o id do cliente via GET
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
$sql2 = 'SELECT a.* FROM tab_autentique a, info_clube b WHERE a.id = b.id_autentique';
$stm = $conexao->prepare($sql2);
$stm->execute();
$assinante = $stm->fetch(PDO::FETCH_OBJ);

// Define os meses do ano em português
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

// Função para formatar CPF ou CNPJ
function formataCPFCNPJ($valor) {
    // Remove caracteres especiais (pontos, traços, etc.)
    $valor = preg_replace('/[^0-9]/', '', $valor);

    // Verifica se o valor tem 11 dígitos (CPF) ou 14 dígitos (CNPJ)
    if (strlen($valor) === 11) {
        // Formata como CPF: xxx.xxx.xxx-xx
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $valor);
    } elseif (strlen($valor) === 14) {
        // Formata como CNPJ: xx.xxx.xxx/xxxx-xx
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $valor);
    } else {
        // Retorna o valor sem formatação se não for CPF ou CNPJ válido
        return $valor;
    }
}

// Gera o conteúdo HTML para a assinatura em PDF
$html_assinatura_pdf = '
<div align="center" style="line-height: 110%; margin-left: 0; margin-right: 0">
<br>
   Natal (RN), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br><br><br>
    
<!-- Requerente -->
<div style="width:100%; float: left; text-align:center;">
    <font size="2">____________________________________________<br>REQUERENTE<br><b>'.strtoupper(utf8_decode($cliente->nome)).'</b><br>CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>
';

// Define o conteúdo HTML como constante para PDF
define("PDF_ASSINA", $html_assinatura_pdf);

?>