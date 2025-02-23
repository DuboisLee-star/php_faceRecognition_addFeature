<?php

include "config/config.php";

// Check user login or not
//if(!isset($_SESSION['uname'])){
//    header('Location: index.php');
//}
//
// logout
//if(isset($_POST['but_logout'])){
//    session_destroy();
//    header('Location: index.php');
//}



require 'config/conexao.php';

	$conexao = conexao::getInstance();
	$sql = 'UPDATE tab_membros SET bloqueio = "Nao" WHERE data_renovacao >= CURDATE()';
	$stm = $conexao->prepare($sql);
	$stm->execute();

	?>