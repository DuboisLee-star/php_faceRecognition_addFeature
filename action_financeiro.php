<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Sistemas para Clubes de Tiro</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<div class='container box-mensagem-crud'>
		<?php 
		require 'config/conexao.php';

		// Atribui uma conex�o PDO
		$conexao = conexao::getInstance();
		
	

		// Recebe os dados enviados pela submiss�o
        $acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$matricula         = (isset($_POST['matricula'])) ? $_POST['matricula'] : '';		

		$valor  = (isset($_POST['valor'])) ? $_POST['valor'] : '';
		$data= (isset($_POST['data'])) ? $_POST['data'] : '';
		$forma_pgto= (isset($_POST['forma_pgto'])) ? $_POST['forma_pgto'] : '';
		$obs = (isset($_POST['obs'])) ? $_POST['obs'] : '';
		$plano = (isset($_POST['plano'])) ? $_POST['plano'] : '';
		$status_pgto = (isset($_POST['status_pgto'])) ? $_POST['status_pgto'] : '';
		$parcela = (isset($_POST['n_parcela'])) ? $_POST['n_parcela'] : '';
		$id_membro = (isset($_POST['id_membro'])) ? $_POST['id_membro'] : '';
		$valor_pago=(isset($_POST['valor_pago'])) ? $_POST['valor_pago'] : '';
		
		
	
		
		// verifica se já existe registro
    	$conexao = conexao::getInstance();
    	$sql = 'SELECT COUNT(id) qtde FROM tab_financeiro_2 WHERE id = :id';
    	$stm = $conexao->prepare($sql);
    	$stm->bindValue(':id', $id);
    	$stm->execute();
    	$existe = $stm->fetch(PDO::FETCH_OBJ);
    	if($existe->qtde <= 0) $acao = 'incluir';
 
		if ($acao == 'incluir'):
		    
		       	$conexao = conexao::getInstance();
    	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
    	$stm = $conexao->prepare($sql);
    	$stm->bindValue(':id', $id_membro);
    	$stm->execute();
    	$membro = $stm->fetch(PDO::FETCH_OBJ);
    	
		    
		    if($parcela > 1){
		        $valor_parcelado= $valor/$parcela;
		        $data_pagamento=new DateTime($data);
		        
		       
		      
		        
		        
		        for($i=1;$i<= $parcela; $i++){
		            $sql = 'INSERT INTO tab_financeiro_2 (id_membro, matricula, valor, plano, n_parcela, data_pgto, forma_pgto, status_pgto, obs)
			                             VALUES(:id_membro, :matricula,:valor, :plano, :n_parcela, :data, :forma_pgto, :status_pgto, :obs)';
  
  $datac=clone $data_pagamento;
  
  $datac->modify("+$i month");
 
  

   
     
			$stm = $conexao->prepare($sql);
			
			//$stm->bindValue(':id', $id);
			$stm->bindValue(':id_membro', $id_membro);
			$stm->bindValue(':matricula', $membro->matricula);
			
			$stm->bindValue(':valor', $valor_parcelado);
			$stm->bindValue(':plano', $membro->plano_pgto);
			$stm->bindValue(':n_parcela', $parcela);
			
			$stm->bindValue(':data', $datac->format('Y-m-d'));			
			$stm->bindValue(':forma_pgto', $forma_pgto);			
			$stm->bindValue(':obs', $obs);			
			$stm->bindValue(':status_pgto', $status_pgto);
		
            $retorno = $stm->execute();
           

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
	
		        
		    }
		            
		        }else{
		            
		                        $sql = 'INSERT INTO tab_financeiro_2 (id_membro, matricula, valor, plano, n_parcela, data_pgto, forma_pgto, status_pgto, obs)
			                             VALUES(:id_membro, :matricula,:valor, :plano, :n_parcela, :data, :forma_pgto, :status_pgto, :obs)';

			$stm = $conexao->prepare($sql);
			
			//$stm->bindValue(':id', $id);
			$stm->bindValue(':id_membro', $id_membro);
			$stm->bindValue(':matricula', $membro->matricula);
			
			$stm->bindValue(':valor', $valor_parcelado);
			$stm->bindValue(':plano', $membro->plano_pgto);
			$stm->bindValue(':n_parcela', $parcela);
			
			$stm->bindValue(':data', $data);			
			$stm->bindValue(':forma_pgto', $forma_pgto);			
			$stm->bindValue(':obs', $obs);			
			$stm->bindValue(':status_pgto', $status_pgto);
		
            $retorno = $stm->execute();
           

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		        }
		        
		         
		    
		 
    
   	endif;
   
           


		// Verifica se foi solicitada a edi��o de dados
		if ($acao == 'editar'):

			$sql = 'UPDATE tab_financeiro_2 SET   valor_pago= :valor_pago, data_pgto=:data,  forma_pgto=:forma_pgto,  obs=:obs, status_pgto=:status_pgto where id= :id';

	

			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':data', $data);			
			$stm->bindValue(':forma_pgto', $forma_pgto);			
			$stm->bindValue(':obs', $obs);	
			$stm->bindValue(':valor_pago', $valor_pago);	
			$stm->bindValue(':status_pgto', $status_pgto);			
		
			$stm->bindValue(':obs', $obs);	         
            
			$retorno = $stm->execute();
			

 
			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		endif;



			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		
		?>

	</div>
</body>
</html>