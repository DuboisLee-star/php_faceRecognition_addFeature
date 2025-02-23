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
	
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros ORDER BY nome ASC';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$atiradores = $stm->fetchAll(PDO::FETCH_OBJ);
	
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

    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i><?=$cliente->nome?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matrícula > <?=$cliente->matricula?></li>
            </ol>
          </div>
        </div>
        
        <div class="row" style="margin-bottom: 10px;">
          <div class="col-lg-12">
              <b>Atirador:</b>
              <select class="form-control select2" onchange="window.location='perfil.php?id='+this.value">
                  <?php if($atiradores): ?>
                    <?php foreach($atiradores as $key => $Atirador): ?>
                        <option value="<?= $Atirador->id; ?>" <?= ($id_cliente == $Atirador->id) ? " selected " : ""; ?>><?= $Atirador->nome.' - '.$Atirador->matricula; ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </select>
              
          </div>
        </div>

        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

			<?php if(empty($cliente)):?>
				<h3 class="text-center text-danger">Cliente não encontrado!</h3>
			<?php else: ?>
			
				<form name="cadastro" action="action_cadastro.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">
						<div class="col-md-2">
						    <a href="#" class="thumbnail">
						      <img src="fotos/<?=$cliente->foto?>" width="150" id="foto-cliente">
						    </a>
						    <div id="screenshots"></div>
    						<canvas class="is-hidden" id="canvas"></canvas>
    						<video autoplay id="video"></video>
					  	</div>
					  	<div class="col-md-12">
					  	    <input type="file" name="foto" id="foto" value="foto" >
					  	</div>
					  	<div class="col-md-12" style="margin-top: 10px;">
        			  	       <button onclick="return habilitaWebcam()" class="btn btn-primary" type="button" id="btnwebcam"><i class="fa fa-camera"></i> Habilitar Webcam</button>
        			  	       <button onclick="return capturaWebcam()" class="btn btn-success" type="button" id="btncaptura" style="display: none;"><i class="fa fa-photo"></i> Capturar</button>
        			  	       <input type="hidden" name="image_webcam" id="image_webcam">
        			  	</div>
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
                    <td><input type="text" class="form-control" id="matricula" name="matricula" maxlength="4"  value="<?=$cliente->matricula?>"></td>
                 </tr>
                  <tr>
                   <td>Bloqueio</td>
                    <td><input type="radio" name="bloqueio" id="bloqueio" value="Sim" <?php if($cliente->bloqueio == "Sim") echo ' checked '; ?>>&nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bloqueio" id="bloqueio" value="Nao" <?php if($cliente->bloqueio == "Nao") echo ' checked '; ?>>&nbsp;N&atilde;o&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bloqueio" id="bloqueio" value="D" <?php if($cliente->bloqueio == "D") echo ' checked '; ?>>&nbsp;Desfiliado</td>
                  </tr>
                   <tr>
                   <td>Envio Automático de Mensagem</td>
                    <td><input type="radio" name="envios_auto" id="envios_auto" value="0" <?php if($cliente->envios_auto == 0) echo ' checked '; ?>>&nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="envios_auto" id="envios_auto" value="1" <?php if($cliente->envios_auto == 1) echo ' checked '; ?>>&nbsp;N&atilde;o&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  <tr>
                   <td>Tipo de Plano</td>
                    <td><input type="radio" name="plano" id="plano" value="Ouro" <?php if($cliente->plano == "Ouro") echo ' checked '; ?>>&nbsp;Ouro | <input type="radio" name="plano" id="plano" value="Prata" <?php if($cliente->plano == "Prata") echo ' checked '; ?>>&nbsp;Prata | <input type="radio" name="plano" id="plano" value="Bronze" <?php if($cliente->plano == "Bronze") echo ' checked '; ?>>&nbsp;Bronze | <input type="radio" name="plano" id="plano" value="Cobre" <?php if($cliente->plano == "Cobre") echo ' checked '; ?>>&nbsp;Cobre</td>
                  </tr>
                  <tr>

                    <td>Plano de pagamento</td>
                    <td><input type="radio" name="plano_pgto" id="plano_pgto" value="M" <?php if($cliente->plano_pgto == "M") echo ' checked '; ?>>&nbsp;Mensalidade&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plano_pgto" id="plano_pgto" value="A" <?php if($cliente->plano_pgto == "A") echo ' checked '; ?>>&nbsp;Anuidade</td>
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

<script>
  // Adicione um evento de mudança ao campo de bloqueio
  const bloqueioRadios = document.getElementsByName("bloqueio");
  const dataRenovacaoInput = document.getElementById("data_renovacao");
  let dataRenovacaoOriginal = dataRenovacaoInput.value;

  for (const radio of bloqueioRadios) {
    radio.addEventListener("change", function () {
      if (radio.value === "D" && radio.checked) {
        // Se a opção for "Desfiliado", defina a data de renovação como NULL
        dataRenovacaoInput.value = null;
      } else {
        // Se qualquer outra opção for selecionada, restaure o valor original da data de renovação
        dataRenovacaoInput.value = dataRenovacaoOriginal;
      }
    });
  }
</script>

	
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
                    <td><input type="text" class="form-control" id="categoria" name="categoria" value="<?=$cliente->categoria?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                   <td>Nivel</td>
                    <td><input type="text" class="form-control" id="nivel" name="nivel" value="<?=$cliente->nivel?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>                      
                   <td>N&uacute;mero CR</td>
                    <td><input type="text" class="form-control" id="cr" name="cr" value="<?=$cliente->cr?>"></td>
                  </tr>
                  <tr>
                    <td>Data emiss&atilde;o CR</td>
                    <td><input type="date" class="form-control" id="cr_emissao" name="cr_emissao" value="<?=$cliente->cr_emissao?>"></td>
                  </tr>                  
                  <tr>
                    <td>Data validade CR</td>
                    <td><input type="date" class="form-control" id="validade_cr" name="validade_cr" value="<?=$cliente->validade_cr?>"></td>
                  </tr>
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
                    <td><input type="text" class="form-control" id="rua" name="rua" value="<?=$cliente->rua?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                    <td>Num.</td>
                    <td><input type="text" class="form-control" id="numero" name="numero" value="<?=$cliente->numero?>"></td>
                 </tr>
                  <tr> 
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="bairro" name="bairro" value="<?=$cliente->bairro?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                    <td>Complemento</td>
                    <td><input type="text" class="form-control" id="complemento" name="complemento" value="<?=$cliente->complemento?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>                      
                    <td>Cidade</td>
                    <td><input type="text" class="form-control" id="cidade" name="cidade" value="<?=$cliente->cidade?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>UF</td>
                    <td><input type="text" class="form-control" id="siglauf" name="siglauf" value="<?=$cliente->siglauf?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>Estado Civil</td>
                    <td><input type="text" class="form-control" id="estadocivil" name="estadocivil" value="<?=$cliente->estadocivil?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>Naturalidade</td>
                    <td><input type="text" class="form-control" id="naturalidade" name="naturalidade" value="<?=$cliente->naturalidade?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>Nacionalidade</td>
                    <td><input type="text" class="form-control" id="nacionalidade" name="nacionalidade" value="<?=$cliente->nacionalidade?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>Data Nascimento</td>
                    <td><input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?=$cliente->data_nascimento?>"></td>
                  </tr>
                  <tr>
                    <td>Profiss&atilde;o</td>
                    <td><input type="text" class="form-control" id="profissao" name="profissao" value="<?=$cliente->profissao?>" style="text-transform: uppercase;"></td>
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
                    <td><input type="text" class="form-control" id="pai" name="pai" value="<?=$cliente->pai?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                   <td>M&atilde;e</td>
                    <td><input type="text" class="form-control" id="mae" name="mae" value="<?=$cliente->mae?>" style="text-transform: uppercase;"></td>
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
                    <td><input type="text" class="form-control" id="orgaouf" name="orgaouf" value="<?=$cliente->orgaouf?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                   <td>Data Expedi&ccedil;&atilde;o</td>
                    <td><input type="date" class="form-control" id="data_exped" maxlength="15" name="data_exped" value="<?=$cliente->data_exped?>" ></td>
                 </tr>
                  <tr>
                   <td>CNH</td>
                    <td><input type="text" class="form-control" id="cnh" maxlength="30" name="cnh" value="<?=$cliente->cnh?>" ></td>
                 </tr>
                  <tr>        
                    <td>Data Expedi&ccedil;&atilde;o CNH</td>
                    <td><input type="date" class="form-control" id="data_exped_cnh" maxlength="15" name="data_exped_cnh" value="<?=$cliente->data_exped_cnh?>" ></td>
                 </tr>
                    <td>&Oacute;rg&atilde;o CNH</td>
                    <td><input type="text" class="form-control" id="orgao_cnh" name="orgao_cnh" value="<?=$cliente->orgao_cnh?>" ></td>
                 </tr>
                  <tr>   
                    <td>Tipo Sanguineo</td>
                    <td><input type="text" class="form-control" id="tipo_sanguineo" name="tipo_sanguineo" value="<?=$cliente->tipo_sanguineo?>"></td>
                 </tr>
                  <tr>                   
                    <td>Senha SISGCORP</td>
                    <td><input type="text" class="form-control" id="senha_sisgcorp" maxlength="50" name="senha_sisgcorp" value="<?=$cliente->senha_sisgcorp?>"></td>
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
					    <textarea class="form-control" rows="6" name="ocorrencias" id="ocorrencias" > <?php echo $cliente->ocorrencias ?>
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
                    <td><input type="text" class="form-control" id="segundo_rua" name="segundo_rua" value="<?=$cliente->segundo_rua?>" style="text-transform: uppercase;"></td>
                 </tr>
                  <tr>
                   <td>N&uacute;mero</td>
                    <td><input type="text" class="form-control" id="segundo_num" name="segundo_num" value="<?=$cliente->segundo_num?>"></td>
                  </tr>
                  <tr>
                  <td>Complemento</td>
                    <td><input type="text" class="form-control" id="segundo_complemento" name="segundo_complemento" value="<?=$cliente->segundo_complemento?>"></td>
                  </tr>
                  <tr>
                    <td>Bairro</td>
                    <td><input type="text" class="form-control" id="segundo_bairro" name="segundo_bairro" value="<?=$cliente->segundo_bairro?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>

                   <td>Cidade</td>
                    <td><input type="text" class="form-control" id="segundo_cidade" name="segundo_cidade" value="<?=$cliente->segundo_cidade?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
				  <td>Estado</td>
                    <td><input type="text" class="form-control" id="segundo_estado" name="segundo_estado" value="<?=$cliente->segundo_estado?>" style="text-transform: uppercase;"></td>
                  </tr>
                  <tr>
                    <td>Observa&ccedil;&otilde;es</td>
                    <td><input type="text" class="form-control" id="segundo_obs" name="segundo_obs" value="<?=$cliente->segundo_obs?>" style="text-transform: uppercase;">
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>
					
<?php include 'config/termos.php';?>
 		  
					<input type="hidden" name="acao" value="editar">
				    <input type="hidden" name="id" value="<?=$cliente->id?>">
				    <input type="hidden" name="user" value="<?=$_SESSION['uname']?>">
				    <input type="hidden" name="foto_atual" value="<?=$cliente->foto?>">
				    <button type="submit" class="btn btn-info" id='botao' onclick="salva()">Salvar</button>
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
		const habilitaWebcam = () => {
		    
		    if(!cameraOn){
		        $("#screenshots").html('');
		        $("#image_webcam").val('');
    		    webCamRequired = true;
    			playVideoStream();
    			$("#btnwebcam").addClass('btn-danger').html('<i class="fa fa-camera"></i> Desabilitar Webcam');
    			$("#btncaptura").fadeIn(100);
		    }else{
		        webCamRequired = false;
		        stopVideoStream();
		        $("#btnwebcam").removeClass('btn-danger').html('<i class="fa fa-camera"></i> Habilitar Webcam');
		        $("#btncaptura").fadeOut(100);
		    }
		    
		}
		const capturaWebcam =() => {
		    if(cameraOn){
		        
		        const img = document.createElement("img");
            	canvas.width = video.videoWidth;
            	canvas.height = video.videoHeight;
            	canvas.getContext("2d").drawImage(video, 0, 0);
            	var image = canvas.toDataURL("image/png");
            	$("#screenshots").html('<img src="'+image+'" width="235">');
            	habilitaWebcam();
		        $(".thumbnail").fadeOut(100);
		        $("#image_webcam").val(image);
		        
		    }else{
		        alert('Webcam não habilitada.');
		    }
		}
	</script>
  
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  <script>
        // lib webcam
        cameraOn = false;
        webCamRequired = false;
        
          if (
            !"mediaDevices" in navigator ||
            !"getUserMedia" in navigator.mediaDevices
          ) {
            alert("A API da câmera não está disponível no seu navegador");
            
          }else{
        
          // get page elements
          const video = document.querySelector("#video");
          const btnPlay = document.querySelector("#btnPlay");
          const btnPause = document.querySelector("#btnPause");
          const btnScreenshot = document.querySelector("#btnScreenshot");
          const btnChangeCamera = document.querySelector("#btnChangeCamera");
          const screenshotsContainer = document.querySelector("#screenshots");
          const canvas = document.querySelector("#canvas");
          const devicesSelect = document.querySelector("#devicesSelect");
        
          // video constraints
          const constraints = {
            video: {
              width: {
                min: 300,
                ideal: 300,
                max: 300,
              },
              height: {
                min: 336,
                ideal: 336,
                max: 336,
              },
        	  zoom: true,
        	  aspectRatio: {
        		  max: 555
        	  }
            },
        	aspectRatio: {
        		  max: 555
        	  }
          };
        
          // use front face camera
          let useFrontCamera = true;
        
          // current video stream
          var videoStream;
        
          // stop video stream
          function stopVideoStream() {
            if (videoStream) {
              videoStream.getTracks().forEach((track) => {
                track.stop();
        		cameraOn = false;
        		$(".thumbnail").fadeIn(100);
        		$("video").fadeOut(100);
              });
            }
          }
          
          function playVideoStream() {
        	initializeCamera();
          }
        
          // initialize
          async function initializeCamera() {
        	  
            stopVideoStream();
            constraints.video.facingMode = useFrontCamera ? "user" : "environment";
        
            try {
              videoStream = await navigator.mediaDevices.getUserMedia(constraints);
              video.srcObject = videoStream;
        	  
        	  cameraOn = true;
        	  $("video").fadeIn(100);
        	  $(".thumbnail").fadeOut(100);
            } catch (err) {
              alert(err);
              alert("Não foi possivel acessar webcam");
            }
          }
        
          }
          function salva(){
              
        	if(cameraOn){
        		var img = $("#image_webcam").val();
        		if(img == ""){alert('Foto da webcam não capturada.'); return false;}
        	}
        	
        }
  </script>
<style>
.is-hidden {display:none;}
 #video {
	width: 100%;
	max-width: 300px;
    border-radius: 5px;
 }
 .img_face_webcam {
	position: absolute;
    width: 31%;
    z-index: 99999999;
    top: 47%;
    left: 33%;
    margin-top: -17%;
    margin-left: -70px;
 }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>$(document).ready(function() { $('.select2').select2();});</script>
</body>

</html>