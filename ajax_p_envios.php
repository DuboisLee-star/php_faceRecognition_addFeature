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
		m.data_renovacao,
		f.*,
		m.matricula,
		m.id, (SELECT count(id) qtde_pendente FROM tab_habitualidade WHERE IFNULL(aprovado,0) = 0 AND matricula = m.matricula) habitualidade_pendente,
    aut.status_pgto AS assinatura_digital
	FROM 
		tab_membros m
      LEFT JOIN tab_autentique_membros aut ON aut.membro_id = m.id
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
		m.data_renovacao,
		f.*,
		m.matricula,
		m.id,
    aut.status_pgto AS assinatura_digital
	FROM 
		tab_membros m
      LEFT JOIN tab_autentique_membros aut ON aut.membro_id = m.id
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
 $foto = URL_painel.'/img/padrao.png';
 if(strlen(trim($row["foto"])) > 0) $foto = URL_painel.'/fotos/'.$row["foto"];

$query = "SELECT * FROM info_clube WHERE id = 1";  
$result_clube = mysqli_query($connect, $query);
$clube = mysqli_fetch_assoc($result_clube);

  $output .= '
   <tr>
   <td><img src="'.$foto.'" height="30" width="30"></td>
   <td>'.$row["matricula"].'</td>					  
   <td >'.$row["nome"].'</td>
<td>
  <div class="btn-group" role="group">
    <input class="form-check-input" type="radio" id="biometria_ativar_' . $row['id'] . '" name="biometria_' . $row['id'] . '" value="1" ' . ($row['biometria'] == "1" ? 'checked' : '') . '>
    <label class="form-check-label" for="biometria_ativar_' . $row['id'] . '">Ativar</label>

    <input class="form-check-input" type="radio" id="biometria_desativar_' . $row['id'] . '" name="biometria_' . $row['id'] . '" value="0" ' . ($row['biometria'] == "0" ? 'checked' : '') . '>
    <label class="form-check-label" for="biometria_desativar_' . $row['id'] . '">Desativar</label>
  </div>
</td>
   </tr>
  ';
 }
 $output .= '
   </table>
   </div>
 ';
 echo $output;
}
else
{
 echo 'nao encontrado';
}

?>