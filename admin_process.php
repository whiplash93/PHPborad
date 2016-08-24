<?php
require_once("dbconfig.php");
//admin_index.php 의 기능부분.
//모드 매개변수에 따라서 작동함	
if(!$_SESSION["session_id"]=="root")
{
	echo "<script>alert('관리자만 접근할 수 있습니다.');location.href='index.php';</script>";
}
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
			
			//tb_view에도 추가해줘야함.
			$sql = "insert into tb_view (b_fname, b_tbname, b_visible, b_seq, b_description)
					values ('b_no', '$tableName', 1, 1, '번호')
					,('b_title', '$tableName', 1, 2, '제목')
					,('b_id', '$tableName', 1, 3, '작성자')
					,('b_hit', '$tableName', 1, 4, '조회수')
					,('b_date', '$tableName', 1, 5, '작성일')";
			$result = $db->query($sql);
			
			
			echo "<script>alert('테이블을 생성하였습니다.');location.href='admin_index.php';</script>"; 
			//테이블생성 성공
		}
		else {"<script>alert('테이블을 생성실패.');location.href='admin_index.php';</script>";
		} //테이블생성 실패
	}
		//테이블 삭제
	else if($mode=="delete"){
		$sql = "DROP TABLE $tbname";
		$result = $db->query($sql); //DB에서 테이블 삭제
		
		$sql = "delete from tb_board where name='$tbname'";
		$result = $db->query($sql); //테이블관리 테이블에서 데이터 삭제
		
		$sql = "delete from tb_view where b_tbname='$tbname'";
		$result = $db->query($sql); //tb_view 테이블에서 데이터 삭제
		
		$sql = "delete from tb_freecomment where b_name='$tbname'";
		$result = $db->query($sql); //댓글관리 테이블에서 데이터 삭제
		
		echo "<script>alert('테이블을 삭제하였습니다.');location.href='admin_index.php';</script>"; 
		//테이블삭제 성공
	}
	//필드 삭제
	else if($mode=="delete_field"){
		$sql = "alter table $tbname drop $fdname";
		$result = $db->query($sql);
		$sql = "DELETE FROM tb_view WHERE b_fname = '$fdname' AND b_tbname = '$tbname'";
		$result = $db->query($sql);
		if($result){
			echo "<script>alert('컬럼을 삭제 하였습니다.');history.go(-1);</script>";
			 //컬럼 삭제 성공
		}
		else {echo "<script>alert('컬럼 삭제 실패 .');history.go(-1);</script>";
		} //컬럼 삭제 실패 
	}
	//테이블 수정을 눌렀을때. 
	else if($mode=="update"){
		$query = " DESC $tbname ";
		$result = $db->query($query);
?>
<html>
 <head>
  <title>관리자 페이지입니다.</title>
    <script>
    function Update_desc()
    {
        var before_name = tb_view.before_field_name.value;
        var update_name = tb_view.update_field_name.value;
        location.href="admin_field_process.php?mode=fd_update&tbname=<?php echo $tbname?>&update="+update_name+"&before="+before_name;
    }
    </script>
 </head>
 <body>
 <a href="#" Onclick="location.href='admin_index.php'">테이블 목록으로 돌아가기</a><br/>
 
 <?php 
		$query = "SELECT * FROM tb_view WHERE b_tbname = '$tbname' ORDER BY b_seq";
		$result = $db->query($query);
		$count = "select count(b_seq) as cnt from tb_view where b_tbname = '$tbname'";
		$res = $db->query($count);
		$row = $res->fetch_assoc();
		$count = $row['cnt'];
		?>
		</p>
		<form name="tb_view" method="post" action="admin_field_process.php?mode=visiblechange&tbname=<?php echo $tbname?>">
		    <caption>실제 테이블 뷰 정보</caption>
			<table border =1 >
						<tr align="center">
							<td>필드명</td>
							<td>형식</td>
							<td>노출여부</td>
							<td>보여질 텍스트</td>
							<td>순서변경</td>
							<td colspan=2>명령</td>
						</tr>
					<?php
					$i=1;
					 while($row = $result->fetch_assoc())
						
					{?>
						<tr align="center" >
							<td><?php echo $row['b_fname']?></td>
							<td>업뎃예정</td>
							<input type ="hidden" name="b_fname[<?php echo $i?>]" value="<?php echo $row['b_fname']?>">
							<td>
							<?php if ($row['b_visible'] == "1") { ?>
													<input type="radio"  name="visible[<?= $i ?>]" checked="on"  value="true">노출
													<input type="radio"  name="visible[<?= $i ?>]" value="false">노출안함
													<?php }
												else if($row['b_visible'] == "0") {?>
													<input type="radio"  name="visible[<?= $i ?>]" value="true">노출
													<input type="radio"  name="visible[<?= $i ?>]" checked="on" value="false">노출안함
											 <?php }?>
							</td>
							<?php   //테이블 수정하기 상태에서 필드네임 매개변수로 넘어온게 지금 돌리고있는 쿼리의 Field명과 일치한다면 /  즉 수정버튼을 눌렀을때를 말함.
										if($mode=="update" && $desc ==  $row['b_description']){?>
														<input type="hidden" name="before_field_name" class="textfield" id="field_0_3" type="text" value="<?=  $row['b_description']?>">
												<td><input name="update_field_name" class="textfield" id="field_0_3" type="text" value="<?= $row['b_description']?>"></td>
						<?php     }else{?>
												<td><?php echo $row['b_description'];?></td> 
							<?php } ?>
						<td> 
						<?php if( $row['b_seq'] == 1){ //순서가 첫번째일경우 ?>
							위로 |
							<a href ="#" Onclick="location.href='admin_field_process.php?mode=seqdown&seq=<?= $row['b_seq']?>&tbname=<?php echo $tbname?>'">아래로</a></td>
							<!--  순서가 첫번째이면 순서변경 위로 비활성화 --> 
							<?php }
						else if( $row['b_seq'] == $count){ ?><!--  순서가 마지막이면 순서변경 아래로 비활성화 --> 
							<a href ="#" Onclick="location.href='admin_field_process.php?mode=sequp&seq=<?= $row['b_seq']?>&tbname=<?php echo $tbname?>'">위로</a> | 
							아래로</td>
						<?php }
						else{?>
							<a href ="#" Onclick="location.href='admin_field_process.php?mode=sequp&seq=<?= $row['b_seq']?>&tbname=<?php echo $tbname?>'">위로</a> |
							<a href ="#" Onclick="location.href='admin_field_process.php?mode=seqdown&seq=<?= $row['b_seq']?>&tbname=<?php echo $tbname?>'">아래로</a></td>
					<?php }
						$i++;
						if($mode=="update" && $desc !=  $row['b_description']){?> <!-- 필드네임값이 없을때. 즉 컬럼 수정하기 버튼을 누르기 전이다. -->
											<td><a href="#" Onclick="location.href='admin_process.php?mode=update&desc=<?php echo $row['b_description']?>&tbname=<?php echo $tbname?>'">수정</a></td>
										<?php }
										if($mode=="update" && $desc ==  $row['b_description']){?> <!-- 필드네임값이 있으면. 즉 컬럼 수정하기 버튼을 누른 후. -->
											<td><input type="button" Onclick="Update_desc()"  value="수정"></td>
										<?php }?>
										<td><a href="#" Onclick="location.href='admin_process.php?mode=delete_field&desc=<?php echo $row['b_description']?>&tbname=<?php echo $tbname?>'" >삭제</a></td>
									</tr>
						 <?php }?>
					</table>
					<input type="submit" value="노출여부 저장" >
		</form>
		<br/>
		
		
		
		
		컬럼을 추가합니다. <br>
		<font color="red" size="2">*추가시 마지막 컬럼 다음으로 추가됩니다.</font>
		<div style="width:400px; height:260px; background-color:#eee; border:1px solid">
		  <form action="admin_field_process.php?mode=form&tbname=<?php echo $tbname?>" method="post">
			<table border = 1 width = 400px;>
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
							<option selected="selected" value="">None</option>
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
					<td> 보여질 텍스트</td>
					<td>
						<input name="field_desc" id="field_0_7" type="text">
					</td>
				</tr>
			</table>
			<div style="width:50px; height:20px; margin:3px 0 0 100px; ">
				<input name="do_save_data" type="submit" value="   저    장    " />
		  </div>
		  </form>
		</div>
		<br/>
		
		
		<!-- 여기서부터~~~  -->
		<form action="admin_field_process.php?mode=form&tbname=<?php echo $tbname?>" method="post">
			<table border = 1 width = 400px; height= 400px>
				<tr>
					<td>컬럼네임</td>
					<td>
					<input name="field_name" title="필드" class="textfield" id="field_0_1" type="text" size="30" maxlength="64" value="<?php $field_name?>">
					</td>
				</tr>
				<tr>
					<td>형식</td>
					<td>
						<select name="field_type" id="field_0_2">
							<option  selected="selected" value="TEXT">한줄 입력칸(text)</option>
							<option value="URL">URL형식(url)</option>
							<option value="EMAIL">이메일 형식(email)</option>
							<option value="PHONE">전화번호 형식(phone)</option>
								<option value="TEXTAREA">여러 줄 입력칸(textarea)</option>
								<option value="PASSWORD">숨김입력칸(password)</option>
								<option value="CHECKBOX">다중 선택(checkbox)	</option>
								<option value="SELECT">단일 선택(select)	</option>
								<option value="RADIO">라디오 버튼(radio)	</option>
								<option value="ZIP">한국주소(zip)	</option>
								<option value="DATE">일자(연월일)	</option>
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
						<input name="field_default_value" class="textfield" id="field_0_4" type="text" size="12" value="<?php $field_default_value?>">
					</td>
				
				<tr>
					<td>필수항목</td>
					<td>
						<input type="radio"  name="NULL" checked="on"  value="true">예
						<input type="radio"  name="NULL" value="false">아니오
					</td>
				</tr>
				<tr>
					<td> 입력항목 이름</td>
					<td>
						<input name="field_desc" id="field_0_7" type="text">
					</td>
				</tr>
				<tr>
					<td>입력시 설명</td>
					<td>
						<input name="field_desc" id="field_0_7" type="text">
					</td>
				</tr>
			</table>
			<div style="width:50px; height:20px; margin:3px 0 0 100px; ">
				<input name="do_save_data" type="submit" value="   저    장    " />
		  </div>
		  </form>
		
  </body>
</html>
<?php }
	else
		echo "<script>alert('잘못된 접근입니다.');location.href='index.php';</script>";
?>