<?php
    
    include "config/config.php";
    
    parse_str($_POST['data'], $dados);
    
    $cpf =  str_replace(".","",$dados['cpf_usuario']);
    $cpf =  str_replace("-","",$cpf);

    $sql_query = "select nome, data_nascimento, mae from tab_membros where cpf='".$cpf."'";
	$result = mysqli_query($con,$sql_query);
    
    $nome = "";

    while ($linha = mysqli_fetch_array($result)){
        $nome = $linha['nome'];
        $nascimento = $linha['data_nascimento'];
        $mae = $linha['mae'];
    }
    
    $resposta['nome'] = $nome;
    $resposta['nascimento'] = $nascimento;
    $resposta['mae'] = $mae;
    echo json_encode($resposta);

?>
