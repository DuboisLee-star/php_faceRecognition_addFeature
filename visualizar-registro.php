<?php
session_start();
require 'config.php'; // Conexão com o banco de dados

// Consulta os registros de presença e imagens
$sql = "
    SELECT 
        p.id, p.matricula, p.nome_completo, p.data, p.hora, 
        u.caminho_imagem, p.imagem_facial
    FROM 
        presencas p
    LEFT JOIN 
        uploads u ON p.id = u.presenca_id
    ORDER BY 
        p.data DESC, p.hora DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Presença</title>
    <style>
        * {
            background-color: black;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: auto;
            margin: 0;
            padding: 20px;
        }
        .container {
            text-align: center;
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            width: 90%;
            max-width: 1200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid white;
            padding: 10px;
            text-align: left;
        }
        img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #fff;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #005f73;
        }
    </style>
</head>
<body>

<!-- Lista de Registros -->
<div class="container">
    <h2>Histórico de Presença</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matrícula</th>
                    <th>Nome Completo</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Imagem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['matricula']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td><?php echo htmlspecialchars($row['hora']); ?></td>
                        <td>
                            <?php 
                                if (!empty($row['caminho_imagem'])) {
                                    echo '<img src="uploads/' . htmlspecialchars($row['caminho_imagem']) . '" alt="Imagem">';
                                } elseif (!empty($row['imagem_facial'])) {
                                    echo '<img src="' . htmlspecialchars($row['imagem_facial']) . '" alt="Imagem">';
                                } else {
                                    echo '<p>Sem imagem</p>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum registro encontrado.</p>
    <?php endif; ?>

    <a href="https://sistema.hostmarq.com.br/painel.php" class="back-button">Voltar ao Painel</a>
</div>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>