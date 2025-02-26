<?php
require 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Ensure session is started
    
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    // $filename = filter_input(INPUT_POST, 'filename', FILTER_SANITIZE_STRING);
    $image_webcam_0 = isset($_POST['image_webcam_0']) ? $_POST['image_webcam_0'] : '';
    $image_webcam_1 = isset($_POST['image_webcam_1']) ? $_POST['image_webcam_1'] : '';
    $image_webcam_2 = isset($_POST['image_webcam_2']) ? $_POST['image_webcam_2'] : '';

    // Validate received data
    if (!$id || (!$image_webcam_0 && !$image_webcam_1 && !$image_webcam_2)) {
        echo json_encode(["error" => "❌ Invalid data received."]);
        exit;
    }

    // Output received POST data for debugging
    header('Content-Type: application/json');
    echo json_encode([
        "id" => $id,
        "filename" => $filename,
        "image_webcam_0" => $image_webcam_0,
        "image_webcam_1" => $image_webcam_1,
        "image_webcam_2" => $image_webcam_2
    ], JSON_PRETTY_PRINT);
    
    exit;
}
?>
