<?php
header('Content-Type: application/json');
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['image'])) {
    echo json_encode(["status" => "error", "message" => "Imagem não recebida"]);
    exit;
}

$image = $data['image'];

// Removendo o prefixo 'data:image/png;base64,' da string base64
$image = str_replace('data:image/png;base64,', '', $image);
$image = str_replace(' ', '+', $image);
$imageData = base64_decode($image);

$filename = "uploads/" . uniqid() . ".png";

if (!file_put_contents($filename, $imageData)) {
    echo json_encode(["status" => "error", "message" => "Falha ao salvar a imagem"]);
    exit;
}

// Inserindo no banco de dados
$stmt = $conn->prepare("INSERT INTO imagens (http://localhost/phpmyadmin/) VALUES (?)");
$stmt->bind_param("s", $filename);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Imagem salva com sucesso"]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao salvar no banco: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>

