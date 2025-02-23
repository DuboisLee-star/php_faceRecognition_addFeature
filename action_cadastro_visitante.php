<?php 

		require 'config/conexao.php';

		// Atribui uma conex„o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss„o

		$acao                      = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                        = (isset($_POST['id'])) ? $_POST['id'] : '';
		$cr_visitante              = (isset($_POST['cr_visitante'])) ? $_POST['cr_visitante'] : '';
		$cpf_visitante             = (isset($_POST['cpf_visitante'])) ? $_POST['cpf_visitante'] : '';	
		$nome_visitante            = (isset($_POST['nome_visitante'])) ? $_POST['nome_visitante'] : '';	
		$rg_visitante              = (isset($_POST['rg_visitante'])) ? $_POST['rg_visitante'] : '';	
		$orgao_exped_visitante     = (isset($_POST['orgao_exped_visitante'])) ? $_POST['orgao_exped_visitante'] : '';	
		$rua_visitante             = (isset($_POST['rua_visitante'])) ? $_POST['rua_visitante'] : '';	
		$num_visitante             = (isset($_POST['num_visitante'])) ? $_POST['num_visitante'] : '';	
		$bairro_visitante          = (isset($_POST['bairro_visitante'])) ? $_POST['bairro_visitante'] : '';	
		$cep_visitante             = (isset($_POST['cep_visitante'])) ? $_POST['cep_visitante'] : '';	
		$cidade_visitante          = (isset($_POST['cidade_visitante'])) ? $_POST['cidade_visitante'] : '';	
		$estado_visitante          = (isset($_POST['estado_visitante'])) ? $_POST['estado_visitante'] : '';	
		$estado_civil_visitante    = (isset($_POST['estado_civil_visitante'])) ? $_POST['estado_civil_visitante'] : '';	
		$data_nascimento_visitante = (isset($_POST['nome_visitante'])) ? $_POST['data_nascimento_visitante'] : '';	
		$sexo_visitante            = (isset($_POST['sexo_visitante'])) ? $_POST['sexo_visitante'] : '';	
		$email_visitante           = (isset($_POST['email_visitante'])) ? $_POST['email_visitante'] : '';	
		$telefone_visitante        = (isset($_POST['telefone_visitante'])) ? $_POST['telefone_visitante'] : '';	
		$tipo_atirador             = (isset($_POST['tipo_atirador'])) ? $_POST['tipo_atirador'] : '';			
		$obs_visitante             = (isset($_POST['obs_visitante'])) ? $_POST['obs_visitante'] : '';



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
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:


			 	$nome_foto = $foto_atual;


			endif;



 $sql = 'INSERT INTO tab_habitualidade (cr_visitante, cpf_visitante, rg_visitante, orgao_exped_visitante, rua_visitante, num_visitante, bairro_visitante, cep_visitante, cidade_visitante, estado_visitante, estado_civil_visitante, data_nascimento_visitante, sexo_visitante, email_visitante, email_visitante, telefone_visitante, tipo_atirador, obs_visitante, foto)

					VALUES(:cr_visitante, :cpf_visitante, :rg_visitante, :orgao_exped_visitante, :rua_visitante, :num_visitante, :bairro_visitante, :cep_visitante, :cidade_visitante, :estado_visitante, :estado_civil_visitante, :data_nascimento_visitante, :sexo_visitante, :email_visitante, :email_visitante, :telefone_visitante, :tipo_atirador, :obs_visitante, :foto)';

			// $sql .= 'WHERE id = :id';
			$stm = $conexao->prepare($sql);

			// $stm->bindValue(':id', $id);
		$cr_visitante              = (isset($_POST['cr_visitante'])) ? $_POST['cr_visitante'] : '';
		$cpf_visitante             = (isset($_POST['cpf_visitante'])) ? $_POST['cpf_visitante'] : '';	
		$nome_visitante            = (isset($_POST['nome_visitante'])) ? $_POST['nome_visitante'] : '';	
		$rg_visitante              = (isset($_POST['rg_visitante'])) ? $_POST['rg_visitante'] : '';	
		$orgao_exped_visitante     = (isset($_POST['orgao_exped_visitante'])) ? $_POST['orgao_exped_visitante'] : '';	
		$rua_visitante             = (isset($_POST['rua_visitante'])) ? $_POST['rua_visitante'] : '';	
		$num_visitante             = (isset($_POST['num_visitante'])) ? $_POST['num_visitante'] : '';	
		$bairro_visitante          = (isset($_POST['bairro_visitante'])) ? $_POST['bairro_visitante'] : '';	
		$cep_visitante             = (isset($_POST['cep_visitante'])) ? $_POST['cep_visitante'] : '';	
		$cidade_visitante          = (isset($_POST['cidade_visitante'])) ? $_POST['cidade_visitante'] : '';	
		$estado_visitante          = (isset($_POST['estado_visitante'])) ? $_POST['estado_visitante'] : '';	
		$estado_civil_visitante    = (isset($_POST['estado_civil_visitante'])) ? $_POST['estado_civil_visitante'] : '';	
		$data_nascimento_visitante = (isset($_POST['nome_visitante'])) ? $_POST['data_nascimento_visitante'] : '';	
		$sexo_visitante            = (isset($_POST['sexo_visitante'])) ? $_POST['sexo_visitante'] : '';	
		$email_visitante           = (isset($_POST['email_visitante'])) ? $_POST['email_visitante'] : '';	
		$telefone_visitante        = (isset($_POST['telefone_visitante'])) ? $_POST['telefone_visitante'] : '';	
		$tipo_atirador             = (isset($_POST['tipo_atirador'])) ? $_POST['tipo_atirador'] : '';		
		$obs_visitante             = (isset($_POST['obs_visitante'])) ? $_POST['obs_visitante'] : '';
		
			$stm->bindValue(':foto', $nome_foto);
			
			$retorno = $stm->execute();


			if ($retorno):

				  include_once ("config/url_action.php");	

				echo "<div class='alert alert-success' role='alert'>Atirador cadastrado com sucesso... </div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=painel.php'>";

		endif;


                // Verifica se foi solicitada a ediÁ„o de dados

		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto È diferente da padr„o, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.png'):

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

			$sql = 'UPDATE tab_habitualidade SET id=:id,  foto=:foto ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':cr_visitante', $cr_visitante);
			$stm->bindValue(':cpf_visitante', $cpf_visitante);
			$stm->bindValue(':data_renovacao', $data_renovacao);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':validade_cr', $validade_cr);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':rua', $rua);
			$stm->bindValue(':numero', $numero);
			$stm->bindValue(':bairro', $bairro);
			$stm->bindValue(':cep', $cep);
			$stm->bindValue(':cidade', $cidade);
			$stm->bindValue(':siglauf', $siglauf);
			$stm->bindValue(':estadocivil', $estadocivil);
			$stm->bindValue(':naturalidade', $naturalidade);
			$stm->bindValue(':nacionalidade', $nacionalidade);
			$stm->bindValue(':data_nascimento', $data_nascimento);
			$stm->bindValue(':profissao', $profissao);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':instagram', $instagram);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':pai', $pai);
			$stm->bindValue(':mae', $mae);
			$stm->bindValue(':cpf', $cpf);
			$stm->bindValue(':identidade', $identidade);
			$stm->bindValue(':orgaouf', $orgaouf);
			$stm->bindValue(':data_exped', $data_exped);
			$stm->bindValue(':tituloeleitoral', $tituloeleitoral);
			$stm->bindValue(':ocorrencias', $ocorrencias);	
			$stm->bindValue(':termos_filiacao', $termos_filiacao);	
			$stm->bindValue(':termos_estatuto', $termos_estatuto);	
			$stm->bindValue(':termos_idoneidade', $termos_idoneidade);
     		$stm->bindValue(':plano', $plano);			
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);	
			$stm->bindValue(':segundo_bairro', $segundo_bairro);	
			$stm->bindValue(':segundo_cidade', $segundo_cidade);	
			$stm->bindValue(':segundo_estado', $segundo_estado);	
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

			echo "<script>window.location='perfil.php?id=".$id."';</script>";



		endif;



                // Verifica se foi solicitada a exclus„o dos dados

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



			echo "<meta http-equiv=refresh content='0;URL=painel.php'>";

		endif;

		?>

