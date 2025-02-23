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
    $date1 = new DateTime(date('Y-m-d H:i'));
    $date2 = new DateTime($data); // YYYY-MM-DD
    $interval = $date1->diff($date2);
    return $interval->days;
}
?>
<?php

require 'config/conexao.php';

// Recebe o id do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)) {
    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = 'SELECT * FROM tab_habitualidade WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id_cliente);
    $stm->execute();
    $cliente = $stm->fetch(PDO::FETCH_OBJ);

    if(!empty($cliente)) {
        // Formata a data no formato nacional
        $array_data     = explode('-', $cliente->data_nascimento);
        $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

        // Converte os campos para maiúsculas
        $cliente->nome_visitante = strtoupper($cliente->nome_visitante);
        $cliente->evento = strtoupper($cliente->evento);
        $cliente->modalidade = strtoupper($cliente->modalidade);  
        $cliente->classificacao = strtoupper($cliente->classificacao);         
        $cliente->tipo = strtoupper($cliente->tipo);
        $cliente->calibre = strtoupper($cliente->calibre);
    }
}

$datacadastro = date('d-m-Y h:i', time()); 
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

  <!-- PARA adicionar botao +  -->
  <link rel="stylesheet" href="path/to/your/css">
  <style>
    .form-group {
        margin-bottom: 10px;
    }
    .list-item {
        margin-bottom: 5px;
    }
  </style>
    
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">   
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i);

    if (texto.substring(0,1) != saida){
        documento.value += texto.substring(0,1);
    } else {
        documento.value += texto.substring(0,1);
    }
}

function salva(){
    var nome_visitante = document.getElementById("nome_visitante").value;

    if(nome_visitante == ""){alert("Informe o nome do Atirador."); return false;}

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
          <li></li>
        </ul>
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">
          <li class="dropdown">            
            <form method='post' action=""><input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout"></form>
          </li>
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>HABITUALIDADE VISITANTE</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>
      
        <!-------------------------------------------------------------------------------------------------------------- page start-->
        <fieldset>
            <form name="cadastro" action="action_editar_habitualidade_visitante.php" method="post" id='form-contato' enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-2"></div>
                </div>
                <br>

                <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <header class="panel-heading">
                        DADOS DA HABITUALIDADE
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
                                <td>Data de cadastro</td>
                                <td><input type="datetime-local" class="form-control" name="datacadastro" id="datacadastro" value="<?php echo !empty($cliente->datacadastro) ? date('Y-m-d H:i', strtotime($cliente->datacadastro)) : ''; ?>"></td>
                              </tr>
                              <tr>
                                <td>Data e Hora inicial do evento</td>
                                <td><input type="datetime-local" class="form-control" name="data_inicial_visitante" id="data_inicial_visitante" value="<?php echo !empty($cliente->data_inicial_visitante) ? date('Y-m-d H:i', strtotime($cliente->data_inicial_visitante)) : ''; ?>"></td>
                              </tr>
                              <tr>
                                <td>Data e Hora final do evento</td>
                                <td><input type="datetime-local" class="form-control" name="data_final_visitante" id="data_final_visitante" value="<?php echo !empty($cliente->data_final_visitante) ? date('Y-m-d H:i', strtotime($cliente->data_final_visitante)) : ''; ?>"></td>
                              </tr>
                              <tr>                                  
                                <td>CR do Atirador</td>
                                <td><input type="text" class="form-control" id="cr_visitante" name="cr_visitante" value="<?php echo $cliente->cr_visitante ?>"></td>
                              </tr>
                              <tr>
                                <td>Validade do CR</td>
                                <td><input type="date" class="form-control" id="cr_visitante_validade" name="cr_visitante_validade" value="<?php echo $cliente->cr_visitante_validade ?>"></td>
                              </tr>
                              <tr>
                                <td>CPF do Visitante</td>
                                <td><input type="text" class="form-control" name="cpf_visitante" id="cpf_visitante" maxlength="14" value="<?php echo $cliente->cpf_visitante ?>"></td>
                              </tr>
                              <tr>
                                <td>WhatsApp do Visitante</td>
                                <td><input type="text" class="form-control" name="zap_visitante" id="zap_visitante" maxlength="15" value="<?php echo $cliente->zap_visitante ?>"></td>
                              </tr>
                              <tr>
                                <td>Nome do Visitante</td>
                                <td><input type="text" class="form-control" name="nome_visitante" id="nome_visitante" value="<?php echo strtoupper($cliente->nome_visitante) ?>"></td>
                              </tr>
                              <tr>
                                <td>Evento</td>
                                <td><input type="text" class="form-control" name="evento" id="evento" value="<?php echo strtoupper($cliente->evento) ?>"></td>
                              </tr>
                              <tr>
                                <td>Modalidade</td>
                                <td><input type="text" class="form-control" name="modalidade" id="modalidade" value="<?php echo strtoupper($cliente->modalidade) ?>"></td>
                              </tr>
                              <tr>
                                <td>Tipo</td>
                                <td><input type="text" class="form-control" name="tipo" id="tipo" value="<?php echo strtoupper($cliente->tipo) ?>"></td>
                              </tr>
                              <tr>
                                <td>Calibre</td>
                                <td><input type="text" class="form-control" name="calibre" id="calibre" value="<?php echo strtoupper($cliente->calibre) ?>"></td>
                              </tr>
                              <tr>
                                <td>Nº SIGMA</td>
                                <td><input type="text" class="form-control" name="numsigma" id="numsigma" value="<?php echo $cliente->numsigma ?>"></td>
                              </tr>
                              <tr>
                               <td>QTDE Munições</td>
                                <td><input type="text" class="form-control" name="qtdemunicoes" id="qtdemunicoes" value="<?php echo strtoupper($cliente->qtdemunicoes) ?>"></td>
                              </tr>
                              <tr>
                               <td>Pontuação</td>
                                <td><input type="text" class="form-control" name="pontos" id="pontos" value="<?php echo strtoupper($cliente->pontos) ?>"></td>
                              </tr>
                              <tr>
                               <td>Classificação</td>
                                <td><input type="text" class="form-control" name="classificacao" id="classificacao" value="<?php echo strtoupper($cliente->classificacao) ?>"></td>
                              </tr>
                              <tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
                <button type="button" onclick="salva()" class="btn btn-primary">Salvar</button>
                <a href='index.php' class="btn btn-danger">Cancelar</a>
                <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
            </form>
        </fieldset>
        <!-------------------------------------------------------------------------------------------------------------- page end-->
      </section>
    </section>
    <!--main content end-->
  </section>
  <!-- container section end -->

  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="js/scripts.js"></script>

</body>
</html>