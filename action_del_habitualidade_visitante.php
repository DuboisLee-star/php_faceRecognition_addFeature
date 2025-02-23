<?php

include_once("config/conexao.php");

$conexao = conexao::getInstance();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    
    $sql = 'DELETE FROM tab_habitualidade WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stm->execute()) {
        
        header("Location: painel_visitantes.php");
    } else {
         
        header("Location: painel_visitantes.php");
    }
} else {
    
    header("Location: painel_visitantes.php");
}
?>