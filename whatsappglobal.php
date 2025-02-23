<?php
include($_SERVER["DOCUMENT_ROOT"] . "/phpmailer/class.phpmailer.php");
include ("config/config.php");
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

// todos
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_membros order by matricula';
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

// todos bloqueados
$sql = "SELECT * FROM tab_membros WHERE bloqueio = 'Sim' order by matricula";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes2 = $stm->fetchAll(PDO::FETCH_OBJ);

// todos ativos
$sql = "SELECT * FROM tab_membros WHERE bloqueio = 'Nao' order by matricula";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes3 = $stm->fetchAll(PDO::FETCH_OBJ);

// Apenas do mês corrente ativos
$sql = "SELECT * FROM tab_membros WHERE DATE_FORMAT(data_renovacao, '%Y-%m') = '".date('Y-m')."' AND bloqueio = 'Nao' order by matricula";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes4 = $stm->fetchAll(PDO::FETCH_OBJ);

// Apenas do mês corrente bloqueados
$sql = "SELECT * FROM tab_membros WHERE DATE_FORMAT(data_renovacao, '%Y-%m') = '".date('Y-m')."' AND bloqueio = 'Sim' order by matricula";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes5 = $stm->fetchAll(PDO::FETCH_OBJ);

// todos desfiliados
$sql = "SELECT * FROM tab_membros WHERE bloqueio = 'D' order by matricula";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes6 = $stm->fetchAll(PDO::FETCH_OBJ);

// todos visitantes
$sql = "SELECT *, zap_visitante as telefone FROM tab_habitualidade WHERE tipo_atirador = '2'";
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes7 = $stm->fetchAll(PDO::FETCH_OBJ);

// dispara e-mail
$act = false;
$msg_email = false;
$tipo_envio = "T";
$enviar_para = "1";
if(isset($_POST['act'])) $act = $_POST['act'];
if(isset($_POST['msg_email'])) $msg_email = $_POST['msg_email'];
if(isset($_POST['tipo_envio'])) $tipo_envio = $_POST['tipo_envio'];
if(isset($_POST['enviar_para'])) $enviar_para = $_POST['enviar_para'];

if($act == "envia"){
    
    // verifica se possui saldo
    require('whatsapp/whatsapp.php');
    $wpp = new Whatsapp();
    $saldo = $wpp->verifica_saldo();
    if(!$saldo){
        $_SESSION['msg_ok'] = 3;
        echo '<script>window.location=window.location.href;</script>';
	    exit();
    }
    
    if($tipo_envio == "I"){
	    
	    $diretorio = $_SERVER['DOCUMENT_ROOT'].'/img/whatsapp';
	    $fileNameParts = explode('.', $_FILES['image']['name']);
        $ext = end($fileNameParts);
	    $nome_arquivo = date('Y-m-d-H-i-s').'_'.microtime(true).'.'.$ext;
	    
	    // verifica se o arquivo existe
	    if(!file_exists($diretorio)){
		    mkdir($diretorio);  
	    }   
	    
	    if(move_uploaded_file($_FILES['image']['tmp_name'], $diretorio.'/'.$nome_arquivo)){
	        $msg_email = 'https://'.$_SERVER['HTTP_HOST'].'/img/whatsapp/'.$nome_arquivo;
	    };
	    
	}
	
	$clientes_envio = false;
	switch($enviar_para){
	    case "1": $clientes_envio = $clientes; break;
	    case "2": $clientes_envio = $clientes2; break;
	    case "3": $clientes_envio = $clientes3; break;
	    case "4": $clientes_envio = $clientes4; break;
	    case "5": $clientes_envio = $clientes5; break;
	    case "6": $clientes_envio = $clientes6; break;
	    case "7": $clientes_envio = $clientes7; break;
	}
	
	if(count($clientes_envio) <= 0){
	    $_SESSION['msg_ok'] = 4;
	    echo '<script>window.location=window.location.href;</script>';
	    exit();
	}

	foreach($clientes_envio as $cliente){

        $wpp->matricula = $cliente->matricula;
        $wpp->number = $cliente->telefone;
        $wpp->message = $msg_email;
        $wpp->tipo_envio = $tipo_envio;
        
        $wpp->cria_fila();
		
	}
	
	
	$_SESSION['msg_ok'] = 1;
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
  <!--<link href="css/font-awesome.min.css" rel="stylesheet" />-->  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
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

    <!--menu lateral fim-->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-quote-left" aria-hidden="true"></i><i class="fa fa-quote-right" aria-hidden="true"></i>WhatsApp</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-envelope"></i>Notificação WhatsApp para Atiradores</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
      		<?php if(empty($clientes)):?>
				<h3 class="text-center text-danger">Nenhum registro cadastrado!</h3>
			<?php else: ?>
					
					<?php
					if(isset($_SESSION['msg_ok'])){
					    if($_SESSION['msg_ok'] == 1) echo '<div class="alert alert-success" role="alert">Notificação enviada com sucesso!</div>';
					    if($_SESSION['msg_ok'] == 2) echo '<div class="alert alert-danger" role="alert">Falha ao enviar notificação.</div>';
					    if($_SESSION['msg_ok'] == 3) echo '<div class="alert alert-danger" role="alert">Sem saldo para envio de mensagem whatsapp.</div>';
					    if($_SESSION['msg_ok'] == 4) echo '<div class="alert alert-danger" role="alert">Nenhum atirador localizado para enviar.</div>';
					    unset($_SESSION['msg_ok']);
					}
					?>
					
					<form action="" method="post" name="form_email" id="form_email" enctype='multipart/form-data'>
					<script>
					const enviaNotificacao = () => {
					    
					    var mensagem = $("#msg_email").val();
					    var tipo_envio = $("#tipo_envio").val();
					    var image = $("#imagem").val();
					    
					    
                		  if(tipo_envio == "T" && mensagem.length <= 3){alert("Mensagem inválida."); return false;}
                		  if(tipo_envio == "I" && image == ""){alert("Imagem não selecionada."); return false;}
					    
					    $("button").prop('disabled', true).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Enviado...aguarde!');
					    
					    $("#act").val('envia');
					    $("#form_email").submit();
					    
					}
					</script>
					
						<div class="form-group">
						Quantidade de mensagens: {<strong><?= count($clientes); ?></strong>}
						</div>
						<div class="form-group">
							<label for="email">Tipo de Envio:</label>
							<select class="form-control" name="tipo_envio" id="tipo_envio" onchange="tipoEnvio(this.value)">
						        <option value="T">Mensagem de Texto</option>
						        <option value="I">Imagem</option>
						    </select>
						</div>
						<div class="form-group">
							<label for="email">Enviar Para:</label>
							<select class="form-control" name="enviar_para" id="enviar_para">
						        <option value="1">Todos (<?= count($clientes); ?>)</option>
						        <option value="3">Todos Ativos (<?= count($clientes3); ?>)</option>						        
						        <option value="2">Todos Inativos (<?= count($clientes2); ?>)</option>
						        <option value="6">Todos Desfiliados (<?= count($clientes6); ?>)</option>
						        <option value="7">Todos Visitantes (<?= count($clientes7); ?>)</option>						        
						        <option value="4">Apenas mês corrente ativos (<?= count($clientes4); ?>)</option>
						        <option value="5">Apenas mês corrente inativos (<?= count($clientes5); ?>)</option>
						    </select>
						</div>
						<div class="form-group">
							<label for="email">Mensagem WhatsApp:</label>
							<span id="campo_whatsapp"><textarea class="form-control" rows="6" name="msg_email" id="msg_email"></textarea><p><b>Itálico:</b> Para escrever sua mensagem em <i>itálico</i>, insira o sinal de sublinhado antes e depois do texto: _texto_</p>						<p><b>Negrito:</b> Para escrever sua mensagem em <b>negrito</b>, insira um asterisco antes e depois do texto: *texto*</p></span>
						</div>
						
						
						<button type="button" class="btn btn-success" id="botao" onclick="return enviaNotificacao()"><i class="fa fa-whatsapp"></i> Enviar Notificação</button>
						<input type="hidden" name="act" id="act">
					</form>

			<?php endif; ?>
		</fieldset>
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
	const tipoEnvio = (tipo_envio) => {
	      
	      if(tipo_envio == 'T'){
	          $("#campo_whatsapp").html('<textarea class="form-control" rows="4" id="msg_email" name="msg_email"></textarea><p><b>Itálico:</b> Para escrever sua mensagem em <i>itálico</i>, insira o sinal de sublinhado antes e depois do texto: _texto_</p><p><b>Negrito:</b> Para escrever sua mensagem em <b>negrito</b>, insira um asterisco antes e depois do texto: *texto*</p>');
	      }else{
	          $("#campo_whatsapp").html('<input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.gif">');
	      }
	      
	  }
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