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

    $sql = 'SELECT 
                tab_membros.id AS id, 
                tab_membros.matricula AS matricula, 
                tab_membros.nome AS nome, 
                tab_face_reg.face_data AS face_data
            FROM tab_membros 
            LEFT JOIN tab_face_reg ON tab_membros.matricula = tab_face_reg.matricula  
            WHERE tab_membros.id = :id;';

    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id_membro, PDO::PARAM_INT);
    $stm->execute();
    $membro = $stm->fetch(PDO::FETCH_OBJ);

    if ($membro) {
        // Decode face_data JSON safely
        $membro->face_data = !empty($membro->face_data) ? json_decode($membro->face_data, true) : [];

        // Ensure it's an array and has at least 3 elements
        $membro->face0 = isset($membro->face_data[0]) ? $membro->face_data[0] : null;
        $membro->face1 = isset($membro->face_data[1]) ? $membro->face_data[1] : null;
        $membro->face2 = isset($membro->face_data[2]) ? $membro->face_data[2] : null;
    }

    // Debug output
    // var_dump($membro);
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
                                <form action="action_regFace.php" method="post" id="form-photo">
                                    <div class="row justify-content-center">
                                        <!-- Thumbnails Frame -->
                                        <div class="col-md-8 text-center">
                                            <div style="display: flex; width: 460px; height: 150px; gap: 5px; border-radius: 10px; padding: 5px; overflow: hidden; background: #f3f3f3; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                                <?php
                                                $defaultImage = "img/padrao.png";
                                                for ($i = 0; $i < 3; $i++) {
                                                    $faceImage = isset($membro->face_data[$i]) && !empty($membro->face_data[$i]) ? $membro->face_data[$i] : $defaultImage;
                                                    ?>
                                                    <img id="face_<?php echo $i; ?>" src="<?php echo htmlspecialchars($faceImage); ?>" 
                                                        width="150" height="150" style="object-fit: cover; border-radius: 10px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <!-- Reset Button -->
                                            <button onclick="resetCapturedImages()" type="button" class="btn btn-warning mt-2">
                                                <i class="fa fa-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                
                                    <!-- Webcam & Capture Section -->
                                    <div class="row justify-content-center mt-4">
                                        <div class="col-md-6 text-center">
                                            <canvas id="canvas" class="is-hidden" style="display: none;"></canvas>
                                            <video autoplay id="video" style="width: 300px; height: 225px; display:none; border: 2px solid #333; border-radius: 10px;"></video>
                                
                                            <!-- Buttons -->
                                            <div class="mt-3">
                                                <button onclick="return habilitaWebcam()" class="btn btn-primary" type="button" id="btnwebcam">
                                                    <i class="fa fa-camera"></i> Habilitar Webcam
                                                </button>
                                                <button onclick="return capturaWebcam()" class="btn btn-success" type="button" 
                                                        id="btncaptura" style="display: none;">
                                                    <i class="fa fa-photo"></i> Capturar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Hidden Inputs for Captured Images -->
                                    <input type="hidden" name="image_webcam_0" id="image_webcam_0">
                                    <input type="hidden" name="image_webcam_1" id="image_webcam_1">
                                    <input type="hidden" name="image_webcam_2" id="image_webcam_2">
                                    <!--<input type="hidden" name="image_descriptor_0" id="image_descriptor_0">-->
                                    <!--<input type="hidden" name="image_descriptor_1" id="image_descriptor_1">-->
                                    <!--<input type="hidden" name="image_descriptor_2" id="image_descriptor_2">-->
                                    <input type="hidden" name="id" value="<?php echo $membro->id; ?>">
                                
                                    <!-- Submit Button -->
                                    <div class="row justify-content-center mt-4">
                                        <div class="col-md-6 text-center">
                                            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar Foto</button>
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
    
    <script defer src="./face_models/face-api.min.js"></script>
    <script>
        let cameraOn = false;
        let videoStream;
        let capturedImages = 0; // Track the next available slot for a new image
        const defaultImage = "img/padrao.png"; // Default image
        let sampleDescriptors = []; // Store face descriptors
        
        async function loadModels() {
            try {
                await faceapi.nets.ssdMobilenetv1.loadFromUri('./models/ssd_mobilenetv1'); 
                await faceapi.nets.faceLandmark68Net.loadFromUri('./models/face_landmark_68'); 
                await faceapi.nets.faceRecognitionNet.loadFromUri('./models/face_recognition'); 
                console.log("✅ Face models loaded!");
            } catch (error) {
                console.error("❌ Model loading error:", error);
            }
        }
        
        async function habilitaWebcam() {
            if (!cameraOn) {
                $("#btnwebcam").addClass('btn-danger').html('<i class="fa fa-camera"></i> Desabilitar Webcam');
                $("#btncaptura").fadeIn(100);
                await playVideoStream();
            } else {
                stopVideoStream();
                $("#btnwebcam").removeClass('btn-danger').html('<i class="fa fa-camera"></i> Habilitar Webcam');
                $("#btncaptura").fadeOut(100);
            }
            return false;
        }
    
        async function capturaWebcam() {
            if (!cameraOn) {
                alert('Webcam não habilitada.');
                return false;
            }
        
            // Find the first empty slot
            let availableSlot = -1;
            for (let i = 0; i < 3; i++) {
                if (!$(`#image_webcam_${i}`).val()) {
                    availableSlot = i;
                    break;
                }
            }
        
            if (availableSlot === -1) {
                alert("Você já capturou 3 imagens!");
                return false;
            }
        
            const canvas = document.querySelector("#canvas");
            const video = document.querySelector("#video");
            const ctx = canvas.getContext("2d");
        
            // Set canvas size to 150x150 for consistency
            canvas.width = 150;
            canvas.height = 150;
            ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, 150, 150);
        
            // Convert to Base64 and store in the first available slot
            let imgData = canvas.toDataURL("image/png");
            $(`#face_${availableSlot}`).attr("src", imgData);
            $(`#image_webcam_${availableSlot}`).val(imgData);
            
            capturedImages = availableSlot + 1;
            
            // Generate face descriptor
            let image = await faceapi.fetchImage(imgData);
            let detection = await faceapi.detectSingleFace(image, new faceapi.SsdMobilenetv1Options())
                .withFaceLandmarks()
                .withFaceDescriptor();
        
            if (!detection) {
                alert("No face detected! Try again.");
                return;
            }
        
            sampleDescriptors[availableSlot] = detection.descriptor;
        
            return false;
        }

    
        function stopVideoStream() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                cameraOn = false;
                $("video").fadeOut(100);
            }
        }
    
        async function playVideoStream() {
            try {
                const video = document.querySelector("#video");
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = videoStream;
                cameraOn = true;
                $("video").fadeIn(100);
            } catch (err) {
                alert("Não foi possível acessar webcam: " + err);
            }
        }
    
        // Function to reset images to default
        function resetCapturedImages() {
            capturedImages = 0; // Reset counter
            for (let i = 0; i < 3; i++) {
                $(`#face_${i}`).attr("src", defaultImage);
                $(`#image_webcam_${i}`).val("");
            }
        }
    
       document.getElementById("form-photo").addEventListener("submit", async function (event) {
            event.preventDefault();
    
            if (capturedImages < 3) {
                alert("Por favor, capture todas as 3 fotos antes de enviar.");
                return;
            }
    
            const formData = new FormData(this);
    
            // Convert descriptors array to JSON string and append it to form data
            formData.append("descriptors", JSON.stringify(sampleDescriptors));
            console.log("📤 Sending Test FormData...");
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1].substring(0, 100)}`);
            }
            
            fetch("action_regFace.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("🔍 Server Response:", data);
                alert(JSON.stringify(data, null, 2));
    
                if (data.success) {
                    window.location.href = "painel.php";
                }
            })
            .catch(error => console.error("❌ Error:", error));
        });
    
    </script>

</body>
</html>
