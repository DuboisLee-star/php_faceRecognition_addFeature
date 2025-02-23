<?php

include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit;
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$data_inicial = isset($_POST['data_inicial']) ? $_POST['data_inicial'] : false;
$data_final = isset($_POST['data_final']) ? $_POST['data_final'] : false;

if ($data_inicial && $data_final) {
    
    // Escapar as entradas para evitar SQL Injection
    $data_inicial = mysqli_real_escape_string($con, $data_inicial);
    $data_final = mysqli_real_escape_string($con, $data_final);

    // Montar a consulta SQL para buscar nas colunas `validade_gt`
    $query = "
    SELECT m.matricula, m.nome, a.validade_gt
    FROM tab_membros m
    JOIN tab_armas a ON m.matricula = a.matricula
    WHERE a.validade_gt BETWEEN '$data_inicial' AND '$data_final'
    ORDER BY a.matricula"; 
    
    $result = mysqli_query($con, $query);

    if ($result) {
        
        // Processar os resultados, por exemplo:
        while ($row = mysqli_fetch_assoc($result)) {
            
            // Aqui você pode trabalhar com os dados retornados
        }
    } else {
        echo "Erro na consulta: " . mysqli_error($con);
    }
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Pesquisa em GTs</h3>
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
					<header class="panel-heading">ESCOLHA O PERÍODO</header>
					<div class="panel-body">
						<div class="form">

							<form action="" method="post" name="filtro_form">
								<div class="row">
									<div class="col-lg-3">
										<input type="date" required class="form-control" id="data_inicial" name="data_inicial" placeholder="dd/mm/aaaa" value="<?= ($data_inicial) ? $data_inicial : ''; ?>">
									</div>
									<div class="col-lg-3">
										<input type="date" required class="form-control" id="data_final" name="data_final" placeholder="dd/mm/aaaa" value="<?= ($data_final) ? $data_final : ''; ?>">
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
		  
		  
		  <?php if($data_inicial && $data_final): ?>
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">RESULTADO</header>
						<div class="panel-body">
							<div class="form">


<table class="table table-striped">
    <tr>
        <td><b>Matrícula</b></td> 
        <td><b>Nome</b></td>  
        <td><b>Validade GT</b></td>
        <td><b>Dias</b></td>
        <td><b>Status</b></td>
    </tr>
<?php if($result): ?>
    <?php foreach($result as $key => $rel): ?>
        <tr>
            <td><?= $rel['matricula']; ?></td>
            <td><?= $rel['nome']; ?></td> <!-- Removida a função utf8_decode -->
            <td><?= date('d/m/Y', strtotime($rel['validade_gt'])); ?></td>
            <td>
                <?php
                // Calcula os dias restantes
                $dataAtual = new DateTime();
                $validade_gt = new DateTime($rel['validade_gt']);
                $interval = $dataAtual->diff($validade_gt);
                $diasRestantes = $interval->format('%R%a dias');
                echo $diasRestantes;
                ?>
            </td>
            <td>
                <?= (strtotime($rel['validade_gt']) >= strtotime('today')) ? '<span class="badge bg-success">válido</span>' : '<span class="badge bg-danger">vencido</span>'; ?>
            </td>
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