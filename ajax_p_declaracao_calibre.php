<?php

include "config/ajax_p_menus.php";

function restante($data) {
    $date1 = new DateTime(date('Y-m-d'));
    $date2 = new DateTime($data);
    $interval = $date1->diff($date2);
    return $interval->days;
}

include_once("config/url_painel.php");

$output = '';

// Consulta SQL para obter valores distintos da coluna 'calibre'
$calibre_query = "SELECT DISTINCT h.calibre, h.matricula FROM tab_habitualidade h";
$calibre_result = mysqli_query($connect, $calibre_query);

// Associar os calibres às matrículas
$calibres_por_matricula = array();
while ($calibre_row = mysqli_fetch_array($calibre_result)) {
    $matricula = $calibre_row['matricula'];
    $calibre = $calibre_row['calibre'];
    if (!isset($calibres_por_matricula[$matricula])) {
        $calibres_por_matricula[$matricula] = array();
    }
    $calibres_por_matricula[$matricula][] = $calibre;
}

$output = '';

$search = '';
if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($connect, $_POST["query"]);
} 

$output .= '
    <div id="result" class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><i class="icon_profile"></i> Foto</th>
                    <th><i class="icon-file-text column_sort" id="matricula"></i> Matricula</th>
                    <th><i class="icon_profile column_sort" id="nome"></i> Nome completo</th>
                    <th><i class="icon_cogs"></i> Calibres Utilizados</th>
                </tr>
            </thead>
';


// Consulta principal para obter os membros e informações financeiras
$query = "
    SELECT 
        m.foto,
        m.nome,
        m.matricula,
        m.id
    FROM 
        tab_membros m
    WHERE 
        m.matricula LIKE '%" . $search . "%'
        OR m.nome LIKE '%" . $search . "%'
    ORDER BY m.matricula ASC
";

$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $foto = URL_painel . '/img/padrao.png';
        if (strlen(trim($row["foto"])) > 0) $foto = URL_painel . '/fotos/' . $row["foto"];

        $matricula = $row["matricula"];
        $calibres = isset($calibres_por_matricula[$matricula]) ? $calibres_por_matricula[$matricula] : array();

$calibre_buttons = '';
foreach ($calibres as $calibre) {
    $matricula = $row["matricula"]; // Pode usar $matricula se preferir
    $calibre_buttons .= '<div class="btn-group">
                      <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ' . $calibre . '
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="relatorios/habitualidade2.php?id=' . $matricula . '&calibre=' . urlencode($calibre) . '" target="_blank">Permitido</a>
                        <a class="dropdown-item" href="relatorios/habitualidade3.php?id=' . $matricula . '&calibre=' . urlencode($calibre) . '" target="_blank">Restrito</a>
                      </div>
                    </div>';
}

        $output .= '
            <tr>
                <td><img src="' . $foto . '" height="30" width="30"></td>
                <td>' . $matricula . '</td>					  
                <td>' . $row["nome"] . '</td>
                <td>
                    <div class="btn-group btn-group-sm">' . $calibre_buttons . '</div>
                </td>
            </tr>
        ';
    }
    echo $output;
} else {
    echo 'não encontrado';
}
?>