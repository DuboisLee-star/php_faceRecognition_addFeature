<?php
$atirador = false;
set_time_limit(60);
include(dirname( __FILE__ ).'/whatsapp/whatsapp.php');

$wpp = new Whatsapp();
$response = $wpp->envia_mensagem_fila();

#$wpp->envia_mensagem_aniversario();