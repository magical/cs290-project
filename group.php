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
$group = get_group($db, $_GET['id']);
if (!$group) {
  header('Status: 404');
  die('no such user');
}

$course = get_course($db, $group['course_id']);
$users = get_group_members($db, $group['id']);
$posts = get_group_posts($db, $group['id']);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group for <?= htmlspecialchars($course['department'].' '.$course['number']) ?></title>
    <?php include 'includes/_head.html';?>
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

    <h1>Study Group</h1>

    <dl class="dl-horizontal">
      <dt>Name
      <dd><?= htmlspecialchars($group['name']) ?>

      <dt>Course
      <dd><?= htmlspecialchars($course['department']) ?>
          <?= htmlspecialchars($course['number']) ?>
          <?= htmlspecialchars($course['title']) ?>
    </dl>

    <h2>Members</h2>

    <ul>
      <?php foreach ($users as $user) { ?>
        <li><?= htmlspecialchars($user['name']) ?></li>
      <?php } ?>
    </ul>
	<form action= "calendar.php" method="get"> 
		<h3> Create a Group Meeting </h3>	
		<div class="form-group">
			<input value="Create Group" type="submit" name="">
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
