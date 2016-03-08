<?php require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}

$db=connect_db();

?>

<!DOCTYPE html>
<html>
  <head>
    <title> Members Editing | Study Group Finder </title>
    <script src="js/jquery-1.12.1.min.js" type="text/javascript"></script>
    <script>
      function reload(id){
              self.location="members_edit.php?id="+id;
      }
    </script>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <div class="breadcrumbs">
      <a href="index.php">Home</a>
      » <a href="group.php?id=<?= $group['id'] ?>">Group: <?= htmlspecialchars($group['name']) ?></a>
      » Edit Members
    </div>

    <form action="members_entry.php" class='form-horizontal' role='form' method='post' name='mementry'>

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
    $_SESSION['memgid']=$selectedgid;
    foreach($user_groups as $g){
        echo '<option value="'.htmlspecialchars($g['id']).'">'.htmlspecialchars($g['name'])."</option>\n";
        }   
    ?>
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
    <label for='name'>Remove a current member or yourself(email):</label>
    <input type='text' class='form-control' name='removemem' id='removemem'>
    </div>
    </div>
    <br><br>

    <input type='submit' class='btn btn-info' value='SUBMIT'>
    </form>
    </div>
    </body>
    </html>


