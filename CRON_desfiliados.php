<?php

include "config/config.php";

require 'config/conexao.php';

	$conexao = conexao::getInstance();
	$sql = 'UPDATE tab_membros SET bloqueio = "D" WHERE data_renovacao IS NULL';
	$stm = $conexao->prepare($sql);
	$stm->execute();

?>