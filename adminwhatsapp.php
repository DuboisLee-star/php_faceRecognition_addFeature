<?php

ini_set('error_reporting', E_ERROR); 
register_shutdown_function("fatal_handler"); 
function fatal_handler() { 
$error = error_get_last(); 
echo("<pre>"); 
print_r($error); 
}

$atirador = false;
include('whatsapp/whatsapp.php');
$wpp = new Whatsapp();
$status = $wpp->service_status();

if(isset($_GET['createInstance'])){
    $name = trim($_GET['createInstance']);
    if($name == ''){
        exit('Nome da instancia não informado.');
    }
    $result = $wpp->instance_create($name);
    
    if($result == 1){
        exit("<script>alert('Instancia criada com sucesso.');window.location.href='adminwhatsapp.php';</script>");
    }else{
        exit('Falha ao criar instancia.');
    }
}
?>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin WhatsApp</title>

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
            <h4 class="align-self-center m-0 p-0"><i class="fa fa-whatsapp text-success" aria-hidden="true"></i> ADMIN WhatsApp</h4>
        </header>
    

        <main>

            <div class="card">
                <div class="card-body">
                    
                    <?php					$connected = isset($status['state']) ? $status['state'] : false;
                    if($connected != 'open'){
                        // if($status['code'] == 400){

                            echo '<div class="alert alert-danger mb-5" role="alert"><i class="fa fa-times-circle" aria-hidden="true"></i> Serviço não conectado!</div>';

                            $qr_code = $wpp->generate_qrcode();

                            if(isset($qr_code['base64'])){
                                echo '<p>Escaneio o QR Code abaixo para conectar uma conta ao serviço.</p><img src="'.$qr_code['base64'].'" width="250">';
                            }

                        // }
                    }else{
                        echo '<div class="alert alert-success mb-5" role="alert"><b><i class="fa fa-check-circle" aria-hidden="true"></i> Serviço Conectado!</b></div>';
                    }
                    ?>

                </div>
            </div>
            
        </main>

        <footer>
            v: 1.0.0
        </footer>


    </div>

</body>
</html>