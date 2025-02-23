<?php

include_once ("config/url_painel.php");

//Monta o Corpo da Mensagem
//====================================================
$email_conteudo =  '
				
<img src="'.$url.'/img/logo_site.png" width="90">

'.$motivo.'

Nome: '.$nome.'
Whatsapp: '.$telefone.'
Email: '.$email.'

Email enviado pelo Sistema!
';

//====================================================
//Seta os Headers (Alerar somente caso necessario)
//====================================================
$email_headers = implode ( "\n",array ( "From: secretaria@hostmarq.com.br", "Subject: Ação no Sistema","Return-Path: werbermarques@gmail.com","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
//====================================================
//Enviando o email
//====================================================
if (mail ('werbermarques@gmail.com', 'Ação realizada no sistema.', nl2br($email_conteudo), $email_headers)){}
//====================================================

?>