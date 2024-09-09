<!DOCTYPE html>
<html>
    <head>
        <title>Video Compressor</title>
    </head>
    <body>
        <?php echo '<p>Compress Video Here</p>'; ?>
        
        <!-- Form untuk upload file video -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="videoUpload">Upload Video (.mp4):</label>
            <input type="file" name="video" id="videoUpload" accept=".mp4" required>
            <br><br>
            <input type="submit" value="Upload Video">
        </form>
    </body>
</html>
