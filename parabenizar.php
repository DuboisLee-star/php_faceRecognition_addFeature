<?php
require_once $_SERVER ["DOCUMENT_ROOT"] . "/phpmailer/class.phpmailer.php";
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
	$sql = 'Select A.id, A.matricula, B.email, B.matricula, B.data_filiacao, COUNT(A.id) AS agrupado, TIMESTAMPDIFF(DAY,CURDATE(),B.data_renovacao) AS intervalo, TIMESTAMPDIFF(DAY,CURDATE(),B.data_renovacao)/COUNT(A.id) AS media from tab_membros B
	INNER JOIN 
	tab_habitualidade A ON A.matricula = B.matricula   GROUP BY A.matricula HAVING  COUNT(A.id) >= 8 AND media <= 2.5 ';
	$stm = $conexao->prepare($sql);
	$stm->execute();
    $clientes = $stm->fetchAll(PDO::FETCH_OBJ);
    
	// dispara e-mail
	
	$assunto_email = false;
	$msg_email = false;
	$email = false;
	$assunto_email = "Mensagem Importante do seu Clube de Tiro";
	$msg_email = "Olá Nosso sistema identificou que você cumpriu suas <b>8 habitualidades obrigatórias no ano</b> como estabelece a Portaria 150 do COLOG de 5 de dezembro de 2019. <br><br>Passando para te desejar os <b>PARABÉNS!</b><br>Mensagem do seu Clube de Tiro";
	
	
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
	if(mailSend($cliente->email, $email_assunto, nl2br($email_conteudo))){
	$result_email = true;
	}}
	
	
	
	if ($result_email){
	$_SESSION['msg_ok'] = 1; 
	}
	else{
	$_SESSION['msg_ok'] = 2;
	}
	//====================================================
	
	
	
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