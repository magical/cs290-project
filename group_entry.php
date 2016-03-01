<?php require_once 'includes/all.php';

//session_start();

	$db=connect_db();


	$gid=$_SESSION['selgid'];


if($_SERVER['REQUEST_METHOD']=='POST'){

	$gname=$_POST['groupnm'];
	$gmsg=$_POST['gmsg'];
	$newsou=$_POST['newcou'];
	$newcam=$_POST['meetcam'];
	$newbdg=$_POST['budg'];
	$newday=$_POST['week'];
	$newtime=$_POST['selt'];

	$campus="SELECT name FROM campuses WHERE id=$newcam";


	if(!empty($gname)){
			$stmt=$db->prepare("UPDATE groups SET name='$gname' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=$gid");			
	}

	if(!empty($gmsg)){
			$stmt=$db->prepare("UPDATE groups SET blurb='$gmsg' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=$gid");			
	}

	if(!empty($newsou)){
			$stmt=$db->prepare("UPDATE groups SET course_id='$newsou' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=$gid");
	}

	if(!empty($newcam) && !empty($newbdg)){
			foreach($db->query($campus) as $cam){
				$camnm=$cam['name'];
				$stmt=$db->prepare("UPDATE groups SET place='$camnm $newbdg' WHERE id=$gid");
				$stmt->execute();
				header("Location:group.php?id=$gid");	
		}

	}

	if(!empty($newday) && !empty($newtime)){
			$stmt=$db->prepare("UPDATE groups SET time='$newday $newtime:00' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=$gid");
	}
}
?>
