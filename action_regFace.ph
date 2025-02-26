<?php
require 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Ensure session is started
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $filename = filter_input(INPUT_POST, 'filename', FILTER_SANITIZE_STRING);
    $image_webcam = isset($_POST['image_webcam']) ? $_POST['image_webcam'] : '';

    // Validate received data
    if (!$id || !$filename || !$image_webcam) {
        echo json_encode(["error" => "❌ Invalid data received."]);
        exit;
    }

    // Decode the base64 image
    $imageData = str_replace('data:image/png;base64,', '', $image_webcam);
    $imageData = base64_decode($imageData);

    $filePath = "fotos/" . $filename . ".png";

    // Overwrite existing file if it exists
    if (file_put_contents($filePath, $imageData)) {
        try {
            $conexao = conexao::getInstance();

            // Retrieve current photo for logging purposes
            $sql = 'SELECT foto FROM tab_membros WHERE id = :id';
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':id', $id);
            $stm->execute();
            $currentPhoto = $stm->fetchColumn();

            // Update the database with the new photo filename
            $sql = 'UPDATE tab_membros SET foto = :foto WHERE id = :id';
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':foto', $filename . ".png");
            $stm->bindValue(':id', $id);

            if ($stm->execute()) {
                // Log the change
                $dados_alterados = [
                    'foto' => [
                        'anterior' => $currentPhoto,
                        'novo' => $filename . ".png"
                    ]
                ];
                
                $alteracao = json_encode($dados_alterados, JSON_UNESCAPED_UNICODE);
                
                $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
                           VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';
                
                $stm = $conexao->prepare($sql_log);
                $stm->bindValue(':username', $_SESSION['uname']);
                $stm->bindValue(':tabela', 'tab_membros');
                $stm->bindValue(':tipo_alteracao', 'atualizacao_foto');
                $stm->bindValue(':registro_id', $id);
                $stm->bindValue(':registro', $alteracao);
                $stm->bindValue(':data', date('Y-m-d H:i:s'));
                $stm->execute();
                
                echo json_encode(["success" => "✅ Image updated successfully!", "path" => $filePath]);
                // header('Location: painel.php');
                exit;
            } else {
                echo json_encode(["error" => "❌ Failed to update database."]);
                exit;
            }
        } catch (PDOException $e) {
            // Delete uploaded file if database update fails
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo json_encode(["error" => "❌ Database error: " . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(["error" => "❌ Failed to save image file."]);
        exit;
    }
}
?>
