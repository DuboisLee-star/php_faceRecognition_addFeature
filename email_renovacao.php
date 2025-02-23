<?php


require_once $_SERVER ["DOCUMENT_ROOT"] . "/phpmailer/class.phpmailer.php";

include "config/config.php";
include_once ("config/url_painel.php");


// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;
	
}

?>

<?php

require 'config/conexao.php';
include_once ("config/url_painel.php");
include_once ("config/email_painel.php");

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->data_nascimento);
		$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

	endif;

endif;

// dispara e-mail
$act = false;
$assunto_email = false;
$msg_email = false;
$email = false;
$nome = false;
if(isset($_POST['act'])) $act = $_POST['act'];
if(isset($_POST['assunto_email'])) $assunto_email = $_POST['assunto_email'];
if(isset($_POST['msg_email'])) $msg_email = $_POST['msg_email'];
if(isset($_POST['email'])) $email = $_POST['email'];
if(isset($_POST['nome'])) $nome = $_POST['nome'];

if($act == "envia" && $msg_email && $email && $nome && $assunto_email){
	
	//REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
 	//====================================================
	$email_remetente = $email_rementente_mun; // deve ser um email do dominio
	//====================================================
 
 
	//ConfiguraÃ§Ãµes do email, ajustar conforme necessidade
	//====================================================
	$email_destinatario = $email; // qualquer email pode receber os dados
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
	if (mail ($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
		$_SESSION['msg_ok'] = 1; 
	}
  	else{
		$_SESSION['msg_ok'] = 2;
	}
	//====================================================
	
	echo '<script>window.location=window.location.href;</script>';
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <link rel="shortcut icon" href="img/favicon.png">

  <title>ADM</title>

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- bootstrap theme -->
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <!--external css-->
  <!-- font icon -->
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->


</head>

<body>
  <!-- container section start -->
  <section id="container" class="">
    <!--header start-->

    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
     <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
          </li>
        </ul>
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">
          <li class="dropdown">            
          <form method='post' action=""><input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout"></form>
          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->

    <!-- menu lateral inicio -->

<?php include 'menu_lateral_esq.php';?>
	
    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-bullhorn" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------- page start-->
		<fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Membro não encontrado!</h3>
			<?php else: ?>
					
					<?php
					if(isset($_SESSION['msg_ok'])){
						if($_SESSION['msg_ok'] == 1) echo '<div class="alert alert-success" role="alert">E-mail enviado com sucesso!</div>';
						if($_SESSION['msg_ok'] == 2) echo '<div class="alert alert-danger" role="alert">Falha ao enviar e-mail.</div>';
						unset($_SESSION['msg_ok']);
					}
					?>
					
					
					<div class="row">
						
				      	<div class="col-md-2">
						    <a href="#" class="thumbnail">
						      <img src="<?php '.URL_painel.' ?>/fotos/<?=$cliente->foto?>" height="190" width="150" id="foto-cliente">
						    </a>
					  	</div>
				  	</div>

				    <div class="form-group">
				      <label for="nome">Nome</label>
				      <input disabled type="text" class="form-control" value="<?=$cliente->nome?>">
				      <span class='msg-erro msg-nome'></span>
				    </div>
					
					<div class="form-group">
				      <label for="email">E-mail</label>
				      <input disabled type="email" class="form-control" value="<?=$cliente->email?>">
				      <span class='msg-erro msg-email'></span>
				    </div>
					
					<form action="" method="post" name="form_email" id="form_email">
					<script>
					function enviaEmail(){
						
						var msg_email = document.getElementById('msg_email').value;
						var email = document.getElementById('email').value;
						var assunto_email = document.getElementById('assunto_email').value;
						if(assunto_email == ""){alert('Assunto do e-mail não informado.'); return false;}
						if(msg_email == ""){alert('Nenhuma mensagem digitada.'); return false;}
						if(email == ""){alert('Endereço de e-mail não encontrado.'); return false;}
						
						document.getElementById('botao').disabled = true;
						document.getElementById('act').value='envia';
						document.getElementById('form_email').submit();
						
					}
					</script>
					
					
					
						<div class="form-group">
							<label for="nome">Assunto do E-mail:</label>
							<input type="text" class="form-control" name="assunto_email" id="assunto_email" value="Comunicado Renovar Matricula">
							<span class='msg-erro msg-nome'></span>
						</div>
						<div class="form-group">
							<label for="email">Mensagem do e-mail:</label>
							<textarea class="form-control" rows="6" name="msg_email" id="msg_email"><br><img src="<?= $cliente->url; ?>img/logo_site.png" height="90"><br><br>

Olá <b><?=$cliente->nome?></b>

Este &eacute; um email autom&aacute;tico para informar que sua filia&ccedil;&atilde;o ao seu Clube de Tiro com matrícula <b><?= $cliente->matricula; ?></b>, tem data de renovação prevista para <b><?=date('d/m/Y', strtotime($cliente->data_renovacao));?></b>. Essa filiação precisa ser atualizada pois estará vencendo em <b><?= restante($cliente->data_renovacao)?></b> dias. 

Por favor procure &agrave; secretaria do clube e se informe sobre procedimentos.

Atenciosamente,
Clube de Tiro
Secretaria<br>
</textarea>
					</div>
					<button type="button" class="btn btn-primary" id="botao" onclick="return enviaEmail()">Enviar e-mail</button>
					<input type="hidden" name="act" id="act">
					<input type="hidden" name="email" id="email" value="<?=$cliente->email?>">
					<input type="hidden" name="nome" id="nome" value="<?=$cliente->nome?>">
					</form>

			<?php endif; ?>
		</fieldset>
</form>
	</div>
	<script type="text/javascript" src="js/custom.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="js/simditor-2.3.27/styles/simditor.css" />
	<script type="text/javascript" src="js/simditor-2.3.27/site/assets/scripts/jquery.min.js"></script>
	<script type="text/javascript" src="js/simditor-2.3.27/site/assets/scripts/module.js"></script>
	<script type="text/javascript" src="js/simditor-2.3.27/site/assets/scripts/hotkeys.js"></script>
	<script type="text/javascript" src="js/simditor-2.3.27/site/assets/scripts/uploader.js"></script>
	<script type="text/javascript" src="js/simditor-2.3.27/site/assets/scripts/simditor.js"></script>

	<script>
	$(function(){
		var $preview, editor, mobileToolbar, toolbar;
		Simditor.locale = 'en-US';
		toolbar = ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link', 'image', 'hr', '|', 'indent', 'outdent', 'alignment'];
		var editor = new Simditor({
		  textarea: $('#msg_email'),
		  //optional options
		  toolbar: toolbar
		});
	});
	</script>

			
        <!------------------------------------------------------------------------------------------------------------ page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">
          <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://hostmarq.com.br/license/
            Purchase the pro version form: https://hostmarq.com.br/buy/?theme=NiceAdmin
          -->
          by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
        </div>
    </div>
  </section>
  <!-- container section end -->
  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>


</body>

</html>
