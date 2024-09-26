<?php
require('db.php');

//target direktori file yang diupload pada server
$targetDir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //mengambil detail dari file yang diupload
    $file = $_FILES['video'];
    $targetFile = $targetDir . basename($file['name']);


    $compressedFile = $targetDir . 'compressed_' . basename($file['name']);

    //periksa apakah file yang diupload dalam format video MP4
    $allowedType = 'video/mp4';

    //inisialisasi response
    $response = ['status' => '', 'message' => ''];

    //periksa apakah terjadi error saat upload video
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {

        if ($file["type"] !== $allowedType) {
            $response['status'] = 'error';
            $response['message'] = "Only .mp4 video files are allowed.";
            echo json_encode($response);
            exit;
        }

        //pindahkan file yang diupload ke direktori target
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                $response['status'] = 'error';
                $response['message'] = "Database connection failed: ' . $conn->connect_error";
                echo json_encode($response);
                exit;
            }

            // inisialisasi nama file
            $fileName = basename($file['name']); 

            // SQL query untuk memasukkan data ke dalam database
            $sql = "INSERT INTO db (fileName, status) VALUES (?, 0)";

            $stmt = $conn->prepare($sql);

            // bind parameter
            $stmt->bind_param("s", $fileName);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Video uploaded and saved to database successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error saving file information to the database: ' . $stmt->error;
            }
        

        //$ffmpegCommand = "ffmpeg -i " . escapeshellarg($targetFile) . " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);


        // ob_start();
        // system($ffmpegCommand, $returnCode);
            // ob_end_clean();

            // if ($returnCode === 0) {
            //     $response['status'] = 'success';
            //     $response['message'] = "The file " . htmlspecialchars($file['name']) . " has been uploaded successfully.";
            //     $response['compressed_file'] = $compressedFile;
            // } else {
            //     $response['status'] = 'error';
            //     $response['message'] = "Video upload was succesful, but compression failed.";
            // }

            $stmt->close();
            $conn->close();

        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to move the uploaded file.';
        }

    } else {
        $response['status'] = 'error';
        $response['message'] = 'No file uploaded or an upload error occurred.';
    }

    echo json_encode($response) ;
    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
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
        <div class="container">
            <img src="LogoInformatika.png" class="logo">
            <p id="message" class="success-message"></p>
        </div>
    </body>
</html>