<?php

include_once ("config/url_painel.php");

//Monta o Corpo da Mensagem
				//====================================================
				$email_conteudo =  '
				
<img src="https://sistema.hostmarq.com.br/img/logo_site.png" width="90" alt="HOSTMARQ">

Novo inscrito em curso de tiro.

<img src="https://sistema.hostmarq.com.br/fotoscursos/'.$nome_foto.'" width="60">

Nome: '.$nome.'
CPF: '.$cpf.'
Telefone: '.$telefone.'
Email: '.$email.'
Curso: '.$curso.'
Data Inicial do Curso: '.$data_inicial_do_curso.'
Data Final do Curso: '.$data_final_do_curso.'
Carga Horária: '.$carga_horaria_curso.'

Email enviado pelo Sistema!
';
				//====================================================
				//Seta os Headers (Alerar somente caso necessario)
				//====================================================
				$email_headers = implode ( "\n",array ( "From: secretaria@hostmarq.com.br", "Cc: werbermarques@gmail.com", "Reply-To: werbermarques@gmail.com.com", "Subject: Novo inscrito em Curso de Tiro","Return-Path:  werbermarques@gmail.com","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8" ) );
				//====================================================
			 
				//Enviando o email
				//====================================================
				if (mail ('werbermarques@gmail.com', 'Novo inscrito em Curso de Tiro.', nl2br($email_conteudo), $email_headers)){}
				//====================================================

?>