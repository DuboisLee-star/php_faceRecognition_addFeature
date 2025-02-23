<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Sistemas para Clubes de Tiro</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<div class='container box-mensagem-crud'>
		<?php 
		require 'config/conexao.php';

		// Atribui uma conex�o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss�o
        $acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$matricula         = (isset($_POST['matricula'])) ? $_POST['matricula'] : '';		

		$anuidade  = (isset($_POST['valor_anuidade'])) ? $_POST['valor_anuidade'] : '';
		$data_anuidade = (isset($_POST['data_pgto_anuidade'])) ? $_POST['data_pgto_anuidade'] : '';
		$forma_pgto_anuidade= (isset($_POST['forma_pgto_anuidade'])) ? $_POST['forma_pgto_anuidade'] : '';
		$obs_anuidade = (isset($_POST['obs_anuidade'])) ? $_POST['obs_anuidade'] : '';
		
				
		
		$mensalidade      = (isset($_POST['valor_mensalidade'])) ? $_POST['valor_mensalidade'] : '';
		
		$forma_pgto_mensalidade = (isset($_POST['forma_pgto_mensalidade'])) ? $_POST['forma_pgto_mensalidade'] : '';
	
		
		$data_mensalidade = (isset($_POST['data_pgto_mensalidade'])) ? $_POST['data_pgto_mensalidade'] : '';
		

		$obs_mensalidade = (isset($_POST['obs_mensalidade'])) ? $_POST['obs_mensalidade'] : '';
		
		
		
		
		
		// verifica se já existe registro
    	$conexao = conexao::getInstance();
    	$sql = 'SELECT COUNT(id) qtde FROM tab_financeiro WHERE matricula = :matricula';
    	$stm = $conexao->prepare($sql);
    	$stm->bindValue(':matricula', $matricula);
    	$stm->execute();
    	$existe = $stm->fetch(PDO::FETCH_OBJ);
    	if($existe->qtde <= 0) $acao = 'incluir';
    	

		// Valida os dados recebidos
		/*$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do Registros desconhecido.</li>';
	        endif;
 
	        // Se for a��o diferente de excluir valida os dados obrigat�rios
	        if ($acao != 'excluir'):

                        if ($nome == '' || strlen($nome) < 3):
		        $mensagem .= '<li>Nome n o informado.</li>';
		endif;*/


		// Verifica se foi solicitada a inclus�o de dados
		if ($acao == 'incluir'):


            $sql = 'INSERT INTO tab_financeiro (id_membro, matricula, valor_anuidade, data_pgto_anuidade, forma_pgto_anuidade, obs_anuidade, valor_mensalidade, data_pgto_mensalidade, forma_pgto_mensalidade, obs_mensalidade)
			                             VALUES(:id_membro, :matricula, :anuidade, :data_anuidade, :forma_pgto_anuidade, :obs_anuidade,  :mensalidade, :data_mensalidade,  :forma_pgto_mensalidade, :obs_mensalidade)';

			$stm = $conexao->prepare($sql);
			
			//$stm->bindValue(':id', $id);
			$stm->bindValue(':id_membro', $id);
			$stm->bindValue(':matricula', $matricula);
			
			$stm->bindValue(':anuidade', $anuidade);
			$stm->bindValue(':data_anuidade', $data_anuidade);			
			$stm->bindValue(':forma_pgto_anuidade', $forma_pgto_anuidade);			
			$stm->bindValue(':obs_anuidade', $obs_anuidade);			
			
			$stm->bindValue(':mensalidade', $mensalidade);
			$stm->bindValue(':data_mensalidade', $data_mensalidade);			
			$stm->bindValue(':forma_pgto_mensalidade', $forma_pgto_mensalidade);			
			$stm->bindValue(':obs_mensalidade', $obs_mensalidade);	
			       
			
            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		endif;


		// Verifica se foi solicitada a edi��o de dados
		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto � diferente da padr�o, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.jpg'):
					unlink("fotos/" . $foto_atual);
				endif;

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extens�o do arquivo � aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extens�o Inv�lida!</h1>";
			       exit;
			    endif;

 
			     // Verifica se o upload foi enviado via POST   
			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):  
			             
			          // Verifica se o diret�rio de destino existe, sen�o existir cria o diret�rio  
			          if(!file_exists("fotos")):  
			               mkdir("fotos");  
			          endif;  
			  
			          // Monta o caminho de destino com o nome do arquivo  
			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  
			            
			          // Essa fun��o move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			

			endif;

			$sql = 'UPDATE tab_financeiro SET matricula=:matricula, valor_anuidade=:anuidade, data_pgto_anuidade=:data_anuidade,  forma_pgto_anuidade=:forma_pgto_anuidade,  obs_anuidade=:obs_anuidade, valor_mensalidade=:mensalidade, data_pgto_mensalidade=:data_mensalidade, forma_pgto_mensalidade=:forma_pgto_mensalidade, obs_mensalidade=:obs_mensalidade';

			$sql .= ' WHERE matricula = :matricula';

			$stm = $conexao->prepare($sql);

			//$stm->bindValue(':id', $id);
			$stm->bindValue(':id_membro', $id);
			$stm->bindValue(':matricula', $matricula);
			
			$stm->bindValue(':anuidade', $anuidade);
			$stm->bindValue(':data_anuidade', $data_anuidade);			
			$stm->bindValue(':forma_pgto_anuidade', $forma_pgto_anuidade);			
			$stm->bindValue(':obs_anuidade', $obs_anuidade);			
			
			$stm->bindValue(':mensalidade', $mensalidade);
			$stm->bindValue(':data_mensalidade', $data_mensalidade);			
			$stm->bindValue(':forma_pgto_mensalidade', $forma_pgto_mensalidade);			
			$stm->bindValue(':obs_mensalidade', $obs_mensalidade);	         
            
			$retorno = $stm->execute();
			
// echo '<pre>';// print_r($_POST);// var_dump($retorno);// exit();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		endif;



			echo "<meta http-equiv=refresh content='0;URL=adminfinanceiro.php'>";
		
		?>

	</div>
</body>
</html>