<?php require_once 'includes/all.php';
if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>
      Group Editing
    </title>
    <script src="jquery-1.12.0.min.js" type="text/javascript"></script>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>
    <?php
    $db=connect_db();

    $uid=get_logged_in_user_id();

    $group = get_group($db, $uid);

    $users = get_group_members($db, $group['id']);
    ?>

    <div class='container'>

	<form action='member_entry.php' class='form-horizontal' role='form' method='post' name='dentry'>

    <div class='jumbotron'>
    <h2>Group Member Editing</h2>
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


