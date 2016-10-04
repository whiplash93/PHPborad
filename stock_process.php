<?php
require_once("dbconfig.php");
$no = $_GET['bno'];
$sql = "SELECT * FROM tb_view WHERE b_chksoo = '1'"; // $row['b_fname']
$result = $db->query($sql);
$row = $result->fetch_assoc();
$fname = $row['b_fname']; //수량필드를 변수 fname으로 설정.

if(isset($row['b_fname'])){ //설정된 수량필드가 있는지 확인
	if($mode == 'in'){ //입고모드라면
		 stock_in();
	}
	else if ($mode == 'out'){ //출고 모드 라면
		stock_out();
	}
}
else {
	?>
	<script>
		alert('수량 필드가 없습니다. 설정하십시오.');
		location.replace("./admin_process.php?mode=update&tbname=<?php echo $tbname?>");
	</script>
	<?php 
}

//입고작업
function stock_in() {
	global $no, $tbname, $db, $fname;
	$sql = "SELECT * FROM $tbname WHERE b_no = $no"; //넘버를 이용해서 해당 번호의 수량필드를 검색 
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$soo = $row[$fname]; //실제 수량 값이 들어가있을 변수

	$sql = "UPDATE $tbname SET $fname = $soo+1  WHERE b_no = '$no'"; //수량을 1개 늘려줌
	$res = $db->query($sql);		
	?>
	<script>
		alert('입고 되었습니다. (현재 수량 : <?php echo $soo+1?>)');
		location.replace("./index.php?tbname=<?php echo $tbname?>");
	</script>
	<?
}

//출고 작업
function stock_out(){
	global $no, $tbname, $db, $fname;
	$sql = "SELECT * FROM $tbname WHERE b_no = $no"; //넘버를 이용해서 해당 번호의 수량필드를 검색 
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$soo = $row[$fname];
	if($row[$fname] <= 0){ //해당 번호의 수량필드에서 수량이 0인지 
	?>
		<script>
			alert('수량이 0이거나 - 입니다. 더 이상 줄어들 수 없습니다.');
			location.replace("./index.php?tbname=<?php echo $tbname?>");
		</script>
	<?php 
	}else { //0과 -가 아닐때 
		$sql = "UPDATE $tbname SET $fname = $soo-1  WHERE b_no = '$no'";
		$res = $db->query($sql);
		?>
		<script>
			alert('출고 되었습니다. (현재 수량 : <?php echo $soo-1?>)');
			location.replace("./index.php?tbname=<?php echo $tbname?>");
		</script>
		<?
	}
	//해당 번호의 수량이 1씩 줄어들어야함. 
	//no는 제품코드에 해당함. 바코드로 찍을때 어떤 정보가 넘어오는지는 모르지만 no 같이 제품코드 같은게 같이 넘어와야함.	
}