<?php 

		require 'config/conexao.php';



		// Atribui uma conexão PDO

		$conexao = conexao::getInstance();



		// Recebe os dados enviados pela submissão

		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';	
		$email             = (isset($_POST['email'])) ? $_POST['email'] : '';
		$telefone          = (isset($_POST['telefone'])) ? $_POST['telefone'] : '';
		$foto_atual	       = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';

		// Valida os dados recebidos

		$mensagem = '';

		if ($acao == 'editar' && $id == ''):

		    $mensagem .= '<li>ID do registros desconhecido.</li>';

	    endif;



	        // Se for aÃ§Ã£o diferente de excluir valida os dados obrigatÃ³rios

	        if ($acao != 'excluir'):














						if ($mensagem != ''):

				$mensagem = '<ul>' . $mensagem . '</ul>';

				echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";

				exit;

			endif;





            // Constrói a data no formato ANSI yyyy/mm/dd

			$data_temp = explode('/', $data_nascimento);

			$data_ansi = $data_temp[2] . '/' . $data_temp[1] . '/' . $data_temp[0];

		endif;



		// Verifica se foi solicitada a inclusÃ£o de dados

		if ($acao == 'incluir'):



			$nome_foto = 'padrao.png';

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0):  



				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');

			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));



			     // Validamos se a extensÃ£o do arquivo Ã© aceita

			    if (array_search($extensao, $extensoes_aceitas) === false):

			       echo "<h1>ExtensÃ£o InvÃ¡lida!</h1>";

			       exit;

			    endif;

 

			     // Verifica se o upload foi enviado via POST   

			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):  

			             

			          // Verifica se o diretório de destino existe, senão existir cria o diretório  

			          if(!file_exists("fotos")):  

			               mkdir("fotos");  

			          endif;   

			  

			          // Monta o caminho de destino com o nome do arquivo  

			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  

			            

			          // Essa funÃ§Ã£o move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  

			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  

			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  

			          endif;  

			     endif;

			else:



			 	$nome_foto = $foto_atual;



			endif;



 $sql = 'INSERT INTO tab_membros (id, nome, email, telefone, foto)

					VALUES(:id, :nome, :email, :telefone, :foto)';



			// $sql .= 'WHERE id = :id';



			$stm = $conexao->prepare($sql);



			$stm->bindValue(':id', $id);
			$stm->bindValue(':nome', $nome);			
			$stm->bindValue(':email', $email);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':foto', $nome_foto);

			$retorno = $stm->execute();

			if ($retorno):

				 include_once ("config/url_action.php");	
			
				echo "<div class='alert alert-success' role='alert'>Atirador cadastrado com sucesso... </div> ";

		    else:

		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";

			endif;



			echo "<meta http-equiv=refresh content='0;URL=cadastro_com_sucesso_atualizar.php'>";

		endif;



                // Verifica se foi solicitada a edição de dados

		if ($acao == 'editar'):



			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 



				// Verifica se a foto é diferente da padrão, se verdadeiro exclui a foto antiga da pasta

				if ($foto_atual <> 'padrao.png'):

					unlink("fotos/" . $foto_atual);

				endif;



				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');

			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));



			     // Validamos se a extensão do arquivo é aceita

			    if (array_search($extensao, $extensoes_aceitas) === false):

			       echo "<h1>Extensão Inválida!</h1>";

			       exit;

			    endif;

 

			     // Verifica se o upload foi enviado via POST   

			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):   

			             

			          // Verifica se o diretório de destino existe, senão existir cria o diretório  

			          if(!file_exists("fotos")):  

			               mkdir("fotos");  

			          endif;  

			  

			          // Monta o caminho de destino com o nome do arquivo  

			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  

			            

			          // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  

			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  

			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  

			          endif;  

			     endif;

			else:



			 	$nome_foto = $foto_atual;



			endif;



			$sql = 'UPDATE tab_membros SET id=:id, nome=:nome, email=:email, telefone=:telefone, foto=:foto ';



			$sql .= 'WHERE id = :id';



			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':nome', $nome);			
			$stm->bindValue(':email', $email);
			$stm->bindValue(':telefone', $telefone);
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

			echo "<script>window.location='cadastro_com_sucesso_atualizar.php?id=".$id."';</script>";



		endif;



                // Verifica se foi solicitada a exclusão dos dados

		if ($acao == 'excluir'):



			// Captura o nome da foto para excluir da pasta

			$sql = "SELECT foto FROM tab_membros WHERE id = :id AND foto <> 'padrao.png'";

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

				echo "<div class='alert alert-success' role='alert'>Registro exclu&iacute;do com sucesso, aguarde voc&ecirc; est&aacute; sendo redirecionado ...</div> ";

		    else:

		    	echo "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";

			endif;



			echo "<meta http-equiv=refresh content='0;URL=cadastro_com_sucesso_atualizar.php'>";

		endif;

		?>

