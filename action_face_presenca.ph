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

    try {
        $conexao = conexao::getInstance();

        // Retrieve matricula from tab_membros using id
        $sql = 'SELECT matricula FROM tab_membros WHERE id = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', $id);
        $stm->execute();
        $matricula = $stm->fetchColumn();

        if (!$matricula) {
            echo json_encode(["error" => "❌ No matching member found."]);
            exit;
        }

        // Ensure the directory exists
        $directory = "face_presenca";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // Create the directory with full permissions
        }

        // Decode the base64 image
        $imageData = str_replace('data:image/png;base64,', '', $image_webcam);
        $imageData = base64_decode($imageData);

        $filePath = $directory . "/" . $filename . ".png";

        // Overwrite existing file if it exists
        if (file_put_contents($filePath, $imageData)) {
            // Insert new record into tab_face_presenca
            $sql = 'INSERT INTO tab_face_presenca (matricula, face, datahora) VALUES (:matricula, :face, NOW())';
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':matricula', $matricula);
            $stm->bindValue(':face', $filename . ".png");

            if ($stm->execute()) {
                // Log the change
                $dados_alterados = [
                    'face' => [
                        'matricula' => $matricula,
                        'novo' => $filename . ".png"
                    ]
                ];

                $alteracao = json_encode($dados_alterados, JSON_UNESCAPED_UNICODE);

                $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
                            VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';

                $stm = $conexao->prepare($sql_log);
                $stm->bindValue(':username', $_SESSION['uname']);
                $stm->bindValue(':tabela', 'tab_face_presenca');
                $stm->bindValue(':tipo_alteracao', 'insercao_face');
                $stm->bindValue(':registro_id', $matricula);
                $stm->bindValue(':registro', $alteracao);
                $stm->bindValue(':data', date('Y-m-d H:i:s'));
                $stm->execute();

                echo json_encode(["success" => "✅ Face presence recorded successfully!", "path" => $filePath]);
                exit;
            } else {
                echo json_encode(["error" => "❌ Failed to insert record into database."]);
                exit;
            }
        } else {
            echo json_encode(["error" => "❌ Failed to save image file."]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "❌ Database error: " . $e->getMessage()]);
        exit;
    }
}
?>
