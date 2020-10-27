<?php
function startHTML() {//beginning tags of HTML document
	echo "
	<html>
	<head>
	<title>Project 4</title>
	</head>
	<body>
	";
}
function endHTML() {//ending tags of HTML document
	echo "
	</body>
	</html>
	";
}

//checks to make sure whichPlatform is valid
function platformCheck($jsonObject, $whichPlatform){
	$whichPlatformCheck = false;
	foreach($jsonObject as $key => $value){
		foreach($value as $key2 => $value2){
			foreach($value2 as $key3 => $value3){//gets valid whichPlatform options and compares against given input
				if($key3 == "label"){
					if($value3 == $whichPlatform){
						$whichPlatformCheck = true;
					}
				}
			}
		}
	}
	if($whichPlatformCheck == false){//whichPlatform input not valid, exits script
		echo 'Invalid platform provided<br>';
		exit();
	}
	else{//whichPlatform input valid
		return true;
	}
}

//checks to make sure searchField is valid
function searchCheck($jsonObject, $searchField){
	$count = 0;
	$dupArray = array();
	foreach($jsonObject as $key => $value){//gets valid searchField options
		foreach($value as $key2 => $value2){
			foreach($value2 as $key3 => $value3){
				if($key3 == "searchable"){
					foreach($value3 as $key4 => $value4){
						$dupArray[$count] = $value4;
						$count++;
					}
					
				}
			}
		}
	}
	//removes duplicate information
	$uniqueArray = array_unique($dupArray);
	$searchFieldCheck = false;
	foreach($uniqueArray as $key5 => $value5){//checks given searchField against valid options
		if($value5 == $searchField){
			$searchFieldCheck = true;
		}
	}
	if($searchFieldCheck == false){//searchField input not valid, exits script
		echo 'Invalid search field provided<br>';
		exit();
	}
	else{//searchField input valid
		return true;
	}
}

function getPlatformURL($jsonObject, $whichPlatform){
	$platformURL = "";
	foreach($jsonObject as $key1 => $value1){
		foreach($value1 as $key2 => $value2){
			foreach($value2 as $key3 => $value3){
				if($value3 == $whichPlatform){
					foreach($value2 as $key4 => $value4){
						if($key4 == "url"){
							$platformURL = $value4;
						}	
					}
				}
			}
		}
	}
	if($platformURL == ""){//requested URL not found, exits script
		echo "Error: Could not find requested content<br>";
		exit();
	}
	return $platformURL;
}

function getDescriptor($jsonObject){
	$descriptor = "";
	foreach($jsonObject as $key1 => $value1){
		foreach($value1 as $key2 => $value2){
			foreach($value2 as $key3 => $value3){
				if($key3 == "descriptors"){
					$descriptor = $value3;
				}
			}
		}
	}
	if($descriptor == ""){//descruptor not found, exits script
		echo "Error: Could not find descriptor type<br>";
		exit();
	}
	return $descriptor;
}

//prints HTML document when criteria is empty
function printRequestNoCriteria($requestedObject, $searchField, $descriptor){
	startHTML();
	foreach($requestedObject as $key1 => $value1){//prints descriptors
		if($key1 == $descriptor){
			echo $descriptor.":";
			foreach($value1 as $key2 => $value2){
				echo "<p>";
				echo $value2;
				echo "</p>";
			}
			echo "<br><br>";
		}
		else{
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){//inside the sub-object
					if($key3 == $searchField){//prints the specific request in bold
						echo "<p>";
						echo "<b>".$key3.": </b>";
						echo $value3;
						echo "</p>";
					}
					else{//prints the rest of the general request
						echo "<p>";
						echo $key3.": ";
						echo $value3;
						echo "</p>";
					}
				}
				
			}
		}
	}
	endHTML();
}

//prints HTML document when criteria is specified
function printRequestYesCriteria($requestedObject, $searchField, $criteria, $descriptor){
	startHTML();
		foreach($requestedObject as $key1 => $value1){//prints descriptors
		if($key1 == $descriptor){
			echo $descriptor.":";
			foreach($value1 as $key2 => $value2){
				echo "<p>";
				echo $value2;
				echo "</p>";
			}
			echo "<br><br>";
		}
		else{
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){//inside the sub-object
					if($criteria == $value3){
						foreach($value2 as $key4 =>$value4){
							if($criteria == $value4){//prints the specific request in bold
								echo "<p><b>";
								echo $key4.": ";
								echo $value4;
								echo "</b></p>";
							}
							else{//prints the rest of the general request
								echo "<p>";
								echo $key4.": ";
								echo $value4;
								echo "</p>";
							}
						}
					}
				}
				
			}
		}
	}
	endHTML();

}

//prints HTML document giving user the three forms
function presentForm(){
	$jsonString = file_get_contents("http://www.cs.uky.edu/~paul/public/Games.json");
	$jsonObject = json_decode($jsonString, true);
	startHTML();
	//start echo
	echo " 
	<form action='project4.php' method='get'>
		<select name='whichPlatform'>
		"; //end echo
		
		foreach($jsonObject as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){//prints the drop down options for label
					if($key3 == "label")
						echo "<option value = '".$value3."'>".$value3."</option>";
				}
			}
		}
		//start echo
		echo " 
		</select><br>
		<select name='searchField'>"
		; //end echo
		$count = 0;
		$dupArray = array();
		foreach($jsonObject as $key => $value){//finds the information to print
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					if($key3 == "searchable"){
						foreach($value3 as $key4 => $value4){
							$dupArray[$count] = $value4;
							$count++;
						}
						
					}
				}
			}
		}
		//removes duplicate information
		$uniqueArray = array_unique($dupArray);
		foreach($uniqueArray as $key5 => $value5){//prints the drop down options for label
			echo "<option value = '".$value5."'>".$value5."</option>";
		}
		//start echo
		echo " 
		</select><br>
		<input type='text' name='criteria'><br>
		<input type='submit' value = 'Search!' name='Report'>
	</form>"
	; //end echo
	endHTML();
}

//processes the users input
function processForm(){
	$jsonString = file_get_contents("http://www.cs.uky.edu/~paul/public/Games.json");
	$jsonObject = json_decode($jsonString, true);
	//gets the values passed in
	$whichPlatform = $_GET['whichPlatform'];
	$searchField = $_GET['searchField'];
	$criteria = $_GET['criteria'];
	//validates the user input
	//whichPlatform validation
	$whichPlatformCheck = platformCheck($jsonObject, $whichPlatform);
	//searchField validation
	$searchFieldCheck = searchCheck($jsonObject, $searchField);
	//gets the URL for the requested content
	$platformURL = getPlatformURL($jsonObject, $whichPlatform);
	//gets the descriptor type
	$descriptor = getDescriptor($jsonObject);
	//opens and decodes requested JSON file
	$requestedString = file_get_contents($platformURL);
	$requestedObject = json_decode($requestedString, true);
	if($criteria == ""){
		printRequestNoCriteria($requestedObject, $searchField, $descriptor);
	}
	else{
		printRequestYesCriteria($requestedObject, $searchField, $criteria, $descriptor);
	}
}

//determines which HTML document should be printed on load
if (isset($_GET['whichPlatform'])) {
	processForm();
} else {
	presentForm();
}

?>