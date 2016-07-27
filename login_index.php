<?
 
 //# 세션 스타트 #//
 session_start();

?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript">
<!--

 function login_send()
 {
  form = document.login_form;
  if (form.id.value == "")
  {
   alert("아이디 입력 요망");
   form.id.focus();
   return false;
  }
  else if(form.pw.value == "")
  {
   alert("비밀번호 입력 요망");
   form.pw.focus();
   return false;
  }
  else
  {
   return true;
  }
 }

function OpenWindow(file_name,width,height)
{

	var winHeight = document.body.clientHeight;	// 현재창의 높이
	var winWidth = document.body.clientWidth;	// 현재창의 너비
	var winX = window.screenLeft;	// 현재창의 x좌표
	var winY = window.screenTop;	// 현재창의 y좌표

	var popX = winX + (winWidth - width)/2;
	var popY = winY + (winHeight - height)/2;
 	window.open(file_name,'open_win1', "width="+width+"px,height="+height+"px,top="+popY+",left="+popX,scrollbars=no);
 //window.open(file_name,'open_win1','top=200,left=500,width='+width+',height='+height+',scrollbars=no');
}
//-->
</script>
</head>
<body>

<!--로그인 되었을때의 처리 -->
<? 
 if($_SESSION["session_id"]) 
 { 
?>
 <table align="center" border="1" bgcolor="pink" cellpadding="0" cellspacing="0" width="400">
  <tr align="center" height="30">
   <td>SESSION 아이디</td>
   <td><?=session_id();?></td>
  </tr>
  <tr align="center" height="30">
   <td>SESSION 파일 저장경로</td>
   <td><?=session_save_path();?></td>
  </tr>
  <tr align="center" height="30">
   <td colspan="2"><input type="button" value="   패스워드변경   " onClick="OpenWindow('login_pwchange.php','300','130');"></td>
  </tr>
  <tr align="center" height="30">
   <td colspan="2"><input type="button" value="   로그아웃하기   " onClick="location.href='login_ok.php?logout=yes';"></td>
  </tr>

 </table>
<? 
 } else { 
?>
<!-- 로그아웃되었을때의 처리 -->
 <form name="login_form" method="post" action="login_ok.php" onSubmit="return login_send();">
 <table align="center" border="0" bgcolor="pink" cellpadding="0" cellspacing="0" width="200">
  <tr align="center" height="30">
   <td>아 이 디</td>
   <td><input type="text" name="id" style="width:100px;" value=""></td>
  </tr>
  <tr align="center" height="30">
   <td>패스워드</td>
   <td><input type="password" name="pw"  style="width:100px;" value=""></td>
  </tr>
  <tr align="center" height="30">
   <td colspan="2"><input type="submit" value="     로그인하기     "></td>   
  </tr>
 </table>
 </form>
 </body>
 </html>
<?
 } 
?>