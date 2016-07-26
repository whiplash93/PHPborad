<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript">
<!--

 function pw_send()
 {
  form = document.pw_form;
  if (form.id.value == "")
  {
   alert("패스워드를 찾을 아이디를 입력해주십시오.");
   form.id.focus();
   return false;
  }
 }

//-->
</script>
</head>
<body>
<form name="pw_form" method="post" action="login_process.php?mode=pw_find" onSubmit="return pw_send();">
 <table align="center" border="1" bgcolor="skyblue" cellpadding="0" cellspacing="0" width="240">
  <tr height="30">
   <td>아이디</td>
   <td>&nbsp;<input type="text" name="id" ></td>
  </tr>
  <tr align="center" height="30">
   <td colspan="2"><input type="submit" value="패스워드 찾기"></td>
  </tr>
 </table>
 </form>
 </body>
 </html>