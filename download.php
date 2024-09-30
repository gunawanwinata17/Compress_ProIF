<!DOCTYPE html>
<html>
<head>
    <title>Compression Result</title>
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

        .logo {
            width: 50%;
            height: 50%;
        }

        .button {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="LogoInformatika.png" class="logo">
        <h1>Compression Result</h1>

        <!-- tombol download -->
        <?php if (isset($_GET['file'])): ?>
            <button class="button" id="downloadButton">Download</button>
        <?php else: ?>
            <p class="error-message">File not found.</p>
        <?php endif; ?>
    </div>

    <script>
        // tambahkan event listener pada tombol download
        document.getElementById('downloadButton').addEventListener('click', function () {
            // dapatkan nama file dari query parameter
            const fileName = '<?php echo $_GET['file']; ?>';

            // buat link download
            const downloadLink = document.createElement('a');
            downloadLink.href = 'uploads/' + fileName;
            downloadLink.download = fileName;
            // simulasikan klik pada link download
            downloadLink.click();
        });
    </script>
</body>
</html>
