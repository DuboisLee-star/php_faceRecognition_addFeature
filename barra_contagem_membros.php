<?php

// Inclua o arquivo de configura«®«ªo
include('config/config.php');

// Conex«ªo com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conex«ªo
if ($conn->connect_error) {
    die("Falha na conex«ªo: " . $conn->connect_error);
}

// Consulta para membros Ativos
$sqlAtivos = "SELECT COUNT(*) AS total_ativos FROM tab_membros WHERE bloqueio = 'Nao'";
$resultAtivos = $conn->query($sqlAtivos);
$rowAtivos = $resultAtivos->fetch_assoc();
$totalAtivos = $rowAtivos['total_ativos'];

// Consulta para membros Inativos
$sqlInativos = "SELECT COUNT(*) AS total_inativos FROM tab_membros WHERE bloqueio = 'Sim'";
$resultInativos = $conn->query($sqlInativos);
$rowInativos = $resultInativos->fetch_assoc();
$totalInativos = $rowInativos['total_inativos'];

// Consulta para membros Desfiliados
$sqlDesfiliados = "SELECT COUNT(*) AS total_desfiliados FROM tab_membros WHERE bloqueio = 'D'";
$resultDesfiliados = $conn->query($sqlDesfiliados);
$rowDesfiliados = $resultDesfiliados->fetch_assoc();
$totalDesfiliados = $rowDesfiliados['total_desfiliados'];

// Consulta para membros Todos
$sqlTodos = "SELECT COUNT(*) AS total_todos FROM tab_membros";
$resultTodos = $conn->query($sqlTodos);
$rowTodos = $resultTodos->fetch_assoc();
$totalTodos = $rowTodos['total_todos'];

// Consulta para Visitantes
$sqlVisitantes = "SELECT COUNT(*) AS total_visitantes FROM tab_habitualidade WHERE tipo_atirador = '2'";
$resultVisitantes = $conn->query($sqlVisitantes);
$rowVisitantes = $resultVisitantes->fetch_assoc();
$totalVisitantes = $rowVisitantes['total_visitantes'];

// Fecha a conexÃ£o com o banco de dados
$conn->close();
?>

<!-- Inserindo os resultados no HTML -->
              
              <div id="employee_table" class="table-responsive">
              <table class="table">
              <thead>
              <tr>
              <th><ol class="breadcrumb"><li><i class="fa fa-user-circle-o" aria-hidden="true"></i><a href="painel.php"></i>&nbsp;Ativos</li></a>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-user-o" aria-hidden="true"></i><a href="painel_inativos.php"></i>&nbsp;Inativos</li></a>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-user-times" aria-hidden="true"></i><a href="painel_desfiliados.php"></i>&nbsp;Desfiliados</li></a>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-users" aria-hidden="true"></i><a href="painel_todos.php"></i>&nbsp;Todos</li></a>&nbsp;&nbsp;||&nbsp;&nbsp;<i class="fa fa-address-book-o" aria-hidden="true"></i><a href="painel_visitantes.php"></i>&nbsp;Visitantes</li></a>
  </ol><ol class="breadcrumb"><li><i class="fa fa-user-circle-o" aria-hidden="true"></i><?php echo $totalAtivos; ?></li>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<i class="fa fa-user-o" aria-hidden="true"></i>&nbsp;<?php echo $totalInativos; ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<i class="fa fa-user-times" aria-hidden="true"></i>&nbsp;<?php echo $totalDesfiliados; ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<i class="fa fa-users" aria-hidden="true"></i>&nbsp;<?php echo $totalTodos; ?>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;<i class="fa fa-address-book" aria-hidden="true"></i>&nbsp;<?php echo $totalConvidados; ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<i class="fa fa-address-book-o" aria-hidden="true"></i>&nbsp;<?php echo $totalVisitantes; ?></ol>
</th>
    </tr>
              </thead>
              </table>
              </div>
          