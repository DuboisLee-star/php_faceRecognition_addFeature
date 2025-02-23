<?php
require 'config/conexao.php';

$idusuario = $_GET['id'];
$conexao = conexao::getInstance();

$sqlValidacao = "SELECT codigo FROM validacao WHERE idusuario = :idusuario";
$stmtValidacao = $conexao->prepare($sqlValidacao);
$stmtValidacao->bindParam(':idusuario', $idusuario);
$stmtValidacao->execute();
$codigoExistente = $stmtValidacao->fetch(PDO::FETCH_ASSOC);

if ($codigoExistente) {
    $codigo = $codigoExistente['codigo'];
} else {
    
    $codigo = gerarCodigoAleatorio(10);

    $sqlInsert = "INSERT INTO validacao (codigo, idusuario) VALUES (:codigo, :idusuario)";
    $stmtInsert = $conexao->prepare($sqlInsert);
    $stmtInsert->bindParam(':codigo', $codigo);
    $stmtInsert->bindParam(':idusuario', $idusuario);
    $stmtInsert->execute();
}

$tabela = 'tab_habitualidade';
$query = $conexao->prepare("SELECT * FROM $tabela WHERE id = :idusuario");
$query->bindParam(':idusuario', $idusuario);
$query->execute();
$dados = $query->fetchAll(PDO::FETCH_ASSOC);

// Verifique se a consulta retornou resultados antes de continuar

    $cr_visitante = $dados[0]['cr_visitante'];

// Consulta para buscar todos os registros com o mesmo cr_visitante E do mesmo idusuario
$query = $conexao->prepare("SELECT * FROM $tabela WHERE cr_visitante = :cr_visitante AND id = :idusuario");
$query->bindParam(':cr_visitante', $cr_visitante);
$query->bindParam(':idusuario', $idusuario);
$query->execute();
$dados_agrupados = $query->fetchAll(PDO::FETCH_ASSOC);




$query_clube = $conexao->query("SELECT clube_cnpj, clube_cr, clube_nome FROM info_clube");
$dados_clube = $query_clube->fetch(PDO::FETCH_ASSOC);

$caminhoImagem = 'img/imagem.png';

if (!file_exists($caminhoImagem)) {
    die("Erro: O arquivo de imagem não foi encontrado.");
}

$image = imagecreatefrompng($caminhoImagem);
if (!$image) {
    die("Erro: Não foi possível criar uma imagem a partir do arquivo PNG.");
}

$corTexto = imagecolorallocate($image, 0, 0, 0);
$corTextoB = imagecolorallocate($image, 255, 255, 255); // Branco

$posX = 7;
$posY = 550;
$larguraCelula = 500;
$alturaCelula = 100;
$espacoEntreLinhas = $alturaCelula;

$caminhoFonte = 'fonts/arial.ttf';
$tamanhoFonte = 25;

if (!file_exists($caminhoFonte)) {
    die("Erro: O arquivo de fonte não foi encontrado.");
}

$texto1 = "DADOS DO ATIRADOR";
imagettftext($image, 40, 0, 900, 755, $corTextoB, $caminhoFonte, $texto1);
$posY += 250;

$texto2 = "DADOS DA PROVA";
imagettftext($image, 40, 0, 968, 1105, $corTextoB, $caminhoFonte, $texto2);
$posY += 250;

$texto3 = "CLASSIFICAÇÃO";
imagettftext($image, 40, 0, 960, 1695, $corTextoB, $caminhoFonte, $texto3);
$posY += 250;

$texto4 = "AUTENTICAÇÃO DO DOCUMENTO";
imagettftext($image, 40, 0, 750, 2578, $corTextoB, $caminhoFonte, $texto4);
$posY += 250;

foreach ($dados as $row) {
    $texto5 = $row['evento'];
    imagettftext($image, 40, 0, 60, 1220, $corTexto, $caminhoFonte, $texto5);
    $posY += 250;
}

// Gerar o QR Code
require 'phpqrcode/qrlib.php';
$qrTexto = $url_site . "validacao.php?codigo=" . $codigo;
$caminhoQRCode = 'qrcode.png';
QRcode::png($qrTexto, $caminhoQRCode, QR_ECLEVEL_L, 10);

// Inserir o QR Code na imagem
$qrImage = imagecreatefrompng($caminhoQRCode);
if (!$qrImage) {
    die("Erro: Não foi possível criar uma imagem a partir do arquivo QR Code.");
}

// Posição do QR Code na imagem
$qrX = imagesx($image) - imagesx($qrImage) - 2050; // Posição X: 20px da borda direita
$qrY = imagesy($image) - imagesy($qrImage) - 490; // Posição Y: 20px da borda inferior

// Mesclar as duas imagens
imagecopy($image, $qrImage, $qrX, $qrY, 0, 0, imagesx($qrImage), imagesy($qrImage));

$headers = ['         MODALIDADE', '          DISPAROS', '         ARMAMENTO', str_repeat(" ", 7). '        PONTOS', 'CLASSIFICAÇÃO'];

foreach ($headers as $index => $header) {
    imagettftext($image, $tamanhoFonte, 0, $posX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $header);
}
$posY += $alturaCelula;
  
foreach ($dados_agrupados as $row) {
                
                $tip=$row[tipo];
                $calib=$row[calibre];
                $numsig=$row[numsigma];
                $municao=$row[qtdemunicoes];
                $modalidade=$row[modalidade];
                $pontos=$row[pontos];
                $classificacao=$row[classificacao];
                $classificacao_array=explode(',', $classificacao);
                $pontos_array=explode(',', $pontos);
                $mod_array=explode(',',$modalidade);
                $municao_arr=explode(',',$municao);
                $tipo_arr= explode(',',$tip);
                $calib_arr=explode(',',$calib);
                $numsig_arr=explode(',', $numsig);
                $array_armas=["classificacao"=>$classificacao_array, "tipo"=>$tipo_arr, "calibre"=>$calib_arr, "numsigma"=>$numsig_arr,"municao"=>$municao_arr, "modalidade"=>$mod_array, "pontos"=>$pontos_array];
                
               
               
                foreach($array_armas[tipo] as $key=> $val_tipo){
                  
                 $armasUtilizadas= $val_tipo." | ".$array_armas[calibre][$key]." | ".$array_armas[numsigma][$key];
            
                 
              $valores = [
        str_repeat(' ', 4) . $row['modalidade'],
        str_repeat(' ', 16) . $array_armas[municao][$key],
        $armasUtilizadas,
        str_repeat(' ', 20) . $array_armas[pontos][$key],
       $array_armas[classificacao][$key],
        ];  
        
              
   foreach ($valores as $index => $valor) {
         
    
        // Ajuste para $armasUtilizadas, movendo-o 12 espaços para a esquerda
         $adjustedPosX = ($index == 2) ? $posX - 12 : $posX;
   
            imagettftext($image, $tamanhoFonte, 0, $adjustedPosX + $index * $larguraCelula, $posY, $corTexto, $caminhoFonte, $valor);
      
    }
     $posY += $espacoEntreLinhas;
        
              }
    
 
}

foreach ($dados as $row) {
    $texto = "    LOCAL: " . $dados_clube['clube_nome'] . str_repeat(" ", 54) . " CNPJ DO CLUBE: " . $dados_clube['clube_cnpj'];
    imagettftext($image, 35, 0, $posX, 1355, $corTexto, $caminhoFonte, $texto);
    $posY += 250;
}

foreach ($dados as $row) {
    $texto = "    PERÍODO: " . date('d/m/Y', strtotime($row['data_inicial_visitante'])) . ' à ' . date('d/m/Y', strtotime($row['data_final_visitante'])) . str_repeat(" ", 30) . " CR DO CLUBE: " . $dados_clube['clube_cr'];
    imagettftext($image, 35, 0, $posX, 1545, $corTexto, $caminhoFonte, $texto);
    $posY += 250;
}

foreach ($dados as $row) {
    $texto = "    NOME: " . $row['nome_visitante'] . str_repeat(" ", 50) . " CPF: " . $row['cpf_visitante'];
    imagettftext($image, 35, 0, $posX, 860, $corTexto, $caminhoFonte, $texto);
    $posY += 250;
}

foreach ($dados as $row) {
    $texto = "    CR nº: " . $row['cr_visitante'] . str_repeat(" ", 90) . " VALIDADE CR: " . date('d/m/Y', strtotime($row['cr_visitante_validade']));
    imagettftext($image, 35, 0, $posX, 975, $corTexto, $caminhoFonte, $texto);
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

function gerarCodigoAleatorio($tamanho) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}
?>