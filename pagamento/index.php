<?php
$atirador = false;
include $_SERVER['DOCUMENT_ROOT']."/config/conexao.php";
require_once('app/config.php');
$app = true;
$metodo = isset($_GET['metodo']) ? $_GET['metodo'] : false;
$data = isset($_GET['c']) ? $_GET['c'] : false;

$dados_cliente = unserialize(base64_decode($data));
$mes_competencia = $dados_cliente['mes'];
$ano_competencia = $dados_cliente['ano'];

//print_r($dados_cliente);

// dados do atirador
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_membros WHERE matricula = :matricula';
$stm = $conexao->prepare($sql);
$stm->bindValue(':matricula', $dados_cliente['matricula']);
$stm->execute();
$cliente = $stm->fetch(PDO::FETCH_OBJ);

// dados do clube
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$config = $stm->fetch(PDO::FETCH_OBJ);

$descricao_plano = "";
if($config->plano == "semplano"){
    exit("Nenhum plano configurado.");
}else
if($config->plano == "personalizado"){
    $valor_plano = $config->valor_plano_personalizado;
    $descricao_plano = "Plano ".$config->nome_plano_personalizado;
}else
if($config->plano == "fixo"){
    $valor_plano = $config->valor_plano_fixo;
    $descricao_plano = "Plano Fixo";
}else
if($config->plano == "todos"){
    
    switch(substr($cliente->plano,0,1)){
        case "O":
            $valor_plano = $config->valor_plano_ouro;
            $descricao_plano = "Plano Ouro";
        break;
        case "P":
            $valor_plano = $config->valor_plano_prata;
            $descricao_plano = "Plano Prata";
        break;
        case "B":
            $valor_plano = $config->valor_plano_bronze;
            $descricao_plano = "Plano Bronze";
        break;
        default:
            $valor_plano = 0.00;
            $descricao_plano = "Sem Plano";
        break;
    }
    
}
?><!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= TITULO; ?> | Pagamento</title>
    <link rel="shortcut icon" href="<?= URL_SITE; ?>/pagamento/image/favicon.png">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <img src="<?= URL_SITE; ?>/img/logo_site.png" alt="Clube de Tiro" height="70">
                <span class="fs-4 p-2">| <?= TITULO; ?> <i class="fa fa-lock text-success fs-6"></i></span>
            </a>
            <h4 class="align-self-center m-0 p-0"><?= TEXTO; ?></h4>
        </header>
    

        <main>
            <?php
            switch($metodo){
                case 'pix':
                        require_once('app/pix.php');
                    break;
                case 'cartao':
                        require_once('app/cartao.php');
                    break;
                case 'boleto':
                        require_once('app/boleto.php');
                    break;
                default:
                    require_once('app/main.php');
                break;
            }
            ?>
        </main>

        <footer>
            v: 1.0.0
        </footer>


    </div>

    <div class="loading">
        <div class="lds-dual-ring"></div>
    </div>

    <script>
        const selecionaPagamento = (metodo) => {
            $(".loading").fadeIn(100);
            window.location='?c=<?= $data; ?>&metodo='+metodo;
        }
    </script>

</body>
</html>