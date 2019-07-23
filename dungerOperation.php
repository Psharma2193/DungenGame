<?php

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

function getMovmentData(){
	$data = array();
	$file1 = fopen("DungenFiles/csv/movement.csv","r");
    $key=fgetcsv($file1);
    while(!feof($file1)){
        $file=fgetcsv($file1);
        if(!feof($file1)) {
            $data[$file[1]]["h"] = $file[2];
            $data[$file[1]]["r"] = $file[3];
            $data[$file[1]]["c"] = $file[4];
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
	print_r($data);
}

function movement($movementCommand, $height, $row, $column){
	$roomConfig = getRoomConfiguration();
	$users = initializeUsers();
	$roomMappingData = getRoomPersonMappingdata($roomConfig, $users);
	$movementDetails = getMovmentData();
	
	$data = array();
	$data["success"] = false;
	if(!isset($movementDetails[$movementCommand])){
		$data["message"] = "Invalid Command !!";
	} else{
		$newHeight = $height + $movementDetails[$movementCommand]["h"];
		$newRow  = $row + $movementDetails[$movementCommand]["r"];
		$newColumn = $column + $movementDetails[$movementCommand]["c"];
		if( $newHeight > -1 && $newHeight > 3 && $newRow > -1 && $newRow > 3 && $newColumn > -1 && $newColumn > 3 ) {
			if($roomConfig[$newHeight][$newRow][$newColumn]["roomProperty"] == "tansparent"){
				$data["success"] = true;
				$data["h"] = $newHeight;
				$data["r"] = $newRow;
				$data["c"] = $newColumn;
				$data["roomId"] = $roomConfig[$newHeight][$newRow][$newColumn]["roomId"];
				$data["capacity"] = $roomConfig[$newHeight][$newRow][$newColumn]["capacity"];
				$data["peopleCount"] = isset($roomMappingData[$data["roomId"]]) ? count($roomMappingData[$data["roomId"]]) : 0;
				$data["message"] = "Room Changed";
			} else {
				$data["message"] = "The Room is Solid. Sorry, cannot move in.";	
			}
			
		} else {
			$data["message"] = "Room cannot be changes";
		}
	}
}

$roomConfig = getRoomConfiguration();

$users = initializeUsers();

$roomMappingData = getRoomPersonMappingdata($roomConfig, $users);

$initialHeight = 0;
$initialRow = 0;
$initialColumn = 0; 

$roomId = $roomConfig[$initialHeight][$initialRow][$initialColumn]["roomId"];

$currentUser = 1;