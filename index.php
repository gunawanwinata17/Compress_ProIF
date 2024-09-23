<?php
//coba kode lama
//target direktori file yg diupload pada server 
$targetDir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //mengambil detail dari file yg diupload
    $file = $_FILES['video'];
    $targetFile = $targetDir . basename($file['name']);

    //file yg sudah dicompressed
    $compressedFile = $targetDir . 'compressed_' . basename($file['name']);

    //periksa apakah file yg diupload dalam format video MP4
    $allowedType = 'video/mp4';

    //inisialisasi response
    $response = ['status' => '', 'message' => ''];

    if ($file["type"] !== $allowedType) {
        $response['status'] = 'error';
        $response['message'] = "Only .mp4 video files are allowed.";
        echo json_encode($response);
        exit;
    }

    //periksa apakah terjadi error saat upload video
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['status'] = 'error';
        $response['message'] = "Error occurred while uploading the file.";
        echo json_encode($response);
        exit;
    }

    //pindahkan file yg diupload ke direktori target
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {

        $ffmpegCommand = "ffmpeg -i " . escapeshellarg($targetFile) . " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);


        ob_start();
        system($ffmpegCommand, $returnCode);
        ob_end_clean();

        if ($returnCode === 0) {
            $response['status'] = 'success';
            $response['message'] = "The file " . htmlspecialchars($file['name']) . " has been uploaded successfully.";
            $response['compressed_file'] = $compressedFile;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Video upload was succesful, but compression failed.";
        }

    } else {
        $response['status'] = 'error';
        $response['message'] = "Sorry, there was an error uploading your file.";
    }

    //mengirim response sebagai JSON
    echo json_encode($response);

    exit;
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Video Compressor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            transition: background-color 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container.dragover {
            background-color: #e3f2fd;
        }

        .container h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: bold;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .logo {
            width: 50%;
            height: 50%;
        }

        .button {
            display: none;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error-message,
        .success-message {
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container" id="dropContainer">
        <img src="LogoInformatika.png" class="logo">
        <h1>Compress Video Here</h1>

        <!-- form untuk upload file video -->
        <form id="uploadForm" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <p class="drag-drop-text">or drop a file here</p>
            <br>
            <input type="submit" value="Upload Video">
        </form>

        <!-- pesan hasil upload -->
        <p id="message" class="success-message"></p>

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

        // menangani pengiriman form
        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();


            const formData = new FormData(uploadForm);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        message.textContent = data.message;
                        message.classList.remove('error-message');
                        message.classList.add('success-message');
                        actionButton.style.display = 'block'; // tampilkan tombol setelah upload sukses
                    } else {
                        message.textContent = data.message;
                        message.classList.remove('success-message');
                        message.classList.add('error-message');
                        actionButton.onclick = function () {
                            if (data.compressed_file) {
                                window.location.href = data.compressed_file; // ketika di klik maka melakukan download data
                            } else {
                                console.error("No compressed file found");
                            }
                        };
                    }
                })
                .catch(error => {
                    message.textContent = "There was an error during the upload.";
                    message.classList.add('error-message');
                    actionButton.style.display = 'block';
                });
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