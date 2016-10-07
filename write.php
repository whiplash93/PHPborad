<?php
	require_once("dbconfig.php");

	//$_GET['bno']이 있을 때만 $bno 선언
	if(isset($_GET['bno'])) {
		$bNo = $_GET['bno'];
	}
	if(isset($bNo)) {
		$sql = "select * from $tbname where b_no = $bNo";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
	}
	
	$sql = "select * from tb_board where name = '$tbname'";
	$result = $db->query($sql);
	$tb_row = $result->fetch_assoc();
	$tbdesc = $tb_row[description];
	
	$sql = "SELECT * FROM tb_view WHERE b_tbname = '$tbname' AND b_fname != 'b_no' AND b_fname != 'b_hit' AND b_fname != 'b_date' ORDER BY b_seq";
	$result = $db->query($sql);

	

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $tbdesc?> </title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
</head>
<body>
	<article class="boardArticle">
		<h3><?php echo $tbdesc?> 목록추가</h3>
		<div id="boardWrite">
			<form action="./write_update.php?tbname=<?php echo $tbname?>" enctype='multipart/form-data' method="post">
				<?php
				if(isset($bNo)) {
					echo '<input type="hidden" name="bno" value="' . $bNo . '">';
				}
				?>
				<table id="boardWrite">
					<caption class="readHide"><?php echo $tbdesc?> 글쓰기</caption>
					<tbody>
						<tr>
							</td>
						</tr>
						<?php while ($view_row = $result->fetch_assoc())
						{
								$b_type = $view_row['b_type'];
	 							if($b_type <> 'TEXT' && $b_type <> 'TEXTAREA' && $b_type <> 'IMG' && $b_type <> 'URL')
	 							{?>
	 								<tr>
	 									<th scope="row"><?php echo $view_row['b_description']?></th>
	 									<td class="title"><input type="text" name="<?=  $view_row['b_fname']?>" value="<?= isset($row[$view_row['b_fname']])?$row[$view_row['b_fname']]:null?>">
	 							<?}
	 							
								if($b_type == 'TEXT')
								{?>
								<tr>
									<input type= "hidden" name= "b_fname" value="TEXT">
									<th scope="row"><?php echo $view_row['b_description']?></th> 
									<td class="title"><input type="text" name="<?= $view_row['b_fname']?>" value="<?= isset($row[$view_row['b_fname']])?$row[$view_row['b_fname']]:null?>">
							<?}?>
								<? if($b_type == 'TEXTAREA')
								{?>
								<tr>
									<th scope="row"><?php echo $view_row['b_description']?></th> 
									<td class="content"><textarea id="bContent"  name="<?=$view_row['b_fname']?>"><?= isset($row[$view_row['b_fname']])?$row[$view_row['b_fname']]:null?></textarea>
							<?}?>
								<? if($b_type == 'IMG')
								{?>
								<tr>
									<th scope="row"><?php echo $view_row['b_description']?></th>
									<td class="title"><input type="file" size='20' name="<?= $view_row['b_fname']?>"></br>
									<?php
									if(isset($row[$view_row['b_fname']]))
									{?>
											<?php echo $row[$view_row['b_fname']];?>
											<a href="#" onClick="location.href='delete_update.php?bNo=<?php echo $bNo?>&mode=update&tbname=<?php echo $tbname?>'">삭제</a>
											<strong style='color:red; font-size:12px;'>★기존에 등록했던 파일 이름</strong></br>
						<?php }?>
									
									<strong style='color:red; font-size:12px;'>★반드시 jpg, jpeg, png, gif 파일만 업로드 가능합니다.</strong><br />
									
							<?}?>
								<? if($b_type == 'URL')
								{?>
								<tr>
									<th scope="row"><?php echo $view_row['b_description']?></th>
									<td class="title"><input type="text" name="<?=  $view_row['b_fname']?>" value="<?= isset($row[$view_row['b_fname']])?$row[$view_row['b_fname']]:null?>">
							<?}?>
								</br>
								<?= $view_row['b_destitle']?></td>
	 			<?php }?>
	 					</tr>
						<? # 파일 업로드 시, 아래와 같이 name, id(꼭, "MAX_FILE_SIZE" 이여야 함) & value = 파일크기(즉, 1000 = 1KB) ?>
						<input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="10000000" />
						<input type="hidden" name="b_file" value="<?php  echo isset($row['b_file'])?$row['b_file']:null?>" />
							</td>
						</tr>
					</tbody>
				</table>
				<div class="btnSet">
					<button type="submit" class="btnSubmit btn">
						<?php echo isset($bNo)?'수정':'작성'?>
					</button>
					<a href="./index.php?tbname=<?=$tbname?>" class="btnList btn">목록</a>
				</div>
			</form>
		</div>
	</article>
</body>
</html>