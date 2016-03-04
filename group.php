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

$db = connect_db();

$user_id = get_logged_in_user_id();
$user_groups = get_user_groups($db, $user_id);

$group = get_group($db, $_GET['id']);
if (!$group) {
  header('Status: 404');
  die('no such user');
}

$course = get_course($db, $group['course_id']);
$users = get_group_members($db, $group['id']);
$is_member = is_member($db, $user_id, $group['id']);
if ($is_member) {
  $posts = get_group_posts($db, $group['id']);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group for <?= htmlspecialchars($course['department'].' '.$course['number']) ?></title>
    <?php include 'includes/_head.html';?>
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

    <br><br>
    <div class='row'>
      <div class='col-sm-3'>
        <label for='name'>Select the group</label>
        <select name='cgrp' id='greload' onChange="reload(this.value);" class='form-control'>
        <option class='cgop' value=''>Select Group</option>;
        <?php
          foreach($user_groups as $g){
            echo '<option value="'.htmlspecialchars($g['id']).'">'.htmlspecialchars($g['name'])."</option>\n";
          }
        ?>
        </select>
        <br>
      </div>
    </div>

    <h2>Study Group: <?= htmlspecialchars($group['name']) ?></h2>

    <?php if ($is_member) { ?>
      <a href="group_edit.php?id=<?= htmlspecialchars(urlencode($group['id'])) ?>" class="btn btn-default btn-sm">
        <span class="glyphicon glyphicon-cog"></span> Edit
      </a>
    <?php } ?>

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

    <h2>Members</h2>

    <?php if ($is_member) { ?>
      <div>
        <a href="members_edit.php" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-cog"></span> Edit
        </a>
      </div>
    <?php } ?>

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
	

    <?php if ($is_member) { ?>
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
    <?php } ?>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
