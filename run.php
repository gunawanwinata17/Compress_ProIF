<?php

$file = $_FILES['video'];
$ssh = "ssh gunawan@compress.ifunpar.id";
$password = "UNPAR123"; //harus disetting lagi agar aman
//$compressedFile

//encode h.265
//$runCompress = shell_exec("ffmpeg -i $file -vcodec libx265" $compressedFile)

?>