<?php
include 'config.php';

$sql = "SELECT caminho, nome_completo, data_hora FROM registros ORDER BY data_hora DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Fotos</title>
    <style>
        .gallery { display: flex; flex-wrap: wrap; }
        .gallery div { margin: 10px; text-align: center; }
        img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>

<h2>Galeria de Fotos</h2>
<div class="gallery">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div>
            <img src="<?php echo $row['caminho']; ?>" alt="Foto de <?php echo $row['nome_completo']; ?>">
            <p><?php echo $row['nome_completo']; ?></p>
            <small><?php echo $row['data_hora']; ?></small>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
