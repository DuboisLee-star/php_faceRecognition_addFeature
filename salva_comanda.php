<?php 

include "config/config.php";
require 'config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit();
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Inicializa a variável de sessão para armazenar os itens da comanda, se ainda não estiver definida
if (!isset($_SESSION['comanda_itens'])) {
    $_SESSION['comanda_itens'] = [];
}

// Verifica se o formulário foi submetido
if (isset($_POST['adicionar_item'])) {
    $numero_comanda = $_POST['numero_comanda'];
    $produto_servico = $_POST['produto_servico'];
    $qtde = $_POST['qtde'];
    $valor = $_POST['valor'];
    $total = $_POST['total'];
    
    // Convertendo vírgulas para pontos
    $valor = str_replace(',', '.', $valor);
    $total = str_replace(',', '.', $total);

    // Convertendo para float para garantir que o backend receba o formato correto
    $valor = floatval($valor);
    $total = floatval($total);
    
    // Debug: Verificar os valores após a conversão
    var_dump($valor, $total);    
    
    // Verifica se o valor e o total não são 0.00 antes de adicionar à sessão
    if ($valor !== 0.0 && $total !== 0.0) {  // Comparação numérica
        // Adiciona o item à sessão
        $_SESSION['comanda_itens'][] = [
            'numero_comanda' => $numero_comanda,
            'produto_servico' => $produto_servico,
            'qtde' => $qtde,
            'valor' => $valor,
            'total' => $total,
        ];
    } else {
        echo "Valores inválidos: valor e total não podem ser 0,00.";
    }
}

// Inicializa a variável de conexão
$conexao = conexao::getInstance();

// Insere os itens da comanda na base de dados
if (!empty($_SESSION['comanda_itens'])) {
    foreach ($_SESSION['comanda_itens'] as $item) {
        $sql_inserir_item = 'INSERT INTO tab_comanda (numero_comanda, produto_servico, qtde, valor, total) VALUES (:numero_comanda, :produto_servico, :qtde, :valor, :total)';
        $stm_inserir_item = $conexao->prepare($sql_inserir_item);
        $stm_inserir_item->bindValue(':numero_comanda', $item['numero_comanda']);
        $stm_inserir_item->bindValue(':produto_servico', $item['produto_servico']);
        $stm_inserir_item->bindValue(':qtde', $item['qtde']);
        $stm_inserir_item->bindValue(':valor', $item['valor']);
        $stm_inserir_item->bindValue(':total', $item['total']);

        try {
            $stm_inserir_item->execute();
        } catch (PDOException $e) {
            // Melhorar o feedback ao usuário
            echo 'Erro ao inserir item na comanda: ' . htmlspecialchars($e->getMessage());
        }
    }

    // Limpa os itens da comanda após a inserção
    $_SESSION['comanda_itens'] = [];

    // Redireciona para a página de comandas após a inserção
    header('Location: comanda.php');
    exit();
}
?>