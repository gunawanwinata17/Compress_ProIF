<?php
require('db.php');

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "select * from db where status = 0";

$result = $conn->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $fileName = $row['fileName'];
        $fileID = $row['id'];

        // Ubah status menjadi 2 (sedang diproses)
        $queryUpdate = "update db set status = 2 where fileName = ? AND id = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("si", $fileName, $fileID);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Inisialisasi file mentahan dan nama output file setelah kompres berhasil
        $rawFile = '/home/gunawan/proif/Compress_ProIF/uploads/' . $fileName;
        $compressedFile = '/home/gunawan/proif/Compress_ProIF/uploads/compressed_' . $fileID . '_' . $fileName;
        $ffmpegPath = '/usr/bin/ffmpeg';
        $ffmpegCommand = $ffmpegPath . " -i "  . escapeshellarg($rawFile) .  " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);

        //filePath tanpa cron job
        // $rawFile = 'uploads/' . $fileName;
        // $compressedFile = 'uploads/compressed_' . $fileID . '_' . $fileName;
        // $ffmpegCommand = "ffmpeg -i "  . escapeshellarg($rawFile) .  " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);

        ob_start();
        system("$ffmpegCommand 2>&1", $returnCode); 
        $output = ob_get_contents();
        ob_end_clean();

        //ngecek error cron tab
        // $logFile = '/home/gunawan/proif/Compress_ProIF/logfile.log';
        // file_put_contents($logFile, $output, FILE_APPEND);

        echo $returnCode;

        if ($returnCode === 0){
            $query = "update db set status = 1 where fileName = ? AND id = ?";//berhasil
        }
        else {
            $query = "update db set status = -1 where fileName = ? AND id = ?";//gagal
            echo "Error: " . $output ;
        }
        
            //exec query nya
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $fileName, $fileID);
        $stmt->execute();
        $stmt->close();
    }
} else {
    echo "no processing file";
}

// Menutup koneksi
$conn->close();
?>
