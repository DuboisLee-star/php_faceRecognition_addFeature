<?php

/*01 ------------------------------------------------- */

$dominios[] = array(
    'host' => '162.240.153.81',
    'user' => 'remoto@acgunclubedetiro.com.br',
    'pass' => '*Wer3004ber',
    'root' => '/sistema.hostmarq.com.br/'
);

/*02 ------------------------------------------------- */

$dominios[] = array(
    'host' => '162.240.153.81',
    'user' => 'remoto@acti.hostmarq.com.br',
    'pass' => '*Wer3004ber',
    'root' => '/sistema.hostmarq.com.br/'
);

/*03 ------------------------------------------------- */

$dominios[] = array(
    'host' => '162.240.153.81',
    'user' => 'remoto@alfaclubetiroibotiramense.com.br',
    'pass' => '*Wer3004ber',
    'root' => '/sistema.hostmarq.com.br/'
);

if($_SERVER['REQUEST_METHOD'] == "POST"){

    ini_set('default_socket_timeout', 120);
    
    foreach($dominios as $key => $Dominio){
        echo "<b>[{$Dominio['user']}]</b><br>";

        try{
            // testa conexão
            $conexaoFTP = ftp_connect($Dominio['host']);

            if(@ftp_login($conexaoFTP, $Dominio['user'], $Dominio['pass'])){
                
                $arquivos = explode("\n", $_POST['arquivos']);

                foreach($arquivos as $arquivo){
                    $arquivo = trim($arquivo);
                    if(strlen(trim($arquivo)) > 0){

                        $arquivo_local  = '/home/wwhost'.$arquivo;
                        $dados_caminho  = explode('/', $arquivo);
                        $nome_arquivo   = end($dados_caminho);
                        $caminho_remoto = str_replace($nome_arquivo, '', $arquivo);
                        $caminho_remoto = str_replace('/sistema.hostmarq.com.br/', $Dominio['root'], $caminho_remoto);

                        // verifica se o arquivo existe
                        if(file_exists($arquivo_local)){

                            ftp_pasv($conexaoFTP, true);

                            // muda para o diretório remoto
                            ftp_chdir($conexaoFTP, $caminho_remoto);

                            // envia o arquivo
                            $upload = ftp_put($conexaoFTP, $nome_arquivo, $arquivo_local, FTP_BINARY);

                            echo 'Arquivo enviado com sucesso ('.$arquivo.').<br>';

                        }else{
                            echo '<span style="color: red;">Arquivo não existe ('.$arquivo.');</span><br>';
                        }

                    }
                }

                echo '<span style="color: green;">Atualização realizada com sucesso!</span><br>';

            }else{
                echo '<span style="color: red;">Falha de login</span>';
            }

            echo '<hr>';

            ftp_close($conexaoFTP);
        } catch (Exception $e) {

            echo 'Erro: '.$e;

        }

    }

    echo '<br><a href="updatesystem.php">voltar</a>';
    

    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12"><br>
                <div align=left><img src="https://sistema.hostmarq.com.br/img/logo_site_black.png" height=120>
                <h3>Gerenciador</h3>
                <p>Atualização de arquivos (um por linha).</p>

                <form action="" method="post">
                    <div class="col-md-12">
                        <textarea name="arquivos" id="arquivos" cols="30" rows="10" class="form-control" placeholder="/sistema.hostmarq.com.br/nome_do_arquivo.php"></textarea>
                    </div>
                    <div class="col-md-12 text-center mt-5">
                        <button type="submit" class="btn btn-primary">Atualizar Domínios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>