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
                        <h3 class="page-header"><i class="fa fa-camera"></i>Face to Habitualidade</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
                            <li><i class="fa fa-camera"></i>Atualizar Foto</li>
                        </ol>
                    </div>
                </div>

                
                <!-- Photo Update Section -->
                <div class="row">
                    <!-- Left Panel: Webcam & Captured Photo -->
                    <div class="col-md-6 d-flex flex-column align-items-center">
                        <section class="panel w-100">
                            <header class="panel-heading text-center">
                                Atualizar Foto - <?php echo htmlspecialchars($membro->nome); ?>
                                (Matrícula: <?php echo htmlspecialchars($membro->matricula); ?>)
                            </header>
                            <div class="panel-body">
                                <!-- Webcam Stream & Captured Image (Same Position) -->
                                <video autoplay id="video" style="width: 300px; height: auto; border: 2px solid #333; border-radius: 10px;"></video>
                                <canvas id="canvas" style="width: 300px; height: auto; border: 2px solid #333; border-radius: 10px; display: none;"></canvas>
                
                                <!-- Hidden Input for Captured Image -->
                                <input type="hidden" name="image_webcam" id="image_webcam">
                
                                <!-- Buttons: Webcam Toggle & Capture -->
                                <div class="mt-3 d-flex justify-content-center gap-2">
                                    <button onclick="return toggleWebcam()" class="btn btn-primary" type="button" id="btnwebcam">
                                        <i class="fa fa-camera"></i> Habilitar Webcam
                                    </button>
                                    <button onclick="return capturaWebcam()" class="btn btn-success" type="button" id="btncaptura" style="display: none;">
                                        <i class="fa fa-photo"></i> Capturar
                                    </button>
                                </div>
                            </div>
                        </section>
                    </div>
                
                    <!-- Right Panel (Future Use) -->
                    <div class="col-md-6 d-flex justify-content-center align-items-center text-center">
                        <p class="text-muted">Painel direito reservado para informações adicionais.</p>
                    </div>
                </div>

            </section>
    </section>
    </section>

    <!-- javascripts -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/scripts.js"></script>
    <script defer src="./face_models/face-api.min.js"></script>
    <script>
        let cameraOn = false;
        let videoStream;
        
        async function loadModels() {
            try {
                await faceapi.nets.ssdMobilenetv1.loadFromUri('./face_models/ssd_mobilenetv1'); 
                await faceapi.nets.faceLandmark68Net.loadFromUri('./face_models/face_landmark_68'); 
                await faceapi.nets.faceRecognitionNet.loadFromUri('./face_models/face_recognition'); 
                console.log("✅ Face models loaded!");
            } catch (error) {
                console.error("❌ Model loading error:", error);
            }
        }
        
        function toggleWebcam() {
            const video = document.getElementById("video");
            const canvas = document.getElementById("canvas");
            const btnWebcam = document.getElementById("btnwebcam");
            const btnCaptura = document.getElementById("btncaptura");
        
            if (!cameraOn) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => {
                        videoStream = stream;
                        video.srcObject = stream;
                        video.style.display = "block";
                        canvas.style.display = "none"; // Hide captured image
                        btnCaptura.style.display = "inline-block";
                        btnWebcam.classList.remove("btn-primary");
                        btnWebcam.classList.add("btn-danger");
                        btnWebcam.innerHTML = '<i class="fa fa-times"></i> Desabilitar Webcam';
                        cameraOn = true;
                    })
                    .catch(error => alert("Erro ao acessar webcam: " + error));
            } else {
                stopVideoStream();
                video.style.display = "none";
                btnCaptura.style.display = "none";
                btnWebcam.classList.remove("btn-danger");
                btnWebcam.classList.add("btn-primary");
                btnWebcam.innerHTML = '<i class="fa fa-camera"></i> Habilitar Webcam';
                cameraOn = false;
            }
            return false;
        }
        
        function stopVideoStream() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
        }
        
        async function capturaWebcam() {
            if (!cameraOn) {
                alert('Webcam não habilitada.');
                return false;
            }
            
            const canvas = document.getElementById("canvas");
            const video = document.getElementById("video");
            const ctx = canvas.getContext("2d");
        
            // Calculate height while maintaining aspect ratio
            const aspectRatio = video.videoHeight / video.videoWidth;
            canvas.width = 300;
            canvas.height = 300 * aspectRatio;
        
            // Draw the image
            ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas.width, canvas.height);
            
            const imgData = canvas.toDataURL("image/png");
            document.getElementById("image_webcam").value = imgData;
        
            // Stop webcam & show captured image
            stopVideoStream();
            video.style.display = "none";
            canvas.style.display = "block"; 
        
            // Reset webcam button
            const btnWebcam = document.getElementById("btnwebcam");
            btnWebcam.classList.remove("btn-danger");
            btnWebcam.classList.add("btn-primary");
            btnWebcam.innerHTML = '<i class="fa fa-camera"></i> Habilitar Webcam';
            cameraOn = false;
        
            // Generate face descriptor
            let image = await faceapi.fetchImage(imgData);
            let detection = await faceapi.detectSingleFace(image, new faceapi.SsdMobilenetv1Options())
                .withFaceLandmarks()
                .withFaceDescriptor();
        
            if (!detection) {
                alert("Nenhum rosto detectado! Tente novamente.");
                return;
            }
        
            // const descriptor = Array.from(detection.descriptor); // Convert to array
            const descriptor = detection.descriptor; // Convert to array

            // Send descriptor to the server for identification
            console.log("Sending Descriptor:", descriptor);
            fetch("identify_face.php", {
                method: "POST",
                body: JSON.stringify({descriptors: descriptor  }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Identificado: ${data.nome} (Matrícula: ${data.matricula})`);
                } else {
                    alert("Nenhuma correspondência encontrada.");
                }
            })
            .catch(error => console.error("Erro ao identificar:", error));
        
            return false;
            
            console.log("Descriptor:", detection.descriptor);
        
            // alert("Imagem capturada com sucesso!");
            return false;
        }
        document.addEventListener("DOMContentLoaded", loadModels);
    </script>

</body>
</html>
