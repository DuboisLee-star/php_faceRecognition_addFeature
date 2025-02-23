<?php

require 'config/conexao.php';

// Atribui uma conexão PDO
$conexao = conexao::getInstance();

// Recebe os dados enviados pela submissão
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$datacadastro = !empty($_POST['datacadastro']) ? $_POST['datacadastro'] : NULL;

// Tratamento de datas no formato ISO 8601
$data_inicial_visitante = !empty($_POST['data_inicial_visitante']) ? DateTime::createFromFormat('Y-m-d\TH:i', $_POST['data_inicial_visitante']) : false;
$data_final_visitante = !empty($_POST['data_final_visitante']) ? DateTime::createFromFormat('Y-m-d\TH:i', $_POST['data_final_visitante']) : false;

$data_inicial_visitante = $data_inicial_visitante instanceof DateTime ? $data_inicial_visitante->format('Y-m-d H:i:s') : NULL;
$data_final_visitante = $data_final_visitante instanceof DateTime ? $data_final_visitante->format('Y-m-d H:i:s') : NULL;

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
$modalidade = implode(',', $modalidade);
$tipo = implode(',', $tipo);
$calibre = implode(',', $calibre);
$numsigma = implode(',', $numsigma);
$qtdemunicoes = implode(',', $qtdemunicoes);
$pontos = implode(',', $pontos);
$classificacao = implode(',', $classificacao);

// Converte a data de cadastro para o formato ANSI (YYYY-MM-DD)
if (!empty($datacadastro)) {
    $data_temp = explode('/', $datacadastro);
    $data_ansi = count($data_temp) == 3 ? $data_temp[2] . '-' . $data_temp[1] . '-' . $data_temp[0] : $datacadastro;
} else {
    $data_ansi = NULL;
}

// Verifica se foi solicitada a inclusão de dados
if ($acao == 'incluir') {
    // Valida os dados recebidos
    $mensagem = '';
    if (empty($modalidade) || empty($tipo) || empty($calibre) || empty($numsigma) || empty($qtdemunicoes) || empty($pontos) || empty($classificacao)) {
        $mensagem .= '<li>Dados incompletos.</li>';
    }
    if ($mensagem != '') {
        $mensagem = '<ul>' . $mensagem . '</ul>';
        echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";
        exit;
    }

    // Insere os dados
    $sql = 'INSERT INTO tab_habitualidade (datacadastro, data_inicial_visitante, data_final_visitante, nome_visitante, cr_visitante, cr_visitante_validade, cpf_visitante, zap_visitante, evento, modalidade, tipo, calibre, numsigma, qtdemunicoes, pontos, classificacao, tipo_atirador)
            VALUES(:datacadastro, :data_inicial_visitante, :data_final_visitante, :nome_visitante, :cr_visitante, :cr_visitante_validade, :cpf_visitante, :zap_visitante, :evento, :modalidade, :tipo, :calibre, :numsigma, :qtdemunicoes, :pontos, :classificacao, :tipo_atirador)';

    $stm = $conexao->prepare($sql);
    $stm->bindValue(':datacadastro', $data_ansi, PDO::PARAM_STR);
    $stm->bindValue(':data_inicial_visitante', $data_inicial_visitante, PDO::PARAM_STR);
    $stm->bindValue(':data_final_visitante', $data_final_visitante, PDO::PARAM_STR);        
    $stm->bindValue(':nome_visitante', strtoupper($nome_visitante));
    $stm->bindValue(':cr_visitante', $cr_visitante);
    $stm->bindValue(':cr_visitante_validade', $cr_visitante_validade, PDO::PARAM_STR);
    $stm->bindValue(':cpf_visitante', $cpf_visitante);
    $stm->bindValue(':zap_visitante', $zap_visitante);
    $stm->bindValue(':evento', strtoupper($evento));
    $stm->bindValue(':modalidade', $modalidade);        
    $stm->bindValue(':tipo', $tipo);
    $stm->bindValue(':calibre', $calibre);
    $stm->bindValue(':numsigma', $numsigma);
    $stm->bindValue(':qtdemunicoes', $qtdemunicoes);
    $stm->bindValue(':pontos', $pontos);
    $stm->bindValue(':classificacao', $classificacao);        
    $stm->bindValue(':tipo_atirador', $tipo_atirador);

    if ($stm->execute()) {
        include_once("config/url_action.php");    
        echo "<div class='alert alert-success' role='alert'>Atirador cadastrado com sucesso... </div> ";
    } else {
        $error = $stm->errorInfo();
        echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro: {$error[2]}</div> ";
    }

    echo "<meta http-equiv='refresh' content='0;URL=painel_visitantes.php'>";
}

?>