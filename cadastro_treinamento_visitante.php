<?php

date_default_timezone_set('America/Sao_Paulo');

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

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_habitualidade WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
	  $conexao = conexao::getInstance();
      $sql4 = 'SELECT * FROM tab_armas ORDER BY descricao ASC';
      $stm = $conexao->prepare($sql4);
      $stm->execute();
      $armas = $stm->fetchAll(PDO::FETCH_OBJ);
    
      $conexao = conexao::getInstance();
      $sql5 = 'SELECT * FROM tab_habitu_op WHERE tipo = "L" ORDER BY local ASC';
      $stm = $conexao->prepare($sql5);
      $stm->execute();
      $habitu_op = $stm->fetchAll(PDO::FETCH_OBJ);

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

  <!-- PARA adicionar botao +  -->
    <link rel="stylesheet" href="path/to/your/css">
    <style>
        .form-group {
            margin-bottom: 10px;
        }
        .list-item {
            margin-bottom: 5px;
        }
    </style>
    
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i);

    if (texto.substring(0,1) != saida){
        documento.value += texto.substring(0,1);
    } else {
        documento.value += texto.substring(0,1);
    }
}

function salva(){
	
    var nome_visitante = document.getElementById("nome_visitante").value;	

	if(nome_visitante == ""){alert("Informe o nome do Atirador."); return false;}

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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>TREINAMENTO VISITANTE</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				<form name="cadastro" action="action_cadastro_treinamento_visitante.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
				
			      	<div class="col-md-2">
					    
				  	</div>
				  	
			  	</div>
			<br>
				


          <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                DADOS DO TREINAMENTO
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
                   <td><font color="red">Data do lançamento</font></td>
                    <td><font color="red"><?php echo date('d/m/Y H:i'); ?></font></td>
                  </tr>
                  <tr>	
                   <td>Data Inicial do Treino</td>
                    <td><input type="datetime-local" class="form-control" id="data_inicial_visitante" name="data_inicial_visitante" value=""></td>
                  </tr>
                  <tr>   
                   <td>Data Final do Treino</td>
                    <td><input type="datetime-local" class="form-control" id="data_final_visitante" name="data_final_visitante" value=""></td>
                  </tr>
                  <tr>                   
                   <td>CR do Atirador</td>
                    <td><input type="text" class="form-control" id="cr_visitante" name="cr_visitante" value=""></td>
                  </tr>
                  <tr>
                   <td>Validade do CR</td>
                    <td><input type="date" class="form-control" id="cr_visitante_validade" name="cr_visitante_validade" value=""></td>
                  </tr>
                  <tr>                      
                   <td>CPF do Visitante</td>
                    <td><input type="text" class="form-control" name="cpf_visitante" id="cpf_visitante" maxlength="14" value=""></td>
                  </tr>
                  <tr>
                    <td>Whatsapp do Visitante</td>
                    <td><input type="text" class="form-control" name="zap_visitante" id="zap_visitante" maxlength="15" value=""></td>
                  </tr>
                  <tr>	                      					  
                   <td>Nome do Atirador</td>
                    <td><input type="text" class="form-control" id="nome_visitante" name="nome_visitante" value="" style="text-transform: uppercase;"></td>
                  </tr>
                    <tr>
                      <td>Evento</td>
                      <td><input type="text" class="form-control" id="evento" name="evento" value="" style="text-transform: uppercase;">
                      </td>
                    </tr>
                    <tr>
                     <td>Modalidade</td>
                      <td>
                        <input type="text" class="form-control" id="modalidade" name="modalidade[]">
                        <span class='msg-erro msg-modalidade'></span>
                        <br>
                        <button type="button" id="add-line-modalidade">+</button>
                        <div id="dynamic-fields-modalidade"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Tipo de armamento</td>
                      <td>
                        <select class="form-control" id="tipo" name="tipo[]">
                          <option value="" disabled selected>- escolha uma arma -</option>
                          <option value="PISTOLA">PISTOLA</option>
                          <option value="REVÓLVER">REVÓLVER</option>
                          <option value="RIFLE">RIFLE</option>
                          <option value="CARABINA">CARABINA</option>
                          <option value="ESPINGARDA">ESPINGARDA</option>
                          <option value="FUZIL">FUZIL</option>
                        </select>
                        <span class='msg-erro msg-tipo'></span>
                        <br>
                        <button type="button" id="add-line-tipo">+</button>
                        <div id="dynamic-fields-tipo"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Calibre do Armamento</td>
                      <td>
                        <select class="form-control" id="calibre" name="calibre[]">
                          <option value="" disabled selected>- escolha uma arma -</option>
                            <option value="6.35">6.35</option>
                            <option value="17HMR">17HMR</option>
                            <option value=".22">.22</option>
                            <option value=".28">.28</option>
                            <option value=".30">.30</option>
                            <option value=".30M1">.30M1</option>
                            <option value=".30.06">.30.06</option>
                            <option value=".32">.32</option>
                            <option value=".36">.36</option>
                            <option value=".38">.38</option>
                            <option value=".38SPL">.38SPL</option>
                            <option value=".38SUPER">.38SUPER</option>
                            <option value=".380ACP">.380ACP</option>
                            <option value=".357MAG">.357MAG</option>
                            <option value="7MM">7MM</option>
                            <option value="9MM">9MM</option>
                            <option value="10MM">10MM</option>
                            <option value=".40">.40</option>
                            <option value=".44">.44</option>
                            <option value=".45">.45</option>
                            <option value=".454">.454</option>
                            <option value="12GA">12GA</option>
                            <option value="16GA">16GA</option>
                            <option value="20GA">20GA</option>
                            <option value="36GA">36GA</option>
                            <option value=".308">.308</option>
                            <option value="5.56">5.56</option>
                            <option value="7.62">7.62</option>
                        </select>
                        <span class='msg-erro msg-calibre'></span>
                        <br>
                        <button type="button" id="add-line-calibre">+</button>
                        <div id="dynamic-fields-calibre"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Nº SIGMA</td>
                      <td>
                        <input type="text" class="form-control" id="numsigma" name="numsigma[]">
                        <span class='msg-erro msg-numsigma'></span>
                        <br>
                        <button type="button" id="add-line-numsigma">+</button>
                        <div id="dynamic-fields-numsigma"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>QTDE Munições</td>
                      <td>
                        <input type="text" class="form-control" id="qtdemunicoes" name="qtdemunicoes[]">
                        <span class='msg-erro msg-qtdemunicoes'></span>
                        <br>
                        <button type="button" id="add-line-qtdemunicoes">+</button>
                        <div id="dynamic-fields-qtdemunicoes"></div>
                      </td>
                     <tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>
	
    <input type="hidden" class="form-control" id="tipo_atirador" name="tipo_atirador" value="2">
	<input type="hidden" name="datacadastro" value="<?php echo date('Y-m-d H:i'); ?>">	
	<input type="hidden" class="form-control" id="pontos" name="pontos[]" value="-----">
	<input type="hidden" class="form-control" id="classificacao" name="classificacao[]" value="-----">
	<input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">

    <input type="hidden" name="id" value="<?=$cliente->id?>">
    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
    </form>
    </fieldset>					  
			  
            				   
    <!--main content end-->
	<!--------------------------------------------------------- page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
        </div>
    </div>
  </section> 
  
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
	
<script>
// BOTAO DINÂMICO ADICIONAR
 document.addEventListener('DOMContentLoaded', function () {
            let countModalidade = 1;
            let countTipo = 1;
            let countCalibre = 1;
            let countNumSigma = 1;
            let countQtdemunicoes = 1;
            let countPontos = 1;
            let countClassificacao = 1;            

            const buttonModalidade = document.getElementById('add-line-modalidade');
            const buttonTipo = document.getElementById('add-line-tipo');            
            const buttonCalibre = document.getElementById('add-line-calibre');
            const buttonNumSigma = document.getElementById('add-line-numsigma');
            const buttonQtdemunicoes = document.getElementById('add-line-qtdemunicoes');
            const buttonPontos = document.getElementById('add-line-pontos');
            const buttonClassificacao = document.getElementById('add-line-classificacao');            

            const containerModalidade = document.getElementById('dynamic-fields-modalidade');
            const containerTipo = document.getElementById('dynamic-fields-tipo');            
            const containerCalibre = document.getElementById('dynamic-fields-calibre');
            const containerNumSigma = document.getElementById('dynamic-fields-numsigma');
            const containerQtdemunicoes = document.getElementById('dynamic-fields-qtdemunicoes');
            const containerPontos = document.getElementById('dynamic-fields-pontos');
            const containerClassificacao = document.getElementById('dynamic-fields-classificacao');            



             buttonModalidade.addEventListener('click', function () {
                countModalidade++;

                const newLineModalidade = document.createElement('div');
                newLineModalidade.classList.add('form-group');
                newLineModalidade.classList.add('list-item');

                newLineModalidade.innerHTML = `
                    <div>
                        <label for="modalidade{countModalidade}">Modalidade ${countModalidade}</label>
                        <input type="text" class="form-control" id="sigma${countModalidade}" name="modalidade[]">
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerModalidade.appendChild(newLineModalidade);
            });
            
            buttonTipo.addEventListener('click', function () {
                
                countTipo++;

                const newLineTipo = document.createElement('div');
                newLineTipo.classList.add('form-group');
                newLineTipo.classList.add('list-item');

                newLineTipo.innerHTML = `
                    <div>
                        <label for="tipo${countTipo}">Tipo de Armamento ${countTipo}</label>
                        <select class="form-control" id="tipo${countTipo}" name="tipo[]">
                            <option value="" disabled selected>- escolha uma arma -</option>
                            <option value="Pistola">Pistola</option>
                            <option value="Revolver">Revólver</option>
                            <option value="Rifle">Rifle</option>
                            <option value="Carabina">Carabina</option>
                            <option value="Espingarda">Espingarda</option>
                            <option value="Fuzil">Fuzil</option>
                        </select>
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerTipo.appendChild(newLineTipo);
            });

            buttonCalibre.addEventListener('click', function () {
                countCalibre++;

                const newLineCalibre = document.createElement('div');
                newLineCalibre.classList.add('form-group');
                newLineCalibre.classList.add('list-item');

                newLineCalibre.innerHTML = `
                    <div>
                        <label for="calibre${countCalibre}">Calibre ${countCalibre}</label>
                        <select class="form-control" id="calibre${countCalibre}" name="calibre[]">
                            <option value="" disabled selected>- escolha um calibre -</option>
                            <option value="6.35">6.35</option>
                            <option value="17HMR">17HMR</option>
                            <option value=".22">.22</option>
                            <option value=".28">.28</option>
                            <option value=".30">.30</option>
                            <option value=".30M1">.30M1</option>
                            <option value=".30.06">.30.06</option>
                            <option value=".32">.32</option>
                            <option value=".36">.36</option>
                            <option value=".38">.38</option>
                            <option value=".38SPL">.38SPL</option>
                            <option value=".38SUPER">.38SUPER</option>
                            <option value=".380ACP">.380ACP</option>
                            <option value=".357MAG">.357MAG</option>
                            <option value="7MM">7MM</option>
                            <option value="9MM">9MM</option>
                            <option value="10MM">10MM</option>
                            <option value=".40">.40</option>
                            <option value=".44">.44</option>
                            <option value=".45">.45</option>
                            <option value=".454">.454</option>
                            <option value="12GA">12GA</option>
                            <option value="16GA">16GA</option>
                            <option value="20GA">20GA</option>
                            <option value="36GA">36GA</option>
                            <option value=".308">.308</option>
                            <option value="5.56">5.56</option>
                            <option value="7.62">7.62</option>
                        </select>
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerCalibre.appendChild(newLineCalibre);
            });

 
             buttonNumSigma.addEventListener('click', function () {
                countNumSigma++;

                const newLineNumSigma = document.createElement('div');
                newLineNumSigma.classList.add('form-group');
                newLineNumSigma.classList.add('list-item');

                newLineNumSigma.innerHTML = `
                    <div>
                        <label for="numsigma${countNumSigma}">Nº SIGMA ${countNumSigma}</label>
                        <input type="text" class="form-control" id="numsigma{countNumSigma}" name="numsigma[]">
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerNumSigma.appendChild(newLineNumSigma);
            });

            buttonQtdemunicoes.addEventListener('click', function () {
                countQtdemunicoes++;

                const newLineQtdemunicoes = document.createElement('div');
                newLineQtdemunicoes.classList.add('form-group');
                newLineQtdemunicoes.classList.add('list-item');

                newLineQtdemunicoes.innerHTML = `
                    <div>
                        <label for="qtdemunicoes${countQtdemunicoes}">QTDE Munições ${countQtdemunicoes}</label>
                        <input type="text" class="form-control" id="qtdemunicoes${countQtdemunicoes}" name="qtdemunicoes[]">
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerQtdemunicoes.appendChild(newLineQtdemunicoes);
            });
            
            
                       
                buttonPontos.addEventListener('click', function () {
                countPontos++;

                const newLinePontos = document.createElement('div');
                newLinePontos.classList.add('form-group');
                newLinePontos.classList.add('list-item');

                newLinePontos.innerHTML = `
                    <div>
                        <label for="pontos{countPontos}">Pontuação ${countPontos}</label>
                        <input type="text" class="form-control" id="pontos{countPontos}" name="pontos[]">
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerPontos.appendChild(newLinePontos);
            });
            
 
                buttonClassificacao.addEventListener('click', function () {
                countClassificacao++;

                const newLineClassificacao = document.createElement('div');
                newLineClassificacao.classList.add('form-group');
                newLineClassificacao.classList.add('list-item');

                newLineClassificacao.innerHTML = `
                    <div>
                        <label for="classificacao{countClassificacao}">Classificacao ${countClassificacao}</label>
                        <input type="text" class="form-control" id="classificacao{countClassificacao}" name="classificacao[]">
                        <button type="button" class="remove-field">-</button>
                    </div>
                `;

                containerClassificacao.appendChild(newLineClassificacao);
            });
            

            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-field')) {
                    e.target.parentElement.parentElement.remove();
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