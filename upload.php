<?php 
require_once 'includes/all.php';

$db=connect_db();

$pic=$_FILES["fileupload"]["name"];
$tmp=$_FILES["fileupload"]["tmp_name"];
$size=$_FILES["fileupload"]["size"];

$uploadOK=1;

$filetype=pathinfo($pic, PATHINFO_EXTENSION);

if(isset($_POST["filesub"])){
	$check=getimagesize($tmp);
	if($check == false && file_exists($pic) && $size>1048576 && $filetype!="jpg" && $filetype!="png" && $filetype!="jpeg" && $filetype!="gif"){
		$uploadOK=0;
	}else{
		$uploadOK=1;
	}
}

/*if(file_exists($pic) && $size>1048576 && $filetype!="jpg" && $filetype!="png" && $filetype!="jpeg" && $filetype!="gif"){
	$uploadOK=0;
}*/

if($uploadOK==1){
	$filedata=file_get_contents($tmp);

	$stmt=$db->prepare("INSERT INTO pic (filename,filedata, filesize) VALUES (?,?,?)");
	$stmt->bindParam(1, $pic, PDO::PARAM_STR, 255);
	$stmt->bindParam(2, $filedata, PDO::PARAM_LOB);
	$stmt->bindParam(3, $size, PDO::PARAM_INT);
	$stmt->execute();


	header("Location: display.php");
}else{
	header("Location: dataentry.php");
}

$db=null;

?>