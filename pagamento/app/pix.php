<?php
if(!isset($app)) exit();
require_once('config.php');

$nome_completo = explode(' ', $cliente->nome);

$dados['transaction_amount']                    = (float)$valor_plano;
$dados['description']                           = $descricao_plano;
$dados['external_reference']                    = $cliente->matricula;
$dados['payment_method_id']                     = "pix";
$dados['notification_url']                      = URL_SITE.'/pagamento/app/callback_pix.php';
$dados['payer']['email']                        = $cliente->email;
$dados['payer']['first_name']                   = $nome_completo[0];
$dados['payer']['last_name']                    = $nome_completo[1];
$dados['payer']['identification']['type']       = "CPF";
$dados['payer']['identification']['number']     = $cliente->cpf;
$dados['payer']['address']['zip_code']          = $cliente->cep;
$dados['payer']['address']['street_name']       = $cliente->rua;
$dados['payer']['address']['street_number']     = $cliente->numero;
$dados['payer']['address']['neighborhood']      = $cliente->bairro;
$dados['payer']['address']['city']              = $cliente->cidade;
$dados['payer']['address']['federal_unit']      = $cliente->siglauf;

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($dados),
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.ACCESS_TOKEN        
    ),
));
$response = curl_exec($curl);
$resultado = json_decode($response);
// echo '<pre>';
// print_r($resultado);
// echo '</pre>';
?>

            <div class="row">
                
                <!-- pix -->
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="alert alert-primary" role="alert">
                                <ul class="m-0 pl-5">
                                    <li>Abra o aplicativo do seu banco no celular</li>
                                    <li>Selecione a opção de pagar com Pix / escanear QR code</li>
                                </ul>
                            </div>

                            <div class="col-12 text-center">
                                <p class="text-dark m-0"><strong>Aguardando pagamento...</strong> <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></p>
                                <img src="data:image/jpeg;base64,<?= $resultado->point_of_interaction->transaction_data->qr_code_base64; ?>" style="height: 300px; width: 300px;">
                            </div>
                            
                            <div class="text-center">Valor para Pagamento:</b> <b>R$ <?= number_format($valor_plano,2,',','.'); ?></b></div>

                            <b>Copie:</b><br>

                            <div class="bg_codigo p-2"><?= $resultado->point_of_interaction->transaction_data->qr_code; ?></div>

                        </div>
                    </div>
                </div>

            </div>
<script>
<?php if(isset($resultado->point_of_interaction->transaction_data->qr_code)): ?>    
    var finish = false;
    var startStatus = setInterval(function(){

        $.ajax({
            url: "<?= URL_SITE; ?>/pagamento/app/callback_pix.php?id=<?= $resultado->id; ?>&status=status",
            type:'GET',
            success:function(result){

                if(result == "approved"){
                    clearInterval(startStatus);
                    pagamentoAprovado();
                }

            },
        });

    }, 4000);
    const pagamentoAprovado = () => {
        var html = '<div class="alert alert-success m-0" role="alert"><h1 class="display-6"><i class="fa fa-check-circle" aria-hidden="true"></i> Pagamento Confirmado!</h1></div>';
        $(".card-body").html(html);
    }
<?php endif; ?>
</script>
<?php
if(isset($resultado->point_of_interaction->transaction_data->qr_code)):
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
$stm->bindValue(':matricula', $cliente->matricula);
$stm->bindValue(':valor', $valor_plano);
$stm->bindValue(':status', $resultado->status);
$stm->bindValue(':mes', $mes_competencia);
$stm->bindValue(':ano', $ano_competencia);
$stm->bindValue(':plano', $cliente->plano_pgto);
$stm->bindValue(':meiopagamento', 'pix');
$stm->bindValue(':datacadastro', date('Y-m-d H:i:s'));

$stm->execute();
endif;
?>
