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

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;
	
}
?>

<?php

require 'config/conexao.php';
$DataAtual = date("d-m-Y");  

// Recebe o termo de pesquisa se existir
$termo = (isset($_GET['termo'])) ? $_GET['termo'] : '';

// Verifica se o termo de pesquisa está vazio, se estiver executa uma consulta completa
if (empty($termo)):

	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE bloqueio LIKE "sim" order by matricula';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

else:

	// Executa uma consulta baseada no termo de pesquisa passado como parâmetro
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros order by matricula';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':nome', $termo.'%');
	$stm->bindValue(':matricula', $termo.'%');
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

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
        <div class="icon-reorder tooltips" data-original-title="Menu Navega&ccedil;&atilde;o" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
            <form class="navbar-form" method="get" id='form-contato' action="" >
            <input class="form-control" placeholder="Search" type="text" for="termo" id="termo" name="termo">
		    <button type="submit" class="btn btn-success pull btn-sm">Buscar</button>
            </form>
          </li>
        </ul>
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
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-money" aria-hidden="true"></i>Inadimplentes</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-table"></i>Tabela</li>
            </ol>
          </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Tabela de Atiradores Inadimplentes
              </header>
              <div class="table-responsive">
			  <?php if(!empty($clientes)):?>
                <table class="table">
                  <thead>
                    <tr>
                      <th><i class="icon_profile"></i> Foto</th>
                      <th><i class="icon_profile"></i> Nome completo</th>
                      <th><i class="icon_mobile"></i> Plano</th>
                      <th><i class="icon_mobile"></i> Anual</th>
					  <td>J</td>
					  <td>F</td>
					  <td>M</td>					  
					  <td>A</td>
					  <td>M</td>
					  <td>J</td>
					  <td>J</td>
					  <td>A</td>
					  <td>S</td>
					  <td>O</td>
					  <td>N</td>					  	
					  <td>D</td>
                      <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
                    </tr>
					<?php foreach($clientes as $cliente):?>
                  </thead>
                  <tbody>
                    <tr>
                      <td><img src='fotos/<?=$cliente->foto?>' height='30' width='30'></td>
                      <td><?=$cliente->nome?></td>
                      <td><?=$cliente->plano_pgto?></td>
                      <td><?=$cliente->anuidade2022?></td>
					  <td><?=$cliente->mens_jan2022?></td>
					  <td><?=$cliente->mens_fev2022?></td>
					  <td><?=$cliente->mens_mar2022?></td>					  
					  <td><?=$cliente->mens_abr2022?></td>
					  <td><?=$cliente->mens_mai2022?></td>
					  <td><?=$cliente->mens_jun2022?></td>
					  <td><?=$cliente->mens_jul2022?></td>
					  <td><?=$cliente->mens_ago2022?></td>
					  <td><?=$cliente->mens_set2022?></td>
					  <td><?=$cliente->mens_out2022?></td>
					  <td><?=$cliente->mens_nov2022?></td>					  	
					  <td><?=$cliente->mens_dez2022?></td>
					  <td>
<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='editar_financeiro.php?id=<?=$cliente->id?>' Title="Editar" alt="Editar"><i class="fa fa-edit" aria-hidden="true"></i></a>
</div>

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='email_renovacao.php?id=<?=$cliente->id?>' Title="Alerta Renova&ccedil;&atilde;o" alt="Alerta Renova&ccedil;&atilde;o"><i class="fa fa-print" aria-hidden="true"></i></a>
</div>						

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='relatorios/membros_inativos.php?id=<?=$cliente->id?>' Title="Membros Inativos" alt="Membros Inativos"><i class="fa fa-bullhorn" aria-hidden="true"></i></a>
</div>	

					  </td>
                    </tr>
                  </tbody>
				  <?php endforeach;?> 
				  
                </table>
				<?php else: ?>

				<!-- Mensagem caso não exista clientes ou não encontrado  -->
				<h3 class="text-center text-primary">Não existem membros cadastrados!</h3>
			<?php endif; ?> 
			
		</fieldset>
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


</body>

</html>