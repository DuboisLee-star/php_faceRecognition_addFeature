<?php

 include "config/ajax_p_menus.php";

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;
	
}

include_once ("config/url_painel.php");

$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($connect, $_POST["query"]);
 $query = "
  SELECT * FROM tab_cursos
  WHERE nome LIKE '%".$search."%'
  OR cpf LIKE '%".$search."%' 
  OR curso LIKE '%".$search."%' 
  OR telefone LIKE '%".$search."%'   
  
 ";
}
else
{
 $query = "
  SELECT * FROM tab_cursos ORDER BY id
 ";
}
$result = mysqli_query($connect, $query);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
  
  <div id="result" class="table-responsive">
  <table class="table">
  <thead>
  <tr>
  <th><i class="icon_profile"></i> Foto</th>
  <th><i class="icon_profile"></i> Nome completo</th>
  <th><i class="fa fa-trophy"></i> Curso</th>  
  <th><i class="icon_calendar column_sort"></i> Data Inicial</th>
  <th><i class="icon_calendar column_sort"></i> Data Final</th>  
  <th><i class="fa fa-clock-o"></i> Carga Horária</th>
  <th><i class="fa fa-clock-o"></i> Status</th>   
  <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
  </tr>
  </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
  $output .= '
   
   <tr>
   <td><img src="'.URL_painel.'fotoscursos/'.$row["foto"].'" height="30" width="30"></td>
   <td >'.$row["nome"].'</td>
   <td>'.$row["curso"].'</td>   
   <td>'.date('d/m/Y', strtotime($row["data_inicial_do_curso"])) .'</td>
   <td>'.date('d/m/Y', strtotime($row["data_final_do_curso"])) .'</td>   
   <td>'.$row["carga_horaria_curso"].'</td>   
   <td>'.$row["status"].'</td>		
   <td>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="editar_participante_curso.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
   </div>
   
   
       <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/declaracao_curso.php?id='.$row["id"].'" Title="Declaração" alt="Declaração" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DECLARAÇÃO</i></a></div>
   
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="action_del_participante_cursos.php?id='.$row["id"].'" onclick="return confirm("Confirma excluir registro?");"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
   </div>

   </td>
   </tr>
   
  ';
 }
 echo $output;
}
else
{
 echo 'nao encontrado';
}

?> 