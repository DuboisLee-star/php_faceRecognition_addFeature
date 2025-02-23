<?php

require 'config/conexao.php';

$conexao = conexao::getInstance();
$response = [];

    $codigo = $_GET['codigo'];
    $sql = "SELECT idusuario FROM validacao WHERE codigo = :codigo";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $idusuario = $stmt->fetch(PDO::FETCH_ASSOC)['idusuario'];
    
        $sql_habitualidade = "SELECT * FROM tab_habitualidade WHERE id = :idusuario";
        $stmt_habitualidade = $conexao->prepare($sql_habitualidade);
        $stmt_habitualidade->bindParam(':idusuario', $idusuario);
        $stmt_habitualidade->execute();
    
        if ($stmt_habitualidade->rowCount() > 0) {
            $userData = $stmt_habitualidade->fetch(PDO::FETCH_ASSOC);

            $cr_visitante = $userData['cr_visitante'];
            $sql_armas = "SELECT data_inicial_visitante, data_final_visitante, tipo, calibre, qtdemunicoes, numsigma, pontos, modalidade, classificacao FROM tab_habitualidade WHERE cr_visitante = :cr_visitante";
            $stmt_armas = $conexao->prepare($sql_armas);
            $stmt_armas->bindParam(':cr_visitante', $cr_visitante);
            $stmt_armas->execute();
            $armasData = $stmt_armas->fetchAll(PDO::FETCH_ASSOC);
    
            $response = [
                'status' => 'Dados Validados com Sucesso!',
                'data' => $userData,
                'armas' => $armasData
            ];
        } else {
            $response = [
                'status' => 'Dados Validados com Sucesso!',
                'data' => null,
                'armas' => []
            ];
        }
    } else {
        $response = [
            'status' => 'Inválido',
            'data' => null,
            'armas' => []
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
?>