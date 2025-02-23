<?php
// Inclui o arquivo de configuração de conexão
require_once 'config/conexao.php';

// Usa as constantes definidas no arquivo de conexão
$host = HOST;
$user = USER;
$pass = PASSWORD;
$dbname = DBNAME;

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir as tabelas que você deseja exportar
$tabelas = ['tab_membros', 'tab_habitualidade', 'tab_armas', 'tab_financeiro_2',  'tab_compras'];

// Inicializa o conteúdo do backup
$backup_content = "SET FOREIGN_KEY_CHECKS=0;\n";

// Loop através das tabelas para gerar os dados de cada uma
foreach ($tabelas as $tabela) {
    // Adicionar estrutura da tabela
    $query_estrutura = "SHOW CREATE TABLE `$tabela`";
    $resultado_estrutura = $conn->query($query_estrutura);

    if ($resultado_estrutura) {
        $linha = $resultado_estrutura->fetch_assoc();
        $backup_content .= "\n\n" . $linha['Create Table'] . ";\n\n";
    }

    // Adicionar dados da tabela
    $query_dados = "SELECT * FROM `$tabela`";
    $resultado_dados = $conn->query($query_dados);

    if ($resultado_dados) {
        while ($linha = $resultado_dados->fetch_assoc()) {
            $valores = array_map(function ($valor) use ($conn) {
                return isset($valor) ? "'" . $conn->real_escape_string($valor) . "'" : "NULL";
            }, $linha);

            $backup_content .= "INSERT INTO `$tabela` VALUES (" . implode(", ", $valores) . ");\n";
        }
    }
}

$backup_content .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

// Nome do arquivo de backup
$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

// Salvar o conteúdo em um arquivo temporário
file_put_contents($backup_file, $backup_content);

// Forçar o download do arquivo gerado
if (file_exists($backup_file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($backup_file) . '"');
    header('Content-Length: ' . filesize($backup_file));
    flush();
    readfile($backup_file);

    // Deletar o arquivo de backup após o download
    unlink($backup_file);
    exit;
} else {
    echo 'Erro ao gerar o backup.';
}

$conn->close();
?>