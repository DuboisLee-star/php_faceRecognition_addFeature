<?php
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

date_default_timezone_set('America/Sao_Paulo');

session_start();

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

//dados biometria
$action = isset($_POST['action']) ? $_POST['action'] : false;
$biometria = isset($_POST['biometria']) ? $_POST['biometria'] : false;
$id_membro = isset($_POST['id']) ? $_POST['id'] : false;
$biometria_capturada = isset($_POST['biometria']) ? $_POST['biometria'] : false;
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : false;

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
  $sql4 = 'SELECT * FROM tab_armas WHERE sequencia = "1" ORDER BY descricao ASC';
  $stm = $conexao->prepare($sql4);
  $stm->execute();
  $armas4 = $stm->fetchAll(PDO::FETCH_OBJ);

  $conexao = conexao::getInstance();
  $sql5 = 'SELECT * FROM tab_armas WHERE id_membro = :id_membro AND sequencia = 2 ORDER BY descricao ASC';
  $stm = $conexao->prepare($sql5);
  $stm->execute(['id_membro' => $id_cliente]);
  $armas5 = $stm->fetchAll(PDO::FETCH_OBJ);
  
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
  
    // Consulta os grupos distintos na tabela tab_armas
    $sql_grupos = 'SELECT * FROM tab_grupos_armas';
    $stm_grupos = $conexao->prepare($sql_grupos);
    $stm_grupos->execute();
    $grupos = $stm_grupos->fetchAll(PDO::FETCH_OBJ);

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
 td.acoesbt {
    display: flex;

    flex-direction: row;
    padding: 22px;
}
td.acoesbt .btn {
    margin: 5px;
    margin-top: 10px;
}
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
            <input type="hidden" class="form-control" id="user" name="user" value="<?= $_SESSION['uname'] ?>" >
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
                      
                      '<div class="col-sm-3" style="padding:0 !important;">' + select_calibre + '</div>' +
                      '<div class="col-sm-3" style="padding:0 !important;"><input type="text" name="manual_sigma_' + linha + '[]" class="form-control" placeholder="Sigma"></div></div>';
                      '<div class="col-sm-3" style="padding:0 !important;"><input type="text" name="manual_id_' + linha + '[]" class="form-control" placeholder="Id"></div></div>';
                  } else {
                      
                 var armas = '<select name="tipo_' + linha + '[]" class="form-control">';
                    armas += '<option value="">- selecione -</option>';
                    
                    // Select Acervo Clube
                   <?php if (!empty($armas4)) : ?>
                      armas += '<optgroup label="== Acervo Clube ==">';
                      <?php foreach ($armas4 as $Armas) : ?>
                        armas += '<option value="<?= $Armas->tipo . '|' . $Armas->calibre . '|' . $Armas->numsigma.'|'.$Armas->id; ?>"><?= $Armas->tipo . ' |' . $Armas->calibre . ' |' . $Armas->numsigma;?></option>';
                      <?php endforeach; ?>
                      armas += '</optgroup>';
                    <?php endif; ?>
                    
                    // Select Acervo Pessoal
                    <?php if (!empty($armas5)) : ?>
                    
                    armas += '<optgroup label="== Acervo Pessoals ==">';

                   <?php foreach ($armas5 as $Arma_Pessoal) : ?>
                    
                       <?php if ($Arma_Pessoal->validade_gt >= date('Y-m-d')) : ?>
                       
                   armas += '<option value="<?= $Arma_Pessoal->tipo . '|' . $Arma_Pessoal->calibre . '|' . $Arma_Pessoal->numsigma.'|'.$Arma_Pessoal->id;  ?>"><?= $Arma_Pessoal->tipo . ' | ' . $Arma_Pessoal->calibre . ' |' . $Arma_Pessoal->numsigma;?></option>';
                
                    <?php else : ?>
                    armas += ' <option disabled value="">[Arma com GT vencida!]</option>';
                   <?php endif?>
                   
                    <?php endforeach; ?>
                    armas += '</optgroup>';
                    <?php endif; ?>
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
                    ' <label class="custom-control-label" for="tipo_municao_R_' + linha + '"> Recarga</label>' +
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
              <input type="hidden" name="acao" value="inserir">
              <input type="hidden" name="id" value="<?= $cliente->id ?>">
              <!--<?php if($clube->biometria == 1){ ?>-->
              
              <!--<button class="btn btn-success btn-sm" type="button"  onclick="return registrarPresenca()" id='botao' style="float: right; margin-left: 5px;">SALVAR</button>-->
              <a class="btn btn-info btn-sm" href="javascript:void();" onclick="return registrarPresenca()" style="float: right; margin-left: 5px;">[+] NOVO LANÇAMENTO</a>
              <!--<?php }else{?>-->
              <a class="btn btn-info btn-sm" href="nova_habitualidade.php?id=<?= $cliente->id ?>"  style="float: right; margin-left: 5px;">[+] NOVO LANÇAMENTO</a>
              <!--<button class="btn btn-success btn-sm" type="submit" id='botao' style="float: right; margin-left: 5px;">SALVAR</button>-->
              <!--<?php }?>-->

              <!--<button class="btn btn-info btn-sm" type="button" onclick="addHabitualidade(1)" style="float: right; margin-left: 5px;">[+] NOVO LANÇAMENTO</button>-->
             
              
              
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

            linkTodosCalibres.forEach(function(link2) {
                var href2 = link2.getAttribute('href');
                var newHref2 = href2 + "&datainicial=" + encodeURIComponent(datainicial) + "&datafinal=" + encodeURIComponent(datafinal);
                link2.setAttribute('href', newHref2);
            });

        }
    </script>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-info btn-sm ml-2 mb-3" onclick="return aprovarHabitualidade()" type="button" title="Aprovar Habitualidade" alt="Aprovar Habitualidade">
                  <i class="fa fa-check" aria-hidden="true">&nbsp;APROVAR</i>
                </button>
              </div>



              <div class="btn-group btn-group-sm">
                <a class="dropdown-item btn btn-info btn-sm ml-2 mb-3" id="todoscalibres" href="#" target="_blank">TODOS OS GRUPOS</a>
              </div>
              <script>
                    document.getElementById('todoscalibres').addEventListener('click', function(event) {
                        event.preventDefault();
                        var datainicial = document.getElementById('datainicial').value;
                        var datafinal = document.getElementById('datafinal').value;
                        var clienteId = <?= json_encode($cliente->id) ?>;
                
                        var url = `relatorios/habitualidadeTodosCalibres.php?id=${clienteId}`;
                        
                        if (datainicial) {
                            url += `&datainicial=${datainicial}`;
                        }
                        
                        if (datafinal) {
                            url += `&datafinal=${datafinal}`;
                        }
                
                        window.open(url, '_blank');
             });
</script>
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
 
              <?php foreach ($grupos as $cal) : ?>

              

            <div class="btn-group">
               <a  class="btn btn-primary btn-sm mr-2 mb-3" id="calibre_especifico" style="color:white;" href="relatorios/habitualidade2.php?id=<?= $cliente->id ?>&calibre=<?= urlencode($cal->id) ?>" target="_blank"><?= $cal->nome ?></a>
<!--               <script>-->
<!--                    document.getElementById('calibre_especifico').addEventListener('click', function(event) {-->
<!--                        event.preventDefault();-->
<!--                        var datainicial = document.getElementById('datainicial').value;-->
<!--                        var datafinal = document.getElementById('datafinal').value;-->
<!--                        var clienteId = <?= json_encode($cliente->id) ?>;-->
                
<!--                        var url = `relatorios/habitualidade2.php?id=<?= $cliente->id ?>&calibre=<?= urlencode($cal->id) ?>`;-->
                        
<!--                        if (datainicial) {-->
<!--                            url += `&datainicial=${datainicial}`;-->
<!--                        }-->
                        
<!--                        if (datafinal) {-->
<!--                            url += `&datafinal=${datafinal}`;-->
<!--                        }-->
                
<!--                        window.open(url, '_blank');-->
<!--             });-->
<!--</script>-->
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
                          <th colspan="4"><b>Tipo | Calibre | Sigma</b></th>
                          <th><b>Qtde</b></th>
                          <th><b>Livro</b></th>                          
                          <th><b>Origem Munição</b></th>
                          <th><b>Tipo Munição</b></th>
                          <th></th>
                        </thead>
                        <?php if ($habitualidade) : ?>
                          <tbody>
                              <?= $habitualidade->tipo?>
                            <?php foreach ($habitualidade as $idh => $value) : ?>
                              <tr data-linha style="<?= ((int)$habitualidade[$idh]->aprovado == 0 && (int)$clube->tipo_habitualidade > 1) ? 'background: #fff6a6;' : ''; ?>">
                                <td>
                                  <input type="hidden" name="id_habitualidade[]" value="<?= $habitualidade[$idh]->id; ?>">
                                  <input type="hidden" name="aprovado[]" value="<?= $habitualidade[$idh]->aprovado; ?>">
                                  <input type="hidden" name="data_aprovacao[]" value="<?= ($habitualidade[$idh]->aprovado == 1) ? $habitualidade[$idh]->data_aprovacao : ''; ?>">
                                  <input type="hidden" name="id_linha[]" value="<?= $idh; ?>"><input type="text" class="form-control mask-date" name="habitu_data[]" maxlength="50" value="<?= date('d/m/Y H:i', strtotime($habitualidade[$idh]->datacadastro)) ?>" readonly>
                                </td>
                                <td><input type="text" class="form-control" name="habitu_local[]" size="8" value="<?= $habitualidade[$idh]->local ?>" readonly></td>
                                <td><input type="text" class="form-control" name="habitu_evento[]" value="<?= $habitualidade[$idh]->evento ?>" readonly></td>
                                <td colspan="4">
                    <?php $tipo = $habitualidade[$idh]->tipo . '|' . $habitualidade[$idh]->calibre . '|' . $habitualidade[$idh]->numsigma; ?>
                    <?php if ($habitualidade[$idh]->digitado == 0) : ?>

                <select name="tipo_<?= htmlspecialchars($idh, ENT_QUOTES, 'UTF-8'); ?>[]" class="form-control" disabled>
                  
                    
                   
                           
                             
                                    <?php
                                    // Formatar o valor da opção
           
            $selectedValue = trim($habitualidade[$idh]->tipo) . '|' . trim($habitualidade[$idh]->calibre) . '|' . trim($habitualidade[$idh]->numsigma);
           
                                    ?>
                                    <option value="<?= htmlspecialchars($selectedValue, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= htmlspecialchars($selectedValue, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                               
                          
                      



</select>
                        <?php else : ?>
                                    <div class="form-group">
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->tipo; ?>" type="text" name="manual_tipo_<?= $idh; ?>[]" class="form-control" placeholder="Tipo"></div>
                                      
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
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->numsigma; ?>" type="text" name="manual_sigma_<?= $idh; ?>[]" class="form-control" placeholder="Sigma"></div>
                                    </div>
                                  <?php endif; ?>
                                </td>
                                <td><input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value="<?= $habitualidade[$idh]->qtdemunicoes ?>" readonly></td>
                                <td><input type="text" class="form-control" name="habitu_livro[]" size="10" value="<?= $habitualidade[$idh]->livro ?>" readonly></td>                                
                                <td>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->municao == "C") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="origem_municao_C_<?= $idh; ?>" name="origem_municao_<?= $idh; ?>[]" value="C" disabled>
                                    <label class="custom-control-label" for="origem_municao_C_<?= $idh; ?>">Clube</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->municao == "A") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="origem_municao_A_<?= $idh; ?>" name="origem_municao_<?= $idh; ?>[]" value="A" disabled>
                                    <label class="custom-control-label" for="origem_municao_A_<?= $idh; ?>">Atirador</label>
                                  </div>
                                </td>
                                <td>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->tipomunicao == "O") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="tipo_municao_O_<?= $idh; ?>" name="tipo_municao_<?= $idh; ?>[]" value="O" disabled>
                                    <label class="custom-control-label" for="tipo_municao_O_<?= $idh; ?>">Original</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input <?php if ($habitualidade[$idh]->tipomunicao == "R") {
                                              echo " checked ";
                                            } ?> class="custom-control-input" type="radio" id="tipo_municao_R_<?= $idh; ?>" name="tipo_municao_<?= $idh; ?>[]" value="R" disabled>
                                    <label class="custom-control-label" for="tipo_municao_R_<?= $idh; ?>">Recarga</label>
                                  </div>
                                </td>
                                <td class="acoesbt"><a href="editar_habitualidade.php?id_habitualidade=<?= $habitualidade[$idh]->id; ?>&id=<?=$cliente->id?>" class="btn btn-info btn-sm editarmobile" ><i class="fa fa-edit"></i></a><a href="javascript:void(0);" onclick="dropHabitualidade('<?= $habitualidade[$idh]->id; ?>')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
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
                                        <option value="<?= $Armas->tipo . '|' . $Armas->calibre . '|' . $Armas->numsigma; ?>" <?= (trim($tipo) == ($Armas->tipo . '|' . $Armas->calibre . '|' . $Armas->numsigma)) ? ' selected ' : ''; ?>><?= $Armas->tipo . ' | ' . $Armas->calibre . ' | ' . $Armas->numsigma; ?></option>
                                      <?php endforeach; ?>
                                    </optgroup>
                                  <?php endif; ?>
                                  
                                </select>
                              </td>
                              <td><input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value="" readonly></td>
                              <td><input type="text" class="form-control" name="habitu_livro[]" size="10" value="" radonly></td>                              
                              <td>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="origem_municao_C_0" name="origem_municao_0[]" value="C" disabled>
                                  <label class="custom-control-label" for="origem_municao_C_0">Clube</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="origem_municao_A_0" name="origem_municao_0[]" value="A" disabled>
                                  <label class="custom-control-label" for="origem_municao_A_0">Atirador</label>
                                </div>
                              </td>
                              <td>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="tipo_municao_O_0" name="tipo_municao_0[]" value="O" disabled>
                                  <label class="custom-control-label" for="tipo_municao_O_0">Original</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input class="custom-control-input" type="radio" id="tipo_municao_R_0" name="tipo_municao_0[]" value="R" disabled>
                                  <label class="custom-control-label" for="tipo_municao_R_0">Recarga</label>
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
  
  <script>
    const openLoading = (close = false) => {
        if(!close){
            $(".bgloading").fadeIn(0);
            $("button").prop('disabled', true);
        }else{
            $(".bgloading").fadeOut(0);
            $("button").prop('disabled', false);
        }
        
    }
    const cadastrarBiometria = () => {
        
        openLoading();
        
        try{
    	
        	$.ajax({
        		url: 'http://localhost:9000/api/public/v1/captura/Enroll/1',
        		type: 'GET',
        		success: function(retorno){
        		    
        		    openLoading(true);
        		  
        			if(retorno == ""){;
        				alert("Falha ao registrar digital");
        			}else{
        				gravarBiometria(retorno);
        			}
        		},
        		'error': function(e) {
        		      console.log(e);
        		    openLoading(true);
                    alert('Falha ao iniciar leitor biometrico');
        		}
        	});
        	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const alterarBiometria = () => {
        
        var r = confirm('Deseja realmente alterar a biometria deste membro?');
        if(r){
        
            openLoading();
            
            try{
        	
            	$.ajax({
            		url: 'http://localhost:9000/api/public/v1/captura/Enroll/1',
            		type: 'GET',
            		success: function(retorno){
            		    
            		    openLoading(true);
            		    
            			if(retorno == ""){;
            				alert("Falha ao capturar biometria");
            			}else{
            				alteraBiometria(retorno);
            			}
            		},
            		'error': function(e) {
            		    openLoading(true);
                        alert('Falha ao iniciar leitor biometrico');
            		}
            	});
            	
            }catch(e) {
                openLoading(true);
                alert('Erro interno: '+e.message);
            }
        
        }
    }
    const gravarBiometria = (biometria) => {
        
        openLoading();
        
        try{
        
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'cadastrar',
        		    'biometria' : biometria,
        		    'id'        : '<?= $cliente->id; ?>'
        		},
        		success: function(retorno){
        		    
        			if(retorno == ""){
        			    openLoading(true);
        				alert("Não foi possivel gravar a biometria.");
        			}else{
        				alert("Biometria cadastrada com sucesso.");
        				window.location=window.location.href;
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const alteraBiometria = (biometria) => {
            
        openLoading();
    
        try{
        
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'alterar',
        		    'biometria' : biometria,
        		    'id'        : '<?= $cliente->id; ?>'
        		},
        		success: function(retorno){
        		    
        			if(retorno == ""){
        			    openLoading(true);
        				alert("Não foi possivel gravar a biometria.");
        			}else{
        				alert("Biometria alterada com sucesso.");
        				window.location=window.location.href;
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const registrarPresenca = () => {
        openLoading();
        
        try{
        
            $.ajax({
        		url: 'http://localhost:9000/api/public/v1/captura/Capturar/1',
        		type: 'GET',
        		success: function (data) {
        			if(data == ""){
        			    openLoading(true);
        				alert("Biometria não capturada.");
        			}else{
        				comparaBiometria(data, '<?= $cliente->biometria; ?>');
        			}
        		},
        		'error': function(e) {
        		    openLoading(true);
                    alert('Falha ao iniciar leitor biometrico');
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const comparaBiometria = (biometria_capturada, biometria_membro) => {
        
        $.ajax({
    		url: 'http://localhost:9000/api/public/v1/captura?Digital=' + biometria_capturada + '|' + biometria_membro,
    		type: 'GET',
    		success: function(retorno){
    		    
    		    openLoading(true);
    		    
    		    if(retorno == "OK"){
    		        registraPresenca(biometria_capturada);
    		        
    		     
    		        
    		      
    		        alert('Registro Biometrico encontrado');
    		           document.location="nova_habitualidade.php?id=<?= $cliente->id ?>";
    		    }else{
    		        alert('ERRO: Biometria não compativel.');
    		    }
    		    
    		},
    		'error': function(e) {
    		    openLoading(true);
                alert('Falha ao iniciar leitor biometrico');
    		}
    	});
    }
    const registraPresenca = (biometria_capturada) => {
        
        openLoading();
            
        try{
    
            $.ajax({
        		url: '',
        		type: 'POST',
        		data: {
        		    'action'    : 'registra_presenca',
        		    'biometria' : biometria_capturada,
        		    'matricula' : '<?= $cliente->matricula; ?>'
        		},
        		success: function(retorno){
        		    openLoading(true);
        			if(retorno == "S"){
        				alert("Presença registrada com sucesso.");
        			   document.location="nova_habitualidade.php?id=<?= $cliente->id ?>";
        			}else{
        				alert("ERRO. Falha ao registrar presença.");
        			}
        		}
        	});
    	
        }catch(e) {
            openLoading(true);
            alert('Erro interno: '+e.message);
        }
        
    }
    const excluirBiometria = () => {
        var r = confirm('Deseja realmente excluir a biometria deste membro?');
        if(r){
            openLoading();
            
            try{
        
                $.ajax({
            		url: '',
            		type: 'POST',
            		data: {
            		    'action'    : 'excluir',
            		    'id'        : '<?= $cliente->id; ?>'
            		},
            		success: function(retorno){
            		    
            			if(retorno == ""){
            			    openLoading(true);
            				alert("Falha ao excluir biometria.");
            			}else{
            				alert("Biometria excluida com sucesso.");
            				window.location=window.location.href;
            			}
            		}
            	});
        	
            }catch(e) {
                openLoading(true);
                alert('Erro interno: '+e.message);
            }
        }
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
    <input type="hidden" name="user" value="<?= $_SESSION['uname'] ?>">
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