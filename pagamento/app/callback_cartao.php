<?php
$atirador = false;
include $_SERVER['DOCUMENT_ROOT']."/config/conexao.php";
require_once('config.php');
//header('Content-Type: application/json');
$conteudo = json_decode(file_get_contents("php://input"));

if(strlen(trim($conteudo->installments)) <= 0) $conteudo->installments = 1;

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "transaction_amount": '.$conteudo->transaction_amount.',
         "token": "'.$conteudo->token.'",
         "description": "'.$conteudo->description.'",
         "installments": '.$conteudo->installments.',
         "payment_method_id": "'.$conteudo->payment_method_id.'",
         "issuer_id": '.$conteudo->issuer_id.',
         "notification_url": "'.URL_SITE.'/pagamento/app/callback_pix",
         "payer": {
           "email": "'.$conteudo->payer->email.'"
         }
    }',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.ACCESS_TOKEN        
    ),
));
$response = curl_exec($curl);
$resultado = json_decode($response);
curl_close($curl);

echo $response;


// grava no banco de dados
$conexao = conexao::getInstance();
$sql = 'INSERT INTO tab_pagamentos
    (
        id_pagamento,
        matricula,
        valor,
        status,
        mes,
        ano,
        plano,
        meiopagamento,
        datacadastro
    ) VALUES (
        :id_pagamento,
        :matricula,
        :valor,
        :status,
        :mes,
        :ano,
        :plano,
        :meiopagamento,
        :datacadastro
    )
';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id_pagamento', $resultado->id);
$stm->bindValue(':matricula', $conteudo->matricula);
$stm->bindValue(':valor', $conteudo->transaction_amount);
$stm->bindValue(':status', $resultado->status);
$stm->bindValue(':mes', $conteudo->mes_competencia);
$stm->bindValue(':ano', $conteudo->ano_competencia);
$stm->bindValue(':plano', $conteudo->plano_pgto);
$stm->bindValue(':meiopagamento', 'cartao ('.$conteudo->installments.'x)');
$stm->bindValue(':datacadastro', date('Y-m-d H:i:s'));

$stm->execute();

if($resultado->status == "approved"){
    
    $conexao = conexao::getInstance();
    $sql = "SELECT * FROM tab_pagamentos WHERE id_pagamento = :id_pagamento AND status = 'approved'";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id_pagamento', $resultado->id);  
    $stm->execute();
    $pagamento = $stm->fetch(PDO::FETCH_OBJ);

    if($pagamento->lancado != 1){

        if($pagamento->plano == "A"){
            $data_inicio = date('Y-m-d');
            $data = new DateTime(date('Y-m-d'));
            $data->add(new DateInterval('P1Y'));
            $data_renovacao = $data->format('Y-m-d');
        }else{
            $data_inicio = $pagamento->ano.'-'.$pagamento->mes.'-01';
            $data = new DateTime($pagamento->ano.'-'.$pagamento->mes.'-01');
            $data->add(new DateInterval('P1M'));
            $data_renovacao = "";
        }
        $data_fim = $data->format('d/m/Y');

        $meses = array("janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
        switch($pagamento->plano){
            case 'A':
                $campo_tabela = "anuidade".$pagamento->ano;
            break;
            case 'M':
                $campo_tabela = "mens_".substr($meses[(int)$pagamento->mes-1],0,3).$pagamento->ano;
            break;
        }
        
        // atualiza a data da renovação caso plano seja anual
        if($pagamento->plano == 'A'){
            $sql = "
                UPDATE
                    tab_membros
                SET
                    data_renovacao = :data_renovacao
                WHERE
                    matricula = :matricula
            ";
            $conexao = conexao::getInstance();
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':data_renovacao', $data_renovacao);
            $stm->bindValue(':matricula', $pagamento->matricula);
            $stm->execute();
        }
        
        // verifica se já existe registro
        $conexao = conexao::getInstance();
        $sql = 'SELECT COUNT(id) qtde FROM tab_financeiro WHERE matricula = :matricula';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':matricula', $pagamento->matricula);
        $stm->execute();
        $existe = $stm->fetch(PDO::FETCH_OBJ);
        if($existe->qtde > 0){
            
            $sql = "
                UPDATE 
                    tab_financeiro 
                SET 
                    {$campo_tabela} = :campo_tabela
                WHERE
                    matricula = :matricula
            ";
            $conexao = conexao::getInstance();
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':matricula', $pagamento->matricula);
            $stm->bindValue(':campo_tabela', $pagamento->valor);
            
            $retorno = $stm->execute();
            
        }else{
    
            $sql = " INSERT INTO tab_financeiro
                (
                    matricula,
                    {$campo_tabela}
                ) VALUES (
                    :matricula,
                    :campo_tabela
                )
            ";
            $conexao = conexao::getInstance();
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':matricula', $pagamento->matricula);
            $stm->bindValue(':campo_tabela', $pagamento->valor);
            
            $retorno = $stm->execute();
            
        }

        // marca como pagamento lançado
        $conexao = conexao::getInstance();
        $sql = "UPDATE tab_pagamentos SET lancado = 1 WHERE id_pagamento = :id_pagamento AND status = 'approved'";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id_pagamento', $resultado->id);  
        $stm->execute();

    }

}

exit();