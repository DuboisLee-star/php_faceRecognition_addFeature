<?php
session_start();
require 'conexao.php'; // Arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos esperados foram recebidos
    if (
        isset($_POST['usuario_id'], $_POST['nome_completo'], $_POST['matricula'], 
              $_POST['imagem_facial'], $_POST['tipo_registro'])
    ) {
        $usuario_id = intval($_POST['usuario_id']); // Garante que é um número inteiro
        $nome_completo = trim($_POST['nome_completo']);
        $matricula = trim($_POST['matricula']);
        $imagem_facial = trim($_POST['imagem_facial']);
        $tipo_registro = trim($_POST['tipo_registro']);
        $data_hora = date('Y-m-d H:i:s');

        // Verifica se a conexão com o banco foi estabelecida
        if (!$conn) {
            die("Erro de conexão com o banco de dados.");
        }

        try {
            $query = "INSERT INTO registros_faciais (usuario_id, nome_completo, matricula, imagem_facial, tipo, data_hora) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isssss", $usuario_id, $nome_completo, $matricula, $imagem_facial, $tipo_registro, $data_hora);
            
            if ($stmt->execute()) {
                // Verifica o tipo de registro e redireciona ou exibe mensagem
                if ($tipo_registro === 'habitualidade') {
                    $usuario_id_encoded = urlencode($usuario_id);
                    $data_hora_encoded = urlencode($data_hora);
                    header("Location: lancamento_habitualidade.php?usuario_id={$usuario_id_encoded}&data_hora={$data_hora_encoded}");
                    exit();
                } else {
                    echo "Registro de presença realizado com sucesso.";
                }
            } else {
                echo "Erro ao registrar presença: " . $stmt->error;
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Erro ao processar o registro: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "Erro: Dados incompletos.";
    }
} else {
    die("Acesso negado.");
}