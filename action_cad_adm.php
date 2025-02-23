<?php 
		require 'config/conexao.php';

		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$username          = (isset($_POST['username'])) ? $_POST['username'] : '';
		$name              = (isset($_POST['name'])) ? $_POST['name'] : '';
		$password          = (isset($_POST['password'])) ? $_POST['password'] : '';
		$user              = (isset($_POST['user'])) ? $_POST['user'] : '';

		// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do registros desconhecido.</li>';
	    endif;

		// Verifica se foi solicitada a inclusÃ£o de dados
		if ($acao == 'incluir'):
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
			$stm->bindValue(':tabela', 'users');
			$stm->bindValue(':tipo_alteracao', 'criacao');
		  // Converte o array para JSON
            $alteracao = json_encode(['Usuario' => $username]);
            $stm->bindValue(':registro', $alteracao);
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();
		   
		 
 
	
        $sql = 'INSERT INTO users (username, name, password)
			   VALUES(:username, :name, :password)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':username', $username);
			$stm->bindValue(':name', $name);
			$stm->bindValue(':password', $password);
            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminusers.php'>";
		endif;

		?>