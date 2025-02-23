<?php

include "config/config.php";
require 'config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

// Recebe os IDs do membro e da arma via GET
$id_arma = (isset($_GET['id_arma'])) ? $_GET['id_arma'] : '';
$id_cliente = (isset($_GET['id_cliente'])) ? $_GET['id_cliente'] : '';
$user= (isset($_GET['user'])) ? $_GET['user'] : '';
$novo_proprietario=(isset($_POST['novo_proprietario'])) ? $_POST['novo_proprietario'] : '';

// Verifica se os IDs são válidos
if (!empty($id_arma) && is_numeric($id_arma)):

    // Consulta os dados do membro
    $conexao = conexao::getInstance();
    $sql_membro = 'SELECT id, matricula, nome FROM tab_membros WHERE id = :id';
    $stm_membro = $conexao->prepare($sql_membro);
    $stm_membro->bindValue(':id', $id_cliente);
    $stm_membro->execute();
    $membro = $stm_membro->fetch(PDO::FETCH_OBJ);
    
     $sql_membros = 'SELECT id, matricula, nome FROM tab_membros';
     $stm_membros = $conexao->prepare($sql_membros);
   
    $stm_membros->execute();
    $membros = $stm_membros->fetchAll(PDO::FETCH_OBJ);

    // Consulta os dados da arma
    $sql_arma = 'SELECT id, matricula, numsigma, id_grupo, tipo, modelo, calibre, validade_gt, validade_craf, imagem_gt, imagem_craf, base64_gt, base64_craf, sequencia FROM tab_armas WHERE id = :id';
    $stm_arma = $conexao->prepare($sql_arma);
    $stm_arma->bindValue(':id', $id_arma);
    $stm_arma->execute();
    $arma = $stm_arma->fetch(PDO::FETCH_OBJ);
    
    $conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);
    
    // Processa o formulário de atualização
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
       $tipo_transferencia=(isset($_POST['tipo_transferencia'])) ? $_POST['tipo_transferencia'] : '';
       
       if($tipo_transferencia == 'membro'){
           
           
           
             // Atualiza os dados da arma
        $sql_update = 'UPDATE tab_armas SET id_proprietario = :id_proprietario, id_membro=:id_membro, sequencia=:sequencia  WHERE id = :id';
        $stm_update = $conexao->prepare($sql_update);
        $stm_update->bindValue(':sequencia', 2);
        $stm_update->bindValue(':id_proprietario', $novo_proprietario);
        $stm_update->bindValue(':id_membro', $novo_proprietario);
        $stm_update->bindValue(':id', $id_arma);

        $stm_update->execute();
       }else{
           
             // Atualiza os dados da arma
        $sql_update = 'UPDATE tab_armas SET id_proprietario = :id_proprietario, id_membro = :id_membro,  clube_ou_instrutor= :clube_instrutor, sequencia=:sequencia WHERE id = :id';
        $stm_update = $conexao->prepare($sql_update);
        $stm_update->bindValue(':id_proprietario', null);
        $stm_update->bindValue(':id_membro', null);
           $stm_update->bindValue(':sequencia', 1);
              $stm_update->bindValue(':clube_instrutor', 'Transferida de Membro');
        $stm_update->bindValue(':id', $id_arma);

        $stm_update->execute();
       }
       
       //Dados do operador    
           $conexao = conexao::getInstance();
            $sql_user = "SELECT * FROM users WHERE username = :name";
            $stm = $conexao->prepare($sql_user);
            
            $stm->bindParam(':name', $user, PDO::PARAM_STR);
            
            $stm->execute();
            
            $usuario = $stm->fetch(PDO::FETCH_OBJ);
            
            //inserção do LOg
            
             $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
            			   VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';

			$stm = $conexao->prepare($sql_log);
			$stm->bindValue(':username', $usuario->name);
			$stm->bindValue(':tabela', 'tab_armas');
			$stm->bindValue(':tipo_alteracao', 'Transferencia');
				$stm->bindValue(':registro_id', $arma->id);
		  // Converte o array para JSON
            $alteracao = json_encode(['Transferencia de Arma: ' =>'', 'id'=> $arma->id,'tipo'=>$arma->tipo, 'modelo'=> $arma->modelo, 'calibre'=>$arma->calibre, 'numsigma'=>$arma->numsigma,'de membro_id '=>$id_cliente, 'para membro_id'=>$novo_proprietario]);
            $stm->bindValue(':registro', $alteracao);
         
			$stm->bindValue(':data', date('Y-m-d H:i:s'));
            $retorno_log = $stm->execute();
        // Redireciona após a atualização
        // header('Location: armas.php?id=' . $membro->id);
      
       echo '<script type="text/javascript">
    window.history.go(-2);
</script>';
        exit();
    }

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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>   
  <link rel="shortcut icon" href="img/favicon.png">
   	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

</head>
<style>
     @media(max-width:768px){  
    .doc {
    width: 100%;
}
video#camera, #camera2 {
    display: none;
}
    }

</style>
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
            <form class="navbar-form">
              <input class="form-control" placeholder="Search" type="text">
            </form>
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

    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Transferir - <?=$arma->tipo?> | <?= $arma->modelo?> | <?=$arma->calibre?> | <?=$arma->numsigma?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$membro->matricula?></li>
            </ol>
          </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------- page start-->

<fieldset>
<?php if(empty($arma)):?>
<h3 class="text-center text-danger">Armamento não encontrado!</h3>
<?php else: ?>
<form method="post" action="">

<div class="form-group">
<label for="nome_cliente">De:</label><br>      
<?php if($arma->sequencia == 1){?>
<input type="text" class="form-control" id="nome_cliente" name="cliente" value="<?= ($clube->clube_nome) ?>" readonly>
<?php }else{?>
<input type="text" class="form-control" id="nome_cliente" name="cliente" value="<?= ($membro->nome) ?>" readonly>
<?php }?>
<input type="hidden" class="form-control" id="numsigma" name="id_cliente" value="<?= ($membro->id) ?>">
<span class='msg-erro msg-numsigma'></span>
</div>
 <div class="form-group">
     <label for="tipo_tranf">Tipo de Transferencia:</label><br>
    
    <label for="trans_membro">Membro</label> 
    <input type="radio" name="tipo_transferencia" value="membro" id="trans_membro" checked><br>
     <label for="trans_clube">Clube</label> 
    <input type="radio" name="tipo_transferencia" value="clube" id="trans_clube">
     </div>

  <div class="form-group membros">
     
            <label for="proprietario">Para:</label><br>
            
            <select id="proprietario" name='novo_proprietario' class="form-control novo_proprietario">
                <option value="">Selecione</option>
                <?php 
                foreach($membros as $memb){
                ?>
                <option value="<?= htmlspecialchars($memb->id) ?>"><?=$memb->matricula?> - <?=$memb->nome?></option>
               <?php }?>
         
            </select>
          </div>
<hr>
<input type="hidden" name="user" value="<?= $_SESSION['uname'] ?>">
<button type="submit" class="btn btn-primary" id='botao'> Transferir </button>
<a href="armas.php?id=<?= $membro->id ?>" class="btn btn-secondary">Cancelar</a>
</form>
<?php endif; ?>
</fieldset>

        <!-------------------------------------------------------------------------------------------------------------- page end-->
   
   
      <div class="text-center">
        <div class="credits">Designed by <a href="#">HOSTMARQ</a>
        </div>
      </div>
    </section>
    <!--main content end-->
  </section>
  <!-- container section end -->

  <!-- javascripts -->

  <script src="js/jquery.js"></script>
 
  <script src="js/bootstrap.min.js"></script>
  <!-- nicescroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!-- jquery validate js -->
  <script type="text/javascript" src="js/jquery.validate.min.js"></script>

  <!-- custom form validation script for this page-->
  <script src="js/form-validation-script.js"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
      $(document).ready(function() {
    $('.novo_proprietario').select2();
});


  </script>
 
</body>
</html>