<?php
	$name=$_REQUEST['name'];
	$status=$_REQUEST['status'];

	if(!isset($_REQUEST['name']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Name data NULL';
		$output[]='Action: Resend Name Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['status']))
	{
		$output[]='Status: Error';
		$output[]='MSG: Status data NULL';
		$output[]='Action: Resend Status Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
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

	$sql="SELECT EXISTS(SELECT * from `manufacturers` where `manufacturer` = '$name')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `manufacturers` (`manufacturer`, `status`) values('$name','$status')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		
		$time_end=microtime(true);
    	$seconds=$time_end-$time_start;
    	$execution_time=($seconds)/60;
		$output[]='Status: Success';
		$output[]='MSG: Manufacturer Inserted';
		$output[]='Action: '.$execution_time;
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	else
	{
		$output[]='Status: Success';
		$output[]='MSG: Manufacturer Already Exist1';
		$output[]='Action: Resend Manufacturer Data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
?>