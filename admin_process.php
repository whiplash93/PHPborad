<?php
require_once("dbconfig.php");
	//테이블 생성 
	if ($mode=="create"){
		$sql = "create table $tableName (
				b_no int unsigned not null primary key auto_increment,
				b_title varchar(100) not null,
				b_content text not null,
				b_date datetime not null,
				b_hit int unsigned not null default 0,
				b_id varchar(20) not null,
				b_password varchar(100) not null,
				b_file varchar(255) not null,
				b_filedate varchar(255) not null
			   )";
		$result = $db->query($sql);

		if($result){
			$sql = "insert into tb_board (name, description) VALUES('$tableName','$description')";
			$result = $db->query($sql);
			echo "<script>alert('테이블을 생성하였습니다.');location.href='admin_index.php';</script>"; //테이블생성 성공
		}
		else {"<script>alert('테이블을 생성실패.');location.href='admin_index.php';</script>";} //테이블생성 실패
	}
	
	else if($mode=="delete"){
		$sql = "DROP TABLE $name";
		$result = $db->query($sql); //DB에서 테이블 삭제
		$sql = "delete from tb_board where name='$name'";
		$result = $db->query($sql); //테이블에서 데이터 삭제
		echo "<script>alert('테이블을 삭제하였습니다.');location.href='admin_index.php';</script>"; //테이블생성 성공
	}
	else
		echo "<script>alert('잘못된 접근입니다.');location.href='index.php';</script>";
?>
