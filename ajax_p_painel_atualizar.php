
<?php

 include "config/ajax_p_menus.php";

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;
	
}

include_once ("config/url_painel.php");

 $search = mysqli_real_escape_string($connect, $_POST["query"]);
 $query = "
  SELECT * FROM tab_membros
  WHERE matricula LIKE '%".$search."%'
  OR nome LIKE '%".$search."%' 
  ORDER BY matricula
 ";
$result = mysqli_query($connect, $query);

{
 $output .= '
  
  <div id="result" class="table-responsive">
  <table class="table">
  <thead>
  <tr>
  <th><i class="icon-file-text column_sort" id="matricula"></i> Matricula</th>
  <th><i class="icon_profile column_sort" id="nome" ></i> Nome completo</th>
 
  <th><i class="icon_cogs "></i> A&ccedil;&atilde;o</th>
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
              else if ( $daydiff > 1000 ) { $daydiff= "data de renovacao ausente"; }
             
              else if ($renova < date('Y-m-d')) {$daydiff= "vencido há {$daydiff} dias";}
              else if ($renova > date('Y-m-d') ) {$daydiff= "{$daydiff} dias restantes";}
              else if ($renova = date('Y-m-d') ) {$daydiff= "{$daydiff} dias restantes";}
              
              else { $daydiff= "{$daydiff} dias restantes"; }

 
 $foto = URL_painel.'/img/padrao.png';
 if(strlen(trim($row["foto"])) > 0) $foto = URL_painel.'/fotos/'.$row["foto"];
 
  $output .= '
   
   <tr>
   <td>'.$row["matricula"].'</td>					  
   <td >'.$row["nome"].'</td>

   <td>
   
              
              <div class="btn-group btn-group-sm">
              <a class="btn btn-info btn-sm" href="atualizar.php?id='.$row["id"].'" Title="Editar" alt="Editar">ATUALIZAR</a>
              </div>
   
   </td>
   </tr>
   
  ';
 }
 echo $output;
}


?> 