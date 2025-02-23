<?php

include "config/ajax_p_menus.php";

function restante($data) {
    $date1 = new DateTime(date('Y-m-d'));
    $date2 = new DateTime($data); // YYYY-MM-DD
    $interval = $date1->diff($date2);
    return $interval->days;
}

include_once("config/url_painel.php");

$output = '';
if (isset($_POST["query"])) {
    $search = mysqli_real_escape_string($connect, $_POST["query"]);
    $query = "
        SELECT 
            m.foto,
            m.nome,
            m.plano_pgto,
            f.*,
            m.matricula,
            m.id
        FROM 
            tab_membros m
                LEFT JOIN tab_financeiro f ON f.matricula = m.matricula
        WHERE 
            m.matricula LIKE '%" . $search . "%'
            OR m.nome LIKE '%" . $search . "%' 
        ORDER BY m.matricula ASC
    ";
} else {
    $query = "
        SELECT 
            m.foto,
            m.nome,
            m.plano_pgto,
            f.*,
            m.matricula,
            m.id
        FROM 
            tab_membros m
                LEFT JOIN tab_financeiro f ON f.matricula = m.matricula
        ORDER BY m.matricula ASC
    ";
}

$result = mysqli_query($connect, $query);
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
     
    <td>J</td>
    <td>F</td>
    <td>M</td>
    <td>A</td>
    <td>M</td>
    <td>J</td>
    <td>J</td>
    <td>A</td>
    <td>S</td>
    <td>O</td>
    <td>N</td>
    <td>D</td>
    <th><i class="icon_cogs"></i> A&ccedil;&atilde;o</th>
    </tr>
    </thead>
    ';

    while ($row = mysqli_fetch_array($result)) {
        $foto = URL_painel . '/img/padrao.png';
        if (strlen(trim($row["foto"])) > 0) {
            $foto = URL_painel . '/fotos/' . $row["foto"];
        }

        $botao_pagar = array(
            'ano' => '', '01' => '', '02' => '', '03' => '',
            '04' => '', '05' => '', '06' => '', '07' => '',
            '08' => '', '09' => '', '10' => '', '11' => '', '12' => ''
        );

        if ($row["plano_pgto"] == 'A' && $row["valor_anuidade"] <= 0) {
            $dados = array(
                'matricula' => $row["matricula"],
                'mes' => date('m'),
                'ano' => 2025
            );
            $botao_pagar['ano'] = '<a href="/pagamento/?c=' . base64_encode(serialize($dados)) . '" class="btn btn-info btn-sm" target="_blank" style="padding: 0px 5px;"><i class="fa fa-usd" aria-hidden="true"></i></a>';
        }

        $meses = array("", "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
        for ($x = 1; $x <= 12; $x++) {
            $mes = str_pad($x, 2, '0', STR_PAD_LEFT);
            $desc_mes = substr($meses[(int)$x], 0, 3);

            if ($row["plano_pgto"] == 'M' && $row["mens_" . $desc_mes . "2024"] <= 0 && $row["valor_anuidade"] <= 0) {
                $dados = array('matricula' => $row["matricula"], 'mes' => $mes, 'ano' => 2024);
                $botao_pagar[$mes] = ' <a href="/pagamento/?c=' . base64_encode(serialize($dados)) . '" class="btn btn-primary btn-sm" target="_blank" style="padding: 0px 5px;"><i class="fa fa-usd" aria-hidden="true"></i></a>';
            }
        }

        $output .= '
        <tr>
        <td><img src="' . $foto . '" height="30" width="30"></td>
        <td>' . $row["nome"] . '</td>
        <td>' . $row["plano_pgto"] . '</td>
        <td><font size="2">' . $row["valor_anuidade"] . $botao_pagar['ano'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['01'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['02'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['03'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['04'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['05'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['06'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['07'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['08'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['09'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['10'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['11'] . '</font></td>
        <td><font size="2">' . $row["valor_mensalidade"] . $botao_pagar['12'] . '</font></td>
        <td>
        <div class="btn-group btn-group-sm">
            <a class="btn btn-info btn-sm" href="editar_financeiro.php?id=' . $row["id"] . '" Title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
        </td>
        </tr>';
    }
    echo $output;
} else {
    echo 'nao encontrado';
}

?>