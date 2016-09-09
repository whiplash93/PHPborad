<?php
require_once("dbconfig.php");
$bNo = $_GET['bno'];
$tbname = $_REQUEST['tbname'];
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
	
// 	$sql = "select b_title, b_content, b_date, b_hit, b_id, b_file, b_filedate from "."$tbname"." where b_no = ".$bNo;
// 	$result = $db->query($sql);
// 	$row = $result->fetch_assoc();
// 	$filename = $row['b_file'];
// 	$filedate = $row['b_filedate'];
	
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
		<th><?= $table_row[$table_column[$i]] ?> </th>
	</tr>
	<?php 
	}?>
	</table>
				<a href="./write.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>">수정</a>
				<a href="./delete.php?tbname=<?php echo $tbname?>&bno=<?php echo $bNo?>&filedate=<?php echo $filedate?>">삭제</a>
				<a href="./index.php?tbname=<?php echo $tbname?>">목록</a>
		<div id="boardComment">
			<?php require_once("./comment.php")?>
		</div>
		</div>
	</article>
</body>
</html>