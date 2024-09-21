<?php
$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "compress";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// SQL untuk membuat tabel
$sql = "CREATE TABLE Files (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Filename VARCHAR(255) NOT NULL,
    Status ENUM('pending', 'completed', 'failed') NOT NULL
)";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    echo "Tabel 'Files' berhasil dibuat";
} else {
    echo "Error membuat tabel: " . $conn->error;
}

// Menutup koneksi
$conn->close();
?>
