<?php

include "config/ajax_p_menus.php";
include_once("config/url_painel.php");
require 'config/conexao.php';

$conexao = conexao::getInstance();


// Verifica se há uma consulta de pesquisa
if (isset($_POST["query"])) {
  
   
    $search = $_POST["query"];
    
    $query = "
        SELECT datacadastro, nome_visitante, cr_visitante, cr_visitante_validade, tipo, calibre, numsigma, evento, tipo_atirador, id
        FROM tab_habitualidade
        WHERE tipo_atirador LIKE '%2%' 
        AND (id LIKE '%".$search."%'  
        OR cr_visitante LIKE '%".$search."%'  
        OR nome_visitante LIKE '%".$search."%' )
        ORDER BY datacadastro DESC 
    ";
    
    
  
$result = mysqli_query($connect, $query);

if (count($result) > 0) {
    $output = '
        <div id="result" class="table-responsive">
        <table class="table">
        <thead>
        <tr>
        <th><i class="icon_profile"></i> Data</th> 
        <th><i class="icon_profile"></i> Nome do Visitante</th>
        <th><i class="icon_profile"></i> Armamento</th>
        <th><i class="icon_profile"></i> Evento</th>  
        <th><i class="icon_cogs"></i> Ação</th>
        </tr>
        </thead>
        <tbody>
    ';
    foreach ($result as $row) {
        
                $tip=$row[tipo];
                $calib=$row[calibre];
                $numsig=$row[numsigma];
                $tipo_arr= explode(',',$tip);
                $calib_arr=explode(',',$calib);
                $numsig_arr=explode(',', $numsig);
                $array_armas=["tipo"=>$tipo_arr, "calibre"=>$calib_arr, "numsigma"=>$numsig_arr];
                
        $output .= '
            <tr>
            <td>'.(!empty($row["datacadastro"]) ? date('d/m/Y', strtotime($row["data"])) : 'NULL') .'</td>
            <td>'.strtoupper($row["nome_visitante"]).'</td>
            <td>'.strtoupper($row["tipo"]).' | '.strtoupper($row["calibre"]).' | '.$row["numsigma"].'</td>
            
            <td>'.strtoupper($row["evento"]).'</td>
            <td>   
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="editar_habitualidade_visitante.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="documentos_visitantes.php?id='.$row["id"].'" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante.php?id='.$row["id"].'" Title="Dec. Habitualidade" alt="Dec. Habitualidade" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;HBTL</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/modalidade_visitante.php?id='.$row["id"].'" Title="Mod. Visitante" alt="Mod. Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;MDV</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante2.php?id='.$row["id"].'" Title="Visitante" alt="Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;VST</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="imagem.php?id='.$row["id"].'" Title="CERT" alt="CERT" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;CERT</i></a>
                </div>
                
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return delUsers(' . $row['id'] . ');" data-confirm="Excluir Habitualidade"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                </div>
            </td>
            </tr>
        ';
    }
    $output .= '</tbody></table></div>';
    echo $output;
} else {
    echo 'não encontrado';
}

} else {
    $query = "
        SELECT * FROM tab_habitualidade
        WHERE tipo_atirador LIKE '%2%' 
        ORDER BY datacadastro DESC
    ";
    $stm = $conexao->prepare($query);
    $stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);

}



if (isset($_POST["query"])) {
    $searchParam = "%{$search}%";
    $stm->bindValue(':search', $searchParam);
}




if (count($result) > 0) {
    $output = '
        <div id="result" class="table-responsive">
        <table class="table">
        <thead>
        <tr>
        <th><i class="icon_profile"></i> Data</th> 
        <th><i class="icon_profile"></i> Nome do Visitante</th>
        <th><i class="icon_profile"></i> Armamento</th>
        <th><i class="icon_profile"></i> Evento</th>  
        <th><i class="icon_cogs"></i> Ação</th>
        </tr>
        </thead>
        <tbody>
    ';
    foreach ($result as $row) {
        $output .= '
            <tr>
            <td>'.(!empty($row["datacadastro"]) ? date('d/m/Y', strtotime($row["data"])) : 'NULL') .'</td>
            <td>'.strtoupper($row["nome_visitante"]).'</td>
            <td>'.strtoupper($row["tipo"]).' | '.strtoupper($row["calibre"]).' | '.$row["numsigma"].'</td>   
            <td>'.strtoupper($row["evento"]).'</td>
            <td>   
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="editar_habitualidade_visitante.php?id='.$row["id"].'" Title="Editar" alt="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="documentos_visitantes.php?id='.$row["id"].'" Title="Documentos" alt="Documentos"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante.php?id='.$row["id"].'" Title="Dec. Habitualidade" alt="Dec. Habitualidade" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;HBTL</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/modalidade_visitante.php?id='.$row["id"].'" Title="Mod. Visitante" alt="Mod. Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;MDV</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="relatorios/habitualidade_visitante2.php?id='.$row["id"].'" Title="Visitante" alt="Visitante" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;VST</i></a>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-info btn-sm" href="imagem.php?id='.$row["id"].'" Title="CERT" alt="CERT" target="_blank"><i class="fa fa-file-text-o" aria-hidden="true">&nbsp;CERT</i></a>
                </div>
                
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return delUsers(' . $row['id'] . ');" data-confirm="Excluir Habitualidade"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                </div>
            </td>
            </tr>
        ';
    }
    $output .= '</tbody></table></div>';
    echo $output;
} else {
    echo 'não encontrado';
}
?>

<script>
function delUsers(id) {
    // Exibe a caixa de confirmação
    if (confirm('Tem certeza de que deseja excluir este registro?')) {
        // Redireciona para o script de exclusão com o ID como parâmetro
        window.location.href = 'action_del_habitualidade_visitante.php?id=' + id;
    }
    return false; // Impede o link de seguir o href padrão
}
</script>