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

$data_ini = isset($_POST['data_ini']) ? $_POST['data_ini'] : false;
$data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : false;


if($data_ini && $data_fim){
    
    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = "SELECT * FROM tab_logwhatsapp WHERE datacadastro BETWEEN :data_ini and :data_fim ORDER BY datacadastro DESC ";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':data_ini', $data_ini.' 00:00:00');
    $stm->bindValue(':data_fim', $data_fim.' 23:59:59');
    $stm->execute();
    $relatorio = $stm->fetchAll(PDO::FETCH_OBJ);
    
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Relatório de envio WhatsApp</h3>
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
					RELATÓRIO DE ENVIO WHATSAPP
					</header>
					<div class="panel-body">
						<div class="form">

							<form action="" method="post" name="filtro_form">
								<div class="row">
									<div class="col-lg-3">
										<input type="date" required class="form-control" id="data_ini" name="data_ini" placeholder="dd/mm/aaaa" value="<?= ($data_ini) ? $data_ini : ''; ?>">
									</div>
									<div class="col-lg-3">
										<input type="date" required class="form-control" id="data_fim" name="data_fim" placeholder="dd/mm/aaaa" value="<?= ($data_fim) ? $data_fim : ''; ?>">
									</div>
									<div class="col-lg-3">
										<button class="btn btn-primary" type="submit">Exibir</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</section>
			</div>
          </div>
		  
		  
		  <?php if($data_ini && $data_fim): ?>
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
						RELATÓRIO
						</header>
						<div class="panel-body">
							<div class="form">


								<table class="table table-striped">
									<tr>
										<td><b>Data</b></td>
										<td><b>Celular</b></td>
										<td><b>Mensagem</b></td>
										<td><b>Status</b></td>
									</tr>
									<?php if($relatorio): ?>
										<?php foreach($relatorio as $key => $rel): ?>
										<tr>
											<td><?= date('d/m/Y H:i', strtotime($rel->datacadastro)); ?></td>
											<td><?= $rel->numero; ?></td>
											<td><?= $rel->mensagem; ?></td>
											<td><?= ($rel->sucesso) ? '<span class="badge bg-success">sucesso</span>' : '<span class="badge bg-danger">erro</span>'; ?></td>
										</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</table>

							</div>
						</div>
					</section>
				</div>
			</div>
		  <?php endif; ?>
		  
		  
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
$(document).ready(function(e){
	$("[data-money]").maskMoney({thousands:'.', decimal:',', precision: 2, allowZero: true});
});
const selecionaPlano = (plano) => {
	$("[data-money]").val('0,00');
	$("[data-plano]").addClass("hidden");
	$("[data-plano="+plano+"]").removeClass("hidden");
}
</script>

</body>

</html>