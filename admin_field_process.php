<?php
	require_once("dbconfig.php");
	
	
	
	//컬럼 추가
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
		if ($mode == 'seqchange')
		
		
?>