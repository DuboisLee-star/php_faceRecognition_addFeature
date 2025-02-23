<?php

require 'config/conexao.php';

// Atribui uma conexão PDO
$conexao = conexao::getInstance();

// Recebe os dados enviados pela submissão
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$data = !empty($_POST['datacadastro']) ? $_POST['datacadastro'] : NULL;
$data_inicial_visitante = !empty($_POST['data_inicial_visitante']) ? $_POST['data_inicial_visitante'] : NULL;
$data_final_visitante = !empty($_POST['data_final_visitante']) ? $_POST['data_final_visitante'] : NULL;
$nome_visitante = isset($_POST['nome_visitante']) ? strtoupper($_POST['nome_visitante']) : '';
$cr_visitante = isset($_POST['cr_visitante']) ? $_POST['cr_visitante'] : '';
$cr_visitante_validade = !empty($_POST['cr_visitante_validade']) ? $_POST['cr_visitante_validade'] : NULL;
$cpf_visitante = isset($_POST['cpf_visitante']) ? $_POST['cpf_visitante'] : '';
$zap_visitante = isset($_POST['zap_visitante']) ? $_POST['zap_visitante'] : '';
$evento = isset($_POST['evento']) ? $_POST['evento'] : '';
$modalidade = isset($_POST['modalidade']) ? array_map('strtoupper', $_POST['modalidade']) : [];
$tipo = isset($_POST['tipo']) ? array_map('strtoupper', $_POST['tipo']) : [];
$calibre = isset($_POST['calibre']) ? array_map('strtoupper', $_POST['calibre']) : [];
$numsigma = isset($_POST['numsigma']) ? $_POST['numsigma'] : [];
$qtdemunicoes = isset($_POST['qtdemunicoes']) ? $_POST['qtdemunicoes'] : [];
$pontos = isset($_POST['pontos']) ? $_POST['pontos'] : [];
$classificacao = isset($_POST['classificacao']) ? $_POST['classificacao'] : [];
$tipo_atirador = isset($_POST['tipo_atirador']) ? $_POST['tipo_atirador'] : '';

// Converte arrays em strings separadas por vírgulas
$modalidade = is_array($modalidade) ? $modalidade : [];
$tipo = is_array($tipo) ? $tipo : [];
$calibre = is_array($calibre) ? $calibre : [];
$numsigma = is_array($numsigma) ? $numsigma : [];
$qtdemunicoes = is_array($qtdemunicoes) ? $qtdemunicoes : [];
$pontos = is_array($pontos) ? $pontos : [];
$classificacao = is_array($classificacao) ? $classificacao : [];

// Converte a data para o formato ANSI (YYYY-MM-DD)
$data_temp = !empty($data) ? explode('/', $data) : [];
$data_ansi = count($data_temp) == 3 ? $data_temp[2] . '-' . $data_temp[1] . '-' . $data_temp[0] : $data;

// Verifica se foi solicitada a inclusão de dados
if ($acao == 'incluir') {
    // Valida os dados recebidos
    $mensagem = '';
    if (empty($modalidade) || empty($tipo) || empty($calibre) || empty($sigma) || empty($qtdemunicoes) || empty($pontos) || empty($classificacao)) {
        $mensagem .= '<li>Dados incompletos.</li>';
    }
    if ($mensagem != '') {
        $mensagem = '<ul>' . $mensagem . '</ul>';
        echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";
        exit;
    }
    
    // Limpa os dados anteriores se houver
    $sql_delete = 'DELETE FROM tab_habitualidade WHERE nome_visitante = :nome_visitante AND data = :data';
    $stm_delete = $conexao->prepare($sql_delete);
    $stm_delete->bindValue(':nome_visitante', $nome_visitante);
    $stm_delete->bindValue(':datacadastro', $data_ansi);
    $stm_delete->execute();

    // Insere os dados
    $sql = 'INSERT INTO tab_habitualidade (datacadastro, data_inicial_visitante, data_final_visitante, nome_visitante, cr_visitante, cr_visitante_validade, cpf_visitante, zap_visitante, evento, modalidade, tipo, calibre, sigma, qtdemunicoes, pontos, classificacao, tipo_atirador)
            VALUES(:datacadastro, :data_inicial_visitante, :data_final_visitante, :nome_visitante, :cr_visitante, :cr_visitante_validade, :cpf_visitante, :zap_visitante, :evento, :modalidade, :tipo, :calibre, :sigma, :qtdemunicoes, :pontos, :classificacao, :tipo_atirador)';

    $stm = $conexao->prepare($sql);

    $retorno = true;
    for ($i = 0; $i < max(count($tipo), count($calibre), count($sigma), count($qtdemunicoes)); $i++) {
        $stm->bindValue(':datacadastro', $data_ansi, PDO::PARAM_STR);
        $stm->bindValue(':data_inicial_visitante', $data_inicial_visitante, PDO::PARAM_STR);
        $stm->bindValue(':data_final_visitante', $data_final_visitante, PDO::PARAM_STR);        
        $stm->bindValue(':nome_visitante', strtoupper($nome_visitante));
        $stm->bindValue(':cr_visitante', $cr_visitante);
        $stm->bindValue(':cr_visitante_validade', $cr_visitante_validade, PDO::PARAM_STR);
        $stm->bindValue(':cpf_visitante', $cpf_visitante);
        $stm->bindValue(':zap_visitante', $zap_visitante);
        $stm->bindValue(':evento', strtoupper($evento));
        $stm->bindValue(':modalidade', isset($modalidade[$i]) ? $modalidade[$i] : NULL);        
        $stm->bindValue(':tipo', isset($tipo[$i]) ? $tipo[$i] : NULL);
        $stm->bindValue(':calibre', isset($calibre[$i]) ? $calibre[$i] : NULL);
        $stm->bindValue(':numsigma', isset($numsigma[$i]) ? $numsigma[$i] : NULL);
        $stm->bindValue(':qtdemunicoes', isset($qtdemunicoes[$i]) ? $qtdemunicoes[$i] : NULL);
        $stm->bindValue(':pontos', isset($pontos[$i]) ? $pontos[$i] : NULL);
        $stm->bindValue(':classificacao', isset($classificacao[$i]) ? $classificacao[$i] : NULL);        
        $stm->bindValue(':tipo_atirador', $tipo_atirador);
        
        if (!$stm->execute()) {
            $retorno = false;
            break;
        }
    }

    if ($retorno) {
        include_once("config/url_action.php");    
        echo "<div class='alert alert-success' role='alert'>Atirador cadastrado com sucesso... </div> ";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
    }

    echo "<meta http-equiv='refresh' content='0;URL=painel_visitantes.php'>";
}

// Verifica se foi solicitada a edição de dados
if ($acao == 'editar') {
    $sql = 'UPDATE tab_habitualidade SET datacadastro=:datacadastro, data_inicial_visitante=:data_inicial_visitante, data_final_visitante=:data_final_visitante, nome_visitante=:nome_visitante, cr_visitante=:cr_visitante, cr_visitante_validade=:cr_visitante_validade, cpf_visitante=:cpf_visitante, zap_visitante=:zap_visitante, evento=:evento, modalidade=:modalidade, tipo=:tipo, calibre=:calibre, sigma=:sigma, qtdemunicoes=:qtdemunicoes,
 pontos=:pontos, classificacao=:classificacao WHERE id = :id';

    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $stm->bindValue(':datacadastro', $data_ansi, PDO::PARAM_STR);
    $stm->bindValue(':data_inicial_visitante', $data_inicial_visitante, PDO::PARAM_STR);
    $stm->bindValue(':data_final_visitante', $data_final_visitante, PDO::PARAM_STR);    
    $stm->bindValue(':nome_visitante', strtoupper($nome_visitante));
    $stm->bindValue(':cr_visitante', $cr_visitante);
    $stm->bindValue(':cr_visitante_validade', $cr_visitante_validade, PDO::PARAM_STR);
    $stm->bindValue(':cpf_visitante', $cpf_visitante);
    $stm->bindValue(':zap_visitante', $zap_visitante);
    $stm->bindValue(':evento', strtoupper($evento));
    $stm->bindValue(':modalidade', strtoupper($modalidade));    
    $stm->bindValue(':tipo', strtoupper($tipo));
    $stm->bindValue(':calibre', strtoupper($calibre));
    $stm->bindValue(':sigma', strtoupper($sigma));
    $stm->bindValue(':qtdemunicoes', strtoupper($qtdemunicoes));
    $stm->bindValue(':pontos', strtoupper($pontos));
    $stm->bindValue(':classificacao', strtoupper($classificacao));    

    $retorno = $stm->execute();

    if ($retorno) {
        $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Registro atualizado.</div>';
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Erro ao editar registro!</div>';
    }

    echo "<script>window.location='painel_visitantes.php?id=".$id."';</script>";
}
?>