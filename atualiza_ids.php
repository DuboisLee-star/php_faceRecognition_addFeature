<?php

include "config/config.php";
require 'config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

$conexao = conexao::getInstance();

// Busca todas as armas
$sql_arma = 'SELECT * FROM tab_armas';
$stm_arma = $conexao->prepare($sql_arma);
$stm_arma->execute();
$armas = $stm_arma->fetchAll(PDO::FETCH_OBJ);

// Para cada arma, atualiza a tab_habitualidades
foreach ($armas as $arma) {
    // Atualiza o campo arma_id na tab_habitualidades com base no numsigma
    $sql_update = 'UPDATE tab_habitualidade SET arma_id = :arma_id, modelo= :modelo, calibre= :calibre WHERE numsigma = :numsigma';
    $stm_update = $conexao->prepare($sql_update);

    $stm_update->bindParam(':arma_id', $arma->id);
    $stm_update->bindParam(':numsigma', $arma->numsigma);
    $stm_update->bindParam(':modelo', $arma->modelo);
    $stm_update->bindParam(':calibre', $arma->calibre);

    $stm_update->execute();
    echo "Arma ".$arma->tipo.' '.$arma->calibre.' Atualizada <br>';
}

echo "Atualização concluída!";
sleep(3);

        // Redireciona após a atualização
        header('Location: armas.php?id=' . $membro->id);
        exit();
 
?>