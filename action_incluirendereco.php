		<?php 
		require 'config/conexao.php';

		// Atribui uma conex„o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss„o
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$cr                = (isset($_POST['cr'])) ? $_POST['cr'] : '';
		$validade_cr       = (isset($_POST['validade_cr'])) ? $_POST['validade_cr'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$segundo_rua       = (isset($_POST['segundo_rua'])) ? $_POST['segundo_rua'] : '';
		$segundo_num       = (isset($_POST['segundo_num'])) ? $_POST['segundo_num'] : '';
		$segundo_bairro    = (isset($_POST['segundo_bairro'])) ? $_POST['segundo_bairro'] : '';
		$segundo_cep       = (isset($_POST['segundo_cep'])) ? $_POST['segundo_cep'] : '';
		$segundo_cidade    = (isset($_POST['segundo_cidade'])) ? $_POST['segundo_cidade'] : '';
		$segundo_estado    = (isset($_POST['segundo_estado'])) ? $_POST['segundo_estado'] : '';
 	    $segundo_obs       = (isset($_POST['segundo_obs'])) ? $_POST['segundo_obs'] : '';
		$foto_atual	   = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';

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
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

                        $sql = 'INSERT INTO tab_membros (id, cr, validade_cr, nome, segundo_rua, segundo_num, segundo_bairro, segundo_cep, segundo_cidade, segundo_estado, segundo_obs, foto)
							   VALUES(:id, :cr, :validade_cr, :nome, :segundo_rua, :segundo_num, :segundo_bairro, :segundo_cep, :segundo_cidade, :segundo_estado,  :segundo_obs, :foto)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':validade_cr', $validade_cr);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);
			$stm->bindValue(':segundo_bairro', $segundo_bairro);
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_cidade', $segundo_cidade);
			$stm->bindValue(':segundo_estado', $segundo_estado);
			$stm->bindValue(':segundo_obs', $segundo_obs);			
			$stm->bindValue(':foto', $nome_foto);
                  	      
            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=cad_enderecos.php'>";
		endif;


                // Verifica se foi solicitada a ediÁ„o de dados
		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto È diferente da padr„o, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.jpg'):
					unlink("fotos/" . $foto_atual);
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
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

			$sql = 'UPDATE tab_membros SET id=:id, cr=:cr, validade_cr=:validade_cr, segundo_rua=:segundo_rua, segundo_num=:segundo_num, segundo_bairro=:segundo_bairro, segundo_cep=:segundo_cep, segundo_cidade=:segundo_cidade, segundo_estado=:segundo_estado, segundo_obs=:segundo_obs, foto=:foto ';
			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':validade_cr', $validade_cr);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);
			$stm->bindValue(':segundo_bairro', $segundo_bairro);
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_cidade', $segundo_cidade);
			$stm->bindValue(':segundo_estado', $segundo_estado);
			$stm->bindValue(':segundo_obs', $segundo_obs);
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=cad_enderecos.php'>";
		endif;

                // Verifica se foi solicitada a exclus„o dos dados
		if ($acao == 'excluir'):

			// Captura o nome da foto para excluir da pasta
			$sql = "SELECT foto FROM tab_membros WHERE id = :id AND foto <> 'padrao.jpg'";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->execute();
			$cliente = $stm->fetch(PDO::FETCH_OBJ);

			if (!empty($cliente) && file_exists('fotos/'.$cliente->foto)):
				unlink("fotos/" . $cliente->foto);
			endif;

			// Exclui o registro do banco de dados
			$sql = 'DELETE FROM tab_membros WHERE id = :id';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=cad_enderecos.php'>";
		endif;
		?>

