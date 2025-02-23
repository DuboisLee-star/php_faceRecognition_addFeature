<?php
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

date_default_timezone_set('America/Sao_Paulo');

session_start();

include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
  header('Location: index.php');
}

// logout
if (isset($_POST['but_logout'])) {
  session_destroy();
  header('Location: index.php');
}

?>
<?php

require 'config/conexao.php';



//dados biometria



$data_inicial = isset($_POST['data_inicial']) ? $_POST['data_inicial'] : false;

$data_final = isset($_POST['data_final']) ? $_POST['data_final'] : false;

$tipo_de_alteracao=  isset($_POST['tipo']) ? $_POST['tipo'] : false;


  // Captura os dados do cliente solicitado
  $conexao = conexao::getInstance();
// Inicializa a query básica
$sql = 'SELECT * FROM tab_logs WHERE 1=1';

// Adiciona filtros de data inicial e final
if (!empty($data_inicial)) {
    $sql .= ' AND data >= :data_inicial';
}
if (!empty($data_final)) {
    $sql .= ' AND data <= :data_final';
}

// Adiciona filtro de tipo_de_alteracao se não for "todos"
if (!empty($tipo_de_alteracao) && $tipo_de_alteracao !== 'todos') {
    $sql .= ' AND tipo_de_alteracao = :tipo_de_alteracao';
}

// Ordena por data (created_at) em ordem descendente
$sql .= ' ORDER BY created_at DESC';

$stm = $conexao->prepare($sql);

// Associa os valores aos parâmetros
if (!empty($data_inicial)) {
    $stm->bindValue(':data_inicial', $data_inicial);
}
if (!empty($data_final)) {
    $stm->bindValue(':data_final', $data_final);
}
if (!empty($tipo_de_alteracao) && $tipo_de_alteracao !== 'todos') {
    $stm->bindValue(':tipo_de_alteracao', $tipo_de_alteracao);
}

// Executa a consulta
$stm->execute();
$logs = $stm->fetchAll(PDO::FETCH_OBJ);




$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);

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
 <style>
  .modal .modal-dialog { width: 30%; }  
  @media(min-width:768px){  
  .modal .modal-dialog { width: 20%; }  
    } 
  </style>
  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Escolha um periodo</h5>
   </button>
  </div>
  <div class="modal-body">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function(){  
  $('.data').on('keyup', function(){
  var $this = $(this);
  var mydate = $this.val();
  mydate = mydate.replace(/\D|\s/, '');  
  mydate = mydate.replace(/^(00)(.*)?/, '01$2');
  mydate = mydate.replace(/^([0-9]{2})(00)(.*)?/, '$101');
  mydate = mydate.replace(/^([3-9])([2-9])(.*)?/, '2$2');
  mydate = mydate.replace(/^(3[01])(02)(.*)?/, '29$2');
  mydate = mydate.replace(/^([0-9]{2})([2-9]|1[3-9])(.*)?/, '$112');
  mydate = mydate.replace(/^([0-9]{2})([0-9]{2})([0-9].*?)/, '$1/$2/$3');
  mydate = mydate.replace(/^([0-9]{2})([0-9])/, '$1/$2');    
  //ano bissexto
  var day = mydate.substr(0,2) || '01';
  var month = mydate.substr(3,2) || '01';
  var year = mydate.substr(6,4);
  if(year.length == 4 && day == '29' && month == '02' && (year % 4 != 0 || (year.substr(2,2) == '00' && year % 400 != 0))) {
  mydate = mydate.replace(/^29/,'28');
  }
  mydate = mydate.substr(0,10);
  $this.val(mydate);
  })
  })
  </script>
  
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
  <input type="submit"  onclick="document.getElementById('form').submit();" class="btn btn-primary" value="Gerar">
  </div>
  </div>
  </div>
  </div>
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
  <style>
    .gd-dropdown-menu .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.25rem 1.5rem;
      clear: both;
      font-weight: 400;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
    }
    .gd-dropdown-menu .dropdown-item:hover {
      color: #16181b;
      text-decoration: none;
      background-color: #f8f9fa;
    }
  </style>
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
    <?php include 'menu_lateral_esq.php'; ?>
    <!-- menu lateral fim -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i>Logs</h3>
          
          </div>
        </div>

        <form method="POST" action="">
        <div class="form-group">
            <label for="datainicial">Data Inicial</label>
            <input type="date" class="form-control" id="datainicial" onchange="updateLinks()">
        </div>
        <div class="form-group">
            <label for="datafinal">Data Final</label>
            <input type="date" class="form-control" id="datafinal" onchange="updateLinks()">
            
            
        </div>   
           <div class="form-group">
            <label for="tipo">Tipo</label>
            <select class="form-control" name='tipo'>
                <option value="">Todos</option>
             
                    <option value="criacao">Criação</option>    
                    <option value="edicao">Edição</option>    
                    <option value="exclusao">Exclusão</option> 
                    <option value="transferencia">Transferencia</option>   
           
            </select>
            
        </div> 
        <button class="btn btn-info">Buscar</button>
        </form>
        
    <hr>
    
    
    <table class="table">
  <thead>
    <tr>
      <th scope="col" style="width: 100px;">Data</th>      
      <th scope="col" style="width: 100px;">Usuário</th>
      <th scope="col" style="width: 120px;">Tabela</th>
      <th scope="col" style="width: 100px;">Tipo</th>
      <th scope="col" style="width: 350px;">Log</th>

    </tr>
  </thead>
  <tbody>
      <?php foreach($logs as $log){
      

      
      ?>
    <tr>
      <td style="width: 100px;"><?= date('d/m/Y', strtotime($log->created_at)) ?></td>      
      <td style="width: 100px;"><?= $log->username ?></td>
      <td style="width: 120px;"><?= $log->tabela ?></td>
      <td style="width: 100px;"><?= $log->tipo_de_alteracao ?></td>
      <td style="width: 350px;"><?= $log->registro ?></td>

    </tr>
    
    <?php }?>
</table>
        

    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $datainicial = isset($_POST['datainicial']) ? $_POST['datainicial'] : '';
    $datafinal = isset($_POST['datafinal']) ? $_POST['datafinal'] : '';

}
 echo $datainicial;
?>
     
        <!-------------------------------------------------------------------------------------------------------------- page start-->
     
      
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



  
  <style>
    label.custom-control-label {
      font-size: 11px;
      font-family: sans-serif;
    }

    th,
    td {
      /*white-space: nowrap;*/
    }
  </style>
</body>
</html>