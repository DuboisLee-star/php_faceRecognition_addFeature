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
  <th><i class="icon-file-text"></i> Matricula</th>
  <th><i class="icon_profile"></i> Nome completo</th>
  <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
  </tr>
  </thead>
  
 ';
 while($row = mysqli_fetch_array($result))
 { 
  $output .= '
   
   <tr>
   <td>'.$row["matricula"].'</td>					  
   <td >'.$row["nome"].'</td>
   <td>
   
 <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dga.php?id='.$row["id"].'" Title="Dec. Guarda Acervo"  target="_blank" alt="Dec. Guarda Acervo"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DGA</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dga2.php?id='.$row["id"].'" Title="Dec. Guarda Acervo 2 End."  target="_blank" alt="Dec. Guarda Acervo 2 End."><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DGA2</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dsa.php?id='.$row["id"].'" Title="Dec. Seg. Acervo" alt="Dec. Seg. Acervo" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DSA</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dsa2.php?id='.$row["id"].'" Title="Dec. Seg. Acervo 2" alt="Dec. Seg. Acervo 2" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DSA2</i></a></div>

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dic.php?id='.$row["id"].'" Title="Dec. Inquerito Crim" alt="Dec. Inquerito Crim" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DIC</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/dc.php?id='.$row["id"].'" Title="Dec. Compromisso" alt="Dec. Compromisso" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DC</i></a></div>   
  
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/desfiliacao1.php?id='.$row["id"].'" Title="Dec. Filiacao 1" alt="Dec. Filiacao 1" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DFL1</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/desfiliacao2.php?id='.$row["id"].'" Title="Dec. Filiacao 2" alt="Dec. Filiacao 2" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DFL2</i></a></div>

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/filiacao.php?id='.$row["id"].'" Title="Dec. Filiacao" alt="Dec. Filiacao" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;FLÇ</i></a></div>

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/residencia5anos.php?id='.$row["id"].'" Title="Dec. Residencia" alt="Dec. Residencia 1o end." target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;RSD</i></a></div>

   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/emprestimo_arma.php?id='.$row["id"].'" Title="Empréstimo Arma" alt="Empréstimo Arma" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DEA</i></a></div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/modalidade.php?id='.$row["id"].'" Title="Dec. Mod. e Prova" alt="Dec. Mod. e Prova" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DMP</i></a></div>
  
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="relatorios/habitualidade.php?id='.$row["id"].'" Title="Dec. Hab. Iniciante" alt="Dec. Hab. Iniciante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;DHI</i></a>
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
