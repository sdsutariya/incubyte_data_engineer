<?php
		
		//creating database connection
		$con = new mysqli("localhost","root","","test") or die("connection error");

		if($con->connect_error){					//check for the connection
			echo "Connection Failed <hr>";
			}
		else{
			echo "connection established successfuly <hr>";
			}
		
		$table = "create table if not exists customer
					(Customer_Name varchar(255) primary key not null, 
					Customer_Id varchar(18) not null,
						Open_Date date not null,
						Last_Consulted_Date date,
						Vaccination_Id char(5),
						Dr_Name char(255),
						State char(5),
						Country char(5),
						post_code int(5),
						DOB date,
						Is_Active char(1)
					)";

		// prepare query for the table insetion in database
		$con->query($table) or die("Error ");			

		//read the .txt file
		$handle = fopen("test.txt","r");

        $query = "insert into customer(Customer_Name,Customer_Id,Open_Date,Last_Consulted_Date,Vaccination_Id,Dr_Name,State,
						Country,DOB,Is_Active) VALUES(?,?,?,?,?,?,?,?,?,?)";

        //prepare statement for the customer table to insert data from .txt file

       $result = $con->prepare($query) or die("error in result");

       //for not inserted if data is already available

		while(!feof($handle)){

			//read a single line from .txt
			$data = fgets($handle);

			//spilt data of single line with |
			$final_data = explode("|", $data);
			
			//check for data not the header in .txt file
			if($final_data[1] == "D"){

				$d = array();

				$result_2 = $con->prepare("select Customer_Name from customer") or die("error in result");
				$result_2->execute();

				$data_2 = $result_2->get_result();
				
				//To change the date format
				$str = $final_data[10];
				$day = substr($str,0,2);
				$month = substr($str,2,2);
				$year = substr($str,4);
				$strdate = $year.$month.$day;


				//fetching array of customer name
				while($data_m = $data_2->fetch_array()){
					$d[] = $data_m[0];

				}


				//for unqie data in customr table if available then not inserted
				if(!in_array($final_data[2], $d)){
					$result->bind_param("ssssssssss", $final_data[2], $final_data[3], $final_data[4], $final_data[5], $final_data[6], $final_data[7], $final_data[8], $final_data[9], $strdate, $final_data[11]);
					$result->execute() or die("error in insertion.") ;
				}
			}
		}
?>