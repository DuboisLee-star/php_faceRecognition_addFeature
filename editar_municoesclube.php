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

// Valida se existe um id e se ele � num�rico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_compras WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->compra_data);
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

<script>
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}
function salva(){
	
    var compra_data = document.getElementById("compra_data").value;


    if(compra_data == ""){alert("Preencha a data da compra."); return false;}


	
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
            <h3 class="page-header"><i class="fa fa-users" aria-hidden="true"></i>MUNIÇÕES DO CLUBE</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-users"></i>Entrada de Munições do Clube</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      		
		 <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Relação de Compras de Munições
              </header>
              <div class="panel-body">
                <div class="form">
			
		<fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Não existem compras cadastradas!</h3>
			<?php else: ?>
				<form action="action_edit_municoesclube.php" method="post" id='form-contato' enctype='multipart/form-data'>

  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Data da compra</td>
                    <td><input type="date" class="form-control" id="compra_data" name="compra_data" value="<?= date('Y-m-d', strtotime($cliente->compra_data))?>"></td>
                 </tr>
                  <tr>
                   <td>Nome da loja</td>
                    <td><input type="text" class="form-control" id="compra_loja" name="compra_loja" value="<?=$cliente->compra_loja?>"></td>
                 </tr>
                  <tr>
                   <td>Número da NF</td>
                    <td><input type="text" class="form-control" id="compra_nf" name="compra_nf" value="<?=$cliente->compra_nf?>"></td>
                  </tr>
                  <tr>
                    <td>Calibre</td>
                    <td><input type="text" class="form-control" id="compra_calibre" name="compra_calibre" value="<?=$cliente->compra_calibre?>"></td>
                  </tr>
                  <tr>
                    <td>Quantidade de munições</td>
                    <td><input type="text" class="form-control" id="compra_qtdecalibre" name="compra_qtdecalibre" value="<?=$cliente->compra_qtdecalibre?>"></td>
                  </tr>
                  <tr>
                    <td>Insumos</td>
                    <td><input type="text" class="form-control" id="compra_insumos" name="compra_insumos" value="<?=$cliente->compra_insumos?>"></td>
                  </tr>
                  <tr>
					  <td>Quantidade de Insumos</td>
                    <td><input type="text" class="form-control" id="compra_qtdeinsumos" name="compra_qtdeinsumos" value="<?=$cliente->compra_qtdeinsumos?>"></td>
                  </tr>
                  <tr>
                    <td>Valor</td>
                    <td><input type="text" class="form-control" id="compra_valor" name="compra_valor" value="<?=$cliente->compra_valor?>"></td>
                  </tr>
                  <tr>
                   <td>Tipo Munição</td>
                    <td>
						<select name="compra_tipo" id="compra_tipo" class="form-control">
							<option value="O" <?= ($cliente->compra_tipo == "O") ? " selected " : ""; ?>>Original</option>
							<option value="R" <?= ($cliente->compra_tipo == "R") ? " selected " : ""; ?>>Recarregada</option>
						</select>
					</td>
                   </tr>                   
                  <tr>
                    <td></td>
                    <td> <input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <button type="submit" class="btn btn-primary" id='botao'>Salvar</button>
                   </form>
			<?php endif; ?>
		</fieldset></td>
                  </tr>
                </tbody>
              </table>
            </section>
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


</body>

</html>
