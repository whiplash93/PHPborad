<?php
require_once("dbconfig.php");

$query = " select * from tb_board ";
$result = $db->query($query);

if(!$_SESSION["session_id"]=="root")
{
	echo "<script>alert('관리자만 접근할 수 있습니다.');location.href='index.php';</script>";
}
			
?>

<!DOCTYPE html>
<html>
 <head>
 <script>
	 function create_table()
	 {
	  form = document.form_create_tb;
	  if (form.tableName.value == "")
	  {
	   alert("테이블 이름을 입력하세요.");
	   form.tableName.focus();
	   return false;
	    
	  }
	  else return true;
	 }
 </script>
  <meta charset="utf-8" />
   <title>관리자페이지 </title>
 </head>	
 <body>
 	<h3>관리자 페이지에 접속하였습니다.</h3>
    <caption>게시판목록</caption>
    <table border="1" text-align = "center" >
	   <tr align = "center">
		 <td>테이블명</td>
		 <td>설명</td>
		 <td>명령</td>
	   </tr>
	   <?php while($row = $result->fetch_assoc()) {?>
	   <tr>
		 <td><a href="index.php?tbname=<?php echo $row[name]?>"><?php echo $row[name]?></a></td>
		 <td><?php echo $row[description]?></td>
		 <td><a href="#" onClick="location.href='admin_process.php?mode=update&tbname=<?php echo $row[name]?>'">수정</a> |
			 <a href="#" onClick="location.href='admin_process.php?mode=delete&tbname=<?php echo $row[name]?>'">삭제</a></td>
	   </tr>
	   <?php }?>
	</table>
	<p/>
	<div style="width:400px; height:100px; background-color:#eee; border:1px solid">
	<form name="form_create_tb" method="post" action="admin_process.php?mode=create" onSubmit="return create_table();">
	데이터베이스에 새로운 테이블을 만듭니다.<br>
	이름 :  <input type="text" name="tableName" value=""/><br/>
	설명 :  <input type="text" name="description" value=""/><br/>
	<div style="width:50px; height:20px; margin:3px 0 0 80px; "><input type="submit" value="   생성    "/></div>
	</form>
	</div>
 </body>
</html>