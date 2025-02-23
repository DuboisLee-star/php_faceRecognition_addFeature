<?php

session_start();
include_once("config/conexao_del.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$user= $_SESSION['uname'];

if (!empty($id)) {
    require 'config/conexao.php';

    $conexao = conexao::getInstance();

    // Verificação do usuário a ser excluído
    $sql_user1 = "SELECT * FROM users WHERE id = :id";
    $stm = $conexao->prepare($sql_user1);
    $stm->bindParam(':id', $id, PDO::PARAM_INT);
    $stm->execute();
    $usuario1 = $stm->fetch(PDO::FETCH_OBJ);

    if (!$usuario1) {
        $_SESSION['msg'] = "<p style='color:red;'>Usuário não encontrado</p>";
        header("Location: adminusers.php");
        exit;
    }

    // Verificação do operador
    if (!empty($user)) {
        $sql_user = "SELECT * FROM users WHERE username = :name";
        $stm = $conexao->prepare($sql_user);
        $stm->bindParam(':name', $user, PDO::PARAM_STR);
        $stm->execute();
        $usuario = $stm->fetch(PDO::FETCH_OBJ);

        if (!$usuario) {
            $_SESSION['msg'] = "<p style='color:red;'>Operador não encontrado</p>";
            header("Location: adminusers.php");
            exit;
        }
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Usuário inválido</p>";
        header("Location: adminusers.php");
        exit;
    }

    // Inserção do log
    $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
                VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';
    $stm = $conexao->prepare($sql_log);
    $stm->bindValue(':username', $usuario->name, PDO::PARAM_STR);
    $stm->bindValue(':tabela', 'users', PDO::PARAM_STR);
    $stm->bindValue(':tipo_alteracao', 'exclusao', PDO::PARAM_STR);
    $stm->bindValue(':registro_id', $usuario1->id, PDO::PARAM_INT);
    $alteracao = json_encode(['Exclusao do usuario' => $usuario1->name]);
    $stm->bindValue(':registro', $alteracao, PDO::PARAM_STR);
    $stm->bindValue(':data', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stm->execute();

    // Exclusão do usuário
    $result_usuario = "DELETE FROM users WHERE id = :id";
    $stm = $conexao->prepare($result_usuario);
    $stm->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stm->execute()) {
        $_SESSION['msg'] = "<p style='color:green;'>Usuário apagado com sucesso</p>";
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Erro ao apagar o usuário</p>";
    }
    header("Location: adminusers.php");
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Necessário selecionar um usuário</p>";
    header("Location: adminusers.php");
}