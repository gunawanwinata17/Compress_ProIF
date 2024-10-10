<?php
require('db.php') ;

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "select * from db where status = 0" ;

$result = $conn->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $fileName = $row['fileName'];
        $fileID = $row['id'];

        //inisialisasi file mentahan dan nama output file setelah kompress berhasil
        $rawFile = 'uploads/' . $fileName;
        $compressedFile = 'uploads/compressed_' . $fileName . $fileID;
        $ffmpegCommand = "ffmpeg -i " . escapeshellarg($rawFile) .  " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);
        
        ob_start();
        system($ffmpegCommand, $returnCode);
        $output = ob_get_contents() ;
        ob_end_clean();

        if ($returnCode === 0)
            $query = "update db set status = 1 where fileName = ?";//berhasil
        else
            $query = "update db set status = -1 where fileName = ?";//gagal
            echo "Error: " . $output ;
        
            //exec query nya
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $stmt->close();

    }
}else{
    echo "no processing file" ;
}

// Menutup koneksi
$conn->close();
?>
