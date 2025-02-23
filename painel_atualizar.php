<?php

include "config/config.php";

include_once ("config/url_painel.php");

include "config/ajax_p_menus.php";

$query = "SELECT a.*, (SELECT count(id) qtde_pendente FROM tab_habitualidade WHERE IFNULL(aprovado,0) = 0 AND matricula = a.matricula) habitualidade_pendente FROM tab_membros a WHERE a.bloqueio LIKE '%Nao%' ORDER BY a.matricula";  
$result = mysqli_query($connect, $query);  


$query = "SELECT count(id) qtde_pendente FROM tab_habitualidade WHERE IFNULL(aprovado,0) = 0 ";  
$result_pendente = mysqli_query($connect, $query);
$qtde_pendente = mysqli_num_rows($result_pendente);

$query = "SELECT * FROM info_clube WHERE id = 1";  
$result_clube = mysqli_query($connect, $query);
$clube = mysqli_fetch_assoc($result_clube);
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
  <!--<link href="css/font-awesome.min.css" rel="stylesheet" />-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />


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
        <div class="icon-reorder tooltips" data-original-title="Menu Navega&ccedil;&atilde;o" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel_atualizar.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
            
            <input class="form-control" placeholder="matricula, nome, plano" type="text" for="termo" id="search_text" name="search_text">
		   
            
          </li>
        </ul> 
        <!-- ajax search --> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>  
        $(document).ready(function(){  
        $(document).on('click', '.column_sort', function(){  
        var column_name = $(this).attr("id");  
        var order = $(this).data("order");  
        var arrow = '';  
        //glyphicon glyphicon-arrow-up  
        //glyphicon glyphicon-arrow-down  
        
        $.ajax({  
        url:"filtro/filtroback.php",  
        method:"POST",  
        data:{column_name:column_name, order:order},  
        success:function(data)  
        {  
        $('#employee_table').html(data);  
        $('#'+column_name+'').append(arrow);  
        }  
        })  
        });  
        });  
        </script>  
        <script>
        $(document).ready(function(){
        
      
        
        function load_data(query)
        {
        $.ajax({
        url:"ajax_p_painel_atualizar.php",
        method:"POST",
        data:{query:query},
        success:function(data)
        {
        $('#result').html(data);
        }
        });
        }
        $('#search_text').keyup(function(){
        var search = $(this).val();
        load_data(search);
       
       
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

          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->

    <!-- menu lateral inicio -->
	

	
     <!-- menu lateral fim -->
  
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div   class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>ATUALIZAÇÃO CADASTRAL</h3>
            <ol class="breadcrumb">
            </ol></font>
          </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Tabela de Atiradores
              
                 </header>
                 
                 <?php if($qtde_pendente > 0 && (int)$clube['tipo_habitualidade'] > 1): ?><div style="background: #fffacc; color: #af8100; padding: 10px; margin: 10px;">Preencha os dados com atenção!</div><?php endif; ?>
                 
              <div id="result">
              <div id="employee_table" class="table-responsive">
              <table class="table">
              <thead>
              <tr>
              <th><i class="icon-file-text column_sort" id="matricula" data-order="desc"></i> Matricula</th>			
              <th><i class="icon_profile column_sort" id="nome" data-order="desc"></i> Nome completo</th>
               <th><i class="icon_cogs "></i> A&ccedil;&atilde;o</th>
              </tr>
              </thead>
              <?php ?>
              <?php  
              while($row = mysqli_fetch_array($result))  
              { 
              $diasr = "dias";  
             
              $renova = $row["data_renovacao"]; 
              $daydiff=floor((abs(strtotime(date("Y-m-d")) - strtotime($row["data_renovacao"]))/(60*60*24)));
              $ano = 365;
              $vencido = $daydiff-$ano; 
              if ( $daydiff < 1) { $daydiff = "venceu hoje"; } 
              else if ( $daydiff > 1000 ) { $daydiff= "data de renovacao ausente"; }
             
              else if ($renova < date('Y-m-d')) {$daydiff= "vencido há {$daydiff} dias";}
              else if ($renova > date('Y-m-d') ) {$daydiff= "{$daydiff} dias restantes";}
              else if ($renova = date('Y-m-d') ) {$daydiff= "{$daydiff} dias restantes";}
              
              else { $daydiff= "{$daydiff} dias restantes"; }
              
               $foto = URL_painel.'/img/padrao.png';
               if(strlen(trim($row["foto"])) > 0) $foto = URL_painel.'/fotos/'.$row["foto"];
 
             $output .=  '<tr>

              <td>'.$row["matricula"].'</td>
              <td>'.$row["nome"].'</td>	              
          					 
              <td>
              
              <div class="btn-group btn-group-sm">
              <a class="btn btn-info btn-sm" href="atualizar.php?id='.$row["id"].'" Title="Editar" alt="Editar">ATUALIZAR</a>
              </div>
              
              
              </td>
              </tr>';
             }
             echo $output;
             
             ?>
              </table>
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
        <!-- page end-->
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
          Design by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br><br>
        </div>
    </div>
  </section>
  
  <!-- container section end -->
  <!-- javascripts -->
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nicescroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  
      <script>
      const delUsers = (id_usuario) => {
          var r = confirm("Confirma excluir registro?");
          if(r){
            window.location="action_del_users.php?id="+id_usuario;
            return true;
          }
          return false;
      }
  </script>


</body>

</html>