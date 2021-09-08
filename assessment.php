<?php
		
		//creating database connection
		$con = new mysqli("localhost","root","","test") or die("connection error");

		if($con->connect_error){					//check for the connection
			echo "Connection Failed <hr>";
			}
		else{
			echo "connection established successfuly <hr>";
			}

?>