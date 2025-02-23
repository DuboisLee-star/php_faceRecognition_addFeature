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

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele È numÈrico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
	// pega dados da habitualidade
	$sql2 = " SELECT * FROM tab_municoesclube WHERE matricula = :matricula ORDER BY matricula DESC ";
	$stm = $conexao->prepare($sql2);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->execute();
	$compra = $stm->fetchAll(PDO::FETCH_OBJ);

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

    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-money" aria-hidden="true"></i>MUNI&Ccedil;&Otilde;ES RECARREGADAS</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?> > CR > <?=$cliente->cr?> > <?=$cliente->nome?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->

<div class="table-responsive">
<fieldset>
			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente n√£o encontrado!</h3>
			<?php else: ?>
			
<form action="action_municoesclube.php" method="post" id='form-contato' enctype='multipart/form-data'>
<input type="hidden" class="form-control" id="matricula" name="matricula" value="<?=$cliente->matricula?>" readonly>
<input type="hidden" class="form-control" id="nome" name="nome" value="<?=$cliente->nome?>" disabled>

<div class="form-group">
<script>
function addCompra(){
	var html_novo = '<tr><td><input type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i'); ?>"></td>
	<td><input type="text" class="form-control" name="compra_descricao[]" size="8" value=""></td>
	<td><input type="text" class="form-control" name="compra_arma[]" value=""></td>
	<td><input type="text" class="form-control" name="compra_num_serie[]" value=""></td>
	<td><input type="text" class="form-control" name="compra_calibre[]" value=""></td>
	<td><input type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value=""></td>
	<td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-danger btn-sm">X</a></td></tr>';
	$("#lista_compra tbody").prepend(html_novo);
}
function drop(botao){
	var r = confirm("Deseja realmente excluir esta linha?");
	if(r) $(botao).parent("td").parent("tr").remove();
}
</script>
<button class="btn btn-primary btn-sm" type="button" onclick="addCompra()" style="float: right;">[+] Mais</button>       
<table class="table table-hover" id="lista_compra" style="min-width: 600px;">
         <thead>
           <th><b>Data/Hora</b></th>
           <th><b>Evento</b></th>
           <th><b>Arma</b></th>
           <th><b>Num.Serie</b></th>           
		   <th><b>Calibre</b></th>
           <th><b>Qtde</b></th>
         <th></th>
        </thead>
   	    <?php if($compra): ?>
        <tbody>
		<?php foreach($compra as $idh => $value): ?>
        
     <tr>
     <td><input type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i', strtotime($compra[$idh]->compra_datacadastro))?>"></td>
     <td><input type="text" class="form-control" name="compra_descricao[]" value="<?=$compra[$idh]->compra_descricao?>"></td>
     <td><input type="text" class="form-control" name="compra_arma[]" value="<?=$compra[$idh]->compra_arma?>"></td>
     <td><input type="text" class="form-control" name="compra_num_serie[]" value="<?=$compra[$idh]->compra_num_serie?>"></td>
     <td><input type="text" class="form-control" name="compra_calibre[]" value="<?=$compra[$idh]->compra_calibre?>"></td>     
     <td><input type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value="<?=$compra[$idh]->compra_qtdecalibre?>"></td>
     <td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-danger btn-sm">X</a></td>
           </tr> 
			<?php endforeach; ?>
	        <?php else: ?>
            <tr>
            <td><input type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i'); ?>" ></td>
            <td><input type="text" class="form-control" name="compra_descricao[]" size="8" value=""></td>
            <td><input type="text" class="form-control" name="compra_arma[]" value=""></td>
            <td><input type="text" class="form-control" name="compra_num_serie[]" value=""></td>
            <td><input type="text" class="form-control" name="compra_calibre[]" value=""></td>            
            <td><input type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value=""></td>			
            <td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-danger btn-sm">X</a></td>
            </tr>
			<?php endif; ?>
            </tbody>
            </table>
									<div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group label-floating">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

<input type="hidden" name="acao" value="editar">
<input type="hidden" name="id" value="<?=$cliente->id?>">
<button type="submit" class="btn btn-info pull-right" id='botao'>Salvar</button>
<div class="clearfix"></div>
</form>
<?php endif; ?>
</fieldset>
</div>
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
