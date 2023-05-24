<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
function db_iconnect($dbName)
{
	$un="webuser";
	$pw="E!.Sx[U@q5yOIt_K";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
function insert_type()
{
	$dblink=db_iconnect("test");
	$query=$_POST['type'];
	$query2=$_POST['status'];
	

	$sql="SELECT EXISTS(SELECT * from `type` where `type` = '$query')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `type` (`type`, `status`) values('$query','$query2')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
	}
	$sql="SELECT `auto_id` from `type` where `type` = '$query'";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	$_POST['type']=$row[0];
	
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	return(1);
}
function new_type()
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	
    echo '<h3>Insert New Type</h3>';
    echo '<form method="post" action="">';
	echo '<input type="hidden" name="new_type_submitted" value="1">';
	echo '<input type="hidden" name="new_manu_submitted">';
	echo '<input type="hidden" name="manufacturer" value="' . htmlspecialchars($_POST['manufacturer']) . '">';
	echo '<input type="hidden" name="serial_num" value="' . htmlspecialchars($_POST['serial_num']) . '">';
	echo '<input type="hidden" name="status" value="' . htmlspecialchars($_POST['status']) . '">';
    echo '<label for="type">Type: </label>';
	echo '<input type="text" id="type" name="type"><br>';
    echo '<label for="status">Status: </label>';
    echo '<select name="status">';
    echo '<option value="active">active</option>';
    echo '<option value="inactive">inactive</option>';  
    echo '</select><br>';
    echo '<button type="submit" name="submit" value="submit_device">Submit</button>';
    echo '</form>';

    
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
function insert_manufacturer()
{
	$dblink=db_iconnect("test");
	$query=$_POST['manufacturer'];
	$query2=$_POST['status'];
	

	$sql="SELECT EXISTS(SELECT * from `manufacturers` where `manufacturer` = '$query')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `manufacturers` (`manufacturer`, `status`) values('$query','$query2')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
	}
	$sql="SELECT `auto_id` from `manufacturers` where `manufacturer` = '$query'";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	$_POST['manufacturer']=$row[0];
	
	
	return(1);
}
function new_manufacturer()
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	
    echo '<h3>Insert New Manufacturer</h3>';
    echo '<form method="post" action="">';
	if(isset($_POST['new_type_submitted']))
	{
		echo '<input type="hidden" name="new_type_submitted">';
	}
	echo '<input type="hidden" name="new_manu_submitted" value="1">';
	echo '<input type="hidden" name="type" value="' . htmlspecialchars($_POST['type']) . '">';
	echo '<input type="hidden" name="serial_num" value="' . htmlspecialchars($_POST['serial_num']) . '">';
	echo '<input type="hidden" name="status" value="' . htmlspecialchars($_POST['status']) . '">';
    echo '<label for="manufacturer">Manufacturer: </label>';
	echo '<input type="text" id="manufacturer" name="manufacturer"><br>';
    echo '<label for="status">Status: </label>';
    echo '<select name="status">';
    echo '<option value="active">active</option>';
    echo '<option value="inactive">inactive</option>';  
    echo '</select><br>';
    echo '<button type="submit" name="submit" value="submit_device">Submit</button>';
    echo '</form>';

    
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
function insert_device()
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['type'];
	$query2=$_POST['manufacturer'];
	$query3=$_POST['serial_num'];
	$query4=$_POST['status'];
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	
	$sql="SELECT EXISTS(SELECT * from `equipment_prod` where `serial_num` = '$query3')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `equipment_prod` (`type`, `manufacturer`, `serial_num`) values('$query','$query2','$query3')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		if($query4 == 'inactive')
		{
			$sql="Select `auto_id` from `equipment_prod` where `serial_num` = '$query3'";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
			$row=$result->fetch_row();
			$sql="Insert into `equipment_inactive_status` (`equipment_id`) values('$row[0]')";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		}

		echo '<h3>Device Inserted</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
	else
	{
		echo '<h3>Device Exists Already</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
}

if(isset($_POST['submit']) && ($_POST['submit']) == "submit_device")
{
	if(isset($_POST['submit']) && ($_POST['submit']) == "submit_device")
	{
		$dblink=db_iconnect("test");
		$time_start=microtime(true);
		$query=$_POST['type'];
		$query2=$_POST['manufacturer'];
		$query3=$_POST['serial_num'];
		$query4=$_POST['status'];

		if ($query == 'new_type') 
		{
			new_type();
		} 
		else if ($query2 == 'new_manu') 
		{
			new_manufacturer();
		} 
		else if (isset($_POST['new_type_submitted']) && isset($_POST['new_manu_submitted'])) 
		{
			$res1 = insert_type();
			$res2 = insert_manufacturer();
			if ($res1 == 1 && $res2 == 1) 
			{
				insert_device();
			}
		} 
		else if (isset($_POST['new_type_submitted'])) 
		{
			$res = insert_type();
			if ($res == 1) 
			{
				insert_device();
			}
		} 
		else if (isset($_POST['new_manu_submitted'])) 
		{
			echo "HELLO";
			$res = insert_manufacturer();
			if ($res == 1) 
			{
				insert_device();
			}
		}
		else
			insert_device();
	
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
	}
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "insert_device")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
			/* type */
	echo '<form method="post" action="">';
	$sql="Select distinct (`auto_id`) from `type`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	echo '<select name="type">';
	echo '<option value="" disabled selected>Select a Type</option>';
	echo '<option value="new_type">New Type</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select><br>';
	
			/* manu */
	$sql="Select distinct (`auto_id`) from `manufacturers`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
    echo '<select name="manufacturer">';
	echo '<option value="" disabled selected>Select a Manufacturer</option>';
    echo '<option value="new_manu">New Manufacturer</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select><br>';
	
			/* Serial Number */
	echo '<label for="serial_num">S/N: </label>';
	echo '<input type="text" id="serial_num" name="serial_num" placeholder="Serial Number"><br>';
	
			/* Status */
	echo '<label for="status">Status: </label>';
	echo '<select name="status">';
	echo '<option value="active">active</option>';
	echo '<option value="inactive">inactive</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select><br>';
	
	echo '<button type="submit" name="submit" value="submit_device">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "insert_type")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);

	echo '<h3>Insert New Type</h3>';
	echo '<form method="post" action="">';
	echo '<label for="name">Name: </label>';
	echo '<input type="text" id="name" name="name" placeholder="Name"><br>';
	echo '<label for="status">Status: </label>';
	echo '<select name="status">';
	echo '<option value="active">active</option>';
	echo '<option value="inactive">inactive</option>';  
    echo '</select><br>';
	echo '<button type="submit" name="submit" value="submit_type_insert">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "submit_type_insert")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	$query=$_POST['name'];
	$query2=$_POST['status'];

	$sql="SELECT EXISTS(SELECT * from `type` where `type` = '$query')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `type` (`type`, `status`) values('$query','$query2')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		echo '<h3>Type Inserted</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
	else
	{
		echo '<h3>Type Exists Already</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "insert_manufacturer")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);

	echo '<h3>Insert New Manufacturer</h3>';
	echo '<form method="post" action="">';
	echo '<label for="name">Name: </label>';
	echo '<input type="text" id="name" name="name" placeholder="Name"><br>';
	echo '<label for="status">Status: </label>';
	echo '<select name="status">';
	echo '<option value="active">active</option>';
	echo '<option value="inactive">inactive</option>';  
    echo '</select><br>';
	echo '<button type="submit" name="submit" value="submit_manu_insert">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "submit_manu_insert")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	$query=$_POST['name'];
	$query2=$_POST['status'];

	$sql="SELECT EXISTS(SELECT * from `manufacturers` where `manufacturer` = '$query')";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	$row = $result->fetch_row();
	if($row[0] != 1)
	{
		$sql="Insert into `manufacturers` (`manufacturer`, `status`) values('$query','$query2')";
		$result=$dblink->query($sql) or
				die("Something went wrong with $sql<br>".$dblink->error);
		echo '<h3>Manufacturer Inserted</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
	else
	{
		echo '<h3>Manufacturer Exists Already</h3>';
		echo '<h4>Press Button to Return to Home</h4>';
		echo '<form method="post" action="">';
		echo '<button type="submit">Home</button>';
		echo '</form>';
	}
	
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
	echo '<button type="submit" name="submit" value="insert_device">Insert Device</button><br>';
	echo '<button type="submit" name="submit" value="insert_type">Insert Type</button><br>';
	echo '<button type="submit" name="submit" value="insert_manufacturer">Insert Manufacturer</button>';
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