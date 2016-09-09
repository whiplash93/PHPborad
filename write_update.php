<?php
	require_once("dbconfig.php");
	
	//$_POST['bno']이 있을 때만 $bno 선언
	if(isset($_POST['bno'])) {
		$bNo = $_POST['bno'];
	}

	//bno이 없다면(글 쓰기라면) 변수 선언
	if(empty($bNo)) {
		$bID = $_POST['bID'];
		$date = date('Y-m-d H:i:s');
		$tbname = $_GET['tbname'];
	}
	//항상 변수 선언
	// 설정
	$allowed_ext = array('jpg','jpeg','png','PNG','gif','JPG','JPEG','GIT');
	$bPassword = $_POST['bPassword'];
	$bTitle = $_POST['bTitle'];
	$bContent = $_POST['bContent'];
	$time = explode(' ',microtime());
	$error = $_FILES['File']['error'];
	$name = $_FILES['File']['name'];
	$ext = array_pop(explode('.', $name));
	$File = iconv("UTF-8","EUC-KR",$_FILES['File']['name']);	// 기존첨부파일명
	if($File){ //파일 첨부가 되있다면
		$Filedate = $tbname.'_'.$time[1].substr($time[0],2,6);	// 새로운첨부파일명  테이블명_마이크로타임
	}
	
	


if( $error != 4){	
	// 오류 확인 //4번은 파일이 첨부되지 않은경우. 위는 파일이 있으면...
	if( $error != UPLOAD_ERR_OK ) {
		switch( $error ) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				echo ("<script>
						alert('파일 용량이 너무 큽니다!');
						history.go(-1);
						</script>");
				exit;
				break;
// 			case UPLOAD_ERR_NO_FILE:
// 				echo "파일이 첨부되지 않았습니다. ($error)";
				
// 				break;
			default:
				//echo "파일이 제대로 업로드되지 않았습니다. ($error)";
// 				echo ("<script>
// 						alert('파일이 제대로 업로드되지 않았습니다.');
// 						history.go(-1);
// 						</script>");
// 				exit;
				echo "파일이 첨부되지 않았습니다. ($error)";
				break;
		}
		exit;
	}
	
	// 확장자 확인
	if( !in_array($ext, $allowed_ext) ) {
		//echo "허용되지 않는 확장자입니다.";
		echo ("<script>
				alert('허용되지 않는 확장자입니다. jpg, jpeg, png, gif 파일만 업로드 가능합니다.');
				history.go(-1);
				</script>");
		exit;
	}
}	
	# 파일 업로드
	$uploaddir = 'upload/';
	$uploadfile = $uploaddir.basename($_FILES['File']['name']);
	if(move_uploaded_file($_FILES['File']['tmp_name'], 'upload/'.$Filedate));{
		// echo "파일이 유효하고, 성공적으로 업로드 되었습니다.";
	}
	
//글 수정
if(isset($bNo)) {
	//수정 할 글의 비밀번호가 입력된 비밀번호와 맞는지 체크
	$sql = 'select count(b_password) as cnt from ' . $tbname . ' where b_password=password("' . $bPassword . '") and b_no = ' . $bNo;
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	
	//비밀번호가 맞다면 업데이트 쿼리 작성
	if($row['cnt']) {
		if($b_file==''){
		$sql = 'update ' . $tbname . ' set b_title="' . $bTitle . '", b_content="' . $bContent . '", b_file="' . $name . '", b_filedate="' . $Filedate .'" where b_no = ' . $bNo;
		echo $sql;
		}
		else{
		$sql = 'update ' . $tbname . ' set b_title="' . $bTitle . '", b_content="' . $bContent . '" where b_no = ' . $bNo;
		echo "b_file : $b_file";
		echo $sql;
		}
		$msgState = '수정';
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

//글 등록
} else {
	
	$sql = "insert into $tbname (b_no, b_title, b_content, b_date, b_hit, b_id, b_password, b_file, b_filedate) values(null, '$bTitle', '$bContent', '$date', 0, '$bID', password('$bPassword'), '$name', '$Filedate' )";
		$msgState = '등록';
	}
	$result = $db->query($sql);

//메시지가 없다면 (오류가 없다면)
if(empty($msg)) {
// 	$result = $db->query($sql);
	//쿼리가 정상 실행 됐다면,
	if($result) {
		$msg = '정상적으로 글이 ' . $msgState . '되었습니다.';
		if(empty($bNo)) {
			$bNo = $db->insert_id;
			
			$sql = "SELECT * FROM tb_view WHERE b_tbname = '$tbname' AND b_fname != 'b_id' AND b_fname != 'b_no' AND b_fname != 'b_hit' AND b_fname != 'b_date' AND b_fname != 'b_title'";
			$result = $db->query($sql);
			while ($array_row = $result->fetch_assoc())
			{
				if(isset($_POST[$array_row['b_fname']]))
				{
					$value = $_POST[$array_row['b_fname']];
				}	
				for($i=1;$i<20;$i++)
				{
					if(isset($_POST[$array_row['b_fname'].$i]))
					{
						$values[$i] = $_POST[$array_row['b_fname'].$i];
						$value==''? $value .= $values[$i] : $value .= ','.$values[$i];
						echo $value;
					}
				}
				$fname = $array_row['b_fname']; //b_aa, b_bb, b_cc, b_dd.....
				$sql = "UPDATE $tbname SET $fname = '$value' WHERE b_no = $bNo";
				$res_up = $db->query($sql);
				echo $sql;
				$value = '';
			}
		
		}
		$replaceURL = './view.php?tbname='."$tbname".'&bno=' . $bNo;
	} else {
		$msg = '글을 ' . $msgState . '하지 못했습니다.';
?>
		<script>
			alert("<?php echo $msg?>");
			history.back();
		</script>
<?php
		exit;
	}
}

?>
<script>
	alert("<?php echo $msg?>");
	location.replace("<?php echo $replaceURL?>");
</script>