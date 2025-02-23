<?php
$atirador = false;
set_time_limit(60*10);
include(dirname( __FILE__ ).'/whatsapp/whatsapp.php');

$wpp = new Whatsapp();

$wpp->envia_mensagem_aniversario();
$wpp->envia_mensagem_renovacao();
$wpp->envia_mensagem_renovacao_gt();

/*
if((int)date('d') == 5 || (int)date('d') == 15){
    $wpp->envia_mensagem_vencimento_anual();
}

if((int)date('d') == 6 || (int)date('d') == 16){
    $wpp->envia_mensagem_vencimento_mensal();
}
*/