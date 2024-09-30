<?php
require('db.php');

// Ambil id file dari URL
$id_file = $_GET['id'];

// Cek status file
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "Error: " . $conn->connect_error;
    exit;
}

// SQL query untuk memasukkan data ke dalam database
$sql = "SELECT status FROM db WHERE id = ?";
$stmt = $conn->prepare($sql);

// bind parameter
$stmt->bind_param("i", $id_file);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['status'] == 1) {
        // Jika status = 1, artinya succes & tampilkan link download
        $link_download = "download.php?id=$id_file";
        echo "File berhasil diupload dan dikompresi. <a href='$link_download'>Download file</a>";
    } elseif ($data['status'] == -1) {
        // Jika status = -1 artinya kompresi gagal. Tampilkan "kompresi gagal"
        echo "Kompresi gagal. Silakan coba lagi.";
    } elseif ($data['status'] == 0) {
        // Jika status = 0, artinya masih processing & tampilkan "mohon tunggu"
        echo "Mohon tunggu. File sedang diproses.";
        // Auto refresh dalam 30 dtk
        header("Refresh: 30");
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
