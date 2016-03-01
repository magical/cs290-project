<?php
require_once 'includes/all.php';

 
if(!is_logged_in()) {
	header("Location: signin.php");
	exit(0);
}

if (!isset($_GET['id'])) {
  // um
  header('Status: 404');
  die('404 not found');
}

$uid=get_logged_in_user_id();
$groid="SELECT group_id FROM group_members WHERE user_id=$uid";


$db = connect_db();
$group = get_group($db, $_GET['id']);
if (!$group) {
  header('Status: 404');
  die('no such user');
}

$course = get_course($db, $group['course_id']);
$users = get_group_members($db, $group['id']);
//	$posts = get_group_posts($db, $group['id']);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group for <?= htmlspecialchars($course['department'].' '.$course['number']) ?></title>
    <?php include 'includes/_head.html';?>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="jquery-1.12.1.min.js"></script>
    <script>
      function reload(id){
        $.ajax({
          type: "POST",
          url: "group.php",
          data: 'id='+id,
          success: function(content){
            $("body").html(content);
          }
        })
        self.location="group.php?id="+id;
      }
    </script>
    <style>
      article {
        background: #dadada;
        padding: 1em;
        border-radius: .5em;
        margin: 1em 0;
      }
    </style>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <div class='container'>

    <div class='row'>
    <br><br>
    <div class='col-sm-3'>
    <label for='name'>Select the group</label>
    <select name='cgrp' id='greload' onChange="reload(this.value);" class='form-control'>
    <option class='cgop' value=''>Select Group</option>;
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
 

    <dl class="dl-horizontal">
    <div class="container">
    <h2>Study Group: <?= htmlspecialchars($group['name']) ?></h2>
    <a href="group_edit.php?id=1" class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-cog"></span> Edit
    </a>
    </div>
    </div>

    <dl class="dl-horizontal">
      <dt>Name
      <dd><?= htmlspecialchars($group['name']) ?>

      <dt>Group Message
      <dd><?= htmlspecialchars($group['blurb']) ?>

      <dt>Meeting Place
      <dd><?= htmlspecialchars($group['place']) ?>

      <dt>Meeting Time
      <dd><?= htmlspecialchars($group['time']) ?>

      <dt>Course
      <dd><?= htmlspecialchars($course['department']) ?>
          <?= htmlspecialchars($course['number']) ?>
          <?= htmlspecialchars($course['title']) ?>
    </dl>

    <div class="container">
    <h2>Members</h2>
    <a href="members_edit.php?id=1" class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-cog"></span> Edit
    </a>
    </div>
    <br>
    <ul>
      <?php foreach ($users as $user) { ?>
        <li><?= htmlspecialchars($user['name']) ?></li>
      <?php } ?>
    </ul>
	<form action= "calendar.php" method="get"> 
		<h3> Create a Group Meeting </h3>	
		<input type="hidden" name="group_id" value="<?htmlspecialchars($group['id']) ?>">
		<div class="form-group">
			<button class="btn btn-primary">Create Group Meeting </button>
		</div>
	</form>
	


    <h2>Discussion</h2>

    <?php
      foreach ($posts as $post) {
        $date = new DateTime($post['created_at']);
        echo '<article id="post-'.$post['id'].'">';
        echo '<b>'.htmlspecialchars($post['user_name']).
          ' on '.htmlspecialchars($date->format("M j")).
          ' at '.htmlspecialchars($date->format("H:i")).
          '</b>';
        echo '<p>'.htmlspecialchars($post['body']).'</p>';
        echo '</article>';
      }
    ?>

    <form action="post.php" method="POST">
      <input type="hidden" name="group_id" value="<?= htmlspecialchars($group['id']) ?>">
      <div class="form-group">
        <textarea name="body" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <button class="btn btn-primary">Post</button>
      </div>
    </form>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
