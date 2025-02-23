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

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_habitualidade WHERE id = :id';
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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Cadastro</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel_visitas.php">Tela de visitantes</a></li>
              <li><i class="fa fa-bars"></i>Visitante</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>
            <?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
            
            <?php
            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            
            
				<form action="action_cadastro_visitas.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
					<div class="col-md-2">
					    <a href="#" class="thumbnail">
					      <img src="fotosvisitas/<?=$cliente->foto?>" width="150" id="foto-cliente">
					    </a>
				  	</div>
				  	
			  	</div>
			<br>
				
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados do Visitante
              </header>
              <div class="panel-body">
                <div class="form">
			  <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Data da visita</td>
                    <td><input type="date" class="form-control" id="data_visita" name="data_visita" value="<?=$cliente->data_visita?>"></td>
                 </tr>
                  <tr>
                   <td>Convidado por</td>
                    <td><input type="text" class="form-control" id="convidado_por" name="convidado_por" value="<?=$cliente->convidado_por?>"></td>
                  </tr>
                  <tr>
                    <td>Email do atirador</td>
                    <td><input type="email" class="form-control" id="email_atirador" name="email_atirador" value="<?=$cliente->email_atirador?>"></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>
				  
	
       <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados pessoais
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Nome e Sobrenome</td>
                    <td><input type="text" class="form-control" id="nome_visita" name="nome_visita" value="<?=$cliente->nome_visita?>"></td>
                 </tr>
                  <tr>
                    <td>Whatsapp do visitante</td>
                    <td><input type="text" class="form-control" id="telefone_visita" name="telefone_visita" value="<?=$cliente->telefone_visita?>"></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		

       
       <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados Sociais
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Facebook</td>
                    <td><input type="text" class="form-control" id="facebook_visita" name="facebook_visita" value="<?=$cliente->facebook_visita?>"></td>
                 </tr>
                  <tr>
                   <td>Instagram</td>
                    <td><input type="text" class="form-control" id="instagram_visita" name="instagram_visita" value="<?=$cliente->instagram_visita?>"></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		


       <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Autoriza&ccedil;&ccedil;o para visitante
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Status da Autoriza&ccedil;&atilde;o</td>
                    <td><input type="radio" name="autorizacao" id="autorizacao" value="Autorizado" <?php if($cliente->autorizacao == "Autorizado") echo ' checked '; ?>>&nbsp;Autorizado&nbsp;<br>
&nbsp;<input type="radio" name="autorizacao" id="autorizacao" value="N&atilde;o Autorizado" <?php if($cliente->autorizacao == "N&atilde;o Autorizado") echo ' checked '; ?>>&nbsp;N&atilde;o Autorizado&nbsp;<br>
&nbsp;<input type="radio" name="autorizacao" id="autorizacao" value="Em An&aacute;lise" <?php if($cliente->autorizacao == "Em An&aacute;lise") echo ' checked '; ?>>&nbsp;Em An&aacute;lise<br>
&nbsp;<input type="radio" name="autorizacao" id="autorizacao" value="Cancelado" <?php if($cliente->autorizacao == "Cancelado") echo ' checked '; ?>>&nbsp;Cancelado</td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		


		
					<input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="submit" class="btn btn-info" id='botao'>Salvar</button>
                      </form>
                      <?php endif; ?>
              		</fieldset>		
    <!--main content end-->
		
        <!------------------------------------------------------------------------------------------------------------ page end-->
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
