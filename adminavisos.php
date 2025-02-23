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

	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_avisos order by data_aviso';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->data_nascimento);
		$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />	
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
	
    var data_aviso = document.getElementById("data_aviso").value;
	var hora_aviso = document.getElementById("hora_aviso").value;
	var local_aviso = document.getElementById("local_aviso").value;
	var evento_aviso = document.getElementById("evento_aviso").value;
	
    if(data_aviso == ""){alert("Preencha a data do aviso"); return false;}
	if(hora_aviso == ""){alert("Preencha a hora do aviso"); return false;}
	if(local_aviso == ""){alert("Preencha o local do aviso"); return false;}
	if(evento_aviso == ""){alert("Preencha o evento do aviso"); return false;}
	
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
            <h3 class="page-header"><i class="fa fa-quote-left" aria-hidden="true"></i><i class="fa fa-quote-right" aria-hidden="true"></i>Avisos</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-key"></i>Avisos</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Tabela de Avisos
              </header>
              <div class="table-responsive">
			  <?php if(!empty($clientes)):?>
                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th><i class="icon_calendar"></i> Data</th>
                      <th><i class="icon_calendar"></i> Hora</th>
                      <th><i class="icon_profile"></i> Local</th>
                      <th><i class="icon_profile"></i> Evento</th>					  					  					  
                      <th><i class="icon_cogs"></i> Ação</th>
                    </tr>
					<?php foreach($clientes as $cliente):?>
                  </thead>
                  <tbody>
                    <tr>
					  <td><font size="2"><?=$cliente->id?></font></td>
					  <td><font size="2"><?=date('d/m/Y', strtotime($cliente->data_aviso));?></font></td>
					  <td><font size="2"><?=$cliente->hora_aviso?></font></td>
					  <td><font size="2"><?=$cliente->local_aviso?></font></td>
					  <td><font size="2"><?=$cliente->evento_aviso?></font></td>					  					  
					  <td>

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='editar_aviso.php?id=<?=$cliente->id?>' Title="Editar" alt="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
</div>						

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='action_del_avisos.php?id=<?=$cliente->id?>' onclick="return confirm('Confirma excluir registro?');"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
</div>

			  </td>
                    </tr>
                  </tbody>
				  <?php endforeach;?>
                </table>
				<?php else: ?>

				<!-- Mensagem caso não exista clientes ou não encontrado  -->
				<h3 class="text-center text-primary">Nenhum aviso cadastrado!</h3>
			<?php endif; ?>
		</fieldset>
              </div>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">

            </section>
          </div>
        </div>
		
		
		 <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Cadastrar Novo Aviso
              </header>
              <div class="panel-body">
                <div class="form">
				<form action="action_cad_avisos.php" method="post" id='form-contato' enctype='multipart/form-data'>
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Data do aviso</td>
                    <td><input type="date" class="form-control" id="data_aviso" name="data_aviso"></td>
                 </tr>
                  <tr>
                   <td>Hora do aviso</td>
                    <td><input type="text" class="form-control" id="hora_aviso" name="hora_aviso"></td>
                  </tr>
                  <tr>
                   <td>Local do aviso</td>
                    <td><input type="text" class="form-control" id="local_aviso" name="local_aviso"></td>
                  </tr>
                  <tr>
                    <td>Evento do aviso</td>
                    <td><input type="text" class="form-control" id="evento_aviso" name="evento_aviso"></td>
                  </tr>
                  <tr>
                    <td></td>					
                    <td>

				    <input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
                    </fieldset></form>
					
		</td>
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
