<?php

include "config/ajax_p_menus.php";
include_once("config/url_painel.php");
require 'config/conexao.php';

// Check user login or not
// if(!isset($_SESSION['uname'])){
//     header('Location: index.php');
// }

// logout
// if(isset($_POST['but_logout'])){
//     session_destroy();
//     header('Location: index.php');
// }
$conexao = conexao::getInstance();

$itens_por_pagina = 50; // Número de registros por página

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itens_por_pagina;


    $query = "
        SELECT * FROM tab_habitualidade
        WHERE tipo_atirador LIKE '%2%' 
        ORDER BY datacadastro DESC
        LIMIT $offset, $itens_por_pagina
    ";
$stm = $conexao->prepare($query);
$stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);

$total_query = "SELECT count(*) as total FROM tab_habitualidade
        WHERE tipo_atirador LIKE '%2%'";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_items = $total_row['total'];

// Calcular o número total de páginas
$total_pages = ceil($total_items / $itens_por_pagina);




function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;
	
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
            
            <input class="form-control" placeholder="Buscar por: nome, rua, cidade e profissao" type="text" for="termo" id="search_text" name="search_text">
		   
            
          </li>
        </ul> 
        <!-- ajax search --> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>
        $(document).ready(function(){
        
        //load_data();
        
        function load_data(query)
        {
        $.ajax({
        url:"ajax_p_visitantes.php",
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Visitantes</h3>
            
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
                  <div id="resulttable" class="table-responsive">
        <table class="table">
        <thead>
        <tr>
        <th><i class="icon_profile"></i> Data</th> 
        <th><i class="icon_profile"></i> Nome do Visitante</th>
        <th><i class="icon_profile"></i> Armamento</th>
        <th><i class="icon_profile"></i> Evento</th>  
        <th><i class="icon_cogs"></i> Ação</th>
        </tr>
        </thead>
        <tbody>
            <?php  foreach ($result as $row){?>
                <tr>
            <td><?php echo date('d/m/Y', strtotime($row[datacadastro])) ?></td>
            <td><?php echo "$row[nome_visitante]";?></td>
            <?php
                $tip=$row[tipo];
                $calib=$row[calibre];
                $numsig=$row[numsigma];
                $tipo_arr= explode(',',$tip);
                $calib_arr=explode(',',$calib);
                $numsig_arr=explode(',', $numsig);
                $array_armas=["tipo"=>$tipo_arr, "calibre"=>$calib_arr, "numsigma"=>$numsig_arr];
                
                // echo "<pre>".print_r($array_armas)."</pre>";
                // echo "<pre>".var_dump($array_armas[calibre])."</pre>";
            
                ?>
            <td><?php   foreach($array_armas[tipo] as $key=> $val_tipo){
                  
                  echo $val_tipo." | ".$array_armas[calibre][$key]." | ".$array_armas[numsigma][$key]."<br>";
            
              }?></td>   
            <td><?php echo $row[evento]; ?></td>
            <td>   
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="editar_habitualidade_visitante.php?id=<?php echo $row[id]?>" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="documentos_visitantes.php?id=<?php echo $row[id]?>" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante.php?id=<?php echo $row[id]?>" Title="Dec. Habitualidade" alt="Dec. Habitualidade" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;HBTL</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/modalidade_visitante.php?id=<?php echo $row[id]?>" Title="Mod. Visitante" alt="Mod. Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;MDV</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante2.php?id=<?php echo $row[id]?>" Title="Visitante" alt="Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;VST</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="imagem.php?id=<?php echo $row[id]?>" Title="CERT" alt="CERT" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;CERT</i></a>
                </div>
                
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return delUsers(<?php echo $row[id]?>);" data-confirm="Excluir Habitualidade"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                </div>
            </td>
            </tr>
            <?php }?>
            </tbody></table>
            </div>
            </div>
 <!-- Pagination -->
        <div class="text-center">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <!-- Link para a página anterior -->
              <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                <a class="page-link " href="<?php if($page <= 1) {echo "";}else{echo "?page=".$page - 1;} ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>

              <!-- Links das páginas numeradas -->
              <?php for($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php if($i == $page){ echo 'active'; } ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
              <?php endfor; ?>

              <!-- Link para a próxima página -->
              <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                <a class="page-link" href="<?php if($page>= $total_pages) {echo "";}else{echo "?page=".$page + 1;} ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav>
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
            window.location="action_del_habitualidade_visitante.php?id="+id_usuario;
            return true;
          }
          return false;
      }
  </script>
</body>
</html>