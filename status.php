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
$sql = "SELECT * FROM db WHERE id = ?";

$stmt = $conn->prepare($sql);

// bind parameter
$stmt->bind_param("i", $id_file);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if ($data['status'] == 1) {
        // Jika status = 1, artinya succes & tampilkan link download

        $message = "File berhasil diupload dan dikompresi" ;
        $statusClass = "success" ;
        $filename = $data['fileName'] ;
        header('Location: download.php?file=' . urldecode($filename));
    } elseif ($data['status'] == -1) {
        // Jika status = -1 artinya kompresi gagal. Tampilkan "kompresi gagal"
        $message = "Kompresi gagal. Silakan coba lagi.";
        $statusClass = "failed" ;
    } elseif ($data['status'] == 0) {
        // Jika status = 0, artinya masih processing & tampilkan "mohon tunggu"
        $message = "Mohon tunggu. File sedang diproses.";
        $statusClass = "waiting" ;
        // Auto refresh dalam 30 dtk
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<html>
<!DOCTYPE html>
<head>
    <title>Video Compressor</title>
    <meta http-equiv="refresh" content="10" >
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            transition: background-color 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: bold;
        }

        .logo {
            width: 50%;
            height: 50%;
        }

        .error-message,
        .success-message {
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
        }

        .success {
            color: green;
        }

        .failed {
            color: red;
        }

        .waiting {
            color: blue;
        }

    </style>
</head>

<body>
    <div class="container" id="dropContainer">
        <img src="LogoInformatika.png" class="logo">
        <h1>Status</h1>
        <p id=statusMessage></p>
        
    </div>

    <script>
        
        const statusMessage = document.getElementById("statusMessage");
        const message = "<?php echo $message;?>" ;
        const statusClass = "<?php echo $statusClass; ?>" ;

        statusMessage.classList.add(statusClass) ;
        statusMessage.innerHTML = message ;


    </script>
</body>

</html>
