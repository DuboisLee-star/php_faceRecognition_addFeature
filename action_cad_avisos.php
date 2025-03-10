<?php 
		require 'config/conexao.php';

		// Atribui uma conex�o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss�o
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$data_aviso        = (isset($_POST['data_aviso'])) ? $_POST['data_aviso'] : '';
		$hora_aviso        = (isset($_POST['hora_aviso'])) ? $_POST['hora_aviso'] : '';
		$local_aviso       = (isset($_POST['local_aviso'])) ? $_POST['local_aviso'] : '';
		$evento_aviso      = (isset($_POST['evento_aviso'])) ? $_POST['evento_aviso'] : '';

		// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do registros desconhecido.</li>';
	    endif;

		// Verifica se foi solicitada a inclusão de dados
		if ($acao == 'incluir'):

        $sql = 'INSERT INTO tab_avisos (data_aviso, hora_aviso, local_aviso, evento_aviso)
			   VALUES(:data_aviso, :hora_aviso, :local_aviso, :evento_aviso)';

			$stm = $conexao->prepare($sql);
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

		?>