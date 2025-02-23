<?php

include "config/config.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit();
}

// Logout
if (isset($_POST['but_logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

require 'config/conexao.php';

// Inicializa a variável de conexão
$conexao = conexao::getInstance();

// Define o ID, se estiver disponível
$id = isset($_GET['id']) ? $_GET['id'] : null;


// Consulta para obter o maior número de comanda
$sql = 'SELECT MAX(numero_comanda) AS max_numero FROM tab_comanda';
$stm = $conexao->prepare($sql);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_OBJ);
//listar produtos
$sql = "SELECT id, produto_servico, valor_unitario, data_cadastro FROM tab_precos";
$stm = $conexao->prepare($sql);
$stm->execute();
$precos = $stm->fetchAll(PDO::FETCH_OBJ); // Buscando os dados como objetos



        
// Incrementa o maior número de comanda em 1 para definir o próximo número
$proximo_numero = ($result->max_numero !== null) ? $result->max_numero + 1 : 1;

// Formata o número para que sempre tenha 5 dígitos, com zeros à esquerda
$proximo_numero_formatado = sprintf('%05d', $proximo_numero);

// Consulta para obter a soma dos valores do dia atual apenas para status "aberta" e "fechada"
$sql_soma_diaria = 'SELECT SUM(total) AS total_dia
FROM tab_comanda
WHERE DATE(data_hora) = CURDATE() AND status IN ("Aberta", "Fechada", "Paga", "Cancelada")';
$stm_soma_diaria = $conexao->prepare($sql_soma_diaria);
$stm_soma_diaria->execute();
$result_soma_diaria = $stm_soma_diaria->fetch(PDO::FETCH_OBJ);
$total_dia = $result_soma_diaria->total_dia;

// Consulta para obter produtos e serviços da tabela 'tab_precos'
$sql_produtos = 'SELECT produto_servico, valor_unitario FROM tab_precos ORDER BY produto_servico ASC';
$stm_produtos = $conexao->prepare($sql_produtos);
$stm_produtos->execute();
$produtos = $stm_produtos->fetchAll(PDO::FETCH_OBJ);

// Consulta as comandas do cliente
$sql_comandas = 'SELECT id, numero_comanda,  total, status, nome,  data_hora  
FROM tab_comanda ORDER BY data_hora DESC, status ASC';
$stm_comandas = $conexao->prepare($sql_comandas);
$stm_comandas->execute();
$comandas = $stm_comandas->fetchAll(PDO::FETCH_OBJ);





?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <link rel="shortcut icon" href="img/favicon.png">
  <title>ADM</title>

<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (Use apenas uma versão) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Custom CSS -->
<link href="css/elegant-icons-style.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet">
<link href="css/style-responsive.css" rel="stylesheet" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JQuery (deve ser carregado antes do Select2 JS) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body>
  <section id="container" class="">
    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>

      <div class="nav search-row" id="top_menu">
        <ul class="nav top-menu">
          <li></li>
        </ul>
      </div>

      <div class="top-nav notification-row">
        <ul class="nav pull-right top-menu">
          <li class="dropdown">            
            <form method='post' action="">
              <input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout">
            </form>
          </li>
        </ul>
      </div>
    </header>

    <?php include 'menu_lateral_esq.php';?>

    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i>Comandas</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>
        <!-- Botão para abrir o modal -->
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#comandaModal">
        <i class="fa fa-plus" aria-hidden="true"></i> COMANDA</button>
        <br><br>

        <!-- Exibe a soma dos valores do dia atual se o total for maior que zero -->
<?php if (!is_null($total_dia) && $total_dia > 0): ?>
  <div class="alert alert-info">
    <strong>Total do dia:</strong> R$ <?= number_format($total_dia, 2, ',', '.') ?>
  </div>
<?php else: ?>
  <div class="alert alert-info">
    <strong>Total do dia:</strong> R$ 0,00
  </div>
<?php endif; ?>

        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">Comandas</header>
<table class="table table-striped table-advance table-hover">
    <thead>
        <tr>
            <th>Data e Hora</th>
            <th>Nome</th>
            <th>Produto/Serviço</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($comandas): ?>
            <?php foreach ($comandas as $comanda): ?>
                <tr>

                    <td><?= date('d/m/Y H:i', strtotime($comanda->data_hora)); ?></td>
                    <td><?= htmlspecialchars($comanda->nome); ?></td>
                    
                    <td><?= htmlspecialchars($comanda->produto_servico); ?>
                    
                    
                    
                    <?php 
                    
                        $sql_comandas_itens = 'SELECT * FROM tab_comanda_itens where comanda_id ="'.$comanda->id.'"';
                        $stm_comandas_it = $conexao->prepare($sql_comandas_itens);
                        $stm_comandas_it->execute();
                        $comandas_itens = $stm_comandas_it->fetchAll(PDO::FETCH_OBJ);
                    
                    ?>
                    
                    <?php foreach($comandas_itens as $itemComanda):?>
                    <?php if(count($comandas_itens) >1){
                       echo $itemComanda->produto." | "; 
                    }else{
                        echo $itemComanda->produto;  
                    }
                        
                    ?>
                        
                       
                    <?php endforeach;?>
                    
                    </td>
                    <td><?= 'R$ ' . number_format($comanda->total, 2, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($comanda->status); ?></td>
                    <td>
                        
                        
                    <!-- Botão de Editar como Modal -->
                    <button class="btn btn-info btn-sm editarComandaBtn" 
                        data-id="<?= $comanda->id; ?>" 
                        data-numero="<?= htmlspecialchars($comanda->numero_comanda); ?>" 
                        data-produto_servico="<?= htmlspecialchars($comanda->produto_servico); ?>" 
                        data-qtde="<?= htmlspecialchars($comanda->qtde); ?>" 
                        data-valor="<?= htmlspecialchars($comanda->valor); ?>" 
                        data-status="<?= htmlspecialchars($comanda->status); ?>"
                        data-total="<?= htmlspecialchars($comanda->total); ?>" 
                        data-toggle="modal" 
                        data-target="#editComandaModal-<?php echo $comanda->id?>">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </button>

                    
                        <!-- Botão de Imprimir -->
                        <button class="btn btn-info btn-sm" onclick="imprimirComanda(<?= $comanda->id; ?>)">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </button>

                        <!-- Botão de Excluir -->
                        <a class="btn btn-info btn-sm" href="excluir_comanda.php?id=<?= $comanda->id; ?>" onclick="return confirm('Tem certeza que deseja excluir esta comanda?');">
                            <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <!-- Modal de Edição -->
<div class="modal fade" id="editComandaModal-<?php echo $comanda->id?>" tabindex="-1" role="dialog" aria-labelledby="editComandaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editComandaModalLabel">Editar Comanda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditComanda" method="POST" action="action_edit_comanda.php">
                    <input type="hidden" name="id" value="<?php echo $comanda->id; ?>">
                    <div class="form-group">
                        <label for="edit_numero_comanda">Número da Comanda</label>
                        <input type="text" class="form-control" id="edit_numero_comanda" name="numero_comanda" value="<?php echo $comanda->id?>" readonly>
                    </div> 
                    <div class="form-group">
                        <label for="edit_nome_comanda">Nome</label>
                        <input type="text" class="form-control" id="edit_nome_comanda" name="nome_comanda" value="<?php echo $comanda->nome?>">
                    </div>
                     <?php 
                    
                        $sql_comandas_itens = 'SELECT * FROM tab_comanda_itens where comanda_id ="'.$comanda->id.'"';
                        $stm_comandas_it = $conexao->prepare($sql_comandas_itens);
                        $stm_comandas_it->execute();
                        $comandas_itens = $stm_comandas_it->fetchAll(PDO::FETCH_OBJ);
                    
                    ?>
                    
                    <?php foreach($comandas_itens as $cmdi):?>
                    
                    <div class="form-group">
                        <label for="edit_produto_servico">Produto/Serviço</label>
                        <input type="text" class="form-control" id="edit_produto_servico" name="produtos[<?php echo $cmdi->id;?>][produto]" value="<?php echo $cmdi->produto;?>">
                    </div>
                   
                    <!-- Agrupar Quantidade e Valor Unitário na mesma linha -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_qtde">Quantidade</label>
                            <input type="number" class="form-control" id="edit_qtde" name="produtos[<?php echo $cmdi->id;?>][quantidade]" value="<?php echo $cmdi->quantidade;?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_valor">Valor Unitário</label>
                            <input type="number" class="form-control" id="edit_valor" name="produtos[<?php echo $cmdi->id;?>][valor]" value="<?php echo $cmdi->valor;?>">
                        </div>
                        <input type="hidden" name="produtos[<?php echo $cmdi->id;?>][idproduto]" value="<?php echo $cmdi->id;?>">
                    </div>
                     <?php endforeach;?>
             
                   <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="<?echo $comanda->status?>" selected><?echo $comanda->status?></option>
                            <option value="Aberta">Aberta</option>
                            <option value="Fechada">Fechada</option>
                            <option value="Paga">Paga</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <!--<div class="form-group">-->
                    <!--    <label for="edit_total">Total</label>-->
                    <!--    <input type="number" class="form-control" id="edit_total" name="total" readonly>-->
                    <!--</div>-->
                    


                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhuma comanda encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
            </section>
          </div>
        </div>

      </section>
    </section>


<!-- Modal -->
<div class="modal fade" id="comandaModal" tabindex="-1" role="dialog" aria-labelledby="comandaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comandaModalLabel">Registrar Comanda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formComanda" method="POST" action="action_comanda.php">
                   
                       
                             <div class="form-group">
                        <label for="numero_comanda">Número da Comanda</label>
                        <input type="text" class="form-control" id="numero_comanda" name="numero_comanda" value="<?php echo $proximo_numero_formatado; ?>" readonly>
                    </div>
                   
                     <div class="form-group">
                         <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                 
                      
                        
                    </div>
                   
                    <div class="form-group">
                        <label for="produto_servico">Produto/Serviço</label>
                        
                        <select class="form-control" name="produto_servico" id="produto_servico">
                            <option value="">Selecione</option>
                            <?php foreach($precos as $prod):?>
                                <option value="<?php echo $prod->id;?>"><?php echo $prod->produto_servico;?></option>
                            <?php endforeach ?>
                            
                        </select>
                    </div>

                    <!-- Agrupar Quantidade e Valor Unitário na mesma linha -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="qtde">Quantidade</label>
                            <input type="number" class="form-control" id="qtde" name="qtde">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="valor">Valor Unitário</label>
                            <input type="text" class="form-control" id="valor" name="valor" readonly>
                            <input type="hidden" id="nomeProduto">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="total"><i class="fa fa-usd" aria-hidden="true"></i> Total</label>
                        <input type="number" class="form-control" name="total" id="total" readonly>
                    </div>
                    <button type="button" class="btn btn-info btn-sm " id="addProduto"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                </form>
                <hr>
                <h5>Produtos adicionados</h5>
                <ul id="listaProdutos"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">x</button>
                <button type="button" id="registrarComanda" class="btn btn-info btn-sm"><i class="fa fa-check-square" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</div>




<script>
function imprimirComanda(comandaId) {
    // Você pode criar uma página PHP que renderiza apenas a comanda em questão
    // e abrir em uma nova aba para permitir a impressão
    var url = 'relatorios/imprimir_comanda.php?id=' + comandaId;
    window.open(url, '_blank');
}
</script>


<script>
// Função para calcular o total baseado em quantidade e valor unitário
function calcularTotal(qtde, valor) {
    return (qtde * valor).toFixed(2);
}

// Função para atualizar o total ao alterar quantidade ou valor no modal de edição
$(document).on('input', '#edit_qtde, #edit_valor', function() {
    var qtde = parseFloat($('#edit_qtde').val()) || 0;
    var valor = parseFloat($('#edit_valor').val()) || 0;
    var total = calcularTotal(qtde, valor);
    $('#edit_total').val(total);
});

// Preencher o modal de edição com os dados da comanda
$('.editarComandaBtn').on('click', function() {
    var id = $(this).data('id');
    var numero = $(this).data('numero');
    var produto_servico = $(this).data('produto_servico');
    var qtde = $(this).data('qtde');
    var valor = $(this).data('valor');
    var status = $(this).data('status');
    var total = $(this).data('total');

    $('#edit_numero_comanda').val(numero);
    $('#edit_produto_servico').val(produto);
    $('#edit_qtde').val(qtde);
    $('#edit_valor').val(valor);
    $('#status').val(status);    
    $('#edit_total').val(total);
});

</script>
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

 <!--<script type="text/javascript" src="js/custom.js"></script>-->
 <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  <script src="js/jquery.maskMoney.js"></script>
 


<script>
    let produtos = [];

    // Função para calcular o total
    $('#qtde, #valor').on('input', function() {
        let qtde = $('#qtde').val();
        let valor = $('#valor').val();
        let total = qtde * valor;
        $('#total').val(total.toFixed(2));
    });
    
  

    // Adicionar produto na lista
    $('#addProduto').on('click', function() {
        let produto_servico = $('#produto_servico').val();
        let qtde = $('#qtde').val();
        let valor = $('#valor').val();
        let total = $('#total').val();
        let nomeProduto=$('#nomeProduto').val();

        if (produto_servico && qtde && valor && total) {
            let produto = {
                produto_servico: produto_servico,
                qtde: qtde,
                valor: valor,
                total: total,
                nomeProduto:nomeProduto
            };

            produtos.push(produto);

            // Exibir produto na lista
            $('#listaProdutos').append('<li>' + produto_servico + ' - Qtde: ' + qtde + ', Valor: ' + valor + ', Status: ' + status + ', Total: ' + total + '</li>');

            // Limpar os campos
            $('#produto_servico').val('');
            $('#qtde').val('');
            $('#valor').val('');
            $('#status').val('');
            $('#total').val('');
        } else {
            alert("Preencha todos os campos antes de adicionar.");
        }
    });

    // Registrar comanda e produtos
    $('#registrarComanda').on('click', function() {
        if (produtos.length === 0) {
            alert("Adicione pelo menos um produto antes de registrar a comanda.");
            return;
        }

        let form = $('#formComanda');
        let numero_comanda = $('#numero_comanda').val();

        // Adicionar campos ocultos para cada produto no formulário
        produtos.forEach(function(produto, index) {
            form.append('<input type="hidden" name="produtos[' + index + '][produto_servico]" value="' + produto.produto_servico + '">');
            form.append('<input type="hidden" name="produtos[' + index + '][qtde]" value="' + produto.qtde + '">');
            form.append('<input type="hidden" name="produtos[' + index + '][valor]" value="' + produto.valor + '">');
            form.append('<input type="hidden" name="produtos[' + index + '][nomeProduto]" value="' + produto.nomeProduto + '">');
            form.append('<input type="hidden" name="produtos[' + index + '][status]" value="' + produto.status + '">');
            form.append('<input type="hidden" name="produtos[' + index + '][total]" value="' + produto.total + '">');
        });

        // Submeter o formulário
        form.submit();
    });
    
   
                    $('#produto_servico').change(function() {
                        var produtoServicoId = $('#produto_servico').val(); // Captura o valor selecionado
                
                        // Fazer a chamada AJAX para o backend passando o produtoServicoId como parâmetro
                        $.ajax({
                            url: 'preco_produto.php', // Arquivo PHP onde a consulta será realizada
                            method: 'post',
                            data: { id_produto: produtoServicoId },
                            success: function(response) {
                                // Supondo que a resposta seja o valor_unitario do produto
                                
                             console.log(response.valor);
                                
                                $('#valor').val(response.valor); // Define o valor no campo #valor
                                $('#nomeProduto').val(response.nomeProduto)
                            },
    error: function(xhr, status, error) {
        console.error('Erro na requisição AJAX:', error);
    }
                        });
                    });
             
                                
                                
    
                               
</script>
</section>
</section>
</body>
</html>