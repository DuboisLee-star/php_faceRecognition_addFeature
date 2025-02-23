<?php 
		require 'config/conexao.php';

		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão
		$acao                 = 1;	
		$id =1;
		$sigla_clube          = (isset($_POST['sigla_clube'])) ? $_POST['sigla_clube'] : '';
		$clube_nome           = (isset($_POST['clube_nome'])) ? $_POST['clube_nome'] : '';		
		$clube_cr             = (isset($_POST['clube_cr'])) ? $_POST['clube_cr'] : '';
		$clube_validade_cr    = (isset($_POST['clube_validade_cr'])) ? $_POST['clube_validade_cr'] : '';	
		$clube_cnpj           = (isset($_POST['clube_cnpj'])) ? $_POST['clube_cnpj'] : '';	
		$clube_endereco       = (isset($_POST['clube_endereco'])) ? $_POST['clube_endereco'] : '';	
		$clube_telefone       = (isset($_POST['clube_telefone'])) ? $_POST['clube_telefone'] : '';
		$clube_email          = (isset($_POST['clube_email'])) ? $_POST['clube_email'] : '';	
		$clube_email_om       = (isset($_POST['clube_email_om'])) ? $_POST['clube_email_om'] : '';
		
		$url                  = (isset($_POST['url'])) ? $_POST['url'] : '';	
		$urlapp               = (isset($_POST['urlapp'])) ? $_POST['urlapp'] : '';			

		$termos_filiacao      = (isset($_POST['termos_filiacao'])) ? $_POST['termos_filiacao'] : '';	
		$termos_idoneidade    = (isset($_POST['termos_idoneidade'])) ? $_POST['termos_idoneidade'] : '';	
		$termos_estatuto      = (isset($_POST['termos_estatuto'])) ? $_POST['termos_estatuto'] : '';
		$clube_logo           = (isset($_POST['clube_logo'])) ? $_POST['clube_logo'] : '';	
		$foto_atual           = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';	
		$id_autentique        = (isset($_POST['id_autentique'])) ? $_POST['id_autentique'] : '';	
		$tipo_habitualidade   = (isset($_POST['tipo_habitualidade'])) ? $_POST['tipo_habitualidade'] : '';
		$latitude             = (isset($_POST['latitude'])) ? $_POST['latitude'] : '';
		$longitude            = (isset($_POST['longitude'])) ? $_POST['longitude'] : '';
		
		$plano                        = (isset($_POST['plano'])) ? $_POST['plano'] : '';	
		$forma_plano                  = (isset($_POST['forma_plano'])) ? $_POST['forma_plano'] : '';
		$biometria                    = (isset($_POST['biometria'])) ? $_POST['biometria'] : '';
		$facial                       = (isset($_POST['facial'])) ? $_POST['facial'] : '';
		$v_valor_plano_ouro           = (isset($_POST['valor_plano_ouro'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_ouro'])) : '';	
		$v_valor_plano_prata          = (isset($_POST['valor_plano_prata'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_prata'])) : '';	
		$v_valor_plano_bronze         = (isset($_POST['valor_plano_bronze'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_bronze'])) : '';	
		$v_nome_plano_personalizado   = (isset($_POST['nome_plano_personalizado'])) ? trim($_POST['nome_plano_personalizado']) : '';	
		$v_valor_plano_personalizado  = (isset($_POST['valor_plano_personalizado'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_personalizado'])) : '';	
		$v_valor_plano_fixo_anual     = (isset($_POST['valor_plano_fixo_anual'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_fixo_anual'])) : '';
		$v_valor_plano_fixo_mensal    = (isset($_POST['valor_plano_fixo_mensal'])) ? str_replace(',', '.', str_replace('.', '', $_POST['valor_plano_fixo_mensal'])) : '';
		
		$valor_plano_ouro = "";
		$valor_plano_prata = "";
		$valor_plano_bronze = "";
		$nome_plano_personalizado = "";
		$valor_plano_personalizado = "";
		$valor_plano_fixo = "";
		
		switch($plano){
			case "todos":
				$valor_plano_ouro = $v_valor_plano_ouro;
				$valor_plano_prata = $v_valor_plano_prata;
				$valor_plano_bronze = $v_valor_plano_bronze;
			break;
			case "personalizado":
				$nome_plano_personalizado = $v_nome_plano_personalizado;
				$valor_plano_personalizado = $v_valor_plano_personalizado;
			break;
			case "fixo":
				$valor_plano_fixo_anual = $v_valor_plano_fixo_anual;
				$valor_plano_fixo_mensal = $v_valor_plano_fixo_mensal;
			break;
			default:
				$plano = "semplano";
			break;
		}
		
		
		//$compra_data = DateTime::createFromFormat ("d/m/Y", $compra_data)->format( "Y-m-d" );

		
            // Verifica se foi solicitada a edição de dados
		    if ($acao == 1):
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

			          $foto_atual = date('dmY') . '_' . $_FILES['foto']['name'];  

			            

			          // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  

			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$foto_atual)):  

			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  

			          endif;  

			     endif;

			else:



			 	



			endif;
			
			$nome_foto = $foto_atual;


			$sql = 'UPDATE info_clube SET sigla_clube=:sigla_clube, clube_nome=:clube_nome, clube_cr=:clube_cr, clube_validade_cr=:clube_validade_cr, clube_cnpj=:clube_cnpj, clube_endereco=:clube_endereco, clube_telefone=:clube_telefone, clube_email=:clube_email, clube_email_om=:clube_email_om, url=:url, urlapp=:urlapp, forma_plano=:forma_plano, biometria=:biometria, facial=:facial, termos_filiacao=:termos_filiacao, termos_estatuto=:termos_estatuto, termos_idoneidade=:termos_idoneidade, clube_logo=:clube_logo, plano=:plano, valor_plano_ouro=:valor_plano_ouro, valor_plano_prata=:valor_plano_prata, valor_plano_bronze=:valor_plano_bronze, nome_plano_personalizado=:nome_plano_personalizado, valor_plano_personalizado=:valor_plano_personalizado, valor_plano_fixo_anual=:valor_plano_fixo_anual, valor_plano_fixo_mensal=:valor_plano_fixo_mensal, id_autentique=:id_autentique, tipo_habitualidade=:tipo_habitualidade, latitude=:latitude, longitude=:longitude WHERE id = :id';
			
			$stm = $conexao->prepare($sql);
	        $stm->bindValue(':id', $id);
			$stm->bindValue(':sigla_clube', $sigla_clube);	                
			$stm->bindValue(':clube_nome', $clube_nome);			
			$stm->bindValue(':clube_cr', $clube_cr);
			$stm->bindValue(':clube_validade_cr', $clube_validade_cr);
			$stm->bindValue(':clube_cnpj', $clube_cnpj);
			$stm->bindValue(':clube_endereco', $clube_endereco);
			$stm->bindValue(':clube_telefone', $clube_telefone);			
			$stm->bindValue(':clube_email', $clube_email);	
			$stm->bindValue(':clube_email_om', $clube_email_om);

			$stm->bindValue(':url', $url);
			$stm->bindValue(':urlapp', $urlapp);			
			
			$stm->bindValue(':termos_filiacao', $termos_filiacao);
			$stm->bindValue(':termos_estatuto', $termos_estatuto);
			$stm->bindValue(':termos_idoneidade', $termos_idoneidade);
			$stm->bindValue(':clube_logo', $nome_foto);		
			$stm->bindValue(':plano', $plano);	
			$stm->bindValue(':forma_plano', $forma_plano);
			$stm->bindValue(':biometria', $biometria);
			$stm->bindValue(':facial', $facial);
			$stm->bindValue(':valor_plano_ouro', $valor_plano_ouro);		
			$stm->bindValue(':valor_plano_prata', $valor_plano_prata);		
			$stm->bindValue(':valor_plano_bronze', $valor_plano_bronze);		
			$stm->bindValue(':nome_plano_personalizado', $nome_plano_personalizado);		
			$stm->bindValue(':valor_plano_personalizado', $valor_plano_personalizado);		
			$stm->bindValue(':valor_plano_fixo_anual', $valor_plano_fixo_anual);
			$stm->bindValue(':valor_plano_fixo_mensal', $valor_plano_fixo_mensal);
			$stm->bindValue(':id_autentique', $id_autentique);
			$stm->bindValue(':tipo_habitualidade', $tipo_habitualidade);
			$stm->bindValue(':latitude', $latitude);
			$stm->bindValue(':longitude', $longitude);

			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=configuracoes.php'>";
		endif;

		?>