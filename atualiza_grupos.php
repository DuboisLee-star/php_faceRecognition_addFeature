<?php

// Inclui o arquivo de configuração de conexão
require_once 'config/conexao.php';

// Usa as constantes definidas no arquivo de conexão
$host = HOST;
$user = USER;
$pass = PASSWORD;
$dbname = DBNAME;

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta inicial para buscar os registros da tabela `tab_armas`
$sql_arma = "SELECT id, tipo, calibre FROM tab_armas";
$result_arma = $conn->query($sql_arma);

if ($result_arma->num_rows > 0) {
    // Prepara a consulta de atualização para `tab_armas`
    $sql_update_armas = "UPDATE tab_armas SET id_grupo = ? WHERE id = ?";
    $stmt_armas = $conn->prepare($sql_update_armas);

    if (!$stmt_armas) {
        die("Erro na preparação da consulta para tab_armas: " . $conn->error);
    }

    // Prepara a consulta de atualização para `tab_habitualidade`
    $sql_update_habitualidade = "UPDATE tab_habitualidade SET id_grupo = ? WHERE arma_id = ?";
    $stmt_habitualidade = $conn->prepare($sql_update_habitualidade);

    if (!$stmt_habitualidade) {
        die("Erro na preparação da consulta para tab_habitualidade: " . $conn->error);
    }

    // Itera pelos registros de `tab_armas`
    while ($arma = $result_arma->fetch_object()) {
        $grupo = null;

        // Define o grupo com base no tipo e calibre
        if ($arma->tipo == 'Pistola' && in_array($arma->calibre, ['.22', '22LR', '.32', '.380ACP', '.380', '.765'])) {
            $grupo = 1;
        } elseif ($arma->tipo == 'Revolver' && in_array($arma->calibre, ['.22', '22LR', '.32', '.38', '.38SPL', '.38SUPER'])) {
            $grupo = 1;
        } elseif ($arma->tipo == 'Pistola' && in_array($arma->calibre, ['9MM', '.40', '.45'])) {
            $grupo = 2;
        } elseif ($arma->tipo == 'Revolver' && in_array($arma->calibre, ['.44', '.454', '.357', '.357MAG'])) {
            $grupo = 2;
        } elseif ($arma->tipo == 'Espingarda' && in_array($arma->calibre, ['.22', '22LR', '12GA', '12 GA', '12', '.17', '.20', '20GA', '.28', '28GA', '.26', '26GA'])) {
            $grupo = 3;
        } elseif ($arma->tipo == 'Espingarda' && in_array($arma->calibre, ['10GA', '8GA', '6GA'])) {
            $grupo = 4;
        } elseif (($arma->tipo == 'Carabina' || $arma->tipo == 'Rifle') && in_array($arma->calibre, ['.17', '.17HMR', '.22', '.22LR', '.38', '.38SPL'])) {
            $grupo = 5;
        } elseif (($arma->tipo == 'Carabina' || $arma->tipo == 'Fuzil') && in_array($arma->calibre, ['.44', '.40', '9MM', '.308', '.454', '5.56', '.556', '.762', '7,62'])) {
            $grupo = 6;
        }

        // Atualiza o registro em `tab_armas` se o grupo foi definido
        if ($grupo !== null) {
            $stmt_armas->bind_param("ii", $grupo, $arma->id);

            if ($stmt_armas->execute()) {
                echo "tab_armas: Arma ID: {$arma->id}, Tipo: {$arma->tipo}, Modelo: {$arma->modelo}, Calibre: {$arma->calibre} atualizada para Grupo: {$grupo}<br>";
            } else {
                echo "Erro ao atualizar tab_armas: Arma ID: {$arma->id} - " . $stmt_armas->error . "<br>";
            }

            // Atualiza também em `tab_habitualidade`
            $stmt_habitualidade->bind_param("ii", $grupo, $arma->id);

            if ($stmt_habitualidade->execute()) {
                echo "tab_habitualidade: Arma ID: {$arma->id}, Tipo: {$arma->tipo}, Modelo: {$arma->modelo}, Calibre: {$arma->calibre} atualizada para Grupo: {$grupo}<br>";
            } else {
                echo "Erro ao atualizar tab_habitualidade: Arma ID: {$arma->id} - " . $stmt_habitualidade->error . "<br>";
            }
        }
    }

    // Fecha os statements
    $stmt_armas->close();
    $stmt_habitualidade->close();
} else {
    echo "Nenhuma arma encontrada na tabela tab_armas.";
}

// Fecha a conexão
$conn->close();
?>