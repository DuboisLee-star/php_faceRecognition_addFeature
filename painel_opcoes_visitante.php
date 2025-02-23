<?php

date_default_timezone_set('America/Sao_Paulo');

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
	$sql = 'SELECT * FROM tab_habitualidade WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
	  $conexao = conexao::getInstance();
      $sql4 = 'SELECT * FROM tab_armas ORDER BY descricao ASC';
      $stm = $conexao->prepare($sql4);
      $stm->execute();
      $armas = $stm->fetchAll(PDO::FETCH_OBJ);
    
      $conexao = conexao::getInstance();
      $sql5 = 'SELECT * FROM tab_habitu_op WHERE tipo = "L" ORDER BY local ASC';
      $stm = $conexao->prepare($sql5);
      $stm->execute();
      $habitu_op = $stm->fetchAll(PDO::FETCH_OBJ);

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

  <!-- PARA adicionar botao +  -->
    <link rel="stylesheet" href="path/to/your/css">
    <style>
        .form-group {
            margin-bottom: 10px;
        }
        .list-item {
            margin-bottom: 5px;
        }
    </style>
    
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i);

    if (texto.substring(0,1) != saida){
        documento.value += texto.substring(0,1);
    } else {
        documento.value += texto.substring(0,1);
    }
}

function salva(){
	
    var nome_visitante = document.getElementById("nome_visitante").value;	

	if(nome_visitante == ""){alert("Informe o nome do Atirador."); return false;}

	document.getElementById("form-contato").submit();
	
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>ÁREA DE VISITANTE</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>

                <!-- page start -->
                <fieldset>
                    <form name="cadastro" action="action_cadastro_habitualidade_visitante.php" method="post" id='form-contato' enctype='multipart/form-data'>
                        <div class="row">
                            <div class="col-md-2"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <section class="panel">
                                    <header class="panel-heading">PARTICIPAÇÕES</header>
                                    <div class="panel-body">
                                        <div class="form">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Escolha uma opção:</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <!-- Botão para "TREINAMENTOS" -->
                                                            <a href="cadastro_treinamento_visitante.php" class="btn btn-info">TREINAMENTOS</a>

                                                            <!-- Botão para "COMPETIÇÕES" -->
                                                            <a href="cadastro_competicoes_visitante.php" class="btn btn-info">COMPETIÇÕES</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" id="tipo_atirador" name="tipo_atirador" value="2">
                        <input type="hidden" name="data" value="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" name="acao" value="<?php echo strlen(trim($id_cliente)) > 0 ? 'editar' : 'incluir'; ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($cliente->id); ?>">
                    </form>
                </fieldset>
                <!-- page end -->
            </section>
        </section>
        <!--main content end-->

        <div class="text-center">
            <div class="credits">by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br></div>
        </div>
    </section>

    <!-- Custom JS -->
    <script src="js/custom.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- nice scroll -->
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <!-- custom script for all page -->
    <script src="js/scripts.js"></script>

</body>
</html>