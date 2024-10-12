<?php
require('db.php');

//target direktori file yang diupload pada server
$targetDir = "uploads/";

//mengambil detail dari file yang diupload
$file = $_FILES['video'];
$targetFile = $targetDir . basename($file['name']);
$uploadOk = 1;

//periksa apakah file yang diupload dalam format video MP4
$allowedType = 'video/mp4';

//periksa apakah terjadi error saat upload video
if (isset($_POST["submit"])) {

    if ($file["type"] !== $allowedType) {
        $uploadOk = 0;
        header('Location: index.php?error=type');
        exit;
    }
    
} else {
    $uploadOk = 0;
    header('Location: index.php?error=no_file');
    exit;
}

if ($uploadOk == 0) {
    header('Location: index.php?error=upload_failed');
    exit;
} else {
    //pindahkan file yang diupload ke direktori target
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            header('Location: index.php?error=db');
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

            //ambil id dari file yg di-insert ke db
            $lastInsertedId = $conn->insert_id ;

            //redirect ke status.php dgn id file yg didapat
            header('Location: status.php?id=' . $lastInsertedId);
        } else {
            header('Location: index.php?error=db_insert');
        }

        $stmt->close();
        $conn->close();

    } else {
        header('Location: index.php?error=move_failed');
    }
}


    

?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <!-- <style>
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
        </style> -->
    </head>
    <body>
        <!-- <div class="container">
            <img src="LogoInformatika.png" class="logo">
            <p id="message" class="success-message"></p>
        </div> -->
    </body>
</html>