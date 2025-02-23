
<?php 
		require 'config/conexao.php';

		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$data_aviso        = (isset($_POST['data_aviso'])) ? $_POST['data_aviso'] : '';
		$hora_aviso        = (isset($_POST['hora_aviso'])) ? $_POST['hora_aviso'] : '';
		$local_aviso       = (isset($_POST['local_aviso'])) ? $_POST['local_aviso'] : '';
		$evento_aviso      = (isset($_POST['evento_aviso'])) ? $_POST['evento_aviso'] : '';

		// Verifica se foi solicitada a inclusÃ£o de dados
		if ($acao == 'incluir'):

        $sql = 'INSERT INTO tab_avisos (id, data_aviso, hora_aviso, local_aviso, evento_aviso)
			   VALUES(:id, :data_aviso, :hora_aviso, :local_aviso, :evento_aviso)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':data_aviso', $data_aviso);
			$stm->bindValue(':hora_aviso', $hora_aviso);
			$stm->bindValue(':local_aviso', $local_aviso);
			$stm->bindValue(':evento_aviso', $evento_aviso);

            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminavisos.php'>";
		endif;

            // Verifica se foi solicitada a edição de dados
		    if ($acao == 'editar'):

		    $sql = 'UPDATE tab_avisos SET data_aviso=:data_aviso, hora_aviso=:hora_aviso, local_aviso=:local_aviso, evento_aviso=:evento_aviso ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':data_aviso', $data_aviso);
			$stm->bindValue(':hora_aviso', $hora_aviso);
			$stm->bindValue(':local_aviso', $local_aviso);
			$stm->bindValue(':evento_aviso', $evento_aviso);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminavisos.php'>";
		endif;

		?>

