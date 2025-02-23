<?php
header('Content-Type: application/json');
include "config/config.php";
require 'config/conexao.php';

if (isset($_POST['id_produto'])) {
    $idproduto = $_POST['id_produto'];
   
$conexao = conexao::getInstance();
    // Consulta SQL
    $sql = "SELECT id, produto_servico, valor_unitario, data_cadastro FROM tab_precos WHERE id = :id";
    $stm = $conexao->prepare($sql);
    $stm->bindParam(':id', $idproduto);
    $stm->execute();
    
    $preco = $stm->fetch(PDO::FETCH_OBJ);
    
    echo json_encode(['valor'=>$preco->valor_unitario,'nomeProduto'=>$preco->produto_servico]);
    
}
?>