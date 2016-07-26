<?php
require_once("dbconfig.php");

$query = "select b_file, b_filedate from $tbname where b_no=".$_GET['num'];
$result = $db->query($query);

$result = $result->fetch_assoc();

$dir = "./upload/";
$filename = iconv("UTF-8","CP949", $result['b_file']);
$filedate = iconv("UTF-8","CP949", $result['b_filedate']);

if(file_exists($dir.$filedate))
{
	header("Content-Type: Application/octet-stream");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($dir.$filedate));

	$fp = fopen($dir.$filedate, "rb");
	while(!feof($fp))
	{
		echo fread($fp, 1024);
	}
	fclose($fp);
}
else
{
	echo "<script>alert('파일이 없습니다.);";
	echo "history.back();</script>";
	exit;
}
$db->close();