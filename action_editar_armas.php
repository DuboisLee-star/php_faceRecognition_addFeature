<?php
include 'config/config.php';
require 'config/conexao.php';

// Verifica se o usuсrio estс logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit;
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = isset($_POST['id_cliente']) ? (int)$_POST['id_cliente'] : 0;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $modelo = isset($_POST['modelo']) ? $_POST['moodelo'] : '';
    $calibre = isset($_POST['calibre']) ? $_POST['calibre'] : '';
    $numsigma = isset($_POST['numsigma']) ? $_POST['numsigma'] : '';    
    $validade_craf = isset($_POST['validade_craf']) ? date('Y-m-d', strtotime(str_replace('/', '-', $_POST['validade_craf']))) : '';
    $validade_gt = isset($_POST['validade_gt']) ? date('Y-m-d', strtotime(str_replace('/', '-', $_POST['validade_gt']))) : '';

    // Verifica se o ID do cliente щ vсlido
    if ($id_cliente > 0) {
        // Atualiza os dados da arma no banco de dados
        $conexao = conexao::getInstance();
        $sql = "UPDATE tab_armas SET 
                    tipo = :tipo, 
                    modelo = :modelo,
                    calibre = :calibre, 
                    numsigma = :numsigma,
                    validade_craf = :validade_craf, 
                    validade_gt = :validade_gt 
                WHERE id_membro = :id_cliente";
                
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':tipo', $tipo);
        $stm->bindValue(':modelo', $modelo);        
        $stm->bindValue(':calibre', $calibre);
        $stm->bindValue(':numsigma', $numsigma);        
        $stm->bindValue(':validade_craf', $validade_craf);
        $stm->bindValue(':validade_gt', $validade_gt);
        $stm->bindValue(':id_cliente', $id_cliente);

        // Executa a query
        $resultado = $stm->execute();

        if ($resultado) {
            // Redireciona para o arquivo armas.php passando o ID do cliente
            header("Location: armas.php?id=$id_cliente");
            exit;
        } else {
            echo "Erro ao atualizar os dados.";
        }
    } else {
        echo "ID do cliente invсlido.";
    }
} else {
    echo "Mщtodo de requisiчуo invсlido.";
}
?>