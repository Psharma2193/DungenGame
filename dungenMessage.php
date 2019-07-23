<?php

$messageCommand = $_GET['messageCommand'];
$userId = 1;

function getCurrentRoomData(){
	$filename = "DungenFiles/txt/currentUser.txt";
  	$handle = fopen($filename, "r") ;
  	$contents = fread($handle, filesize($filename));
	$currentRoomData = json_decode($contents, true);
	return array('height' => $currentRoomData['h'], 'row' => $currentRoomData['r'], 'column' => $currentRoomData['column']);
}

$currentRoomData = getCurrentRoomData();

$height = $currentRoomData['height'];
$row  = $currentRoomData['row'];
$column = $currentRoomData['column'];

function getRoomConfiguration(){
    $data = array();
    $file1 = fopen("DungenFiles/csv/Room.csv","r");
    $key=fgetcsv($file1);
    while(!feof($file1)){
        $file=fgetcsv($file1);
        if(!feof($file1)) {
            $data[$file[1]][$file[2]][$file[3]]["roomId"] = $file[4];
            $data[$file[1]][$file[2]][$file[3]]["roomProperty"] = $file[5];
            $data[$file[1]][$file[2]][$file[3]]["capacity"] = $file[6];
        }
        
    }
    fclose($file1);
    return $data;
}

function initializeUsers(){
	$data = array();
	$file1 = fopen("DungenFiles/csv/users.csv","r");
    $key=fgetcsv($file1);
    while(!feof($file1)){
        $file=fgetcsv($file1);
        if(!feof($file1)) {
            $data[$file[1]]["name"] = $file[2];
            $data[$file[1]]["roomId"] = $file[3];
        }
        
    }
    fclose($file1);
    return $data;	
}

function getRoomPersonMappingdata($roomConfig, $users){
	$data = array();
	foreach ($users as $key => $value) {
		$data[$value["roomId"]][] = $key;
	}
	return $data;
}

function updateMessagedata($data){
	$jsonData = json_encode($data);
	$fp = fopen("DungenFiles/txt/messageData.txt", 'w');
	fwrite($fp, $jsonData);
	fclose($fp);
}

function decodeCommand($userId, $messageCommand, $height, $row, $column){
	
	$roomConfig = getRoomConfiguration();
	$users = initializeUsers();
	$roomMappingData = getRoomPersonMappingdata($roomConfig, $users);
	$commandData = explode(" ", $messageCommand);

	switch($commandData[0]){
		case "say" :
			$messageInfo = implode(" ", array_slice($commandData,1,count($commandData) - 1));
			$data = say($roomConfig, $users, $roomMappingData, $userId, $height, $row, $column, $messageInfo);
			updateMessagedata($data);
			$data["success"] = true;
			break;
		case "tell" :
			$messageInfo = implode(" ", array_slice($commandData,2,count($commandData) - 2));
			$data = tell($users, $userId, $commandData[1], $messageInfo);
			updateMessagedata($data);
			$data["success"] = true;
			break;
		case "yell" :
			$messageInfo = implode(" ", array_slice($commandData,1,count($commandData) - 1));
			$data = yell($users, $userId, $messageInfo);
			updateMessagedata($data);
			$data["success"] = true;
			break;
		default :
			$data["message"] = "Error Conecting Server. Please try again later.";
			$data["success"] = false;
	}
	return $data;
}

function yell($person, $senderId, $message){
    foreach ($person as $key => $value) {
        if($senderId == $key){
            $person[$key]["sendMessage"][] = $message;    
        } else {
            $person[$key]["receivedMessage"][] = $message;
        }
    }
    return $person;
}

function say($roomConfig, $person, $personRoomMapping, $senderId, $roomH,  $roomR, $roomC, $message){
    foreach ($person as $key => $value) {
        if($senderId == $key){
            $person[$key]["sendMessage"][] = $message;    
        } else if(in_array($key, $personRoomMapping[$roomConfig[$roomH][$roomR][$roomC]["roomId"]])) {
            $person[$key]["receivedMessage"][] = $message;
        }
    }
    return $person;
}

function tell($person, $senderId, $receiverId, $message){
    $person[$senderId]["sendMessage"][] = $message;
    $person[$receiverId]["receivedMessage"][] = $message;
    return $person;
}

$data = decodeCommand($userId, $messageCommand, $height, $row, $column);

header('Content-type: application/json');
echo json_encode( $data );