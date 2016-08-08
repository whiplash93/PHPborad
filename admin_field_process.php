<?php
	require_once("dbconfig.php");
		if ($mode == 'form'){
			$sql = "alter table $tbname add $field_name $field_type($field_length) default '$field_default_type'  $field_null $field_extra";
			$result = $db->query($sql);
			if ($result){
				?>
				<script>
					alert('필드가 추가 되었습니다.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php }
			else
				?>
				<script>
					alert('오류 : 필드가 추가되지 못했습니다.');
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
				<?php 
		}

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
?>