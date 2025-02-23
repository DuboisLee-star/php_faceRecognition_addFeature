<?php
if(!isset($_SESSION)){
session_start();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>CTC</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<div class='container box-mensagem-crud'>

        <?php 
		$atirador = true;
		require 'config/conexao.php';
		
		// ini_set('display_errros', true);
		// error_reporting(-1);
		
		
		// echo '<pre>';
		// print_r($_POST);
		

		// Atribui uma conexao PDO
		$conexao = conexao::getInstance();
		
		$act = (isset($_POST['act'])) ? $_POST['act'] : false;
		$matricula = (isset($_POST['matricula'])) ? $_POST['matricula'] : false;
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$id_habitualidade_del = (isset($_POST['id_habitualidade_del'])) ? $_POST['id_habitualidade_del'] : '';
			$user= (isset($_POST['user'])) ? $_POST['user'] : '';
		if($act == 'aprova_habitualidade' && $matricula){
		    
		    $sql = " UPDATE tab_habitualidade SET aprovado=:aprovado, data_aprovacao=:data_aprovacao WHERE matricula=:matricula AND IFNULL(aprovado,0) = 0 ";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_aprovacao', date('Y-m-d H:i'));		
			$stm->bindValue(':aprovado', 1);
			$retorno = $stm->execute();
		    
		    echo '<script>alert("Aprovação realizada com sucesso.");window.location="/habitualidade.php?id='.$id.'";</script>';
		    exit();
		}
		if($act == 'excluir_habitualidade' && $matricula && $id_habitualidade_del){
		 	$user= (isset($_POST['user'])) ? $_POST['user'] : '';
		    			    				    $conexao = conexao::getInstance();
		    			    				    
		    $sql_habitualidade = "SELECT * FROM tab_habitualidade WHERE id = :id";
            $stm = $conexao->prepare($sql_habitualidade);
            
            $stm->bindParam(':id', $id_habitualidade_del);
            
            $stm->execute();
            
            $habitualidade_apagada = $stm->fetch(PDO::FETCH_OBJ);
            
            $sql_user = "SELECT * FROM users WHERE username = :name";
            $stm = $conexao->prepare($sql_user);
            
            $stm->bindParam(':name', $user, PDO::PARAM_STR);
            
            $stm->execute();
            
            $usuario = $stm->fetch(PDO::FETCH_OBJ);

         $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
			   VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';

			$stm = $conexao->prepare($sql_log);
			$stm->bindValue(':username', $usuario->name);
			$stm->bindValue(':tabela', 'tab_habitualidade');
			$stm->bindValue(':tipo_alteracao', 'exclusao');
				$stm->bindValue(':registro_id', $id_habitualidade_del);
		  // Converte o array para JSON
            $alteracao = json_encode(['Exclusao de habitualidade' =>'-','id'=>$id_habitualidade_del, 'matricula'=>$habitualidade_apagada->matricula]);
            $stm->bindValue(':registro', $alteracao);
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();
		    
		    $sql = " DELETE FROM tab_habitualidade WHERE id = :id ";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id_habitualidade_del);
			$retorno = $stm->execute();
		    
		    echo '<script>alert("Registro excluido com sucesso.");window.location="/habitualidade.php?id='.$id.'";</script>';
		    exit();
		}
		

		// Recebe os dados enviados pela submissão
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$matricula         = (isset($_POST['matricula'])) ? $_POST['matricula'] : false;

		$foto_atual	   = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';
		
		$aprovado    = (isset($_POST['aprovado'])) ? $_POST['aprovado'] : '';
		$id_habitualidade    = (isset($_POST['id_habitualidade'])) ? $_POST['id_habitualidade'] : '';
		$data_aprovacao    = (isset($_POST['data_aprovacao'])) ? $_POST['data_aprovacao'] : '';

		$habitu_data = (isset($_POST['habitu_data'])) ? $_POST['habitu_data'] : false;
		$habitu_local = (isset($_POST['habitu_local'])) ? $_POST['habitu_local'] : false;
		$habitu_evento = (isset($_POST['habitu_evento'])) ? $_POST['habitu_evento'] : false;
		$tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : false;
        $modelo = (isset($_POST['modelo'])) ? $_POST['modelo'] : false;
		$habitu_qtdemunicoes = (isset($_POST['habitu_qtdemunicoes'])) ? $_POST['habitu_qtdemunicoes'] : false;
		$habitu_livro = (isset($_POST['habitu_livro'])) ? $_POST['habitu_livro'] : false;

		$manual_tipo = (isset($_POST['manual_tipo'])) ? $_POST['manual_tipo'] : false;
		$manual_modelo = (isset($_POST['manual_modelo'])) ? $_POST['manual_modelo'] : false;
		$manual_calibre = (isset($_POST['manual_calibre'])) ? $_POST['manual_calibre'] : false;
		$manual_sigma = (isset($_POST['manual_sigma'])) ? $_POST['manual_sigma'] : false;
		
		// echo '<pre>';
		// print_r($_POST);
		// exit();

		// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do registros desconhecido.</li>';
	    endif;


                // Verifica se foi solicitada a edição de dados
		if ($acao == 'editar'):
			
			/*
			$sql = 'DELETE FROM tab_habitualidade WHERE matricula = :matricula';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':matricula', $matricula);
			$retorno = $stm->execute();
			*/
			
			// grava habilutalidade
			if(is_array($habitu_local)){
				foreach($habitu_local as $key => $value){
					
					$id_linha = $_POST['id_linha'][$key];
					
					if($matricula){
					    
						try {
							$datacadastro = DateTime::createFromFormat ("d/m/Y H:i", $habitu_data[$key]);
							// var_dump($datacadastro);
							if($datacadastro){
								$datacadastro = $datacadastro->format( "Y-m-d H:i" );
							}else{
								$datacadastro = date('Y-m-d H:i');
							}
						} catch (Exception $e) {
							$datacadastro = date('Y-m-d H:i');
						}
						


						$digitado = 0;
						$id_linha=0;
						if(!isset($_POST['tipo_'.$id_linha][0])){
							
							$indice_tipo = 'manual_tipo_'.$id_linha;
							$indice_modelo = 'manual_modelo_'.$id_linha;
							$indice_calibre = 'manual_calibre_'.$id_linha;
							$indice_sigma = 'manual_sigma_'.$id_linha;
							$indice_id='manual_id_'.$id_linha;
							
							$habitu_tipo = array(
								0 => (isset($_POST[$indice_tipo][0]) ? trim($_POST[$indice_tipo][0]) : ''),
								1 => (isset($_POST[$indice_modelo][0]) ? trim($_POST[$indice_modelo][0]) : ''),
								2 => (isset($_POST[$indice_calibre][0]) ? trim($_POST[$indice_calibre][0]) : ''),
								3 => (isset($_POST[$indice_sigma][0]) ? trim($_POST[$indice_sigma][0]) : ''),
								4 => (isset($_POST[$indice_id][0]) ? trim($_POST[$indice_id][0]) : '')
							
							);
							
							$digitado = 1;
							
						}else{
							$array_tipo = $_POST['tipo_'.$id_linha][0];
							$habitu_tipo = explode('|',$array_tipo);
						}
						
						
						// verifica aprova��o
						if(strlen(trim($aprovado[$key])) <= 0){
					        $aprovado[$key] = 1;
					        $data_aprovacao[$key] = date('Y-m-d H:i');
						}

						
						// print_r($habitu_tipo);
						
						if(strlen(trim($id_habitualidade)) == null || $id_habitualidade =='' ){
			
						    
						    $conexao = conexao::getInstance();
            
            $sql_user = "SELECT * FROM users WHERE username = :name";
            $stm = $conexao->prepare($sql_user);
            
            $stm->bindParam(':name', $user, PDO::PARAM_STR);
            
            $stm->execute();
            
            $usuario = $stm->fetch(PDO::FETCH_OBJ);

         $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro, created_at)
			   VALUES(:username, :tabela, :tipo_alteracao, :registro, :data)';

			$stm = $conexao->prepare($sql_log);
			$stm->bindValue(':username', $usuario->name);
			$stm->bindValue(':tabela', 'tab_habitualidade');
			$stm->bindValue(':tipo_alteracao', 'criacao');
		  // Converte o array para JSON
            $alteracao = json_encode(['Criacao de habitualidade' =>'','matricula'=>$matricula]);
            $stm->bindValue(':registro', $alteracao);
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();
            
            $sql_arma = "SELECT * FROM tab_armas WHERE id = :armaId";
            $stm = $conexao->prepare($sql_arma);
            
            	$stm->bindValue(':armaId', (isset($habitu_tipo[3]) ? $habitu_tipo[3] : ''));
            
            $stm->execute();
            
            $arma = $stm->fetch(PDO::FETCH_OBJ);
            
                    echo "Municao: ". $_Post['origem_municao_'];
                    exit;
						    
    						$sql = " INSERT INTO tab_habitualidade (matricula, id_grupo, data, local, evento, tipo, modelo, calibre, numsigma, arma_id, qtdemunicoes, livro, datacadastro, municao, tipomunicao, digitado, aprovado, data_aprovacao) VALUES (:matricula, :id_grupo, :data, :local, :evento, :tipo, :modelo, :calibre, :sigma,:armaId, :qtdemunicoes, :livro, :datacadastro, :municao, :tipomunicao, :digitado, :aprovado, :data_aprovacao) ";
    						$stm = $conexao->prepare($sql);
    						$stm->bindValue(':matricula', $matricula);
    							$stm->bindValue(':id_grupo', $arma->id_grupo);
    						$stm->bindValue(':data', date('Y-m-d', strtotime($datacadastro)));
    						$stm->bindValue(':local', $habitu_local[$key]);
    						$stm->bindValue(':evento', $habitu_evento[$key]);
    						$stm->bindValue(':tipo', (isset($habitu_tipo[0]) ? $habitu_tipo[0] : ''));
    						$stm->bindValue(':modelo', (isset($habitu_tipo[1]) ? $habitu_tipo[1] : ''));
    						$stm->bindValue(':calibre', (isset($habitu_tipo[2]) ? $habitu_tipo[2] : ''));
    						$stm->bindValue(':sigma', (isset($habitu_tipo[3]) ? $habitu_tipo[3] : ''));
    						$stm->bindValue(':armaId', (isset($habitu_tipo[4]) ? $habitu_tipo[4] : ''));
    						$stm->bindValue(':qtdemunicoes', $habitu_qtdemunicoes[$key]);
    						$stm->bindValue(':livro', $habitu_livro[$key]);
    						$stm->bindValue(':datacadastro', $datacadastro);
    						$stm->bindValue(':digitado', $digitado);
    						
    						$stm->bindValue(':aprovado', (isset($aprovado[$key]) ? $aprovado[$key] : ''));
    						$stm->bindValue(':data_aprovacao', (isset($data_aprovacao[$key]) ? $data_aprovacao[$key] : ''));
    						
    	                	$stm->bindValue(':municao', (isset($_POST['origem_municao_']) ? $_POST['origem_municao_'] : ''));
    						$stm->bindValue(':tipomunicao', (isset($_POST['tipo_municao_']) ? $_POST['tipo_municao_'] : ''));
    						
    						$retorno = $stm->execute();
    						
						}else{
		
						    
						    $sql = " 
						        UPDATE 
						            tab_habitualidade 
						        SET 
						            data            = :data, 
						            local           = :local, 
						            evento          = :evento, 
						            tipo            = :tipo,
						            modelo          = :modelo,
						            calibre         = :calibre, 
						            numsigma        = :sigma, 
						            arma_id         =:armaId,
						            qtdemunicoes    = :qtdemunicoes, 
						            livro           = :livro, 
						            municao         = :municao, 
						            tipomunicao     = :tipomunicao,
						            datacadastro    = :datacadastro
						        WHERE 
						            id = :id
						    ";
						    $stm = $conexao->prepare($sql);
    						$stm->bindValue(':data', date('Y-m-d', strtotime($datacadastro)));
    						$stm->bindValue(':local', $habitu_local[$key]);
    						$stm->bindValue(':evento', $habitu_evento[$key]);
    						$stm->bindValue(':tipo', (isset($habitu_tipo[0]) ? $habitu_tipo[0] : ''));
    						$stm->bindValue(':modelo', (isset($habitu_tipo[1]) ? $habitu_tipo[1] : ''));
    						$stm->bindValue(':calibre', (isset($habitu_tipo[2]) ? $habitu_tipo[2] : ''));
    						$stm->bindValue(':sigma', (isset($habitu_tipo[3]) ? $habitu_tipo[3] : ''));
    						$stm->bindValue(':armaId', (isset($habitu_tipo[4]) ? $habitu_tipo[4] : ''));
    						$stm->bindValue(':qtdemunicoes', $habitu_qtdemunicoes[$key]);
    						$stm->bindValue(':livro', $habitu_livro[$key]);
    						$stm->bindValue(':municao', (isset($_POST['origem_municao_'.$id_linha][0]) ? $_POST['origem_municao_'.$id_linha][0] : ''));
    						$stm->bindValue(':tipomunicao', (isset($_POST['tipo_municao_'.$id_linha][0]) ? $_POST['tipo_municao_'.$id_linha][0] : ''));
    						$stm->bindValue(':datacadastro', $datacadastro);
    						$stm->bindValue(':id', $id_habitualidade);
    						
    						$retorno = $stm->execute();
    					
						    
						}
						
						if(!$sucesso){
							if($retorno) $sucesso = true;
						}
						
						
					}
					
				}
			}
			

			if ($sucesso):
				$_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><font color='993399'><strong>Registro atualizado</strong></font></div>";
		    else:
		    	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";

			endif;


			echo '<script>window.location="/habitualidade.php?id='.$id.'";</script>';exit();
		endif;

       
		?>

	</div>
</body>
</html>