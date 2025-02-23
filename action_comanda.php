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
	
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Certifique-se de que todos os campos necessários estão definidos

  $id = $_POST['id'] ?? null;
  $numero_comanda = $_POST['numero_comanda'];
  $produto_servico = $_POST['produtos'];
  $qtde = $_POST['qtde'];
  $valor = $_POST['valor'];
  $total = $_POST['total'];
  $status = $_POST['status'] ?? 'Aberto';
  $comanda_id = $_POST['comanda_id'];
  $nome=$_POST['nome'];

$total=0;

foreach($produto_servico as $prod){
    $total+=$prod['total'];
}
    // Preparar a consulta SQL
    $insertComanda = "INSERT INTO tab_comanda (numero_comanda, status, data_hora, total, nome) 
        VALUES ( ?,'Aberta',?,?,?)";

    // Preparar a declaração
    $stmt = $conn->prepare($insertComanda);
    // Vincular os parâmetros à consulta
    $stmt->bind_param('ssss',  $numero_comanda, date('Y-m-d H:i'), $total, $nome);
    $stmt->execute();
    $stmt->close();
    $idComanda = $conn->insert_id;
    
    foreach($produto_servico as $produto){
        
         $insertItensComanda = "insert into tab_comanda_itens (produto, quantidade, valor, comanda_id) VALUES (?,?,?,?)";

      $stmt2 = $conn->prepare($insertItensComanda);

      $stmt2->bind_param('sssi', $produto['nomeProduto'], $produto['qtde'], $produto['valor'], $idComanda);
      $stmt2->execute();
       
    }
    
    if ($stmt2) {
      header("Location: comanda.php");
      exit;
    } else {
  
      echo "Erro ao gravar itens da comanda: " . $mysqli->error;
    }
  }

$conn->close();
?>