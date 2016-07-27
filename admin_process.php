<?php
require_once("dbconfig.php");
	//테이블 생성 
	
// 	if ($mode<>"create" || $mode<>"update" || $mode<>"delete" || $mode<>"delete_field"){
// 		echo "<script>alert('잘못된 접근입니다.');location.href='index.php';</script>";
// 	}
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
		else {"<script>alert('테이블을 생성실패.');location.href='admin_index.php';</script>";
		} //테이블생성 실패
	}
		//테이블 삭제
	else if($mode=="delete"){
		$sql = "DROP TABLE $tbname";
		$result = $db->query($sql); //DB에서 테이블 삭제
		$sql = "delete from tb_board where name='$tbname'";
		$result = $db->query($sql); //테이블에서 데이터 삭제
		echo "<script>alert('테이블을 삭제하였습니다.');location.href='admin_index.php';</script>"; //테이블삭제 성공
	}

	else if($mode=="delete_field"){
		$sql = "alter table $tbname drop $fdname";
		$result = $db->query($sql);
		if($result){
			echo "<script>alert('컬럼을 삭제 하였습니다.');history.go(-1);</script>"; //컬럼 삭제 성공
		}
		else {echo "<script>alert('컬럼 삭제 실패 .');history.go(-1);</script>";
		} //컬럼 삭제 실패 
	}
	//테이블 수정
	else if($mode=="update"){
		$query = " DESC $tbname ";
		$result = $db->query($query);
?>
<html>
 <head>
  <title>관리자 페이지입니다.</title>
    <script>
		function refresh_page(){
			
		}
    </script>
 </head>
 <body>
 <a href="#" Onclick="location.href='admin_index.php'">테이블 목록으로 돌아가기</a><br/>
    <caption>테이블 정보</caption>
		<table border =1 >
			<tr align="center">
				<td>필드이름</td>
				<td>타입</td>
				<td>Null</td>
				<td>Key</td>
				<td>기본값</td>
				<td>Extra</td>
				<td colspan=2>명령</td>
			</tr>
		<?php while($row = $result->fetch_assoc())
		{?>
			<tr align="center" >
				<td><?php echo $row['Field'];?></td>
				<td><?php echo $row['Type'];?></td>
				<td><?php echo $row['Null'];?></td>
				<td><?php echo $row['Key'];?></td>
				<td><?php echo ($row['Default'] == "")? "NONE" : $row['Default'];?></td> <!-- 삼항연산자를 사용해서 빈칸이면 NONE을 준다. 값이 빈칸이 아니면 테이블에 있는 값을 표시한다. -->
				<td><?php echo $row['Extra'];?></td>
				<td><a href="#" Onclick="location.href='admin_process.php?mode=update_field&field_name=<?php echo $row['Field']?>&tbname=<?php echo $tbname?>&field_type=<?php echo $row['Type']?>&field_length=<?php echo $row['Type']?>'">수정</a></td>
				<td><a href="#" Onclick="location.href='admin_process.php?mode=delete_field&fdname=<?php echo $row['Field']?>&tbname=<?php echo $tbname?>'" >삭제</a></td>
				
			</tr>
 <?php }?>
		</table>
		<br/>
		필드를 추가하거나 수정합니다. <br>
		<font color="red" size="2">*추가시 마지막 필드 다음으로 추가됩니다.</font>
		<div style="width:400px; height:220px; background-color:#eee; border:1px solid">
		  <form action="admin_field_process.php" method="post">
			<table border = 1>
				<tr>
					<td>필드</td>
					<td>
					<input name="field_name" title="필드" class="textfield" id="field_0_1" type="text" size="30" maxlength="64" value="<?php $field_name?>">
					</td>
				</tr>
				<tr>
					<td>종류</td>
					<td>
						<select name="field_type" id="field_0_2">
							<option  selected="selected" value="INT">INT</option>
							<option value="VARCHAR">VARCHAR</option>
							<option value="TEXT">TEXT</option>
							<option value="DATE">DATE</option>
							
							<optgroup label="NUMERIC">
								<option value="TINYINT">TINYINT</option>
								<option value="SMALLINT">SMALLINT</option>
								<option value="MEDIUMINT">MEDIUMINT</option>
								<option value="INT">INT</option>
								<option value="BIGINT">BIGINT</option>
								<option value="-">-</option>
								<option value="DECIMAL">DECIMAL</option>
								<option value="FLOAT">FLOAT</option>
								<option value="DOUBLE">DOUBLE</option>
								<option value="REAL">REAL</option>
								<option value="-">-</option>
								<option value="BIT">BIT</option>
								<option value="BOOL">BOOL</option>
								<option value="SERIAL">SERIAL</option>
							</optgroup>
							
							<optgroup label="DATE and TIME">
								<option value="DATE">DATE</option>
								<option value="DATETIME">DATETIME</option>
								<option value="TIMESTAMP">TIMESTAMP</option>
								<option value="TIME">TIME</option>
								<option value="YEAR">YEAR</option>
							</optgroup>
							
							<optgroup label="STRING">
								<option value="CHAR">CHAR</option>
								<option value="VARCHAR">VARCHAR</option>
								<option value="-">-</option>
								<option value="TINYTEXT">TINYTEXT</option>
								<option value="TEXT">TEXT</option>
								<option value="MEDIUMTEXT">MEDIUMTEXT</option>
								<option value="LONGTEXT">LONGTEXT</option>
								<option value="-">-</option>
								<option value="BINARY">BINARY</option>
								<option value="VARBINARY">VARBINARY</option>
								<option value="-">-</option>
								<option value="TINYBLOB">TINYBLOB</option>
								<option value="MEDIUMBLOB">MEDIUMBLOB</option>
								<option value="BLOB">BLOB</option>
								<option value="LONGBLOB">LONGBLOB</option>
								<option value="-">-</option>
								<option value="ENUM">ENUM</option>
								<option value="SET">SET</option>
							</optgroup>
							
							<optgroup label="SPATIAL">
								<option value="GEOMETRY">GEOMETRY</option>
								<option value="POINT">POINT</option>
								<option value="LINESTRING">LINESTRING</option>
								<option value="POLYGON">POLYGON</option>
								<option value="MULTIPOINT">MULTIPOINT</option>
								<option value="MULTILINESTRING">MULTILINESTRING</option>
								<option value="MULTIPOLYGON">MULTIPOLYGON</option>
								<option value="GEOMETRYCOLLECTION">GEOMETRYCOLLECTION</option>
							</optgroup>    
						
						</select>
					</td>
				</tr>
				<tr>
					<td>길이/값</td>
					<td>
						<input name="field_length" class="textfield" id="field_0_3" type="text" size="30" value="<?php $field_length?>">
					</td>
				</tr>
				<tr>
					<td>기본값</td>
					<td>
						<select name="field_default_type">
							<option selected="selected" value="NONE">None</option>
							<option value="USER_DEFINED">As defined:</option>
							<option value="NULL">NULL</option>
							<option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
						</select>
						<br/>
						<input name="field_default_value" class="textfield" id="field_0_4" type="text" size="12" value="<?php $field_default_value?>">
					</td>
				<tr>
					<td>Null</td>
					<td>
						<input name="field_null" id="field_0_7" type="checkbox" value="NULL">
					</td>
				</tr>
				<tr>
					<td>AUTO_INCREMENT</td>
					<td>
						<input name="field_extra" id="field_0_8" type="checkbox" value="AUTO_INCREMENT">
					</td>
				</tr>
			</table>
			<div style="width:50px; height:20px; margin:3px 0 0 160px; ">
				<input name="do_save_data" type="submit" value="   저    장    " />
		  </div>
		  </form>
		</div>
  </body>
</html>
<?php }
	else
		echo "<script>alert('잘못된 접근입니다.');location.href='index.php';</script>";
?>