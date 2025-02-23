<?php

if(!isset($_SESSION)){
session_start();
}

if(!isset($_SESSION['uname'])){
    exit(header('index.php'));
}
// Permite qualquer domínio acessar os recursos (aberto a todos)
header("Access-Control-Allow-Origin: *");

// Permite métodos específicos (GET, POST, PUT, DELETE, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Permite cabeçalhos personalizados (caso você use cabeçalhos como Content-Type ou Authorization)
header("Access-Control-Allow-Headers: Content-Type, Authorization");
?>
<?php

date_default_timezone_set('America/Sao_Paulo');

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)):
    
    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = 'SELECT * FROM info_clube WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', 1);
    $stm->execute();
    $clube = $stm->fetch(PDO::FETCH_OBJ);

	// Captura os dados do cliente solicitado
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_membros WHERE id = :id';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':id', $id_cliente);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	
	// pega dados da habitualidade
	$sql2 = " SELECT * FROM tab_habitualidade WHERE matricula = :matricula ORDER BY datacadastro DESC ";
	$stm = $conexao->prepare($sql2);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->execute();
	$habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

    $query = "SELECT * FROM tab_habitualidade";
    $query .= " LEFT JOIN tab_habitualidade_membros ON tab_habitualidade.id = tab_habitualidade_membros.habitualidade_id";
    $query .= " WHERE matricula = :matricula";
    $stm = $conexao->prepare($query);
    $stm->bindValue(':matricula', $cliente->matricula);
    $stm->execute();
    $newHabitualidade = $stm->fetchAll(PDO::FETCH_OBJ);
	
$conexao = conexao::getInstance();
  $sql4 = 'SELECT * FROM tab_armas WHERE sequencia = "1" ORDER BY descricao ASC';
  $stm = $conexao->prepare($sql4);
  $stm->execute();
  $armas4 = $stm->fetchAll(PDO::FETCH_OBJ);

  $conexao = conexao::getInstance();
  $sql5 = 'SELECT * FROM tab_armas WHERE id_proprietario = :id_membro AND sequencia = 2 ORDER BY descricao ASC';
  $stm = $conexao->prepare($sql5);
  $stm->execute(['id_membro' => $id_cliente]);
  $armas5 = $stm->fetchAll(PDO::FETCH_OBJ);
	
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_habitu_op WHERE tipo = "L" ORDER BY local ASC';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$habitu_op = $stm->fetchAll(PDO::FETCH_OBJ);
	
	$conexao = conexao::getInstance();
	$sql = 'SELECT * FROM tab_habitu_op WHERE tipo = "E" ORDER BY evento ASC';
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$habitu_ev = $stm->fetchAll(PDO::FETCH_OBJ);
	
	// Consulta os grupos distintos na tabela tab_armas
    $sql_grupos = 'SELECT * FROM tab_grupos_armas';
    $stm_grupos = $conexao->prepare($sql_grupos);
    $stm_grupos->execute();
    $grupos = $stm_grupos->fetchAll(PDO::FETCH_OBJ);
    
    $conexao = conexao::getInstance();
    $sql = "
        SELECT 
            id, 
            nome_loja, 
            validadepromocao 
        FROM 
            tab_empresas 
        WHERE
            validadepromocao >= now()
            AND (
                produto1 <> '' OR
                produto2 <> '' OR
                produto3 <> '' OR
                produto4 <> '' OR
                produto5 <> '' OR
                produto6 <> '' OR
                produto7 <> '' OR
                produto8 <> '' OR
                produto9 <> '' OR
                produto10 <> ''
            )
        ORDER BY
            validadepromocao
        ASC
    ";
    $stm = $conexao->prepare($sql);
	$stm->execute();
	$promocoes = $stm->fetchAll(PDO::FETCH_OBJ);
    
    $conexao = conexao::getInstance();
    $sql = "
        SELECT 
            * 
        FROM
            tab_avisos
        WHERE
            data_aviso >= '".date('Y-m-d')."'
        ORDER BY
            data_aviso ASC
    ";

    $stm = $conexao->prepare($sql);
	$stm->execute();
	$avisos = $stm->fetchAll(PDO::FETCH_OBJ);

	if(!empty($cliente)):

		// Formata a data no formato nacional
		$array_data     = explode('-', $cliente->data_nascimento);
		$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

	endif;

endif;

// Faça a consulta ao banco de dados para obter os calibres do tipo 'C'
$conexao = conexao::getInstance();
$sql = "SELECT calibre FROM tab_habitu_op WHERE tipo = 'C'";
$stm = $conexao->prepare($sql);
$stm->execute();
$getallCalibres = $stm->fetchAll(PDO::FETCH_COLUMN);


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
 <style>
  .modal .modal-dialog { width: 30%; }  
  @media(min-width:768px){  
  .modal .modal-dialog { width: 20%; }  
    } 
  </style>
  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Escolha um periodo</h5>
   </button>
  </div>
  <div class="modal-body">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function(){  
  $('.data').on('keyup', function(){
  var $this = $(this);
  var mydate = $this.val();
  mydate = mydate.replace(/\D|\s/, '');  
  mydate = mydate.replace(/^(00)(.*)?/, '01$2');
  mydate = mydate.replace(/^([0-9]{2})(00)(.*)?/, '$101');
  mydate = mydate.replace(/^([3-9])([2-9])(.*)?/, '2$2');
  mydate = mydate.replace(/^(3[01])(02)(.*)?/, '29$2');
  mydate = mydate.replace(/^([0-9]{2})([2-9]|1[3-9])(.*)?/, '$112');
  mydate = mydate.replace(/^([0-9]{2})([0-9]{2})([0-9].*?)/, '$1/$2/$3');
  mydate = mydate.replace(/^([0-9]{2})([0-9])/, '$1/$2');    
  //ano bissexto
  var day = mydate.substr(0,2) || '01';
  var month = mydate.substr(3,2) || '01';
  var year = mydate.substr(6,4);
  if(year.length == 4 && day == '29' && month == '02' && (year % 4 != 0 || (year.substr(2,2) == '00' && year % 400 != 0))) {
  mydate = mydate.replace(/^29/,'28');
  }
  mydate = mydate.substr(0,10);
  $this.val(mydate);
  })
  })
  </script>
  <form method="get" id="form" target="_blank" action="relatorios/habitualidade2.php" >
  <div class="row" >
  <div class="col"> 
  <label for="formGroupExampleInput">&nbsp;De:</label>
  <input type="text"  class="data form-control"  name="datainicial" value="" required>
  <label for="formGroupExampleInput">&nbsp;Até:</label>
  <input type="text" class="data form-control" name="datafinal" value="" required>
  </div> 
  </div>
  </form>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
  <input type="submit"  onclick="document.getElementById('form').submit();" class="btn btn-primary" value="Gerar">
  </div>
  </div>
  </div>
  </div>
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
  <style>
    .gd-dropdown-menu .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.25rem 1.5rem;
      clear: both;
      font-weight: 400;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
    }
    .gd-dropdown-menu .dropdown-item:hover {
      color: #16181b;
      text-decoration: none;
      background-color: #f8f9fa;
    }
  </style>
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
    <?php include 'menu_lateral_esq.php'; ?>
    <!-- menu lateral fim -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i><?= $cliente->nome ?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matrícula > <?= $cliente->matricula ?></li>
            </ol>
          </div>
        </div>        
        <!-------------------------------------------------------------------------------------------------------------- page start-->

<fieldset>
<form method="post" action="novo_action_habitualidade.php">
     <input type="hidden" class="" id="matricula" name="matricula" value="<?=$cliente->matricula?>" readonly>
     <input type="hidden" class="" id="data_filiacao" name="data_filiacao" value="<?=$cliente->data_filiacao?>" disabled>													
     <input type="hidden" class="" id="cr" name="cr" value="<?=$cliente->cr?>" disabled>													
     <input type="hidden" class="l" id="validade_cr" name="validade_cr" value="<?=$cliente->validade_cr?>" disabled>
     <input type="hidden" class="l" id="nome" name="nome" value="<?=$cliente->nome?>">
     <input type="hidden" name="id" value="<?=$cliente->id?>">
     <input type="hidden" name="acao" value="editar">
     <input type="hidden" class="form-control" id="user" name="user" value="<?= $_SESSION['uname'] ?>" >
    
<div class="form-group">
   
<label for="numsigma">Data:</label><br>                       

<input type="hidden" name="id_habitualidade[]" value=""><input type="hidden" name="aprovado[]" value=""><input type="hidden" name="data_aprovacao[]" value=""><input type="hidden" name="id_linha[]" value="0"><input type="text" class="form-control mask-date" name="habitu_data[]" maxlength="50" value="<?= date('d/m/Y H:i'); ?>">
<span class='msg-erro msg-numsigma'></span>
</div>

<div class="form-group">
<label for="local">Local:</label><br>
 <select class="form-control" name="habitu_local[]" size="1" id="local"><option value="">-</option><?php foreach ($habitu_op as $op) : ?><option value="<?= $op->local; ?>"><?= $op->local; ?></option><?php endforeach; ?></select>
<span class='msg-erro msg-tipo'></span>
</div>

<div class="form-group">
    <label for="evento">Evento:</label><br>

   <select class="form-control" name="habitu_evento[]" size="1"><option value="">-</option><?php foreach ($habitu_ev as $ev) : ?><option value="<?= $ev->evento; ?>"><?= $ev->evento; ?></option><?php endforeach; ?></select>

    <span class='msg-erro msg-evento'></span>
</div>

<div class="form-group">
            <label for="grupo">Armamento:</label><br>
             <?php $tipo = $habitualidade[$idh]->tipo . '|' . $habitualidade[$idh]->modelo . '|' . $habitualidade[$idh]->calibre . '|' . $habitualidade[$idh]->numsigma; ?>

                    <?php if ($habitualidade[$idh]->digitado == 0) : ?>
                    <?php echo "".$idh;?>
            <select name="tipo<?= htmlspecialchars($idh, ENT_QUOTES, 'UTF-8'); ?>[]" class="form-control">
                    <option value="">- selecione -</option>
                    
                    <!--Select Acervo Clube-->
                        <?php if (!empty($armas4)) : ?>
                            <optgroup label="== Acervo Clube ==">
                                <?php foreach ($armas4 as $Armas) : ?>
                                    <?php
                                    // Formatar o valor da opção
                                    $optionValue =trim($Armas->tipo) . '|' . trim($Armas->modelo) . '|' . trim($Armas->calibre) . '|' . trim($Armas->numsigma).'|'.trim($Armas->id);
                    
                                    // Verificar se o valor da opção deve ser selecionado
                                    $selectedValue = trim($habitualidade[$idh]->tipo) . '|' . trim($habitualidade[$idh]->modelo) . '|' . trim($habitualidade[$idh]->calibre) . '|' . trim($habitualidade[$idh]->numsigma).'|'.trim($habitualidade[$idh]->arma_id);
                                    $isSelected = ($optionValue == $selectedValue) ? ' selected' : '';
                                    ?>
                                    <option value="<?= htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>" <?= $isSelected; ?>>
                                        <?= htmlspecialchars($Armas->tipo . ' | ' . $Armas->modelo . ' | ' . $Armas->calibre . ' |' . $Armas->numsigma, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        
<!--Select Acervo Pessoal-->
<?php if (!empty($armas5)) : ?>
    <optgroup label="== Acervo Pessoal ==">
        <?php foreach ($armas5 as $Arma_Pessoal) : ?>
            <?php
            // Formatar o valor da opção
            $optionValue = trim($Arma_Pessoal->tipo) . '|' . trim($Arma_Pessoal->modelo) . '|' . trim($Arma_Pessoal->calibre) . '|' . trim($Arma_Pessoal->numsigma) . '|' . trim($Arma_Pessoal->id);
            
            // Verificar se o valor da opção deve ser selecionado
            $selectedValue = trim($habitualidade[$idh]->tipo) . '|' . trim($habitualidade[$idh]->modelo) . '|' . trim($habitualidade[$idh]->calibre) . '|' . trim($habitualidade[$idh]->numsigma) . '|' . trim($habitualidade[$idh]->arma_id);
            
            // Verificar se o valor da opção deve ser selecionado
            $isSelected = ($optionValue == $selectedValue) ? ' selected' : '';
            ?>
            <option value="<?= htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>" <?= $isSelected; ?>>
                <?= htmlspecialchars($Arma_Pessoal->tipo . ' | ' . $Arma_Pessoal->modelo . ' | ' . $Arma_Pessoal->calibre . ' |' . $Arma_Pessoal->numsigma, ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </optgroup>
<?php endif; ?>

                        <?php else : ?>
                                    <div class="form-group">
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->tipo; ?>" type="text" name="manual_tipo_<?= $idh; ?>[]" class="form-control" placeholder="Tipo"></div>
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->modelo; ?>" type="text" name="manual_tipo_<?= $idh; ?>[]" class="form-control" placeholder="Modelo"></div>
                                      <div class="col-sm-3" style="padding:0 !important;">
                                        <select name="manual_calibre_<?= $idh; ?>[]" class="form-control">
                                          <?php if(count($getallCalibres) > 0): ?>

                                            <?php foreach($getallCalibres as $calibre) : ?>
                                              <option value="<?= $calibre?>" <?= ($calibre == $habitualidade[$idh]->calibre) ? ' selected ' : ''; ?>><?= $calibre?></option>
                                            <?php endforeach; ?>

                                          <?php  else: ?>
                                            <option value="">Nenhum calibre encontrado</option>
                                          <?php endif; ?>

                                        </select>
                                      </div>
                                      <div class="col-sm-3" style="padding:0 !important;"><input value="<?= $habitualidade[$idh]->numsigma; ?>" type="text" name="manual_sigma_<?= $idh; ?>[]" class="form-control" placeholder="Sigma"></div>
                                    </div>

                                  <?php endif; ?>
                                  </select>
            
          </div>
         

<div class="form-group">
<label for="qtdemunicao">Quantidade Munição:</label><br>
<input type="text" class="form-control" name="habitu_qtdemunicoes[]" size="10" value="" id="qtdemunicao">
<span class='msg-erro msg-validade_gt'></span>
</div>

<div class="form-group">
<label for="livro">Livro:</label><br>
<input type="text" class="form-control" name="habitu_livro[]" size="10" value="" id="livro">
<span class='msg-erro msg-livro'></span>
</div>

<div class="form-group">
<label for="validade_craf">Origem Munição:</label><br>
<div class="custom-control custom-radio">
                                    <input  class="custom-control-input" type="radio" id="origem_municao_C_<?= $idh; ?>" name="origem_municao" value="C">
                                    <label class="custom-control-label" for="origem_municao_C_<?= $idh; ?>">Clube</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input  class="custom-control-input" type="radio" id="origem_municao_A_<?= $idh; ?>" name="origem_municao" value="A">
                                    <label class="custom-control-label" for="origem_municao_A_<?= $idh; ?>">Atirador</label>
                                  </div>
<span class='msg-erro msg-validade_craf'></span>
</div>
<div class="form-group">
<label for="validade_craf">Tipo de Munição:</label><br>
<div class="custom-control custom-radio">
                                    <div class="custom-control custom-radio">
                                    <input  class="custom-control-input" type="radio" id="tipo_municao_O_<?= $idh; ?>" name="tipo_municao" value="O">
                                    <label class="custom-control-label" for="tipo_municao_O_<?= $idh; ?>">Original</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                    <input  class="custom-control-input" type="radio" id="tipo_municao_R_<?= $idh; ?>" name="tipo_municao" value="R">
                                    <label class="custom-control-label" for="tipo_municao_R_<?= $idh; ?>">Recarga</label>
                                  </div>
<span class='msg-erro msg-validade_craf'></span>
</div>

<input type="hidden" id="latitude" name="latitude">
<input type="hidden" id="longitude" name="longitude">
<br>
<button type="submit" class="btn btn-primary" id='botao'> Salvar </button>
<a href="habitualidade.php?id=<?= $cliente->id ?>" class="btn btn-secondary">Cancelar</a>
</form>

</fieldset>
  </div>
        </div>

        <!-------------------------------------------------------------------------------------------------------------- page end-->
      </section>
      <div class="text-right">
        <div class="credits">
          Designed by <a href="#">HOSTMARQ</a>
        </div>
      </div>
    </section>
    <!--main content end-->
  <form action="action_habitualidade.php" method="post" name="aprova_h_pendente" id="aprova_h_pendente">
    <input type="hidden" name="act" value="aprova_habitualidade">
    <input type="hidden" name="matricula" value="<?= $cliente->matricula; ?>">
    <input type="hidden" name="id" value="<?= $cliente->id ?>">
  </form>
  <form action="action_habitualidade.php" method="post" name="excluir_habitualidade" id="excluir_habitualidade">
    <input type="hidden" name="act" value="excluir_habitualidade">
    <input type="hidden" name="matricula" value="<?= $cliente->matricula; ?>">
    <input type="hidden" name="id_habitualidade_del" id="id_habitualidade_del" value="">
    <input type="hidden" name="id" value="<?= $cliente->id ?>">
  </form>  
  <!-- container section end -->
<script src="atirador/assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="atirador/assets/js/material.min.js" type="text/javascript"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Dynamic Elements plugin -->
<script src="atirador/assets/js/arrive.min.js"></script>
<!--  PerfectScrollbar Library -->
<script src="atirador/assets/js/perfect-scrollbar.jquery.min.js"></script>

<!-- Material Dashboard javascript methods -->
<script src="atirador/assets/js/material-dashboard.js?v=1.2.0"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <!-- javascripts -->
  <script src="js/jquery.js"></script>
 
  <script src="js/bootstrap.min.js"></script>
  <!-- nicescroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!-- jquery validate js -->
  <script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2qxd0_98emODxrh1FADAMyPy5PTAclCI&callback=console.debug&libraries=maps,marker&v=beta"></script>

  <!-- custom form validation script for this page-->
  <script src="js/form-validation-script.js"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>

  <script>
$(document).ready(function() {
    $('.mask-date').mask('00/00/0000 00:00');
    if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    function(position) {
    var latitude=$("#latitude").val(position.coords.latitude);
    var longitude=$("#longitude").val(position.coords.longitude);
       
      console.log("Latitude: " + latitude);
      console.log("Longitude: " + longitude);
    },
    function(error) {
      console.error("Erro ao obter localização: ", error);
    }
  );
} else {
  console.log("Geolocalização não é suportada pelo seu navegador.");
}
  
});
</script>
<?php if($clube->tipo_habitualidade == 3 || $clube->tipo_habitualidade == 4): ?>
<script>


function verifica_ssl(){
    return <?= (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') ? false : true; ?>
}

// verifica se o gps esta ativo
function verifica_gps_ativo(){
    
   
    
    if(!verifica_ssl()){
        alert('Acesso permitido somente via HTTPS');
        return false;
    }
    
    try{
		
		var startPos;
		var geoOptions = {
			enableHighAccuracy: true
		}
		
		


}
window.onload=function(){
    verifica_gps_ativo();
}


 
</script>


<?php endif; ?>
<script src="https://unpkg.com/imask"></script>
<script>
    const element = document.querySelector('.mask-date');
const maskOptions = {
  mask: '00/00/0000 00:00'
};
const mask = IMask(element, maskOptions);
</script>
<script>

    $(document).ready(function() {
  

    const aprovarHabitualidade = () => {
      var r = confirm('Confirma aprovação das habitualidades pendentes desse atirador?');
      if (r) {
        document.getElementById("aprova_h_pendente").submit();
      }
      return false;
    }
    const dropHabitualidade = (id_habitualidade) => {
        var r = confirm('Deseja realmente excluir este registro?');
      if (r) {
        document.getElementById("id_habitualidade_del").value=id_habitualidade;
        document.getElementById("excluir_habitualidade").submit();
      }
      return false; 
    }
  </script>
 
</body>

</html>
