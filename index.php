<?php 

//target direktori file yg diupload pada server 
$targetDir = "uploads/" ;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //mengambil detail dari file yg diupload
    $file = $_FILES['video'];
    $targetFile = $targetDir . basename($file['name']) ;

    //periksa apakah file yg diupload dalam format video MP4
    $fileType = mime_content_type($file['tmp_name']) ;
    $allowedType = 'video/mp4' ;

    if($fileType !== $allowedType) {
        die("Only .mp4 video files are allowed.") ;
    }

    //periksa apakah terjadi error saat upload video
    if($file['error'] !== UPLOAD_ERR_OK) {
        die("Error occurred while uploading the file.") ;
    }

    //pindahkan file yg diupload ke direktori target
    if(move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo "The file " . htmlspecialchars($file['name']) . " has been uploaded successfully." ;
    }else{
        echo "Sorry there was an error uploading your file." ;
    }

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

        .logo{
            width: 50%;
            height: 50%;
        }
    </style>
</head>
<body>
    <div class="container" id="dropContainer">
        <img src="LogoInformatika.png" class="logo">
        <h1>Compress Video Here</h1>
        
        <!-- form untuk upload file video -->
        <form id="uploadForm" action="index.php" method="post" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <p class="drag-drop-text">or drop a file here</p>
            <br>
            <input type="submit" value="Upload Video">
        </form>
    </div>

    <script>
        // Mengambil elemen container dan input file
        const container = document.getElementById('dropContainer');
        const fileInput = document.getElementById('videoUpload');

        // Mencegah perilaku default untuk peristiwa drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Menambahkan kelas saat file sedang diseret ke dalam container
        ['dragenter', 'dragover'].forEach(eventName => {
            container.addEventListener(eventName, () => {
                container.classList.add('dragover');
            });
        });

        // Menghapus kelas saat file tidak lagi berada di atas container
        ['dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, () => {
                container.classList.remove('dragover');
            });
        });

        // Menangani peristiwa drop dan tetapkan file ke input
        container.addEventListener('drop', (e) => {
            const droppedFiles = e.dataTransfer.files;
            if (droppedFiles.length) {
                fileInput.files = droppedFiles;
            }
        });
    </script>
</body>
</html>

