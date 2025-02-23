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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />  

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
	
    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-barcode" aria-hidden="true"></i>GERAR GRUs</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>GRU</li>
            </ol>
          </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------- page start-->

        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Preenchimento de GRU
              </header>
              <div class="panel-body">
                <div class="form">
					
	
  <script>document.getElementsByTagName("html")[0].className += " js";</script>

              <form name="form_gru" id="form_gru" action="gerar_gru_siafi.php" target=”_blank”  method="POST">
			  <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Dados</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>CPF</td>
                    <td><input type="text" class="form-control" id="cpf_usuario" name="cpf_usuario" size="20"></td>
                 </tr>
                  <tr>
                   <td>Nome</td>
                    <td><input type="text" class="form-control" id="nome_usuario" name="nome_usuario" readonly size="30" ></td>
                  </tr>
                  <tr>
                    <td>Regi&atilde;o Militar</td>
                    <td><select name="regiao_militar" class="form-control" id="regiao_militar" disabled style="width: 541; height: 20">
											<option value="">Selecione</option>
											<option value="201">1ª RM - ES</option>
											<option value="202">2ª RM - SP</option>
											<option value="203">3ª RM - RJ</option>
											<option value="204">4ª RM - MG</option>
											<option value="205">5ª RM - PR/SC</option>
											<option value="206">6ª RM - BA/SE</option>
											<option value="207" selected="selected">7ª RM - RN/PB/PE/AL</option>
											<option value="208">8ª RM - PA/AP/TO</option>
											<option value="209">9ª RM - MT/MS</option>
											<option value="210">10ª RM - CE/PI/MA</option>
											<option value="211">11ª RM - DF/GO/TO</option>
											<option value="212">12ª RM - AM/AC/RO/RR</option>
										</select></td>
                 </tr>
                  <tr>
				  <td>Tipo de Servi&ccedil;o</td>
				  <td><select name="taxa_servico" id="taxa_servico" class="form-control" disabled style="width: 541; height: 23">
											<option value="">Selecione</option>
											<option data-valor="100,00" value="23">Concessão CR</option>
											<option data-valor="50,00"  value="24">Revalidação CR</option>
											<option data-valor="50,00"  value="27">Cancelamento CR</option>
											<option data-valor="25,00"  value="28">2a via CR</option>
											<option data-valor="50,00"  value="24">Apostilamento de Arma</option>
											<option data-valor="25,00"  value="41">Autorização de Compra</option>
											<option data-valor="30,00"  value="51">Anuência Exportação</option>
											<option data-valor="50,00"  value="53">Desembaraço Alfandegário</option>
											<option data-valor="35,00"  value="64">Concessão CII</option>
											<option data-valor="20,00"   value="67">GT Guia de Tráfego</option>
											<option data-valor="50,00"  value="68">Comprovante CR Colecionador, Atirador ou Caçador</option>
											<option data-valor="10,00"  value="69">Comprovante Registro Arma Fogo</option>
											<option data-valor="88,00"  value="71">Emissão de CRAF</option>
											<option data-valor="88,00"  value="72">Renovação de CRAF</option>
                                                                                        <option data-valor="88,00"  value="75">2a via do CRAF</option>
											<option data-valor="1466,68"  value="73">Expedição Porte de Arma de Fogo</option>											
                                                                                        <option data-valor="1466,68"  value="74">Renovação Porte Arma de Fogo</option>
                                                                                        <option data-valor="88,00"  value="76">Expedição de 2a via de Porte de Arma de Fogo</option>
											
										</select>
				  </td>
				  </tr>
                  <tr>
                   <td>C&oacute;digo do Servi&ccedil;o</td>
                    <td><input type="text" class="form-control" id="referencia" name="referencia" readonly size="30"></td>
                  </tr>
                  <tr>
                   <td>M&ecirc;s e Ano</td>
                    <td><input type="text" class="form-control" id="competencia" placeholder="Ex: 02/2019" name="competencia" size="30"></td>
                  </tr>
                  <tr>										
                   <td>Data Vencimento</td>
                    <td><input type="text" class="form-control" id="vencimento" placeholder="Ex: 02/10/2019" name="vencimento" size="30"></td>
                  </tr>
                  <tr>										
                   <td>Valores</td>
                    <td><input type="text" class="form-control" id="valor_principal" name="valor_principal" readonly size="30" ></td>
                  </tr>
                  <tr>										
                   <td>Total</td>
                    <td><input type="text" class="form-control" id="valor_total" name="valor_total" readonly size="30" ></td>
                  </tr>
                  <tr>										
                   <td></td>
                    <td><button type="button" class="form-control" id="btn-gerarGru" disabled>Gerar GRU</button></td>
                  </tr>
                </tbody>
              </table>		</form>
            </section>
          </div>
        </div>



  
  <div class="cd-faq__overlay" aria-hidden="true"></div>
</section> <!-- cd-faq -->
<script src="js/jquery.js"></script>
<script src="assets/js/util.js"></script> <!-- util functions included in the CodyHouse framework -->
<!--<script src="assets/js/main.js"></script> -->
	


<script>
    $(document).ready(function(){
			
			$("#cpf_usuario").mask("999.999.999-99");
			$("#competencia").mask("99/9999");
    	$("#vencimento").mask("99/99/9999");
			
			$("#cpf_usuario").keyup(function(){
			
				if($(this).val()!=''){
					var data = $('#form_gru').serialize();
					if(($("#cpf_usuario").val().length)==14){

						if(valida_cpf($("#cpf_usuario").val())){

							var i = 1;
							$.ajax({
								url: 'verificarUsuario.php',
								data: {data},
								dataType: 'JSON',
								type: 'POST',
								async: false,
								success: function (resposta) {
									if(resposta.nome!=''){	
										$("#nome_usuario").val(resposta.nome)
										habilitar();
										$("#informacoes_gru").fadeIn();
									}
									else{
										if(i == 1){
											alert("CPF não encontrado.");
											$("#cpf_usuario").val("");
											$("#cpf_usuario").focus();
											desabilitar();
											$("#informacoes_gru").fadeOut();
										}
										i++;	
									}
										
								}
							});
						}
						else{
								alert("CPF inválido");
								$("#cpf_usuario").val("");
								$("#cpf_usuario").focus();
								desabilitar();
								$("#informacoes_gru").fadeOut();
						}
					}
				}

			});

			$("#regiao_militar").change(function(){
					$("#referencia").val("");
					if($(this).val()!=''){
						if($("#taxa_servico").val()!=''){
								$("#referencia").val($("#regiao_militar").val()+$("#taxa_servico").val());
						}
					}
			});

			$("#taxa_servico").change(function(){
					
					$("#referencia").val("");
					if($(this).val()!=''){
						if($("#regiao_militar").val()!=''){
								$("#referencia").val($("#regiao_militar").val()+$("#taxa_servico").val());
						}						
					}

					if($('#taxa_servico option:selected').attr("data-valor")!=undefined){
						 $("#valor_principal").val($('#taxa_servico option:selected').attr("data-valor"));
						 $("#valor_total").val($('#taxa_servico option:selected').attr("data-valor"));
					}
					else{
						$("#valor_principal").val('');
						$("#valor_total").val($('#taxa_servico option:selected').attr("data-valor"));
					}

			});

			$("#btn-gerarGru").click(function(){
					if(validarCampos()){
						$("#form_gru").submit();
					}
			});

    });  

		function validarCampos(){
			var camposPreenchidos = true;

			if($("#competencia").val()==''){
				camposPreenchidos = false;
				alert("Preencha o campo Competência");
				$("#competencia").focus();
			}
			else if($("#vencimento").val()==''){
				alert("Preencha o campo Vencimento");
				camposPreenchidos = false;
				$("#vencimento").focus();
			}

			return camposPreenchidos;

		}

		function desabilitar(){

			$("#regiao_militar").val("");
			$("#regiao_militar").attr("disabled", true);

			$("#taxa_servico").val("");
			$("#taxa_servico").attr("disabled", true);
			
			$("#competencia").val("");
			$("#competencia").attr("disabled", true);

			$("#vencimento").val("");
			$("#vencimento").attr("disabled", true);

			$("#btn-gerarGru").attr("disabled", true);

		}

		function habilitar(){
			$("#regiao_militar").attr("disabled", false);

			
			$("#taxa_servico").attr("disabled", false);
			
			
			$("#competencia").attr("disabled", false);

			
			$("#vencimento").attr("disabled", false);

			$("#btn-gerarGru").attr("disabled", false);
		}

		/*
         calc_digitos_posicoes
         
         Multiplica dígitos vezes posições
         
         @param string digitos Os digitos desejados
         @param string posicoes A posição que vai iniciar a regressão
         @param string soma_digitos A soma das multiplicações entre posições e dígitos
         @return string Os dígitos enviados concatenados com o último dígito
         */
    function calc_digitos_posicoes(digitos, posicoes = 10, soma_digitos = 0) {

				// Garante que o valor � uma string
				digitos = digitos.toString();

				// Faz a soma dos dígitos com a posição
				// Ex. para 10 posições:
				//   0    2    5    4    6    2    8    8   4
				// x10   x9   x8   x7   x6   x5   x4   x3  x2
				//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
				for (var i = 0; i < digitos.length; i++) {
						// Preenche a soma com o dígito vezes a posição
						soma_digitos = soma_digitos + (digitos[i] * posicoes);

						// Subtrai 1 da posição
						posicoes--;

						// Parte específica para CNPJ
						// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
						if (posicoes < 2) {
								// Retorno a posição para 9
								posicoes = 9;
						}
				}

				// Captura o resto da divisão entre soma_digitos dividido por 11
				// Ex.: 196 % 11 = 9
				soma_digitos = soma_digitos % 11;

				// Verifica se soma_digitos é menor que 2
				if (soma_digitos < 2) {
						// soma_digitos agora será zero
						soma_digitos = 0;
				} else {
						// Se for maior que 2, o resultado é 11 menos soma_digitos
						// Ex.: 11 - 9 = 2
						// Nosso dígito procurado é 2
						soma_digitos = 11 - soma_digitos;
				}

				// Concatena mais um dígito aos primeiro nove dígitos
				// Ex.: 025462884 + 2 = 0254628842
				var cpf = digitos + soma_digitos;

				// Retorna
				return cpf;

		} // calc_digitos_posicoes

		/*
		Valida CPF

		@param  string cpf O CPF com ou sem pontos e traço
		@return bool True para CPF correto - False para CPF incorreto
		*/
		function valida_cpf(valor) {

			// Garante que o valor é uma string
			valor = valor.toString();

			// Remove caracteres inválidos do valor
			valor = valor.replace(/[^0-9]/g, '');

			// Captura os 9 primeiros dígitos do CPF
			// Ex.: 02546288423 = 025462884
			var digitos = valor.substr(0, 9);

			// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
			var novo_cpf = calc_digitos_posicoes(digitos);

			// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
			var novo_cpf = calc_digitos_posicoes(novo_cpf, 11);

			// Verifica se o novo CPF gerado é idêntico ao CPF enviado
			if (novo_cpf === valor) {
					// CPF válido
					return true;
			} else {
					// CPF inválido
					return false;
			}

		} // valida_cpf

		

</script>    	
			        </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table>
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
          by <a href="https://bootstrapmade.com/">HOSTMARQ</a><br><br>
        </div>
    </div>
  </section>
  <!-- container section end -->
  <!-- javascripts -->
  
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  
  <!--<script type="text/javascript" src="assets/jQuery/jQuery-2.1.4.min.js"></script>-->
	<script type="text/javascript" src="assets/jQuery/mask.js"></script>


</body>

</html>
