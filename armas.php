<?php

include "config/config.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
}

require 'config/conexao.php';

// Recebe o id do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$matricula = isset($_GET['matricula']) ? $_GET['matricula'] : '';

// Verifica se existe um id válido
if (!empty($id_cliente) && is_numeric($id_cliente)):

    // Consulta os dados do cliente
    $conexao = conexao::getInstance();
    $sql_membros = 'SELECT id, matricula, nome FROM tab_membros WHERE id = :id';
    $stm_membros = $conexao->prepare($sql_membros);
    $stm_membros->bindValue(':id', $id_cliente);
    $stm_membros->execute();
    $cliente = $stm_membros->fetch(PDO::FETCH_OBJ);
    
    $sql3 = 'SELECT * FROM tab_membros ORDER BY nome ASC';
    $stm = $conexao->prepare($sql3);
    $stm->bindValue(':id', $id_cliente);
    $stm->execute();
    $atiradores = $stm->fetchAll(PDO::FETCH_OBJ);

    // Consulta as armas do cliente
    $sql_armas = 'SELECT id, tipo, modelo, calibre, numsigma FROM tab_armas WHERE id_proprietario = :id';
    $stm_armas = $conexao->prepare($sql_armas);
    $stm_armas->bindValue(':id', $id_cliente);
    $stm_armas->execute();
    $armas = $stm_armas->fetchAll(PDO::FETCH_OBJ);

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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
  <section id="container" class="">
    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>

      <div class="nav search-row" id="top_menu">
        <ul class="nav top-menu">
          <li></li>
        </ul>
      </div>

      <div class="top-nav notification-row">
        <ul class="nav pull-right top-menu">
          <li class="dropdown">            
            <form method='post' action="">
              <input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout">
            </form>
          </li>
        </ul>
      </div>
    </header>

    <?php include 'menu_lateral_esq.php';?>

    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-hand-o-right" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>

        <div class="btn-group btn-group-sm">
          <a class="btn btn-info btn-sm" href="habitualidade.php?id=<?=$cliente->id?>" title="Habitualidade" alt="Habitualidade" target="_self">
          <i class="fa fa-street-view" aria-hidden="true"></i></a>
        </div> 
        
    <a class="btn btn-info btn-sm" href="inserir_arma.php?id_cliente=<?= $id_cliente ?>&matricula=<?= $matricula ?>">
    <i class="fa fa-plus"></i> NOVA ARMA</a>
        
</div>
<br><br>

<div class="row" style="margin-bottom: 10px;">
  <div class="col-lg-12">
    <b>Filtrar:</b>
    <select class="form-control select2" onchange="selecionaAtirador(this.value);">
      <?php if ($atiradores) : ?>
        <?php foreach ($atiradores as $key => $Atirador) : ?>
          <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>>
            <?= $Atirador->nome . ' - ' . $Atirador->matricula; ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
</div>
<script>
$(document).ready(function() {
  $('.select2').select2();
});
function selecionaAtirador(id_atirador) {
  window.location = 'armas.php?id=' + id_atirador;
}
</script>

        <div class="row">
          <div class="col-sm-12">
            <section class="panel">
              <header class="panel-heading">
               Tabela de Armas e Equipamentos
              </header>
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Armamento</th>
                    <th>A&ccedil;&atilde;o</th>								
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($armas)): ?>
                    <?php foreach ($armas as $index => $arma): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $arma->tipo ?> | <?= $arma->modelo ?> | <?= $arma->calibre ?> | <?= $arma->numsigma ?> </td>
                        <td>
                         <a class="btn btn-info btn-sm" title="Editar" href="editar_arma.php?id_arma=<?= $arma->id ?>&id_cliente=<?= $id_cliente ?>&user=<?= $_SESSION['uname'] ?>" alt="Editar">
                         <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                         <a class="btn btn-warning btn-sm" title="Transferir" href="transferir_arma.php?id_arma=<?= $arma->id ?>&id_cliente=<?= $id_cliente ?>&user=<?= $_SESSION['uname'] ?>" alt="Transferir">
                         <i class="fa fa-random"></i></a>

                          <a class="btn btn-info btn-sm" title="Excluir" href="action_del_armas.php?id=<?=$arma->id?>&user=<?= $_SESSION['uname'] ?>" onclick="return confirm('Confirma excluir registro?');">
                          <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3">Nenhuma arma encontrada.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </section>
          </div>
        </div>
      </section>
    </section>

    <div class="text-center">
      <div class="credits">
        Designed by <a href="https://hostmarq.com/">HOSTMARQ</a><br><br>
      </div>
    </div>
  </section>

  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>$(document).ready(function() { $('.select2').select2();});</script>
                
</body>
</html>