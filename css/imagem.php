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
$query = $pdo->query("SELECT id, qtdemunicoes, evento, tipo, modelo FROM $tabela where id=32");
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

$posX = 10;
$posY = 1800;
$larguraCelula = 550; 
$alturaCelula = 100; 
$espacoEntreLinhas = $alturaCelula; 

$caminhoFonte = 'fonts/arial.ttf';
$tamanhoFonte = 40; 

if (!file_exists($caminhoFonte)) {
    die("Erro: O arquivo de fonte não foi encontrado.");
}

$headers = ['Cod', 'Municoes', 'Evento', 'tipo', 'modelo'];

foreach ($headers as $index => $header) {
    imagettftext($image, $tamanhoFonte, 0, $posX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $header);
}
$posY += $alturaCelula;

foreach ($dados as $row) {
    $valores = [
        $row['id'],
        $row['qtdemunicoes'],
        $row['evento'],
        $row['tipo'],
        $row['modelo']
    ];

    foreach ($valores as $index => $valor) {
        imagettftext($image, $tamanhoFonte, 0, $posX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $valor);
    }

    $posY += $espacoEntreLinhas;
}

// Salvar a imagem gerada
$imagePath = 'imagem_saida.png';
imagepng($image, $imagePath);

// Limpar a memória
imagedestroy($image);

// Incluir a biblioteca FPDF
require('fdf/fpdf.php');

// Criar uma instância do FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Adicionar a imagem ao PDF
$pdf->Image($imagePath, 10, 10, 0, 0, 'PNG');

// Nome do arquivo PDF gerado
$pdfOutput = 'imagem.pdf';

// Enviar o PDF para o navegador
$pdf->Output($pdfOutput, 'I');
?>
