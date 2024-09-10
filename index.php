<!DOCTYPE html>
<html>
    <head>
        <title>Video Compressor</title>
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

            input[type="file"] {
                margin-bottom: 20px;
            }

            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            p {
                margin-bottom: 30px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php echo '<h1>Compress Video Here</h1>'; ?>
            
            <!-- form untuk upload file video -->
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="videoUpload">Upload Video (.mp4):</label>
                <input type="file" name="video" id="videoUpload" accept=".mp4" required>
                <br>
                <input type="submit" value="Upload Video">
            </form>
        </div>
    </body>
</html>
