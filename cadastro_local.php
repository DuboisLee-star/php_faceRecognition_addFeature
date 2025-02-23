<?php
include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
}

?>
<?php
require 'config/conexao.php';

$id = isset($_GET['id']) ? $_GET['id'] : false;
$local = isset($_POST['local']) ? $_POST['local'] : false;

if ($local) {

    if ($id) {

        $conexao = conexao::getInstance();
        $sql = "UPDATE tab_habitu_op SET local = :local, data_alteracao = :data_alteracao WHERE id = :id AND tipo = 'L'";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':local', $local);
        $stm->bindValue(':data_alteracao', date('Y-m-d H:i:s'));
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        exit("<script>alert('" . (($retorno) ? 'Alteração realizada com sucesso.' : 'Falha ao alterar local.') . "'); window.location='cadastro_local.php';</script>");

    } else {

        $conexao = conexao::getInstance();
        $sql = "INSERT INTO tab_habitu_op (local, data_cadastro, tipo) VALUES (:local, :data_cadastro, 'L') ";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':local', $local);
        $stm->bindValue(':data_cadastro', date('Y-m-d H:i:s'));
        $retorno = $stm->execute();

        exit("<script>alert('" . (($retorno) ? 'Cadastro realizado com sucesso.' : 'Falha ao cadastrar local.') . "'); window.location='cadastro_local.php';</script>");
    }

    exit();
}

// Capture the data of the requested weapon
$conexao = conexao::getInstance();
$sql = "SELECT * FROM tab_habitu_op WHERE data_exclusao IS NULL AND tipo = 'L' ORDER BY local ASC ";
$stm = $conexao->prepare($sql);
$stm->execute();
$armas = $stm->fetchAll(PDO::FETCH_OBJ);

if ($id) {

    $conexao = conexao::getInstance();
    $sql = "SELECT * FROM tab_habitu_op WHERE data_exclusao IS NULL AND id = :id AND tipo = 'L' ORDER BY local ASC ";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $stm->execute();
    $dadosLocal = $stm->fetch(PDO::FETCH_OBJ);
    $local = $dadosLocal->local;
}
?>
<!-- Rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>  
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />  
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}

</script>

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
            <h3 class="page-header"><i class="fa fa-plus-circle" aria-hidden="true"></i>Cadastro de Locais</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				
        <div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
					CADASTRO DE LOCAIS DE HABITUALIDADES
					</header>
					<div class="panel-body">
						<div class="form">

							<form action="" method="post" name="filtro_form" id="filtro_form">
								<input type="hidden" name="id" value="<?= ($id) ? $id : ''; ?>">
								<div class="row">
                                    <div class="col-lg-3">
                                       <b>Nome do local:</b> 
										<input type="text" required class="form-control" id="local" name="local" placeholder="CLUBE, ESTANDE, CTC, CTA..." value="<?= ($local) ? $local : ''; ?>">
                                    </div>	</p>							    
									<div class="col-lg-3"><br>
										<button class="btn btn-info" type="button" onclick="return salvar()">Salvar</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</section>
			</div>
          </div>
		  
		  
		  
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
						LOCAIS CADASTRADOS
						</header>
						<div class="panel-body">
							<div class="form">


	<table class="table table-striped">
    <tr>
        <td><b>Local do treino</b></td>        
        <td><b><i class="fa fa-cogs" aria-hidden="true"></i></b></td>
        <td><b></b></td>
    </tr>
    <?php if ($armas): ?>
        <?php foreach ($armas as $key => $local): ?>
            <tr>
                <td><?= $local->local; ?></td>
                <td><a href="?id=<?= $local->id; ?>" class="btn btn-info btn-sm" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

							</div>
						</div>
					</section>
				</div>
			</div>
		  
		  
    </fieldset>					  
			  
            				   
    <!--main content end-->
	<!--------------------------------------------------------- page end-->
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
  
  

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->
  
  <!--<script type="text/javascript" src="js/custom.js"></script>-->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  <script src="js/jquery.maskMoney.js"></script>
<script>
const salvar = () => {
    var local = document.getElementById("local").value;    

    if (local === "") {
        alert("Preencha todos os campos obrigatórios.");
        return false;
    }

    document.getElementById('filtro_form').submit();
}
</script>

</body>

</html>