<?php
echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" type="text/css" href="../assets/css/style.css">';
    echo '</head>';
    echo '<body>';
    echo '</body>';
echo '</html>';

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
			/*{Search By Type Manufaturer Filter }*/
if (isset($_POST['submit']) && ($_POST['submit']) == "search_by_type")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true); 

	echo '<form method="post" action="">';
    $sql="Select distinct (`manufacturer`) from `equipment_prod`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
    echo '<select name="manufacturer">';
	echo '<option value="" disabled selected>Select a Manufacturer</option>';
    echo '<option value="all">All Manufacturers</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
    echo '<button type="submit" name="submit" value="submit_manu_filter">Submit</button>';
    echo '</form>';
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
		/*{Search By Type with Manufacturer filter Results }*/
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_manu_filter")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['manufacturer'];
    if ($query == 'all') 
	{
        $sql="Select distinct `type` from `equipment_prod`";
    }
	else 
	{
        $sql="Select distinct `type`from `equipment_prod` where `manufacturer`='$query'";
    }
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);
    echo '<form method="post" action="">';
    echo '<select name="type">';
	echo '<option value="" disabled selected>Select a Type</option>';
	echo '<option value="all">All Types</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
	echo '<input type="hidden" name="manufacturer" value="'.$query.'">';
	echo '<button type="submit" name="submit" value="submit_type_w_manu">Submit</button>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_type_w_manu")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['type'];
    $query2=$_POST['manufacturer'];
    echo '<form method="post" action="">';
	echo '<input type="text" id="serial_num" name="serial_num" placeholder="Serial Number">';
	echo '<input type="hidden" name="type" value="'.$query.'">';
	echo '<input type="hidden" name="manufacturer" value="'.$query2.'">';
	echo '<button type="submit" name="submit" value="submit_type">Submit</button>';
	echo '</form>';
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="submit_type">All</button>';
	echo '<input type="hidden" name="type" value="'.$query.'">';
	echo '<input type="hidden" name="manufacturer" value="'.$query2.'">';
	echo '<input type="hidden" name="serial_num" value="all">';
	echo '</form>';
	
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_type")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['type'];
    $query2=$_POST['manufacturer'];
	$query3=$_POST['serial_num'];
	if($query3 == 'all')
	{
		if ($query == 'all' && $query2 == 'all') 
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` ";
		}
		else if ($query == 'all' && $query2 != 'all')
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `manufacturer`='$query2' ";
		}
		else if ($query != 'all' && $query2 == 'all')
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$query' ";
		}
		else
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$query' AND `manufacturer`='$query2' ";
			
		}		
	}
	else
	{
		if ($query == 'all' && $query2 == 'all') 
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` ";
		}
		else if ($query == 'all' && $query2 != 'all')
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `manufacturer`='$query2' ";
		}
		else if ($query != 'all' && $query2 == 'all')
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$query' ";
		}
		else
		{
			$sql="Select `auto_id`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$query' AND `manufacturer`='$query2' AND `serial_num` = '$query3' ";
			
		}		
		
	}
    
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);
    echo '<h3>Search by Type: '.$query.'</h3>';
	echo '<h4>Filter by Manufactuerer: '.$query2.'</h4>';
    echo '<table>';
    
	$num_rows = $result->num_rows;
	if ($num_rows > 0)
	{
		echo '<tr><td>Auto Index</td><td>Manufacturer</td><td>Serial Number</td></tr>';
		while ($data=$result->fetch_array(MYSQLI_ASSOC))
    	{
			
			
			echo '<tr>';
			echo "<td>$data[auto_id]</td>";
			echo '<td>'.$data['manufacturer'].'</td>';
			echo "<td>$data[serial_num]</td>";
			echo '<td><form method="post" action=""><input type="hidden" name="auto_id" value="'.$data['auto_id'].'"><button type="submit" name="submit" value="modify_device">Modify</button></form></td>';
			
    	}
		echo "</tr>";
	}
	else 
	{
		if($query3 == 'all')
			{
				echo '<h4>Serial Number: '.$query3.'</h4>';
			}
    	echo '<tr><td colspan="3">No results found</td></tr>';
	}
    
    echo '</table>';
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
		/*{Search By Manufaturer Type Filter }*/
else if (isset($_POST['submit']) && ($_POST['submit']) == "search_by_manufacturer")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true); 

	echo '<form method="post" action="">';
    $sql="Select distinct (`type`) from `equipment_prod`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
    echo '<select name="type">';
	echo '<option value="" disabled selected>Select a Type</option>';
    echo '<option value="all">All Typess</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
    echo '<button type="submit" name="submit" value="submit_type_filter">Submit</button>';
    echo '</form>';
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
		/*{Search By Type with Manufacturer filter Results }*/
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_type_filter")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['type'];
    if ($query == 'all') 
	{
        $sql="Select distinct `manufacturer` from `equipment_prod`";
    }
	else 
	{
        $sql="Select distinct `manufacturer`from `equipment_prod` where `type`='$query'";
    }
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);
    echo '<form method="post" action="">';
    echo '<select name="manufacturer">';
	echo '<option value="" disabled selected>Select a Manufacturer</option>';
	echo '<option value="all">All Manufacturers</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
	echo '<input type="hidden" name="type" value="'.$query.'">';
	echo '<button type="submit" name="submit" value="submit_manu_w_type">Submit</button>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_manu_w_type")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['manufacturer'];
    $query2=$_POST['type'];
    echo '<form method="post" action="">';
	echo '<input type="text" id="serial_num" name="serial_num" placeholder="Serial Number">';
	echo '<input type="hidden" name="manufacturer" value="'.$query.'">';
	echo '<input type="hidden" name="type" value="'.$query2.'">';
	echo '<button type="submit" name="submit" value="submit_manufacturer">Submit</button>';
	echo '</form>';
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="submit_manufacturer">All</button>';
	echo '<input type="hidden" name="manufacturer" value="'.$query.'">';
	echo '<input type="hidden" name="type" value="'.$query2.'">';
	echo '<input type="hidden" name="serial_num" value="all">';
	echo '</form>';
	
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_manufacturer")
{
    $dblink=db_iconnect("test");
    $time_start=microtime(true);
    $query=$_POST['manufacturer'];
    $query2=$_POST['type'];
	$query3=$_POST['serial_num'];
	if($query3 == 'all')
	{
		if ($query == 'all' && $query2 == 'all') 
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` ";
		}
		else if ($query == 'all' && $query2 != 'all')
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `type`='$query2' ";
		}
		else if ($query != 'all' && $query2 == 'all')
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `manufacturer`='$query' ";
		}
		else
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `manufacturer`='$query' AND `type`='$query2' ";
		}		
	}
	else
	{
		if ($query == 'all' && $query2 == 'all') 
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `serial_num` = '$query3' ";
		}
		else if ($query == 'all' && $query2 != 'all')
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `type`='$query2' AND `serial_num` = '$query3' ";
		}
		else if ($query != 'all' && $query2 == 'all')
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `manufacturer`='$query' AND `serial_num` = '$query3' ";
		}
		else
		{
			$sql="Select `auto_id`,`type`,`serial_num` from `equipment_prod` where `manufacturer`='$query' AND `type`='$query2' AND `serial_num` = '$query3' ";
		}		
	}
    
    $result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);
    echo '<h3>Search by Manufacturer: '.$query.'</h3>';
	echo '<h4>Filter by Type: '.$query2.'</h4>';
    echo '<table>';
    
	$num_rows = $result->num_rows;
	if ($num_rows > 0)
	{
		if($query3 == 'all')
			{
				echo '<h4>Serial Number: '.$query3.'</h4>';
			}
		echo '<tr><td>Auto Index</td><td>Manufacturer</td><td>Serial Number</td></tr>';
		while ($data=$result->fetch_array(MYSQLI_ASSOC))
    	{
			
			
			echo '<tr>';
			echo "<td>$data[auto_id]</td>";
			echo '<td>'.$data['type'].'</td>';
			echo "<td>$data[serial_num]</td>";
			echo '<td><form method="post" action=""><input type="hidden" name="auto_id" value="'.$data['auto_id'].'"><button type="submit" name="submit" value="modify_device">Modify</button></form></td>';
			echo "</tr>";
    	}
		
	}
	else 
	{
		if($query3 == 'all')
		{
			echo '<h4>Serial Number: '.$query3.'</h4>';
		}
    	echo '<tr><td colspan="3">No results found</td></tr>';
	}
    
    echo '</table>';
    $time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
		/*{Search By Serial Number}*/
else if(isset($_POST['submit']) && ($_POST['submit']) == "search_by_serial_num")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select distinct (`type`) from `equipment_prod`";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<input type="text" id="serial_num" name="serial_num" placeholder="Serial Number">';
	echo '<button type="submit" name="submit" value="submit_serial">Submit</button>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
	
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "submit_serial")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['serial_num'];
	$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `serial_num`='$query'";
	$result=$dblink->query($sql) or
        die("Something went wrong with $sql<br>\n".$dblink->error);
	echo '<h3>Search by Serial Number: '.$query.'</h3>';
    echo '<table>';
    echo '<tr><td>Auto Index</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td><td></td></tr>';
    while ($data=$result->fetch_array(MYSQLI_ASSOC))
    {
        echo '<tr>';
        echo "<td>$data[auto_id]</td>";
        echo "<td>$data[type]</td>";
		echo "<td>$data[manufacturer]</td>";
        echo '<td>'.$data['serial_num'].'</td>';
		echo '<td><form method="post" action=""><input type="hidden" name="auto_id" value="'.$data['auto_id'].'"><button type="submit" name="submit" value="modify_device">Modify</button></form></td>';
        echo "</tr>";
    }
    echo '</table>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if (isset($_POST['submit']) && ($_POST['submit']) == "search_by_all")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	echo '<form method="post" action="">';
	$sql="Select distinct (`type`) from `equipment_prod`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
	echo '<select name="type">';
	echo '<option value="" disabled selected>Select a Type</option>';
	echo '<option value="all">All Types</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
	$sql="Select distinct (`manufacturer`) from `equipment_prod`";
    $result=$dblink->query($sql) or
            die("Something went wrong with $sql<br>".$dblink->error);
    echo '<select name="manufacturer">';
	echo '<option value="" disabled selected>Select a Manufacturer</option>';
    echo '<option value="all">All Manufacturers</option>';
    while($data=$result->fetch_array(MYSQLI_NUM))
    {
        echo '<option value="'.$data[0].'">'.$data[0].'</option>';
    }
    echo '</select>';
	echo '<input type="text" id="serial_num" name="serial_num" placeholder="Serial Number">';
	echo '<button type="submit" name="submit" value="submit_all">Submit</button>';
	echo '</form>';
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "submit_all")
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['type'];
	$query2=$_POST['manufacturer'];
	$query3=$_POST['serial_num'];
	
	echo '<h3>Search by All: </h3>';
	
	if($query == 'all' and $query2 == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `serial_num` = '$query3' ";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else if($query == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `type` = '$query' && `serial_num` = '$query3' ";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else if($query2 == 'all')
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `manufacturer` = '$query2' && `serial_num` = '$query3' ";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
	else
	{
		$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` where `type`='$query' AND `manufacturer`='$query2' AND `serial_num`='$query3' ";
		$result=$dblink->query($sql) or
        	die("Something went wrong with $sql<br>\n".$dblink->error);
	}
    echo '<table>';
    echo '<tr><td>Auto Index</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
    while ($data=$result->fetch_array(MYSQLI_ASSOC))
    {
        echo '<tr>';
        echo "<td>$data[auto_id]</td>";
        echo '<td>'.$data['type'].'</td>';
		echo '<td>'.$data['manufacturer'].'</td>';
        echo '<td>'.$data['serial_num'].'</td>';
		echo '<td><form method="post" action=""><input type="hidden" name="auto_id" value="'.$data['auto_id'].'"><button type="submit" name="submit" value="modify_device">Modify</button></form></td>';
        echo "</tr>";
    }
    echo '</table>';
	
	$time_end=microtime(true);
    $seconds=$time_end-$time_start;
    $execution_time=($seconds)/60;
    echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "modify_device")
{
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
        $auto_id = $_POST['auto_id'];
        $submit = $_POST['submit'];
		echo 'Are You Sure You Want To Modify Device with Auto ID: '.$auto_id.'';
        echo '<form id="modifyForm" method="post" action="modify.php">';
        echo '<input type="hidden" name="auto_id" value="'.$auto_id.'">';
        echo '<input type="hidden" name="submit" value="'.$submit.'">';
		echo '<button type="submit" name="submit" value="modify_device">Modify</button>';
        echo '</form>';
        echo '<script>';
		echo 'console.log("Submitting form with auto_id: '.$auto_id.'");';
		echo 'document.getElementById("modifyForm").submit();';
		echo '</script>';
        exit();
}
else
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select distinct (`type`) from `equipment_prod`";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="search_by_type">Search by type</button>';
	echo '</form>';

	$sql="Select distinct (`manufacturer`) from `equipment_prod`";
	$result=$dblink->query($sql) or
			die("Something went wrong with $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="search_by_manufacturer">Search by Manufacturer</button>';
	echo '</form>';
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="search_by_serial_num">Search by Serial Number</button>';
	echo '</form>';
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="search_by_all">Search by All</button>';
	echo '</form>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>";
}

?>
