<?php

$f="v3.mp4";
$w=640;
$h=ceil((720/1280)*$w);
$fpr=36;

$d=explode("Duration:",shell_exec("ffmpeg -i ".$f." 2>&1")); 
//var_dump($d);
$fps=explode(" fps,",$d[1]); $fps=explode(" ",$fps[0]); $fps=array_pop($fps);
$d=explode(",",$d[1]);
$d=explode(".",trim($d[0]));
$d[0]=explode(":",trim($d[0]));
$dd=0;
foreach($d[0] as $k=>$v) {
    $k=pow(60,2-$k);
    $dd+=$k*$v;
}
$dd=$dd*$fps+$d[1];
$frame_rate=$fps/($dd/$fpr);
print "frames=".$dd."; fps=".$fps."; fr=".$frame_rate."";
$files=scandir("tmp"); foreach ($files  as $fi) { if (is_file("tmp/".$fi)) { unlink("tmp/".$fi); }}

chdir("tmp");
shell_exec ("ffmpeg -i ../".$f." -r ".$frame_rate." image-%3d.png");
$files="";
$frames_in_pic=0;
$_files=scandir(".");
foreach ($_files as $fi) {
    if (is_file($fi)) { 
	// check is frame black
	$img=imagecreatefrompng($fi);
	$img_tmp=imagecreatetruecolor(1,1);
	imagecopyresampled ( $img_tmp , $img , 0 , 0 , 0 , 0 , 1 , 1 , imagesx($img) , imagesy($img) );
	imagedestroy($img);
	$tmp_color=imagecolorat($img_tmp,0,0);
	//print $tmp_color."\n";
	imagedestroy($img_tmp);
	if ($tmp_color!=0) {
	    $files.=$fi." ";
	    $frames_in_pic++;
	}
    }
}
shell_exec("echo \"".$files."\" | convert @- -sharpen 0x1.0 -filter Lanczos -distort resize ".$w."x +append -interlace JPEG -quality 80% ../dji_".$frames_in_pic."_".$w."_".$h.".jpg");
chdir("..");
$files=scandir("tmp"); foreach ($files  as $fi) { if (is_file("tmp/".$fi)) { unlink("tmp/".$fi); }}


die("\n");

?>