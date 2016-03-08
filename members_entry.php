<?php require_once 'includes/all.php';

$db=connect_db();

$selectedgid=$_SESSION['memgid'];

// TODO: Check if the user is in the group they are editing
if($_SERVER['REQUEST_METHOD']=='POST' && !empty($selectedgid)){
    $addmem=$_POST['addmemb'];
    $removemem=$_POST['removemem'];
	 if(!empty($addmem) && !filter_var($addmem, FILTER_VALIDATE_EMAIL)) {
	 	//echo "<script type'text/javascript'>alert('Invalid add member email')</script>";
		$_SESSION['flash_errors'] = 'Invalid add member email';
		header("Location: members_edit?id=$selectedgid.php");
		//echo "<script>setTimeout(\"location.href='members_edit.php?\", 1000);</script>";
	 }
	 elseif(!empty($removemem) && !filter_var($removemem, FILTER_VALIDATE_EMAIL)) {
	 	//echo "<script type'text/javascript'>alert('Invalid remove member email')</script>";
		$_SESSION['flash_errors'] = 'Invalid remove member email';
		header("Location: members_edit?id=$selectedgid.php");
		//echo "<script>setTimeout(\"location.href='members_edit.php?\", 1000);</script>";
	 } elseif (empty($removemem) && empty($addmem)) {
	 	  $_SESSION['flash_errors'] = 'No emails entered';
		  header("Location: members_edit?id=$selectedgid.php"); 
	 }else{
	 	  if (empty($addmem)) {
        $j = $db->prepare("SELECT id FROM users WHERE email = :email");
        $j->bindValue(":email", $addmem);
        $j->execute();
		  $k = $j->fetch();
		  }
		  elseif (empty($removemem)) {
		  $j = $db->prepare("SELECT id FROM users WHERE email = :email");
        $j->bindValue(":email", $removemem);
        $j->execute();
		  $k = $j->fetch();
		  }
		  else {
		  	$_SESSION['flash_session'] = 'here';
			header("Location: members_edit?id=$selectedgid.php");
		  }
		  if (!$k) {
    	    if(!empty($addmem) && filter_var($addmem, FILTER_VALIDATE_EMAIL)){
			 //echo "IN";
    	     $q = $db->prepare("SELECT id FROM users WHERE email = :email");
           $q->bindValue(":email", $addmem);
           $q->execute();
			  $jq=$q->fetch();
           //foreach($q as $nid){
			  	//echo "nmem";
            //check if user is already in the group
            $nmem=$jq['id'];
            $q2 = $db->prepare("SELECT EXISTS (SELECT 1 FROM group_members WHERE user_id=:user_id AND group_id=:group_id)");
            $q2->bindValue(":user_id", $nmem);
            $q2->bindValue(":group_id", $selectedgid);
            $q2->execute();
            $res=$q2->fetch()[0];
            if(!$res){
                $stmt=$db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)");
                $stmt->bindValue(":group_id", $selectedgid);
                $stmt->bindValue(":user_id", $nmem);
                $stmt->execute();
					 $_SESSION['flash_success'] = $addmem . ' successfully added';
                header("Location: members_edit.php?id=".urlencode($selectedgid));
            }
            else{
                //echo "<script type='text/javascript'>alert('User already exist in this group!')</script>";
                //echo "<script>setTimeout(\"location.href='members_edit.php?id='.urlencode($selectedgid);\", 1000);</script>";
					 $_SESSION['flash_errors'] = 'User already exists in group';
					 header("Location: members_edit?id=$selectedgid.php");
            }
           //}
          }

          if(!empty($removemem) && filter_var($removemem, FILTER_VALIDATE_EMAIL)){
			 //echo "IN2";
           $q = $db->prepare("SELECT id FROM users WHERE email = :email");
           $q->bindValue(":email", $removemem);
           $q->execute();
			  $remid=$q->fetch();
           //foreach($q as $remid){
            //check if user is actually in the group
            $reid=$remid['id'];
            $q2 = $db->prepare("SELECT EXISTS (SELECT 1 FROM group_members WHERE user_id=:user_id AND group_id=:group_id)");
            $q2->bindValue(":group_id", $selectedgid);
            $q2->bindValue(":user_id", $reid);
            $q2->execute();
            $res = $q2->fetch()[0];
            if($res){
                $stmt=$db->prepare("DELETE FROM group_members WHERE user_id=:user_id AND group_id=:group_id");
                $stmt->bindValue(":group_id", $selectedgid);
                $stmt->bindValue(":user_id", $reid);
                $stmt->execute();
					 $_SESSION['flash_success'] = $removemem . ' successfully removed';
                header("Location: members_edit?id=".urlencode($selectedgid));
            }
            else{
                //echo "<script type='text/javascript'>alert('No such a group member!')</script>";
                //echo "<script>setTimeout(\"location.href='members_edit.php?'\", 1000);</script>";
					 $_SESSION['flash_errors'] = 'No such group member';
					 header("Location: members_edit?id=$selectedgid.php");
            }
           //}
          }
			 //echo "WOLOLO";
	      } else {
			//echo "<script type='text/javascript'>alert('No emails entered!')</script>";
			$_SESSION['flash_errors'] = 'No email entered';
			header("Location: members_edit?id=$selectedgid.php");
			}
		}
		//echo "THE BIG MISS";
}else{
	//echo "<script type='text/javascript'>alert('Please select a group first')</script>";
	//echo "<script>setTimeout(\"location.href='members_edit.php';\", 1000);</script>";
	$_SESSION['flash_errors'] = 'No group selected';
	header("Location: members_edit?id=$selectedgid.php");
}

$db=null;
?>