<?php
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();  // Assegura que a sessão está iniciada

date_default_timezone_set('America/Sao_Paulo');

include "config/config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
    exit();
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
    exit();
}

include_once ("config/url_painel.php");
include "config/ajax_p_menus.php";

// grava biometria
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : false;
if(!$id){
    echo '<script>alert(\'Membro não localizado.\');window.location=\'painel.php\';</script>';
    exit();
}
$action = isset($_POST['action']) ? $_POST['action'] : false;
$biometria = isset($_POST['biometria']) ? $_POST['biometria'] : false;
$id_membro = isset($_POST['id']) ? $_POST['id'] : false;
$biometria_capturada = isset($_POST['biometria']) ? $_POST['biometria'] : false;
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : false;

if($action){
    if($action == "cadastrar" || $action == "alterar"){
        
        try{
        
            if(strlen(trim($biometria)) <= 0) exit('EB');
            
            $dtlog = ($action == "cadastrar") ? " , dtcad_biometria = '".date('Y-m-d H:i:s')."' " : " , dtalt_biometria = '".date('Y-m-d H:i:s')."' ";
            
            $query = " UPDATE tab_membros SET biometria = '{$biometria}' {$dtlog} WHERE id = '{$id_membro}' ";
            if (mysqli_query($connect, $query)) {
                exit('S');
            }
            exit('E');
            
        } catch (Exception $e) {
            exit('EI');
        }
    }
    if($action == "excluir"){
        
        try{
            
            $query = " UPDATE tab_membros SET biometria = NULL, dtalt_biometria = '".date('Y-m-d H:i:s')."' WHERE id = '{$id_membro}' ";
            if (mysqli_query($connect, $query)) {
                exit('S');
            }
            exit('E');
            
        } catch (Exception $e) {
            exit('EI');
        }
        
    }
    if($action == "registra_presenca"){
        
        try{
            
            $query = " INSERT INTO tab_registro_presenca
                (
                    matricula,
                    biometria_capturada,
                    datahora
                ) VALUES (
                    '{$matricula}',
                    '{$biometria_capturada}',
                    '".date('Y-m-d H:i:s')."'
                )
            ";
            if (mysqli_query($connect, $query)) {
                exit('S');
            }
            exit('E');
            
        } catch (Exception $e) {
            exit('EI');
        }
        
    }
}


$query = "SELECT * FROM tab_membros WHERE id = '{$id}'";  
$result = mysqli_query($connect, $query);
$dados = mysqli_fetch_assoc($result);
if(strlen(trim($dados['id'])) <= 0){
    echo '<script>alert(\'Membro não localizado.\');window.location=\'painel.php\';</script>';
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
<script>
    const openLoading = (close = false) => {
        if(!close){
            $(".bgloading").fadeIn(0);
            $("button").prop('disabled', true);
        }else{
            $(".bgloading").fadeOut(0);
            $("button").prop('disabled', false);
        }
        
    }
    const cadastrarBiometria = () => {
        
        openLoading();
        
        try{
    	
        	$.ajax({
        		url: 'http://localhost:9000/api/public/v1/captura/Enroll/1',
        		type: 'GET',
        		success: function(retorno){
        		    
        		    openLoading(true);
        		  
        			if(retorno == ""){;
        				alert("Falha ao registrar digital");
        			}else{
        				gravarBiometria(retorno);
        			}
        		},
        		'error': function(e) {
        		      console.log(e);
        		    openLoading(true);
                    alert('Falha ao iniciar leitor biometrico');
        		}
        	});
        	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const alterarBiometria = () => {
        
        var r = confirm('Deseja realmente alterar a biometria deste membro?');
        if(r){
        
            openLoading();
            
            try{
        	
            	$.ajax({
            		url: 'http://localhost:9000/api/public/v1/captura/Enroll/1',
            		type: 'GET',
            		success: function(retorno){
            		    
            		    openLoading(true);
            		    
            			if(retorno == ""){;
            				alert("Falha ao capturar biometria");
            			}else{
            				alteraBiometria(retorno);
            			}
            		},
            		'error': function(e) {
            		    openLoading(true);
                        alert('Falha ao iniciar leitor biometrico');
            		}
            	});
            	
            }catch(e) {
                openLoading(true);
                alert('Erro interno: '+e.message);
            }
        
        }
    }
    const gravarBiometria = (biometria) => {
        
        openLoading();
        
        try{
        
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'cadastrar',
        		    'biometria' : biometria,
        		    'id'        : '<?= $dados['id']; ?>'
        		},
        		success: function(retorno){
        		    
        			if(retorno == ""){
        			    openLoading(true);
        				alert("Não foi possivel gravar a biometria.");
        			}else{
        				alert("Biometria cadastrada com sucesso.");
        				window.location=window.location.href;
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const alteraBiometria = (biometria) => {
            
        openLoading();
    
        try{
        
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'alterar',
        		    'biometria' : biometria,
        		    'id'        : '<?= $dados['id']; ?>'
        		},
        		success: function(retorno){
        		    
        			if(retorno == ""){
        			    openLoading(true);
        				alert("Não foi possivel gravar a biometria.");
        			}else{
        				alert("Biometria alterada com sucesso.");
        				window.location=window.location.href;
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const registrarPresenca = () => {
        openLoading();
        
        try{
        
            $.ajax({
        		url: 'http://localhost:9000/api/public/v1/captura/Capturar/1',
        		type: 'GET',
        		success: function (data) {
        			if(data == ""){
        			    openLoading(true);
        				alert("Biometria não capturada.");
        			}else{
        				comparaBiometria(data, '<?= $dados['biometria']; ?>');
        			}
        		},
        		'error': function(e) {
        		    openLoading(true);
                    alert('Falha ao iniciar leitor biometrico');
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const comparaBiometria = (biometria_capturada, biometria_membro) => {
        
        $.ajax({
    		url: 'http://localhost:9000/api/public/v1/captura?Digital=' + biometria_capturada + '|' + biometria_membro,
    		type: 'GET',
    		success: function(retorno){
    		    
    		    openLoading(true);
    		    
    		    if(retorno == "OK"){
    		        registraPresenca(biometria_capturada);
    		    }else{
    		        alert('ERRO: Biometria não compativel.');
    		    }
    		    
    		},
    		'error': function(e) {
    		    openLoading(true);
                alert('Falha ao iniciar leitor biometrico');
    		}
    	});
    }
    const registraPresenca = (biometria_capturada) => {
        
        openLoading();
            
        try{
    
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'registra_presenca',
        		    'biometria' : biometria_capturada,
        		    'matricula' : '<?= $dados['matricula']; ?>'
        		},
        		success: function(retorno){
        		    openLoading(true);
        			if(retorno == "S"){
        				alert("Presença registrada com sucesso.");
        			}else{
        				alert("ERRO. Falha ao registrar presença.");
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const excluirBiometria = () => {
        var r = confirm('Deseja realmente excluir a biometria deste membro?');
        if(r){
            openLoading();
            
            try{
        
                $.ajax({
            		url: '',
            		type: 'POST',
            		data: {
            		    'action'    : 'excluir',
            		    'id'        : '<?= $dados['id']; ?>'
            		},
            		success: function(retorno){
            		    
            			if(retorno == ""){
            			    openLoading(true);
            				alert("Falha ao excluir biometria.");
            			}else{
            				alert("Biometria excluida com sucesso.");
            				window.location=window.location.href;
            			}
            		}
            	});
        	
            }catch(e) {
                openLoading(true);
                alert('Erro interno: '+e.message);
            }
            
        }
    }
</script>
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Cadastro de Biometria do Membro</h3>
            <ol style="overflow-y: scroll; display: scroll" class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>				
            </ol></font>
          </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Biometria
              
                 </header>
              <div id="result">
              <div id="employee_table" class="table-responsive">
				
				<?php if(strlen(trim($dados['biometria'])) > 0): ?>
                    <!--<div style="background: #d3eeff; color: #315b87; padding: 10px; margin: 10px;"><i class="fa fa-check-circle" aria-hidden="true"></i> Biometria já cadastrada.</div>-->
                    
                    <div style="padding: 15px;">
                        <button type="button" class="btn btn-success" onclick="return registrarPresenca()" style="margin-right: 15px;"><i class="fa fa-check" aria-hidden="true"></i> REGISTRAR PRESENÇA</button>
                        <button type="button" class="btn btn-info" onclick="return alterarBiometria()"><i class="fa fa-pencil" aria-hidden="true"></i> Alterar Biometria</button>
                        <button type="button" class="btn btn-danger" onclick="return excluirBiometria()"><i class="fa fa-times" aria-hidden="true"></i> Excluir Biometria</button>
                    </div>
                    
                <?php else: ?>
                    <div style="background: #fffacc; color: #af8100; padding: 10px; margin: 10px;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Biometria ainda não cadastrada.</div>
                    
                    
                    <div style="padding: 15px;">
                        <button type="button" class="btn btn-primary" onclick="return cadastrarBiometria()"><i class="fa fa-plus-circle" aria-hidden="true"></i> Cadastrar Biometria</button>
                    </div>
                    
                <?php endif; ?>
				
				
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

<div class="bgloading"><i class="fa fa-spinner fa-spinner fa-spin" aria-hidden="true"></i></div>
<style>
    .bgloading {
        position: fixed;
        z-index: 9999;
        background: rgba(0,0,0,0.6);
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display:none;
    }
    .bgloading i {
        font-size: 70px;
        color: #ffffff;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -35px;
        margin-left: -35px;
    }
</style>
</body>

</html>