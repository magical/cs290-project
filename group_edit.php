<?php 
//session_start();

require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}
    $db=connect_db();


/*if($_SERVER['REQUEST_METHOD']=='POST' && !empty($selectedgid)){//check if the user has selected a group
echo "$selectedgid";
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
            $stmt=$db->prepare("UPDATE groups SET name='$gname' WHERE id=$selectedgid");
            $stmt->execute();

            header("Location:group.php?id=".urlencode($selectedgid));           
    }

    if(!empty($gmsg)){
            $stmt=$db->prepare("UPDATE groups SET blurb='$gmsg' WHERE id=$selectedgid");
            $stmt->execute();

            header("Location:group.php?id=".urlencode($selectedgid));           
    }

    if(!empty($newsou)){
            $stmt=$db->prepare("UPDATE groups SET course_id='$newsou' WHERE id=$selectedgid");
            $stmt->execute();

            header("Location:group.php?id=".urlencode($selectedgid));
    }

    if(!empty($newcam) && !empty($newbdg)){
            foreach($db->query($campus) as $cam){
                $camnm=$cam['name'];
                $stmt=$db->prepare("UPDATE groups SET place='$camnm $newbdg' WHERE id=$selectedgid");
                $stmt->execute();
                header("Location:group.php?id=".urlencode($selectedgid));   
        }

    }

    if(!empty($newday) && !empty($newtime)){
            $stmt=$db->prepare("UPDATE groups SET time='$newday $newtime:00' WHERE id=$selectedgid");
            $stmt->execute();

            header("Location:group.php?id=".urlencode($selectedgid));
    }

    if($check=='1'){
        $stmt=$db->prepare("UPDATE groups SET is_private='$check' WHERE id=$selectedgid");
        $stmt->execute();

        header("Location: group.php?id=".urlencode($selectedgid));
    }

    if($check=='0'){
        $stmt=$db->prepare("UPDATE groups SET is_private='$check' WHERE id=$selectedgid");
        $stmt->execute();

        header("Location: group.php?id=".urlencode($selectedgid));
    }
}else{
    echo "<script type='text/javascript'>alert('Please select a group first!')</script>";
}*/

?>
<!DOCTYPE html>
<html>
  <head>
    <title>
      Group Editing
    </title>
    <script src="jquery-1.12.1.min.js" type="text/javascript"></script>
    <script>
    	function getbudg(val){
    		$.ajax({
    			type:"POST",
    			url:"get_building.php",
    			data: 'id='+val,
    			success: function(data){
    				$("#mbudg").html(data);
    			}
    		});
    	}

        function reload(id){
            $.ajax({
                //type: "POST",
                url: "group_edit.php",
                //data:'id='+id,
                success: function(content){
                $("body").html(content);
                }
            });
            self.location="group_edit.php?id="+id;
        }
    </script>
    <?php include 'includes/_head.html';?>
  </head>


  <body>
    <?php include 'includes/_nav.php';?>

    <div class='container'>

	<form action='group_entry.php' class='form-horizontal' role='form' method='post' name='dentry'>

    <div class='row'>
    <br><br>
    <div class='col-sm-3'>
    <label for='name'>Select the group</label>
    <select name='sgrop' id='greload' onChange="reload(this.value);" class='form-control'>
    <option value=''>Select Group</option>;
    <?php 
    $group = get_group($db, $_GET['id']);
    $selectedgid=$group['id'];
    $user_id = get_logged_in_user_id();
    $user_groups = get_user_groups($db, $user_id);
    $_SESSION['selgid']=$selectedgid;
    foreach($user_groups as $g){
        echo '<option value="'.htmlspecialchars($g['id']).'">'.htmlspecialchars($g['name'])."</option>\n";
        }
    ?>
    </select>
    </div>
    </div>

    <div class='jumbotron'>
    <h2>Study Group Editing For Group: <?= htmlspecialchars($group['name'])?></h2>
    </div>

    <div class='row'>

    <div class ='col-sm-3'>
    <label for='name'>New Group Name:</label>
    <input type='text' class='form-control' name='groupnm' id='groupnm' placeholder="NEW GROUP NAME">
    </div>


    <div class ='col-sm-3'>
    <label for='name'>Group Message(optional):</label>
    <input type='text' class='form-control' name='gmsg' id='groupmsg' placeholder="GROUP MESSAGE">
    </div>

    <div class='col-sm-3'>
    <input name="hcheck" value="0" type="hidden">
    <input type="checkbox" name="hcheck" value="1">
    <label for="hcheck">Check to hide your groups(uncheck to show your groups)</label>
    <br>
    </div>
    </div>

    <br><br>






    <div class='row'>
    <div class='col-sm-3'>
    <label for='name'>Select new courses(optional):</label>
    <select name='newcou' id='ncou' class='form-control'>
    <option class='ncor' value=''>Select Course</option>;
    <?php 
      $p=$db->prepare("SELECT id, department,number FROM courses order by department AND number");
      $p->execute();
      foreach($p as $couquery){
      $coud=$couquery[department];
      $counum=$couquery[number];
      $cid=$couquery[id];
      echo "<option value='$cid'>$coud $counum</option>";
    } ?>
    </select>
    </div>

    <div class='col-sm-3'>
    <label for='name'>Select a new campus(optional):</label>
    <select name="meetcam" id="mcampus" class="form-control" onChange="getbudg(this.value);">
    <option class='metplce' value=''>Select a Campus</option>
    <?php 
    $campus=$db->prepare("SELECT id,name FROM campuses order by name");
    $campus->execute();
    foreach($campus as $camquery){
      $camnm=$camquery[name];
      $camid=$camquery[id]; 
      echo "<option value='$camid'>$camnm</option>";
    }?>
    </select>
    </div>

    <div class='col-sm-3'>
    <label for='name'>Select a new building(optional)</label>
    <select name='budg' id='mbudg' class='form-control'>
    <option value=''>Select a Building</option>
    </select>

    </div>
    </div>



    <br><br>  

    <div class='row'>
    <h4>Change meeting date and time(optional):</h4>
    <div class='col-sm-3'>
    <label for='name'>Select Day:</label>
    <select id="swk" name='week' class='form-control'>
    <option class='ww' value=''>Select Day</option>
    <?php $week=array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday');
    foreach ($week as $value) {
      echo '<option value="'.$value.'">'.$value.'</option>';
    }?>
    </select>
    </div>

    <div class='col-sm-3'>
    <label for='name'>Select Time:</label>
    <select id="stime" name='selt' class='form-control'>
    <option value=''>Select Time</option>
    <?php
    for($i=1;$i<=24;$i++){
      echo "<option value='$i'>$i:00</option>";
    }?>
    </select>
    </div>
    </div>


    <br><br>


    <input type='submit' class='btn btn-info' value='SUBMIT'>
    </form>

    </div>


    
    <?php include 'includes/_footer.php';?>
  </body>
</html>
