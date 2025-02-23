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

// Captura os dados do cliente solicitado
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', 1);
$stm->execute();
$cliente = $stm->fetch(PDO::FETCH_OBJ);

$conexao = conexao::getInstance();
$sql = 'SELECT * FROM tab_autentique ORDER BY id ASC';
$stm = $conexao->prepare($sql);
$stm->execute();
$assinantes = $stm->fetchAll(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>  
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
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>Configurações</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
            </ol>
          </div>
        </div>
		
		
        <!-------------------------------------------------------------------------------------------------------------- page start-->
      
    <fieldset>

				<form name="cadastro" action="action_configuracoes.php" method="post" id='form-contato' enctype='multipart/form-data'>
					<div class="row">

			      	<div class="col-md-1">
					    <a href="#" class="thumbnail">
					      <img src="fotos/<?=$cliente->clube_logo?>" width="70" id="foto-cliente">
					    </a>
				  	</div>
				  	<input type="file" name="foto" id="foto" value="foto" >
				  	<input type="hidden" name="foto_atual" id="foto_atual" value="<?= isset($cliente->clube_logo) ? $cliente->clube_logo : ""; ?>" >
			  	</div>
			<br>
				
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                DADOS DO CLUBE
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
                    <td>SIGLA do CLube de Tiro</td>
                    <td><input type="text" class="form-control" id="sigla_clube" name="sigla_clube" value="<?=$cliente->sigla_clube?>"></td>
                 </tr>
                    <tr>
                    <td>Nome do CLube de Tiro</td>
                    <td><input type="text" class="form-control" id="clube_nome" name="clube_nome" value="<?=$cliente->clube_nome?>"></td>
                 </tr>
                  <tr>
                   <td>CR do Clube</td>
                    <td><input type="text" class="form-control" id="clube_cr" name="clube_cr" value="<?=$cliente->clube_cr?>"></td>
                  </tr>
                  <tr>
                    <td>Validade CR Clube</td>
                    <td><input type="date" class="form-control" id="clube_validade_cr" name="clube_validade_cr" value="<?=$cliente->clube_validade_cr?>"></td>
                  </tr>
                  <tr>   
                   <td>CNPJ do Clube</td>
                    <td><input type="text" class="form-control" id="clube_cnpj" name="clube_cnpj" value="<?=$cliente->clube_cnpj?>" maxlength="18" OnKeyPress="formatar('##.###.###/####-##', this)"></td>
                  </tr>
                  <tr>	                      					  
                   <td>Endereço do Clube</td>
                    <td><input type="text" class="form-control" id="clube_endereco" name="clube_endereco" value="<?=$cliente->clube_endereco?>"></td>
                  </tr>
                  <tr>			
                    <td>Telefone do Clube</td>
                    <td><input type="text" class="form-control" id="clube_telefone" name="clube_telefone" value="<?=$cliente->clube_telefone?>" maxlength="14" OnKeyPress="formatar('##-#-####-####', this)"></td>
                    </tr>
                  <tr>                         
                   <td>Email para receber informações do sistema</td>
                    <td><input type="email" class="form-control" id="clube_email" name="clube_email" value="<?=$cliente->clube_email?>"></td>
                  </tr>
                  <tr>
                   <td>Email da OM que o clube pertence</td>
                    <td><input type="email" class="form-control" id="clube_email_om" name="clube_email_om" value="<?=$cliente->clube_email_om?>"></td>
                  </tr>
				<tr>
                   <td>Link para o Portal Web</td>
                    <td><input type="text" class="form-control" id="url" name="url" value="<?=$cliente->url?>" ></td>
                  </tr>
				<tr>
                   <td>Link para o APP na Play Store</td>
                    <td><input type="text" class="form-control" id="urlapp" name="urlapp" value="<?=$cliente->urlapp?>" ></td>
                  </tr>
				<tr>				    				    
					<td>Assinante do Documento (Autentique)</td>
					<td>
						<?php if($assinantes): ?>
							<?php foreach($assinantes as $key => $Assinante): ?>
								<div class="form-group">
									<div class="form-check form-switch">
										<input class="form-check-input" type="radio" id="id_autentique_<?= $Assinante->id; ?>" name="id_autentique" value="<?= $Assinante->id; ?>" <?= ($Assinante->id == $cliente->id_autentique) ? " checked " : ""; ?>>
										<label class="form-check-label" for="id_autentique_<?= $Assinante->id; ?>"><?= $Assinante->nome; ?></label>
									</div> 
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
				  
                  <tr>
                      <td>Forma de trabalho</td>
                      <td>
<div class="form-group">
<label for="forma_plano"></label><br>
<div class="form-check form-switch">
<input class="form-check-input" type="radio" id="forma_plano" name="forma_plano" value="M"  <?php if($cliente->forma_plano == "M") echo ' checked  '; ?>>
<label class="form-check-label" for="mySwitch">Mensalidade</label>
</div> 
<div class="form-check form-switch">
<input class="form-check-input" type="radio" id="forma_plano" name="forma_plano" value="A"  <?php if($cliente->forma_plano == "A") echo ' checked'; ?>>
<label class="form-check-label" for="mySwitch">Anuidade</label>
</div> 
<div class="form-check form-switch">
<input class="form-check-input" type="radio" id="forma_plano" name="forma_plano" value="M|A"  <?php if($cliente->forma_plano == "M|A") echo ' checked '; ?>>
<label class="form-check-label" for="mySwitch">Mensalidade e Anuidade</label>
</div> 
<span class='msg-erro msg-forma_plano'></span>
</div>
</td>
</tr>
                  
                  <tr>                      
                   <td>Planos de trabalho</td>
                    <td>                
                  <div class="form-check form-switch">
                  <input class="form-check-input" type="radio" id="plano1" name="plano" value="todos" onclick="selecionaPlano(this.value)" <?= ($cliente->plano == "todos") ? " checked " : ""; ?>>
                  <label class="form-check-label" for="mySwitch">Plano Ouro, Prata, Bronze</label>
				  
				  
				  <div class="row <?= ($cliente->plano != "todos") ? " hidden " : ""; ?>" data-plano="todos">
					<div class="col-2 text-right">Plano ouro:</div>
					<div class="col-10"><input class="form-control" type="text" name="valor_plano_ouro" id="valor_plano_ouro" data-money value="<?= number_format($cliente->valor_plano_ouro,2,',','.'); ?>"></div>
				  </div>
				  <div class="row <?= ($cliente->plano != "todos") ? " hidden " : ""; ?>" data-plano="todos">
					<div class="col-2 text-right">Plano prata:</div>
					<div class="col-10"><input class="form-control" type="text" name="valor_plano_prata" id="valor_plano_prata" data-money value="<?= number_format($cliente->valor_plano_prata,2,',','.'); ?>"></div>
				  </div>
				  <div class="row <?= ($cliente->plano != "todos") ? " hidden " : ""; ?>" data-plano="todos">
					<div class="col-2 text-right">Plano bronze:</div>
					<div class="col-10"><input class="form-control" type="text" name="valor_plano_bronze" id="valor_plano_bronze" data-money value="<?= number_format($cliente->valor_plano_bronze,2,',','.'); ?>"></div>
				  </div>
				  
                  </div>    
                  <div class="form-check form-switch">
                  <input class="form-check-input" type="radio" id="plano2" name="plano" value="personalizado" onclick="selecionaPlano(this.value)" <?= ($cliente->plano == "personalizado") ? " checked " : ""; ?>>
                  <label class="form-check-label" for="mySwitch">Plano Personalizado</label>
				  
				  <div class="row <?= ($cliente->plano != "personalizado") ? " hidden " : ""; ?>" data-plano="personalizado">
					<div class="col-2 text-right">Nome do plano:</div>
					<div class="col-4"><input class="form-control" type="text" name="nome_plano_personalizado" id="nome_plano_personalizado" value="<?= ($cliente->nome_plano_personalizado); ?>"></div>
					<div class="col-2 text-right">Valor do plano:</div>
					<div class="col-4"><input class="form-control" type="text" name="valor_plano_personalizado" id="valor_plano_personalizado" data-money value="<?= number_format($cliente->valor_plano_personalizado,2,',','.'); ?>"></div>
				  </div>
				  
                  </div>  
                  <div class="form-check form-switch">
                  <input class="form-check-input" type="radio" id="plano3" name="plano" value="fixo" onclick="selecionaPlano(this.value)" <?= ($cliente->plano == "fixo") ? " checked " : ""; ?>>
                  <label class="form-check-label" for="mySwitch">Plano Fixo</label>
				  <div class="row <?= ($cliente->plano != "fixo") ? " hidden " : ""; ?>" data-plano="fixo">
					<div class="col-2 text-right">Valor Anual:</div>
					<div class="col-10"><input class="form-control mb-2" type="text" name="valor_plano_fixo_anual" id="valor_plano_fixo" data-money value="<?= number_format($cliente->valor_plano_fixo_anual,2,',','.'); ?>"></div>
					<div class="col-2 text-right">Valor Mensal:</div>
					<div class="col-10"><input class="form-control" type="text" name="valor_plano_fixo_mensal" id="valor_plano_fixo" data-money value="<?= number_format($cliente->valor_plano_fixo_mensal,2,',','.'); ?>"></div>
				  </div>
				  
                  </div>
                  <div class="form-check form-switch">
                  <input class="form-check-input" type="radio" id="plano4" name="plano" value="semplano" onclick="selecionaPlano(this.value)" <?= ($cliente->plano == "semplano" || strlen(trim($cliente->plano)) <= 0) ? " checked " : ""; ?>>
                  <label class="form-check-label" for="mySwitch">Sem Plano</label>
                  </div>                   
                  </td>
                  </tr>
                  <tr>
                        <td>Configuração Habitualidade</td> 
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="config_habitualidade_0" name="tipo_habitualidade" value="0" onclick="configHabitualidade(this.value)" <?= ((int)$cliente->tipo_habitualidade == 0) ? ' checked ' : ''; ?>>
                                <label class="form-check-label" for="mySwitch">Sem configuração</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="config_habitualidade_1" name="tipo_habitualidade" value="1" onclick="configHabitualidade(this.value)" <?= ($cliente->tipo_habitualidade == 1) ? ' checked ' : ''; ?>>
                                <label class="form-check-label" for="mySwitch">Somente visualiza habitualidade</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="config_habitualidade_2" name="tipo_habitualidade" value="2" onclick="configHabitualidade(this.value)" <?= ($cliente->tipo_habitualidade == 2) ? ' checked ' : ''; ?>>
                                <label class="form-check-label" for="mySwitch">Precisa de aprovação do ADMIN</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="config_habitualidade_3" name="tipo_habitualidade" value="3" onclick="configHabitualidade(this.value)" <?= ($cliente->tipo_habitualidade == 3) ? ' checked ' : ''; ?>>
                                <label class="form-check-label" for="mySwitch">Lançamento por Geolocalização</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" id="config_habitualidade_4" name="tipo_habitualidade" value="4" onclick="configHabitualidade(this.value)" <?= ($cliente->tipo_habitualidade == 4) ? ' checked ' : ''; ?>>
                                <label class="form-check-label" for="mySwitch">Lançamento por Geolocalização + ADMIN</label>
                            </div>
                            <div class="row" data-geolocalizacao style="<?= ($cliente->tipo_habitualidade == 3) || ($cliente->tipo_habitualidade == 4) ? '' : 'display: none;'; ?>">
                                <div class="col-2 text-right"></div>
                                <div class="col-10"><p style="background: #fffacf;color: #af9d05;padding: 5px;">Informe a latitude e longitude do clube.</p></div>
                                <div class="col-2 text-right">Latitude:</div>
                                <div class="col-10"><input class="form-control" type="text" name="latitude" id="latitude" value="<?= $cliente->latitude; ?>"></div>
                                <div class="col-2 text-right">Longitude:</div>
                                <div class="col-10"><input class="form-control" type="text" name="longitude" id="longitude" value="<?= $cliente->longitude; ?>"></div>
                            </div>
                        </td> 
                        <tr>
                     <td>Uso de Biometria</td> 
                        <td>
                        <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" id="biometria" name="biometria" value="1"  <?php if($cliente->biometria == "1") echo ' checked'; ?>>
                        <label class="form-check-label" for="mySwitch">Ativado</label>
                        </div> 
                        <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" id="biometria" name="biometria" value="0"  <?php if($cliente->biometria == "0") echo ' checked '; ?>>
                        <label class="form-check-label" for="mySwitch">Desativado</label>
                        </div> 
                        </td>         
                        </tr>
                                                <tr>
                     <td>Reconhecimento Facial</td> 
                        <td>
                        <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" id="facial" name="facial" value="1"  <?php if($cliente->facial == "1") echo ' checked'; ?>>
                        <label class="form-check-label" for="mySwitch">Ativado</label>
                        </div> 
                        <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" id="facial" name="facial" value="0"  <?php if($cliente->facial == "0") echo ' checked '; ?>>
                        <label class="form-check-label" for="mySwitch">Desativado</label>
                        </div> 
                        </td>         
                        </tr>
                    </tr>
                    <tr>
                    <td>Normas do Clube de Tiro</td>
                    <td>
              <div class="container"><br/>
  <div class="form-group">
    <div class="form-check form-switch">
      <label data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        <input class="form-check-input" type="checkbox" id="termos_filiacao"  value="<?=$cliente->termos_filiacao?>" checked/> Termos sobre a Filiação ao Clube 
      </label>
    </div>
  </div>
  <div id="collapseOne" aria-expanded="false" class="collapse">
    <textarea rows="2" class="form-control estiloinput" name="termos_filiacao" ><?=$cliente->termos_filiacao?></textarea></div>
  
	  
  <div class="form-group">
    <div class="form-check form-switch">
      <label data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <input class="form-check-input" type="checkbox" id="termos_estatuto" name="termos_estatuto" value="<?=$cliente->termos_filiacao?>" checked/> Termos sobre o Estatuto do Clube 
      </label>
    </div>
  </div>
  <div id="collapseTwo" aria-expanded="false" class="collapse">
    <textarea rows="2" class="form-control estiloinput" name="termos_estatuto" ><?=$cliente->termos_estatuto?></textarea>
  </div>
	  
  <div class="form-group">
    <div class="form-check form-switch">
      <label data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
        <input class="form-check-input" type="checkbox" id="termos_idoneidade" name="termos_idoneidade" value="<?=$cliente->termos_idoneidade?>" checked>Termos sobre a Idoneidade do Atirador Esportivo
      </label>
    </div>
  </div>
  <div id="collapseThree" aria-expanded="false" class="collapse">
    <textarea  rows="2"  class="form-control estiloinput" name="termos_idoneidade"><?=$cliente->termos_idoneidade?></textarea>
  </div>
</div>                   
                </tbody>
              </table>
            </section>
          </div>
        </div>
		  
	<input type="hidden" name="acao" value="<?php if(strlen(trim($id_cliente)) > 0){echo "editar";}else{echo "incluir";} ?>">

    <input type="hidden" name="id" value="<?=$cliente->id?>">
    <button type="submit" class="btn btn-info"  >Salvar</button>
    </form>
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
  
  

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->
  
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
$(document).ready(function(e){
	$("[data-money]").maskMoney({thousands:'.', decimal:',', precision: 2, allowZero: true});
});
const selecionaPlano = (plano) => {
	$("[data-money]").val('0,00');
	$("[data-plano]").addClass("hidden");
	$("[data-plano="+plano+"]").removeClass("hidden");
}
const configHabitualidade = (config) => {
    if(config != 3 && config != 4){
        $("[data-geolocalizacao]").fadeOut(0);
        $("[name=latitude],[name=longitude]").val('');
    }else{
        $("[data-geolocalizacao]").fadeIn(0);
    }
}
</script>

</body>

</html>