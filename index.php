<?php
	require_once("dbconfig.php");
	
	if(isset($_GET['tbname'])) {
		$tbname = $_GET['tbname'];
	} else {
		$tbname = 'tb_freeboard'; 
	}		
	$sql = " select * from tb_board where name = '$tbname' ";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$tbdesc = $row['description'];
	
	/* 페이징 시작 */
	//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	
	/* 검색 시작 */
	
	if(isset($_GET['searchColumn'])) {
		$searchColumn = $_GET['searchColumn'];
		$subString .= '&amp;searchColumn=' . $searchColumn;
	}
	if(isset($_GET['searchText'])) {
		$searchText = $_GET['searchText'];
		$subString .= '&amp;searchText=' . $searchText;
	}
	
	if(isset($searchColumn) && isset($searchText)) {
		$searchSql = ' where ' . $searchColumn . ' like "%' . $searchText . '%"';
	} else {
		$searchSql = '';
	}
	/* 검색 끝 */
	$sql = "select count(*) as cnt from $tbname $searchSql";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	
	$allPost = $row['cnt']; //전체 게시글의 수
	
	if(empty($allPost)) {
		$emptyData = '<tr><td class="textCenter" colspan="5">글이 존재하지 않습니다.</td></tr>';
	} else {

		$onePage = 15; // 한 페이지에 보여줄 게시글의 수.
		$allPage = ceil($allPost / $onePage); //전체 페이지의 수
		
		if($page < 1 && $page > $allPage) {
?>
			<script>
				alert("존재하지 않는 페이지입니다.");
				history.back();
			</script>
<?php
			exit;
		}
	
		$oneSection = 10; //한번에 보여줄 총 페이지 개수(1 ~ 10, 11 ~ 20 ...)
		$currentSection = ceil($page / $oneSection); //현재 섹션
		$allSection = ceil($allPage / $oneSection); //전체 섹션의 수
		
		$firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지
		
		if($currentSection == $allSection) {
			$lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
		} else {
			$lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
		}
		
		$prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
		$nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.
		
		$paging = '<ul>'; // 페이징을 저장할 변수
		
		//첫 페이지가 아니라면 처음 버튼을 생성
		if($page != 1) { 
			$paging .= '<li class="page page_start"><a href="./index.php?page=1' . $subString . '">처음</a></li>';
		}
		//첫 섹션이 아니라면 이전 버튼을 생성
		if($currentSection != 1) { 
			$paging .= '<li class="page page_prev"><a href="./index.php?page=' . $prevPage . $subString . '">이전</a></li>';
		}
		
		for($i = $firstPage; $i <= $lastPage; $i++) {
			if($i == $page) {
				$paging .= '<li class="page current">' . $i . '</li>';
			} else {
				$paging .= '<li class="page"><a href="./index.php?page=' . $i . $subString . '">' . $i . '</a></li>';
			}
		}
		
		//마지막 섹션이 아니라면 다음 버튼을 생성
		if($currentSection != $allSection) { 
			$paging .= '<li class="page page_next"><a href="./index.php?page=' . $nextPage . $subString . '">다음</a></li>';
		}
		
		//마지막 페이지가 아니라면 끝 버튼을 생성
		if($page != $allPage) { 
			$paging .= '<li class="page page_end"><a href="./index.php?page=' . $allPage . $subString . '">끝</a></li>';
		}
		$paging .= '</ul>';
		
		/* 페이징 끝 */
		
		
		$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
		$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문
		
// 		$sql = 'select * from tb_freeboard' . $searchSql . ' order by b_no desc' . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
// 		$result = $db->query($sql);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>PHP게시판 활용</title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
	<script>
	function OpenWindow(file_name,width,height)
	{
	 window.open(file_name,'open_win1','top=100,left=100,width='+width+',height='+height+',scrollbars=no');
	}
	</script>
</head>
<body>
<!-- 상단 테이블 나열 -->
	<article class="boardArticle">
		<center>|<?php 
		$query = " select * from tb_board order by id ";
		$result = $db->query($query);
		while($row = $result->fetch_assoc()) {?>
		<B><font size="5" ><a href="index.php?tbname=<?php echo $row['name']?>"><?php echo $row['description']?></a></font></B> | <?php }?> 
		</center><br>
		<!-- 게시판 테이블에 있는대로 하이퍼링크 건 테이블이름 출력 -->
<!-- 상단 테이블 나열 끝-->
		<div id="boardList">
			<table>
				<caption class="readHide"><?php echo $tbdesc ?></caption>
		  <?php if($_SESSION["session_id"]=="root") 
				{?>
				<div class="login">
				
				<a href="#" onClick="location.href='admin_index.php';">관리자 페이지로 </a>(<a href="#" onClick="location.href='login_ok.php?logout=yes';">로그아웃</a>)</div>
				<?php }else{ ?>
				<div class="login"><a href="#" onClick="OpenWindow('login_index.php','300','130')">관리자 로그인</a></div>
				<?php }?>
				<thead>
					<tr>
						<th scope="col" class="no">번호</th>
						<th scope="col" class="title">제목</th>
						<th scope="col" class="author">작성자</th>
						<th scope="col" class="date">작성일</th>
						<th scope="col" class="hit">조회</th>
						<th scope="col" class="add">파일여부</th>
					</tr>
				</thead>
				<tbody>
						<?php
						$sql = "select * from $tbname $searchSql order by b_no desc $sqlLimit"; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
						$result = $db->query($sql);
						$i=0;
						if(isset($emptyData)) {
							echo $emptyData;
						} else {
							while($row = $result->fetch_assoc())
							{
								$datetime = explode(' ', $row['b_date']);
								$date = $datetime[0];
								$time = $datetime[1];
								
								if($date == Date('Y-m-d'))
									$row['b_date'] = $time;
								else
									$row['b_date'] = $date;
								
								$boardNum = $allPost-($allPage*($page-1));
								
								$Num = $boardNum - $i;
								$i++;
								if($Num <= 0){ //글 번호가 0보다 같거나 작으면
									//아무것도 실행하지 않음으로써 열을 다 지운다.
									//0보다 큰 경우면
								}else{?>
						<tr>
							<td class="no"><?php echo $Num?></td>
							<td class="title">
								<a href="./view.php?tbname=<?php echo $tbname?>&bno=<?php echo $row['b_no']?>"><?php echo $row['b_title']?></a>
							</td>
							<td class="author"><?php echo $row['b_id']?></td>
							<td class="date"><?php echo $row['b_date']?></td>
							<td class="hit"><?php echo $row['b_hit']?></td>
							<td class="add"><?php if($row['b_file'] != null){?>
																       O
														<?php } else{ ?>
																	   X  
													      <?php }?>
							</td>
						</tr>
						<?php
								}
							}
								}$i=0;
						?>
				</tbody>
			</table>
			<div class="btnSet">
				<a href="./write.php?tbname=<?php echo $tbname?>" class="btnWrite btn">글쓰기</a>
			</div>
			<div class="paging">
				<?php echo $paging ?>
			</div>
			<div class="searchBox">
				<form action="./index.php" method="get">
					<input type="hidden" name="tbname" value="<?php echo $tbname?>">
					<select name="searchColumn">
						<option <?php echo $searchColumn=='b_title'?'selected="selected"':null?> value="b_title">제목</option>
						<option <?php echo $searchColumn=='b_content'?'selected="selected"':null?> value="b_content">내용</option>
						<option <?php echo $searchColumn=='b_id'?'selected="selected"':null?> value="b_id">작성자</option>
					</select>
					<input type="text" name="searchText" value="<?php echo isset($searchText)?$searchText:null?>">
					<button type="submit">검색</button>
				</form>
			</div>
		</div>
	</article>
</body>
</html>