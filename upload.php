<?php 
require_once 'includes/all.php';

$db=connect_db();


if(isset($_POST["filesub"])){
	$pic=$_FILES["fileupload"]["name"];
	$tmp=$_FILES["fileupload"]["tmp_name"];
	$size=$_FILES["fileupload"]["size"];

	$filetype=pathinfo($pic, PATHINFO_EXTENSION);

	if($filetype=="jpg" || $filetype=="gif" || $filetype=="jpeg" || $filetype=="png" && $size<=1048576){
		$filedata=file_get_contents($tmp);

		$stmt=$db->prepare("INSERT INTO pic (filename,filedata, filesize) VALUES (?,?,?)");
		$stmt->bindParam(1, $pic, PDO::PARAM_STR, 255);
		$stmt->bindParam(2, $filedata, PDO::PARAM_LOB);
		$stmt->bindParam(3, $size, PDO::PARAM_INT);
		$stmt->execute();


		echo "<script type='text/javascript'>alert('Upload Successfully'); window.location.href='dataentry.php'</script>";	
	}else{
		//header("Location: dataentry.php");	
		echo "<script type='text/javascript'>alert('Failed to upload'); window.location.href='dataentry.php'</script>";	
	}
}

$db=null;

?>