<?php
require './Connection/db.php';
require 'download.php';
require 'process.php';
require 'upload.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Video Compressor</title>
    <link rel="stylesheet" href="/Assets/css/style.css">
    <link rel="stylesheet" href="/Assets/images/LogoInformatika.png">
</head>

<body>
    <div class="container" id="dropContainer">
        <img src="LogoInformatika.png" class="logo">
        <h1>Compress Video Here</h1>

        <!-- form untuk upload file video -->
        <form action="upload.php" id="uploadForm" method="post" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <p class="drag-drop-text">or drop a file here</p>
            <br>
            <input type="submit" value="Upload Video" name="submit">
        </form>

        <!-- pesan hasil upload -->
        <?php if (isset($_GET['success'])): ?>
            <p id="message" class="success-message">File successfully uploaded.</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p id="message" class="error-message">Failed to upload file.</p>
        <?php endif; ?>

        <!-- tombol akan muncul setelah upload -->
        <button id="actionButton" class="button">Download</button>
    </div>

    <script>
        // mengambil elemen container dan input file
        const container = document.getElementById('dropContainer');
        const fileInput = document.getElementById('videoUpload');
        const uploadForm = document.getElementById('uploadForm');
        const message = document.getElementById('message');
        const actionButton = document.getElementById('actionButton');

        // mencegah perilaku default untuk peristiwa drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // menambahkan kelas saat file sedang diseret ke dalam container
        ['dragenter', 'dragover'].forEach(eventName => {
            container.addEventListener(eventName, () => {
                container.classList.add('dragover');
            });
        });

        // menghapus kelas saat file tidak lagi berada di atas container
        ['dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, () => {
                container.classList.remove('dragover');
            });
        });

        // menangani peristiwa drop dan tetapkan file ke input
        container.addEventListener('drop', (e) => {
            const droppedFiles = e.dataTransfer.files;
            if (droppedFiles.length) {
                fileInput.files = droppedFiles;
            }
        });

        // tambahkan event listener pada tombol download
        actionButton.addEventListener('click', function () {
            // dapatkan nama file yang telah diupload
            const fileName = fileInput.files[0].name;

            // dapatkan nama file yang telah dikompresi
            const compressedFileName = 'compressed_' + fileName;

            // buat link download
            const downloadLink = document.createElement('a');
            downloadLink.href = 'download.php?file=' + compressedFileName;
            downloadLink.download = compressedFileName;

            // simulasikan klik pada link download
            downloadLink.click();
        });
    </script>
</body>

</html>