
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
  OR matricula LIKE '%".$search."%'   
  OR nome LIKE '%".$search."%' 
  OR plano LIKE '%".$search."%'   
  ORDER BY matricula
 ";
$result = mysqli_query($connect, $query);

{
 $output .= '
  
  <div id="result" class="table-responsive">
  <table class="table">
  <thead>
  <tr>
  <th><i class="icon_profile"></i> Foto</th>
  <th><i class="icon-file-text column_sort" id="matricula"></i> Matricula</th>
  <th><i class="icon_profile column_sort" id="nome" ></i> Nome completo</th>

  <th><i class="icon_calendar column_sort" id="data_renovacao"></i> Data Renova&ccedil;&atilde;o</th>
  <th><i class="icon_calendar column_sort" id="data_renovacao"></i> Contagem dias</th>   
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
 
    <td>'. ($row["data_renovacao"] ? date('d/m/Y', strtotime($row["data_renovacao"])) : ''). '</td>
   <td>'.$daydiff.'</td>		
   <td>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="perfil.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="armas.php?id='.$row["id"].'" Title="Armas" alt="Armas"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a>
   </div>
   
			  <div class="btn-group btn-group-sm">
              <a class="btn btn-info btn-sm" href="whatsapp.php?id='.$row["id"].'" Title="Notificação WhatsApp" alt="Notificação WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
              </div>   
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="habitualidade.php?id='.$row["id"].'" Title="Habitualidade" alt="Habitualidade"><i class="fa fa-street-view" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="compra_municoes.php?id='.$row["id"].'" Title="Compras" alt="Compras"><i class="fa fa-usd" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="documentos.php?id='.$row["id"].'" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="email.php?id='.$row["id"].'" Title="Email" alt="Contato"><i class="fa fa-envelope" aria-hidden="true"></i></a>
   </div>
   
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="emailsenha.php?id='.$row["id"].'" Title="Senha" alt="Senha"><i class="fa fa-key" aria-hidden="true"></i></a>
   </div>													
   
              <div class="btn-group btn-group-sm">
              <a class="btn '.((strlen(trim($row['biometria'])) <= 0) ? 'btn-warning' : 'btn-info').' btn-sm" href="biometria.php?id='.$row["id"].'" Title="Biometria" alt="Biometria"><i class="fa fa-ils" aria-hidden="true"></i></a>
              </div>
              
              <div class="btn-group btn-group-sm">
              <a class="btn btn-sm ' . ($row["assinatura_digital"] == 'Pendente' ? 'btn-warning' : ($row["assinatura_digital"] == 'Pago' ? 'btn-success' : 'btn-info')) . '" href="assinatura_digital.php?id='.$row["id"].'" Title="Assinatura digital (' . (empty($row["assinatura_digital"]) ? 'Não solicitado' : $row["assinatura_digital"]) . ')" alt="Assinatura digital (' . (empty($row["assinatura_digital"]) ? 'Não solicitado' : $row["assinatura_digital"]) . ')"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
              </div>
              
   <div class="btn-group btn-group-sm">
   <a class="btn btn-info btn-sm" href="action_del_users.php?id='.$row["id"].'" ><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
   </div>
   
   </td>
   </tr>
   
  ';
 }
 echo $output;
}


?> 