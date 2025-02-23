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

// Definição da função formataCPFCNPJ
if (!function_exists('formataCPFCNPJ')) {
    function formataCPFCNPJ($valor) {
        $valor = preg_replace('/[^0-9]/', '', $valor);
        if (strlen($valor) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $valor);
        }
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $valor);
    }
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

$caminho_imagem = 'https://sistema.hostmarq.com.br/assets/img/icp.png';

$html_assinatura_pdf = ('
<p align="center">Natal (RN), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>
<table style="width: 80%; margin: 0 auto; text-align: left; border-collapse: collapse;">
    <tr>
        <td style="width: 40%; padding: 10px;">
            <img src="'.$caminho_imagem.'" width="200" style="display: block;">
        </td>
        <td style="width: 60%; padding: 10px; vertical-align: middle;">
            <p style="font-size: 16px; font-weight: bold; margin: 5px 0;">'.$assinante->nome.'</p>
            <p style="font-size: 14px; font-weight: bold; margin: 5px 0;">'.$assinante->funcao.'</p>
            <p style="font-size: 14px; margin: 5px 0;">CPF: '.formataCPFCNPJ($assinante->cpf).'</p>
            <p style="font-size: 14px; margin: 5px 0;">
                <a href="https://validar.iti.gov.br/" target="_blank" style="text-decoration: none; color:black">
                    https://validar.iti.gov.br
                <a>
            </p>
        </td>
    </tr>
    <tr>
</table>
<label>hash</label>
</div>
');

define("PDF_ASSINA", $html_assinatura_pdf);

?>