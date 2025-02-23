<?php
require 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $foto_atual = filter_input(INPUT_POST, 'foto_atual', FILTER_SANITIZE_STRING);
    $image_webcam = isset($_POST['image_webcam']) ? $_POST['image_webcam'] : '';
    
    // Initialize photo name variable
    $nome_foto = $foto_atual;
    $success = false;
    $message = '';
    
    // Check if a new photo was uploaded via file input
    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0 && empty($image_webcam)) {
        $extensoes_aceitas = array('bmp', 'png', 'svg', 'jpeg', 'jpg');
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extensao, $extensoes_aceitas)) {
            header('Location: atualizar_foto.php?id=' . $id . '&status=error&message=' . urlencode('Extensão de arquivo inválida'));
            exit;
        }
        
        // Create directory if it doesn't exist
        if (!file_exists("fotos")) {
            mkdir("fotos", 0777, true);
        }
        
        $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];
        
        // If there was an old photo, delete it
        if (!empty($foto_atual) && $foto_atual != 'padrao.png' && file_exists('fotos/'.$foto_atual)) {
            unlink('fotos/' . $foto_atual);
        }
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)) {
            $success = true;
        } else {
            $message = "Erro ao fazer upload do arquivo.";
        }
    }
    // Check if webcam photo was uploaded
    else if (!empty($image_webcam)) {
        // Remove base64 header if present
        $img = $image_webcam;
        if (strpos($img, 'data:image/png;base64,') === 0) {
            $img = substr($img, strlen('data:image/png;base64,'));
        }
        
        // Create directory if it doesn't exist
        if (!file_exists("fotos")) {
            mkdir("fotos", 0777, true);
        }
        
        $nome_foto = date('dmY') . '_' . md5(date('Y-m-dH:i:s').microtime(true).rand(0,9999)) . '.png';
        
        // If there was an old photo, delete it
        if (!empty($foto_atual) && $foto_atual != 'padrao.png' && file_exists('fotos/'.$foto_atual)) {
            unlink('fotos/' . $foto_atual);
        }
        
        // Save webcam photo
        if (file_put_contents('fotos/'.$nome_foto, base64_decode($img))) {
            $success = true;
        } else {
            $message = "Erro ao salvar foto da webcam.";
        }
    } else {
        $message = "Nenhuma foto foi enviada.";
    }
    
    // Update database if file was saved successfully
    if ($success && !empty($id)) {
        try {
            $conexao = conexao::getInstance();
            
            // Get current member data for logging
            $sql_membro = "SELECT * FROM tab_membros WHERE id = :id";
            $stm = $conexao->prepare($sql_membro);
            $stm->bindValue(':id', $id);
            $stm->execute();
            $membro = $stm->fetch(PDO::FETCH_OBJ);
            
            // Update photo in database
            $sql = 'UPDATE tab_membros SET foto = :foto WHERE id = :id';
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':foto', $nome_foto);
            $stm->bindValue(':id', $id);
            
            if ($stm->execute()) {
                // Log the change
                $dados_alterados = array(
                    'foto' => array(
                        'anterior' => $foto_atual,
                        'novo' => $nome_foto
                    )
                );
                
                $alteracao = json_encode($dados_alterados, JSON_UNESCAPED_UNICODE);
                
                $sql_log = 'INSERT INTO tab_logs (username, tabela, tipo_de_alteracao, registro_id, registro, created_at)
                           VALUES(:username, :tabela, :tipo_alteracao, :registro_id, :registro, :data)';
                
                $stm = $conexao->prepare($sql_log);
                $stm->bindValue(':username', $_SESSION['uname']);
                $stm->bindValue(':tabela', 'tab_membros');
                $stm->bindValue(':tipo_alteracao', 'atualizacao_foto');
                $stm->bindValue(':registro_id', $id);
                $stm->bindValue(':registro', $alteracao);
                $stm->bindValue(':data', date('Y-m-d H:i:s'));
                $stm->execute();
                
                header('Location: atualizar_foto.php?id=' . $id . '&status=success');
                exit;
            } else {
                $message = "Erro ao atualizar foto no banco de dados.";
            }
        } catch(PDOException $e) {
            $message = "Erro no banco de dados: " . $e->getMessage();
            
            // If database update fails, delete the uploaded file
            if (file_exists('fotos/'.$nome_foto)) {
                unlink('fotos/'.$nome_foto);
            }
        }
    }
    
    // If we get here, something went wrong
    header('Location: atualizar_foto.php?id=' . $id . '&status=error&message=' . urlencode($message));
    exit;
}

// If not POST request, redirect to main page
header('Location: painel.php');
exit;
?>