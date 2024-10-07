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

        // Ubah status menjadi 2 (sedang diproses)
        $queryUpdate = "update db set status = 2 where fileName = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("s", $fileName);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Inisialisasi file mentahan dan nama output file setelah kompres berhasil
        $rawFile = 'uploads/' . $fileName;
        $compressedFile = 'uploads/compressed_' . $fileName;
        $ffmpegCommand = "ffmpeg -i " . escapeshellarg($rawFile) .  " -c:v libx264 -crf 23 -preset medium -c:a aac -b:a 128k " . escapeshellarg($compressedFile);

        ob_start();
        system($ffmpegCommand, $returnCode);
        ob_end_clean();

        // Update status berdasarkan hasil kompresi
        if ($returnCode === 0) {
            $query = "update db set status = 1 where fileName = ?"; // berhasil
        } else {
            $query = "update db set status = -1 where fileName = ?"; // gagal
        }

        // Eksekusi query update status
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $stmt->close();
    }
} else {
    echo "no processing file";
}

// Menutup koneksi
$conn->close();
?>
