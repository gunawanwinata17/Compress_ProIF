<?php
function runCompress($file)
{
    $host = 'compress.ifunpar.id';
    $user = 'gunawan';
    $password = 'UNPAR123';
    $remote_directory = '/home/gunawan/uploads/' . basename($file);
    $local_file = $file;
    
    // Perintah SSH dengan password
    $scp_command = "sshpass -p '$password' scp $local_file $user@$host:$remote_directory";
    
    // Jalankan perintah untuk meng-upload file ke server remote
    exec($scp_command, $output, $return_var);
    if ($return_var === 0) {
        echo "File uploaded to remote server successfully.<br>";

        // Jalankan ffmpeg di server remote untuk kompresi
        $ffmpeg_command = "sshpass -p '$password' ssh $user@$host 'ffmpeg -i $remote_directory -vcodec libx265 /home/gunawan/uploads/compressed_" . basename($file) . "'";
        exec($ffmpeg_command, $output, $return_var);
        
        if ($return_var === 0) {
            echo "Compression successful.<br>";
        } else {
            echo "Compression failed.<br>";
        }
    } else {
        echo "Failed to upload file to remote server.<br>";
    }
}
?>
