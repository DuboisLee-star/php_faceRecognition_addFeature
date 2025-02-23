<?php

include "config/config.php";

function restante($data){
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($data); // YYYY-MM-DD
	$interval = $date1->diff($date2);
	return $interval->days;

}

?>

<?php

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
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

// dados do termo
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);
$termos_filiacao = $clube->termos_filiacao;
$termos_estatuto = $clube->termos_estatuto;
$termos_idoneidade = $clube->termos_idoneidade;
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />  
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


    <!-- menu lateral inicio -->
	


    <!--menu lateral fim-->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
				<form name="cadastro" action="action_cadastro_atualizar.php" method="post" id='form-contato' enctype='multipart/form-data'>

				

       <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Fun&ccedil;&atilde;o do Atirador no Clube e CR
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Nome Completo</td>
                    <td><input type="text" class="form-control" id="nome" name="nome" value="<?=$cliente->nome?>" style="text-transform: uppercase;"></td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		

       
  
       <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados de Contato
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Email</td>
                    <td><input type="email" class="form-control" id="email" name="email" value="<?=$cliente->email?>"></td>
                 </tr>
                  <tr>
                    <td>Whatsapp</td>
                    <td><input type="tel" class="form-control" maxlength="14"  id="telefone" name="telefone" value="<?=$cliente->telefone?>" OnKeyPress="formatar('##-#-####-####', this)"></td>
                 </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>		

 		  
					<input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="submit" class="btn btn-info" id='botao'>ENVIAR MEUS DADOS</button>
                    </form>
                    <?php endif; ?>
              		</fieldset>					  

   <!--main content end-->
	<!--------------------------------------------------------- page end-->
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
   
  
   
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
  <script type="text/javascript">
  
  jQuery(document).ready(function( $ ) {
  function limpa_formulario_cep2() {
  
  $("#segundo_rua").val("");
  $("#segundo_bairro").val("");
  $("#segundo_cidade").val("");
  $("#segundo_estado").val("");
  }
  
  $("#segundo_cep").blur(function() {
  
  var cep2 = $(this).val().replace(/\D/g, '');
  
  if (cep2 != "") {
  
  var validacep2 = /^[0-9]{8}$/;
  
  if(validacep2.test(cep2)) {
  
  $("#segundo_rua").val("...");
  $("#segundo_bairro").val("...");						
  $("#segundo_cidade").val("...");
  $("#segundo_estado").val("...");
  
  $.getJSON("//viacep.com.br/ws/"+ cep2 +"/json/?callback=?", function(dados2) {
  
  if (!("erro" in dados2)) {
  
  $("#segundo_rua").val(dados2.logradouro);
  $("#segundo_bairro").val(dados2.bairro);					
  $("#segundo_cidade").val(dados2.localidade);
  $("#segundo_estado").val(dados2.uf);
  }
  else {
  
  limpa_formulario_cep2();
  alert("CEP não encontrado.");
  }
  });
  }
  else {
  
  limpa_formulario_cep2();
  alert("Formato de CEP inválido.");
  }
  }
  else {
  
  limpa_formulario_cep2();
  }
  });
  
  $("#segundo_cep").mask("00000-000");
  
  });
  </script> 
  
 
 
  
  <script> 
  const selectorAll = document.querySelectorAll.bind(document);
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
  };
  
  </script>

<script type="text/javascript">
		$("#segundo_cep").focusout(function(){
			//Início do Comando AJAX
			$.ajax({
				//O campo URL diz o caminho de onde virá os dados
				//É importante concatenar o valor digitado no CEP
				url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/unicode/',
				//Aqui você deve preencher o tipo de dados que será lido,
				//no caso, estamos lendo JSON.
				dataType: 'json',
				//SUCESS é referente a função que será executada caso
				//ele consiga ler a fonte de dados com sucesso.
				//O parâmetro dentro da função se refere ao nome da variável
				//que você vai dar para ler esse objeto.
				success: function(resposta){
					//Agora basta definir os valores que você deseja preencher
					//automaticamente nos campos acima.
					$("#segundo_rua").val(resposta.logradouro);
			
					$("#segundo_bairro").val(resposta.bairro);
					$("#segundo_cidade").val(resposta.localidade);
					$("#segundo_estado").val(resposta.uf);
					//Vamos incluir para que o Número seja focado automaticamente
					//melhorando a experiência do usuário
					$("#segundo_num").focus();
				}
			});
		});
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