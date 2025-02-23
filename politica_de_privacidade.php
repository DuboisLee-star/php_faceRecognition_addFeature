<?php

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele � num�rico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->data_nascimento);
		$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

	endif;

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
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

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
	

	
	var nome = document.getElementById("nome").value;
    var rua = document.getElementById("rua").value;	
    var numero = document.getElementById("numero").value;	
    var bairro = document.getElementById("bairro").value;	
	var cep = document.getElementById("cep").value;
	var cidade = document.getElementById("cidade").value;
	var siglauf = document.getElementById("siglauf").value;
	var email = document.getElementById("email").value;
	var telefone = document.getElementById("telefone").value;
	var cpf = document.getElementById("cpf").value;
	var identidade = document.getElementById("identidade").value;
	
	if(nome == ""){alert("Nome Completo não informado."); return false;}
	if(rua == ""){alert("Rua não informada."); return false;}
	if(numero == ""){alert("Numero não informado."); return false;}
	if(bairro == ""){alert("Bairro não informado."); return false;}	
	if(cep == ""){alert("CEP não informado."); return false;}
	if(cidade == ""){alert("Cidade não informado."); return false;}
	if(siglauf == ""){alert("UF não informado."); return false;}
	if(email == ""){alert("Email não informado."); return false;}
	if(telefone == ""){alert("Whatsapp não informado."); return false;}
	if(cpf == ""){alert("CPF não informado."); return false;}
	if(identidade == ""){alert("Identidade não informado."); return false;}
	

	document.getElementById("form-contato").submit();
	
}
</script>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->


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
     <a href="" class="logo">ADM <span class="lite">CLUBE</span></a>
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

          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				<form name="cadastro" action="action_cadastro_hostmarq_novo.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
								      	<div class="col-md-2">
					   
					    				  	</div>
				  	
			  	</div>
			<br>
				
 
			  
			  
<?php include 'config/politica.php';?>	  
			  
		  

					<input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				 
                      </form>
              		</fieldset>					  
			  

            				   
    <!--main content end-->
		
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
  id("rua").value = "";
  id("bairro").value = "";						
  id("cidade").value = "";
  id("siglauf").value = "";
  
  }
  function meu_callback(conteudo) {
  if (!("erro" in conteudo)) {
  //Atualiza os campos com os valores.
  id("rua").value = conteudo.logradouro;
  id("bairro").value = conteudo.bairro;						
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
  };</script>
  <!-- container section end -->
  <!-- javascripts --> 
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"> 
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