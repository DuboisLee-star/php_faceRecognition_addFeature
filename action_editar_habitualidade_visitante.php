<?php
session_start();
include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit;
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

require 'config/conexao.php';

// Verifica se o formulсrio foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e limpa os dados enviados pelo formulсrio
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    
    // Outras variсveis
    $datacadastro = isset($_POST['datacadastro']) && trim($_POST['datacadastro']) !== '' ? trim($_POST['datacadastro']) : null;
    $data_inicial_visitante = isset($_POST['data_inicial_visitante']) && trim($_POST['data_inicial_visitante']) !== '' ? trim($_POST['data_inicial_visitante']) : null;
    $data_final_visitante = isset($_POST['data_final_visitante']) && trim($_POST['data_final_visitante']) !== '' ? trim($_POST['data_final_visitante']) : null;
    $cr_visitante = isset($_POST['cr_visitante']) ? trim($_POST['cr_visitante']) : '';
    $cr_visitante_validade = isset($_POST['cr_visitante_validade']) ? trim($_POST['cr_visitante_validade']) : '';
    $cpf_visitante = isset($_POST['cpf_visitante']) ? trim($_POST['cpf_visitante']) : '';
    $zap_visitante = isset($_POST['zap_visitante']) ? trim($_POST['zap_visitante']) : '';
    $nome_visitante = isset($_POST['nome_visitante']) ? trim($_POST['nome_visitante']) : '';
    $evento = isset($_POST['evento']) ? trim($_POST['evento']) : '';
    $modalidade = isset($_POST['modalidade']) ? trim($_POST['modalidade']) : '';
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';    
    $calibre = isset($_POST['calibre']) ? trim($_POST['calibre']) : '';
    $numsigma = isset($_POST['numsigma']) ? trim($_POST['numsigma']) : '';
    $qtdemunicoes = isset($_POST['qtdemunicoes']) ? trim($_POST['qtdemunicoes']) : '';
    $pontos = isset($_POST['pontos']) ? trim($_POST['pontos']) : '';
    $classificacao = isset($_POST['classificacao']) ? trim($_POST['classificacao']) : '';    

    // Verifica se o ID щ numщrico
    if (!empty($id) && is_numeric($id)) {
        try {
            $conexao = conexao::getInstance();

            // Atualiza os dados no banco de dados
            $sql = 'UPDATE tab_habitualidade SET 
                datacadastro=:datacadastro,
                data_inicial_visitante = :data_inicial_visitante,                
                data_final_visitante = :data_final_visitante,
                cr_visitante = :cr_visitante, 
                cr_visitante_validade = :cr_visitante_validade, 
                cpf_visitante = :cpf_visitante, 
                zap_visitante = :zap_visitante, 
                nome_visitante = :nome_visitante, 
                evento = :evento, 
                modalidade = :modalidade, 
                tipo = :tipo,                 
                calibre = :calibre, 
                numsigma = :numsigma, 
                qtdemunicoes = :qtdemunicoes,
                pontos = :pontos,
                classificacao = :classificacao                 
                WHERE id = :id';

            $stmt = $conexao->prepare($sql);
           $stmt->bindParam(':datacadastro', $datacadastro);
            $stmt->bindParam(':data_inicial_visitante', $data_inicial_visitante);
            $stmt->bindParam(':data_final_visitante', $data_final_visitante);            
            $stmt->bindParam(':cr_visitante', $cr_visitante);
            $stmt->bindParam(':cr_visitante_validade', $cr_visitante_validade);
            $stmt->bindParam(':cpf_visitante', $cpf_visitante);
            $stmt->bindParam(':zap_visitante', $zap_visitante);
            $stmt->bindParam(':nome_visitante', $nome_visitante);
            $stmt->bindParam(':evento', $evento);
            $stmt->bindParam(':modalidade', $modalidade);
            $stmt->bindParam(':tipo', $tipo);            
            $stmt->bindParam(':calibre', $calibre);
            $stmt->bindParam(':numsigma', $numsigma);
            $stmt->bindParam(':qtdemunicoes', $qtdemunicoes);
            $stmt->bindParam(':pontos', $pontos);
            $stmt->bindParam(':classificacao', $classificacao);            
            $stmt->bindParam(':id', $id);

            // Executa a atualizaчуo
            $stmt->execute();

            // Redireciona para a pсgina de painel ou de sucesso
            header('Location: painel_visitantes.php');
            exit;

        } catch (PDOException $e) {
            // Exibe o erro em caso de falha na execuчуo da query
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "ID invсlido.";
    }
} else {
    echo "Nenhum dado enviado.";
}
?>