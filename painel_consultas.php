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
include_once ("config/url_painel.php");

include "config/ajax_p_menus.php";
  $query = "SELECT * FROM tab_membros ORDER BY matricula";  
 $result = mysqli_query($connect, $query);  


     
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
            
            <input class="form-control" placeholder="matricula, nome, marca, modelo, serie e sigma " type="text" for="termo" id="search_text" name="search_text">
		   
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
        url:"ajax_p_consultas.php",
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>CONSULTAR ARMAMENTO</h3>

	<?php include 'barra_contagem_membros.php';?>
	
          </div>
        </div>
        <!-- page start-->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Tabela de Atiradores
              
                 </header>
              <div id="result">
              <div id="employee_table" class="table-responsive">
              <table class="table">
              <thead>
              <tr>
              <th><i class="fa fa-camera"></i> Foto</th>
              <th><i class="fa fa-laptop" id="matricula" data-order="desc"></i> Matrícula</th>  
              <th><i class="icon_profile column_sort" id="nome" data-order="desc"></i> Nome completo</th>
              
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
             $output .=  '<tr>
              <td><img src="'.URL_painel.'/fotos/'.$row["foto"].'" height="30" width="30"></td>
              <td>'.$row["matricula"].'</td>
               <td>'.$row["nome"].'</td>	
              <td>'.$row["marca"].'</td>              
              <td>'.$row["modelo"].'</td>	
              <td>'.$row["calibre"].'</td>	              
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
      <div class="credits">Design by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br><br>
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