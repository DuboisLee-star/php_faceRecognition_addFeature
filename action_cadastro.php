<?php 

require 'config/conexao.php';

// Atribui uma conexão PDO
$conexao = conexao::getInstance();

// Recebe os dados enviados pela submissão
$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$user              = (isset($_POST['user'])) ? $_POST['user'] : '';
$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
$matricula         = (isset($_POST['matricula'])) ? $_POST['matricula'] : '';
$data_filiacao     = isset($_POST['data_filiacao']) && $_POST['data_filiacao'] !== '' ? $_POST['data_filiacao'] : null;
$data_renovacao    = isset($_POST['data_renovacao']) && $_POST['data_renovacao'] !== '' ? $_POST['data_renovacao'] : null;
$categoria         = isset($_POST['categoria']) ? strtoupper($_POST['categoria']) : '';
$nivel             = isset($_POST['nivel']) ? strtoupper($_POST['nivel']) : '';
$msg_auto          = isset($_POST['envios_auto']) ? strtoupper($_POST['envios_auto']) : '';
$cr                = isset($_POST['cr']) ? strtoupper($_POST['cr']) : '';
$bloqueio          = isset($_POST['bloqueio']) ? $_POST['bloqueio'] : '';
$plano_pgto        = isset($_POST['plano_pgto']) ? $_POST['plano_pgto'] : '';	
$plano             = isset($_POST['plano']) ? $_POST['plano'] : '';			
$cr_emissao        = isset($_POST['cr_emissao']) && $_POST['cr_emissao'] !== '' ? strtoupper($_POST['cr_emissao']) : null;
$validade_cr       = isset($_POST['validade_cr']) && $_POST['validade_cr'] !== '' ? strtoupper($_POST['validade_cr']) : null;
$nome              = isset($_POST['nome']) ? strtoupper($_POST['nome']) : '';
$rua               = isset($_POST['rua']) ? strtoupper($_POST['rua']) : '';
$numero            = isset($_POST['numero']) ? strtoupper($_POST['numero']) : '';
$bairro            = isset($_POST['bairro']) ? strtoupper($_POST['bairro']) : '';
$complemento       = isset($_POST['complemento']) ? strtoupper($_POST['complemento']) : '';		
$cep               = isset($_POST['cep']) ? strtoupper($_POST['cep']) : '';
$cidade            = isset($_POST['cidade']) ? strtoupper($_POST['cidade']) : '';
$siglauf           = isset($_POST['siglauf']) ? strtoupper($_POST['siglauf']) : '';
$estadocivil       = isset($_POST['estadocivil']) ? strtoupper($_POST['estadocivil']) : '';
$naturalidade      = isset($_POST['naturalidade']) ? strtoupper($_POST['naturalidade']) : '';
$nacionalidade     = isset($_POST['nacionalidade']) ? strtoupper($_POST['nacionalidade']) : '';
$data_nascimento   = isset($_POST['data_nascimento']) && $_POST['data_nascimento'] !== '' ? $_POST['data_nascimento'] : null;
$profissao         = isset($_POST['profissao']) ? strtoupper($_POST['profissao']) : '';
$email             = isset($_POST['email']) ? $_POST['email'] : '';
$instagram         = isset($_POST['instagram']) ? $_POST['instagram'] : '';
$telefone          = isset($_POST['telefone']) ? strtoupper($_POST['telefone']) : '';
$pai               = isset($_POST['pai']) ? strtoupper($_POST['pai']) : '';
$mae               = isset($_POST['mae']) ? strtoupper($_POST['mae']) : '';
$cpf               = isset($_POST['cpf']) ? strtoupper(str_replace(array('.','-'), '', $_POST['cpf'])) : '';
$identidade        = isset($_POST['identidade']) ? strtoupper($_POST['identidade']) : '';
$orgaouf           = isset($_POST['orgaouf']) ? strtoupper($_POST['orgaouf']) : '';
$data_exped        = isset($_POST['data_exped']) && $_POST['data_exped'] !== '' ? strtoupper($_POST['data_exped']) : null;
$cnh               = isset($_POST['cnh']) ? strtoupper($_POST['cnh']) : '';
$data_exped_cnh    = isset($_POST['data_exped_cnh']) && $_POST['data_exped_cnh'] !== '' ? $_POST['data_exped_cnh'] : null;
$orgao_cnh         = isset($_POST['orgao_cnh']) ? strtoupper($_POST['orgao_cnh']) : '';
$senha_sisgcorp    = isset($_POST['senha_sisgcorp']) ? $_POST['senha_sisgcorp'] : '';
$tipo_sanguineo    = isset($_POST['tipo_sanguineo']) ? $_POST['tipo_sanguineo'] : '';
$tituloeleitoral   = isset($_POST['tituloeleitoral']) ? strtoupper($_POST['tituloeleitoral']) : '';
$url               = isset($_POST['url']) ? $_POST['url'] : '';
$motivo            = isset($_POST['motivo']) ? $_POST['motivo'] : '';		
$ocorrencias       = isset($_POST['ocorrencias']) ? strtoupper($_POST['ocorrencias']) : '';
$segundo_cep       = isset($_POST['segundo_cep']) ? strtoupper($_POST['segundo_cep']) : '';
$segundo_rua       = isset($_POST['segundo_rua']) ? strtoupper($_POST['segundo_rua']) : '';
$segundo_num       = isset($_POST['segundo_num']) ? strtoupper($_POST['segundo_num']) : '';
$segundo_complemento = isset($_POST['segundo_complemento']) ? strtoupper($_POST['segundo_complemento']) : '';
$segundo_bairro    = isset($_POST['segundo_bairro']) ? strtoupper($_POST['segundo_bairro']) : '';
$segundo_cidade    = isset($_POST['segundo_cidade']) ? strtoupper($_POST['segundo_cidade']) : '';		
$segundo_estado    = isset($_POST['segundo_estado']) ? strtoupper($_POST['segundo_estado']) : '';

		$foto_atual	       = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';
		$image_webcam	   = (isset($_POST['image_webcam'])) ? $_POST['image_webcam'] : '';
		if(strlen(trim($image_webcam)) > 0){
		    $image_webcam = substr($image_webcam, 22);
		}

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
			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0 && strlen(trim($image_webcam)) <= 0):  
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

                if(strlen(trim($image_webcam)) > 0){
                    $nome_foto = date('dmY') . '_' . md5(date('Y-m-dH:i:s').microtime(true).rand(0,9999)).'.png';
                    $ifp = fopen('fotos/'.$nome_foto, 'wb');
                    fwrite($ifp, base64_decode($image_webcam));
                    fclose($ifp);
                }else{
                    $nome_foto = $foto_atual;   
                }
			endif;

 $sql = 'INSERT INTO tab_membros (id, matricula, data_filiacao, data_renovacao, categoria, nivel, bloqueio, envios_auto, plano_pgto, plano, cr, cr_emissao, validade_cr, nome, rua, numero, bairro, complemento, cep, cidade, siglauf, estadocivil, naturalidade, nacionalidade, data_nascimento, profissao, email, instagram, telefone, pai, mae, cpf, identidade, orgaouf, data_exped, cnh, data_exped_cnh, orgao_cnh, tipo_sanguineo, senha_sisgcorp,  tituloeleitoral, url, motivo, segundo_cep, segundo_rua, segundo_num, segundo_complemento, segundo_bairro, segundo_cidade, segundo_estado, ocorrencias, foto)
		VALUES(:id, :matricula, :data_filiacao, :data_renovacao, :categoria, :nivel, :bloqueio,:envios_auto, :plano_pgto, :plano, :cr, :cr_emissao, :validade_cr,  :nome, :rua, :numero, :bairro, :complemento, :cep, :cidade, :siglauf, :estadocivil, :naturalidade, :nacionalidade, :data_nascimento, :profissao, :email, :instagram, :telefone, :pai, :mae, :cpf, :identidade, :orgaouf, :data_exped, :cnh, :data_exped_cnh, :orgao_cnh, :senha_sisgcorp, :tipo_sanguineo, :tituloeleitoral, :url, :motivo, :ocorrencias, :segundo_cep, :segundo_rua, :segundo_complemento, :segundo_num, :segundo_bairro, :segundo_cidade, :segundo_estado, :foto)';



			// $sql .= 'WHERE id = :id';
			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);

			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_filiacao', $data_filiacao);
			$stm->bindValue(':data_renovacao', $data_renovacao);			
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':nivel', $nivel);			
			$stm->bindValue(':bloqueio', $bloqueio);
			$stm->bindValue(':envios_auto', $msg_auto);
			$stm->bindValue(':plano_pgto', $plano_pgto);
			$stm->bindValue(':plano', $plano);			
			$stm->bindValue(':cr', $cr);	
			$stm->bindValue(':cr_emissao', $cr_emissao);			
			$stm->bindValue(':validade_cr', $validade_cr);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':rua', $rua);
			$stm->bindValue(':numero', $numero);
			$stm->bindValue(':bairro', $bairro);
			$stm->bindValue(':complemento', $complemento);			
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
			$stm->bindValue(':cnh', $cnh);
			$stm->bindValue(':data_exped_cnh', $data_exped_cnh);
			$stm->bindValue(':orgao_cnh', $orgao_cnh);	
			$stm->bindValue(':senha_sisgcorp', $senha_sisgcorp);
			$stm->bindValue(':tipo_sanguineo', $tipo_sanguineo);
			$stm->bindValue(':tituloeleitoral', $tituloeleitoral);
			$stm->bindValue(':url', $url);
			$stm->bindValue(':motivo', $motivo);			
			$stm->bindValue(':ocorrencias', $ocorrencias);	
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);
			$stm->bindValue(':segundo_complemento', $segundo_complemento);			
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
			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0 && strlen(trim($image_webcam)) <= 0): 

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
			 	if(strlen(trim($image_webcam)) > 0){
			 	    unlink("fotos/" . $foto_atual);
                    $nome_foto = date('dmY') . '_' . md5(date('Y-m-dH:i:s').microtime(true).rand(0,9999)).'.png';
                    $ifp = fopen('fotos/'.$nome_foto, 'wb');
                    fwrite($ifp, base64_decode($image_webcam));
                    fclose($ifp);
                }else{
                    $nome_foto = $foto_atual;   
                }

			endif;
               
            $conexao = conexao::getInstance();
            $sql_membro = "SELECT * FROM tab_membros WHERE id = :id";
            $stm = $conexao->prepare($sql_membro);
            $stm->bindParam(':id', $id);
            $stm->execute();
            $membro = $stm->fetch(PDO::FETCH_OBJ);
            
            //Dados do operador    
            $sql_user = "SELECT * FROM users WHERE username = :name";
            $stm = $conexao->prepare($sql_user);
            $stm->bindParam(':name', $user, PDO::PARAM_STR);
            $stm->execute();
            $usuario = $stm->fetch(PDO::FETCH_OBJ);
            
            //inserção do LOg
            $dados_post=$_POST;
            
          foreach($dados_post as $campo => $valor_novo){
          $valor_atual = $membro->$campo ?? null;
            if ($valor_atual !== $valor_novo) {
            $dados_alterados[$campo] = [
                'anterior' => $valor_atual,
                'novo' => $valor_novo
            ];
        }
          }
    
    $alteracao = json_encode($dados_alterados, JSON_UNESCAPED_UNICODE);
    
       $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
            			   VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';

			$stm = $conexao->prepare($sql_log);
			$stm->bindValue(':username', $usuario->name);
			$stm->bindValue(':tabela', 'tab_membros');
			$stm->bindValue(':tipo_alteracao', 'edicao');
				$stm->bindValue(':registro_id', $id);
		  // Converte o array para JSON
           
            $stm->bindValue(':registro', $alteracao);
	$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();

			$sql = 'UPDATE tab_membros SET id=:id, matricula=:matricula, data_filiacao=:data_filiacao, data_renovacao=:data_renovacao, categoria=:categoria, nivel=:nivel, bloqueio=:bloqueio,envios_auto=:envios_auto, plano_pgto=:plano_pgto, plano=:plano, cr=:cr, cr_emissao=:cr_emissao, validade_cr=:validade_cr, nome=:nome, rua=:rua,  numero=:numero, bairro=:bairro, complemento=:complemento, cep=:cep, cidade=:cidade, siglauf=:siglauf, estadocivil=:estadocivil, naturalidade=:naturalidade, nacionalidade=:nacionalidade, data_nascimento=:data_nascimento, profissao=:profissao, email=:email, instagram=:instagram, telefone=:telefone, pai=:pai, mae=:mae, cpf=:cpf, identidade=:identidade, orgaouf=:orgaouf, data_exped=:data_exped, cnh=:cnh, data_exped_cnh=:data_exped_cnh, orgao_cnh=:orgao_cnh, senha_sisgcorp=:senha_sisgcorp, tipo_sanguineo=:tipo_sanguineo, tituloeleitoral=:tituloeleitoral, ocorrencias=:ocorrencias, segundo_cep=:segundo_cep, segundo_rua=:segundo_rua, segundo_num=:segundo_num, segundo_complemento=:segundo_complemento, segundo_bairro=:segundo_bairro, segundo_cidade=:segundo_cidade, segundo_estado=:segundo_estado, foto=:foto ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);

			$stm->bindValue(':id', $id);
			$stm->bindValue(':matricula', $matricula);
			$stm->bindValue(':data_filiacao', $data_filiacao);
			$stm->bindValue(':data_renovacao', $data_renovacao);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':nivel', $nivel);			
			$stm->bindValue(':bloqueio', $bloqueio);
			$stm->bindValue(':envios_auto', $msg_auto);
			$stm->bindValue(':plano_pgto', $plano_pgto);
			$stm->bindValue(':plano', $plano);			
			$stm->bindValue(':cr', $cr);
			$stm->bindValue(':cr_emissao', $cr_emissao);			
			$stm->bindValue(':validade_cr', $validade_cr);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':rua', $rua);
			$stm->bindValue(':numero', $numero);
			$stm->bindValue(':bairro', $bairro);
			$stm->bindValue(':complemento', $complemento);			
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
			$stm->bindValue(':cnh', $cnh);
			$stm->bindValue(':data_exped_cnh', $data_exped_cnh);
			$stm->bindValue(':orgao_cnh', $orgao_cnh);
			$stm->bindValue(':senha_sisgcorp', $senha_sisgcorp);
			$stm->bindValue(':tipo_sanguineo', $tipo_sanguineo);
			$stm->bindValue(':tituloeleitoral', $tituloeleitoral);
			$stm->bindValue(':ocorrencias', $ocorrencias);	
			$stm->bindValue(':segundo_cep', $segundo_cep);
			$stm->bindValue(':segundo_rua', $segundo_rua);
			$stm->bindValue(':segundo_num', $segundo_num);
			$stm->bindValue(':segundo_complemento', $segundo_complemento);		
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