<?php
// Conexão com o banco de dados (ajuste as configurações)
$host = "localhost";
$usuario = "wwhost_hostmarq";
$senha = "?gOP?PHH}AwHH{{{P??OT0gG";
$banco = "wwhost_hostmarq";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conexao->connect_error);
}

// Processar os números de telefone
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefones = $_POST["telefones"];
    $telefones = explode("\n", $telefones);

    foreach ($telefones as $telefone) {
        $telefone = trim($telefone);
        if (!empty($telefone)) {
            // Inserir na tabela
            $telefone = $conexao->real_escape_string($telefone);
            $bloqueio = "D";
            $sql = "INSERT INTO tab_membros (telefone, bloqueio) VALUES ('$telefone', '$bloqueio')";
            $resultado = $conexao->query($sql);
        }
    }
}

$conexao->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultado da Importação</title>
</head>
<body>
    <h1>Resultado da Importação</h1>
    <p>Números de telefone foram importados com sucesso.</p>
    <a href="importar_telefones.php">Voltar</a>
</body>
</html>