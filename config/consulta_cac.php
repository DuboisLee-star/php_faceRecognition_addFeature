<?php

require_once('config/conexao.php');

$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_membros ORDER BY nome ASC';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $id_cliente);
$stm->execute();
$atiradores = $stm->fetchAll(PDO::FETCH_OBJ);

?>