<?php

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

include_once("config/url_painel.php");

include "config/ajax_p_menus.php";

// Defina o número de itens por página
$items_per_page = 50;

// Obtenha a página atual a partir da URL, ou defina-a como 1 se não estiver definida
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Ajuste a consulta para incluir a paginação
$query = "SELECT a.*, aut.status_pgto AS assinatura_digital, 
(SELECT count(id) qtde_pendente 
FROM tab_habitualidade 
WHERE IFNULL(aprovado, 0) = 0 
AND matricula = a.matricula) habitualidade_pendente 
FROM tab_membros a 
LEFT JOIN tab_autentique_membros aut ON aut.membro_id = a.id
WHERE a.bloqueio 
LIKE '%Nao%' 
ORDER BY a.matricula 
LIMIT $offset, $items_per_page";

$result = mysqli_query($connect, $query);


// Consulta para contar o número total de registros
$total_query = "SELECT COUNT(*) as total FROM tab_membros WHERE bloqueio LIKE '%Nao%'";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_items = $total_row['total'];

// Calcular o número total de páginas
$total_pages = ceil($total_items / $items_per_page);

$query = "
    SELECT 
        h.id
    FROM 
        tab_habitualidade h 
        INNER JOIN tab_membros m on m.matricula = h.matricula
    WHERE 
        IFNULL(h.aprovado, 0) = 0
";  
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
        <div class="icon-reorder tooltips" data-original-title="Menu Navegação" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
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
        url:"ajax_p_declaracoes.php",
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
            <h3 class="page-header"><i class="fa fa-tasks" aria-hidden="true"></i>Painel de Controle</h3>
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
              <th><i class="icon-file-text column_sort" id="matricula" data-order="desc"></i> Matricula</th>            
              <th><i class="icon_profile column_sort" id="nome" data-order="desc"></i> Nome completo</th>
              <th><i class="icon_cogs "></i> Ação</th>
              </tr>
              </thead>
              <?php
              while($row = mysqli_fetch_array($result))  
{ 
              ?>
              <tbody>
<tr>


  <td><?php echo $row["matricula"]; ?></td>
  <td><?php echo strtoupper($row["nome"]); ?></td>

  
  <td>
      
<!-- Botão Declaração de Guarda de Acervo -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dga.php?id=<?php echo $row['id']; ?>" title="Dec. Guarda Acervo" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DGA</a>
</div>

<!-- Botão Declaração de Guarda de Acervo 2º Endereço -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dga2.php?id=<?php echo $row['id']; ?>" title="Dec. Guarda Acervo 2" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DGA2</a>
</div>
    
<!-- Botão Declaração de Segurança de Acervo -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dsa.php?id=<?php echo $row['id']; ?>" title="Dec. Seg Acervo" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DSA</a>
</div>

<!-- Botão Declaração de Segurança de Acervo 2º Endereço -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dsa2.php?id=<?php echo $row['id']; ?>" title="Dec. Seg Acervo 2" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DSA2</a>
</div>

<!-- Botão Declaração de Inquérito Criminal -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dic.php?id=<?php echo $row['id']; ?>" title="Dec. Inquerito" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DIC</a>
</div>

<!-- Botão Declaração de Compromisso -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/dc.php?id=<?php echo $row['id']; ?>" title="Dec. Compromisso" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DC</a>
</div>
   
<!-- Botão Declaração de Desfiliação -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/desfiliacao1.php?id=<?php echo $row['id']; ?>" title="Dec. Desfiliação" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DSF1</a>
</div>

<!-- Botão Declaração de Desfiliação 2 -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/desfiliacao2.php?id=<?php echo $row['id']; ?>" title="Dec. Desfiliação" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DSF2</a>
</div>

<!-- Botão Declaração de Filiação -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/filiacao.php?id=<?php echo $row['id']; ?>" title="Dec. filiação" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;FLÇ</a>
</div>

<!-- Botão Declaração de Residência -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/residencia5anos.php?id=<?php echo $row['id']; ?>" title="Dec. Residência" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;RSD</a>
</div>

<!-- Botão Declaração de Empréstimo de Arma -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/emprestimo_arma.php?id=<?php echo $row['id']; ?>" title="Dec. Empréstimo Arma" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DEA</a>
</div>

<!-- Botão Declaração de Modalidade e Prova -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/modalidade.php?id=<?php echo $row['id']; ?>" title="Dec. Modalidade" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DMP</a>
</div>

<!-- Botão Declaração de Emprestimo de Arma -->
<div class="btn-group btn-group-sm">
  <a class="btn btn-info btn-sm custom-btn" href="relatorios/habitualiade.php?id=<?php echo $row['id']; ?>" title="Dec. Empréstimo Arma" target="_blank">
    <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;DEA</a>
</div>
 
  </td>
</tr>
</tbody>

              <?php  
              }
              ?>
              </table>
              </div>
              </div>
            </section>
          </div>
        </div>

        <!-- Pagination -->
        <div class="text-center">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <!-- Link para a página anterior -->
              <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
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
                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <!-- page end-->
      </section>
    </section>
    <!--main content end-->
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

</body>

</html>