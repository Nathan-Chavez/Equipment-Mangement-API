<?php
	$serialNum = $_REQUEST['serial_num'];

	if(!isset($_REQUEST['serial_num']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Serial Number data NULL';
		$output[]='Action: Resend serial number data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$info=array();
	$time_start=microtime(true);
	$dblink=db_iconnect("test");
	
	$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `serial_num`='$serialNum'";
	$result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);

	if ($result->num_rows == 0) {
    	//$infoJson=json_encode($info);
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: Success';
		$output[]='MSG: No Data Associated With Serial Number';
		$output[]='Action: '.$execution_time;
		$responseData=json_encode($output);
		echo $responseData;
	}
	else
	{	
		while ($data=$result->fetch_array(MYSQLI_ASSOC))
		{
			//Type name
			$sql="Select `type` from `type` where `auto_id`='$data[type]'";
			$rst=$dblink->query($sql) or
				die("Something went wrong with $sql<br>\n".$dblink->error);
			$tmp=$rst->fetch_array(MYSQLI_ASSOC);
			$type=$tmp['type'];
			//Manufactuer name
			$sql="Select `manufacturer` from `manufacturers` where `auto_id`='$data[manufacturer]'";
			$rst=$dblink->query($sql) or
				die("Something went wrong with $sql<br>\n".$dblink->error);
			$tmp=$rst->fetch_array(MYSQLI_ASSOC);
			$manufacturer=$tmp['manufacturer'];
			//$info[]=array($type,$manufacturer,$data['serial_num']);
			$info[]="$type,$manufacturer,$data[serial_num]";
		}

		$infoJson=json_encode($info);
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: Success';
		if($infoJson == '[]')
		{
			$output[]='MSG: No Data with Given Values';
			$output[]='Action: Resend All Values';
		}
		else
		{
			$output[]='MSG: '.$infoJson;
			$output[]='Action: '.$execution_time;
		}
		$responseData=json_encode($output);
		echo $responseData;
	}

?>