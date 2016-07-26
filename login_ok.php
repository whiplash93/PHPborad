<?
require_once("dbconfig.php");
 //# 세션 스타트 #//
 session_start();
 
 //# DB연결 설정 #//
 $query = " select * from tb_admin ";
 $result = $db->query($query);
 $result = $result->fetch_assoc();

 $sql = 'select count(admin_pwd) as cnt from tb_admin where admin_pwd=password("' . $pw . '")';
 $result2 = $db->query($sql);
 $row = $result2->fetch_assoc();
 
 
 //## 사용자 로그아웃 시도 ##//
 if($logout == "yes")
 {
  //# 모든세션값 소멸 #//
  session_unset(); 
  echo "<script>alert('로그아웃성공');location.href='index.php';</script>";
 }
 //## 사용자 로그인 시도 ##//
 else
 {
  //# 아이디/패스워드 체크(대소문자 비교) #//
  if($result[admin_id] == $id && $row[cnt])
  {
   $_SESSION["session_id"] =  $id;
   $_SESSION["session_pw"] =  $pw;
   //# 해당페이지이동(Header앞에 공백,출력문자열 있으면 에러) #//
   echo "<script>window.opener.location.reload();window.close();</script>";
   //Header("Location:index.php");
  }
  else
  {
   echo "<script>alert('로그인실패');location.href='login_index.php';</script>";
  }
 }

?>