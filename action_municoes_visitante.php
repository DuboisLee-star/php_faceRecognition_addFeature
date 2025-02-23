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

		// Atribui uma conex�o PDO
		$conexao = conexao::getInstance();

		// Recebe os dados enviados pela submiss�o
		$acao              = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$id                = (isset($_POST['id'])) ? $_POST['id'] : '';
		$nome              = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$matricula         = (isset($_POST['matricula'])) ? $_POST['matricula'] : false;

		$habitu_local1     = (isset($_POST['habitu_local1'])) ? $_POST['habitu_local1'] : '';
		$habitu_data1      = (isset($_POST['habitu_data1'])) ? $_POST['habitu_data1'] : '';
		$habitu_evento1    = (isset($_POST['habitu_evento1'])) ? $_POST['habitu_evento1'] : '';

		$habitu_local2     = (isset($_POST['habitu_local2'])) ? $_POST['habitu_local2'] : '';
		$habitu_data2      = (isset($_POST['habitu_data2'])) ? $_POST['habitu_data2'] : '';
		$habitu_evento2    = (isset($_POST['habitu_evento2'])) ? $_POST['habitu_evento2'] : '';

		$habitu_local3     = (isset($_POST['habitu_local3'])) ? $_POST['habitu_local3'] : '';
		$habitu_data3      = (isset($_POST['habitu_data3'])) ? $_POST['habitu_data3'] : '';
		$habitu_evento3    = (isset($_POST['habitu_evento3'])) ? $_POST['habitu_evento3'] : '';

		$habitu_local4     = (isset($_POST['habitu_local4'])) ? $_POST['habitu_local4'] : '';
		$habitu_data4      = (isset($_POST['habitu_data4'])) ? $_POST['habitu_data4'] : '';
		$habitu_evento4    = (isset($_POST['habitu_evento4'])) ? $_POST['habitu_evento4'] : '';

		$habitu_local5     = (isset($_POST['habitu_local5'])) ? $_POST['habitu_local5'] : '';
		$habitu_data5      = (isset($_POST['habitu_data5'])) ? $_POST['habitu_data5'] : '';
		$habitu_evento5    = (isset($_POST['habitu_evento5'])) ? $_POST['habitu_evento5'] : '';

		$habitu_local6     = (isset($_POST['habitu_local6'])) ? $_POST['habitu_local6'] : '';
		$habitu_data6      = (isset($_POST['habitu_data6'])) ? $_POST['habitu_data6'] : '';
		$habitu_evento6    = (isset($_POST['habitu_evento6'])) ? $_POST['habitu_evento6'] : '';

		$habitu_local7     = (isset($_POST['habitu_local7'])) ? $_POST['habitu_local7'] : '';
		$habitu_data7      = (isset($_POST['habitu_data7'])) ? $_POST['habitu_data7'] : '';
		$habitu_evento7    = (isset($_POST['habitu_evento7'])) ? $_POST['habitu_evento7'] : '';

		$habitu_local8     = (isset($_POST['habitu_local8'])) ? $_POST['habitu_local8'] : '';
		$habitu_data8      = (isset($_POST['habitu_data8'])) ? $_POST['habitu_data8'] : '';
		$habitu_evento8    = (isset($_POST['habitu_evento8'])) ? $_POST['habitu_evento8'] : '';
		$foto_atual	   = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';

		$compra_data         = (isset($_POST['compra_data'])) ? $_POST['compra_data'] : false;
		$compra_loja         = (isset($_POST['compra_local'])) ? $_POST['compra_local'] : false;
		$compra_nf           = (isset($_POST['compra_evento'])) ? $_POST['compra_evento'] : false;
		$compra_calibre      = (isset($_POST['compra_arma'])) ? $_POST['compra_arma'] : false;
		$compra_qtdecalibre  = (isset($_POST['compra_calibre'])) ? $_POST['compra_calibre'] : false;
		$compra_qtdeinsumos  = (isset($_POST['compra_qtde'])) ? $_POST['compra_qtde'] : false;
		$notifica_exercito  = ($_POST['notifica_exercito'] == 1) ? $_POST['notifica_exercito'] : false;
		
		
		$anexo_pdf = false;
		if(isset($_FILES['anexo_pdf'])){
			if($_FILES['anexo_pdf']['size'] > 0){
				$anexo_pdf = $_FILES['anexo_pdf'];
			}
		}
		
		// dados atirador
		$sql = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		// dados atirados

		// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $id == ''):
		    $mensagem .= '<li>ID do registros desconhecido.</li>';
	    endif;

	        // Se for ação diferente de excluir valida os dados obrigatórios
	        if ($acao != 'excluir'):

						if ($mensagem != ''):
				$mensagem = '<ul>' . $mensagem . '</ul>';
				echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";
				exit;
			endif;


                        // Constr�i a data no formato ANSI yyyy/mm/dd
			$data_temp = explode('/', $data_nascimento);
			$data_ansi = $data_temp[2] . '/' . $data_temp[1] . '/' . $data_temp[0];
		endif;



		// Verifica se foi solicitada a inclusão de dados
		if ($acao == 'incluir'):

			$nome_foto = 'padrao.jpg';
			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0):  

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extensão do arquivo é aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extensão Inválida!</h1>";
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
			            
			          // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			else:

			 	$nome_foto = $foto_atual;

			endif;

			$sql = 'INSERT INTO tab_membros (id, nome, habitu_local1, habitu_data1, habitu_evento1, habitu_local2, habitu_data2, habitu_evento2, habitu_local3, habitu_data3, habitu_evento3, habitu_local4, habitu_data4, habitu_evento4, habitu_local5, habitu_data5, habitu_evento5, habitu_local6, habitu_data6, habitu_evento6, habitu_local7, habitu_data7, habitu_evento7, habitu_local8, habitu_data8, habitu_evento8, foto)
				   VALUES(:id, :nome, :habitu_local1, :habitu_data1, :habitu_evento1, :habitu_local2, :habitu_data2, :habitu_evento2, :habitu_local3, :habitu_data3, :habitu_evento3, :habitu_local4, :habitu_data4, :habitu_evento4, :habitu_local5, :habitu_data5, :habitu_evento5, :habitu_local6, :habitu_data6, :habitu_evento6, :habitu_local7, :habitu_data7, :habitu_evento7, :habitu_local8, :habitu_data8, :habitu_evento8, :foto)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':nome', $nome);
			/*$stm->bindValue(':habitu_local1', $habitu_local1);
			$stm->bindValue(':habitu_data1', $habitu_data1);
			$stm->bindValue(':habitu_evento1', $habitu_evento1);
			$stm->bindValue(':habitu_local2', $habitu_local2);
			$stm->bindValue(':habitu_data2', $habitu_data2);
			$stm->bindValue(':habitu_evento2', $habitu_evento2);
			$stm->bindValue(':habitu_local3', $habitu_local3);
			$stm->bindValue(':habitu_data3', $habitu_data3);
			$stm->bindValue(':habitu_evento3', $habitu_evento3);
			$stm->bindValue(':habitu_local4', $habitu_local4);
			$stm->bindValue(':habitu_data4', $habitu_data4);
			$stm->bindValue(':habitu_evento4', $habitu_evento4);
			$stm->bindValue(':habitu_local5', $habitu_local5);
			$stm->bindValue(':habitu_data5', $habitu_data5);
			$stm->bindValue(':habitu_evento5', $habitu_evento5);
			$stm->bindValue(':habitu_local6', $habitu_local6);
			$stm->bindValue(':habitu_data6', $habitu_data6);
			$stm->bindValue(':habitu_evento6', $habitu_evento6);
			$stm->bindValue(':habitu_local7', $habitu_local7);
			$stm->bindValue(':habitu_data7', $habitu_data7);
			$stm->bindValue(':habitu_evento7', $habitu_evento7);
			$stm->bindValue(':habitu_local8', $habitu_local8);
			$stm->bindValue(':habitu_data8', $habitu_data8);
			$stm->bindValue(':habitu_evento8', $habitu_evento8);*/
			$stm->bindValue(':foto', $nome_foto);
            $retorno = $stm->execute();
			
			// grava habilutalidade
			if(!$compra_loja){
				foreach($compra_loja as $key){
					print_r($compra_loja[$key]);
					exit();
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

			 	$nome_foto = $foto_atual;

			endif;

			/*$sql = 'UPDATE tab_membros SET id=:id, habitu_local1=:habitu_local1, habitu_data1=:habitu_data1, habitu_evento1=:habitu_evento1, habitu_local2=:habitu_local2, habitu_data2=:habitu_data2, habitu_evento2=:habitu_evento2, habitu_local3=:habitu_local3, habitu_data3=:habitu_data3, habitu_evento3=:habitu_evento3, habitu_local4=:habitu_local4, habitu_data4=:habitu_data4, habitu_evento4=:habitu_evento4, habitu_local5=:habitu_local5, habitu_data5=:habitu_data5, habitu_evento5=:habitu_evento5, habitu_local6=:habitu_local6, habitu_data6=:habitu_data6, habitu_evento6=:habitu_evento6, habitu_local7=:habitu_local7, habitu_data7=:habitu_data7, habitu_evento7=:habitu_evento7, habitu_local8=:habitu_local8, habitu_data8=:habitu_data8, habitu_evento8=:habitu_evento8, foto=:foto ';

			$sql .= 'WHERE id = :id';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':id', $id);
			$stm->bindValue(':habitu_local1', $habitu_local1);
			$stm->bindValue(':habitu_data1', $habitu_data1);
			$stm->bindValue(':habitu_evento1', $habitu_evento1);
			$stm->bindValue(':habitu_local2', $habitu_local2);
			$stm->bindValue(':habitu_data2', $habitu_data2);
			$stm->bindValue(':habitu_evento2', $habitu_evento2);
			$stm->bindValue(':habitu_local3', $habitu_local3);
			$stm->bindValue(':habitu_data3', $habitu_data3);
			$stm->bindValue(':habitu_evento3', $habitu_evento3);
			$stm->bindValue(':habitu_local4', $habitu_local4);
			$stm->bindValue(':habitu_data4', $habitu_data4);
			$stm->bindValue(':habitu_evento4', $habitu_evento4);
			$stm->bindValue(':habitu_local5', $habitu_local5);
			$stm->bindValue(':habitu_data5', $habitu_data5);
			$stm->bindValue(':habitu_evento5', $habitu_evento5);
			$stm->bindValue(':habitu_local6', $habitu_local6);
			$stm->bindValue(':habitu_data6', $habitu_data6);
			$stm->bindValue(':habitu_evento6', $habitu_evento6);
			$stm->bindValue(':habitu_local7', $habitu_local7);
			$stm->bindValue(':habitu_data7', $habitu_data7);
			$stm->bindValue(':habitu_evento7', $habitu_evento7);
			$stm->bindValue(':habitu_local8', $habitu_local8);
			$stm->bindValue(':habitu_data8', $habitu_data8);
			$stm->bindValue(':habitu_evento8', $habitu_evento8);
			
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();
			*/
			// limpa registros
			$sql = 'DELETE FROM tab_compras WHERE matricula = :matricula AND notificado IS NULL';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':matricula', $matricula);
			$retorno = $stm->execute();
			
			$sucesso = false;
			
			$compras = "";
			
			// grava habilutalidade
			if(is_array($compra_loja)){
				foreach($compra_loja as $key => $value){
					
					if($matricula){
						// $sql = " INSERT INTO tab_habitualidade (matricula, data, local, evento, tipo, calibre, qtdemunicoes, datacadastro) VALUES (:matriula, :data, :local, :evento, :tipo, :calibre, :qtdemunicoes, :datacadastro) ";
						// $stm = $conexao->prepare($sql);
						// $stm->bindValue(':matriula', $matricula);
						// $stm->bindValue(':data', $habitu_data[$key]);
						// $stm->bindValue(':local', $habitu_local[$key]);
						// $stm->bindValue(':evento', $habitu_evento[$key]);
						// $stm->bindValue(':tipo', $habitu_tipo[$key]);
						// $stm->bindValue(':calibre', $habitu_calibre[$key]);
						// $stm->bindValue(':qtdemunicoes', $habitu_qtdemunicoes[$key]);
						// $stm->bindValue(':datacadastro', date('Y-m-d H:i'));
						// $retorno = $stm->execute();
						
						$compra_datacadastro = DateTime::createFromFormat ("d/m/Y H:i", $compra_data[$key])->format( "Y-m-d H:i" );
						
						$sql = " INSERT INTO tab_compras (matricula, compra_data, compra_local, compra_evento, compra_arma, compra_calibre, compra_qtde, notificado) VALUES (:matricula, :compra_data, :compra_local, :compra_evento, :compra_arma, :compra_calibre, :compra_qtde, :notificado) ";
						// echo $sql.'<hr>';
						$stm = $conexao->prepare($sql);
						$stm->bindValue(':matricula', $matricula);
						$stm->bindValue(':compra_data', $compra_data[$key]);
						$stm->bindValue(':compra_local', $compra_local[$key]);
						$stm->bindValue(':compra_evento', $compra_evento[$key]);
						$stm->bindValue(':compra_arma', $compra_arma[$key]);
						$stm->bindValue(':compra_calibre', $compra_calibre[$key]);
						$stm->bindValue(':compra_qtde', $compra_qtde[$key]);
						$stm->bindValue(':notificado', (($notifica_exercito == 1) ? 1 : NULL));
						$retorno = $stm->execute();
						// $retorno = true;
						if($retorno){
							
							$produto = (strlen(trim($compra_qtdecalibre[$key])) > 0) ? $compra_calibre[$key] : $compra_insumos[$key];
							$quantidade = (strlen(trim($compra_qtdecalibre[$key])) > 0) ? $compra_qtdecalibre[$key] : $compra_qtdeinsumos[$key];
							$tipo = (strlen(trim($compra_qtdecalibre[$key])) > 0) ? "Muni&ccedil;&atilde;o" : "Insumo";
							
							$compras .= "<tr>
								<td bgcolor='#ffffff'>{$tipo}</td>
								<td bgcolor='#ffffff'>Data: {$compra_data[$key]} - N.F: {$compra_nf[$key]}</td>
								<td bgcolor='#ffffff'>".utf8_decode($produto)."</td>
								<td bgcolor='#ffffff'>{$quantidade}</td>
							</tr>";
						}
						
						if(!$sucesso){
							if($retorno) $sucesso = true;
						}
						
					}
					
				}
			}

			//$retorno = $stm->execute();
// exit();
			if ($sucesso):
				if($notifica_exercito == 1 && strlen(trim($compras)) > 0){
					
					$uid = md5(uniqid(time()));
					
					
					$compras = trim($compras);
					//------------------------------------------------------
					$email_remetente = "contatos@ctassu.com.br"; // deve ser um email do dominio
					$email_reply = "contatos@ctassu.com.br";
					$email_conteudo =  "
<!DOCTYPE html>
<html lang=\"pt-br\">
<head>
    <meta charset=\"UTF-8\">
					<body><style>td, th {border:1px solid #000000;}table {border-collapse: collapse;}</style>
<strong>Comunica&ccedil;&atilde;o de Compra de Atirador</strong><br>
<br>
<strong>N&ordm; CR do Atirador:</strong> {$cliente->cr}<br>
<strong>Nome completo do atirador:</strong> {$cliente->nome}<br>
<br>
<table cellspacing='1' cellpadding='3' bgcolor='#000000'>
<thead>
<tr>
<th bgcolor='#f0f0f0'><strong>Produto</strong></th>
<th bgcolor='#f0f0f0'><strong>Dados da Nota Fiscal (em anexo)</strong></th>
<th bgcolor='#f0f0f0'><strong>Calibre</strong></th>
<th bgcolor='#f0f0f0'><strong>Qtde</strong></th>
</tr>
</thead>
<tbody>
{$compras}
</tbody>
</table></body>
</html>

					";
					
					
					
					$email_assunto = "Comunicação de Compra de Atirador";
					// $email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
                    
					
					// attachment
					// if($anexo_pdf){
						
						// $filename = $anexo_pdf['name'];
						// $content = file_get_contents($anexo_pdf['tmp_name']);
						// $content = chunk_split(base64_encode($content));
						
						
						
						// $eol = "\n";
						// $email_conteudo .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
						// $email_conteudo .= "Content-Transfer-Encoding: base64" . $eol;
						// $email_conteudo .= "Content-Disposition: attachment" . $eol;
						// $email_conteudo .= $content . $eol;
						// $email_conteudo .= "--" . $separator . "--";
						
						// $email_headers .= $body;
						
					// }
					
					
					
					
					
					

					// header
					$header = "From: {$email_remetente}\r\n";
					$header .= "Reply-To:{$email_reply}\r\n";
					$header .= "Return-Path: {$email_remetente}\r\n";
					$header .= "X-Priority: 3\r\n";
					$header .= "MIME-Version: 1.0\r\n";
					$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

					// message & attachment
					$nmessage = "--".$uid."\r\n";
					$nmessage .= "Content-type:text/html; charset=UTF-8\r\n";
					$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$nmessage .= $email_conteudo."\r\n\r\n";
					$nmessage .= "--".$uid."\r\n";
					
					// anexo
					if($anexo_pdf){
						$content = file_get_contents($anexo_pdf['tmp_name']);
						$content = chunk_split(base64_encode($content));
						$file_name = basename($file);
						
						$nmessage .= "Content-Type: application/octet-stream; name=\"".$anexo_pdf['name']."\"\r\n";
						$nmessage .= "Content-Transfer-Encoding: base64\r\n";
						$nmessage .= "Content-Disposition: attachment; filename=\"".$anexo_pdf['name']."\"\r\n\r\n";
						$nmessage .= $content."\r\n\r\n";
						$nmessage .= "--".$uid."--";
					}
					
					
					
					// if (mail('werbermarques@gmail.com', $email_assunto, utf8_encode($nmessage), $header)){}
					if (mail('sfpc16bimtz@gmail.com', $email_assunto, utf8_encode($nmessage), $header)){}
					if (mail('$email', $email_assunto, utf8_encode($nmessage), $header)){}
					if (mail($cliente->email, $email_assunto, utf8_encode($nmessage), $header)){}
					
					
					
					
					
					// if (mail ('sfpc16bimtz@gmail.com', ($email_assunto), utf8_encode($email_conteudo), $email_headers)){}
					// if (mail ($cliente->email, ($email_assunto), utf8_encode($email_conteudo), $email_headers)){}
					//------------------------------------------------------
					
				}
				$_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><font color='993399'><strong>Registro atualizado</strong></font></div>";
		    else:
		    	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";

			endif;

			#echo "<button onclick=history.go(-1);>Confirmar</button>";
			echo "<script>history.go(-1);</script>";
		endif;

        // Verifica se foi solicitada a exclus�o dos dados
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