<?php
	require_once("dbconfig.php");
	
	//관리자 세션 없으면 못들어옴.
	if(!$_SESSION["session_id"]=="root")
	{
		echo "<script>alert('관리자만 접근할 수 있습니다.');location.href='index.php';</script>";
	}
	$mode = $_REQUEST['mode'];
	//tbname 은 dbconfig 에서 받아옴.
		
//컬럼 이름변경
		if ($mode == 'fd_update'){
			
			$before_fdname = $_POST['before_field_name'];
			$type = $_POST['update_field_type'];
			$update_fdname = $_POST['update_field_name'];
			
			$sql = "UPDATE  tb_view SET b_description = '$update_fdname' WHERE b_description = '$before_fdname' AND b_tbname = '$tbname'";
			$result = $db->query($sql);
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
			
			foreach($_REQUEST["b_fname"] as $key=>$val) :
						if(trim($val)) :
								$b_fname[$key] = trim($val);
						endif;
			endforeach;
			
			foreach($_REQUEST["visible"] as $key=>$val) :
						if(trim($val)) :
								$visible[$key] = trim($val);
						endif;
			endforeach;
			
			
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
		
//순서변경 모드 중 순서올리기
		if($mode == 'sequp'){
			//b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$seq = $_REQUEST['seq'];
			$nummin = $seq-1; //b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$sql =  "UPDATE  tb_view SET b_seq= 0 WHERE b_tbname = '$tbname' AND b_seq = $nummin";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $nummin WHERE b_tbname = '$tbname' AND b_seq = $seq";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $seq WHERE b_tbname = '$tbname' AND b_seq = 0";
			$result = $db->query($sql);
			
			if ($result){?>
					<script>
						location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
					</script>
<?php }
			else{?>
					<script>
						location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
					</script>
<?php }
		}
//순서 변경모드 중 순서내리기		
		if($mode == 'seqdown'){
			//b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$seq = $_REQUEST['seq'];
			$numplus = $seq+1; //b_seq는 admin process에서 넘겨주는 매개변수 b_seq 의 값이다.
			$sql =  "UPDATE  tb_view SET b_seq= 0 WHERE b_tbname = '$tbname' AND b_seq = $numplus";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $numplus WHERE b_tbname = '$tbname' AND b_seq = $seq";
			$result = $db->query($sql);
			$sql =  "UPDATE  tb_view SET b_seq= $seq WHERE b_tbname = '$tbname' AND b_seq = 0";
			$result = $db->query($sql);
			
			if ($result){?>
				<script>
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
<?php  }
			else{?>
				<script>
					location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
				</script>
<?php }
		}
//컬럼 추가 모드
		if($mode == 'add_column'){
			$b_type = $_REQUEST['b_type'];
			$field_type = $_REQUEST['field_type'];
			$field_default_type = $_REQUEST['field_default_type'];
			$field_null = $_REQUEST['field_null'];
			$field_extra = $_REQUEST['field_extra'];
			$field_name = $_REQUEST['field_name'];
			$field_default_value  = $_REQUEST['field_default_value'];
			$field_desc = $_REQUEST['field_desc'];
			$field_destitle = $_REQUEST['field_destitle'];
			Switch ($b_type)
			{
				case TEXT: $field_type='varchar(300)'; break;
				case URL: $field_type='varchar(300)'; break;
				case TEXTAREA: $field_type='text'; break;
				case IMG: $field_type='varchar(300)'; break;
				case chksoo: $field_type='int(11)'; $b_type = 'TEXT'; break;
				
			}
			
			echo $b_type;
			echo $field_type;
				$sql = "ALTER TABLE $tbname ADD $field_name $field_type ";
			if($field_default_type != ''){
				$sql .= "DEFAULT '$field_default_type'  $field_null ";
			}
			elseif($field_default_type ==''){
				$sql .= "$field_null $field_extra ";
			}
			echo $sql;
			$result = $db->query($sql);
			
			if ($result){
				$count = "select count(b_seq) as cnt from tb_view where b_tbname = '$tbname'";
				$res = $db->query($count);
				$row = $res->fetch_assoc();
				$count = $row['cnt']+1;
				if(isset($field_default_value))
				{
					$b_type .= ','.$field_default_value;
				}
				$sql = "INSERT INTO tb_view(b_fname, b_tbname, b_type, b_visible, b_seq, b_description, b_destitle, b_chksoo) VALUES ('$field_name', '$tbname', '$b_type', 1, $count, '$field_desc','$field_destitle','')";
				$result = $db->query($sql);
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
		}// 컬럼 추가모드 끝
//수량 필드변경
		if($mode == 'chksoo'){
			$b_fname = $_REQUEST["b_fname"];
			
			$query = " DESC $tbname"; //테이블 정보 조회 여기선 데이터타입을 가져오기 위함
			$result = $db->query($query);
			while($row = $result->fetch_assoc()){
				//echo '필드네임 : '.$row['Field'].' 타입 : '.$row['Type'];
				if($row['Field']==$b_fname && $row['Type'] =='int(11)'){
					echo '필드네임 : '.$row['Field'].' 타입 : '.$row['Type'];
					echo '데이터타입 검사완료 int';
					$check = 1;
					continue;
				}
				elseif ($row['Field']==$b_fname && $row['Type'] <>'int(11)'){
					?>
						<script>
							alert('데이터 타입이 int 형이 아니므로 수량필드로 선택할 수 없습니다.');
							location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
						</script>
					<?php 
				}
			}
			
			if($check == 1) {
				echo "b_fname:".$b_fname;
				$sql = "SELECT b_fname FROM tb_view WHERE b_tbname = '$tbname' AND b_chksoo='1'";
				$result = $db->query($sql);
				$chksoo = $result->fetch_assoc();
				$chksoo = $chksoo['b_fname'];
				echo "chksoo :".$chksoo;
				//$chksoo['b_fname'] 는 현재의 수량필드
				
				$sql = "UPDATE tb_view SET b_chksoo ='' WHERE b_fname = '$chksoo' AND b_tbname = '$tbname'";
				$res = $db->query($sql);
				echo $sql;
				$sql = "UPDATE tb_view SET b_chksoo ='1' WHERE b_fname = '$b_fname'  AND b_tbname = '$tbname'";
				$res = $db->query($sql);
				echo $sql;
	
					if ($res){?>
						<script>
							alert('수량필드가 변경되었습니다.');
							location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
						</script>
					<?}else {?>
						<script>
							alert('수량필드가 변경되지못했습니다.');
							location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
						</script>
					<?}
			}
		}
?>