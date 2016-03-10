<?php require_once 'includes/all.php';

$db=connect_db();

$selectedgid=$_SESSION['memgid'];
$user = get_user($db, get_logged_in_user_id());

// TODO: Check if the user is in the group they are editing
if($_SERVER['REQUEST_METHOD']=='POST' && !empty($selectedgid)){
    $addmem=$_POST['addmemb'];
    if(isset($_POST['removemem'])) {
      $removemem=$_POST['removemem'];
    }else {
      $removemem = array();
    }
	 if (!empty($addmem) && !empty($removemem)){ 
	 }
	 if(!empty($addmem) && !filter_var($addmem, FILTER_VALIDATE_EMAIL)) {
	 	//echo "<script type'text/javascript'>alert('Invalid add member email')</script>";
		$_SESSION['flash_errors'] = 'Invalid add member email';
		header("Location: members_edit?id=$selectedgid");
		//echo "<script>setTimeout(\"location.href='members_edit.php?\", 1000);</script>";
	 }
	 /*elseif(!empty($removemem) && !filter_var($removemem, FILTER_VALIDATE_EMAIL)) {
	 	//echo "<script type'text/javascript'>alert('Invalid remove member email')</script>";
		$_SESSION['flash_errors'] = 'Invalid remove member email';
		header("Location: members_edit?id=$selectedgid");
		//echo "<script>setTimeout(\"location.href='members_edit.php?\", 1000);</script>";
	 }*/ elseif (empty($removemem) && empty($addmem)) {
	 	  $_SESSION['flash_errors'] = 'No emails entered';
       header("Location: members_edit?id=$selectedgid"); 
     }else{
       if (!empty($addmem)) {
         $j = $db->prepare("SELECT id FROM users WHERE email = :email");
         $j->bindValue(":email", $addmem);
         $j->execute();
         $k = $j->fetch();
         if (empty($k['id'])) {
           $_SESSION['flash_errors'] = 'No such user exists';
           header("Location: members_edit.php?id=$selectedgid");
         }
       }
       if (!empty($removemem)) {
           $j = $db->prepare("SELECT id FROM users WHERE email = :email");
         foreach($removemem as $useremail){
           
           $j->bindValue(":email", $useremail);
           $j->execute();
           $k = $j->fetch();
           if (empty($k['id'])) {
             $_SESSION['flash_errors'] = 'No such user exists';
             header("Location: members_edit.php?id=$selectedgid");
           }
         }
       }
       if (!empty($k)) {
         if(!empty($addmem) && filter_var($addmem, FILTER_VALIDATE_EMAIL)){
           $q = $db->prepare("SELECT id FROM users WHERE email = :email");
           $q->bindValue(":email", $addmem);
           $q->execute();
           $jq=$q->fetch();  

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
               $_SESSION['flash_success'] = array('alert' => $addmem . ' successfully added');
               header("Location: members_edit.php?id=".urlencode($selectedgid));
               exit(0);
             }
             else{
               $_SESSION['flash_errors'] = 'User already exists in group';
               header("Location: members_edit?id=$selectedgid");  
             }
         } 

          if(!empty($removemem)){
			 $q = $db->prepare("SELECT id FROM users WHERE email = :email");
           foreach($removemem as $userCheck){
             $q->bindValue(":email", $userCheck);
             $q->execute();
           	 $remid=$q->fetch();
             //foreach($q as $remid){
             //check if user is actually in the group
             $q2 = $db->prepare("SELECT EXISTS (SELECT 1 FROM group_members WHERE user_id=:user_id AND group_id=:group_id)");
             $reid=$remid['id'];
             $q2->bindValue(":group_id", $selectedgid);
             $q2->bindValue(":user_id", $reid);
             $q2->execute();
             $res = $q2->fetch()[0];
                
                
             if($res){
               $stmt=$db->prepare("DELETE FROM group_members WHERE user_id=:user_id AND group_id=:group_id");
               $stmt->bindValue(":group_id", $selectedgid);
               $stmt->bindValue(":user_id", $reid);
               $stmt->execute();
               $_SESSION['flash_success'][] = htmlspecialchars($userCheck) . ' successfully removed';
                  
             }
           } 
            foreach($removemem as $headerCheck) {
                if($user['email'] === $headerCheck) {
                  header("Location: group.php?id=".urlencode($selectedgid));
                  exit(0);
                }
            }
                header("Location: members_edit?id=".urlencode($selectedgid));
                
           //}
          }
			 //echo "WOLOLO";
	      } else {
			//echo "<script type='text/javascript'>alert('No emails entered!')</script>";
			$_SESSION['flash_errors'] = 'No such user exists';
			header("Location: members_edit?id=$selectedgid");
		}
	}
		//echo "THE BIG MISS";
}else{
	//echo "<script type='text/javascript'>alert('Please select a group first')</script>";
	//echo "<script>setTimeout(\"location.href='members_edit.php';\", 1000);</script>";
	if (!isset($_SESSION['flash_success'])) {
	$_SESSION['flash_errors'] = 'No group selected';
	header("Location: members_edit?id=$selectedgid");
	}
}

$db=null;
?>