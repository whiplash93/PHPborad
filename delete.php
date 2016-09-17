<?php
	require_once("dbconfig.php");
	session_start();
	//$_GET['bno']이 있어야만 글삭제가 가능함.
	if(isset($_GET['bno'])) {
		$bNo = $_GET['bno'];
	}
	$sql = "select * from tb_board where name = '$tbname'";
	$result = $db->query($sql);
	$tb_row = $result->fetch_assoc();
	$tbdesc = $tb_row[description];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>자유게시판 </title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
</head>
<body>
	<article class="boardArticle">
		<h3><?php echo $tbdesc?> 글삭제</h3>
		<?php
			if(isset($bNo)) {
				$sql = "select count(b_no) as cnt from $tbname where b_no = $bNo";
				$result = $db->query($sql);
				$row = $result->fetch_assoc();
				if(empty($row['cnt'])) {
		?>
		<script>
			alert('글이 존재하지 않습니다.');
			history.back();
		</script>
		<?php
			exit;
				}
				
				$sql = "select b_title from $tbname where b_no = $bNo";
				$result = $db->query($sql);
				$row = $result->fetch_assoc();
		?>
		<div id="boardDelete">
			<form action="./delete_update.php?tbname=<?php echo $tbname?>&filedate=<?php echo $_GET['filedate']?>" method="post">
				<input type="hidden" name="bno" value="<?php echo $bNo?>">
				<table>
					<caption class="readHide">자유게시판 글삭제</caption>
					<thead>
						<tr>
							<th scope="col" colspan="2">글삭제</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">글 제목</th>
							<td><?php echo $row['b_title']?></td>
						</tr>
						<tr>
							<th scope="row"><label for="bPassword">비밀번호</label></th>
							<td><?php if($_SESSION["session_id"]){?>
							<input type="password" name="bPassword" id="bPassword" disabled ><br/>관리자는 비밀번호가 필요없습니다.<br/>삭제를 원하시면 삭제 버튼을 누르십시오.</td>
							<?php }else{?>
							<input type="password" name="bPassword" id="bPassword"></td>
							<?php }?>
						</tr>
					</tbody>
				</table>

				<div class="btnSet">
					<button type="submit" class="btnSubmit btn">삭제</button>
					<a href="./index.php?tbname=<?php echo $tbname?>" class="btnList btn">목록</a>
				</div>
			</form>
		</div>
	<?php
		//$bno이 없다면 삭제 실패
		} else {
	?>
		<script>
			alert('정상적인 경로를 이용해주세요.');
			history.back();
		</script>
	<?php
			exit;
		}
	?>
	</article>
</body>
</html>