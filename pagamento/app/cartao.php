<?php
if(!isset($app)) exit();
require_once('config.php');
?>
<script src="https://sdk.mercadopago.com/js/v2"></script>
            <div class="row">
                
                <!-- catão -->
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body" data-card>
                            
                            <div class="card" style="max-width: 32rem; margin: auto; width: 100%;">

                                <div class="card-header">
                                    <b>Pagamento com Cartão de Crédito</b>
                                </div>
                                <div class="card-body">
                                    <form id="form-checkout">
                                        <div class="row">

                                        <div id="error"></div>
                                            
                                            <div class="input-group col-12 mb-4">
                                                <div id="form-checkout__cardNumber" class="form-control iframe"></div>
                                                <span class="input-group-text"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="col-6 mb-4">
                                                <div id="form-checkout__expirationDate" class="form-control iframe"></div>
                                            </div>
                                            <div class="col-6 mb-4">
                                                <div id="form-checkout__securityCode" class="form-control iframe"></div>
                                            </div>
                                            <div class="col-12 mb-4">
                                                <select id="form-checkout__issuer" class="form-control"></select>
                                            </div>

                                            <div class="input-group col-12 mb-4">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                <input type="text" id="form-checkout__cardholderName"  class="form-control"/>
                                            </div>

                                            <div class="col-2 mb-4">
                                                <select id="form-checkout__identificationType" class="form-control"></select>
                                            </div>

                                            <div class="col-10 mb-4">
                                                <input type="text" id="form-checkout__identificationNumber" class="form-control" />
                                            </div>

                                            <div class="input-group col-12 mb-4">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-list-ol" aria-hidden="true"></i></span>
                                                <select id="form-checkout__installments" class="form-control"></select>
                                            </div>

                                            <div class="input-group col-12 mb-4">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                                <input type="email" id="form-checkout__cardholderEmail" class="form-control" />
                                            </div>

                                            <div class="col-12 mb-4 text-center">Valor para Pagamento:</b> <b>R$ <?= number_format($valor_plano,2,',','.'); ?></b></div>

                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" onclick="Pagar()" id="form-checkout__submit" class="btn btn-primary btn-block">Pagar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

<script>
const Pagar = () => {

    var dados = {
        'nome': $("#form-checkout__cardholderName").val(),
        'identificacao': $("#form-checkout__identificationNumber").val(),
        'parcela': $("#form-checkout__installments").val(),
        'email': $("#form-checkout__cardholderEmail").val(),
    }
    if(dados.nome == ''){$("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times" aria-hidden="true"></i> Títular não informado.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); return false;}
    if(dados.identificacao == '' || dados.identificacao.length < 11){$("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times" aria-hidden="true"></i> Número do documento inválido.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); return false;}
    if(dados.parcela == ''){$("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times" aria-hidden="true"></i> Parcelas não informada.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); return false;}
    if(dados.email == ''){$("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times" aria-hidden="true"></i> E-mail inválido.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); return false;}

    $(".loading").fadeIn(100);
    //$("#form-checkout").submit();
    
}

const mp = new MercadoPago("<?= PUBLIC_KEY; ?>");

    const cardForm = mp.cardForm({
      amount: "<?= $valor_plano; ?>",
      iframe: true,
      form: {
        id: "form-checkout",
        cardNumber: {
          id: "form-checkout__cardNumber",
          placeholder: "Número do cartão",
        },
        expirationDate: {
          id: "form-checkout__expirationDate",
          placeholder: "MM/AA",
        },
        securityCode: {
          id: "form-checkout__securityCode",
          placeholder: "Código de segurança",
        },
        cardholderName: {
          id: "form-checkout__cardholderName",
          placeholder: "Titular do cartão",
        },
        issuer: {
          id: "form-checkout__issuer",
          placeholder: "Banco emissor",
        },
        installments: {
          id: "form-checkout__installments",
          placeholder: "Parcelas",
        },        
        identificationType: {
          id: "form-checkout__identificationType",
          placeholder: "Tipo de documento",
        },
        identificationNumber: {
          id: "form-checkout__identificationNumber",
          placeholder: "Número do documento (CPF/CNPJ)",
        },
        cardholderEmail: {
          id: "form-checkout__cardholderEmail",
          placeholder: "E-mail",
        },
      },
      callbacks: {
        onIdentificationTypesReceived: error => {
            if (error) return console.log("onIdentificationTypesReceived: ", error);
          console.log("Form error1");
        },
        onIssuersReceived: error => {
            if (error) return console.log("onIssuersReceived: ", error);
          console.log("Form error2");
        },
        onInstallmentsReceived: error => {
            if (error) return console.log("onInstallmentsReceived: ", error);
          console.log("Form error3");
        },
        onPaymentMethodsReceived: error => {
            if (error) return console.log("onPaymentMethodsReceived: ", error);
          console.log("Form error4");
        },
        onCardTokenReceived: error => {
            if (error) exibeErro(error);
            return false;
        },
        onFormMounted: error => {
          if (error) return console.warn("Form Mounted handling error: ", error);
          console.log("Form mounted");
        },
        onSubmit: event => {
          event.preventDefault();
          
            const {
                paymentMethodId: payment_method_id,
                issuerId: issuer_id,
                cardholderEmail: email,
                amount,
                token,
                installments,
                identificationNumber,
                identificationType,
            } = cardForm.getCardFormData();

            const resultado = fetch("/pagamento/app/callback_cartao.php", {
                method: "POST",
                headers: {
                "Content-Type": "application/json",
                },
                body: JSON.stringify({
                token,
                issuer_id,
                payment_method_id,
                transaction_amount: Number(amount),
                installments: Number(installments),
                description: '<?= $descricao_plano ?>',
                matricula: '<?= $cliente->matricula ?>',
                plano_pgto: '<?= $cliente->plano_pgto ?>',
                mes_competencia: '<?= $mes_competencia ?>',
                ano_competencia: '<?= $ano_competencia ?>',
                payer: {
                    email,
                    identification: {
                    type: identificationType,
                    number: identificationNumber,
                    },
                },
                }),
            }).then((response) => response.json());

            const verificaPagamento = () => {
                resultado.then((response) => {
                    switch(response.status){
                        case 'approved':
                            $("[data-card]").html('<div class="alert alert-success m-0" role="alert"><h1 class="display-6"><i class="fa fa-check-circle" aria-hidden="true"></i> Pagamento aprovado.</h1><p>Identificação do Pagamento: <b>'+response.id+'</b></p></div>');
                        break;
                        case 'in_process':
                            if(response.status_detail == 'pending_contingency') $("[data-card]").html('<div class="alert alert-warning m-0" role="alert"><h1 class="display-6"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Pagamento em processamento.</h1><p>Estamos processando o pagamento.<br>Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado.</p></div>');
                            if(response.status_detail == 'pending_review_manual') $("[data-card]").html('<div class="alert alert-warning m-0" role="alert"><h1 class="display-6"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Pagamento em processamento.</h1><p>Estamos processando seu pagamento.<br> Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado ou se necessitamos de mais informação.</p></div>');
                        break;
                        case 'rejected':
                            if(response.status_detail == 'cc_rejected_bad_filled_card_number') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Revise o número do cartão.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_bad_filled_date') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Revise a data de vencimento.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_bad_filled_other') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Revise os dados.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_bad_filled_security_code') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Revise o código de segurança do cartão.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_blacklist') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Não pudemos processar seu pagamento.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_call_for_authorize') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Você deve autorizar ao '+response.payment_method_id+' o pagamento do valor ao Mercado Pago.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_card_disabled') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Ligue para o '+response.payment_method_id+' para ativar seu cartão. O telefone está no verso do seu cartão.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_card_error') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Não conseguimos processar seu pagamento.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_duplicated_payment') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Você já efetuou um pagamento com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_high_risk') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Seu pagamento foi recusado.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_insufficient_amount') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Sem saldo suficiente.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_invalid_installments') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Cartão não processa essa qtde. de parcelas.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_max_attempts') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Você atingiu o limite de tentativas permitido, Escolha outro cartão ou outra forma de pagamento.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_other_reason') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-Cartão não processa pagamentos.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                            if(response.status_detail == 'cc_rejected_card_type_not_allowed') $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Não Aprovado</b> <p class="pt-2">-O pagamento foi rejeitado porque o usuário não tem a função crédito habilitada em seu cartão multiplo (débito e crédito).</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        break;

                    }

                    $(".loading").fadeOut(100);

                });
            };

            verificaPagamento();

        },
        onFetching: (resource) => {
          console.log("Fetching resource: ", resource);
        }
      },
    });

    const exibeErro = (msg) => {
        var mensagem = '';
        switch(msg[0].field){
            case "expirationDate": mensagem = 'Validade do cartão inválida.'; break;
            case "securityCode": mensagem = 'Código de segurança inválido.'; break;
            case "cardNumber": mensagem = 'Número do cartão inválido.'; break;
            default: mensagem = 'Erro não indentificado.'; break;
        }

        $(".loading").fadeOut(100);
        $("#error").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fa fa-times" aria-hidden="true"></i> '+mensagem+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
    }
</script>
<style>
    .iframe {
        height: 42px;
    }
</style>