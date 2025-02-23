<?php

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
	
	function formataCPFCNPJ($cpfCnpj) {
    // Implemente a lógica para formatar o CPF/CNPJ conforme necessário
    // Por exemplo:
    return substr($cpfCnpj, 0, 3) . '.' . substr($cpfCnpj, 3, 3) . '.' . substr($cpfCnpj, 6, 3) . '-' . substr($cpfCnpj, 9, 2);
}

require_once('config/conexao.php');

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql1 = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql1);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$assinante = $stm->fetch(PDO::FETCH_OBJ);

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

$html_assinatura_pdf = ('
<p align="center">Rondon&oacute;polis (MT), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<!-- Requerente -->
<br><b>
'.$assinante->nome.'</b><br>
'.$assinante->funcao.'</b><br>
CPF: '.formataCPFCNPJ($assinante->cpf).'
</div>

');

define("PDF_ASSINA", $html_assinatura_pdf);

?>