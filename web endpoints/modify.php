<?php
function db_iconnect($dbName)
{
	$un="webuser";
	$pw="E!.Sx[U@q5yOIt_K";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}

if (isset($_POST['submit']) && $_POST['submit'] == "modify_device")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
    if (!isset($_POST['auto_id']) || empty($_POST['auto_id']))
    {
        // If auto_id is not set or empty, redirect to search.php
        header("Location: search.php?action=''");
        exit();
    }
    else
    {
		$auto_id=$_POST['auto_id'];
		$sql="Select * from `equipment_prod` where `auto_id` = '$auto_id'";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
		$row=$result->fetch_row();
		
		$sql="Select `auto_id` from `type`";
    	$result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
		echo '<form method="post" action="">';
		echo '<label for="type">Type: </label>';
		echo '<select name="type">';
		echo '<option value="'.$row[1].'" disabled selected>Unchanged</option>';
		while($data=$result->fetch_array(MYSQLI_NUM))
		{
			if($data[0] == $row[1])
			{
				continue;
			}
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
		echo '</select>';
		echo '<br>';
		echo '<br>';
		
		$sql="Select `auto_id` from `manufacturers`";
    	$result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
		echo '<label for="manufacturer">Manufacturer: </label>';
		echo '<select name="manufacturer">';
		echo '<option value="'.$row[2].'" disabled selected>Unchanged</option>';
		while($data=$result->fetch_array(MYSQLI_NUM))
		{
			if($data[0] == $row[2])
			{
				continue;
			}
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
		echo '</select>';
		echo '<br>';
		echo '<br>';
		
		$sql="SELECT EXISTS(SELECT * from `equipment_inactive_status` where `equipment_id` = '$auto_id')";
    	$result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
		$row2=$result->fetch_row();
		echo '<label for="status">Status: </label>';
		echo '<select name="status">';
		if($row2[0] != 1)
		{
			
			echo '<option value="unchanged" disabled selected>Unchanged</option>';
			echo '<option value="inactive" >Inactive</option>';
		}
		else
		{
			echo '<option value="unchanged" disabled selected>Unchanged</option>';
			echo '<option value="active" >Active</option>';
		}
		echo '</select>';
		echo '<br>';
		echo '<br>';
		
		echo '<input type="hidden" name="auto_id" value="'.$auto_id.'">';
		echo '<button type="submit" name="submit" value="modify_device_submit">Submit</button>';
		echo '</form>';
    }
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_device_submit")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$auto_id=$_POST['auto_id'];
	$type=$_POST['type'];
	$manu=$_POST['manufacturer'];
	$status=$_POST['status'];
	
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo $status;
	if($_POST['type'] == "unchanged")
	{
		$sql="UPDATE `equipment_prod` SET `type` = '$type' WHERE `auto_id` = '$auto_id'";
    	$result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	}
	if($_POST['manufacturer'] == "unchanged")
	{
		$sql="UPDATE `equipment_prod` SET `manufacturer` = '$manu' WHERE `auto_id` = '$auto_id'";
    	$result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	}
	if($_POST['status'] == "active")
	{
		$sql="DELETE FROM `equipment_inactive_status` WHERE `equipment_id` = '$auto_id'";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	}
	else if($_POST['status'] == "inactive")
	{
		echo 'hello';
		$sql="Insert into `equipment_inactive_status` (`equipment_id`) values('$auto_id')";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	}
		
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_type")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select distinct (`type`) from `type`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	echo '<h3>Choose Type to Modify: </h3>';
	echo '<form method="post" action="">';
	echo '<select name="type">';
	echo '<option value="" disabled selected>Select a Type</option>';
	while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
	echo '</select>';
	echo '<button type="submit" name="submit" value="modify_type_info">Submit</button><br>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_type_info")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['type'];
	
	echo '<h3>Modifying Type: '.$query.'</h3>';
	echo '<h4>Leave Name Blank To leave it Unchanged</h4>';
	$sql="Select `status` from `type` where `type` = '$query'";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();

	echo '<form method="post" action="">';
	echo '<label for="name">Name: </label>';
	echo '<input type="text" id="name" name="name" placeholder="'.$query.'"><br>';
	echo '<label for="status">Status: </label>';
	echo '<select name="status">';
	if($row[0] == 'active')
	{
		echo '<option value="unchanged" selected>Unchanged</option>';
		echo '<option value="inactive" >Inactive</option>';
	}
	else if($row[0] == 'inactive')
	{
		echo '<option value="unchanged" selected>Unchanged</option>';
		echo '<option value="active" >active</option>';
	}
	echo '</select><br><br>';
	echo '<input type="hidden" name="type" value='.$query.'>';
	echo '<button type="submit" name="submit" value="modify_type_submit">Submit</button><br>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_type_submit")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	$query=$_POST['name'];
	$query2=$_POST['status'];
	$query3=$_POST['type'];
	
	echo '<form method="post" action="">';
	if($query == '' && $query2 == 'unchanged')
	{
		echo '<h3>Type '.$query3.' is Unchanged </h3>';

	}
	else
	{
		echo '<h3>Type '.$query3.' Modified</h3>';
		if($query != '')
		{
			$sql="UPDATE `type` SET `type` = '$query' where `type` = '$query3'";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
			$query3 = $query;
		}
		else
		{
			$query=$query3;
		}

		if($query2 != 'unchanged')
		{
			if($query2 == 'active')
			{
				$sql="UPDATE `type` SET `status` = 'active' where `type` = '$query3'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				echo '<h4>Amount of Devices Re-Activated: '.$count.'</h4>';
			}
			else
			{
				$sql="UPDATE `type` SET `status` = 'inactive' where `type` = '$query3'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);

				$sql="INSERT INTO `equipment_inactive_status` (`equipment_id`) SELECT `auto_id` FROM `equipment_prod` WHERE `type` = (SELECT `auto_id` FROM `type` WHERE `type` = '$query3')";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$count = $dblink->affected_rows;
				echo '<h4>Amount of Devices De-Activated: '.$count.'</h4>';
				
			}
		}
	}
	
	echo '<button type="submit" name="submit">Home</button><br>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_manu")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select distinct (`manufacturer`) from `manufacturers`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	echo '<h3>Choose Manufacturer to Modify: </h3>';
	echo '<form method="post" action="">';
	echo '<select name="manufacturer">';
	echo '<option value="" disabled selected>Select a Manufacturer</option>';
	while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
	echo '</select>';
	echo '<button type="submit" name="submit" value="modify_manu_info">Submit</button><br>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_manu_info")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['manufacturer'];
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<h3>Modifying Manufacturer: '.$query.'</h3>';
	echo '<h4>Leave Name Blank To leave it Unchanged</h4>';
	$sql="Select `status` from `manufacturers` where `manufacturer` = '$query'";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();

	echo '<form method="post" action="">';
	echo '<label for="name">Name: </label>';
	echo '<input type="text" id="name" name="name" placeholder="'.$query.'"><br>';
	echo '<label for="status">Status: </label>';
	echo $row[0];
	echo '<select name="status">';
	if($row[0] == 'active')
	{
		echo '<option value="unchanged" selected>Unchanged</option>';
		echo '<option value="inactive" >Inactive</option>';
	}
	else
	{
		echo '<option value="unchanged" selected>Unchanged</option>';
		echo '<option value="active" >Active</option>';
	}
	echo '</select><br><br>';
	echo '<input type="hidden" name="manufacturer" value='.$query.'>';
	echo '<button type="submit" name="submit" value="modify_manu_submit">Submit</button><br>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && $_POST['submit'] == "modify_manu_submit")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['name'];
	$query2=$_POST['status'];
	$query3=$_POST['manufacturer'];
	
	echo '<form method="post" action="">';
	if($query == '' && $query2 == 'unchanged')
	{
		echo '<h3>Manufacturer '.$query3.' is Unchanged </h3>';

	}
	else
	{
		echo '<h3>Manufacturer '.$query3.' Modified</h3>';
		if($query != '')
		{
			$sql="UPDATE `manufacturers` SET `manufacturer` = '$query' where `manufacturer` = '$query3'";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
			$query3 = $query;
		}
		else
		{
			$query=$query3;
		}
		if($query2 != 'unchanged')
		{
			if($query2 == 'active')
			{
				$sql="UPDATE `manufacturers` SET `status` = 'active' where `manufacturer` = '$query3'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$sql="DELETE from `equipment_inactive_status` where `equipment_id` IN (SELECT `auto_id` FROM `equipment_prod` WHERE `manufacturer` = (SELECT `auto_id` FROM `manufacturers` WHERE `manufacturer` = '$query'))";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$count = $dblink->affected_rows;
				echo '<h4>Amount of Devices Re-Activated: '.$count.'</h4>';
			}
			else
			{
				$sql="UPDATE `manufacturers` SET `status` = 'inactive' where `manufacturer` = '$query3'";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);

				$sql="INSERT INTO `equipment_inactive_status` (`equipment_id`) SELECT `auto_id` FROM `equipment_prod` WHERE `manufacturer` = (SELECT `auto_id` FROM `manufacturers` WHERE `manufacturer` = '$query3')";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql<br>".$dblink->error);
				$count = $dblink->affected_rows;
				echo '<h4>Amount of Devices De-Activated: '.$count.'</h4>';
				
			}
		}
	}
	
	echo '<button type="submit" name="submit">Home</button><br>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="modify_device">Modify Device</button><br>';
	echo '<button type="submit" name="submit" value="modify_type">Modify Type</button><br>';
	echo '<button type="submit" name="submit" value="modify_manu">Modify Manufacturer</button>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}
?>