
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
  SELECT * FROM tab_habitualidade
  WHERE tipo_atirador LIKE '%2%' AND id LIKE '%".$search."%'
  OR cr_visitante LIKE '%".$search."%' 
  OR nome_visitante LIKE '%".$search."%' 
  OR evento LIKE '%".$search."%' 
 
 ";
}
else
{
 $query = "
  SELECT * FROM tab_habitualidade WHERE tipo_atirador LIKE '%2%' ORDER BY data DESC
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
  <th><i class="icon_profile"></i> Data/Hora</th> 
  <th><i class="icon_profile"></i> CR</th>    
  <th><i class="icon_profile"></i> Atirador</th>  
  <th><i class="icon_profile"></i> Evento</th>  
  <th><i class="icon_profile"></i> Armamento</th> 
  <th><i class="icon_profile"></i> QTDE</th> 
  <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
  </tr>
  </thead>
 ';
  while($row = mysqli_fetch_array($result))
 { 
 $diasr = "dias"; 
 $renova = $row["data"]; 
 $daydiff=floor((abs(strtotime(date("Y-m-d")) - strtotime($row["data"]))/(60*60*24)));
 $ano = 365;
 $vencido = $daydiff-$ano; 
 if ( $daydiff < 1) { $daydiff = "venceu hoje"; } 
 else if ( $daydiff > 1000 ) { $daydiff= "data de renovacao vazia"; }
 else if ($daydiff > 365) { $daydiff= "vencido há {$vencido} dias";}
 else { $daydiff= "{$daydiff} dias restantes"; }
 
  $output .= '
    
   
   <tr>
   <td>'.$row["data"].'</td>
   <td >'.$row["cr_visitante"].'</td>   
   <td >'.$row["nome_visitante"].'</td>
   <td>'.$row["evento"].'</td>	
   <td>'.$row["tipo"].' | '.$row["modelo"].' | '.$row["calibre"].' | '.$row["sigma"].'</td>	
   <td>'.$row["qtdemunicoes"].'</td>
   <td>   
   
 <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="editar_habitualidade_visitante.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
   </div>
   
 <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="documentos_visitantes.php?id='.$row["id"].'" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
   </div>
   
<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href="javascript:void(0);" onclick="return delUsers(\'' . $row['id'] . '\');" data-confirm="Excluir Habitualidade"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
 </div>
   
<div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante.php?id='.$row["id"].'" Title="Dec. Habitualidade" alt="Dec. Habitualidade" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;HBTL</i></a></div>
   
<div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante2.php?id='.$row["id"].'" Title="Visitante" alt="Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;VST</i></a></div>
   
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