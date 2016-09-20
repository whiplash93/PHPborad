<?php
	require_once("dbconfig.php");

	$mode = $_REQUEST['mode'];
	
	//$_POST['bno']이 있을 때만 $bno 선언
	if(isset($_POST['bno'])) {
		$bNo = $_POST['bno'];
	}
	
	//글 수정에서 삭제버튼 누르면 파일 삭제
	if($mode == 'update'){
		
		$sql = "SELECT * from $tbname where b_no = $bNo";
		$result = $db->query($sql);
		echo $sql;
		$row = $result->fetch_assoc();
		$b_file = $row['b_file'];
		$b_filedata =$row['b_filedata'];
		$filename = "upload/" .$b_filedata;
		$sql = 'update ' . $tbname . ' set b_file= "", b_filedate= "" where b_no = ' . $bNo;
		$result = $db->query($sql);
	
		if ( is_file($filename) ) {
			if ( is_writable($filename) ) {
				unlink($filename); //파일삭제
				echo '파일 삭제됨.';
			} else {
				echo '파일에 대한 쓰기(삭제) 권한 없음.';
			}
		}
		else{
			//echo '파일이 이미 삭제됬거나 존재하지 않음.';
		}
		?>
		<script>
		alert('첨부파일이 삭제 되었습니다.');
		location.replace("./write.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>");
		</script>
		exit;
		<?php


	}
	


	$bPassword = $_POST['bPassword'];

//글 삭제
	if(isset($bNo) && $_SESSION["session_id"])
	{
		$sql = "delete from $tbname where b_no = $bNo"; //tb_board 에서 삭제하고
		$result = $db->query($sql);
		$sql = "delete from tb_freecomment where b_no = $bNo AND b_name = $tbname"; //tb_freecomment 에서 댓글도 삭제해준다.
		$result = $db->query($sql);
	}
			
if(isset($bNo) && !$_SESSION["session_id"]) {
	//삭제 할 글의 비밀번호가 입력된 비밀번호와 맞는지 체크
	$sql = "select count(b_password) as cnt from $tbname where b_password=password($bPassword) and b_no = $bNo";
	$result = $db->query($sql);
	echo sql;
	$row = $result->fetch_assoc();
	//비밀번호가 맞다면 삭제 쿼리 작성
	if($row['cnt']) {
		$sql = "delete from $tbname where b_no = $bNo"; //tb_board 에서 삭제하고
		$result = $db->query($sql);
		$sql = "delete from tb_freecomment where b_no = $bNo AND b_name = $tbname"; //tb_freecomment 에서 댓글도 삭제해준다.
		$result = $db->query($sql);
	//틀리다면 메시지 출력 후 이전화면으로
	} else {
		$msg = '비밀번호가 맞지 않습니다.';
	?>
		<script>
			alert("<?php echo $msg?>");
			history.back();
		</script>
	<?php
		exit;
	}
}
//쿼리가 정상 실행 됐다면,
if($result) {
	$msg = '정상적으로 글이 삭제되었습니다.';
	$replaceURL = './';
	//파일name에는 업로드경로와 업로드된 파일명정보가 저장됨.
	$filename = "upload/" . $_GET['filedate'];
	//업로드한 파일이 있다면 쓰기(삭제)권한 검사
	if ( is_file($filename) ) {
		if ( is_writable($filename) ) {
			unlink($filename); //파일삭제
			echo '파일 삭제됨.';
		} else {
			echo '파일에 대한 쓰기(삭제) 권한 없음.';
		}
	}else if($_GET['filedate'] ==''){
		echo '파일이 첨부되지 않은 게시물입니다.';
	}
	else{
		echo '파일이 이미 삭제됬거나 존재하지 않음.';
	}
}else{ //쿼리가 정상적으로 실행되지 않았을때
	$msg = '글을 삭제하지 못했습니다.';
?>
	<script>
		alert("<?php echo $msg?>");
		history.back();
	</script>
<?php
	exit;
}
?>
<script>
	alert("<?php echo $msg?>");
	location.replace("<?php echo $replaceURL?>");
</script>

