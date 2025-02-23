<?php

include "config/config.php";
require 'config/conexao.php';

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
	$conexao = conexao::getInstance();
	$sql = 'SELECT 	*	FROM tab_membros ';
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


</head>
<style>
    #paginacao {
    position: absolute;
    bottom: -69px;
}
.novoFinanceiro{
    width:26% !important;
}
</style>
<body>
  <!-- container section start -->
  <section id="container" class="">
    <!--header start-->
    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Menu Navega&ccedil;&atilde;o" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
            <form class="navbar-form" method="get" id='form-contato' action="" >
            <input class="form-control" placeholder="Search" type="text" for="termo" id="search_text" name="search_text">
		    
            </form>
          </li>
        </ul>
        <!-- ajax search --> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
        <script>
        $(document).ready(function(){
        
        load_data();
        
        function load_data(query)
        {
        $.ajax({
        url:"ajax_p_financeiro.php",
        method:"POST",
        data:{query:query},
        success:function(data)
        {
        $('#result').html(data);
        }
        });
        }
        $('#search_text').keyup(function(){
        var search = $('#search_text').val();
        if(search != '')
        {
        load_data(search);
        }
        else
        {
        load_data();
        }
        });
        });
        </script>
        <!--  search form end -->
      </div>
      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">

          <!-- user login dropdown start-->
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
            <h3 class="page-header"><i class="fa fa-money" aria-hidden="true"></i>Financeiro</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-table"></i>Tabela</li>
            </ol>
          </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
                <div class="row">
                    <div class="col-md-3">
                    
                     <a href="novo_financeiro.php" class="btn btn-primary mb-2" style="margin:5px;" >Novo financeiro</a>
                    </div>
                </div>
                
               
              <header class="panel-heading">
             
                Tabela Financeira dos Atiradores
              </header>
              <div id="result" class="table-responsive">
              </div>
              <?php
           
              ?>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">

            </section>
          </div>
        </div>
        <!-- page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">Design by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br><br>
        </div>
    </div>
  </section>
  <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="novoFinanceiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog novoFinanceiro" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Novo Financeiro</h5>
       
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="action_financeiro.php" method="post">
            <input type="hidden" name="acao" value="incluir">
            <label for="membro">Membro/Cliente:</label>
            <select name="id_membro" class="form-control  mb-2 id_membro" id="id_membro" style="width: 300px;">
                        <option value=""></option>
                        <?php foreach($clientes as $cliente){?>
                        <option value="<?=$cliente->id?>" ><?=$cliente->nome?></option>
                        <?php }?>
                        
                        
                    </select>
                        <label for="valor_mensalidade">Valor:</label>
                    <input type="number" class="form-control mb-2" id="valor_mensalidade" name="valor" style="width: 300px;" placeholder="Valor" value="">
                    <label for="data_pgto_mensalidade">Data de pagamento:</label>
                    <input type="date" class="form-control  mb-2" id="data_pgto_mensalidade" style="width: 300px;" name="data" value="<?=$financeiro->data_pgto?>">
                    <label for="forma_pgto_mensalidade">Forma de pagamento:</label>
                    <select name="forma_pgto" id="forma_pgto_mensalidade	" class="form-control  mb-2" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($financeiro->forma_pgto	 === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($financeiro->forma_pgto	 === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($financeiro->forma_pgto	 === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($financeiro->forma_pgto	 === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                     <option value="Crediario"<?php if ($financeiro->forma_pgto	 === 'Crediario') echo ' selected'; ?>>Crediario</option>
                    <option value="Transferência"<?php if ($financeiro->forma_pgto	 === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
                    <label for="">Número de parcelas:</label>
                       <select name="n_parcela" class="form-control mb-2" style="width: 300px;">
                        <option value="1">1</option>
                        <?php for($i=2;$i<13;$i++){?>
                        <option value="<?=$i?>"><?=$i?></option>
                        <?php }?>
                        
                    </select>
                      <label for="">Status do pagamento:</label>
                    <select name="status_pgto" class="form-control  mb-2" style="width: 300px;">
                        <option value="">Status Pgto</option>
                        <option value="pendente" <?php if($financeiro->status_pgto == 'pendente') echo 'selected';?>>Pendente</option>
                        <option value="pago" <?php if($financeiro->status_pgto == 'pago') echo 'selected';?>>Pago</option>
                        
                    </select>
                      <label for="">Observação:</label>
					<textarea class="form-control  mb-2" rows="3" name="obs" placeholder="Observação" id="obs" style="width: 300px;"><?php echo $financeiro->obs ?></textarea></td>
                   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
  <!-- container section end -->
  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nicescroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>

</body>
</html>