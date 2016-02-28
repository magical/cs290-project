<?php require_once 'includes/all.php';

	$db=connect_db();

	$gname=$_POST['groupnm'];
	$gmsg=$_POST['gmsg'];
	$newsou=$_POST['newcou'];
	$newcam=$_POST['meetcam'];
	$newbdg=$_POST['budg'];
	$newday=$_POST['week'];
	$newtime=$_POST['selt'];


	$uid=get_logged_in_user_id();

	$gid="SELECT group_id FROM group_members WHERE user_id=$uid";

	$campus="SELECT name FROM campuses WHERE id=$newcam";


	if(!empty($gname)){
		foreach($db->query($gid) as $groupid){
			$groid=$groupid['group_id'];
			$stmt=$db->prepare("UPDATE groups SET name='$gname' WHERE id=$groid");
			$stmt->execute();

			header("Location:group.php");			
		}
	}

	if(!empty($gmsg)){
		foreach($db->query($gid) as $groupid){
			$groid=$groupid['group_id'];
			$stmt=$db->prepare("UPDATE groups SET blurb='$gmsg' WHERE id=$groid");
			$stmt->execute();

			header("Location:group.php");			
		}
	}

	if(!empty($newsou)){
		foreach($db->query($gid) as $groupid){
			$groid=$groupid['group_id'];
			$stmt=$db->prepare("UPDATE groups SET course_id='$newsou' WHERE id=$groid");
			$stmt->execute();

			header("Location:group.php");			
		}
	}

	if(!empty($newcam) && !empty($newbdg)){
		foreach($db->query($gid) as $groupid){
			$groid=$groupid['group_id'];
			foreach($db->query($campus) as $cam){
				$camnm=$cam['name'];
				$stmt=$db->prepare("UPDATE groups SET place='$camnm $newbdg' WHERE id=$groid");
				$stmt->execute();
				header("Location:group.php");			
			}			
		}

	}

	if(!empty($newday) && !empty($newtime)){
		foreach($db->query($gid) as $groupid){
			$groid=$groupid['group_id'];
			$stmt=$db->prepare("UPDATE groups SET time='$newday $newtime:00' WHERE id=$groid");
			$stmt->execute();

			header("Location:group.php");
		}

	}
?>
