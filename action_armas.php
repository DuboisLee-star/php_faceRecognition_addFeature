<?php 

require 'config/conexao.php';

// Atribui uma conexão PDO
$conexao = conexao::getInstance();

// Recebe os dados enviados pela submissão
$acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
$tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : '';        
$modelo = (isset($_POST['modelo'])) ? $_POST['modelo'] : '';
$calibre = (isset($_POST['calibre'])) ? $_POST['calibre'] : '';
$numsigma = (isset($_POST['numsigma'])) ? $_POST['numsigma'] : '';
$validade_gt = (isset($_POST['validade_gt'])) ? $_POST['validade_gt'] : '';
$validade_craf = (isset($_POST['validade_craf'])) ? $_POST['validade_craf'] : '';

// Valida os dados recebidos
$mensagem = '';
if ($acao == 'editar' && $id == ''):
    $mensagem .= '<li>ID do registro desconhecido.</li>';
endif;

// Se for ação diferente de excluir valida os dados obrigatórios
if ($acao != 'excluir' && $mensagem != ''):
    $mensagem = '<ul>' . $mensagem . '</ul>';
    echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div>";
    exit;
endif;

// Verifica se foi solicitada a inclusão de dados
if ($acao == 'incluir'):

    // Primeiro, insere na tabela tab_membros
    $sql_membros = 'INSERT INTO tab_membros (id, nome) VALUES(:id, :nome)';
    $stm_membros = $conexao->prepare($sql_membros);
    $stm_membros->bindValue(':id', $id);
    $stm_membros->bindValue(':nome', $nome);
    $retorno_membros = $stm_membros->execute();

    // Agora, insere na tabela tab_armas
    $sql_armas = 'INSERT INTO tab_armas (numsigma, tipo, modelo, calibre, validade_gt, validade_craf, id_membro) VALUES(:numsigma, :tipo, :modelo, :calibre, :validade_gt, :validade_craf, :id)';
    $stm_armas = $conexao->prepare($sql_armas);
    $stm_armas->bindValue(':tipo', $tipo);
    $stm_armas->bindValue(':modelo', $modelo);    
    $stm_armas->bindValue(':calibre', $calibre);
    $stm_armas->bindValue(':numsigma', $numsigma);    
    $stm_armas->bindValue(':validade_gt', $validade_gt);
    $stm_armas->bindValue(':validade_craf', $validade_craf);
    $stm_armas->bindValue(':id', $id); // Relaciona com o id da tabela tab_membros
    $retorno_armas = $stm_armas->execute();

    if ($retorno_membros && $retorno_armas):
        $_SESSION['msg'] = "<div class='alert alert-secondary' role='alert'><strong>Registro incluído com sucesso.</strong></div>";
    else:
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div>";
    endif;

    echo "<script>window.location='armas.php?id=".$id."';</script>";

endif;

// Verifica se foi solicitada a edição de dados
if ($acao == 'editar'):

    // Primeiro, atualiza a tabela tab_membros
    $sql_membros = 'UPDATE tab_membros SET nome=:nome WHERE id = :id';
    $stm_membros = $conexao->prepare($sql_membros);
    $stm_membros->bindValue(':id', $id);
    $stm_membros->bindValue(':nome', $nome);
    $retorno_membros = $stm_membros->execute();

    // Agora, atualiza a tabela tab_armas
    $sql_armas = 'UPDATE tab_armas SET tipo=:tipo, modelo=:modelo, calibre=:calibre, numsigma=:numsigma, validade_gt=:validade_gt, validade_craf=:validade_craf WHERE id_membro = :id';
    $stm_armas = $conexao->prepare($sql_armas);
    $stm_armas->bindValue(':tipo', $tipo);
    $stm_armas->bindValue(':modelo', $modelo);    
    $stm_armas->bindValue(':calibre', $calibre);
    $stm_armas->bindValue(':numsigma', $numsigma);    
    $stm_armas->bindValue(':validade_gt', $validade_gt);
    $stm_armas->bindValue(':validade_craf', $validade_craf);
    $stm_armas->bindValue(':id', $id); // Relaciona com o id da tabela tab_membros
    $retorno_armas = $stm_armas->execute();

    if ($retorno_membros && $retorno_armas):
        $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Registro atualizado.</div>';
    else:
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Erro ao editar registro!</div>';
    endif;

    echo "<script>window.location='armas.php?id=".$id."';</script>";

endif;

// Verifica se foi solicitada a exclusão dos dados
if ($acao == 'excluir'):

    // Exclui apenas o registro da tabela tab_armas
    $sql_armas = 'DELETE FROM tab_armas WHERE id_membro = :id';
    $stm_armas = $conexao->prepare($sql_armas);
    $stm_armas->bindValue(':id', $id);
    $retorno_armas = $stm_armas->execute();

    if ($retorno_armas):
        $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Registro excluído com sucesso.</div>';
    else:
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Erro ao excluir registro!</div>';
    endif;

    echo "<script>window.location='armas.php';</script>";

endif;
?>
</div>
</body>
</html>