<?php 
require_once 'includes/all.php';

$db=connect_db();

$user_id = get_logged_in_user_id();

if(isset($_POST["filesub"])){
	$pic=$_FILES["fileupload"]["name"];
	$tmp=$_FILES["fileupload"]["tmp_name"];
	$size=$_FILES["fileupload"]["size"];

	$filetype=strtolower(pathinfo($pic, PATHINFO_EXTENSION));

	if(($filetype=="jpg" || $filetype=="gif" || $filetype=="jpeg" || $filetype=="png" )&& $size<=1048576){
		$filedata=file_get_contents($tmp);

		$stmt=$db->prepare("INSERT INTO pic (filename,filedata, filesize) VALUES (?,?,?)");
		$stmt->bindParam(1, $pic, PDO::PARAM_STR, 255);
		$stmt->bindParam(2, $filedata, PDO::PARAM_LOB);
		$stmt->bindParam(3, $size, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $db->prepare("SELECT id FROM pic WHERE filename = :filename");
		$stmt->bindParam('filename', $pic);
		$stmt->execute();
		$pic_id = $stmt->fetch();
		
		$stmt=$db->prepare("UPDATE users SET pic_id = :pic_id WHERE id = :user_id");
		$stmt->bindParam("pic_id", $pic_id[0]);
		$stmt->bindParam("user_id", $user_id);
		$stmt->execute();
			
		


		echo "<script type='text/javascript'>alert('Upload Successful'); window.location.href='profile_edit.php'</script>";	
	}else{
		//header("Location: course_edit.php");	
		echo "<script type='text/javascript'>alert('Failed to upload size is $size filetype is $filetype'); window.location.href='profile_edit.php'</script>";	
	}
}

$db=null;

?>
