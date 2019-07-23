<?php
$url = "18.191.86.105/dungenMovement.php?movementCommand=". $_POST['movementCommand'] ."&height=". $_POST['height'] ."&row=". $_POST['row']."&column=". $_POST['column'];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$data = json_decode($result, true);

header('Content-type: application/json');
echo json_encode( $data );