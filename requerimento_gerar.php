<?php

include "config/config.php";

$admin = false;

// Check user login or not
if(!isset($_SESSION['uname'])){
	
	// check user logado
	if(!isset($_SESSION['is_user'])){
		header('Location: index.php');
		exit();
	}
	
}else{
	$admin = true;
}

// logout
if(isset($_POST['but_logout']) || isset($_GET['logout'])){
    session_destroy();
    header('Location: index.php');
	exit();
}
?>
<?php
require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
if(!$admin){
	$id_cliente = $_SESSION['user_id'];
}

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
            <h3 class="page-header"><i class="fa fa fa-bars"></i> <?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------- page start-->
    <meta charset="utf-8">
</script>
	
	<script type="text/javascript" src="assets/jQuery/jQuery-2.1.4.min.js"></script>
	<script type="text/javascript" src="assets/jQuery/mask.js"></script>
	
  <script>document.getElementsByTagName("html")[0].className += " js";</script>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<div class='container'>

	
	
		<fieldset>

		<legend><?php if(!$admin){echo ' <a href="?logout" class="btn btn-danger">Desconectar</a>';} ?></legend>
			
			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Membro não encontrado!</h3>
			<?php else: ?>
                       <div class="row">
					   <?php if($admin): ?>
						<?php endif; ?>
						<div class="col-md-2">
						    <a href="#" class="thumbnail">
						      <img src="fotos/<?=$cliente->foto?>" width="150" id="foto-cliente">
						    </a>
							<?php if($admin): ?><?php endif; ?>
					  	</div>
					  	<input type="hidden" name="foto" id="foto" value="foto" >
				  	</div><br>

				    <div class="form-group">
				      <label for="cr">N&uacute;mero CR</label>
				      <input type="text" class="form-control" id="cr" name="cr" value="<?=$cliente->cr?>" disabled>
				      <span class='msg-erro msg-cr'></span>
				    </div>

				    <input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <?php if($admin): ?><?php endif; ?>
   


<script language="JavaScript">
<!--
function MM_openBrWindow(theUrl, winName, features) {
window.open(theUrl, winName, features);
}
-->
</script>
</head>
<body>


<!-- armars -->
<div style="margin-bottom: 15px;">
	<button class="accordion panel_filtro1" type="button"><b>REQUERIMENTO AUTORIZAÇÃO DE COMPRA</b></button>
	<div class="panel">
		<table style="margin-top: 0 !important" width="100%" cellspacing="1" cellpadding="5" bgcolor="#cccccc" class="table table-bordered">
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>Armas</b></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma1" id="arma1" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca1.' - '.$cliente->modelo1.' - '.$cliente->calibre1; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma2" id="arma2" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca2.' - '.$cliente->modelo2.' - '.$cliente->calibre2; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma3" id="arma3" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca3.' - '.$cliente->modelo3.' - '.$cliente->calibre3; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma4" id="arma4" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca4.' - '.$cliente->modelo4.' - '.$cliente->calibre4; ?></td>
			</tr>
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>Fornecedores</b></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor1" id="fornecedor1" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor1.' - '.$cliente->crfornecedor1; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor2" id="fornecedor2" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor2.' - '.$cliente->crfornecedor2; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor3" id="fornecedor3" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor3.' - '.$cliente->crfornecedor3; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor4" id="fornecedor4" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor4.' - '.$cliente->crfornecedor4; ?></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff" colspan="2" align="center"><a href="javascript:void(0);" onclick="return Comprar()" class="btn btn-info">Gerar Requerimento</a></td>
			</tr>
		</table>
	</div>
</div>


<script>
function Comprar(){
	var armas = "";
	var fornecedor = "";
	if($("#arma1").prop("checked") == true) armas += "1,"; 
	if($("#arma2").prop("checked") == true) armas += "2,"; 
	if($("#arma3").prop("checked") == true) armas += "3,"; 
	if($("#arma4").prop("checked") == true) armas += "4,";
	if($("#fornecedor1").prop("checked") == true) fornecedor += "1,"; 
	if($("#fornecedor2").prop("checked") == true) fornecedor += "2,"; 
	if($("#fornecedor3").prop("checked") == true) fornecedor += "3,"; 
	if($("#fornecedor4").prop("checked") == true) fornecedor += "4,"; 
	if(armas == ""){alert("Nenhuma arma selecionada."); return false;}
	if(fornecedor == ""){alert("Nenhum fornecedor selecionado."); return false;}
	document.getElementById("form_gerar").action="/relatorios/autoriz_compra.php?id=<?= $id_cliente; ?>&a="+armas+"&f="+fornecedor;
	document.getElementById("form_gerar").submit();
}
var acc = document.getElementsByClassName("panel_filtro1");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>
<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
  border-bottom: 1px solid #cccccc;
}
.active, .accordion:hover {
  background-color: #ccc;
}
.panel {
  padding: 18px;
  background-color: white;
  display: none;
  overflow: hidden;
}
.accordion:after {
  content: '\02795'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #777;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2796"; /* Unicode character for "minus" sign (-) */
}
</style>
<!-- armars -->

<!-- apostilar -->
<div style="margin-bottom: 15px;">
	<button class="accordion panel_filtro2" type="button"><b>REQUERIMENTO APOSTILAMENTO & CRAF</b></button>
	<div class="panel">
		<table style="margin-top: 0 !important" width="100%" cellspacing="1" cellpadding="5" bgcolor="#cccccc" class="table table-bordered">
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>Armas</b></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma1" id="arma1_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca1.' - '.$cliente->modelo1.' - '.$cliente->calibre1; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma2" id="arma2_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca2.' - '.$cliente->modelo2.' - '.$cliente->calibre2; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma3" id="arma3_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca3.' - '.$cliente->modelo3.' - '.$cliente->calibre3; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="arma4" id="arma4_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->marca4.' - '.$cliente->modelo4.' - '.$cliente->calibre4; ?></td>
			</tr>
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>Fornecedores</b></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor1" id="fornecedor1_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor1.' - '.$cliente->crfornecedor1; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor2" id="fornecedor2_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor2.' - '.$cliente->crfornecedor2; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor3" id="fornecedor3_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor3.' - '.$cliente->crfornecedor3; ?></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="checkbox" name="fornecedor4" id="fornecedor4_b" value="1"></td>
				<td bgcolor="#ffffff"><?= $cliente->fornecedor4.' - '.$cliente->crfornecedor4; ?></td>
			</tr>
			<tr>
				<td bgcolor="#ffffff" colspan="2" align="center"><a href="javascript:void(0);" onclick="return ComprarApostilamento()" class="btn btn-info">Gerar Requerimento</a></td>
			</tr>
		</table>
	</div>
</div>


<script>
function ComprarApostilamento(){
	var armas = "";
	var fornecedor = "";
	if($("#arma1_b").prop("checked") == true) armas += "1,"; 
	if($("#arma2_b").prop("checked") == true) armas += "2,"; 
	if($("#arma3_b").prop("checked") == true) armas += "3,"; 
	if($("#arma4_b").prop("checked") == true) armas += "4,";
	if($("#fornecedor1_b").prop("checked") == true) fornecedor += "1,"; 
	if($("#fornecedor2_b").prop("checked") == true) fornecedor += "2,"; 
	if($("#fornecedor3_b").prop("checked") == true) fornecedor += "3,"; 
	if($("#fornecedor4_b").prop("checked") == true) fornecedor += "4,"; 
	if(armas == ""){alert("Nenhuma arma selecionada."); return false;}
	if(fornecedor == ""){alert("Nenhum fornecedor selecionado."); return false;}
	document.getElementById("form_gerar").action="/relatorios/apostilamento.php?id=<?= $id_cliente; ?>&a="+armas+"&f="+fornecedor;
	document.getElementById("form_gerar").submit();
}
var acc = document.getElementsByClassName("panel_filtro2");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>

				    <?php if($admin): ?>

					<?php endif; ?>
                                    </FORM>
			<?php endif; ?>
		</fieldset>

	</div>
	<script type="text/javascript" src="js/custom.js"></script>

<form action="" method="post" name="form_gerar" id="form_gerar" target="_blank"></form>
</body>
</html>		
			
        <!-------------------------------------------------------------------------------------------------------------- page end-->
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
          by <a href="https://bootstrapmade.com/">HOSTMARQ</a><br><br>
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
