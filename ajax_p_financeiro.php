<?php

include "config/ajax_p_menus.php";

function restante($data) {
    $date1 = new DateTime(date('Y-m-d'));
    $date2 = new DateTime($data); // YYYY-MM-DD
    $interval = $date1->diff($date2);
    return $interval->days;
}

include_once("config/url_painel.php");

$itemsPerPage = 60; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual
$offset = ($page - 1) * $itemsPerPage; 

$output = '';
if (isset($_POST["query"])) {
    $search = mysqli_real_escape_string($connect, $_POST["query"]);
    $query = "
        SELECT 
     
            m.foto,
            m.nome,
            m.data_renovacao,
            m.plano_pgto,
            f.*,
            m.matricula,
            m.id,
               f.id as financeiro
        FROM 
            tab_membros m
                LEFT JOIN tab_financeiro_2 f ON f.matricula = m.matricula
        WHERE 
          m.nome LIKE '%" . $_POST["query"] . "%' or 
           m.matricula LIKE '%" . $_POST["query"] . "%' 
           
        ORDER BY f.data_pgto ASC, f.status_pgto ASC
        LIMIT $offset, $itemsPerPage
    ";
} else {
    $query = "
        SELECT 
         
            m.foto,
            m.nome,
            m.data_renovacao,
            m.plano_pgto,
            f.*,
            m.matricula,
            m.id,
            f.id as financeiro
        FROM 
            tab_membros m
                LEFT JOIN tab_financeiro_2 f ON f.matricula = m.matricula
        ORDER BY f.plano DESC, f.data_pgto ASC
        LIMIT $offset, $itemsPerPage
    ";
}

$result = mysqli_query($connect, $query);


$countQuery = "
    SELECT COUNT(*) as total
    FROM 
        tab_membros m
            LEFT JOIN tab_financeiro_2 f ON f.matricula = m.matricula
";
$countResult = mysqli_query($connect, $countQuery);
$totalItems = mysqli_fetch_assoc($countResult)['total'];

$totalPages = ceil($totalItems / $itemsPerPage);
if (mysqli_num_rows($result) > 0) {
    $output .= '
    <div id="result" class="table-responsive">
    <table class="table">
    <thead>
    <tr>
    <th><i class="icon_profile"></i> Foto</th>
    <th><i class="icon_profile"></i> Nome completo</th>
    <th><i class="icon_mobile"></i> Plano</th>
    <th><i class="icon_mobile"></i> Anuidade</th>

       <th>Data Pagamento</th>
    
   
    <th>Status</th>
    <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
    </tr>
    </thead>
    ';

    while ($row = mysqli_fetch_array($result)) {
        $foto = URL_painel . '/img/padrao.png';
        if (strlen(trim($row["foto"])) > 0) {
            $foto = URL_painel . '/fotos/' . $row["foto"];
        }
        
        $financeiro=$row['financeiro'];
        $botao_pagar = array(
            'ano' => '', '01' => '', '02' => '', '03' => '',
            '04' => '', '05' => '', '06' => '', '07' => '',
            '08' => '', '09' => '', '10' => '', '11' => '', '12' => ''
        );

        if ($row["plano"] == 'A' && $row["status_pgto"] == 'pendente') {
            $dados = array(
                'matricula' => $row["matricula"],
                'mes' => date('m'),
                'ano' => date('Y', strtotime($row['data_pgto']))
            );
            $botao_pagar['ano'] = '<a href="/pagamento/?c=' . base64_encode(serialize($dados)) . '" class="btn btn-info btn-sm" target="_blank" style="padding: 0px 5px;"><i class="fa fa-usd" aria-hidden="true"></i></a>';
        }

        $meses = array("", "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
        for ($x = 1; $x <= 12; $x++) {
            $mes = str_pad($x, 2, '0', STR_PAD_LEFT);
            $desc_mes = substr($meses[(int)$x], 0, 3);

            if ($row["plano"] == 'M' && $row["status_pgto"] =='pendente') {
                $dados = array('matricula' => $row["matricula"], 'mes' => $mes, 'ano' => 2025);
                $botao_pagar[$mes] = ' <a href="/pagamento/?c=' . base64_encode(serialize($dados)) . '" class="btn btn-primary btn-sm" target="_blank" style="padding: 0px 5px;"><i class="fa fa-usd" aria-hidden="true"></i></a>';
            }
        }
        if($row['status_pgto'] == 'pago'){
            $output .='<tr style="background: #67829c;
    color: #fff;">';
        }else{
             $output .='<tr style="    background: #fffacc;
    color: #2c2b2b;">';
        }
        $output .= '
        
        <td><img src="' . $foto . '" class="img-responsive img-thumbnail" height="30" width="30"></td>
        <td>' . $row["nome"] . '</td>
        <td>' . $row["plano_pgto"] . '</td>';
        
        
        
        if($row['plano_pgto'] == 'A'){
            $output .='<td><font size="2">' . $row["valor"] . '<br>'.$botao_pagar['ano'] . '</font></td>';
        }else{
            $output .='<td><font size="2">' . $row["valor"] . '<br>'.$botao_pagar[$mes] . '</font></td>';
        }
            if($row['plano_pgto'] == 'M'){
                if($row['data_pgto'] == null){
                    $output .='';
                }else{
               if(date('d-m-Y', strtotime($row['data_pgto'])) > date('d-m-Y') and $row['status_pgto'] == 'pendente'){
           $output .='<td class="text-danger">'.date('d-m-Y', strtotime($row['data_pgto'])).'</td>
        ';  
        }else{
           $output .='<td>'.date('d-m-Y', strtotime($row['data_pgto'])).'</td>
        '; 
        } }
            }else{
                
                 if(date('d-m-Y', strtotime($row['data_renovacao'])) > date('d-m-Y') and $row['status_pgto'] == 'pendente'){
           $output .='<td class="text-danger">'.date('d-m-Y', strtotime($row['data_renovacao'])).'</td>
        ';  
        }else{
           $output .='<td>'.date('d-m-Y', strtotime($row['data_renovacao'])).'</td>
        '; 
        } 
            }
        
    
        $output .='<td style="text-transform:capitalize">'.$row['status_pgto'].'</td><td><div class="btn-group btn-group-sm">
            <a class="btn btn-info btn-sm" href="editar_financeiro.php?id=' . $financeiro . '" Title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></a></td>
        </div>
        </td>
        </tr>';
    }
    
  
  echo $output;
     $output2 ='<nav aria-label="Page navigation example" id="paginacao">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>';
    for ($i = 1; $i <= $totalPages; $i++){
    $output2 .='<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
    }
    $output2 .='<li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>';
    echo $output2;
         
} else {
    echo 'nao encontrado';
}

?>