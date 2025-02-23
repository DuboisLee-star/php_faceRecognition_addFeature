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
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
	// pega dados da habitualidade
	$sql2 = " SELECT * FROM tab_compras WHERE matricula = :matricula ORDER BY matricula DESC, compra_datacadastro DESC ";
	$stm = $conexao->prepare($sql2);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->execute();
	$compra = $stm->fetchAll(PDO::FETCH_OBJ);

  // Faça a consulta ao banco de dados para obter os calibres do tipo 'C'
  $conexao = conexao::getInstance();
  $sql = "SELECT calibre FROM tab_habitu_op WHERE tipo = 'C'";
  $stm = $conexao->prepare($sql);
  $stm->execute();
  $getallCalibres = $stm->fetchAll(PDO::FETCH_COLUMN);
	
	include "config/consulta_cac.php";

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		        
        <div class="row" style="margin-bottom: 10px;">
          <div class="col-lg-12">
              <b>Atirador:</b>
              <select class="form-control select2" onchange="window.location='compra_municoes.php?id='+this.value">
                  <?php if($atiradores): ?>
                    <?php foreach($atiradores as $key => $Atirador): ?>
                        <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>><?= $Atirador->nome.' - '.$Atirador->matricula; ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </select>
              
              
            
          </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------- page start-->

<div class="table-responsive">
<fieldset>
			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
			
				<form action="action_municoes.php" method="post" id='form-contato' enctype='multipart/form-data'>

<input type="hidden" class="form-control" id="matricula" name="matricula" value="<?=$cliente->matricula?>" readonly>
<input type="hidden" class="form-control" id="data_filiacao" name="data_filiacao" value="<?=$cliente->data_filiacao?>" disabled>													
<input type="hidden" class="form-control" id="cr" name="cr" value="<?=$cliente->cr?>" disabled>													
<input type="hidden" class="form-control" id="validade_cr" name="validade_cr" value="<?=$cliente->validade_cr?>" disabled>
<input type="hidden" class="form-control" id="nome" name="nome" value="<?=$cliente->nome?>" disabled>

<div class="form-group">
<script>
function addCompra(){
  let calibres = <?=json_encode($getallCalibres)?>;

  let options = '';
  for(let i = 0; i < calibres.length; i++){
    options += '<option value="' + calibres[i] + '">' + calibres[i] + '</option>';
  }


	var html_novo = `<tr><td><input type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i'); ?>"></td><td><input type="text" class="form-control" name="compra_loja[]" size="8" value=""></td><td><input type="text" class="form-control" name="compra_nf[]" value=""></td>
  <td>
    <select type="text" class="form-control" name="compra_calibre[]" >
    <option value="">- selecione -</option>` + options + `
    </select>
  </td>
  <td><input type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value=""></td><td><select type="text" class="form-control" name="compra_insumos[]"><option value="">- selecione -</option><option>Espoleta</option><option>Polvora</option></select></td><td><input type="text" class="form-control" name="compra_qtdeinsumos[]" size="10" value=""></td><td><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-danger btn-sm">X</a></td></tr>`;
  
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
           <th><b>Origem</b></th>
           <th><b>Descri&ccedil;&atilde;o/N.F</b></th>
		   <th><b>Calibre</b></th>
           <th><b>Qtde M</b></th>
           <th><b>Insumo</b></th>		   
           <th><b>Qtde I</b></th>
         <th></th>
        </thead>
   	    <?php if($compra): ?>
        <tbody>
		<?php foreach($compra as $idh => $value): ?>
		<?php $notificado = ($compra[$idh]->notificado == 1) ? " disabled " : "";  ?>
        
     <tr>
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i', strtotime($compra[$idh]->compra_datacadastro))?>"></td>
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_loja[]" size="8" value="<?=$compra[$idh]->compra_loja?>"></td>
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_nf[]" value="<?=$compra[$idh]->compra_nf?>"></td>
     <td>

      <select name="compra_calibre[]" class="form-control">
        <?php if(count($getallCalibres) > 0): ?>

          <?php foreach($getallCalibres as $calibre) : ?>
            <option value="<?=$calibre?>" <?= $compra[$idh]->compra_calibre == $calibre ? 'selected' : ''?>><?=$calibre?></option>
          <?php endforeach; ?>

        <?php  else: ?>
          <option value="">Nenhum calibre encontrado</option>
        <?php endif; ?>

      </select>

    </td>
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value="<?=$compra[$idh]->compra_qtdecalibre?>"></td>
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_insumos[]" value="<?=$compra[$idh]->compra_insumos?>"></td>	 
     <td><input <?= $notificado; ?> type="text" class="form-control" name="compra_qtdeinsumos[]" size="10" value="<?=$compra[$idh]->compra_qtdeinsumos?>"></td>
     <td><?php if($notificado == ""): ?><a href="javascript:void(0);" onclick="drop(this)" class="btn btn-danger btn-sm">X</a><?php endif; ?></td>
           </tr> 
			<?php endforeach; ?>
	        <?php else: ?>
            <tr>
            <td><input type="text" class="form-control" name="compra_data[]" value="<?= date('d/m/Y H:i'); ?>" ></td>
            <td><input type="text" class="form-control" name="compra_loja[]" size="8" value=""></td>
            <td><input type="text" class="form-control" name="compra_nf[]" value=""></td>
            <td>
              <select type="text" class="form-control" name="compra_calibre[]" >
                <option value="">- selecione -</option>
                <option>10MM</option>
                <option>17HMR</option>
                <option>.22</option>
                <option>.32</option>
                <option>.36</option>
                <option>.38</option>
                <option>.38SPL</option>
                <option>.380ACP</option>
                <option>.357MAG</option>
                <option>.40</option>
                <option>.44</option>
                <option>.45</option>
                <option>.454</option>
                <option>12GA</option>
                <option>16GA</option>
                <option>20GA</option>
                <option>.308</option>
                <option>.556</option>
                <option>.762</option>
              </select>
            </td>
            <td><input type="text" class="form-control" name="compra_qtdecalibre[]" size="7" value=""></td>			
            <td><select type="text" class="form-control" name="compra_insumos[]"><option value="">- selecione -</option><option>Espoleta</option><option>Polvora</option></select></td>	
            <td><input type="text" class="form-control" name="compra_qtdeinsumos[]" size="10" value=""></td>			
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
<label rel="notifica_exercito" style="float: right; margin-top: 6px; margin-right: 25px;"><input type="checkbox" name="notifica_exercito" value="1"> Notificar Exército</label>
<label rel="anexo_pdf" style="float: right; margin-top: 6px; margin-right: 25px;">Anexar PDF: <input style="display: unset !important; border: 1px solid #cccccc; padding: 3px 10px; background: #ffffff;" type="file" name="anexo_pdf"></label>
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
                <script>$(document).ready(function() { $('.select2').select2();});</script>

</body>
</html>