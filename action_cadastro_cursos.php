<?php 
		require 'config/conexao.php';

		// Atribui uma conex„o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss„o
		$acao                   = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                     = (isset($_POST['id'])) ? $_POST['id'] : '';
		$data_inicial_do_curso  = (isset($_POST['data_inicial_do_curso'])) ? $_POST['data_inicial_do_curso'] : '';
		$data_final_do_curso    = (isset($_POST['data_final_do_curso'])) ? $_POST['data_final_do_curso'] : '';		
		$carga_horaria_curso    = (isset($_POST['carga_horaria_curso'])) ? $_POST['carga_horaria_curso'] : '';		
		$curso                  = (isset($_POST['curso'])) ? $_POST['curso'] : '';
		$matricula              = (isset($_POST['matricula'])) ? $_POST['matricula'] : '';
		$nome                   = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$cpf                    = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
		$telefone               = (isset($_POST['telefone'])) ? $_POST['telefone'] : '';
		$email                  = (isset($_POST['email'])) ? $_POST['email'] : '';
		$status                 = (isset($_POST['status'])) ? $_POST['status'] : '';
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

			$nome_foto = 'padrao.jpg';
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
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotoscursos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

            $sql = 'INSERT INTO tab_cursos (data_inicial_do_curso, data_final_do_curso, carga_horaria_curso, curso, matricula, nome, cpf, telefone, email, status, foto)
					VALUES(:data_inicial_do_curso, :data_final_do_curso, :carga_horaria_curso, :curso, :matricula, :nome, :cpf, :telefone, :email, :status, :foto)';

			// $sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);

			// $stm->bindValue(':id', $id);
			$stm->bindValue(':data_inicial_do_curso', $data_inicial_do_curso);
			$stm->bindValue(':data_final_do_curso', $data_final_do_curso);
			$stm->bindValue(':carga_horaria_curso', $carga_horaria_curso);		
			$stm->bindValue(':curso', $curso);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':cpf', $cpf);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':status', $status);
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();
                  	      
			if ($retorno):
			    
			include_once ("config/url_action_cursos.php");				    
				
				


				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=painel_cursos.php'>";
		endif;

                // Verifica se foi solicitada a ediÁ„o de dados
		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto È diferente da padr„o, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.jpg'):
					unlink("fotoscursos/" . $foto_atual);
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
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotoscursos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

			$sql = 'UPDATE tab_cursos SET data_inicial_do_curso=:data_inicial_do_curso, data_final_do_curso=:data_final_do_curso, carga_horaria_curso=:carga_horaria_curso, curso=:curso, matricula=:matricula, nome=:nome, cpf=:cpf, telefone=:telefone, email=:email, status=:status, foto=:foto WHERE id = :id ';

			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':data_inicial_do_curso', $data_inicial_do_curso);
			$stm->bindValue(':data_final_do_curso', $data_final_do_curso);		
			$stm->bindValue(':carga_horaria_curso', $carga_horaria_curso);		
			$stm->bindValue(':curso', $curso);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':cpf', $cpf);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':status', $status);
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
			echo "<script>window.location='painel_cursos.php?id=".$id."';</script>";

		endif;

                // Verifica se foi solicitada a exclus„o dos dados
		if ($acao == 'excluir'):

			// Captura o nome da foto para excluir da pasta
			$sql = "SELECT foto FROM tab_cursos WHERE id = :id AND foto <> 'padrao.jpg'";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->execute();
			$cliente = $stm->fetch(PDO::FETCH_OBJ);

			if (!empty($cliente) && file_exists('fotoscursos/'.$cliente->foto)):
				unlink("fotoscursos/" . $cliente->foto);
			endif;

			// Exclui o registro do banco de dados
			$sql = 'DELETE FROM tab_cursos WHERE id = :id';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=painel_cursos.php'>";
		endif;
		?>
