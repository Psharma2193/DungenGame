<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<div class="container">
  		<div class="jumbotron" style="font-size: 40px;background-color: #FF0000;">
  			Multiuser Dungen
  		</div>
  	</div>
  	<h3>Current Room Details</h3>
  	<table class="table">
  		<thead>
    		<tr>
      			<th scope="col">Room Level</th>
      			<th scope="col">Room Row</th>
      			<th scope="col">Room Column</th>
      			<th scope="col">Room Capacity</th>
    		</tr>
  		</thead>
  		<?php 
  			$filename = "DungenFiles/txt/currentUser.txt";
  			$handle = fopen($filename, "r") ;
  			$contents = fread($handle, filesize($filename));
			$currentRoomData = json_decode($contents, true);		
			$htmlString = "";
			$htmlString = $htmlString . '<tr>';
			$htmlString = $htmlString . '<td scope="col">' . $currentRoomData["h"] . '</td>';
			$htmlString = $htmlString . '<td scope="col">' . $currentRoomData["r"] . '</td>';
			$htmlString = $htmlString . '<td scope="col">' . $currentRoomData["c"] . '</td>';
			$htmlString = $htmlString . '<td scope="col">' . $currentRoomData["capacity"] . '</td>';
			$htmlString = $htmlString . '</tr>';
			fclose($myfile);
			echo $htmlString;
  		?>
  	</table>

  	<h3>User Details</h3>
  	<table class="table">
  		<thead>
    		<tr>
      			<th scope="col">User Id</th>
      			<th scope="col">User Name</th>
      			<th scope="col">Sent Messages</th>
      			<th scope="col">Received Messages</th>
    		</tr>
  		</thead>
  		<?php 
  			$filename = "DungenFiles/txt/messageData.txt";
  			$handle = fopen($filename, "r") ;
  			$contents = fread($handle, filesize($filename));
			$messageData = json_decode($contents, true);
  			$htmlString = "";
  			foreach ($messageData as $key => $value) {
  				$htmlString = $htmlString . '<tr>';
  				$htmlString = $htmlString . '<td scope="col">' . $key . '</td>';
  				$htmlString = $htmlString . '<td scope="col">'.  $value["name"]  . '</td>';
  				if(!is_array($value["sendMessage"]))
  					$htmlString = $htmlString . '<td scope="col">NA</td>';
  				else{
  					$htmlString = $htmlString . '<td scope="col"><ul>';
  					foreach ($value["sendMessage"] as $key => $valueData) {
  						$htmlString = $htmlString . '<li>' . $valueData .'</li>';
  					}
  					$htmlString = $htmlString . '</ul></td>';
  				}
  				if(!is_array($value["receivedMessage"]))
  					$htmlString = $htmlString . '<td scope="col">NA</td>';
  				else{
  					$htmlString = $htmlString . '<td scope="col"><ul>';
  					foreach ($value["receivedMessage"] as $key => $valueData) {
  						$htmlString = $htmlString . '<li>' . $valueData .'</li>';
  					}
  					$htmlString = $htmlString . '</ul></td>';
  				}
  				$htmlString = $htmlString . '</tr>';
  			}
  			echo $htmlString;
  		?>
	</table>
</body>
</html>