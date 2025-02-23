<?php
header('Content-Type: application/json; charset=utf-8');
$atirador = false;
include('whatsapp/whatsapp.php');

try {
    $dados = json_decode(file_get_contents('php://input'), true);

    $whatsapp             = new Whatsapp();
    $whatsapp->matricula  = $dados['matricula'];
    $whatsapp->number     = $dados['numero'];
    $whatsapp->message    = $dados['mensagem'];
    $whatsapp->referencia = $dados['referencia'];
    $whatsapp->tipo_envio = isset($dados['tipo_envio']) ? $dados['tipo_envio'] : "T";
    
    $result = $whatsapp->cria_fila();

    if($result){
        $response['sucesso'] = true;
        $response['message'] = 'Mensagem enviada com sucesso';
    }else{
        $response['error'] = true;
        $response['message'] = 'Falha ao gravar mensagem.';
    }

    echo json_encode($response);
    

} catch (Exception $e) {
    $response['error'] = true;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}

