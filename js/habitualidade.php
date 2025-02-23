<?php

date_default_timezone_set('America/Sao_Paulo');

include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
  header('Location: index.php');
}

// logout
if (isset($_POST['but_logout'])) {
  session_destroy();
  header('Location: index.php');
}

?>
<?php

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele � num�rico
if (!empty($id_cliente) && is_numeric($id_cliente)) :

  // Captura os dados do cliente solicitado
  $conexao = conexao::getInstance();
  $sql = 'SELECT * FROM tab_membros WHERE id = :id';
  $stm = $conexao->prepare($sql);
  $stm->bindValue(':id', $id_cliente);
  $stm->execute();
  $cliente = $stm->fetch(PDO::FETCH_OBJ);

  // pega dados da habitualidade
  $sql2 = " SELECT * FROM tab_habitualidade WHERE matricula = :matricula ORDER BY datacadastro DESC ";
  $stm = $conexao->prepare($sql2);
  $stm->bindValue(':matricula', $cliente->matricula);
  $stm->execute();
  $habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

  $conexao = conexao::getInstance();
  $sql3 = 'SELECT * FROM tab_membros ORDER BY nome ASC';
  $stm = $conexao->prepare($sql3);
  $stm->bindValue(':id', $id_cliente);
  $stm->execute();
  $atiradores = $stm->fetchAll(PDO::FETCH_OBJ);

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

  $conexao = conexao::getInstance();
  $sql6 = 'SELECT * FROM tab_habitu_op WHERE tipo = "E" ORDER BY evento ASC';
  $stm = $conexao->prepare($sql6);
  $stm->execute();
  $habitu_ev = $stm->fetchAll(PDO::FETCH_OBJ);

  // Faça a consulta ao banco de dados para obter os calibres do tipo 'C'
  $conexao = conexao::getInstance();
  $sql = "SELECT calibre FROM tab_habitu_op WHERE tipo = 'C'";
  $stm = $conexao->prepare($sql);
  $stm->execute();
  $getallCalibres = $stm->fetchAll(PDO::FETCH_COLUMN);

  if (!empty($cliente)) :

    // Formata a data no formato nacional
    $array_data     = explode('-', $cliente->data_nascimento);
    $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

  endif;

endif;

$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);

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
 <style>
  .modal .modal-dialog { width: 30%; }  
  @media(min-width:768px){  
  .modal .modal-dialog { width: 20%; }  
    } 
  </style>
  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Escolha um periodo</h5>
   </button>
  </div>
  <div class="modal-body">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function(){  
  $('.data').on('keyup', function(){
  var $this = $(this);
  var mydate = $this.val();
  mydate = mydate.replace(/\D|\s/, '');  
  mydate = mydate.replace(/^(00)(.*)?/, '01$2');
  mydate = mydate.replace(/^([0-9]{2})(00)(.*)?/, '$101');
  mydate = mydate.replace(/^([3-9])([2-9])(.*)?/, '2$2');
  mydate = mydate.replace(/^(3[01])(02)(.*)?/, '29$2');
  mydate = mydate.replace(/^([0-9]{2})([2-9]|1[3-9])(.*)?/, '$112');
  mydate = mydate.replace(/^([0-9]{2})([0-9]{2})([0-9].*?)/, '$1/$2/$3');
  mydate = mydate.replace(/^([0-9]{2})([0-9])/, '$1/$2');    
  //ano bissexto
  var day = mydate.substr(0,2) || '01';
  var month = mydate.substr(3,2) || '01';
  var year = mydate.substr(6,4);
  if(year.length == 4 && day == '29' && month == '02' && (year % 4 != 0 || (year.substr(2,2) == '00' && year % 400 != 0))) {
  mydate = mydate.replace(/^29/,'28');
  }
  mydate = mydate.substr(0,10);
  $this.val(mydate);
  })
  })
  </script>
  <form method="get" id="form" target="_blank" action="relatorios/habitualidade2.php" >
  <div class="row" >
  <div class="col"> 
  <label for="formGroupExampleInput">&nbsp;De:</label>
  <input type="text"  class="data form-control"  name="datainicial" value="" required>
  <label for="formGroupExampleInput">&nbsp;Até:</label>
  <input type="text" class="data form-control" name="datafinal" value="" required>
  </div> 
  </div>
  </form>
  </div>
  
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
  <input type="submit"  onclick="document.getElementById('form').submit();" class="btn btn-primary" value="Gerar">
  </div>
  </div>
  </div>
  </div>
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
  <style>
    .gd-dropdown-menu .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.25rem 1.5rem;
      clear: both;
      font-weight: 400;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
    }
    .gd-dropdown-menu .dropdown-item:hover {
      color: #16181b;
      text-decoration: none;
      background-color: #f8f9fa;
    }
  </style>
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
    <?php include 'menu_lateral_esq.php'; ?>
    <!-- menu lateral fim -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i><?= $cliente->nome ?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matrícula > <?= $cliente->matricula ?></li>
            </ol>
          </div>
        </div>

        <form method="POST" action="">
        <div class="form-group">
            <label for="datainicial">Data Inicial</label>
            <input type="date" class="form-control" id="datainicial" onchange="updateLinks()">
        </div>
        <div class="form-group">
            <label for="datafinal">Data Final</label>
            <input type="date" class="form-control" id="datafinal" onchange="updateLinks()">
        </div>          </form>

                     
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $datainicial = isset($_POST['datainicial']) ? $_POST['datainicial'] : '';
    $datafinal = isset($_POST['datafinal']) ? $_POST['datafinal'] : '';

   
}
 echo $datainicial;
?>

 
        <div class="row" style="margin-bottom: 10px;">
          <div class="col-lg-12">
            <b>Atirador:</b>
            <select class="form-control select2" onchange="selecionaAtirador(this.value);">
              <?php if ($atiradores) : ?>
                <?php foreach ($atiradores as $key => $Atirador) : ?>
                  <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>><?= $Atirador->nome . ' - ' . $Atirador->matricula; ?></option>
                  
                  <?php endforeach; ?>
              <?php endif; ?>
            </select>
            
          
           
          </div>
        </div>


        <!-------------------------------------------------------------------------------------------------------------- page start-->


        <?php if (empty($cliente)) : ?>
          <h3 class="text-center text-danger">Cliente não encontrado!</h3>
        <?php else : ?>

          <form action="action_habitualidade.php" method="post" id='form-contato' enctype='multipart/form-data'>


            <input type="hidden" class="form-control" id="matricula" name="matricula" value="<?= $cliente->matricula ?>" readonly>
            <input type="hidden" class="form-control" id="data_filiacao" name="data_filiacao" value="<?= $cliente->data_filiacao ?>" disabled>
            <input type="hidden" class="form-control" id="cr" name="cr" value="<?= $cliente->cr ?>" disabled>
            <input type="hidden" class="form-control" id="validade_cr" name="validade_cr" value="<?= $cliente->validade_cr ?>" disabled>
            <input type="hidden" class="form-control" id="nome" name="nome" value="<?= $cliente->nome ?>" disabled>


            <div class="form-group">
              <script>
                $(document).ready(function() {
                  $('.select2').select2();
                })

                function selecionaAtirador(id_atirador) {
                  window.location = 'habitualidade.php?id=' + id_atirador;
                }

                let calibres = <?=json_encode($getallCalibres)?>;
                let options = '';
                for(let i = 0; i < calibres.length; i++){
                  options += '<option value="' + calibres[i] + '">' + calibres[i] + '</option>';
                }

                function addHabitualidade(tipo) {

                  var linha = parseInt($("[data-linha]").length) + 1;

                  if (tipo == 2) {

                    var select_calibre = '<select name="manual_calibre_' + linha + '[]" class="form-control">' + options + '</select>';

                    var armas = '<div class="form-group">' +
                      '<div class="col-sm-3" style="padding:0 !important;"><input type="text" name="manual_tipo_' + linha + '[]" class="form-control" placeholder="Tipo"></div>' +
                      '<div class="col-sm-3" style="padding:0 !important;"><input type="text" name="manual_modelo_' + linha + '[]" class="form-control" placeholder="Modelo"></div>' +
                      '<div class="col-sm-3" style="padding:0 !important;">' + select_calibre + '</div>' +
                      '<div class="col-sm-3" style="padding:0 !important;"><input type="text" name="manual_sigma_' + linha + '[]" class="form-control" placeholder="Sigma"></div></div>';

                  } else {
                    var armas = '<select name="tipo_' + linha + '[]" class="form-control">';
                    armas += '<option value="">- selecione -</option>';

                    <?php if ($armas) : ?>
                      armas += '<optgroup label="== Acervo Clube-Instrutor ==">';
                      <?php foreach ($armas as $key => $Armas) : ?>
                        armas += '<option value="<?= $Armas->tipo . '|' . $Armas->modelo . '|' . $Armas->calibre . '|' . $Armas->numsigma; ?>"><?= $Armas->tipo . ' | ' . $Armas->modelo . ' | ' . $Armas->calibre . ' | ' . $Armas->numsigma; ?></option>';
                      <?php endforeach; ?>
                      armas += '</optgroup>';
                    <?php endif; ?>

                    armas += '<optgroup label="== Acervo Pessoal ==">';

                    armas += '<?php if ($cliente->validade_craf1 >= date('Y-m-d') && $cliente->validade_gt1 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie1 . '|' . $cliente->modelo1 . '|' . $cliente->calibre1 . '|' . $cliente->num_sigma_arma1 ?>">- <?php echo $cliente->especie1 . ' | ' . $cliente->modelo1 . ' | ' . $cliente->calibre1 . ' | ' . $cliente->num_sigma_arma1 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf2 >= date('Y-m-d') && $cliente->validade_gt2 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie2 . '|' . $cliente->modelo2 . '|' . $cliente->calibre2 . '|' . $cliente->num_sigma_arma2 ?>">- <?php echo $cliente->especie2 . ' | ' . $cliente->modelo2 . ' | ' . $cliente->calibre2 . ' | ' . $cliente->num_sigma_arma2 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf3 >= date('Y-m-d') && $cliente->validade_gt3 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie3 . '|' . $cliente->modelo3 . '|' . $cliente->calibre3 . '|' . $cliente->num_sigma_arma3 ?>">- <?php echo $cliente->especie3 . ' | ' . $cliente->modelo3 . ' | ' . $cliente->calibre3 . ' | ' . $cliente->num_sigma_arma3 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf4 >= date('Y-m-d') && $cliente->validade_gt4 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie4 . '|' . $cliente->modelo4 . '|' . $cliente->calibre4 . '|' . $cliente->num_sigma_arma4 ?>">- <?php echo $cliente->especie4 . ' | ' . $cliente->modelo4 . ' | ' . $cliente->calibre4 . ' | ' . $cliente->num_sigma_arma4 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf5 >= date('Y-m-d') && $cliente->validade_gt5 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie5 . '|' . $cliente->modelo5 . '|' . $cliente->calibre5 . '|' . $cliente->num_sigma_arma5 ?>">- <?php echo $cliente->especie5 . ' | ' . $cliente->modelo5 . ' | ' . $cliente->calibre5 . ' | ' . $cliente->num_sigma_arma5 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf6 >= date('Y-m-d') && $cliente->validade_gt6 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie6 . '|' . $cliente->modelo6 . '|' . $cliente->calibre6 . '|' . $cliente->num_sigma_arma6 ?>">- <?php echo $cliente->especie6 . ' | ' . $cliente->modelo6 . ' | ' . $cliente->calibre6 . ' | ' . $cliente->num_sigma_arma6 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf7 >= date('Y-m-d') && $cliente->validade_gt7 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie7 . '|' . $cliente->modelo7 . '|' . $cliente->calibre7 . '|' . $cliente->num_sigma_arma7 ?>">- <?php echo $cliente->especie7 . ' | ' . $cliente->modelo7 . ' | ' . $cliente->calibre7 . ' | ' . $cliente->num_sigma_arma7 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf8 >= date('Y-m-d') && $cliente->validade_gt8 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie8 . '|' . $cliente->modelo8 . '|' . $cliente->calibre8 . '|' . $cliente->num_sigma_arma8 ?>">- <?php echo $cliente->especie8 . ' | ' . $cliente->modelo8 . ' | ' . $cliente->calibre8 . ' | ' . $cliente->num_sigma_arma8 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf9 >= date('Y-m-d') && $cliente->validade_gt9 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie9 . '|' . $cliente->modelo9 . '|' . $cliente->calibre9 . '|' . $cliente->num_sigma_arma9 ?>">- <?php echo $cliente->especie9 . ' | ' . $cliente->modelo9 . ' | ' . $cliente->calibre9 . ' | ' . $cliente->num_sigma_arma9 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf10 >= date('Y-m-d') && $cliente->validade_gt10 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie10 . '|' . $cliente->modelo10 . '|' . $cliente->calibre10 . '|' . $cliente->num_sigma_arma10 ?>">- <?php echo $cliente->especie10 . ' | ' . $cliente->modelo10 . ' | ' . $cliente->calibre10 . ' | ' . $cliente->num_sigma_arma10 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf11 >= date('Y-m-d') && $cliente->validade_gt11 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie11 . '|' . $cliente->modelo11 . '|' . $cliente->calibre11 . '|' . $cliente->num_sigma_arma11 ?>">- <?php echo $cliente->especie11 . ' | ' . $cliente->modelo11 . ' | ' . $cliente->calibre11 . ' | ' . $cliente->num_sigma_arma11 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf12 >= date('Y-m-d') && $cliente->validade_gt12 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie12 . '|' . $cliente->modelo12 . '|' . $cliente->calibre12 . '|' . $cliente->num_sigma_arma12 ?>">- <?php echo $cliente->especie12 . ' | ' . $cliente->modelo12 . ' | ' . $cliente->calibre12 . ' | ' . $cliente->num_sigma_arma12 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf13 >= date('Y-m-d') && $cliente->validade_gt13 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie13 . '|' . $cliente->modelo13 . '|' . $cliente->calibre13 . '|' . $cliente->num_sigma_arma13 ?>">- <?php echo $cliente->especie13 . ' | ' . $cliente->modelo13 . ' | ' . $cliente->calibre13 . ' | ' . $cliente->num_sigma_arma13 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf14 >= date('Y-m-d') && $cliente->validade_gt14 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie14 . '|' . $cliente->modelo14 . '|' . $cliente->calibre14 . '|' . $cliente->num_sigma_arma14 ?>">- <?php echo $cliente->especie14 . ' | ' . $cliente->modelo14 . ' | ' . $cliente->calibre14 . ' | ' . $cliente->num_sigma_arma14 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf15 >= date('Y-m-d') && $cliente->validade_gt15 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie15 . '|' . $cliente->modelo15 . '|' . $cliente->calibre15 . '|' . $cliente->num_sigma_arma15 ?>">- <?php echo $cliente->especie15 . ' | ' . $cliente->modelo15 . ' | ' . $cliente->calibre15 . ' | ' . $cliente->num_sigma_arma15 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf16 >= date('Y-m-d') && $cliente->validade_gt16 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie16 . '|' . $cliente->modelo16 . '|' . $cliente->calibre16 . '|' . $cliente->num_sigma_arma16 ?>">- <?php echo $cliente->especie16 . ' | ' . $cliente->modelo16 . ' | ' . $cliente->calibre16 . ' | ' . $cliente->num_sigma_arma16 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf17 >= date('Y-m-d') && $cliente->validade_gt17 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie17 . '|' . $cliente->modelo17 . '|' . $cliente->calibre17 . '|' . $cliente->num_sigma_arma17 ?>">- <?php echo $cliente->especie17 . ' | ' . $cliente->modelo17 . ' | ' . $cliente->calibre17 . ' | ' . $cliente->num_sigma_arma17 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf18 >= date('Y-m-d') && $cliente->validade_gt18 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie18 . '|' . $cliente->modelo18 . '|' . $cliente->calibre18 . '|' . $cliente->num_sigma_arma18 ?>">- <?php echo $cliente->especie18 . ' | ' . $cliente->modelo18 . ' | ' . $cliente->calibre18 . ' | ' . $cliente->num_sigma_arma18 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf19 >= date('Y-m-d') && $cliente->validade_gt19 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie19 . '|' . $cliente->modelo19 . '|' . $cliente->calibre19 . '|' . $cliente->num_sigma_arma19 ?>">- <?php echo $cliente->especie19 . ' | ' . $cliente->modelo19 . ' | ' . $cliente->calibre19 . ' | ' . $cliente->num_sigma_arma19 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf20 >= date('Y-m-d') && $cliente->validade_gt20 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie20 . '|' . $cliente->modelo20 . '|' . $cliente->calibre20 . '|' . $cliente->num_sigma_arma20 ?>">- <?php echo $cliente->especie20 . ' | ' . $cliente->modelo20 . ' | ' . $cliente->calibre20 . ' | ' . $cliente->num_sigma_arma20 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf21 >= date('Y-m-d') && $cliente->validade_gt21 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie21 . '|' . $cliente->modelo21 . '|' . $cliente->calibre21 . '|' . $cliente->num_sigma_arma21 ?>">- <?php echo $cliente->especie21 . ' | ' . $cliente->modelo21 . ' | ' . $cliente->calibre21 . ' | ' . $cliente->num_sigma_arma21 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf22 >= date('Y-m-d') && $cliente->validade_gt22 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie22 . '|' . $cliente->modelo22 . '|' . $cliente->calibre22 . '|' . $cliente->num_sigma_arma22 ?>">- <?php echo $cliente->especie22 . ' | ' . $cliente->modelo22 . ' | ' . $cliente->calibre22 . ' | ' . $cliente->num_sigma_arma22 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf23 >= date('Y-m-d') && $cliente->validade_gt23 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie23 . '|' . $cliente->modelo23 . '|' . $cliente->calibre23 . '|' . $cliente->num_sigma_arma23 ?>">- <?php echo $cliente->especie23 . ' | ' . $cliente->modelo23 . ' | ' . $cliente->calibre23 . ' | ' . $cliente->num_sigma_arma23 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf24 >= date('Y-m-d') && $cliente->validade_gt24 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie24 . '|' . $cliente->modelo24 . '|' . $cliente->calibre24 . '|' . $cliente->num_sigma_arma24 ?>">- <?php echo $cliente->especie24 . ' | ' . $cliente->modelo24 . ' | ' . $cliente->calibre24 . ' | ' . $cliente->num_sigma_arma24 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf25 >= date('Y-m-d') && $cliente->validade_gt25 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie25 . '|' . $cliente->modelo25 . '|' . $cliente->calibre25 . '|' . $cliente->num_sigma_arma25 ?>">- <?php echo $cliente->especie25 . ' | ' . $cliente->modelo25 . ' | ' . $cliente->calibre25 . ' | ' . $cliente->num_sigma_arma25 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf26 >= date('Y-m-d') && $cliente->validade_gt26 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie26 . '|' . $cliente->modelo26 . '|' . $cliente->calibre26 . '|' . $cliente->num_sigma_arma26 ?>">- <?php echo $cliente->especie26 . ' | ' . $cliente->modelo26 . ' | ' . $cliente->calibre26 . ' | ' . $cliente->num_sigma_arma26 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf27 >= date('Y-m-d') && $cliente->validade_gt27 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie27 . '|' . $cliente->modelo27 . '|' . $cliente->calibre27 . '|' . $cliente->num_sigma_arma27 ?>">- <?php echo $cliente->especie27 . ' | ' . $cliente->modelo27 . ' | ' . $cliente->calibre27 . ' | ' . $cliente->num_sigma_arma27 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf28 >= date('Y-m-d') && $cliente->validade_gt28 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie28 . '|' . $cliente->modelo28 . '|' . $cliente->calibre28 . '|' . $cliente->num_sigma_arma28 ?>">- <?php echo $cliente->especie28 . ' | ' . $cliente->modelo28 . ' | ' . $cliente->calibre28 . ' | ' . $cliente->num_sigma_arma28 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf29 >= date('Y-m-d') && $cliente->validade_gt29 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie29 . '|' . $cliente->modelo29 . '|' . $cliente->calibre29 . '|' . $cliente->num_sigma_arma29 ?>">- <?php echo $cliente->especie29 . ' | ' . $cliente->modelo29 . ' | ' . $cliente->calibre29 . ' | ' . $cliente->num_sigma_arma29 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';
                    armas += '<?php if ($cliente->validade_craf30 >= date('Y-m-d') && $cliente->validade_gt30 >= date('Y-m-d') || 1 == 1) { ?><option value="<?php echo $cliente->especie30 . '|' . $cliente->modelo30 . '|' . $cliente->calibre30 . '|' . $cliente->num_sigma_arma30 ?>">- <?php echo $cliente->especie30 . ' | ' . $cliente->modelo30 . ' | ' . $cliente->calibre30 . ' | ' . $cliente->num_sigma_arma30 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação  vencida*</option><?php } ?>';

                    armas += '</optgroup>';
                    armas += '</select>';
                  }

                  var dados_municao = '<td>' +
                    '<div class="custom-control custom-radio">' +
                    ' <input class="custom-control-input" type="radio" id="origem_municao_C_' + linha + '" name="origem_municao_' + linha + '[]" value="C"> ' +
                    ' <label class="custom-control-label" for="origem_municao_C_' + linha + '"> Clube</label>' +
                    '</div>' +
                    '<div class="custom-control custom-radio">' +
                    ' <input class="custom-control-input" type="radio" id="origem_municao_A_' + linha + '" name="origem_municao_' + linha + '[]" value="A"> ' +
                    ' <label class="custom-control-label" for="origem_municao_A_' + linha + '"> Atirador</label>' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<div class="custom-control custom-radio">' +
                    ' <input class="custom-control-input" type="radio" id="tipo_municao_O_' + linha + '" name="tipo_municao_' + linha + '[]" value="O"> ' +
                    ' <label class="custom-control-label" for="tipo_municao_O_' + linha + '"> Original</label>' +
                    '</div>' +
                    '<div class="custom-control custom-radio">' +
                    ' <input class="custom-control-input" type="radio" id="tipo_municao_R_' + linha + '" name="tipo_municao_' + linha + '[]" value="R"> ' +
                    ' <label class="custom-control-label" for="tipo_municao_R_' + linha + '"> Recarregada</label>' +
                    '</div>' +
                    '</td>';

                  
                  var html_novo = '<tr data-linha><td><input type="hidden" name="id_habitualidade[]" value=""><input type="hidden" name="aprovado[]" value=""><input type="hidden" name="data_aprovacao[]" value=""><input type="hidden" name="id_linha[]" value="' + linha + '"><input type="text" class="form-control mask-date" name="habitu_data[]" value="<?= date('d/m/Y H:i'); ?>" maxlength="50"></td><td><select class="form-control" name="habitu_local[]" size="1"><option value="">-</option><?php foreach ($habitu_op as $op) : ?><option value="<?= $op->local; ?>"><?= $op->local; ?></option><?php endforeach; ?></select></td><td><select class="form-control" name="habitu_evento[]" size="1"><option value="">-</option><?php foreach ($habitu_ev as $ev) : ?><option value="<?= $ev->evento; ?>"><?= $ev->evento; ?></option><?php endforeach; ?></select></td><td colspan="4">' + armas + '</td><td><input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value=""></td><td><input type="text" class="form-control" name="habitu_livro[]" size="10" value=""></td></td>' + dados_municao + '<td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-primary btn-sm">X</a></td></tr>';
                  $("#lista_habitualidade tbody").prepend(html_novo);

                }

                function drop(botao) {
                  var r = confirm("Deseja realmente excluir esta linha?");
                  if (r) $(botao).parent("td").parent("tr").remove();
                }
              </script>
              <input type="hidden" name="acao" value="editar">
              <input type="hidden" name="id" value="<?= $cliente->id ?>">
              <button class="btn btn-success btn-sm" type="submit" id='botao' style="float: right; margin-left: 5px;">SALVAR</button>
              <button class="btn btn-info btn-sm" type="button" onclick="addHabitualidade(1)" style="float: right; margin-left: 5px;">[+] ACERVO</button>
              <button class="btn btn-primary btn-sm" type="button" onclick="addHabitualidade(2)" style="float: right;">[+] TERCEIROS</button>

              <div class="btn-group btn-group-sm">
                <a class="btn btn-info btn-sm" href="armas.php?id=<?= $cliente->id ?>" title="Armas" alt="Armas">
                  <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </a>
              </div>


              <script>
        function updateLinks() {
           
            var datainicial = document.getElementById('datainicial').value;
            var datafinal = document.getElementById('datafinal').value;

         
            var links = document.querySelectorAll('.dropdown-item');


            var linkTodosCalibres = document.getElementById('todoscalibres');

            links.forEach(function(link) {
                var href = link.getAttribute('href');
                var newHref = href + "&datainicial=" + encodeURIComponent(datainicial) + "&datafinal=" + encodeURIComponent(datafinal);
                link.setAttribute('href', newHref);
            });

            if (linkTodosCalibres) {
                var href2 = linkTodosCalibres.getAttribute('href');
                var newHref2 = href2 + "&datainicial=" + encodeURIComponent(datainicial) + "&datafinal=" + encodeURIComponent(datafinal);
                linkTodosCalibres.setAttribute('href', newHref2);
            }
        }
    </script>

              <div class="btn-group btn-group-sm">
                <button class="btn btn-info btn-sm ml-2 mb-3" onclick="return aprovarHabitualidade()" type="button" title="Aprovar Habitualidade" alt="Aprovar Habitualidade">
                  <i class="fa fa-check" aria-hidden="true">&nbsp;APROVAR</i>
                </button>
              </div>

              <div class="btn-group btn-group-sm">
                <a class="btn btn-info btn-sm" href="biometria.php?id=<?= $cliente->id ?>" title="Biometria" alt="Biometria">
                  <i class="fa fa-ils" aria-hidden="true"></i>
                </a>
              </div>

              <div class="btn-group btn-group-sm">
                <a class="btn btn-primary btn-sm mr-2 mb-3" id="todoscalibres" href="relatorios/habitualidadeTodosCalibres.php?id=<?= $cliente->id ?>" target="_blank">TODOS CALIBRES</a>
              </div>

              <?php

              //Objeto de array que terá o calibre e a matricula
              $calibres = array();

              foreach ($habitualidade as $object) {
                $calibre = $object->calibre;
                $matricula = $object->matricula;

                // Check if the calibre already exists in the $calibres array
                $exists = false;
                foreach ($calibres as $cal) {
                  if ($cal->calibre == $calibre) {
                    $exists = true;
                    break;
                  }
                }

                // If the calibre doesn't exist, add it to the $calibres array
                if (!$exists) {
                  $calibres[] = (object) array('calibre' => $calibre, 'matricula' => $matricula);
                }
              }

              ?>
 
              <?php foreach ($calibres as $cal) : ?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm mr-2 mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $cal->calibre ?></button>
                <div class="dropdown-menu gd-dropdown-menu">
                    <a class="dropdown-item" href="relatorios/habitualidade2.php?id=<?= $cliente->id ?>&calibre=<?= urlencode($cal->calibre) ?>&calibre_uso=Permitido" target="_blank">Permitido</a>
                    <a class="dropdown-item" href="relatorios/habitualidade2.php?id=<?= $cliente->id ?>&calibre=<?= urlencode($cal->calibre) ?>&calibre_uso=Restrito" target="_blank">Restrito</a>
                </div>
            </div>
        <?php endforeach; ?>

              <div class="clearfix"></div>
              <div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-hover" id="lista_habitualidade" style="min-width: 820px;">
                        <thead>
                          <th><b>Data/Hora</b></th>
                          <th><b>Local</b></th>
                          <th><b>Evento</b></th>
                          <th colspan="4"><b>Tipo | Modelo | Calibre | Sigma</b></th>
                          <th><b>Qtde</b></th>
                          <th><b>Livro</b></th>                          
                          <th><b>Origem Munição</b></th>
                          <th><b>Tipo Munição</b></th>
                          <th></th>
                        </thead>
                        <?php if ($habitualidade) : ?>
                          <tbody>
                            <?php foreach ($habitualidade as $idh => $value) : ?>

                              <tr data-linha style="<?= ((int)$habitualidade[$idh]->aprovado == 0 && (int)$clube->tipo_habitualidade > 1) ? 'background: #fff6a6;' : ''; ?>">
                                <td>

                                  <input type="hidden" name="id_habitualidade[]" value="<?= $habitualidade[$idh]->id; ?>">
                                  <input type="hidden" name="aprovado[]" value="<?= $habitualidade[$idh]->aprovado; ?>">
                                  <input type="hidden" name="data_aprovacao[]" value="<?= ($habitualidade[$idh]->aprovado == 1) ? $habitualidade[$idh]->data_aprovacao : ''; ?>">

                                  <input type="hidden" name="id_linha[]" value="<?= $idh; ?>"><input type="text" class="form-control mask-date" name="habitu_data[]" maxlength="50" value="<?= date('d/m/Y H:i', strtotime($habitualidade[$idh]->datacadastro)) ?>">
                                </td>
                                <td><input type="text" class="form-control" name="habitu_local[]" size="8" value="<?= $habitualidade[$idh]->local ?>"></td>
                                <td><input type="text" class="form-control" name="habitu_evento[]" value="<?= $habitualidade[$idh]->evento ?>"></td>
                                <td colspan="4">
                                  <?php $tipo = $habitualidade[$idh]->tipo . '|' . $habitualidade[$idh]->modelo . '|' . $habitualidade[$idh]->calibre . '|' . $habitualidade[$idh]->sigma; ?>

                                  <?php if ($habitualidade[$idh]->digitado == 0) : ?>

                                    <select name="tipo_<?= $idh; ?>[]" class="form-control">
                                      <?php if ($armas) : ?>
                                        <optgroup label="== Acervo Clube-Instrutor ==">
                                          <?php foreach ($armas as $key => $Armas) : ?>
                                            <option value="<?= $Armas->tipo . '|' . $Armas->modelo . '|' . $Armas->calibre . '|' . $Armas->numsigma; ?>" <?= (trim($tipo) == trim($Armas->tipo . '|' . $Armas->modelo . '|' . $Armas->calibre . '|' . $Armas->numsigma)) ? ' selected ' : ''; ?>><?= $Armas->tipo . ' | ' . $Armas->modelo . ' | ' . $Armas->calibre . ' | ' . $Armas->numsigma; ?></option>
                                          <?php endforeach; ?>
                                        </optgroup>
                                      <?php endif; ?>
                                      <optgroup label="== Acervo Pessoal ==">
                                        <option value="">- selecione -</option>
                                        <?php if ($cliente->validade_craf1 >= date('Y-m-d') && $cliente->validade_gt1 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie1 . '|' . $cliente->modelo1 . '|' . $cliente->calibre1 . '|' . $cliente->num_sigma_arma1)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie1 . '|' . $cliente->modelo1 . '|' . $cliente->calibre1 . '|' . $cliente->num_sigma_arma1 ?>">- <?php echo $cliente->especie1 . ' | ' . $cliente->modelo1 . ' | ' . $cliente->calibre1 . ' | ' . $cliente->num_sigma_arma1 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf2 >= date('Y-m-d') && $cliente->validade_gt2 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie2 . '|' . $cliente->modelo2 . '|' . $cliente->calibre2 . '|' . $cliente->num_sigma_arma2)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie2 . '|' . $cliente->modelo2 . '|' . $cliente->calibre2 . '|' . $cliente->num_sigma_arma2 ?>">- <?php echo $cliente->especie2 . ' | ' . $cliente->modelo2 . ' | ' . $cliente->calibre2 . ' | ' . $cliente->num_sigma_arma2 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf3 >= date('Y-m-d') && $cliente->validade_gt3 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie3 . '|' . $cliente->modelo3 . '|' . $cliente->calibre3 . '|' . $cliente->num_sigma_arma3)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie3 . '|' . $cliente->modelo3 . '|' . $cliente->calibre3 . '|' . $cliente->num_sigma_arma3 ?>">- <?php echo $cliente->especie3 . ' | ' . $cliente->modelo3 . ' | ' . $cliente->calibre3 . ' | ' . $cliente->num_sigma_arma3 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf4 >= date('Y-m-d') && $cliente->validade_gt4 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie4 . '|' . $cliente->modelo4 . '|' . $cliente->calibre4 . '|' . $cliente->num_sigma_arma4)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie4 . '|' . $cliente->modelo4 . '|' . $cliente->calibre4 . '|' . $cliente->num_sigma_arma4 ?>">- <?php echo $cliente->especie4 . ' | ' . $cliente->modelo4 . ' | ' . $cliente->calibre4 . ' | ' . $cliente->num_sigma_arma4 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf5 >= date('Y-m-d') && $cliente->validade_gt5 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie5 . '|' . $cliente->modelo5 . '|' . $cliente->calibre5 . '|' . $cliente->num_sigma_arma5)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie5 . '|' . $cliente->modelo5 . '|' . $cliente->calibre5 . '|' . $cliente->num_sigma_arma5 ?>">- <?php echo $cliente->especie5 . ' | ' . $cliente->modelo5 . ' | ' . $cliente->calibre5 . ' | ' . $cliente->num_sigma_arma5 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf6 >= date('Y-m-d') && $cliente->validade_gt6 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie6 . '|' . $cliente->modelo6 . '|' . $cliente->calibre6 . '|' . $cliente->num_sigma_arma6)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie6 . '|' . $cliente->modelo6 . '|' . $cliente->calibre6 . '|' . $cliente->num_sigma_arma6 ?>">- <?php echo $cliente->especie6 . ' | ' . $cliente->modelo6 . ' | ' . $cliente->calibre6 . ' | ' . $cliente->num_sigma_arma6 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf7 >= date('Y-m-d') && $cliente->validade_gt7 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie7 . '|' . $cliente->modelo7 . '|' . $cliente->calibre7 . '|' . $cliente->num_sigma_arma7)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie7 . '|' . $cliente->modelo7 . '|' . $cliente->calibre7 . '|' . $cliente->num_sigma_arma7 ?>">- <?php echo $cliente->especie7 . ' | ' . $cliente->modelo7 . ' | ' . $cliente->calibre7 . ' | ' . $cliente->num_sigma_arma7 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf8 >= date('Y-m-d') && $cliente->validade_gt8 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie8 . '|' . $cliente->modelo8 . '|' . $cliente->calibre8 . '|' . $cliente->num_sigma_arma8)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie8 . '|' . $cliente->modelo8 . '|' . $cliente->calibre8 . '|' . $cliente->num_sigma_arma8 ?>">- <?php echo $cliente->especie8 . ' | ' . $cliente->modelo8 . ' | ' . $cliente->calibre8 . ' | ' . $cliente->num_sigma_arma8 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf9 >= date('Y-m-d') && $cliente->validade_gt9 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie9 . '|' . $cliente->modelo9 . '|' . $cliente->calibre9 . '|' . $cliente->num_sigma_arma9)) {
                                                                                                                                                                echo ' selected ';
                                                                                                                                                              } ?> value="<?php echo $cliente->especie9 . '|' . $cliente->modelo9 . '|' . $cliente->calibre9 . '|' . $cliente->num_sigma_arma9 ?>">- <?php echo $cliente->especie9 . ' | ' . $cliente->modelo9 . ' | ' . $cliente->calibre9 . ' | ' . $cliente->num_sigma_arma9 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf10 >= date('Y-m-d') && $cliente->validade_gt10 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie10 . '|' . $cliente->modelo10 . '|' . $cliente->calibre10 . '|' . $cliente->num_sigma_arma10)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie10 . '|' . $cliente->modelo10 . '|' . $cliente->calibre10 . '|' . $cliente->num_sigma_arma10 ?>">- <?php echo $cliente->especie10 . ' | ' . $cliente->modelo10 . ' | ' . $cliente->calibre10 . ' | ' . $cliente->num_sigma_arma10 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf11 >= date('Y-m-d') && $cliente->validade_gt11 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie11 . '|' . $cliente->modelo11 . '|' . $cliente->calibre11 . '|' . $cliente->num_sigma_arma11)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie11 . '|' . $cliente->modelo11 . '|' . $cliente->calibre11 . '|' . $cliente->num_sigma_arma11 ?>">- <?php echo $cliente->especie11 . ' | ' . $cliente->modelo11 . ' | ' . $cliente->calibre11 . ' | ' . $cliente->num_sigma_arma11 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf12 >= date('Y-m-d') && $cliente->validade_gt12 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie12 . '|' . $cliente->modelo12 . '|' . $cliente->calibre12 . '|' . $cliente->num_sigma_arma12)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie12 . '|' . $cliente->modelo12 . '|' . $cliente->calibre12 . '|' . $cliente->num_sigma_arma12 ?>">- <?php echo $cliente->especie12 . ' | ' . $cliente->modelo12 . ' | ' . $cliente->calibre12 . ' | ' . $cliente->num_sigma_arma12 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf13 >= date('Y-m-d') && $cliente->validade_gt13 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie13 . '|' . $cliente->modelo13 . '|' . $cliente->calibre13 . '|' . $cliente->num_sigma_arma13)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie13 . '|' . $cliente->modelo13 . '|' . $cliente->calibre13 . '|' . $cliente->num_sigma_arma13 ?>">- <?php echo $cliente->especie13 . ' | ' . $cliente->modelo13 . ' | ' . $cliente->calibre13 . ' | ' . $cliente->num_sigma_arma13 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf14 >= date('Y-m-d') && $cliente->validade_gt14 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie14 . '|' . $cliente->modelo14 . '|' . $cliente->calibre14 . '|' . $cliente->num_sigma_arma14)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie14 . '|' . $cliente->modelo14 . '|' . $cliente->calibre14 . '|' . $cliente->num_sigma_arma14 ?>">- <?php echo $cliente->especie14 . ' | ' . $cliente->modelo14 . ' | ' . $cliente->calibre14 . ' | ' . $cliente->num_sigma_arma14 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf15 >= date('Y-m-d') && $cliente->validade_gt15 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie15 . '|' . $cliente->modelo15 . '|' . $cliente->calibre15 . '|' . $cliente->num_sigma_arma15)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie15 . '|' . $cliente->modelo15 . '|' . $cliente->calibre15 . '|' . $cliente->num_sigma_arma15 ?>">- <?php echo $cliente->especie15 . ' | ' . $cliente->modelo15 . ' | ' . $cliente->calibre15 . ' | ' . $cliente->num_sigma_arma15 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf16 >= date('Y-m-d') && $cliente->validade_gt16 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie16 . '|' . $cliente->modelo16 . '|' . $cliente->calibre16 . '|' . $cliente->num_sigma_arma16)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie16 . '|' . $cliente->modelo16 . '|' . $cliente->calibre16 . '|' . $cliente->num_sigma_arma16 ?>">- <?php echo $cliente->especie16 . ' | ' . $cliente->modelo16 . ' | ' . $cliente->calibre16 . ' | ' . $cliente->num_sigma_arma16 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf17 >= date('Y-m-d') && $cliente->validade_gt17 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie17 . '|' . $cliente->modelo17 . '|' . $cliente->calibre17 . '|' . $cliente->num_sigma_arma17)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie17 . '|' . $cliente->modelo17 . '|' . $cliente->calibre17 . '|' . $cliente->num_sigma_arma17 ?>">- <?php echo $cliente->especie17 . ' | ' . $cliente->modelo17 . ' | ' . $cliente->calibre17 . ' | ' . $cliente->num_sigma_arma17 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf18 >= date('Y-m-d') && $cliente->validade_gt18 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie18 . '|' . $cliente->modelo18 . '|' . $cliente->calibre18 . '|' . $cliente->num_sigma_arma18)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie18 . '|' . $cliente->modelo18 . '|' . $cliente->calibre18 . '|' . $cliente->num_sigma_arma18 ?>">- <?php echo $cliente->especie18 . ' | ' . $cliente->modelo18 . ' | ' . $cliente->calibre18 . ' | ' . $cliente->num_sigma_arma18 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf19 >= date('Y-m-d') && $cliente->validade_gt19 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie19 . '|' . $cliente->modelo19 . '|' . $cliente->calibre19 . '|' . $cliente->num_sigma_arma19)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie19 . '|' . $cliente->modelo19 . '|' . $cliente->calibre19 . '|' . $cliente->num_sigma_arma19 ?>">- <?php echo $cliente->especie19 . ' | ' . $cliente->modelo19 . ' | ' . $cliente->calibre19 . ' | ' . $cliente->num_sigma_arma19 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf20 >= date('Y-m-d') && $cliente->validade_gt20 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie20 . '|' . $cliente->modelo20 . '|' . $cliente->calibre20 . '|' . $cliente->num_sigma_arma20)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie20 . '|' . $cliente->modelo20 . '|' . $cliente->calibre20 . '|' . $cliente->num_sigma_arma20 ?>">- <?php echo $cliente->especie20 . ' | ' . $cliente->modelo20 . ' | ' . $cliente->calibre20 . ' | ' . $cliente->num_sigma_arma20 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                        <?php if ($cliente->validade_craf21 >= date('Y-m-d') && $cliente->validade_gt21 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie21 . '|' . $cliente->modelo21 . '|' . $cliente->calibre21 . '|' . $cliente->num_sigma_arma21)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie21 . '|' . $cliente->modelo21 . '|' . $cliente->calibre21 . '|' . $cliente->num_sigma_arma21 ?>">- <?php echo $cliente->especie21 . ' | ' . $cliente->modelo21 . ' | ' . $cliente->calibre21 . ' | ' . $cliente->num_sigma_arma21 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                         <?php if ($cliente->validade_craf22 >= date('Y-m-d') && $cliente->validade_gt22 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie22 . '|' . $cliente->modelo22 . '|' . $cliente->calibre22 . '|' . $cliente->num_sigma_arma22)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie22 . '|' . $cliente->modelo22 . '|' . $cliente->calibre22 . '|' . $cliente->num_sigma_arma22 ?>">- <?php echo $cliente->especie22 . ' | ' . $cliente->modelo22 . ' | ' . $cliente->calibre22 . ' | ' . $cliente->num_sigma_arma22 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                         <?php if ($cliente->validade_craf23 >= date('Y-m-d') && $cliente->validade_gt23 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie23 . '|' . $cliente->modelo23 . '|' . $cliente->calibre23 . '|' . $cliente->num_sigma_arma23)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie23 . '|' . $cliente->modelo23 . '|' . $cliente->calibre23 . '|' . $cliente->num_sigma_arma23 ?>">- <?php echo $cliente->especie23 . ' | ' . $cliente->modelo23 . ' | ' . $cliente->calibre23 . ' | ' . $cliente->num_sigma_arma23 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                         <?php if ($cliente->validade_craf24 >= date('Y-m-d') && $cliente->validade_gt24 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie24 . '|' . $cliente->modelo24 . '|' . $cliente->calibre24 . '|' . $cliente->num_sigma_arma24)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie24 . '|' . $cliente->modelo24 . '|' . $cliente->calibre24 . '|' . $cliente->num_sigma_arma24 ?>">- <?php echo $cliente->especie24 . ' | ' . $cliente->modelo24 . ' | ' . $cliente->calibre24 . ' | ' . $cliente->num_sigma_arma24 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                         <?php if ($cliente->validade_craf25 >= date('Y-m-d') && $cliente->validade_gt25 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie25 . '|' . $cliente->modelo25 . '|' . $cliente->calibre25 . '|' . $cliente->num_sigma_arma25)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie25 . '|' . $cliente->modelo25 . '|' . $cliente->calibre25 . '|' . $cliente->num_sigma_arma25 ?>">- <?php echo $cliente->especie25 . ' | ' . $cliente->modelo25 . ' | ' . $cliente->calibre25 . ' | ' . $cliente->num_sigma_arma25 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                          <?php if ($cliente->validade_craf26 >= date('Y-m-d') && $cliente->validade_gt26 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie26 . '|' . $cliente->modelo26 . '|' . $cliente->calibre26 . '|' . $cliente->num_sigma_arma26)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie26 . '|' . $cliente->modelo26 . '|' . $cliente->calibre26 . '|' . $cliente->num_sigma_arma26 ?>">- <?php echo $cliente->especie26 . ' | ' . $cliente->modelo26 . ' | ' . $cliente->calibre26 . ' | ' . $cliente->num_sigma_arma26 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                          <?php if ($cliente->validade_craf27 >= date('Y-m-d') && $cliente->validade_gt27 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie27 . '|' . $cliente->modelo27 . '|' . $cliente->calibre27 . '|' . $cliente->num_sigma_arma27)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie27 . '|' . $cliente->modelo27 . '|' . $cliente->calibre27 . '|' . $cliente->num_sigma_arma27 ?>">- <?php echo $cliente->especie27 . ' | ' . $cliente->modelo27 . ' | ' . $cliente->calibre27 . ' | ' . $cliente->num_sigma_arma27 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                          <?php if ($cliente->validade_craf28 >= date('Y-m-d') && $cliente->validade_gt28 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie28 . '|' . $cliente->modelo28 . '|' . $cliente->calibre28 . '|' . $cliente->num_sigma_arma28)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie28 . '|' . $cliente->modelo28 . '|' . $cliente->calibre28 . '|' . $cliente->num_sigma_arma28 ?>">- <?php echo $cliente->especie28 . ' | ' . $cliente->modelo28 . ' | ' . $cliente->calibre28 . ' | ' . $cliente->num_sigma_arma28 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                          <?php if ($cliente->validade_craf29 >= date('Y-m-d') && $cliente->validade_gt29 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie29 . '|' . $cliente->modelo29 . '|' . $cliente->calibre29 . '|' . $cliente->num_sigma_arma29)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie29 . '|' . $cliente->modelo29 . '|' . $cliente->calibre29 . '|' . $cliente->num_sigma_arma29 ?>">- <?php echo $cliente->especie29 . ' | ' . $cliente->modelo29 . ' | ' . $cliente->calibre29 . ' | ' . $cliente->num_sigma_arma29 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                          <?php if ($cliente->validade_craf30 >= date('Y-m-d') && $cliente->validade_gt30 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie30 . '|' . $cliente->modelo30 . '|' . $cliente->calibre30 . '|' . $cliente->num_sigma_arma30)) {
                                                                                                                                                                  echo ' selected ';
                                                                                                                                                                } ?> value="<?php echo $cliente->especie30 . '|' . $cliente->modelo30 . '|' . $cliente->calibre30 . '|' . $cliente->num_sigma_arma30 ?>">- <?php echo $cliente->especie30 . ' | ' . $cliente->modelo30 . ' | ' . $cliente->calibre30 . ' | ' . $cliente->num_sigma_arma30 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                      </optgroup>
                                    </select>
                                  <?php else : ?>
                                    <div class="form-group">
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->tipo; ?>" type="text" name="manual_tipo_<?= $idh; ?>[]" class="form-control" placeholder="Tipo"></div>
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->modelo; ?>" type="text" name="manual_modelo_<?= $idh; ?>[]" class="form-control" placeholder="Modelo"></div>
                                      <div class="col-sm-3" style="padding:0 !important;">
                                        <select name="manual_calibre_<?= $idh; ?>[]" class="form-control">
                                          <?php if(count($getallCalibres) > 0): ?>

                                            <?php foreach($getallCalibres as $calibre) : ?>
                                              <option value="<?= $calibre?>" <?= ($calibre == $habitualidade[$idh]->calibre) ? ' selected ' : ''; ?>><?= $calibre?></option>
                                            <?php endforeach; ?>

                                          <?php  else: ?>
                                            <option value="">Nenhum calibre encontrado</option>
                                          <?php endif; ?>

                                        </select>
                                      </div>
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->sigma; ?>" type="text" name="manual_sigma_<?= $idh; ?>[]" class="form-control" placeholder="Sigma"></div>
                                    </div>

                                  <?php endif; ?>
                                </td>
                                <td><input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value="<?= $habitualidade[$idh]->qtdemunicoes ?>"></td>
                                <td><input type="text" class="form-control" name="habitu_livro[]" size="10" value="<?= $habitualidade[$idh]->livro ?>"></td>                                
                                <td>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->municao == "C") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="origem_municao_C_<?= $idh; ?>" name="origem_municao_<?= $idh; ?>[]" value="C">
                                    <label class="custom-control-label" for="origem_municao_C_<?= $idh; ?>">Clube</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->municao == "A") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="origem_municao_A_<?= $idh; ?>" name="origem_municao_<?= $idh; ?>[]" value="A">
                                    <label class="custom-control-label" for="origem_municao_A_<?= $idh; ?>">Atirador</label>
                                  </div>
                                </td>
                                <td>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->tipomunicao == "O") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="tipo_municao_O_<?= $idh; ?>" name="tipo_municao_<?= $idh; ?>[]" value="O">
                                    <label class="custom-control-label" for="tipo_municao_O_<?= $idh; ?>">Original</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->tipomunicao == "R") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="tipo_municao_R_<?= $idh; ?>" name="tipo_municao_<?= $idh; ?>[]" value="R">
                                    <label class="custom-control-label" for="tipo_municao_R_<?= $idh; ?>">Recarregada</label>
                                  </div>
                                </td>
                                <td><a href="javascript:void(0);" onclick="dropHabitualidade('<?= $habitualidade[$idh]->id; ?>')" class="btn btn-danger btn-sm">X</a></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else : ?>
                            <tr data-linha>
                              <td><input type="hidden" name="id_habitualidade[]" value=""><input type="hidden" name="aprovado[]" value=""><input type="hidden" name="data_aprovacao[]" value=""><input type="hidden" name="id_linha[]" value="0"><input type="text" class="form-control mask-date" name="habitu_data[]" maxlength="50" value="<?= date('d/m/Y H:i'); ?>"></td>
                              <td><input type="text" class="form-control" name="habitu_local[]" size="8" value=""></td>
                              <td><input type="text" class="form-control" name="habitu_evento[]" value=""></td>
                              <td colspan="4">
                                <select name="tipo_0[]" class="form-control">
                                  <option value="">- selecione -</option>
                                  <?php if ($armas) : ?>
                                    <optgroup label="== Acervo Clube ==">
                                      <?php foreach ($armas as $key => $Armas) : ?>
                                        <option value="<?= $Armas->tipo . '|' . $Armas->modelo . '|' . $Armas->calibre . '|' . $Armas->numsigma; ?>" <?= (trim($tipo) == ($Armas->tipo . '|' . $Armas->modelo . '|' . $Armas->calibre . '|' . $Armas->numsigma)) ? ' selected ' : ''; ?>><?= $Armas->tipo . ' | ' . $Armas->modelo . ' | ' . $Armas->calibre . ' | ' . $Armas->numsigma; ?></option>
                                      <?php endforeach; ?>
                                    </optgroup>
                                  <?php endif; ?>
                                  <optgroup label="== Acervo Pessoal ==">
                                    <?php if ($cliente->validade_craf1 >= date('Y-m-d') && $cliente->validade_gt1 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie01 . '|' . $cliente->modelo01 . '|' . $cliente->calibre01 . '|' . $cliente->num_sigma_arma01)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie1 . '|' . $cliente->modelo1 . '|' . $cliente->calibre1 . '|' . $cliente->num_sigma_arma1 ?>">- <?php echo $cliente->especie1 . ' | ' . $cliente->modelo1 . ' | ' . $cliente->calibre1 . ' | ' . $cliente->num_sigma_arma1 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf2 >= date('Y-m-d') && $cliente->validade_gt2 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie02 . '|' . $cliente->modelo02 . '|' . $cliente->calibre02 . '|' . $cliente->num_sigma_arma02)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie2 . '|' . $cliente->modelo2 . '|' . $cliente->calibre2 . '|' . $cliente->num_sigma_arma2 ?>">- <?php echo $cliente->especie2 . ' | ' . $cliente->modelo2 . ' | ' . $cliente->calibre2 . ' | ' . $cliente->num_sigma_arma2 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf3 >= date('Y-m-d') && $cliente->validade_gt3 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie03 . '|' . $cliente->modelo03 . '|' . $cliente->calibre03 . '|' . $cliente->num_sigma_arma03)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie3 . '|' . $cliente->modelo3 . '|' . $cliente->calibre3 . '|' . $cliente->num_sigma_arma3 ?>">- <?php echo $cliente->especie3 . ' | ' . $cliente->modelo3 . ' | ' . $cliente->calibre3 . ' | ' . $cliente->num_sigma_arma3 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf4 >= date('Y-m-d') && $cliente->validade_gt4 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie04 . '|' . $cliente->modelo04 . '|' . $cliente->calibre04 . '|' . $cliente->num_sigma_arma04)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie4 . '|' . $cliente->modelo4 . '|' . $cliente->calibre4 . '|' . $cliente->num_sigma_arma4 ?>">- <?php echo $cliente->especie4 . ' | ' . $cliente->modelo4 . ' | ' . $cliente->calibre4 . ' | ' . $cliente->num_sigma_arma4 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf5 >= date('Y-m-d') && $cliente->validade_gt5 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie05 . '|' . $cliente->modelo05 . '|' . $cliente->calibre05 . '|' . $cliente->num_sigma_arma05)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie5 . '|' . $cliente->modelo5 . '|' . $cliente->calibre5 . '|' . $cliente->num_sigma_arma5 ?>">- <?php echo $cliente->especie5 . ' | ' . $cliente->modelo5 . ' | ' . $cliente->calibre5 . ' | ' . $cliente->num_sigma_arma5 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf6 >= date('Y-m-d') && $cliente->validade_gt6 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie06 . '|' . $cliente->modelo06 . '|' . $cliente->calibre06 . '|' . $cliente->num_sigma_arma06)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie6 . '|' . $cliente->modelo6 . '|' . $cliente->calibre6 . '|' . $cliente->num_sigma_arma6 ?>">- <?php echo $cliente->especie6 . ' | ' . $cliente->modelo6 . ' | ' . $cliente->calibre6 . ' | ' . $cliente->num_sigma_arma6 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf7 >= date('Y-m-d') && $cliente->validade_gt7 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie07 . '|' . $cliente->modelo07 . '|' . $cliente->calibre07 . '|' . $cliente->num_sigma_arma07)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie7 . '|' . $cliente->modelo7 . '|' . $cliente->calibre7 . '|' . $cliente->num_sigma_arma7 ?>">- <?php echo $cliente->especie7 . ' | ' . $cliente->modelo7 . ' | ' . $cliente->calibre7 . ' | ' . $cliente->num_sigma_arma7 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf8 >= date('Y-m-d') && $cliente->validade_gt8 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie08 . '|' . $cliente->modelo08 . '|' . $cliente->calibre08 . '|' . $cliente->num_sigma_arma08)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie8 . '|' . $cliente->modelo8 . '|' . $cliente->calibre8 . '|' . $cliente->num_sigma_arma8 ?>">- <?php echo $cliente->especie8 . ' | ' . $cliente->modelo8 . ' | ' . $cliente->calibre8 . ' | ' . $cliente->num_sigma_arma8 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf9 >= date('Y-m-d') && $cliente->validade_gt9 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie09 . '|' . $cliente->modelo09 . '|' . $cliente->calibre09 . '|' . $cliente->num_sigma_arma09)) {
                                                                                                                                                            echo ' selected ';
                                                                                                                                                          } ?> value="<?php echo $cliente->especie9 . '|' . $cliente->modelo9 . '|' . $cliente->calibre9 . '|' . $cliente->num_sigma_arma9 ?>">- <?php echo $cliente->especie9 . ' | ' . $cliente->modelo9 . ' | ' . $cliente->calibre9 . ' | ' . $cliente->num_sigma_arma9 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf10 >= date('Y-m-d') && $cliente->validade_gt10 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie10 . '|' . $cliente->modelo10 . '|' . $cliente->calibre10 . '|' . $cliente->num_sigma_arma10)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie10 . '|' . $cliente->modelo10 . '|' . $cliente->calibre10 . '|' . $cliente->num_sigma_arma10 ?>">- <?php echo $cliente->especie10 . ' | ' . $cliente->modelo10 . ' | ' . $cliente->calibre10 . ' | ' . $cliente->num_sigma_arma10 ?></option><?php } else { ?><option disabled value="">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf11 >= date('Y-m-d') && $cliente->validade_gt11 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie11 . '|' . $cliente->modelo11 . '|' . $cliente->calibre11 . '|' . $cliente->num_sigma_arma11)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie11 . '|' . $cliente->modelo11 . '|' . $cliente->calibre11 . '|' . $cliente->num_sigma_arma11 ?>">- <?php echo $cliente->especie11 . ' | ' . $cliente->modelo11 . ' | ' . $cliente->calibre11 . ' | ' . $cliente->num_sigma_arma11 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf12 >= date('Y-m-d') && $cliente->validade_gt12 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie12 . '|' . $cliente->modelo12 . '|' . $cliente->calibre12 . '|' . $cliente->num_sigma_arma12)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie12 . '|' . $cliente->modelo12 . '|' . $cliente->calibre12 . '|' . $cliente->num_sigma_arma12 ?>">- <?php echo $cliente->especie12 . ' | ' . $cliente->modelo12 . ' | ' . $cliente->calibre12 . ' | ' . $cliente->num_sigma_arma12 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf13 >= date('Y-m-d') && $cliente->validade_gt13 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie13 . '|' . $cliente->modelo13 . '|' . $cliente->calibre13 . '|' . $cliente->num_sigma_arma13)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie13 . '|' . $cliente->modelo13 . '|' . $cliente->calibre13 . '|' . $cliente->num_sigma_arma13 ?>">- <?php echo $cliente->especie13 . ' | ' . $cliente->modelo13 . ' | ' . $cliente->calibre13 . ' | ' . $cliente->num_sigma_arma13 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf14 >= date('Y-m-d') && $cliente->validade_gt14 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie14 . '|' . $cliente->modelo14 . '|' . $cliente->calibre14 . '|' . $cliente->num_sigma_arma14)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie14 . '|' . $cliente->modelo14 . '|' . $cliente->calibre14 . '|' . $cliente->num_sigma_arma14 ?>">- <?php echo $cliente->especie14 . ' | ' . $cliente->modelo14 . ' | ' . $cliente->calibre14 . ' | ' . $cliente->num_sigma_arma14 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf15 >= date('Y-m-d') && $cliente->validade_gt15 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie15 . '|' . $cliente->modelo15 . '|' . $cliente->calibre15 . '|' . $cliente->num_sigma_arma15)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie15 . '|' . $cliente->modelo15 . '|' . $cliente->calibre15 . '|' . $cliente->num_sigma_arma15 ?>">- <?php echo $cliente->especie15 . ' | ' . $cliente->modelo15 . ' | ' . $cliente->calibre15 . ' | ' . $cliente->num_sigma_arma15 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf16 >= date('Y-m-d') && $cliente->validade_gt16 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie16 . '|' . $cliente->modelo16 . '|' . $cliente->calibre16 . '|' . $cliente->num_sigma_arma16)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie16 . '|' . $cliente->modelo16 . '|' . $cliente->calibre16 . '|' . $cliente->num_sigma_arma16 ?>">- <?php echo $cliente->especie16 . ' | ' . $cliente->modelo16 . ' | ' . $cliente->calibre16 . ' | ' . $cliente->num_sigma_arma16 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf17 >= date('Y-m-d') && $cliente->validade_gt17 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie17 . '|' . $cliente->modelo17 . '|' . $cliente->calibre17 . '|' . $cliente->num_sigma_arma17)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie17 . '|' . $cliente->modelo17 . '|' . $cliente->calibre17 . '|' . $cliente->num_sigma_arma17 ?>">- <?php echo $cliente->especie17 . ' | ' . $cliente->modelo17 . ' | ' . $cliente->calibre17 . ' | ' . $cliente->num_sigma_arma17 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf18 >= date('Y-m-d') && $cliente->validade_gt18 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie18 . '|' . $cliente->modelo18 . '|' . $cliente->calibre18 . '|' . $cliente->num_sigma_arma18)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie18 . '|' . $cliente->modelo18 . '|' . $cliente->calibre18 . '|' . $cliente->num_sigma_arma18 ?>">- <?php echo $cliente->especie18 . ' | ' . $cliente->modelo18 . ' | ' . $cliente->calibre18 . ' | ' . $cliente->num_sigma_arma18 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf19 >= date('Y-m-d') && $cliente->validade_gt19 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie19 . '|' . $cliente->modelo19 . '|' . $cliente->calibre19 . '|' . $cliente->num_sigma_arma19)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie19 . '|' . $cliente->modelo19 . '|' . $cliente->calibre19 . '|' . $cliente->num_sigma_arma19 ?>">- <?php echo $cliente->especie19 . ' | ' . $cliente->modelo19 . ' | ' . $cliente->calibre19 . ' | ' . $cliente->num_sigma_arma19 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf20 >= date('Y-m-d') && $cliente->validade_gt20 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie20 . '|' . $cliente->modelo20 . '|' . $cliente->calibre20 . '|' . $cliente->num_sigma_arma20)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie20 . '|' . $cliente->modelo20 . '|' . $cliente->calibre20 . '|' . $cliente->num_sigma_arma20 ?>">- <?php echo $cliente->especie20 . ' | ' . $cliente->modelo20 . ' | ' . $cliente->calibre20 . ' | ' . $cliente->num_sigma_arma20 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf21 >= date('Y-m-d') && $cliente->validade_gt21 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie21 . '|' . $cliente->modelo21 . '|' . $cliente->calibre21 . '|' . $cliente->num_sigma_arma21)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie21 . '|' . $cliente->modelo21 . '|' . $cliente->calibre21 . '|' . $cliente->num_sigma_arma21 ?>">- <?php echo $cliente->especie21 . ' | ' . $cliente->modelo21 . ' | ' . $cliente->calibre21 . ' | ' . $cliente->num_sigma_arma21 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf22 >= date('Y-m-d') && $cliente->validade_gt22 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie22 . '|' . $cliente->modelo22 . '|' . $cliente->calibre22 . '|' . $cliente->num_sigma_arma22)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie22 . '|' . $cliente->modelo22 . '|' . $cliente->calibre22. '|' . $cliente->num_sigma_arma22 ?>">- <?php echo $cliente->especie22 . ' | ' . $cliente->modelo22 . ' | ' . $cliente->calibre22 . ' | ' . $cliente->num_sigma_arma22 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf23 >= date('Y-m-d') && $cliente->validade_gt23 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie23 . '|' . $cliente->modelo23 . '|' . $cliente->calibre23 . '|' . $cliente->num_sigma_arma23)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie23 . '|' . $cliente->modelo23 . '|' . $cliente->calibre23. '|' . $cliente->num_sigma_arma23 ?>">- <?php echo $cliente->especie23 . ' | ' . $cliente->modelo23 . ' | ' . $cliente->calibre23 . ' | ' . $cliente->num_sigma_arma23 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf24 >= date('Y-m-d') && $cliente->validade_gt24 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie24 . '|' . $cliente->modelo24 . '|' . $cliente->calibre24 . '|' . $cliente->num_sigma_arma24)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie24 . '|' . $cliente->modelo24 . '|' . $cliente->calibre24. '|' . $cliente->num_sigma_arma24 ?>">- <?php echo $cliente->especie24 . ' | ' . $cliente->modelo24 . ' | ' . $cliente->calibre24 . ' | ' . $cliente->num_sigma_arma24 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf25 >= date('Y-m-d') && $cliente->validade_gt25 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie25 . '|' . $cliente->modelo25 . '|' . $cliente->calibre25 . '|' . $cliente->num_sigma_arma25)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie25 . '|' . $cliente->modelo25 . '|' . $cliente->calibre25. '|' . $cliente->num_sigma_arma25 ?>">- <?php echo $cliente->especie25 . ' | ' . $cliente->modelo25 . ' | ' . $cliente->calibre25 . ' | ' . $cliente->num_sigma_arma25 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf26 >= date('Y-m-d') && $cliente->validade_gt26 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie26 . '|' . $cliente->modelo26 . '|' . $cliente->calibre26 . '|' . $cliente->num_sigma_arma26)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie26 . '|' . $cliente->modelo26 . '|' . $cliente->calibre26. '|' . $cliente->num_sigma_arma26 ?>">- <?php echo $cliente->especie26 . ' | ' . $cliente->modelo26 . ' | ' . $cliente->calibre26 . ' | ' . $cliente->num_sigma_arma26 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf27 >= date('Y-m-d') && $cliente->validade_gt27 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie27 . '|' . $cliente->modelo27 . '|' . $cliente->calibre27 . '|' . $cliente->num_sigma_arma27)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie27 . '|' . $cliente->modelo27 . '|' . $cliente->calibre27. '|' . $cliente->num_sigma_arma27 ?>">- <?php echo $cliente->especie27 . ' | ' . $cliente->modelo27 . ' | ' . $cliente->calibre27 . ' | ' . $cliente->num_sigma_arma27 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf28 >= date('Y-m-d') && $cliente->validade_gt28 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie28 . '|' . $cliente->modelo28 . '|' . $cliente->calibre28 . '|' . $cliente->num_sigma_arma28)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie28 . '|' . $cliente->modelo28 . '|' . $cliente->calibre28. '|' . $cliente->num_sigma_arma28 ?>">- <?php echo $cliente->especie28 . ' | ' . $cliente->modelo28 . ' | ' . $cliente->calibre28 . ' | ' . $cliente->num_sigma_arma28 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf29 >= date('Y-m-d') && $cliente->validade_gt29 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie29 . '|' . $cliente->modelo29 . '|' . $cliente->calibre29 . '|' . $cliente->num_sigma_arma29)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie29 . '|' . $cliente->modelo29 . '|' . $cliente->calibre29. '|' . $cliente->num_sigma_arma29 ?>">- <?php echo $cliente->especie29 . ' | ' . $cliente->modelo29 . ' | ' . $cliente->calibre29 . ' | ' . $cliente->num_sigma_arma29 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                    <?php if ($cliente->validade_craf30 >= date('Y-m-d') && $cliente->validade_gt30 >= date('Y-m-d') || 1 == 1) { ?><option <?php if (trim($tipo) == trim($cliente->especie30 . '|' . $cliente->modelo30 . '|' . $cliente->calibre30 . '|' . $cliente->num_sigma_arma30)) {
                                                                                                                                                              echo ' selected ';
                                                                                                                                                            } ?> value="<?php echo $cliente->especie30 . '|' . $cliente->modelo30 . '|' . $cliente->calibre30. '|' . $cliente->num_sigma_arma30 ?>">- <?php echo $cliente->especie30 . ' | ' . $cliente->modelo30 . ' | ' . $cliente->calibre30 . ' | ' . $cliente->num_sigma_arma30 ?></option><?php } else { ?><option disabled value="" style="color: red;">*Documentação vencida*</option><?php } ?>
                                  </optgroup>
                                </select>
                              </td>
                              <td><input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value=""></td>
                              <td><input type="text" class="form-control" name="habitu_livro[]" size="10" value=""></td>                              
                              <td>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="origem_municao_C_0" name="origem_municao_0[]" value="C">
                                  <label class="custom-control-label" for="origem_municao_C_0">Clube</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="origem_municao_A_0" name="origem_municao_0[]" value="A">
                                  <label class="custom-control-label" for="origem_municao_A_0">Atirador</label>
                                </div>
                              </td>
                              <td>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="tipo_municao_O_0" name="tipo_municao_0[]" value="O">
                                  <label class="custom-control-label" for="tipo_municao_O_0">Original</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="tipo_municao_R_0" name="tipo_municao_0[]" value="R">
                                  <label class="custom-control-label" for="tipo_municao_R_0">Recarregada</label>
                                </div>
                              </td>
                              <td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-primary btn-sm">X</a></td>
                            </tr>
                          <?php endif; ?>
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="form-group label-floating">
                    </div>
                  </div>
                </div>
              </div>


          </form>
        <?php endif; ?>

        </div>
        </div>
        <script type="text/javascript" src="js/custom.js"></script>
        </div>
        </div>

        <!------------------------------------------------------------------------------------------------------------ page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">
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

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.mask-date').mask('00/00/0000 00:00');
    });

    const aprovarHabitualidade = () => {
      var r = confirm('Confirma aprovação das habitualidades pendentes desse atirador?');
      if (r) {
        document.getElementById("aprova_h_pendente").submit();
      }
      return false;
    }
    const dropHabitualidade = (id_habitualidade) => {
        var r = confirm('Deseja realmente excluir este registro?');
      if (r) {
        document.getElementById("id_habitualidade_del").value=id_habitualidade;
        document.getElementById("excluir_habitualidade").submit();
      }
      return false; 
    }
  </script>
  <form action="action_habitualidade.php" method="post" name="aprova_h_pendente" id="aprova_h_pendente">
    <input type="hidden" name="act" value="aprova_habitualidade">
    <input type="hidden" name="matricula" value="<?= $cliente->matricula; ?>">
    <input type="hidden" name="id" value="<?= $cliente->id ?>">
  </form>
  <form action="action_habitualidade.php" method="post" name="excluir_habitualidade" id="excluir_habitualidade">
    <input type="hidden" name="act" value="excluir_habitualidade">
    <input type="hidden" name="matricula" value="<?= $cliente->matricula; ?>">
    <input type="hidden" name="id_habitualidade_del" id="id_habitualidade_del" value="">
    <input type="hidden" name="id" value="<?= $cliente->id ?>">
  </form>
  <style>
    label.custom-control-label {
      font-size: 11px;
      font-family: sans-serif;
    }

    th,
    td {
      /*white-space: nowrap;*/
    }
  </style>
</body>

</html>