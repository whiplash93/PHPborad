<?
require_once("dbconfig.php");



// // 원본 이미지 load
// $path = $_SERVER['DOCUMENT_ROOT'].'/test/upload/테스트2.PNG';
// $o_img = imagecreatefrompng($path);

// // 썸네일 이미지가 들어갈 집 만들기
// $n_img = imagecreatetruecolor(200,100);

// // 생성한 집에 원본이미지를 축소해서 넣기
// imagecopyresampled($n_img,$o_img,0,0,0,0,200,100,400,200);

// // 이미지 저장하기
// $n_path =  $_SERVER['DOCUMENT_ROOT'].'/test/upload/thumb.png';
// imagepng($n_img, $n_path);

// // 썸네일 생성
// imagecopyresampled($n_img, $o_img, 0,0,$offsetX,$offsetY,$w,$h,$cropW,$cropH);







$root = $_SERVER['DOCUMENT_ROOT'].'/test/upload/';

$tbname = $_GET['tbname'];
$query = "select b_file, b_filedate from $tbname where b_no=".$_GET['no'];
$result = $db->query($query);
$row = $result->fetch_assoc();


// if(rename($root.$row['b_filedate'],$root.$row['b_file']))
// 	echo "성공";
// else 
// 	echo "실패";
			
	$image_path = $_SERVER['DOCUMENT_ROOT'].'/test/upload/'.$_GET['image'];
	
//전송된 이미지 여부 확인
if( isset($_GET['image']) ) {

	//이미지 파일명
// 	$image_name = $_GET['image'];
	$image_name = $row['b_file'];

	//이미지 전체경로를 포함한 이미지명
	$image_path = $_SERVER['DOCUMENT_ROOT'].'/test/upload/'.$_GET['image'];
	//넘어온 이미지경로의 존재여부와 파일여부 확인

	
// 	if(file_exists($image_path) && is_file($image_path)) {
		
		//넘어온 파일 확장자 추출
		$tmp_name = pathinfo($image_path);
		$ext = strtolower($tmp_name['extension']);

		//지정된 확장자만 보여주도록 필터링
		if($ext == 'jpg' || $ext='gif' || $ext='png' || $ext='bmp' || $ext == 'JPG' || $ext='GIF' || $ext='PNG' || $ext='BMP') {

			//이미지 크기정보와 사이즈를 얻어옴
			$img_info = getimagesize($image_path);
			$filesize = filesize($image_path);

			//이미지 전송을 위한 헤더설정
			header("Content-Type: {$img_info['mime']}\n");
			header("Content-Disposition: inline;filename='$image_name'\n");
			header("Content-Length: $filesize\n");
				
			//이미지 내용을 읽어들임
			readfile($image_path);

		}//if
	}
// }//if

?>