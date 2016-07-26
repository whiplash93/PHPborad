<?

 //# DB연결 설정 #//
require_once("dbconfig.php");
 $query = " select * from tb_admin ";
 $result = $db->query($query);
 $result = $result->fetch_assoc();
 //# DB연결 설정 #//
  
?>

<script language="javascript">
<!--

 function pw_send()
 {
  form = document.pw_form;
  if (form.pw.value == "")
  {
   alert("패스워드를 입력해주십시오.");
   form.pw.focus();
   return false;
  }
 }

//-->
</script>

<form name="pw_form" method="post" action="login_process.php?mode=pw_change" onSubmit="return pw_send();">
 <table align="center" border="1" bgcolor="skyblue" cellpadding="0" cellspacing="0" width="240">
  <tr height="30">
   <td>아이디</td>
   <td>&nbsp;<?=$login_row['root']?></td>
  </tr>
  <tr height="30">
   <td>패스워드</td>
   <td>&nbsp;<input type="password" name="pw" value="<?=$login_row[pw]?>" size="21"></td>
  </tr>
  <tr align="center" height="30">
   <td colspan="2"><input type="submit" value="비밀번호 수정하기"></td>
  </tr>
 </table>
 </form>