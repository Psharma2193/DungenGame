<?php
$url = "18.191.86.105/dungenMessage.php?messageCommand=". $_POST['messageCommand'];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$data = json_decode($result, true);

header('Content-type: application/json');
echo json_encode( $data );