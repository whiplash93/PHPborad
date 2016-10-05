<?php
require_once("dbconfig.php");
require_once("img_view.php");

$bNo = $_GET['bno'];
$tbname = $_REQUEST['tbname'];
if(!empty($bNo) && empty($_COOKIE[$tbname.'_' . $bNo])) {
	$sql = "update ".$tbname." set b_hit = b_hit + 1 where b_no = ".$bNo;
	$result = $db->query($sql);
	if(empty($result)) {
		?>
			<script>
				alert('오류가 발생했습니다. 조회수를 늘리지 못했습니다.');
				history.back();
			</script>
			<?php 
		} else {
			setcookie($tbname.'_' . $bNo, TRUE, time() + (60 * 60 * 24), '/');
		}
	}
	
	$sql = " select * from tb_board where name = '$tbname'";
	$result = $db->query($sql);
	$tbdesc = $result->fetch_assoc();
	$tbdesc = $tbdesc['description'];
	//게시판 이름과 설명을 가져오기위한 쿼리
	
	$sql  = " select * from tb_view where b_tbname = '$tbname' AND b_visible = '1' order by b_seq";
	$result_row = $db->query($sql);
	//
	$a = 0;
	while($table_row = $result_row->fetch_assoc())
	{
		$table_column[$a] = $table_row['b_fname'];
		$table_dsc[$a] = $table_row['b_description'];
		$table_type[$a] = $table_row['b_type'];
		
		$a++;
	}
	$cnt = count($table_column);
	$content = $table_column[0];
	
	for($i=1;$i<$cnt;$i++)
	{
		$content .= ','.$table_column[$i];
	}
	$sql = "select $content ". " from "." $tbname ". "where b_no = ".$bNo;
	$result = $db->query($sql);
	$table_row = $result->fetch_assoc();
	
	//파일 데이터 읽어오는 쿼리 b_file은 업로드할때 선택한 실제 파일명이고 b_filedate는 마이크타임으로 새로지은 파일이름이다.
	$query = "select b_file, b_filedate from $tbname where b_no=".$bNo;
	$res = $db->query($query);
	$img_row = $res->fetch_assoc();
	//썸네일된 이미지의 이름 
	$img_name  = "thum_".$tbname."_".$img_row['b_file'];
	
	
	//이미지 출력함수
	function img_view($image){
		//전송된 이미지 여부 확인
		if( isset($image) ) {
			//이미지 파일명
			$image_name = $image;
		
			//이미지 전체경로를 포함한 이미지명
			//$image_path = $_SERVER['DOCUMENT_ROOT'].'/test/upload/'.$image;
			$image_path = './upload/'.$image;
			//넘어온 이미지경로의 존재여부와 파일여부 확인
			echo "이미지경로:::".$image_path; 
			if(file_exists($image_path) && is_file($image_path)) {
			echo "파일존재 여부 확인했음";
				//넘어온 파일 확장자 추출
				$tmp_name = pathinfo($image_path);
				$ext = strtolower($tmp_name['extension']);
		
				//지정된 확장자만 보여주도록 필터링
				if($ext == 'jpg' || $ext='gif' || $ext='png' || $ext='bmp') {
					echo "필터링 if문 통과";
					//이미지 크기정보와 사이즈를 얻어옴
					$img_info = getimagesize($image_path);
					$filesize = filesize($image_path);
		
					//이미지 전송을 위한 헤더설정
					header("Content-Type: {$img_info['mime']}\n");
					header("Content-Disposition: inline;filename='$image_name'\n");
					header("Content-Length: $filesize\n");
						
					//이미지 내용을 읽어들임
					readfile($image_path);
				}//if
			}//if
		}//if
	}//function
	
	
	//$src = $_SERVER['DOCUMENT_ROOT'].'/test/upload/'.$img_name;
	$src = './upload/'.$img_name;

	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>dd</title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
	<script src="./js/jquery-2.1.3.min.js"></script>
	<script>
	</script>
</head>
<body>
<article class="boardArticle">
	<table border='1' width='700px'>
	<?php for($i=0;$i<$cnt;$i++)
	{?>
	<tr>
		<th><?=  $table_dsc[$i]?> </th>
		<?php if ($table_type[$i]=="IMG") {?>
				<?php // echo "dddddddd"; img_view($img_name)?>
				<th><img alt="" src="<?php echo $src?>"></th>
		<?php }else if ($table_type[$i]=="URL") {?>
		<th><a href="<?= $table_row[$table_column[$i]] ?>"></a> </th>
		<?php }else {?>
		<th><?= $table_row[$table_column[$i]] ?> </th>
		<?php }?>
	</tr>
	<?php 
	}?>
	</table>
				<a href="./write.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>">수정</a>
				<a href="./delete.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>&filedate=<?php echo $img_row['b_filedate']?>">삭제</a>
				<a href="./index.php?tbname=<?php echo $tbname?>">목록</a>
		</div>
	</article>
</body>
</html>