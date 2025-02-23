<?php
include 'config/conexao.php';

$id_arma = $_GET['id'];

$conexao = conexao::getInstance();
  $sql2 = " SELECT * FROM tab_habitualidade WHERE arma_id = :id";
  $stm = $conexao->prepare($sql2);
  $stm->bindValue(':id', $id_arma);
  $stm->execute();
  $habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);
  
if(count($habitualidade)>0){
      echo "<script>
            alert('Não foi possível excluir. Arma possui histórico de habitualidade.');
            window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
          </script>";
    exit;
    
}else{
   
     // Consulta os dados da arma
    $sql_arma = 'SELECT id, numsigma, id_grupo, tipo, modelo, calibre, validade_gt, validade_craf FROM tab_armas WHERE id = :id';
    $stm_arma = $conexao->prepare($sql_arma);
    $stm_arma->bindValue(':id', $id_arma);
    $stm_arma->execute();
    $arma = $stm_arma->fetch(PDO::FETCH_OBJ);
    $user              = (isset($_GET['user'])) ? $_GET['user'] : '';
    
    //Dados do operador    
           $conexao = conexao::getInstance();
            $sql_user = "SELECT * FROM users WHERE username = :name";
            $stm = $conexao->prepare($sql_user);
            
            $stm->bindParam(':name', $user, PDO::PARAM_STR);
            
            $stm->execute();
            
            $usuario = $stm->fetch(PDO::FETCH_OBJ);
            
            //inserção do LOg
            
             $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
            			   VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';

			$stm = $conexao->prepare($sql_log);
			$stm->bindValue(':username', $usuario->name);
			$stm->bindValue(':tabela', 'tab_armas');
			$stm->bindValue(':tipo_alteracao', 'exclusao');
				$stm->bindValue(':registro_id', $arma->id);
		  // Converte o array para JSON
            $alteracao = json_encode(['Exclusao de Arma: ' =>'', 'tipo'=> $arma->tipo, 'modelo'=> $arma->modelo, 'calibre'=>$arma->calibre, 'numsigma'=>$arma->numsigma]);
            $stm->bindValue(':registro', $alteracao);
         
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();
    
$conexao = conexao::getInstance();
$sql = 'DELETE FROM tab_armas WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $id_arma);
$stm->execute();

header('Location: ' . $_SERVER['HTTP_REFERER']); 
}
  
?>