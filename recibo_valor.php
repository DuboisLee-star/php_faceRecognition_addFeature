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

ini_set('display_errors', 1);
 error_reporting(~E_NOTICE);
  include "config/ajax_p_menus.php";
  $query = "SELECT * FROM tab_membros WHERE bloqueio LIKE '%Nao%' ORDER BY matricula";  
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Gerador de Recibos</h3>
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
                Valor do Recibo
              
                 </header>
              <div id="result">
              <div id="employee_table" class="table-responsive">
				
				<form action="recibo.php?id=<?= $_GET['id']; ?>" method="post" id="form_recibo" target="_blank">
				<table class="table">

					<tr>
						<td>Competência:</td>
						<td width="200">
							<select name="mes_competencia" id="mes_competencia" class="form-control">
								<option value="">-Escolha o mês-</option>
								<option value="1">Janeiro</option>
								<option value="2">Fevereiro</option>
								<option value="3">Março</option>
								<option value="4">Abril</option>
								<option value="5">Maio</option>
								<option value="6">Junho</option>
								<option value="7">Julho</option>
								<option value="8">Agosto</option>
								<option value="9">Setembro</option>
								<option value="10">Outubro</option>
								<option value="11">Novembro</option>
								<option value="12">Dezembro</option>
							</select>
						</td>
						<td width="80"><input type="text" name="ano_competencia" id="ano_competencia" maxlength="4" placeholder="AAAA" class="form-control"></td>
						<td></td>
					</tr>
					<tr>
						<td width="20"><div class="form-check form-switch">
						               <input class="form-check-input" type="hidden" name="tipo" id="tipo1" value="A" checked></td>
						<td width="160"><label class="form-check-label" for="tipo1">Valor R$</label></td>
						<td width="200"><input type="text" name="valor_plano" id="valor_plano" class="form-control" placeholder="Valor do recibo" data-money value="0,00"></td>
						<td></td>
						<td></td>
					</tr>
					<tr>

						<td></td>
						<td></td>
						<td></td>
					</tr>
						<td>Forma pgto:</td>
						<td width="200">
							<select name="forma_pgto" id="forma_pgto" class="form-control">
								<option value="">- escolha -</option>
								<option value="Boleto">Boleto</option>
								<option value="Cartao">Cartão</option>
								<option value="Pix">Pix</option>
								<option value="Dinheiro">Dinheiro</option>
							</select>
						</td>
						<tr>
						    <td>Referente à :</td>
						<td width="100" colspan="2">
						    <input class="form-control" type="text" name="referente_a" id="referente_a" size="100" maxlength="100" value=""></td>
						<td></td>
					</tr>
				</table>
				<div class="form-check form-switch"><button onclick="return gerarRecibo();" target="_blank" type="button" class="btn btn-info btn-sm" >Gerar Recibo</button></div><br>
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
	  $(document).ready(function(e){
		$("[data-money]").maskMoney({thousands:'.', decimal:',', precision: 2, allowZero: true});
		$("#financeiro").on('click', function(e){
			console.log($(this).prop('checked'));
			if($(this).prop('checked') == true){
				$("[data-baixa]").val('').removeClass('hidden');
			}else{
				$("[data-baixa]").val('').addClass('hidden');
			}
		});
	});
      const delUsers = (id_usuario) => {
          var r = confirm("Confirma excluir registro?");
          if(r){
            window.location="action_del_users.php?id="+id_usuario;
            return true;
          }
          return false;
      }
	  const gerarRecibo = () => {
		  var tipo = $("[name=tipo]:checked").val();
		  var valor = $("#valor_plano").val();
		  var mes = $("#mes_competencia").val();
		  var ano = $("#ano_competencia").val();
		  
		  if(tipo == "A" && valor == "0,00"){alert("Valor do plano não informado."); return false;}
		  
		  // if($("#financeiro").prop('checked') == true){
			  if(mes == ""){alert("Mês não informado."); return false;}
			  if(ano == ""){alert("Ano não informado."); return false;}
		  // }
		  
		  $("#form_recibo").submit();
	  }
  </script>


</body>

</html>