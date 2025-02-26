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

    // Validate received data
    if (!$id || array_filter($image_webcam) === []) {
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
                echo json_encode(["error" => "❌ Failed to save image {$index}.",  "path"=>filePath ]);
                exit;
            }
        }
    }

    echo json_encode([
        "success" => "✅ Files saved successfully.",
        "files" => $filePaths
    ], JSON_PRETTY_PRINT);

    exit;
}
?>
