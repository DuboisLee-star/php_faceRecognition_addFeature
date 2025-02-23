<?php 
		require 'config/conexao.php';

		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$password          = (isset($_POST['password'])) ? $_POST['password'] : '';
			$user              = (isset($_POST['user'])) ? $_POST['user'] : '';

		// Verifica se foi solicitada a inclusÃ£o de dados
		if ($acao == 'incluir'):

        $sql = 'INSERT INTO users (id, password)
			   VALUES(:id, :password)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':password', $password);

            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminusers.php'>";
		endif;

            // Verifica se foi solicitada a edição de dados
		    if ($acao == 'editar'):
		        
		        //verificação de usuario
		         $conexao = conexao::getInstance();
		             $sql_user1 = "SELECT * FROM users WHERE id = :id";
            $stm = $conexao->prepare($sql_user1);
            
            $stm->bindParam(':id', $id);
            
            $stm->execute();
            
            $usuario1 = $stm->fetch(PDO::FETCH_OBJ);
            
       
		        
		        $conexao = conexao::getInstance();
        //Dados do operador    
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
			$stm->bindValue(':tabela', 'users');
			$stm->bindValue(':tipo_alteracao', 'edicao');
				$stm->bindValue(':registro_id', $usuario1->id);
		  // Converte o array para JSON
            $alteracao = json_encode(['Alteracao de senha do usuario: ' => $usuario1->name]);
            $stm->bindValue(':registro', $alteracao);
            
         
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();

		    $sql = 'UPDATE users SET id=:id, password=:password ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':password', $password);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminusers.php'>";
		endif;

		?>
