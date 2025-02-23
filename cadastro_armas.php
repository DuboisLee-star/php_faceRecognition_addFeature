<?php
include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
}

?>
<?php
require 'config/conexao.php';

$id = isset($_GET['id']) ? $_GET['id'] : false;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : false;
$modelo = isset($_POST['modelo']) ? $_POST['modelo'] : false;
$calibre = isset($_POST['calibre']) ? $_POST['calibre'] : false;
$numsigma = isset($_POST['numsigma']) ? $_POST['numsigma'] : false;
$tipo_calibre = isset($_POST['tipo_calibre']) ? $_POST['tipo_calibre'] : false;
$clube_ou_instrutor = isset($_POST['clube_ou_instrutor']) ? $_POST['clube_ou_instrutor'] : false;
$sequencia = isset($_POST['sequencia']) ? $_POST['sequencia'] : false;
$id_grupo = isset($_POST['grupo']) ? $_POST['grupo'] : false;



if ($tipo && $modelo && $calibre && $numsigma) {

    if ($id) {

        $conexao = conexao::getInstance();
        $sql = "UPDATE tab_armas SET tipo=:tipo, modelo=:modelo, id_grupo=:id_grupo, calibre=:calibre, tipo_calibre=:tipo_calibre, numsigma=:numsigma, sequencia=:sequencia, clube_ou_instrutor=:clube_ou_instrutor, data_alteracao=:data_alteracao WHERE id = :id ";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':tipo', $tipo);
        $stm->bindValue(':modelo', $modelo);
        $stm->bindValue(':calibre', $calibre);   
        $stm->bindValue(':numsigma', $numsigma);        
        $stm->bindValue(':id_grupo', $id_grupo);
        $stm->bindValue(':tipo_calibre', $tipo_calibre);        
        $stm->bindValue(':clube_ou_instrutor', $clube_ou_instrutor);   
        $stm->bindValue(':sequencia', $sequencia);          
        $stm->bindValue(':data_alteracao', date('Y-m-d H:i:s'));
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        exit("<script>alert('" . (($retorno) ? 'Alteração realizada com sucesso.' : 'Falha ao alterar arma.') . "'); window.location='cadastro_armas.php';</script>");

    } else {

        $conexao = conexao::getInstance();
        $sql = "INSERT INTO tab_armas (tipo, modelo,id_proprietario, id_grupo, calibre, tipo_calibre, numsigma, clube_ou_instrutor, sequencia, data_cadastro) VALUES (:tipo, :modelo, :id_proprietario, :id_grupo, :calibre, :tipo_calibre, :numsigma, :clube_ou_instrutor, :sequencia, :data_cadastro) ";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':tipo', $tipo);
        $stm->bindValue(':modelo', $modelo);
         $stm->bindValue(':id_proprietario', null);
        $stm->bindValue(':calibre', $calibre);  
        $stm->bindValue(':numsigma', $numsigma);        
        $stm->bindValue(':id_grupo', $id_grupo);
        $stm->bindValue(':tipo_calibre', $tipo_calibre);        
        $stm->bindValue(':clube_ou_instrutor', $clube_ou_instrutor); 
        $stm->bindValue(':sequencia', $sequencia);          
        $stm->bindValue(':data_cadastro', date('Y-m-d H:i:s'));
        $retorno = $stm->execute();

        exit("<script>alert('" . (($retorno) ? 'Cadastro realizado com sucesso.' : 'Falha ao cadastrar arma.') . "'); window.location='cadastro_armas.php';</script>");
    }

    exit();
}

// Capture the data of the requested weapon
$conexao = conexao::getInstance();
$sql = "SELECT * FROM tab_armas WHERE sequencia = '1' ORDER BY descricao ASC";
$stm = $conexao->prepare($sql);
$stm->execute();
$armas = $stm->fetchAll(PDO::FETCH_OBJ);

    $sql_grupos = 'SELECT * FROM tab_grupos_armas';
    $stm_grupos = $conexao->prepare($sql_grupos);
    $stm_grupos->execute();
    $grupos = $stm_grupos->fetchAll(PDO::FETCH_OBJ);

if ($id) {

    $conexao = conexao::getInstance();
    $sql = "SELECT * FROM tab_armas WHERE sequencia = '1' AND id = :id ORDER BY descricao ASC ";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $stm->execute();
    $dadosArma = $stm->fetch(PDO::FETCH_OBJ);
    $tipo = $dadosArma->tipo;
    $modelo = $dadosArma->modelo;
    $calibre = $dadosArma->calibre;
    $numsigma = $dadosArma->numsigma;    
    $tipo_calibre = $dadosArma->tipo_calibre;
    $sequencia = $dadosArma->sequencia;    
    $clube_ou_instrutor = $dadosArma->clube_ou_instrutor;
}

if($_GET['id']!='' || $_GET['id']!= null){
    $conexao = conexao::getInstance();
$sql = "SELECT * FROM tab_armas WHERE id=:id";

$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $_GET['id']);
$stm->execute();
$gun = $stm->fetch(PDO::FETCH_OBJ);

 $sql_grupos = 'SELECT * FROM tab_grupos_armas where id=:id';
    $stm_grupos = $conexao->prepare($sql_grupos);
    $stm_grupos->bindValue(':id', $gun->id_grupo);
    $stm_grupos->execute();
    
    $group = $stm_grupos->fetch(PDO::FETCH_OBJ);
}
// Faça a consulta ao banco de dados para obter os calibres do tipo 'C'
$conexao = conexao::getInstance();
$sql = "SELECT calibre FROM tab_habitu_op WHERE tipo = 'C'";
$stm = $conexao->prepare($sql);
$stm->execute();
$calibres = $stm->fetchAll(PDO::FETCH_COLUMN);

?>
<!-- Rest of your HTML code remains unchanged -->

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
            <h3 class="page-header"><i class="fa fa-plus-circle" aria-hidden="true"></i>Cadastro de Armas</h3>
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
					CADASTRO DE ARMAS
					</header>
					<div class="panel-body">
						<div class="form">

							<form action="" method="post" name="filtro_form" id="filtro_form">
								<input type="hidden" name="id" value="<?= ($id) ? $id : ''; ?>">
								<div class="row">
                                    <div class="col-lg-3">
                                       <b>Origem:</b> 
                                       <select required class="form-control" id="clube_ou_instrutor" name="clube_ou_instrutor">
                                       <option value="">- escolha -</option>   
                                       <option value="Clube">Armamento do Clube</option>
                                       <option value="Instrutor">Armamento de Instrutor</option>
                                       </select>
                                    </div>	</p>							    
									<div class="col-lg-3">
										<b>Tipo:</b> 
										<input type="text" required class="form-control" id="tipo" name="tipo" placeholder="Pistola, revolver..." value="<?= ($tipo) ? $tipo : ''; ?>">
									</div><br>
									<div class="col-lg-3">
										<b>Modelo:</b><br>
										<input type="text" required class="form-control" id="modelo" name="modelo" placeholder="G19, G2C..." value="<?= ($modelo) ? $modelo : ''; ?>">
									</div>
									<div class="col-lg-3">
										<b>Calibre:</b><br>

                    <select name="calibre" class="form-control" id="calibre">
                      <?php if(count($calibres) > 0): ?>

                        <?php foreach($calibres as $calibre) : ?>
                          <option value="<?=$calibre?>"><?=$calibre?></option>
                        <?php endforeach; ?>

                      <?php  else: ?>
                        <option value="">Nenhum calibre encontrado</option>
                      <?php endif; ?>

                    </select>
                    
									</div>
                                    <div class="col-lg-3">
                                    <b>Tipo Calibre:</b><br>
                                    <select required class="form-control" id="tipo_calibre" name="tipo_calibre">
                                    <option value="">- escolha -</option>                                        
                                    <option value="Restrito" <?= ($tipo_calibre === 'Restrito') ? 'selected' : ''; ?>>Restrito</option>
                                    <option value="Permitido" <?= ($tipo_calibre === 'Permitido') ? 'selected' : ''; ?>>Permitido</option>
                                    </select>
                                    </div>									
									<div class="col-lg-3">
										<b>Nº sigma:</b><br>
										<input type="text" required class="form-control" id="numsigma" name="numsigma" placeholder="Número do Sigma" value="<?= ($numsigma) ? $numsigma : ''; ?>">
                                        <input type="hidden" id="sequencia" name="sequencia" value="1">
									</div>
									<div class="col-lg-3">
            <label for="grupo">Grupo:</label><br>
            <select id="grupo" name='grupo' class="form-control" required>
                <?php if($_GET['id']){?>
                    
                
                <option value="<?=$group->id?>" selected><?=$group->nome.': '.$group->descricao?></option>
                
               <?php } ?>
               <option value="">Selecione o grupo</option>
               <?php
            // Gera os botões de rádio para cada calibre
            if (!empty($grupos)) {
                foreach ($grupos as $grupo) {
                    echo'<option value="'.$grupo->id.'">'.$grupo->nome.': '.$grupo->descricao.'</option>';
                    
                  
                }
            } else {
                echo 'Nenhum calibre disponível.';
            }
            
            ?>
         
            </select>
            
          </div>
									<div class="col-lg-3"><br>
										<button class="btn btn-info" type="button" onclick="return salvar()">Salvar</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</section>
			</div>
          </div>
		  
		  
		  
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
						ARMAS CADASTRADAS
						</header>
						<div class="panel-body">
							<div class="form">


								<table class="table table-striped">
    <tr>
        <td><b>Origem</b></td>        
        <td><b>Tipo</b></td>
        <td><b>Modelo</b></td>
        <td><b>Calibre</b></td>
        <td><b>T.C.</b></td>        
        <td><b>Número do Sigma</b></td>
        <td><b>Data de Cadastro</b></td>
        <td><b></b></td>
    </tr>
    <?php if ($armas): ?>
        <?php foreach ($armas as $key => $Arma): ?>
            <tr>
                <td><?= $Arma->clube_ou_instrutor; ?></td>                
                <td><?= $Arma->tipo; ?></td>
                <td><?= $Arma->modelo; ?></td>
                <td><?= $Arma->calibre; ?></td>
                <td><?= $Arma->tipo_calibre; ?></td>                
                <td><?= $Arma->numsigma; ?></td>
                <td><?= !empty($Arma->data_cadastro) ? date('d/m/Y', strtotime($Arma->data_cadastro)) : ''; ?></td>

                <td><a href="?id=<?= $Arma->id; ?>" class="btn btn-info btn-sm" type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  <a class="btn btn-warning btn-sm" title="Transferir" href="transferir_arma.php?id_arma=<?= $Arma->id ?>" alt="Transferir">
                         <i class="fa fa-random"></i></a></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

							</div>
						</div>
					</section>
				</div>
			</div>
		  
		  
    </fieldset>					  
			  
            				   
    <!--main content end-->
	<!--------------------------------------------------------- page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
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
const salvar = () => {
    var clube_ou_instrutor = document.getElementById("clube_ou_instrutor").value;
    var tipo = document.getElementById("tipo").value;
    var modelo = document.getElementById("modelo").value;
    var calibre = document.getElementById("calibre").value;
    var tipo_calibre = document.getElementById("tipo_calibre").value;
    var numsigma = document.getElementById("numsigma").value;

    if (clube_ou_instrutor === "" || tipo === "" || modelo === "" || calibre === "" || tipo_calibre === "" || numsigma === "") {
        alert("Preencha todos os campos obrigatórios.");
        return false;
    }

    document.getElementById('filtro_form').submit();
}
</script>

</body>
</html>