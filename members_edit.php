<?php require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}

$db=connect_db();

$group = get_group($db, $_GET['id']);
$selectedgid=$group['id'];

// TODO: Check if the user is in the group they are editing

if($_SERVER['REQUEST_METHOD']=='POST'){
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
            }
        }
    }

    if(!empty($removemem) && filter_var($removemem, FILTER_VALIDATE_EMAIL)){
        $q = $db->prepare("SELECT id FROM users WHERE email = :email");
        $q->bindValue(":email", $addmem);
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
            }
        }

    }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>
      Members Editing
    </title>
    <script src="jquery-1.12.0.min.js" type="text/javascript"></script>
    <script>
    function reload(id){
            $.ajax({
                //type: "POST",
                url: "members_edit.php",
                //data:'id='+id,
                success: function(content){
                $("body").html(content);
                }
            });
            self.location="members_edit.php?id="+id;
        }
    </script>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>
    <?php
    $db=connect_db();

    $uid=get_logged_in_user_id();
    $groid="SELECT group_id FROM group_members WHERE user_id=$uid";
    ?>

    <div class='container'>

    <form action="" class='form-horizontal' role='form' method='post' name='mementry'>

    <div class='row'>
    <br><br>
    <div class='col-sm-3'>
    <label for='name'>Select the group</label>
    <select name='sgrop' id='greload' onChange="reload(this.value);" class='form-control'>
    <option value=''>Select Group</option>;
    <?php 
    foreach($db->query($groid) as $groupid){
      $gid=$groupid['group_id'];
      $gname="SELECT name FROM groups WHERE id=$gid";
      foreach($db->query($gname) as $groupname){
        $groname=$groupname['name'];
        echo "<option value='$gid'>$groname</option>";
      }
    } ?>
    </select>
    </div>
    </div>

    <div class='jumbotron'>
    <h2>Group Member Editing For Group: <?= htmlspecialchars($group['name'])?></h2>
    </div>

    <div class='row'>

    <div class ='col-sm-3'>
    <label for='name'>Add a new member(email):</label>
    <input type='text' class='form-control' name='addmemb' id='addmemb'>
    </div>


    <div class ='col-sm-3'>
    <label for='name'>Remove a current member(email):</label>
    <input type='text' class='form-control' name='removemem' id='removemem'>
    </div>
    </div>
    <br><br>

    <input type='submit' class='btn btn-info' value='SUBMIT'>
    </form>
    </div>
    </body>
    </html>


