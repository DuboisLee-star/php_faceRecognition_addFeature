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
	SELECT 
		m.foto,
		m.nome,
		m.plano_pgto,
		m.data_renovacao,
		f.*,
		m.matricula,
		m.id
	FROM 
		tab_membros m
			LEFT JOIN tab_financeiro f ON f.matricula = m.matricula
	WHERE 
			m.matricula LIKE '%".$search."%'
		OR m.nome LIKE '%".$search."%' 
		OR m.telefone LIKE '%".$search."%' 
        order by m.matricula ASC
 ";
}
else
{
 $query = "
  SELECT 
		m.foto,
		m.nome,
		m.plano_pgto,
		m.data_renovacao,
		f.*,
		m.matricula,
		m.id
	FROM 
		tab_membros m
			LEFT JOIN tab_financeiro f ON f.matricula = m.matricula
	order by m.matricula ASC
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
   <td><img src="'.$foto.'" height="30" width="30"></td>
   <td>'.$row["matricula"].'</td>					  
   <td >'.$row["nome"].'</td>
	
   <td>
   
  <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/carteira.php?id='.$row["id"].'" Title="Carteira"  target="_blank" alt="Carteira"><i class="fa fa-id-badge" aria-hidden="true">&nbsp;CARTEIRA</i></a></div>&nbsp;

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/contagem_habitualidade.php?id='.$row["id"].'" Title="Contagem Habitualidades" target="_blank" alt="Contagem Habitualidades"><i class="fa fa-bar-chart" aria-hidden="true">&nbsp;CONTAGEM</i></a>
   </div>&nbsp;

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/controle_municao.php?id='.$row["id"].'" Title="Mapa Munições"  target="_blank" alt="Mapa Munições"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;MAPA</i></a></div>&nbsp;
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/cr_renovar.php?id='.$row["id"].'" Title="CR Renovar" alt="CR Renovar" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;RENOVAR</i></a></div>&nbsp;
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/cr_2o_endereco.php?id='.$row["id"].'" Title="Incluir 2o end" alt="Incluir 2o end" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;2º END</i></a></div>&nbsp;
  
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/fichaindividual.php?id='.$row["id"].'" Title="Ficha Individual" alt="Ficha Individual" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;FICHA</i></a></div>&nbsp;				
   
   
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
