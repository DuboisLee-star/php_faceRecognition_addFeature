<?php 
		require 'config/conexao.php';

		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submissão
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$apelido           = (isset($_POST['apelido'])) ? $_POST['apelido'] : '';		
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$rua               = (isset($_POST['rua'])) ? $_POST['rua'] : '';
		$numero            = (isset($_POST['numero'])) ? $_POST['numero'] : '';
		$bairro            = (isset($_POST['bairro'])) ? $_POST['bairro'] : '';
        $cep               = (isset($_POST['cep'])) ? $_POST['cep'] : '';
		$cidade            = (isset($_POST['cidade'])) ? $_POST['cidade'] : '';
		$siglauf           = (isset($_POST['siglauf'])) ? $_POST['siglauf'] : '';
		$estadocivil       = (isset($_POST['estadocivil'])) ? $_POST['estadocivil'] : '';
		$naturalidade      = (isset($_POST['naturalidade'])) ? $_POST['naturalidade'] : '';
		$telefone          = (isset($_POST['telefone'])) ? $_POST['telefone'] : '';
		$email             = (isset($_POST['email'])) ? $_POST['email'] : '';
		$ufnasc            = (isset($_POST['ufnasc'])) ? $_POST['ufnasc'] : '';
		$nacionalidade     = (isset($_POST['nacionalidade'])) ? $_POST['nacionalidade'] : '';
		$cpf               = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
		$identidade        = (isset($_POST['identidade'])) ? $_POST['identidade'] : '';

		// Verifica se foi solicitada a inclusÃ£o de dados
		if ($acao == 'incluir'):

        $sql = 'INSERT INTO tab_procuradores (apelido, nome, rua, numero, bairro, cep, cidade, siglauf, estadocivil, naturalidade, telefone, email, ufnasc, nacionalidade, cpf, identidade)
			   VALUES(:apelido, :nome, :rua, :numero, :bairro, :cep, :cidade, :siglauf, :estadocivil, :naturalidade, :telefone, :email, :ufnasc, :nacionalidade, :identidade)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':apelido', $apelido);			
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':rua', $rua);
			$stm->bindValue(':numero', $numero);
			$stm->bindValue(':bairro', $bairro);
			$stm->bindValue(':cep', $cep);
			$stm->bindValue(':cidade', $cidade);
			$stm->bindValue(':siglauf', $siglauf);
			$stm->bindValue(':estadocivil', $estadocivil);
			$stm->bindValue(':naturalidade', $naturalidade);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':ufnasc', $ufnasc);
			$stm->bindValue(':nacionalidade', $nacionalidade);
			$stm->bindValue(':cpf', $cpf);			
			$stm->bindValue(':identidade', $identidade);

            $retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=cadastro_procuradores.php'>";
		endif;

            // Verifica se foi solicitada a edição de dados
		    if ($acao == 'editar'):

		    $sql = 'UPDATE tab_procuradores SET id=:id, apelido=:apelido, nome=:nome, rua=:rua, numero=:numero, bairro=:bairro, cep=:cep, cidade=:cidade, siglauf=:siglauf, estadocivil=:estadocivil, naturalidade=:naturalidade, telefone=:telefone, email=:email, ufnasc=:ufnasc, nacionalidade=:nacionalidade, cpf=:cpf, identidade=:identidade';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':apelido', $apelido);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':rua', $rua);
			$stm->bindValue(':numero', $numero);
			$stm->bindValue(':bairro', $bairro);
			$stm->bindValue(':cep', $cep);
			$stm->bindValue(':cidade', $cidade);
			$stm->bindValue(':siglauf', $siglauf);
			$stm->bindValue(':estadocivil', $estadocivil);
			$stm->bindValue(':naturalidade', $naturalidade);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':ufnasc', $ufnasc);
			$stm->bindValue(':nacionalidade', $nacionalidade);
			$stm->bindValue(':cpf', $cpf);			
			$stm->bindValue(':identidade', $identidade);
			
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'></div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='0;URL=cadastro_procuradores.php'>";
		endif;

		?>
