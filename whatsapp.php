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

include_once ("config/url_painel.php");

$id = isset($_GET['id']) ? $_GET['id'] : false;
$acao = isset($_POST['acao']) ? $_POST['acao'] : false;
$mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : false;
$tipo_envio = isset($_POST['tipo_envio']) ? $_POST['tipo_envio'] : false;

include "config/ajax_p_menus.php";

include "config/consulta_cac.php";

$query = "SELECT * FROM tab_membros WHERE id = '{$id}' ";  
$result = mysqli_query($connect, $query);  
$cliente = mysqli_fetch_array($result);

if($acao == 'envia_whatsapp'){
	
	require('whatsapp/whatsapp.php');
	
	$wpp = new Whatsapp();
	
	$saldo = $wpp->verifica_saldo();
    if(!$saldo){
        $_SESSION['msg_ok'] = 3;
        echo '<script>window.location=window.location.href;</script>';
	    exit();
    }
	
	$wpp->tipo_envio = $tipo_envio;
	$wpp->number = $cliente['telefone'];
	
	$wpp->matricula = $cliente['matricula'];
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
	        $wpp->message = 'https://'.$_SERVER['HTTP_HOST'].'/img/whatsapp/'.$nome_arquivo;
	    };
	    
	}else{
	    
	    $wpp->message = $mensagem;
	    
	}
	
	$response = $wpp->cria_fila();
	//print_r($response);exit();
	
	if(!isset($response['error'])){
		$_SESSION['msg_ok'] = 1;
	}else{
		$_SESSION['msg_ok'] = 2;
	}
	
	echo "<script>window.location=window.location.href;</script>";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>    

  <title>ADM</title>

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- bootstrap theme -->
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <!--external css-->
  <!-- font icon -->
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <!--<link href="css/font-awesome.min.css" rel="stylesheet" />-->
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        <div class="icon-reorder tooltips" data-original-title="Menu Navega&ccedil;&atilde;o" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
            
            <input class="form-control" placeholder="matricula, nome, plano" type="text" for="termo" id="search_text" name="search_text">
		   
            
          </li>
        </ul> 
        <!-- ajax search --> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>  
        
        </script>
        
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">


          <!-- user login dropdown start-->
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
          <div   class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Notificação WhatsApp</h3>
            <ol style="overflow-y: scroll; display: scroll" class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>				
            </ol></font>
          </div>
        </div>
    
            <div class="row" style="margin-bottom: 10px;">
          <div class="col-lg-12">
              <b>Atirador:</b>
              <select class="form-control select2" onchange="window.location='whatsapp.php?id='+this.value">
                  <?php if($atiradores): ?>
                    <?php foreach($atiradores as $key => $Atirador): ?>
                        <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>><?= $Atirador->nome.' - '.$Atirador->matricula; ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </select>
         </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Notificação WhatsApp
              
                 </header>
              <div id="result">
              <div id="employee_table" class="table-responsive">
				
				<?php
				if(isset($_SESSION['msg_ok'])){
					if($_SESSION['msg_ok'] == 1) echo '<div class="alert alert-success m-3" role="alert"><i class="fa fa-check-circle"></i> Notificação enviada com sucesso.</div>';
					if($_SESSION['msg_ok'] == 2) echo '<div class="alert alert-danger m-3" role="alert"><i class="fa fa-exclamation-circle"></i> Falha ao enviar notificação.</div>';
					if($_SESSION['msg_ok'] == 3) echo '<div class="alert alert-danger m-3" role="alert"><i class="fa fa-exclamation-circle"></i> Sem saldo para envio de mensagem whatsapp.</div>';
					unset($_SESSION['msg_ok']);
				}
				?>
				
				<form action="" method="post" id="form_notificacao" enctype='multipart/form-data'>
				<table class="table">
					<tr>
						<td width="180" align="right"><b>Atirador:</b></td>
						<td><?= $cliente['nome'].' - '.$cliente['matricula']; ?></td>
						<td></td>
					</tr>
					<tr>
						<td align="right"><b>Telefone:</b></td>
						<td><?= $cliente['telefone']; ?></td>
						<td></td>
					</tr>
					<tr>
						<td align="right"><b>Tipo de Envio:</b></td>
						<td>
						    <select class="form-control" name="tipo_envio" id="tipo_envio" onchange="tipoEnvio(this.value)">
						        <option value="T">Mensagem de Texto</option>
						        <option value="I">Imagem</option>
						    </select>    
						</td>
						<td></td>
					</tr>
					<tr>
						<td align="right"><b>Mensagem WhatsApp:</b></td>
						<td id="campo_whatsapp">
						<textarea class="form-control" rows="4" id="mensagem" name="mensagem"></textarea><br><b>Itálico:</b> Para escrever sua mensagem em <i>itálico</i>, insira o sinal de sublinhado antes e depois do texto: _texto_<br><b>Negrito:</b> Para escrever sua mensagem em <b>negrito</b>, insira um asterisco antes e depois do texto: *texto*</p>
						</td>
						<td></td>
					</tr>
				</table>
				<div class="text-center"><button onclick="return enviaNotificacao();" type="button" class="btn btn-success btn-sm" ><i class="fa fa-whatsapp"></i> Enviar Notificação</button></div><br>
				<input type="hidden" name="acao" value="envia_whatsapp">
				</form>
				
              </div>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">

            </section>
          </div>
        </div>
        <!-- page end-->
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
          Design by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br><br>
        </div>
    </div>
  </section>
  
  <!-- container section end -->
  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nicescroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  <script src="js/jquery.maskMoney.js"></script>
  
      <script>
	  
	  const enviaNotificacao = () => {
		  
		  var mensagem = $("#mensagem").val();
		  var tipo_envio = $("#tipo_envio").val();
		  var image = $("#imagem").val();
		  if(tipo_envio == "T" && mensagem.length <= 3){alert("Mensagem inválida."); return false;}
		  if(tipo_envio == "I" && image == ""){alert("Imagem não selecionada."); return false;}
		  
		  $("button").prop('disabled', true).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Enviado...aguarde!');
		  $("#form_notificacao").submit();
		  
	  }
	  const tipoEnvio = (tipo_envio) => {
	      
	      if(tipo_envio == 'T'){
	          $("#campo_whatsapp").html('<textarea class="form-control" rows="4" id="mensagem" name="mensagem"></textarea><p><b>Itálico:</b> Para escrever sua mensagem em <i>itálico</i>, insira o sinal de sublinhado antes e depois do texto: _texto_</p><p><b>Negrito:</b> Para escrever sua mensagem em <b>negrito</b>, insira um asterisco antes e depois do texto: *texto*</p>');
	      }else{
	          $("#campo_whatsapp").html('<input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.gif">');
	      }
	      
	  }
  </script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>$(document).ready(function() { $('.select2').select2();});</script>

</body>

</html>