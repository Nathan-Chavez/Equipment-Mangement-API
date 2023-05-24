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
$dblink=db_iconnect("test");
echo "Hello from php process $argv[1] about to process file:$argv[2]\n";
$fp=fopen("/home/ubuntu/$argv[2]","r");
$count=0;
$time_start=microtime(true); 
$sql="Set autocommit=0";
$dblink->query($sql) or
	die("Something went wrong with $sql<br>\n".$dblink->error);
echo "PHP ID:$argv[1]-Start time is: $time_start\n";
while (($row=fgetcsv($fp)) !== FALSE)
{
	$sql="Select `auto_id` from `manufacturers` Where `manufacturer` = '$row[1]'";
	$res=$dblink->query($sql);
	if (!$res) {
		error_log("Something went wrong with SQL query: $sql - Error: ".$dblink->error,3,"log.txt");
		continue;
	}
	$rowData = $res->fetch_assoc();
	$manuData=$rowData['auto_id'];
	
	$sql="Select `auto_id` from `type` Where `type` = '$row[0]'";
	$res=$dblink->query($sql);
	if (!$res) {
    	error_log("Something went wrong with SQL query: $sql - Error: ".$dblink->error,3,"log.txt");
    	continue;
	}
	$rowData = $res->fetch_assoc();
	$typeData=$rowData['auto_id'];
	
	$sql="Insert ignore into `equipment` (`type`,`manufacture`,`serial_num`) values('$typeData','$manuData','$row[2]')";
	$dblink->query($sql);
	if (!$res) {
    	error_log("Something went wrong with SQL query: $sql - Error: ".$dblink->error,3,"log.txt");
    	continue;
	}
	$count++;
}
$time_end=microtime(true);
echo "PHP ID:$argv[1]-End Time:$time_end\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "PHP ID:$argv[1]-Execution time: $execution_time minutes or $seconds seconds.\n";
$rowsPerSecond=$count/$seconds;
echo "PHP ID:$argv[1]-Insert rate: $rowsPerSecond per second\n";
fclose($fp);
?>