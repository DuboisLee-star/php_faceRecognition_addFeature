<?php

include "config/config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}
?>

<?php

require 'config/conexao.php';

// Recebe o termo de pesquisa se existir
$termo = (isset($_GET['termo'])) ? $_GET['termo'] : '';

// Verifica se o termo de pesquisa está vazio, se estiver executa uma consulta completa
if (empty($termo)):

	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_procuradores order by id';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

else:

	// Executa uma consulta baseada no termo de pesquisa passado como parâmetro
	$conexao = conexao::getInstance();
	$sql = 'SELECT id, nome FROM tab_procuradores WHERE nome LIKE :nome OR id LIKE :id order by id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':nome', $termo.'%');
	$stm->bindValue(':id', $termo.'%');
	$stm->execute();
	$clientes = $stm->fetchAll(PDO::FETCH_OBJ);

endif;
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
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- bootstrap theme -->
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <!--external css-->
  <!-- font icon -->
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->

<script>
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}
function salva(){
	
    var apelino = document.getElementById("apelido").value;
	var nome = document.getElementById("nome").value;
	var endereco = document.getElementById("endereco").value;
	var cep = document.getElementById("cep").value;
	var cidade = document.getElementById("cidade").value;
	var siglauf = document.getElementById("siglauf").value;
	var estadocivil = document.getElementById("estadocivil").value;
	var rua = document.getElementById("rua").value;
	var numero = document.getElementById("numero").value;
	var bairro = document.getElementById("bairro").value;
	
	var naturalidade = document.getElementById("naturalidade").value;
	var telefone = document.getElementById("telefone").value;
	var email = document.getElementById("email").value;
	var ufnasc = document.getElementById("ufnasc").value;
	var siglauf = document.getElementById("siglauf").value;
	var estadocivil = document.getElementById("estadocivil").value;
	var naturalidade = document.getElementById("naturalidade").value;
	var ufnasc = document.getElementById("ufnasc").value;
	var nacionalidade = document.getElementById("nacionalidade").value;
	var cpf = document.getElementById("cpf").value;	
	var identidade = document.getElementById("identidade").value;
	var orgaouf = document.getElementById("orgaouf").value;
	var data_exped = document.getElementById("data_exped").value;	
	
    if(apelido == ""){alert("Preencha o campo Nome Completo."); return false;}
	if(nome == ""){alert("Preencha o campo Nome Completo."); return false;}
	if(rua == ""){alert("Preencha o campo rua."); return false;}
	if(bairro == ""){alert("Preencha o campo bairro."); return false;}
	if(numero == ""){alert("Preencha o campo numero."); return false;}
	
	if(cep == ""){alert("Preencha o campo CEP."); return false;}
	if(cidade == ""){alert("Preencha o campo Cidade."); return false;}
	if(siglauf == ""){alert("Preencha o campo UF."); return false;}
	if(estadocivil == ""){alert("Preencha o campo Estado Civil."); return false;}
	if(naturalidade == ""){alert("Preencha o campo Naturalidade."); return false;}
	if(telefone == ""){alert("Preencha o campo Naturalidade."); return false;}
	if(email == ""){alert("Preencha o campo Naturalidade."); return false;}
	if(ufnasc == ""){alert("Preencha o campo UF de nascimento."); return false;}
	if(nacionalidade == ""){alert("Preencha o campo Nacionalidade."); return false;}
	if(cpf == ""){alert("Preencha o campo CPF."); return false;}
	if(identidade == ""){alert("Preencha o campo Identidade."); return false;}
	if(orgaouf == ""){alert("Preencha o campo Órgão-UF."); return false;}
	if(data_exped == ""){alert("Preencha o campo Data Expedicao."); return false;}
	
	document.getElementById("form-contato").submit();
	
}
</script>

</head>

<body>
  <!-- container section start -->
  <section id="container" class="">
    <!--header start-->

    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
     <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
          </li>
        </ul>
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">
          <li class="dropdown">            
          <form method='post' action=""><input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout"></form>
          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->


    <!-- menu lateral inicio -->
	
<?php include 'menu_lateral_esq.php';?>

    <!--menu lateral fim-->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-users" aria-hidden="true"></i>Procuradores</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-users"></i>Procuradores</li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Relação de Procuradores
              </header>
              <div class="table-responsive">
			  <?php if(!empty($clientes)):?>
                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th><i class="icon_profile"></i> Procurador</th>
                      <th><i class="fa fa-mobile"></i> Telefone</th>
                      <th><i class="fa fa-envelope"></i> Email</th>					  
                      <th><i class="icon_cogs"></i> Ação</th>
                    </tr>
					<?php foreach($clientes as $cliente):?>
                  </thead>
                  <tbody>
                    <tr>
					  <td><font size="2"><?=$cliente->id?></font></td>
					  <td><font size="2"><?=$cliente->apelido?></font></td>
					  <td><font size="2"><?=$cliente->telefone?></font></td>
					  <td><font size="2"><?=$cliente->email?></font></td>
					  <td>

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='editar_proc.php?id=<?=$cliente->id?>' Title="Editar" alt="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
</div>						

<div class="btn-group btn-group-sm">
<a class="btn btn-info btn-sm" href='action_del_proc.php?id=<?=$cliente->id?>' onclick="return confirm('Confirma excluir registro?');"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
</div>


					  </td>
                    </tr>
                  </tbody>
				  <?php endforeach;?>
                </table>
				<?php else: ?>

				<!-- Mensagem caso não exista clientes ou não encontrado  -->
				<h3 class="text-center text-primary">Não existem procuradores cadastrados!</h3>
			<?php endif; ?>
		</fieldset>
              </div>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">

            </section>
          </div>
        </div>
		
		
		 <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Cadastrar Novo Procurador
              </header>
              <div class="panel-body">
                <div class="form">
				<form action="action_cadastro_procuradores.php" name="cadastro" method="post" id='form-contato' enctype='multipart/form-data'>
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Apelido</td>
                    <td><input type="text" class="form-control" id="apelido" name="apelido"></td>
                 </tr>
                  <tr>
                    <td>Nome</td>
                    <td><input type="text" class="form-control" id="nome" name="nome"></td>
                 </tr>
                  
                  <tr>
                   <td>Cep</td>
                    <td><input type="text" class="form-control" id="cep" name="cep"></td>
                </tr> 
                 <tr>
                 <td>Numero</td>
                 <td><input type="text" class="form-control" id="numero" name="numero"></td>
                 </tr>
                <tr> 
                <td>Rua</td>
                <td><input type="text" class="form-control" id="rua" name="rua"></td>
                </tr> 
                <tr>
                <td>Bairro</td>
                <td><input type="text" class="form-control" id="bairro" name="bairro"></td>
                </tr>
                  <tr>
                   <td>Cidade</td>
                    <td><input type="text" class="form-control" id="cidade" name="cidade"></td>
                </tr>
                  <tr>
                   <td>Estado</td>
                    <td><input type="text" class="form-control" id="siglauf" name="siglauf"></td>
                </tr>
                  <tr>
                   <td>Estado Civil</td>
                    <td><input type="text" class="form-control" id="estadocivil" name="estadocivil"></td>
                </tr>
                  <tr>
                   <td>Naturalidade</td>
                    <td><input type="text" class="form-control" id="naturalidade" name="naturalidade"></td>
                </tr>
                  <tr>
                   <td>UF Nascimento</td>
                    <td><input type="text" class="form-control" id="ufnasc" name="ufnasc"></td>
                </tr>
                  <tr>
                   <td>Nacionalidade</td>
                    <td><input type="text" class="form-control" id="nacionalidade" name="nacionalidade"></td>
                </tr>
                  <tr>
                   <td>Telefone</td>
                    <td><input type="text" class="form-control" id="telefone" name="telefone"></td>
                </tr>
                  <tr>
                   <td>Email</td>
                    <td><input type="text" class="form-control" id="email" name="email"></td>
                  </tr>
                  <tr>
                   <td>CPF</td>
                    <td><input type="text" class="form-control" id="cpf" name="cpf"></td>
                </tr>
                  <tr>
                   <td>Identidade</td>
                    <td><input type="text" class="form-control" id="identidade" name="identidade"></td>
                  </tr>
                  <tr>
                   <td>Órgão UF</td>
                    <td><input type="text" class="form-control" id="orgaouf" name="orgaouf"></td>
                  </tr>
                  <tr>
                   <td>Data Expedição</td>
                    <td><input type="date" class="form-control" id="data_exped" name="data_exped"></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		
				            <input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
                    </fieldset></form>
        <!------------------------------------------------------------------------------------------------------------ page end-->
		
		
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">
          <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version form: https://bootstrapmade.com/buy/?theme=NiceAdmin
          -->
          by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
        </div>
    </div>
  </section>
  <!-- container section end -->
  <!-- javascripts -->  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js">
  </script>
  <script>const selectorAll = document.querySelectorAll.bind(document);
  const id = document.getElementById.bind(document);
  function mascara(o, f) {
  obj = o;
  fun = f;
  setTimeout("execmascara()", 1);
  }
  function execmascara() {
  obj.value = fun(obj.value);
  }
  
  function validaCep(v) {
  v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
  v = v.replace(/^(\d{5})(\d{3})$/g, "$1-$2");
  return v;
  }
  
  
  window.onload = () => {
  
  let cep = id("cep");
  cep.onkeyup = function () {
  mascara(this, validaCep);
  };
  };
  
  function limpa_formulário_cep() {
  //Limpa valores do formulário de cep.
  id("bairro").value = ""; 
  id("rua").value = ""; 
  id("cidade").value = "";
  id("siglauf").value = "";
  
  }
  function meu_callback(conteudo) {
  if (!("erro" in conteudo)) {
  //Atualiza os campos com os valores.
  id("bairro").value = conteudo.bairro;
  id("rua").value = conteudo.logradouro;
  id("cidade").value = conteudo.localidade;
  id("siglauf").value = conteudo.uf;
  
  } else {
  //end if.
  //CEP não Encontrado.
  limpa_formulário_cep();
  respostaCep.style.display = "block";
  respostaCep.innerHTML = "CEP não encontrado.";
  }
  }
  function pesquisacep(valor) {
  //Preenche os campos com "..." enquanto consulta webservice.
  /**
  document.getElementById('rua').value="...";
  document.getElementById('bairro').value="...";
  document.getElementById('cidade').value="...";
  document.getElementById('uf').value="...";
  document.getElementById('ibge').value="...";
  */
  //Nova variável "cep" somente com dígitos.
  let cep = valor.replace(/\D/g, "");
  //Verifica se campo cep possui valor informado.
  if (cep != "") {
  //Expressão regular para validar o CEP.
  let validacep = /^[0-9]{8}$/;
  //Valida o formato do CEP.
  if (validacep.test(cep)) {
  //Cria um elemento javascript.
  let script = document.createElement("script");
  //Sincroniza com o callback.
  script.src =
  "https://viacep.com.br/ws/" + cep + "/json/?callback=meu_callback";
  //Insere script no documento e carrega o conteúdo.
  document.body.appendChild(script);
  respostaCep.style.display = "none";
  respostaCep.innerHTML = "";
  } else {
  //end if.
  //cep é inválido.
  limpa_formulário_cep();
  respostaCep.style.display = "block";
  respostaCep.innerHTML = "Formato de CEP inválido.";
  }
  } else {
  //end if.
  //cep sem valor, limpa formulário.
  limpa_formulário_cep();
  }
  }
  
  cadastro.cep.onblur = function () {
  var cep = id("cep");
  pesquisacep(cep.value);
  };
  
  </script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>


</body>

</html>