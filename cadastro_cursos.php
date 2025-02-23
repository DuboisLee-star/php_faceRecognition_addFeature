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

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;

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
	$sql = 'SELECT * FROM tab_cursos WHERE id = :id';
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>   

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
	
    var nome = document.getElementById("nome").value;
	var cpf = document.getElementById("cpf").value;
	var telefone = document.getElementById("telefone").value;
	var email = document.getElementById("email").value;
	var curso = document.getElementById("curso").value;
	
    if(nome == ""){alert("Preencha o campo Nome."); return false;}
	if(cpf == ""){alert("Preencha o campo CPF."); return false;}
	if(telefone == ""){alert("Preencha o campo Whatsapp."); return false;}
	if(email == ""){alert("Preencha o campo email."); return false;}
	if(curso == ""){alert("Preencha o campo curso."); return false;}
	
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Cadastro</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i><a href="painel_cursos.php">Lista de Cursos</a></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				<form action="action_cadastro_cursos.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
					<label for="nome">Selecionar Foto</label>
			      	<div class="col-md-2">
					    <a href="#" class="thumbnail">
					      <img src="fotoscursos/padrao.png" height="190" width="150" id="foto-cliente">
					    </a>
				  	</div>
				  	<input type="file" name="foto" id="foto" value="foto" >
			  	</div>
			<br>
				
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados do Participante
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
                    <td>Nome</td>
                    <td><input type="text" class="form-control" id="nome" name="nome" value=""></td>
                 </tr>
                  <tr>
                   <td>C.P.F</td>
                    <td><input type="text" class="form-control" id="cpf" name="cpf" value=""></td>
                  </tr>
                  <tr>
                    <td>Whatsapp</td>
                    <td><input type="text" class="form-control" id="telefone" name="telefone" value=""></td>
                 </tr>
                  <tr>
                    <td>Email</td>
                    <td><input type="email" class="form-control" id="email" name="email" value=""></td>
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
                Dados do Curso
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
                    <td>Data Inicial do Curso</td>
                    <td><input type="date" class="form-control" id="data_inicial_do_curso" name="data_inicial_do_curso" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                    </tr>
                    <tr>
                    <td>Data Final do Curso</td>
                    <td><input type="date" class="form-control" id="data_final_do_curso" name="data_final_do_curso" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                    </tr>                    
                    <tr>
                    <td>Carga Horária do Curso</td>
                    <td><input type="text" class="form-control" id="carga_horaria_curso" name="carga_horaria_curso" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                    </tr>                    
                  <tr>
                    <td>Escolha um curso</td>
                    <td>
                        
<div class="form-check form-switch">
<input class="form-check-input" type="radio" id="curso" name="curso" value="IAT - Instrutor de Armamento e Tiro">IAT - Instrutor de Armamento e Tiro<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="RI - Reação Iminente">RI - Reação Iminente<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Saque Rápido">Saque Rápido<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Caça Metálica">Caça Metálica<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Tiro Defensivo de Precisão">Tiro Defensivo de Precisão<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Porte de Arma - Legislação e Postura">Porte de Arma - Legislação e Postura<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Porte de Arma - Legislação, Postura, Equipamento e Combate">Porte de Arma - Legislação, Postura, Equipamento e Combate<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Tiro de Precisão">Tiro de Precisão<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Combate de Porte Velado">Combate de Porte Velado<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Defesa do Lar">Defesa do Lar<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Operador de Pistola">Operador de Pistola<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Operador de Arma Curta - Pistola e Revólver">Operador de Arma Curta - Pistola e Revólver<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Treinamento - Calibre 12">Treinamento - Calibre 12<br>
<input class="form-check-input" type="radio" id="curso" name="curso" value="Treinamento - Fuzil e Pistola">Treinamento - Fuzil e Pistola<br>
</div>                        
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		
				   <?php include_once ("config/url_hidden_forms.php"); ?>
			  
<input type="hidden" class="form-control" id="status" name="status" value="Em An&aacute;lise">	
<input type="hidden" class="form-control" id="motivo" name="motivo" value="Novo Cadastrado em Curso de Tiro">	
			  
				    <input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
	


                </form>
              	</fieldset>					  

    <!--main content end-->
		
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
  <!--<script type="text/javascript" src="js/custom.js"></script>-->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>


</body>

</html>
