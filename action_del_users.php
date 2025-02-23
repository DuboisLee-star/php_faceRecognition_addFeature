<?php

session_start();

include_once("config/conexao_del.php");
include 'config/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);



$conexao = conexao::getInstance();

 $sql1 = " SELECT * FROM tab_membros WHERE id = :id";
  $stm = $conexao->prepare($sql1);
  $stm->bindValue(':id', $id);
  $stm->execute();
  $membro = $stm->fetch(PDO::FETCH_OBJ);
  

  
  
  $sql2 = " SELECT * FROM tab_habitualidade WHERE matricula = :matricula";
  $stm = $conexao->prepare($sql2);
  $stm->bindValue(':matricula', $membro->matricula);
  $stm->execute();
  $habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);
  
    
  $sql3 = " SELECT * FROM tab_armas WHERE id_membro = :id";
  $stm = $conexao->prepare($sql3);
  $stm->bindValue(':id', $membro->id);
  $stm->execute();
  $armas = $stm->fetchAll(PDO::FETCH_OBJ);
  
 
  
if(count($habitualidade)>0 || count($armas)>0){
      echo "<script>
            alert('Não foi possível Excluir. Membro possui histórico no sistema.');
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
          </script>";
    exit;
}else{
    
    if (!empty($id)) {
        
            $conexao = conexao::getInstance();
$sql = 'DELETE FROM tab_membros WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $id);
$stm->execute();
   header('Location: ' . $_SERVER['HTTP_REFERER']); 
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Necessﾃ｡rio selecionar um usuﾃ｡rio</p>";
    header("Location: painel.php");
}


}

