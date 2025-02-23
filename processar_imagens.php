<?php
include 'config.php'; // Conectar ao banco de dados

// Buscar imagens não processadas
$sql = "SELECT id, caminho FROM imagens WHERE processado = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $imagePath = "/opt/lampp/htdocs/leitor-facial/" . $row['caminho']; // Caminho completo da imagem

        // Verifica se a imagem existe
        if (!file_exists($imagePath)) {
            echo "Erro: Imagem não encontrada em $imagePath\n";
            continue;
        }

        // Executar o script Python para processar a imagem
        $command = "python3 /opt/lampp/htdocs/leitor-facial/py_scripts/process-image.py '$imagePath' 2>&1";
        $output = shell_exec($command);

        echo "Comando executado: $command\n";
        echo "Saída do comando: $output\n";

        // Atualizar banco de dados após o processamento
        $updateSQL = "UPDATE imagens SET processado = 1 WHERE id = $id";
        $conn->query($updateSQL);
    }
} else {
    echo "Nenhuma imagem para processar.\n";
}

$conn->close();
?>
