<?php 
		require 'config/conexao.php';

		// Atribui uma conex„o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss„o
		$acao                   = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                     = (isset($_POST['id'])) ? $_POST['id'] : '';
		$matricula              = (isset($_POST['matricula'])) ? $_POST['matricula'] : '';
		$data_visita            = (isset($_POST['data_visita'])) ? $_POST['data_visita'] : '';
		$convidado_por          = (isset($_POST['convidado_por'])) ? $_POST['convidado_por'] : '';
		$email_atirador         = (isset($_POST['email_atirador'])) ? $_POST['email_atirador'] : '';
		$nome_visita            = (isset($_POST['nome_visita'])) ? $_POST['nome_visita'] : '';
		$telefone_visita        = (isset($_POST['telefone_visita'])) ? $_POST['telefone_visita'] : '';
		$facebook_visita        = (isset($_POST['facebook_visita'])) ? $_POST['facebook_visita'] : '';
		$instagram_visita       = (isset($_POST['instagram_visita'])) ? $_POST['instagram_visita'] : '';
		$autorizacao            = (isset($_POST['autorizacao'])) ? $_POST['autorizacao'] : '';
		$foto_atual	            = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';

		// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do registros desconhecido.</li>';
	    endif;

	        // Se for a√ß√£o diferente de excluir valida os dados obrigat√≥rios
	        if ($acao != 'excluir'):

						if ($mensagem != ''):
				$mensagem = '<ul>' . $mensagem . '</ul>';
				echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";
				exit;
			endif;

            // ConstrÛi a data no formato ANSI yyyy/mm/dd
			$data_temp = explode('/', $data_nascimento);
			$data_ansi = $data_temp[2] . '/' . $data_temp[1] . '/' . $data_temp[0];
		endif;



		// Verifica se foi solicitada a inclus√£o de dados
		if ($acao == 'incluir'):

			$nome_foto = 'padrao.png';
			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0):  

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extens√£o do arquivo √© aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extens√£o Inv√°lida!</h1>";
			       exit;
			    endif;
 
			     // Verifica se o upload foi enviado via POST   
			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):  
			             
			          // Verifica se o diretÛrio de destino existe, sen„o existir cria o diretÛrio  
			          if(!file_exists("fotos")):  
			               mkdir("fotos");  
			          endif;   
			  
			          // Monta o caminho de destino com o nome do arquivo  
			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  
			            
			          // Essa fun√ß√£o move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotosvisitas/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

            $sql = 'INSERT INTO tab_visitas (matricula, data_visita, convidado_por, email_atirador, nome_visita, telefone_visita,  facebook_visita, instagram_visita, autorizacao, foto)
					VALUES(:matricula, :data_visita, :convidado_por, :email_atirador, :nome_visita, :telefone_visita, :facebook_visita, :instagram_visita, :autorizacao, :foto)';

			// $sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);

			// $stm->bindValue(':id', $id);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_visita', $data_visita);
			$stm->bindValue(':convidado_por', $convidado_por);
			$stm->bindValue(':email_atirador', $email_atirador);
			$stm->bindValue(':telefone_visita', $telefone_visita);
			$stm->bindValue(':nome_visita', $nome_visita);
			$stm->bindValue(':facebook_visita', $facebook_visita);
			$stm->bindValue(':instagram_visita', $instagram_visita);
			$stm->bindValue(':autorizacao', $autorizacao);
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();
                  	      
			if ($retorno):
				
include_once ("config/url_action.php");


				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=painel_visitas.php'>";
		endif;

                // Verifica se foi solicitada a ediÁ„o de dados
		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto È diferente da padr„o, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.png'):
					unlink("fotosvisitas/" . $foto_atual);
				endif;

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extens„o do arquivo È aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extens„o Inv·lida!</h1>";
			       exit;
			    endif;
 
			     // Verifica se o upload foi enviado via POST   
			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):   
			             
			          // Verifica se o diretÛrio de destino existe, sen„o existir cria o diretÛrio  
			          if(!file_exists("fotos")):  
			               mkdir("fotos");  
			          endif;  
			  
			          // Monta o caminho de destino com o nome do arquivo  
			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  
			            
			          // Essa funÁ„o move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotosvisitas/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

			$sql = 'UPDATE tab_visitas SET matricula=:matricula, data_visita=:data_visita, convidado_por=:convidado_por, telefone_visita=:telefone_visita, email_atirador=:email_atirador, facebook_visita=:facebook_visita, instagram_visita=:instagram_visita, autorizacao=:autorizacao, foto=:foto, nome_visita=:nome_visita WHERE id=:id ';

			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':nome_visita', $nome_visita);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_visita', $data_visita);
			$stm->bindValue(':convidado_por', $convidado_por);
			$stm->bindValue(':telefone_visita', $telefone_visita);
			$stm->bindValue(':email_atirador', $email_atirador);
			$stm->bindValue(':facebook_visita', $facebook_visita);
			$stm->bindValue(':instagram_visita', $instagram_visita);
			$stm->bindValue(':autorizacao', $autorizacao);
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();

			if ($retorno):
				$_SESSION['msg'] = '
					<div class="alert alert-success" role="alert">
					  Registro atualizado.
					</div>
				';
		    else:
				$_SESSION['msg'] = '
					<div class="alert alert-danger" role="alert">
					  Erro ao editar registro!
					</div>
				';

			endif;

			#echo "<button onclick=history.go(-1);>Confirmar</button>";
			#echo "<script>history.go(-1);</script>";
			echo "<script>window.location='editar_visitas.php?id=".$id."';</script>";

		endif;

                // Verifica se foi solicitada a exclus„o dos dados
		if ($acao == 'excluir'):

			// Captura o nome da foto para excluir da pasta
			$sql = "SELECT foto FROM tab_visitas WHERE id = :id AND foto <> 'padrao.png'";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->execute();
			$cliente = $stm->fetch(PDO::FETCH_OBJ);

			if (!empty($cliente) && file_exists('fotosvisitas/'.$cliente->foto)):
				unlink("fotosvisitas/" . $cliente->foto);
			endif;

			// Exclui o registro do banco de dados
			$sql = 'DELETE FROM tab_visitas WHERE id = :id';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=painel_visitas.php'>";
		endif;
		?>
