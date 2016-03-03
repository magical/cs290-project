<?php require_once 'includes/all.php';

//session_start();

	$db=connect_db();


	$gid=$_SESSION['selgid'];


if($_SERVER['REQUEST_METHOD']=='POST' && !empty($gid)){//check if the user has selected a group

	$gname=$_POST['groupnm'];
	$gmsg=$_POST['gmsg'];
	$newsou=$_POST['newcou'];
	$newcam=$_POST['meetcam'];
	$newbdg=$_POST['budg'];
	$newday=$_POST['week'];
	$newtime=$_POST['selt'];
	$check=$_POST['hcheck'];

	$campus="SELECT name FROM campuses WHERE id=$newcam";


	if(!empty($gname)){
			$stmt=$db->prepare("UPDATE groups SET name='$gname' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=".urlencode($gid));			
	}

	if(!empty($gmsg)){
			$stmt=$db->prepare("UPDATE groups SET blurb='$gmsg' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=".urlencode($gid));			
	}

	if(!empty($newsou)){
			$stmt=$db->prepare("UPDATE groups SET course_id='$newsou' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=".urlencode($gid));
	}

	if(!empty($newcam) && !empty($newbdg)){
			foreach($db->query($campus) as $cam){
				$camnm=$cam['name'];
				$stmt=$db->prepare("UPDATE groups SET place='$camnm $newbdg' WHERE id=$gid");
				$stmt->execute();
				header("Location:group.php?id=".urlencode($gid));	
		}

	}

	if(!empty($newday) && !empty($newtime)){
			$stmt=$db->prepare("UPDATE groups SET time='$newday $newtime:00' WHERE id=$gid");
			$stmt->execute();

			header("Location:group.php?id=".urlencode($gid));
	}

	if($check=='1'){
		$stmt=$db->prepare("UPDATE groups SET is_private='$check' WHERE id=$gid");
		$stmt->execute();

		header("Location: group.php?id=".urlencode($gid));
	}

	if($check=='0'){
		$stmt=$db->prepare("UPDATE groups SET is_private='$check' WHERE id=$gid");
		$stmt->execute();

		header("Location: group.php?id=".urlencode($gid));
	}
}else{
        echo "<script type='text/javascript'>alert('Please select a group first')</script>";
        echo "<script>setTimeout(\"location.href='group_edit.php';\", 1000);</script>";
}
$db=null;
?>
