<?php
if(!isset($_SESSION)){
session_start();
}
if(!isset($_SESSION['user_id'])){
    exit(header('location: /atirador/'));
}
?>

<?php

require '../config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele � num�rico
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

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>HOSTMARQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css?v=1.2.0" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>
    <script src="https://use.fontawesome.com/17d640c73c.js"></script>

<!--     BARRA PROGRESSO BOTAO DOWNLOADS     -->
<style>
.document-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.alert {
    position: relative;
    padding: 10px;
}

.progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 4px;
    background-color: blue; /* Cor da barra de progresso */
    transition: width 0.5s; /* Duração da animação */
}

.download-message {
    display: none;
    font-weight: bold;
}
</style>



</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-1.jpg">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

        Tip 2: you can also add an image using data-image tag
    -->
		<script type="text/javascript" src="assets/jQuery/jQuery-2.1.4.min.js"></script>
	<script type="text/javascript" src="assets/jQuery/mask.js"></script>
	
  <script>document.getElementsByTagName("html")[0].className += " js";</script>

             <div class="logo">
                <a href='perfil.php?id=<?=$cliente->id?>'>
                    &Aacute;REA DO ATIRADOR
                </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./logout.php"><i><img src="assets/img/sairgr.png" alt="Sair" width="18" height="18" border="0"></i></a>				
            </div>
			   <div class="sidebar-wrapper">
                <ul class="nav">
                   <li>
                        <a href='perfil.php?id=<?=$cliente->id?>'>
						<i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            Meu Perfil
                        </a>
                    </li>
                    
                    <li>
                        <a href='financeiro.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-money" aria-hidden="true"></i>
                            Financeiro
                        </a>
                    </li>
                    <li >
                        <a href='documentos.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                            Documentos
                        </a>
                    </li>
                    <li >
                        <a href='armas.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            Armamentos
                        </a>
                    </li>
                    <li>
                        <a href='controle_municoes.php?id=<?=$cliente->id?>'>
                           <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            Controle Muni&ccedil;&otilde;es
                        </a>
                    </li>
                    <li>
                        <a href='habitualidades.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            Habitualidades
                        </a>
                    </li>                    
                    <li class="active">
                        <a href='relatorios.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-print" aria-hidden="true"></i>
                            <p>Relat&oacute;rios</p>
                        </a>
                    </li>
                    <li >
                        <a href='contatos.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-volume-control-phone" aria-hidden="true"></i>
                            Contatos
                        </a>
                    </li>
                    <li>
                        <a href='avisos.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            Avisos <?= ((count($avisos) > 0) ? '<span class="notificacao">'.count($avisos).'</span>' : ''); ?>
                        </a>
                    </li>
                    <li>
                        <a href='cursos.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-trophy" aria-hidden="true"></i>
                            Cursos
                        </a>
                    </li>
                    <li>
                        <a href='visitas.php?id=<?=$cliente->id?>'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            Convidados
                        </a>
                    </li>
                       
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand"><b><font color="993399 "><?=$clube->sigla_clube?></font></b></a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                            </li>
                            <li>
                            </li>
                       </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                        <div class="card-header" data-background-color="purple">
                            <h4 class="title">Relat&oacute;rios</h4>
                            <p class="category">Lembre antes de imprimir qualquer documento, certificar-se que seus dados est&atilde;o atualizados no sistema.</p>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Declarações do Clube</h5>

<?php if($cliente->bloqueio == 'Nao'): ?>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/filiacao.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-primary">
            <span>Declara&ccedil;&atilde;o Filia&ccedil;&atilde;o</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/modalidade.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-success">
            <span>Declara&ccedil;&atilde;o Modalidade</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/habitualidade.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-danger">
            <span>Declara&ccedil;&atilde;o Habitualidade Iniciante</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

</div>


<div class="col-md-6">
<h5>Outros Documentos</h5>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/controle_municao.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-primary">
            <span>Mapa Munição</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/contagem_habitualidade.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-success">
            <span>Contagem Habitualidades</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/carteira.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-danger">
            <span>Carteira de Associado</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>

<div class="document-buttons">
    <a href="#" data-url="../relatorios/fichaindividual.php?id=<?=$cliente->id?>" onclick="startDownload(this)">
        <div class="alert alert-warning">
            <span>Ficha Individual</span>
            <div class="progress-bar"></div>
            <div class="download-message">AGUARDE O DOWNLOAD</div>
        </div>
    </a>
</div>


<script>
function startDownload(button) {
    const progressBar = button.querySelector('.progress-bar');
    const downloadMessage = button.querySelector('.download-message');

    progressBar.style.width = '0';
    progressBar.style.display = 'block';

    // Simulando um download (substitua isso pelo seu código de download real)
    let width = 0;
    const downloadInterval = setInterval(function() {
        width += 1;
        progressBar.style.width = width + '%';

        if (width >= 100) {
            clearInterval(downloadInterval);
            progressBar.style.display = 'none';
            downloadMessage.style.display = 'block';
            // Redirecionar para o link de download após o download simulado
            setTimeout(function() {
                window.location.href = button.getAttribute('data-url');
            }, 1000); // Aguarde 1 segundo antes de redirecionar
        }
    }, 50); // Velocidade do download simulado
}
</script>

<?php endif; ?>


                               </div>								
                            </div>
							</div>
							</div>
                   <p class="copyright pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy;
                   <script>
                            document.write(new Date().getFullYear())
                    </script>
                       by Hostmarq para Clubes de Tiro<br><br>
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/material.min.js" type="text/javascript"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Dynamic Elements plugin -->
<script src="assets/js/arrive.min.js"></script>
<!--  PerfectScrollbar Library -->
<script src="assets/js/perfect-scrollbar.jquery.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js?v=1.2.0"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

</html>