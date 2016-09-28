<?php
require_once("dbconfig.php");

//admin_index.php 의 기능부분.
//모드 매개변수에 따라서 작동함	
if(!$_SESSION["session_id"]=="root")
{
	echo "<script>alert('관리자만 접근할 수 있습니다.');location.href='index.php';</script>";
}
$tableName = $_REQUEST['tableName'];
$description = $_REQUEST['description'];
$mode = $_REQUEST['mode'];
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
	//컬럼 삭제
	else if($mode=="delete_field"){
		
		$fname = $_REQUEST['b_fname'];
		
		$sql = "SELECT b_seq FROM tb_view WHERE b_fname = '$fname'";
		echo $sql;
		$result = $db->query($sql);
		$seq = $result->fetch_assoc();
		$seq = $seq['b_seq'];
		
		$cnt = "select count(b_seq) as cnt from tb_view where b_tbname = '$tbname'";
		$res = $db->query($cnt);
		$row = $res->fetch_assoc();
		$cnt = $row['cnt'];
		for($i=$seq+1;$i==$cnt;$i++)
		{
			$sql =  "UPDATE tb_view SET b_seq = $i-1  WHERE b_tbname = '$tbname' AND b_seq = $i";
			$result = $db->query($sql);
		}
		
		$sql = "alter table $tbname drop $fname";
		$result = $db->query($sql);
		$sql = "DELETE FROM tb_view WHERE b_fname = '$fname' AND b_tbname = '$tbname'";
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
    <script language="javascript">
    function Update_desc()
    {
        var before_name = tb_view.before_field_name.value;
        var update_name = tb_view.update_field_name.value;
        location.href="admin_field_process.php?mode=fd_update&tbname=<?php echo $tbname?>&update="+update_name+"&before="+before_name;
    }

    function add_column_send()
    {
     		form = document.add_column_form;
     		if (form.field_name.value == "")
     		{
      			alert("컬럼네임 입력 요망");
      			form.field_name.focus();
      			return false;
     		}
     		else if(form.field_desc.value == "")
     		{
      			alert("입력항목이름 입력 요망");
   		    	form.field_desc.focus();
   		    	return false;
     		}
     		else if(form.field_destitle.value == "")
     		{
      			alert("입력시 설명 입력 요망");
   		    	form.field_destitle.focus();
   		    	return false;
     		}
     		else
     		{
      			return true;
     		}
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
		
		$query = "SELECT b_fname FROM tb_view WHERE b_tbname = '$tbname' AND b_chksoo='1'";
		$chksoo = $db->query($query);
		$chksoo = $chksoo->fetch_assoc();
		?>
		</p>
		<form name="tb_view" method="post" action="admin_field_process.php?mode=visiblechange&tbname=<?php echo $tbname?>">
		    <caption>실제 테이블 뷰 정보</caption>
			<table border =1 >
						<tr align="center">
							<td>필드명</td>
							<td>형식</td>
							<td>노출여부</td>
							<td>제목</td>
							<td>입력시 설명</td>
							<td>순서변경</td>
							<td colspan=2>명령</td>
							<td>수량필드</td>
						</tr>
					<?php
					$i=1;
					 while($row = $result->fetch_assoc())
						
					{?>
						<tr align="center" >
							<td><?php echo $row['b_fname']?></td><!-- 필드명 -->
							<td><!--형식-->
							<?php 
								$b_type = explode(",", $row['b_type']); 	 //콤마로 형식뒤에 있는 자료들을 잘라서 b_type변수에 [배열]로 저장한다. //
								$b_type[0] == '' ? $type= TEXT :  $type = $b_type[0]; //공백이면 TEXT로 공백이 아니면 긁어온 타입으로 
								echo $type;
							?>
							</td>
							<input type ="hidden" name="b_fname[<?php echo $i?>]" value="<?php echo $row['b_fname']?>">
							<td><!-- 노출여부 -->
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
										if($mode=="update" && $desc ==  $row['b_description'] && $desc != '' ){?>
														<input type="hidden" name="before_field_name" class="textfield" id="field_0_3" type="text" value="<?=  $row['b_description']?>">
												<td><input name="update_field_name" class="textfield" id="field_0_3" type="text" value="<?= $row['b_description']?>"></td><!-- 제목(수정눌렀을때) -->
						<?php     }else{?>
												<td><?php echo $row['b_description'];?></td> <!-- 제목(일반상태)-->
							<?php } ?>
							<td><!-- 입력시 설명-->
							<?php 
							$row['b_destitle'] =='' ? $title = "　" : $title = $row['b_destitle'];
							echo $title;
							?>
							</td>
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
										<td><a href="#" Onclick="location.href='admin_process.php?mode=delete_field&desc=<?php echo $row['b_description']?>&b_fname=<?php echo $row['b_fname']?>&tbname=<?php echo $tbname?>'" >삭제</a></td>
									
									
									
						<td><?php if($row['b_fname']==$chksoo['b_fname']) 
							{ 		echo '<b>체크</b>';}
								  else{?>
									<a href="#" Onclick="location.href='admin_field_process.php?mode=chksoo&b_fname=<?php echo $row['b_fname']?>&tbname=<?php echo $tbname?>'">변경</a>
								<?php }?>
						</td>
						</tr>
						 <?php }?>
						 
					</table>
					<input type="submit" value="노출여부 저장" >
		</form>
		<br/>
		
		<div style="width:600px; height:400px; background-color:#eee; border:1px solid">
		<!-- 여기서부터~~~  -->
		<form name="add_column_form" action="admin_field_process.php?mode=add_column&tbname=<?php echo $tbname?>" method="post" onSubmit="return add_column_send();">
			<table border = 1 width = 600px; height= 400px>
				<tr>
					<td width="20%">컬럼네임</td>
					<td>
					<input name="field_name" title="필드" class="textfield" id="field_0_1" type="text" size="30" maxlength="64" value="<?php $field_name?>">
					</td>
				</tr>
				<tr>
					<td>형식</td>
					<td>
						<select name="b_type" id="field_0_2">
							<option  selected="selected" value="TEXT">한줄 입력칸(text)</option>
							<option value="TEXTAREA">여러줄 입력칸(textarea)</option>
							<option value="IMG">이미지(img)</option>
							<option value="URL">URL형식(url)</option>
							<option value="chksoo">*수량</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>필수항목</td>
					<td>
						<input type="radio"  name="field_null" checked="Null"  value="NOT NULL">예
						<input type="radio"  name="field_null" value="NULL">아니오
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
						<input name="field_destitle" id="field_0_7" type="text">
					</td>
				</tr>
			</table>
			<div style="width:50px; height:20px; margin:3px 0 0 100px; ">
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