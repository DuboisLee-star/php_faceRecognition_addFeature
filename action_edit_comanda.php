<?php
include "config/config.php";
require 'config/conexao.php';

$conexao = conexao::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $numero_comanda = $_POST['numero_comanda'];
    $produtos = $_POST['produtos'];
    $qtde = $_POST['quantidade'];
    $valor = $_POST['valor'];
    $nome=$_POST['nome_comanda'];
    
    $status = $_POST['status'];


//   foreach($produtos as $prod){
       
//       echo $prod['produto']."<br>";
       
//       echo $prod['idproduto'];
//   }
    
//     exit;
    
    
 $total=0;
 
 
 
 foreach($produtos as $prodc){
     
     $valProd=$prodc['quantidade']*$prodc['valor'];
     
     
     $total+=$valProd;
     
     
 }
 


    $sql = 'UPDATE tab_comanda SET  nome=:nome, total = :total, status= :status WHERE id = :id';
    $stm = $conexao->prepare($sql);

    $stm->bindParam(':id', $id);
    $stm->bindParam(':status', $status);
    $stm->bindParam(':total', $total);
    $stm->bindParam(':nome', $nome);
    $stm->execute();

   
   
    foreach($produtos as $prod){
        
         $sql2 = 'UPDATE tab_comanda_itens SET  produto = :produto, quantidade= :quantidade, valor= :valor WHERE id = :id';
    
    $stm2 = $conexao->prepare($sql2);
    
        $stm2->bindParam(':id', $prod['idproduto']);
    $stm2->bindParam(':produto', $prod['produto']);
    $stm2->bindParam(':quantidade', $prod['quantidade']);
    $stm2->bindParam(':valor', $prod['valor']);
        $stm2->execute();
    }
   

 if ($stm2->execute()) {
        header('Location: comanda.php');
        exit();
    } else {
        echo 'Erro ao atualizar a comanda.';
    }
    
    
}
?>