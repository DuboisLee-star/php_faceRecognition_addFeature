<?php
include "config/config.php";

// Check user login
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}

require 'config/conexao.php';

// Get member data if ID is provided
$id_membro = (isset($_GET['id'])) ? $_GET['id'] : '';
$membro = null;

// Search functionality
$search_results = array();
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $conexao = conexao::getInstance();
    $search = $_GET['search'];
    $sql = "SELECT id, matricula, nome, foto FROM tab_membros 
            WHERE nome LIKE :search OR matricula LIKE :search";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':search', '%'.$search.'%');
    $stm->execute();
    $search_results = $stm->fetchAll(PDO::FETCH_OBJ);
}

// Get member details if ID is provided
if (!empty($id_membro) && is_numeric($id_membro)) {
    $conexao = conexao::getInstance();
    $sql = 'SELECT id, matricula, nome, foto FROM tab_membros WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id_membro);
    $stm->execute();
    $membro = $stm->fetch(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>ADM - Atualizar Foto</title>
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    
    <style>
    .is-hidden {display: none;}
    #video {
        width: 100%;
        max-width: 300px;
        border-radius: 5px;
    }
    .search-box {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <section id="container" class="">
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom">
                    <i class="icon_menu"></i>
                </div>
            </div>
            <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
            <div class="top-nav notification-row">
                <ul class="nav pull-right top-menu">
                    <li class="dropdown">
                        <form method='post' action="">
                            <input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout">
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <?php include 'menu_lateral_esq.php';?>

        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-camera"></i> Atualizar Foto do Membro</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
                            <li><i class="fa fa-camera"></i>Atualizar Foto</li>
                        </ol>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">Buscar Membro</header>
                            <div class="panel-body">
                                <form method="GET" class="form-inline search-box">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Nome ou Matrícula" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                                </form>

                                <?php if (!empty($search_results)): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Nome</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($search_results as $result): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($result->matricula); ?></td>
                                            <td><?php echo htmlspecialchars($result->nome); ?></td>
                                            <td>
                                                <a href="?id=<?php echo $result->id; ?>" class="btn btn-info btn-sm">
                                                    Selecionar
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>

                <?php if ($membro): ?>
                <!-- Photo Update Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Atualizar Foto - <?php echo htmlspecialchars($membro->nome); ?> 
                                (Matrícula: <?php echo htmlspecialchars($membro->matricula); ?>)
                            </header>
                            <div class="panel-body">
                                <form action="action_update_photo.php" method="post" id="form-photo" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <a href="#" class="thumbnail">
                                                <img src="<?php echo $membro->foto ? 'fotos/'.$membro->foto : 'img/padrao.png'; ?>" 
                                                     height="190" width="150" id="foto-membro">
                                            </a>
                                            <div id="screenshots"></div>
                                            <canvas class="is-hidden" id="canvas"></canvas>
                                            <video autoplay id="video" style="display:none;"></video>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="file" name="foto" id="foto" value="foto">
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <button onclick="return habilitaWebcam()" class="btn btn-primary" type="button" id="btnwebcam">
                                                <i class="fa fa-camera"></i> Habilitar Webcam
                                            </button>
                                            <button onclick="return capturaWebcam()" class="btn btn-success" type="button" 
                                                    id="btncaptura" style="display: none;">
                                                <i class="fa fa-photo"></i> Capturar
                                            </button>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <input type="hidden" name="id" value="<?php echo $membro->id; ?>">
                                            <input type="hidden" name="foto_atual" value="<?php echo $membro->foto; ?>">
                                            <input type="hidden" name="image_webcam" id="image_webcam">
                                            <button type="submit" class="btn btn-info">Salvar Foto</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
                <?php endif; ?>

            </section>
        </section>
    </section>

    <!-- javascripts -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/scripts.js"></script>
    
    <script>
        let cameraOn = false;
        let webCamRequired = false;
        let videoStream;

        const habilitaWebcam = () => {
            if (!cameraOn) {
                $("#screenshots").html('');
                $("#image_webcam").val('');
                webCamRequired = true;
                playVideoStream();
                $("#btnwebcam").addClass('btn-danger').html('<i class="fa fa-camera"></i> Desabilitar Webcam');
                $("#btncaptura").fadeIn(100);
            } else {
                webCamRequired = false;
                stopVideoStream();
                $("#btnwebcam").removeClass('btn-danger').html('<i class="fa fa-camera"></i> Habilitar Webcam');
                $("#btncaptura").fadeOut(100);
            }
            return false;
        };

        const capturaWebcam = () => {
            if (cameraOn) {
                const canvas = document.querySelector("#canvas");
                const video = document.querySelector("#video");
                const ctx = canvas.getContext("2d");

                // Set the canvas size to match the displayed video size
                canvas.width = 150;
                canvas.height = 150 * video.videoHeight / video.videoWidth; 

                // Draw the captured frame from the video
                ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas.width, canvas.height);

                // Convert the canvas image to a Data URL
                let image = canvas.toDataURL("image/png");

                // Update the profile image with the captured photo
                $("#foto-membro").attr("src", image).fadeIn(100).css("visibility", "visible");

                // Hide the video preview and show the captured image
                habilitaWebcam();
                $(".thumbnail").fadeOut(100);
            } else {
                alert('Webcam não habilitada.');
            }
            return false;
        };



        function stopVideoStream() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                cameraOn = false;
                $(".thumbnail").fadeIn(100);
                $("video").fadeOut(100);
            }
        }

        async function playVideoStream() {
            try {
                const constraints = {
                    video: {
                        width: { min: 300, ideal: 300, max: 300 },
                        height: { min: 336, ideal: 336, max: 336 }
                    }
                };

                const video = document.querySelector("#video");
                videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = videoStream;
                cameraOn = true;
                $("video").fadeIn(100);
                $(".thumbnail").fadeOut(100);
            } catch (err) {
                alert("Não foi possível acessar webcam: " + err);
            }
        }

        // Check if webcam API is available
        if (!("mediaDevices" in navigator && "getUserMedia" in navigator.mediaDevices)) {
            alert("A API da câmera não está disponível no seu navegador");
        }

        document.addEventListener("DOMContentLoaded", function () {
            const fotoInput = document.getElementById("foto");
            if (fotoInput) {
                fotoInput.addEventListener("change", function (event) {
                    const file = event.target.files[0]; // Get the selected file
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById("foto-membro").src = e.target.result; // Update the image preview
                        };
                        reader.readAsDataURL(file); // Read the image file as a Data URL
                    }
                });
            }

            $(window).on("load", function () {
                if ($("html").getNiceScroll) {
                    $("html").getNiceScroll().remove();
                }
            });
        });
    </script>

</body>
</html>
