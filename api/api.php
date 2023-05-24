<?php
// Header Info
header('Content-type: application/json');
header('HTTP/1.1 200 OK');
$url=$_SERVER['REQUEST_URI']; // request URI component
// Parse the url 
$path=parse_url($url,PHP_URL_PATH);
// Explode the path to its components
$pathComponents=explode("/", trim($path,"/"));
// Grab the second component pathComponents[1]
// This is the endpoint name 
$endPoint=$pathComponents[1];

// function to connect to the database
function db_iconnect($dbName)
{
	$un="webuser";
	$pw="E!.Sx[U@q5yOIt_K";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}

switch($endPoint)
{
	case "search_type":
		include("search_type.php");
		break;
	case "search_manufacturer":
		include("search_manu.php");
		break;
	case "search_serialNumber":
		include("search_serialNumber.php");
		break;
	case "search_all":
		include("search_all.php");
		break;
	case "insert_type":
		include("insert_type.php");
		break;
	case "insert_manufacturer":
		include("insert_manufacturer.php");
		break;
	case "insert_device":
		include("insert_device.php");
		break;
	default:
		$output[]='Status: Error';
		$output[]='MSG: '.$endPoint.' Endpoint Not Found';
		$output[]='Action: None';
		$responseData=json_encode($output);
		echo $responseData;
}
?>