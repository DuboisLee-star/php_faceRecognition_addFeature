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
ORDER BY a.matricula 
LIMIT $offset, $items_per_page";

$result = mysqli_query($connect, $query);

// Consulta para contar o número total de registros
$total_query = "SELECT COUNT(*) as total FROM tab_membros";
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
        url:"ajax_p_todos.php",
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
              
              <?php if($qtde_pendente > 0 && (int)$clube['tipo_habitualidade'] > 1): ?>
              <div style="background: #fffacc; color: #af8100; padding: 10px; margin: 10px;">Existe habitualidade pendente de aprovação!</div>
              <?php endif; ?>
              
              <div id="result">
              <div id="employee_table" class="table-responsive">
              <table class="table">
              <thead>
              <tr>
              <th><i class="icon_profile"></i> Foto</th>
              <th><i class="icon-file-text column_sort" id="matricula" data-order="desc"></i> Matricula</th>            
              <th><i class="icon_profile column_sort" id="nome" data-order="desc"></i> Nome completo</th>
              <th><i class="icon_calendar column_sort "></i> Data Renovação</th>
              <th><i class="icon_calendar column_sort" id="data_renovacao" data-order="desc"></i> Contagem dias</th>                    
              <th><i class="icon_cogs "></i> Ação</th>
              </tr>
              </thead>
              <?php
while($row = mysqli_fetch_array($result))  
{ 
    $renova = $row["data_renovacao"];
    
    // Verifica se $renova é uma data válida antes de formatar
    if (!empty($renova) && strtotime($renova) !== false) {
        $renova = strtotime($renova);
        $renova = date("d/m/Y", $renova);
    } else {
        $renova = ''; // Se for NULL, vazia, ou inválida, deixa em branco
    }
    
    $diff = strtotime($row["data_renovacao"]) - time();
    $dias = floor($diff / (60*60*24));

    if ($dias < 0) {
        $dias = "-" . abs($dias) . " dias vencido(s)";
    } elseif ($dias == 0) {
        $dias = "Último dia";
    } else {
        $dias = $dias . " dias restantes";
    }
?>
              <?php
// Verifica se o campo 'foto' está vazio
$foto = !empty($row['foto']) ? $row['foto'] : 'padrao.png';
?>
              <tbody>
              <tr>
              <td>  <img src="fotos/<?php echo htmlspecialchars($foto); ?>" class="img-responsive img-thumbnail" style="width: 30px; height: 30px; object-fit: cover;" /></td>
              <td><?php echo $row["matricula"]; ?></td>
              <td><?php echo strtoupper($row["nome"]); ?></td>
              <td><?php echo $renova; ?></td>
              <td><?php echo $dias; ?></td>
  
  <td>
   <!-- Botão Editar -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="perfil.php?id=<?php echo $row['id']; ?>" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Gerar Recibo -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="recibo_valor.php?id=<?php echo $row['id']; ?>" Title="Gerar Recibo" alt="Gerar Recibo"><i class="fa fa fa-money" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Notificação WhatsApp -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="whatsapp.php?id=<?php echo $row['id']; ?>" Title="Notificação WhatsApp" alt="Notificação WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Armas -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="armas.php?id=<?php echo $row['id']; ?>" Title="Armas" alt="Armas"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a>
   </div>

    <!-- Botão Habitualidade -->  
    <div class="btn-group btn-group-sm">
    <a class="btn <?php echo ($row['habitualidade_pendente'] > 0 && (int)$clube['tipo_habitualidade'] > 1) ? 'btn-warning' : 'btn-info'; ?> btn-sm" href="habitualidade.php?id=<?php echo $row['id']; ?>" title="Habitualidade" alt="Habitualidade">
    <i class="fa fa-street-view" aria-hidden="true"></i></a>
    </div>

   <!-- Botão Compras -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="compra_municoes.php?id=<?php echo $row['id']; ?>" Title="Compras" alt="Compras"><i class="fa fa-usd" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Documentos -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="documentos.php?id=<?php echo $row['id']; ?>" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Email -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="email.php?id=<?php echo $row['id']; ?>" Title="Email" alt="Contato"><i class="fa fa-envelope" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Senha -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="emailsenha.php?id=<?php echo $row['id']; ?>" Title="Senha" alt="Senha"><i class="fa fa-key" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Biometria -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-sm <?php echo strlen(trim($row['biometria'])) <= 0 ? 'btn-warning' : 'btn-info'; ?>" href="biometria.php?id=<?php echo $row['id']; ?>" Title="Biometria" alt="Biometria"><i class="fa fa-ils" aria-hidden="true"></i></a>
   </div>
   
   <!-- Botão Assinatura Digital -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-sm <?php echo $row['assinatura_digital'] == 'Pendente' ? 'btn-warning' : ($row['assinatura_digital'] == 'Pago' ? 'btn-success' : 'btn-info'); ?>" href="assinatura_digital.php?id=<?php echo $row['id']; ?>" Title="Assinatura digital (<?php echo empty($row['assinatura_digital']) ? 'Não solicitado' : $row['assinatura_digital']; ?>)" alt="Assinatura digital (<?php echo empty($row['assinatura_digital']) ? 'Não solicitado' : $row['assinatura_digital']; ?>)"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
   </div>

   <!-- Botão Deletar -->
   <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="action_del_users.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
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