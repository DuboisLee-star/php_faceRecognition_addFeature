
		<?php 
		require 'config/conexao.php';

		// Atribui uma conex�o PDO
$conexao = conexao::getInstance();

// Recebe os dados enviados pela submissão
$sql1 = 'SELECT * FROM tab_membros';
$stm = $conexao->prepare($sql1);
$stm->execute();
$membros = $stm->fetchAll(PDO::FETCH_OBJ);

$sql3 = 'SELECT * FROM info_clube';
$stm = $conexao->prepare($sql3);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);

$valor_mensal = $clube->valor_plano_fixo_mensal;
$valor_anual = $clube->valor_plano_fixo_anual;
$forma_pgto = '';
$obs = '';
$ano_atual = (new DateTime())->format('Y'); // Obtém o ano atual
$acao='incluir';
if ($acao == 'incluir') {
    foreach ($membros as $membro) {
        $matricula = $membro->matricula;
        $id = $membro->id;

        if ($membro->plano_pgto == 'A') {
            // Plano anual
            $valor = $valor_anual;
            $data_pgto = "{$ano_atual}-01-01"; // Pagamento anual no início do ano

            // Verifica duplicidade
            $sql_check = 'SELECT COUNT(*) FROM tab_financeiro_2 WHERE id_membro = :id_membro AND data_pgto = :data_pgto';
            $stm_check = $conexao->prepare($sql_check);
            $stm_check->bindValue(':id_membro', $id);
            $stm_check->bindValue(':data_pgto', $data_pgto);
            $stm_check->execute();
            $exists = $stm_check->fetchColumn();

            if (!$exists) {
                $sql = 'INSERT INTO tab_financeiro_2 (id_membro, matricula, valor, plano, data_pgto, forma_pgto,status_pgto, obs)
                        VALUES (:id_membro, :matricula, :valor, :plano, :data_pgto, :forma_pgto, :status_pgto,:obs)';
                $stm = $conexao->prepare($sql);
                $stm->bindValue(':id_membro', $id);
                $stm->bindValue(':matricula', $matricula);
                $stm->bindValue(':valor', $valor);
                $stm->bindValue(':plano', 'A');
                $stm->bindValue(':data_pgto', $data_pgto);
                $stm->bindValue(':forma_pgto', $forma_pgto);
                 $stm->bindValue(':status_pgto', 'pendente');
                $stm->bindValue(':obs', $obs);
                $stm->execute();
            }
        } else {
            // Plano mensal
            $valor = $valor_mensal;
            for ($mes = 1; $mes <= 12; $mes++) {
                $data_pgto = sprintf('%s-%02d-01', $ano_atual, $mes); // Data de pagamento no 1º dia do mês

                // Verifica duplicidade
                $sql_check = 'SELECT COUNT(*) FROM tab_financeiro_2 WHERE id_membro = :id_membro AND data_pgto = :data_pgto';
                $stm_check = $conexao->prepare($sql_check);
                $stm_check->bindValue(':id_membro', $id);
                $stm_check->bindValue(':data_pgto', $data_pgto);
                $stm_check->execute();
                $exists = $stm_check->fetchColumn();

                if (!$exists) {
                    $sql = 'INSERT INTO tab_financeiro_2 (id_membro, matricula, valor, plano, data_pgto, forma_pgto, status_pgto, obs)
                            VALUES (:id_membro, :matricula, :valor, :plano, :data_pgto, :forma_pgto,:status_pgto, :obs)';
                    $stm = $conexao->prepare($sql);
                    $stm->bindValue(':id_membro', $id);
                    $stm->bindValue(':matricula', $matricula);
                    $stm->bindValue(':valor', $valor);
                    $stm->bindValue(':plano', 'M');
                    $stm->bindValue(':data_pgto', $data_pgto);
                    $stm->bindValue(':forma_pgto', $forma_pgto);
                     $stm->bindValue(':status_pgto', 'pendente');
                    $stm->bindValue(':obs', $obs);
                    $stm->execute();
                }
            }
        }
    }
}


?>

