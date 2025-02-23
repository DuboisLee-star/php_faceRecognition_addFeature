<?php
// Configuração do banco de dados
$host = 'localhost'; 
$dbname = 'wwhost_hostmarq'; 
$username = 'wwhost_hostmarq'; 
$password = '?gOP?PHH}AwHH{{{P??OT0gG'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

$tabela = 'tab_habitualidade'; 
$query = $pdo->query("SELECT * FROM $tabela where id=32");
$dados = $query->fetchAll(PDO::FETCH_ASSOC);

$caminhoImagem = 'imagem/imagem.png';

if (!file_exists($caminhoImagem)) {
    die("Erro: O arquivo de imagem não foi encontrado.");
}

$image = imagecreatefrompng($caminhoImagem);
if (!$image) {
    die("Erro: Não foi possível criar uma imagem a partir do arquivo PNG.");
}

$corTexto = imagecolorallocate($image, 0, 0, 0); 

$posX = 8;
$posY = 1800;
$larguraCelula = 550; 
$alturaCelula = 100; 
$espacoEntreLinhas = $alturaCelula; 

$caminhoFonte = 'fonts/arial.ttf';
$tamanhoFonte = 40; 

if (!file_exists($caminhoFonte)) {
    die("Erro: O arquivo de fonte não foi encontrado.");
}

$headers = ['MODALIDADE', 'DISPAROS', 'ARMAS(S) UTILIZADAS(S)', 'PONTOS', 'CLASSIFICAÇÃO'];

foreach ($headers as $index => $header) {
    imagettftext($image, $tamanhoFonte, 0, $posX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $header);
}
$posY += $alturaCelula;

foreach ($dados as $row) {
    $valores = [
        $row['calibre'],
        $row['qtdemunicoes'],
        $row['sigma'],
        $row['pontos'],
        $row['evento']
    ];

    foreach ($valores as $index => $valor) {
        imagettftext($image, $tamanhoFonte, 0, $posX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $valor);
    }

    $posY += $espacoEntreLinhas;
}



foreach ($dados as $row) {
    $texto = "LOCAL DA PROVA:" . $row['id'] . ", DADOS DO CLUBE: " . $row['titulo'];
    imagettftext($image,40, 20, $posX, 1355, $corTexto, $caminhoFonte, $texto);
    $posY += 250;
}


$imagePath = 'imagem_saida.png';
imagepng($image, $imagePath);


imagedestroy($image);

require('fdf/fpdf.php');


list($larguraOriginal, $alturaOriginal) = getimagesize($imagePath);


$pdf = new FPDF('P', 'mm', array($larguraOriginal / 3.78, $alturaOriginal / 3.78)); // Ajusta o tamanho da página ao tamanho da imagem
$pdf->AddPage();


$pdf->Image($imagePath, 0, 0, $larguraOriginal / 3.78, $alturaOriginal / 3.78, 'PNG'); // Ajusta a imagem ao tamanho da página

$pdfOutput = 'imagem.pdf';

$pdf->Output($pdfOutput, 'I');
?>
