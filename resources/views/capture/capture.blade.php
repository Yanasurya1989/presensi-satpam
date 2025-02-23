<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webcam Capture</title>
    <style>
        #webcam {
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <h1>Capture Photo from Webcam</h1>
    <video id="webcam" width="640" height="480" autoplay></video>
    <button id="captureBtn">Capture</button>
    <canvas id="canvas" style="display:none;"></canvas>
    <form id="uploadForm" method="POST" action="/capture">
        @csrf
        <input type="hidden" id="imageData" name="image">
        <button type="submit">Save Photo</button>
    </form>

    <script>
        // Akses webcam
        const webcam = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const captureBtn = document.getElementById('captureBtn');
        const imageDataField = document.getElementById('imageData');
        const uploadForm = document.getElementById('uploadForm');

        // Akses media device untuk webcam
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                webcam.srcObject = stream;
            })
            .catch((err) => {
                console.error("Error accessing webcam: ", err);
            });

        // Tangkap gambar saat tombol Capture ditekan
        captureBtn.addEventListener('click', () => {
            context.drawImage(webcam, 0, 0, canvas.width, canvas.height);
            const imageDataUrl = canvas.toDataURL('image/png');
            imageDataField.value = imageDataUrl;
            uploadForm.submit();
        });
    </script>
</body>

</html>
