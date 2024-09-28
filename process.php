<?php
require './Connection/db.php';

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "select * from db where status = 0";

$result = $conn->query($sql);

if($result->num.rows > 0) {
    while($row = $result->fetch_assoc()) {

    }
}else{
    echo "no processing file";
}

// Menutup koneksi
$conn->close();
?>
