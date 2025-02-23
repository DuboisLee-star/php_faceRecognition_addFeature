include 'config.php';
<!DOCTYPE html>

<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captura Facial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark">
    <div class="container text-center mt-5">
        <h2>📷 Capturar Imagem da Webcam</h2>
        <video id="video" width="400" autoplay></video>
        <canvas id="canvas" style="display:none;"></canvas>
        <button id="capture" class="btn btn-danger mt-3">Capturar</button>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => { video.srcObject = stream; })
            .catch(err => console.error("Erro ao acessar a webcam:", err));

        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');

            fetch('processar_webcam.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: imageData })
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .catch(err => console.error(err));
        });
    </script>
</body>
</html>
