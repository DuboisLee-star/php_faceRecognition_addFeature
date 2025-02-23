<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>HOSTMARQ</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<div class='container box-mensagem-crud'>
		<?php 
		require 'config/conexao.php';

		// Atribui uma conex„o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss„o
		$acao               = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                 = (isset($_POST['id'])) ? $_POST['id'] : '';
		$nome               = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$matricula          = (isset($_POST['matricula'])) ? $_POST['matricula'] : false;
		$compra_data        = (isset($_POST['compra_data'])) ? $_POST['compra_data'] : false;
		$compra_descricao   = (isset($_POST['compra_descricao'])) ? $_POST['compra_descricao'] : false;
		$compra_calibre     = (isset($_POST['compra_calibre'])) ? $_POST['compra_calibre'] : false;
		$compra_qtdecalibre = (isset($_POST['compra_qtdecalibre'])) ? $_POST['compra_qtdecalibre'] : false;
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
		$acao = "incluir";
		if ($acao == 'incluir'):
		
			$sql = 'DELETE FROM tab_municoesclube WHERE matricula = :matricula';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':matricula', $matricula);
			$retorno = $stm->execute();

			$sql = 'INSERT INTO tab_municoesclube (matricula, compra_data, compra_descricao, compra_calibre, compra_qtdecalibre)
				   VALUES(:matricula, :compra_data, :compra_descricao, :compra_calibre, :compra_qtdecalibre)';

			if($compra_data){
				foreach($compra_data as $idx => $value){
					
					$stm = $conexao->prepare($sql);
					$stm->bindValue(':matricula', $matricula);
					$stm->bindValue(':compra_data', $compra_data[$idx]);
					$stm->bindValue(':compra_descricao', $compra_descricao[$idx]);
					$stm->bindValue(':compra_calibre', $compra_calibre[$idx]);
					$stm->bindValue(':compra_qtdecalibre', $compra_qtdecalibre[$idx]);
					$retorno = $stm->execute();
					
				}
			}


			if ($retorno):
				$_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><font color='993399'><strong>Registro atualizado</strong></font></div>";
		    else:
		    	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			#echo "<button onclick=history.go(-1);>Confirmar</button>";
			echo "<script>history.go(-1);</script>";
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

			// limpa registros
			$sql = 'DELETE FROM tab_municoesclube WHERE matricula = :matricula';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':matricula', $matricula);
			$retorno = $stm->execute();
			
			$sucesso = false;
			
			// grava habilutalidade
			if(is_array($compra_loja)){
				foreach($compra_loja as $key => $value){
					
					if($matricula){

						$compra_datacadastro = DateTime::createFromFormat ("d/m/Y H:i", $compra_data[$key])->format( "Y-m-d H:i" );
						
						$sql = " INSERT INTO tab_municoesclube (matricula, compra_data, compra_descricao, compra_calibre, compra_qtdecalibre, compra_datacadastro) VALUES (:matricula, :compra_data, :compra_descricao, :compra_calibre, :compra_qtdecalibre, :compra_datacadastro) ";
						$stm = $conexao->prepare($sql);
						$stm->bindValue(':matricula', $matricula);
						$stm->bindValue(':compra_descricao', $compra_descricao[$key]);
						$stm->bindValue(':compra_calibre', $compra_calibre[$key]);
						$stm->bindValue(':compra_qtdecalibre', $compra_qtdecalibre[$key]);
						$stm->bindValue(':compra_datacadastro', date('Y-m-d H:i', strtotime($compra_datacadastro)));
						$stm->bindValue(':compra_data', date('Y-m-d', strtotime($compra_datacadastro)));
						$retorno = $stm->execute();
						
						if(!$sucesso){
							if($retorno) $sucesso = true;
						}
						
					}
					
				}
			}

			//$retorno = $stm->execute();

			if ($sucesso):
				$_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><font color='993399'><strong>Registro atualizado</strong></font></div>";
		    else:
		    	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";

			endif;

			#echo "<button onclick=history.go(-1);>Confirmar</button>";
			echo "<script>history.go(-1);</script>";
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
				$_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><font color='993399'><strong>Registro atualizado</strong></font></div>";
		    else:
		    	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";
			endif;

			#echo "<button onclick=history.go(-1);>Confirmar</button>";
			echo "<script>history.go(-1);</script>";
		endif;
		?>

	</div>
</body>
</html>