<?php 

//target direktori file yg diupload pada server 
$targetDir = "uploads/" ;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //mengambil detail dari file yg diupload
    $file = $_FILES['video'];
    $targetFile = $targetDir . basename($file['name']) ;

    //periksa apakah file yg diupload dalam format video MP4
    $fileType = mime_content_type($file['tmp_name']) ;
    $allowedType = 'vdeo/mp4' ;

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
        <title>Video Uploaded Successfully</title>
    </head>
    <body>
        <div class="container">
        </div>
    </body>
</html>