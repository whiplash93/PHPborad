<?php
	require_once("dbconfig.php");
	require_once("img_view.php");
	//$_POST['bno']이 있을 때만 $bno 선언
	if(isset($_POST['bno'])) {
		$bNo = $_POST['bno'];
	}
	
	$sql = "SELECT * FROM tb_view WHERE b_tbname = '$tbname' AND b_type = 'IMG' ";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$b_fname = $row['b_fname'];
	echo '$b_fname : '.$b_fname;
	//bno이 없다면(글 쓰기라면) 변수 선언
	if(empty($bNo)) {
		$bID = $_POST['bID'];
		$date = date('Y-m-d H:i:s');
	}
	//항상 변수 선언
	// 설정
	
	$sql = "SELECT * FROM tb_view WHERE b_tbname = '$tbname' AND  b_fname != 'b_no'  "; //번호를 제외한 모든 다른 필드를 검색
	$result = $db->query($sql);
	echo "여기까지 실행됨";
	$i = 0;
	while ($array_row = $result->fetch_assoc())//추가된 컬럼 하나씩 쿼리돌려서 $ary_qur 에 저장
	{ 
		if($array_row['b_type'] =='DATE' )
		{
			$value = $date;
		}elseif($array_row['b_type'] =='IMG' )
		{
			$value = $_FILES[$b_fname]['name'];
		}else
		{
			$value = $_POST[$array_row['b_fname']];
		}
		echo "value출력  : ". $value;
		$fname = $array_row['b_fname']; //b_aa, b_bb, b_cc, b_dd.....
		
		if ($i == 0)
		{
			$ary_qur = "$fname = '$value'";
		}
		else
		{
			$ary_qur .= ", $fname = '$value'";
		}
		$i++;
	}
	echo '$ary_qur : '.$ary_qur;
	
	
	$allowed_ext = array('jpg','jpeg','png','PNG','gif','JPG','JPEG','GIT');
	$time = explode(' ',microtime());
	$error = $_FILES[$b_fname]['error'];
	$name = $_FILES[$b_fname]['name'];
	$ext = array_pop(explode('.', $name));
	$File = iconv("UTF-8","cp949",$_FILES[$b_fname]['name']);	// 기존첨부파일명
	$File = $_FILES[$b_fname]['name'];	// 기존첨부파일명
	echo '$File : '.$File;
	if($File){ //파일 첨부가 되있다면
//		$Filedate = $tbname.'_'.$time[1].substr($time[0],2,6);	// 새로운첨부파일명  테이블명_마이크로타임
		$Filedate = $tbname.'_'.$_FILES[$b_fname]['name'];
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
	echo "파일함수 출력 :::".$_FILES[$b_fname]['name'];
	$uploaddir = './upload/';
	$uploadfile = $uploaddir.basename($_FILES[$b_fname]['name']);
	if(move_uploaded_file($_FILES[$b_fname]['tmp_name'], './upload/'.$Filedate));{
		echo "파일이 유효하고, 성공적으로 업로드 되었습니다.";
	}
	make_thumbnail("./upload/".$Filedate, 200, 200, "./upload/thum_".$Filedate);
	
//글 수정
if(isset($bNo)) 
{
	$sql = "UPDATE  '$tbname' SET $ary_qur  WHERE  b_no =  $bNo";
	echo $sql;
	$msgState = '수정';

//글 등록
} else {
	
	$sql = "INSERT INTO $tbname (b_no) VALUES (null)";
	$msgState = '등록';
	}
	$result = $db->query($sql);
	$bNo = $db->insert_id;

	$sql = "UPDATE $tbname SET b_filedate = '$Filedate' , $ary_qur WHERE b_no = $bNo";
	$res_up = $db->query($sql);
	echo "sql 출력 :".$sql;

//메시지가 없다면 (오류가 없다면)
if(empty($msg)) {
// 	$result = $db->query($sql);
	//쿼리가 정상 실행 됐다면,
	if($result) {
		$msg = '정상적으로 글이 ' . $msgState . '되었습니다.';
		if(empty($bNo)) {
			$bNo = $db->insert_id;
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