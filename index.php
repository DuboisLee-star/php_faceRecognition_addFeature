<?php
require 'config/url.php';
require 'config/config.php';
require 'config/conexao.php';

// Defina o ID do clube (ajuste conforme necessário)
$id_clube = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// Consulta ao banco de dados
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM info_clube WHERE id = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $id_clube, PDO::PARAM_INT);
$stm->execute();
$clube = $stm->fetch(PDO::FETCH_OBJ);

if (!$clube) {
    $clube_telefone = 'Telefone não disponível';
    $clube_email = 'Email não disponível';
    $clube_endereco = 'Endereço não disponível';
} else {
    $clube_telefone = $clube->clube_telefone;
    $clube_email = $clube->clube_email;
    $clube_endereco = $clube->clube_endereco;
}
?>
<html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>HOSTMARQ</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="<?php echo $url_site; ?>assets/image/png" href="favicon.ico">

        <!--Google Font link-->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/slick/slick.css"> 
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/slick/slick-theme.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/iconfont.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/magnific-popup.css">
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/bootsnav.css">

        <!--Theme custom css -->
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/style.css">
        <!--<link rel="stylesheet" href="assets/css/colors/maron.css">-->

        <!--Theme Responsive css-->
        <link rel="stylesheet" href="<?php echo $url_site; ?>assets/css/responsive.css" />
<script src="https://use.fontawesome.com/ef9d0d160a.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>

    <body data-spy="scroll" data-target=".navbar-collapse">


        <!-- Preloader -->
        <div id="loading">
            <div id="loading-center">
                <div id="loading-center-absolute">
                    <div class="object" id="object_one"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_three"></div>
                    <div class="object" id="object_four"></div>
                </div>
            </div>
        </div><!--End off Preloader -->

        <div class="culmn">
            <!--Home page style-->

            <nav class="navbar navbar-default bootsnav navbar-fixed">
                <div class="navbar-top bg-grey fix">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="navbar-callus text-left sm-text-center">
                                    <ul class="list-inline">
                                      <li><a href="https://wa.me/<?php echo $clube_telefone; ?>"><i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp: <?php echo $clube_telefone; ?></a></li>
                                     <li><a href="mailto:<?php echo $clube_email; ?>"><i class="fa fa-envelope-o"></i> Email: <?php echo $clube_email; ?></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="navbar-socail text-right sm-text-center">
                                    <ul class="list-inline">
                                        <li><a href=""><i class="fa fa-facebook"></i></a></li>
                                        <li><a href=""><i class="fa fa-twitter"></i></a></li>
                                        <li><a href=""><i class="fa fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container"> 

                    <!-- Start Header Navigation -->
                    </div>
                    <!-- End Header Navigation -->

                </div> 

            </nav>

            <!--Home Sections-->

            <section id="home" class="home bg-black fix">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row">
                        <div class="main_home text-center">
                            <div class="col-md-12">
                                <div class="hello_slid">
                                    <div class="slid_item">
                                        <div class="home_text ">
											<div align=center><img src="<?php echo $url_site; ?>img/logo_site.png" height=110></div><br>
                                            <h2 class="text-white">Seja Bem-vindo!</h2>
                                            <h3 class="text-white">- Escolha a <strong>&Aacute;REA RESTRITA</strong> de acesso -</h3>
                                        </div>

                                        <div class="home_btns m-top-40">
                                            <a href="<?php echo $url_site; ?>index2.php" class="btn btn-default m-top-20">ADMINISTRATIVO</a>
                                            <a href="<?php echo $url_site; ?>atirador" class="btn btn-default m-top-20">ASSOCIADO</a>
                                  
                                        </div>
                                    </div><!-- End off slid item -->
                                  
                                </div>
                            </div>

                        </div>

                    </div><!--End off row-->
                </div><!--End off container -->
            </section> <!--End off Home Sections-->

            <!--Featured Section-->
            <section id="features" class="features">
                <div class="container">
                    <div class="row">
                        <div class="main_features fix roomy-70">
                            <div class="col-md-4">
                                <div class="features_item sm-m-top-30">
                                    <div class="f_item_icon">
                                        <i class="fa fa-building" aria-hidden="true"></i>
                                    </div>
                                    <div class="f_item_text">
                                        <h3>Endere&ccedil;o</h3>
                                        <p><?php echo $clube_endereco; ?>.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="features_item sm-m-top-30">
                                    <div class="f_item_icon">
                                        <i class="fa fa-id-card"></i>
                                    </div>
                                    <div class="f_item_text">
                                        <h3>Seja membro</h3>
                                        <p>Venha fazer-nos uma visita, procure algu&eacute;m filiado ao clube e teremos o prazer em receb&ecirc;-lo.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="features_item sm-m-top-30">
                                    <div class="f_item_icon">
                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                    </div>
                                    <div class="f_item_text">
                                        <h3>Fale conosco</h3>
                                        <p>Fale conosco atrav&eacute;s dos canais de atendimento, solicite informa&ccedil;&otilde;es, tire d&uacute;vidas etc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End off row -->
                </div><!-- End off container -->
            </section><!-- End off Featured Section-->

                <div class="main_footer fix bg-mega text-center p-top-40 p-bottom-30 m-top-80">
                    <div class="col-md-12">
                        <p class="wow fadeInRight" data-wow-duration="1s">
                            Sistemas para Clubes de Tiro - 
                            by 
                            <a target="_blank" href="https://hostmarq.com.br">HOSTMARQ</a> 
                            2021. Todos os Direitos Reservados
                        </p>
                    </div>
                </div>
            </footer>

        </div>

        <!-- JS includes -->

        <script src="<?php echo $url_site; ?>assets/js/vendor/jquery-1.11.2.min.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/owl.carousel.min.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/jquery.magnific-popup.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/jquery.easing.1.3.js"></script>
        <script src="<?php echo $url_site; ?>assets/css/slick/slick.js"></script>
        <script src="<?php echo $url_site; ?>assets/css/slick/slick.min.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/jquery.collapse.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/bootsnav.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/plugins.js"></script>
        <script src="<?php echo $url_site; ?>assets/js/main.js"></script>
    </body>
</html>