<?php
include '../config/config.php';
require '../config/conexao.php';

// Obtém o ID da comanda via GET
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $conexao = conexao::getInstance();

    // Consulta para obter os detalhes da comanda
    $sql = 'SELECT * FROM tab_comanda WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindParam(':id', $id);
    $stm->execute();
    $comanda = $stm->fetch(PDO::FETCH_OBJ);
    
    if ($comanda) {
        // Renderizar os dados da comanda para impressão
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Imprimir Comanda</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .comanda {
                    width: 300px;
                    margin: 0 auto;
                    text-align: justify;
                    border: 1px solid #000;
                    padding: 20px;
                }
                .comanda h1 {
                    font-size: 20px;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body onload="window.print();">
            <div class="comanda">
                 <div class="logo">
                    <img src="../img/logo_site_black.png" height="110">
                </div>
                <h1>Comanda Nº &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= htmlspecialchars($comanda->numero_comanda); ?></h1>
                <p><hr style="border: 1px solid gray; box-shadow: 0px 4px 2px -2px gray;"></p>
                <p>Data/Hora: <?= date('d/m/Y H:i', strtotime($comanda->data_hora)); ?></p>
                <table>
                    <th>Produto/Servico</th>
                    <th>Quant.</th>
                    <th>Val. Unit</th>
                    
                    <?php 
                    
                        $sql_comandas_itens = 'SELECT * FROM tab_comanda_itens where comanda_id ="'.$comanda->id.'"';
                        $stm_comandas_it = $conexao->prepare($sql_comandas_itens);
                        $stm_comandas_it->execute();
                        $comandas_itens = $stm_comandas_it->fetchAll(PDO::FETCH_OBJ);
                    
                    ?>
                    
                    <?php foreach($comandas_itens as $itemComanda):?>
                        <tr>
                        <td><?=$itemComanda->produto;?></td>
                        <td><?=$itemComanda->quantidade;?></td>
                        <td><?=number_format($itemComanda->valor,2,',','.');?></td>
                         </tr>
                    <?php endforeach;?>
                    
                    
                    
                        
                  
                    </tr>
                </table>
                <!--<p>Produto/Serviço: </p>-->
                <!--<p>Quantidade: <?= htmlspecialchars($comanda->qtde); ?></p>-->
                <!--<p>Valor Unitário: R$ <?= number_format($comanda->valor, 2, ',', '.'); ?></p>-->
                <p>Total: R$ <?= number_format($comanda->total, 2, ',', '.'); ?></p>
                <p><hr style="border: 1px solid gray; box-shadow: 0px 4px 2px -2px gray;"></p>
                <p>Status: <?= htmlspecialchars($comanda->status); ?></p>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Comanda não encontrada.";
    }
} else {
    echo "ID da comanda não fornecido.";
}
?>