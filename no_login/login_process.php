<?

 //# DB연결 설정 #//
require_once("dbconfig.php");
 //# DB연결 설정 #//

 //# 패스워드 변경 처리 #//
 if ($mode == "pw_change")
 {
  $qry_result = mysql_query(" update tb_admin set admin_pwd = '$pw' ",$db);
  
  echo "<script>alert('변경완료');window.close();</script>";
 }
 else if($mode == "pw_find")
 {
  $query = " select * from tb_admin where admin_id = '$id' ";
  $result = $db->query($query);
  $row = $result->fetch_assoc();
  if(empty($row))
  {
  echo "<script>alert('아이디를 찾을수 없음');window.close();</script>";
  }
  else
  {
  echo "<script>alert('당신의 패스워드는 $row[id] 입니다.');window.close();</script>";
  }
 }
 
?>