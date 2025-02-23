<?php

include "config/config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}
?>

<?php

require 'config/conexao.php';
include_once ("config/url_painel.php");
include_once ("config/email_painel.php");

	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE plano_pgto LIKE "%A%" AND bloqueio LIKE "%Nao%" AND TIMESTAMPDIFF(DAY,data_renovacao,CURDATE()) = 15 OR plano_pgto LIKE "%A%" AND bloqueio LIKE "%Nao%"  AND TIMESTAMPDIFF(DAY,data_renovacao,CURDATE()) = 1 
	';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->data_nascimento);
		$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

	endif;


// dispara e-mail
$act = false;
$assunto_email = false;
$msg_email = false;
$email = false;
$nome = false;
$assunto_email = 'Renovação de Filiação';
 $msg_email =  'Estamos enviando esse comunicado para lembrar sobre a renovação de sua filiação ao seu Clube de Tiro. Compareça à secretaria para atualização de  documentos, registros e financeiro (anuidade ou mensalidade). Para mais informações procure à secretaria. Obrigado!';


	
	//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
 	//====================================================
	$email_remetente = $email_rementente_mun; // deve ser um email do dominio
	//====================================================

	//ConfiguraÃ§Ãµes do email, ajustar conforme necessidade
	//====================================================
	#$email_destinatario = $email; // qualquer email pode receber os dados
	$email_reply = $email_reply_mun;
	$email_assunto = $assunto_email;
	//====================================================
	
	//Monta o Corpo da Mensagem
	//====================================================
	$email_conteudo =  $msg_email;
 	//====================================================
 
	//Seta os Headers (Alerar somente caso necessario)
	//====================================================
	$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
	//====================================================
 
 
	//Enviando o email
	//====================================================
	$result_email = false;
	
	foreach($clientes as $cliente){
		if (mail ($cliente->email, $email_assunto, nl2br($email_conteudo), $email_headers)){
			$result_email = true;
		}
	}
	
	
	if ($result_email){
		echo '<script>window.location.href = "index.php";</script>';
		
	}
  	else{
		$_SESSION['msg_ok'] = 2;
	}
	//====================================================
	
	echo '<script>window.location=window.location.href;</script>';
	exit();

?>
		<!-- container section end -->
  <!-- javascripts -->
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>


</body>

</html>