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
<?php
$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_procuradores order by id';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);
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
	<button class="accordion panel_filtro1" type="button"><b>EXÉRCITO</b></button>
	<div class="panel">
		
		<table style="margin-top: 0 !important" width="100%" cellspacing="1" cellpadding="5" bgcolor="#cccccc" class="table table-bordered">
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>PROCURADOR 1</b></td>
			</tr> 
		
				
			<?php foreach($clientes as $cliente):?>
			
		<tr>
				<td bgcolor="#f0f0f0" align="center" width="30">
					<div class="span3"><input class="checkbox1" type="checkbox" name="bairro[]"  value="<?=$cliente->id?>"></div></td>
				<td bgcolor="#ffffff"><?=$cliente->apelido?></td>
			
			</tr> 
			
			<?php endforeach;?> 
			
			<tr>
				<td bgcolor="#ffffff" colspan="2" align="center"><a href="#" id="imprimir" class="btn btn-info">Gerar Procuração</a></td>
			</tr> 
		</table>
	</div>
</div> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
$(document).ready(function(){             
  $("#imprimir").click(function(){           
  var testval = [];  
     $('.checkbox1:checked').each(function() {
       testval.push($(this).val());
     }); 
     
     
     
    document.getElementById("imprimir").href = "/relatorios/procuracao_modelo1.php?id=<?= $id_cliente; ?>&a="+testval.join('&f=');
 });
  
});</script>
<script>
			
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
	<button class="accordion panel_filtro2" type="button"><b>POLÍCIA FEDERAL</b></button>
	<div class="panel">
		<table style="margin-top: 0 !important" width="100%" cellspacing="1" cellpadding="5" bgcolor="#cccccc" class="table table-bordered">
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>PROCURADOR 1</b></td>
			</tr>
			<?php if(!empty($clientes)):?>
			<?php foreach($clientes as $cliente):?>
			
			<tr>
				
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="radio" name="arma4" id="arma4_b" value="<?=$cliente->id?>"></td>
				<td bgcolor="#ffffff"><?=$cliente->apelido?></td>
			</tr>
			<?php endforeach;?> 
			
			<tr>
				<td colspan="2" bgcolor="#f0f0f0" align="center"><b>PROCURADOR 2</b></td>
			</tr> 
			<?php foreach($clientes as $cliente):?>
			
			<tr>
				
				<td bgcolor="#f0f0f0" align="center" width="30"><input class="checkbox" type="radio" name="fornecedor4" id="fornecedor4_b" value="<?=$cliente->id?>"></td>
				<td bgcolor="#ffffff"><?=$cliente->apelido?></td>
			</tr> 
			<?php endforeach;?> 
			<?php else: ?>
			
			<!-- Mensagem caso não exista clientes ou não encontrado  -->
			<h3 class="text-center text-primary">Não existem procuradores cadastrados!</h3>
			<?php endif; ?>
			<tr>
				<td bgcolor="#ffffff" colspan="2" align="center"><a id="imprimir2" class="btn btn-info">Gerar Procuração</a></td>
			</tr>
		</table>
	</div>
</div>

<script>
$(document).ready(function(){             
  $("#imprimir2").click(function(){           
  var testval2 = [];
     $('#arma4_b:checked').each(function() {
       testval2.push($(this).val());
     }); 
     var testval3 = [];
     $('#fornecedor4_b:checked').each(function() {
     testval3.push($(this).val());
     }); 
     
    document.getElementById("imprimir2").href = "/relatorios/procuracao_modelo2.php?id=<?= $id_cliente; ?>&a="+testval2+"&f="+testval3;
 });
  
});</script> 

<script>

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