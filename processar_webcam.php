<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['image'])) {
    echo json_encode(["status" => "error", "message" => "Imagem não recebida"]);
    exit;
}

$image = $data['image'];
$image = str_replace('data:image/png;base64,', '', $image);
$image = str_replace(' ', '+', $image);
$imageData = base64_decode($image);

$filename = "uploads/webcam_" . uniqid() . ".png";
file_put_contents($filename, $imageData);

$sql = "INSERT INTO imagens (caminho, descricao) VALUES ('$filename', 'Imagem da Webcam')";
$conn->query($sql);

echo json_encode(["status" => "success", "message" => "Imagem capturada com sucesso"]);
?>
