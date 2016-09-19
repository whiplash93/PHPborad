<?

// // 원본 이미지 load
// $path = $_SERVER['DOCUMENT_ROOT'].'/test/upload/테스트2.PNG';
// $o_img = imagecreatefrompng($path);
//make_thumbnail("./upload/".$file, 200, 200, "./upload/thum_".$file);

function make_thumbnail($source_path, $width, $height, $thumbnail_path){
    list($img_width,$img_height, $type) = getimagesize($source_path);
    if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
    if ($type==1) $img_sour = imagecreatefromgif($source_path);
    else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
    else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
    else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);
    if ($img_width > $img_height) {
        $w = round($height*$img_width/$img_height);
        $h = $height;
        $x_last = round(($w-$width)/2);
        $y_last = 0;
    } else {
        $w = $width;
        $h = round($width*$img_height/$img_width);
        $x_last = 0;
        $y_last = round(($h-$height)/2);
    }
    if ($img_width < $width && $img_height < $height) {
        $img_last = imagecreatetruecolor($width, $height); 
        $x_last = round(($width - $img_width)/2);
        $y_last = round(($height - $img_height)/2);

        imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
        imagedestroy($img_sour);
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    } else {
        $img_dest = imagecreatetruecolor($w,$h); 
        imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height); 
        $img_last = imagecreatetruecolor($width,$height); 
        imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
        imagedestroy($img_dest);
    }
    if ($thumbnail_path) {
        if ($type==1) imagegif($img_last, $thumbnail_path, 100);
        else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 100);
        else if ($type==3 ) imagepng($img_last, $thumbnail_path, 100);
        else if ($type==15) imagebmp($img_last, $thumbnail_path, 100);
    } else {
        if ($type==1) imagegif($img_last);
        else if ($type==2 ) imagejpeg($img_last);
        else if ($type==3 ) imagepng($img_last);
        else if ($type==15) imagebmp($img_last);
    }
    imagedestroy($img_last);
}
?>