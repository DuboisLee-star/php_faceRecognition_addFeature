<?php
session_start(); // Garante que a sessão é iniciada antes de qualquer saída

// Simulação de login (substitua por um sistema de login real)
$_SESSION['usuario_id'] = 123;
$_SESSION['nome_completo'] = "Usuário Teste";
$_SESSION['matricula'] = "20250001";

echo "Usuário logado com sucesso!";

// Opcional: Redireciona para a página protegida
header("Location: webcam.php");
exit();
?>
