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

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
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
	
	var novo_rua = document.getElementById("novo_rua").value;
	var novo_num = document.getElementById("novo_num").value;
	var novo_bairro = document.getElementById("novo_bairro").value;
	var novo_cep = document.getElementById("novo_cep").value;
	var novo_cidade = document.getElementById("novo_cidade").value;
	var novo_estado = document.getElementById("novo_estado").value;
	var novo_obs = document.getElementById("novo_obs").value;
	
	if(novo_rua == ""){alert("Preencha o campo Rua."); return false;}
	if(novo_num == ""){alert("Preencha o campo Num."); return false;}
	if(novo_bairro == ""){alert("Preencha o campo Bairro."); return false;}
	if(novo_cep == ""){alert("Preencha o campo CEP."); return false;}
	if(novo_cidade == ""){alert("Preencha o campo Cidade."); return false;}
	if(novo_estado == ""){alert("Preencha o campo Estado."); return false;}
	if(novo_obs == ""){alert("Preencha o campo OBS."); return false;}
	
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

    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa fa-bars"></i> <?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->

<fieldset>

		<legend></legend>
			
			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
				<form action="action_mudarendereco.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
						
				      	<div class="col-md-2">
						    <a href="#" class="thumbnail">
						      <img src="fotos/<?=$cliente->foto?>" height="190" width="150" id="foto-cliente">
						    </a>
					  	</div>
				  	</div><br>

 <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados do Atirador
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
                    <td>N&uacute;mero do CR</td>
                    <td><input type="text" class="form-control" id="cr" name="cr" value="<?=$cliente->cr?>"></td>
                 </tr>
                  <tr>
                   <td>Data validade do CR</td>
                    <td><input type="date" class="form-control" id="validade_cr" name="validade_cr" value="<?=$cliente->validade_cr?>"></td>
                  </tr>
                  <tr>
                    <td>Nome</td>
                    <td><input type="text" class="form-control" id="nome" name="nome" value="<?=$cliente->nome?>" placeholder="Infome o Nome do Atirador"></td>
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
                Dados do Novo Endere&ccedil;o
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
                    <td>Nome da rua</td>
                    <td><input type="text" class="form-control" id="novo_rua" maxlength="150" name="novo_rua" value="<?=$cliente->novo_rua?>"></td>
                 </tr>
                  <tr>
                   <td>N&uacute;mero</td>
                    <td><input type="text" class="form-control" id="novo_num" maxlength="10" name="novo_num" value="<?=$cliente->novo_num?>"></td>
                  </tr>
                  <tr>
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="novo_bairro" maxlength="100" name="novo_bairro" value="<?=$cliente->novo_bairro?>"></td>
                  </tr>
                  <tr>
                   <td>CEP</td>
                    <td><input type="text" class="form-control" id="novo_cep" maxlength="14" name="novo_cep" value="<?=$cliente->novo_cep?>"></td>
                  </tr>
                  <tr>
                   <td>Cidade</td>
                    <td><input type="text" class="form-control" id="novo_cidade" maxlength="100" name="novo_cidade" value="<?=$cliente->novo_cidade?>"></td>
                  </tr>
                  <tr>
				  <td>Estado</td>
                    <td><input type="text" class="form-control" id="novo_estado" maxlength="2" name="novo_estado" value="<?=$cliente->novo_estado?>"></td>
                  </tr>
                  <tr>
                    <td>Observa&ccedil;&otilde;es</td>
                    <td><input type="text" class="form-control" id="novo_obs" name="novo_obs" value="<?=$cliente->novo_obs?>")"></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>
		
				    <input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "editar";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
                    </fieldset></form>
							

			<?php endif; ?> 
		</fieldset>
		   
		   
		   
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


</body>

</html>
