<?php

include "config/ajax_p_menus.php";

function restante($data) {
    $date1 = new DateTime(date('Y-m-d'));
    $date2 = new DateTime($data); // YYYY-MM-DD
    $interval = $date1->diff($date2);
    return $interval->days;
}

include_once ("config/url_painel.php");

$search = mysqli_real_escape_string($connect, $_POST["query"]);

// Ajuste na consulta SQL para selecionar apenas os campos necessários
$query = "
  SELECT m.matricula, m.nome, m.foto, a.tipo, a.modelo, a.calibre
  FROM tab_membros m
  LEFT JOIN tab_armas a ON m.matricula = a.matricula
  WHERE (
    m.matricula LIKE '%".$search."%'   
    OR m.nome LIKE '%".$search."%' 
    OR a.modelo LIKE '%".$search."%' 
    OR a.calibre LIKE '%".$search."%' 
    OR a.tipo LIKE '%".$search."%'
  )
  ORDER BY m.matricula
";

$result = mysqli_query($connect, $query);

$output = ''; // Inicialize a variável $output

$output .= '
  <div id="result" class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th><i class="fa fa-camera"></i> Foto</th>
          <th><i class="fa fa-laptop" id="matricula"></i> Matrícula</th>
          <th><i class="icon_profile column_sort" id="nome"></i> Nome Completo</th>
          <th><i class="fa fa-cogs"></i> Tipo</th>
          <th><i class="fa fa-wrench"></i> Modelo</th>
          <th><i class="fa fa-bullseye"></i> Calibre</th>
        </tr>
      </thead>
';

while ($row = mysqli_fetch_array($result)) { 
    $output .= '<tr>
      <td><img src="'.URL_painel.'/fotos/'.$row["foto"].'" height="30" width="30"></td>
      <td>'.$row["matricula"].'</td>					  
      <td>'.$row["nome"].'</td>
      <td>'.$row["tipo"].'</td>   
      <td>'.$row["modelo"].'</td>      
      <td>'.$row["calibre"].'</td>  
    </tr>';
}

$output .= '</table></div>'; // Feche a tabela e div

echo $output;
?>