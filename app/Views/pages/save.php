<?php
$data = explode(",", $_POST["img"]);
$data = base64_decode($data[1]);

$file = fopen("kwitansi.png", "w");
fwrite($file, $data);
fclose($file);
echo "OK";
