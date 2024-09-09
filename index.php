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
        <title>Video Compressor</title>
    </head>
    <body>
        <?php echo '<p>Compress Video Here</p>'; ?>
        
        <!-- Form untuk upload file video -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <br><br>
            <input type="submit" value="Upload Video">
        </form>
    </body>
</html>
