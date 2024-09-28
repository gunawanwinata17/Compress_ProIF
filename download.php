<?php
require './Connection/db.php';
// dapatkan nama file yang akan diunduh
$fileName = $_GET['file'];

// dapatkan path file yang akan diunduh
$filePath = 'uploads/' . $fileName;

// periksa apakah file ada
if (file_exists($filePath)) {
    // kirimkan file ke pengguna
    header('Content-Type: video/mp4');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    readfile($filePath);
} else {
    // kirimkan pesan error jika file tidak ada
    header('404 Not Found');
    echo 'File tidak ditemukan.';
}
?>
