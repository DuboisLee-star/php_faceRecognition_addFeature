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


include "config/ajax_p_menus.php";
$acao = isset($_POST['acao']) ? $_POST['acao'] : false;
$cpf = isset($_POST['cpf']) ? str_replace(array('.','-'), '', $_POST['cpf']) : false;
if($acao == 'verifica_cpf' && $cpf){
    
    $query = "SELECT * FROM tab_membros WHERE cpf = '".$cpf."'";
    $result = mysqli_query($connect, $query);
    $check_cpf = mysqli_num_rows($result);
    if($check_cpf > 0){
        exit('1');
    }
    
    exit('2');
}
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
	var telefone = document.getElementById("telefone").value;
	var email = document.getElementById("email").value;

	if(nome == ""){alert("Preencher campo Nome Completo."); return false;}
	if(telefone == ""){alert("Preencher campo Telefone."); return false;}
	if(email == ""){alert("Preencher campo EMAIL."); return false;}


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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Cadastro</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matr&iacute;cula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				<form name="cadastro" action="action_cadastro_novo.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
					<label for="nome">Selecionar Foto</label>
			      	<div class="col-md-2">
					    <a href="#" class="thumbnail">
					      <img src="img/padrao.png" height="190" width="150" id="foto-cliente">
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
                    <td>Matricula</td>
                    <td><input readonly type="text" class="form-control" id="matricula" name="matricula" value="<?php echo geraMatricula(); ?>"></td>
                 </tr>
                  <tr>
                   <td>Data de Filia&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_filiacao" name="data_filiacao" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>

<script>
  // Obtém a data atual no formato YYYY-MM-DD
  var hoje = new Date().toISOString().substr(0, 10);

  // Define o valor do campo de data para a data atual
  document.getElementById("data_filiacao").value = hoje;
</script>
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
                   <td><input type="text" class="form-control" id="categoria" name="categoria" value="Atirador" oninput="this.value = this.value.toUpperCase()"></td>
                 </tr>
                  <tr>
                   <td>N&uacute;mero CR</td>
                    <td><input type="text" class="form-control" id="cr" name="cr" value=""></td>
                  </tr>
                    <tr>
                    <td>Data emissão CR</td>
                    <td><input type="date" class="form-control" id="cr_emissao" name="cr_emissao" value="" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                  </tr>          
                  <tr>
                    <td>Data validade CR</td>
                    <td><input type="date" class="form-control" id="validade_cr" name="validade_cr" value="" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                  </tr>
                  <tr>
                    <td>Nome Completo</td>
                    <td><input type="text" class="form-control" id="nome" name="nome" value="" oninput="this.value = this.value.toUpperCase()"></td>

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
                    <td><input type="text" class="form-control" id="cep" name="cep" maxlength="10" value="" OnKeyPress="formatar('##.###-###', this)"></td>
                  </tr>
                  <tr>					  
                    <td>Rua</td>
                    <td><input type="text" class="form-control" id="rua" name="rua" value="" oninput="this.value = this.value.toUpperCase()"></td>

                 </tr>
                  <tr>
                    <td>Num.</td>
                    <td><input type="text" class="form-control" id="numero" name="numero" value=""></td>
                 </tr>
                  <tr>
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="bairro" name="bairro" value="" oninput="this.value = this.value.toUpperCase()"></td>

                 </tr>
                  <tr>

                    <td>Cidade</td>
                   <td><input type="text" class="form-control" id="cidade" name="cidade" value="" oninput="this.value = this.value.toUpperCase()"></td>

                  </tr>
                  <tr>
                    <td>UF</td>
                    <td><input type="text" class="form-control" id="siglauf" name="siglauf" value="" oninput="this.value = this.value.toUpperCase()"></td>

                  </tr>
                  <tr>
                    <td>Estado Civil</td>
                   <td><input type="text" class="form-control" id="estadocivil" name="estadocivil" value="" oninput="this.value = this.value.toUpperCase()"></td>
                  </tr>
                  <tr>
                    <td>Naturalidade</td>
                   <td><input type="text" class="form-control" id="naturalidade" name="naturalidade" value="" oninput="this.value = this.value.toUpperCase()"></td>
                  </tr>
                  <tr>
                    <td>Nacionalidade</td>
                    <td><input type="text" class="form-control" id="nacionalidade" name="nacionalidade" value="" oninput="this.value = this.value.toUpperCase()"></td>
                  </tr>
                  <tr>
                    <td>Data Nascimento</td>
                    <td><input type="text" class="form-control" id="data_nascimento" name="data_nascimento" value="" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                  </tr>
                  <tr>
                    <td>Profiss&atilde;o</td>
                    <td><input type="text" class="form-control" id="profissao" name="profissao" value="" oninput="this.value = this.value.toUpperCase()"></td>
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
                   <td><input type="text" class="form-control" id="pai" name="pai" value="" oninput="this.value = this.value.toUpperCase()"></td>
                 </tr>
                  <tr>
                   <td>M&atilde;e</td>
                    <td><input type="text" class="form-control" id="mae" name="mae" value="" oninput="this.value = this.value.toUpperCase()"></td>
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
                    <td><input type="email" class="form-control" id="email" name="email" value=""></td>
                 </tr>
                  <tr>
                    <td>Whatsapp</td>
                    <td><input type="tel" class="form-control" id="telefone" name="telefone" maxlength="14"  OnKeyPress="formatar('##-#-####-####', this)"></td>
                 </tr>
                  <tr>
                   <td>Instagram</td>
                    <td><input type="text" class="form-control" id="instagram" name="instagram" value=""></td>
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
                    <td><input type="text" class="form-control" name="cpf" id="cpf" maxlength="14" OnKeyPress="formatar('###.###.###-##', this)" onblur="verificaCPF()"></td>
                 </tr>
                  <tr>
                    <td>Identidade</td>
                    <td><input type="text" class="form-control" id="identidade" maxlength="50" name="identidade" value=""></td>
                 </tr>
                  <tr>
                   <td>&Oacute;rg&atilde;o-UF</td>
                    <td><input type="text" class="form-control" id="orgaouf" maxlength="20" name="orgaouf" value=""></td>
                 </tr>
                  <tr>
                   <td>Data Expedi&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_exped" maxlength="14" name="data_exped" value="" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                 </tr>
                  <tr>
                   <td>Tipo Sanguineo</td>
                    <td><input type="text" class="form-control" id="tipo_sanguineo" maxlength="14" name="tipo_sanguineo" value="" ></td>
                 </tr>
                  <tr>
                   <td>CNH</td>
                    <td><input type="text" class="form-control" id="cnh" maxlength="30" name="cnh" value=""></td>
                 </tr>
                  <tr>
                   <td>Data Expedi&ccedil;&atilde;o CNH</td>
                    <td><input type="date" class="form-control" id="data_exped_cnh" maxlength="14" name="data_exped_cnh" value="" maxlength="10" OnKeyPress="formatar('##/##/####', this)"></td>
                 </tr>
                  <tr>
                   <td>&Oacute;rg&atilde;o CNH</td>
                    <td><input type="text" class="form-control" id="orgao_cnh" maxlength="20" name="orgao_cnh" value=""></td>
                 </tr>
                  <tr>
                    <td>T&iacute;tulo Eleitoral</td>
					<td><input type="text" class="form-control" id="tituloeleitoral" maxlength="14" name="tituloeleitoral" value=""></td>
                 </tr>
                  <tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>	
			  
			  
<?php include 'config/termos.php'; ?>

<?php include 'config/url_hidden_forms.php'; ?>

<input type="hidden" class="form-control" id="bloqueio" name="bloqueio" value="Nao">

<input type="hidden" class="form-control" id="plano_pgto" name="plano_pgto" value="A">	

<input type="hidden" class="form-control" id="motivo" name="motivo" value="Novo Membro Cadastrado via Link">
			  
			  
			  
			  
					<input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="button" class="btn btn-info" id='botao' onclick="return salva()">Salvar</button>
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
 
<script>
const selectorAll = document.querySelectorAll.bind(document);
const id = document.getElementById.bind(document);

function mascara(o, f) {
  obj = o;
  fun = f;
  setTimeout("execmascara()", 1);
}

function execmascara() {
  obj.value = fun(obj.value).toUpperCase();
}

function validaCep(v) {
  v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
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
  // Limpa valores do formulário de cep.
  id("rua").value = "";
  id("bairro").value = "";						
  id("cidade").value = "";
  id("siglauf").value = "";
}

function meu_callback(conteudo) {
  if (!("erro" in conteudo)) {
    // Atualiza os campos com os valores.
    id("rua").value = conteudo.logradouro.toUpperCase();
    id("bairro").value = conteudo.bairro.toUpperCase();						
    id("cidade").value = conteudo.localidade.toUpperCase();
    id("siglauf").value = conteudo.uf.toUpperCase();
  } else {
    // CEP não Encontrado.
    limpa_formulário_cep();
    respostaCep.style.display = "block";
    respostaCep.innerHTML = "CEP não encontrado.";
  }
}

function pesquisacep(valor) {
  // Preenche os campos com "..." enquanto consulta webservice.
  /**
  document.getElementById('rua').value="...";
  document.getElementById('bairro').value="...";
  document.getElementById('cidade').value="...";
  document.getElementById('uf').value="...";
  document.getElementById('ibge').value="...";
  */
  // Nova variável "cep" somente com dígitos.
  let cep = valor.replace(/\D/g, "");
  // Verifica se campo cep possui valor informado.
  if (cep != "") {
    // Expressão regular para validar o CEP.
    let validacep = /^[0-9]{8}$/;
    // Valida o formato do CEP.
    if (validacep.test(cep)) {
      // Cria um elemento javascript.
      let script = document.createElement("script");
      // Sincroniza com o callback.
      script.src = "https://viacep.com.br/ws/" + cep + "/json/?callback=meu_callback";
      // Insere script no documento e carrega o conteúdo.
      document.body.appendChild(script);
      respostaCep.style.display = "none";
      respostaCep.innerHTML = "";
    } else {
      // cep é inválido.
      limpa_formulário_cep();
      respostaCep.style.display = "block";
      respostaCep.innerHTML = "Formato de CEP inválido.";
    }
  } else {
    // cep sem valor, limpa formulário.
    limpa_formulário_cep();
  }
}

cadastro.cep.onblur = function () {
  var cep = id("cep");
  pesquisacep(cep.value);
};

const verificaCPF = () => {
  var cpf = $("#cpf").val();
  if (cpf == '') return false;
  
  $(".bg_loading").fadeIn(100);
  
  $.ajax({
    url: "",
    type: 'POST',
    data: {
      'acao': 'verifica_cpf',
      'cpf': $("#cpf").val()
    },
    success: function(result) {
      if (result == '1') {
        alert('CPF já cadastrado');
        $("#cpf").val('');
      }
      $(".bg_loading").fadeOut(100);
    },
  });
}
</script>

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

<style>
.bg_loading {
    background: rgba(0,0,0,0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    display:none;
}
.texto_cpf {
    background: #ffffff;
    position: fixed;
    width: 250px;
    padding: 10px;
    left: 50%;
    top: 50%;
    margin-left: -175px;
}
</style>
<div class="bg_loading" style="text-align: center"><p class="texto_cpf">Verificando CPF...</p></div>
</body>

</html>