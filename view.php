<?php
	require_once("dbconfig.php");
	$bNo = $_GET['bno'];
	if(!empty($bNo) && empty($_COOKIE[$tbname.'_' . $bNo])) {
		$sql = "update ".$tbname." set b_hit = b_hit + 1 where b_no = ".$bNo;
		$result = $db->query($sql); 
		if(empty($result)) {
			?>
			<script>
				alert('오류가 발생했습니다.');
				history.back();
			</script>
			<?php 
		} else {
			setcookie($tbname.'_' . $bNo, TRUE, time() + (60 * 60 * 24), '/');
		}
	}
	
	$sql = "select b_title, b_content, b_date, b_hit, b_id, b_file, b_filedate from "."$tbname"." where b_no = ".$bNo;
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$filename = $row['b_file'];
	$filedate = $row['b_filedate'];
	$sql = " select * from tb_board where name = '$tbname'";
	$result = $db->query($sql);
	$tbdesc = $result->fetch_assoc();
	$tbdesc = $tbdesc['description'];
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
	function back(){
		history.back();
	}
	</script>
</head>
<body>
	<article class="boardArticle">
		<h3><?php echo $tbdesc?></h3>
		<div id="boardView">
			<h3 id="boardTitle"><?php echo $row['b_title']?></h3>
			<div id="boardInfo">
				<span id="boardID">작성자: <?php echo $row['b_id']?></span>
				<span id="boardDate">작성일: <?php echo $row['b_date']?></span>
				<span id="boardHit">조회: <?php echo $row['b_hit']?></span>
			</div>
			<div id="boardContent"><?php echo $row['b_content']?></div>
			<div id="boardFile">첨부된 파일 :
			<a href="down_Load.php?tbname=<?php echo $tbname?>&fileName=<?php echo $filedate?>&num=<?php echo $bNo ?>"><?php echo $filename ?>
				<?php 
// 				if($filename != null)
// 					echo $filename;
// 	 	 		    else echo "</a> 첨부된 파일 없음";
				?></a>
			</div>
			<div class="btnSet">
				<a href="./write.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>">수정</a>
				<a href="./delete.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>&filedate=<?php echo $filedate?>">삭제</a>
				<a href="./index.php?tbname=<?php echo $tbname?>">목록</a>
			</div>
		<div id="boardComment">
			<?php require_once("./comment.php")?>
		</div>
		</div>
	</article>
</body>
</html>