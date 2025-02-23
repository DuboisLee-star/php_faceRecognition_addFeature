<?php 

		require 'config/conexao.php';
 

// Atribui uma conexão PDO

		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão

		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';

		$matricula = substr(uniqid(rand()), 0, 4);

		$data_filiacao     = isset($_POST['data_filiacao']) && $_POST['data_filiacao'] !== '' ? $_POST['data_filiacao'] : null;
		$data_renovacao    = isset($_POST['data_renovacao']) && $_POST['data_renovacao'] !== '' ? $_POST['data_renovacao'] : null;
		$bloqueio          = (isset($_POST['bloqueio'])) ? $_POST['bloqueio'] : '';
		$plano_pgto        = (isset($_POST['plano_pgto'])) ? $_POST['plano_pgto'] : '';
		$categoria         = (isset($_POST['categoria'])) ? $_POST['categoria'] : '';
		$cr                = (isset($_POST['cr'])) ? $_POST['cr'] : '';
		$cr_emissao = isset($_POST['cr_emissao']) && $_POST['cr_emissao'] !== '' ? strtoupper($_POST['cr_emissao']) : null;
		$validade_cr = isset($_POST['validade_cr']) && $_POST['validade_cr'] !== '' ? strtoupper($_POST['validade_cr']) : null;
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$rua               = (isset($_POST['rua'])) ? $_POST['rua'] : '';
		$numero            = (isset($_POST['numero'])) ? $_POST['numero'] : '';
		$bairro            = (isset($_POST['bairro'])) ? $_POST['bairro'] : '';
		$cep               = (isset($_POST['cep'])) ? $_POST['cep'] : '';
		$cidade            = (isset($_POST['cidade'])) ? $_POST['cidade'] : '';
		$siglauf           = (isset($_POST['siglauf'])) ? $_POST['siglauf'] : '';
		$estadocivil       = (isset($_POST['estadocivil'])) ? $_POST['estadocivil'] : '';
		$naturalidade      = (isset($_POST['naturalidade'])) ? $_POST['naturalidade'] : '';
		$nacionalidade     = (isset($_POST['nacionalidade'])) ? $_POST['nacionalidade'] : '';
		$data_nascimento = isset($_POST['data_nascimento']) && $_POST['data_nascimento'] !== '' ? $_POST['data_nascimento'] : null;
		$profissao         = (isset($_POST['profissao'])) ? $_POST['profissao'] : '';
		$email             = (isset($_POST['email'])) ? $_POST['email'] : '';
		$instagram         = (isset($_POST['instagram'])) ? $_POST['instagram'] : '';
		$telefone          = (isset($_POST['telefone'])) ? $_POST['telefone'] : '';
		$pai               = (isset($_POST['pai'])) ? $_POST['pai'] : '';
		$mae               = (isset($_POST['mae'])) ? $_POST['mae'] : '';
		$cpf               = (isset($_POST['cpf'])) ? str_replace(array('.','-'), '', $_POST['cpf']): '';
		$identidade        = (isset($_POST['identidade'])) ? $_POST['identidade'] : '';
		$orgaouf           = (isset($_POST['orgaouf'])) ? $_POST['orgaouf'] : '';
		$data_exped = isset($_POST['data_exped']) && $_POST['data_exped'] !== '' ? strtoupper($_POST['data_exped']) : null;
		$tituloeleitoral   = (isset($_POST['tituloeleitoral'])) ? $_POST['tituloeleitoral'] : '';
		$cnh               = (isset($_POST['cnh'])) ? $_POST['cnh'] : '';		
	    $data_exped_cnh = isset($_POST['data_exped_cnh']) && $_POST['data_exped_cnh'] !== '' ? $_POST['data_exped_cnh'] : null;
		$orgao_cnh         = (isset($_POST['orgao_cnh'])) ? $_POST['orgao_cnh'] : '';		
		$senha_sisgcorp    = (isset($_POST['senha_sisgcorp'])) ? $_POST['senha_sisgcorp'] : '';		
		$tipo_sanguineo    = (isset($_POST['tipo_sanguineo'])) ? $_POST['tipo_sanguineo'] : '';		
		$ocorrencias       = (isset($_POST['ocorrencias'])) ? $_POST['ocorrencias'] : '';
		$segundo_cep       = (isset($_POST['segundo_cep'])) ? $_POST['segundo_cep'] : '';
		$segundo_rua       = (isset($_POST['segundo_rua'])) ? $_POST['segundo_rua'] : '';
		$segundo_num       = (isset($_POST['segundo_num'])) ? $_POST['segundo_num'] : '';
		$segundo_bairro    = (isset($_POST['segundo_bairro'])) ? $_POST['segundo_bairro'] : '';
		$segundo_cidade    = (isset($_POST['segundo_cidade'])) ? $_POST['segundo_cidade'] : '';
		$segundo_estado    = (isset($_POST['segundo_estado'])) ? $_POST['segundo_estado'] : '';
		$url               = (isset($_POST['url'])) ? $_POST['url'] : '';
		$motivo            = (isset($_POST['motivo'])) ? $_POST['motivo'] : '';
		$foto_atual	       = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';


		// Valida os dados recebidos

		$mensagem = '';

		if ($acao == 'editar' && $id == ''):

		    $mensagem .= '<li>ID do registros desconhecido.</li>';

	    endif;



	        // Se for aÃ§Ã£o diferente de excluir valida os dados obrigatÃ³rios

	        if ($acao != 'excluir'):



                        if ($cep == '' || strlen($cep) < 3):

		        $mensagem .= '<li>Favor preencher seu cep completo.</li>';

		endif;








			if ($telefone == ''): 

				$mensagem .= '<li>Favor preencher com o n&uacute;mero de telefone.</li>';

			elseif(strlen($telefone) < 10):

				  $mensagem .= '<li>Formato do n&uacute;mero de telefone invÃ¡lido.</li>';

		    endif;








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


 $sql = 'INSERT INTO tab_membros (matricula, data_filiacao, data_renovacao, bloqueio, plano_pgto, categoria, cr, cr_emissao, validade_cr, nome, rua, numero, bairro, cep, cidade, siglauf, estadocivil, naturalidade, nacionalidade, data_nascimento, profissao, email, instagram, telefone, pai, mae, cpf, cnh, data_exped_cnh, orgao_cnh, senha_sisgcorp, tipo_sanguineo, identidade, orgaouf, data_exped, tituloeleitoral, url, motivo, segundo_cep, segundo_rua, segundo_num, segundo_bairro, segundo_cidade, segundo_estado, ocorrencias, foto)

					VALUES(:matricula, :data_filiacao, :data_renovacao, :bloqueio, :plano_pgto, :categoria, :cr, :cr_emissao, :validade_cr,  :nome, :rua, :numero, :bairro, :cep, :cidade, :siglauf, :estadocivil, :naturalidade, :nacionalidade, :data_nascimento, :profissao, :email, :instagram, :telefone, :pai, :mae, :cpf, :cnh, :data_exped_cnh, :orgao_cnh, :senha_sisgcorp, :tipo_sanguineo, :identidade, :orgaouf, :data_exped, :tituloeleitoral, :url, :motivo, :ocorrencias, :segundo_cep, :segundo_rua, :segundo_num, :segundo_bairro, :segundo_cidade, :segundo_estado, :foto)';



			// $sql .= 'WHERE id = :id';



			$stm = $conexao->prepare($sql);



			// $stm->bindValue(':id', $id);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_filiacao', $data_filiacao);
			$stm->bindValue(':data_renovacao', $data_renovacao);
			$stm->bindValue(':bloqueio', $bloqueio);			
			$stm->bindValue(':plano_pgto', $plano_pgto);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':cr_emissao', $cr_emissao);			
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
			$stm->bindValue(':cnh', $cnh);
			$stm->bindValue(':data_exped_cnh', $data_exped_cnh);
			$stm->bindValue(':orgao_cnh', $orgao_cnh);
			$stm->bindValue(':senha_sisgcorp', $senha_sisgcorp);
			$stm->bindValue(':tipo_sanguineo', $tipo_sanguineo);
			$stm->bindValue(':url', $url);
			$stm->bindValue(':motivo', $motivo);
			$stm->bindValue(':ocorrencias', $ocorrencias);	
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);	
			$stm->bindValue(':segundo_bairro', $segundo_bairro);	
			$stm->bindValue(':segundo_cidade', $segundo_cidade);	
			$stm->bindValue(':segundo_estado', $segundo_estado);	
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

			$sql = 'UPDATE tab_membros SET id=:id,  data_filiacao=:data_filiacao, data_renovacao=:data_renovacao, bloqueio=:bloqueio, plano_pgto=:plano_pgto, categoria=:categoria, cr=:cr, cr_emissao=:cr_emissao, validade_cr=:validade_cr, nome=:nome, rua=:rua,  numero=:numero, bairro=:bairro, cep=:cep, cidade=:cidade, siglauf=:siglauf, estadocivil=:estadocivil, naturalidade=:naturalidade, nacionalidade=:nacionalidade, data_nascimento=:data_nascimento, profissao=:profissao, email=:email, instagram=:instagram, telefone=:telefone, pai=:pai, mae=:mae, cpf=:cpf, identidade=:identidade, orgaouf=:orgaouf, data_exped=:data_exped, tituloeleitoral=:tituloeleitoral, cnh=:cnh, data_exped_cnh=:data_exped_cnh, orgao_cnh=:orgao_cnh, senha_sisgcorp=:senha_sisgcorp, tipo_sanguineo=:tipo_sanguineo, ocorrencias=:ocorrencias, segundo_cep=:segundo_cep, segundo_rua=:segundo_rua, segundo_num=:segundo_num, segundo_bairro=:segundo_bairro, segundo_cidade=:segundo_cidade, segundo_estado=:segundo_estado, foto=:foto ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':data_filiacao', $data_filiacao);
			$stm->bindValue(':data_renovacao', $data_renovacao);
			$stm->bindValue(':bloqueio', $bloqueio);			
			$stm->bindValue(':plano_pgto', $plano_pgto);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':cr_emissao', $cr_emissao);			
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
			$stm->bindValue(':cnh', $cnh);
			$stm->bindValue(':data_exped_cnh', $data_exped_cnh);
			$stm->bindValue(':orgao_cnh', $orgao_cnh);
			$stm->bindValue(':senha_sisgcorp', $senha_sisgcorp);
			$stm->bindValue(':tipo_sanguineo', $tipo_sanguineo);
			$stm->bindValue(':ocorrencias', $ocorrencias);	
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



			echo "<meta http-equiv=refresh content='0;URL=painel.php'>";

		endif;

		?>
