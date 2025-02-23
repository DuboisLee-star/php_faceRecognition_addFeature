<?php

include "config/config.php";

// Check user login or not
// if(!isset($_SESSION['uname'])){
//    header('Location: index.php');
// }

// logout
// if(isset($_POST['but_logout'])){
//    session_destroy();
//    header('Location: index.php');
// }



require 'config/conexao.php';

// Estabelecer conexão com o banco de dados
$conexao = conexao::getInstance();

// Comando SQL para atualizar as colunas específicas na tabela tab_membros
$sql = 'UPDATE tab_membros
        SET 
            data_filiacao = CASE WHEN data_filiacao = "0000-00-00" THEN NULL ELSE data_filiacao END,
            data_renovacao = CASE WHEN data_renovacao = "0000-00-00" THEN NULL ELSE data_renovacao END,
            cr_emissao = CASE WHEN cr_emissao = "0000-00-00" THEN NULL ELSE cr_emissao END,
            exped_cr = CASE WHEN exped_cr = "0000-00-00" THEN NULL ELSE exped_cr END,
            data_exped_cnh = CASE WHEN data_exped_cnh = "0000-00-00" THEN NULL ELSE data_exped_cnh END,
            validade_cr = CASE WHEN validade_cr = "0000-00-00" THEN NULL ELSE validade_cr END,
            data_exped = CASE WHEN data_exped = "0000-00-00" THEN NULL ELSE data_exped END,
            data_nascimento = CASE WHEN data_nascimento = "0000-00-00" THEN NULL ELSE data_nascimento END';

// Preparar e executar o comando SQL
$stm = $conexao->prepare($sql);
$stm->execute();

	?>