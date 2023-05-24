<?php
	$type=$_REQUEST['type'];
	$manu=$_REQUEST['manufacturer'];
	$serial_num=$_REQUEST['serialNum'];
	$status=$_REQUEST['status'];
	
	// Check if Type Exists
	if(!isset($_REQUEST['type']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Type data NULL';
		$output[]='Action: Resend Type Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	// Validate Type Data Inputed 
	if(!is_numeric($_REQUEST['type'])  && !strcmp($_REQUEST['type'],"new_type") == 0)
	{
		$output[]='Status: Error';
		$output[]='MSG: Invalid Type Data';
		$output[]='Action: Resend type data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	// If type = new_type chain call insert_type api
	if(strcmp($_REQUEST['type'],"new_type") == 0)
	{
		$tname=$_REQUEST['tname'];
		$tstatus=$_REQUEST['tstatus'];
		//Check if Type Name Exists
		if(!isset($_REQUEST['tname']))
		{
			$output[]='Status: Error';
			$output[]='MSG: Invalid Type Name Data';
			$output[]='Action: Resend type name data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		//Check if Type Status Exists
		if(!isset($_REQUEST['tstatus']))
		{
			$output[]='Status: Error';
			$output[]='MSG: Type status data NULL';
			$output[]='Action: Resend Type Status Data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		//Validate Status Data
		if(!(strcmp($_REQUEST['tstatus'],"active") == 0) && !(strcmp($_REQUEST['tstatus'],"inactive") == 0))
		{
			$output[]='Status: Error';
			$output[]='MSG: Invalid status data';
			$output[]='Action: Resend Status Data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		// Call insert_type api using curl
		$curl= curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/insert_type?name=$tname&status=$tstatus",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if($err)
		{
			echo "<h3> CURL ERROR Insert Type API#: $err</h3>";
			die();
		}
		else
		{
			$results = json_decode($response,true);
		}

		$tmp=explode(":",$results[0]);
		$status=trim($tmp[1]);
		if ($status == "Success")
		{
			$tmp=explode(":",$results[1]);
			$data=trim($tmp[1]);
			if (empty($data)) 
			{
				// Display your custom message here
				echo '<h3>No results found.</h3>';
			}
			else if($data == 'Type Inserted')
			{
				$dblink=db_iconnect("test");
				$sql="Select `auto_id` from `type` where `type` = '$tname'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$row = $result->fetch_assoc();
				$type=$row['auto_id'];
			}
			else
			{
				$output[]='Status: Error';
				$output[]='MSG: Type Already Exists';
				$output[]='Action: Resend Type Data';
				$responseData=json_encode($output);
				echo $responseData;
				die();
			}

		}
	}

	if(!isset($_REQUEST['manufacturer']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Manufacturer data NULL';
		$output[]='Action: Resend Manufacturer Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if(strcmp($_REQUEST['manufacturer'],"new_manu") == 0)
	{
		$mname=$_REQUEST['mname'];
		$mstatus=$_REQUEST['mstatus'];

		
		//Check if Manufacturer Name Exists
		if(!isset($_REQUEST['mname']))
		{
			$output[]='Status: Error';
			$output[]='MSG: Invalid Manufacturer Name Data';
			$output[]='Action: Resend manufacturer name data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		//Check if Manufacturer Status Exists
		if(!isset($_REQUEST['mstatus']))
		{
			$output[]='Status: Error';
			$output[]='MSG: Manufacturer status data NULL';
			$output[]='Action: Resend Manufacturer Status Data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		//Validate manufacturer Status Data
		if(!(strcmp($_REQUEST['mstatus'],"active") == 0) && !(strcmp($_REQUEST['mstatus'],"inactive") == 0))
		{
			$output[]='Status: Error';
			$output[]='MSG: Invalid manufacturer status data';
			$output[]='Action: Resend Manufacturer Status Data';
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
		// Call insert_manufacturer api using curl
		$curl= curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/insert_manufacturer?name=$mname&status=$mstatus",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if($err)
		{
			echo "<h3> CURL ERROR Insert Type API#: $err</h3>";
			die();
		}
		else
		{
			$results = json_decode($response,true);
		}
		$tmp=explode(":",$results[0]);
		$status=trim($tmp[1]);
		if ($status == "Success")
		{
			$tmp=explode(":",$results[1]);
			$data=trim($tmp[1]);
			if (empty($data)) 
			{
				// Display your custom message here
				echo '<h3>No results found.</h3>';
			}
			else if($data == 'Manufacturer Inserted')
			{
				$dblink=db_iconnect("test");
				$sql="Select `auto_id` from `manufacturers` where `manufacturer` = '$mname'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$row = $result->fetch_assoc();
				$manu=$row['auto_id'];
			}
			else
			{
				$output[]='Status: Error';
				$output[]='MSG: Manufacturer Already Exists2';
				$output[]='Action: Resend Manufacturer Data';
				$responseData=json_encode($output);
				echo $responseData;
				die();
			}

		}
	}
	//Validate manufacturer data
	if(!is_numeric($_REQUEST['manufacturer']) && !strcmp($_REQUEST['manufacturer'],"new_manu") == 0)
	{
		$output[]='Status: Error';
		$output[]='MSG: Invalid Manufacturer Data';
		$output[]='Action: Resend manufacturer data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	//Check if Serial Number data exists
	if(!isset($_REQUEST['serialNum']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Serial number data NULL';
		$output[]='Action: Resend Serial Number Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	//Check if status data exists
	if(!isset($_REQUEST['status']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Status data NULL';
		$output[]='Action: Resend Status Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	//Validate Status Data
	if(!(strcmp($_REQUEST['status'],"active") == 0) && !(strcmp($_REQUEST['status'],"inactive") == 0))
	{
		$output[]='Status: Error';
		$output[]='MSG: Invalid status data';
		$output[]='Action: Resend Status Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	
	$info=array();
	$time_start=microtime(true);
	$dblink=db_iconnect("test");

	//Check if Device exists
	$sql="SELECT EXISTS(SELECT * from `equipment_prod` where `serial_num` = '$serial_num')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	//If $row ! = 1 then the device does not exist and the device can be inserted
	if($row[0] != 1)
	{
		//Insert device into `equipment_prod`
		$sql="Insert into `equipment_prod` (`type`, `manufacturer`, `serial_num`) values('$type','$manu','$serial_num')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		//If status = inactive then add the auto_id of device to the `equipment_inactive_status
		if($status == 'inactive')
		{
			$sql="Select `auto_id` from `equipment_prod` where `serial_num` = '$serial_num'";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
			$row=$result->fetch_row();
			$sql="Insert into `equipment_inactive_status` (`equipment_id`) values('$row[0]')";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		}
		//Send payload.
		$output[]='Status: Success';
		$output[]='MSG: Device Inserted';
		$output[]='Action: Resend Status Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();	
	}
	//Else device exists send payload
	else
	{
		$output[]='Status: Success';
		$output[]='MSG: Device With Serial Number Already Exists';
		$output[]='Action: Resend Serial Number Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();	
	}

?>   