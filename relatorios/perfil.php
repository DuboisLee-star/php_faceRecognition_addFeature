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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
				<form action="action_cadastro.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
						<div class="col-md-2">
						    <a href="#" class="thumbnail">
						      <img src="fotos/<?=$cliente->foto?>" width="150" id="foto-cliente">
						    </a>
					  	</div>
					  	<input type="file" name="foto" id="foto" value="foto" >
				  	</div>
			<br>
				
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Dados do Clube de Tiro
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
                    <td>Matr&iacute;cula</td>
                    <td><input type="text" class="form-control" id="matricula" name="matricula" maxlength="3"  value="<?=$cliente->matricula?>"></td>
                 </tr>
                  <tr>
                   <td>Data de Filia&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_filiacao" name="data_filiacao" value="<?=$cliente->data_filiacao?>"></td>
                  </tr>
                  <tr>
                    <td>Data de Renova&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_renovacao" name="data_renovacao" value="<?=$cliente->data_renovacao?>"></td>
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
                    <td>Categoria</td>
                    <td><input type="text" class="form-control" id="categoria" name="categoria" value="<?=$cliente->categoria?>"></td>
                 </tr>
                  <tr>
                   <td>N&uacute;mero CR</td>
                    <td><input type="text" class="form-control" id="cr" name="cr" value="<?=$cliente->cr?>"></td>
                  </tr>
                  <tr>
                    <td>Data validade CR</td>
                    <td><input type="date" class="form-control" id="validade_cr" name="validade_cr" value="<?=$cliente->validade_cr?>"></td>
                  </tr>
                  <tr>
                    <td>Nome Completo</td>
                    <td><input type="text" class="form-control" id="nome" name="nome" value="<?=$cliente->nome?>"></td>
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
                Endere&ccedil;o e outros dados
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
                   <td>Cep</td>
                    <td><input type="text" class="form-control" id="cep" name="cep" maxlength="10" value="<?=$cliente->cep?>" maxlength="14" OnKeyPress="formatar('##.###-###', this)"></td>
                  </tr>
                  <tr>					  
                    <td>Rua</td>
                    <td><input type="text" class="form-control" id="rua" name="rua" value="<?=$cliente->rua?>"></td>
                 </tr>
                  <tr>
                    <td>Num.</td>
                    <td><input type="text" class="form-control" id="numero" name="numero" value="<?=$cliente->numero?>"></td>
                 </tr>
                  <tr> 
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="bairro" name="bairro" value="<?=$cliente->bairro?>"></td>
                 </tr>
                  <tr>
                    <td>Cidade</td>
                    <td><input type="text" class="form-control" id="cidade" name="cidade" value="<?=$cliente->cidade?>"></td>
                  </tr>
                  <tr>
                    <td>UF</td>
                    <td><input type="text" class="form-control" id="siglauf" name="siglauf" value="<?=$cliente->siglauf?>"></td>
                  </tr>
                  <tr>
                    <td>Estado Civil</td>
                    <td><input type="text" class="form-control" id="estadocivil" name="estadocivil" value="<?=$cliente->estadocivil?>"></td>
                  </tr>
                  <tr>
                    <td>Naturalidade</td>
                    <td><input type="text" class="form-control" id="naturalidade" name="naturalidade" value="<?=$cliente->naturalidade?>"></td>
                  </tr>
                  <tr>
                    <td>Nacionalidade</td>
                    <td><input type="text" class="form-control" id="nacionalidade" name="nacionalidade" value="<?=$cliente->nacionalidade?>"></td>
                  </tr>
                  <tr>
                    <td>Data Nascimento</td>
                    <td><input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?=$cliente->data_nascimento?>"></td>
                  </tr>
                  <tr>
                    <td>Profiss&atilde;o</td>
                    <td><input type="text" class="form-control" id="profissao" name="profissao" value="<?=$cliente->profissao?>"></td>
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
                Filia&ccedil;&atilde;o do Atirador
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
                    <td>Pai</td>
                    <td><input type="text" class="form-control" id="pai" name="pai" value="<?=$cliente->pai?>"></td>
                 </tr>
                  <tr>
                   <td>M&atilde;e</td>
                    <td><input type="text" class="form-control" id="mae" name="mae" value="<?=$cliente->mae?>"></td>
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
                  <tr>
                   <td>Instagram</td>
                    <td><input type="text" class="form-control" id="instagram" name="instagram" value="<?=$cliente->instagram?>"></td>
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
                Documentos Pessoais
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
                    <td>C.P.F</td>
                    <td><input type="text" class="form-control" maxlength="14"  id="cpf" name="cpf" value="<?=$cliente->cpf?>" OnKeyPress="formatar('###.###.###-##', this)"></td>
                 </tr>
                  <tr>
                    <td>Identidade</td>
                    <td><input type="text" class="form-control" id="identidade" maxlength="14" name="identidade" value="<?=$cliente->identidade?>"></td>
                 </tr>
                  <tr>
                   <td>&Oacute;rg&atilde;o-UF</td>
                    <td><input type="text" class="form-control" id="orgaouf" maxlength="6" name="orgaouf" value="<?=$cliente->orgaouf?>"></td>
                 </tr>
                  <tr>
                   <td>Data Expedi&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_exped" maxlength="14" name="data_exped" value="<?=$cliente->data_exped?>" ></td>
                 </tr>
                  <tr>
                   <td>T&iacute;tulo Eleitoral</td>
					<td><input type="text" class="form-control" id="tituloeleitoral" maxlength="14" name="tituloeleitoral" value="<?=$cliente->tituloeleitoral?>"></td>
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
                Detalhar alguma ocorr&ecirc;ncia com o atirador.
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
                    <td>Ocorr&ecirc;ncias</td>
                    <td>
					    <textarea class="form-control" rows="6" name="ocorrencias" id="ocorrencias">
                        </textarea>
					</td>
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
                2&#176; ENDERE&Ccedil;O
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
                   <td>CEP</td>
                    <td><input type="text" class="form-control" id="segundo_cep" name="segundo_cep" value="<?=$cliente->segundo_cep?>"></td>
                  </tr>
                  <tr>					  
                    <td>Nome da rua</td>
                    <td><input type="text" class="form-control" id="segundo_rua" name="segundo_rua" value="<?=$cliente->segundo_rua?>"></td>
                 </tr>
                  <tr>
                   <td>N&uacute;mero</td>
                    <td><input type="text" class="form-control" id="segundo_num" name="segundo_num" value="<?=$cliente->segundo_num?>"></td>
                  </tr>
                  <tr>
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="segundo_bairro" name="segundo_bairro" value="<?=$cliente->segundo_bairro?>"></td>
                  </tr>
                  <tr>

                   <td>Cidade</td>
                    <td><input type="text" class="form-control" id="segundo_cidade" name="segundo_cidade" value="<?=$cliente->segundo_cidade?>"></td>
                  </tr>
                  <tr>
				  <td>Estado</td>
                    <td><input type="text" class="form-control" id="segundo_estado" name="segundo_estado" value="<?=$cliente->segundo_estado?>"></td>
                  </tr>
                  <tr>
                    <td>Observa&ccedil;&otilde;es</td>
                    <td><input type="text" class="form-control" id="segundo_obs" name="segundo_obs" value="<?=$cliente->segundo_obs?>")">
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>
						
					<input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="submit" class="btn btn-info" id='botao'>Salvar</button>
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
