<?php

session_start();

include_once("config/conexao_del.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if(!empty($id)){
	$result_usuario = "DELETE FROM tab_comanda WHERE id='$id'";
	$resultado_usuario = mysqli_query($conn, $result_usuario);
		$result_usuario2 = "DELETE FROM tab_comanda_itens WHERE comanda_id='$id'";
	$resultado_usuario2 = mysqli_query($conn, $result_usuario2);
	if(mysqli_affected_rows($conn)){
		$_SESSION['msg'] = "<p style='color:green;'>Comanda apagada com sucesso</p>";
		header("Location: comanda.php");
	}else{
		
		$_SESSION['msg'] = "<p style='color:red;'>Erro! Comanda não excluída!</p>";
		header("Location: comanda.php");
	}
}else{	
	$_SESSION['msg'] = "<p style='color:red;'>Necessário selecionar uma comanda.</p>";
	header("Location: comanda.php");
}