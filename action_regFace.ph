<?php
require 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Ensure session is started
    
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $filename_prefix = "face_{$id}_";

    $image_webcam = [
        isset($_POST['image_webcam_0']) ? $_POST['image_webcam_0'] : '',
        isset($_POST['image_webcam_1']) ? $_POST['image_webcam_1'] : '',
        isset($_POST['image_webcam_2']) ? $_POST['image_webcam_2'] : ''
    ];
    
    // $image_descriptors = json_decode($_POST['descriptors'], true); // Decoding JSON to an associative array
       $image_descriptors = isset($_POST['descriptors']) ? $_POST['descriptors'] : null;

    // Validate received data
    if (!$id || empty(array_filter($image_webcam)) || empty($image_descriptors)) {
        echo json_encode(["error" => "❌ Invalid data received."]);
        exit;
    }

    $filePaths = [];
    foreach ($image_webcam as $index => $image) {
        if ($image) {
            $imageData = str_replace('data:image/png;base64,', '', $image);
            $imageData = base64_decode($imageData);

            $filePath = "face_data/{$filename_prefix}{$index}.png";
            if (file_put_contents($filePath, $imageData)) {
                $filePaths[] = $filePath;
            } else {
                echo json_encode(["error" => "❌ Failed to save image {$index}.", "path" => $filePath]);
                exit;
            }
        }
    }

    try {
        $conexao = conexao::getInstance();

        // Retrieve matricula from tab_membros
        $sql = 'SELECT matricula FROM tab_membros WHERE id = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $matricula = $stm->fetchColumn();

        if (!$matricula) {
            echo json_encode(["error" => "❌ Matricula not found for given ID."]);
            exit;
        }

        // Encode image paths to JSON format
        $faceDataJson = json_encode($filePaths, JSON_UNESCAPED_SLASHES);

        // Check if the matricula already exists in tab_face_reg
        $sql = 'SELECT COUNT(*) FROM tab_face_reg WHERE matricula = :matricula';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':matricula', $matricula, PDO::PARAM_STR);
        $stm->execute();
        $exists = $stm->fetchColumn();

        if ($exists) {
            // Update existing record
            $sql = 'UPDATE tab_face_reg SET face_data = :face_data, descriptors = :descriptors WHERE matricula = :matricula';
        } else {
            // Insert new record
            $sql = 'INSERT INTO tab_face_reg (matricula, face_data, descriptors) VALUES (:matricula, :face_data, :descriptors)';
        }

        $stm = $conexao->prepare($sql);
        $stm->bindValue(':matricula', $matricula, PDO::PARAM_STR);
        $stm->bindValue(':face_data', $faceDataJson, PDO::PARAM_STR);
        $stm->bindValue(':descriptors', $image_descriptors, PDO::PARAM_STR);

        if ($stm->execute()) {
            echo json_encode(["success" => true, "message" => "✅ Face data saved successfully!", "matricula" => $matricula]);
        } else {
            echo json_encode(["error" => "❌ Database operation failed."]);
        }
        exit;
    } catch (PDOException $e) {
        foreach ($filePaths as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        echo json_encode([
            "error" => "❌ Database error: " . $e->getMessage(),
            "deleted_files" => $filePaths
        ]);
        
    }
    
    
    echo json_encode([
        "success" => "✅ Files saved successfully.",
        "files" => $filePaths
    ], JSON_PRETTY_PRINT);

    exit;
}

    // if (json_last_error() !== JSON_ERROR_NONE) {
    //     echo json_encode(["success" => false, "error" => "Invalid JSON: " . json_last_error_msg()]);
    //     exit;
    // }
    
    // echo json_encode([
    //     "success" => true,
    //     "message" => "Face descriptors received.",
    //     "descriptors" => $image_descriptors
    // ]);
    // exit;
?>
