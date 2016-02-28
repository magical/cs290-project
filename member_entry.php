<?php require_once 'includes/all.php';

$db=connect_db();



$uid=get_logged_in_user_id();


if($_SERVER['REQUEST_METHOD']=='POST'){
	$addmem=$_POST['addmemb'];
	$removemem=$_POST['removemem'];
	if(!empty($addmem) && filter_var($addmem, FILTER_VALIDATE_EMAIL)){
		$newid="SELECT id FROM users WHERE email='$addmem'";
		foreach($db->query($newid) as $nid){
			$nmem=$nid['id'];

			$gid="SELECT group_id FROM group_members WHERE user_id=$uid";
			foreach($db->query($gid) as $groupid){
				$groid=$groupid['group_id'];

				$stmt=$db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)");
				$stmt->bindValue(":group_id", $groid);
				$stmt->bindValue(":user_id", $nmem);
				$stmt->execute();

				header("Location: members_edit.php");
			}
		}

	}

	if(!empty($removemem) && filter_var($removemem, FILTER_VALIDATE_EMAIL)){
		$removeid="SELECT id FROM users WHERE email='$removemem'";
		foreach($db->query($removeid) as $remid){
			$reid=$remid['id'];

			$gid="SELECT group_id FROM group_members WHERE user_id=$uid";
			foreach($db->query($gid) as $groupid){
				$groid=$groupid['group_id'];

				$stmt=$db->prepare("DELETE FROM group_members WHERE user_id=$reid && group_id=$groid");
				$stmt->execute();

				header("Location: members_edit.php");
			}
		}

	}
}

$db=null;
?>



