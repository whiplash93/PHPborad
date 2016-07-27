<?php
	require_once("dbconfig.php");
	
	$sql = "alter table $tbname add $field_name $field_type($field_length) default '$field_default_type'  $field_null $field_extra";
	$result = $db->query($sql);
	if ($result){
		echo "필드가 추가되었음";
	}else
		echo "필드가 추가되지 못했음";
		
?>