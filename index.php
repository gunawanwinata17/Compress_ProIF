<?php
include('run.php');

// Direktori target file yang diupload di server lokal
$targetDir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil detail dari file yang diupload
    if (isset($_FILES['video'])) {
        $file = $_FILES['video'];
        $targetFile = $targetDir . basename($file['name']);

        // Periksa apakah file yang diupload dalam format video MP4
        $fileType = mime_content_type($file['tmp_name']);
        $allowedType = 'video/mp4';

        if ($fileType !== $allowedType) {
            die("Only .mp4 video files are allowed.");
        }

        // Periksa apakah terjadi error saat upload video
        if ($file['error'] !== UPLOAD_ERR_OK) {
            die("Error occurred while uploading the file.");
        }

        // Pindahkan file yang diupload ke direktori target
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            echo "The file " . htmlspecialchars($file['name']) . " has been uploaded successfully.<br>";

            // Jalankan fungsi untuk mengupload dan mengompres file di server remote
            runCompress($targetFile);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Menjalankan perintah 'ls -l' jika tombol 'run_command' ditekan
    if (isset($_POST['run_command'])) {
        $command = 'ls -l';
        $output = shell_exec($command);
        $error = error_get_last();

        echo "<h3>Command Output:</h3>";
        echo "<pre>$output</pre>";
        
        if ($error) {
            echo "<h3>Error:</h3>";
            echo "<pre>" . print_r($error, true) . "</pre>";
        }
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

        p {
            margin-bottom: 30px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php echo '<h1>Compress Video Here</h1>'; ?>
        
        <!-- form untuk upload file video -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <br>
            <input type="submit" value="Upload Video">
        </form>

        <!-- form untuk menjalankan perintah 'ls -l' -->
        <form method="post">
            <button type="submit" name="run_command">Run "ls" Command</button>
        </form>
    </div>
</body>
</html>
