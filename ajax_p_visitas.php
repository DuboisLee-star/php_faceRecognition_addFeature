
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
  SELECT * FROM tab_visitas
  WHERE nome_visita LIKE '%".$search."%'
  OR convidado_por LIKE '%".$search."%'  
  
 ";
}
else
{
 $query = "
  SELECT * FROM tab_visitas ORDER BY id
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
  <th><i class="icon-file-text"></i> Visitante</th>
  <th><i class="icon_profile"></i> Convidado por</th>
  <th><i class="icon_calendar"></i> Data da visita</th>					  
  <th><i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp</th>					  
  <th><i class="fa fa-clock-o" aria-hidden="true"></i> Status</th>
  <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
  </tr>
  </thead>
 ';
 while($row = mysqli_fetch_array($result))
 { 
 $diasr = "dias"; 
 $renova = $row["data_renovacao"]; 
 $daydiff=floor((abs(strtotime(date("Y-m-d")) - strtotime($row["data_renovacao"]))/(60*60*24)));
 $ano = 365;
 $vencido = $daydiff-$ano; 
 if ( $daydiff < 1) { $daydiff = "venceu hoje"; } 
 else if ( $daydiff > 1000 ) { $daydiff= "data de renovacao vazia"; }
 else if ($daydiff > 365) { $daydiff= "vencido há {$vencido} dias";}
 else { $daydiff= "{$daydiff} dias restantes"; }
 
  $output .= '
   
  
<tr>
   <td><img src="'.URL_painel.'/fotosvisitas/'.$row["foto"].'" height="30" width="30"></td>
   <td>'.$row["nome_visita"].'</td>					  
   <td >'.$row["convidado_por"].'</td>
   <td>'.date('d/m/Y', strtotime($row['data_visita'])) .'</td>
   <td>'.$row["telefone_visita"].'</td>	
    <td>'.$row["autorizacao"].'</td>
   <td>
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="editar_visitas.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="email_libera_visita.php?id='.$row["id"].'" Title="Avisar Visitante" alt="Avisar Visitante"><i class="fa fa-bullhorn" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/fichavisita.php?id='.$row["id"].'" Title="Imprimir Ficha" alt="Imprimir Ficha"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
   </div>
   

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="action_del_visitas.php?id='.$row["id"].'" ><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
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