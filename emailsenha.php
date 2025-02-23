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

function gerar_senha($tamanho, $maiusculas = true, $minusculas = false, $numeros = true, $simbolos = false){

  $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
  $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
  $nu = "0123456789"; // $nu contem os números
  $si = "!@#$%¨&*()_+="; // $si contem os símbolos

  if ($maiusculas){

        // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($ma);
  }

    if ($minusculas){

        // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($mi);

    }

    if ($numeros){
        // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($nu);
    }

    if ($simbolos){
        // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($si);
    }

    // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
    return substr(str_shuffle($senha),0,$tamanho);
}

?>

<?php

require 'config/conexao.php';
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
	
    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = 'SELECT * FROM info_clube WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', 1);
    $stm->execute();
    $urls = $stm->fetch(PDO::FETCH_OBJ);	

	include "config/consulta_cac.php";

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

	/*echo $email_destinatario;

	print_r($_POST);

	exit();

	*/

	$email_reply = $email_reply_mun;

	$email_assunto = $assunto_email;

	//====================================================

	

	//Monta o Corpo da Mensagem

	//====================================================

	$senha_de_acesso = gerar_senha(6);

	$email_conteudo =  str_replace("[SENHA_MEMBRO]", $senha_de_acesso, $msg_email);
	
	//====================================================

 

	//Seta os Headers (Alerar somente caso necessario)

	//====================================================

	$email_headers = implode ( "\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Subject: $email_assunto","Return-Path:  $email_remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );

	//====================================================

 

 

	//Enviando o email

	//====================================================
    if(mailSend($email_destinatario, $email_assunto, nl2br($email_conteudo))){

		

		// marca como senha enviada

		$sql = "UPDATE tab_membros SET senha_enviada = 1, senha = '".md5($senha_de_acesso)."' WHERE id = :id";

		$stm = $conexao->prepare($sql);

		$stm->bindValue(':id', $id_cliente);

		$retorno = $stm->execute();
		
		$conexao = conexao::getInstance();
    	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
    	$stm = $conexao->prepare($sql);
    	$stm->bindValue(':id', $id_cliente);
    	$stm->execute();
    	$cliente = $stm->fetch(PDO::FETCH_OBJ);

        // Captura os dados do cliente solicitado
        $conexao = conexao::getInstance();
        $sql = 'SELECT * FROM info_clube WHERE id = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', 1);
        $stm->execute();
        $urls = $stm->fetch(PDO::FETCH_OBJ);
    	
		// envia por whatsapp a senha
	    $atirador = false;
	    require_once($_SERVER['DOCUMENT_ROOT'].'/whatsapp/whatsapp.php');
	    $wpp = new Whatsapp();
	    $wpp->number = $cliente->telefone;
	    $wpp->matricula = $cliente->matricula;
	    $wpp->referencia = $_SERVER['HTTP_HOST'];
	    $wpp->tipo = 'senhaadm';
	    $wpp->message = "Olá *{$cliente->nome}*,

Estamos enviando os dados para acesso à sua Àrea Restrita pelo APP, onde voce precisa apenas clicar no link e será redirecionado à Play Store, baixe e use os dados abaixo para entrar. Para acessar pelo Portal Web, clique no link a seguir e use os mesmos dados.

*Link direto para o APP:* {$urls->urlapp}

*Link direto para o portal web:* {$urls->url}atirador/

*CPF:* {$cliente->cpf}
*Senha:* {$senha_de_acesso}";

        $wpp->enviar();
		
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
<html lang="zxx">
<!-- Head -->

<head>
    <title>HOSTMARQ</title>
    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">

  <meta name="author" content="GeeksLabs">

  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">

  <link rel="shortcut icon" href="img/favicon.png">







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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

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

            <h3 class="page-header"><i class="fa fa-key" aria-hidden="true"></i><?=$cliente->nome?></h3>

            <ol class="breadcrumb">

              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>

              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>

            </ol>
          </div>
        </div>

		        <div class="row" style="margin-bottom: 10px;">
          <div class="col-lg-12">
              <b>Atirador:</b>
              <select class="form-control select2" onchange="window.location='emailsenha.php?id='+this.value">
                  <?php if($atiradores): ?>
                    <?php foreach($atiradores as $key => $Atirador): ?>
                        <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>><?= $Atirador->nome.' - '.$Atirador->matricula; ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </select>
              
              
            
          </div>
        </div>

		

        <!-------------------------------------------------------------------------------------------------------------- page start-->



<fieldset>



				

			<?php if(empty($cliente)):?>

				<h3 class="text-center text-danger">Membro não encontrado!</h3>

			<?php else: ?>

					

					<?php

					if(isset($_SESSION['msg_ok'])){

						if($_SESSION['msg_ok'] == 1) echo '<div class="alert alert-success" role="alert">Enviado com sucesso!</div>';

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

				      <input disabled type="text" class="form-control" value="<?=$cliente->nome?>" placeholder="Infome o Nome">

				      <span class='msg-erro msg-nome'></span>

				    </div>

					

					<div class="form-group">

				      <label for="email">E-mail</label>

				      <input disabled type="email" class="form-control" value="<?=$cliente->email?>" placeholder="Informe o E-mail">

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

					<input value="Senha de acesso | APP do Clube" type="text" class="form-control" name="assunto_email" id="assunto_email">

					<span class='msg-erro msg-nome'></span>

					</div>

					<div class="form-group">

					<label for="email">Mensagem do e-mail:</label>

					<textarea class="form-control" rows="6" name="msg_email" id="msg_email"><br><img src='<?= $cliente->url; ?>img/logo_site.png' height="80">

Oi <b><?=$cliente->nome?></b>, 

Estamos enviando os dados para acesso à sua Àrea Restrita pelo APP, onde voce precisa apenas clicar no link e será redirecionado à Play Store, baixe e use os dados abaixo para entrar. Para acessar pelo Portal Web, clique no link a seguir e use os mesmos dados.

Link direto para o <b>APP</b>: <?= $urls->urlapp; ?>

Link direto para o <b>Portal Web</b>: <?= $urls->url; ?>atirador

<b>CPF:</b> <?= $cliente->cpf; ?><br>
<b>Senha:</b> [SENHA_MEMBRO]

</textarea>

						</div>

						<button type="button" class="btn btn-primary" id="botao" onclick="return enviaEmail()">Enviar Senha</button>

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

            Licensing information: https://bootstrapmade.com/license/

            Purchase the pro version form: https://bootstrapmade.com/buy/?theme=NiceAdmin

          -->

          by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>

        </div>

    </div>

  </section>

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


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>$(document).ready(function() { $('.select2').select2();});</script>


</body>
</html>