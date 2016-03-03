<?php require_once 'includes/all.php';

$db=connect_db();

$selectedgid=$_SESSION['memgid'];

// TODO: Check if the user is in the group they are editing
if($_SERVER['REQUEST_METHOD']=='POST' && !empty($selectedgid)){
    $addmem=$_POST['addmemb'];
    $removemem=$_POST['removemem'];

    if(!empty($addmem) && filter_var($addmem, FILTER_VALIDATE_EMAIL)){
        $q = $db->prepare("SELECT id FROM users WHERE email = :email");
        $q->bindValue(":email", $addmem);
        $q->execute();

        foreach($q as $nid){
            //check if user is already in the group
            $nmem=$nid['id'];
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
                header("Location: group.php?id=".urlencode($selectedgid));
            }
            else{
                echo "<script type='text/javascript'>alert('User already exist in this group!')</script>";
                echo "<script>setTimeout(\"location.href='members_edit.php?id='.urlencode($selectedgid);\", 1000);</script>";
            }
        }
    }

    if(!empty($removemem) && filter_var($removemem, FILTER_VALIDATE_EMAIL)){
        $q = $db->prepare("SELECT id FROM users WHERE email = :email");
        $q->bindValue(":email", $removemem);
        $q->execute();
        foreach($q as $remid){
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

                header("Location: group.php?id=".urlencode($selectedgid));
            }
            else{
                echo "<script type='text/javascript'>alert('No such a group member!')</script>";
                echo "<script>setTimeout(\"location.href='members_edit.php?'\", 1000);</script>";
            }
        }
    }
}else{
	echo "<script type='text/javascript'>alert('Please select a group first')</script>";
	echo "<script>setTimeout(\"location.href='members_edit.php';\", 1000);</script>";
}

$db=null;
?>