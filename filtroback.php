<?php  
 
 
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
 include "../config/ajax_p_menus.php";
 
 function restante($data){
 $date1 = new DateTime(date('Y-m-d'));
 $date2 = new DateTime($data); // YYYY-MM-DD
 $interval = $date1->diff($date2);
 return $interval->days;
 }
 include_once ("config/url_painel.php");
 
 $output = '';  
 $order = $_POST["order"];  
 if($order == 'desc')  
 {  
      $order = 'asc';  
 }  
 else  
 {  
      $order = 'desc';  
 }  
 $query = "SELECT * FROM tab_membros ORDER BY ".$_POST["column_name"]." ".$_POST["order"]."";  
 $result = mysqli_query($connect, $query);  
 $output .= '  
 <table class="table table-bordered">  
      <tr>  
                <th class="icon_profile"></i> Foto</th>
                <th><i class="icon-file-text column_sort" id="matricula" data-order="'.$order.'"></i> Matricula</th>
                <th><i class="icon_profile column_sort" id="nome" data-order="'.$order.'"></i> Nome completo</th>
                <th><i class="icon_mobile "></i> Whatsap</th>
                <th><i class="icon_calendar column_sort" id="data_renovacao" data-order="'.$order.'"></i> Renova&ccedil;&atilde;o</th>					  
                <th><i class="icon_cogs "></i> A&ccedil;&atilde;o</th>
                </tr>  
 ';  
 while($row = mysqli_fetch_array($result))  
 {  
      $output .= '  
      <td><img src="'.URL_painel.'/fotos/'.$row["foto"].'" height="30" width="30"></td>
      <td>'.$row["matricula"].'</td>					  
      <td >'.$row["nome"].'</td>
      <td>'.$row["telefone"].'</td>
      <td>'.restante($row["data_renovacao"]).' dias restantes</td>		
      <td>
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="perfil.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
      </div>
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="armas.php?id='.$row["id"].'" Title="Armas" alt="Armas"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a>
      </div>
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="habitualidade.php?id='.$row["id"].'" Title="Habitualidade" alt="Habitualidade"><i class="fa fa-book" aria-hidden="true"></i></a>
      </div>
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="compra_municoes.php?id='.$row["id"].'" Title="Compras" alt="Compras"><i class="fa fa-usd" aria-hidden="true"></i></a>
      </div>
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="municoes_clube.php?id='.$row["id"].'" Title="Muni&ccedil;&otilde;es Clube" alt="Muni&ccedil;&otilde;es Clube"><i class="fa fa-money" aria-hidden="true"></i></a>
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
      <a class="btn btn-info btn-sm" href="email_renovacao.php?id='.$row["id"].'" Title="Alerta Renova&ccedil;&atilde;o" alt="Alerta Renova&ccedil;&atilde;o"><i class="fa fa-bullhorn" aria-hidden="true"></i></a>
      </div>						
      
      <div class="btn-group btn-group-sm">
      <a class="btn btn-info btn-sm" href="action_del_users.php?id='.$row["id"].'" ><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
      </div>
      
      </td>
      ';  
 }  
 $output .= '</table>';  
 echo $output;  
 ?>  