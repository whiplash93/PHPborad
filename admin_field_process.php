<?php
	require_once("dbconfig.php");
	//컬럼 추가
	if(!$_SESSION["session_id"]=="root")
	{
		echo "<script>alert('관리자만 접근할 수 있습니다.');location.href='index.php';</script>";
	}
		
		if ($mode == 'form'){
			if($field_null != 'Null'){
				$field_null = 'NOT NULL';
			}
			if($field_length >= 1){
				$sql = "ALTER TABLE $tbname ADD $field_name $field_type($field_length) ";
			}
			elseif ($field_length == ''){
				$sql = "ALTER TABLE $tbname ADD $field_name $field_type ";
			}
			if($field_default_type != ''){
				$sql .= "DEFAULT '$field_default_type'  $field_null $field_extra ";
			}
			elseif($field_default_type ==''){
				$sql .= "$field_null $field_extra ";
			}
			$result = $db->query($sql);
			if ($result){
				?>
				<script>
					alert('컬럼이 추가 되었습니다.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php }
			else{
				?>
				<script>
					alert('오류 : 컬럼이 추가되지 못했습니다.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php 
			} 
		}
		
		
		
		
//컬럼 이름변경
		if ($mode == 'fd_update'){
			
			$before_fdname = $_POST['before_field_name'];
			$type = $_POST['update_field_type'];
			$update_fdname = $_POST['update_field_name'];
			
			
			$sql = "ALTER TABLE `$tbname` CHANGE `$before_fdname` `$update_fdname` $type";
			$result = $db->query($sql);
			echo $sql;
			if ($result){
				?>
				<script>
					alert(' 컬럼 이름이 변경되었습니다.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php  }
			else
				?>
				<script>
					alert(' 오류 : 컬럼 이름 변경실패.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php
		}
		
		
//실제 테이블 뷰 순서변경
		if ($mode == 'visiblechange'){//모드 매개변수가 visiblechange즉 노출설정 변경모드라면
			$i=1;
			while (isset($b_fname[$i])){
				if($visible[$i] == 'true') {
					$visible[$i]= '1';
				} //트루면 1로, false면 0으로 바꿔주는 작업
				else{
					$visible[$i]= '0';
				}
				$sql = "UPDATE  tb_view SET b_visible= '$visible[$i]' WHERE b_fname = '$b_fname[$i]' and b_tbname = '$tbname'";
				$result = $db->query($sql);
 				$i++;
 				?>
 				<script>
 				alert('노출설정을 변경하였습니다.');
 				location.replace("./admin_process.php?mode=update&tbname=<?= $tbname?>");
 				</script>
 				<?php 
			}
		}
		//테이블 컬럼 순서변경 모드
		if($mode == 'sequp'){
			//b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$nummin = $seq-1; //b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$sql =  "UPDATE  tb_view SET b_seq= 0 WHERE b_tbname = '$tbname' AND b_seq = $nummin";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $nummin WHERE b_tbname = '$tbname' AND b_seq = $seq";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $seq WHERE b_tbname = '$tbname' AND b_seq = 0";
			$result = $db->query($sql);
			
			if ($result){
				?>
							<script>
								alert(' 순서 변경이 완료되었습니다.');
								location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
							</script>
<?php  }
			else{
			?>
							<script>
								alert(' 오류 : 컬럼 순서 변경실패.');
								location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
							</script>
							<?php
			}
		}
		if($mode == 'seqdown'){
			//b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$numplus = $seq+1; //b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$sql =  "UPDATE  tb_view SET b_seq= 0 WHERE b_tbname = '$tbname' AND b_seq = $numplus";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $numplus WHERE b_tbname = '$tbname' AND b_seq = $seq";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $seq WHERE b_tbname = '$tbname' AND b_seq = 0";
			$result = $db->query($sql);
			
			if ($result){
				?>
							<script>
								alert(' 순서 변경이 완료되었습니다.');
								location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
							</script>
<?php  }
			else{
			?>
							<script>
								alert(' 오류 : 컬럼 순서 변경실패.');
								location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
							</script>
							<?php
			}
		}
	
		
?>