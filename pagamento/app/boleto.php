<?php
if(!isset($app)) exit();
require_once('config.php');

$nome_completo = explode(' ', $cliente->nome);

$dados['transaction_amount']                    = (float)$valor_plano;
$dados['description']                           = $descricao_plano;
$dados['external_reference']                    = $cliente->matricula;
$dados['payment_method_id']                     = "bolbradesco";
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
?>

            <div class="row">
                
                <!-- pix -->
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            
                            <?php if(isset($resultado->transaction_details->external_resource_url)): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i> Seu boleto foi gerado com sucesso, clique no botão abaixo para visualizar seu boleto bancário</li>
                                </div>

                                <div class="text-center">
                                    <img src="image/boleto.jpg">
                                    <div class="text-center">Valor do Boleto:</b> <b>R$ <?= number_format($valor_plano,2,',','.'); ?></b></div>
                                    <a class="btn btn-success text-center" href="<?= $resultado->transaction_details->external_resource_url; ?>"><i class="fa fa-external-link" aria-hidden="true" target="_blank" onclick="abreBoleto()"></i> Baixar Boleto Bancário</a>
                                </div>
                            <?php else: ?>

                                <div class="alert alert-danger mb-4" role="alert">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Desculpe, não foi possível gerar o boleto bancário.</li>
                                </div>

                                <div class="alert alert-secondary" role="alert">
                                    <pre>
                                    <?php print_r($resultado); ?>
                                    </pre>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
<script>
    const abreBoleto = () => {
        setTimeout(() => {
            window.location='<?= URL_SITE; ?>/atirador/';
        }, 3000);
    }
</script>
<?php
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
if(!isset($resultado->error)):
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id_pagamento', $resultado->id);
    $stm->bindValue(':matricula', $cliente->matricula);
    $stm->bindValue(':valor', $valor_plano);
    $stm->bindValue(':status', $resultado->status);
    $stm->bindValue(':mes', $mes_competencia);
    $stm->bindValue(':ano', $ano_competencia);
    $stm->bindValue(':plano', $cliente->plano_pgto);
    $stm->bindValue(':meiopagamento', 'boleto');
    $stm->bindValue(':datacadastro', date('Y-m-d H:i:s'));

    $stm->execute();
endif;
?>