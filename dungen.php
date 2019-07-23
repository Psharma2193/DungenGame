<?php 
setcookie('CurrentRoomId',1);
setcookie('CurrentRoomHeight',1);
setcookie('CurrentRoomRow',1);
setcookie('CurrentRoomColumn',1);
setcookie('CurrentRoomCapacity',10);
setcookie('CurrentRoomProperties', 'transparent');
setcookie('CurrentRoomPeople', 1);
?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<script type="text/javascript">

  		function directionCommand(){
  			var command = document.getElementById('inputState').value;
  			
  			$.ajax({
              type: "POST",
              url: "dungenDirection.php",
              data: {movementCommand:command, height: "<?php echo $_COOKIE['CurrentRoomHeight']; ?>", row:"<?php echo $_COOKIE['CurrentRoomRow']; ?>", column:"<?php echo $_COOKIE['CurrentRoomColumn']; ?>"},
              dataType:'JSON', 
              success: function(response){
                  console.log(response);
                  if(response) {
                    	
                  }
              }
        });
  		}

      function sendMessage(){
          var command = document.getElementById('messageCommand').value;
          var res = command.replace(/ /g, "%20");
          $.ajax({
              type: "POST",
              url: "dungenMessagenger.php",
              data: {messageCommand:res},
              dataType:'JSON', 
              success: function(response){
                  console.log(response);
                  if(response) {
                      
                  }
              }
        });
      }


  		function initialConfiguration(){
  			disableErrorMessage();
  		}
  		function enableErrorMessage(){
  			document.getElementById('errorMessage').style.display = 'inline';
  		}
  	</script>
</head>
<body>
	<div class="container">
  		<div class="jumbotron" style="font-size: 40px;background-color: #FF0000;">
  			Multiuser Dungen
  		</div>
  	</div>

  	<center>
  		
	<table>
		<caption><h4>Operations</h4></caption>
		<tr>
			<td width="40%">
				<form>
	    			<div class="form-group">
	      				<label for="inputState">Direction</label>
	      			<select id="inputState" class="form-control">
		        		<option selected>Choose...</option>
		        		<option value="north">North</option>
		        		<option value="east">East</option>
		        		<option value="west">West</option>
		        		<option value="south">South</option>
		        		<option value="up">Up</option>
		        		<option value="down">Down</option>
	      			</select>
	    			</div>
	    			<div class="form-group">
	      				<button onClick="directionCommand()" class="btn btn-primary">Hit Me</button>
	    			</div>
				</form>		
			</td>
			<td width="20%">
			</td>
			<td  width="40%">
				<form>
  					<div  class="form-group">
    			<label for="messageCommand">Message Command</label>
    			<input type="text" class="form-control" id="messageCommand" placeholder="Enter the command here">
  			</div>
  			<div class="form-group">
    			<button onclick="sendMessage()" class="btn btn-primary">Run</button>
  			</div>
		</form>
			</td>
		</tr>
	</table>
		</center>

	

	<h3>All Room Details</h3>
  	<table class="table">
  		<thead>
    		<tr>
      			<th scope="col">Room Id</th>
      			<th scope="col">Room Level</th>
      			<th scope="col">Room Row</th>
      			<th scope="col">Room Column</th>
      			<th scope="col">Room Capacity</th>
      			<th scope="col">Room Properties</th>
      			<th scope="col">Number of people in room</th>
    		</tr>
  		</thead>
			<?php 
				function getRoomConfiguration(){
    				$data = array();
    				$file1 = fopen("DungenFiles/csv/Room.csv","r");
    				$key=fgetcsv($file1);
    				while(!feof($file1)){
        				$file=fgetcsv($file1);
        				if(!feof($file1)) {
        					$data[$file[4]]["h"] = $file[1];
        					$data[$file[4]]["r"] = $file[2];
        					$data[$file[4]]["c"] = $file[3];
        					$data[$file[4]]["roomProperty"] = $file[5];
        					$data[$file[4]]["capacity"] = $file[6];
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
        					$data[$file[1]]["userId"] = $file[1];
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

				function getRoomDetails(){
					$roomConfig = getRoomConfiguration();
					$users = initializeUsers();
					$roomMapping  = getRoomPersonMappingdata($roomConfig, $users);
					foreach ($roomConfig as $key => $value) {
						if(isset($roomMapping[$key])){
							$roomConfig[$key]["people"] = count($roomMapping[$key]);	
						} else{
							$roomConfig[$key]["people"] = 0;
						}
						
					}
					return $roomConfig;
				}
				$roomDetails = getRoomDetails();
				$htmlString = '';
				foreach ($roomDetails as $key => $value) {
					$htmlString = $htmlString . '<tr>';
					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $key;
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["h"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["r"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["c"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["capacity"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["roomProperty"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '<td scope="col" id="roomId">';
					$htmlString = $htmlString . $value["people"];
					$htmlString = $htmlString . '</td>';

					$htmlString = $htmlString . '</tr>';
				}
				echo $htmlString;
			?>
	</table>

	<h3>User Details</h3>
  	<table class="table">
  		<thead>
    		<tr>
      			<th scope="col">User Id</th>
      			<th scope="col">User Name</th>
    		</tr>
  		</thead>
  		<?php 
  			$users = initializeUsers();
  			$htmlString = "";
  			foreach ($users as $key => $value) {
  				$htmlString = $htmlString . '<tr>';
  				$htmlString = $htmlString . '<td scope="col">' . $value["userId"] . '</td>';
  				$htmlString = $htmlString . '<td scope="col">'.  $value["name"]  . '</td>';
  				$htmlString = $htmlString . '</tr>';
  			}
  			echo $htmlString;
  		?>
	</table>
</body>
</html>