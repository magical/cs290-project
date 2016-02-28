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

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group for <?= htmlspecialchars($course['department'].' '.$course['number']) ?></title>
    <?php include 'includes/_head.html';?>
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
	


    <?php include 'includes/_footer.php';?>
  </body>
</html>
