<?php
///home/ubuntu/
//$files1 = scandir('/home/ubuntu/');
/*$dir = '/home/ubuntu/';
$files = glob($dir . 'equippartama*');
$file_names = array();
foreach($files as $file) {
    if(is_file($file)) {
        $file_names[] = basename($file);
    }
}*/
$files=array('equipmentpart2.txt'); //USE WHATEVER FILES YOU CREATED IN THE FILE SPLIT
foreach($files as $key=>$value)
{
    shell_exec("/usr/bin/php /var/www/html/import2.php $key $value > /var/www/html/$value.log 2>/var/www/html/$value.log &");
}
echo "Main Process Done\n";
?>