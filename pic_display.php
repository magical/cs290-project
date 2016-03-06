<?php  	
	 require_once 'includes/all.php';
	 	$db = connect_db();
		if (array_key_exists('id', $_GET)) {
			$user = get_user($db, $_GET['id']);
		} elseif (is_logged_in()) {
			$user= get_user($db, get_logged_in_user_id()); 
		} else {
			header('Location: signin.php');
		}
	 	$stmt = $db->prepare("SELECT filedata, filename FROM pic WHERE id = :pic_id");
		$stmt->bindParam("pic_id", $user['pic_id']);
		$stmt->execute();
		$profpic = $stmt->fetch();
		$type = 'image'.substr($profpic['filename'], -3);
		header("Content-Type: $type");	
		echo $profpic['filedata'];
?>