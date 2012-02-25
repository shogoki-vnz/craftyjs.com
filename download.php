<?php
if(isset($_GET['c'])) {
	$file = "release/0.4.5/crafty-min.js";
} else {
	$file = "release/0.4.5/crafty.js";
}
$count = file_get_contents("counter.txt");
file_put_contents("counter.txt",$count+1);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);
?>