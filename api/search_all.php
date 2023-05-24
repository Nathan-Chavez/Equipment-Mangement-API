<?php
	$type=$_REQUEST['type'];
	$manu=$_REQUEST['manufacturer'];
	$serial_num=$_REQUEST['serial_num'];

	if(!isset($_REQUEST['type']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Type data NULL';
		$output[]='Action: Resend type data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	else if(!is_numeric($_REQUEST['type']) && !strcmp($_REQUEST['type'],"all") == 0)
	{
		$output[]='Status: Error';
		$output[]='MSG: Invalid Type Data';
		$output[]='Action: Resend type data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}

	if(!isset($_REQUEST['manufacturer']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Manufacturer data NULL';
		$output[]='Action: Resend Manufacturer data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	else if(!is_numeric($_REQUEST['manufacturer']) && !strcmp($_REQUEST['manufacturer'],"all") == 0)
	{
		$output[]='Status: Error';
		$output[]='MSG: Invalid Manufacturer Data';
		$output[]='Action: Resend manufacturer data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}

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

	if($type == 'all' and $manu == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `serial_num` = '$serial_num' limit 1000";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else if($type == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `type` = '$type' && `serial_num` = '$serial_num' limit 1000";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else if($manu == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `manufacturer` = '$manu' && `serial_num` = '$serial_num' limit 1000";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$type' AND `manufacturer`='$manu' AND `serial_num`='$serial_num' limit 1000";
	}
	$result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n\n".$dblink->error);
	
	if ($result->num_rows == 0) {
    	//$infoJson=json_encode($info);
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		$output[]='Status: Success';
		$output[]='MSG: No Data With Values Sent';
		$output[]='Action: '.$execution_time;
		$responseData=json_encode($output);
		echo $responseData;
		die();
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