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

?>
<?php

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = '
		SELECT 
			m.nome,
			f.*,
			m.id,
			m.matricula,
			m.plano_pgto
		FROM 
			tab_membros m
				LEFT JOIN tab_financeiro f on f.matricula = m.matricula
		WHERE 
			m.id = :id
		';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
		$sql2 = '
		SELECT 
		*
		FROM 
			info_clube 
	
		';
	$stm = $conexao->prepare($sql2);

	$stm->execute();
	$clube = $stm->fetch(PDO::FETCH_OBJ);

	if(!empty($cliente)):

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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
            <h3 class="page-header"><i class="fa fa fa-bars"></i> <?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente n㯠encontrado!</h3>
			<?php else: ?>
			<form action="action_financeiro.php" method="post" id='form-contato' enctype='multipart/form-data'>
		
		
		
		            <!--collapse start-->
            <div class="panel-group m-bot20" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                   <i class="icon_calendar"></i>&nbsp;Ano 2025
                    </a>
                    </h4>
                    </div>
                    <div id="collapseOne" class="collapse">
                    <div class="panel-body">
                   <!--<?php include 'ano2025.php';?>-->
				   <div class="form-group">
                   </div></div></div></div></div>
		
		            <!--collapse start-->
            <div class="panel-group m-bot20" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                   <i class="icon_calendar"></i>&nbsp;Ano 2024
                    </a>
                    </h4>
                    </div>
                    <div id="collapseTwo" class="collapse">
                    <div class="panel-body">
                   <?php include 'ano2024.php';?>
				   <div class="form-group">
                   </div></div></div></div></div>
                   
                   
                   
              <!--collapse start-->
            <div class="panel-group m-bot20" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
					
                   <i class="icon_calendar"></i>&nbsp;Ano 2023
                    </a>
                    </h4>
                    </div>
                    <div id="collapseThree" class="collapse">
                    <div class="panel-body">
                   <!--<?php include 'ano2023.php';?>-->
				   <div class="form-group">
                   </div></div></div></div></div>

				   
		            <!--collapse start-->
            <div class="panel-group m-bot20" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                   <i class="icon_calendar"></i>&nbsp;Ano 2022
                    </a>
                    </h4>
                    </div>
                    <div id="collapseFour" class="collapse">
                    <div class="panel-body">
                   <!--<?php include 'ano2022.php';?>-->
				   <div class="form-group">
                   </div></div></div></div></div>
				   
				   
							
			    <input type="hidden" name="acao" value="editar">
				<input type="hidden" name="id" value="<?=$cliente->id?>">
				<input type="hidden" name="matricula" value="<?=$cliente->matricula?>">
				<input type="hidden" name="plano" value="<?=$cliente->plano_pgto?>">

				<button type="submit" class="btn btn-primary" id='botao'>Salvar</button>
				

                </form>

				<?php endif; ?>
		</fieldset>
    <!--main content end-->
		
        <!----------------------------------------------------------------------------------------------- page end-->
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


</body>

</html>