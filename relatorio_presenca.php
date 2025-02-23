<?php
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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

include "config/ajax_p_menus.php";



$query = "SELECT * FROM tab_membros ORDER BY nome ASC";  
$result = mysqli_query($connect, $query);
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Relatório de Presença</h3>
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
                Relatório de Presença
              
                 </header>
              <div id="result">
              
    				
    				<div class="content">
    				    <div class="col-12">
    				        
    				        <form action="relatorios/relatorio_presenca.php" id="form_relatorio" method="post" target="_blank">
                				<table class="table table-striped" style="bgcolor: #ffffff;">
                                <tbody>
                                    <tr>
                                    <td>Período</td>
                                    <td><input type="month" class="form-control" id="periodo" name="periodo" value="" placeholder="MM/AAAA"></td>
                                 </tr>
                                <tr>
                                    <td>Atirador</td>
                                    <td>
                                        <select class="form-control select2" name="matricula">
                                            <option value="">-- TODOS --</option>
                                            <?php while($atiradores = mysqli_fetch_assoc($result)): ?>
                                                <option value="<?= $atiradores['matricula']; ?>"><?= $atiradores['nome'].' - '.$atiradores['matricula']; ?></option>
                                            <?php endwhile; ?>
                                      </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Ordem</td>
                                    <td>
                                        <select class="form-control" name="ordem">
                                            <option value="1">Data</option>
                                            <option value="2">Matricula</option>
                                      </select>
                                    </td>
                                 </tr>
                                </table>
                            </form>
                            
                            <div class="text-center"><button type="button" class="btn btn-primary" onclick="return gerarRelatorio()">Gerar Relatório</button></div>
                            <br>
                        </div>
        			</div>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="bgloading"><i class="fa fa-spinner fa-spinner fa-spin" aria-hidden="true"></i></div>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
const gerarRelatorio = () => {
    var periodo = document.getElementById("periodo").value;
    if(periodo == ""){
        alert("Período não informado"); return false;
    }
    document.getElementById("form_relatorio").submit();
}
</script>
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