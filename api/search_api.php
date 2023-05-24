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
}
		/*{Search By Type with Manufacturer filter Results }*/
else if (isset($_POST['submit']) && ($_POST['submit']) == "submit_manu_filter")
{
	$dblink=db_iconnect("test");
   
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
    echo '<h3>Search by Type: '.$query.'</h3>';
	echo '<h4>Filter by Manufactuerer: '.$query2.'</h4>';
	
	$curl= curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/search_type?type=$query&manufacturer=$query2&serial_num=$query3",
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
		echo "<h3> CURL ERROR Search By Type API#: $err</h3>";
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
		$data=json_decode($tmp[1],true);
		if (empty($data)) 
		{
    		// Display your custom message here
    		echo '<h3>No results found.</h3>';
		}
		else
		{
			echo '<table>';
			echo '<tr><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
			echo '<tr>';
			foreach($data as $key=>$value)
			{
				$tmp=explode(",",$value);
				echo '<tr>';
				echo '<td>'.$tmp[0].'</td>';
				echo '<td>'.$tmp[1].'</td>';
				echo '<td>'.$tmp[2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
	}
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
	$query=$_POST['manufacturer'];
    $query2=$_POST['type'];
	$query3=$_POST['serial_num'];
	echo '<h3>Search by Manufacturer: '.$query.'</h3>';
	echo '<h4>Filter by Type: '.$query2.'</h4>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
    
  	$curl= curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/search_manufacturer?manufacturer=$query&type=$query2&serial_num=$query3",
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
		echo "<h3> CURL ERROR Search By Manufacturer API#: $err</h3>";
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
		$data=json_decode($tmp[1],true);
		if (empty($data)) 
		{
    		// Display your custom message here
    		echo '<h3>No results found.</h3>';
		}
		else
		{
			echo '<table>';
			echo '<tr><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
			echo '<tr>';
			foreach($data as $key=>$value)
			{
				$tmp=explode(",",$value);
				echo '<tr>';
				echo '<td>'.$tmp[0].'</td>';
				echo '<td>'.$tmp[1].'</td>';
				echo '<td>'.$tmp[2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
	}
	
	
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
	echo '<h3>Search by Serial Number: '.$query.'</h3>';
	
	$curl= curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/search_serialNumber?serial_num=$query",
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
		echo "<h3> CURL ERROR Search By Serial Number API#: $err</h3>";
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
		$data=json_decode($tmp[1],true);
		if (empty($data)) 
		{
    		// Display your custom message here
    		echo '<h3>No results found.</h3>';
		}
		else
		{
			echo '<table>';
			echo '<tr><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
			echo '<tr>';
			foreach($data as $key=>$value)
			{
				$tmp=explode(",",$value);
				echo '<tr>';
				echo '<td>'.$tmp[0].'</td>';
				echo '<td>'.$tmp[1].'</td>';
				echo '<td>'.$tmp[2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
	}
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
	$curl= curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-18-217-128-4.us-east-2.compute.amazonaws.com/api/search_all?type=$query&manufacturer=$query2&serial_num=$query3",
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
		echo "<h3> CURL ERROR Search By All API#: $err</h3>";
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
		$data=json_decode($tmp[1],true);
		
		if (empty($data)) 
		{
    		// Display your custom message here
    		echo '<h3>No results found.</h3>';
		}
		else
		{
			echo '<table>';
			echo '<tr><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
			echo '<tr>';
			foreach($data as $key=>$value)
			{
				$tmp=explode(",",$value);
				echo '<tr>';
				echo '<td>'.$tmp[0].'</td>';
				echo '<td>'.$tmp[1].'</td>';
				echo '<td>'.$tmp[2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
	}
}
else if(isset($_POST['submit']) && ($_POST['submit']) == "modify_device")
{
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
