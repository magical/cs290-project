<?php require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}
if (!isset($_GET['id'])) {
  // um
  header('Status: 404');
  die('missing id');
}

$db=connect_db();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>
      Members Editing
    </title>
    <script src="js/jquery-1.12.1.min.js" type="text/javascript"></script>
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
      if(isset($_SESSION["flash_success"])) {
        //foreach ($_SESSION['flash_success'] as $msg) {
          echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['flash_success']).'</div>';
        //}
        unset($_SESSION["flash_success"]);
      }
      elseif(isset($_SESSION["flash_errors"])) {
       // foreach ($_SESSION['flash_errors'] as $msg) {
          echo '<div class="alert alert-warning">'.htmlspecialchars($_SESSION['flash_errors']).'</div>';
        //}
        unset($_SESSION["flash_errors"]);
      }
		if(isset($_SESSION["flash_errors"])) {
        unset($_SESSION["flash_errors"]);
		  }
    ?>
    <div class='container'>

    <form action="members_entry.php" class='form-horizontal' role='form' method='post' name='mementry'>

    <div class='row'>
    <br><br>
    <div class='col-sm-3'>
    <?php
	 $user_groups = get_user_groups($db, get_logged_in_user_id());
	 $group = get_group($db, $_GET['id']);	
    //$selectedgid=$group['id'];
	 $selectedgid=$_GET['id'];
	 $_SESSION['memgid'] = $selectedgid;
	 echo "<label for='name'>Select the group</label>";
    echo "<select name='sgrop' id='greload' onChange='reload(this.value);' class='form-control'>";
    echo "<option value=''>Select Group</option>";
    foreach($user_groups as $g){
        if ($g['id'] == $selectedgid) {
		  	$sel = "selected='selected'";
		  } else {
		  	$sel = '';
		  }
		  echo '<option value="'.$g['id'].'" '. $sel .'>' .htmlspecialchars($g['name'])."</option>\n"; 
    }
	    
    
    echo '</select>';
    echo '</div>';
    echo '</div>';

    ?>
    <div class='jumbotron'>
    <h2>Group Member Editing For Group: <?= htmlspecialchars($group['name'])?></h2>
    </div>

    <div class='row'>

    <div class ='col-md-4'>
    <label for='name'>Add a new member(email):</label>
    <input type='text' class='form-control' name='addmemb' id='addmemb'>
    </div>


    <div class ='col-md-4'>
    <label for='name'>Remove a current member or yourself(email):</label>
    <input type='text' class='form-control' name='removemem' id='removemem'>
    </div>
    </div>
    <br><br>

    <input type='submit' class='btn btn-info' value='SUBMIT'>
    </form>
	 <?php
	 echo "<a href='group.php?id=$selectedgid' class='btn btn-info'>Return to Group</a>";
	 ?>
    </div>
    </body>
    </html>


