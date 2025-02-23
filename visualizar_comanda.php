<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config/config.php';
require 'config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit();
}

// Captura o número da comanda da URL
$numero_comanda = isset($_GET['numero_comanda']) ? $_GET['numero_comanda'] : '';

// Inicializa a variável de conexão
$conexao = conexao::getInstance();

// Prepara a consulta SQL para obter os detalhes da comanda
$sql = 'SELECT * FROM tab_comanda WHERE numero_comanda = :numero_comanda';
$stm = $conexao->prepare($sql);
$stm->bindParam(':numero_comanda', $numero_comanda);
$stm->execute();
$comanda = $stm->fetch(PDO::FETCH_OBJ);

if (!$comanda) {
    echo '<div class="alert alert-danger">Comanda não encontrada.</div>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Comanda</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Detalhes da Comanda</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto/Serviço</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Processa e exibe os itens da comanda
                $produtos_servicos = explode(',', $comanda->produto_servico);
                $quantidades = explode(',', $comanda->qtde);
                $valores = explode(',', $comanda->valor);

                foreach ($produtos_servicos as $index => $produto) {
                    $quantidade = isset($quantidades[$index]) ? $quantidades[$index] : 0;
                    $valor = isset($valores[$index]) ? $valores[$index] : 0;
                    $total_item = $quantidade * $valor;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($produto) . "</td>";
                    echo "<td>" . htmlspecialchars($quantidade) . "</td>";
                    echo "<td>" . 'R$ ' . number_format($valor, 2, ',', '.') . "</td>";
                    echo "<td>" . 'R$ ' . number_format($total_item, 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>