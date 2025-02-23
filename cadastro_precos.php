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

require 'config/conexao.php';

// Conectar ao banco de dados
$conexao = conexao::getInstance();

// Consulta SQL para buscar todos os registros da tabela `tab_precos`
$sql = "SELECT id, produto_servico, valor_unitario, data_cadastro FROM tab_precos";
$stm = $conexao->prepare($sql);
$stm->execute();
$precos = $stm->fetchAll(PDO::FETCH_OBJ); // Buscando os dados como objetos

// Lógica de exclusão
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conexao = conexao::getInstance();
    $sql = "DELETE FROM tab_precos WHERE id = :id";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $retorno = $stm->execute();

    if ($retorno) {
        echo "<script>alert('Registro excluído com sucesso!'); window.location='cadastro_precos.php';</script>";
    } else {
        echo "<script>alert('Falha ao excluir registro.'); window.location='cadastro_precos.php';</script>";
    }
}

// Se um id é fornecido na URL, buscar os dados correspondentes
$id = isset($_GET['id']) ? $_GET['id'] : false;
$produto_servico = "";
$valor_unitario = "";

// Se um ID é fornecido, buscar os dados do banco de dados
if ($id) {
    $sql = "SELECT produto_servico, valor_unitario FROM tab_precos WHERE id = :id";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $stm->execute();
    $data = $stm->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        $produto_servico = $data['produto_servico'];
        $valor_unitario = $data['valor_unitario'];
    } else {
        echo "<script>alert('Registro não encontrado.'); window.location='cadastro_precos.php';</script>";
    }
}

if (isset($_POST['but_submit'])) {
    $produto_servico = $_POST['produto_servico'];
    $valor_unitario = $_POST['valor_unitario'];

    if ($id) {
        $sql = "UPDATE tab_precos SET produto_servico=:produto_servico, valor_unitario=:valor_unitario, data_alteracao=:data_alteracao WHERE id = :id";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':produto_servico', $produto_servico);
        $stm->bindValue(':valor_unitario', $valor_unitario);
        $stm->bindValue(':data_alteracao', date('Y-m-d H:i:s'));
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        if ($retorno) {
            header('Location: cadastro_precos.php');
        } else {
            echo "<script>alert('Falha ao alterar registro.'); window.location='cadastro_precos.php';</script>";
        }
    } else {
        $sql = "INSERT INTO tab_precos (produto_servico, valor_unitario, data_cadastro) VALUES (:produto_servico, :valor_unitario, :data_cadastro)";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':produto_servico', $produto_servico);
        $stm->bindValue(':valor_unitario', $valor_unitario);
        $stm->bindValue(':data_cadastro', date('Y-m-d H:i:s'));
        $retorno = $stm->execute();

        if ($retorno) {
            header('Location: cadastro_precos.php');
        } else {
            echo "<script>alert('Falha ao cadastrar registro.'); window.location='cadastro_precos.php';</script>";
        }
    }
}
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
  <script>
    function confirmDelete(id) {
        if (confirm("Tem certeza que deseja excluir este registro?")) {
            window.location.href = '?delete_id=' + id;
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
            <h3 class="page-header"><i class="fa fa-usd" aria-hidden="true"></i>Preços</h3>
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
                  VALORES
                </header>
                <div class="panel-body">
                  <div class="form">
<form action="" method="post" name="filtro_form" id="filtro_form">
    <input type="hidden" name="id" value="<?= ($id) ? $id : ''; ?>">
    <div class="row">
        <div class="col-lg-3">
            <b>Produto | Serviço:</b><br>
            <input type="text" required class="form-control" id="produto_servico" name="produto_servico" placeholder="digite..." value="<?= htmlspecialchars($produto_servico, ENT_QUOTES); ?>">
        </div>
        <div class="col-lg-3">
            <b>R$ Valor Unitário:</b><br>
            <input type="text" required class="form-control" id="valor_unitario" name="valor_unitario" placeholder="R$ ..." value="<?= htmlspecialchars($valor_unitario, ENT_QUOTES); ?>">
        </div>
        <div class="col-lg-3">
            <input type="submit" class="btn btn-info" value="<?= ($id) ? 'Alterar' : 'Salvar'; ?>" name="but_submit">
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
                  LISTAGEM
                </header>
                <div class="panel-body">
                  <div class="adv-table">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Produto | Serviço</th>
                          <th>R$ Valor</th>
                          <th>Data do Registro</th>
                          <th>Ações</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($precos): ?>
                          <?php foreach ($precos as $preco): ?>
                            <tr>
                              <td><?= $preco->produto_servico; ?></td>
                              <td><?= $preco->valor_unitario; ?></td>
                              <td><?= date('d/m/Y', strtotime($preco->data_cadastro)); ?></td>
                              <td>
                                <a href="?id=<?= $preco->id; ?>" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <button class="btn btn-info btn-sm" onclick="confirmDelete(<?= $preco->id; ?>)"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></button>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </fieldset>
        <!-------------------------------------------------------------------------------------------------------------- page end-->
      </section>
    </section>
    <!--main content end-->
  
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
    var produto_servico = document.getElementById("produto_servico").value;
    var valor_unitario = document.getElementById("valor_unitario").value;

    if (produto_servico === "" || valor_unitario === "") {
        alert("Preencha todos os campos obrigatórios.");
        return false;
    }

    document.getElementById('filtro_form').submit();
}
</script>

</body>
</html>