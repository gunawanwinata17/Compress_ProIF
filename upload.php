<?php
require './Connection/db.php';

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
            header('Location: index.php?success=true');
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