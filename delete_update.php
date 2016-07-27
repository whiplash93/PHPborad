<?php
	require_once("dbconfig.php");

	//$_POST['bno']이 있을 때만 $bno 선언
	if(isset($_POST['bno'])) {
		$bNo = $_POST['bno'];
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
	$row = $result->fetch_assoc();
	
	//비밀번호가 맞다면 삭제 쿼리 작성
	if($row['cnt']) {
		$sql = "delete from $tbname where b_no = $bNo"; //tb_board 에서 삭제하고
		$result = $db->query($sql);
		$sql2 = "delete from tb_freecomment where b_no = $bNo AND b_name = $tbname"; //tb_freecomment 에서 댓글도 삭제해준다.
		$result2 = $db->query($sql2);
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
	$filename = "upload/" . $_GET['filedate'];
	echo $filename;
	if ( is_file($filename) ) {
		if ( is_writable($filename) ) {
			unlink($filename);
			echo '파일 삭제됨.';
		} else {
			echo '파일에 대한 쓰기(삭제) 권한 없음.';
		}
	}else{
		echo '파일이 아니거나 없음.';
	}
}else{
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

