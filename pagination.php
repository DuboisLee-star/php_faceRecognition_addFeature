<?php
// Configurações de paginação
$limit = 3; // Número de registros por página
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Página atual
$start = ($page - 1) * $limit; // Posição inicial da consulta

// Consulta para contar o total de registros
$result = mysqli_query($connect, "SELECT COUNT(*) AS total FROM tab_membros");
$row = mysqli_fetch_array($result);
$total_records = $row['total'];

// Calcular o número total de páginas
$total_pages = ceil($total_records / $limit);

// Geração do HTML para a navegação
echo '<nav aria-label="Navegação de página exemplo">';
echo '<ul class="pagination">';
if ($page > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>';
}

for ($i = 1; $i <= $total_pages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}

if ($page < $total_pages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '" aria-label="Próximo"><span aria-hidden="true">&raquo;</span></a></li>';
}
echo '</ul>';
echo '</nav>';
?>