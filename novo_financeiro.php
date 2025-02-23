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





	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = '
		SELECT 
	*
		FROM 
			
			tab_financeiro_2 
		WHERE 
			id = :id
		';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_financeiro);
	$stm->execute();
	
	$financeiro = $stm->fetch(PDO::FETCH_OBJ);
	
	
			$sql3 = '
		SELECT 
			m.nome,
			f.*,
			m.id,
			m.matricula,
			m.plano_pgto
		FROM 
			tab_membros m
				LEFT JOIN tab_financeiro f on f.matricula = m.matricula
		WHERE 
			m.id = :id
		';
	$stm = $conexao->prepare($sql3);
	$stm->bindValue(':id', $financeiro->id_membro);
	$stm->execute();
	
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
		$sql2 = '
		SELECT 
		*
		FROM 
			info_clube 
	
		';
	$stm = $conexao->prepare($sql2);

	$stm->execute();
	$clube = $stm->fetch(PDO::FETCH_OBJ);

	if(!empty($cliente)):

	endif;


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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
         
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
           
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

		
			<form action="action_financeiro.php" method="post" id='form-contato' enctype='multipart/form-data'>
		
		    
		
		            <!--collapse start-->
          
		
		            <!--collapse start-->
            <div class="panel-group m-bot20" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                   <i class="icon_calendar"></i>&nbsp;Novo Financeiro
                    </a>
                    </h4>
                    </div>
                    <div id="collapseTwos">
                    <div class="panel-body">
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th></th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                 
                 
                  <tr>
                    <td>Novo Financeiro</td>
                    <td>
                     <form action="action_financeiro.php" method="post">
                         
            <input type="hidden" name="acao" value="incluir">
            <input type="hidden" name="matricula" value="<?=$cliente->matricula?>">
            <input type='hidden' id="valor_anual"  value="<?=$clube->valor_plano_fixo_anual?>">
            <input type='hidden' id="valor_mensal"  value="<?=$clube->valor_plano_fixo_mensal?>">
            <label for="membro">Membro/Cliente:</label><br>
            <select name="id_membro" class="form-control  mb-2 id_membro" id="id_membro" style="width: 300px;">
                        <option value=""></option>
                        <?php foreach($clientes as $cliente){?>
                        <option value="<?=$cliente->id?>" data-plano="<?=$cliente->plano_pgto?>" ><?=$cliente->nome?></option>
                       
                        <?php }?>
                        
                        
                    </select><br>
                        <label for="valor_mensalidade">Valor:</label>
                        
                    <input type="number" class="form-control mb-2" id="valor" name="valor" style="width: 300px;" placeholder="" value="">
                 
                    <label for="data_pgto_mensalidade">Data de pagamento:</label>
                    <input type="date" class="form-control  mb-2" id="data_pgto_mensalidade" style="width: 300px;" name="data" value="<?=$financeiro->data_pgto?>">
                    <label for="forma_pgto_mensalidade">Forma de pagamento:</label>
                    <select name="forma_pgto" id="forma_pgto_mensalidade	" class="form-control  mb-2" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($financeiro->forma_pgto	 === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($financeiro->forma_pgto	 === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($financeiro->forma_pgto	 === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($financeiro->forma_pgto	 === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
      
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
				
           
                    </td>
                  </tr>

                 
                </tbody>
              </table>
				   <div class="form-group">
                   </div></div></div></div></div>
                   
          
		

				<button type="submit" class="btn btn-primary" id='botao'>Salvar</button>
				

                </form>

		
		</fieldset>
    <!--main content end-->
		
        <!----------------------------------------------------------------------------------------------- page end-->
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
    <script>$(document).ready(function() {
    $('.id_membro').select2();
});
$(document).ready(function(){
   $('#id_membro').change(function(){
     
            // Obter o option selecionado
            const optionSelecionada = $(this).find(':selected');

            // Acessar os atributos data-*
            const plano = optionSelecionada.data('plano'); // Usando .data()
           if(plano == 'A'){
               $("#valor").val(<?=$clube->valor_plano_fixo_anual?>);
           }else{
               $("#valor").val(<?=$clube->valor_plano_fixo_mensal?>);
           }

   }) 
});

</script>


</body>

</html>