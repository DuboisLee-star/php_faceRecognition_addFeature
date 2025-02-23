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

// Recebe o termo de pesquisa se existir
$id = (isset($_GET['id'])) ? $_GET['id'] : false;

// lista os texto já criados
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_editortexto order by id ASC';
$stm = $conexao->prepare($sql);
$stm->execute();
$textos = $stm->fetchAll(PDO::FETCH_OBJ);

// exibe os dados do texto a ser gerado
if($id):
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_editortexto WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id);
	$stm->execute();
	$textos_edita = $stm->fetch(PDO::FETCH_OBJ);
endif;

$editor_id = isset($textos_edita->id) ? $textos_edita->id : '';
$editor_titulo = isset($textos_edita->titulo) ? $textos_edita->titulo : '';
$editor_texto = isset($textos_edita->texto) ? base64_decode($textos_edita->texto) : '';

// lista os atiradores
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_membros order by nome ASC';
$stm = $conexao->prepare($sql);
$stm->execute();
$clientes = $stm->fetchAll(PDO::FETCH_OBJ);
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
	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<style>.tox-notifications-container{display: none !important;}</style>

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
	
    var name = document.getElementById("name").value;
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;	
	
    if(name == ""){alert("Preencha o campo Nome."); return false;}
	if(username == ""){alert("Preencha o campo usuario."); return false;}
	if(password == ""){alert("Digite uma senha."); return false;}
	
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
            <h3 class="page-header"><i class="fa fa-users" aria-hidden="true"></i>Editor Texto</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-table"></i>Editor Texto</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Tabela de Texto
              </header>
              <div class="table-responsive">
			  <?php if(!empty($textos)):?>
                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th><i class="icon_profile"></i> Título</th>
                      <th><i class="icon_cogs"></i> Ação</th>
                    </tr>
					<?php foreach($textos as $Texto):?>
                  </thead>
                  <tbody>
                    <tr>
					  <td><font size="2"><?=$Texto->id?></font></td>
					  <td><font size="2"><?=$Texto->titulo?></font></td>
					  <td>

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='editortexto.php?id=<?=$Texto->id?>' Title="Editar" alt="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
</div>
<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='action_editortexto.php?del&id=<?=$Texto->id?>' onclick="return confirm('Confirma excluir registro?');"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
</div>



					  </td>
                    </tr>
                  </tbody>
				  <?php endforeach;?>
                </table>
				<?php else: ?>

				<!-- Mensagem caso não exista clientes ou não encontrado  -->
				<h3 class="text-center text-primary">Não existem textos cadastrados!</h3>
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
                Cadastrar Novo Texto
              </header>
              <div class="panel-body">
                <div class="form">
				<form action="action_editortexto.php" method="post" id='form-contato' enctype='multipart/form-data' target="_blank">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Cliente</td>
                    <td>
						<select class="form-control" name="atirador" id="atirador">
							<option value=""></option>
							<?php if($clientes): ?>
								<?php foreach($clientes as $Atirador): ?>
									<option value="<?= $Atirador->matricula; ?>"><?= $Atirador->nome.' - '.$Atirador->matricula; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</td>
                 </tr>
                  <tr>
                   <td>Título</td>
                    <td><input type="text" class="form-control" id="titulo" name="titulo" value="<?= $editor_titulo; ?>"></td>
                  </tr>
                  <tr>
                    <td>Texto</td>
                    <td><textarea id="texto" name="texto" placeholder="Exemplo de uso de parâmetro: Nome do Atirador é {nome} e o seu  CPF é {cpf} "><?= $editor_texto; ?></textarea></td>
                  </tr>
				  <tr>
                    <td>Parâmetros:</td>
                    <td>
					{matricula} - Nº da matricula do atirador<br>
					{nome} - Nome do atirador<br>
					{cpf} - CPF do atirador<br>
					{cr} - Nº do CR  do atirador<br>
					</td>
                  </tr>
				  <tr>
                    <td></td>
                    <td align="center">
					<input type="hidden" name="acao" id="acao" value="<?= isset($_GET['id']) ? 'editar' : 'incluir'; ?>">
					<input type="hidden" name="id" id="id" value="<?= $editor_id; ?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return gerarPDF()">Gerar PDF</button>
				</td>
                  </tr>
                </tbody>
              </table>
			  </form>
            </section>
          </div>
        </div>		
		
        <!------------------------------------------------------------------------------------------------------------ page end-->
		
		
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
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
  <script src="https://cdn.tiny.cloud/1/pliwnw2gq6c04s6fi0e0stl2d4jonheij1r76skse5vcwpb8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
  <script>
  tinymce.init({
        selector: '#texto',
        plugins: [
          'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
          'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
          'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
      });
	 const gerarPDF = () => {
			
		$("#form-contato").submit();
		
		setTimeout(() => {
			window.location=window.location.href;
		}, 2000);

	 }
  </script>
</body>
</html>