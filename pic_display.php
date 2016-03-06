<?php  	
	 require_once 'includes/all.php';
	 	$db = connect_db();
	 	$user = get_user($db, get_logged_in_user_id());
	 	$stmt = $db->prepare("SELECT filedata, filename FROM pic WHERE id = :pic_id");
		$stmt->bindParam("pic_id", $user['pic_id']);
		$stmt->execute();
		$profpic = $stmt->fetch();
		$type = 'image'.substr($profpic['filename'], -3);
		header("Content-Type: $type");	
		echo $profpic['filedata'];
?>