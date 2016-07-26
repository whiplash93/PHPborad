<?php
header('Content-Type: text/html; charset=utf-8');
$db = new mysqli('localhost', 'root', 'apmsetup', 'mydb');

if($db->connect_error) {
	die('데이터베이스 연결에 문제가 있습니다.\n관리자에게 문의 바랍니다.');
	}
	$db->set_charset('utf-8');
	$db->query("set session character_set_connection=utf8;");
	$db->query("set session character_set_results=utf8;");
	$db->query("set session character_set_client=utf8;");
?>